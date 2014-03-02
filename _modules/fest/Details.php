<?php
namespace Beerfest\Fest;

use Beerfest\Fest\Fest;
use Beerfest\Fest\FestDB;
use Beerfest\User\User;
use Beerfest\Fest\Participant\Participants;
use Beerfest\Core\Button;
use Beerfest\Fest\Item\Vote\Form as VoteForm;

class Details
{
    /**
     * Fest object
     * @var Fest
     */
    private $objFest;


    /**
     * Constructor
     *
     * @param Fest $objFest Fest object
     * @since 22. February 2014, v. 1.00
     */
    public function __construct(Fest $objFest)
    {
        $this->objFest = $objFest;
    }// __construct


    /**
     * Get fest
     *
     * @since 22. February 2014, v. 1.00
     * @return Fest
     */
    private function getFest()
    {
        return $this->objFest;
    }// getFest


    /**
     * Get current fest item
     *
     * @since 27. February 2014, v. 1.00
     * @return Item|null Fest item if defined, null otherwise
     */
    private function getCurrentItem()
    {
        return $this->getFest()->getCurrentItem();
    }// getCurrentItem


    /**
     * Get next item button
     *
     * @since 29. February 2014, v. 1.00
     * @return Button|null Button next if next item exists, null otherwise
     */
    private function getNextItemButton()
    {
        $objFest = $this->getFest();
        $objNextItem = $objFest->getNextItem();

        $objButton = null;
        if($objNextItem)
        {
            $objButton = new Button('text_item', _NEXT_ITEM);
            $objButton->setAjax(false);
            $objButton->setInline(true);
            $objButton->setAttributes(array('href' => STR_ROOT . 'fest:' . $objFest->getCryptId() . '/next:' . $objNextItem->getCryptId()));
        }
        return $objButton;
    }// getNextItemButton


    /**
     * Get fest details as html
     *
     * @since 22. February 2014, v. 1.00
     * @return string
     */
    public function getHtml()
    {
        // Log action
        $objUser = \Beerfest\Core\Auth::getActiveUser();
        $objParticipants = new Participants();
        $objParticipant = $objParticipants->getParticipantByUserId($objUser->getId());
        if($objParticipant)
        {
            $objParticipant->setLastActive();
        }
        $objFest = $this->getFest();

        $objButton = new Button('edit', _EDIT);
        $objButton->setAttributes(array('data-icon' => 'edit', 'data-module' => 'Fest', 'data-id' => $objFest->getCryptId(), 'class' => 'edit'));
        $objButton->setInline(true);
        $strHtml = '';

        // Current vote
        $objCurrentItem = $this->getCurrentItem();

        // Fest admin
        $blnAdmin = ($objUser->getId() == $objFest->get(FestDB::COL_CREATED_BY) || $objUser->isAdmin());
        if($objCurrentItem)
        {
            $objVoteForm = new VoteForm($objCurrentItem);
            $strHtml .= $objVoteForm->getHtml();
        }
        else
        {
            if($blnAdmin)
            {
                $objButton = new Button('start', _START_FEST);
                $objButton->setAjax(false);
                $objButton->setInline(true);
                $objButton->setAttributes(array('href' => STR_ROOT . 'fest:' . $objFest->getCryptId() . '/start'));
                $strHtml .= $objButton->getHtml();
            }
            else
            {
                $strHtml .= '<h1>' . _STAY_TUNED . '</h2><p>' . _VOTING_NOT_STARTED . '</p>';
            }
        }

        if($blnAdmin)
        {
            $objNext = $this->getNextItemButton();
            if($objNext)
            {
                $strHtml .= $objNext->getHtml();
            }
        }

        return $strHtml;
    }// getHtml


}// Details