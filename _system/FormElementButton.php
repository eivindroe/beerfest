<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;

class Button extends FormElement
{
    /**
     * Button type
     * @var string
     */
    const TYPE_SUBMIT = 'submit';
    const TYPE_RESET = 'reset';
    const TYPE_BUTTON = 'button';

    /**
     * Constructor
     *
     * @param string $strType
     * @param string $strName
     * @param string $strLabel
     *
     * $since 25. February 2014, v. 1.00
     */
    public function __construct($strType = self::TYPE_BUTTON, $strName = '', $strLabel = '')
    {
        parent::__construct($strType, $strName, $strLabel);
        $this->setValue($strLabel);
        return $this;
    }// __construct


    /**
     * Get button html
     *
     * @since 25. February 2014, v. 1.00
     * @return string Button html
     */
    public function getHtml()
    {
        if($this->getType() == self::TYPE_BUTTON)
        {
            $strButton = '<button data-inline="true"' . $this->getAttributesHtml() . '>' . $this->getValue() . '</button>';
        }
        else
        {
            $strButton = '<input type="' . $this->getType() . '" data-inline="true"' . $this->getValueHtml() . ' />';
        }
        return $strButton;
    }// getHtml


}// Button