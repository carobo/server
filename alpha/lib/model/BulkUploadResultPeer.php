<?php

/**
 * Subclass for performing query and update operations on the 'bulk_upload_result' table.
 *
 * 
 *
 * @package Core
 * @subpackage model
 */ 
class BulkUploadResultPeer extends BaseBulkUploadResultPeer
{
    protected static $class_types_cache = array(
        BulkUploadResultObjectType::ENTRY => 'BulkUploadResultEntry',
        BulkUploadResultObjectType::CATEGORY => 'BulkUploadResultCategory',
        BulkUploadResultObjectType::USER => 'BulkUploadResultKuser',
        BulkUploadResultObjectType::CATEGORY_USER => 'BulkUploadResultCategoryKuser',
    );
    
	public static function retrieveByBulkUploadId($bulkUploadId)
	{
		$criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $bulkUploadId);
		$criteria->addAscendingOrderByColumn(BulkUploadResultPeer::LINE_INDEX);
		
		return self::doSelect($criteria);
	}
	
	
	/**
	 * @return BulkUploadResult 
	 */
	public static function retrieveByEntryId($entryId, $bulkUploadId = null)
	{
		$criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::OBJECT_ID, $entryId);
		$criteria->add(BulkUploadResultPeer::OBJECT_TYPE, BulkUploadResultObjectType::ENTRY);
		if($bulkUploadId)
			$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $bulkUploadId);
		
		return self::doSelectOne($criteria);
	}
	
	/**
	 * @return BulkUploadResult 
	 */
	public static function retrieveLastByBulkUploadId($bulkUploadId)
	{
		$criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $bulkUploadId);
		$criteria->addDescendingOrderByColumn(BulkUploadResultPeer::LINE_INDEX);
		
		return self::doSelectOne($criteria);
	}
	/**
	 * function to retrieve the number of BulkUploadResults with type ENTRY,and objectId<>null
	 * @param int $bulkUploadId
	 * @return int
	 */
	public static function countWithEntryByBulkUploadId($bulkUploadId)
	{
		$criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $bulkUploadId);
		$criteria->add(BulkUploadResultPeer::OBJECT_ID, null, Criteria::ISNOTNULL);
		$criteria->add(BulkUploadResultPeer::OBJECT_TYPE, BulkUploadResultObjectType::ENTRY);
		
		return self::doCount($criteria);
	}
	
	/**
	 * Function counts amount of bulk upload results for a given bulk upload job ID and object type
	 * @param int $bulkUploadJobId
	 * @param int $bulkUploadObjectType
	 * @return int
	 */
	public static function countWithObjectTypeByBulkUploadId ($bulkUploadJobId, $bulkUploadObjectType)
	{
	    $criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $bulkUploadJobId);
		$criteria->add(BulkUploadResultPeer::OBJECT_ID, null, Criteria::ISNOTNULL);
		$criteria->add(BulkUploadResultPeer::OBJECT_TYPE, $bulkUploadObjectType);
		
		return self::doCount($criteria);
	}
	
	/**
	 * function to retrieve the BulkUploadResults with type ENTRY,and objectId<>null
	 * @param int $bulkUploadId
	 * @return array
	 */
	public static function retrieveWithEntryByBulkUploadId($bulkUploadId)
	{
		$criteria = new Criteria();
		$criteria->add(BulkUploadResultPeer::BULK_UPLOAD_JOB_ID, $bulkUploadId);
		$criteria->add(BulkUploadResultPeer::OBJECT_ID, null, Criteria::ISNOTNULL);
		$criteria->add(BulkUploadResultPeer::OBJECT_TYPE, BulkUploadResultObjectType::ENTRY);
		$criteria->addAscendingOrderByColumn(BulkUploadResultPeer::LINE_INDEX);
		
		return self::doSelect($criteria);
	}
	
/**
	 * The returned Class will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @param      array $row PropelPDO result row.
	 * @param      int $colnum Column to examine for OM class information (first is 0).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getOMClass($row, $colnum)
	{
		if($row)
		{
			$typeField = self::translateFieldName(BulkUploadResultPeer::OBJECT_TYPE, BasePeer::TYPE_COLNAME, BasePeer::TYPE_NUM);
			$bulkUploadReultObjectType = $row[$typeField];
			if(isset(self::$class_types_cache[$bulkUploadReultObjectType]))
				return self::$class_types_cache[$bulkUploadReultObjectType];
				
			$extendedCls = KalturaPluginManager::getObjectClass(parent::OM_CLASS, $bulkUploadReultObjectType);
			if($extendedCls)
			{
				self::$class_types_cache[$bulkUploadReultObjectType] = $extendedCls;
				return $extendedCls;
			}
			self::$class_types_cache[$bulkUploadReultObjectType] = parent::OM_CLASS;
		}
			
		return parent::OM_CLASS;
	}
}
