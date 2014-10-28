<?php
namespace Beerfest;

class Config
{
    private $aryConfig;

    public function init()
    {
    }

    public function getConfig() {
        if(!isset($this->aryConfig))
        {
            $this->aryConfig = array(
                'db' => array(
                    'name'  => '',
                    'user'  => '',
                    'pass'  => ''
                )
            );
        }
        return $this->aryConfig;
    }// getConfig

    public function getTableName()
    {
        return 'beerfest';
        //return 'wno349523_5';
    }

    public function getName()
    {
        return 'localhost';
        //return 'db.roemedia.no';
    }// getName

    public function getUsername()
    {
        return 'root';
        //return 'wno349523';
    }// getUsername

    public function getPassword()
    {
        return 'cimserver';
        //return 'zy6mb59d83';
    }// getPassword


    /**
     * Get library version
     *
     * @param string $strLib Library
     *
     * @since 16. February 2014, v. 1.00
     * @return string Version for given library
     */
    public function getLibVersion($strLib)
    {
        $strVersion = '';
        switch($strLib)
        {
            case 'jquery':
                $strVersion = '1.2.0';
                break;
            case 'jquery.mobile':
                $strVersion = '1.4.1';
                break;
            case 'jquery-ui':
                $strVersion = '1.11.2';
                break;
            case 'jqplot':
                $strVersion = '1.0.8';
                break;
            default:
                break;
        }
        return $strVersion;
    }// getLibVersion


}// config