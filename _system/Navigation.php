<?php
namespace Beerfest\Core;

abstract class Navigation
{
    private $aryItems = array();

    private function getItems()
    {
        return $this->aryItems;
    }// getItems

    public function addItem($strKey, $strLabel, $strPath)
    {
        $aryItem = new NavigationElement($strLabel, $strPath);
        $this->aryItems[$strKey] = $aryItem;
        return $aryItem;
    }// addItem

    public function getHtml()
    {
        $strHtml = '<div data-role="navbar" data-grid="d"><ul>';
        $aryItems = $this->getItems();

        if(count($aryItems))
        {
            foreach($aryItems as $objItem)
            {
                $strHtml .= '<li>' . $objItem->getHtml() . '</li>';
            }
        }
        $strHtml .= '</ul></div>';
        return $strHtml;
    }// getAsHtml


}// Navigation