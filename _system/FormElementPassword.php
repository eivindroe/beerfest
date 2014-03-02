<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;

class Password extends FormElement
{
    /**
     * Constructor
     *
     * @param string $strName Password field name
     * @param string $strLabel Password field label
     *
     * @since 25. February 2014, v. 1.00
     * @return Password
     */
    public function __construct($strName = '', $strLabel = '')
    {
        parent::__construct(FormElement::ELEMENT_PASSWORD, $strName, $strLabel);
        return $this;
    }// __construct


    /**
     * Get password field html
     *
     * @since 25. February 2014, v. 1.00
     * @return string
     */
    public function getHtml()
    {
        return $this->getLabelAsHtml() . '<input type="password" name="' . $this->getName() . '"' . $this->getValueHtml() .
        $this->getPlaceholderHtml() . $this->getAttributesHtml() . ' />';
    }// getHtml


}// Password