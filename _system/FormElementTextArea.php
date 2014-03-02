<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;

class TextArea extends FormElement
{
    /**
     * Textarea max length
     * @var integer
     */
    private $intMaxLength;


    /**
     * Constructor
     * @param string $strName Textarea name
     * @param string $strLabel Textarea label
     * @param null $intMaxLength Input maxlength
     *
     * @since 25. February 2014, v. 1.00
     */
    public function __construct($strName, $strLabel = '', $intMaxLength = null)
    {
        parent::__construct(FormElement::ELEMENT_TEXTAREA, $strName, $strLabel);
        $this->intMaxLength = $intMaxLength;
        return $this;
    }// __construct


    /**
     * Get textarea maxlength
     *
     * @since 25. February 2014, v. 1.00
     * @return integer|null Textarea maxlength
     */
    private function getMaxLength()
    {
        return $this->intMaxLength;
    }// getMaxLength


    /**
     * Get textarea maxlength as html
     *
     * @since 25. February 2014, v. 1.00
     * @return string Textarea max length attribute as html
     */
    private function getMaxLengthAsHtml()
    {
        $strHtml = '';
        if($intMaxLength = $this->getMaxLength())
        {
            $strHtml = ' maxlength="' . $intMaxLength . '"';
        }
        return $strHtml;
    }// getMaxLengthAsHtml


    /**
     * Get textarea html
     *
     * @since 25. February 2014, v. 100
     * @return string Textarea html
     */
    public function getHtml()
    {
        $strHtml = $this->getLabelAsHtml() .
            '<textarea name="' . $this->getName() . '"' . $this->getMaxLengthAsHtml() . '>' . $this->getValue() . '</textarea>';
        return $strHtml;
    }// getHtml


}// TextArea