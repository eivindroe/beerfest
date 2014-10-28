<?php
namespace Beerfest\Fest;

use Beerfest\GenericObject;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;

class Items extends GenericObject
{
    /**
     * Database object
     * @var ItemDB
     */
    private $objDb;


    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->objDb = new ItemDB();
    }// __construct


    /**
     * Get database object
     *
     * @since 22. February 2014, v. 1.00
     * @return ItemDB
     */
    private function getDb()
    {
        return $this->objDb;
    }// getDb


    /**
     * Get fests
     *
     * @param string $strWhere Where clause
     *
     * @since 22. February 2014, v. 1.00
     * @return array Fests
     */
    public function getItems($strWhere = '')
    {
        $objDb = $this->getDb();
        $aryItems = array();
        $aryResult = $this->getDb()->select(array_keys($objDb->getTableColumns()), $strWhere, ItemDB::COL_INDEX);
        foreach($aryResult as $aryRow)
        {
            $intId = $aryRow[$objDb::COL_ID];
            $aryItems[$intId] = new Item($intId);
        }
        return $aryItems;
    }// getItems


}// Items