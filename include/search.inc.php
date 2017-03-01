<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - GNU Licence Copyright (c)  (http://www.)
 *
 * La licence GNU GPL, garanti à l'utilisateur les droits suivants
 *
 * 1. La liberté d'exécuter le logiciel, pour n'importe quel usage,
 * 2. La liberté de l' étudier et de l'adapter à ses besoins,
 * 3. La liberté de redistribuer des copies,
 * 4. La liberté d'améliorer et de rendre publiques les modifications afin
 * que l'ensemble de la communauté en bénéficie.
 *
 * @copyright           (http://www.)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          TDM ; TEAM DEV MODULE
 *
 * ****************************************************************************
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

function tdmpicture_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    //load class
    $fileHandler = xoops_getModuleHandler('file', $moduleDirName);

    $ret = array();
    //cherche le fichier
    $criteria = new CriteriaCompo();
    $criteria->setSort('file_title');
    $criteria->setOrder('ASC');
    if ($userid != 0) {
        $criteria->add(new Criteria('file_uid', $userid));
    }
    $criteria->add(new Criteria('file_display', 1));
    $criteria->add(new Criteria('file_title', '%' . (isset($queryarray[0]) ? $queryarray[0] : '') . '%', 'LIKE'));
    $criteria->setStart($offset);
    $criteria->setLimit($limit);
    $file_arr = $fileHandler->getObjects($criteria);

    $i = 0;
    //while ($myrow = $xoopsDB->fetchArray($result)) {
    foreach (array_keys($file_arr) as $f) {
        $ret[$i]['image'] = 'assets/images/decos/search_file.png';
        $ret[$i]['link']  = 'viewfile.php?st=' . $file_arr[$f]->getVar('file_id') . '&ct=' . $file_arr[$f]->getVar('file_cat') . '&tris=file_title';
        $ret[$i]['title'] = $file_arr[$f]->getVar('file_title');
        $ret[$i]['time']  = $file_arr[$f]->getVar('file_indate');
        $ret[$i]['uid']   = $file_arr[$f]->getVar('file_uid');
        ++$i;
    }

    return $ret;
}
