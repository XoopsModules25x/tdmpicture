<?php
/**
 * ****************************************************************************
 *  - TDMAssoc By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - GNU Licence Copyright (c)  (http://www.)
 *
 * La licence GNU GPL, garanti � l'utilisateur les droits suivants
 *
 * 1. La libert� d'ex�cuter le logiciel, pour n'importe quel usage,
 * 2. La libert� de l' �tudier et de l'adapter � ses besoins,
 * 3. La libert� de redistribuer des copies,
 * 4. La libert� d'am�liorer et de rendre publiques les modifications afin
 * que l'ensemble de la communaut� en b�n�ficie.
 *
 * @copyright       	(http://www.)
 * @license        	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		TDM ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

 if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

function tdmpicture_search($queryarray, $andor, $limit, $offset, $userid){
    global $xoopsDB;
    
//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
    
    $ret = array();
    //cherche le fichier
    $criteria = new CriteriaCompo();
    $criteria->setSort('file_title');
    $criteria->setOrder('ASC');
    if ( $userid != 0 ) {
    $criteria->add(new Criteria('file_uid', $userid));
    }
    $criteria->add(new Criteria('file_display', 1));
    $criteria->add(new Criteria('file_title', '%'.$queryarray[0].'%', 'LIKE'));
    $criteria->setStart($offset);
    $criteria->setLimit($limit);
    $file_arr = $file_handler->getObjects($criteria);

    $i = 0;
    //while($myrow = $xoopsDB->fetchArray($result)){
    foreach (array_keys($file_arr) as $f) {
        $ret[$i]['image'] = "images/decos/search_file.png";
        $ret[$i]['link'] = "viewfile.php?st=".$file_arr[$f]->getVar('file_id')."&ct=".$file_arr[$f]->getVar('file_cat')."&tris=file_title";
        $ret[$i]['title'] = $file_arr[$f]->getVar('file_title');
        $ret[$i]['time'] = $file_arr[$f]->getVar('file_indate');
        $ret[$i]['uid'] = $file_arr[$f]->getVar('file_uid');
        $i++;
    }
    
    return $ret;
}
