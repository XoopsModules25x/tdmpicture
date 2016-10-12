<?php

use Xmf\Module\Admin;

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

include_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$file_protection = 'XOOPS France: Tatane, Cesagonchu, Grosdunord, Phira<br>';
$adminObject->addInfoBox(_AM_TDMPICTURE_TEST);
$adminObject->addInfoBoxLine($file_protection, '', '', 'information');

echo $adminObject->displayNavigation(basename(__FILE__));
\Xmf\Module\Admin::setPaypal('6KJ7RW5DR3VTJ');
echo $adminObject->renderAbout(false);

include_once __DIR__ . '/admin_footer.php';
