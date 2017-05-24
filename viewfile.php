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
 * @author      TDMFR ; TEAM DEV MODULE
 *
 * ****************************************************************************
 */

use Xmf\Request;

include_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'tdmpicture_viewfile.tpl';
require XOOPS_ROOT_PATH . '/header.php';
$moduleDirName = basename(__DIR__);
$xoopsTpl->assign('dirname', $moduleDirName);

// get User ID
//is_object($xoopsUser) ? $xd_uid = $xoopsUser->getVar('uid') : $xd_uid = -1;

$op      = Request::getVar('op', 'list'); //isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$tris    = Request::getString('tris', 'file_indate'); //isset($_REQUEST['tris']) ? $_REQUEST['tris'] : 'file_indate';
$order   = Request::getString('order', 'desc'); //isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';
$post_ct = Request::getString('ct', false); //isset($_REQUEST['ct']) ? $_REQUEST['ct'] : false;
$post_ut = Request::getString('ut', false); //isset($_REQUEST['ut']) ? $_REQUEST['ut'] : false;
$st      = Request::getString('st', false); //isset($_REQUEST['st']) ? $_REQUEST['st'] : false;

isset($_REQUEST['com_mode']) ? $op = 'detail' : '';

//load class
$fileHandler  = xoops_getModuleHandler('file', $moduleDirName);
$catHandler   = xoops_getModuleHandler('category', $moduleDirName);
$gpermHandler = xoops_getHandler('groupperm');

$myts = MyTextSanitizer::getInstance();

global $XoopsUser, $xoopsModule;

//perm
$xoopsTpl->assign('perm_submit', $perm_submit);
$xoopsTpl->assign('perm_vote', $perm_vote);
$xoopsTpl->assign('perm_playlist', $perm_playlist);
$xoopsTpl->assign('perm_dl', $perm_dl);
$xoopsTpl->assign('perm_cat', $perm_cat);

$xoopsTpl->assign('thumb_width', $moduleHelper->getConfig('tdmpicture_thumb_width') . 'px');
$xoopsTpl->assign('thumb_heigth', $moduleHelper->getConfig('tdmpicture_thumb_heigth') . 'px');

switch ($op) {

    case 'list':
    default:
        //navigation Alpha
        $xoopsTpl->assign('comment_view', true);
        // ************************************************************
        // Liste
        // ************************************************************

        //securiter si aucun n'est choisis
        if (empty($st)) {
            redirect_header('index.php', 2, _MD_TDMPICTURE_NOPERM);
        }

        //trouve le fichier
        $file = $fileHandler->get($st);
        $ct   = $file->getVar('file_cat');
        $ut   = $file->getVar('file_uid');

        if (!empty($post_ut)) {
            $GLOBALS['navuser'] = "<a href='user.php?ut=" . $ut . "'>" . XoopsUser::getUnameFromId($ut) . '</a>';
        } else {
            $GLOBALS['navuser'] = false;
        }

        // ************************************************************
        // Liste des Categories
        // ************************************************************

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_display', 1));



        $cat_arr = $catHandler->getall($criteria);
        $mytree = new TDMObjectTree($cat_arr, 'cat_id', 'cat_pid');
//        asigne les URL
        define('TDM_CAT_URL', TDMPICTURE_CAT_URL);
        define('TDM_CAT_PATH', TDMPICTURE_CAT_PATH);
        $GLOBALS['scat_display'] = false;
        $cat_display = $moduleHelper->getConfig('tdmpicture_cat_display');
        $cat_cel = $moduleHelper->getConfig('tdmpicture_cat_cel');
        $display_cat = $mytree->makeCatBox($fileHandler, 'cat_title','-', $ct);
        $xoopsTpl->assign('display_cat', $display_cat);

//        news categorie
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('cat_display', 1));
        $criteria->add(new Criteria('cat_index', 1));



        $criteria->setSort('cat_weight');
        $criteria->setOrder('DESC');
        //$criteria->add(new Criteria('cat_display', 1));
        $cat_arr     = $catHandler->getall($criteria);
        $mytree      = new TdmCatObjectTree($cat_arr, 'cat_id', 'cat_pid');
        $display_cat = $mytree->makeCatBox($fileHandler, 'cat_title', '-', $ct);
        $numcat      = $catHandler->getCount($criteria);
        $xoopsTpl->assign('tree_display', $display_cat);
        //  $xoopsTpl->assign('tree_title', $GLOBALS['cat_title']);
        //

        //navigation
        $navigation = '';
        $xoopsTpl->assign('cat_view', true);
        //$xoopsTpl->assign('selectcat', TdmpictureUtility::selectCat($mytree, (int)($ct)));
        //$xoopsTpl->assign('selecttris', tdmpicture_trisselect((int)($ct), $tris));
        //$xoopsTpl->assign('selectview', TdmpictureUtility::selectView((int)($ct), $limit));
        $meta_title = $meta_keywords = $meta_description = $GLOBALS['cat_title'];

        //$xoopsTpl->assign('nav_bar', $GLOBALS['navbar']);

        unset($criteria);

        //navigation categorie ou user
        $criteria3 = new CriteriaCompo();
        $criteria3->add(new Criteria('file_display', 1));
        if (!empty($post_ut)) {
            $criteria3->add(new Criteria('file_uid', $ut));
        } else {
            $criteria3->add(new Criteria('file_cat', $ct));
        }
        $criteria3->setSort($tris);
        $criteria3->setOrder($order);
        $arr = $fileHandler->getObjects($criteria3);
        //$array_ids = array() ;
        //$files = array() ;
        foreach (array_keys($arr) as $f) {
            if ($gpermHandler->checkRight('tdmpicture_catview', $arr[$f]->getVar('file_cat'), $groups, $xoopsModule->getVar('mid'))) {
                $array_ids[]    = $arr[$f]->getVar('file_id');
                $array_titles[] = $arr[$f]->getVar('file_title');
                $array_files[]  = $arr[$f]->getVar('file_file');

                //apelle lien image
                $file_path = $arr[$f]->getFilePath($arr[$f]->getVar('file_file'));
                //test thumb
                if (file_exists($file_path['thumb_path'])) {
                    $thumb      = $file_path['thumb_url'];
                    $thumb_path = $file_path['thumb_path'];
                } else {
                    $thumb      = TDMPICTURE_IMAGES_URL . '/blank.png';
                    $thumb_path = TDMPICTURE_IMAGES_PATH . '/blank.png';
                }

                $array_thumbs[]      = $thumb;
                $array_thumbs_path[] = $thumb_path;
                $array_uts[]         = $arr[$f]->getVar('file_uid');
                $array_cats[]        = $arr[$f]->getVar('file_cat');
            }
        }
        $numrows = count($array_ids) - 1;
        $pos     = array_search($st, $array_ids);

        $nav_page = '';
        if ($pos != 0) {
            if (file_exists($array_thumbs_path[$pos - 1])) {
                $previmg = '<img src=' . $array_thumbs[$pos - 1] . " class='detail_img'>";
                if (!empty($post_ut)) {
                    $prev_page = "<a title='" . $array_titles[$pos - 1] . "' href='viewfile.php?st=" . $array_ids[$pos - 1] . '&ut=' . $array_uts[$pos - 1] . '&tris=' . $tris . '&order=' . $order . "'>" . $previmg . '</a>';
                } else {
                    $prev_page = "<a title='" . $array_titles[$pos - 1] . "' href='viewfile.php?st=" . $array_ids[$pos - 1] . '&ct=' . $array_cats[$pos - 1] . '&tris=' . $tris . '&order=' . $order . "'>" . $previmg . '</a>';
                }
                $xoopsTpl->assign('prev_page', $prev_page);
            }
        }

        if ($pos != $numrows) {
            if (file_exists($array_thumbs_path[$pos + 1])) {
                $nextimg = '<img src=' . $array_thumbs[$pos + 1] . " class='detail_img'>";
                if (!empty($post_ut)) {
                    $next_page = "<a title='" . $array_titles[$pos + 1] . "' href='viewfile.php?st=" . $array_ids[$pos + 1] . '&ut=' . $array_uts[$pos + 1] . '&tris=' . $tris . '&order=' . $order . "'>" . $nextimg . '</a>';
                } else {
                    $next_page = "<a title='" . $array_titles[$pos + 1] . "' href='viewfile.php?st=" . $array_ids[$pos + 1] . '&ct=' . $array_cats[$pos + 1] . '&tris=' . $tris . '&order=' . $order . "'>" . $nextimg . '</a>';
                }
                $xoopsTpl->assign('next_page', $next_page);
            }
        }
        //

        //Fichier
        $criteria2 = new CriteriaCompo();
        $criteria2->add(new Criteria('file_display', 1));
        $criteria2->add(new Criteria('file_id', $st));
        $criteria2->setLimit(1);
        $file_arr = $fileHandler->getObjects($criteria2);
        $numfile  = $fileHandler->getCount($criteria2);
        $xoopsTpl->assign('numfile', $numfile);
        $file  = array();
        $files = array();

        foreach (array_keys($file_arr) as $f) {

            //si pas le droit d'afficher la cat
            if (!$gpermHandler->checkRight('tdmpicture_catview', $file_arr[$f]->getVar('file_cat'), $groups, $xoopsModule->getVar('mid'))) {
                redirect_header('index.php', 2, _MD_TDMPICTURE_NOPERM);
            }
            //cherche le cat
            $cat                 = $catHandler->get($file_arr[$f]->getVar('file_cat'));
            $file['cat']         = $myts->displayTarea($cat->getVar('cat_title'));
            $file['file_cat_id'] = $file_arr[$f]->getVar('file_cat');
            $file_path           = $file_arr[$f]->getFilePath()['image_path'] . $file_arr[$f]->getVar('file_file');
            //affiche les liens
            $form = $file_arr[$f]->getFormlink();

            $xoopsTpl->assign('getlink', $form->render());

            //apelle lien image
            $file_path = $file_arr[$f]->getFilePath($file_arr[$f]->getVar('file_file'));

            //test image
            if (file_exists($file_path['image_path'])) {
                $file['img_popup'] = $file_path['image_url'];
                $file['img']       = $file_path['image_url'];
            } else {
                $file['img_popup'] = TDMPICTURE_IMAGES_URL . '/blank.png';
                $file['img']       = TDMPICTURE_IMAGES_URL . '/blank.png';
            }

            //met ajout le nombre d'affichage
            $hits = $file_arr[$f]->getVar('file_hits');
            ++$hits;
            $file_arr[$f]->setVar('file_hits', $hits);
            $fileHandler->insert($file_arr[$f]);

            //

            $file['id']    = $file_arr[$f]->getVar('file_id');
            $meta_title    .= ' : ' . $file_arr[$f]->getVar('file_title');
            $file['title'] = $myts->displayTarea($file_arr[$f]->getVar('file_title'));
            $xoopsTpl->assign('tree_title', $file['title']);
            $file['type']   = $file_arr[$f]->getVar('file_type');
            $file['hits']   = $file_arr[$f]->getVar('file_hits');
            $file['dl']     = $file_arr[$f]->getVar('file_dl');
            $file['size']   = TdmpictureUtility::getPrettySize($file_arr[$f]->getVar('file_size'));
            $file['with']   = $file_arr[$f]->getVar('file_res_x');
            $file['height'] = $file_arr[$f]->getVar('file_res_y');

            //poster
            $poster       = new XoopsUser($file_arr[$f]->getVar('file_uid'));
            $poster_image = XOOPS_ROOT_PATH . '/uploads/' . $poster->getVar('user_avatar');
            if (file_exists($poster_image) && $poster->getVar('user_avatar') != ''
                && $poster->getVar('user_avatar') !== 'blank.png'
            ) {
                $file['userimg'] = "<img class='img'src='" . XOOPS_URL . '/uploads/' . $poster->getVar('user_avatar') . "' height='60px' title=" . $poster->getVar('uname'). " style='border: 1px solid #CCC;' alt=" . $poster->getVar('uname') . '>';
            } else {
                $file['userimg'] = "<img class='img' src='" . TDMPICTURE_IMAGES_URL . "/user.gif'  height='60px' style='border: 1px solid #CCC' title='Anonyme' alt='Anonyme'>";
            }
            //
            $file['postername'] = XoopsUser::getUnameFromId($file_arr[$f]->getVar('file_uid'));
            $file['uid']        = $file_arr[$f]->getVar('file_uid');
            //test si l'user a un album
            $file['useralb'] = TdmpictureUtility::getUserAlbum($file_arr[$f]->getVar('file_uid'));
            //
            if ($xd_uid == $file_arr[$f]->getVar('file_uid')) {
                $xoopsTpl->assign('file_edit', true);
            }
            $meta_desc        = $file_arr[$f]->getVar('file_text');
            $file['text']     = $file_arr[$f]->getVar('file_text');
            $file['indate']   = formatTimestamp($file_arr[$f]->getVar('file_indate'), 'S');
            $file['votes']    = $file_arr[$f]->getVar('file_votes');
            $file['counts']   = $file_arr[$f]->getVar('file_counts');
            $file['comments'] = $file_arr[$f]->getVar('file_comments');

            //moyen des vote
            //@$moyen = ceil( $file['votes']/ $file['counts'] );
            //if (@$moyen == 0) {
            //$file['moyen'] = "";
            //} else {
            //echo $file['moyen'] = "<img src='".TDMPICTURE_IMAGES_URL."rate".$moyen.".png'/>";
            //}
            //favorie
            if ($file['counts'] >= $moduleHelper->getConfig('tdmpicture_favourite')) {
                $file['favourite'] = "<img src='" . TDMPICTURE_IMAGES_URL . "/flag.png'/>";
            } else {
                $file['favourite'] = '';
            }

            if (!empty($xoopsUser)) {
                if ($xoopsUser->getVar('uid') == $file_arr[$f]->getVar('file_uid') || $xoopsUser->isAdmin()) {
                    if (!$gpermHandler->checkRight('tdmpicture_view', 128, $groups, $xoopsModule->getVar('mid'))) {
                        $file['menu'] = false;
                        $file['edit'] = false;
                    } else {
                        $file['menu'] = true;
                        $file['edit'] = true;
                    }

                    if (!$gpermHandler->checkRight('tdmpicture_view', 512, $groups, $xoopsModule->getVar('mid'))) {
                        $file['menu'] = false;
                        $file['del']  = false;
                    } else {
                        $file['menu'] = true;
                        $file['del']  = true;
                    }
                }
            }

            $xoopsTpl->append('file', $file);
        }

        break;

}
TdmpictureUtility::getHeader();
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name() . ' : ' . $meta_title));

if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'keywords', TdmpictureUtility::getKeywords($meta_desc));
    $xoTheme->addMeta('meta', 'description', $meta_desc);
} else {    // Compatibility for old Xoops versions
    $xoopsTpl->assign('xoops_meta_keywords', TdmpictureUtility::getKeywords($moduleHelper->getConfig('tdmpicture_keywords')));
    $xoopsTpl->assign('xoops_meta_description', $moduleHelper->getConfig('tdmpicture_description'));
}
//fonction commentaire
include XOOPS_ROOT_PATH . '/include/comment_view.php';
//
include XOOPS_ROOT_PATH . '/footer.php';
