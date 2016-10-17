<?php

use Xmf\Module\Helper;

//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
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
// URL: http://www.myweb.ne.jp/, http://xoops.org/, http://jp.xoops.org/ //
// Project: XOOPS Project                                                    //
// ------------------------------------------------------------------------- //

error_reporting(0);

include_once __DIR__ . '/../../../mainfile.php';
include_once __DIR__ . '/../../../include/cp_header.php';
//require __DIR__ . '/../fpdf/fpdf.php';
require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';
//include_once XOOPS_ROOT_PATH.'/modules/tdmpicture/class/tdmassoc_pdf_table.php';
include_once XOOPS_ROOT_PATH . '/modules/tdmpicture/class/utilities.php';

global $xoopsDB, $xoopsConfig;

$moduleDirName = basename(dirname(__DIR__));
$moduleHelper  = Helper::getHelper($moduleDirName);

if (file_exists(XOOPS_ROOT_PATH . '/modules/tdmpicture/language/' . $xoopsConfig['language'] . '/admin.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/tdmpicture/language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/tdmpicture/language/english/admin.php';
}
$myts = MyTextSanitizer:: getInstance(); // MyTextSanitizer object

$option = !empty($_REQUEST['option']) ? $_REQUEST['option'] : 'default';

//Text generale
$pdf_data['member_name']               = Chars(_AM_TDMPICTURE_MEMBER_FORM_NAME);
$pdf_data['member_firstname']          = Chars(_AM_TDMPICTURE_MEMBER_FORM_FISRTNAME);
$pdf_data['member_adress']             = Chars(_AM_TDMPICTURE_MEMBER_FORM_ADRESS);
$pdf_data['member_zipcode']            = Chars(_AM_TDMPICTURE_MEMBER_PDF_ZIPCODE);
$pdf_data['member_town']               = Chars(_AM_TDMPICTURE_MEMBER_FORM_TOWN);
$pdf_data['member_phone']              = Chars(_AM_TDMPICTURE_MEMBER_FORM_PHONE);
$pdf_data['member_registration_start'] = Chars(_AM_TDMPICTURE_MEMBER_FORM_REGISTRATION_START);
$pdf_data['member_registration_end']   = Chars(_AM_TDMPICTURE_MEMBER_FORM_REGISTRATION_END);
$pdf_data['status_name']               = Chars(_AM_TDMPICTURE_MEMBER_FORM_STATUS);

//Composition de l'association
$pdf_data['composition_title']       = Chars(_AM_TDMPICTURE_MEMBER_PDF_COMPOSITION_TITLE);
$pdf_data['composition_list_admin']  = Chars(_AM_TDMPICTURE_MEMBER_PDF_COMPOSITION_LIST_ADMIN_TITLE);
$pdf_data['composition_list_office'] = Chars(_AM_TDMPICTURE_MEMBER_PDF_COMPOSITION_LIST_OFFICE_TITLE);

//Liste d'administrateurs
$pdf_data['list_admin_title'] = Chars(_AM_TDMPICTURE_MEMBER_PDF_LISTADMIN_TITLE);

switch ($option) {
    default:
        redirect_header('javascript:history.go(-1)', 2, _PM_REDNON);
        break;

    case 'list_office_and_admin':

        $pdf = new PDF();
        $pdf->Open();

        $titre = $pdf_data['composition_title'];
        //$pdf->SetTitle($pdf_data['title']);

        $pdf->AddPage();

        $pdf->TitreChapitre($myts->displayTarea($pdf_data['composition_list_office']));

        $pdf->AddCol('member_name', 20, '' . $pdf_data['member_name'], 'L');
        $pdf->AddCol('member_firstname', 30, $pdf_data['member_firstname'], 'L');
        $pdf->AddCol('member_adress', 60, $pdf_data['member_adress'], 'L');
        $pdf->AddCol('member_zipcode', 13, $pdf_data['member_zipcode'], 'L');
        $pdf->AddCol('member_town', 25, $pdf_data['member_town'], 'L');
        $pdf->AddCol('member_phone', 24, $pdf_data['member_phone'], 'L');
        $pdf->AddCol('status_name', 32, $pdf_data['status_name'], 'L');
        $prop = array(
            'HeaderColor' => array(
                255,
                150,
                100
            ),
            'color1'      => array(
                210,
                245,
                255
            ),
            'color2'      => array(
                255,
                255,
                210
            ),
            'padding'     => 2
        );

        $pdf->Table('SELECT M.member_name, M.member_firstname, M.member_adress, M.member_zipcode, M.member_town, M.member_phone, S.status_name FROM '
                    . $xoopsDB->prefix('tdmassoc_member') . ' M, ' . $xoopsDB->prefix('tdmassoc_status')
                    . ' S WHERE S.status_id=M.member_status AND M.member_office = "1" ORDER BY S.status_order limit 0,10', $prop);

        $pdf->TitreChapitre($myts->displayTarea($pdf_data['composition_list_admin']));

        $pdf->AddCol('member_name', 20, '' . $pdf_data['member_name'], 'L');
        $pdf->AddCol('member_firstname', 30, $pdf_data['member_firstname'], 'L');
        $pdf->AddCol('member_adress', 60, $pdf_data['member_adress'], 'L');
        $pdf->AddCol('member_zipcode', 13, $pdf_data['member_zipcode'], 'L');
        $pdf->AddCol('member_town', 25, $pdf_data['member_town'], 'L');
        $pdf->AddCol('member_phone', 24, $pdf_data['member_phone'], 'L');
        $pdf->AddCol('status_name', 32, $pdf_data['status_name'], 'L');
        $prop = array(
            'HeaderColor' => array(
                255,
                150,
                100
            ),
            'color1'      => array(
                210,
                245,
                255
            ),
            'color2'      => array(
                255,
                255,
                210
            ),
            'padding'     => 2
        );

        /*$criteria = new CriteriaCompo();
        $criteria->add(new Criteria('member_waiting', 1,'='));
        $criteria->setOrder('ASC');
        $assoc_arr = $memberHandler->getObjects($criteria);*/
        $pdf->Table('SELECT M.member_name, M.member_firstname, M.member_adress, M.member_zipcode, M.member_town, M.member_phone, S.status_name FROM '
                    . $xoopsDB->prefix('tdmassoc_member') . ' M, ' . $xoopsDB->prefix('tdmassoc_status')
                    . ' S WHERE S.status_id=M.member_status AND M.member_office = "0" ORDER BY S.status_order limit 0,10', $prop);
        $pdf->Output();

        break;

    case 'list_admin':

        $pdf = new PDF();
        $pdf->Open();

        $titre = $pdf_data['list_admin_title'];
        //$pdf->SetTitle($pdf_data['title']);

        $pdf->AddPage();

        $pdf->AddCol('member_name', 20, '' . $pdf_data['member_name'], 'L');
        $pdf->AddCol('member_firstname', 30, $pdf_data['member_firstname'], 'L');
        $pdf->AddCol('member_adress', 60, $pdf_data['member_adress'], 'L');
        $pdf->AddCol('member_zipcode', 13, $pdf_data['member_zipcode'], 'L');
        $pdf->AddCol('member_town', 25, $pdf_data['member_town'], 'L');
        $pdf->AddCol('member_phone', 24, $pdf_data['member_phone'], 'L');
        $pdf->AddCol('status_name', 32, $pdf_data['status_name'], 'L');
        $prop = array(
            'HeaderColor' => array(
                255,
                150,
                100
            ),
            'color1'      => array(
                210,
                245,
                255
            ),
            'color2'      => array(
                255,
                255,
                210
            ),
            'padding'     => 1
        );

        $pdf->Table('SELECT M.member_name, M.member_firstname, M.member_adress, M.member_zipcode, M.member_town, M.member_phone, S.status_name FROM '
                    . $xoopsDB->prefix('tdmassoc_member') . ' M, ' . $xoopsDB->prefix('tdmassoc_status')
                    . ' S WHERE S.status_id=M.member_status ORDER BY S.status_order limit 0,10', $prop);
        $pdf->Output();

        break;

    case 'ticket':
        $ticketHandler = xoops_getModuleHandler('tdmassoc_ticket', 'tdmpicture');
        $ticket        = $ticketHandler->get($_REQUEST['ticket_id']);

        $ticket_nb_pages = !empty($_REQUEST['ticket_nb_pages']) ? $_REQUEST['ticket_nb_pages'] : '1';
        $type_page       = !empty($_REQUEST['type_page']) ? $_REQUEST['type_page'] : 'A4';
        $method1         = !empty($_REQUEST['method1']) ? $_REQUEST['method1'] : '1';
        $method2         = !empty($_REQUEST['method2']) ? $_REQUEST['method2'] : '1';

        $text_size               = $ticket->getVar('ticket_text_size');
        $text_color              = TDMPicture_color($ticket->getVar('ticket_text_color'));
        $ticket_picture          = $ticket->getVar('ticket_picture');
        $ticket_width_fixe       = $ticket->getVar('ticket_width');
        $ticket_height_fixe      = $ticket->getVar('ticket_height');
        $ticket_num1_width       = $ticket->getVar('ticket_num1_width');
        $ticket_num1_width_fixe  = $ticket_num1_width;
        $ticket_num1_height      = $ticket->getVar('ticket_num1_height');
        $ticket_num1_height_fixe = $ticket_num1_height;
        $ticket_num2_width       = $ticket->getVar('ticket_num2_width');
        $ticket_num2_width_fixe  = $ticket_num2_width;
        $ticket_num2_height      = $ticket->getVar('ticket_num2_height');
        $ticket_num2_height_fixe = $ticket_num2_height;
        $num_ticket              = $ticket->getVar('ticket_nbticket');

        //Quel format ?
        if ($type_page == 1) {
            //A4
            if ($method1 == 1) {
                $pdf = new PDF('P', 'mm', 'A4');
                for ($l = 0; $l < $ticket_nb_pages; ++$l) {
                    $pdf->AddPage();
                    //V1: Calcul de la largeur du billet en 210
                    //Hauteur
                    $ticket_height = 0;
                    for ($i = 0; $ticket_height <= 297; ++$i) {
                        if ((297 - $ticket_height) > $ticket_height_fixe) {
                            //Largeur
                            $ticket_width = 0;
                            for ($j = 0; $ticket_width <= 210; ++$j) {
                                if ((210 - $ticket_width) > $ticket_width_fixe) {
                                    ++$num_ticket;
                                    $pdf->Image('' . XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/ticket/' . $ticket_picture . '',
                                                $ticket_width, $ticket_height, $ticket_width_fixe, $ticket_height_fixe);
                                    $pdf->SetFont('Arial', 'B', $text_size);
                                    $pdf->SetTextColor($text_color['r'], $text_color['v'], $text_color['b']);
                                    $pdf->Rotate(90, $ticket_num1_width, $ticket_num1_height);
                                    $pdf->NumText($ticket_num1_width, $ticket_num1_height, $num_ticket);
                                    $pdf->Rotate(0);
                                    $pdf->NumText($ticket_num2_width, $ticket_num2_height, $num_ticket);
                                }
                                $ticket_width += $ticket_width_fixe;
                                $ticket_num1_width += $ticket_width_fixe;
                                $ticket_num2_width += $ticket_width_fixe;
                            }
                            $ticket_num1_width = $ticket_num1_width_fixe;
                            $ticket_num2_width = $ticket_num2_width_fixe;
                        }
                        $ticket_height += $ticket_height_fixe;
                        $ticket_num1_height += $ticket_height_fixe;
                        $ticket_num2_height += $ticket_height_fixe;
                    }
                    $ticket_num1_width  = $ticket_num1_width_fixe;
                    $ticket_num2_width  = $ticket_num2_width_fixe;
                    $ticket_num1_height = $ticket_num1_height_fixe;
                    $ticket_num2_height = $ticket_num2_height_fixe;
                }
            } else {
                //V2: Calcul de la largeur du billet en 297
                $pdf = new PDF('L', 'mm', 'A4');
                for ($l = 0; $l < $ticket_nb_pages; ++$l) {
                    $pdf->AddPage();
                    //Hauteur
                    $ticket_height = 0;
                    for ($i = 0; $ticket_height <= 210; ++$i) {
                        if ((210 - $ticket_height) > $ticket_height_fixe) {
                            //Largeur
                            $ticket_width = 0;
                            for ($j = 0; $ticket_width <= 297; ++$j) {
                                if ((297 - $ticket_width) > $ticket_width_fixe) {
                                    ++$num_ticket;
                                    $pdf->Image('' . XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/ticket/' . $ticket_picture . '',
                                                $ticket_width, $ticket_height, $ticket_width_fixe, $ticket_height_fixe);
                                    $pdf->SetFont('Arial', 'B', $text_size);
                                    $pdf->SetTextColor($text_color['r'], $text_color['v'], $text_color['b']);
                                    $pdf->Rotate(90, $ticket_num1_width, $ticket_num1_height);
                                    $pdf->NumText($ticket_num1_width, $ticket_num1_height, $num_ticket);
                                    $pdf->Rotate(0);
                                    $pdf->NumText($ticket_num2_width, $ticket_num2_height, $num_ticket);
                                }
                                $ticket_width += $ticket_width_fixe;
                                $ticket_num1_width += $ticket_width_fixe;
                                $ticket_num2_width += $ticket_width_fixe;
                            }
                            $ticket_num1_width = $ticket_num1_width_fixe;
                            $ticket_num2_width = $ticket_num2_width_fixe;
                        }
                        $ticket_height += $ticket_height_fixe;
                        $ticket_num1_height += $ticket_height_fixe;
                        $ticket_num2_height += $ticket_height_fixe;
                    }
                    $ticket_num1_width  = $ticket_num1_width_fixe;
                    $ticket_num2_width  = $ticket_num2_width_fixe;
                    $ticket_num1_height = $ticket_num1_height_fixe;
                    $ticket_num2_height = $ticket_num2_height_fixe;
                }
            }
        } else {
            if ($method2 == 1) {
                $pdf = new PDF('P', 'mm', 'A3');
                for ($l = 0; $l < $ticket_nb_pages; ++$l) {
                    $pdf->AddPage();

                    //V1: Calcul de la largeur du billet en 297
                    //Hauteur
                    $ticket_height = 0;
                    for ($i = 0; $ticket_height <= 420; ++$i) {
                        if ((420 - $ticket_height) > $ticket_height_fixe) {
                            //Largeur
                            $ticket_width = 0;
                            for ($j = 0; $ticket_width <= 297; ++$j) {
                                if ((297 - $ticket_width) > $ticket_width_fixe) {
                                    ++$num_ticket;
                                    $pdf->Image('' . XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/ticket/' . $ticket_picture . '',
                                                $ticket_width, $ticket_height, $ticket_width_fixe, $ticket_height_fixe);
                                    $pdf->SetFont('Arial', 'B', $text_size);
                                    $pdf->SetTextColor($text_color['r'], $text_color['v'], $text_color['b']);
                                    $pdf->Rotate(90, $ticket_num1_width, $ticket_num1_height);
                                    $pdf->NumText($ticket_num1_width, $ticket_num1_height, $num_ticket);
                                    $pdf->Rotate(0);
                                    $pdf->NumText($ticket_num2_width, $ticket_num2_height, $num_ticket);
                                }
                                $ticket_width += $ticket_width_fixe;
                                $ticket_num1_width += $ticket_width_fixe;
                                $ticket_num2_width += $ticket_width_fixe;
                            }
                            $ticket_num1_width = $ticket_num1_width_fixe;
                            $ticket_num2_width = $ticket_num2_width_fixe;
                        }
                        $ticket_height += $ticket_height_fixe;
                        $ticket_num1_height += $ticket_height_fixe;
                        $ticket_num2_height += $ticket_height_fixe;
                    }
                    $ticket_num1_width  = $ticket_num1_width_fixe;
                    $ticket_num2_width  = $ticket_num2_width_fixe;
                    $ticket_num1_height = $ticket_num1_height_fixe;
                    $ticket_num2_height = $ticket_num2_height_fixe;
                }
            } else {
                //V2: Calcul de la largeur du billet en 420
                $pdf = new PDF('L', 'mm', 'A3');
                for ($l = 0; $l < $ticket_nb_pages; ++$l) {
                    $pdf->AddPage();

                    //Hauteur
                    $ticket_height = 0;
                    for ($i = 0; $ticket_height <= 297; ++$i) {
                        if ((297 - $ticket_height) > $ticket_height_fixe) {
                            //Largeur
                            $ticket_width = 0;
                            for ($j = 0; $ticket_width <= 420; ++$j) {
                                if ((420 - $ticket_width) > $ticket_width_fixe) {
                                    ++$num_ticket;
                                    $pdf->Image('' . XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/ticket/' . $ticket_picture . '',
                                                $ticket_width, $ticket_height, $ticket_width_fixe, $ticket_height_fixe);
                                    $pdf->SetFont('Arial', 'B', $text_size);
                                    $pdf->SetTextColor($text_color['r'], $text_color['v'], $text_color['b']);
                                    $pdf->Rotate(90, $ticket_num1_width, $ticket_num1_height);
                                    $pdf->NumText($ticket_num1_width, $ticket_num1_height, $num_ticket);
                                    $pdf->Rotate(0);
                                    $pdf->NumText($ticket_num2_width, $ticket_num2_height, $num_ticket);
                                }
                                $ticket_width += $ticket_width_fixe;
                                $ticket_num1_width += $ticket_width_fixe;
                                $ticket_num2_width += $ticket_width_fixe;
                            }
                            $ticket_num1_width = $ticket_num1_width_fixe;
                            $ticket_num2_width = $ticket_num2_width_fixe;
                        }
                        $ticket_height += $ticket_height_fixe;
                        $ticket_num1_height += $ticket_height_fixe;
                        $ticket_num2_height += $ticket_height_fixe;
                    }
                    $ticket_num1_width  = $ticket_num1_width_fixe;
                    $ticket_num2_width  = $ticket_num2_width_fixe;
                    $ticket_num1_height = $ticket_num1_height_fixe;
                    $ticket_num2_height = $ticket_num2_height_fixe;
                }
            }
        }

        $ticket->setVar('ticket_nbticket', $num_ticket);
        if ($ticketHandler->insert($ticket)) {
        }

        //////////////////////////////////
        $pdf->Output();
        break;

    //pdf pour les newsletter
    case 'list_newsletter':
        $newsletterHandler = xoops_getModuleHandler('tdmassoc_newsletter', 'tdmpicture');
        $newsletter        = $newsletterHandler->get($_REQUEST['newsletter_id']);

        $newsletter_head   = utf8_decode(Chars($newsletter->getVar('newsletter_head')));
        $newsletter_text   = utf8_decode(Chars($newsletter->getVar('newsletter_text')));
        $newsletter_foot   = utf8_decode(Chars($newsletter->getVar('newsletter_foot')));
        $newsletter_indate = formatTimestamp($newsletter->getVar('newsletter_indate'), 'm');
        $color             = TDMPicture_color($newsletter->getVar('newsletter_color'));
        $pdf               = new TCPDF();
        $pdf->AddPage();
        //titre
        $pdf->SetFont('Arial', 'B', 15);
        $w = $pdf->GetStringWidth($newsletter->getVar('newsletter_title')) + 6;
        $pdf->SetX((210 - $w) / 2);
        $pdf->SetDrawColor(204, 204, 204);
        $pdf->SetFillColor($color['r'], $color['v'], $color['b']);
        $pdf->SetLineWidth(0.2);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($w, 8, Chars($newsletter->getVar('newsletter_title')), 1, 1, 'C', true);
        $pdf->Ln(6);
        //Sauvegarde de l'ordonnée

        // date
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(50, 8, Chars(_AM_TDMPICTURE_FORMINDATE) . ': ' . $newsletter_indate, 1, 1, 'L', true);
        $pdf->Ln(6);

        //content
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(239, 239, 239);
        $pdf->MultiCell(190, 10, $newsletter_head, 1, 1, 'C', true);
        $pdf->Ln(4);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(190, 10, $newsletter_text, 1, 1, 'C', true);
        $pdf->Ln(4);
        $pdf->SetFillColor(239, 239, 239);
        $pdf->MultiCell(190, 10, $newsletter_foot, 1, 1, 'C', true);

        $pdf->Output();

        $titre = $newsletter->getVar('newsletter_title');
        $pdf->AddPage();
        $prop = array(
            'HeaderColor' => array(
                255,
                150,
                100
            ),
            'color1'      => array(
                210,
                245,
                255
            ),
            'color2'      => array(
                255,
                255,
                210
            ),
            'padding'     => 2
        );
        /*$criteria = new CriteriaCompo();
        $criteria->add(new Criteria('member_waiting', 1,'='));
        $criteria->setOrder('ASC');
        $assoc_arr = $memberHandler->getObjects($criteria);*/
        $pdf->Table('SELECT newsletter_head FROM ' . $xoopsDB->prefix('tdmassoc_newsletter') . ' WHERE newsletter_id = ' . $_REQUEST['newsletter_id']
                    . ' limit 0,10', $prop);
        $pdf->Output();

        break;

    //pour les produits
    case 'list_fact':

        $productHandler = xoops_getModuleHandler('tdmassoc_product', 'tdmpicture');
        $stockHandler   = xoops_getModuleHandler('tdmassoc_stock', 'tdmpicture');
        $product        = $productHandler->get($_REQUEST['product_id']);
        $product_ref    = utf8_decode(Chars($product->getVar('product_ref')));
        $product_tva    = $product->getVar('product_tva');
        $product_text   = utf8_decode(Chars($product->getVar('product_text')));
        $product_indate = formatTimestamp($product->getVar('product_indate'), 'm');
        $num            = $product->getVar('product_cid');
        $cat            = array(
            '1' => _AM_TDMPICTURE_PRODUCTCAT_ACHAT,
            '2' => _AM_TDMPICTURE_PRODUCTCAT_VENTE,
            '3' => _AM_TDMPICTURE_PRODUCTCAT_LOCATION,
            '4' => _AM_TDMPICTURE_PRODUCTCAT_PRETS,
            '5' => _AM_TDMPICTURE_PRODUCTCAT_CADEAUX,
            '6' => _AM_TDMPICTURE_PRODUCTCAT_DIVERS
        );

        //calcul les stocks
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('stock_product', $_REQUEST['product_id']));
        $stock_arr =& $stockHandler->getall($criteria);
        $stock_qte = 0;
        foreach (array_keys($stock_arr) as $s) {
            $math = $stock_arr[$s]->getVar('stock_math');
            $qte  = (int)$stock_arr[$s]->getVar('stock_qte');
            $stock_qte += number_format($math . $qte, 2);
        }
        //adition stock et depart
        $product_qte = number_format($product->getVar('product_qte') + $stock_qte, 0);
        //

        $product_ht = $product->getVar('product_inht');
        $title_ht   = _AM_TDMPICTURE_FORMINHT;

        $product_fullht  = $product_ht * $product_qte;
        $str             = str_replace(',', '.', $product_tva);
        $mintva          = $str / 100;
        $product_fulltva = $str / 100 * $product_fullht;
        $product_ttc     = $product_fullht * (1 + $mintva);

        $pdf = new TCPDF();
        $pdf->AddPage();

        //titre
        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Cell(130);
        $pdf->Cell(30, 10, $cat['' . $num . ''], 0, 0, 'L');
        $pdf->Ln(8);
        //minidate et mini ref
        //mini ref
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(130);
        $pdf->Cell(30, 10, Chars(_AM_TDMPICTURE_FORMREF) . ' : ' . $product_ref, 0, 0, 'L');
        $pdf->Ln(5);
        //mini date entrer
        $pdf->Cell(130);
        $pdf->Cell(30, 10, Chars(_AM_TDMPICTURE_FORMDATE) . ' : ' . $product_indate, 0, 0, 'L');
        $pdf->Ln(5);
        //mini date sortis
        //if ($num == 3 || $num == 4 || $num == 6) {
        //$product_outregdate = formatTimeStamp($product->getVar("product_outregdate"),"m");
        //$pdf->Cell(130);
        //$pdf->Cell(30,10, Chars(_AM_TDMPICTURE_FORMOUTREGDATE). " : ".$product_outregdate,0,0,'L');
        //}

        $pdf->Ln(20);
        //Tableau Largeurs des colonnes
        $w = array(
            40,
            50,
            30,
            25,
            25,
            20
        );

        //En-tête
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($w[0], 7, Chars(_AM_TDMPICTURE_FORMREF), 1, 0, 'L', 0);
        $pdf->Cell($w[1], 7, Chars(_AM_TDMPICTURE_FORMTEXT), 1, 0, 'L', 0);
        $pdf->Cell($w[2], 7, Chars(_AM_TDMPICTURE_FORMQTE), 1, 0, 'C', 0);
        $pdf->Cell($w[3], 7, Chars($title_ht), 1, 0, 'C', 0);
        $pdf->Cell($w[4], 7, Chars(_AM_TDMPICTURE_FORMCOUNTHT), 1, 0, 'C', 0);
        $pdf->Cell($w[5], 7, Chars(_AM_TDMPICTURE_FORMTVA), 1, 0, 'C', 0);

        $pdf->Ln();
        //Données
        $posX = $pdf->GetX();
        $posY = $pdf->GetY();
        $pdf->MultiCell($w[0], 6, $product_ref, 'LR', 'L', 0);
        $pdf->SetXY($posX + $w[0], $posY);
        $pos2X = $pdf->GetX();
        $pdf->MultiCell($w[1], 6, $product_text, 'LR', 'L', 0);
        $pdf->SetXY($pos2X + $w[1], $posY);

        $pdf->Cell($w[2], 6, $product_qte, 'LR', 0, 'C');
        $pdf->Cell($w[3], 6, $product_ht, 'LR', 0, 'C');
        $pdf->Cell($w[4], 6, $product_fullht, 'LR', 0, 'C');
        $pdf->Cell($w[5], 6, $product_tva, 'LR', 0, 'C');
        $pdf->Ln();
        //introduit un espace
        $pdf->Cell($w[0], 80, '', 'LR', 0, 'L');
        $pdf->Cell($w[1], 80, '', 'LR', 0, 'L');
        $pdf->Cell($w[2], 80, '', 'LR', 0, 'C');
        $pdf->Cell($w[3], 80, '', 'LR', 0, 'C');
        $pdf->Cell($w[4], 80, '', 'LR', 0, 'C');
        $pdf->Cell($w[5], 80, '', 'LR', 0, 'C');
        $pdf->Ln();
        //Trait de terminaison
        $pdf->Cell(array_sum($w), 0, '', 'T');
        $pdf->Ln(20);
        //tableau recapitulatif
        //Tableau Largeurs des colonnes

        //En-tête
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(90);
        $pdf->Cell(80, 10, Chars(_AM_TDMPICTURE_PRODUCT_RECA), 1, 0, 'C', 0);
        $pdf->Cell(20, 10, Chars($moduleHelper->getConfig('assoc_type_money')), 1, 0, 'C', 0);
        $pdf->Ln(10);
        //Données
        $pdf->Cell(90);
        $pdf->Cell(80, 7, Chars(_AM_TDMPICTURE_FORMCOUNTHT) . ' : ', 'L', 0, 'L');
        $pdf->Cell(20, 7, $product_fullht, 'R', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90);
        $pdf->Cell(80, 7, Chars(_AM_TDMPICTURE_FORMTVA) . ' : ', 'L', 0, 'L');
        $pdf->Cell(20, 7, $product_fulltva, 'R', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90);
        $pdf->Cell(80, 0, '', 1, 0, 'L');
        $pdf->Cell(20, 0, '', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(90);
        $pdf->Cell(80, 10, Chars(_AM_TDMPICTURE_FORMTTC) . ' : ', 'L', 0, 'L');
        $pdf->Cell(20, 10, $product_ttc, 'R', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90);
        $pdf->Cell(80, 0, '', 1, 0, 'L');
        $pdf->Cell(20, 0, '', 1, 0, 'C');
        $pdf->Ln();

        $pdf->Output();

        break;

    //pour les stocks
    case 'list_stock':
        $stockHandler   = xoops_getModuleHandler('tdmassoc_stock', 'tdmpicture');
        $productHandler = xoops_getModuleHandler('tdmassoc_product', 'tdmpicture');
        $stock          = $stockHandler->get($_REQUEST['stock_id']);
        $product        = $productHandler->get($stock->getVar('stock_product'));

        //travail les reponse
        $stock_title  = utf8_decode(Chars($stock->getVar('stock_title')));
        $product_tva  = $product->getVar('product_tva');
        $stock_text   = utf8_decode(Chars($stock->getVar('stock_text')));
        $stock_qte    = $stock->getVar('stock_qte');
        $stock_indate = formatTimestamp($stock->getVar('stock_indate'), 'm');
        $num          = $product->getVar('product_cid');
        $cat          = array(
            '1' => _AM_TDMPICTURE_PRODUCTCAT_ACHAT,
            '2' => _AM_TDMPICTURE_PRODUCTCAT_VENTE,
            '3' => _AM_TDMPICTURE_PRODUCTCAT_LOCATION,
            '4' => _AM_TDMPICTURE_PRODUCTCAT_PRETS,
            '5' => _AM_TDMPICTURE_PRODUCTCAT_CADEAUX,
            '6' => _AM_TDMPICTURE_PRODUCTCAT_DIVERS
        );

        $product_ht = $product->getVar('product_inht');
        $title_ht   = _AM_TDMPICTURE_FORMINHT;

        $product_fullht  = $product_ht * $stock_qte;
        $str             = str_replace(',', '.', $product_tva);
        $mintva          = $str / 100;
        $product_fulltva = $str / 100 * $product_fullht;
        $product_ttc     = $product_fullht * (1 + $mintva);

        $pdf = new TCPDF();
        $pdf->AddPage();

        //titre
        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Cell(130);
        $pdf->Cell(30, 10, $cat['' . $num . ''], 0, 0, 'L');
        $pdf->Ln(8);
        //minidate et mini ref
        //mini ref
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(130);
        $pdf->Cell(30, 10, Chars(_AM_TDMPICTURE_FORMREF) . ' : ' . $stock_title, 0, 0, 'L');
        $pdf->Ln(5);
        //mini date entrer
        $pdf->Cell(130);
        $pdf->Cell(30, 10, Chars(_AM_TDMPICTURE_FORMDATE) . ' : ' . $stock_indate, 0, 0, 'L');
        $pdf->Ln(5);
        //mini date sortis
        if ($num == 3 || $num == 4 || $num == 6) {
            $stock_outregdate = formatTimestamp($stock->getVar('stock_outregdate'), 'm');
            $pdf->Cell(130);
            $pdf->Cell(30, 10, Chars(_AM_TDMPICTURE_FORMOUTREGDATE) . ' : ' . $stock_outregdate, 0, 0, 'L');
        }

        $pdf->Ln(20);
        //Tableau Largeurs des colonnes
        $w = array(
            40,
            50,
            30,
            25,
            25,
            20
        );

        //En-tête
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($w[0], 7, Chars(_AM_TDMPICTURE_FORMREF), 1, 0, 'L', 0);
        $pdf->Cell($w[1], 7, Chars(_AM_TDMPICTURE_FORMTEXT), 1, 0, 'L', 0);
        $pdf->Cell($w[2], 7, Chars(_AM_TDMPICTURE_FORMQTE), 1, 0, 'C', 0);
        $pdf->Cell($w[3], 7, Chars($title_ht), 1, 0, 'C', 0);
        $pdf->Cell($w[4], 7, Chars(_AM_TDMPICTURE_FORMCOUNTHT), 1, 0, 'C', 0);
        $pdf->Cell($w[5], 7, Chars(_AM_TDMPICTURE_FORMTVA), 1, 0, 'C', 0);

        $pdf->Ln();
        //Données
        $posX = $pdf->GetX();
        $posY = $pdf->GetY();
        $pdf->MultiCell($w[0], 6, $stock_title, 'LR', 'L', 0);
        $pdf->SetXY($posX + $w[0], $posY);
        $pos2X = $pdf->GetX();
        $pdf->MultiCell($w[1], 6, $stock_text, 'LR', 'L', 0);
        $pdf->SetXY($pos2X + $w[1], $posY);
        $pdf->Cell($w[2], 6, $stock_qte, 'LR', 0, 'C');
        $pdf->Cell($w[3], 6, $product_ht, 'LR', 0, 'C');
        $pdf->Cell($w[4], 6, $product_fullht, 'LR', 0, 'C');
        $pdf->Cell($w[5], 6, $product_tva, 'LR', 0, 'C');
        $pdf->Ln();
        //introduit un espace
        $pdf->Cell($w[0], 80, '', 'LR', 0, 'L');
        $pdf->Cell($w[1], 80, '', 'LR', 0, 'L');
        $pdf->Cell($w[2], 80, '', 'LR', 0, 'C');
        $pdf->Cell($w[3], 80, '', 'LR', 0, 'C');
        $pdf->Cell($w[4], 80, '', 'LR', 0, 'C');
        $pdf->Cell($w[5], 80, '', 'LR', 0, 'C');
        $pdf->Ln();
        //Trait de terminaison
        $pdf->Cell(array_sum($w), 0, '', 'T');
        $pdf->Ln(20);
        //tableau recapitulatif
        //Tableau Largeurs des colonnes

        //En-tête
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(90);
        $pdf->Cell(80, 10, Chars(_AM_TDMPICTURE_PRODUCT_RECA), 1, 0, 'C', 0);
        $pdf->Cell(20, 10, Chars($moduleHelper->getConfig('assoc_type_money')), 1, 0, 'C', 0);
        $pdf->Ln(10);
        //Données
        $pdf->Cell(90);
        $pdf->Cell(80, 7, Chars(_AM_TDMPICTURE_FORMCOUNTHT) . ' : ', 'L', 0, 'L');
        $pdf->Cell(20, 7, $product_fullht, 'R', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90);
        $pdf->Cell(80, 7, Chars(_AM_TDMPICTURE_FORMTVA) . ' : ', 'L', 0, 'L');
        $pdf->Cell(20, 7, $product_fulltva, 'R', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90);
        $pdf->Cell(80, 0, '', 1, 0, 'L');
        $pdf->Cell(20, 0, '', 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(90);
        $pdf->Cell(80, 10, Chars(_AM_TDMPICTURE_FORMTTC) . ' : ', 'L', 0, 'L');
        $pdf->Cell(20, 10, $product_ttc, 'R', 0, 'C');
        $pdf->Ln();
        $pdf->Cell(90);
        $pdf->Cell(80, 0, '', 1, 0, 'L');
        $pdf->Cell(20, 0, '', 1, 0, 'C');
        $pdf->Ln();

        $pdf->Output();

        break;

    //pour le RIB
    case 'list_rib':

        $secu_id = urldecode(hash('ripemd128', $moduleHelper->getConfig('assoc_label')));

        if ($_REQUEST['secu_id'] != $secu_id) {
            redirect_header('account.php', 3, _AM_TDMPICTURE_FORMNONE);
        } else {
            $pdf = new TCPDF();
            $pdf->AddPage();

            //titre
            $pdf->SetFont('Arial', 'B', 25);
            $pdf->Cell(40);
            $pdf->Cell(30, 10, Chars(_AM_TDMPICTURE_ACCOUNT_RIBDESC), 0, 0, 'L');
            $pdf->Ln(20);
            //mini nom
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 10, Chars($moduleHelper->getConfig('assoc_name')), 0, 0, 'L');
            $pdf->Ln(10);
            //mini adresse
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(30, 10, Chars($moduleHelper->getConfig('assoc_adress')), 0, 0, 'L');
            $pdf->Ln(5);
            //mini tel
            $pdf->Cell(30, 10, $moduleHelper->getConfig('assoc_tel'), 0, 0, 'L');
            $pdf->Ln(20);
            //Tableau Largeurs des colonnes
            //En-tête
            $pdf->Cell(90, 0, '', 1, 0, 'L');
            $pdf->Cell(90, 0, '', 1, 0, 'L');
            $pdf->Ln();
            //
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(90, 7, Chars($moduleHelper->getConfig('assoc_label')), 'LR', 0, 'L', 0);
            $pdf->Cell(90, 7, Chars(_MI_TDMPICTURE_ACCOUNT_COBANQ . ' : ' . $moduleHelper->getConfig('assoc_banque')), 'LR', 0, 'L', 0);

            $pdf->Ln();
            //Données
            $pdf->Cell(90, 6, Chars($moduleHelper->getConfig('assoc_domiciliation')), 'LR', 0, 'L');
            $pdf->Cell(90, 6, Chars(_MI_TDMPICTURE_ACCOUNT_COGUI . ' : ' . $moduleHelper->getConfig('assoc_guichet')), 'LR', 0, 'L');
            $pdf->Ln();
            $pdf->Cell(90, 6, '', 'LR', 0, 'L');
            $pdf->Cell(90, 6, Chars(_MI_TDMPICTURE_ACCOUNT_COMPTE . ' : ' . $moduleHelper->getConfig('assoc_compte')), 'LR', 0, 'L');
            $pdf->Ln();
            $pdf->Cell(90, 6, '', 'LR', 0, 'L');
            $pdf->Cell(90, 6, Chars(_MI_TDMPICTURE_ACCOUNT_CLEFRIB . ' : ' . $moduleHelper->getConfig('assoc_rib')), 'LR', 0, 'L');
            $pdf->Ln();
            $pdf->Cell(90, 10, '', 'LR', 0, 'L');
            $pdf->Cell(90, 10, '', 'LR', 0, 'L');
            $pdf->Ln();
            $pdf->Cell(90, 0, '', 1, 0, 'L');
            $pdf->Cell(90, 0, '', 1, 0, 'L');
            $pdf->Ln();
            //tableau recapitulatif
            //Tableau Largeurs des colonnes
            //Données
            $pdf->Cell(180, 7, Chars(_MI_TDMPICTURE_ACCOUNT_IBAN . ' : ' . $moduleHelper->getConfig('assoc_iban')), 'LR', 0, 'L');
            $pdf->Ln();
            $pdf->Cell(180, 7, Chars(_MI_TDMPICTURE_ACCOUNT_BIC . ' : ' . $moduleHelper->getConfig('assoc_bic')), 'LR', 0, 'L');
            $pdf->Ln();
            $pdf->Cell(180, 0, '', 1, 0, 'L');
            $pdf->Ln();

            $pdf->Output();
        }
        break;
}
//

/**
 * @param $text
 * @return mixed
 */
function Chars($text)
{
    $myts = MyTextSanitizer:: getInstance();

    return preg_replace(array(
                            '/&#039;/i',
                            '/&#233;/i',
                            '/&#232;/i',
                            '/&#224;/i',
                            '/&quot;/i',
                            '/<br \/>/i',
                            '/&agrave;/i',
                            '/&#8364;/i'
                        ), array(
                            "'",
                            'é',
                            'è',
                            'à',
                            '"',
                            "\n",
                            'à',
                            '€'
                        ), $text);
}
