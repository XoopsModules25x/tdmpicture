<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Include
 * @subpackage      Functions
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: common.php 10929 2013-01-27 15:42:57Z lord_venom $
 */

if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

define("TDMPICTURE_DIRNAME", basename(dirname(dirname(__FILE__))));
define("TDMPICTURE_URL", XOOPS_URL . '/modules/' . TDMPICTURE_DIRNAME);
define("TDMPICTURE_IMAGES_URL", TDMPICTURE_URL . '/images/');
define("TDMPICTURE_UPLOADS_URL", XOOPS_URL . $xoopsModuleConfig['tdm_upload_path']);
define("TDMPICTURE_THUMB_URL", XOOPS_URL . $xoopsModuleConfig['tdm_upload_thumb']);
define("TDMPICTURE_CAT_URL", TDMPICTURE_URL . '/upload/cat/');

define("TDMPICTURE_ROOT_PATH", XOOPS_ROOT_PATH . '/modules/' . TDMPICTURE_DIRNAME);
define("TDMPICTURE_IMAGES_PATH", TDMPICTURE_ROOT_PATH . '/images/');
define("TDMPICTURE_UPLOADS_PATH", XOOPS_ROOT_PATH . $xoopsModuleConfig['tdm_upload_path']);
define("TDMPICTURE_THUMB_PATH", XOOPS_ROOT_PATH . $xoopsModuleConfig['tdm_upload_thumb']);
define("TDMPICTURE_CAT_PATH", XOOPS_ROOT_PATH . '/modules/' . TDMPICTURE_DIRNAME. '/upload/cat/');

//define option du module
define("TDMPICTURE_RSS", $xoopsModuleConfig['tdmpicture_rss']);
define("TDMPICTURE_SOCIAL", $xoopsModuleConfig['tdmpicture_social']);
define("TDMPICTURE_PDF", $xoopsModuleConfig['tdmpicture_pdf']);
//define("TDMPICTURE_DISPLAY_CAT", $xoopsModuleConfig['tdmpicture_cat_display']);
//define("TDMPICTURE_THUMB_DECO", $xoopsModuleConfig['tdmpicture_thumb_deco']);
define("TDMPICTURE_SLIDE_WIDTH", $xoopsModuleConfig['tdmpicture_slide_width']);
define("TDMPICTURE_SLIDE_HEIGHT", $xoopsModuleConfig['tdmpicture_slide_height']);
define("TDMPICTURE_WIDTH", $xoopsModuleConfig['tdmpicture_width']);
define("TDMPICTURE_HEIGHT", $xoopsModuleConfig['tdmpicture_heigth']);
define('TDMPICTURE_THUMB_WIDTH', $xoopsModuleConfig['tdmpicture_thumb_width']);
define('TDMPICTURE_THUMB_HEIGTH', $xoopsModuleConfig['tdmpicture_thumb_heigth']);


include_once TDMPICTURE_ROOT_PATH . '/include/functions.php';
include_once TDMPICTURE_ROOT_PATH . '/class/thumbnail.inc.php';
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH."/class/tree.php";
include_once TDMPICTURE_ROOT_PATH . '/class/tree.php';
include_once TDMPICTURE_ROOT_PATH . '/class/tree2.php';

//enregistre le style de vue pour la session_cache_expire
		if(!isset($_SESSION['tdmpicture_display'])) {
    	$_SESSION['tdmpicture_display'] = $xoopsModuleConfig['tdmpicture_display'];
		define('TDMPICTURE_DISPLAY', $xoopsModuleConfig['tdmpicture_display'] );
		} else {
		define('TDMPICTURE_DISPLAY', $_SESSION['tdmpicture_display'] );
		}

?>
