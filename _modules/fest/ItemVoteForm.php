<?php
namespace Beerfest\Fest\Item\Vote;

use Beerfest\Core\Form\Controller;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Item\Vote\VoteDB;
use Beerfest\Fest\Item\Vote\Votes;
use Beerfest\Fest\Item\Vote\Vote;

class Form extends Controller
{
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
     * @var \Beerfest\User\User
     */
    private $objUser;


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
     * @return \Beerfest\User\User
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
     * Load form elements
     *
     * @since 27. February 2014, v. 1.00
     * @return void
     */
    public function loadElements()
    {
        $objItem = $this->getItem();
        $objVote = $this->getVote();
        $aryRange = $objItem->getRangeAsArray();

        $objRange = $this->addRangeField(VoteDB::COL_VALUE, _VOTE_VALUE);
        $objRange->setRequired(true);
        $objRange->setRange($aryRange[0], $aryRange[1]);
        $objRange->setStep(0.1);
        $objRange->setAttributes(array('data-highlight' => 'true'));

        $objFestId = $this->addHiddenField(VoteDB::COL_FEST_ID);
        $objFestItem = $this->addHiddenField(VoteDB::COL_FEST_ITEM_ID);

        if($objVote)
        {
            $objRange->setValue($objVote->get(VoteDB::COL_VALUE));
            $objFestId->setValue($objVote->get(VoteDB::COL_FEST_ID));
            $objFestItem->setValue($objVote->get(VoteDB::COL_FEST_ITEM_ID));
        }
        elseif($objItem)
        {
            $objFestId->setValue($objItem->get(ItemDB::COL_FEST_ID));
            $objFestItem->setValue($objItem->getId());
        }

        $objSubmit = $this->addButtonSubmit(_VOTE);
        $objReset = $this->addButtonReset();
        $objCancel = $this->addButtonCancel();
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

        foreach($aryVote as $strKey => $strValue)
        {
            $objVote->set($strKey, $strValue);
        }
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
            $strHtml .= '<h1>' . $objItem->get(ItemDB::COL_NAME) . '</h1>';
        }
        if($objVote)
        {
            $strHtml .= '<p>'. _DATE_VOTED . ': ' . date('Y-m-d H:i', $objVote->get(VoteDB::COL_DATE)) . '</p>';
        }
        $strHtml .= parent::getHtml();

        return $strHtml;
    }// getHtml


}// Form