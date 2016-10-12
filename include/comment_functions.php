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
 * @param $file_id
 * @param $total_num
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

// comment callback functions

function picture_comments_update($file_id, $total_num)
{
    global $xoopsDB;

    $fileHandler = xoops_getModuleHandler('tdmpicture_file', $moduleDirName);
    $view         = $fileHandler->get($file_id);
    $hits         = $view->getVar('file_comments');
    ++$hits;
    $obj = $fileHandler->get($file_id);
    $obj->setVar('file_comments', $hits);
    $ret = $fileHandler->insert($obj);

    return $ret;
}

/**
 * @param $comment
 */
function picture_comments_approve(&$comment)
{
    // notification mail here
}
