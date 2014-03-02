<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;
use Beerfest\Core\Form\Range;

class RangeSlider extends FormElement
{
    private $intMin = 1;
    private $intMax = 10;
    private $objLow;
    private $objHigh;

    /**
     * Constructor
     *
     * @param string $strName Range slider name
     * @param string $strLabel Range slider label
     *
     * @since 27. February 2014, v. 1.00
     */
    public function __construct($strName, $strLabel)
    {
        parent::__construct(FormElement::ELEMENT_RANGE, $strName, $strLabel);
        $this->objLow = new Range($strName . '[low]', $strLabel);
        $this->objHigh = new Range($strName . '[high]', $strLabel);
        return $this;
    }// __construct


    /**
     * Set range
     *
     * @param integer $intMin Minimum value
     * @param integer $intMax Maximum value
     *
     * @since 27. February 2014, v. 1.00
     * @return void
     */
    public function setRange($intMin, $intMax)
    {
        if(is_numeric($intMin))
        {
            $this->intMin = $intMin;
        }
        if(is_numeric($intMax))
        {
            $this->intMax = $intMax;
        }
    }// setRange


    /**
     * Set range slider value
     *
     * @param mixed $mxdValue Range slider value
     *
     * @since 27. February 2014, v. 1.00
     * @return void
     */
    public function setValue($mxdValue)
    {
        if(is_array($mxdValue))
        {
            $intLow = $mxdValue['low'];
            $intHigh = $mxdValue['high'];
        }
        else
        {
            list($intLow, $intHigh) = explode(';', $mxdValue);
        }
        $this->getLow()->setValue($intLow);
        $this->getHigh()->setValue($intHigh);
    }// setValue


    /**
     * Get low range object
     *
     * @since 27. February 2014, v. 1.00
     * @return Range
     */
    private function getLow()
    {
        return $this->objLow;
    }// getLow


    /**
     * Get high range object
     *
     * @since 27. February 2014, v. 1.00
     * @return Range
     */
    private function getHigh()
    {
        return $this->objHigh;
    }// getHigh


    /**
     * Get minimum value
     *
     * @since 27. February 2014, v. 1.00
     * @return integer Minimum value
     */
    private function getMin()
    {
        return $this->intMin;
    }// getMin


    /**
     * Get minimum value as html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Minimum value as html
     */
    private function getMinAsHtml()
    {
        return ' min="' . $this->getMin() . '"';
    }// getMinAsHtml


    /**
     * Get maximum value
     *
     * @since 27. February 2014, v. 1.00
     * @return integer Maximum value
     */
    private function getMax()
    {
        return $this->intMax;
    }// getMax


    /**
     * Get maximum values as html
     *
     * @since 27. February 2014, v. 1.00
     * @return string
     */
    private function getMaxAsHtml()
    {
        return ' max="' . $this->getMax() . '"';
    }// getMaxAtHtml


    /**
     * Get range slider value
     *
     * @since 27. February 2014, v. 1.00
     * @return string Range slider value semicolon separated
     */
    public function getValue()
    {
        return $this->getLow()->getValue() . ';' . $this->getHigh()->getValue();
    }// getValue


    /**
     * Validate range slider posted value
     *
     * @param array $aryData Posted data
     *
     * @since 27. February 2014, v. 1.00
     * @return boolean True if valid, false if not
     */
    public function validate($aryData)
    {
        if(isset($aryData['low']))
        {
            $this->getLow()->setValue($aryData['low']);
        }
        if(isset($aryData['high']))
        {
            $this->getHigh()->setValue($aryData['high']);
        }
        return true;
    }// validate


    /**
     * Get range slider as html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Range slider as html
     */
    public function getHtml()
    {
        $strHtml = $this->getLabelAsHtml();
        $strHtml .= '<div data-role="rangeslider">';
        $strHtml .= '<input type="' . $this->objLow->getType() . '" name="' . $this->objLow->getName() . '"' . $this->objLow->getValueHtml() . $this->getMinAsHtml() . $this->getMaxAsHtml() . ' />';
        $strHtml .= '<input type="' . $this->objHigh->getType() . '" name="' . $this->objHigh->getName() . '"' . $this->objHigh->getValueHtml() . $this->getMinAsHtml() . $this->getMaxAsHtml() . ' />';
        $strHtml .= '</div>';
        return $strHtml;
    }// getHtml


}// RangeSlider