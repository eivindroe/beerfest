<?php
require '../../../_config/config.inc.php';
$objConfig = new \Beerfest\Config;
$strVersion = $objConfig->getLibVersion('jqplot');

$strFilePath = 'source/jquery.jqplot.' . $strVersion . '.min.js';
if(file_exists($strFilePath))
{
    readfile($strFilePath);
}

// Plugins
readfile('source/plugins/jqplot.barRenderer.min.js');
readfile('source/plugins/jqplot.highlighter.min.js');
readfile('source/plugins/jqplot.cursor.min.js');
readfile('source/plugins/jqplot.pointLabels.min.js');
readfile('source/plugins/jqplot.enhancedLegendRenderer.min.js');
readfile('source/plugins/jqplot.dateAxisRenderer.min.js');
readfile('source/plugins/jqplot.canvasTextRenderer.min.js');
readfile('source/plugins/jqplot.canvasAxisTickRenderer.min.js');
readfile('source/plugins/jqplot.categoryAxisRenderer.min.js');