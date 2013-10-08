<?php
/**
 * ****************************************************************************
 *  - TDMPicture By TDM   - TEAM DEV MODULE FOR XOOPS
 *  - GNU Licence Copyright (c)  (http://www.)
 *
 * La licence GNU GPL, garanti à l'utilisateur les droits suivants
 *
 * 1. La liberté d'exécuter le logiciel, pour n'importe quel usage,
 * 2. La liberté de l' étudier et de l'adapter à ses besoins,
 * 3. La liberté de redistribuer des copies,
 * 4. La liberté d'améliorer et de rendre publiques les modifications afin
 * que l'ensemble de la communauté en bénéficie.
 *
 * @copyright       	(http://www.)
 * @license        	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		TDM ; TEAM DEV MODULE 
 *
 * ****************************************************************************
 */

//Admin menu 
define("_MI_TDMPICTURE_ADMENUINDEX","Index");
define("_MI_TDMPICTURE_ADMENUCAT","Categories"); 
define("_MI_TDMPICTURE_ADMENUFILE","Images");
define("_MI_TDMPICTURE_ADMENUPERMISSIONS","Permissions"); 
define("_MI_TDMPICTURE_ADMENUABOUT","About");
define("_MI_TDMPICTURE_ADMENUPREF","Preferences");
//1.07

define("_MI_TDMPICTURE_ADMENUIMPORT","Import");
define("_MI_TDMPICTURE_ADMENUBATCH","Batch");


//Preferences 

define("_MI_TDMPICTURE_UPLOAD_PATH","Upload Directory: TDMPicture");
define("_MI_TDMPICTURE_UPLOAD_THUMB","Thumbnails Upload Directory: TDMPicture");
define("_MI_TDMPICTURE_UPLOAD_DESC","The first and last character must be «/».<br />
The write permission of this directory should be 777 or 707 in Unix.");
define("_MI_TDMPICTURE_MYALBUM_PATH","Upload Directory: Myalbum");
define("_MI_TDMPICTURE_MYALBUM_THUMB","Thumbnails Upload Directory: Myalbum");
define("_MI_TDMPICTURE_EXTGALLERY_PATH","Upload Directory: Extgallery");
define("_MI_TDMPICTURE_EXTGALLERY_THUMB","Thumbnails Upload Directory: Extgallery");

define("_MI_TDMPICTURE_MIMEMAX","Max file size for upload");
define("_MI_TDMPICTURE_MIMETYPE","Allowed Extensions separated by |"); 
define("_MI_TDMPICTURE_EDITOR","Publisher"); 
define("_MI_TDMPICTURE_FAVOURITE","Number of item to be favorites"); 
define("_MI_TDMPICTURE_UPMAX","Maximum number of simultaneous uploads"); 
define("_MI_TDMPICTURE_LAST","Maximum number of new file display on index");
define("_MI_TDMPICTURE_WIDTH","Maximum width of the image in detail"); 
define("_MI_TDMPICTURE_HEIGTH","Maximum height of the image in detail"); 
define("_MI_TDMPICTURE_THUMB_WIDTH","Thumbnail: Maximum width of the image"); 
define("_MI_TDMPICTURE_THUMB_HEIGTH","Thumbnail: Maximum height of image"); 
define("_MI_TDMPICTURE_THUMB_QUALITY","Thumbnail: Image quality"); 
define("_MI_TDMPICTURE_PAGE","Maximum number of files per page?"); 
define("_MI_TDMPICTURE_DESCRIPTION","META: Description pages without information for the rest of the pages it will be automatic"); 
define("_MI_TDMPICTURE_KEYWORDS","META: Keywords pages without information, separate words with a space for the rest of the pages it will be automatic"); 
define("_MI_TDMPICTURE_SLIDE_WIDTH","Slideshow Thumbnail: Image width"); 
define("_MI_TDMPICTURE_SLIDE_HEIGTH","Slideshow Thumbnail: Image height"); 
define("_MI_TDMPICTURE_CAT_WIDTH","Category: Image Width");
define("_MI_TDMPICTURE_CAT_HEIGTH","Category: Image Height");
define("_MI_TDMPICTURE_DISPLAY","Order by default");
// block 
define("_AM_TDMPICTURE_BLOCK_DATE","Recent Files"); 
define("_AM_TDMPICTURE_BLOCK_HITS","Most viewed file");
define("_AM_TDMPICTURE_BLOCK_COUNTS","Popular file");
define("_AM_TDMPICTURE_BLOCK_DL","Most downloaded files");
define("_AM_TDMPICTURE_BLOCK_COMMENTS","Most commented file");
// 
define("_AM_TDMPICTURE_SELECT_STYLE","Style of Block"); 
define("_AM_TDMPICTURE_SELECT_TEXT","Text"); 
define("_AM_TDMPICTURE_SELECT_IMAGE","Image"); 
define("_AM_TDMPICTURE_SELECT_SLIDE","Slideshow"); 

// version 1.3
//define("_MI_TDMPICTURE_FULL_WIDTH","Maximum width of the image true");
//define("_MI_TDMPICTURE_FULL_HEIGTH","Maximum height of the real image");
//define("_MI_TDMPICTURE_JAVA_WIDTH","Width of java applet (upload)");
//define("_MI_TDMPICTURE_JAVA_HEIGTH","Height of java applet (upload)");
//define("_MI_TDMPICTURE_CAT_CEL","Category: Number of Column");
//define("_MI_TDMPICTURE_CAT_DISPLAY","Category: Style category");
//define("_MI_TDMPICTURE_CAT_DISPLAY_TEXT","text");
//define("_MI_TDMPICTURE_CAT_DISPLAY_IMG","Image");
//define("_MI_TDMPICTURE_CAT_SOUSCEL","Category: Number of sub-category");
define("_MI_TDMPICTURE_VIEWMYALBUM","See my album");
define("_MI_TDMPICTURE_VIEWALBUM","Album List");
define("_MI_TDMPICTURE_UPLOAD","Upload");
define("_MI_TDMPICTURE_SOCIAL","Show social bar?");
define("_MI_TDMPICTURE_RSS","View RSS?");
define("_MI_TDMPICTURE_PDF","Display PDF?");
//define("_MI_TDMPICTURE_THUMB_DECO","Thumbnail: Decoration Image");

// New
define("_AM_TDMPICTURE_BLOCK_RANDS","Random File");
define("_AM_TDMPICTURE_SELECT_IMAGE_DESC","Image Description ");

// Version 1.5

//define("_MI_TDMPICTURE_CAT_DISPLAY_TEXTIMG","Category and subcategory (with image) ");
//define("_MI_TDMPICTURE_CAT_DISPLAY_SUB","Category and subcategory (without image) ");
//define("_MI_TDMPICTURE_CAT_DISPLAY_SUBIMG","Category and subcategory (with image) ");

//define("_MI_TDMPICTURE_CAT_DISPLAY_NONE","No ");

define("_MI_TDMPICTURE_THUMB_STYLE","Thumbnails: centering");
define("_MI_TDMPICTURE_THUMB_STYLE_CENTER","Center (center width");
define("_MI_TDMPICTURE_THUMB_STYLE_HW","Max width * height");
define("_MI_TDMPICTURE_THUMB_STYLE_H","Max Height");
define("_MI_TDMPICTURE_THUMB_STYLE_W","Max Width");

define("_MI_TDMPICTURE_CAT","Submit album ");

define("_MI_TDMPICTURE_SIZE","Dimensions");
define("_MI_TDMPICTURE_SIZEDESC","Allowed form sizes (Width x Height) separated by | ");