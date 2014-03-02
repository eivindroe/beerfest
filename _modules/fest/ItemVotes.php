<?php
namespace Beerfest\Fest\Item\Vote;

use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Item\Vote\Vote;
use Beerfest\Fest\Item\Vote\VoteDB;
use Beerfest\Core\HtmlList\HtmlList as ListController;
use Beerfest\User\Users;

class Votes
{
    /**
     * Vote db
     * @var VoteDB
     */
    private $objDb;


    /**
     * Item object
     * @var Item
     */
    private $objItem;


    /**
     * Votes collection
     * @var array
     */
    private $aryVotes;


    /**
     * Constructor
     *
     * @param Item $objItem Item object
     *
     * @since 27. February 2014, v. 1.00
     */
    public function __construct(Item $objItem)
    {
        $this->objDb = new VoteDB();
        $this->objItem = $objItem;
    }// __construct


    /**
     * Get database object
     *
     * @since 27. February 2014, v. 1.00
     * @return VoteDB
     */
    private function getDb()
    {
        return $this->objDb;
    }// getDb

    /**
     * Get fest item
     *
     * @since 27. February 2014, v. 1.00
     * @return Item
     */
    public function getItem()
    {
        return $this->objItem;
    }// getItem


    /**
     * Get item name
     *
     * @since 29. February 2014, v. 1.00
     * @return string Item name
     */
    public function getItemName()
    {
        $objItem = $this->getItem();
        return $objItem->get(ItemDB::COL_NAME);
    }// getItemName


    /**
     * Get item votes
     *
     * @since 29. February 2014, v. 1.00
     * @return array Item vote objects
     */
    public function getVotes()
    {
        if(!isset($this->aryVotes))
        {
            $aryVotes = array();
            $objDb = $this->getDb();
            $objItem = $this->getItem();
            $strWhere = sql_where($objDb::COL_FEST_ITEM_ID, $objItem->getId());

            $aryResult = $objDb->select(array_keys($objDb->getTableColumns()), $strWhere);
            if(count($aryResult))
            {
                foreach($aryResult as $aryRow)
                {
                    $intId = $aryRow[VoteDB::COL_ID];
                    $aryVotes[$intId] = new Vote(md5($intId));
                }
            }
            $this->aryVotes = $aryVotes;
        }

        return $this->aryVotes;
    }// getVotes


    /**
     * Get vote values
     *
     * @since 29. February 2014, v. 1.00
     * @return array Vote values
     */
    public function getVoteValues()
    {
        $aryVotes = $this->getVotes();

        $aryValues = array();
        if(count($aryVotes))
        {
            foreach($aryVotes as $objVote)
            {
                $aryValues[] = $objVote->getValue();
            }
        }
        return $aryValues;
    }


    /**
     * Get vote count
     *
     * @since 29. February 2014, v. 1.00
     * @return integer Vote count
     */
    public function getCount()
    {
        return count($this->getVotes());
    }// getCount


    /**
     * Get item average votes value
     *
     * @since 29. February 2014, v.1.00
     * @return integer Item average votes value
     */
    public function getAverageValue()
    {
        $intAverage = 0;
        $aryVotes = $this->getVotes();

        if(count($aryVotes))
        {
            $intTotalValue = 0;
            $intVotes = 0;
            foreach($aryVotes as $objVote)
            {
                $intVotes++;
                $intTotalValue += $objVote->getValue();
            }

            $intAverage = number_format(($intTotalValue / $intVotes), 1);
        }
        return $intAverage;
    }// getAverageValue


    /**
     * Get votes as list html
     *
     * @since 29. February 2014, v. 1.00
     * @return Vote list as html
     */
    public function getAsListHtml()
    {
        $strHtml = '';
        $aryVotes = $this->getVotes();

        if(count($aryVotes))
        {
            $objList = new ListController(_VOTES);
            $objList->addColumn('user', _VOTE_USER);
            $objValue = $objList->addColumn('value', _VOTE_VALUE);
            $objValue->setAlignment($objValue::ALIGN_CENTER);

            $objUsers = new \Beerfest\User\Users();
            $aryUserNames = $objUsers->getAllNames();

            foreach($aryVotes as $intKey => $objVote)
            {
                $aryRow = array('user' => $aryUserNames[$objVote->get(VoteDB::COL_USER_ID)], 'value' => $objVote->get(VoteDB::COL_VALUE));
                $objList->addRow($intKey, $aryRow);
            }
            $objAverage = $objList->addRow(0, array('user' => _ITEM_AVERAGE_VALUE, 'value' => $this->getAverageValue()));
            $objAverage->setHighlight(true);
            $strHtml = $objList->getListHtml();
        }

        return $strHtml;
    }// getVotes


    /**
     * Get item votes by fest and user
     *
     * @param integer $intUserId User id
     *
     * @since 27. February 2014, v. 1.00
     * @return null|Vote Vote if found, null otherwise
     */
    public function getVoteByUser($intUserId)
    {
        $objVote = null;
        $objDb = $this->getDb();
        $objItem = $this->getItem();
        $strWhere = sql_where($objDb::COL_FEST_ITEM_ID, $objItem->getId()) . ' AND ' .
            sql_where($objDb::COL_FEST_ID, $objItem->get(ItemDB::COL_FEST_ID)) . ' AND ' . sql_where($objDb::COL_USER_ID, $intUserId);
        $aryResult = $objDb->select(array($objDb::COL_ID), $strWhere);

        if(count($aryResult))
        {
            $objVote = new Vote(md5($aryResult[0][$objDb::COL_ID]));
        }
        return $objVote;
    }// getVoteByUser


}// Votes