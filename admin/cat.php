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

use Xmf\Request;

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

//load class
/** @var TdmpictureFileHandler $fileHandler */
$fileHandler = xoops_getModuleHandler('file', $moduleDirName);
/** @var TdmpictureCategoryHandler $catHandler */
$catHandler = xoops_getModuleHandler('category', $moduleDirName);

$myts = MyTextSanitizer::getInstance();
$op   = Request::getVar('op', 'list'); //isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

//compte les cats
$numcat = $catHandler->getCount();
//compte les cats en attente
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('cat_display', 0));
$cat_waiting = $catHandler->getCount($criteria);
//xoops_cp_header();
//code list
switch ($op) {

    //sauv
    case 'save_cat':

        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('cat.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['cat_id'])) {
            $obj = $catHandler->get(Request::getVar('cat_id'));
        } else {
            $obj = $catHandler->create();
        }

        //upload
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        //        $uploaddir = constant($modir . '_UPLOAD_PATH') . '/cat/';
        $uploaddir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/upload/cat/';
        $mimetype  = explode('|', $moduleHelper->getConfig('tdmpicture_mimetype'));
        $uploader  = new XoopsMediaUploader($uploaddir, $mimetype, $moduleHelper->getConfig('tdmpicture_mimemax'));

        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('picture_');
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $obj->setVar('cat_img', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('cat_img', Request::getVar('img'));
        }
        //
        $obj->setVar('cat_pid', Request::getVar('cat_pid'));
        $obj->setVar('cat_title', Request::getVar('cat_title'));
        $obj->setVar('cat_text', Request::getVar('cat_text'));
        $obj->setVar('cat_weight', Request::getVar('cat_weight'));
        $obj->setVar('cat_display', Request::getVar('cat_display'));
        $obj->setVar('cat_index', Request::getVar('cat_index'));
        $obj->setVar('cat_uid', $xoopsUser->getVar('uid'));

        if ($catHandler->insert($obj)) {

            //permission
            $perm_id      = Request::getVar('op', $obj->getVar('cat_id'));// isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : $obj->getVar('cat_id');
            $gpermHandler = xoops_getHandler('groupperm');
            $criteria     = new CriteriaCompo();
            $criteria->add(new Criteria('gperm_itemid', $perm_id, '='));
            $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
            $criteria->add(new Criteria('gperm_name', 'tdmpicture_catview', '='));
            $gpermHandler->deleteAll($criteria);

            if (isset($_POST['groups_view'])) {
                foreach ($_POST['groups_view'] as $onegroup_id) {
                    $gpermHandler->addRight('tdmpicture_catview', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                }
            }

            redirect_header('cat.php', 2, _AM_TDMPICTURE_BASE);
        }
        //include_once(__DIR__ . '/../include/forms.php');
        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit':
        //admin menu
        //        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_TDMPICTURE_ADD_CAT, 'cat.php?op=new_cat', 'add');
        $adminObject->addItemButton(_AM_TDMPICTURE_LIST_CAT, 'cat.php?op=list', 'list');
        $adminObject->displayButton('left');

        $obj  = $catHandler->get(Request::getVar('cat_id'));
        $form = $obj->getForm();
        $form->display();
        break;

        break;

    case 'delete':
        $obj = $catHandler->get(Request::getVar('cat_id'));

        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('cat.php', 2, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            //supprimer les enfant de la base et leur dossier
            $arr       = $catHandler->getall();
            $mytree    = new XoopsObjectTree($arr, 'cat_id', 'cat_pid');
            $treechild = $mytree->getAllChild($obj->getVar('cat_id'));
            foreach ($treechild as $child) {
                $ret = $catHandler->get($child->getVar('cat_id'));
                $catHandler->delete($ret);
            }

            //supprime le cat
            if ($catHandler->delete($obj)) {
                redirect_header('cat.php', 2, _AM_TDMPICTURE_BASE);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            //            $adminObject = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));

            xoops_confirm(array(
                              'ok'     => 1,
                              'cat_id' => $_REQUEST['cat_id'],
                              'op'     => 'delete'
                          ), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMPICTURE_FORMSUREDELCAT, $obj->getVar('cat_title')));
        }
        break;

    case 'update':
        $obj = $catHandler->get(Request::getVar('cat_id'));
        $obj->getVar('cat_display') == 1 ? $obj->setVar('cat_display', 0) : $obj->setVar('cat_display', 1);
        if ($catHandler->insert($obj)) {
            redirect_header('cat.php', 2, _AM_TDMPICTURE_BASE);
        }
        break;

    case 'list':
    default:

        //menu admin
        //        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        if ($cat_waiting != 0 && !isset($_REQUEST['cat_display'])) {
            $waitString = _AM_TDMPICTURE_BUTTON_CAT_WAITING . $cat_waiting;
            echo $waitString;
            $adminObject->addItemButton($waitString, 'cat.php?op=list&cat_display=0', 'update');
        }
        if (isset($_REQUEST['cat_display'])) {
            $adminObject->addItemButton(_AM_TDMPICTURE_LIST_CAT, 'cat.php?op=list', 'list');
        }
        $adminObject->addItemButton(_AM_TDMPICTURE_ADD_CAT, 'cat.php?op=new_cat', 'add');
        $adminObject->displayButton('left');

        //Parameters
        $criteria = new CriteriaCompo();
        //$limit = 10;
        //if (isset($_REQUEST['start'])) {
        //$criteria->setStart($_REQUEST['start']);
        //$start = $_REQUEST['start'];
        //} else {
        //$criteria->setStart(0);
        //$start = 0;
        //}

        if (isset($_REQUEST['cat_display'])) {
            $criteria->add(new Criteria('cat_display', Request::getVar('cat_display')));
        }

        //$criteria->setLimit($limit);
        $criteria->setOrder('ASC');
        $assoc_cat = $catHandler->getAll($criteria);
        $numrows   = $catHandler->getCount();

        //nav
        //if ($numrows > $limit) {
        //$pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&cat_display='.$_REQUEST['cat_display']);
        //$pagenav = $pagenav->renderNav(2);
        //} else {
        //$pagenav = '';
        //}
        //Affichage du tableau des cat�gories
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer" >';
            echo '<tr>';
            //echo '<th align="center">'._AM_TDMPICTURE_IMG.'</th>';
            echo '<th align="center">' . _AM_TDMPICTURE_TITLE . '</th>';
            echo '<th align="center">' . _AM_TDMPICTURE_AUTEUR . '</th>';
            echo '<th align="center">' . _AM_TDMPICTURE_WEIGHT . '</th>';
            echo '<th align="center">' . _AM_TDMPICTURE_DISPLAY . '</th>';
            echo '<th align="center">' . _AM_TDMPICTURE_PRINCIPAL . '</th>';
            echo '<th align="center">' . _AM_TDMPICTURE_ACTION . '</th>';
            echo '</tr>';
            $class              = 'odd';
            $mytree             = new TDMObjectTree($assoc_cat, 'cat_id', 'cat_pid');
            $category_ArrayTree = $mytree->makeArrayTree('', '<img src="' . TDMPICTURE_IMAGES_URL . '/decos/arrow.gif">');
            foreach (array_keys($category_ArrayTree) as $i) {
                $class     = ($class === 'even') ? 'odd' : 'even';
                $cat_id    = $assoc_cat[$i]->getVar('cat_id');
                $cat_uid   = XoopsUser::getUnameFromId($assoc_cat[$i]->getVar('cat_uid'));
                $cat_pid   = $assoc_cat[$i]->getVar('cat_pid');
                $cat_title = $myts->displayTarea($assoc_cat[$i]->getVar('cat_title'));

                $display   = $assoc_cat[$i]->getVar('cat_display') == 1 ? "<a href='cat.php?op=update&cat_id=" . $cat_id . "'><img src='" . $pathIcon16
                                                                          . "/1.png' border='0'></a>" : "<a href='cat.php?op=update&cat_id=" . $cat_id . "'><img alt='" . _AM_TDMPICTURE_UPDATE
                                                                                                        . "' title='" . _AM_TDMPICTURE_UPDATE . "' src='" . $pathIcon16 . "/0.png' border='0'></a>";
                $principal = $assoc_cat[$i]->getVar('cat_index') == 1 ? "<img src='" . $pathIcon16 . "/1.png' border='0'>" : "<img src='" . $pathIcon16 . "/0.png' border='0'>";
                //on test l'existance de l'image
                $imgpath = TDMPICTURE_CAT_PATH . $assoc_cat[$i]->getVar('cat_img');
                if (file_exists($imgpath)) {
                    $cat_img = TDMPICTURE_CAT_URL . $assoc_cat[$i]->getVar('cat_img');
                } else {
                    $cat_img = false;
                }

                echo '<tr class="' . $class . '">';
                //  echo '<td align="center" width="10%" style="vertical-align:middle;"><img src="'.$cat_img.'" alt="" title="" height="40"></td>';
                echo '<td align="left" width="55%" style="vertical-align:middle;">' . $category_ArrayTree[$i] . $cat_title . '</td>';
                echo '<td align="center" width="10%" style="vertical-align:middle;">' . $cat_uid . '</td>';
                echo '<td align="center" width="5%" style="vertical-align:middle;">' . $assoc_cat[$i]->getVar('cat_weight') . '</td>';
                echo '<td align="center" width="5%" style="vertical-align:middle;">' . $display . '</td>';
                echo '<td align="center" width="5%" style="vertical-align:middle;">' . $principal . '</td>';
                echo '<td align="center" width="10%" style="vertical-align:middle;">';
                echo '<a href="cat.php?op=edit&cat_id=' . $cat_id . '"><img src=' . $pathIcon16 . '/edit.png border="0" alt="' . _AM_TDMPICTURE_MODIFY . '" title="' . _AM_TDMPICTURE_MODIFY . '"></a>';
                echo '<a href="cat.php?op=delete&cat_id=' . $cat_id . '"><img src=' . $pathIcon16 . '/delete.png border="0" alt="' . _AM_TDMPICTURE_DELETE . '" title="' . _AM_TDMPICTURE_DELETE
                     . '"></a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table><br><br>';
            //echo '<div align=right>'.$pagenav.'</div>';
        } else {
            echo '<div class="errorMsg" style="text-align: center;">' . sprintf(_AM_TDMPICTURE_THEREARE_CAT, 0) . '</div>';
        }

        break;

    // vue cr�ation
    case 'new_cat':
        //menu admin
        //        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_TDMPICTURE_LIST_CAT, 'cat.php?op=list', 'list');
        $adminObject->displayButton('left');

        // Affichage du formulaire de cr?ation de cat?gories
        $obj  = $catHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

}
require_once __DIR__ . '/admin_footer.php';
