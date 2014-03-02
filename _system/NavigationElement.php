<?php
namespace Beerfest\Core;

class NavigationElement
{
    /**
     * Navigation constants
     */
    const STR_LABEL = 'label';
    const STR_PATH = 'path';

    private $strLabel;
    private $strPath;

    private $blnAjax = false;

    public function __construct($strLabel, $strPath)
    {
        $this->strLabel = $strLabel;
        $this->strPath = $strPath;
        return $this;
    }// __construct

    public function setAjax($blnAjax)
    {
        if(is_bool($blnAjax))
        {
            $this->blnAjax = $blnAjax;
        }
        return $this->blnAjax;
    }// setAjax

    private function getAjax()
    {
        return $this->blnAjax;
    }// getAjax

    private function getAjaxHtml()
    {
        return ' data-ajax="' . ($this->getAjax() ? 'true' : 'false') . '"';
    }// getAjaxHtml

    private function getLabel()
    {
        return $this->strLabel;
    }// getLabel

    private function getPath()
    {
        return $this->strPath;
    }// getPath

    public function getHtml()
    {
        $strHtml = '<a href="/roemedia/beerfest/' . $this->getPath() . '"' . $this->getAjaxHtml() . ' data-role="button">' . $this->getLabel() . '</a>';
        return $strHtml;
    }


}// NavigationElement