<?php
require '../../../_config/config.inc.php';
$objConfig = new \Beerfest\Config;
$strVersion = $objConfig->getLibVersion('jqplot');

$strFilePath = 'source/jquery.jqplot.' . $strVersion . '.min.js';
if(file_exists($strFilePath))
{
    readfile($strFilePath);
}