<?php
/**
 * show_image.php
 *
 * Example utility file for dynamically displaying images
 *
 * @author      Ian Selby
 */

//reference thumbnail class
include_once __DIR__ . '/thumbnail.inc.php';

$thumb = new Thumbnail($_GET['filename']);
$thumb->resize($_GET['width'], $_GET['height']);
$thumb->show();
exit;
