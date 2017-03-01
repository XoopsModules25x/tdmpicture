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
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Include
 * @subpackage      Functions
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

use Xmf\Module\Helper;

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
$moduleDirName = basename(dirname(__DIR__));
$upperDirName  = strtoupper($moduleDirName);
$moduleHelper  = Helper::getHelper($moduleDirName);

define('TDMPICTURE_DIRNAME', basename(dirname(__DIR__)));
define('TDMPICTURE_URL', XOOPS_URL . '/modules/' . TDMPICTURE_DIRNAME);
define('TDMPICTURE_IMAGES_URL', TDMPICTURE_URL . '/assets/images');
define('TDMPICTURE_UPLOADS_URL', XOOPS_URL . $moduleHelper->getConfig('tdm_upload_path'));
define('TDMPICTURE_THUMB_URL', XOOPS_URL . $moduleHelper->getConfig('tdm_upload_thumb'));
define('TDMPICTURE_CAT_URL', TDMPICTURE_UPLOADS_URL . '/cat');

define('TDMPICTURE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . TDMPICTURE_DIRNAME);
define('TDMPICTURE_IMAGES_PATH', TDMPICTURE_ROOT_PATH . '/assets/images');
define('TDMPICTURE_UPLOADS_PATH', XOOPS_ROOT_PATH . $moduleHelper->getConfig('tdm_upload_path'));
define('TDMPICTURE_THUMB_PATH', XOOPS_ROOT_PATH . $moduleHelper->getConfig('tdm_upload_thumb'));
define('TDMPICTURE_CAT_PATH', TDMPICTURE_UPLOADS_PATH . '/cat');

//define module options
define('TDMPICTURE_RSS', $moduleHelper->getConfig('tdmpicture_rss'));
define('TDMPICTURE_SOCIAL', $moduleHelper->getConfig('tdmpicture_social'));
define('TDMPICTURE_PDF', $moduleHelper->getConfig('tdmpicture_pdf'));
//define("TDMPICTURE_DISPLAY_CAT", $moduleHelper->getConfig('tdmpicture_cat_display'));
//define("TDMPICTURE_THUMB_DECO", $moduleHelper->getConfig('tdmpicture_thumb_deco'));
define('TDMPICTURE_SLIDE_WIDTH', $moduleHelper->getConfig('tdmpicture_slide_width'));
define('TDMPICTURE_SLIDE_HEIGHT', $moduleHelper->getConfig('tdmpicture_slide_height'));
define('TDMPICTURE_WIDTH', $moduleHelper->getConfig('tdmpicture_width'));
define('TDMPICTURE_HEIGHT', $moduleHelper->getConfig('tdmpicture_heigth'));
define('TDMPICTURE_THUMB_WIDTH', $moduleHelper->getConfig('tdmpicture_thumb_width'));
define('TDMPICTURE_THUMB_HEIGTH', $moduleHelper->getConfig('tdmpicture_thumb_heigth'));

include_once TDMPICTURE_ROOT_PATH . '/class/utility.php';
include_once TDMPICTURE_ROOT_PATH . '/class/thumbnail.inc.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once TDMPICTURE_ROOT_PATH . '/class/tree.php';
include_once TDMPICTURE_ROOT_PATH . '/class/tree2.php';

//enregistre le style de vue pour la session_cache_expire
if (!isset($_SESSION['tdmpicture_display'])) {
    $_SESSION['tdmpicture_display'] = $moduleHelper->getConfig('tdmpicture_display');
    define('TDMPICTURE_DISPLAY', $moduleHelper->getConfig('tdmpicture_display'));
} else {
    define('TDMPICTURE_DISPLAY', $_SESSION['tdmpicture_display']);
}

//============================================

if (!defined($upperDirName . '_DIRNAME')) {
    define($upperDirName . '_DIRNAME', $moduleDirName);
    define($upperDirName . '_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($upperDirName . '_DIRNAME'));
    define($upperDirName . '_URL', XOOPS_URL . '/modules/' . constant($upperDirName . '_DIRNAME'));
    define($upperDirName . '_ADMIN', constant($upperDirName . '_URL') . '/admin/index.php');
    define($upperDirName . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($upperDirName . '_DIRNAME'));
    define($upperDirName . '_AUTHOR_LOGOIMG', constant($upperDirName . '_URL') . '/assets/images/logoModule.png');
}


// Define here the place where main upload path

//$img_dir = $moduleHelper->getConfig('uploaddir'];

//define($upperDirName . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . constant($upperDirName . '_DIRNAME')); // WITHOUT Trailing slash
//define("ADSLIGHT_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
//define($upperDirName . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . constant($upperDirName . '_DIRNAME')); // WITHOUT Trailing slash
