<?php
namespace Beerfest\Core;

class Request
{
    const SESSION_REFERER = 'referer';

    /**
     * Get page referer
     *
     * @since 22. February 2014, v. 100
     * @return string Page referer
     */
    public static function getReferer()
    {
        $strReferer = '';
        if(isset($_SERVER['HTTP_REFERER']))
        {
            $strReferer = $_SERVER['HTTP_REFERER'];

            if(isset($_SESSION[self::SESSION_REFERER]))
            {
                $strSessionRef = $_SESSION[self::SESSION_REFERER];
                if($strSessionRef != $strReferer)
                {
                    $strReferer = $strSessionRef;
                }
            }
            else
            {
                $_SESSION[self::SESSION_REFERER] = $strReferer;
            }
        }
        return $strReferer;
    }// getReferer


}// Request