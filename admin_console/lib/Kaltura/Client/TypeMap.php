<?php
/**
 * @package Admin
 * @subpackage Client
 */
class Kaltura_Client_TypeMap
{
	private static $map = array(
		'KalturaDynamicEnum' => 'Kaltura_Client_Type_DynamicEnum',
		'KalturaBaseEntry' => 'Kaltura_Client_Type_BaseEntry',
		'KalturaFileSync' => 'Kaltura_Client_FileSync_Type_FileSync',
		'KalturaFileSyncListResponse' => 'Kaltura_Client_FileSync_Type_FileSyncListResponse',
		'KalturaBaseJob' => 'Kaltura_Client_Type_BaseJob',
		'KalturaJobData' => 'Kaltura_Client_Type_JobData',
		'KalturaBatchJob' => 'Kaltura_Client_Type_BatchJob',
		'KalturaBatchJobListResponse' => 'Kaltura_Client_Type_BatchJobListResponse',
		'KalturaAsset' => 'Kaltura_Client_Type_Asset',
		'KalturaFlavorAsset' => 'Kaltura_Client_Type_FlavorAsset',
		'KalturaMediaInfo' => 'Kaltura_Client_Type_MediaInfo',
		'KalturaMediaInfoListResponse' => 'Kaltura_Client_AdminConsole_Type_MediaInfoListResponse',
		'KalturaString' => 'Kaltura_Client_Type_String',
		'KalturaAssetParams' => 'Kaltura_Client_Type_AssetParams',
		'KalturaFlavorParams' => 'Kaltura_Client_Type_FlavorParams',
		'KalturaFlavorParamsOutput' => 'Kaltura_Client_Type_FlavorParamsOutput',
		'KalturaFlavorParamsOutputListResponse' => 'Kaltura_Client_AdminConsole_Type_FlavorParamsOutputListResponse',
		'KalturaInvestigateFlavorAssetData' => 'Kaltura_Client_AdminConsole_Type_InvestigateFlavorAssetData',
		'KalturaThumbAsset' => 'Kaltura_Client_Type_ThumbAsset',
		'KalturaThumbParams' => 'Kaltura_Client_Type_ThumbParams',
		'KalturaThumbParamsOutput' => 'Kaltura_Client_Type_ThumbParamsOutput',
		'KalturaThumbParamsOutputListResponse' => 'Kaltura_Client_AdminConsole_Type_ThumbParamsOutputListResponse',
		'KalturaInvestigateThumbAssetData' => 'Kaltura_Client_AdminConsole_Type_InvestigateThumbAssetData',
		'KalturaTrackEntry' => 'Kaltura_Client_AdminConsole_Type_TrackEntry',
		'KalturaInvestigateEntryData' => 'Kaltura_Client_AdminConsole_Type_InvestigateEntryData',
		'KalturaSchedulerStatus' => 'Kaltura_Client_Type_SchedulerStatus',
		'KalturaSchedulerConfig' => 'Kaltura_Client_Type_SchedulerConfig',
		'KalturaSchedulerWorker' => 'Kaltura_Client_Type_SchedulerWorker',
		'KalturaScheduler' => 'Kaltura_Client_Type_Scheduler',
		'KalturaSearchItem' => 'Kaltura_Client_Type_SearchItem',
		'KalturaFilter' => 'Kaltura_Client_Type_Filter',
		'KalturaBaseJobBaseFilter' => 'Kaltura_Client_Type_BaseJobBaseFilter',
		'KalturaBaseJobFilter' => 'Kaltura_Client_Type_BaseJobFilter',
		'KalturaBatchJobBaseFilter' => 'Kaltura_Client_Type_BatchJobBaseFilter',
		'KalturaBatchJobFilter' => 'Kaltura_Client_Type_BatchJobFilter',
		'KalturaWorkerQueueFilter' => 'Kaltura_Client_Type_WorkerQueueFilter',
		'KalturaBatchQueuesStatus' => 'Kaltura_Client_Type_BatchQueuesStatus',
		'KalturaControlPanelCommand' => 'Kaltura_Client_Type_ControlPanelCommand',
		'KalturaSchedulerStatusResponse' => 'Kaltura_Client_Type_SchedulerStatusResponse',
		'KalturaControlPanelCommandBaseFilter' => 'Kaltura_Client_Type_ControlPanelCommandBaseFilter',
		'KalturaControlPanelCommandFilter' => 'Kaltura_Client_Type_ControlPanelCommandFilter',
		'KalturaFilterPager' => 'Kaltura_Client_Type_FilterPager',
		'KalturaControlPanelCommandListResponse' => 'Kaltura_Client_Type_ControlPanelCommandListResponse',
		'KalturaSchedulerListResponse' => 'Kaltura_Client_Type_SchedulerListResponse',
		'KalturaSchedulerWorkerListResponse' => 'Kaltura_Client_Type_SchedulerWorkerListResponse',
		'KalturaAssetParamsBaseFilter' => 'Kaltura_Client_Type_AssetParamsBaseFilter',
		'KalturaAssetParamsFilter' => 'Kaltura_Client_Type_AssetParamsFilter',
		'KalturaFlavorParamsBaseFilter' => 'Kaltura_Client_Type_FlavorParamsBaseFilter',
		'KalturaFlavorParamsFilter' => 'Kaltura_Client_Type_FlavorParamsFilter',
		'KalturaFlavorParamsListResponse' => 'Kaltura_Client_Type_FlavorParamsListResponse',
		'KalturaBatchJobResponse' => 'Kaltura_Client_Type_BatchJobResponse',
		'KalturaMailJobData' => 'Kaltura_Client_Type_MailJobData',
		'KalturaBatchJobFilterExt' => 'Kaltura_Client_Type_BatchJobFilterExt',
		'KalturaPartner' => 'Kaltura_Client_Type_Partner',
		'KalturaPermissionItem' => 'Kaltura_Client_Type_PermissionItem',
		'KalturaPermissionItemBaseFilter' => 'Kaltura_Client_Type_PermissionItemBaseFilter',
		'KalturaPermissionItemFilter' => 'Kaltura_Client_Type_PermissionItemFilter',
		'KalturaPermissionItemListResponse' => 'Kaltura_Client_Type_PermissionItemListResponse',
		'KalturaPermission' => 'Kaltura_Client_Type_Permission',
		'KalturaPermissionBaseFilter' => 'Kaltura_Client_Type_PermissionBaseFilter',
		'KalturaPermissionFilter' => 'Kaltura_Client_Type_PermissionFilter',
		'KalturaPermissionListResponse' => 'Kaltura_Client_Type_PermissionListResponse',
		'KalturaThumbParamsBaseFilter' => 'Kaltura_Client_Type_ThumbParamsBaseFilter',
		'KalturaThumbParamsFilter' => 'Kaltura_Client_Type_ThumbParamsFilter',
		'KalturaThumbParamsListResponse' => 'Kaltura_Client_Type_ThumbParamsListResponse',
		'KalturaUiConf' => 'Kaltura_Client_Type_UiConf',
		'KalturaUiConfBaseFilter' => 'Kaltura_Client_Type_UiConfBaseFilter',
		'KalturaUiConfFilter' => 'Kaltura_Client_Type_UiConfFilter',
		'KalturaUiConfListResponse' => 'Kaltura_Client_Type_UiConfListResponse',
		'KalturaUiConfTypeInfo' => 'Kaltura_Client_Type_UiConfTypeInfo',
		'KalturaUserRole' => 'Kaltura_Client_Type_UserRole',
		'KalturaUserRoleBaseFilter' => 'Kaltura_Client_Type_UserRoleBaseFilter',
		'KalturaUserRoleFilter' => 'Kaltura_Client_Type_UserRoleFilter',
		'KalturaUserRoleListResponse' => 'Kaltura_Client_Type_UserRoleListResponse',
		'KalturaUser' => 'Kaltura_Client_Type_User',
		'KalturaUserBaseFilter' => 'Kaltura_Client_Type_UserBaseFilter',
		'KalturaUserFilter' => 'Kaltura_Client_Type_UserFilter',
		'KalturaUserListResponse' => 'Kaltura_Client_Type_UserListResponse',
		'KalturaMetadataBaseFilter' => 'Kaltura_Client_Metadata_Type_MetadataBaseFilter',
		'KalturaMetadataFilter' => 'Kaltura_Client_Metadata_Type_MetadataFilter',
		'KalturaMetadata' => 'Kaltura_Client_Metadata_Type_Metadata',
		'KalturaMetadataListResponse' => 'Kaltura_Client_Metadata_Type_MetadataListResponse',
		'KalturaMetadataProfileBaseFilter' => 'Kaltura_Client_Metadata_Type_MetadataProfileBaseFilter',
		'KalturaMetadataProfileFilter' => 'Kaltura_Client_Metadata_Type_MetadataProfileFilter',
		'KalturaMetadataProfile' => 'Kaltura_Client_Metadata_Type_MetadataProfile',
		'KalturaMetadataProfileListResponse' => 'Kaltura_Client_Metadata_Type_MetadataProfileListResponse',
		'KalturaMetadataProfileField' => 'Kaltura_Client_Metadata_Type_MetadataProfileField',
		'KalturaMetadataProfileFieldListResponse' => 'Kaltura_Client_Metadata_Type_MetadataProfileFieldListResponse',
		'KalturaPartnerBaseFilter' => 'Kaltura_Client_Type_PartnerBaseFilter',
		'KalturaPartnerFilter' => 'Kaltura_Client_Type_PartnerFilter',
		'KalturaStorageProfile' => 'Kaltura_Client_StorageProfile_Type_StorageProfile',
		'KalturaStorageProfileListResponse' => 'Kaltura_Client_StorageProfile_Type_StorageProfileListResponse',
		'KalturaFileSyncBaseFilter' => 'Kaltura_Client_FileSync_Type_FileSyncBaseFilter',
		'KalturaFileSyncFilter' => 'Kaltura_Client_FileSync_Type_FileSyncFilter',
		'KalturaSystemPartnerUsageFilter' => 'Kaltura_Client_SystemPartner_Type_SystemPartnerUsageFilter',
		'KalturaSystemPartnerUsageItem' => 'Kaltura_Client_SystemPartner_Type_SystemPartnerUsageItem',
		'KalturaSystemPartnerUsageListResponse' => 'Kaltura_Client_SystemPartner_Type_SystemPartnerUsageListResponse',
		'KalturaPartnerListResponse' => 'Kaltura_Client_Type_PartnerListResponse',
		'KalturaSystemPartnerConfiguration' => 'Kaltura_Client_SystemPartner_Type_SystemPartnerConfiguration',
		'KalturaSystemPartnerPackage' => 'Kaltura_Client_SystemPartner_Type_SystemPartnerPackage',
		'KalturaFlavorParamsOutputBaseFilter' => 'Kaltura_Client_Type_FlavorParamsOutputBaseFilter',
		'KalturaFlavorParamsOutputFilter' => 'Kaltura_Client_Type_FlavorParamsOutputFilter',
		'KalturaThumbParamsOutputBaseFilter' => 'Kaltura_Client_Type_ThumbParamsOutputBaseFilter',
		'KalturaThumbParamsOutputFilter' => 'Kaltura_Client_Type_ThumbParamsOutputFilter',
		'KalturaMediaInfoBaseFilter' => 'Kaltura_Client_Type_MediaInfoBaseFilter',
		'KalturaMediaInfoFilter' => 'Kaltura_Client_Type_MediaInfoFilter',
		'KalturaTrackEntryListResponse' => 'Kaltura_Client_AdminConsole_Type_TrackEntryListResponse',
		'KalturaUiConfAdmin' => 'Kaltura_Client_AdminConsole_Type_UiConfAdmin',
		'KalturaUiConfAdminListResponse' => 'Kaltura_Client_AdminConsole_Type_UiConfAdminListResponse',
		'KalturaInternalToolsSession' => 'Kaltura_Client_KalturaInternalTools_Type_InternalToolsSession',
		'KalturaExclusiveLockKey' => 'Kaltura_Client_Type_ExclusiveLockKey',
		'KalturaFreeJobResponse' => 'Kaltura_Client_Type_FreeJobResponse',
		'KalturaBulkUploadPluginData' => 'Kaltura_Client_Type_BulkUploadPluginData',
		'KalturaBulkUploadResult' => 'Kaltura_Client_Type_BulkUploadResult',
		'KalturaConvertCollectionFlavorData' => 'Kaltura_Client_Type_ConvertCollectionFlavorData',
		'KalturaNotification' => 'Kaltura_Client_Type_Notification',
		'KalturaBatchGetExclusiveNotificationJobsResponse' => 'Kaltura_Client_Type_BatchGetExclusiveNotificationJobsResponse',
		'KalturaFileExistsResponse' => 'Kaltura_Client_Type_FileExistsResponse',
		'KalturaVirusScanProfileBaseFilter' => 'Kaltura_Client_VirusScan_Type_VirusScanProfileBaseFilter',
		'KalturaVirusScanProfileFilter' => 'Kaltura_Client_VirusScan_Type_VirusScanProfileFilter',
		'KalturaBaseEntryBaseFilter' => 'Kaltura_Client_Type_BaseEntryBaseFilter',
		'KalturaBaseEntryFilter' => 'Kaltura_Client_Type_BaseEntryFilter',
		'KalturaVirusScanProfile' => 'Kaltura_Client_VirusScan_Type_VirusScanProfile',
		'KalturaVirusScanProfileListResponse' => 'Kaltura_Client_VirusScan_Type_VirusScanProfileListResponse',
		'KalturaDistributionThumbDimensions' => 'Kaltura_Client_ContentDistribution_Type_DistributionThumbDimensions',
		'KalturaDistributionProfile' => 'Kaltura_Client_ContentDistribution_Type_DistributionProfile',
		'KalturaDistributionProfileBaseFilter' => 'Kaltura_Client_ContentDistribution_Type_DistributionProfileBaseFilter',
		'KalturaDistributionProfileFilter' => 'Kaltura_Client_ContentDistribution_Type_DistributionProfileFilter',
		'KalturaDistributionProfileListResponse' => 'Kaltura_Client_ContentDistribution_Type_DistributionProfileListResponse',
		'KalturaDistributionValidationError' => 'Kaltura_Client_ContentDistribution_Type_DistributionValidationError',
		'KalturaEntryDistribution' => 'Kaltura_Client_ContentDistribution_Type_EntryDistribution',
		'KalturaEntryDistributionBaseFilter' => 'Kaltura_Client_ContentDistribution_Type_EntryDistributionBaseFilter',
		'KalturaEntryDistributionFilter' => 'Kaltura_Client_ContentDistribution_Type_EntryDistributionFilter',
		'KalturaEntryDistributionListResponse' => 'Kaltura_Client_ContentDistribution_Type_EntryDistributionListResponse',
		'KalturaDistributionProviderBaseFilter' => 'Kaltura_Client_ContentDistribution_Type_DistributionProviderBaseFilter',
		'KalturaDistributionProviderFilter' => 'Kaltura_Client_ContentDistribution_Type_DistributionProviderFilter',
		'KalturaDistributionProvider' => 'Kaltura_Client_ContentDistribution_Type_DistributionProvider',
		'KalturaDistributionProviderListResponse' => 'Kaltura_Client_ContentDistribution_Type_DistributionProviderListResponse',
		'KalturaGenericDistributionProvider' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProvider',
		'KalturaGenericDistributionProviderBaseFilter' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderBaseFilter',
		'KalturaGenericDistributionProviderFilter' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderFilter',
		'KalturaGenericDistributionProviderListResponse' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderListResponse',
		'KalturaGenericDistributionProviderAction' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderAction',
		'KalturaGenericDistributionProviderActionBaseFilter' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderActionBaseFilter',
		'KalturaGenericDistributionProviderActionFilter' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderActionFilter',
		'KalturaGenericDistributionProviderActionListResponse' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProviderActionListResponse',
		'KalturaDropFolder' => 'Kaltura_Client_DropFolder_Type_DropFolder',
		'KalturaDropFolderBaseFilter' => 'Kaltura_Client_DropFolder_Type_DropFolderBaseFilter',
		'KalturaDropFolderFilter' => 'Kaltura_Client_DropFolder_Type_DropFolderFilter',
		'KalturaDropFolderListResponse' => 'Kaltura_Client_DropFolder_Type_DropFolderListResponse',
		'KalturaDropFolderFile' => 'Kaltura_Client_DropFolder_Type_DropFolderFile',
		'KalturaDropFolderFileBaseFilter' => 'Kaltura_Client_DropFolder_Type_DropFolderFileBaseFilter',
		'KalturaDropFolderFileFilter' => 'Kaltura_Client_DropFolder_Type_DropFolderFileFilter',
		'KalturaDropFolderFileListResponse' => 'Kaltura_Client_DropFolder_Type_DropFolderFileListResponse',
		'KalturaDataEntry' => 'Kaltura_Client_Type_DataEntry',
		'KalturaPlayableEntry' => 'Kaltura_Client_Type_PlayableEntry',
		'KalturaMediaEntry' => 'Kaltura_Client_Type_MediaEntry',
		'KalturaLiveStreamBitrate' => 'Kaltura_Client_Type_LiveStreamBitrate',
		'KalturaLiveStreamEntry' => 'Kaltura_Client_Type_LiveStreamEntry',
		'KalturaLiveStreamAdminEntry' => 'Kaltura_Client_Type_LiveStreamAdminEntry',
		'KalturaMixEntry' => 'Kaltura_Client_Type_MixEntry',
		'KalturaPlayableEntryBaseFilter' => 'Kaltura_Client_Type_PlayableEntryBaseFilter',
		'KalturaPlayableEntryFilter' => 'Kaltura_Client_Type_PlayableEntryFilter',
		'KalturaMediaEntryBaseFilter' => 'Kaltura_Client_Type_MediaEntryBaseFilter',
		'KalturaMediaEntryFilter' => 'Kaltura_Client_Type_MediaEntryFilter',
		'KalturaMediaEntryFilterForPlaylist' => 'Kaltura_Client_Type_MediaEntryFilterForPlaylist',
		'KalturaPlaylist' => 'Kaltura_Client_Type_Playlist',
		'KalturaMailJob' => 'Kaltura_Client_Type_MailJob',
		'KalturaBulkDownloadJobData' => 'Kaltura_Client_Type_BulkDownloadJobData',
		'KalturaBulkUploadJobData' => 'Kaltura_Client_Type_BulkUploadJobData',
		'KalturaCaptureThumbJobData' => 'Kaltura_Client_Type_CaptureThumbJobData',
		'KalturaConvartableJobData' => 'Kaltura_Client_Type_ConvartableJobData',
		'KalturaConvertCollectionJobData' => 'Kaltura_Client_Type_ConvertCollectionJobData',
		'KalturaConvertJobData' => 'Kaltura_Client_Type_ConvertJobData',
		'KalturaConvertProfileJobData' => 'Kaltura_Client_Type_ConvertProfileJobData',
		'KalturaExtractMediaJobData' => 'Kaltura_Client_Type_ExtractMediaJobData',
		'KalturaFlattenJobData' => 'Kaltura_Client_Type_FlattenJobData',
		'KalturaImportJobData' => 'Kaltura_Client_Type_ImportJobData',
		'KalturaNotificationJobData' => 'Kaltura_Client_Type_NotificationJobData',
		'KalturaPostConvertJobData' => 'Kaltura_Client_Type_PostConvertJobData',
		'KalturaProvisionJobData' => 'Kaltura_Client_Type_ProvisionJobData',
		'KalturaPullJobData' => 'Kaltura_Client_Type_PullJobData',
		'KalturaRemoteConvertJobData' => 'Kaltura_Client_Type_RemoteConvertJobData',
		'KalturaStorageJobData' => 'Kaltura_Client_Type_StorageJobData',
		'KalturaStorageDeleteJobData' => 'Kaltura_Client_Type_StorageDeleteJobData',
		'KalturaStorageExportJobData' => 'Kaltura_Client_Type_StorageExportJobData',
		'KalturaBulkUploadCsvJobData' => 'Kaltura_Client_Type_BulkUploadCsvJobData',
		'KalturaBulkUploadXmlJobData' => 'Kaltura_Client_Type_BulkUploadXmlJobData',
		'KalturaDistributionJobProviderData' => 'Kaltura_Client_ContentDistribution_Type_DistributionJobProviderData',
		'KalturaDistributionRemoteMediaFile' => 'Kaltura_Client_ContentDistribution_Type_DistributionRemoteMediaFile',
		'KalturaDistributionJobData' => 'Kaltura_Client_ContentDistribution_Type_DistributionJobData',
		'KalturaDistributionDeleteJobData' => 'Kaltura_Client_ContentDistribution_Type_DistributionDeleteJobData',
		'KalturaDistributionFetchReportJobData' => 'Kaltura_Client_ContentDistribution_Type_DistributionFetchReportJobData',
		'KalturaDistributionSubmitJobData' => 'Kaltura_Client_ContentDistribution_Type_DistributionSubmitJobData',
		'KalturaDistributionUpdateJobData' => 'Kaltura_Client_ContentDistribution_Type_DistributionUpdateJobData',
		'KalturaVirusScanJobData' => 'Kaltura_Client_VirusScan_Type_VirusScanJobData',
		'KalturaAssetParamsOutput' => 'Kaltura_Client_Type_AssetParamsOutput',
		'KalturaMediaFlavorParams' => 'Kaltura_Client_Type_MediaFlavorParams',
		'KalturaMediaFlavorParamsOutput' => 'Kaltura_Client_Type_MediaFlavorParamsOutput',
		'KalturaSearchCondition' => 'Kaltura_Client_Type_SearchCondition',
		'KalturaSearchComparableCondition' => 'Kaltura_Client_Type_SearchComparableCondition',
		'KalturaSearchOperator' => 'Kaltura_Client_Type_SearchOperator',
		'KalturaContentDistributionSearchItem' => 'Kaltura_Client_ContentDistribution_Type_ContentDistributionSearchItem',
		'KalturaAccessControlBaseFilter' => 'Kaltura_Client_Type_AccessControlBaseFilter',
		'KalturaAccessControlFilter' => 'Kaltura_Client_Type_AccessControlFilter',
		'KalturaMailJobBaseFilter' => 'Kaltura_Client_Type_MailJobBaseFilter',
		'KalturaMailJobFilter' => 'Kaltura_Client_Type_MailJobFilter',
		'KalturaNotificationBaseFilter' => 'Kaltura_Client_Type_NotificationBaseFilter',
		'KalturaNotificationFilter' => 'Kaltura_Client_Type_NotificationFilter',
		'KalturaAssetBaseFilter' => 'Kaltura_Client_Type_AssetBaseFilter',
		'KalturaAssetFilter' => 'Kaltura_Client_Type_AssetFilter',
		'KalturaAssetParamsOutputBaseFilter' => 'Kaltura_Client_Type_AssetParamsOutputBaseFilter',
		'KalturaAssetParamsOutputFilter' => 'Kaltura_Client_Type_AssetParamsOutputFilter',
		'KalturaConversionProfileAssetParamsBaseFilter' => 'Kaltura_Client_Type_ConversionProfileAssetParamsBaseFilter',
		'KalturaConversionProfileAssetParamsFilter' => 'Kaltura_Client_Type_ConversionProfileAssetParamsFilter',
		'KalturaConversionProfileBaseFilter' => 'Kaltura_Client_Type_ConversionProfileBaseFilter',
		'KalturaConversionProfileFilter' => 'Kaltura_Client_Type_ConversionProfileFilter',
		'KalturaFlavorAssetBaseFilter' => 'Kaltura_Client_Type_FlavorAssetBaseFilter',
		'KalturaFlavorAssetFilter' => 'Kaltura_Client_Type_FlavorAssetFilter',
		'KalturaMediaFlavorParamsBaseFilter' => 'Kaltura_Client_Type_MediaFlavorParamsBaseFilter',
		'KalturaMediaFlavorParamsFilter' => 'Kaltura_Client_Type_MediaFlavorParamsFilter',
		'KalturaMediaFlavorParamsOutputBaseFilter' => 'Kaltura_Client_Type_MediaFlavorParamsOutputBaseFilter',
		'KalturaMediaFlavorParamsOutputFilter' => 'Kaltura_Client_Type_MediaFlavorParamsOutputFilter',
		'KalturaThumbAssetBaseFilter' => 'Kaltura_Client_Type_ThumbAssetBaseFilter',
		'KalturaThumbAssetFilter' => 'Kaltura_Client_Type_ThumbAssetFilter',
		'KalturaDataEntryBaseFilter' => 'Kaltura_Client_Type_DataEntryBaseFilter',
		'KalturaDataEntryFilter' => 'Kaltura_Client_Type_DataEntryFilter',
		'KalturaLiveStreamEntryBaseFilter' => 'Kaltura_Client_Type_LiveStreamEntryBaseFilter',
		'KalturaLiveStreamEntryFilter' => 'Kaltura_Client_Type_LiveStreamEntryFilter',
		'KalturaLiveStreamAdminEntryBaseFilter' => 'Kaltura_Client_Type_LiveStreamAdminEntryBaseFilter',
		'KalturaLiveStreamAdminEntryFilter' => 'Kaltura_Client_Type_LiveStreamAdminEntryFilter',
		'KalturaMixEntryBaseFilter' => 'Kaltura_Client_Type_MixEntryBaseFilter',
		'KalturaMixEntryFilter' => 'Kaltura_Client_Type_MixEntryFilter',
		'KalturaPlaylistBaseFilter' => 'Kaltura_Client_Type_PlaylistBaseFilter',
		'KalturaPlaylistFilter' => 'Kaltura_Client_Type_PlaylistFilter',
		'KalturaAdminUserBaseFilter' => 'Kaltura_Client_Type_AdminUserBaseFilter',
		'KalturaAdminUserFilter' => 'Kaltura_Client_Type_AdminUserFilter',
		'KalturaBaseSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_BaseSyndicationFeedBaseFilter',
		'KalturaBaseSyndicationFeedFilter' => 'Kaltura_Client_Type_BaseSyndicationFeedFilter',
		'KalturaCategoryBaseFilter' => 'Kaltura_Client_Type_CategoryBaseFilter',
		'KalturaCategoryFilter' => 'Kaltura_Client_Type_CategoryFilter',
		'KalturaGoogleVideoSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_GoogleVideoSyndicationFeedBaseFilter',
		'KalturaGoogleVideoSyndicationFeedFilter' => 'Kaltura_Client_Type_GoogleVideoSyndicationFeedFilter',
		'KalturaITunesSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_ITunesSyndicationFeedBaseFilter',
		'KalturaITunesSyndicationFeedFilter' => 'Kaltura_Client_Type_ITunesSyndicationFeedFilter',
		'KalturaTubeMogulSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_TubeMogulSyndicationFeedBaseFilter',
		'KalturaTubeMogulSyndicationFeedFilter' => 'Kaltura_Client_Type_TubeMogulSyndicationFeedFilter',
		'KalturaUploadTokenBaseFilter' => 'Kaltura_Client_Type_UploadTokenBaseFilter',
		'KalturaUploadTokenFilter' => 'Kaltura_Client_Type_UploadTokenFilter',
		'KalturaWidgetBaseFilter' => 'Kaltura_Client_Type_WidgetBaseFilter',
		'KalturaWidgetFilter' => 'Kaltura_Client_Type_WidgetFilter',
		'KalturaYahooSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_YahooSyndicationFeedBaseFilter',
		'KalturaYahooSyndicationFeedFilter' => 'Kaltura_Client_Type_YahooSyndicationFeedFilter',
		'KalturaApiActionPermissionItemBaseFilter' => 'Kaltura_Client_Type_ApiActionPermissionItemBaseFilter',
		'KalturaApiActionPermissionItemFilter' => 'Kaltura_Client_Type_ApiActionPermissionItemFilter',
		'KalturaApiParameterPermissionItemBaseFilter' => 'Kaltura_Client_Type_ApiParameterPermissionItemBaseFilter',
		'KalturaApiParameterPermissionItemFilter' => 'Kaltura_Client_Type_ApiParameterPermissionItemFilter',
		'KalturaGenericSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_GenericSyndicationFeedBaseFilter',
		'KalturaGenericSyndicationFeedFilter' => 'Kaltura_Client_Type_GenericSyndicationFeedFilter',
		'KalturaGenericXsltSyndicationFeedBaseFilter' => 'Kaltura_Client_Type_GenericXsltSyndicationFeedBaseFilter',
		'KalturaGenericXsltSyndicationFeedFilter' => 'Kaltura_Client_Type_GenericXsltSyndicationFeedFilter',
		'KalturaAnnotationBaseFilter' => 'Kaltura_Client_Annotation_Type_AnnotationBaseFilter',
		'KalturaAnnotationFilter' => 'Kaltura_Client_Annotation_Type_AnnotationFilter',
		'KalturaAuditTrailBaseFilter' => 'Kaltura_Client_Audit_Type_AuditTrailBaseFilter',
		'KalturaAuditTrailFilter' => 'Kaltura_Client_Audit_Type_AuditTrailFilter',
		'KalturaDwhHourlyPartnerBaseFilter' => 'Kaltura_Client_PartnerAggregation_Type_DwhHourlyPartnerBaseFilter',
		'KalturaDwhHourlyPartnerFilter' => 'Kaltura_Client_PartnerAggregation_Type_DwhHourlyPartnerFilter',
		'KalturaShortLinkBaseFilter' => 'Kaltura_Client_ShortLink_Type_ShortLinkBaseFilter',
		'KalturaShortLinkFilter' => 'Kaltura_Client_ShortLink_Type_ShortLinkFilter',
		'KalturaApiActionPermissionItem' => 'Kaltura_Client_Type_ApiActionPermissionItem',
		'KalturaApiParameterPermissionItem' => 'Kaltura_Client_Type_ApiParameterPermissionItem',
		'KalturaGenericDistributionProfileAction' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProfileAction',
		'KalturaGenericDistributionProfile' => 'Kaltura_Client_ContentDistribution_Type_GenericDistributionProfile',
		'KalturaSyndicationDistributionProfile' => 'Kaltura_Client_ContentDistribution_Type_SyndicationDistributionProfile',
		'KalturaComcastDistributionProfile' => 'Kaltura_Client_ComcastDistribution_Type_ComcastDistributionProfile',
		'KalturaDailymotionDistributionProfile' => 'Kaltura_Client_DailymotionDistribution_Type_DailymotionDistributionProfile',
		'KalturaExampleDistributionProfile' => 'Kaltura_Client_ExampleDistribution_Type_ExampleDistributionProfile',
		'KalturaFreewheelDistributionProfile' => 'Kaltura_Client_FreewheelDistribution_Type_FreewheelDistributionProfile',
		'KalturaHuluDistributionProfile' => 'Kaltura_Client_HuluDistribution_Type_HuluDistributionProfile',
		'KalturaIdeticDistributionProfile' => 'Kaltura_Client_IdeticDistribution_Type_IdeticDistributionProfile',
		'KalturaMsnDistributionProfile' => 'Kaltura_Client_MsnDistribution_Type_MsnDistributionProfile',
		'KalturaMyspaceDistributionProfile' => 'Kaltura_Client_MyspaceDistribution_Type_MyspaceDistributionProfile',
		'KalturaSynacorDistributionProfile' => 'Kaltura_Client_SynacorDistribution_Type_SynacorDistributionProfile',
		'KalturaVerizonDistributionProfile' => 'Kaltura_Client_VerizonDistribution_Type_VerizonDistributionProfile',
		'KalturaYouTubeDistributionProfile' => 'Kaltura_Client_YouTubeDistribution_Type_YouTubeDistributionProfile',
		'KalturaDistributionValidationErrorInvalidData' => 'Kaltura_Client_ContentDistribution_Type_DistributionValidationErrorInvalidData',
		'KalturaDistributionValidationErrorInvalidMetadata' => 'Kaltura_Client_ContentDistribution_Type_DistributionValidationErrorInvalidMetadata',
		'KalturaDistributionValidationErrorMissingFlavor' => 'Kaltura_Client_ContentDistribution_Type_DistributionValidationErrorMissingFlavor',
		'KalturaDistributionValidationErrorMissingMetadata' => 'Kaltura_Client_ContentDistribution_Type_DistributionValidationErrorMissingMetadata',
		'KalturaDistributionValidationErrorMissingThumbnail' => 'Kaltura_Client_ContentDistribution_Type_DistributionValidationErrorMissingThumbnail',
		'KalturaSyndicationDistributionProvider' => 'Kaltura_Client_ContentDistribution_Type_SyndicationDistributionProvider',
		'KalturaComcastDistributionProvider' => 'Kaltura_Client_ComcastDistribution_Type_ComcastDistributionProvider',
		'KalturaDailymotionDistributionProvider' => 'Kaltura_Client_DailymotionDistribution_Type_DailymotionDistributionProvider',
		'KalturaExampleDistributionProvider' => 'Kaltura_Client_ExampleDistribution_Type_ExampleDistributionProvider',
		'KalturaFreewheelDistributionProvider' => 'Kaltura_Client_FreewheelDistribution_Type_FreewheelDistributionProvider',
		'KalturaHuluDistributionProvider' => 'Kaltura_Client_HuluDistribution_Type_HuluDistributionProvider',
		'KalturaIdeticDistributionProvider' => 'Kaltura_Client_IdeticDistribution_Type_IdeticDistributionProvider',
		'KalturaMsnDistributionProvider' => 'Kaltura_Client_MsnDistribution_Type_MsnDistributionProvider',
		'KalturaMyspaceDistributionProvider' => 'Kaltura_Client_MyspaceDistribution_Type_MyspaceDistributionProvider',
		'KalturaSynacorDistributionProvider' => 'Kaltura_Client_SynacorDistribution_Type_SynacorDistributionProvider',
		'KalturaVerizonDistributionProvider' => 'Kaltura_Client_VerizonDistribution_Type_VerizonDistributionProvider',
		'KalturaYouTubeDistributionProvider' => 'Kaltura_Client_YouTubeDistribution_Type_YouTubeDistributionProvider',
	);
	
	public static function getZendType($kalturaType)
	{
		if(isset(self::$map[$kalturaType]))
			return self::$map[$kalturaType];
		return null;
	}
}
