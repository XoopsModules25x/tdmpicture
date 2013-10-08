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
 * @author		TDMFR ; TEAM DEV MODULE 
 *
 * ****************************************************************************
 */
 
include_once "header.php";
$myts =& MyTextSanitizer::getInstance();

$xoopsOption['template_main'] = 'tdmpicture_index.html';
include_once XOOPS_ROOT_PATH.'/header.php';

$xoopsTpl->assign('dirname', $mydirname);


 $op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
 $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
 $tris = isset($_REQUEST['tris']) ? $_REQUEST['tris'] : 'file_indate';
 $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
 $ut = isset($_REQUEST['ut']) ? $_REQUEST['ut'] : false;

//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

//perm
$xoopsTpl->assign('perm_submit', $perm_submit);
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_playlist', $perm_playlist);
$xoopsTpl->assign('perm_dl', $perm_dl);
$xoopsTpl->assign('perm_cat', $perm_cat);

//$xoopsTpl->assign('nav_alpha', tdmpicture_NavAlpha(@$_REQUEST['CT'], 'artiste.php'));

//mode de visualisation
//$xoopsTpl->assign('view_mode', $view_mode = isset($_REQUEST['view_mode']) ? $_REQUEST['view_mode'] : 'block' );
$xoopsTpl->assign('tris', $tris);
$xoopsTpl->assign('order', $order);
//$xoopsTpl->assign('slide_width', $xoopsModuleConfig['tdmpicture_slide_width']);
//$xoopsTpl->assign('slide_height', $xoopsModuleConfig['tdmpicture_slide_height']);
$xoopsTpl->assign('baseurl', $_SERVER['PHP_SELF']);
$xoopsTpl->assign('display', $xoopsModuleConfig['tdmpicture_display'] );

 switch($op) {		

  case "list": 
  default:
  
    //securiter si aucun n'est choisis
	if (empty($ut)) {
	redirect_header('index.php', 2, _MD_TDMPICTURE_NOPERM);
	exit();
    }

$xoopsTpl->assign('cat_id', true);
$xoopsTpl->assign('cat_view', true);
//trouve le nom
$name_uid = XoopsUser::getUnameFromId($ut);
$xoopsTpl->assign('tree_title', $name_uid);
 // ************************************************************
 // Liste des Categories
 // ************************************************************

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('cat_display', 1));
		$criteria->setSort('cat_weight');
		$criteria->setOrder('DESC');
		$cat_arr = $cat_handler->getall($criteria);
		$mytree = new TDMCATObjectTree($cat_arr, 'cat_id', 'cat_pid');
		$display_cat = $mytree->makeCatBox($file_handler, 'cat_title','-', false);
		
		$numcat = $cat_handler->getCount($criteria);
		$xoopsTpl->assign('tree_display', $display_cat);	

		$xoopsTpl->assign('display_tris', $mytree->makeSelTris($_SERVER['PHP_SELF'], "ut=".$ut, $tris, $order));		
	
		
unset($criteria);

//$xoopsTpl->assign('selectcat', tdmpicture_catselect($mytree, false));

 // ************************************************************
 // Liste fichier
 // ************************************************************

		$criteria3 = new CriteriaCompo();
		$criteria3->add(new Criteria('file_uid', $ut)); 
		$criteria3->add(new Criteria('file_display', 1)); 
		$criteria3->setStart($start);	 
		$criteria3->setLimit($xoopsModuleConfig['tdmpicture_page']);
		$criteria3->setSort($tris);

		$criteria3->setOrder($order);
		$file_arr = $file_handler->getObjects($criteria3);
		$numfile = $file_handler->getCount($criteria3);
		$xoopsTpl->assign('numfile', $numfile);
		$file = array();
		$files = array();
		foreach (array_keys($file_arr) as $f) {
		
	//perm
	if ($gperm_handler->checkRight('tdmpicture_catview', $file_arr[$f]->getVar('file_cat'), $groups, $xoopsModule->getVar('mid'))) {

		//cherche le cat
		$cat = $cat_handler->get($file_arr[$f]->getVar('file_cat'));
		$file['file_cat'] = $myts->displayTarea($cat->getVar('cat_title'));
		$file['file_cat_id'] = $file_arr[$f]->getVar('file_cat');
		//	
		$file['cat_nav'] = 'ut='.$file_arr[$f]->getVar('file_uid');		
		//on test l'existance de l'image
		
		//apelle lien image
		$file_path = $file_arr[$f]->getFilePath($file_arr[$f]->getVar('file_file'));
		
		//test image
		if (file_exists($file_path['image_path'])) {
		$file['img_popup'] = $file_path['image_url'];
		} else {
		$file['img_popup'] = TDMPICTURE_IMAGES_URL."/blank.png";
		}
		
		//test thumb
		if (file_exists($file_path['thumb_path'])) {
		$file['img'] = $file_path['thumb_url'];
		} else {
		$file['img'] = TDMPICTURE_IMAGES_URL."/blank.png";
		}
	
	$file['id'] = $file_arr[$f]->getVar("file_id");
	$file['title'] = $myts->displayTarea($file_arr[$f]->getVar("file_title"));
	$file['text'] = $myts->displayTarea($file_arr[$f]->getVar("file_text"));
	
	
	$file['file_catum'] = $file_arr[$f]->getVar("file_catum");
	$file['hits'] = $file_arr[$f]->getVar("file_hits");
	$file['dl'] = $file_arr[$f]->getVar("file_dl");
	$file['postername'] = XoopsUser::getUnameFromId($file_arr[$f]->getVar('file_uid'));
	$file['uid'] = $file_arr[$f]->getVar('file_uid');
	//test si l'user a un album
	$file['useralb'] = tdmpicture_useralb($file_arr[$f]->getVar('file_uid'));
	//
	$file['indate'] = formatTimeStamp($file_arr[$f]->getVar('file_indate'),"S");
	//nombre de vote
	$file['votes'] = $file_arr[$f]->getVar('file_votes');
	//total des votes
	$file['counts'] = $file_arr[$f]->getVar("file_counts");
	$file['comments'] = $file_arr[$f]->getVar("file_comments");
	
//moyen des votes
	@$moyen = ceil( $file['votes']/ $file['counts'] );
	if (@$moyen == 0) {
	$file['moyen'] = "";
	} else {
	$file['moyen'] = "<img src='".TDMPICTURE_IMAGES_URL."rate".$moyen.".png'/>";
	}
	
//favorie
	if ($file['counts'] >= $xoopsModuleConfig['tdmpicture_favourite']) {
	$file['favourite'] = "<img src='".TDMPICTURE_IMAGES_URL."flag.png'/>";
	} else {
	$file['favourite'] = "";
	}

	if (!empty($xoopsUser))
	{
	
	if ($xoopsUser->getVar('uid') == $file_arr[$f]->getVar('file_uid') OR $xoopsUser->isAdmin()) 
	{
	
	if (!$gperm_handler->checkRight('tdmpicture_view', 128, $groups, $xoopsModule->getVar('mid'))) {
	$file['menu'] = false;
	$file['edit'] = false;
	} else {
	$file['menu'] = true;
	$file['edit'] = true;
	}
	
	if (!$gperm_handler->checkRight('tdmpicture_view', 512, $groups, $xoopsModule->getVar('mid'))) {
	$file['menu'] = false;
	$file['del'] = false;
	} else {
	$file['menu'] = true;
	$file['del'] = true;
	}
	
	}
	}
	
	$xoopsTpl->append('file', $file);
} }

	//navigation
	if ( $numfile > $xoopsModuleConfig['tdmpicture_page'] ) {
	$pagenav = new XoopsPageNav($numfile, $xoopsModuleConfig['tdmpicture_page'], $start, 'start', 'ut='.$ut.'&tris='.$tris.'&order='.$order);
	$xoopsTpl->assign('nav_page', $pagenav->renderNav(2));
	} 

   break;

}
tdmpicture_header();
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()));

if(isset($xoTheme) && is_object($xoTheme)) {
$xoTheme->addMeta( 'meta', 'keywords', tdmpicture_keywords($xoopsModuleConfig['tdmpicture_keywords']));
$xoTheme->addMeta( 'meta', 'description', $xoopsModuleConfig['tdmpicture_description']);
} else {	// Compatibility for old Xoops versions
$xoopsTpl->assign('xoops_meta_keywords', tdmpicture_keywords($xoopsModuleConfig['tdmpicture_keywords']));
$xoopsTpl->assign('xoops_meta_description', $xoopsModuleConfig['tdmpicture_description']);
}
		
include_once XOOPS_ROOT_PATH.'/footer.php';
?>