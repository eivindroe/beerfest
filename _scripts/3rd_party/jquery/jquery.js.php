<?php
require '../../../_config/config.inc.php';
$objConfig = new \Beerfest\Config;
$strVersion = $objConfig->getLibVersion('jquery');

$strFilePath = 'source/jquery.' . $strVersion . '.min.js';
if(file_exists($strFilePath))
{
    readfile($strFilePath);
}