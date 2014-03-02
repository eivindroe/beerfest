<?php
namespace Beerfest\Fest\Participant;

use Beerfest\GenericObject;
use Beerfest\Fest\Participant\ParticipantDB;

class Participant extends GenericObject
{
    /**
     * Constructor
     *
     * @param null|string $mxdId Participant id
     * @since 22. February 2014, v. 1.00
     */
    public function __construct($mxdId = null)
    {
        parent::__construct(new ParticipantDB(), $mxdId);
    }// __construct


    /**
     * Toggle active
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if active, false if deactivated
     */
    public function toggleActive()
    {
        $blnActive = $this->get(ParticipantDB::COL_ACTIVE);

        if($blnActive)
        {
            $this->deactivate();
        }
        else
        {
            $this->activate();
        }
        return $this->get(ParticipantDB::COL_ACTIVE);
    }// toggleActive


    /**
     * Deactivate participant
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function deactivate()
    {
        $this->set(ParticipantDB::COL_ACTIVE, false);
        $this->save();
    }// deactivate


    /**
     * Activate participant
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function activate()
    {
        $this->set(ParticipantDB::COL_ACTIVE, true);
        $this->save();
    }// activate


    public function setLastActive($intTime = null)
    {
        if(!isset($intTime) || !is_numeric($intTime))
        {
            $intTime = time();
        }
        $this->set(ParticipantDB::COL_LAST_ACTIVE, $intTime);
        $this->save();
        return $intTime;
    }// setLastActive


}// Participant