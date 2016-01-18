<?php
/**
 * ****************************************************************************
 *  - TDMSpot By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.)
 *
 * Cette licence, contient des limitations
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier ni l'adapter à vos besoins,
 * 3. Vous ne devez le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer ni de rendre publiques les modifications
 *
 * @license     TDMFR GNU public license
 * @author		TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */
 
include 'admin_header.php';
xoops_cp_header();
$xoTheme->addStylesheet( XOOPS_URL . '/modules/system/css/admin.css');

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

 switch($op) {
  

     case "import":
  global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;
  
    $import = import_liste($_REQUEST['base']) ;
    if ($import['query']) {
    $error = $xoopsDB->queryF($import['query']);
    }else {
    $error = false;
    }

    if ($error) {
    redirect_header('import.php', 2, _AM_TDMPICTURE_BASE);
    } else {
    redirect_header('import.php', 10,  _AM_TDMPICTURE_BASEERROR."<br />".$xoopsDB->error());
    }

    break;
        
    
    case "comment":
  global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;
  
  $module = isset($_REQUEST['module']) ? $_REQUEST['module'] : false;
  
    $module_src =& $module_handler->getByDirname($module) ;
    $src_mid = $module_src->getvar( 'mid' ) ;
  
  
    $sq2 = "INSERT INTO ".$xoopsDB->prefix("xoopscomments")." (com_pid, com_rootid, com_modid, com_itemid, com_icon, com_created, com_modified,	com_uid, com_ip, com_title,	com_text, com_sig, com_status, com_exparams, dohtml, dosmiley, doxcode,	doimage, dobr) SELECT com_pid, com_rootid, ".$xoopsModule->getvar( 'mid' )." , com_itemid, com_icon, com_created, com_modified,	com_uid, com_ip, com_title,	com_text, com_sig, com_status, com_exparams, dohtml, dosmiley, doxcode,	doimage, dobr FROM ".$xoopsDB->prefix("xoopscomments")." WHERE com_modid='".$src_mid."'";
    $error=$xoopsDB->queryF($sq2);
    //  INSERT INTO la_table(id, nom, prenom ...)
    //SELECT id*0+$nouveau_id, nom, prenom ... WHERE id = $id_enreg_a_dupliquer
    if ($error) {
    redirect_header('import.php', 2, _AM_TDMPICTURE_BASE);
    } else {
    redirect_header('import.php', 2, _AM_TDMPICTURE_BASEERROR);
    }

    break;

    case "rename":
    define('BASE', XOOPS_ROOT_PATH.'/uploads/extgallery/public-photo/thumb/');
 
    function parcourirArborescence($repertoire) {
   
    if (@ $dh = opendir($repertoire)) {
        while (($fichier = readdir($dh)) != FALSE) {
            if ($fichier == '.') {
                continue; // Skip it
            }
            if ($fichier == '..') {
                continue; // Skip it
            }
            if (is_dir($repertoire . $fichier)) {
                parcourirArborescence($repertoire . $fichier); // Récursivité
            } else {
                $nom = substr($fichier, 6);
                rename($repertoire . $fichier, $repertoire . $nom);
            }
        }
        @ closedir($dh);

        return true;
    }

    return false;
}
 
    $error = parcourirArborescence(BASE);
    if ($error) {
    redirect_header('import.php', 2, _AM_TDMPICTURE_BASE);
    } else {
    redirect_header('import.php', 2,  _AM_TDMPICTURE_BASEERROR);
    }

break;
    
 case "list":
  default:
  
  global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

$aboutAdmin = new ModuleAdmin();
$file_protection = "Tatane, Xoopsfr<br /><br />
Cesag, Xoopsfr<br /><br />Grosdunord, Xoopsfr<br /><br />Phira, Xoopsfr<br />";
$aboutAdmin->addInfoBox(_AM_TDMPICTURE_NOTE);
$aboutAdmin->addInfoBoxLine(_AM_TDMPICTURE_NOTE, _AM_TDMPICTURE_NOTEDESC, '', '', 'information');
echo $aboutAdmin->addNavigation('import.php');
    echo $aboutAdmin->renderInfoBox();
    


//myalbum
$sq1 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("myalbum_photos")."'";
$result1=$xoopsDB->queryF($sq1);
$contact=$xoopsDB->fetchArray($result1);

$sq2 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("myalbum_cat")."'";
$result2=$xoopsDB->queryF($sq2);
$cat=$xoopsDB->fetchArray($result2);

$sq3 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("xoopscomments")."'";
$result3=$xoopsDB->queryF($sq3);
$com=$xoopsDB->fetchArray($result3);
 
    
 echo '<fieldset><legend class="CPmediumTitle">myalbum</legend>

		<br/>';
        if ($contact > 0) {
        echo '<b><span style="color: green; padding-left: 20px;"><img src="./../images/on.gif" > ' .$contact['Name']. ' : '.tdmpicture_PrettySize($contact['Data_length'] + $contact['Index_length']) . '  | <b><a href="import.php?op=import&base=myalbum_photos">'._AM_TDMPICTURE_IMPORT.'</a></b>';
        } else {
        echo '<b><span style="color: red; padding-left: 20px;"><img src="./../images/off.gif"> '. _AM_IMPORT_NONE .'</a></span></b>';
        }
        echo '<br/>';
        if ($cat > 0) {
        echo '<b><span style="color: green; padding-left: 20px;"><img src="./../images/on.gif" > ' .$cat['Name']. ' : '.tdmpicture_PrettySize($cat['Data_length'] + $cat['Index_length']) . '  | <b><a href="import.php?op=import&base=myalbum_cat">'._AM_TDMPICTURE_IMPORT.'</a></b>';
        } else {
        echo '<b><span style="color: red; padding-left: 20px;"><img src="./../images/off.gif"> '. _AM_IMPORT_NONE .'</a></span></b>';
        }
        echo '<br/>';
        if ($com > 0) {
        echo '<b><span style="color: green; padding-left: 20px;"><img src="./../images/on.gif" > ' .$com['Name']. ' : '.tdmpicture_PrettySize($com['Data_length'] + $com['Index_length']) . '  | <b><a href="import.php?op=comment&module=myalbum">'._AM_TDMPICTURE_IMPORT.'</a></b>';
        } else {
        echo '<b><span style="color: red; padding-left: 20px;"><img src="./../images/off.gif"> '. _AM_IMPORT_NONE .'</a></span></b>';
        }
        echo '<br /><br />
	</fieldset><br />';
//
//extgallery
$sq1 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("extgallery_publicphoto")."'";
$result1=$xoopsDB->queryF($sq1);
$contact=$xoopsDB->fetchArray($result1);

$sq2 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("extgallery_publiccat")."'";
$result2=$xoopsDB->queryF($sq2);
$cat=$xoopsDB->fetchArray($result2);

$sq3 = "SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("xoopscomments")."'";
$result3=$xoopsDB->queryF($sq3);
$com=$xoopsDB->fetchArray($result3);

 
 echo '<fieldset><legend class="CPmediumTitle">extgallery</legend>

		<br/>';
        if ($contact > 0) {
        echo '<b><span style="color: green; padding-left: 20px;"><img src="./../images/on.gif" > ' .$contact['Name']. ' : '.tdmpicture_PrettySize($contact['Data_length'] + $contact['Index_length']) . '  | <b><a href="import.php?op=import&base=extgallery_publicphoto">'._AM_TDMPICTURE_IMPORT.'</a></b>';
        } else {
        echo '<b><span style="color: red; padding-left: 20px;"><img src="./../images/off.gif"> '. _AM_IMPORT_NONE .'</a></span></b>';
        }
        echo '<br/>';
        if ($cat > 0) {
        echo '<b><span style="color: green; padding-left: 20px;"><img src="./../images/on.gif" > ' .$cat['Name']. ' : '.tdmpicture_PrettySize($cat['Data_length'] + $cat['Index_length']) . '  | <b><a href="import.php?op=import&base=extgallery_publiccat">'._AM_TDMPICTURE_IMPORT.'</a></b>';
        } else {
        echo '<b><span style="color: red; padding-left: 20px;"><img src="./../images/off.gif"> '. _AM_IMPORT_NONE .'</a></span></b>';
        }
        echo '<br/>';
        if ($com > 0) {
        echo '<b><span style="color: green; padding-left: 20px;"><img src="./../images/on.gif" > ' .$com['Name']. ' : '.tdmpicture_PrettySize($com['Data_length'] + $com['Index_length']) . '  | <b><a href="import.php?op=comment&module=extgallery">'._AM_TDMPICTURE_IMPORT.'</a></b>';
        } else {
        echo '<b><span style="color: red; padding-left: 20px;"><img src="./../images/off.gif"> '. _AM_IMPORT_NONE .'</a></span></b>';
        }
        echo '<br /><br />
	</fieldset><br />';
//
    
  break;
  }
  
function import_liste($liste) {
  global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

$import = array();
    switch($liste) {
    default:
    $import = false;
    break;
 
    case "myalbum_photos":
    $import['query'] = "INSERT INTO ".$xoopsDB->prefix("tdmpicture_file")." ( `file_id`, `file_cat`, `file_file`,
	`file_title`, `file_text`, `file_type`, `file_display`,	`file_hits`, `file_dl`,	`file_votes`,
	`file_counts`, `file_indate`, `file_uid`, `file_size`, `file_res_x`, `file_res_y`, `file_comments`, `file_ext`)
	SELECT  `lid`, `cid` , CONCAT(lid, '.', ext), `title`, NULL,	`ext`, `status`, `hits`, NULL,
	`votes`, `rating`, `date`, `submitter`,	NULL, `res_x` ,	`res_y`, `comments`, 1	FROM ".$xoopsDB->prefix("myalbum_photos")."";
    $import['conf_path'] = "tdm_myalbum_path";
    $import['conf_thumbs'] = "tdm_myalbum_thumb";
    break;
    case "myalbum_cat":
    $import['query'] = "INSERT INTO ".$xoopsDB->prefix("tdmpicture_cat")." ( `cat_id`, `cat_pid`, `cat_title`,
  `cat_date`, `cat_text`, `cat_img`, `cat_weight`, `cat_display`, `cat_uid`, `cat_index`)
	SELECT  cid,  pid, title, NULL,  NULL, imgurl, NULL, 1, 1, 1 FROM ".$xoopsDB->prefix("myalbum_cat")."";
    break;
    case "extgallery_publicphoto":
    $import['query'] = "INSERT INTO ".$xoopsDB->prefix("tdmpicture_file")." ( `file_id`, `file_cat`, `file_file`,
	`file_title`, `file_text`, `file_type`, `file_display`,	`file_hits`, `file_dl`,	`file_votes`,
	`file_counts`, `file_indate`, `file_uid`, `file_size`, `file_res_x`, `file_res_y`, `file_comments`, `file_ext`)
	SELECT  `photo_id`, `cat_id` , `photo_name`, `photo_desc`, `photo_title`,	NULL, `photo_approved`, `photo_hits`, NULL,
	`photo_nbrating`, `photo_rating`, `photo_date`, `uid`,	`photo_size`, `photo_res_x` ,	`photo_res_y`, `photo_comment`, 2	FROM ".$xoopsDB->prefix("extgallery_publicphoto")."";
    $import['conf_path'] = "tdm_extgallery_path";
    $import['conf_thumbs'] = "tdm_extgallery_thumb";
    break;
    case "extgallery_publiccat":
    $import['query'] = "INSERT INTO ".$xoopsDB->prefix("tdmpicture_cat")." ( `cat_id`, `cat_pid`, `cat_title`,
  `cat_date`, `cat_text`, `cat_img`, `cat_weight`, `cat_display`, `cat_uid`, `cat_index`)
	SELECT  cat_id,  cat_pid, cat_name, cat_date,  cat_desc, cat_imgurl, cat_weight, 1, 1, 1 FROM ".$xoopsDB->prefix("extgallery_publiccat")."";
    break;
    }
    
    return $import;

}
include 'admin_footer.php';
