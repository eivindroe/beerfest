<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;

class File extends FormElement
{
    /**
     * Constructor
     *
     * @param string $strName File field name
     * @param string $strLabel File field label
     *
     * @since 02. March 2014, v. 1.00
     * @return File
     */
    public function __construct($strName, $strLabel)
    {
        parent::__construct(FormElement::ELEMENT_FILE, $strName, $strLabel);
        return $this;
    }// __construct


    /**
     * Get file form field as html
     *
     * @since 02. March 2014, v. 1.00
     * @return string File form field as html
     */
    public function getHtml()
    {
        return $this->getLabelAsHtml() . '<input type="file" name="' . $this->getName() . '" />';
    }// getHtml


}// File