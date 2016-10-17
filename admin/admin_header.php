<?php

//use Xmf\Module\Admin;
use Xmf\Module\Helper;

/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence GPL Copyright (c)  (http://xoops.org)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @license     TDM GPL license
 * @author      TDM TEAM DEV MODULE
 *
 * ****************************************************************************
 */

include_once __DIR__ . '/../../../include/cp_header.php';
include_once __DIR__ . '/../class/utilities.php';
include_once __DIR__ . '/../include/common.php';
//require __DIR__ . '/../class/utilities.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleHelper  = Helper::getHelper($moduleDirName);

global $xoopsModule;
$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = XOOPS_URL . '/' . $moduleHelper->getConfig('modicons32');

//Load languages
xoops_loadLanguage('admin', $xoopsModule->getVar('dirname'));
xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname'));
xoops_loadLanguage('main', $xoopsModule->getVar('dirname'));

/** @var Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();
$myts        = MyTextSanitizer::getInstance();
