ALTER TABLE `tdmpicture_file`
  ADD `file_ext` INT(11) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `tdmpicture_cat`
  MODIFY cat_img VARCHAR(150);
