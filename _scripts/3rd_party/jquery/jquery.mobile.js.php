<?php
require '../../../_config/config.inc.php';
$objConfig = new \Beerfest\Config;
$strVersion = $objConfig->getLibVersion('jquery.mobile');

$strFilePath = 'source/jquery.mobile-' . $strVersion . '.min.js';
if(file_exists($strFilePath))
{
    readfile($strFilePath);
}