<?php
namespace Beerfest\Fest\Item\Vote;

use Beerfest\GenericObject;
use Beerfest\Fest\Item\Vote\VoteDB;
use Beerfest\User\User;

class Vote extends GenericObject
{
    /**
     * Constructor
     *
     * @param null|string $mxdId Item vote id
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct($mxdId = null)
    {
        parent::__construct(new VoteDB(), $mxdId);
        return $this;
    }// __construct


    /**
     * Get vote value
     *
     * @since 29. February 2014, v. 1.00
     * @return float Vote value
     */
    public function getValue()
    {
        return number_format($this->get(VoteDB::COL_VALUE), 1);
    }// getValue


    /**
     * Get user name
     *
     * @since 04. March 2014, v. 1.00
     * @return string User name
     */
    public function getUserName()
    {
        $intUserId = $this->get(VoteDB::COL_USER_ID);
        $objUser = new User($intUserId);
        return $objUser->getFullName();
    }// getUserName


    /**
     * Save vote
     *
     * @since 27. February 2014, v. 1.00
     * @return integer Vote id
     */
    public function save()
    {
        if(!$this->getId())
        {
            $objUser = \Beerfest\Core\Auth::getActiveUser();
            $this->set(VoteDB::COL_USER_ID, $objUser->getId());
        }
        $this->set(VoteDB::COL_DATE, time());
        return parent::save();
    }// save


}// ItemVote