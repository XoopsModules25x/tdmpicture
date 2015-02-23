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
 * 4. Vous n'avez pas la liberté de l'améliorer et de rendre publiques les modifiuserions
 *
 * @license     TDMFR PRO license
 * @author		TDMFR ; TEAM DEV MODULE 
 *
 * ****************************************************************************
 */

include '../../../include/cp_header.php'; 
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/include/common.php';
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname")."/class/tree.php";

 xoops_cp_header();
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
TDMPicture_adminmenu(1, _AM_TDMPICTURE_MANAGE_user);
} else {
include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
loadModuleAdminMenu (1, _AM_TDMPICTURE_MANAGE_user);
}

$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$user_handler =& xoops_getModuleHandler('tdmpicture_user', 'TDMPicture');

$myts =& MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

//compte les users
$numuser = $user_handler->getCount();
//compte les users en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('user_display', 0));
$user_waiting = $user_handler->getCount($criteria);

//menu
echo '<div class="CPbigTitle" style="background-image: url(../images/decos/user.png); background-repeat: no-repeat; background-position: left; padding-left: 60px; padding-top:20px; padding-bottom:15px;"><h3><strong>'._AM_TDMPICTURE_MANAGE_user.'</strong></h3>';
echo '</div><br /><div class="head" align="center">';
echo !isset($_REQUEST['user_display']) ||  $_REQUEST['user_display'] == 1 ? '<a href="user.php?op=list&user_display=0">'.sprintf(_AM_TDMPICTURE_THEREARE_user_WAITING,$user_waiting).'</a>' : '<a href="user.php?op=list&user_display=1">'.sprintf(_AM_TDMPICTURE_THEREARE_user,$numuser).'</a>';
echo '</div><br>';
 switch($op) {
  
    //sauv  
 case "save_user":
 
		if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('user.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if (isset($_REQUEST['user_id'])) {
        $obj =& $user_handler->get($_REQUEST['user_id']);
		} else {
        $obj =& $user_handler->create();
		}   
	
	//upload	
	include_once XOOPS_ROOT_PATH.'/class/uploader.php';	
	$uploaddir = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/user/";
	$mimetype = explode('|',$xoopsModuleConfig['tdmpicture_mimetype']);
    $uploader = new XoopsMediaUploader($uploaddir, $mimetype, $xoopsModuleConfig['tdmpicture_mimemax']);

		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
		$uploader->setPrefix('picture_') ;
		$uploader->fetchMedia($_POST['xoops_upload_file'][0]);
		if (!$uploader->upload()) {
		$errors = $uploader->getErrors();
		redirect_header("javascript:history.go(-1)",3, $errors);
		} else {
		$obj->setVar('user_img', $uploader->getSavedFileName());
		}
		} else {
		$obj->setVar('user_img', $_REQUEST['img']);
		}
	//
		$obj->setVar('user_pid', $_REQUEST['user_pid']);
		$obj->setVar('user_title', $_REQUEST['user_title']);
		$obj->setVar('user_text', $_REQUEST['user_text']);
		$obj->setVar('user_weight', $_REQUEST['user_weight']);
		$obj->setVar('user_display', $_REQUEST['user_display']);

		if ($user_handler->insert($obj)) {
	 
	//permission
	$perm_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $obj->getVar('user_id');
	$gperm_handler = &xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $perm_id, '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'tdmpicture_userview', '='));
	$gperm_handler->deleteAll($criteria);

	if(isset($_POST['groups_view'])) {
		foreach($_POST['groups_view'] as $onegroup_id) {
			$gperm_handler->addRight('tdmpicture_userview', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}
	
        redirect_header('user.php', 2, _AM_TDMPICTURE_BASE);
		}
		//include_once('../include/forms.php');
		echo $obj->getHtmlErrors();
		$form =& $obj->getForm();
		$form->display();
    break;
	
	 case "edit": 
    $obj = $user_handler->get($_REQUEST['user_id']);
    $form = $obj->getForm();
    $form->display();
    break;

    break;
	
 case "delete":
	$obj =& $user_handler->get($_REQUEST['user_id']);
	
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('user.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
		
	//supprimer les enfant de la base et leur dossier
	$arr = $user_handler->getall();
	$mytree = new XoopsObjectTree($arr, 'user_id', 'user_pid');
	$treechild = $mytree->getAllChild($obj->getVar('user_id'));
	foreach($treechild as $child) {
	$ret =& $user_handler->get($child->getVar('user_id'));
	$user_handler->delete($ret);	
    } 
	
	//supprime le user	
     if ($user_handler->delete($obj)) {
        redirect_header('user.php', 2, _AM_TDMPICTURE_BASE);
       } else {
           echo $obj->getHtmlErrors();
        }
    } else {
        xoops_confirm(array('ok' => 1, 'user_id' => $_REQUEST['user_id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMPICTURE_FORMSUREDELuser, $obj->getVar('user_title')));
    }
    break;
	
 	case "update":
		$obj = $user_handler->get($_REQUEST['user_id']);
		$obj->setVar('user_display', 1);
		if ($user_handler->insert($obj)) {
         redirect_header('user.php', 2, _AM_TDMPICTURE_BASE);
        }
	break;
	
 case "list": 
  default:

	//Parameters	
	$criteria = new CriteriaCompo();
	$limit = 10;
	if (isset($_REQUEST['start'])) {
	$criteria->setStart($_REQUEST['start']);
	$start = $_REQUEST['start'];
	} else {
	$criteria->setStart(0);
	$start = 0;
	}
	
	if (isset($_REQUEST['user_display'])) {
	$criteria->add(new Criteria('user_display', $_REQUEST['user_display']));
	}
	
	
	//$criteria->setLimit($limit);
	$criteria->setOrder('ASC');
	$assoc_user = $user_handler->getAll($criteria);
	$numrows = $user_handler->getCount();
	
	//nav
	if ( $numrows > $limit ) {
	$pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&user_display='.$_REQUEST['user_display']);
	$pagenav = $pagenav->renderNav(2);
	} else {
	$pagenav = '';
	}
		//Affichage du tableau des userégories
		if ($numrows>0) {
			echo '<table width="100%" cellspacing="1" class="outer">';
			echo '<tr>';
			echo '<th align="center">'._AM_TDMPICTURE_IMG.'</th>';
			echo '<th align="center">'._AM_TDMPICTURE_TITLE.'</th>';
			echo '<th align="center">'._AM_TDMPICTURE_AUTEUR.'</th>';
			echo '<th align="center">'._AM_TDMPICTURE_WEIGHT.'</th>';
			echo '<th align="center">'._AM_TDMPICTURE_DISPLAY.'</th>';
			echo '<th align="center">'._AM_TDMPICTURE_ACTION.'</th>';
			echo '</tr>';
			$class = 'odd';
			$mytree = new TDMObjectTree($assoc_user, 'user_id', 'user_pid');            
            $useregory_ArrayTree = $mytree->makeArrayTree('','<img src="'.TDMPICTURE_IMAGES_URL.'decos/arrow.gif">');
			foreach (array_keys($useregory_ArrayTree) as $i) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$user_id = $assoc_user[$i]->getVar('user_id');
			$user_uid = XoopsUser::getUnameFromId($assoc_user[$i]->getVar('user_uid'));
			$user_pid = $assoc_user[$i]->getVar('user_pid');
			$user_title = $myts->displayTarea($assoc_user[$i]->getVar('user_title'));
			
			$display = $assoc_user[$i]->getVar('user_display') == 1 ? "<img src='./../images/on.gif' border='0'>" : "<a href='user.php?op=update&user_id=".$user_id."'><img alt='"._AM_TDMPICTURE_UPDATE."' title='"._AM_TDMPICTURE_UPDATE."' src='./../images/off.gif' border='0'></a>";

			//on test l'existance de l'image
			$imgpath = TDMPICTURE_user_PATH.$assoc_user[$i]->getVar("user_img");
			if (file_exists($imgpath)) {
			$user_img = TDMPICTURE_user_URL.$assoc_user[$i]->getVar("user_img");
			} else {
			$user_img = false;
			}
			
 				echo '<tr class="'.$class.'">';
				echo '<td align="center" width="10%"><img src="'.$user_img.'" alt="" title="" height="60"></td>';
				echo '<td align="left" width="60%">'.$useregory_ArrayTree[$i].$user_title.'</td>';
				echo '<td align="center" width="10%">'.$user_uid.'</td>';
				echo '<td align="center" width="5%">'.$assoc_user[$i]->getVar('user_weight').'</td>';
				echo '<td align="center" width="5%">'.$display.'</td>';
				echo '<td align="center" width="10%">';
				echo '<a href="user.php?op=edit&user_id='.$user_id.'"><img src="./../images/edit_mini.gif" border="0" alt="'._AM_TDMPICTURE_MODIFY.'" title="'._AM_TDMPICTURE_MODIFY.'"></a>';
				echo '<a href="user.php?op=delete&user_id='.$user_id.'"><img src="./../images/delete_mini.gif" border="0" alt="'._AM_TDMPICTURE_DELETE.'" title="'._AM_TDMPICTURE_DELETE.'"></a>';
				echo '</td>';
				echo '</tr>';
			 }
			 echo '</table><br /><br />';
			 echo '<div align=right>'.$pagenav.'</div><br />';
		}
		// Affichage du formulaire de cr?ation de user?gories
    	$obj =& $user_handler->create();
    	$form = $obj->getForm();
    	$form->display();
    break;
	
  }
xoops_cp_footer();
?>