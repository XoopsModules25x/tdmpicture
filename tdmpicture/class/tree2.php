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

class TDMCATObjectTree extends XoopsObjectTree {
    
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
	
	function _makeCatBoxOptions( $item_handler, $fieldName, $selected, $key, &$ret, &$ret2, $prefix_orig, $prefix_curr = '') {
Global $xoopsModule, $xoopsModuleConfig, $cat_display, $cat_cel, $groups, $tris, $order  ;
	
		
	$gperm_handler =& xoops_gethandler('groupperm');
	$parent = "";
	$prefix_class = "";
	$scat_display = isset($GLOBALS['scat_display']) ? $GLOBALS['scat_display'] : true;
	//$GLOBALS['navbar'] .= "";
		
	
	if ( $key > 0 && $gperm_handler->checkRight('tdmpicture_catview', $this->_tree[$key]['obj']->getVar('cat_id'), $groups, $xoopsModule->getVar('mid'))) {
		$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('file_cat', $this->_tree[$key]['obj']->getVar('cat_id')));
		$criteria->add(new Criteria('file_display', 1));
		$GLOBALS['count'] = $count = $item_handler->getCount($criteria);
		//$cat_link = tdmspot_seo_genUrl( $xoopsModuleConfig['tdmspot_seo_cat'], $this->_tree[$key]['obj']->getVar('id'), $this->_tree[$key]['obj']->getVar('title'), $start, $limit, $tris );
		//$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$this->_tree[$key]['obj']->getVar('cat_id')."&tris=".$tris."&order=".$order;
		$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$this->_tree[$key]['obj']->getVar('cat_id');
		//recherche image
		//$imgpath = TDMPICTURE_CAT_PATH  . $this->_tree[$key]['obj']->getVar('cat_img');
		//if (file_exists($imgpath) && $this->_tree[$key]['obj']->getVar('cat_img') != "blank.gif") {
		//$picture = '<a href ="'.$cat_link.'" title="'. $this->_tree[$key]['obj']->getVar('cat_title').'"><img src="' . TDMPICTURE_CAT_URL .  $this->_tree[$key]['obj']->getVar('cat_img').'" class="img" width="'.$xoopsModuleConfig['tdmpicture_cat_width'].'"  height="'.$xoopsModuleConfig['tdmpicture_cat_height'].'"></a>';
		//} else {
		//$picture = '<a href ="'.$cat_link.'" title="'. $this->_tree[$key]['obj']->getVar('cat_title').'"><img src="' . TDMPICTURE_CAT_URL . 'no_picture.png" class="img" width="'.$xoopsModuleConfig['tdmpicture_cat_width'].'"  height="'.$xoopsModuleConfig['tdmpicture_cat_height'].'"></a>';
		//}
		
		if (empty($prefix_curr)) {
		//echo $this->_tree[$key]['obj']->getVar('cat_title');
		$prefix_class = "class=last";
		}else {
		$prefix_class = "";
		//echo "passe";
		}			
			
		if (isset($selected) && $value == $selected ) {
		
		if ( isset( $this->_tree[$this->_tree[$key]['parent']]['obj'] ) ) {
		$category_parent = $this->getAllParent($key);
		$category_parent = array_reverse($category_parent);
	
		foreach (array_keys($category_parent) as $j) {
		//$parent_link = TDMPICTURE_URL."/viewcat.php?ct=".$category_parent[$j]->getVar('cat_id')."&tris=".$tris."&limit=".$limit;
		$parent_link = TDMPICTURE_URL."/viewcat.php?ct=".$category_parent[$j]->getVar('cat_id');
        $ret .= '<li class=last><a href="' .$parent_link . '">' . $category_parent[$j]->getVar('cat_title') . '</a></li>';
        }
		
		}
	
		
		$prefix_class = "class=last";
		$GLOBALS['cat_title'] = $this->_tree[$key]['obj']->getVar('cat_title');
		$ret .=  '<li class=last><a href ="'.$cat_link.'" title="('.$count.')">'.$this->_tree[$key]['obj']->getVar('cat_title'). '</a></li>';		
		
		}
		
		if ($this->_tree[$key]['obj']->getVar('cat_pid') == $selected) { 
		
		$cat_title = (strlen($this->_tree[$key]['obj']->getVar('cat_title')) > 30 ? substr($this->_tree[$key]['obj']->getVar('cat_title'),0,(30)) : $this->_tree[$key]['obj']->getVar('cat_title'));
		$cat_text = (strlen($this->_tree[$key]['obj']->getVar('cat_text')) > 120 ? substr($this->_tree[$key]['obj']->getVar('cat_text'),0,(120))."..." : $this->_tree[$key]['obj']->getVar('cat_text'));	

		$ret .=  '<li '.$prefix_class.'><a href ="'.$cat_link.'" title="('.$count.')">'.$cat_title. '</a></li>';
		//$ret .= $select;
		//echo $cat_title;
		//}
	}
		
			//}
			
			$prefix_curr++;

			//}

		}
		



		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
			$this->_makeCatBoxOptions( $item_handler, $fieldName, $selected, $childkey, $ret, $ret2, $prefix_orig, $prefix_curr);
		
				
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
	
		function _makeCatBoxOptions2( $fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '', $i=0) {
		
		 global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $count, $xoopsTpl;

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
		if ( $key > 0 && $this->_tree[$key]['obj']->getVar('cat_pid') !=0 ) {
			$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
			$url = "viewcat.php?ct=".$this->_tree[$key]['obj']->getVar($this->_myId)."&tris=".$tris."&limit=".$limit;
			//$value = tdmspot_seo_genUrl( $xoopsModuleConfig['tdmspot_seo_cat'], $this->_tree[$key]['obj']->getVar('id'), $this->_tree[$key]['obj']->getVar('title'), $start, $limit, $tris );


			if ($value == $selected) {	
			//$ret .=  '<li class=last><a href ="'.$cat_link.'" title="('.$count.')">'. $cat_title. '</a></li>';
			$prefix_curr = "class=last";
			
			$GLOBALS['cat_title'] = $this->_tree[$key]['obj']->getVar('cat_title');
			
			}
		
			if ( $key > 0 && $gperm_handler->checkRight('tdmpicture_catview', $this->_tree[$key]['obj']->getVar('cat_id'), $groups, $xoopsModule->getVar('mid'))) {
		
			//if ( $value == $selected ) {
			//	$ret .= ' selected="selected"';
			//}
			 $this->_tree[$key]['obj']->getVar( $fieldName );
	
			$ret .= '<li '.$prefix_curr.'><a href="' . $url . '" title="'.$count.'">' . $this->_tree[$key]['obj']->getVar( $fieldName ) . '</a></li>';
	
			}
			$prefix_curr .= $prefix_orig;
			$i++;
		}	
		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {	
		
			//sous cat
			if ($i<=1){		
				$this->_makeCatBoxOptions2( $fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr, $i );
				$i++;
				}
				
			}
		}
	}
	
		//fonction du trie
		function makeSelTris($url, $cat, $tris, $order) {
		
		 global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
		 
	//option du tris / nom de champ sql => nom afficher //	
	$option = array('file_title' => _MD_TDMPICTURE_TRITITLE , 'file_indate' => _MD_TDMPICTURE_TRIDATE, 'file_counts' => _MD_TDMPICTURE_TRICOUNTS, 'file_hits' => _MD_TDMPICTURE_TRIHITS, 'file_comments' => _MD_TDMPICTURE_TRICOMMENT, 'file_dl' => _MD_TDMPICTURE_TRIDL); 
	
	$ret = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';
	
	foreach ($option as $key => $value) {
	$select =  ($tris ==  $key) ? 'selected="selected"' : false;
	$cat_link = $url."?".$cat."&tris=".$key;
	$ret .= '<option '.$select.' value="'.$cat_link.'">'.$value.'</option>';

	}
	$ret .= '</select>';
	if ($order == "desc") {
	$ret .= '<a href='.$url.'?'.$cat.'&tris='.$tris.'&order=asc title='._MD_TDMPICTURE_ASC.'><img src='.TDMPICTURE_IMAGES_URL.'/asc.png></a>';
	} else {
	$ret .= '<a href='.$url.'?'.$cat.'&tris='.$tris.'&order=desc title='._MD_TDMPICTURE_DESC.' ><img src='.TDMPICTURE_IMAGES_URL.'/desc.png></a>';
	}
	return $ret;
	}
	
	//makeCatBox($item_handler,name cat, )  
	function makeCatBox( $item_handler, $fieldName, $prefix = '-', $selected = '', $key = 0 ) {
	global $cat_display, $navuser, $xoopsModule, $xoopsModuleConfig;
	
		$ret = '<div class="breadCrumbHolder module">
        <div id="breadCrumb" class="breadCrumb module outer">
		<ul>';		
		$ret .= '<li class=first><a href ="'.TDMPICTURE_URL.'"/index.php" title="'. $xoopsModule->name().'">' . $xoopsModule->name(). '</a></li>';
		$chcount = 1;
		$GLOBALS['class'] = "odd";
		$this->_makeCatBoxOptions( $item_handler, $fieldName, $selected, $key, $ret, $ret2, '', '', $chcount );	
		$ret .= $ret2;
		if (!empty($GLOBALS['navuser'])) {
$ret .="<li class=last>".$GLOBALS['navuser']."</li>";
}
		$ret .= '</ul></div>';		

		return $ret;
		
	}
	
	function makecatSelBox( $name, $fieldName, $prefix = '-', $selected = '', $addEmptyOption = false, $key = 0, $extra = '', $perm = false ) {
				
		//$ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
		//if ( false != $addEmptyOption ) {
		//	$ret .= '<option value="0">'.$addEmptyOption.'</option>';
		//}
		//if(!$extra){
		//$this->_makeSelBoxOptions( $fieldName, $selected, $key, $ret, $perm, $prefix  );
		//} else {
		$this->_makeSelBoxOptions2( $fieldName, $selected, $key, $ret, $perm, $prefix  );
		//}
		return $ret . '</select>';
	}
	
}
?>