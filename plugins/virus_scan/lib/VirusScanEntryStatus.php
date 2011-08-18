<?php
/**
 * @package api
 * @subpackage enum
 */
class VirusScanEntryStatus implements IKalturaPluginEnum, entryStatus
{
	const INFECTED = 'Infected';
	
	public static function getAdditionalValues()
	{
		return array(
			'INFECTED' => self::INFECTED
		);
	}
	
	/**
	 * @return array
	 */
	public static function getAdditionalDescriptions()
	{
		return array();
	}
}
