<?php
namespace Beerfest\Core\Form;

use Beerfest\Core\Form\FormElement;

class Select extends FormElement
{
    /**
     * Select options
     * @var array
     */
    private $aryOptions;


    /**
     * Selected option value
     * @var string
     */
    private $strSelected;


    /**
     * Constructor
     *
     * @param string $strName Select name
     * @param string $strLabel Select label
     *
     * @since 25. February 2014, v. 1.00
     */
    public function __construct($strName = '', $strLabel = '')
    {
        parent::__construct($this, $strName, $strLabel);
        return $this;
    }// __construct


    /**
     * Add select options
     *
     * @param array $aryOptions Select options
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    public function addOptions($aryOptions)
    {
        foreach($aryOptions as $strValue => $strName)
        {
            $this->aryOptions[$strValue] = $strName;
        }
    }// addOptions


    /**
     * Add single select option
     *
     * @param string $strValue Selext value
     * @param string $strName Selexct name
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    public function addOption($strValue, $strName)
    {
        $this->aryOptions[$strValue] = $strName;
    }// addOption


    /**
     * Get select options
     *
     * @since 25. February 2014, v. 1.00
     * @return array Select options
     */
    private function getOptions()
    {
        return $this->aryOptions;
    }// getOptions


    /**
     * Get select option by value
     *
     * @param string $strValue Select value
     *
     * @since 25. February 2014, v. 1.00
     * @return null|array Option as array if defined, null if not
     */
    private function getOption($strValue)
    {
        $aryOption = null;
        $aryOptions = $this->getOptions();
        if(isset($aryOptions[$strValue]))
        {
            $aryOption = $aryOptions[$strValue];
        }
        return $aryOption;
    }// getOption


    /**
     * Set selected value
     *
     * @param string $strValue Select value
     *
     * @since 25. February 2014, v. 1.00
     * @return string Select value
     */
    public function setSelected($strValue)
    {
        if($strValue && $this->getOption($strValue))
        {
            $this->strSelected = $strValue;
        }
        return $this->strSelected;
    }// setSelected


    /**
     * Get selected value
     *
     * @since 25. February 2014, v. 1.00
     * @return string Selected value
     */
    private function getSelected()
    {
        return $this->strSelected;
    }// getSelected


    /**
     * Get selected attribute html
     *
     * @param string $strValue Select value
     *
     * @since 25. February 2014, v. 1.00
     * @return string
     */
    private function getSelectedHtml($strValue)
    {
        $strSelected = $this->getSelected();
        if($strSelected == $strValue)
        {
            $strSelected = ' selected="selected"';
        }
        return $strSelected;
    }// getSelectedHtml


    /**
     * Get select html
     *
     * @since 25. February 2014, v. 1.00
     * @return string Select html
     */
    public function getHtml()
    {
        $strHtml = $this->getLabelAsHtml();
        $strHtml .= '<select name="' . $this->getName() . '"' . $this->getAttributesHtml() . '>';
        $aryOptions = $this->getOptions();
        if(count($aryOptions))
        {
            foreach($aryOptions as $strValue => $strName)
            {
                $strHtml .= '<option value="' . $strValue . '"' . $this->getSelectedHtml($strValue) . '>' . $strName . '</option>';
            }
        }
        $strHtml .= '</select><br />';
        return $strHtml;
    }// getHtml


}// Select