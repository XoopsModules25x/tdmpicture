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

use Xmf\Request;

include_once __DIR__ . '/header.php';
$myts = MyTextSanitizer::getInstance();

include_once XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/include/common.php';

$xoopsTpl->assign('dirname', $moduleDirName);

$op   = Request::getVar('op', 'list'); //isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';

//load class
$fileHandler = xoops_getModuleHandler('file', $moduleDirName);
$catHandler  = xoops_getModuleHandler('category', $moduleDirName);

switch ($op) {

    case 'edit':

        //perm
        if (!$gpermHandler->checkRight('tdmpicture_view', 128, $groups, $xoopsModule->getVar('mid'))) {
            redirect_header(XOOPS_URL, 2, _MD_TDMPICTURE_NOPERM);
        }

        $obj = $fileHandler->get($_REQUEST['file_id']);
        if (!empty($xoopsUser) && $xoopsUser->getVar('uid') == $obj->getVar('file_uid') || $xoopsUser->isAdmin()) {
            $form = $obj->getForm();
            $form->display();
        } else {
            redirect_header(TDMPICTURE_URL, 2, _MD_TDMPICTURE_NOPERM);
        }
        break;

    case 'edit_file':

        //perm
        if (!$gpermHandler->checkRight('tdmpicture_view', 128, $groups, $xoopsModule->getVar('mid'))) {
            redirect_header(XOOPS_URL, 2, _MD_TDMPICTURE_NOPERM);
        }

        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['file_id'])) {
            $obj = $fileHandler->get($_REQUEST['file_id']);
        } else {
            $obj = $fileHandler->create();
        }

        if (!empty($xoopsUser) && $xoopsUser->getVar('uid') == $obj->getVar('file_uid') || $xoopsUser->isAdmin()) {
            //fichier commun
            $obj->setVar('file_title', $_REQUEST['file_title']);
            $obj->setVar('file_display', $_REQUEST['file_display']);
            $obj->setVar('file_cat', $_REQUEST['file_cat']);
            $obj->setVar('file_indate', time());
            $obj->setVar('file_text', $_REQUEST['file_text']);
            $obj->setVar('file_size', $_REQUEST['file_size']);
            $obj->setVar('file_res_x', $_REQUEST['file_res_x']);
            $obj->setVar('file_res_y', $_REQUEST['file_res_y']);

            $erreur = $fileHandler->insert($obj);
        }

        if ($erreur) {
            redirect_header('index.php', 2, _MD_TDMPICTURE_BASE);
        } else {
            redirect_header('index.php', 2, _MD_TDMPICTURE_BASEERROR);
        }
        break;

    case 'delete':

        //perm
        if (!$gpermHandler->checkRight('tdmpicture_view', 512, $groups, $xoopsModule->getVar('mid'))) {
            redirect_header(XOOPS_URL, 2, _MD_TDMPICTURE_NOPERM);
        }

        $obj = $fileHandler->get($_REQUEST['file_id']);

        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            if (!empty($xoopsUser) && $xoopsUser->getVar('uid') == $obj->getVar('file_uid') || $xoopsUser->isAdmin()) {
                if ($fileHandler->delete($_REQUEST['file_id'])) {
                    redirect_header('javascript:history.go(-2)', 2, _AM_TDMPICTURE_BASE);
                } else {
                    redirect_header(TDMPICTURE_URL, 2, _AM_TDMPICTURE_BASEERROR);
                }
            }
        } else {
            xoops_confirm(array(
                              'ok'      => 1,
                              'file_id' => $_REQUEST['file_id'],
                              'op'      => 'delete'
                          ), $_SERVER['REQUEST_URI'], _MD_TDMPICTURE_FORMSUREDEL);
        }
        break;

    case 'list':
    default:

        redirect_header(TDMPICTURE_URL, 2, _MD_TDMPICTURE_NOPERM);

        break;

}
TdmpictureUtility::getHeader();
include_once XOOPS_ROOT_PATH . '/footer.php';
