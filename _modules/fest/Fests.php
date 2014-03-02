<?php
namespace Beerfest\Fest;

use Beerfest\Fest\FestDB;

class Fests
{
    /**
     * User DB object
     * @var FestDB
     */
    private $objDb;

    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->objDb = new FestDB();
    }// __construct


    /**
     * Get database object
     *
     * @since 22. February 2014, v. 1.00
     * @return FestDB
     */
    private function getDb()
    {
        return $this->objDb;
    }// getDb


    /**
     * Get all fests
     *
     * @param string $strOrder Order clause
     *
     * @since 22. February 2014, v. 1.00
     * @return array Fests
     */
    public function getAll($strOrder = '')
    {
        $objDb = $this->getDb();
        $aryFests = array();
        $aryResult = $this->getDb()->select(array_keys($objDb->getTableColumns()), '', $strOrder);
        foreach($aryResult as $aryRow)
        {
            $aryFests[$aryRow[$objDb::COL_ID]] = new Fest(md5($aryRow[$objDb::COL_ID]));
        }
        return $aryFests;
    }// getFests


    /**
     * Get fests by user
     *
     * @param integer $intUserId User id
     *
     * @since 28. February 2014, v. 1.00
     * @return array|null Fests by user as array, null if none
     */
    public function getByUser($intUserId)
    {
        $objFestDb = $this->getDb();
        $strFestWhere = sql_where($objFestDb::COL_CREATED_BY, $intUserId);
        $aryFestResult = $objFestDb->select(array($objFestDb::COL_ID), $strFestWhere);
        $aryFests = array();
        if(count($aryFestResult))
        {
            foreach($aryFestResult as $aryFest)
            {
                $aryFests[] = $aryFest[$objFestDb::COL_ID];
            }
        }

        $objParticipantDb = new \Beerfest\Fest\Participant\ParticipantDB();
        $strParticipantWhere = sql_where($objParticipantDb::COL_USERID, $intUserId);

        if(count($aryFests))
        {
            $strParticipantWhere .= ' AND ' . sql_where_not_in($objParticipantDb::COL_FESTID, $aryFests);
        }
        $aryParticipantFests = $objParticipantDb->select(array($objParticipantDb::COL_FESTID), $strParticipantWhere);

        if(count($aryParticipantFests))
        {
            foreach($aryParticipantFests as $aryParticipantFest)
            {
                $aryFests[] = $aryParticipantFest[$objParticipantDb::COL_FESTID];
            }
        }

        return $aryFests;
    }// getByUser


}// Fests