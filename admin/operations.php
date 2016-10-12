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

include_once __DIR__ . '/admin_header.php';
xoops_cp_header();

TdmPictureUtilities::adminheader();

$fileHandler = xoops_getModuleHandler('tdmpicture_file', $moduleDirName);
$catHandler  = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);

$myts  = MyTextSanitizer::getInstance();
$op    = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
$size  = isset($_REQUEST['size']) ? $_REQUEST['size'] : 0;
if ($size <= 0 || $size > 10000) {
    $size = 10;
}

//$forceredo = isset( $_POST['forceredo'] ) ? (int)( $_POST['forceredo'] ) : false ;
$forcethumb = isset($_POST['forcethumb']) ? (int)$_POST['forcethumb'] : false;

//$removerec = isset( $_POST['removerec'] ) ? (int)( $_POST['removerec'] ) : false ;
$removerec = isset($_REQUEST['removerec']) ? (int)$_REQUEST['removerec'] : false;

$resize = isset($_REQUEST['resize']) ? (int)$_REQUEST['resize'] : false;

// get flag of safe_mode
//$safe_mode_flag = ini_get('safe_mode');

//$origine = "/chemin/vers/source/";
//$destination = "/chemin/vers/destination/";
//if (CopyDir($origine, $destination)) {
//   echo "Le dossier ".$origine." a ete copie avec succes vers ".$destination;
//};
// check or make thumbs_dir
//if ( $myalbum_makethumb && ! is_dir( $thumbs_dir ) ) {
//  if ($safe_mode_flag) {
///     redirect_header(XOOPS_URL."/modules/$moduleDirName/admin/",10,"At first create & chmod 777 '$thumbs_dir' by ftp or shell.");
//      exit ;
//  }

//$rs = mkdir( $thumbs_dir , 0777 ) ;
//if (! $rs) {
//  redirect_header(XOOPS_URL."/modules/$moduleDirName/",10,"$thumbs_dir is not a directory");
//  exit ;
//} else @chmod( $thumbs_dir , 0777 ) ;
//}

if (!empty($_POST['submit'])) {
    ob_start();

    $result         = $xoopsDB->query("SELECT lid , ext , res_x , res_y FROM $table_photos ORDER BY lid LIMIT $start , $size")
                      || die('DB Error');
    $record_counter = 0;
    while (list($lid, $ext, $w, $h) = $xoopsDB->fetchRow($result)) {
        ++$record_counter;
        echo ($record_counter + $start - 1) . ') ';
        printf(_AM_FMT_CHECKING, "$lid.$ext");

        // Check if the main image exists
        if (!is_readable("$photos_dir/$lid.$ext")) {
            echo _AM_MB_PHOTONOTEXISTS . ' &nbsp; ';
            if ($removerec) {
                myalbum_delete_photos("lid='$lid'");
                echo _AM_MB_RECREMOVED . "<br>\n";
            } else {
                echo _AM_MB_SKIPPED . "<br>\n";
            }
            continue;
        }

        // Check if the file is normal image
        if (!in_array(strtolower($ext), $myalbum_normal_exts)) {
            if ($forceredo || !is_readable("$thumbs_dir/$lid.gif")) {
                myalbum_create_thumb("$photos_dir/$lid.$ext", $lid, $ext);
                echo _AM_MB_CREATEDTHUMBS . "<br>\n";
            } else {
                echo _AM_MB_SKIPPED . "<br>\n";
            }
            continue;
        }

        // Size of main photo
        list($true_w, $true_h) = getimagesize("$photos_dir/$lid.$ext");
        echo "{$true_w}x{$true_h} .. ";

        // Check and resize the main photo if necessary
        if ($resize && ($true_w > $myalbum_width || $true_h > $myalbum_height)) {
            $tmp_path = "$photos_dir/myalbum_tmp_photo";
            @unlink($tmp_path);
            rename("$photos_dir/$lid.$ext", $tmp_path);
            myalbum_modify_photo($tmp_path, "$photos_dir/$lid.$ext");
            @unlink($tmp_path);
            echo _AM_MB_PHOTORESIZED . ' &nbsp; ';
            list($true_w, $true_h) = getimagesize("$photos_dir/$lid.$ext");
        }

        // Check and repair record of the photo if necessary
        if ($true_w != $w || $true_h != $h) {
            $xoopsDB->query("UPDATE $table_photos SET res_x=$true_w, res_y=$true_h WHERE lid=$lid")
            || die('DB error: UPDATE photo table.');
            echo _AM_MB_SIZEREPAIRED . ' &nbsp; ';
        }

        // Create Thumbs
        if (is_readable("$thumbs_dir/$lid.$ext")) {
            list($thumb_w, $thumb_h) = getimagesize("$thumbs_dir/$lid.$ext");
            echo "{$thumb_w}x{$thumb_h} ... ";
            if ($forceredo) {
                $retcode = myalbum_create_thumb("$photos_dir/$lid.$ext", $lid, $ext);
            } else {
                $retcode = 3;
            }
        } else {
            if ($myalbum_makethumb) {
                $retcode = myalbum_create_thumb("$photos_dir/$lid.$ext", $lid, $ext);
            } else {
                $retcode = 3;
            }
        }

        switch ($retcode) {
            case 0 :
                echo _AM_MB_FAILEDREADING . "<br>\n";
                break;
            case 1 :
                echo _AM_MB_CREATEDTHUMBS . "<br>\n";
                break;
            case 2 :
                echo _AM_MB_BIGTHUMBS . "<br>\n";
                break;
            case 3 :
                echo _AM_MB_SKIPPED . "<br>\n";
                break;
        }
    }
    $result_str = ob_get_contents();
    ob_end_clean();

    $start += $size;
}

// Make form objects
$form = new XoopsThemeForm(_AM_FORM_RECORDMAINTENANCE, 'batchupload', 'redothumbs.php');
$form->setExtra("enctype='multipart/form-data'");

$start_text      = new XoopsFormText(_AM_TEXT_RECORDFORSTARTING, 'start', 20, 20, $start);
$size_text       = new XoopsFormText(_AM_TEXT_NUMBERATATIME . "<br><br><span style='font-weight:normal'>" . _AM_LABEL_DESCNUMBERATATIME . '</span>', 'size', 20, 20, $size);
$forceredo_radio = new XoopsFormRadioYN(_AM_RADIO_FORCEREDO, 'forceredo', $forceredo);
$removerec_radio = new XoopsFormRadioYN(_AM_RADIO_REMOVEREC, 'removerec', $removerec);
$resize_radio    = new XoopsFormRadioYN(_AM_RADIO_RESIZE . " ({$myalbum_width}x{$myalbum_height})", 'resize', $resize);

if (isset($record_counter) && $record_counter < $size) {
    $submit_button = new XoopsFormLabel('', _AM_MB_FINISHED . " &nbsp; <a href='redothumbs.php'>" . _AM_LINK_RESTART . '</a>');
} else {
    $submit_button = new XoopsFormButton('', 'submit', _AM_SUBMIT_NEXT, 'submit');
}

// Render forms
xoops_cp_header();
include __DIR__ . '/mymenu.php';

// check $xoopsModule
if (!is_object($xoopsModule)) {
    redirect_header("$mod_url/", 1, _NOPERM);
}
echo "<h3 style='text-align:left;'>" . sprintf(_AM_H3_FMT_RECORDMAINTENANCE, $xoopsModule->name()) . "</h3>\n";

myalbum_opentable();
$form->addElement($start_text);
$form->addElement($size_text);
$form->addElement($forceredo_radio);
$form->addElement($removerec_radio);
$form->addElement($resize_radio);
$form->addElement($submit_button);
$form->display();
myalbum_closetable();

if (isset($result_str)) {
    echo "<br>\n";
    echo $result_str;
}

xoops_cp_footer();
