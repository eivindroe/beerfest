<?php
namespace Beerfest\Fest;

use \Beerfest\DBTable;
use \Beerfest\Fest\Item\ItemDB;

class FestDB extends DBTable
{
    /**
     * Database columns
     * @var string
     */
    const COL_ID = 'festid';
    const COL_NAME = 'name';
    const COL_LOCATION = 'location';
    const COL_ACTIVE = 'active';
    const COL_CURRENT_ITEM = ItemDB::COL_ID;
    const COL_CREATED = 'created';
    const COL_CREATED_BY = 'created_by';
    const COL_DELETED = 'deleted';


    /**
     * Get table name
     *
     * @since 22. February 2014, v. 1.00
     * @return string Table name
     */
    public function getTableName()
    {
        return 'fest';
    }// getTableName


    /**
     * Get table columns
     *
     * @since 22. February 2014, v. 1.00
     * @return array Table columns
     */
    public function getTableColumns()
    {
        return array(
            self::COL_ID => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED => true,
                DBTable::DB_AUTO_INCREMENT => true,
                DBTable::DB_PRIMARY => true,
                DBTable::DB_UNIQUE => true
            ),
            self::COL_NAME => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE => 255,
                DBTable::DB_REQUIRED => true
            ),
            self::COL_LOCATION => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE => 255,
                DBTable::DB_REQUIRED => true
            ),
            self::COL_ACTIVE => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_BOOL    
            ),
            self::COL_CURRENT_ITEM => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_INT,
            ),
            self::COL_CREATED => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_INT
            ),
            self::COL_CREATED_BY => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_INT
            ),
            self::COL_DELETED => array(
                DBTable::DB_TYPE => DBTable::DB_TYPE_INT
            )
        );
    }// getTableColumns


}// FestDB