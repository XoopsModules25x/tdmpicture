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
 
include_once "header.php";
include_once XOOPS_ROOT_PATH.'/header.php';
$myts =& MyTextSanitizer::getInstance();
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');

 $post_st = isset($_REQUEST['st']) ? $_REQUEST['st'] : false;
 $post_size = isset($_REQUEST['size']) ? $_REQUEST['size'] : false;
 
 
    if (empty($post_st)) {
     redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
        exit();
    }

$document = $file_handler->get($post_st);

if (!$document) {
    redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
    exit();
}

//apelle lien image
$filePaths = $document->getFilePath($document->getVar('file_file'));

if ($post_size == "full") {
    $imagePath = $filePaths['image_path'];
} else {
    $imagePath = $filePaths['thumb_path'];
}

//on test l'existance de l'image
if (file_exists($imagePath)) {
    //$document_file = TDMPICTURE_UPLOADS_URL.$document->getVar("file_file");
//    $document_file = $filePath2;
    if ($post_size == "full") {
        $imageUrl = $filePaths['image_url'];
    } else {
        $imageUrl = $filePaths['thumb_url'];
    }

} else {
    redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
    exit();
}

$dl = $document->getVar('file_hits');
$dl++;
$document->setVar('file_hits', $dl);
$file_handler->insert($document);

echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=".$imageUrl."\"></meta></head><body></body></html>";
