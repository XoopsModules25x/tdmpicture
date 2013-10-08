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
 
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}


function tdmpicture_header()
{
global $xoopsConfig, $xoopsModule, $xoTheme, $xoopsTpl;
$myts =& MyTextSanitizer::getInstance();

if(isset($xoTheme) && is_object($xoTheme)) {
$xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery-1.4.4.js");
$xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery-ui-1.7.1.custom.min.js");
$xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/AudioPlayer.js");
$xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery.colorbox.js");
$xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery.jBreadCrumb.1.1.js");

$xoTheme->addStylesheet(XOOPS_URL."/modules/".$xoopsModule->dirname()."/css/tdmpicture.css");



}else {
$mp_module_header = "<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/css/tdmpicture.css'/>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery-1.4.4.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery-ui-1.7.1.custom.min.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/AudioPlayer.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery.colorbox.js'></script>
";
$xoopsTpl->assign('xoops_module_header', $mp_module_header);
}

}

function tdmpicture_adminheader()
{
global $xoopsConfig, $xoopsModule, $xoTheme, $xoopsTpl;
$myts =& MyTextSanitizer::getInstance();


if(isset($xoTheme) && is_object($xoTheme)) {
  $xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery-1.4.4.js");
  $xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/AudioPlayer.js");
  $xoTheme->addScript(XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery.Jcrop.js");
	
  $xoTheme->addStylesheet(XOOPS_URL."/modules/".$xoopsModule->dirname()."/css/jquery.Jcrop.css");
  } else {
$mp_module_header = "<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/css/jquery.Jcrop.css'/>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery-1.4.4.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/AudioPlayer.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/js/jquery.Jcrop.js'></script>
";
echo $mp_module_header;
}


}

//** function copie
//function CopyDir($origine, $destination) {
//    $test = scandir($origine);

//    $file = 0;
//    $file_tot = 0;

 //   foreach($test as $val) {
 //       if($val!="." && $val!="..") {
 //           if(is_dir($origine."/".$val)) {
 //               CopyDir($origine."/".$val, $destination."/".$val);
 //               IsDir_or_CreateIt($destination."/".$val);
 //           } else {
 //               $file_tot++;
 //               if(copy($origine."/".$val, $destination."/".$val)) {
 //                   $file++;
 //               } else {
  //                  if(!file_exists($origine."/".$val)) {
 //                       echo $origine."/".$val;
 //                   };
 //               };
 //           };
 //       };
 //   }
 //   return true;
//}
//

/**
 * Creation des meta keywords
 *
 */
 
 function tdmpicture_keywords($content)
{
	$tmp = array();
	// Search for the "Minimum keyword length"
		$config_handler =& xoops_gethandler('config');
		$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
		$limit = $xoopsConfigSearch['keyword_min'];

	$myts =& MyTextSanitizer::getInstance();
	$content = str_replace ("<br />", " ", $content);
	$content= $myts->undoHtmlSpecialChars($content);
	$content= strip_tags($content);
	$content=strtolower($content);
	$search_pattern=array("&nbsp;","\t","\r\n","\r","\n",",",".","'",";",":",")","(",'"','?','!','{','}','[',']','<','>','/','+','-','_','\\','*');
	$replace_pattern=array(' ',' ',' ',' ',' ',' ',' ',' ','','','','','','','','','','','','','','','','','','','');
	$content = str_replace($search_pattern, $replace_pattern, $content);
	$keywords = explode(' ',$content);
	$keywords = array_unique($keywords);

	foreach($keywords as $keyword) {
		if(strlen($keyword)>=$limit && !is_numeric($keyword)) {
			$tmp[] = $keyword;
		}
	}

	if(count($tmp) > 0) {
		return implode(',',$tmp);
	} else {
			return '';
		}
}
 
 //admin navigation
 	function tdm_switchselect($text, $form_sort) {

	global $start, $order, $file_cat, $sort, $xoopsModule, $xoopsModuleConfig;

	$select_view = '<form name="form_switch" id="form_switch" action="'.$_SERVER['REQUEST_URI'].'" method="post"><span style="font-weight: bold;">'.$text.'</span>';
	//$sorts =  $sort ==  'asc' ? 'desc' : 'asc'; 
	if ($form_sort == $sort) {
	$sel1 =  $order ==  'asc' ? 'selasc.png' : 'asc.png'; 
	$sel2 =  $order ==  'desc' ? 'seldesc.png' : 'desc.png'; 
	}else {
	$sel1 =  'asc.png';
	$sel2 =  'desc.png';	
	}
	$select_view .= '  <a href="'.$_SERVER['PHP_SELF'].'?file_cat='.$file_cat.'&start='.$start.'&sort='.$form_sort.'&order=asc" /><img src="'.TDMPICTURE_IMAGES_URL.'/decos/'.$sel1.'" title="ASC" alt="ASC"></a>';
	$select_view .= '<a href="'.$_SERVER['PHP_SELF'].'?file_cat='.$file_cat.'&start='.$start.'&sort='.$form_sort.'&order=desc" /><img src="'.TDMPICTURE_IMAGES_URL.'/decos/'.$sel2.'" title="DESC" alt="DESC"></a>';
	$select_view .= '</form>';
	return $select_view;
	}
	
/**
 * Creation de la hauteur largeur image
 *
 */
 function redimage($img_src,$dst_w,$dst_h) {
   // Lit les dimensions de l'image
	$size = GetImageSize($img_src); 
 //$size[0] = width;
 //size[1] = height;
 
   $src_w = $size[0]; $src_h = $size[1];
   // Teste les dimensions tenant dans la zone
   if ($src_h > $dst_h ) {
  $test_h = round(($dst_w / $src_w) * $src_h);
   $test_w = round(($dst_h / $src_h) * $src_w);
   } elseif ($src_w > $dst_w) {
   $test_h = round(($dst_w / $src_w) * $src_h);
   $test_w = round(($dst_h / $src_h) * $src_w);
   } else {
   $test_h = $src_h;
   $test_w = $src_w;
   }
   // Si Height final non précisé (0)
   if(!$dst_h) {
   $dst_h = $test_h;
   }
   // Sinon si Width final non précisé (0)
   elseif(!$dst_w) {
   $dst_w = $test_w;
   }
   // Sinon teste quel redimensionnement tient dans la zone
   elseif($test_h>$dst_h) { $dst_w = $test_w;
   }
   else { $dst_h = $test_h; $dst_w = $test_w;
}
$dst['min_w'] = $dst_w;
 $dst['min_h'] = $dst_h;
   // Affiche les dimensions optimales
  return $dst;
}

/**
 * xd_getdefaultmatchtypeid
 *
 * Returns default matchtype id
 *
 * @package pronoboulistenaute
 * @author wild0ne (mailto:wild0ne@partypilger.de)
 * @copyright (c) wild0ne
 * @param $eventid    get default matchtype for related event
 */

 function tdmpicture_PrettySize($size)
{
    $mb = 1024 * 1024;
    if ($size > $mb)
    {
        $mysize = sprintf ("%01.2f", $size / $mb) . _MD_TDMPICTURE_MEGABYTES;
    }elseif ($size >= 1024)
    {
        $mysize = sprintf ("%01.2f", $size / 1024) . _MD_TDMPICTURE_KILOBYTES;
    }
    else
    {
        $mysize = sprintf('oc', $size);
    }
    return $mysize;
}

//trouve si l'user a un album
 function tdmpicture_useralb($uid) 
 {

//calcul les albums
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('file_uid', $uid));
	$numalb = $file_handler->getCount($criteria);
	
	if ($numalb != 0) {
	return true;
	} else {
	return false;
	}
	
	}

function tdmpicture_catselect($mytree, $cat) {
 
 include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
 
 global $xoopsTpl, $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
 //perm
$gperm_handler =& xoops_gethandler('groupperm');
	
	//$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');
	//$criteria = new CriteriaCompo();
	//$criteria->add(new Criteria('cat_display', 1));
	//$criteria->add(new Criteria('cat_index', 1));
	//$criteria->setSort('cat_weight');
	//$criteria->setOrder('DESC');
	//$arr = $cat_handler->getall($criteria);
	//$mytree = new XoopsObjectTree($arr, 'id', 'pid');
	//$mytree = new TDMObjectTree($arr, 'cat_id', 'cat_pid'); 
	
	$form = new XoopsThemeForm('', 'catform', $_SERVER['REQUEST_URI'], 'post', true);
	//$form->setExtra('enctype="multipart/form-data"');
	$tagchannel_select = new XoopsFormLabel('', $mytree->makeSelBox('cat_pid', 'cat_title','-', $cat, '-- '._MD_TDMPICTURE_CAT, 0, "OnChange='window.document.location=this.options[this.selectedIndex].value;'", 'tdmpicture_catview'), 'pid');
	$form->addElement($tagchannel_select);
	
	//$form->display();
	$form->assign($xoopsTpl);
	
	}
	
	//fonction deplacer
	//function tdmpicture_trisselect($cat, $tris) {
 
	//global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
	//$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');
	//$option = array('file_title' => _MD_TDMPICTURE_TRITITLE , 'file_indate' => _MD_TDMPICTURE_TRIDATE, 'file_counts' => _MD_TDMPICTURE_TRICOUNTS, 'file_hits' => _MD_TDMPICTURE_TRIHITS, 'file_comments' => _MD_TDMPICTURE_TRICOMMENT); 
	//$select_tris = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';
 	//trouve le nom de la cat
	//$cat = $cat_handler->get($cat);
	//foreach ($option as $key => $value) {
	//$select =  ($tris ==  $key) ? 'selected="selected"' : false;
	//$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$cat."&tris=".$key."&limit=".$limit;
	//$select_tris .= '<option '.$select.' value="'.$cat_link.'">'.$value.'</option>';

//}
//$select_tris .= '</select>';

//return $select_tris;
//}

	function tdmpicture_viewselect($cat, $limit) {

	global $start, $tris, $xoopsModule, $xoopsModuleConfig;
	$option = array('10' => 10, '20' => 20, '30' => 30, '40' => 40, '50' => 50, '100' => 100); 
	$select_view = '<select name="limit" onchange="window.document.location=this.options[this.selectedIndex].value;">';
	//trouve le nom de la cat
	foreach (array_keys($option) as $i) {
	$select =  ($limit ==  $option[$i]) ? 'selected="selected"' : false;
	//$view_link = $start.$option[$i].$tris;
	$link = TDMPICTURE_URL."/viewcat.php?ct=".$cat."&tris=".$tris."&limit=".$option[$i];
	$select_view .= '<option '.$select.' value="'.$link.'">'.$option[$i].'</option>';
	}
	$select_view .= '</select>';
	return $select_view;
	}

function print_tab($array, $before = "", $after = "") {

        //Affichage du texte HTML avant le tableau

        echo $before."\n";

        //Encadrement de l'affichage du tableau par des balises <PRE>

        echo "<pre>\n";

        //Affichage récursif du tableau

        print_r($array);

        echo "</pre>\n";

        //Affichage du texte HTML après le tableau

        echo $after."\n";

}
/**
 * admin menu
 */
 function TDMPicture_adminmenu ($currentoption = 0, $breadcrumb = '') {      
		
	/* Nice buttons styles */
	echo "
    	<style type='text/css'>
    	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/TDMAssoc/images/deco/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		#buttonbar li { display:inline; margin:0; padding:0; }
		#buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/TDMAssoc/images/deco/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
		#buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/TDMAssoc/images/deco/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
		/* Commented Backslash Hack hides rule from IE5-Mac \*/
		#buttonbar a span {float:none;}
		/* End IE5-Mac hack */
		#buttonbar a:hover span { color:#333; }
		#buttonbar #current a { background-position:0 -150px; border-width:0; }
		#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		#buttonbar a:hover { background-position:0% -150px; }
		#buttonbar a:hover span { background-position:100% -150px; }
		</style>
    ";
	
	global $xoopsModule, $xoopsConfig;
	$myts = &MyTextSanitizer::getInstance();
	
	$tblColors = Array();
	$tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
	$tblColors[$currentoption] = 'current';
	if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
		include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
	} else {
		include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/english/modinfo.php';
	}
	
	echo "<div id='buttontop'>";
	echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
	//echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _AM_SF_OPTS . "</a> | <a href=\"import.php\">" . _AM_SF_IMPORT . "</a> | <a href=\"../index.php\">" . _AM_SF_GOMOD . "</a> | <a href=\"../help/index.html\" target=\"_blank\">" . _AM_SF_HELP . "</a> | <a href=\"about.php\">" . _AM_SF_ABOUT . "</a></td>";
	echo "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>
	<a href='" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/index.php'>".$xoopsModule->getVar('dirname')."</a>
	</td>";
	echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($xoopsModule->name()) . "  </b> ".$breadcrumb." </td>";
	echo "</tr></table>";
	echo "</div>";
	
	echo "<div id='buttonbar'>";
	echo "<ul>";
    echo "<li id='" . $tblColors[0] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/index.php\"><span>"._MI_TDMSOUND_ADMENUINDEX."</span></a></li>";
	echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/genre.php\"><span>"._MI_TDMSOUND_ADMENUGENRE."</span></a></li>";
	echo "<li id='" . $tblColors[2] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/artiste.php\"><span>"._MI_TDMSOUND_ADMENUARTISTE."</span></a></li>";
	echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/album.php\"><span>"._MI_TDMSOUND_ADMENUALBUM."</span></a></li>";
	echo "<li id='" . $tblColors[4] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/files.php\"><span>"._MI_TDMSOUND_ADMENUFILE."</span></a></li>";
	echo "<li id='" . $tblColors[5] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/permissions.php\"><span>" ._MI_TDMSOUND_ADMENUPERMISSIONS. "</span></a></li>";
	echo "<li id='" . $tblColors[6] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/admin/about.php\"><span>"._MI_TDMSOUND_ADMENUABOUT."</span></a></li>";
	echo "<li id='" . $tblColors[7] . "'><a href='../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule ->getVar('mid')."'><span>" ._MI_TDMSOUND_ADMENUPREF. "</span></a></li>";
	echo "</ul></div>&nbsp;";
}

?>
