<?php
namespace Beerfest\Fest;

use Beerfest\Core\Form\Controller;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Fest;

class Form extends Controller
{
    /**
     * Fest object
     * @var Fest
     */
    private $objFest;

    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct(Fest $objFest)
    {
        $strAction = ($objFest->getId() ? ':' . $objFest->getCryptId() : ':add');
        parent::__construct('fest', 'post', 'fest' . $strAction);
        $this->loadElements();
        if($objFest->getId())
        {
            $this->setDefaults($objFest->getAll());
        }
        $this->objFest = $objFest;
    }// __construct


    /**
     * Load form elements
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function loadElements()
    {
        $objName = $this->addTextField(FestDB::COL_NAME, _FEST_NAME);
        $objName->setRequired(true);
        $objName->setPlaceholder(_FEST_NAME);

        $objLocation = $this->addTextArea(FestDB::COL_LOCATION, _FEST_LOCATION);
        $objLocation->setPlaceholder(_FEST_LOCATION);
        $objLocation->setRequired(true);

        $objActive = $this->addSelectField(FestDB::COL_ACTIVE, _FEST_ACTIVE);
        $objActive->setAttributes(array('data-role' => 'slider'));
        $objActive->addOption(0, _NO);
        $objActive->addOption(1, _YES);

        $this->addButtonSubmit();
        $this->addButtonReset();
        $this->addButtonCancel();
    }// loadElements


}// Form