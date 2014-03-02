<?php
namespace Beerfest\User;

use Beerfest\Core\HtmlList\HtmlList;
use Beerfest\User\UserDB;
use Beerfest\User\Users;

class UserList extends HtmlList
{
    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct()
    {
        parent::__construct('User', _USER_LIST);
        $this->addButtonNew('User');
    }// __construct


    /**
     * Load columns
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadColumns()
    {
        $this->addColumn(UserDB::COL_FIRSTNAME, _FIRSTNAME);
        $this->addColumn(UserDB::COL_LASTNAME, _LASTNAME);
        $objEmail = $this->addColumn(UserDB::COL_EMAIL, _EMAIL);
        $objEmail->setPriority(2);
        $objActive = $this->addColumn(UserDB::COL_LAST_ACTIVE, _USER_LAST_ACTIVE);
        $objActive->setPriority(3);
        $objActive->setIsDate(true);
    }// loadColumns


    /**
     * Load content
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function loadContent()
    {
        $objUsers = new Users();
        foreach($objUsers->getAll(UserDB::COL_LASTNAME) as $aryUser)
        {
            $strId = md5($aryUser[UserDB::COL_ID]);
            $objRow = $this->addRow($aryUser[UserDB::COL_ID], $aryUser);
            $objRow->setEdit($strId);
            $objRow->setDelete($strId);
            $objRow->setId($strId);
        }
    }// loadContent


}// UserList