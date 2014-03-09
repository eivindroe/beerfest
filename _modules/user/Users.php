<?php
namespace Beerfest\User;

use Beerfest\User\UserDB;

class Users
{
    /**
     * User DB object
     * @var UserDB
     */
    private $objDb;

    /**
     * Constructor
     *
     * @since 22. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->objDb = new UserDB();
    }// __construct


    /**
     * Get user database
     *
     * @since 22. February 2014, v. 1.00
     * @return UserDB
     */
    private function getDb()
    {
        return $this->objDb;
    }


    /**
     * Get all users
     *
     * @param string $strOrder Order by clause
     *
     * @since 22. February 2014, v. 1.00
     * @return mixed
     */
    public function getAll($strOrder = '')
    {
        $aryUsers = $this->getDb()->select(array_keys($this->getDb()->getTableColumns()), '', $strOrder);
        return $aryUsers;
    }// getAll


    /**
     * Get all names
     *
     * @since 29. February 2014, v. 1.00
     * @return array Associative array with all user full names (id => name)
     */
    public function getAllNames()
    {
        $aryNames = array();
        $aryUsers = $this->getAll();

        if(count($aryUsers))
        {
            foreach($aryUsers as $aryUser)
            {
                $intId = $aryUser[UserDB::COL_ID];
                $objUser = new User($intId);
                $aryNames[$intId] = $objUser->getFullName();
            }
        }

        return $aryNames;
    }// getAllNames


    /**
     * Get user names by ids
     *
     * @param array $aryUserIds User ids
     *
     * @since 22. February 2014, v. 1.00
     * @return mixed
     */
    public function getUserNames($aryUserIds)
    {
        return $this->getDb()->getUserNames($aryUserIds);
    }// getUserNames


}// Users