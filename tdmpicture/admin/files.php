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

tdmpicture_adminheader();



$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

$myts =& MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'file_indate';
$file_cat = isset($_REQUEST['file_cat']) ? $_REQUEST['file_cat'] : 0;
$file_display = isset($_REQUEST['file_display']) ? $_REQUEST['file_display'] : 1;
$file_id = isset($_REQUEST['file_id']) ? $_REQUEST['file_id'] : 0;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;

$redirect_page = XOOPS_URL . '/modules/system/admin.php?fct=comments&amp;com_modid=' . $com_modid . '&amp;com_itemid';
 
 //compte les fichiers
$numfile = $file_handler->getCount();
$numcat = $cat_handler->getCount();
//compte les fichiers en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('file_display', 0));
$file_waiting = $file_handler->getCount($criteria);
 
 switch($op) {
  
  //upload
  case "upload":
  
          //menu admin
            $category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('cat.php');
            $category_admin->addItemButton(_AM_TDMPICTURE_LIST_FILE, 'files.php?op=list', 'list');
            echo $category_admin->renderButton();
			
  		// Affichage du formulaire de cr?ation de cat?gories
    	$obj =& $file_handler->create();
    	$form = $obj->getForm();
    	$form->display();
		break;
		
		
    //sauv  
 case "save_file":
 
		if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('files.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
				
		if (isset($_REQUEST['file_id'])) {
        $obj =& $file_handler->get($_REQUEST['file_id']);
		} else {
        $obj =& $file_handler->create();
		}
		//include	
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';
		include_once(TDMPICTURE_ROOT_PATH."/class/thumbnail.inc.php");
		//prepare l'upload
		$path = $obj->getFilePath();

		@chmod ($path['image_path'], 0755);
		$mimetype = explode('|',$xoopsModuleConfig['tdmpicture_mimetype']);
		$uploader = new XoopsMediaUploader($path['image_path'], $mimetype, $xoopsModuleConfig['tdmpicture_mimemax'], null, null);
		
		

	
			//variable commune
		$obj->setVar('file_cat', $_REQUEST['file_cat']);
		$obj->setVar('file_display', $_REQUEST['file_display']);
		$obj->setVar('file_indate', time());
		$obj->setVar('file_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
		//$obj->setVar('file_ext', $_REQUEST['file_ext']);
		
	
	//upload	

foreach ($_FILES['tdmfile']['error'] as $key => $value) 
{ 
	
		
	if ($uploader->fetchMedia('tdmfile',$key)) {
		$uploader->setPrefix('picture_') ;
		$uploader->fetchMedia('tdmfile',$key);
		if (!$uploader->upload()) {
		$errors = $uploader->getErrors();
		redirect_header("files.php",2, $errors);
		} else {
		$erreur=true;
		if (strrpos($uploader->getMediaName(), '.') !== false) {
         $ext = substr($uploader->getMediaName(), 0, strrpos($uploader->getMediaName(), "."));
		} else {
		$ext = $uploader->getMediaName();
		}
		
		$file_path = $obj->getFilePath($uploader->getSavedFileName());

		$photo = new Thumbnail($file_path['image_path']);
		//$photo = new Thumbnail(TDMPICTURE_UPLOADS_PATH.$uploader->getSavedFileName());
		$obj->setVar('file_title', $ext);
		$obj->setVar('file_file', $uploader->getSavedFileName());
		$obj->setVar('file_type', $uploader->getMediaType());		
		//redimention image 
		if (!empty($_REQUEST['resize'])) {
		$size = explode('x',$_REQUEST['resize']);	
		$photo->adaptiveResize($size[0], $size[1]);
		$photo->save($file_path['image_path'],$xoopsModuleConfig['tdmpicture_thumb_quality']);
		}	

		$obj->setVar('file_res_x', $photo->getCurrentWidth());
		$obj->setVar('file_res_y', $photo->getCurrentHeight());
		$obj->setVar('file_size', $photo->getCurrentSize());
		
		//thumb
		$file_handler->thumb('', $uploader->getSavedFileName());
	
		$erreur = $file_handler->insert($obj);
		}
	} 
}

	if ($erreur) {
    redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
	}else {
	$uploader->getErrors();
	}
		//include_once('../include/forms.php');
		echo $obj->getHtmlErrors();
		$form =& $obj->getForm();
		$form->display();
    break;
	
		case "edit_file":

		if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if (isset($_REQUEST['file_id'])) {
        $obj =& $file_handler->get($_REQUEST['file_id']);
		} else {
        $obj =& $file_handler->create();
		}
		
	//fichier commun
	$obj->setVar('file_title', $_REQUEST['file_title']);
	$obj->setVar('file_display', $_REQUEST['file_display']);
	$obj->setVar('file_cat', $_REQUEST['file_cat']);
	$obj->setVar('file_indate', time());
	$obj->setVar('file_text', $_REQUEST['file_text']);
	$obj->setVar('file_size', $_REQUEST['file_size']);
	$obj->setVar('file_res_x', $_REQUEST['file_res_x']);
	$obj->setVar('file_res_y', $_REQUEST['file_res_y']);		
		
	$erreur = $file_handler->insert($obj);

	if ($erreur) {
     redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
      } else {
	redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
	}
    break;
	
	
 case "edit": 
 	        //admin menu
			$category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('files.php');
            $category_admin->addItemButton(_AM_TDMPICTURE_FILE_UPLOAD, 'files.php?op=upload', 'add');
            $category_admin->addItemButton(_AM_TDMPICTURE_LIST_FILE, 'files.php?op=list', 'list');
            echo $category_admin->renderButton();
			
    $obj = $file_handler->get($_REQUEST['file_id']);
    $form = $obj->getForm();
    $form->display();
    break;
	
 case "delete":
		
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('files.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
		
		//supprime de la base	
        if ($file_handler->delete($_REQUEST['file_id'])) {
           redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
        } else {
            echo $obj->getHtmlErrors();
       }
    } else {
	
			$category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('files.php');
			
        xoops_confirm(array('ok' => 1, 'file_id' => $_REQUEST['file_id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], _AM_TDMPICTURE_FORMSUREDEL);
    }
    break;
	
	case _AM_TDMPICTURE_DELETE :
	
     if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('files.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
		
		$_POST['id'] = unserialize($_REQUEST['id']);
		$erreur =& $file_handler->deletes($_POST['id']);
		
		if (isset($erreur)) {
        redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
       } else {
         redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
		
		} else { 
		$category_admin = new ModuleAdmin();
            echo $category_admin->addNavigation('files.php');
			
		
        xoops_confirm(array('ok' => 1, 'deletes' => 1, 'op' => $_REQUEST['op'], 'id'=> serialize(array_map("intval", $_REQUEST['id']))), $_SERVER['REQUEST_URI'], _AM_TDMPICTURE_FORMSUREDEL);
    }
break;
	
	case "display":
	
	$erreur =& $file_handler->display($_REQUEST['file_id']);
	if (isset($erreur)) {
      redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
      } else {
	  redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
	break;
	
	case _AM_TDMPICTURE_DISPLAY."/"._AM_TDMPICTURE_HIDDEN:

	$erreur =& $file_handler->displays($_REQUEST['id']);

	if (isset($erreur)) {
        redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
      } else {
         redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
	break;
	
	case "update":

	$erreur =& $file_handler->update($_REQUEST['file_id']);
	if (isset($erreur)) {
      redirect_header('onclick="javascript:history.go(-1);"', 2, _AM_TDMPICTURE_BASE);
      } else {
	  redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
	break;
	
	case _AM_TDMPICTURE_UPDATE:

	$erreur =& $file_handler->updates($_REQUEST['id']);

	if (isset($erreur)) {
        redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
      } else {
         redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
	break;
	
	
	
		
	case "thumb":
	$erreur =& $file_handler->thumb($_REQUEST['file_id']);
	
	if (isset($erreur)) {
        redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
      } else {
         redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
	break;
	
	
	case _AM_TDMPICTURE_THUMB:

	$erreur =& $file_handler->thumbs($_REQUEST['id']);

	if (isset($erreur)) {
        redirect_header('files.php', 2, _AM_TDMPICTURE_BASE);
      } else {
         redirect_header('files.php', 2, _AM_TDMPICTURE_BASEERROR);
        }
	break;
	
	
 case "list": 
  default:

  	if (!$numcat) {
	 redirect_header('cat.php', 2, _AM_TDMPICTURE_CATERROR);
	}
	
	//menu admin
	$category_admin = new ModuleAdmin();
    echo $category_admin->addNavigation('files.php');
	if ($file_waiting != 0 && !isset($_REQUEST['file_display'])){
    $waitString =  _AM_TDMPICTURE_BUTTON_FILES_WAITING.$file_waiting;
	//$category_admin->addItemButton(sprintf(_AM_TDMPICTURE_THEREARE_FILE_WAITING,$file_waiting), 'files.php?op=list&file_display=0', 'update');
    $category_admin->addItemButton($waitString, 'files.php?op=list&file_display=0', 'update');
    }
	if (isset($_REQUEST['file_display'])) {
	$category_admin->addItemButton(_AM_TDMPICTURE_LIST_FILE, 'files.php?op=list', 'list');
	}
	$category_admin->addItemButton(_AM_TDMPICTURE_FILE_UPLOAD, 'files.php?op=upload', 'add');
    echo $category_admin->renderButton();
  
  //creation du formulaire de tris
	$form = new XoopsThemeForm(_SEARCH, "tris", "files.php");
 
	$form->addElement(new XoopsFormHidden("op", "list"));
	$form->addElement(new XoopsFormHidden("file_display", $file_display));
	//$form->addElement(new XoopsFormHidden("start", $start));

    $cat_select = new XoopsFormSelect(_AM_TDMPICTURE_CAT, 'file_cat', $file_cat);
    $cat_select->addOption(0, _ALL);
    $cat_select->addOptionArray($cat_handler->getList());
    $form->addElement($cat_select);
 
	$form->addElement(new XoopsFormText(_AM_TDMPICTURE_ID, 'file_id', 8, 8, $file_id));
 
	$button_tray = new XoopsFormElementTray(_AM_TDMPICTURE_ACTION ,'');
	$button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
	$form->addElement($button_tray);

	$form->display();
 
	echo"<br />";
	
	//Parameters	
	$criteria = new CriteriaCompo();

	if (isset($_REQUEST['start'])) {
	$criteria->setStart($_REQUEST['start']);
	} else {
	$criteria->setStart(0);
	}
	
	if (isset($file_display)) {
	$criteria->add(new Criteria('file_display', $file_display));
	}
	
	if (isset($_REQUEST['file_cat']) && $_REQUEST['file_cat'] != 0) {
	$criteria->add(new Criteria('file_cat', $_REQUEST['file_cat']));
	}
	
	if (isset($file_id) && $file_id != 0) {
	$criteria->add(new Criteria('file_id', $file_id));
	}
	
	$criteria->setLimit(10);
	$criteria->setSort($sort);
	$criteria->setOrder($order);
	$arr = $file_handler->getObjects( $criteria );
	$numrows = $file_handler->getCount( $criteria );
	
	//nav
	if ( $numrows > 10 ) {
	$pagenav = new XoopsPageNav($numrows, 10, $start, 'start', 'op=list&file_display='.$file_display.'&file_cat='.$file_cat.'&sort='.$sort.'&order='.$order.'');
	$pagenav = $pagenav->renderNav(2);
	} else {
	$pagenav = '';
	}
	
		//Affichage du tableau des catégories
		if ($numrows>0) {
			echo '<form name="form" id="form" action="files.php" method="post"><table width="100%" cellspacing="1" class="outer">';
			echo '<tr>';
			echo '<th align="center" width="5%"><input name="allbox" id="allbox" onclick="xoopsCheckAll(\'form\', \'allbox\');" type="checkbox" value="Check All" /></th>';
			echo '<th align="center" width="10%">'._AM_TDMPICTURE_IMG.'</th>';
			echo '<th align="center" width="10%">'._AM_TDMPICTURE_CAT.'</th>';
			echo '<th align="center" width="25%">'. tdm_switchselect(_AM_TDMPICTURE_TITLE, 'file_title').'</th>';
			echo '<th align="center" width="7%">'. tdm_switchselect(_AM_TDMPICTURE_WIDTH, 'file_res_x').'</th>';
			echo '<th align="center" width="7%">'. tdm_switchselect(_AM_TDMPICTURE_HEIGHT, 'file_res_y').'</th>';	
			echo '<th align="center" width="5%">'. tdm_switchselect(_AM_TDMPICTURE_TYPE, 'file_type').'</th>';
			echo '<th align="center" width="5%">'. tdm_switchselect(_AM_TDMPICTURE_WEIGHT, 'file_size').'</th>';
			echo '<th align="center" width="7%">'. tdm_switchselect(_AM_TDMPICTURE_HITS, 'file_hits').'</th>';
			echo '<th align="center" width="5%">'._AM_TDMPICTURE_THUMB.'</th>';
			echo '<th align="center" width="5%">'._AM_TDMPICTURE_DISPLAY.'</th>';
			echo '<th align="center" width="10%">'._AM_TDMPICTURE_ACTION.'</th>';
			echo '</tr>';
			$class = 'odd';
		    foreach (array_keys($arr) as $i) {
			//trouve la categorie
			$ret =& $cat_handler->get($arr[$i]->getVar('file_cat'));
			$file_cattitle = $ret->getVar('cat_title');
			//info file
   			$class = ($class == 'even') ? 'odd' : 'even';
			$file_id = $arr[$i]->getVar('file_id');
			$file_title = $myts->displayTarea($arr[$i]->getVar('file_title'));
			$file_type = $arr[$i]->getVar('file_type');
			$file_size = $arr[$i]->getVar('file_size');
			$file_hits = $arr[$i]->getVar('file_hits');
			
			$display = $arr[$i]->getVar('file_display') == 1 ? "<a href='files.php?op=display&file_id=".$file_id."'><img src='" . $pathIcon16."/1.png' border='0'></a>" : "<a href='files.php?op=display&file_id=".$file_id."'><img alt='"._AM_TDMPICTURE_DISPLAY."' title='"._AM_TDMPICTURE_DISPLAY."' src='" . $pathIcon16."/0.png' border='0'></a>";

			//apelle lien image
			$file_path = $arr[$i]->getFilePath($arr[$i]->getVar('file_file'));

			if (file_exists($file_path['image_path'])) {
			$file_img = $file_path['image_url'];
			} else {
			$file_img = XOOPS_URL. "/modules/".$xoopsModule->dirname()."/upload/blank.gif";
			}
			
   			//on test l'existance du thumb
			$thumb_img = "<a href='files.php?op=thumb&file_id=".$file_id."'>";
			if (file_exists($file_path['thumb_path'])) {
			$thumb_img .= "<img alt='"._AM_TDMPICTURE_ADDTHUMB."' title='"._AM_TDMPICTURE_ADDTHUMB."' src='" . $pathIcon16."/1.png' border='0'>";
			} else {
			$thumb_img .= "<img alt='"._AM_TDMPICTURE_ADDTHUMB."' title='"._AM_TDMPICTURE_ADDTHUMB."' src='" . $pathIcon16."/0.png' border='0'>";
			}
			$thumb_img .= "</a>";
			
 				echo '<tr class="'.$class.'">';
				echo '<td align="center" style="vertical-align:middle;"><input type="checkbox" name="id[]" id="id[]" value="'.$arr[$i]->getVar("file_id") .'" /></td>';
				echo '<td align="center" style="vertical-align:middle;"><img src="'.$file_img.'" alt="" title="" height="60"></td>';
				echo '<td align="center" style="vertical-align:middle;">'.$file_cattitle.'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$file_title.'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$arr[$i]->getVar('file_res_x').'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$arr[$i]->getVar('file_res_y').'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$file_type.'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.tdmpicture_PrettySize($file_size).'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$file_hits.'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$thumb_img.'</td>';
				echo '<td align="center" style="vertical-align:middle;">'.$display.'</td>';
				echo '<td align="center" style="vertical-align:middle;">';
				echo '<a href="files.php?op=update&file_id='.$file_id.'"><img src=' . $pathIcon16.'/up.png border="0" alt="'._AM_TDMPICTURE_UPDATE.'" title="'._AM_TDMPICTURE_UPDATE.'"></a>';
				echo '<a href="files.php?op=edit&file_id='.$file_id.'"><img src=' . $pathIcon16.'/edit.png border="0" alt="'._AM_TDMPICTURE_MODIFY.'" title="'._AM_TDMPICTURE_MODIFY.'"></a>';
				echo '<a href="files.php?op=delete&file_id='.$file_id.'"><img src=' . $pathIcon16.'/delete.png border="0" alt="'._AM_TDMPICTURE_DELETE.'" title="'._AM_TDMPICTURE_DELETE.'"></a>';
				//echo '<a href="files.php?op=edit_img&file_id='.$file_id.'"><img src="./../images/picture_edit.png" border="0" alt="'._AM_TDMPICTURE_EDITIMG.'" title="'._AM_TDMPICTURE_EDITIMG.'"></a>';
				echo '</td>';
				echo '</tr>';
			 }
			 echo '</table>';
			 echo '<input type="submit" name="op" value="'._AM_TDMPICTURE_DISPLAY.'/'._AM_TDMPICTURE_HIDDEN.'" />
			 <input type="submit" name="op" value="'._AM_TDMPICTURE_UPDATE.'" />
			 <input type="submit" name="op" value="'._AM_TDMPICTURE_THUMB.'" />
			 <input type="submit" name="op" value="'._AM_TDMPICTURE_DELETE.'" />';
			 
			 echo '</form><br /><br />';
			 echo '<div align=right>'.$pagenav.'</div><br />';
		} else{
            echo '<div class="errorMsg" style="text-align: center;">' . sprintf(_AM_TDMPICTURE_THEREARE_FILE,0) . '</div>';
        }

    break;
	
  }

include 'admin_footer.php';