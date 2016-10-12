<?php
/**
 * ****************************************************************************
 *  - TDMSpot By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - Licence PRO Copyright (c)  (http://www.)
 *
 * Cette licence, contient des limitations
 *
 * 1. Vous devez posséder une permission d'exécuter le logiciel, pour n'importe quel usage.
 * 2. Vous ne devez pas l' étudier ni l'adapter à vos besoins,
 * 3. Vous ne devez le redistribuer ni en faire des copies,
 * 4. Vous n'avez pas la liberté de l'améliorer ni de rendre publiques les modifications
 *
 * @license     TDMFR GNU public license
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */
include __DIR__ . '/../../mainfile.php';
include_once $GLOBALS['xoops']->path('class/template.php');

$fileHandler = xoops_getModuleHandler('tdmpicture_file', $moduleDirName);
$catHandler  = xoops_getModuleHandler('tdmpicture_cat', $moduleDirName);

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

header('Content-Type:text/xml; charset=utf-8');

$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);

if (!$tpl->is_cached('db:tdmpicture_rss.tpl')) {
    xoops_load('XoopsLocal');
    $tpl->assign('channel_title', XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
    $tpl->assign('channel_link', XOOPS_URL . '/');
    $tpl->assign('channel_desc', XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true));
    $tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true));
    $tpl->assign('channel_category', 'News');
    $tpl->assign('channel_generator', 'XOOPS');
    $tpl->assign('channel_language', _LANGCODE);
    $tpl->assign('image_url', XOOPS_URL . '/images/logo.png');
    $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.png');
    if (empty($dimention[0])) {
        $width = 88;
    } else {
        $width = ($dimention[0] > 144) ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);

    //cherche les news
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('file_display', 1));
    $criteria->setSort('file_indate');
    $criteria->setOrder('ASC');
    $item_arr = $fileHandler->getall($criteria);
    $tpitem   = array();
    foreach (array_keys($item_arr) as $i) {
        $tpitem['id']    = $item_arr[$i]->getVar('file_id');
        $tpitem['title'] = $item_arr[$i]->getVar('file_title');
        $tpitem['cat']   = $item_arr[$i]->getVar('file_cat');
        //trouve la categorie
        if ($cat = $catHandler->get($item_arr[$i]->getVar('file_cat'))) {
            $tpitem['cat_title'] = $cat->getVar('cat_title');
            $tpitem['cat_id']    = $cat->getVar('cat_id');
        }

        $tpitem['text'] = $item_arr[$i]->getVar('file_text');

        $tpitem['indate'] = formatTimestamp($item_arr[$i]->getVar('file_indate'), 'm');
        $tpitem['link']   = XOOPS_URL . '/modules/TDMPicture/viewfile.php?st=' . $item_arr[$i]->getVar('file_id') . '&amp;ct=' . $item_arr[$i]->getVar('file_cat');
        $tpitem['guid']   = XOOPS_URL . '/modules/TDMPicture/viewfile.php?st=' . $item_arr[$i]->getVar('file_id') . '&amp;ct=' . $item_arr[$i]->getVar('file_cat');

        $tpl->append('tpitem', $tpitem);
    }
}
$tpl->display('db:tdmpicture_rss.tpl');
