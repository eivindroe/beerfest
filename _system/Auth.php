<?php
namespace Beerfest\Core;

use Beerfest\User\UserDB;
use Beerfest\User\User;

class Auth
{
    /**
     * Check login
     *
     * @param string $strUsername Posted username
     * @param string $strPassword Posted password
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if valid login, false if not
     */
    public function checkLogin($strUsername, $strPassword)
    {
        $blnValid = false;
        $objDb = new UserDB();
        $strWhere = sql_where(UserDB::COL_USERNAME, $strUsername);
        $strWhere .= ' AND ' . sql_where(UserDB::COL_PASSWORD, \Beerfest\Core\Crypt::encrypt($strPassword));

        $aryResult = $objDb->select(array(UserDB::COL_ID), $strWhere);

        if(count($aryResult))
        {
            $blnValid = true;
            $this->createSession($aryResult[0][UserDB::COL_ID]);
        }
        return $blnValid;
    }// checkLogin


    /**
     * Check if user is authenticated
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if authenticated, false if not
     */
    public function isAuthenticated()
    {
        $blnAuthenticated = false;
        if(isset($_COOKIE['logged_in']))
        {
            $blnAuthenticated = true;
        }
        return $blnAuthenticated;
    }// isAuthenticated


    /**
     * Logout user
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    public function logOut()
    {
        $this->destroySession();
    }// logout


    /**
     * Create login session
     *
     * @param integer $intUserId User id
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function createSession($intUserId)
    {
        $objUser = new User($intUserId);
        $objUser->setLastActive();
        setcookie('logged_in', $objUser->getCryptId(), time()+86400, '/');
    }// createSession


    /**
     * Destroy login session
     *
     * @since 22. February 2014, v. 1.00
     * @return void
     */
    private function destroySession()
    {
        unset($_COOKIE['logged_in']);
        setcookie('logged_in', null, -1, '/');
    }// destroySession


    /**
     * Get active user id
     *
     * @since 22. February 2014, v. 1.00
     * @return integer Active user id
     */
    public static function getActiveUserId()
    {
        $objUser = self::getActiveUser();
        return $objUser->getId();
    }// getActiveUserId


    /**
     * Get active user
     *
     * @since 22. February 2014, v. 1.00
     * @return \Beerfest\User\User
     */
    public static function getActiveUser()
    {
        return new \Beerfest\User\User($_COOKIE['logged_in']);
    }// getActiveUser


}// Auth