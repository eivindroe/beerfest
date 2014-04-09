<?php
namespace Beerfest\User;

use Beerfest\DBTable;

class UserDB extends DBTable
{
    const COL_ID = 'userid';
    const COL_USERNAME = 'username';
    const COL_PASSWORD = 'password';
    const COL_FIRSTNAME = 'firstname';
    const COL_LASTNAME = 'lastname';
    const COL_EMAIL = 'email';
    const COL_LAST_ACTIVE = 'last_active';
    const COL_ACTIVE_FEST = 'active_fest';
    const COL_DELETED = 'deleted';


    /**
    * Get table name
    *
    * @since 22. February 2014, v. 1.00
    * @return Table name
    */
    public function getTableName()
    {
        return 'user';
    }// getTableName


    /**
     * Get table columns
     *
     * @since 11. February 2014, v. 1.00
     * @return array Table columns
     */
    public function getTableColumns()
    {
        return array(
            self::COL_ID => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_INT,
                DBTable::DB_REQUIRED            => true,
                DBTable::DB_PRIMARY             => true,
                DBTable::DB_UNIQUE              => true,
                DBTable::DB_AUTO_INCREMENT      => true
            ),
            self::COL_USERNAME => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE                => 255,
                DBTable::DB_REQUIRED            => true,
                DBTable::DB_UNIQUE              => true
            ),
            self::COL_PASSWORD => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE                => 255,
                DBTable::DB_REQUIRED            => true
            ),
            self::COL_FIRSTNAME => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE                => 255,
                DBTable::DB_REQUIRED            => true
            ),
            self::COL_LASTNAME => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE                => 255,
                DBTable::DB_REQUIRED            => true
            ),
            self::COL_EMAIL => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_VARCHAR,
                DBTable::DB_SIZE                => 255,
                DBTable::DB_REQUIRED            => true
            ),
            self::COL_LAST_ACTIVE => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_BIGINT
            ),
            self::COL_ACTIVE_FEST => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_INT
            ),
            self::COL_DELETED => array(
                DBTable::DB_TYPE                => DBTable::DB_TYPE_INT
            )
        );
    }


    /**
     * Insert user
     *
     * @since 22. February 2014, v. 1.00
     * @return User id
     */
    public function insert($aryData)
    {
        if(isset($aryData[self::COL_PASSWORD]))
        {
            $aryData[self::COL_PASSWORD] = \Beerfest\Core\Crypt::encrypt($aryData[self::COL_PASSWORD]);
        }
        DBTable::insert($aryData);
    }// insert


    /**
     * Get users by users id
     *
     * @since 22. February 2014, v. 1.00
     * @return array Users
     */
    public function getUsers($aryUserIds)
    {
        $aryResult = array();
        $strWhere = sql_where_in(self::COL_ID, $aryUserIds);
        $arySelect = $this->select(array(self::COL_ID, self::COL_FIRSTNAME, self::COL_LASTNAME), $strWhere);

        if(count($arySelect))
        {
            foreach($arySelect as $aryRow)
            {
                $aryResult[$aryRow[self::COL_ID]] = $aryRow;
            }
        }
        return $aryResult;
    }// getUsers


    /**
     * Get user names by id
     *
     * @since 22. February 2014, v. 1.00
     * @return array User names
     */
    public function getUserNames($aryUserIds)
    {
        $aryNames = array();
        $aryUsers = $this->getUsers($aryUserIds);
        if(count($aryUsers))
        {
            foreach($aryUsers as $aryUser)
            {
                $aryNames[$aryUser[self::COL_ID]] = $aryUser[self::COL_FIRSTNAME] . ' ' . $aryUser[self::COL_LASTNAME];
            }
        }
        return $aryNames;
    }// getUserNames


}// UserDB