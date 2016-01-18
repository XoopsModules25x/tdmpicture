<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.tdmxoops.net)
 *
 * Cette licence, contient des limitations!!!
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier,
 * 3. Vous ne devez pas le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer et de rendre publiques les modifications
 *
 * @license     TDMFR PRO license
 * @author		TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();

$i = 1;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]["link"]  = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';

//$i++;
//$adminmenu[$i]["title"] =  _MI_ISTATS_INDEX;
//$adminmenu[$i]["link"]  = "admin/main.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';

$i++;
$adminmenu[$i]["title"] =  _MI_TDMPICTURE_ADMENUCAT;
$adminmenu[$i]["link"]  = "admin/cat.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';

$i++;
$adminmenu[$i]["title"] =  _MI_TDMPICTURE_ADMENUFILE;
$adminmenu[$i]["link"]  = "admin/files.php";
$adminmenu[$i]["icon"]  = 'images/decos/min_file.png';

$i++;
$adminmenu[$i]["title"] = _MI_TDMPICTURE_ADMENUPERMISSIONS;
$adminmenu[$i]["link"]  = "admin/permissions.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/permissions.png';

//1.07
$i++;
$adminmenu[$i]['title'] = _MI_TDMPICTURE_ADMENUIMPORT;
$adminmenu[$i]["link"]  = "admin/import.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/compfile.png';

$i++;
$adminmenu[$i]['title'] = _MI_TDMPICTURE_ADMENUBATCH;
$adminmenu[$i]["link"]  = "admin/batch.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/compfile.png';

$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';

 
//$adminmenu[0]['title'] = _MI_TDMPICTURE_ADMENUINDEX;
//$adminmenu[0]['link'] = "admin/index.php";
//$adminmenu[0]['icon']  = 'images/decos/min_index.png';
//
//$adminmenu[1]['title'] = _MI_TDMPICTURE_ADMENUCAT;
//$adminmenu[1]['link'] = "admin/cat.php";
//$adminmenu[1]['icon']  = 'images/decos/min_cat.png';

//$adminmenu[2]['title'] = _MI_TDMPICTURE_ADMENUFILE;
//$adminmenu[2]['link'] = "admin/files.php";
//$adminmenu[2]['icon']  = 'images/decos/min_file.png';

//$adminmenu[3]['title'] = _MI_TDMPICTURE_ADMENUPERMISSIONS;
//$adminmenu[3]['link'] = "admin/permissions.php";
//$adminmenu[3]['icon']  = 'images/decos/min_permissions.png';
//
//$adminmenu[4]['title'] = _MI_TDMPICTURE_ADMENUABOUT;
//$adminmenu[4]['link'] = "admin/about.php";
//$adminmenu[4]['icon']  = 'images/decos/min_about.png';
;
