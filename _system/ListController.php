<?php
namespace Beerfest\Core\HtmlList;

use Beerfest\Core\HtmlList\Navigation as ListNavigation;
use Beerfest\Core\HtmlList\Column;
use Beerfest\Core\HtmlList\Row;
use Beerfest\Core\Button;

abstract class Controller implements ListInterface
{
    /**
     * List name
     * @var string
     */
    private $strName;

    /**
     * List title
     * @var string
     */
    private $strTitle = '';

    /**
     * List attributes
     * @var array
     */
    private $aryAttributes = array();

    /**
     * List columns
     * @var array
     */
    private $aryColumns = array();

    /**
     * List rows
     * @var array
     */
    private $aryRows = array();

    /**
     * List buttons
     * @var array
     */
    private $aryButtons = array();

    /**
     * Flag add edit column
     * @var boolean
     */
    private $blnEdit = false;

    /**
     * Flag add delete column
     * @var boolean
     */
    private $blnDelete = false;


    /**
     * Flag collapsible list
     * @var boolean
     */
    private $blnCollapsible = false;


    /**
     * Constructor
     * @param string $strName List name
     * @param string $strTitle List title
     *
     * @since 25. February 2014, v. 1.00
     */
    public function __construct($strName, $strTitle = '')
    {
        $this->setAttributes(array('data-module' => $strName));
        $this->strName = $strName;
        $this->strTitle = $strTitle;
    }// __construct


    /**
     * Get list name
     *
     * @since 25. February 2014, v. 1.00
     * @return string List name
     */
    private function getName()
    {
        return $this->strName;
    }// getName


    /**
     * Get list title
     *
     * @since 25. February 2014, v. 1.00
     * @return string List title
     */
    private function getTitle()
    {
        return $this->strTitle;
    }// getTitle


    /**
     * Set list attributes
     *
     * @param array $aryAttributes List attributes
     *
     * @since 25. February 2014, v. 1.00
     * @return array
     */
    public function setAttributes($aryAttributes)
    {
        if(is_array($aryAttributes))
        {
            $this->aryAttributes = array_merge($this->aryAttributes, $aryAttributes);
        }
        return $this->aryAttributes;
    }// setAttributes


    /**
     * Get list attributes
     *
     * @since 25. February 2014, v. 1.00
     * @return array List attributes
     */
    private function getAttributes()
    {
        return $this->aryAttributes;
    }// getAttributes


    /**
     * Get list attributes as html
     *
     * @since 25. February 2014, v. 1.00
     * @return string List attributes as html
     */
    private function getAttributesHtml()
    {
        $strHtml = '';
        $aryAttributes = $this->getAttributes();
        if(count($aryAttributes))
        {
            foreach($aryAttributes as $strKey => $strValue)
            {
                $strHtml .= ' ' . $strKey . '="' . $strValue . '"';
            }
        }
        return $strHtml;
    }// getAttributesHtml


    /**
     * Set collapsible
     *
     * @param boolean $blnCollapsible Collapsible
     *
     * @since 29. February 2014, v. 1.00
     * @return boolean Collapsible value
     */
    public function setCollapsible($blnCollapsible)
    {
        if(is_bool($blnCollapsible))
        {
            $this->blnCollapsible = $blnCollapsible;
        }
        return $this->blnCollapsible;
    }// setCollapsible


    /**
     * Get collapsible

     * @since 29. February 2014, v. 1.00
     * @return boolean List collapsible value
     */
    private function getCollapsible()
    {
        return $this->blnCollapsible;
    }// getCollapsible


    /**
     * Flag edit column
     *
     * @param boolean $blnEdit Flag use edit
     *
     * @since 25. February 2014, v. 1.00
     * @return boolean True/false
     */
    public function setEdit($blnEdit)
    {
        if($this->blnEdit === false && $blnEdit === true)
        {
            $this->blnEdit = $blnEdit;
        }
        return $this->blnEdit;
    }// setEdit


    /**
     * Check if list has edit button
     *
     * @since 25. February 2014, v. 1.00
     * @return boolean True if edit button added, false if not (skip add edit column)
     */
    private function hasEdit()
    {
        return $this->blnEdit;
    }// hasEdit


    /**
     * Flag delete column
     *
     * @param boolean $blnDelete Flag use delete
     *
     * @since 25. February 2014, v. 1.00
     * @return boolean True/false
     */
    public function setDelete($blnDelete)
    {
        if($this->blnDelete === false && $blnDelete === true)
        {
            $this->blnDelete = $blnDelete;
        }
        return $this->blnDelete;
    }// setDelete


    /**
     * Check if list has delete button
     *
     * @since 25. February 2014, v. 1.00
     * @return boolean True if delete button added, false if not (skip add delete column)
     */
    private function hasDelete()
    {
        return $this->blnDelete;
    }// hasDelete


    /**
     * Add button to list
     *
     * @param string $strKey Button key
     * @param string $strLabel Button label
     *
     * @since 25. February 2014, v. 1.00
     * @return Button
     */
    public function addButton($strKey, $strLabel)
    {
        $objButton = new Button($strKey, $strLabel);
        $this->aryButtons[$strKey] = $objButton;
        return $objButton;
    }// addButton


    /**
     * Add button new in list
     *
     * @param string $strModule Beerfest module
     *
     * @since 25. February 2014, v. 1.00
     * @return Button
     */
    public function addButtonNew($strModule)
    {
        $objButton = $this->addButton('add', _ADD);
        $objButton->setAttributes(array('data-module' => ucfirst($strModule), 'class' => 'add', 'data-icon' => 'plus'));
        $objButton->setInline(true);
        return $objButton;
    }// addButtonNew


    /**
     * Add list column
     * @param string $strName Column name
     * @param string $strTitle Column title
     *
     * @since 25. February 2014, v. 1.00
     * @return Column
     */
    public function addColumn($strName, $strTitle)
    {
        $objColumn = new Column($strName, $strTitle);
        $this->aryColumns[$strName] = $objColumn;
        return $objColumn;
    }// addColumn


    /**
     * Add list row
     * @param integer $intRow Row number
     * @param array $aryData Row data (matching columns)
     *
     * @since 25. February 2014, v. 1.00
     * @return Row
     */
    public function addRow($intRow, $aryData)
    {
        $objRow = new Row($this, $intRow, $aryData);
        $this->aryRows[$intRow] = $objRow;
        return $objRow;
    }// addRow


    /**
     * Get list buttons
     *
     * @since 25. February 2014, v. 1.00
     * @return array
     */
    private function getButtons()
    {
        return $this->aryButtons;
    }// getButtons


    /**
     * Get list column elements
     *
     * @since 25. February 2014, v. 1.00
     * @return array Column objects (Column)
     */
    private function getColumns()
    {
        if($this->hasEdit() || $this->hasDelete())
        {
            $objFunctions = $this->addColumn('functions', '');
            $objFunctions->setAlignment($objFunctions::ALIGN_CENTER);
            $objFunctions->setAutoFit(true);
        }
        return $this->aryColumns;
    }// getColumns


    /**
     * Get list row elements
     *
     * @since 25. February 2014, v. 1.00
     * @return array Row objects (Row)
     */
    private function getRows()
    {
        return $this->aryRows;
    }// getRows


    /**
     * Get list buttons html
     *
     * @since 25. February 2014, v. 1.00
     * @return string List buttons html
     */
    public function getButtonHtml()
    {
        $strHtml = '';
        $aryButtons = $this->getButtons();
        if(count($aryButtons))
        {
            foreach($aryButtons as $objButton)
            {
                $strHtml .= $objButton->getHtml();
            }
        }
        return $strHtml;
    }// getButtons


    /**
     * Get list as html
     *
     * @since 25. February 2014, v. 1.00
     * @return string List as html
     */
    public function getListHtml()
    {
        $this->loadColumns();
        $this->loadContent();

        $aryColumns = $this->getColumns();
        $aryRows = $this->getRows();
        $strTitle = $this->getTitle();

        $strList = '';

        if($strTitle)
        {
            $strList .= '<h1>' . $strTitle . '</h1>';
        }

        $strList .= $this->getButtonHtml();

        $strList .= "\n" . '<table' . $this->getAttributesHtml() . '>';

        if(count($aryColumns))
        {
            $strList .= "\n\t" . '<thead><tr>';
            foreach($aryColumns as $objColumn)
            {
                $strList .= "\n\t\t" . '<th' . $objColumn->getAttributesAsHtml() . '>' . $objColumn->getTitle() . '</th>';
            }
            $strList .= "\n\t</tr></thead>";

            $aryColumnKeys = array_keys($aryColumns);

            if(count($aryRows))
            {
                $strList .= '<tbody>';
                foreach($aryRows as $objRow)
                {
                    $strList .= "\n\t<tr" . $objRow->getIdHtml() . $objRow->getAttributesAsHtml() . ">";
                    foreach($aryColumnKeys as $strKey)
                    {
                        $objColumn = $aryColumns[$strKey];
                        $strValue = $objRow->getValue($strKey);
                        if($strValue && $objColumn->getIsDate())
                        {
                            $strValue = date($objColumn->getDateFormat(), (int) $strValue);
                        }
                        $strList .= "\n\t\t<td" . $objColumn->getAttributesAsHtml() . ">" . $strValue . "</td>";
                    }
                    $strList .= "\n\t</tr>";
                }
                $strList .= '</tbody>';
            }
        }
        $strList .= "\n</table>";

        if($this->getCollapsible() === true)
        {
            $strList = '<div data-role="collapsible" data-iconpos="right" data-collapsed="false" data-insert="false"' .
                'data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u" data-content-theme="false">' . $strList . '</div>';
        }

        return $strList;
    }// getListHtml


}// Controller