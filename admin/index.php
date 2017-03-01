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
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
//$moduleDirName = basename(dirname(__DIR__));
//load class
$fileHandler = xoops_getModuleHandler('file', $moduleDirName);
$catHandler  = xoops_getModuleHandler('category', $moduleDirName);
//compte les cat
$numcat = $catHandler->getCount();
//compte les genres en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_display', 0));
$cat_waiting = $catHandler->getCount($criteria);
//compte les fichiers
$numfile = $fileHandler->getCount();
//compte les fichiers en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('file_display', 0));
$file_waiting = $fileHandler->getCount($criteria);

///////////////////////////////////////////////////////////
//apelle du menu admin
//test dossier
// dossier dans uploads
$folder = array(
    TDMPICTURE_UPLOADS_PATH,
    TDMPICTURE_THUMB_PATH,
    TDMPICTURE_CAT_PATH
);
//test GD
if (!extension_loaded('gd')) {
    if (!dl('gd.so')) {
        $veriffile = '<span style="color: red;"><img src="' . $pathIcon16 . '0.png"> ' . _AM_TDMPICTURE_MANAGE_GDERROR . '</a>';
    }
} else {
    $veriffile = '<span style="color: green;"><img src="' . $pathIcon16 . '1.png" >' . _AM_TDMPICTURE_MANAGE_GDOK . '</span>';
}

//$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->addInfoBox(_AM_TDMPICTURE_MANAGE_CAT);
$adminObject->addInfoBoxLine(sprintf(_AM_TDMPICTURE_THEREARE_CAT, $numcat));
if ($cat_waiting == 0) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMPICTURE_THEREARE_CAT_WAITING, '<span class="green">' . 0 . '</span>'), '', 'green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMPICTURE_THEREARE_CAT_WAITING, '<span class="red">' . $cat_waiting . '</span>'), '', 'red');
}

$adminObject->addInfoBox(_AM_TDMPICTURE_MANAGE_FILE);
$adminObject->addInfoBoxLine(sprintf(_AM_TDMPICTURE_THEREARE_FILE, $numfile));
if ($file_waiting == 0) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMPICTURE_THEREARE_FILE_WAITING, '<span class="green">' . 0 . '</span>'), '', 'green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMPICTURE_THEREARE_FILE_WAITING, '<span class="red">' . $file_waiting . '</span>'), '', 'red');
}

//foreach (array_keys($folder) as $i) {
//    $adminObject->addConfigBoxLine($folder[$i], 'folder');
//    $adminObject->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
//}
$adminObject->addConfigBoxLine($veriffile);

$configurator = include __DIR__ . '/../include/config.php';
$classUtility = ucfirst($moduleDirName) . 'Utility';
if (!class_exists($classUtility)) {
    xoops_load('utility', $moduleDirName);
}

//  ---  CHECK FOLDERS ---------------
if (count($configurator['uploadFolders']) > 0) {
    foreach (array_keys($configurator['uploadFolders']) as $i) {
        $adminObject->addConfigBoxLine($configurator['uploadFolders'][$i], 'folder');
    }
}

$adminObject->displayNavigation(basename(__FILE__));
$adminObject->displayIndex();

//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
//TdmpictureUtility::getAdminMenu(0, _AM_TDMPICTURE_INDEXDESC);
//} else {
//include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//loadModuleAdminMenu (0, _AM_TDMPICTURE_INDEXDESC);
//}

require_once __DIR__ . '/admin_footer.php';
