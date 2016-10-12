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
 * @author          G. Mage (www.tdmxoops.net)
 *
 * ***************************************************************************/
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/class/tree.php';

/**
 * Class TdmCatObjectTree
 */
class TdmCatObjectTree extends XoopsObjectTree
{
    //function __constrcut(){
    //}
    /**
     * @param        $fieldName
     * @param        $key
     * @param        $ret
     * @param        $prefix_orig
     * @param string $prefix_curr
     */
    public function _makeArrayTreeOptions($fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value       = $this->tree[$key]['obj']->getVar($this->myId);
            $ret[$value] = $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= $prefix_orig;
        }

        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeArrayTreeOptions($fieldName, $childkey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * @param        $fieldName
     * @param string $prefix
     * @param int    $key
     * @return array
     */
    public function makeArrayTree($fieldName, $prefix = '-', $key = 0)
    {
        $ret = array();
        $this->_makeArrayTreeOptions($fieldName, $key, $ret, $prefix);

        return $ret;
    }

    /**
     * @param        $itemHandler
     * @param        $fieldName
     * @param        $selected
     * @param        $key
     * @param        $ret
     * @param        $ret2
     * @param        $prefix_orig
     * @param string $prefix_curr
     */
    public function _makeCatBoxOptions(
        $itemHandler,
        $fieldName,
        $selected,
        $key,
        &$ret,
        &$ret2,
        $prefix_orig,
        $prefix_curr = ''
    ) {
        global $xoopsModule, $cat_display, $cat_cel, $groups, $tris, $order;

        $gpermHandler = xoops_getHandler('groupperm');
        $parent        = '';
        $prefix_class  = '';
        $scat_display  = isset($GLOBALS['scat_display']) ? $GLOBALS['scat_display'] : true;
        //$GLOBALS['navbar'] .= "";

        if ($key > 0
            && $gpermHandler->checkRight('tdmpicture_catview', $this->tree[$key]['obj']->getVar('cat_id'), $groups, $xoopsModule->getVar('mid'))
        ) {
            $value    = $this->tree[$key]['obj']->getVar($this->myId);
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('file_cat', $this->tree[$key]['obj']->getVar('cat_id')));
            $criteria->add(new Criteria('file_display', 1));
            $GLOBALS['count'] = $count = $itemHandler->getCount($criteria);
            //$cat_link = tdmspot_seo_genUrl( $helper->getConfig('tdmspot_seo_cat'), $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris );
            //$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$this->tree[$key]['obj']->getVar('cat_id')."&tris=".$tris."&order=".$order;
            $cat_link = TDMPICTURE_URL . '/viewcat.php?ct=' . $this->tree[$key]['obj']->getVar('cat_id');
            //recherche image
            //$imgpath = TDMPICTURE_CAT_PATH  . $this->tree[$key]['obj']->getVar('cat_img');
            //if (file_exists($imgpath) && $this->tree[$key]['obj']->getVar('cat_img') != "blank.png") {
            //$picture = '<a href ="'.$cat_link.'" title="'. $this->tree[$key]['obj']->getVar('cat_title').'"><img src="' . TDMPICTURE_CAT_URL .  $this->tree[$key]['obj']->getVar('cat_img').'" class="img" width="'.$helper->getConfig('tdmpicture_cat_width').'"  height="'.$helper->getConfig('tdmpicture_cat_height').'"></a>';
            //} else {
            //$picture = '<a href ="'.$cat_link.'" title="'. $this->tree[$key]['obj']->getVar('cat_title').'"><img src="' . TDMPICTURE_CAT_URL . 'no_picture.png" class="img" width="'.$helper->getConfig('tdmpicture_cat_width').'"  height="'.$helper->getConfig('tdmpicture_cat_height').'"></a>';
            //}

            if (empty($prefix_curr)) {
                //echo $this->tree[$key]['obj']->getVar('cat_title');
                $prefix_class = 'class=last';
            } else {
                $prefix_class = '';
                //echo "passe";
            }

            if (isset($selected) && $value == $selected) {
                if (isset($this->tree[$this->tree[$key]['parent']]['obj'])) {
                    $category_parent = $this->getAllParent($key);
                    $category_parent = array_reverse($category_parent);

                    foreach (array_keys($category_parent) as $j) {
                        //$parent_link = TDMPICTURE_URL."/viewcat.php?ct=".$category_parent[$j]->getVar('cat_id')."&tris=".$tris."&limit=".$limit;
                        $parent_link = TDMPICTURE_URL . '/viewcat.php?ct=' . $category_parent[$j]->getVar('cat_id');
                        $ret .= '<li class=last><a href="' . $parent_link . '">' . $category_parent[$j]->getVar('cat_title') . '</a></li>';
                    }
                }

                $prefix_class         = 'class=last';
                $GLOBALS['cat_title'] = $this->tree[$key]['obj']->getVar('cat_title');
                $ret .= '<li class=last><a href ="' . $cat_link . '" title="(' . $count . ')">' . $this->tree[$key]['obj']->getVar('cat_title') . '</a></li>';
            }

            if ($this->tree[$key]['obj']->getVar('cat_pid') == $selected) {
                $cat_title = (strlen($this->tree[$key]['obj']->getVar('cat_title')) > 30 ? substr($this->tree[$key]['obj']->getVar('cat_title'), 0, 30) : $this->tree[$key]['obj']->getVar('cat_title'));
                $cat_text  = (strlen($this->tree[$key]['obj']->getVar('cat_text')) > 120 ? substr($this->tree[$key]['obj']->getVar('cat_text'), 0, 120) . '...' : $this->tree[$key]['obj']->getVar('cat_text'));

                $ret .= '<li ' . $prefix_class . '><a href ="' . $cat_link . '" title="(' . $count . ')">' . $cat_title . '</a></li>';
                //$ret .= $select;
                //echo $cat_title;
                //}
            }

            //}

            ++$prefix_curr;

            //}
        }

        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeCatBoxOptions($itemHandler, $fieldName, $selected, $childkey, $ret, $ret2, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * @param        $fieldName
     * @param        $selected
     * @param        $key
     * @param        $ret
     * @param        $perm
     * @param        $prefix_orig
     * @param string $prefix_curr
     */
    public function _makeSelBoxOptions($fieldName, $selected, $key, &$ret, $perm, $prefix_orig, $prefix_curr = '')
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule;

        //perm
        $gpermHandler = xoops_getHandler('groupperm');
        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
            $uid    = $xoopsUser->getVar('uid');
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $uid    = 0;
        }
        //
        if ($key > 0) {
            $value = $this->tree[$key]['obj']->getVar($this->myId);
            //$url = "viewcat.php?ct=".$this->tree[$key]['obj']->getVar($this->myId)."&tris=".$tris."&limit=".$limit;
            //$value = tdmspot_seo_genUrl( $helper->getConfig('tdmspot_seo_cat'), $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris );

            if (!empty($perm) && $gpermHandler->checkRight($perm, $value, $groups, $xoopsModule->getVar('mid'))) {
                $ret .= '<option value="' . $value . '"';

                if ($value == $selected) {
                    $ret .= ' selected';
                }
                $ret .= '>' . $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName) . '</option>';
            }
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeSelBoxOptions($fieldName, $selected, $childkey, $ret, $perm, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * @param        $fieldName
     * @param        $selected
     * @param        $key
     * @param        $ret
     * @param        $prefix_orig
     * @param string $prefix_curr
     * @param int    $i
     */
    public function _makeCatBoxOptions2($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '', $i = 0)
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule, $count, $xoopsTpl;

        //perm
        $gpermHandler = xoops_getHandler('groupperm');
        if (is_object($xoopsUser)) {
            $groups = $xoopsUser->getGroups();
            $uid    = $xoopsUser->getVar('uid');
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
            $uid    = 0;
        }
        //
        if ($key > 0 && $this->tree[$key]['obj']->getVar('cat_pid') != 0) {
            $value = $this->tree[$key]['obj']->getVar($this->myId);
            $url   = 'viewcat.php?ct=' . $this->tree[$key]['obj']->getVar($this->myId) . '&tris=' . $tris . '&limit=' . $limit;
            //$value = tdmspot_seo_genUrl( $helper->getConfig('tdmspot_seo_cat'), $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris );

            if ($value == $selected) {
                //$ret .=  '<li class=last><a href ="'.$cat_link.'" title="('.$count.')">'. $cat_title. '</a></li>';
                $prefix_curr = 'class=last';

                $GLOBALS['cat_title'] = $this->tree[$key]['obj']->getVar('cat_title');
            }

            if ($key > 0
                && $gpermHandler->checkRight('tdmpicture_catview', $this->tree[$key]['obj']->getVar('cat_id'), $groups, $xoopsModule->getVar('mid'))
            ) {

                //if ($value == $selected) {
                //  $ret .= ' selected';
                //}
                $this->tree[$key]['obj']->getVar($fieldName);

                $ret .= '<li ' . $prefix_curr . '><a href="' . $url . '" title="' . $count . '">' . $this->tree[$key]['obj']->getVar($fieldName) . '</a></li>';
            }
            $prefix_curr .= $prefix_orig;
            ++$i;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {

                //sous cat
                if ($i <= 1) {
                    $this->_makeCatBoxOptions2($fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr, $i);
                    ++$i;
                }
            }
        }
    }

    //fonction du trie
    /**
     * @param $url
     * @param $cat
     * @param $tris
     * @param $order
     * @return string
     */
    public function makeSelTris($url, $cat, $tris, $order)
    {
        global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule;

        //option du tris / nom de champ sql => nom afficher //
        $option = array(
            'file_title'    => _MD_TDMPICTURE_TRITITLE,
            'file_indate'   => _MD_TDMPICTURE_TRIDATE,
            'file_counts'   => _MD_TDMPICTURE_TRICOUNTS,
            'file_hits'     => _MD_TDMPICTURE_TRIHITS,
            'file_comments' => _MD_TDMPICTURE_TRICOMMENT,
            'file_dl'       => _MD_TDMPICTURE_TRIDL
        );

        $ret = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';

        foreach ($option as $key => $value) {
            $select   = ($tris == $key) ? 'selected' : false;
            $cat_link = $url . '?' . $cat . '&tris=' . $key;
            $ret .= '<option ' . $select . ' value="' . $cat_link . '">' . $value . '</option>';
        }
        $ret .= '</select>';
        if ($order == 'desc') {
            $ret .= '<a href=' . $url . '?' . $cat . '&tris=' . $tris . '&order=asc title=' . _MD_TDMPICTURE_ASC . '><img src=' . TDMPICTURE_IMAGES_URL . '/asc.png></a>';
        } else {
            $ret .= '<a href=' . $url . '?' . $cat . '&tris=' . $tris . '&order=desc title=' . _MD_TDMPICTURE_DESC . ' ><img src=' . TDMPICTURE_IMAGES_URL . '/desc.png></a>';
        }

        return $ret;
    }

    //makeCatBox($itemHandler,name cat, )
    /**
     * @param        $itemHandler
     * @param        $fieldName
     * @param string $prefix
     * @param string $selected
     * @param int    $key
     * @return string
     */
    public function makeCatBox($itemHandler, $fieldName, $prefix = '-', $selected = '', $key = 0)
    {
        global $cat_display, $navuser, $xoopsModule;

        $ret = '<div class="breadCrumbHolder module">
        <div id="breadCrumb" class="breadCrumb module outer">
        <ul>';
        $ret .= '<li class=first><a href ="' . TDMPICTURE_URL . '/index.php" title="' . $xoopsModule->name() . '">' . $xoopsModule->name() . '</a></li>';
        $chcount          = 1;
        $GLOBALS['class'] = 'odd';
        $this->_makeCatBoxOptions($itemHandler, $fieldName, $selected, $key, $ret, $ret2, '', '', $chcount);
        $ret .= $ret2;
        if (!empty($GLOBALS['navuser'])) {
            $ret .= '<li class=last>' . $GLOBALS['navuser'] . '</li>';
        }
        $ret .= '</ul></div>';

        return $ret;
    }

    /**
     * @param        $name
     * @param        $fieldName
     * @param string $prefix
     * @param string $selected
     * @param bool   $addEmptyOption
     * @param int    $key
     * @param string $extra
     * @param bool   $perm
     * @return string
     */
    public function makecatSelBox(
        $name,
        $fieldName,
        $prefix = '-',
        $selected = '',
        $addEmptyOption = false,
        $key = 0,
        $extra = '',
        $perm = false
    ) {
        //$ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
        //if (false != $addEmptyOption) {
        //  $ret .= '<option value="0">'.$addEmptyOption.'</option>';
        //}
        //if (!$extra) {
        //$this->_makeSelBoxOptions( $fieldName, $selected, $key, $ret, $perm, $prefix  );
        //} else {
        $this->_makeSelBoxOptions2($fieldName, $selected, $key, $ret, $perm, $prefix);

        //}
        return $ret . '</select>';
    }
}
