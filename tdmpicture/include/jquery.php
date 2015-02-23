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
 
require('../../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');


 if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}


$myts =& MyTextSanitizer::getInstance();
global $xoopsUser, $xoopsModuleConfig, $xoopsModule;

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');
$pl_handler =& xoops_getModuleHandler('tdmpicture_pl', 'TDMPicture');
$vote_handler =& xoops_getModuleHandler('tdmpicture_vote', 'TDMPicture');
$gperm_handler =& xoops_gethandler('groupperm');

$module_handler =& xoops_gethandler('module');
$xoopsModule =& $module_handler->getByDirname('TDMPicture');

if(!isset($xoopsModuleConfig)){
	$config_handler = &xoops_gethandler('config');
	$xoopsModuleConfig = &$config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
    }	

include_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/include/common.php');
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/".$xoopsConfig['language']."/main.php");

$mydirname = basename( dirname( __FILE__ ) ) ;
require(XOOPS_ROOT_PATH.'/header.php');

$xoopsTpl->assign('dirname', $mydirname);



//perm
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
	$xd_uid = $xoopsUser->getVar('uid');
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
	$xd_uid = 0;
}


 switch($op) {
 
 	case "add": 
	
    if($_REQUEST['pl_file']){
	
//interdit au non membre	
if (empty($xoopsUser)) {
echo "<a href='".XOOPS_URL."/user.php'>"._MD_TDMPICTURE_QUERYNOREGISTER."</a>";
exit();
}

if (!$gperm_handler->checkRight('sound_view', 4, $groups, $xoopsModule->getVar('mid'))) {
echo _MD_TDMPICTURE_QUERYNOPERM;
exit();
}
		//cherche le fichier
		$file = $file_handler->get($_REQUEST['pl_file']);
		//	
		
		$obj =& $pl_handler->create();
		$obj->setVar('pl_file', $file->getVar('file_id'));
		$obj->setVar('pl_album', $file->getVar('file_album'));
		$obj->setVar('pl_artiste', $file->getVar('file_artiste'));
		$obj->setVar('pl_genre', $file->getVar('file_genre'));
		$obj->setVar('pl_indate', time());
		$obj->setVar('pl_uid', $xoopsUser->getVar('uid'));
		 
        //Insertion
      if ($pl_handler->insert($obj)){
    echo _MD_TDMPICTURE_QUERYOK;
	exit();
    }
	}   

	 break;
	 
	case "remove": 
	
    if($_REQUEST['pl_file']){
	
//interdit au non membre	
if (empty($xoopsUser)) {
echo _MD_TDMPICTURE_QUERYNOREGISTER;
exit();
}

if (!$gperm_handler->checkRight('sound_view', 4, $groups, $xoopsModule->getVar('mid'))) {
echo _MD_TDMPICTURE_QUERYNOPERM;
exit();
}
		//cherche les fichier
		$obj = $pl_handler->get($_REQUEST['pl_file']);
		//	
        //suppression
      if ($pl_handler->delete($obj)){
    echo _MD_TDMPICTURE_QUERYDELOK;
    }
	}   

	 break;
	 
		case "upload": 
		global $xoopsDB, $xoopsTpl, $xoopsModule, $xoopsModuleConfig, $xoopsUser;

		include_once XOOPS_ROOT_PATH.'/class/uploader.php';
		
		
		$uploaddir = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/";
		$mimetype = explode('|',$xoopsModuleConfig['tdmpicture_mimetype']);
		$uploader = new XoopsMediaUploader(TDMPICTURE_UPLOADS_PATH, $mimetype, $xoopsModuleConfig['tdmpicture_mimemax'], null, null);
		
		$obj =& $file_handler->create();
		$obj->setVar('file_cat', $_REQUEST['file_cat']);
		$obj->setVar('file_display', $_REQUEST['file_display']);
		$obj->setVar('file_indate', time());
		$obj->setVar('file_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
		//$obj->setVar('file_ext', $_REQUEST['file_ext']);
	
foreach ($_FILES as $file => $fileArray) {
		
	if ($uploader->fetchMedia($file)) {
		$uploader->setPrefix('picture_') ;
		$uploader->fetchMedia($file);
		if (!$uploader->upload()) {
		$msgError = $uploader->getErrors();
		} else {
		$bSuccess=true;
		if (strrpos($uploader->getMediaName(), '.') !== false) {
         $ext = substr($uploader->getMediaName(), 0, strrpos($uploader->getMediaName(), "."));
		} else {
		$ext = $uploader->getMediaName();
		}
		
		$obj->setVar('file_title', $ext);
		$obj->setVar('file_file', $uploader->getSavedFileName());
		$obj->setVar('file_size', $uploader->getMediaSize());
		$obj->setVar('file_type', $uploader->getMediaType());
		$dimensions=getimagesize(TDMPICTURE_UPLOADS_PATH.$uploader->getSavedFileName());
		$obj->setVar('file_res_x', $dimensions[0]);
		$obj->setVar('file_res_y', $dimensions[1]);	
		
		//thumb
		include_once(TDMPICTURE_ROOT_PATH."/class/thumbnail.inc.php");
		$thumb = new Thumbnail(TDMPICTURE_UPLOADS_PATH.$uploader->getSavedFileName());
		$thumb->resize($xoopsModuleConfig['tdmpicture_thumb_width'],$xoopsModuleConfig['tdmpicture_thumb_heigth']);
		$thumb->save(TDMPICTURE_THUMB_PATH.$uploader->getSavedFileName(),$xoopsModuleConfig['tdmpicture_thumb_quality']);
		//$thumb->save($uploaddir.'thumb/'.$uploader->getSavedFileName(),$xoopsModuleConfig['TDMPicture_thumb_quality']);
		//$msgError = TDMPICTURE_UPLOADS_PATH;
		$file_handler->insert($obj);
		}
	} 
}
if ($bSuccess) {
	echo "SUCCESS\n";
} else {
	echo "ERROR: $msgError\n";
}

	 break;
	 
	case "adds": 
	
    if($_REQUEST['alb_id']){
	
//interdit au non membre	
if (empty($xoopsUser)) {
echo _MD_TDMPICTURE_QUERYNOREGISTER;
exit();
}

if (!$gperm_handler->checkRight('sound_view', 4, $groups, $xoopsModule->getVar('mid'))) {
echo _MD_TDMPICTURE_QUERYNOPERM;
exit();
}

	//cherche les fichiers de l'album
	$criteria = new CriteriaCompo();
    $criteria->add(new Criteria('file_album', $_REQUEST['alb_id']));
	$criteria->add(new Criteria('file_display', 1));
	$file_arr = $file_handler->getObjects($criteria);	
	
	foreach (array_keys($file_arr) as $a) {
		
		$obj =& $pl_handler->create();
		$obj->setVar('pl_file', $file_arr[$a]->getVar('file_id'));
		$obj->setVar('pl_album', $file_arr[$a]->getVar('file_album'));
		$obj->setVar('pl_artiste', $file_arr[$a]->getVar('file_artiste'));
		$obj->setVar('pl_genre', $file_arr[$a]->getVar('file_genre'));
		$obj->setVar('pl_indate', time());
		$obj->setVar('pl_uid', $xoopsUser->getVar('uid'));
		
		$erreur = $pl_handler->insert($obj);
		}
        //Insertion
      if ($erreur){
    echo _MD_TDMPICTURE_QUERYOK;
    }
	}   

	 break;
	 
	
	 
	case "addvote": 
	
	//interdit au non membre	
if (empty($xoopsUser)) {
echo _MD_TDMPICTURE_QUERYNOREGISTER;
exit();
}

if (!$gperm_handler->checkRight('tdmpicture_view', 64, $groups, $xoopsModule->getVar('mid'))) {
echo _MD_TDMPICTURE_QUERYNOPERM;
exit();
}
	
	if ($_REQUEST['vote_id']) {
		
	$criteria = new CriteriaCompo();
    $criteria->add(new Criteria('vote_file', $_REQUEST['vote_id']));
	$criteria->add(new Criteria('vote_ip', $_SERVER["REMOTE_ADDR"]));
	$numvote = $vote_handler->getCount($criteria);
	
	if ($numvote > 0) {
	echo _MD_TDMPICTURE_VOTENOOK;
	exit();
	} else {
	$obj =& $vote_handler->create();
	$obj->setVar('vote_file', $_REQUEST['vote_id']);
	$obj->setVar('vote_ip', $_SERVER["REMOTE_ADDR"]);
	$erreur = $vote_handler->insert($obj);
	
	$file = $file_handler->get($_REQUEST['vote_id']);
	$count = $file->getVar('file_counts');
	$vote = $file->getVar('file_votes');
	$count++;
	$vote++;
    $file->setVar('file_counts', $count);
	$file->setVar('file_votes', $vote);
	$erreur .= $file_handler->insert($file);
	}
	
	
	if ($erreur){
	echo  _MD_TDMPICTURE_VOTEOK;
	  return true;
	} else {
	echo _MD_TDMPICTURE_BASEERROR;
	return false;
	}
	
	}
    break;
	
		case "removevote": 
	
	//interdit au non membre	
if (empty($xoopsUser)) {
echo _MD_TDMPICTURE_QUERYNOREGISTER;
exit();
}

if (!$gperm_handler->checkRight('sound_view', 256, $groups, $xoopsModule->getVar('mid'))) {
echo _MD_TDMPICTURE_QUERYNOPERM;
exit();
}

	if ($_REQUEST['vote_id']) {
		

	$criteria = new CriteriaCompo();
    $criteria->add(new Criteria('vote_file', $_REQUEST['vote_id']));
	$criteria->add(new Criteria('vote_ip', $_SERVER["REMOTE_ADDR"]));
	$numvote = $vote_handler->getCount($criteria);
	
	if ($numvote > 0) {
	echo _MD_TDMPICTURE_VOTENOOK;
	exit();
	} else {
	$obj =& $vote_handler->create();
	$obj->setVar('vote_file', $_REQUEST['vote_id']);
	$obj->setVar('vote_ip', $_SERVER["REMOTE_ADDR"]);
	$erreur = $vote_handler->insert($obj);
	
	$file = $file_handler->get($_REQUEST['vote_id']);
	$count = $file->getVar('file_counts');
	$vote = $file->getVar('file_votes');
	$count = $count - 1;
	$vote++;
    $file->setVar('file_counts', $count);
	$file->setVar('file_votes', $vote);
	$erreur .= $file_handler->insert($file);
	}
		
	if ($erreur){
	echo  _MD_TDMPICTURE_VOTEOK;
	  return true;
	} else {
	echo _MD_TDMPICTURE_BASEERROR;
	return false;
	}
	
	}
    break;
	
	case "cookie": 

	
    $_SESSION['tdmpicture_display'] = $_REQUEST['display'];


	return  $_REQUEST['display'];
	
	 break;

}

?>
