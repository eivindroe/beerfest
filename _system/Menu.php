<?php
namespace Beerfest\Menu;

use Beerfest\Core\Auth;
use Beerfest\User\User;

class Menu
{
    /**
     * Custom constants
     * @var string
     */
    const COL_URL   = 'href';
    const COL_TITLE = 'title';
    const COL_ID    = 'id';

    /**
     * Active user
     * @var User
     */
    private $objActiveUser;

    /**
     * Flag admin mode
     * @var boolean
     */
    private $blnAdmin;

    /**
     * Menu items
     * @var array
     */
    private $aryMenuItems;

    /**
     * Panel items
     * @var array
     */
    private $aryPanelItems;

    /**
     * Get active user
     *
     * @since 22. February 2014, v. 1.00
     * @return User
     */
    private function getActiveUser()
    {
        if(!isset($this->objActiveUser))
        {
            $objAuth = new Auth();
            $this->objActiveUser = $objAuth->getActiveUser();
        }
        return $this->objActiveUser;
    }// getActiveUser


    /**
     * Get active user button
     *
     * @since 28. February 2014, v. 1.00
     * @return string Active user button
     */
    public function getActiveUserButton()
    {
        $objUser = $this->getActiveUser();
        $strHtml = '<a href="' . STR_ROOT . 'user:' . $objUser->getCryptId() . '" data-ajax="false" style="color: #000; text-decoration: none; font-weight: normal">' . $objUser->getFullName() . '</a>';
        return $strHtml;
    }// getActiveUserButton


    /**
     * Check if admin mode
     *
     * @since 22. February 2014, v. 1.00
     * @return boolean True if admin mode, false if not
     */
    private function isAdmin()
    {
        if(!isset($this->blnAdmin))
        {
            $objActiveUser = $this->getActiveUser();
            $this->blnAdmin = $objActiveUser->isAdmin();
        }
        return $this->blnAdmin;
    }// isAdmin


    /**
     * Add menu item
     *
     * @param string $strType Menu type (menu, panel)
     * @param string $strUrl Item url
     * @param string $strTitle Item title
     * @param string $strId Item id
     *
     * @since 22. February 2014, v. 1.00
     * @return array Item
     */
    private function addItem($strType, $strUrl, $strTitle, $strId = '')
    {
        $aryItem = array(
            self::COL_URL => $strUrl,
            self::COL_TITLE => $strTitle,
            self::COL_ID => $strId
        );
        if($strType == 'menu')
        {
            $this->aryMenuItems[] = $aryItem;
        }
        else $this->aryPanelItems[] = $aryItem;
        return $aryItem;
    }// addItem


    /**
     * Add menu item
     *
     * @param string $strUrl Menu item url
     * @param string $strTitle Menu item title
     *
     * @since 22. February 2014, v. 1.00
     * @return array Menu item
     */
    private function addMenuItem($strUrl, $strTitle)
    {
        return $this->addItem('menu', STR_ROOT . $strUrl, $strTitle);
    }// addMenuItem


    /**
     * Add panel item
     *
     * @param array $aryItem Item
     *
     * @since 22. February 2014, v. 1.00
     * @return array Panel item
     */
    private function addPanelItem($aryItem)
    {
        $strUrl = $aryItem[self::COL_URL];
        $strTitle = $aryItem[self::COL_TITLE];
        $strId = '';
        if(isset($aryItem[self::COL_ID]))
        {
            $strId = $aryItem[self::COL_ID];
        }
        return $this->addItem('panel', $strUrl, $strTitle, $strId);
    }// addPanelItem


    /**
     * Get menu items
     *
     * @since 22. February 2014, v. 1.00
     * @return array Menu items
     */
    public function getItems()
    {
        $objUser = $this->getActiveUser();
        if(!isset($this->aryMenuItems))
        {
            $this->addMenuItem('', '');
            $objActiveFest = $objUser->getActiveFest();
            if($objActiveFest)
            {
                $strId = $objActiveFest->getCryptId();
                if($objActiveFest)
                {
                    $this->addMenuItem('fest:' . $strId, $objActiveFest->get(\Beerfest\Fest\FestDB::COL_NAME));
                    $this->addMenuItem('fest:' . $strId . '/items', _ITEMS);
                    if($objUser->isAdmin())
                    {
                        $this->addMenuItem('fest:' . $strId . '/participants', _PARTICIPANTS);
                        $this->addMenuItem('fest:' . $strId . '/result', _FEST_RESULT);
                    }
                }
            }
            else
            {
                $this->aryMenuItems = array();
            }
        }

        return $this->aryMenuItems;
    }// getItems


    /**
     * Get menu items as html
     *
     * @since 22. February 2014, v. 1.00
     * @return string Menu items as html
     */
    public function getItemsAsHtml()
    {
        $strHtml = '';
        $aryItems = $this->getItems();
        if(count($aryItems))
        {
            foreacH($aryItems as $aryItem)
            {
                $strHtml .= '<li><a href="' . $aryItem[self::COL_URL] . '" data-ajax="false" title="' . $aryItem[self::COL_TITLE] . '">' . $aryItem[self::COL_TITLE] . '</a></li>';
            }
        }
        return $strHtml;
    }// getItemsAsHtml


    /**
     * Get panel items
     *
     * @since 22. February 2014, v. 1.00
     * @return array Panel items
     */
    public function getPanelItems()
    {
        if(!isset($this->aryPanelItems))
        {
            $this->addPanelItem(array(
                self::COL_URL   => STR_ROOT . 'user:' . $this->getActiveUser()->getCryptId(),
                self::COL_TITLE => $this->getActiveUser()->getFullName()
            ));
            $blnAdmin = $this->isAdmin();
            if($blnAdmin)
            {
                $this->addPanelItem(array(
                    self::COL_URL   => STR_ROOT . 'fest/list',
                    self::COL_TITLE => _FESTS
                ));
                $this->addPanelItem(array(
                    self::COL_URL   => STR_ROOT . 'user/list',
                    self::COL_TITLE => _USERS
                ));
            }

            $this->addPanelItem(array(
                self::COL_URL   => STR_ROOT . 'logout',
                self::COL_TITLE => _LOG_OUT,
                self::COL_ID    => 'logout'
            ));
        }
        return $this->aryPanelItems;
    }// getPanelItems


    /**
     * Get panel items as html
     *
     * @since 22. February 2014, v. 1.00
     * @return string Panel as html
     */
    public function getPanelAsHtml()
    {
        $strHtml = '';
        $aryItems = $this->getPanelItems();
        if(count($aryItems))
        {
            foreach($aryItems as $aryItem)
            {
                $strId = '';
                if(isset($aryItem[self::COL_ID]))
                {
                    $strId = ' id="' . $aryItem[self::COL_ID]  . '"';
                }
                $strHtml .= '<li><a href="' . $aryItem[self::COL_URL] . '"' . $strId . ' data-ajax="false" title="' . $aryItem[self::COL_TITLE] . '">' . $aryItem[self::COL_TITLE] . '</a></li>';
            }
        }
        return $strHtml;
    }// getPanelAsHtml


}// Menu