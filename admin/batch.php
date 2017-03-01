<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence GPL Copyright (c)  (http://xoops.org)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @license     TDM GPL license
 * @author      TDM TEAM DEV MODULE
 *
 * ****************************************************************************
 */

use Xmf\Module\Helper;
use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
$moduleDirName = basename(dirname(__DIR__));
$moduleHelper  = Helper::getHelper($moduleDirName);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

$fileHandler = xoops_getModuleHandler('file', $moduleDirName);
$catHandler  = xoops_getModuleHandler('category', $moduleDirName);

$op           = Request::getVar('op', 'list'); //
$batch_folder = Request::getString('batch_folder', '' . XOOPS_ROOT_PATH . "/uploads/$moduleDirName/photo/");
$nbPhotos     = Request::getInt('nbPhotos', 0, 'POST');

switch ($op) {

    case 'batch':
        global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

        if (get_cfg_var('max_execution_time') === null) {
            $maxExecTime = 10;
        } else {
            $maxExecTime = get_cfg_var('max_execution_time');
        }
        $maxTime        = time() + $maxExecTime - 5;
        $maxTimeReached = false;

        $photos = array();

        $dir = opendir($batch_folder);
        while ($f = readdir($dir)) {
            if (is_file($batch_folder . $f)) {
                if (preg_match('/.*gif/', strtolower($f)) || preg_match('/.*jpg/', strtolower($f))
                    || preg_match('/.*png/', strtolower($f))
                ) {
                    $photos[] = $f;
                }
            }
        }

        if (count($photos) < 1) {
            redirect_header('batch.php', 2, _AM_TDMPICTURE_BASEERROR);
            exit;
        }

        $i = 0;
        foreach ($photos as $k => $photo) {
            if ($k < $nbPhotos) {
                continue;
            }
            // copie photo
            //rename($batchRep.$photo,$photoRep.$photo);
            copy($batch_folder . $photo, TDMPICTURE_UPLOADS_PATH . $photo);

            //class photo
            $class_photo = new Thumbnail(TDMPICTURE_UPLOADS_PATH . $photo);
            //ajout a la base
            $obj = $fileHandler->create();
            $obj->setVar('file_cat', $_REQUEST['file_cat']);
            $obj->setVar('file_text', $_REQUEST['file_text']);
            $obj->setVar('file_display', $_REQUEST['file_display']);
            $obj->setVar('file_indate', time());
            $obj->setVar('file_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

            //$obj->setVar('file_title', $ext);
            $obj->setVar('file_file', $photo);
            $obj->setVar('file_res_x', $class_photo->getCurrentWidth());
            $obj->setVar('file_res_y', $class_photo->getCurrentHeight());
            $obj->setVar('file_size', $class_photo->getCurrentSize());
            $obj->setVar('file_type', $class_photo->getCurrentType());

            $erreur = $fileHandler->insert($obj);
            //creer le thumb
            $fileHandler->thumb($class_photo, $photo);

            ++$i;

            if (time() > $maxTime) {
                $maxTimeReached = true;
                break;
            }
        }

        if (count($photos) < $i + $nbPhotos) {
            $maxTimeReached = false;
        }

        if ($maxTimeReached) {
            $adminObject = \Xmf\Module\Admin::getInstance();
            //$aboutAdmin->addInfoBox(_AM_TDMPICTURE_NOTE);
            //$aboutAdmin->addInfoBoxLine(_AM_TDMPICTURE_NOTE, _AM_TDMPICTURE_BATCHDESC, '', '', 'information');
            $adminObject->displayNavigation(basename(__FILE__));
            echo $aboutAdmin->renderInfoBox();

            echo '<div class="confirmMsg">';

            $photoMore = count($photos) - $i;
            echo '<h4>' . sprintf(_AM_TDMPICTURE_BATCH_NEXT, $i + $nbPhotos, $photoMore) . '</h4>';
            echo '<form method="post" action="batch.php?op=batch">';
            echo '<input type="hidden" name="file_cat" value="' . $_POST['file_cat'] . '" />';
            echo '<input type="hidden" name="file_display" value="' . $_POST['file_display'] . '" />';
            echo '<input type="hidden" name="photo_desc" value="' . $_POST['photo_desc'] . '" />';
            echo '<input type="hidden" name="batch_folder" value="' . $_POST['batch_folder'] . '" />';
            echo '<input type="hidden" name="nbPhotos" value="' . ($i + $nbPhotos) . '" />';
            echo '<input type="submit" name="confirm_submit" value="Continue" />';
            echo '</form>';
            echo '</div>';

            //  xoops_confirm(array('file_cat'=>$_POST['file_cat'], 'file_display'=>$_POST['file_display'], 'file_text'=>$_POST['file_text'], 'batch_folder'=>$_POST['batch_folder'], 'nbPhoto'=>$nbPhotos), 'batch.php?op=batch', sprintf(_AM_TDMPICTURE_BATCH_NEXT,($i + $nbPhotos), $photoMore));
        } else {
            redirect_header('batch.php', 2, _AM_TDMPICTURE_BASE);
        }

        break;

    case 'list':
    default:

        global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

        $gpermHandler = xoops_getHandler('groupperm');
        //permission
        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
            $uid    = $xoopsUser->getVar('uid');
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $uid    = 0;
        }

        $aboutAdmin = \Xmf\Module\Admin::getInstance();
        //$aboutAdmin->addInfoBox(_AM_TDMPICTURE_NOTE);
        //$aboutAdmin->addInfoBoxLine(_AM_TDMPICTURE_NOTE, _AM_TDMPICTURE_BATCHDESC, '', '', 'information');
        $adminObject->displayNavigation(basename(__FILE__));
        echo $aboutAdmin->renderInfoBox();

        //compte les fichiers dans le dossier
        $nbPhotos = 0;
        $dir      = opendir($batch_folder);
        while ($f = readdir($dir)) {
            if (is_file($batch_folder . $f)) {
                ++$nbPhotos;
            }
        }

        $form = new XoopsThemeForm(_AM_TDMPICTURE_FOLDER, 'batch', 'batch.php', '', true);
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_TITLE, 'batch_folder', 100, 255, $batch_folder), true);
        $indeximage_tray = new XoopsFormElementTray('', '&nbsp;');
        $indeximage_tray->addElement(new XoopsFormLabel('', sprintf(_AM_TDMPICTURE_THEREAREIMG, $nbPhotos)));
        $form->addElement($indeximage_tray);
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->display();

        echo '<br>';

        $form = new XoopsThemeForm(_AM_TDMPICTURE_ADD, 'batch_photo', 'batch.php', '', true);

        //categorie
        $catHandler = xoops_getModuleHandler('category', $moduleDirName);

        $cat_select = new XoopsFormSelect(_AM_TDMPICTURE_CAT, 'file_cat');
        //$cat_select->addOption(0, _ALL);
        $cat_select->addOptionArray($catHandler->getList());
        $form->addElement($cat_select);
        //

        //editor
        $editor_configs           = array();
        $editor_configs['name']   = 'file_text';
        $editor_configs['value']  = '';
        $editor_configs['rows']   = 20;
        $editor_configs['cols']   = 80;
        $editor_configs['width']  = '100%';
        $editor_configs['height'] = '400px';
        $editor_configs['editor'] = $moduleHelper->getConfig('tdmpicture_editor');
        $form->addElement(new XoopsFormEditor(_MD_TDMPICTURE_TEXT, 'file_text', $editor_configs), false);

        //
        $form->addElement(new XoopsFormRadioYN(_AM_TDMPICTURE_DISPLAYUSER, 'file_display', 0, _YES, _NO));
        $form->addElement(new XoopsFormRadioYN(_AM_TDMPICTURE_BATCH_DELETE, 'batch_delete', 0, _YES, _NO));
        $form->addElement(new XoopsFormHidden('batch_folder', $batch_folder));
        $form->addElement(new XoopsFormHidden('op', 'batch'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->display();

        echo '<br>';

        //

        break;
}

/**
 * @param $liste
 * @return array|bool
 */
function import_liste($liste)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule;

    $import = array();
    switch ($liste) {
        default:
            $import = false;
            break;

        case 'myalbum_photos':
            $import['query']       = 'INSERT INTO ' . $xoopsDB->prefix('tdmpicture_file') . " ( `file_id`, `file_cat`, `file_file`,
    `file_title`, `file_text`, `file_type`, `file_display`, `file_hits`, `file_dl`, `file_votes`,
    `file_counts`, `file_indate`, `file_uid`, `file_size`, `file_res_x`, `file_res_y`, `file_comments`, `file_ext`)
    SELECT  `lid`, `cid` , CONCAT(lid, '.', ext), `title`, NULL,    `ext`, `status`, `hits`, NULL,
    `votes`, `rating`, `date`, `submitter`, NULL, `res_x` , `res_y`, `comments`, 1  FROM " . $xoopsDB->prefix('myalbum_photos') . '';
            $import['conf_path']   = 'tdm_myalbum_path';
            $import['conf_thumbs'] = 'tdm_myalbum_thumb';
            break;
        case 'myalbum_cat':
            $import['query'] = 'INSERT INTO ' . $xoopsDB->prefix('tdmpicture_cat') . ' ( `cat_id`, `cat_pid`, `cat_title`,
  `cat_date`, `cat_text`, `cat_img`, `cat_weight`, `cat_display`, `cat_uid`, `cat_index`)
    SELECT  cid,  pid, title, NULL,  NULL, imgurl, NULL, 1, 1, 1 FROM ' . $xoopsDB->prefix('myalbum_cat') . '';
            break;
        case 'extgallery_publicphoto':
            $import['query']       = 'INSERT INTO ' . $xoopsDB->prefix('tdmpicture_file') . ' ( `file_id`, `file_cat`, `file_file`,
    `file_title`, `file_text`, `file_type`, `file_display`, `file_hits`, `file_dl`, `file_votes`,
    `file_counts`, `file_indate`, `file_uid`, `file_size`, `file_res_x`, `file_res_y`, `file_comments`, `file_ext`)
    SELECT  `photo_id`, `cat_id` , `photo_name`, `photo_desc`, `photo_title`,   NULL, `photo_approved`, `photo_hits`, NULL,
    `photo_nbrating`, `photo_rating`, `photo_date`, `uid`,  `photo_size`, `photo_res_x` ,   `photo_res_y`, `photo_comment`, 2   FROM ' . $xoopsDB->prefix('extgallery_publicphoto') . '';
            $import['conf_path']   = 'tdm_extgallery_path';
            $import['conf_thumbs'] = 'tdm_extgallery_thumb';
            break;
        case 'extgallery_publiccat':
            $import['query'] = 'INSERT INTO ' . $xoopsDB->prefix('tdmpicture_cat') . ' ( `cat_id`, `cat_pid`, `cat_title`,
  `cat_date`, `cat_text`, `cat_img`, `cat_weight`, `cat_display`, `cat_uid`, `cat_index`)
    SELECT  cat_id,  cat_pid, cat_name, cat_date,  cat_desc, cat_imgurl, cat_weight, 1, 1, 1 FROM ' . $xoopsDB->prefix('extgallery_publiccat') . '';
            break;
    }

    return $import;
}

require_once __DIR__ . '/admin_footer.php';
