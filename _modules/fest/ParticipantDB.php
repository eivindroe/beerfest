<?php
namespace Beerfest\Fest\Participant;

use Beerfest\DBTable;
use Beerfest\Fest\FestDB;
use Beerfest\User\UserDB;

class ParticipantDB extends DBTable
{
    /**
     * Database columns
     * @var string
     */
    const COL_ID = 'fest_ptc_id';
    const COL_FESTID = FestDB::COL_ID;
    const COL_USERID = UserDB::COL_ID;
    const COL_ACTIVE = 'active';
    const COL_LAST_ACTIVE = 'last_active';
    const COL_DELETED = 'deleted';


    /**
     * Get table name
     *
     * @since 22. February 2014, v. 1.00
     * @return string Table name
     */
    public function getTableName()
    {
        return 'fest_participant';
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
            ),self::COL_FESTID => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED        => true
            ),
            self::COL_USERID => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED        => true
            ),
            self::COL_ACTIVE => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_BOOL
            ),
            self::COL_LAST_ACTIVE => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_BIGINT
            ),
            self::COL_DELETED => array(
                DBTable::DB_TYPE            => DBTable::DB_TYPE_INT
            )
        );
    }// getTableColumns


    /**
     * Get fests by user
     *
     * @param integer $intUserId User id
     *
     * @since 22. February 2014, v. 1.00
     * @return array
     */
    public function getFestsByUser($intUserId)
    {
        $aryResult = array();
        $strWhere = sql_where(self::COL_USERID, $intUserId) . ' AND ' . sql_where(self::COL_ACTIVE, 1);
        $arySelect = $this->select(array(self::COL_FESTID), $strWhere);
        if(count($arySelect))
        {
            foreach($arySelect as $aryRow)
            {
                $aryResult[] = $aryRow[self::COL_FESTID];
            }
        }
        return $aryResult;
    }// getFestsBysUser


}// ParticipantDB