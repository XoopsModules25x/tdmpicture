<?php

use Xmf\Module\Helper;

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
 * Class TDMObjectTree
 */
class TDMObjectTree extends XoopsObjectTree
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
     * @param        $prefix_orig
     * @param string $prefix_curr
     * @param        $ret2
     * @param        $chcount
     */
    public function _makeCatBoxOptions($itemHandler, $fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '', &$ret2, $chcount)
    {
        global $xoopsModule, $cat_display, $cat_cel, $groups, $start, $limit, $tris;
        $moduleDirName = basename(dirname(__DIR__));
        $moduleHelper        = Helper::getHelper($moduleDirName);
        $gpermHandler  = xoops_getHandler('groupperm');
        $parent        = '';
        $scat_display  = isset($GLOBALS['scat_display']) ? $GLOBALS['scat_display'] : true;
        //$GLOBALS['navbar'] .= "";

        if ($key > 0
            && $gpermHandler->checkRight('tdmpicture_catview', $this->tree[$key]['obj']->getVar('cat_id'), $groups,
                                         $xoopsModule->getVar('mid'))
        ) {
            $value    = $this->tree[$key]['obj']->getVar($this->myId);
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('file_cat', $this->tree[$key]['obj']->getVar('cat_id')));
            $criteria->add(new Criteria('file_display', 1));
            $count = $itemHandler->getCount($criteria);
            //$cat_link = tdmspot_seo_genUrl( $moduleHelper->getConfig('tdmspot_seo_cat'), $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris );
            $cat_link = TDMPICTURE_URL . '/viewcat.php?ct=' . $this->tree[$key]['obj']->getVar('cat_id') . '&tris='
                        . $tris . '&limit=' . $limit;
            //recherche image
            $imgpath = TDMPICTURE_CAT_PATH . $this->tree[$key]['obj']->getVar('cat_img');
            if (file_exists($imgpath) && $this->tree[$key]['obj']->getVar('cat_img') != 'blank.png') {
                $picture = '<a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('cat_title')
                           . '"><img src="' . TDMPICTURE_CAT_URL . $this->tree[$key]['obj']->getVar('cat_img')
                           . '" class="img" width="' . $moduleHelper->getConfig('tdmpicture_cat_width') . '"  height="'
                           . $moduleHelper->getConfig('tdmpicture_cat_height') . '"></a>';
            } else {
                $picture = '<a href ="' . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('cat_title')
                           . '"><img src="' . TDMPICTURE_CAT_URL . 'no_picture.png" class="img" width="'
                           . $moduleHelper->getConfig('tdmpicture_cat_width') . '"  height="'
                           . $moduleHelper->getConfig('tdmpicture_cat_height') . '"></a>';
            }

            if (isset($selected) && $value == $selected) {
                $url_link          = TDMPICTURE_URL . '/index.php';
                $GLOBALS['navbar'] = '<a href ="' . $url_link . '" title="' . $xoopsModule->name() . '">'
                                     . $xoopsModule->name() . '</a> > ';
                //trie
                $navtrie = $this->makeSelTris((int)$value, $tris);

                if (isset($this->tree[$this->tree[$key]['parent']]['obj'])) {
                    $parent_link = TDMPICTURE_URL . '/viewcat.php?ct='
                                   . $this->tree[$this->tree[$key]['parent']]['obj']->getVar('cat_id') . '&tris='
                                   . $tris . '&limit=' . $limit;
                    $GLOBALS['navbar'] .= '<a href ="' . $parent_link . '" title="'
                                          . $this->tree[$this->tree[$key]['parent']]['obj']->getVar('cat_title') . '">'
                                          . $this->tree[$this->tree[$key]['parent']]['obj']->getVar('cat_title')
                                          . '</a> > ';
                }

                //$GLOBALS['cat_count'] = $count;
                $GLOBALS['cat_title'] = $this->tree[$key]['obj']->getVar('cat_title');

                $select = $this->makeSelBox('cat_pid', 'cat_title', '-', $selected, '',
                                            $this->tree[$key]['obj']->getVar('cat_id'),
                                            "OnChange='window.document.location=this.options[this.selectedIndex].value;'",
                                            'tdmpicture_catview');
                $ret2   = '<li class="' . $GLOBALS['class'] . '"><div><div id="img">' . $picture
                          . '<br><span id="tree_num"> (' . $count . ')</span></div><div id="tree_detail"><h2><a href ="'
                          . $cat_link . '" title="' . $this->tree[$key]['obj']->getVar('cat_title') . '">'
                          . $this->tree[$key]['obj']->getVar($fieldName) . '</h2></a><span id="tree_text">'
                          . $this->tree[$key]['obj']->getVar('cat_text')
                          . '</span></div><br style="clear: both;" /><div id="tree_form">' . $GLOBALS['navbar']
                          . $select . ' | ' . _MD_TDMPICTURE_TRIBY . ' > ' . $navtrie . '</div></div></div></li>';
            }

            //if ((!$prefix_curr) && ($this->tree[$key]['obj']->getVar('cat_pid') == $selected)) {
            if ($scat_display && $this->tree[$key]['obj']->getVar('cat_pid') == $selected) {
                if ((!$prefix_curr) || $moduleHelper->getConfig('tdmpicture_cat_display')) {
                    if (!empty($this->tree[$key]['child']) && $moduleHelper->getConfig('tdmpicture_cat_select')) {
                        $select = $this->makeSelBox('cat_pid', 'cat_title', '-', 0, '',
                                                    $this->tree[$key]['obj']->getVar('cat_id'),
                                                    "OnChange='window.document.location=this.options[this.selectedIndex].value;'",
                                                    'tdmpicture_catview');
                    } else {
                        $select = false;
                    }

                    $cat_title = (strlen($this->tree[$key]['obj']->getVar('cat_title'))
                                  > 30 ? substr($this->tree[$key]['obj']->getVar('cat_title'), 0,
                                                30) : $this->tree[$key]['obj']->getVar('cat_title'));
                    $cat_text  = (strlen($this->tree[$key]['obj']->getVar('cat_text'))
                                  > 120 ? substr($this->tree[$key]['obj']->getVar('cat_text'), 0, 120)
                                          . '...' : $this->tree[$key]['obj']->getVar('cat_text'));
                    $ret .= '<li style="width:46%;" class="' . $GLOBALS['class'] . '"><div><div id="img">' . $picture
                            . '<br><span id="tree_num"> (' . $count
                            . ')</span></div><div id="tree_detail"><h2><a href ="' . $cat_link . '" title="'
                            . $this->tree[$key]['obj']->getVar('cat_title') . '">' . $cat_title
                            . '</h2></a><span id="tree_text">' . $cat_text
                            . '</span></div><br style="clear: both;" /><div id="tree_form">' . $select
                            . '</div></div></div></li>';
                }
            }

            //}
            $prefix_curr .= $prefix_orig;

            //}
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $GLOBALS['class'] = ($GLOBALS['class'] == 'even') ? 'odd' : 'even';
                $this->_makeCatBoxOptions($itemHandler, $fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr, $ret2, $chcount);
            }
        }
    }

    /**
     * @param string $fieldName
     * @param string $selected
     * @param int    $key
     * @param string $ret
     * @param string $prefix_orig
     * @param string $prefix_curr
     * @param mixed  $perm
     */
    public function _makeSelBoxOptions($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '', $perm)
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
            //$value = tdmspot_seo_genUrl( $moduleHelper->getConfig('tdmspot_seo_cat'), $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris );

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
                $this->_makeSelBoxOptions($fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr, $perm);
            }
        }
    }

    /**
     * @param string $fieldName
     * @param string $selected
     * @param int    $key
     * @param string $ret
     * @param string $prefix_orig
     * @param string $prefix_curr
     * @param mixed  $perm
     */
    public function _makeSelBoxOptions2($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '', $perm)
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
            $url   = 'viewcat.php?ct=' . $this->tree[$key]['obj']->getVar($this->myId) . '&tris=' . $tris . '&limit='
                     . $limit;
            //$value = tdmspot_seo_genUrl( $moduleHelper->getConfig('tdmspot_seo_cat'), $this->tree[$key]['obj']->getVar('id'), $this->tree[$key]['obj']->getVar('title'), $start, $limit, $tris );

            if (!empty($perm) && $gpermHandler->checkRight($perm, $value, $groups, $xoopsModule->getVar('mid'))) {
                $ret .= '<option value="' . $url . '"';

                if ($value == $selected) {
                    $ret .= ' selected';
                }
                $ret .= '>' . $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName) . '</option>';
            }
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeSelBoxOptions2($fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr, $perm);
            }
        }
    }

    //fonction du trie
    /**
     * @param $cat
     * @param $tris
     * @return string
     */
    public function makeSelTris($cat, $tris)
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
            $cat_link = TDMPICTURE_URL . '/viewcat.php?ct=' . $cat . '&tris=' . $key;
            $ret .= '<option ' . $select . ' value="' . $cat_link . '">' . $value . '</option>';
        }
        $ret .= '</select>';

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
        global $cat_display;

        $ret = '<div style="text-align:right;"><a href="javascript:;" onclick="masque(\'1\')" >+-</a></div>';
        $ret .= '<table cellpadding="0" id="masque_1" cellspacing="0" style="border-collapse: separate;"><tr><td><ul id="tree_menu">';

        $chcount          = 1;
        $GLOBALS['class'] = 'odd';
        $this->_makeCatBoxOptions($itemHandler, $fieldName, $selected, $key, $ret,  $prefix, '', $ret2, $chcount);
        $ret .= $ret2;
        $ret .= '<br style="clear: both;" /></ul></td></tr></table><br>';

        return $ret;
    }

    /**
     * @param string $name
     * @param string $fieldName
     * @param string $prefix
     * @param string $selected
     * @param bool   $addEmptyOption
     * @param int    $key
     * @param string $extra
     * @param bool   $perm
     * @return string
     */
    public function makeSelBox($name, $fieldName, $prefix = '-', $selected = '', $addEmptyOption = false, $key = 0,
                               $extra = '', $perm = false
    )
    {
        $ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
        if (false != $addEmptyOption) {
            $ret .= '<option value="0">' . $addEmptyOption . '</option>';
        }
        if (!$extra) {
            $this->_makeSelBoxOptions($fieldName, $selected, $key, $ret, $prefix, $prefix, $perm);
        } else {
            $this->_makeSelBoxOptions2($fieldName, $selected, $key, $ret, $prefix, $prefix, $perm);
        }

        return $ret . '</select>';
    }
}
