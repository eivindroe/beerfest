<?php
namespace Beerfest\Fest\Item\Vote;

use Beerfest\DBTable;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\FestDB;
use Beerfest\User\UserDB;

class VoteDB extends DBTable
{
    /**
     * Db columns
     * @var string
     */
    const COL_ID            = 'festitemvoteid';
    const COL_FEST_ITEM_ID  = ItemDB::COL_ID;
    const COL_FEST_ID       = FestDB::COL_ID;
    const COL_USER_ID       = UserDB::COL_ID;
    const COL_VALUE         = 'value';
    const COL_DETAILS       = 'details';
    const COL_DATE          = 'date';
    const COL_DELETED       = 'deleted';


    /**
     * Get database table name
     *
     * @since 22. February 2014, v. 1.00
     * @return string Table name
     */
    public function getTableName()
    {
        return 'fest_item_vote';
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
                DBTable::DB_REQUIRED        => true,
                DBTable::DB_PRIMARY         => true,
                DBTable::DB_UNIQUE          => true,
                DBTable::DB_AUTO_INCREMENT  => true
            ),
            self::COL_FEST_ITEM_ID => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED        => true,
            ),
            self::COL_FEST_ID => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED        => true,
            ),
            self::COL_USER_ID => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED        => true,
            ),
            self::COL_VALUE => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_DOUBLE,
                DBTable::DB_REQUIRED        => true
            ),
            self::COL_DETAILS => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_VARCHAR
            ),
            self::COL_DATE => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_BIGINT,
                DBTable::DB_REQUIRED        => true
            ),
            self::COL_DELETED => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT
            )
        );
    }// getTableColumns


}// FestItemVoteDB