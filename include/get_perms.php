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

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$global_perms = 0;
if (is_object($xoopsDB)) {
    if (!is_object($xoopsUser)) {
        $whr_groupid = 'gperm_groupid=' . XOOPS_GROUP_ANONYMOUS;
    } else {
        $groups      =& $xoopsUser->getGroups();
        $whr_groupid = 'gperm_groupid IN (';
        foreach ($groups as $groupid) {
            $whr_groupid .= "$groupid,";
        }
        $whr_groupid = substr($whr_groupid, 0, -1) . ')';
    }
    $sq2 = 'SELECT gperm_itemid FROM ' . $xoopsDB->prefix('group_permission') . ' LEFT JOIN ' . $xoopsDB->prefix('modules') . " m ON gperm_modid=m.mid WHERE m.dirname='" . $xoopsModule->getVar('dirname')
           . "' AND (gperm_name='sound_view') AND ($whr_groupid)";
    $rs  = $xoopsDB->query($sq2);
    while (list($itemid) = $xoopsDB->fetchRow($rs)) {
        $global_perms |= $itemid;
    }
}
