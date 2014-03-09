<?php
namespace Beerfest\Fest\Item;

use Beerfest\Core\Form\Controller;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;

class Form extends Controller
{
    /**
     * Fest object
     * @var Item
     */
    private $objItem;

    /**
     * Constructor
     *
     * @param Item $objItem Fest Item object
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct(Item $objItem)
    {
        $strAction = ($objItem->getId() ? $objItem->getCryptId() : 'add');
        parent::__construct('item', 'post', 'item:' . $strAction);
        $this->loadElements();
        if($objItem->getId())
        {
            $this->setDefaults($objItem->getAll());
        }
        $this->objItem = $objItem;
    }// __construct


    /**
     * Load item form elements
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadElements()
    {
        $objName = $this->addTextField(ItemDB::COL_NAME, _ITEM_NAME);
        $objName->setPlaceholder( _ITEM_NAME);
        $objName->setRequired(true);

        $objDesc = $this->addTextArea(ItemDB::COL_DESC, _ITEM_DESCRIPTION, 255);
        $objDesc->setPlaceholder(_ITEM_DESCRIPTION);

        $objRange = $this->addRangeSliderField(ItemDB::COL_RANGE, _ITEM_RANGE);
        $objRange->setRange(1, 10);

        $this->addHiddenField(ItemDB::COL_FEST_ID);

        $this->addButtonSubmit();
        $this->addButtonReset();
        $this->addButtonCancel();
    }// loadElements


}// Form