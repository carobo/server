<?php
/**
 * @package plugins.elasticSearch
 * @subpackage api.objects
 */
class KalturaESearchGroupResult extends KalturaESearchResult
{
	/**
	 * @var KalturaGroup
	 */
	public $object;

	private static $map_between_objects = array(
		'object',
	);

	protected function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	protected function doFromObject($srcObj, KalturaDetachedResponseProfile $responseProfile = null)
	{
		$object = new KalturaGroup();
		$object->fromObject($srcObj->getObject(), $responseProfile);
		$this->object = $object;
		return parent::doFromObject($srcObj, $responseProfile);
	}

}
