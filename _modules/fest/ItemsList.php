<?php
namespace Beerfest\Fest\Item;

use Beerfest\Core\HtmlList\HtmlList;

use Beerfest\Fest\Fest;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Core\Form\Select;

class ItemsList extends HtmlList
{
    /**
     * Custom columns
     * @var string
     */
    const COL_ACTIVE = 'active';

    /**
     * Fest object
     * @var Fest
     */
    private $objFest;

    /**
     * Fest items
     * @var array
     */
    private $aryItems;


    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct(Fest $objFest)
    {
        $this->objFest = $objFest;
        parent::__construct('Item', _ITEM_LIST);
    }// __construct


    /**
     * Get fest object
     *
     * @since 29. February 2014, v. 1.00
     * @return Fest
     */
    private function getFest()
    {
        return $this->objFest;
    }// getFest


    /**
     * Add button new - custom implementation
     *
     * @since 27. February 2014, v. 1.00
     * @return \Beerfest\Core\Button|null Button if admin, null otherwise
     */
    public function addButtonNew()
    {
        $objUser = \Beerfest\Core\Auth::getActiveUser();
        $objButton = null;
        if($objUser->isAdmin() || $objUser->getId() == $this->getFest()->get(FestDB::COL_CREATED_BY))
        {
            $objButton = parent::addButtonNew('Item');
            $objButton->setAttributes(array('data-id' => $this->getFest()->getCryptId()));
        }
        return $objButton;
    }// addButtonNew


    /**
     * Get list items
     *
     * @since 22. February 2014, v. 100
     * @return array List items
     */
    private function getItems()
    {
        if(!isset($this->aryItems))
        {
            $this->aryItems = $this->getFest()->getItems();
        }
        return $this->aryItems;
    }// getItems


    /**
     * Load list columns
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadColumns()
    {
        $objActive = $this->addColumn(self::COL_ACTIVE, _ITEM_ACTIVE);
        $objActive->setAlignment($objActive::ALIGN_CENTER);
        $this->addColumn(ItemDB::COL_NAME, _ITEM_NAME);
        $objDescription = $this->addColumn(ItemDB::COL_DESC, _ITEM_DESCRIPTION);
        $objDescription->setPriority(2);
        $objRange = $this->addColumn(ItemDB::COL_RANGE, _ITEM_RANGE);
        $objRange->setPriority(3);
    }// loadColumns


    /**
     * Load list content
     *
     * @since 22. February 2014, v. 100
     * @return void
     */
    public function loadContent()
    {
        $objFest = $this->getFest();
        $this->addButtonNew();
        $aryItems = $this->getItems();
        $objCurrentUser = \Beerfest\Core\Auth::getActiveUser();
        $intCurrentItem = $objFest->getCurrentItem()->getId();
        if(count($aryItems))
        {
            foreach($aryItems as $intKey => $objItem)
            {
                $aryItem = $objItem->getAll();
                $strId = $objItem->getCryptId();
                $objSelect = new Select();
                $objSelect->setAttributes(array('data-role' => 'slider', 'data-fest' => $objFest->getCryptId(), 'data-item' => $strId, 'data-module' => 'Item', 'data-mini' => true, 'class' => 'toggle'));
                $objSelect->addOption(0, _NO);
                $objSelect->addOption(1, _YES);
                if($intCurrentItem == $objItem->getId())
                {
                    $objSelect->setSelected(1);
                    $objSelect->setDisabled(true);
                }
                $aryItem[self::COL_ACTIVE] = $objSelect->getHtml();
                $objRow = $this->addRow($intKey, $aryItem);
                if($objCurrentUser->isAdmin())
                {
                    $objRow->setEdit($strId);
                    $objRow->setDelete($strId);
                }
                $objRow->setId($strId);
            }
        }
    }// loadContent


}// ItemsList