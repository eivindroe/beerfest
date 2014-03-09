<?php
namespace Beerfest\User;

use Beerfest\Core\HtmlList\HtmlList;
use Beerfest\Core\Button;
use Beerfest\User\User;
use Beerfest\User\UserDB;
use Beerfest\Fest\Fest;
use Beerfest\Fest\FestDB;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\ItemDB;
use Beerfest\Fest\Item\Vote\Vote;
use Beerfest\Fest\Item\Vote\VoteDB;

class Details
{
    /**
     * User object
     * @var User
     */
    private $objUser;

    /**
     * Constructor
     *
     * @since 27. February 2014, v. 1.00
     */
    public function __construct(User $objUser)
    {
        $this->objUser = $objUser;
    }// __construct


    /**
     * Get user object
     *
     * @since 27. February 2014, v. 1.00
     * @return User
     */
    private function getUser()
    {
        return $this->objUser;
    }// getUser


    /**
     * Get details html
     *
     * @since 27. February 2014, v. 1.00
     * @return string Details html
     */
    public function getHtml()
    {
        $objUser = $this->getUser();

        $objButton = new Button('edit', _EDIT);
        $objButton->setAttributes(array('data-icon' => 'edit', 'data-module' => 'User', 'data-id' => $objUser->getCryptId(), 'class' => 'edit'));
        $objButton->setInline(true);

        $strHtml = '<h1>' . $objUser->getFullName() . ' ' .  $objButton->getHtml() .  '</h1>';

        $strHtml .= '<p>' .
                    '<strong>' . _EMAIL . '</strong>: ' . $objUser->get(UserDB::COL_EMAIL) . '<br />' .
                    '<strong>' . _USER_LAST_ACTIVE . '</strong>: ' . $objUser->getLastActiveAsDate() . '</p><br />';

        $aryVotes = $objUser->getVotes();

        if(count($aryVotes))
        {
            $aryFestIds = array_keys($aryVotes);

            foreach($aryFestIds as $intFestId)
            {
                $objFest = new Fest($intFestId);

                $objList = new HtmlList('votes', $objFest->get(FestDB::COL_NAME));

                $objList->addColumn(ItemDB::COL_NAME, _ITEM_NAME);
                $objVoteValue = $objList->addColumn(VoteDB::COL_VALUE, _VOTE_VALUE);
                $objVoteValue->setAlignment($objVoteValue::ALIGN_CENTER);

                $aryFestVotes = $aryVotes[$intFestId];

                $intTotalValue = 0;
                foreach($aryFestVotes as $objVote)
                {
                    $intValue = $objVote->getValue();
                    $objItem = new Item($objVote->get(VoteDB::COL_FEST_ITEM_ID));
                    $objList->addRow(
                        $objVote->getId(),
                        array(
                            ItemDB::COL_NAME => $objItem->get(ItemDB::COL_NAME),
                            VoteDB::COL_VALUE => $intValue
                        )
                    );
                    $intTotalValue += $intValue;
                }

                $intAverage = number_format(($intTotalValue / count($aryFestVotes)), 1);

                $objTotal = $objList->addRow(0, array(ItemDB::COL_NAME => _ITEM_AVERAGE_VALUE, VoteDB::COL_VALUE => $intAverage));
                $objTotal->setHighlight(true);

                $objList->setCollapsible(true);

                $strHtml .= $objList->getListHtml();
            }
        }

        return $strHtml;
    }// getHtml


}// Details