<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project http://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @author       XOOPS Development Team
 */

//require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
//require_once __DIR__ . '/common.php';

$moduleDirName = basename(dirname(__DIR__));
$upperDirName  = strtoupper($moduleDirName);
/*
if (!defined($upperDirName . '_DIRNAME')) {
    define($upperDirName . '_DIRNAME', $moduleDirName);
    define($upperDirName . '_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($upperDirName . '_DIRNAME'));
    define($upperDirName . '_URL', XOOPS_URL . '/modules/' . constant($upperDirName . '_DIRNAME'));
    define($upperDirName . '_ADMIN', constant($upperDirName . '_URL') . '/admin/index.php');
    define($upperDirName . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . constant($upperDirName . '_DIRNAME'));
    define($upperDirName . '_AUTHOR_LOGOIMG', constant($upperDirName . '_URL') . '/assets/images/logoModule.png');
}
*/

// Define here the place where main upload path
//$img_dir = $moduleHelper->getConfig('uploaddir');

//define($upperDirName . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
//define("ADSLIGHT_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define($upperDirName . '_UPLOAD_PATH_CONFIG', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash

//Configurator
return array(
    'name'          => 'Module Configurator',
    'uploadFolders' => array(
        constant($upperDirName . '_UPLOAD_PATH_CONFIG'),
        constant($upperDirName . '_UPLOAD_PATH_CONFIG') . '/thumb',
        constant($upperDirName . '_UPLOAD_PATH_CONFIG') . '/cat',
    ),
    'copyFiles'     => array(
        constant($upperDirName . '_UPLOAD_PATH_CONFIG') . '/thumb',
        constant($upperDirName . '_UPLOAD_PATH_CONFIG') . '/cat',
    ),

    'templateFolders' => array(
        '/templates/',
        '/templates/blocks/'
    ),
    'oldFiles'        => array(
        '/admin/admin.css',
        '/changelog.txt',
        '/include/update.php',
        '/include/functions.php',
    ),
    'oldFolders'      => array(
        '/css',
        '/fpdf',
        '/images',
        '/js',
    ),
);

// module information
$modCopyright
    = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . constant($upperDirName . '_AUTHOR_LOGOIMG') . "' alt='XOOPS Project' /></a>";

//
//xoops_loadLanguage('common', $moduleDirName);
//
//xoops_load('constants', $moduleDirName);
//xoops_load('utility', $moduleDirName);
//xoops_load('XoopsRequest');
//xoops_load('XoopsFilterInput');
