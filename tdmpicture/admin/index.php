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



include 'admin_header.php';
xoops_cp_header();

//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');
//compte les cat
$numcat = $cat_handler->getCount();
//compte les genres en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_display', 0));
$cat_waiting = $cat_handler->getCount($criteria);
//compte les fichiers
$numfile = $file_handler->getCount();
//compte les fichiers en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('file_display', 0));
$file_waiting = $file_handler->getCount($criteria);

///////////////////////////////////////////////////////////
//apelle du menu admin
//test dossier 
// dossier dans uploads
$folder = array(TDMPICTURE_UPLOADS_PATH, TDMPICTURE_THUMB_PATH, TDMPICTURE_CAT_PATH);
//test GD
 if (!extension_loaded('gd')) {
  if (!dl('gd.so')) {
$veriffile = '<span style="color: red;"><img src="./../images/off.gif"> '._AM_TDMPICTURE_MANAGE_GDERROR.'</a>';
    }
}else {
$veriffile = '<span style="color: green;"><img src="./../images/on.gif" >'._AM_TDMPICTURE_MANAGE_GDOK.'</span>';
}

	$index_admin = new ModuleAdmin();
    $index_admin->addInfoBox(_AM_TDMPICTURE_MANAGE_CAT);
    $index_admin->addInfoBox(_AM_TDMPICTURE_MANAGE_FILE);
    $index_admin->addInfoBoxLine(_AM_TDMPICTURE_MANAGE_CAT, _AM_TDMPICTURE_THEREARE_CAT, $numcat);
    $index_admin->addInfoBoxLine(_AM_TDMPICTURE_MANAGE_FILE, _AM_TDMPICTURE_THEREARE_FILE, $numfile);
    if ($cat_waiting == 0){
        $index_admin->addInfoBoxLine(_AM_TDMPICTURE_MANAGE_CAT, _AM_TDMPICTURE_THEREARE_CAT_WAITING, 0, 'Green');
    }else{
        $index_admin->addInfoBoxLine(_AM_TDMPICTURE_MANAGE_CAT, _AM_TDMPICTURE_THEREARE_CAT_WAITING, $cat_waiting, 'Red');
    }
    if ($file_waiting == 0){
        $index_admin->addInfoBoxLine(_AM_TDMPICTURE_MANAGE_FILE, _AM_TDMPICTURE_THEREARE_FILE_WAITING, 0, 'Green');
    }else{
        $index_admin->addInfoBoxLine(_AM_TDMPICTURE_MANAGE_FILE, _AM_TDMPICTURE_THEREARE_FILE_WAITING, $file_waiting, 'Red');
    }

    foreach (array_keys( $folder) as $i) {
        $index_admin->addConfigBoxLine($folder[$i], 'folder');
        $index_admin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
    }
	$index_admin->addConfigBoxLine($veriffile);
    echo $index_admin->addNavigation('index.php');
    echo $index_admin->renderIndex();


//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
//TDMPicture_adminmenu(0, _AM_TDMPICTURE_INDEXDESC);
//} else {
//include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//loadModuleAdminMenu (0, _AM_TDMPICTURE_INDEXDESC);
//}

xoops_cp_footer();
?>