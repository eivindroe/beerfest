<?php
namespace Beerfest\User;

use Beerfest\GenericObject;
use Beerfest\User\UserDB;
use Beerfest\Fest\Participant\ParticipantDB;
use Beerfest\Fest\Fest;
use Beerfest\Fest\Fests;
use Beerfest\Fest\Item\Vote\VoteDB;
use Beerfest\Fest\Item\Vote\Vote;

class User extends GenericObject
{
    /**
     * Constructor
     *
     * @param mixed $mxdId User id
     *
     * @since 11. February 2014, v. 1.00
     */
    public function __construct($mxdId = null)
    {
        parent::__construct(new UserDB(), $mxdId);
    }// __construct


    /**
     * Get all user data
     *
     * @since 11. February 2014, v. 1.00
     * @return array User data
     */
    public function getAll()
    {
        $aryData = parent::getAll();
        return $aryData;
    }// getAll


    /**
     * Get user full name
     *
     * @since 11. February 2014, v. 1.00
     * @return string User full name
     */
    public function getFullName()
    {
        $strName = '';
        if($this->getId())
        {
            $strName = $this->get(UserDB::COL_FIRSTNAME) . ' ' . $this->get(UserDB::COL_LASTNAME);
        }
        return $strName;
    }// getFullName


    /**
     * Get last active as date
     *
     * @since 29. February 2014, v. 1.00
     * @return string Last active as date
     */
    public function getLastActiveAsDate()
    {
        $mxdLastActive = $this->get(UserDB::COL_LAST_ACTIVE);
        if($mxdLastActive)
        {
            $mxdLastActive = date('l j. F Y H:i:s', $mxdLastActive);
        }
        return $mxdLastActive;
    }// getLastActiveAsDate


    /**
     * Set last active date
     *
     * @param integer|null $intTime Last active date as timestamp, if null current time is used
     *
     * @since 23. February 2014, v. 1.00
     * @return integer Last active timestamp
     */
    public function setLastActive($intTime = null)
    {
        if(!isset($intTime) || !is_numeric($intTime))
        {
            $intTime = time();
        }
        $this->set(UserDB::COL_LAST_ACTIVE, $intTime);
        $this->save();
        return $intTime;
    }// setLastActive


    /**
     * Get user active fest
     *
     * @since 22. February 2014, v. 1.00
     * @return Fest|null
     */
    public function getActiveFest()
    {
        $aryFests = $this->getFests();

        $objFest = null;
        if(count($aryFests))
        {
            $intActiveFest = $aryFests[1];
            $objFest = new Fest(md5($intActiveFest));
        }
        return $objFest;
    }// getActiveFest


    /**
     * Get user fests
     *
     * @since 28. February 2014, v. 1.00
     * @return array|null Array fests connected to user, null if none
     */
    public function getFests()
    {
        $objFests = new Fests();
        $aryFests = $objFests->getByUser($this->getId());
        return $aryFests;
    }// getFests


    /**
     * Check if user is admin
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if is admin, false if not
     */
    public function isAdmin()
    {
        $blnAdmin = false;
        if($this->getId() == 1)
        {
            $blnAdmin = true;
        }
        return $blnAdmin;
    }// isAdmin


    /**
     * Save user
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    public function save()
    {
        parent::save();
    }// save


    /**
     * Get all votes by user
     *
     * @since 29. February 2014, v. 1.00
     * @return array Votes by user
     */
    public function getVotes()
    {
        $aryVotes = array();
        $objDb = new VoteDB();
        $strWhere = sql_where(VoteDB::COL_USER_ID, $this->getId());
        $aryResult = $objDb->select(array($objDb::COL_ID), $strWhere);

        if(count($aryResult))
        {
            foreach($aryResult as $aryRow)
            {
                $objVote = new Vote(md5($aryRow[$objDb::COL_ID]));
                $aryVotes[$objVote->get($objDb::COL_FEST_ID)][] = $objVote;
        }
        }
        return $aryVotes;
    }// getVotes


}// User