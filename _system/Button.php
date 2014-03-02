<?php
namespace Beerfest\Core;

class Button
{
    /**
     * Button key
     * @var string
     */
    private $strKey;

    /**
     * Button label
     * @var string
     */
    private $strLabel;

    /**
     * Button attributes
     * @var array
     */
    private $aryAttributes = array('data-mini' => 'true');


    /**
     * Constructor
     *
     * @param string $strKey Button key
     * @param string $strLabel Button label
     *
     * @since 25. February 2014, 1.00
     */
    public function __construct($strKey, $strLabel)
    {
        $this->strKey = $strKey;
        $this->strLabel = $strLabel;
    }// __construct


    /**
     * Get button label
     *
     * @since 25. February 2014, 1.00
     * @return string Button label
     */
    private function getLabel()
    {
        return $this->strLabel;
    }// getLabel


    /**
     * Set button attributes
     *
     * @param array $aryAttributes Button attributes
     *
     * @since 25. February 2014, 1.00
     * @return array Button attributes
     */
    public function setAttributes($aryAttributes)
    {
        if(is_array($aryAttributes))
        {
            if(count($this->aryAttributes))
            {
                $this->aryAttributes = array_merge($this->aryAttributes, $aryAttributes);
            }
            else
            {
                $this->aryAttributes = $aryAttributes;
            }
        }
        return $this->aryAttributes;
    }// setAttributes


    /**
     * Get button attributes
     *
     * @since 25. February 2014, 1.00
     * @return array Button attributes
     */
    private function getAttributes()
    {
        $aryAttributes = $this->aryAttributes;
        return $aryAttributes;
    }// getAttributes


    /**
     * Remove button attribute
     *
     * @param string $strAttr Button attribute
     *
     * @since 25. February 2014, 1.00
     * @return void
     */
    private function removeAttribute($strAttr)
    {
        unset($this->aryAttributes[$strAttr]);
    }// removeAttribute


    /**
     * Get button attributes as html
     *
     * @since 25. February 2014, v. 1.00
     * @return string Button attributes as html
     */
    private function getAttributesHtml()
    {
        $strHtml = '';
        $aryAttributes = $this->getAttributes();
        if(count($aryAttributes))
        {
            foreach($aryAttributes as $strKey => $strValue)
            {
                $strHtml .= ' ' . $strKey . '="' . $strValue . '"';
            }
        }
        return $strHtml;
    }// getAttributesHtml


    /**
     * Set button inline
     *
     * @param boolean $blnInline True if inline, false if not
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    public function setInline($blnInline)
    {
        if(is_bool($blnInline))
        {
            $strInline = ($blnInline ? 'true' : 'false');
            $this->removeAttribute('data-inline');
            $this->setAttributes(array('data-inline' => $strInline));
        }
    }// setInline


    /**
     * Set use ajax
     *
     * @param boolean $blnAjax True if use ajax, false if not
     *
     * @since 25. February 2014, 1.00
     * @return void
     */
    public function setAjax($blnAjax)
    {
        if(is_bool($blnAjax))
        {
            $strAjax = ($blnAjax ? 'true' : 'false');
            $this->removeAttribute('data-ajax');
            $this->setAttributes(array('data-ajax' => $strAjax));
        }
    }// setAjax


    /**
     * Get button html
     *
     * @since 25. February 2014, 1.00
     * @return string Button html
     */
    public function getHtml()
    {
        $strHtml = '<a data-role="button"' . $this->getAttributesHtml() . '>' . $this->getLabel()  . '</a>';
        return $strHtml;
    }// getHtml


}// ListButton