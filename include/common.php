<?php

use Xmf\Module\Helper;

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
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$moduleDirName = basename(dirname(__DIR__));
$modir = strtoupper($moduleDirName);
$helper = Helper::getHelper($moduleDirName);

define('TDMPICTURE_DIRNAME', basename(dirname(__DIR__)));
define('TDMPICTURE_URL', XOOPS_URL . '/modules/' . TDMPICTURE_DIRNAME);
define('TDMPICTURE_IMAGES_URL', TDMPICTURE_URL . '/assets/images');
define('TDMPICTURE_UPLOADS_URL', XOOPS_URL . $helper->getConfig('tdm_upload_path'));
define('TDMPICTURE_THUMB_URL', XOOPS_URL . $helper->getConfig('tdm_upload_thumb'));
define('TDMPICTURE_CAT_URL', TDMPICTURE_UPLOADS_URL . '/cat');

define('TDMPICTURE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMPICTURE_DIRNAME);
define('TDMPICTURE_IMAGES_PATH', TDMPICTURE_ROOT_PATH . '/assets/images');
define('TDMPICTURE_UPLOADS_PATH', XOOPS_ROOT_PATH . $helper->getConfig('tdm_upload_path'));
define('TDMPICTURE_THUMB_PATH', XOOPS_ROOT_PATH . $helper->getConfig('tdm_upload_thumb'));
define('TDMPICTURE_CAT_PATH', TDMPICTURE_UPLOADS_PATH . '/cat');

//define option du module
define('TDMPICTURE_RSS', $helper->getConfig('tdmpicture_rss'));
define('TDMPICTURE_SOCIAL', $helper->getConfig('tdmpicture_social'));
define('TDMPICTURE_PDF', $helper->getConfig('tdmpicture_pdf'));
//define("TDMPICTURE_DISPLAY_CAT", $helper->getConfig('tdmpicture_cat_display'));
//define("TDMPICTURE_THUMB_DECO", $helper->getConfig('tdmpicture_thumb_deco'));
define('TDMPICTURE_SLIDE_WIDTH', $helper->getConfig('tdmpicture_slide_width'));
define('TDMPICTURE_SLIDE_HEIGHT', $helper->getConfig('tdmpicture_slide_height'));
define('TDMPICTURE_WIDTH', $helper->getConfig('tdmpicture_width'));
define('TDMPICTURE_HEIGHT', $helper->getConfig('tdmpicture_heigth'));
define('TDMPICTURE_THUMB_WIDTH', $helper->getConfig('tdmpicture_thumb_width'));
define('TDMPICTURE_THUMB_HEIGTH', $helper->getConfig('tdmpicture_thumb_heigth'));

include_once TDMPICTURE_ROOT_PATH . '/class/utilities.php';
include_once TDMPICTURE_ROOT_PATH . '/class/thumbnail.inc.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once TDMPICTURE_ROOT_PATH . '/class/tree.php';
include_once TDMPICTURE_ROOT_PATH . '/class/tree2.php';

//enregistre le style de vue pour la session_cache_expire
if (!isset($_SESSION['tdmpicture_display'])) {
    $_SESSION['tdmpicture_display'] = $helper->getConfig('tdmpicture_display');
    define('TDMPICTURE_DISPLAY', $helper->getConfig('tdmpicture_display'));
} else {
    define('TDMPICTURE_DISPLAY', $_SESSION['tdmpicture_display']);
}

//============================================

if (!defined($modir . '_DIRNAME')) {
    define($modir . '_DIRNAME', $moduleDirName);
    define($modir . '_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($modir . '_DIRNAME'));
    define($modir . '_URL', XOOPS_URL . '/modules/' . constant($modir . '_DIRNAME'));
    define($modir . '_ADMIN', constant($modir . '_URL') . '/admin/index.php');
    define($modir . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($modir . '_DIRNAME'));
    define($modir . '_AUTHOR_LOGOIMG', constant($modir . '_URL') . '/assets/images/logoModule.png');
}


// Define here the place where main upload path

//$img_dir = $helper->getConfig('uploaddir'];

define($modir . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . constant($modir . '_DIRNAME')); // WITHOUT Trailing slash
//define("ADSLIGHT_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define($modir . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . constant($modir . '_DIRNAME')); // WITHOUT Trailing slash
