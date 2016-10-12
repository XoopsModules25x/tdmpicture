<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.tdmxoops.net)
 *
 * Cette licence, contient des limitations!!!
 *
 * 1. Vous devez poss�der une permission d'ex�cuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' �tudier,
 * 3. Vous ne devez pas le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la libert� de l'am�liorer et de rendre publiques les modifications
 *
 * @license     TDMFR PRO license
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

global $xoopsModule;
$moduleDirName = basename(dirname(__DIR__));
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$xoopsModule   = $moduleHandler->getByDirname($moduleDirName);
if (!isset($GLOBALS['xoopsModuleConfig'])) {
    $configHandler    = xoops_getHandler('config');
    $GLOBALS['xoopsModuleConfig'] = &$configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
}

include_once XOOPS_ROOT_PATH . '/modules/' . basename(dirname(__DIR__)) . '/include/common.php';

/**
 * @param $cats
 * @return string
 */
function addCatSelect($cats)
{
    if (is_array($cats)) {
        $cat_sql = '(' . current($cats);
        array_shift($cats);
        foreach ($cats as $cat) {
            $cat_sql .= ',' . $cat;
        }
        $cat_sql .= ')';
    }

    return $cat_sql;
}

/**
 * @param $options
 * @return array
 */
function b_tdmpicture($options)
{
    global $xoopsConfig, $xoopsModule, $xoTheme, $xoopsTpl;
    $moduleDirName = basename(dirname(__DIR__));

    if (isset($xoTheme) && is_object($xoTheme)) {
        $xoTheme->addStylesheet(TDMPICTURE_URL . '/css/tdmpicture.css');
        $xoTheme->addScript(TDMPICTURE_URL . '/assets/js/jquery-1.4.4.js');
        $xoTheme->addScript(TDMPICTURE_URL . '/assets/js/AudioPlayer.js');
    }

    xoops_loadLanguage('main', $moduleDirName);

    $blocks = array();
    $block  = array();
    // 1, _MB_TDMPICTURE_DISPLAY
    // 2, _MB_TDMPICTURE_TITLELENGTH
    // 3, _MB_TDMPICTURE_SLIDELENGTH
    // 4, _MB_TDMPICTURE_MINTHUMBWIDTH
    // 5, _MB_TDMPICTURE_MINTHUMBHEIGTH
    // 6, _AM_TDMPICTURE_SELECT_STYLE
    //7 categorie

    $type_block           = $options[0];
    $nb_document          = $options[1];
    $lenght_title         = $options[2];
    $block_slide          = $options[3];
    $block_minthumbwidth  = $options[4];
    $block_minthumbheigth = $options[5];
    $style_block          = $options[6];

    $myts         = MyTextSanitizer::getInstance();
    $fileHandler = xoops_getModuleHandler('tdmpicture_file', $moduleDirName);
    $catHandler  = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);

    if (!isset($GLOBALS['xoopsModuleConfig'])) {
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $xoopsModule       = $moduleHandler->getByDirname($moduleDirName);
        $configHandler    = xoops_getHandler('config');
        $GLOBALS['xoopsModuleConfig'] = $configHandler->getConfigList($xoopsModule->getVar('mid'));
    }

    switch ($type_block) {

        case 'file_indate':
            $block['block_url'] = "<a href='" . TDMPICTURE_URL . "/index.php?tris=file_indate'>" . _AM_TDMPICTURE_BLOCK_DATE . '</a>';
            break;
        case 'file_counts':
            $block['block_url'] = "<a href='" . TDMPICTURE_URL . "/index.php?tris=file_counts'>" . _AM_TDMPICTURE_BLOCK_COUNTS . '</a>';
            break;
        case 'file_hits':
            $block['block_url'] = "<a href='" . TDMPICTURE_URL . "/index.php?tris=file_hits'>" . _AM_TDMPICTURE_BLOCK_HITS . '</a>';
            break;
        case 'file_dl':
            $block['block_url'] = "<a href='" . TDMPICTURE_URL . "/index.php?tris=file_dl'>" . _AM_TDMPICTURE_BLOCK_DL . '</a>';
            break;
        case 'file_comments':
            $block['block_url'] = "<a href='" . TDMPICTURE_URL . "/index.php?tris=file_comments'>" . _AM_TDMPICTURE_BLOCK_COMMENTS . '</a>';
            break;
    }

    $criteria = new CriteriaCompo();
    $criteria->setLimit($nb_document);

    if (!empty($options[7])) {
        $criteria->add(new Criteria('file_cat', '(' . $options[7] . ')', 'IN'));
    }

    $criteria->add(new Criteria('file_display', 1));
    $criteria->setSort($type_block);
    $criteria->setOrder('DESC');
    $file_arr = $fileHandler->getall($criteria);
    $e        = 1;

    foreach (array_keys($file_arr) as $i) {

        //cherche le cat
        $cat                       = $catHandler->get($file_arr[$i]->getVar('file_cat'));
        $blocks[$i]['cat']         = $myts->displayTarea($cat->getVar('cat_title'));
        $blocks[$i]['file_cat_id'] = $file_arr[$i]->getVar('file_cat');

        //apelle lien image
        $file_path = $file_arr[$i]->getFilePath($file_arr[$i]->getVar('file_file'));

        //test image
        if (file_exists($file_path['image_path'])) {
            $blocks[$i]['img_popup'] = $file_path['image_url'];
        } else {
            $blocks[$i]['img_popup'] = TDMPICTURE_IMAGES_URL . '/blank.png';
        }

        //test thumb
        if (file_exists($file_path['thumb_path'])) {
            $blocks[$i]['img'] = $file_path['thumb_url'];
        } else {
            $blocks[$i]['img'] = TDMPICTURE_IMAGES_URL . '/blank.png';
        }

        $blocks[$i]['i']     = $e;
        $blocks[$i]['id']    = $file_arr[$i]->getVar('file_id');
        $title               = $myts->displayTarea((strlen($file_arr[$i]->getVar('file_title')) > $lenght_title ? substr($file_arr[$i]->getVar('file_title'), 0, $lenght_title) . '...' : $file_arr[$i]->getVar('file_title')));
        $blocks[$i]['title'] = $title;
        //$blocks[$i]['cat'] =  $file_arr[$i]->getVar('file_cat');
        $blocks[$i]['tris']       = $type_block;
        $blocks[$i]['votes']      = $file_arr[$i]->getVar('file_votes');
        $blocks[$i]['counts']     = $file_arr[$i]->getVar('file_counts');
        $blocks[$i]['hits']       = $file_arr[$i]->getVar('file_hits');
        $blocks[$i]['indate']     = formatTimestamp($file_arr[$i]->getVar('file_indate'), 'm');
        $blocks[$i]['postername'] = XoopsUser::getUnameFromId($file_arr[$i]->getVar('file_uid'));
        $blocks[$i]['uid']        = $file_arr[$i]->getVar('file_uid');
        $blocks[$i]['comments']   = $file_arr[$i]->getVar('file_comments');
        $blocks[$i]['name']       = $i;
        ++$e;
    }

    if ($style_block == 4) {
        $block['block_name'] = $i;
    }

    $block['block_style']     = $style_block;
    $block['block_slide']     = $block_slide;
    $block['block_minwidth']  = $block_minthumbwidth;
    $block['block_minheigth'] = $block_minthumbheigth;

    $block['blocks'] = $blocks;

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_tdmpicture_edit($options)
{
    $moduleDirName = basename(dirname(__DIR__));
    $catHandler = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);
    $criteria    = new CriteriaCompo();
    $criteria->add(new Criteria('cat_display', 1));
    $criteria->setSort('cat_title');
    $criteria->setOrder('ASC');
    $assoc_arr = $catHandler->getall($criteria);
    $form      = "<input type=\"hidden\" name=\"options[0]\" value=\"" . $options[0] . "\" />";

    $array = array(
        _AM_TDMPICTURE_SELECT_TEXT,
        _AM_TDMPICTURE_SELECT_IMAGE,
        _AM_TDMPICTURE_SELECT_IMAGE_DESC,
        _AM_TDMPICTURE_SELECT_SLIDE,
        _AM_TDMPICTURE_SELECT_MUR
    );

    $form .= tdmpicture_blocktext($options, 1, _MB_TDMPICTURE_DISPLAY, 5);
    $form .= tdmpicture_blocktext($options, 2, _MB_TDMPICTURE_TITLELENGTH, 5);
    $form .= tdmpicture_blocktext($options, 3, _MB_TDMPICTURE_SLIDELENGTH, 5);
    $form .= tdmpicture_blocktext($options, 4, _MB_TDMPICTURE_MINTHUMBWIDTH, 5);
    $form .= tdmpicture_blocktext($options, 5, _MB_TDMPICTURE_MINTHUMBHEIGTH, 5);

    $form .= tdmpicture_blockselect($options, 6, _AM_TDMPICTURE_SELECT_STYLE, $array);

    print_r($options);
    $selectedid = explode(',', $options[7]);
    $form .= _MB_TDMPICTURE_CATTODISPLAY . "<br><select name=\"options[7][]\" multiple=\"multiple\" size=\"5\">";
    $form .= "<option value=\"0\" " . (array_search(0, $selectedid) === false ? '' : 'selected') . '>' . _MB_TDMPICTURE_ALLCAT . '</option>';
    foreach (array_keys($assoc_arr) as $i) {
        $form .= "<option value=\"" . $assoc_arr[$i]->getVar('cat_id') . "\" " . (array_search($assoc_arr[$i]->getVar('cat_id'), $selectedid) === false ? '' : 'selected') . '>' . $assoc_arr[$i]->getVar('cat_title') . '</option>';
    }
    $form .= '</select><br>';

    return $form;
}

/**
 * @param $options
 * @param $number
 * @param $lang
 * @return string
 */
function tdmpicture_blockradio($options, $number, $lang)
{
    $radio = '&nbsp;' . lang . ": <input type='radio' name='options[{$number}]' value='" . $number . "'";
    if ($options[$number] == $number) {
        $radio .= ' checked';
    }
    $radio .= ' />&nbsp;<br>';

    return $radio;
}

/**
 * @param $options
 * @param $number
 * @param $lang
 * @param $array
 * @return string
 */
function tdmpicture_blockselect($options, $number, $lang, $array)
{
    $select = $lang . ': ';
    $select .= "<select name='options[{$number}]'>";
    foreach ($array as $key => $value) {
        $select .= "<option value=\"" . $key . "\" " . (($key == $options[$number]) === false ? '' : 'selected') . '>' . $value . '</option>';
    }

    $select .= '</select><br>';

    return $select;
}

/**
 * @param $options
 * @param $number
 * @param $lang
 * @param $size
 * @return string
 */
function tdmpicture_blocktext($options, $number, $lang, $size)
{
    $text = $lang . ": <input name='options[{$number}]' size='" . $size . "' maxlength=\"255\" value='" . $options[$number] . "' type=\"text\" /><br>";

    return $text;
}
