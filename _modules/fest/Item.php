<?php
namespace Beerfest\Fest\Item;

use Beerfest\GenericObject;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Item\Vote\Votes;

class Item extends GenericObject
{
    /**
     * Item votes object
     * @var Votes
     */
    private $objVotes = null;

    /**
     * Constructor
     *
     * @param null|string $mxdId Item id
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct($mxdId = null)
    {
        parent::__construct(new ItemDB(), $mxdId);
    }// __construct


    /**
     * Get item range as array
     *
     * @since 28. February 2014, v. 1.00
     * @return array Range as array (0 => min, 1 => max)
     */
    public function getRangeAsArray()
    {
        return explode(';', $this->get(ItemDB::COL_RANGE));
    }// getRangeAsArray


    /**
     * Get item votes
     *
     * @since 29. February 2014, v. 1.00
     * @return Votes
     */
    public function getVotes()
    {
        if(!isset($this->objVotes))
        {
            $this->objVotes = new Votes($this);
        }
        return $this->objVotes;
    }// getVotes


    public function getVotesForChart()
    {
        $strVotes = '';
        $objVotes = $this->getVotes();
        $aryValues = $objVotes->getVoteValues();

        return implode(', ', $aryValues);
    }// getVotesForChart


    /**
     * Get item name
     *
     * @since 30. February 2014. v. 1.00
     * @return string Item name
     */
    public function getName()
    {
        return $this->get(ItemDB::COL_NAME);
    }// getName


}// Item