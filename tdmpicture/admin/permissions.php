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
 
include 'admin_header.php';
xoops_cp_header();

include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
include_once ("../class/calendargrouppermform.php");
//include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/mygrouppermform.php';


if( ! empty( $_POST['submit'] ) ) {
    redirect_header( XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/permissions.php" , 1 , _AM_XD_GPERMUPDATED);
}
    
    $category_admin = new ModuleAdmin();
    echo $category_admin->addNavigation('permissions.php');

$module_id = $xoopsModule->getVar('mid');

$perm_name = "tdmpicture_view";
$perm_desc = _AM_TDMPICTURE_MANAGE_PERM;

    $global_perms_array = array(
        '4' => _AM_TDMPICTURE_PERM_4 ,
        '8' => _AM_TDMPICTURE_PERM_8 ,
        '16' => _AM_TDMPICTURE_PERM_16 ,
        '128' => _AM_TDMPICTURE_PERM_128,
        '256' => _AM_TDMPICTURE_PERM_256,
        '512' => _AM_TDMPICTURE_PERM_512,
        '1024' => _AM_TDMPICTURE_PERM_1024,
        '1048' => _AM_TDMPICTURE_PERM_1048,
        
        '2' => _AM_TDMPICTURE_PERM_2 ,
        '32' => _AM_TDMPICTURE_PERM_32 ,
        '64' => _AM_TDMPICTURE_PERM_64
         );

    

$permform = new CalendarGroupPermForm('', $module_id, $perm_name, '', '');
    

foreach( $global_perms_array as $perm_id => $perm_name ) {
if($perm_name == _AM_TDMPICTURE_PERM_2 || $perm_name == _AM_TDMPICTURE_PERM_32 || $perm_name == _AM_TDMPICTURE_PERM_64) {
        $permform->addItem( $perm_id , $perm_name ) ;
} else {
        $permform->addItem( $perm_id , $perm_name, 0, true ) ;
        }
    }
    

     echo '<style type="text/css">
    <!--
.tips{
    color:#000000;
    border:1px solid #00cc00;
    padding:8px 8px 8px 35px;
    background:#f8fff8 url("../images/decos/idea.png") no-repeat 5px 4px;
}
    //-->
    </style>';

    echo '<div class="tips">' . _AM_TDMPICTURE_MANAGE_PERM . '</div><br />';
echo $permform->render();

include 'admin_footer.php';
