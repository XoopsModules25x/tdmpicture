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
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;


function xoops_module_update_TDMPicture(&$xoopsModule, $oldVersion = null) {

  global $xoopsConfig, $xoopsDB, $xoopsUser;

  if($oldVersion < 106) {
  $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/TDMPicture/sql/mysql1.06.sql");
  }
  
  if($oldVersion < 108) {
  $xoopsDB->queryFromFile(XOOPS_ROOT_PATH."/modules/TDMPicture/sql/mysql1.07.sql");
 
   }

  return true;
}

function FieldExists($fieldname,$table) {
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
	return($xoopsDB->getRowsNum($result) > 0);
}

function TableExists($tablename) {
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
	return($xoopsDB->getRowsNum($result) > 0);
}
?>
