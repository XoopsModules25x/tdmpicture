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

	if (empty($_REQUEST['st'])) {
	 redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
	 	exit();
    }

	if (!$perm_dl) {
	 redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
	 	exit();
    }

$document = $file_handler->get($_REQUEST['st']);

if (!$document){
 redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
	exit();
}
		//$file_path = $document->getFilePath().$document->getVar("file_file");
			//apelle lien image
		$file_path = $document->getFilePath($document->getVar('file_file'));
		//on test l'existance de l'image
		//$imgpath = XOOPS_ROOT_PATH.$file_path;
		if (file_exists($file_path['image_path'])) {
		//$document_file = TDMPICTURE_UPLOADS_URL.$document->getVar("file_file");
		$document_file = $file_path['image_url'];
		} else {
		 redirect_header('index.php', 2, _AM_TDMPICTURE_BASEERROR);
	exit();
		}


$dl = $document->getVar('file_dl');
$dl++;
$document->setVar('file_dl', $dl);
$file_handler->insert($document);

//header('Cache-Control: no-cache, must-revalidate');
//header('Pragma: no-cache');
//header('Content-Type: application/force-download');
//header ('Content-Disposition: attachment; filename='.$document_file.'');
//header('Content-length: '.$document->getVar("file_size"));
  header('Content-Type: application/force-download');
  header('Content-Disposition: attachment; filename='.basename($document_file));
  readfile($document_file);
		
		
//echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=".$document_file."\"></meta></head><body></body></html>";
?>