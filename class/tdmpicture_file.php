<?php
use Xmf\Module\Helper;


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

class TDMPicture_file extends XoopsObject
{
    // constructor
    /**
     * TDMPicture_file constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('file_id', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_cat', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_file', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('file_title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('file_text', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('file_type', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('file_display', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('file_hits', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_dl', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_votes', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_counts', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_indate', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_size', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_res_x', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_res_y', XOBJ_DTYPE_INT, null, false);
        $this->initVar('file_comments', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('file_ext', XOBJ_DTYPE_INT, null, false, 11);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
    }

    /**
     * @param bool $force
     * @return array
     */
    public function getFilePath($force = false)
    {
        $moduleDirName = basename(dirname(__DIR__));
        $moduleHelper = Helper::getHelper($moduleDirName);
        switch ($this->getVar('file_ext')) {
            case 0:
            default:
                $image = $moduleHelper->getConfig('tdm_upload_path');
                $thumb = $moduleHelper->getConfig('tdm_upload_thumb');
                break;
            case 1:
                $image = $moduleHelper->getConfig('tdm_myalbum_path');
                $thumb = $moduleHelper->getConfig('tdm_myalbum_thumb');
                break;
            case 2:
                $image = $moduleHelper->getConfig('tdm_extgallery_path');
                $thumb = $moduleHelper->getConfig('tdm_extgallery_thumb');
                break;
        }

        if (!empty($force)) {
            $image .=  '/'. $force;
            $thumb .=  '/'.  $force;
        }

        return array(
            'image_url'  => XOOPS_URL .  $image,
            'image_path' => XOOPS_ROOT_PATH .   $image,
            'thumb_url'  => XOOPS_URL .   $thumb,
            'thumb_path' => XOOPS_ROOT_PATH .   $thumb
        );
    }

    /**
     * @return mixed
     */
    public function getFileThumb()
    {
        $moduleDirName = basename(dirname(__DIR__));
        $moduleHelper = Helper::getHelper($moduleDirName);
        switch ($this->getVar('file_ext')) {
            case 0:
            default:
                $url = $moduleHelper->getConfig('tdm_upload_thumb');
                break;
            case 1:
                $url = $moduleHelper->getConfig('tdm_myalbum_thumb');
                break;
            case 2:
                $url = $moduleHelper->getConfig('tdm_extgallery_thumb');
                break;
        }

        return $url;
    }

    /**
     * @return XoopsSimpleForm
     */
    public function getFormLink()
    {
        $file_path = TDMPICTURE_URL . '/get.php?st=' . $this->getVar('file_id');
        $url       = TDMPICTURE_URL . '/viewfile.php?st=' . $this->getVar('file_id');
        $form      = new XoopsSimpleForm('', 'form', '', 'post', true);
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_LINK, '', 100, 255, $url));
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_LINKFULLSCREEN, '', 100, 255, TDMPICTURE_URL . '/get.php?st=' . $this->getVar('file_id') . '&size=full'));
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_LINKDHUMB, '', 100, 255, TDMPICTURE_URL . '/get.php?st=' . $this->getVar('file_id')));
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_LINKFORUM, '', 100, 255, '[url=' . $url . '][img]' . $file_path . '[/img][/url]'));
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_LINKFORUM1, '', 100, 255, '[url=' . $url . '][img=' . $file_path . '][/url]'));
        $form->addElement(new XoopsFormText(_MD_TDMPICTURE_LINKHTML, '', 100, 255, '<a href="' . $url . '" target="_blank"><img src="' . $file_path . '" border="0" alt="' . $this->getVar('file_title') . '"/></a>'));

        return $form;
    }

    /**
     * @param bool $action
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        $moduleDirName = basename(dirname(__DIR__));
        $moduleHelper = Helper::getHelper($moduleDirName);

        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? sprintf(_MD_TDMPICTURE_ADD) : sprintf(_MD_TDMPICTURE_EDIT);

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $gpermHandler = xoops_getHandler('groupperm');
        //permission
        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
            $uid    = $xoopsUser->getVar('uid');
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $uid    = 0;
        }

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //  $form->addElement(new XoopsFormText(_MD_TDMPICTURE_TITLE, 'filecat_title', 100, 255, $this->getVar('filecat_title')), true);
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('file_id')));
            $form->addElement(new XoopsFormText(_MD_TDMPICTURE_TITLE, 'file_title', 100, 255, $this->getVar('file_title')), true);
        }

        //

        if (!$this->isNew()) {
            $button_file = new XoopsFormText(_MD_TDMPICTURE_URL, 'file_file', 100, 255, $this->getVar('file_file'));
            $button_file->setExtra('disabled');
            $form->addElement($button_file);

            //categorie
            $catHandler = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);

            $criteriaDisplay = new CriteriaCompo();
            $criteriaDisplay->add(new Criteria('cat_display', 1));
            $criteriaDisplay->add(new Criteria('cat_index', 1));

            $criteriaUser = new CriteriaCompo();
            $criteriaUser->add($criteriaDisplay);
            $criteriaUser->add(new Criteria('cat_display', 1), 'OR');
            $criteriaUser->add(new Criteria('cat_uid', $uid));

            $arr    = $catHandler->getall($criteriaUser);
            $mytree = new TDMObjectTree($arr, 'cat_id', 'cat_pid');
            $form->addElement(new XoopsFormLabel(_MD_TDMPICTURE_CAT, $mytree->makeSelBox('file_cat', 'cat_title', '-', $this->getVar('file_cat'), '', 0, '', 'tdmpicture_catview')));

            //

            //editor
            $editor_configs           = array();
            $editor_configs['name']   = 'file_text';
            $editor_configs['value']  = $this->getVar('file_text', 'e');
            $editor_configs['rows']   = 20;
            $editor_configs['cols']   = 80;
            $editor_configs['width']  = '100%';
            $editor_configs['height'] = '400px';
            $editor_configs['editor'] = $moduleHelper->getConfig('tdmpicture_editor');
            $form->addElement(new XoopsFormEditor(_MD_TDMPICTURE_TEXT, 'file_text', $editor_configs), false);

            //
            $form->addElement(new XoopsFormText(_MD_TDMPICTURE_SIZE, 'file_size', 10, 11, $this->getVar('file_size')));

            $form->addElement(new XoopsFormText(_MD_TDMPICTURE_WIDTH, 'file_res_x', 10, 11, $this->getVar('file_res_x')));

            $form->addElement(new XoopsFormText(_MD_TDMPICTURE_HEIGHT, 'file_res_y', 10, 11, $this->getVar('file_res_y')));

            //display
            if (is_object($xoopsUser) && $xoopsUser->isAdmin()) {
                $form->addElement(new XoopsFormRadioYN(_MD_TDMPICTURE_DISPLAYUSER, 'file_display', $this->getVar('file_display'), _YES, _NO));
            } else {
                $gpermHandler = xoops_getHandler('groupperm');

                if (is_object($xoopsUser)) {
                    $groups = $xoopsUser->getGroups();
                } else {
                    $groups = XOOPS_GROUP_ANONYMOUS;
                }

                if ($gpermHandler->checkRight('tdmpicture_view', 16, $groups, $xoopsModule->getVar('mid'))) {
                    $form->addElement(new XoopsFormHidden('file_display', 1));
                } else {
                    $form->addElement(new XoopsFormHidden('file_display', 0));
                }
            }

            $form->addElement(new XoopsFormHidden('op', 'edit_file'));
            $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        }

        if ($this->isNew()) {

            //categorie
            $catHandler = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);

            $criteriaDisplay = new CriteriaCompo();
            $criteriaDisplay->add(new Criteria('cat_display', 1));
            $criteriaDisplay->add(new Criteria('cat_index', 1));

            $criteriaUser = new CriteriaCompo();
            $criteriaUser->add($criteriaDisplay);
            $criteriaUser->add(new Criteria('cat_display', 1), 'OR');
            $criteriaUser->add(new Criteria('cat_uid', $uid));

            $arr = $catHandler->getall($criteriaUser);
            //$mytree = new XoopsObjectTree($arr, 'cat_id', 'cat_pid');
            //$form->addElement(new XoopsFormLabel(_MD_TDMPICTURE_CAT, $mytree->makeSelBox('file_cat', 'cat_title','-', $this->getVar('cat_pid'), true)), true);

            $mytree = new TDMObjectTree($arr, 'cat_id', 'cat_pid');
            $form->addElement(new XoopsFormLabel(_MD_TDMPICTURE_CAT, $mytree->makeSelBox('file_cat', 'cat_title', '-', $this->getVar('cat_pid'), '', 0, '', 'tdmpicture_catview')));

            //

            if (is_object($xoopsUser) && $xoopsUser->isAdmin()) {
                $form->addElement(new XoopsFormRadioYN(_MD_TDMPICTURE_DISPLAYUSER, 'file_display', 1, _YES, _NO));
            } else {
                $gpermHandler = xoops_getHandler('groupperm');

                if (is_object($xoopsUser)) {
                    $groups = $xoopsUser->getGroups();
                } else {
                    $groups = XOOPS_GROUP_ANONYMOUS;
                }

                if ($gpermHandler->checkRight('tdmpicture_view', 16, $groups, $xoopsModule->getVar('mid'))) {
                    $form->addElement(new XoopsFormHidden('file_display', 1));
                } else {
                    $form->insertBreak('<div align="center">' . _MD_TDMPICTURE_UPLOAD_LIMIT . '</div>', 'odd');
                    $form->addElement(new XoopsFormHidden('file_display', 0));
                }
            }

            //javascript
            //echo '<script language="JavaScript" type="text/javascript">
            //    if (_ie == true) document.writeln(\'<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" WIDTH = "0" HEIGHT = "0" NAME = "JUploadApplet"  codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#Version=5,0,0,3"><noembed><xmp>\');
            //    else if (_ns == true && _ns6 == false) document.writeln(\'<embed \' +
            //      \'type="application/x-java-applet;version=1.5" \
            //            CODE = "wjhk.jupload2.EmptyApplet" \
            //            ARCHIVE = "'.TDMPICTURE_URL.'/jupload/wjhk.jupload.jar" \
            //           NAME = "JUploadApplet" \
            //           WIDTH = "0" \
            //           HEIGHT = "0" \
            //           type ="application/x-java-applet;version=1.6" \
            //           scriptable ="false" \' +
            //      \'scriptable=false \' +
            //      \'pluginspage="http://java.sun.com/products/plugin/index.html#download"><noembed><xmp>\');
            //</script>';

            //note
            $form->insertBreak('<div align="center">' . _MD_TDMPICTURE_UPLOAD_DESC . '</div>', 'odd');

            //$form->insertBreak('
            //<applet code="wjhk.jupload2.JUploadApplet"
            //           archive="'.TDMPICTURE_URL.'/jupload/wjhk.jupload.jar" width="'.$moduleHelper->getConfig('tdmpicture_java_width').'" height="'.$moduleHelper->getConfig('tdmpicture_java_heigth').'" alt=""
            //            mayscript>
            //            <param name="postURL"
            //                value="'.TDMPICTURE_URL.'/include/jquery.php?op=upload&file_cat='.$_REQUEST['file_cat'].'&file_display='.$_REQUEST['file_display'].'" />
            //           <param name="maxChunkSize" value="'.$moduleHelper->getConfig('tdmpicture_mimemax').'" />
            //           <param name="uploadPolicy" value="PictureUploadPolicy" />
            //            <param name="nbFilesPerRequest" value="1" />
            //            <!-- Optionnal, see code comments -->
            //           <param name="maxPicHeight" value="'.$moduleHelper->getConfig('tdmpicture_full_heigth').'" />
            //           <!-- Optionnal, see code comments -->
            //           <param name="maxPicWidth" value="'.$moduleHelper->getConfig('tdmpicture_full_width').'" />
            //           <!-- Optionnal, see code comments -->
            //           <param name="debugLevel" value="0" />
            //          <param name="showLogWindow" value="false" />
            //           <!-- Optionnal, see code comments --> Java 1.5 or higher
            //           plugin required. </applet> <br>

            //', 'odd');

            echo '<script language="JavaScript" type="text/javascript">
var $tdmpicture = jQuery.noConflict();
$tdmpicture(document).ready( function() {

    //Cr�ation dun premier input
    creerInput();
    });


</script>';

            //pour multi upload
            $form->addElement(new XoopsFormHidden('MAX_FILE_SIZE', $moduleHelper->getConfig('tdmpicture_mimemax')));

            $fileseltray = new XoopsFormElementTray(_MD_TDMPICTURE_UPLOAD, '<br>');
            $fileseltray->addElement(new XoopsFormLabel('<div id="tdmfiletext" maxlength="' . $moduleHelper->getConfig('tdmpicture_upmax') . '">
            <b>' . sprintf(_MD_TDMPICTURE_MULTIUPLOAD, $moduleHelper->getConfig('tdmpicture_upmax')) . '</b><br>

        </div>'), false);
            $form->addElement($fileseltray);

            //
            //centrage
            //$tagchannel = array('100|75' => '100x75 (avatar)', '150|112' => '150x112 (thumbnail)', '320|240' => '320x240 (for websites and email)', '640|480' => '640x480 (for message boards)', '800|600' => '800x600 (15-inch monitor)', '1024|768' => '1024x768 (17-inch monitor)', '1280|1024' => '1280x1024 (19-inch monitor)', '1600|1200' => '1600x1200 (21-inch monitor)', '1024|768' => '1024x768 (17-inch monitor)' );
            $tagchannel        = explode('|', $moduleHelper->getConfig('tdmpicture_size'));
            $tag               = explode('|', $moduleHelper->getConfig('tdmpicture_size'));
            $data              = array_combine($tagchannel, $tag);
            $tagchannel_select = new XoopsFormSelect(_MD_TDMPICTURE_RESIZE, 'resize', 0);
            $tagchannel_select->addOption(0, _NONE);
            $tagchannel_select->addOptionArray($data);

            $form->addElement($tagchannel_select);

            $form->addElement(new XoopsFormHidden('op', 'save_file'));

            $button_tray = new XoopsFormElementTray('', '');

            $button_create = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
            $button_tray->addElement($button_create);

            $button_cancel = new XoopsFormButton('', 'submit', _CANCEL, 'button');
            $button_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($button_cancel);

            $form->addElement($button_tray);
        }

        return $form;
    }
}

/**
 * Class TDMPicturetdmpicture_fileHandler
 */
class TDMPicturetdmpicture_fileHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null $criteria
     * @param null $fields
     * @param bool $asObject
     * @param bool $id_as_key
     * @return array
     */
    public function &getGroupby($criteria = null, $fields = null, $asObject = true, $id_as_key = true)
    {
        if (is_array($fields) && count($fields) > 0) {
            if (!in_array($this->handler->keyName, $fields)) {
                $fields[] = $this->handler->keyName;
            }
            $select = '`' . implode('`, `', $fields) . '`';
        } else {
            $select = '*';
        }
        $limit = null;
        $start = null;
        $sql   = "SELECT {$select} FROM `{$this->table}`";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($groupby = $criteria->getGroupby()) {
                $sql .= ' ' . $groupby;
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        if (empty($orderSet)) {
            $sql .= " ORDER BY `{$this->keyName}` DESC";
        }
        $result = $this->db->query($sql, $limit, $start);
        $ret    = array();
        if ($asObject) {
            while ($myrow = $this->db->fetchArray($result)) {
                $object = $this->create(false);
                $object->assignVars($myrow);
                if ($id_as_key) {
                    $ret[$myrow[$this->keyName]] = $object;
                } else {
                    $ret[] = $object;
                }
                unset($object);
            }
        } else {
            $object = $this->create(false);
            while ($myrow = $this->db->fetchArray($result)) {
                $object->assignVars($myrow);
                if ($id_as_key) {
                    $ret[$myrow[$this->keyName]] = $object->getValues(array_keys($myrow));
                } else {
                    $ret[] = $object->getValues(array_keys($myrow));
                }
            }
            unset($object);
        }

        return $ret;
    }

    /**
     * TDMPicturetdmpicture_fileHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        $this->_dirname = basename(dirname(__DIR__));
        parent::__construct($db, 'tdmpicture_file', 'TDMPicture_file', 'file_id', 'file_title');
    }

    /**
     * @param $ids
     * @return bool
     */
    public function deletes($ids)
    {
        foreach ($ids as $lid) {
            $this->delete($lid, true);
        }

        return true;
    }

    /**
     * @param XoopsObject $photo
     * @param bool        $force
     * @return bool
     */
    public function delete(XoopsObject $photo, $force = true)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;

        if (is_numeric($photo)) {
            $obj = $this->get($photo);
        }

        //$file_thumb = $obj->getFileThumb().$obj->getVar("file_file");
        //$file_path = $obj->getFilePath().$obj->getVar("file_file");
        $file_path = $obj->getFilePath($obj->getVar('file_file'));

        xoops_comment_delete($xoopsModule->getVar('mid'), $obj->getVar('file_id'));
        //xoops_notification_deletebyitem( $xoopsModule->getVar('mid') , 'tdmpicture' , $obj->getVar('file_id') ) ;

        @unlink($file_path['image_path']);
        @unlink($file_path['thumb_path']);

        return parent::delete($obj, $force);
    }

    /**
     * @param $ids
     * @return bool
     */
    public function displays($ids)
    {
        foreach ($ids as $lid) {
            $this->display($lid, true);
        }

        return true;
    }

    /**
     * @param      $photo
     * @param bool $force
     * @return bool
     */
    public function display($photo, $force = true)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;

        if (is_numeric($photo)) {
            $obj = $this->get($photo);
        }

        $obj->getVar('file_display') == 1 ? $obj->setVar('file_display', 0) : $obj->setVar('file_display', 1);
        $this->insert($obj);

        return true;
    }

    /**
     * @param $ids
     * @return bool
     */
    public function updates($ids)
    {
        foreach ($ids as $lid) {
            $this->update($lid, true);
        }

        return true;
    }

    /**
     * @param      $photo
     * @param bool $force
     * @return bool
     */
    public function update($photo, $force = true)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;

        if (is_numeric($photo)) {
            $obj = $this->get($photo);
        }
        $file_path = $obj->getFilePath($obj->getVar('file_file'));
        $photo     = new Thumbnail($file_path['image_path']);

        $obj->setVar('file_indate', time());
        $obj->setVar('file_res_x', $photo->getCurrentWidth());
        $obj->setVar('file_res_y', $photo->getCurrentHeight());
        $obj->setVar('file_size', $photo->getCurrentSize());
        $obj->setVar('file_type', $photo->getCurrentType());
        //thumb
        $this->thumb($obj->getVar('file_id'));

        $this->insert($obj);

        return true;
    }

    /**
     * @param $ids
     * @return bool
     */
    public function thumbs($ids)
    {
        foreach ($ids as $lid) {
            $this->thumb($lid, false);
        }

        return true;
    }

    /**
     * @param      $class_photo
     * @param bool $photo
     * @return bool
     */
    public function thumb($class_photo, $photo = false)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        $moduleDirName = basename(dirname(__DIR__));
        $moduleHelper = Helper::getHelper($moduleDirName);

        if (is_numeric($class_photo)) {
            $obj         = $this->get($class_photo);
            $file_path   = $obj->getFilePath($obj->getVar('file_file'));
            $class_photo = new Thumbnail($file_path['image_path']);
        }
        //        else {
        //      $obj = $this->create();
        //      $file_path = $obj->getFilePath($force);
        //      }

        //$thumb = new Thumbnail($file_path['image_path']);
        //   $class_photo = new Thumbnail($file_path['image_path']);

        switch ($moduleHelper->getConfig('tdmpicture_thumb_style')) {

            case 'center':
            default:
                $class_photo->cropFromCenter($moduleHelper->getConfig('tdmpicture_thumb_width'));
                break;

            case 'limit-width-height':
                $class_photo->resize($moduleHelper->getConfig('tdmpicture_thumb_width'), $moduleHelper->getConfig('tdmpicture_thumb_heigth'));
                break;

            case 'limit-width':
                $class_photo->resize($moduleHelper->getConfig('tdmpicture_thumb_width'), '');
                break;

            case 'limit-height':
                $class_photo->resize('', $moduleHelper->getConfig('tdmpicture_thumb_heigth'));
                break;
        }
        //  $test1 = $class_photo->fileName;
        $class_photo->save(TDMPICTURE_THUMB_PATH . '/' .$photo, $moduleHelper->getConfig('tdmpicture_thumb_quality'));

        //test si reussis
        return true;
    }
}
