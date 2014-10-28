<?php
namespace Beerfest\User;

use Beerfest\Core\Form\Controller;
use Beerfest\User\UserDB;
use Beerfest\User\User;

class Form extends Controller
{
    /**
     * Custom columns
     * @var string
     */
    const COL_NEW_PASSWORD = 'new_password';
    const COL_REPEAT_PASSWORD = 'repeat_password';

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
        $strAction = ($objUser->getId() ? ':' . $objUser->getCryptId() : ':add');
        parent::__construct('user', 'post', 'user' . $strAction);
        $this->loadElements();
        if($objUser->getId())
        {
            $this->setDefaults($objUser->getAll());
        }
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
     * Load form elements
     *
     * @since 27. February 2014, v. 1.00
     * @return void
     */
    public function loadElements()
    {
        $objUser = $this->getUser();

        $objUsername = $this->addTextField(UserDB::COL_USERNAME, _USERNAME);
        if($objUser->getId())
        {
            $objUsername->setDisabled(true);
        }
        else
        {
            $objUsername->setPlaceholder(_USERNAME);
            $objUsername->setRequired(true);
        }

        $objFirstname = $this->addTextField(UserDB::COL_FIRSTNAME, _FIRSTNAME);
        $objFirstname->setPlaceholder(_FIRSTNAME);
        $objFirstname->setRequired(true);

        $objLastname = $this->addTextField(UserDB::COL_LASTNAME, _LASTNAME);
        $objLastname->setPlaceholder(_LASTNAME);
        $objLastname->setRequired(true);

        $objEmail = $this->addTextField(UserDB::COL_EMAIL, _EMAIL);
        $objEmail->setPlaceholder(_EMAIL);
        $objEmail->setRequired(true);

        $intId = $objUser->getId();

        if($intId)
        {
            if($objUser->getId() == \Beerfest\Core\Auth::getActiveUserId())
            {
                $objNewPassword = $this->addPassword(self::COL_NEW_PASSWORD, _NEW_PASSWORD);
                $objNewPassword->setPlaceholder(_NEW_PASSWORD);
                $objRepeatPassword = $this->addPassword(self::COL_REPEAT_PASSWORD, _REPEAT_PASSWORD);
                $objRepeatPassword->setPlaceholder(_REPEAT_PASSWORD);
            }
        }
        else
        {
            $objPassword = $this->addPassword(UserDB::COL_PASSWORD, _PASSWORD);
            $objPassword->setPlaceholder(_PASSWORD);
            $objPassword->setRequired(true);
        }

        $this->addButtonSubmit();
        $this->addButtonReset();
        $this->addButtonCancel();
    }// loadElements


    /**
     * Validate posted user data
     *
     * @param array $aryPost Posted data
     *
     * @since 27. February 2014, v. 1.00
     * @return boolean True if valid data, false if not
     */
    public function validate($aryPost)
    {
        $objUser = $this->getUser();
        $blnValid = true;
        if($objUser->getId())
        {
            if(isset($aryPost[self::COL_NEW_PASSWORD]) || isset($aryPost[self::COL_REPEAT_PASSWORD]))
            {
                if(!isset($aryPost[self::COL_REPEAT_PASSWORD]) || (isset($aryPost[self::COL_REPEAT_PASSWORD]) &&
                        $aryPost[self::COL_REPEAT_PASSWORD] !== $aryPost[self::COL_NEW_PASSWORD]))
                {
                    $blnValid = false;
                    $this->setError(self::COL_NEW_PASSWORD, _PASSWORDS_DOES_NOT_MATCH);
                }

                $aryPost[UserDB::COL_PASSWORD] = $aryPost[self::COL_NEW_PASSWORD];
                unset($aryPost[self::COL_NEW_PASSWORD]);
                unset($aryPost[self::COL_REPEAT_PASSWORD]);
            }
        }
        else
        {
            $objDb = new UserDB();
            $aryUser = $objDb->select(array($objDb::COL_USERNAME), sql_where($objDb::COL_USERNAME, $aryPost[$objDb::COL_USERNAME]));
            if(count($aryUser))
            {
                $blnValid = false;
                $this->setError($objDb::COL_USERNAME, _USERNAME_ALREADY_IN_USE);
            }
        }

        if($blnValid === true)
        {
            $blnValid = parent::validate($aryPost);
        }
        return $blnValid;
    }// validate


}// Form