<?php
/**
 * @package server-infra
 * @subpackage Media
 */
class KFFMpegMediaParser extends KBaseMediaParser
{
	protected $cmdPath;
	protected $ffmprobeBin;
	
	public $checkScanTypeFlag=false;
	
	/**
	 * @param string $filePath
	 * @param string $cmdPath
	 */
	public function __construct($filePath, $ffmpegBin=null, $ffprobeBin=null)
	{
		if(isset($ffmpegBin)){
			$this->cmdPath = $ffmpegBin;
		}
		else if(kConf::hasParam('bin_path_ffmpeg')) {
			$this->cmdPath = kConf::get('bin_path_ffmpeg');
		}
		else{
			$this->cmdPath = "ffmpeg";
		}
		
		if(isset($ffprobeBin)){
			$this->ffprobeBin = $ffprobeBin;
		}
		else if (kConf::hasParam('bin_path_ffprobe')) {
			$this->ffprobeBin = kConf::get('bin_path_ffprobe');
		}
		else{
			$this->ffprobeBin = "ffprobe";
		}
		if(strstr($filePath, "http")===false) {
			if (!file_exists($filePath))
				throw new kApplicativeException(KBaseMediaParser::ERROR_NFS_FILE_DOESNT_EXIST, "File not found at [$filePath]");
		}
		parent::__construct($filePath);
	}
	
	/**
	 * @return string
	 */
	protected function getCommand($filePath=null)
	{
		if(!isset($filePath)) $filePath=$this->filePath;
		if(isset($this->encryptionKey))
			return "{$this->ffprobeBin} -decryption_key {$this->encryptionKey} -i {$filePath} -show_streams -show_format -show_programs -v quiet -show_data  -print_format json";
		else	
			return "{$this->ffprobeBin} -i {$filePath} -show_streams -show_format -show_programs -v quiet -show_data  -print_format json";
	}
	
	/**
	 * @return string
	 */
	public function getRawMediaInfo($filePath=null)
	{
		if(!isset($filePath)) $filePath=$this->filePath;
		$cmd = $this->getCommand($filePath);
		KalturaLog::debug("Executing '$cmd'");
		$output = shell_exec($cmd);
		if (trim($output) === "")
			throw new kApplicativeException(KBaseMediaParser::ERROR_EXTRACT_MEDIA_FAILED, "Failed to parse media using " . get_class($this));
			
		return $output;
	}
	
	/**
	 * 
	 * @param string $output
	 * @return KalturaMediaInfo
	 */
	protected function parseOutput($output)
	{
		$outputlower = strtolower($output);
		$jsonObj = json_decode($outputlower);
			// Check for json decode errors caused by inproper utf8 encoding.
		if(json_last_error()!=JSON_ERROR_NONE) $jsonObj = json_decode(utf8_encode($outputlower));
		if(!(isset($jsonObj) && isset($jsonObj->format))){
			/*
			 * For ARF (webex) files - simulate container ID and format.
			 * On no-content return null
			 */
			if(strstr($this->filePath,".arf")){
				$mediaInfo = new KalturaMediaInfo();
				$mediaInfo->containerFormat = "arf";
				$mediaInfo->containerId = "arf";
				$mediaInfo->fileSize = round(filesize($this->filePath)/1024);
				return $mediaInfo;
			}
			return null;
		}
		
		$mediaInfo = new KalturaMediaInfo();
		$mediaInfo->rawData = $output;
		$this->parseFormat($jsonObj->format, $mediaInfo);
		if(isset($jsonObj->streams) && count($jsonObj->streams)>0){
			$this->parseStreams($jsonObj->streams, $mediaInfo);
		}

//		list($silenceDetect, $blackDetect) = self::checkForSilentAudioAndBlackVideo($this->cmdPath, $this->filePath, $mediaInfo);
		if(isset($this->checkScanTypeFlag) && $this->checkScanTypeFlag==true)
			$mediaInfo->scanType = self::checkForScanType($this->cmdPath, $this->filePath);
		else
			$mediaInfo->scanType = 0; // Progressive
		// mov,mp4,m4a,3gp,3g2,mj2 to check is format inside
		if(in_array($mediaInfo->containerFormat, array("mov","mp4","m4a","3gp","3g2","mj2")) && isset($this->ffprobeBin)){
			$mediaInfo->isFastStart = self::checkForFastStart($this->ffprobeBin, $this->filePath);
		}
		
		/*
		 * Detect WVC1 files with 'Progressive Segmented' mode. FFmpeg 2.6 (and earlier) cannot handle them.
		 * To be handled by mencoder in auto-inter-src mode
		 */
		if(in_array($mediaInfo->videoCodecId,array("wvc1","wmv3"))){
			$cmd = "$this->cmdPath -i $this->filePath 2>&1 ";
			$output = shell_exec($cmd);
			if(strstr($output,"Progressive Segmented")){
				if(isset($mediaInfo->contentStreams) && count($mediaInfo->contentStreams['video'])>0){
					$mediaInfo->contentStreams['video'][0]->progressiveSegmented=true;
				}
			}
		}
		KalturaLog::log(print_r($mediaInfo,1));
		$mediaInfo->contentStreams = json_encode($mediaInfo->contentStreams);
		return $mediaInfo;
	}
	
	/**
	 * 
	 * @param $format - generated by ffprobe
	 * @param KalturaMediaInfo $mediaInfo
	 * @return KalturaMediaInfo
	 */
	protected function parseFormat($format, KalturaMediaInfo $mediaInfo)
	{
		$mediaInfo->fileSize = isset($format->size)? round($format->size/1024,2): null;
		$mediaInfo->containerFormat = 
			isset($format->format_name)? self::matchContainerFormat($this->filePath, trim($format->format_name)): null;
		if(isset($format->tags) && isset($format->tags->major_brand)){
			$mediaInfo->containerId = trim($format->tags->major_brand);
		}
		$mediaInfo->containerBitRate = isset($format->bit_rate)? round($format->bit_rate/1000,2): null;
			// If format duration is not set or zero'ed, 
			// try to retrieve duration from format/tag section 
		$mediaInfo->containerDuration = self::retrieveDuration($format);

		if(isset($format->tags->producer))
			$mediaInfo->producer = $format->tags->producer;
		return $mediaInfo;
	}
	
	/**
	 * 
	 * @param $streams - generated by ffprobe
	 * @param KalturaMediaInfo $mediaInfo
	 * @return KalturaMediaInfo
	 */
	protected function parseStreams($streams, KalturaMediaInfo $mediaInfo)
	{
	$vidCnt = 0;
	$audCnt = 0;
	$dataCnt = 0;
	$otherCnt = 0;
		foreach ($streams as $stream){
			$copyFlag = false;
			$mAux = new KalturaMediaInfo();
			$mAux->id = $stream->index;
			$mAux->codecType = $stream->codec_type;
			switch($stream->codec_type){
			case "video":
					/*
					 * SUP-18051,SUP-18025,SUP-17840,SUP-18018
					 * For audio-only MP3's/M4A's - prevent detecting of cover JPG/PNG as a video stream
					 */
				if(in_array($stream->codec_name, array('mjpeg','png'))
				&& (in_array($mediaInfo->containerFormat, array('mp3','mpeg audio','isom','mp4','mpeg4','mpeg-4','m4a'))
				||  in_array($mediaInfo->containerId, array('mp3','mpeg audio','isom','mp4','mpeg4','mpeg-4','m4a'))) ){
					break;
				}
				$this->parseVideoStream($stream, $mAux);
				if($vidCnt==0)
					$copyFlag=true;
				$vidCnt++;
				break;
			case "audio":
				$this->parseAudioStream($stream, $mAux);
				if($audCnt==0)
					$copyFlag=true;
				$audCnt++;
				break;
			case "data":
				$this->parseDataStream($stream, $mAux);
				if($dataCnt==0)
					$copyFlag=true;
				$dataCnt++;
				break;
			default:
				$otherCnt++;
				break;
			}
			self::removeUnsetFields($mAux);
			$mediaInfo->contentStreams[$stream->codec_type][] = $mAux;
			if($copyFlag){
				self::copyFields($mAux, $mediaInfo);
			}
		}
		$mediaInfo->id = null;
		if(isset($mediaInfo->codecType)) unset($mediaInfo->codecType);
		return $mediaInfo;
	}

	/**
	 * 
	 * @param string $srcFileName
	 * @param string $formatStr
	 * @return string
	 */
	private static function matchContainerFormat($srcFileName, $formatStr)
	{
		$extStr = pathinfo($srcFileName, PATHINFO_EXTENSION);
		$formatArr = explode(",", $formatStr);
		if(!empty($extStr) && strlen($extStr)>1) {
			foreach($formatArr as $frmt){
				if(strstr($extStr, $frmt)!=false || strstr($frmt, $extStr)!=false){
					return $frmt;
				}
			}
		}
		if(in_array("mp4", $formatArr))
			return "mp4";
		else
			return $formatArr[0];
	}
	
	/**
	 * 
	 * @param $stream - generated by ffprobe
	 * @param KalturaMediaInfo $mediaInfo
	 * @return KalturaMediaInfo
	 */
	protected function parseVideoStream($stream, KalturaMediaInfo $mediaInfo)
	{
		$mediaInfo->videoFormat = isset($stream->codec_name)? trim($stream->codec_name): null;
		$mediaInfo->videoCodecId = isset($stream->codec_tag_string)? trim($stream->codec_tag_string): null;
			// If stream duration is not set or zero'ed, 
			// try to retrieve duration from stream/tag section 
		$mediaInfo->videoDuration = self::retrieveDuration($stream);

		$mediaInfo->videoBitRate = isset($stream->bit_rate)? round($stream->bit_rate/1000,2): null;
		$mediaInfo->videoBitRateMode; // FIXME
		$mediaInfo->videoWidth = isset($stream->width)? trim($stream->width): null;
		$mediaInfo->videoHeight = isset($stream->height)? trim($stream->height): null;
		$mediaInfo->videoFrameRate = null;
		if(isset($stream->r_frame_rate)){
			$r_frame_rate = trim($stream->r_frame_rate);
			if(is_numeric($r_frame_rate))
				$mediaInfo->videoFrameRate = $r_frame_rate;
			else {
				$value=eval("return ($r_frame_rate);");
				if($value!=false) $mediaInfo->videoFrameRate = round($value,3);
			}
		}
			
		$mediaInfo->videoDar = null;
		if(isset($stream->display_aspect_ratio)){
			$display_aspect_ratio = trim($stream->display_aspect_ratio);
			if(is_numeric($display_aspect_ratio))
				$mediaInfo->videoDar = $display_aspect_ratio;
			else {
				$darStr = str_replace(":", "/",$display_aspect_ratio);
				$value=eval("return ($darStr);");
				if($value!=false) $mediaInfo->videoDar = $value;
			}
		}
			
		if(isset($stream->tags) && isset($stream->tags->rotate)){
			$mediaInfo->videoRotation = trim($stream->tags->rotate);
		}
		$mediaInfo->scanType = 0; // default 0/progressive
		
		$mediaInfo->matrixCoefficients = isset($stream->color_space)? trim($stream->color_space): null;
		$mediaInfo->colorTransfer = isset($stream->color_transfer)? trim($stream->color_transfer): null;
		$mediaInfo->colorPrimaries = isset($stream->color_primaries)? trim($stream->color_primaries): null;

		if(isset($stream->pix_fmt))
			self::parsePixelFormat($stream->pix_fmt, $mediaInfo);

		return $mediaInfo;
	}
	
	/**
	 * @param stream - generated by ffprobe
	 * @param KalturaMediaInfo
	 * @return KalturaMediaInfo
	 */
	protected function parseAudioStream($stream, $mediaInfo)
	{
		$mediaInfo->audioFormat = isset($stream->codec_name)? trim($stream->codec_name): null;
		$mediaInfo->audioCodecId = isset($stream->codec_tag_string)? trim($stream->codec_tag_string): null;
			// If stream duration is not set or zero'ed, 
			// try to retrieve duration from stream/tag section 
		$mediaInfo->audioDuration = self::retrieveDuration($stream);

		$mediaInfo->audioBitRate = isset($stream->bit_rate)? round($stream->bit_rate/1000,2): null;
		$mediaInfo->audioBitRateMode; // FIXME
		$mediaInfo->audioChannels = isset($stream->channels)? trim($stream->channels): null;
			// mono,stereo,downmix,FR,FL,BR,BL,LFE
		$mediaInfo->audioChannelLayout = isset($stream->channel_layout)? self::parseAudioLayout($stream->channel_layout): null;
		$mediaInfo->audioSamplingRate = isset($stream->sample_rate)? trim($stream->sample_rate): null;
		if ($mediaInfo->audioSamplingRate < 1000)
			$mediaInfo->audioSamplingRate *= 1000;
		$mediaInfo->audioResolution = isset($stream->bits_per_sample)? trim($stream->bits_per_sample): null;
		if(isset($stream->tags) && isset($stream->tags->language)){
			$mediaInfo->audioLanguage = trim($stream->tags->language);
		}
		return $mediaInfo;
	}
	
	/**
	 * 
	 * @param unknown_type $layout
	 * @return string
	 */
	protected static function parseAudioLayout($layout)
	{
		$lout = KDLAudioLayouts::Detect($layout);
		if(!isset($lout))
			$lout = $layout;
		return $lout;
	}
	
	/**
	 * @param stream - generated by ffprobe
	 * @param KalturaMediaInfo
	 * @return KalturaMediaInfo
	 */
	protected function parseDataStream($stream, KalturaMediaInfo $mediaInfo)
	{
		$mediaInfo->dataFormat = isset($stream->codec_name)? $stream->codec_name: null;
		$mediaInfo->dataCodecId = isset($stream->codec_tag_string)? $stream->codec_tag_string: null;
		$mediaInfo->dataDuration = isset($stream->duration)? ($stream->duration*1000): null;
		return $mediaInfo;
	}
	
	/**
	 * 
	 * @param unknown_type $ffmpegBin
	 * @param unknown_type $srcFileName
	 * @param KalturaMediaInfo $mediaInfo
	 * @param unknown_type $detectDur
	 * @return multitype:Ambigous <string, NULL>
	 */
	public static function checkForSilentAudioAndBlackVideo($ffmpegBin, $srcFileName, KalturaMediaInfo $mediaInfo, $detectDur=null)
	{
		KalturaLog::log("contDur:$mediaInfo->containerDuration,vidDur:$mediaInfo->videoDuration,audDur:$mediaInfo->audioDuration");
	
		/*
		 * Evaluate vid/aud detection durations
		 */
		if(isset($mediaInfo->videoDuration) && $mediaInfo->videoDuration>4000)
			$vidDetectDur = round($mediaInfo->videoDuration/2000,2);
		else if(isset($mediaInfo->containerDuration) && $mediaInfo->containerDuration>4000)
			$vidDetectDur = round($mediaInfo->containerDuration/2000,2);
		else
			$vidDetectDur = 0;
			
		if(isset($mediaInfo->audioDuration) && $mediaInfo->audioDuration>4000)
			$audDetectDur = round($mediaInfo->audioDuration/2000,2);
		else if(isset($mediaInfo->containerDuration) && $mediaInfo->containerDuration>4000)
			$audDetectDur = round($mediaInfo->containerDuration/2000,2);
		else
			$audDetectDur = 0;
	
			/*
			 * Limit the aud/vid detect duration to match the global detect duration,
			 * if such duration is provided
			 */
		if(isset($detectDur) && $detectDur>0) {
			if($audDetectDur>$detectDur) $audDetectDur=$detectDur;
			if($vidDetectDur>$detectDur) $vidDetectDur=$detectDur;
		}
		
		list($silenceDetected,$blackDetected) = self::detectSilentAudioAndBlackVideoIntervals($ffmpegBin, $srcFileName, $vidDetectDur, $audDetectDur, $detectDur);
		
		if(isset($blackDetected)){
			list($blackStart,$blackDur) = $blackDetected[0];
			if($blackDur==-1) $blackDur = $vidDetectDur;
			$blackDetectMsg = "black frame content for at least $blackDur sec";
		}
		else{
			$blackDetectMsg = null;
		}

		if(isset($silenceDetected)){
			list($silenceStart,$silenceDur) = $silenceDetected[0];
			if($silenceDur==-1) $silenceDur = $audDetectDur;
			$silenceDetectMsg = "silent content for at least $silenceDur sec";
		}
		else{
			$silenceDetectMsg = null;
		}

		$detectMsg = $silenceDetectMsg;
		if(isset($blackDetectMsg))
			$detectMsg = isset($detectMsg)?"$detectMsg,$blackDetectMsg":$blackDetectMsg;
		
		if(empty($detectMsg))
			KalturaLog::log("No black frame or silent content in $srcFileName");
		else
			KalturaLog::log("Detected - $detectMsg, in $srcFileName");
		
		return array($silenceDetectMsg, $blackDetectMsg);		
	}

	/**
	 * 
	 * @param unknown_type $ffmpegBin
	 * @param unknown_type $srcFileName
	 * @param KalturaMediaInfo $mediaInfo
	 * @return boolean
	 */
	public static function checkForGarbledAudio($ffmpegBin, $srcFileName, KalturaMediaInfo $mediaInfo)
	{
		KalturaLog::log("contDur:$mediaInfo->containerDuration,audDur:$mediaInfo->audioDuration");
		if(isset($mediaInfo->audioDuration)){ 
			$audDetectDur = ($mediaInfo->audioDuration>600000)? 600: round($mediaInfo->audioDuration/1000,2);
		}
		else if(isset($mediaInfo->containerDuration)){ 
			$audDetectDur = ($mediaInfo->containerDuration>600000)? 600: round($mediaInfo->containerDuration/1000,2);
		}		
		else if(isset($mediaInfo->videoDuration)){ 
			$audDetectDur = ($mediaInfo->videoDuration>600000)? 600: round($mediaInfo->videoDuration/1000,2);
		}
		else	
			$audDetectDur = 0;
		
		if($audDetectDur>0 && $audDetectDur<10){
			KalturaLog::log("Audio OK - short audio, audDetectDur($audDetectDur)");
			return false;
		}
		
		list($silenceDetected,$blackDetected) = KFFMpegMediaParser::detectSilentAudioAndBlackVideoIntervals($ffmpegBin, $srcFileName, null, 0.05, $audDetectDur,"-90dB");
		
		$ticks = isset($silenceDetected)? count($silenceDetected): 0;
		if($ticks<=10){
			KalturaLog::log("Audio OK - low numbers of ticks($ticks)");
			return false;
		}
		
		KalturaLog::log("audDetectDur($audDetectDur),ticks($ticks)");
		if($audDetectDur>0) {
			$ticksPerMin = $ticks/($audDetectDur/60);
			KalturaLog::log("ticksPerMin($ticksPerMin)");
			
			if($ticksPerMin<15 
			||($audDetectDur<60 && $ticksPerMin<30) 
			||($audDetectDur<120 && $ticksPerMin<20) ){
				KalturaLog::log("Audio OK");
				return false;
			}
		}
		else if($ticks<100) {
			KalturaLog::log("Audio OK - no duration, number of ticks smaller than threshold(100)");
			return false;
		}
		
		KalturaLog::log("Detected garbled audio.");
		return true;
	}
	
	/**
	 * 
	 * @param unknown_type $ffmpegBin
	 * @param unknown_type $srcFileName
	 * @param unknown_type $blackInterval
	 * @param unknown_type $silenceInterval
	 * @param unknown_type $detectDur
	 * @param unknown_type $audNoiseLevel
	 * @return NULL|multitype:Ambigous <NULL, number, unknown>
	 */
	public static function detectSilentAudioAndBlackVideoIntervals($ffmpegBin, $srcFileName, $blackInterval, $silenceInterval, $detectDur=null, $audNoiseLevel=0.0001)
	{
		//		KalturaLog::log("checkSilentAudioAndBlackVideo(contDur:$mediaInfo->containerDuration,vidDur:$mediaInfo->videoDuration,audDur:$mediaInfo->audioDuration)");
	
		/*
		 * Set appropriate detection filters
		*/
		$detectFiltersStr=null;
		// ~/ffmpeg-2.1.3 -i /web//content/r71v1/entry/data/321/479/1_u076unw9_1_wprx637h_21.copy -vf blackdetect=d=2500 -af silencedetect=noise=0.0001:d=2500 -f null dummyfilename 2>&1
		if(isset($blackInterval) && $blackInterval>0) {
			$detectFiltersStr = "-vf blackdetect=d=$blackInterval";
		}
		if(isset($silenceInterval) && $silenceInterval>0) {
			$detectFiltersStr.= " -af silencedetect=noise=$audNoiseLevel:d=$silenceInterval";
		}
	
		if(empty($detectFiltersStr)){
			KalturaLog::log("No duration values in the source file metadata. Cannot run black/silence detection for the $srcFileName");
			return null;
		}
	
		$cmdLine = "$ffmpegBin ";
		if(isset($detectDur) && $detectDur>0){
			$cmdLine.= "-t $detectDur";
		}
		$cmdLine.= " -i $srcFileName $detectFiltersStr -nostats -f null dummyfilename 2>&1";
		KalturaLog::log("Black/Silence detection cmdLine - $cmdLine");
	
		/*
		 * Execute the black/silence detection
		*/
		$lastLine=exec($cmdLine , $outputArr, $rv);
		if($rv!=0) {
			KalturaLog::err("Black/Silence detection failed on ffmpeg call - rv($rv),lastLine($lastLine)");
			return null;
		}
	
	
		/*
		 * Searce the ffmpeg printout for
		 * - blackdetect or black_duration
		 * - silencedetect or silence_duration
		 */
		$silenceDetected= self::parseDetectionOutput($outputArr,"silencedetect", "silence_duration", "silence_start");
		$blackDetected  = self::parseDetectionOutput($outputArr,"blackdetect", "black_duration", "black_start");
		return array($silenceDetected, $blackDetected);
		
	}
	
	/**
	 * 
	 * @param unknown_type $outputStr
	 * @param unknown_type $detectString
	 * @param unknown_type $durationString
	 * @return NULL|number|unknown
	 */
	private static function parseDetectionOutput(array $outputArr, $detectString, $durationString, $startString=null)
	{
		$detectedArr = array();
		$start = null;
		$dur = null;
		$isDetected = false;
		foreach ($outputArr as $line){
			if(strstr($line, $detectString)==false){
				continue;
			}
			$isDetected = true;
			if(isset($startString) && ($str=strstr($line, $startString))!=false){
				sscanf($str,"$startString:%f", $start);
			}
			if(($str=strstr($line, $durationString))!=false){
				sscanf($str,"$durationString:%f", $dur);
				if(!isset($start)) {
					$start = 0; 
				}
				$detectedArr[] = array($start,$dur);
				$start = $dur = null;
			}
		}
		if($isDetected==true) {
			if(count($detectedArr)==0){
				$detectedArr[] = array(0,-1);	
			}
			return $detectedArr;
		}
		else
			return null;
	}
	
	/**
	 * 
	 * @param unknown_type $ffprobeBin
	 * @param unknown_type $srcFileName
	 * @return array of scene cuts
	 */
	public static function retrieveSceneCuts($ffprobeBin, $srcFileName)
	{
		KalturaLog::log("srcFileName($srcFileName)");
	
		$cmdLine = "$ffprobeBin -show_frames -select_streams v -of default=nk=1:nw=1 -f lavfi \"movie='$srcFileName',select=gt(scene\,.4)\" -show_entries frame=pkt_pts_time";
		KalturaLog::log("$cmdLine");
		$lastLine=exec($cmdLine , $outputArr, $rv);
		if($rv!=0) {
			KalturaLog::err("SceneCuts detection failed on ffmpeg call - rv($rv),lastLine($lastLine)");
			return null;
		}
		/*
		 * The resultant array contains in sequential lines - pairs of time & scene-cut value 
		 */
		$sceneCutArr = array();
		for($i=1; $i<count($outputArr); $i+=2){
			$sceneCutArr[$outputArr[$i-1]] = $outputArr[$i];
		}
		return $sceneCutArr;
	}
	
	/**
	 * 
	 * @param unknown_type $ffprobeBin
	 * @param unknown_type $srcFileName
	 * @return array of keyframes
	 */
	public static function retrieveKeyFrames($ffprobeBin, $srcFileName,$start=null,$duration=null)
	{
		KalturaLog::log("srcFileName($srcFileName)");
		
		$trimStr=null;
		if(isset($start) && $start>0){
			$trimStr = ",trim=start=$start";
		}
		if(isset($duration) && $duration>0){
			if(isset($trimStr))
				$trimStr.= ":duration=$duration";
			else
				$trimStr = ",trim=duration=$duration";
		}
		
		$cmdLine = "$ffprobeBin -show_frames -select_streams v -of default=nk=1:nw=1 -f lavfi \"movie='$srcFileName',select=eq(pict_type\,PICT_TYPE_I)$trimStr\" -show_entries frame=pkt_pts_time";
		KalturaLog::log("$cmdLine");
		$lastLine=exec($cmdLine , $outputArr, $rv);
		if($rv!=0) {
			KalturaLog::err("Key Frames detection failed on ffmpeg call - rv($rv),lastLine($lastLine)");
			return null;
		}
		return $outputArr;
	}

	/**
	 * 
	 * @param unknown_type $ffprobeBin
	 * @param unknown_type $srcFileName
	 * @return array with detcted GOP values (min, max, dectected)
	 */
	public static function detectGOP($ffprobeBin, $srcFileName, $start=null, $duration=null)
	{
		$kFrames = KFFMpegMediaParser::retrieveKeyFrames($ffprobeBin, $srcFileName, $start, $duration);
		if(!isset($kFrames) || count($kFrames)<2){
			return null;
		}
			/*
			 * Turn the KF timings into integers representing 10th seconds
			 */
		foreach($kFrames as $k=>$kF){
			$kFrames[$k] = (int)round($kF*100);
		}
KalturaLog::log("KFrames:".serialize($kFrames));
			/*
			 * Calculate GOP minimum, maximum and histogram counters
			 */
		$gopMin = $gopMax = $kFrames[1]-$kFrames[0];
//		$gopHist = array();		//  GOP Histogram array - counts number occurences of each GOP
//		$gopHist[$gopMin] = 1;

			 // If there are more than 1 gop (2 KF's), then For more than With only 2 KF's - no reason to continue
		for($i=2;$i<count($kFrames); $i++){
			$gop = $kFrames[$i]-$kFrames[$i-1];
			$gopMin = min($gopMin,$gop);
			$gopMax = max($gopMax,$gop);
			
/*			if(key_exists($gop, $gopHist)){
				$gopHist[$gop] = $gopHist[$gop]+1;
			}
			else{
				$gopHist[$gop] = 1;
			}*/
		}
		
			/*
			 * Detect 0.5-4sec gops
			 *  Create GOP hustogram
			 *  Calculte the appeared to expected number of GOPs
			 *  The GOP with hihest ratio considered to be the 'detected' GOP
			 */
		$kf2gopHist = array(50=>0, 100=>0, 150=>0, 200=>0, 250=>0, 300=>0, 350=>0, 400=>0);
		$kf2gopHist = array(200=>0, 400=>0);
		$delta=6;
		for($tm=$kFrames[0]; $tm<=$kFrames[count($kFrames)-1];$tm+=50){
			for($t=$tm-$delta; $t<=$tm+$delta; $t++){
				if(array_search($t, $kFrames)!==false){
					break;
				}
			}
			if($t>$tm+$delta){
				continue;
			}
			foreach($kf2gopHist as $gop=>$cnt) {
				if(($tm % $gop)<5){
					$kf2gopHist[$gop]++;
				}
			}
		}
KalturaLog::log("kf2gopHist raw:".serialize($kf2gopHist));
			/*
			 * Calculate the appeared-to-expected-number-of-GOPs ratio.
			 */ 
		foreach($kf2gopHist as $gop=>$cnt) {
			$kf2gopHist[$gop] = $cnt/(round(($kFrames[count($kFrames)-1]-$kFrames[0])/$gop-0.5)+1);
		}
			// Sort the histogram array and get the GOP value that had the higest ratio
		asort($kf2gopHist);
KalturaLog::log("kf2gopHist norm:".serialize($kf2gopHist));
		end($kf2gopHist);
		$gopDetected = key($kf2gopHist);
		
			// Turn back the timing values from 10th's of sec to seconds
		$rv = array(($gopMin/100), ($gopMax/100), ($gopDetected/100));
		return $rv;
	}
	
	/**
	 * 
	 * @param $ffmpegBin
	 * @param $srcFileName
	 * @return number
	 */
	private static function checkForScanType($ffmpegBin, $srcFileName, $frames=1000)
	{
/*
	ffmpeg-2.1.3 -filter:v idet -frames:v 100 -an -f rawvideo -y /dev/null -nostats -i /mnt/shared/Media/114141.flv
	[Parsed_idet_0 @ 0000000000331de0] Single frame detection: TFF:1 BFF:96 Progressive:2 Undetermined:1	
	[Parsed_idet_0 @ 0000000000331de0] Multi frame detection: TFF:0 BFF:100 Progressive:0 Undetermined:0	
	$mediaInfo->scanType=1; 
*/
		if(stristr(PHP_OS,'win')) $nullDev = "NULL";
		else $nullDev = "/dev/null";

		$cmdLine = "$ffmpegBin -filter:v idet -frames:v $frames -an -f rawvideo -y $nullDev -i $srcFileName -nostats  2>&1";
		KalturaLog::log("ScanType detection cmdLine - $cmdLine");
		$lastLine=exec($cmdLine , $outputArr, $rv);
		if($rv!=0) {
			KalturaLog::err("ScanType detection failed on ffmpeg call - rv($rv),lastLine($lastLine)");
			return 0;
		}
		$tff=0; $bff=0; $progessive=0; $undermined=0;
		foreach($outputArr as $line){
			if(strstr($line, "Parsed_idet")==false)
				continue;
			KalturaLog::log($line);
			$str = strstr($line, "TFF");
			sscanf($str,"TFF:%d BFF:%d Progressive:%d Undetermined:%d", $t, $b, $p, $u);
			$tff+=$t; $bff+=$b; $progessive+=$p; $undermined+=$u;
		}
		$scanType = 0; // Default would be 'progressive'
		if($progessive<$tff+$bff)
			$scanType = 1;
		KalturaLog::log("ScanType: $scanType");
		return $scanType;
	}

	/**
	 * 
	 * @param unknown_type $ffprobeBin
	 * @param unknown_type $srcFileName
	 * @return boolean
	 */
	private function checkForFastStart($ffprobeBin, $srcFileName)
	{
/*
	dd if=anatol/0_2s6bf81e.fs.mp4 count=1 | ffmpeg -i pipe:
	[mov,mp4,m4a,3gp,3g2,mj2 @ 0x1493100] error reading header: -541478725
	[mov,mp4,m4a,3gp,3g2,mj2 @ 0xcb3100] moov atom not found
*/
		if(!isset($ffprobeBin))
			return false;
		/*
		 * Cannot run linux 'dd' command on Win
		 */
		if(stristr(PHP_OS,'win')) return 1;
		
		$cmdLine = "dd if=$srcFileName count=1 | $ffprobeBin -i pipe:  2>&1";
		KalturaLog::log("FastStart detection cmdLine - $cmdLine");
		$lastLine=exec($cmdLine, $outputArr, $rv);
		{
			KalturaLog::log("FastStart detection results printout - lastLine($lastLine),output-\n".print_r($outputArr,1));
		}
		$fastStart = 1;
		foreach($outputArr as $line){
			if(strstr($line, "moov atom not found")==false)
				continue;
			$fastStart = 0;
			KalturaLog::log($line);
		}
		KalturaLog::log("FastStart: $fastStart");
		return $fastStart;
/*		
		$hf=fopen($srcFileName,"rb");
		$sz = filesize($srcFileName);
		$sz = 10000;
		$contents = fread($hf, $sz);
		fclose($hf);
		$auxFilename = "d:\\tmp\\aaa1.mp4";
		$hf=fopen($auxFilename,"wb");
		$rv = fwrite($hf, $contents);
		
		
		$str=$this->getRawMediaInfo($auxFilename);
*/
	}

	/**
	 *
	 * @param unknown_type $ffprobeBin
	 * @param unknown_type $srcFileName
	 * @return array of keyframes
	 */
	public static function retrieveFramesTimings($ffprobeBin, $srcFileName)
	{
		KalturaLog::log("srcFileName($srcFileName)");
			
		$trimStr=null;
			
		$cmdLine = "$ffprobeBin $srcFileName -show_frames -select_streams v -v quiet -of json -show_entries frame=pkt_pts_time,key_frame,coded_picture_number";
		KalturaLog::log("$cmdLine");
		$lastLine=exec($cmdLine , $outputArr, $rv);
		if($rv!=0) {
			KalturaLog::err("Key Frames detection failed on ffmpeg call - rv($rv),lastLine($lastLine)");
			return null;
		}
		$jsonObj = json_decode(implode("\n",$outputArr));
		if(isset($jsonObj) && isset($jsonObj->frames))
			return $jsonObj->frames;
		else
			return null;
	}
	
	/**
	 * 
	 * @param unknown_type $ffprobeBin
	 * @param unknown_type $srcFileName
	 * $param unknown_type $reset
	 * @return array of volumeLevels
	 */	
	public static function retrieveVolumeLevels($ffprobeBin, $srcFileName, $reset=1)
	{
		KalturaLog::log("srcFileName($srcFileName)");
				
		$cmdLine = "$ffprobeBin -f lavfi -i \"amovie='$srcFileName',astats=metadata=1:reset=$reset\" -show_entries frame=pkt_pts_time:frame_tags=lavfi.astats.Overall.RMS_level -of csv=p=0 -v quiet";
		KalturaLog::log("$cmdLine");
		$lastLine=exec($cmdLine , $outputArr, $rv);
		if($rv!=0) {
			KalturaLog::err("Volume level detection failed on ffprobe call - rv($rv),lastLine($lastLine)");
			return null;
		}
		$volumeLevels = array();
		foreach($outputArr as $line) {
			list($tm,$vol) = explode(',', $line);
			$tm = (int)($tm*1000);
			if(!isset($tm) || !isset($vol))
				continue;
			$vol = trim($vol);
			if($vol!='-inf')
				$volumeLevels[$tm] = $vol;
			else
				$volumeLevels[$tm] = -1000;
		}

		return $volumeLevels;
	}

	/**
	 * retrieveDuration
	 *
	 * @param unknown_type $stream
	 * @return int or null
	 */	
	private static function retrieveDuration($stream)
	{
			// If stream duration is not set or zero'ed, 
			// try to retrieve duration from stream/tag section 
		if(isset($stream->duration) && $stream->duration>0)
			return round($stream->duration*1000);
		else if(isset($stream->tags->duration))
			return self::convertDuration2msec($stream->tags->duration);
		else
			return null;
	}
	
	/**
	 * parsePixelFormat
	 *
	 * @param KalturaMediaInfo $mediaInfo
	 */	
	private static function parsePixelFormat($pixelFormat, KalturaMediaInfo $mediaInfo)
	{
		KalturaLog::log("In - pixelFormat:$pixelFormat");
//$pixelFormat='yuv422pzzz';;//
		$rv = preg_match('/\s*([a-z]+)\s*([0-9]+)\s*([a-z]*)\s*([0-9]*)/', $pixelFormat, $matches, PREG_OFFSET_CAPTURE);
		if($rv===false || !(isset($matches) && is_array($matches) and count($matches)>=3)){
			KalturaLog::log("Out - Unrecognized pixelFormat");
			return;
		}
		$mediaInfo->pixelFormat = $pixelFormat;
		$mediaInfo->colorSpace = $matches[1][0];
		$mediaInfo->chromaSubsampling = $matches[2][0];
		if(count($matches)>=5)
			$mediaInfo->bitsDepth = $bitsDepth = $matches[4][0];
		else
			$bitsDepth = null;
		KalturaLog::log("Out - colorSpace:$mediaInfo->colorSpace, chromaSubsampling:$mediaInfo->chromaSubsampling, bitsDepth:$bitsDepth");
	}
};

