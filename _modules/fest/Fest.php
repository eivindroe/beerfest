<?php
namespace Beerfest\Fest;

use Beerfest\GenericObject;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Result;
use Beerfest\Fest\Items;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Participant\Participants;
use Beerfest\Fest\Participant\ParticipantDB;
use Beerfest\User\User;
use Beerfest\User\Users;
use Beerfest\User\UserDB;
use Beerfest\Core\Auth;

class Fest extends GenericObject
{
    /**
     * Constructor
     *
     * @param string $mxdId Fest id crypted
     *
     * @since 20. February 2014, v. 1.00
     */
    public function __construct($mxdId = null)
    {
        parent::__construct(new FestDB(), $mxdId);
    }// __construct


    /**
     * Get fest items
     *
     * @since 22. February 2014, v. 1.00
     * @return array Fest items
     */
    public function getItems()
    {
        $objFestItems = new Items($this);
        return $objFestItems->getItems(sql_where(ItemDB::COL_FEST_ID, $this->getId()));
    }// getItems


    /**
     * Get fest item vote weighting
     *
     * @since 10. March 2014, v. 1.00
     * @return array Fest item vote weighting
     */
    public function getWeighting()
    {
        return json_decode($this->get(FestDB::COL_VOTING), true);
    }// getWeighting


    /**
     * Get fest participants
     *
     * @since 22. February 2014, v. 1.00
     * @return array Fest items
     */
    public function getParticipants()
    {
        $objUsers = new Users();
        $aryUsers = $objUsers->getAll();

        $objParticipants = new Participants();
        $strWhere = sql_where(ParticipantDB::COL_FESTID, $this->getId());
        $aryParticipants = $objParticipants->getParticipants($strWhere);

        $aryParticipantsOrdered = array();
        if(count($aryParticipants))
        {
            foreach($aryParticipants as $aryParticipant)
            {
                $aryParticipantsOrdered[$aryParticipant[ParticipantDB::COL_USERID]] = $aryParticipant;
            }
        }

        $aryResult = array();
        foreach($aryUsers as $aryUser)
        {
            $intUserId = $aryUser[UserDB::COL_ID];
            $objUser = new User($intUserId);
            if(isset($aryParticipantsOrdered[$intUserId]))
            {
                $aryRow = $aryParticipantsOrdered[$intUserId];
            }
            else
            {
                $aryRow = array(
                    ParticipantDB::COL_ID       => null,
                    ParticipantDB::COL_USERID   => $intUserId,
                    ParticipantDB::COL_ACTIVE   => 0,
                    ParticipantDB::COL_FESTID   => $this->getId(),
                    Participants::COL_NAME      => $objUser->getFullName()
                );
            }
            $aryResult[] = $aryRow;
        }
        return $aryResult;
    }// getParticipants


    /**
     * Check if user is fest admin
     *
     * @since 09. May 2014, v. 1.00
     * @return boolean True if user is system admin or fest creator
     */
    public function isFestAdmin()
    {
        $objUser = Auth::getActiveUser();
        if($objUser->isAdmin() || $objUser->getId() == $this->get(FestDB::COL_CREATED_BY))
        {
            $blnAdmin = true;
        }
        else $blnAdmin = false;
        return $blnAdmin;
    }// isFestAdmin


    /**
     * Set anonymous property on fest - turn on or off
     *
     * @param boolean $blnAnonymous True to turn on anonymous fest functionality, false if turn off
     *
     * @since 23. October 2014, v. 1.10
     * @return void
     */
    public function setAnonymous($blnAnonymous = true)
    {
        if(is_bool($blnAnonymous))
        {
            $this->set(FestDB::COL_ANONYMOUS, $blnAnonymous);
        }
    }// setAnonymous


    /**
     * Check if fest is flagged as anonymous
     *
     * @since 23. October 2014, v. 1.10
     * @return boolean True if flagged anonymous, false if not
     */
    public function isAnonymous()
    {
        return ($this->get(FestDB::COL_ANONYMOUS));
    }// isAnonymous


    /**
     * Toggle active
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if active, false if deactivated
     */
    public function toggleActive()
    {
        $blnActive = $this->get(FestDB::COL_ACTIVE);

        if($blnActive)
        {
            $this->deactivate();
        }
        else
        {
            $this->activate();
        }
        return $this->get(FestDB::COL_ACTIVE);
    }// toggleActive


    /**
     * Deactivate fest
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function deactivate()
    {
        $this->set(FestDB::COL_ACTIVE, false);
        $this->save();
    }// deactivate


    /**
     * Activate fest
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function activate()
    {
        $this->set(FestDB::COL_ACTIVE, true);
        $this->save();
    }// activate


    /**
     * Start fest and set active item
     *
     * @since 28. February 2014, v. 1.00
     * @return integer|null Active item if items connected to fest, null otherwise
     */
    public function start()
    {
        $this->activate();
        if($this->get(FestDB::COL_CURRENT_ITEM) == 0)
        {
            $aryItems = $this->getItems();
            if(count($aryItems))
            {
                $intItemId = key($aryItems);
                $this->set(FestDB::COL_CURRENT_ITEM, $intItemId);
                $this->save();
            }
        }
        return $this->get(FestDB::COL_CURRENT_ITEM);
    }// start


    /**
     * Save fest
     *
     * @since 28. February 2014, v. 1.00
     * @return void
     */
    public function save()
    {
        if(!$this->getId())
        {
            $this->set(FestDB::COL_CREATED, time());
            $this->set(FestDB::COL_CREATED_BY, Auth::getActiveUserId());
        }
        parent::save();
    }// save


    /**
     * Get current fest item
     *
     * @since 27. February 2014, v. 1.00
     * @return Item|null Item if defined, null otherwise
     */
    public function getCurrentItem()
    {
        $mxdCurrentItem = null;
        $intCurrentItem = $this->get(FestDB::COL_CURRENT_ITEM);
        if($intCurrentItem)
        {
            $mxdCurrentItem = new Item($intCurrentItem);
        }
        return $mxdCurrentItem;
    }// getCurrentItem


    /**
     * Get next item
     *
     * @since 29. February 2014, v. 1.00
     * @return Item|null Item if exists, null otherwise
     */
    public function getNextItem()
    {
        $objNextItem = null;
        $intCurrentItem = $this->get(FestDB::COL_CURRENT_ITEM);
        $aryItems = $this->getItems();
        if(count($aryItems))
        {
            if($intCurrentItem)
            {
                $objCurrent = reset($aryItems);
                while($objNextItem === null)
                {
                    if($intCurrentItem == $objCurrent->getId())
                    {
                        $objNextItem = next($aryItems);
                        break;
                    }
                    else
                    {
                        $objCurrent = next($aryItems);
                    }
                }
                if(end($aryItems) == $intCurrentItem)
                {
                    $objNextItem = null;
                }
            }
        }

        return $objNextItem;
    }// getNextItem


    /**
     * Set current item
     *
     * @param Item $objItem Item object
     *
     * @since 29. February 2014, v. 1.00
     * @return void
     */
    public function setCurrentItem($objItem)
    {
        $this->set(FestDB::COL_CURRENT_ITEM, $objItem->getId());
        $this->set(FestDB::COL_VOTING, addslashes($this->get(FestDB::COL_VOTING)));
        $this->save();
    }// setCurrentItem


    /**
     * Get result as html
     *
     * @since 29. February 2014, v. 1.00
     * @return string Result as html
     */
    public function getResultAsHtml()
    {
        $objResult = new Result($this);
        return $objResult->getHtml();
    }// getResultAsHtml


}// Fest