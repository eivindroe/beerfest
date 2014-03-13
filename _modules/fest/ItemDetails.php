<?php
namespace Beerfest\Fest\Item;

use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\Vote\Votes;


class Details
{
    /**
     * Item object
     * @var Item
     */
    private $objItem;


    /**
     * Constructor
     *
     * @param Item $objItem Item object
     *
     * @since 23. February 2014, v. 1.00
     */
    public function __construct(Item $objItem)
    {
        $this->objItem = $objItem;
    }// __construct


    /**
     * Get fest item
     *
     * @since 22. February 2014, v. 1.00
     * @return Item
     */
    private function getItem()
    {
        return $this->objItem;
    }// getItem


    /**
     * Get fest item as html
     *
     * @since 22. February 2014, v. 100
     * @return string Fest item as html
     */
    public function getAsHtml()
    {
        $objItem = $this->getItem();

        $strHtml = '<h1>' . $objItem->get(ItemDB::COL_NAME) . '</h1><p>' . _ITEM_DESCRIPTION . ': ' .
            $objItem->get(ItemDB::COL_DESC) . '</p><br />';

        $objVotes = $objItem->getVotes();

        if($objVotes)
        {
            $strHtml .= $objVotes->getAsListHtml();
        }

        return $strHtml;
    }// getAsHtml


}// Details