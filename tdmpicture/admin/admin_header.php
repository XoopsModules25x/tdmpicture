<?php
/**
 * ****************************************************************************
 *  - TDMCreate By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence GPL Copyright (c)  (http://www.xoops.org)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @license     TDM GPL license
 * @author		TDM TEAM DEV MODULE
 *
 * Version : 1.38 Thu 2012/04/12 14:04:25 : Timgno Exp $
 * ****************************************************************************
 */
include '../../../include/cp_header.php';
include_once("../include/functions.php");
include_once("../include/common.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH."/class/tree.php");
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';

if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
    include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
    //return true;
}else{
     redirect_header("../../../admin.php", 5, _AM_MODULEADMIN_MISSING, false);
    //return false;
}

//$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
//$pathImageIcon = XOOPS_URL .'/'. $moduleInfo->getInfo('icons16');
//$pathImageAdmin = XOOPS_URL .'/'. $moduleInfo->getInfo('icons32');
$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

$myts =& MyTextSanitizer::getInstance();

if ($xoopsUser) {
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . "/user.php", 1, _NOPERM);
    exit();
}

//Load languages
xoops_loadLanguage('admin', $xoopsModule->getVar("dirname"));
xoops_loadLanguage('modinfo', $xoopsModule->getVar("dirname"));
xoops_loadLanguage('main', $xoopsModule->getVar("dirname"));
