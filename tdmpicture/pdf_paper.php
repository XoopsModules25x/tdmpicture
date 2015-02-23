<?php
// $Id: pdf_paper.php 10929 2013-01-27 15:42:57Z lord_venom $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //


error_reporting(0);

include_once "header.php";
include_once XOOPS_ROOT_PATH.'/header.php';
$myts =& MyTextSanitizer::getInstance();
require './fpdf/fpdf.php';

global $xoopsDB, $xoopsConfig, $xoopsModuleConfig;

	if (empty($_REQUEST['st'])) {
	redirect_header('index.php', 2, _MD_TDMPICTURE_NOPERM);
	exit();
    }
	
  if( file_exists(TDMPICTURE_ROOT_PATH."/language/".$xoopsConfig['language']."/admin.php") ) {
	 include_once(TDMPICTURE_ROOT_PATH."/language/".$xoopsConfig['language']."/admin.php");
  } else {
	 include_once(TDMPICTURE_ROOT_PATH."/language/english/admin.php");
  }
$myts = & MyTextSanitizer :: getInstance(); // MyTextSanitizer object

$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';

		//Text generale
		$pdf_data['member_name'] = Chars(_AM_TDMASSOC_MEMBER_FORM_NAME);
		$pdf_data['member_firstname'] = Chars(_AM_TDMASSOC_MEMBER_FORM_FISRTNAME);
		$pdf_data['member_adress'] = Chars(_AM_TDMASSOC_MEMBER_FORM_ADRESS);
		$pdf_data['member_zipcode'] = Chars(_AM_TDMASSOC_MEMBER_PDF_ZIPCODE);
		$pdf_data['member_town'] = Chars(_AM_TDMASSOC_MEMBER_FORM_TOWN);
		$pdf_data['member_phone'] =Chars( _AM_TDMASSOC_MEMBER_FORM_PHONE);
		$pdf_data['member_registration_start'] = Chars(_AM_TDMASSOC_MEMBER_FORM_REGISTRATION_START);
		$pdf_data['member_registration_end'] = Chars(_AM_TDMASSOC_MEMBER_FORM_REGISTRATION_END);
		$pdf_data['status_name'] = Chars(_AM_TDMASSOC_MEMBER_FORM_STATUS);
		
		//Composition de l'association
		$pdf_data['composition_title'] = Chars(_AM_TDMASSOC_MEMBER_PDF_COMPOSITION_TITLE);
		$pdf_data['composition_list_admin'] = Chars(_AM_TDMASSOC_MEMBER_PDF_COMPOSITION_LIST_ADMIN_TITLE);
		$pdf_data['composition_list_office'] = Chars(_AM_TDMASSOC_MEMBER_PDF_COMPOSITION_LIST_OFFICE_TITLE);
		
		//Liste d'administrateurs
		$pdf_data['list_admin_title'] = Chars(_AM_TDMASSOC_MEMBER_PDF_LISTADMIN_TITLE);
		
		
switch( $option )
{
   default:
   	redirect_header("javascript:history.go(-1)",2, _PM_RMEDNON);
   break;
	
//pdf pour l'affichage full
   	case "auto":
//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

$file =& $file_handler->get($_REQUEST['st']);
$file_path = $file->getFilePath($file->getVar('file_file'));

$newsletter_text = utf8_decode(Chars($file->getVar('file_text')));
$newsletter_indate = formatTimeStamp($file->getVar("file_indate"),"m");
$color = '#CCCCCC';

$h = $file->getVar('file_res_y');
$w = $file->getVar('file_res_x');
//tien dans 1 A4 210 × 297
if ($h <= 297 || $w <= 210) {
$wt = 210;
if ($h > 210) {
$pdf=new FPDF('P','mm','A4');
} else {
$pdf=new FPDF('L','mm','A4');
}

}
//
//tien dans 1 A3 297 × 420
elseif ($h <= 420 || $w <= 297) {
$wt = 297;
if ($h > 297) {
$pdf=new FPDF('P','mm','A3');
} else {
$pdf=new FPDF('L','mm','A3');
}

}
//
//tien dans 1 A2 420 × 594
elseif ($h <= 594 || $w <= 420) {
$wt = 420;
if ($h > 420) {
$pdf=new FPDF('P','mm','A2');
} else {
$pdf=new FPDF('L','mm','A2');
}

}
//tien dans 1 A1 594 × 841
elseif ($h <= 841 || $w <= 594) {
$wt = 594;
if ($h > 594) {
$pdf=new FPDF('P','mm','A1');
} else {
$pdf=new FPDF('L','mm','A1');
}

}
//tien dans 1 A0 841 × 1189
elseif ($h <= 1189 || $w <= 841) {
$wt = 841;
if ($h > 841) {
$pdf=new FPDF('P','mm','A1');
} else {
$pdf=new FPDF('L','mm','A1');
}

}
//tien dans 1 2A0 1189 × 1682
elseif ($h <= 1682 || $w <= 1189) {
$wt = 1189;
if ($h > 1189) {
$pdf=new FPDF('P','mm','A1');
} else {
$pdf=new FPDF('L','mm','A1');
}

}

//tien dans 1 4A0 1682 × 2378
elseif ($h <= 2378 || $w <= 1682) {
$wt = 1682;
if ($h > 1682) {
$pdf=new FPDF('P','mm','A1');
} else {
$pdf=new FPDF('L','mm','A1');
}

}else {
redirect_header("javascript:history.go(-1)",2, _MD_TDMPICTURE_PDFNONE);
	exit();
	}
//
//
$pdf->AddPage();
//titre
$absx=($wt-$w)/2;
$pdf->Image($file_path['image_path'], $absx, 0,$w);		
$pdf->Output();

  	break;

 case "A4":
//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

$file =& $file_handler->get($_REQUEST['st']);
$file_path = $file->getFilePath($file->getVar('file_file'));

$newsletter_text = utf8_decode(Chars($file->getVar('file_text')));
$newsletter_indate = formatTimeStamp($file->getVar("file_indate"),"m");
$color = '#CCCCCC';

$h = $file->getVar('file_res_y');
$w = $file->getVar('file_res_x');
//tien dans 1 A4 210 × 297
if ($h > 210) {
$pdf=new FPDF('P','mm','A4');
} else {
$pdf=new FPDF('L','mm','A4');
}
//
//
$pdf->AddPage();
//titre
$size = '210';
$absx=(210-$size)/2;
$pdf->Image($file_path['image_path'], $absx, 0,$size);		
$pdf->Output();

  	break;
	
case "A3":
//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

$file =& $file_handler->get($_REQUEST['st']);
$file_path = $file->getFilePath($file->getVar('file_file'));

$newsletter_text = utf8_decode(Chars($file->getVar('file_text')));
$newsletter_indate = formatTimeStamp($file->getVar("file_indate"),"m");
$color = '#CCCCCC';

$h = $file->getVar('file_res_y');
$w = $file->getVar('file_res_x');

if ($h > 297) {
$pdf=new FPDF('P','mm','A3');
} else {
$pdf=new FPDF('L','mm','A3');
}

//
$pdf->AddPage();
//titre
$size = '297';
$absx=(297-$size)/2;
$pdf->Image($file_path['image_path'], $absx, 0,$size);		
$pdf->Output();

  	break;
	
case "A2":
//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

$file =& $file_handler->get($_REQUEST['st']);
$file_path = $file->getFilePath($file->getVar('file_file'));

$newsletter_text = utf8_decode(Chars($file->getVar('file_text')));
$newsletter_indate = formatTimeStamp($file->getVar("file_indate"),"m");
$color = '#CCCCCC';

$h = $file->getVar('file_res_y');
$w = $file->getVar('file_res_x');


if ($h > 420) {
$pdf=new FPDF('P','mm','A2');
} else {
$pdf=new FPDF('L','mm','A2');
}

//
$pdf->AddPage();
//titre
$size = '420';
$absx=(420-$size)/2;
$pdf->Image($file_path['image_path'], $absx, 0,$size);		
$pdf->Output();

  	break;
	
case "A1":
//load class
$file_handler =& xoops_getModuleHandler('tdmpicture_file', 'TDMPicture');
$cat_handler =& xoops_getModuleHandler('tdmpicture_cat', 'TDMPicture');

$file =& $file_handler->get($_REQUEST['st']);
$file_path = $file->getFilePath($file->getVar('file_file'));

$newsletter_text = utf8_decode(Chars($file->getVar('file_text')));
$newsletter_indate = formatTimeStamp($file->getVar("file_indate"),"m");
$color = '#CCCCCC';

$h = $file->getVar('file_res_y');
$w = $file->getVar('file_res_x');

if ($h > 594) {
$pdf=new FPDF('P','mm','A1');
} else {
$pdf=new FPDF('L','mm','A1');
}
//
//
$pdf->AddPage();
//titre
$size = '594';
$absx=(594-$size)/2;
$pdf->Image($file_path['image_path'], $absx, 0,$size);		
$pdf->Output();

  	break;
	
}
//



    function Chars($text)
    {
	$myts = & MyTextSanitizer :: getInstance(); 
        return preg_replace(
                            array( "/&#039;/i", "/&#233;/i", "/&#232;/i", "/&#224;/i", "/&quot;/i", '/<br \/>/i', "/&agrave;/i", "/&#8364;/i"),
                            array( "'", "é", "è", "à", '"', "\n", "à", "€"),
                           $text);
    }


?>