/* MY ALBUM IMPORT FOR FILE */

INSERT INTO `tdmpicture_file` (
	`file_id`,
	`file_cat`,
	`file_file`,
	`file_title`,
	`file_text`,
	`file_type`,
	`file_display`,
	`file_hits`,
	`file_dl`,
	`file_votes`,
	`file_counts`,
	`file_indate`,
	`file_uid`,
	`file_size`,
	`file_res_x`,
	`file_res_y`,
	`file_comments`,
	`file_ext`)
	SELECT  NULL, 
	`cid` ,
	CONCAT(lid, '.', ext),
	`title`,
	NULL,
	`ext`, 
	`status`,
	`hits`,
	NULL,
	`votes`,
    `rating`,
	`date`, 
	`submitter`,
	NULL,
	`res_x` ,
	`res_y`,  
	`comments`,
	1
	FROM `myalbum_photos`
	
/* MY ALBUM IMPORT FOR CAT */



INSERT INTO `tdmpicture_cat` (
  `cat_id`,
  `cat_pid`,
  `cat_title`,
  `cat_date`,
  `cat_text`,
  `cat_img`,
  `cat_weight`,
  `cat_display`,
  `cat_uid`,
  `cat_index`)
SELECT
  cid,
  pid,
  title,
  NULL,
  NULL,
  imgurl,
  NULL,
  1,
  1,
  1
 FROM myalbum_cat
 
 
 
 
 
  

 
  
  
