<?php
namespace Beerfest\Core\HtmlList;

class Column
{
    /**
     * Alignments
     * @var string
     */
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_RIGHT = 'right';

    /**
     * Priorities
     * @var string
     */
    const PRIORITY_1 = 1;
    const PRIORITY_2 = 2;
    const PRIORITY_3 = 3;
    const PRIORITY_4 = 4;
    const PRIORITY_5 = 5;

    /**
     * Column name
     * @var string
     */
    private $strName;

    /**
     * Column title
     * @var string
     */
    private $strTitle;

    /**
     * Flag date
     * @var boolean
     */
    private $blnIsDate = false;

    /**
     * Date format
     * @var string
     */
    private $strDateFormat = 'l j. F Y H:i:s';

    /**
     * Column alignment
     * @var string
     */
    private $strAlign = self::ALIGN_LEFT;


    /**
     * Column priority
     * @var integer
     */
    private $intPriority = self::PRIORITY_1;


    /**
     * Column width
     * @var mixed
     */
    private $mxdWidth = '';


    /**
     * Column attributes
     * @var array
     */
    private $aryAttributes = array();


    /**
     * Constructor
     *
     * @param string $strName Column name
     * @param string $strTitle Column title
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct($strName, $strTitle)
    {
        $this->strName = $strName;
        $this->strTitle = $strTitle;
        return $this;
    }// __construct

    /**
     * Set column alignment
     *
     * @param string $strAlign Alignment (ALIGN_LEFT, ALIGN_CENTER, ALIGN_RIGHT)
     *
     * @since 22. February 2014, v. 1.00
     * @return string Alignment
     */
    public function setAlignment($strAlign)
    {
        $aryValid = array(self::ALIGN_LEFT, self::ALIGN_CENTER, self::ALIGN_RIGHT);
        if(in_array($strAlign, $aryValid))
        {
            $this->strAlign = $strAlign;
        }
        return $this->strAlign;
    }// setAlignment


    /**
     * Get column alignment
     *
     * @since 22. February 2014, v. 1.00
     * @return string Column alignment
     */
    private function getAlignment()
    {
        return $this->strAlign;
    }// getAlignment


    /**
     * Set column attribute
     *
     * @param string $strKey Attribute key
     * @param string $strValue Attribute value
     *
     * @since 29. February 2014, v. 1.00
     * @return string Column attribute value
     */
    public function setAttribute($strKey, $strValue)
    {
        $aryAttributes = $this->getAttributes();
        if(isset($aryAttributes[$strKey]))
        {
            if($strKey == 'class' || $strKey == 'style')
            {
                $strValue = $aryAttributes[$strKey] . ' ' . $strValue;
            }
            else
            {
                unset($this->aryAttributes[$strKey]);
            }
        }
        $this->aryAttributes[$strKey] = $strValue;
        return $this->aryAttributes[$strKey];
    }// setAttribute


    /**
     * Set column attributes
     *
     * @param array $aryAttributes Attributes
     *
     * @since 29. February 2014, v. 1.00
     * @return array Column attributes
     */
    public function setAttributes($aryAttributes)
    {
        if(is_array($aryAttributes))
        {
            foreach($aryAttributes as $strKey => $strValue)
            {
                $this->setAttribute($strKey, $strValue);
            }
        }
        return $this->getAttributes();
    }// setAttributes


    /**
     * Get column attributes
     *
     * @since 29. February 2014, v. 1.00
     * @return array Column attributes
     */
    private function getAttributes()
    {
        return $this->aryAttributes;
    }// getAttributes


    /**
     * Get column attributes as html
     *
     * @since 22. February 2014, v. 1.00
     * @return string Column attributes as html
     */
    public function getAttributesAsHtml()
    {
        $this->setAttribute('class', 'text-' . $this->getAlignment());
        $this->setAttribute('data-priority', $this->getPriority());
        $mxdWidth = $this->getWidth();
        if($mxdWidth)
        {
            $this->setAttribute('style', 'width:' . $mxdWidth . ';');
        }

        $strAttributes = '';
        $aryAttributes = $this->getAttributes();

        if(count($aryAttributes))
        {
            foreach($aryAttributes as $strKey => $strValue)
            {
                $strAttributes .= ' ' . $strKey . '="' . $strValue . '"';
            }
        }
        return $strAttributes;
    }// getAttributesAsHtml


    /**
     * Flag column as date
     *
     * @param boolean $blnIsDate True/false
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if date, false otherwise
     */
    public function setIsDate($blnIsDate)
    {
        if(is_bool($blnIsDate))
        {
            $this->blnIsDate = $blnIsDate;
        }
        return $this->blnIsDate;
    }// setIsDate


    /**
     * Check if column is date
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if date, false otherwise
     */
    public function getIsDate()
    {
        return $this->blnIsDate;
    }// getIsDate


    /**
     * Set column date format
     *
     * @param string $strFormat Date format
     *
     * @since 22. February 2014, v. 1.00
     * @return string Column date format
     * @TODO Add dateformat validation
     */
    public function setDateFormat($strFormat)
    {
        $this->strDateFormat = $strFormat;
        return $this->strDateFormat;
    }// setDateFormat


    /**
     * Get column date format
     *
     * @since 22. February 2014, v. 1.00
     * @return string Column date format
     */
    public function getDateFormat()
    {
        return $this->strDateFormat;
    }// getDateFormat


    /**
     * Get column title
     *
     * @since 22. February 2014, v. 1.00
     * @return string Column title
     */
    public function getTitle()
    {
        return $this->strTitle;
    }// getTitle


    /**
     * Set column priority
     *
     * @param integer $intPriority Column priority
     *
     * @since 22. February 2014, v. 1.00
     * @return integer Column priority
     */
    public function setPriority($intPriority)
    {
        if(is_numeric($intPriority))
        {
            $this->intPriority = $intPriority;
        }
        return $this->intPriority;
    }// setPriority


    /**
     * Get column priority
     *
     * @since 22. February 2014, v. 1.00
     * @return integer Column priority
     */
    private function getPriority()
    {
        return $this->intPriority;
    }// getPriority


    /**
     * Get column width
     *
     * @since 29. February 2014, v. 1.00
     * @return mixed Column width
     */
    private function getWidth()
    {
        return $this->mxdWidth;
    }// getWidth


    /**
     * Set column width
     *
     * @param mixed $mxdWidth Column width
     *
     * @since 29. February 2014, v. 1.00
     * @return void
     */
    public function setWidth($mxdWidth)
    {
        $this->mxdWidth = $mxdWidth;
    }// setWidth


    /**
     * Set auto fit column
     *
     * @param boolean $blnAutoFit True/false
     *
     * @since 29. February 2014, v. 1.00
     * @return void
     */
    public function setAutoFit($blnAutoFit)
    {
        if(is_bool($blnAutoFit) && $blnAutoFit === true)
        {
            $this->setAttribute('style', 'width: 1px; white-space: nowrap');
        }
    }// setAutoFit


}// Column