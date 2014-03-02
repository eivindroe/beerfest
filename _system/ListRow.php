<?php
namespace Beerfest\Core\HtmlList;

class Row
{
    /**
     * List Controller
     * @var HtmlList
     */
    private $objController;

    /**
     * Row index
     * @var integer
     */
    private $intRow;

    /**
     * Row data
     * @var array
     */
    private $aryData;

    /**
     * Row crypt id
     * @var string
     */
    private $strId;

    /**
     * Row edit id
     * @var string
     */
    private $strEdit;

    /**
     * Row delete id
     * @var string
     */
    private $strDelete;


    /**
     * Flag highlight row
     * @var boolean
     */
    private $blnHighlight = false;


    /**
     * Row attributes
     * @var array
     */
    private $aryAttributes = array();


    /**
     * Constructor
     *
     * @param HtmlList $objController List controller
     * @param integer $intRow Row index
     * @param array $aryData Row data
     *
     * @since 22. February 2014, v. 1.00
     * @return Row
     */
    public function __construct(HtmlList $objController, $intRow, $aryData)
    {
        $this->objController = $objController;
        $this->intRow = $intRow;
        $this->aryData = $aryData;
        return $this;
    }// __construct


    /**
     * Get list controller
     *
     * @since 22. February 2014, v. 1.00
     * @return HtmlList
     */
    private function getController()
    {
        return $this->objController;
    }// getController


    /**
     * Set row crypt id
     *
     * @param string $strId Row crypt id
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function setId($strId)
    {
        $this->strId = $strId;
    }// setId


    /**
     * Get row id
     *
     * @since 22. February 2014, v. 1.00
     * @return string Row crypt id
     */
    private function getId()
    {
        $strId = '';
        if($this->strId)
        {
            $strId = $this->strId;
        }
        return $strId;
    }// getId


    /**
     * Get row id as html
     *
     * @since 22. February 2014, v. 1.00
     * @return string Row id as html
     */
    public function getIdHtml()
    {
        $strId = $this->getId();
        if($strId)
        {
            $strId = ' data-id="' . $strId . '" class="view"';
        }
        return $strId;
    }// getIdHtml


    /**
     * Set highlight row
     *
     * @param $blnHighlight
     *
     * @since 29. February 2014, v. 1.00
     * @return boolean Row highlight value
     */
    public function setHighlight($blnHighlight)
    {
        if(is_bool($blnHighlight))
        {
            $this->blnHighlight = $blnHighlight;
        }
        return $this->blnHighlight;
    }// setHighlight


    /**
     * Check if highlight row
     *
     * @since 29. February 2014, v. 1.00
     * @return boolean True if highlight row is enabled, false otherwise
     */
    private function isHighlight()
    {
        return $this->blnHighlight;
    }// isHighlight


    private function setAttribute($strKey, $strValue)
    {
        $this->aryAttributes[$strKey] = $strValue;
    }// setAttribute


    public function setAttributes($aryAttributes)
    {
        if(is_array($aryAttributes))
        {
            foreach($aryAttributes as $strKey => $strValue)
            {
                $this->setAttribute($strKey, $strValue);
            }
        }
    }// setAttributes


    private function getAttributes()
    {
        return $this->aryAttributes;
    }// getAttributes

    /**
     * Get row attributes as html
     *
     * @since 29. February 2014, v. 1.00
     * @return string Row attributes as html
     */
    public function getAttributesAsHtml()
    {
        $strHtml = '';
        if($this->isHighlight())
        {
            $this->setAttribute('class', 'text-bold');
        }
        $aryAttributes = $this->getAttributes();
        if(count($aryAttributes))
        {
            foreach($aryAttributes as $strKey => $strValue)
            {
                $strHtml .= ' ' . $strKey . '="' . $strValue . '"';
            }
        }
        return $strHtml;
    }// getAttributesAsHtml


    /**
     * Enabled edit button on row
     *
     * @param string $strEdit Edit id
     *
     * @since 22. February 2014, v. 1.00
     * @return string Edit id
     */
    public function setEdit($strEdit)
    {
        $this->getController()->setEdit(true);
        $this->strEdit = $strEdit;
        return $this->strEdit;
    }// setEdit


    /**
     * Get edit crypt id
     *
     * @since 22. February 2014, v. 1.00
     * @return string Edit crypt id
     */
    private function getEdit()
    {
        $strEdit = '';
        if(isset($this->strEdit))
        {
            $strEdit = $this->strEdit;
        }
        return $strEdit;
    }// getEdit


    /**
     * Check if row has edit enabled
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if edit is enabled on row, false otherwise
     */
    public function hasEdit()
    {
        return isset($this->strEdit);
    }// hasEdit


    /**
     * Set delete crypt id
     *
     * @param string $strDelete Delete crypt id
     *
     * @since 22. February 2014, v. 1.00
     * @return string Delete crypt id
     */
    public function setDelete($strDelete)
    {
        $this->getController()->setDelete(true);
        $this->strDelete = $strDelete;
        return $this->strDelete;
    }// setDelete


    /**
     * Get delete crypt id
     *
     * @since 22. February 2014, v. 1.00
     * @return string Delete crypt id
     */
    private function getDelete()
    {
        $strDelete = '';
        if(isset($this->strDelete))
        {
            $strDelete = $this->strDelete;
        }
        return $strDelete;
    }// getDelete


    /**
     * Check if row has delete button
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if delete is enabled on row, false otherwise
     */
    public function hasDelete()
    {
        return isset($this->strDelete);
    }// hasDelete


    /**
     * Get row data
     *
     * @since 22. February 2014, v. 1.00
     * @return array Row data
     */
    private function getData()
    {
        return $this->aryData;
    }// getData


    /**
     * Get row column value
     *
     * @param string $strKey Column key
     *
     * @since 22. February 2014, v. 1.00
     * @return string Row column value
     */
    public function getValue($strKey)
    {
        $strValue = '';
        $aryData = $this->getData();

        if($strKey == 'functions')
        {
            $strValue .= '<div class="row-functions">';
            if($this->hasEdit())
            {
                $strValue .= '<a href="#" class="edit" data-role="button" data-inline="true" data-mini="true" data-icon="edit" data-id="' . $this->getEdit() . '">' . _EDIT . '</a>';
            }
            if($this->hasDelete())
            {
                $strValue .= '<a href="#" class="delete" data-role="button" data-inline="true" data-mini="true" data-icon="delete" data-id="' . $this->getDelete() . '">' . _DELETE . '</a>';
            }
            $strValue .= '</div>';
        }
        elseif(isset($aryData[$strKey]))
        {
            $strValue = $aryData[$strKey];
        }
        return $strValue;
    }// getValue


}// Row