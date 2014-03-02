<?php
namespace Beerfest\Fest\Item;

use \Beerfest\DBTable;
use \Beerfest\Fest\FestDB;

class ItemDB extends DBTable
{
    /**
     * Columns
     * @var string
     */
    const COL_ID = 'festitemid';
    const COL_FEST_ID = FestDB::COL_ID;
    const COL_NAME = 'name';
    const COL_DESC = 'description';
    const COL_RANGE = 'range';
    const COL_DELETED = 'deleted';


    /**
     * Get database table name
     *
     * @since 22. February 2014, v. 1.00
     * @return string Table name
     */
    public function getTableName()
    {
        return 'fest_item';
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
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT,
                DBTable::DB_PRIMARY         => true,
                DBTable::DB_UNIQUE          => true,
                DBTable::DB_AUTO_INCREMENT  => true,
                DBTable::DB_REQUIRED        => true
            ),
            self::COL_FEST_ID => array(
                DBTable::DB_TYPE             => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED         => true
            ),
            self::COL_NAME => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE            => 255,
                DBTable::DB_REQUIRED        => true
            ),
            self::COL_DESC => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE            => 255
            ),
            self::COL_RANGE => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE            => 10
            ),
            self::COL_DELETED => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT
            )
        );
    }// getTableColumns


}// FestItemDB