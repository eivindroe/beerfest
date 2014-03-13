<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\Element;
use Beerfest\Core\Form\Text;
use Beerfest\Core\Form\Textarea;
use Beerfest\Core\Form\Select;
use Beerfest\Core\Form\Button;
use Beerfest\Core\Form\File;
use Beerfest\Core\Request;

abstract class Controller
{
    /**
     * Form name
     * @var string
     */
    private $strName;

    /**
     * Form submit method (POST | GET)
     * @var string
     */
    private $strMethod;

    /**
     * Form submit action
     * @var string
     */
    private $strAction;

    /**
     * Form elements
     * @var array
     */
    private $aryElements = array();


    /**
     * Form errors
     * @var array
     */
    private $aryError = array();


    /**
     * Filtered posted data
     * @var array
     */
    private $aryPostData;


    /**
     * Form referer (posted)
     * @var string
     */
    private $strReferer;


    /**
     * Constructor
     *
     * @param string $strName Form name
     * @param string $strMethod Form submit method (POST | GET)
     * @param string $strAction Form submit action
     *
     * @since 25. February 2014, 1.00
     */
    public function __construct($strName, $strMethod = 'post', $strAction = '')
    {
        $this->strName = $strName;
        $this->strMethod = $strMethod;
        $this->strAction = '/roemedia/beerfest/' . $strAction;
        $this->setReferer(Request::getReferer());
    }// __construct


    /**
     * Get form name
     *
     * @since 22. February 2014, v. 1.00
     * @return string Form name
     */
    private function getName()
    {
        return $this->strName;
    }// getName


    /**
     * Get form method (POST | GET)
     *
     * @since 22. February 2014, v. 1.00
     * @return string Form method
     */
    private function getMethod()
    {
        return $this->strMethod;
    }// getMethod


    /**
     * Get form action (url)
     *
     * @since 22. February 2014, v. 1.00
     * @return string Action
     */
    private function getAction()
    {
        return $this->strAction;
    }// getAction


    /**
     * Add element to form
     *
     * @param FormElement $objElement Element object
     *
     * @since 22. February 2014, v. 1.00
     * @return mixed
     */
    public function addElement($objElement)
    {
        $this->aryElements[$objElement->getName()] = $objElement;
        return $objElement;
    }// addElement


    /**
     * Get element
     * @param string $strName Element name
     * @return FormElement|null
     */
    private function getElement($strName)
    {
        $objElement = null;
        if(isset($this->aryElements[$strName]))
        {
            $objElement = $this->aryElements[$strName];
        }
        return $objElement;
    }// getElement


    /**
     * Add hidden field
     *
     * @param string $strName Field name
     *
     * @since 25. February 2014, v. 1.00
     * @return Hidden
     */
    public function addHiddenField($strName)
    {
        return $this->addElement(new Hidden($strName));
    }// addHiddenField


    /**
     * Add text field to form
     *
     * @param string $strName Text field name
     * @param string $strLabel Text field label
     *
     * @since 25. February 2014, v. 1.00
     * @return Text
     */
    public function addTextField($strName, $strLabel)
    {
        return $this->addElement(new Text($strName, $strLabel));
    }// addTextField


    /**
     * Add password to form
     *
     * @param string $strName Password name
     * @param string $strLabel Password label
     *
     * @since 25. February 2014, v. 1.00
     * @return Password
     */
    public function addPassword($strName, $strLabel)
    {
        return $this->addElement(new Password($strName, $strLabel));
    }// addPasswordField


    /**
     * Add textarea to form
     *
     * @param string $strName Textarea name
     * @param string $strLabel Textarea label
     * @param null|integer $intMaxLength Textarea maxlength
     *
     * @since 25. February 2014, v. 1.00
     *
     * @return TextArea
     */
    public function addTextArea($strName, $strLabel, $intMaxLength = null)
    {
        return $this->addElement(new TextArea($strName, $strLabel, $intMaxLength));
    }// addTextArea


    /**
     * Add textfield to form
     *
     * @param string $strName Select field name
     * @param string $strLabel Select field label
     *
     * @since 25. February 2014, v. 1.00
     * @return Select
     */
    public function addSelectField($strName, $strLabel)
    {
        return $this->addElement(new Select($strName, $strLabel));
    }// addSelectField


    /**
     * Add range field to form
     *
     * @param string $strName Range field name
     * @param string $strLabel Range field label
     *
     * @since 25. February 2014, v. 1.00
     * @return Range
     */
    public function addRangeField($strName, $strLabel)
    {
        return $this->addElement(new Range($strName, $strLabel));
    }// addRangeField


    /**
     * Add range slider field to form
     *
     * @param string $strName Range field name
     * @param string $strLabel Range field label
     *
     * @since 25. February 2014, v. 1.00
     * @return RangeSlider
     */
    public function addRangeSliderField($strName, $strLabel)
    {
        return $this->addElement(new RangeSlider($strName, $strLabel));
    }// addRangeSliderField


    /**
     * Add file form field
     *
     * @param string $strName File field name
     * @param string $strLabel File field label
     *
     * @since 02. March 2014, v. 1.00
     * @return File
     */
    public function addFileField($strName, $strLabel)
    {
        return $this->addElement(new File($strName, $strLabel));
    }// addFileField


    /**
     * Add button submit
     *
     * @param string $strLabel Button label
     *
     * @since 25. February 2014, v. 1.00
     * @return Button
     */
    public function addButtonSubmit($strLabel = _SAVE)
    {
        return $this->addElement(new Button(Button::TYPE_SUBMIT, 'submit', $strLabel));
    }// addButtonSubmit


    /**
     * Add button reset
     *
     * @param string $strLabel Button label
     *
     * @since 25. February 2014, v. 1.00
     * @return Button
     */
    public function addButtonReset($strLabel = _RESET)
    {
        return $this->addElement(new Button(Button::TYPE_RESET, 'reset', $strLabel));
    }// addButtonReset


    /**
     * Add button cancel
     *
     * @param string $strLabel Button label
     *
     * @since 25. February 2014, v. 1.00
     * @return Button
     */
    public function addButtonCancel($strLabel = _CANCEL)
    {
        return $this->addElement(new Button(Button::TYPE_BUTTON, 'cancel', $strLabel));
    }// addButtonCancel


    /**
     * Set form defaults
     *
     * @param array $aryDefaults Form defaults
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    protected function setDefaults($aryDefaults)
    {
        $aryElements = $this->getElements();
        foreach($aryDefaults as $strKey => $strValue)
        {
            if(isset($aryElements[$strKey]))
            {
                $objElement = $aryElements[$strKey];
                $objElement->setValue($strValue);
            }
        }
    }// setDefaults


    /**
     * Get form elements
     *
     * @since 25. February 2014, v. 1.00
     * @return array Elements array
     */
    private function getElements()
    {
        return $this->aryElements;
    }// getElements


    /**
     * Get form html
     *
     * @since 22. February 2014, v. 1.00
     * @return string Form html
     */
    public function getHtml()
    {
        $strHtml = '<form data-ajax="false" name="' . $this->getName() . '" method="' . $this->getMethod() . '" action="' . $this->getAction() . '">';
        foreach($this->getElements() as $objElement)
        {
            if($objElement->isSkipFieldContain())
            {
                $strHtml .= $objElement->getHtml();
            }
            else
            {
                $strHtml .= '<div class="ui-field-contain">' . $objElement->getHtml() . '</div>';
            }
        }
        $strHtml .= '</form>';
        return $strHtml;
    }// getHtml


    /**
     * Validate form against defined data
     *
     * @param array $aryPost Data to validate
     *
     * @since 25. February 2014, v. 1.00
     * @return boolean True if valid, false if not
     */
    public function validate($aryPost)
    {
        $blnValid = true;
        $aryValidPost = array();
        foreach($aryPost as $strKey => $strValue)
        {
            $objElement = $this->getElement($strKey);
            if(is_object($objElement))
            {
                if($objElement->validate($strValue))
                {
                    $objElement->setValue($strValue);
                    $aryValidPost[$strKey] = $objElement->getValue();
                }
                else
                {
                    $blnValid = false;
                    $this->setError($strKey, $objElement->getError());
                }
            }
        }

        if($blnValid)
        {
            $this->setReferer($aryPost['referer']);
            $this->setPostData($aryValidPost);
        }

        return $blnValid;
    }// validate


    /**
     * Set form referer
     *
     * @param string $strReferer Form referer
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    private function setReferer($strReferer)
    {
        $objHidden = $this->addHiddenField('referer', '');
        $objHidden->setValue($strReferer);
        $this->strReferer = $strReferer;
    }// setReferer


    /**
     * Get form referer
     *
     * @since 25. February 2014, v. 1.00
     * @return string Form referer
     */
    public function getReferer()
    {
        return $this->strReferer;
    }// getReferer


    /**
     * Set filtered post data
     *
     * @param array $aryPost Data
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    private function setPostData($aryPost)
    {
        unset($aryPost['referer']);
        unset($aryPost[FormElement::ELEMENT_BUTTON]);
        unset($aryPost[FormElement::ELEMENT_RESET]);
        unset($aryPost[FormElement::ELEMENT_SUBMIT]);
        unset($aryPost[FormElement::ELEMENT_SUBMIT]);
        foreach($aryPost as $strKey => $strValue)
        {
            $objElement = $this->getElement($strKey);
            if(is_object($objElement))
            {
                if($objElement->validate($strValue))
                {
                    $objElement->setValue($strValue);
                    $aryPost[$strKey] = $objElement->getValue();
                }
            }
        }
        $this->aryPostData = $aryPost;
    }// setPostData


    /**
     * Get posted data
     *
     * @since 25. February 2014, v. 1.00
     * @return array Filtered posted data
     */
    public function getPostData()
    {
        return $this->aryPostData;
    }// getPostData


    /**
     * Set element error to form errors
     *
     * @param string $strKey Element key
     * @param string $strError Element error
     *
     * @since 25. February 2014, v. 1.00
     * @return string Error given
     */
    protected function setError($strKey, $strError)
    {
        return $this->aryError[$strKey] = $strError;
    }// setError


    /**
     * Get form errors
     *
     * @since 25. February 2014, v. 1.00
     * @return array Form errors
     */
    public function getErrors()
    {
        return $this->aryError;
    }// getErrors


}// Controller