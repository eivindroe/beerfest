<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;

class Range extends FormElement
{
    /**
     * Range minimum
     * @var integer
     */
    private $intMin = 1;

    /**
     * Range maximum
     * @var integer
     */
    private $intMax = 1;


    /**
     * Range step
     * @var integer
     */
    private $intStep = 1;

    /**
     * Constructor
     *
     * @param string $strName Range element name
     * @param string $strLabel Range element label
     *
     * @since 27. February 2014, v. 1.00
     */
    public function __construct($strName = '', $strLabel = '')
    {
        parent::__construct(FormElement::ELEMENT_RANGE, $strName, $strLabel);
        return $this;
    }// __construct


    /**
     * Set element range
     *
     * @param integer $intMin Range minimum value
     * @param integer $intMax Range maximum value
     *
     * @since 27. February 2014, v. 1.00
     * @return Range
     */
    public function setRange($intMin, $intMax)
    {
        if(is_numeric($intMin))
        {
            $this->intMin = $intMin;
        }
        if(is_numeric(($intMax)))
        {
            $this->intMax = $intMax;
        }
        return $this;
    }// setRange


    /**
     * Get range minimum value
     *
     * @since 27. February 2014, v. 1.00
     * @return integer Range minimum value
     */
    private function getMin()
    {
        return $this->intMin;
    }// getMin


    /**
     * Get range minimum value as html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Range minimum value as html
     */
    private function getMinAsHtml()
    {
        return ' min="' . $this->getMin() . '"';
    }// getMinAsHtml


    /**
     * Get range maximum value
     *
     * @since 27. February 2014, v. 1.00
     * @return integer Range maximum value
     */
    private function getMax()
    {
        return $this->intMax;
    }// getMax


    /**
     * Get range maximum value as html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Range maximum value as html
     */
    private function getMaxAsHtml()
    {
        return ' max="' . $this->getMax() . '"';
    }// getMaxAsHtml


    /**
     * Set range step
     *
     * @param integer $intStep Range step
     *
     * @since 27. February 2014, v. 1.00
     * @return Range
     */
    public function setStep($intStep)
    {
        if(is_numeric($intStep))
        {
            $this->intStep = $intStep;
        }
        return $this;
    }// setStep


    /**
     * Get range step
     *
     * @since 27. February 2014, v. 1.00
     * @return integer Range step
     */
    private function getStep()
    {
        return $this->intStep;
    }// getStep


    /**
     * Get range step as html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Range step as html
     */
    private function getStepAsHtml()
    {
        return ' step="' . $this->getStep() . '"';
    }// getStepAsHtml


    /**
     * Get range element html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Range element html
     */
    public function getHtml()
    {
        return $this->getLabelAsHtml() . '<input type="range" name="' . $this->getName() . '"' . $this->getValueHtml() . $this->getPlaceholderHtml() .
        $this->getMinAsHtml() . $this->getMaxAsHtml() . $this->getStepAsHtml() . $this->getAttributesHtml() . '" />';
    }// getHtml


}// Range