<?php
namespace Beerfest\User;

use Beerfest\User\UserDB;
use Beerfest\Core\Form\Controller;

class LoginForm extends Controller
{
    /**
     * Constructor
     *
     * @since 25. February 2014, v. 1.00
     */
    public function __construct()
    {
        parent::__construct('login', 'post', 'user/login');
        $this->loadElements();
    }// __construct


    /**
     * Load form elements
     *
     * @since 25. February 2014, v. 1.00
     * @return void
     */
    private function loadElements()
    {
        $objUsername = $this->addTextField(UserDB::COL_USERNAME, _USERNAME);
        $objUsername->setPlaceholder(_USERNAME);
        $objUsername->setRequired(true);

        $objPassword = $this->addPassword(UserDB::COL_PASSWORD, _PASSWORD);
        $objPassword->setPlaceholder(_PASSWORD);
        $objPassword->setRequired(true);

        $this->addButtonSubmit(_LOG_IN);
        $this->addButtonReset();
    }// loadElements


}// LoginForm