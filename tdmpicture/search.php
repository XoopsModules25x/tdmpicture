<?php
/**
 * ****************************************************************************
 *  - TDMDownloads By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - GNU Licence Copyright (c)  (www.xoops.org)
 *
 * La licence GNU GPL, garanti à l'utilisateur les droits suivants
 *
 * 1. La liberté d'exécuter le logiciel, pour n'importe quel usage,
 * 2. La liberté de l' étudier et de l'adapter à ses besoins,
 * 3. La liberté de redistribuer des copies,
 * 4. La liberté d'améliorer et de rendre publiques les modifications afin
 * que l'ensemble de la communauté en bénéficie.
 *
 * @copyright   http://www.tdmxoops.net
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		TDM (G.Mage); TEAM DEV MODULE
 *
 * ****************************************************************************
 */

include_once 'header.php';
$myts =& MyTextSanitizer::getInstance();
// template d'affichage
$xoopsOption['template_main'] = 'tdmpicture_liste.html';
include_once XOOPS_ROOT_PATH.'/header.php';
//paramètres:

$xoopsTpl->assign('dirname', $mydirname);
$xoopsTpl->assign('tree_title', _MD_TDMPICTURE_LISTE);
//perm
$xoopsTpl->assign('perm_submit', $perm_submit);
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_playlist', $perm_playlist);
$xoopsTpl->assign('perm_dl', $perm_dl);
$xoopsTpl->assign('perm_cat', $perm_cat);

$userid = isset($_REQUEST['userid']) ? intval($_REQUEST['userid']) : false;

//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');
$member_handler =& xoops_gethandler('member');


		//news categorie
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('cat_display', 1));
		//$criteria->add(new Criteria('cat_index', 1));
		$criteria->setSort('cat_weight');
		$criteria->setOrder('DESC');
		//$criteria->add(new Criteria('cat_display', 1));
		$cat_arr = $cat_handler->getall($criteria);
		$mytree = new TDMCATObjectTree($cat_arr, 'cat_id', 'cat_pid');
		$display_cat = $mytree->makeCatBox($file_handler, 'cat_title','-', false);
		
		$numcat = $cat_handler->getCount($criteria);
		$xoopsTpl->assign('tree_display', $display_cat);
	//	$xoopsTpl->assign('tree_title', $GLOBALS['cat_title']);
		//

$criteria_2 = new CriteriaCompo();
$criteria_2->add(new Criteria('file_display', 1));

if (!empty($userid)){
    $criteria_2->add(new Criteria('file_uid', $userid));
    $arguments = 'userid=' . $userid;
}


if (isset($_REQUEST['limit'])) {
 	$criteria_2->setLimit($_REQUEST['limit']);
 	$limit = $_REQUEST['limit'];
} else {
 	$criteria_2->setLimit($xoopsModuleConfig['tdmpicture_page']);
 	$limit = $xoopsModuleConfig['tdmpicture_page'];
}
if (isset($_REQUEST['start'])) {
	$criteria_2->setStart($_REQUEST['start']);
	$start = $_REQUEST['start'];
} else {
	$criteria_2->setStart(0);
 	$start = 0;
}
$criteria_2->setGroupby("file_uid");
//pour faire une jointure de table   
$file_arr = $file_handler->getGroupby($criteria_2);

$numrows = count($file_handler->getCount($criteria_2));
$xoopsTpl->assign('numrows', $numrows);

$keywords = '';
$file = array();
foreach (array_keys($file_arr) as $i) {
   // $tdmdownloads_tab['file_id'] = $tdmdownloads_arr[$i]->getVar('file_id');
   // $tdmdownloads_tab['file_cat'] = $tdmdownloads_arr[$i]->getVar('file_cat'); 
   // $tdmdownloads_tab['file_file'] = $tdmdownloads_arr[$i]->getVar('file_file');
   // $tdmdownloads_tab['file_title'] = $tdmdownloads_arr[$i]->getVar('file_title');
    $file['file_uid'] = $file_arr[$i]->getVar('file_uid');
  //compte le nombre de photo
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('file_uid', $file_arr[$i]->getVar('file_uid')));
	$file['file_usercount'] = $file_handler->getCount($criteria);
	//membre
	$members =& $member_handler->getUser($file_arr[$i]->getVar('file_uid'));
	if($members) {
	
    $file['user_uname'] = $members->getVar('uname');
	$file['user_name'] = $members->getVar('name');
	//if ($members->getVar('user_avatar') != "blank.gif") {
	//$file['user_avatarurl'] = XOOPS_URL.'/uploads/'.$members->getVar('user_avatar');
	//} else {
	//$file['user_avatarurl'] = TDMMP_IMAGES_URL.'/imguser.png';
	
	//poster	  
	$poster_image =  XOOPS_ROOT_PATH.'/uploads/'.$members->getVar('user_avatar');
	if (file_exists($poster_image) && $members->getVar('user_avatar') != 'blank.gif') {
	$file['user_img'] = "<img class='img'src='".XOOPS_URL."/uploads/".$members->getVar('user_avatar')."' height='60px' title=".$members->getVar('uname')." style='border: 1px solid #CCC;' alt=".$members->getVar('uname').">";
	} else {
	$file['user_img'] = "<img class='img' src='".TDMPICTURE_IMAGES_URL."/user.gif'  height='60px' style='border: 1px solid #CCC' title='Anonyme' alt='Anonyme'>";
	}
	}
	
	
	
	$xoopsTpl->append('file', $file);
}

if ( $numrows > $limit ) {
	$pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', $arguments);
 	$pagenav = $pagenav->renderNav(4);
} else {
 	$pagenav = '';
}
$xoopsTpl->assign('pagenav', $pagenav);
// référencement
// titre de la page
tdmpicture_header();
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()));

if(isset($xoTheme) && is_object($xoTheme)) {
$xoTheme->addMeta( 'meta', 'keywords', tdmpicture_keywords($xoopsModuleConfig['tdmpicture_keywords']));
$xoTheme->addMeta( 'meta', 'description', $xoopsModuleConfig['tdmpicture_description']);
} else {	// Compatibility for old Xoops versions
$xoopsTpl->assign('xoops_meta_keywords', tdmpicture_keywords($xoopsModuleConfig['tdmpicture_keywords']));
$xoopsTpl->assign('xoops_meta_description', $xoopsModuleConfig['tdmpicture_description']);
}
include XOOPS_ROOT_PATH.'/footer.php';
?>