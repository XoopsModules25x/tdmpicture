<?php
/* ****************************************************************************
 * TDMMoney - MODULE FOR XOOPS
 * Copyright (c) G. Mage (www.tdmxoops.net)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       G. Mage (www.tdmxoops.net)
 * @license         ???
 * @package         TDMMoney
 * @author 			G. Mage (www.tdmxoops.net)
 *
 * ***************************************************************************/
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
 
 
 include_once XOOPS_ROOT_PATH . '/class/tree.php';

class TDMObjectTree extends XoopsObjectTree {
    
    //function __constrcut(){
   //}    
    function _makeArrayTreeOptions( $fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '' ) {
		if ( $key > 0 ) {
		
			$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
			$ret[$value] = $prefix_curr . $this->_tree[$key]['obj']->getVar( $fieldName );
			$prefix_curr .= $prefix_orig;
            
		}

		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
			
				$this->_makeArrayTreeOptions( $fieldName, $childkey, $ret, $prefix_orig, $prefix_curr );
			}
		}
	}  
	
    function makeArrayTree( $fieldName, $prefix = '-', $key = 0) {
		$ret = array();
		$this->_makeArrayTreeOptions( $fieldName, $key, $ret, $prefix );
		return $ret;
	}
	
	function _makeCatBoxOptions( $item_handler, $fieldName, $selected, $key, &$ret, &$ret2, $prefix_orig, $prefix_curr = '', $chcount) {
Global $xoopsModule, $xoopsModuleConfig, $cat_display, $cat_cel, $groups, $start, $limit, $tris ;
	

	$gperm_handler =& xoops_gethandler('groupperm');
	$parent = "";
	$scat_display = isset($GLOBALS['scat_display']) ? $GLOBALS['scat_display'] : true;
	//$GLOBALS['navbar'] .= "";
		
	
	if ( $key > 0 && $gperm_handler->checkRight('tdmpicture_catview', $this->_tree[$key]['obj']->getVar('cat_id'), $groups, $xoopsModule->getVar('mid'))) {
		$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('file_cat', $this->_tree[$key]['obj']->getVar('cat_id')));
		$criteria->add(new Criteria('file_display', 1));
		$count = $item_handler->getCount($criteria);
		//$cat_link = tdmspot_seo_genUrl( $xoopsModuleConfig['tdmspot_seo_cat'], $this->_tree[$key]['obj']->getVar('id'), $this->_tree[$key]['obj']->getVar('title'), $start, $limit, $tris );
		$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$this->_tree[$key]['obj']->getVar('cat_id')."&tris=".$tris."&limit=".$limit;
		//recherche image
		$imgpath = TDMPICTURE_CAT_PATH  . $this->_tree[$key]['obj']->getVar('cat_img');
		if (file_exists($imgpath) && $this->_tree[$key]['obj']->getVar('cat_img') != "blank.gif") {
		$picture = '<a href ="'.$cat_link.'" title="'. $this->_tree[$key]['obj']->getVar('cat_title').'"><img src="' . TDMPICTURE_CAT_URL .  $this->_tree[$key]['obj']->getVar('cat_img').'" class="img" width="'.$xoopsModuleConfig['tdmpicture_cat_width'].'"  height="'.$xoopsModuleConfig['tdmpicture_cat_height'].'"></a>';
		} else {
		$picture = '<a href ="'.$cat_link.'" title="'. $this->_tree[$key]['obj']->getVar('cat_title').'"><img src="' . TDMPICTURE_CAT_URL . 'no_picture.png" class="img" width="'.$xoopsModuleConfig['tdmpicture_cat_width'].'"  height="'.$xoopsModuleConfig['tdmpicture_cat_height'].'"></a>';
		}
			

		if (isset($selected) && $value == $selected ) {
		
		$url_link = TDMPICTURE_URL."/index.php";
		$GLOBALS['navbar'] = '<a href ="'.$url_link.'" title="'. $xoopsModule->name().'">' . $xoopsModule->name(). '</a> > ';
		//trie
		$navtrie = $this->makeSelTris(intval($value), $tris);
		
		if ( isset( $this->_tree[$this->_tree[$key]['parent']]['obj'] ) ) {
		
		$parent_link = TDMPICTURE_URL."/viewcat.php?ct=".$this->_tree[$this->_tree[$key]['parent']]['obj']->getVar('cat_id')."&tris=".$tris."&limit=".$limit;
		$GLOBALS['navbar'] .= '<a href ="'.$parent_link.'" title="'. $this->_tree[$this->_tree[$key]['parent']]['obj']->getVar('cat_title').'">' . $this->_tree[$this->_tree[$key]['parent']]['obj']->getVar('cat_title'). '</a> > ';
		
		}	

		
		//$GLOBALS['cat_count'] = $count;
		$GLOBALS['cat_title'] = $this->_tree[$key]['obj']->getVar('cat_title');

		$select = $this->makeSelBox('cat_pid', 'cat_title','-', $selected, '', $this->_tree[$key]['obj']->getVar('cat_id'), "OnChange='window.document.location=this.options[this.selectedIndex].value;'", 'tdmpicture_catview');
		$ret2 =  '<li class="'.$GLOBALS['class'].'"><div><div id="img">'.$picture. '<br/><span id="tree_num"> ('.$count.')</span></div><div id="tree_detail"><h2><a href ="'.$cat_link.'" title="'. $this->_tree[$key]['obj']->getVar('cat_title').'">'. $this->_tree[$key]['obj']->getVar( $fieldName ). '</h2></a><span id="tree_text">'. $this->_tree[$key]['obj']->getVar('cat_text'). '</span></div><br style="clear: both;" /><div id="tree_form">'.$GLOBALS['navbar'].$select.' | '._MD_TDMPICTURE_TRIBY.' > '.$navtrie.'</div></div></div></li>';

		
		}		
		
		//if ((!$prefix_curr) && ($this->_tree[$key]['obj']->getVar('cat_pid') == $selected)) {
		if (($scat_display) && $this->_tree[$key]['obj']->getVar('cat_pid') == $selected) {	
		
		if ((!$prefix_curr) || ($xoopsModuleConfig['tdmpicture_cat_display'])) {
		
		if (!empty( $this->_tree[$key]['child'] ) && ($xoopsModuleConfig['tdmpicture_cat_select'])) {
		$select = $this->makeSelBox('cat_pid', 'cat_title','-', 0, '', $this->_tree[$key]['obj']->getVar('cat_id'), "OnChange='window.document.location=this.options[this.selectedIndex].value;'", 'tdmpicture_catview');
		} else {
		$select = false;
		}


		$cat_title = (strlen($this->_tree[$key]['obj']->getVar('cat_title')) > 30 ? substr($this->_tree[$key]['obj']->getVar('cat_title'),0,(30)) : $this->_tree[$key]['obj']->getVar('cat_title'));
		$cat_text = (strlen($this->_tree[$key]['obj']->getVar('cat_text')) > 120 ? substr($this->_tree[$key]['obj']->getVar('cat_text'),0,(120))."..." : $this->_tree[$key]['obj']->getVar('cat_text'));	
		$ret .=  '<li style="width:46%;" class="'.$GLOBALS['class'].'"><div><div id="img">'.$picture. '<br/><span id="tree_num"> ('.$count.')</span></div><div id="tree_detail"><h2><a href ="'.$cat_link.'" title="'. $this->_tree[$key]['obj']->getVar('cat_title').'">'. $cat_title. '</h2></a><span id="tree_text">'. $cat_text. '</span></div><br style="clear: both;" /><div id="tree_form">'.$select.'</div></div></div></li>';
		}

			}
			
		
			//}
			$prefix_curr .= $prefix_orig;

			//}
			
		}
		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
			$GLOBALS['class'] = ($GLOBALS['class'] == "even") ? "odd" : "even";
			$this->_makeCatBoxOptions( $item_handler, $fieldName, $selected, $childkey, $ret, $ret2, $prefix_orig, $prefix_curr , $chcount);
				
					}
		}

	}
	
		function _makeSelBoxOptions( $fieldName, $selected, $key, &$ret, $perm, $prefix_orig, $prefix_curr = '' ) {
		
		 global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
		 
		 //perm
		 $gperm_handler =& xoops_gethandler('groupperm');
		 	if (is_object($xoopsUser)) {
			$groups = $xoopsUser->getGroups();
			$uid = $xoopsUser->getVar('uid');
			} else {
			$groups = XOOPS_GROUP_ANONYMOUS;
			$uid = 0;
			}
			//
		if ( $key > 0 ) {
			$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
			//$url = "viewcat.php?ct=".$this->_tree[$key]['obj']->getVar($this->_myId)."&tris=".$tris."&limit=".$limit;
			//$value = tdmspot_seo_genUrl( $xoopsModuleConfig['tdmspot_seo_cat'], $this->_tree[$key]['obj']->getVar('id'), $this->_tree[$key]['obj']->getVar('title'), $start, $limit, $tris );

			if ( !empty( $perm ) && $gperm_handler->checkRight($perm, $value, $groups, $xoopsModule->getVar('mid'))) {
			
			$ret .= '<option value="' . $value . '"';
		
			if ( $value == $selected ) {
				$ret .= ' selected="selected"';
			}
			$ret .= '>' . $prefix_curr . $this->_tree[$key]['obj']->getVar( $fieldName ) . '</option>';
			}
			$prefix_curr .= $prefix_orig;
		}
		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
				$this->_makeSelBoxOptions( $fieldName, $selected, $childkey, $ret, $perm, $prefix_orig, $prefix_curr );
			}
		}
	}
	
		function _makeSelBoxOptions2( $fieldName, $selected, $key, &$ret, $perm, $prefix_orig, $prefix_curr = '' ) {
		
		 global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
		 
		 //perm
		 $gperm_handler =& xoops_gethandler('groupperm');
		 	if (is_object($xoopsUser)) {
			$groups = $xoopsUser->getGroups();
			$uid = $xoopsUser->getVar('uid');
			} else {
			$groups = XOOPS_GROUP_ANONYMOUS;
			$uid = 0;
			}
			//
		if ( $key > 0 ) {
			$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
			$url = "viewcat.php?ct=".$this->_tree[$key]['obj']->getVar($this->_myId)."&tris=".$tris."&limit=".$limit;
			//$value = tdmspot_seo_genUrl( $xoopsModuleConfig['tdmspot_seo_cat'], $this->_tree[$key]['obj']->getVar('id'), $this->_tree[$key]['obj']->getVar('title'), $start, $limit, $tris );

			if ( !empty( $perm ) && $gperm_handler->checkRight($perm, $value, $groups, $xoopsModule->getVar('mid'))) {
			
			$ret .= '<option value="' . $url . '"';
		
			if ( $value == $selected ) {
				$ret .= ' selected="selected"';
			}
			$ret .= '>' . $prefix_curr . $this->_tree[$key]['obj']->getVar( $fieldName ) . '</option>';
			}
			$prefix_curr .= $prefix_orig;
		}
		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
				$this->_makeSelBoxOptions2( $fieldName, $selected, $childkey, $ret, $perm, $prefix_orig, $prefix_curr );
			}
		}
	}
	
		//fonction du trie
		function makeSelTris($cat, $tris) {
		
		 global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
		 
	//option du tris / nom de champ sql => nom afficher //	
	$option = array('file_title' => _MD_TDMPICTURE_TRITITLE , 'file_indate' => _MD_TDMPICTURE_TRIDATE, 'file_counts' => _MD_TDMPICTURE_TRICOUNTS, 'file_hits' => _MD_TDMPICTURE_TRIHITS, 'file_comments' => _MD_TDMPICTURE_TRICOMMENT, 'file_dl' => _MD_TDMPICTURE_TRIDL); 
	
	$ret = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';
	
	foreach ($option as $key => $value) {
	$select =  ($tris ==  $key) ? 'selected="selected"' : false;
	$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$cat."&tris=".$key;
	$ret .= '<option '.$select.' value="'.$cat_link.'">'.$value.'</option>';

	}
	$ret .= '</select>';
		
		return $ret;
	}
	
	//makeCatBox($item_handler,name cat, )  
	function makeCatBox( $item_handler, $fieldName, $prefix = '-', $selected = '', $key = 0 ) {
	Global $cat_display;
		
		$ret = '<div style="text-align:right"><a href="javascript:;" onclick="javascript:masque(\'1\')" >+-</a></div>';
		$ret .= '<table cellpadding="0" id="masque_1" cellspacing="0" style="border-collapse: separate;"><tr><td><ul id="tree_menu">';		
	
		$chcount = 1;
		$GLOBALS['class'] = "odd";
		$this->_makeCatBoxOptions( $item_handler, $fieldName, $selected, $key, $ret, $ret2, $prefix, '', $chcount );	
		$ret .= $ret2;
		$ret .= '<br style="clear: both;" /></ul></td></tr></table><br />';		

		return $ret;
		
	}
	
	function makeSelBox( $name, $fieldName, $prefix = '-', $selected = '', $addEmptyOption = false, $key = 0, $extra = '', $perm = false ) {
				
		$ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
		if ( false != $addEmptyOption ) {
			$ret .= '<option value="0">'.$addEmptyOption.'</option>';
		}
		if(!$extra){
		$this->_makeSelBoxOptions( $fieldName, $selected, $key, $ret, $perm, $prefix  );
		} else {
		$this->_makeSelBoxOptions2( $fieldName, $selected, $key, $ret, $perm, $prefix  );
		}
		return $ret . '</select>';
	}
	
}
?>