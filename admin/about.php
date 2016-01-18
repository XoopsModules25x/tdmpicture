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
 * @author		TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

include_once dirname(__FILE__) . '/admin_header.php';

xoops_cp_header();

$aboutAdmin = new ModuleAdmin();
$file_protection = "Tatane, Xoopsfr<br /><br />
Cesag, Xoopsfr<br /><br />Grosdunord, Xoopsfr<br /><br />Phira, Xoopsfr<br />";
$aboutAdmin->addInfoBox(_AM_TDMPICTURE_TEST);
$aboutAdmin->addInfoBoxLine(_AM_TDMPICTURE_TEST, $file_protection, '', '', 'information');
echo $aboutAdmin->addNavigation('about.php');
echo $aboutAdmin->renderAbout('6KJ7RW5DR3VTJ', FALSE);

include 'admin_footer.php';
