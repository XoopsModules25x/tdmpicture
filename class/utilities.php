<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops

        Redesigned and ameliorate By Luc Bizet user at www.frxoops.org
        Started with the Classifieds module and made MANY changes
        Website : http://www.luc-bizet.fr
        Contact : adslight.translate@gmail.com
-------------------------------------------------------------------------
             Original credits below Version History
##########################################################################
#                    Classified Module for Xoops                         #
#  By John Mordo user jlm69 at www.xoops.org and www.jlmzone.com         #
#      Started with the MyAds module and made MANY changes               #
##########################################################################
 Original Author: Pascal Le Boustouller
 Author Website : pascal.e-xoops@perso-search.com
 Licence Type   : GPL
-------------------------------------------------------------------------
*/

use Xmf\Request;
use Xmf\Module\Helper;

/**
 * TdmPictureUtilities Class
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      XOOPS Development Team
 * @package     Tdmpicture
 * @since       1.03
 *
 */

//namespace Xoopsmodules/Tdmpicture;

$moduleDirName = basename(dirname(__DIR__));
$myts          = MyTextSanitizer::getInstance();

/**
 * Class AdslightUtilities
 */
class TdmPictureUtilities
{

    /**
     * @param string $caption
     * @param string $name
     * @param string $value
     * @param string $width
     * @param string $height
     * @param string $supplemental
     *
     * @return XoopsFormDhtmlTextArea|XoopsFormEditor
     */
    public static function getEditor($caption, $name, $value = '', $width = '100%', $height = '300px', $supplemental = '')
    {

        global $xoopsModule;
        $moduleDirName = basename(dirname(__DIR__));
        $moduleHelper  = Helper::getHelper($moduleDirName);
        $options       = array();
        $isAdmin       = $GLOBALS['xoopsUser']->isAdmin($xoopsModule->getVar('mid'));

        if (class_exists('XoopsFormEditor')) {
            $options['name']   = $name;
            $options['value']  = $value;
            $options['rows']   = 20;
            $options['cols']   = '100%';
            $options['width']  = $width;
            $options['height'] = $height;
            if ($isAdmin) {
                $myEditor = new XoopsFormEditor(ucfirst($name), $moduleHelper->getConfig('adslightAdminUser'), $options, $nohtml = false,
                                                $onfailure = 'textarea');
            } else {
                $myEditor = new XoopsFormEditor(ucfirst($name), $moduleHelper->getConfig('adslightEditorUser'), $options, $nohtml = false,
                                                $onfailure = 'textarea');
            }
        } else {
            $myEditor = new XoopsFormDhtmlTextArea(ucfirst($name), $name, $value, '100%', '100%');
        }

        //        $form->addElement($descEditor);

        return $myEditor;
    }

    /**
     * @param $tablename
     *
     * @return bool
     */
    public static function checkTableExists($tablename)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * @param $fieldname
     * @param $table
     *
     * @return bool
     */
    public static function checkFieldExists($fieldname, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * @param $field
     * @param $table
     *
     * @return mixed
     */
    public static function addField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

        return $result;
    }

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder The full path of the directory to check
     *
     * @return void
     */
    public static function createFolder($folder)
    {
        try {
            if (!@mkdir($folder) && !is_dir($folder)) {
                throw new \RuntimeException(sprintf('Unable to create the %s directory', $folder));
            } else {
                file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n", '<br/>';
        }
    }

    /**
     * @param $file
     * @param $folder
     * @return bool
     */
    public static function copyFile($file, $folder)
    {
        return copy($file, $folder);
        //        try {
        //            if (!is_dir($folder)) {
        //                throw new \RuntimeException(sprintf('Unable to copy file as: %s ', $folder));
        //            } else {
        //                return copy($file, $folder);
        //            }
        //        } catch (Exception $e) {
        //            echo 'Caught exception: ', $e->getMessage(), "\n", "<br/>";
        //        }
        //        return false;
    }

    /**
     * @param $src
     * @param $dst
     */
    public static function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        //    @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     *
     * Verifies XOOPS version meets minimum requirements for this module
     * @static
     * @param XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkXoopsVer(XoopsModule $module)
    {
        xoops_loadLanguage('admin', $module->dirname());
        //check for minimum XOOPS version
        $currentVer  = substr(XOOPS_VERSION, 6); // get the numeric part of string
        $currArray   = explode('.', $currentVer);
        $requiredVer = '' . $module->getInfo('min_xoops'); //making sure it's a string
        $reqArray    = explode('.', $requiredVer);
        $success     = true;
        foreach ($reqArray as $k => $v) {
            if (isset($currArray[$k])) {
                if ($currArray[$k] > $v) {
                    break;
                } elseif ($currArray[$k] == $v) {
                    continue;
                } else {
                    $success = false;
                    break;
                }
            } else {
                if ((int)$v > 0) { // handles things like x.x.x.0_RC2
                    $success = false;
                    break;
                }
            }
        }

        if (!$success) {
            $module->setErrors(sprintf(_AM_ADSLIGHT_ERROR_BAD_XOOPS, $requiredVer, $currentVer));
        }

        return $success;
    }

    /**
     *
     * Verifies PHP version meets minimum requirements for this module
     * @static
     * @param XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkPhpVer(XoopsModule $module)
    {
        xoops_loadLanguage('admin', $module->dirname());
        // check for minimum PHP version
        $success = true;
        $verNum  = phpversion();
        $reqVer  =& $module->getInfo('min_php');
        if (false !== $reqVer && '' !== $reqVer) {
            if (version_compare($verNum, $reqVer, '<')) {
                $module->setErrors(sprintf(_AM_ADSLIGHT_ERROR_BAD_PHP, $reqVer, $verNum));
                $success = false;
            }
        }

        return $success;
    }

    public static function header()
    {
        global $xoopsConfig, $xoopsModule, $xoTheme, $xoopsTpl;
        $myts = MyTextSanitizer::getInstance();

        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/jquery-1.4.4.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/jquery-ui-1.7.1.custom.min.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/AudioPlayer.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/jquery.colorbox.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/jquery.jBreadCrumb.1.1.js');

            $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/css/tdmpicture.css');
        } else {
            $mp_module_header = "<link rel='stylesheet' type='text/css' href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/css/tdmpicture.css'/>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/jquery-1.4.4.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/jquery-ui-1.7.1.custom.min.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/AudioPlayer.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/jquery.colorbox.js'></script>
";
            $xoopsTpl->assign('xoops_module_header', $mp_module_header);
        }
    }

    public static function adminheader()
    {
        global $xoopsConfig, $xoopsModule, $xoTheme, $xoopsTpl;
        $myts = MyTextSanitizer::getInstance();

        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/jquery-1.4.4.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/AudioPlayer.js');
            $xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/js/jquery.Jcrop.js');

            $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/css/jquery.Jcrop.css');
        } else {
            $mp_module_header = "<link rel='stylesheet' type='text/css' href='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/css/jquery.Jcrop.css'/>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/jquery-1.4.4.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/AudioPlayer.js'></script>
<script type='text/javascript' src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/js/jquery.Jcrop.js'></script>
";
            echo $mp_module_header;
        }
    }

    //** function copie
    //function CopyDir($origine, $destination) {
    //    $test = scandir($origine);

    //    $file = 0;
    //    $file_tot = 0;

    //   foreach ($test as $val) {
    //       if ($val!="." && $val!="..") {
    //           if (is_dir($origine."/".$val)) {
    //               CopyDir($origine."/".$val, $destination."/".$val);
    //               IsDir_or_CreateIt($destination."/".$val);
    //           } else {
    //               ++$file_tot;
    //               if (copy($origine."/".$val, $destination."/".$val)) {
    //                   ++$file;
    //               } else {
    //                  if (!file_exists($origine."/".$val)) {
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
     * @param $content
     * @return string
     */

    public static function keywords($content)
    {
        $tmp = array();
        // Search for the "Minimum keyword length"
        $configHandler     = xoops_getHandler('config');
        $xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $limit             = $xoopsConfigSearch['keyword_min'];

        $myts            = MyTextSanitizer::getInstance();
        $content         = str_replace('<br>', ' ', $content);
        $content         = $myts->undoHtmlSpecialChars($content);
        $content         = strip_tags($content);
        $content         = strtolower($content);
        $search_pattern  = array(
            '&nbsp;',
            "\t",
            "\r\n",
            "\r",
            "\n",
            ',',
            '.',
            "'",
            ';',
            ':',
            ')',
            '(',
            '"',
            '?',
            '!',
            '{',
            '}',
            '[',
            ']',
            '<',
            '>',
            '/',
            '+',
            '-',
            '_',
            '\\',
            '*'
        );
        $replace_pattern = array(
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );
        $content         = str_replace($search_pattern, $replace_pattern, $content);
        $keywords        = explode(' ', $content);
        $keywords        = array_unique($keywords);

        foreach ($keywords as $keyword) {
            if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
                $tmp[] = $keyword;
            }
        }

        if (count($tmp) > 0) {
            return implode(',', $tmp);
        } else {
            return '';
        }
    }

    //admin navigation
    /**
     * @param $text
     * @param $form_sort
     * @return string
     */
    public static function switchselect($text, $form_sort)
    {
        global $start, $order, $file_cat, $sort, $xoopsModule;

        $select_view = '<form name="form_switch" id="form_switch" action="' . $_SERVER['REQUEST_URI']
                       . '" method="post"><span style="font-weight: bold;">' . $text . '</span>';
        //$sorts =  $sort ==  'asc' ? 'desc' : 'asc';
        if ($form_sort == $sort) {
            $sel1 = $order === 'asc' ? 'selasc.png' : 'asc.png';
            $sel2 = $order === 'desc' ? 'seldesc.png' : 'desc.png';
        } else {
            $sel1 = 'asc.png';
            $sel2 = 'desc.png';
        }
        $select_view .= '  <a href="' . $_SERVER['PHP_SELF'] . '?file_cat=' . $file_cat . '&start=' . $start . '&sort=' . $form_sort
                        . '&order=asc" /><img src="' . TDMPICTURE_IMAGES_URL . '/decos/' . $sel1 . '" title="ASC" alt="ASC"></a>';
        $select_view .= '<a href="' . $_SERVER['PHP_SELF'] . '?file_cat=' . $file_cat . '&start=' . $start . '&sort=' . $form_sort
                        . '&order=desc" /><img src="' . TDMPICTURE_IMAGES_URL . '/decos/' . $sel2 . '" title="DESC" alt="DESC"></a>';
        $select_view .= '</form>';

        return $select_view;
    }

    /**
     * Creation de la hauteur largeur image
     * @param $img_src
     * @param $dst_w
     * @param $dst_h
     * @return mixed
     */
    public static function redimage($img_src, $dst_w, $dst_h)
    {
        // Lit les dimensions de l'image
        $size = getimagesize($img_src);
        //$size[0] = width;
        //size[1] = height;

        $src_w = $size[0];
        $src_h = $size[1];
        // Teste les dimensions tenant dans la zone
        if ($src_h > $dst_h) {
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
        if (!$dst_h) {
            $dst_h = $test_h;
        } // Sinon si Width final non précisé (0)
        elseif (!$dst_w) {
            $dst_w = $test_w;
        } // Sinon teste quel redimensionnement tient dans la zone
        elseif ($test_h > $dst_h) {
            $dst_w = $test_w;
        } else {
            $dst_h = $test_h;
            $dst_w = $test_w;
        }
        $dst['min_w'] = $dst_w;
        $dst['min_h'] = $dst_h;

        // Affiche les dimensions optimales
        return $dst;
    }

    /**
     * xd_getdefaultmatchtypeid
     *
     * Returns default matchtype id for related event
     *
     * @package       pronoboulistenaute
     * @author        wild0ne (mailto:wild0ne@partypilger.de)
     * @copyright (c) wild0ne
     * @param $size
     * @return string
     */

    public static function prettySize($size)
    {
        $mb = 1024 * 1024;
        if ($size > $mb) {
            $mysize = sprintf('%01.2f', $size / $mb) . _MD_TDMPICTURE_MEGABYTES;
        } elseif ($size >= 1024) {
            $mysize = sprintf('%01.2f', $size / 1024) . _MD_TDMPICTURE_KILOBYTES;
        } else {
            $mysize = sprintf('oc', $size);
        }

        return $mysize;
    }

    //trouve si l'user a un album
    /**
     * @param $uid
     * @return bool
     */
    public static function useralb($uid)
    {
        $moduleDirName = basename(dirname(__DIR__));
        //calcul les albums
        $fileHandler = xoops_getModuleHandler('tdmpicture_file', $moduleDirName);
        $criteria    = new CriteriaCompo();
        $criteria->add(new Criteria('file_uid', $uid));
        $numalb = $fileHandler->getCount($criteria);

        if ($numalb != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $mytree
     * @param $cat
     */
    public static function catselect($mytree, $cat)
    {
        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        global $xoopsTpl, $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule;
        //perm
        $gpermHandler = xoops_getHandler('groupperm');

        //$catHandler = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);
        //$criteria = new CriteriaCompo();
        //$criteria->add(new Criteria('cat_display', 1));
        //$criteria->add(new Criteria('cat_index', 1));
        //$criteria->setSort('cat_weight');
        //$criteria->setOrder('DESC');
        //$arr = $catHandler->getall($criteria);
        //$mytree = new XoopsObjectTree($arr, 'id', 'pid');
        //$mytree = new TDMObjectTree($arr, 'cat_id', 'cat_pid');

        $form = new XoopsThemeForm('', 'catform', $_SERVER['REQUEST_URI'], 'post', true);
        //$form->setExtra('enctype="multipart/form-data"');
        $tagchannel_select = new XoopsFormLabel('', $mytree->makeSelBox('cat_pid', 'cat_title', '-', $cat, '-- ' . _MD_TDMPICTURE_CAT, 0,
                                                                        "OnChange='window.document.location=this.options[this.selectedIndex].value;'",
                                                                        'tdmpicture_catview'), 'pid');
        $form->addElement($tagchannel_select);

        //$form->display();
        $form->assign($xoopsTpl);
    }

    //fonction deplacer
    //function tdmpicture_trisselect($cat, $tris) {

    //global $start, $tris, $limit, $groups, $xoopsUser, $xoopsModule;
    //$catHandler = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);
    //$option = array('file_title' => _MD_TDMPICTURE_TRITITLE , 'file_indate' => _MD_TDMPICTURE_TRIDATE, 'file_counts' => _MD_TDMPICTURE_TRICOUNTS, 'file_hits' => _MD_TDMPICTURE_TRIHITS, 'file_comments' => _MD_TDMPICTURE_TRICOMMENT);
    //$select_tris = '<select name="tris" onchange="window.document.location=this.options[this.selectedIndex].value;">';
    //trouve le nom de la cat
    //$cat = $catHandler->get($cat);
    //foreach ($option as $key => $value) {
    //$select =  ($tris ==  $key) ? 'selected' : false;
    //$cat_link = TDMPICTURE_URL."/viewcat.php?ct=".$cat."&tris=".$key."&limit=".$limit;
    //$select_tris .= '<option '.$select.' value="'.$cat_link.'">'.$value.'</option>';

    //}
    //$select_tris .= '</select>';

    //return $select_tris;
    //}

    /**
     * @param $cat
     * @param $limit
     * @return string
     */
    public static function selectView($cat, $limit)
    {
        global $start, $tris, $xoopsModule;
        $option      = array(
            '10'  => 10,
            '20'  => 20,
            '30'  => 30,
            '40'  => 40,
            '50'  => 50,
            '100' => 100
        );
        $select_view = '<select name="limit" onchange="window.document.location=this.options[this.selectedIndex].value;">';
        //trouve le nom de la cat
        foreach (array_keys($option) as $i) {
            $select = ($limit == $option[$i]) ? 'selected' : false;
            //$view_link = $start.$option[$i].$tris;
            $link = TDMPICTURE_URL . '/viewcat.php?ct=' . $cat . '&tris=' . $tris . '&limit=' . $option[$i];
            $select_view .= '<option ' . $select . ' value="' . $link . '">' . $option[$i] . '</option>';
        }
        $select_view .= '</select>';

        return $select_view;
    }

    /**
     * @param        $array
     * @param string $before
     * @param string $after
     */
    public static function printTab($array, $before = '', $after = '')
    {
        //Affichage du texte HTML avant le tableau

        echo $before . "\n";

        //Encadrement de l'affichage du tableau par des balises <PRE>

        echo "<pre>\n";

        //Affichage récursif du tableau

        print_r($array);

        echo "</pre>\n";

        //Affichage du texte HTML après le tableau

        echo $after . "\n";
    }

    /**
     * admin menu
     * @param int    $currentoption
     * @param string $breadcrumb
     */
    public static function adminmenu($currentoption = 0, $breadcrumb = '')
    {
        /* Nice buttons styles */
        echo "
        <style type='text/css'>
        #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/tdmpicture/images/deco/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
        #buttonbar li { display:inline; margin:0; padding:0; }
        #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/tdmpicture/images/deco/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
        #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/tdmpicture/images/deco/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
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
        $myts = MyTextSanitizer::getInstance();

        $tblColors                 = array();
        $tblColors[0]
                                   =
        $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = '';
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
    <a href='" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/index.php'>" . $xoopsModule->getVar('dirname') . '</a>
    </td>';
        echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>"
             . $myts->displayTarea($xoopsModule->name()) . '  </b> ' . $breadcrumb . ' </td>';
        echo '</tr></table>';
        echo '</div>';

        echo "<div id='buttonbar'>";
        echo '<ul>';
        echo "<li id='" . $tblColors[0] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/admin/index.php\"><span>"
             . _MI_TDMSOUND_ADMENUINDEX . '</span></a></li>';
        echo "<li id='" . $tblColors[1] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/admin/genre.php\"><span>"
             . _MI_TDMSOUND_ADMENUGENRE . '</span></a></li>';
        echo "<li id='" . $tblColors[2] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/admin/artiste.php\"><span>"
             . _MI_TDMSOUND_ADMENUARTISTE . '</span></a></li>';
        echo "<li id='" . $tblColors[3] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/admin/album.php\"><span>"
             . _MI_TDMSOUND_ADMENUALBUM . '</span></a></li>';
        echo "<li id='" . $tblColors[4] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/admin/files.php\"><span>"
             . _MI_TDMSOUND_ADMENUFILE . '</span></a></li>';
        echo "<li id='" . $tblColors[5] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname')
             . "/admin/permissions.php\"><span>" . _MI_TDMSOUND_ADMENUPERMISSIONS . '</span></a></li>';
        echo "<li id='" . $tblColors[6] . "'><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . "/admin/about.php\"><span>"
             . _MI_TDMSOUND_ADMENUABOUT . '</span></a></li>';
        echo "<li id='" . $tblColors[7] . "'><a href='../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid')
             . "'><span>" . _MI_TDMSOUND_ADMENUPREF . '</span></a></li>';
        echo '</ul></div>&nbsp;';
    }
}
