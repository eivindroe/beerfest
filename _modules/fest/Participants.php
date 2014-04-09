<?php
namespace Beerfest\Fest\Participant;

use Beerfest\GenericObject;
use Beerfest\Fest\Participant\ParticipantDB;
use Beerfest\Fest\Participant\Participant;
use Beerfest\User\Users;

class Participants extends GenericObject
{
    /**
     * Custom data
     * @var string
     */
    const COL_NAME = 'name';


    /**
     * DB object
     * @var FestParticipantDB
     */
    private $objDb;


    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->objDb = new ParticipantDB();
    }// __construct


    private function getDb()
    {
        return $this->objDb;
    }// getDb


    /**
     * Get participants
     *
     * @param string $strWhere Where clause
     *
     * @since 22. February 2014, v. 1.00
     * @return array
     */
    public function getParticipants($strWhere = '')
    {
        $objDb = $this->getDb();
        $aryParticipants = array();
        $aryUserIds = array();
        $aryResult = $this->getDb()->select(array_keys($objDb->getTableColumns()), $strWhere);
        if(count($aryResult))
        {
            foreach($aryResult as $aryRow)
            {
                $intId = $aryRow[$objDb::COL_ID];
                $aryUserIds[] = $aryRow[ParticipantDB::COL_USERID];
                $aryParticipantsCollection[$intId] = new Participant($intId);
            }

            $objUsers = new Users();
            $aryUserNames = $objUsers->getUserNames($aryUserIds);

            if(count($aryUserNames))
            {
                foreach($aryParticipantsCollection as $intId => $objParticipant)
                {
                    $intUserId = $objParticipant->get(ParticipantDB::COL_USERID);
                    if(isset($aryUserNames[$intUserId]))
                    {
                        $aryParticipant = $objParticipant->getAll();
                        $aryParticipant[self::COL_NAME] = $aryUserNames[$intUserId];
                        $aryParticipants[$intId] = $aryParticipant;
                    }
                }
            }
        }

        return $aryParticipants;
    }// getParticipants


    /**
     * Get participant by user id
     *
     * @param integer $intUserId User id
     *
     * @since 22. February 2014, v. 1.00
     * @return Participant|null
     */
    public function getParticipantByUserId($intUserId)
    {
        $objParticipant = null;
        $objDb = $this->getDb();
        $strWhere = sql_where(ParticipantDB::COL_USERID, $intUserId);
        $arySelect = $objDb->select(array(ParticipantDB::COL_ID), $strWhere);
        if(count($arySelect))
        {
            $objParticipant = new Participant($arySelect[0][ParticipantDB::COL_ID]);
        }
        return $objParticipant;
    }// getParticipantId


}// Participants