<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.tdmxoops.net)
 *
 * Cette licence, contient des limitations!!!
 *
 * 1. Vous devez poss�der une permission d'ex�cuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' �tudier,
 * 3. Vous ne devez pas le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la libert� de l'am�liorer et de rendre publiques les modifications
 *
 * @license     TDMFR PRO license
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

if (!isset($moduleDirName)) {
    $moduleDirName = basename(dirname(__DIR__));
}

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

//$moduleHelper->loadLanguage('modinfo');

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMPICTURE_ADMENUCAT,
    'link'  => 'admin/cat.php',
    'icon'  => $pathIcon32 . '/category.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMPICTURE_ADMENUFILE,
    'link'  => 'admin/files.php',
    //    'icon'  => $pathModIcon32 . '/decos/min_file.png'
    'icon'  => 'assets/images/decos/min_file.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMPICTURE_ADMENUPERMISSIONS,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32 . '/permissions.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMPICTURE_ADMENUIMPORT,
    'link'  => 'admin/import.php',
    'icon'  => $pathIcon32 . '/compfile.png'
);

$adminmenu[] = array(
    'title' => _MI_TDMPICTURE_ADMENUBATCH,
    'link'  => 'admin/batch.php',
    'icon'  => $pathIcon32 . '/exec.png'
);

$adminmenu[] = array(
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
);
