<?php
namespace Beerfest\Fest\Participant;

use Beerfest\Core\HtmlList\HtmlList;
use Beerfest\Core\Form\Select;
use Beerfest\Fest\Participant\ParticipantDB;
use Beerfest\Fest\Fest;

class ParticipantsList extends HtmlList
{
    /**
     * Fest object
     * @var Fest
     */
    private $objFest;

    /**
     * Participants
     * @var array
     */
    private $aryParticipants;

    /**
     * Constructor
     *
     * @param Fest $objFest Fest object
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct(Fest $objFest)
    {
        parent::__construct('Participant', _PARTICIPANTS);
        $this->objFest = $objFest;
    }// __construct


    /**
     * Get fest object
     *
     * @since 28. February 2014, v. 1.00
     * @return Fest
     */
    private function getFest()
    {
        return $this->objFest;
    }// getFest


    /**
     * Get participants
     *
     * @since 22. February 2014, v. 1.00
     * @return array Participants
     */
    private function getParticipants()
    {
        if(!isset($this->aryParticipants))
        {
            $objFest = $this->getFest();
            $this->aryParticipants = $objFest->getParticipants();
        }
        return $this->aryParticipants;
    }// getParticipants


    /**
     * Load list columns
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadColumns()
    {
        $objActive = $this->addColumn(ParticipantDB::COL_ACTIVE, _PARTICIPANT_ACTIVE);
        $objActive->setAlignment($objActive::ALIGN_CENTER);
        $this->addColumn(Participants::COL_NAME, _PARTICIPANT_NAME);
        $objLastActive = $this->addColumn(ParticipantDB::COL_LAST_ACTIVE, _PARTICIPANT_LAST_ACTIVE);
        $objLastActive->setIsDate(true);
    }// loadColumns


    /**
     * Load list content
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadContent()
    {
        $aryParticipants = $this->getParticipants();
        if(count($aryParticipants))
        {
            foreach($aryParticipants as $aryRow)
            {
                $strId = '';
                if(isset($aryRow[ParticipantDB::COL_ID]))
                {
                    $strId = \Beerfest\Core\Crypt::encrypt($aryRow[ParticipantDB::COL_ID]);
                }
                $intSelected = ($aryRow[ParticipantDB::COL_ACTIVE] ? 1 : 0);
                $objSelect = new Select('active');
                $aryAttributes = array('data-role' => 'slider', 'data-id' => $strId, 'data-module' => 'Participant',
                    'data-mini' => true, 'class' => 'toggle');
                if(!$strId)
                {
                    $objFest = $this->getFest();
                    $aryAttributes['data-fest'] = $objFest->getCryptId();
                    $aryAttributes['data-user'] = \Beerfest\Core\Crypt::encrypt($aryRow[ParticipantDB::COL_USERID]);
                }
                $objSelect->setAttributes($aryAttributes);
                $objSelect->addOption(0, _NO);
                $objSelect->addOption(1, _YES);
                $objSelect->setSelected($intSelected);

                $aryRow[ParticipantDB::COL_ACTIVE] = $objSelect->getHtml();
                $objRow = $this->addRow($aryRow[ParticipantDB::COL_USERID], $aryRow);
                $objRow->setId($strId);
            }
        }
    }// loadContent


}// ParticipantsList