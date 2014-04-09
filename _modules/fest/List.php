<?php
namespace Beerfest\Fest;

use Beerfest\Core\HtmlList\HtmlList;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Fest;
use Beerfest\Fest\Fests;
use Beerfest\Core\Form\Select;

class FestList extends HtmlList
{
    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->loadButtons();
        parent::__construct('Fest', _FESTS);
    }// __construct


    /**
     * Load list buttons
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function loadButtons()
    {
        $objAdd = $this->addButtonNew(_FEST);
        $objAdd->setInline(true);
    }// loadButtons


    /**
     * Load columns
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadColumns()
    {
        $objActive = $this->addColumn(FestDB::COL_ACTIVE, _ACTIVE);
        $objActive->setAlignment($objActive::ALIGN_CENTER);
        $this->addColumn(FestDB::COL_NAME, _FEST_NAME);
        $this->addColumn(FestDB::COL_LOCATION, _FEST_LOCATION);
    }// loadColumns


    /**
     * Load content
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadContent()
    {
        $objFests = new Fests();
        $objActiveUser = \Beerfest\Core\Auth::getActiveUser();
        foreach($objFests->getAll(FestDB::COL_NAME) as $objFest)
        {
            $blnAdmin = false;
            if($objActiveUser->isAdmin() || ($objActiveUser->getId() == $objFest->get(FestDB::COL_CREATED_BY)))
            {
                $blnAdmin = true;
            }
            $strId = $objFest->getCryptId();
            $intSelected = ($objFest->get(FestDB::COL_ID) == $objActiveUser->getActiveFest()->getId() ? 1 : 0);
            $objSelect = new Select('active');
            $objSelect->setAttributes(array('data-role' => 'slider', 'data-id' => $strId, 'data-module' => 'Fest', 'data-mini' => true, 'class' => 'toggle'));
            $objSelect->addOption(0, _NO);
            $objSelect->addOption(1, _YES);
            $objSelect->setSelected($intSelected);
            $aryFest = $objFest->getAll();
            $aryFest[FestDB::COL_ACTIVE] = $objSelect->getHtml();

            $objRow = $this->addRow($objFest->getId(), $aryFest);
            $objRow->setId($strId);

            if($blnAdmin)
            {
                $objRow->setEdit($strId);
                $objRow->setDelete($strId);
            }
        }
    }// loadContent


}// FestList