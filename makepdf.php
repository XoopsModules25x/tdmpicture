<?php

/**
 * File : makefile.pdf for publisher
 * For tcpdf_for_xoops 2.01 and higher
 * Created by montuy337513 / philodenelle - http://www.chg-web.org
 **/
error_reporting(0);

include_once __DIR__ . '/header.php';
$itemid       = \Xmf\Request::getInt('st', 0, 'GET');
$item_page_id = \Xmf\Request::getInt('page', -1, 'GET');

if ($itemid == 0) {
    redirect_header('javascript:history.go(-1)', 1, _MD_AMREVIEWS_NOITEMSELECTED);
}

//2.5.7
//if (!is_file(XOOPS_PATH . '/vendor/tcpdf/tcpdf.php')) {
//    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/viewtopic.php?topic_id=' . $itemid, 3, 'TCPF for Xoops not installed in ./xoops_lib/vendor/');
//}

//2.5.8
require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';
//if (!is_file(XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php')) {
//    redirect_header('javascript:history.go(-1)', 3, _MD_AMREVIEWS_ERROR_NO_PDF);
//}

// Creating the item object for the selected item
$itemObj = $moduleHelper->getHandler('review')->get($itemid);

// if the selected item was not found, exit
if (!$itemObj) {
    redirect_header('javascript:history.go(-1)', 1, _MD_AMREVIEWS_NOITEMSELECTED);
}

// Creating the category object that holds the selected item
$categoryObj = $publisher->getHandler('category')->get($itemObj->categoryid());

// Check user permissions to access that category of the selected item
if (!$itemObj->accessGranted()) {
    redirect_header('javascript:history.go(-1)', 1, _NOPERM);
}

xoops_loadLanguage('main', AMREVIEWS_DIRNAME);

$dateformat    = $itemObj->getDatesub();
$sender_inform = sprintf(_MD_AMREVIEWS_WHO_WHEN, $itemObj->posterName(), $itemObj->getDatesub());
$mainImage     = $itemObj->getMainImage();

$content = '';
if ($mainImage['image_path'] != '') {
    $content .= '<img src="' . $mainImage['image_path'] . '" alt="' . $myts->undoHtmlSpecialChars($mainImage['image_name']) . '"/><br>';
}
$content .= '<a href="' . AMREVIEWS_URL . '/item.php?itemid=' . $itemid . '" style="text-decoration: none; color: black; font-size: 120%;" title="' . $myts->undoHtmlSpecialChars($itemObj->getTitle())
            . '">' . $myts->undoHtmlSpecialChars($itemObj->getTitle()) . '</a>';
$content .= '<br><span style="color: #CCCCCC; font-weight: bold; font-size: 80%;">' . _CO_AMREVIEWS_CATEGORY . ' : </span><a href="' . AMREVIEWS_URL . '/category.php?categoryid='
            . $itemObj->categoryid() . '" style="color: #CCCCCC; font-weight: bold; font-size: 80%;" title="' . $myts->undoHtmlSpecialChars($categoryObj->name()) . '">'
            . $myts->undoHtmlSpecialChars($categoryObj->name()) . '</a>';
$content .= '<br><span style="font-size: 80%; font-style: italic;">' . $sender_inform . '</span><br>';
$content .= $itemObj->getBody();
$content = str_replace('[pagebreak]', '', $content);

// Configuration for TCPDF_for_XOOPS
$pdf_data = array(
    'author'           => $itemObj->posterName(),
    'title'            => $myts->undoHtmlSpecialChars($categoryObj->name()),
    'page_format'      => 'A4',
    'page_orientation' => 'P',
    'unit'             => 'mm',
    'rtl'              => false
    //true if right to left
);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false);

$doc_title  = publisherConvertCharset($myts->undoHtmlSpecialChars($itemObj->getTitle()));
$docSubject = $myts->undoHtmlSpecialChars($categoryObj->name());

$docKeywords = $myts->undoHtmlSpecialChars($itemObj->meta_keywords());
if (array_key_exists('rtl', $pdf_data)) {
    $pdf->setRTL($pdf_data['rtl']);
}
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($docSubject);
//$pdf->SetKeywords(XOOPS_URL . ', '.' by TCPDF_for_XOOPS (chg-web.org), '.$doc_title);
$pdf->SetKeywords($docKeywords);

$firstLine  = publisherConvertCharset($GLOBALS['xoopsConfig']['sitename']) . ' (' . XOOPS_URL . ')';
$secondLine = publisherConvertCharset($GLOBALS['xoopsConfig']['slogan']);

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $firstLine, $secondLine);
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $firstLine, $secondLine, array(
    0,
    64,
    255
), array(
                        0,
                        64,
                        128
                    ));

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

//2.5.7
//$pdf->setHeaderFont(array(PDF_FONT_NAME_SUB, '', PDF_FONT_SIZE_SUB));
//$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//2.5.8
$pdf->setHeaderFont(array(
                        PDF_FONT_NAME_MAIN,
                        '',
                        PDF_FONT_SIZE_MAIN
                    ));
$pdf->setFooterFont(array(
                        PDF_FONT_NAME_DATA,
                        '',
                        PDF_FONT_SIZE_DATA
                    ));

$pdf->setFooterData($tc = array(
    0,
    64,
    0
), $lc = array(
    0,
    64,
    128
));

//initialize document
$pdf->Open();
$pdf->AddPage();
$pdf->writeHTML($content, true, 0, true, 0);
$pdf->Output();
