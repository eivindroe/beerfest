<?php
namespace Beerfest\Fest\Item\Vote;

use Beerfest\Core\Form\Controller;
use Beerfest\Fest\Fest;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Item\Vote\VoteDB;
use Beerfest\Fest\Item\Vote\Votes;
use Beerfest\Fest\Item\Vote\Vote;
use Beerfest\User\User;

class Form extends Controller
{
    /**
     * Weighting
     * @var string
     */
    const COL_WEIGHTING = 'weighting';

    /**
     * Item object
     * @var Item
     */
    private $objItem;


    /**
     * Vote object
     * @var Vote
     */
    private $objVote;


    /**
     * Active user
     * @var User
     */
    private $objUser;


    /**
     * Fest connected to item
     * @var Fest
     */
    private $objFest;


    /**
     * Constructor
     *
     * @param Item $objItem Item object
     *
     * @since 27. February 2014, v. 1.00
     */
    public function __construct(Item $objItem)
    {
        $this->objItem = $objItem;

        parent::__construct('Vote', 'post', 'item:' . $objItem->getCryptId() . '/vote');
        $this->loadElements();
    }// __construct


    /**
     * Get active user
     *
     * @since 29. February 2014, v. 1.00
     * @return User
     */
    private function getActiveUser()
    {
        if(!isset($this->objUser))
        {
            $this->objUser = \Beerfest\Core\Auth::getActiveUser();
        }
        return $this->objUser;
    }// getActiveUser


    /**
     * Get vote
     *
     * @since 27. February 2014, v. 1.00
     * @return null|Vote
     */
    private function getVote()
    {
        if(!isset($this->objVote))
        {
            $objItem = $this->getItem();
            $objVotes = new Votes($objItem);
            $objUser = $this->getActiveUser();
            $objVote = $objVotes->getVoteByUser($objUser->getId());
            $this->objVote = $objVote;
        }
        return $this->objVote;
    }// getVote


    /**
     * Get item object
     *
     * @since 27. February 2014, v. 1.00
     * @return Item
     */
    private function getItem()
    {
        return $this->objItem;
    }// getItem


    /**
     * Get fest object
     *
     * @since 10. March 2014, v. 1.00
     * @return Fest
     */
    private function getFest()
    {
        if(!isset($this->objFest))
        {
            $this->objFest = new Fest($this->getItem()->get(ItemDB::COL_FEST_ID));;
        }

        return $this->objFest;
    }// getFest


    /**
     * Load form elements
     *
     * @since 27. February 2014, v. 1.00
     * @return void
     */
    public function loadElements()
    {
        $objItem = $this->getItem();
        $objVote = $this->getVote();

        $objFest = $this->getFest();
        $aryWeighting = $objFest->getWeighting();
        $aryWeightingObjects = array();
        if(count($aryWeighting))
        {
            $aryLabel = array(
                'color' => _ITEM_COLOR,
                'foam' => _ITEM_FOAM,
                'taste' => _ITEM_TASTE
            );
            foreach($aryWeighting as $strKey => $intValue)
            {
                $aryWeightingObjects[$strKey] = $this->addRangeField(self::COL_WEIGHTING . '_' .
                    $strKey, $aryLabel[$strKey])->setStep(0.1)->setRange(0, 10)->setAttributes(
                        array('data-highlight' => 'true', 'class' => 'weighting', 'data-weight' => $intValue));
            }
        }

        $objTotal = $this->addTextField(VoteDB::COL_VALUE, _VOTE_TOTAL)->setAttributes(array('id' => 'vote_total'))->setReadOnly(true);

        $objFestId = $this->addHiddenField(VoteDB::COL_FEST_ID);
        $objFestItem = $this->addHiddenField(VoteDB::COL_FEST_ITEM_ID);

        if($objVote)
        {
            $objFestId->setValue($objVote->get(VoteDB::COL_FEST_ID));
            $objFestItem->setValue($objVote->get(VoteDB::COL_FEST_ITEM_ID));
            $objTotal->setValue($objVote->get(VoteDB::COL_VALUE));

            // Set weighting defaults
            $aryVote = json_decode($objVote->get(VoteDB::COL_DETAILS), true);
            foreach($aryVote as $strKey => $aryValue)
            {
                $aryWeightingObjects[$strKey]->setValue($aryValue['value']);
            }
        }
        elseif($objItem)
        {
            $objFestId->setValue($objItem->get(ItemDB::COL_FEST_ID));
            $objFestItem->setValue($objItem->getId());
        }

        $this->addButtonSubmit(_VOTE);
        $this->addButtonReset();
        $this->addButtonCancel();
    }// loadElements


    /**
     * Save vote
     *
     * @since 29. February 2014, v. 1.00
     * @return integer Vote id
     */
    public function saveVote()
    {
        $objUser = $this->getActiveUser();
        $objVote = $this->getVote();

        if($objVote == null)
        {
            $objVote = new Vote();
            $objVote->set(VoteDB::COL_USER_ID, $objUser->getId());
        }

        $aryVote = $this->getPostData();
        $aryWeighting = $this->getFest()->getWeighting();
        $aryVoteDetails = array();
        $intVoteValue = 0;

        foreach($aryVote as $strKey => $mxdValue)
        {
            if(stristr($strKey, self::COL_WEIGHTING))
            {
                $strKey = str_replace(self::COL_WEIGHTING . '_', '', $strKey);
                if(isset($aryWeighting[$strKey]))
                {
                    $aryVoteDetails[$strKey]['value'] = $mxdValue;
                    $aryVoteDetails[$strKey]['weight'] = $aryWeighting[$strKey];
                    $intVoteValue += ($mxdValue * $aryWeighting[$strKey]);
                }
            }
            else
            {
                $objVote->set($strKey, $mxdValue);
            }
        }
        $objVote->set(VoteDB::COL_VALUE, $intVoteValue);
        $objVote->set(VoteDB::COL_DETAILS, addslashes(json_encode($aryVoteDetails)));
        return $objVote->save();
    }// saveVote


    /**
     * Load form elements
     *
     * @since 27. February 2014, v. 1.00
     * @return string Vote html
     */
    public function getHtml()
    {
        $objItem = $this->getItem();
        $objVote = $this->getVote();

        $strHtml = '';
        if($objItem)
        {
            $strHtml .= '<h1>' . $objItem->getName() . '</h1>';
        }
        if($objVote)
        {
            $strHtml .= '<p>'. _DATE_VOTED . ': ' . date('Y-m-d H:i', $objVote->get(VoteDB::COL_DATE)) . '</p>';
        }
        $strHtml .= parent::getHtml();

        return $strHtml;
    }// getHtml


}// Form