<?php
namespace Beerfest\Fest;

use Beerfest\Core\Form\Controller;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Fest;

class Form extends Controller
{
    /**
     * Custom columns
     * @var string
     */
    const COL_COLOR = 'color';
    const COL_FOAM = 'foam';
    const COL_TASTE = 'taste';
    const COL_WEIGHTING = 'weighting';

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
     * Get posted data
     *
     * @since 10. March 2014, v. 1.00
     * @return array Posted data formatted
     */
    public function getPostData()
    {
        $aryPost = parent::getPostData();
        $aryWeighting = array();

        if(isset($aryPost[self::COL_COLOR]))
        {
            $aryWeighting[self::COL_COLOR] = $aryPost[self::COL_COLOR];
            unset($aryPost[self::COL_COLOR]);
        }

        if(isset($aryPost[self::COL_FOAM]))
        {
            $aryWeighting[self::COL_FOAM] = $aryPost[self::COL_FOAM];
            unset($aryPost[self::COL_FOAM]);
        }

        if(isset($aryPost[self::COL_TASTE]))
        {
            $aryWeighting[self::COL_TASTE] = $aryPost[self::COL_TASTE];
            unset($aryPost[self::COL_TASTE]);
        }

        $aryPost[FestDB::COL_VOTING] = addslashes(json_encode($aryWeighting));

        return $aryPost;
    }// getPostedData


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

        // Color
        $objColor = $this->addRangeField(self::COL_COLOR, _ITEM_COLOR);
        $objColor->setRange(0, 1)->setStep(0.01);

        // Foam
        $objFoam = $this->addRangeField(self::COL_FOAM, _ITEM_FOAM);
        $objFoam->setRange(0, 1)->setStep(0.01);

        // Taste
        $objTaste = $this->addRangeField(self::COL_TASTE, _ITEM_TASTE);
        $objTaste->setRange(0, 1)->setStep(0.01);

        $this->addButtonSubmit();
        $this->addButtonReset();
        $this->addButtonCancel();
    }// loadElements


}// Form