CREATE TABLE `tdmpicture_file` (
  `file_id`       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_cat`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_file`     TEXT,
  `file_title`    VARCHAR(255)     NOT NULL DEFAULT '',
  `file_text`     TEXT,
  `file_type`     VARCHAR(255)              DEFAULT NULL,
  `file_display`  INT(1)           NOT NULL DEFAULT '0',
  `file_hits`     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_dl`       INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_votes`    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_counts`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_indate`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_uid`      INT(11)          NOT NULL,
  `file_size`     INT(11)                   DEFAULT NULL,
  `file_res_x`    INT(11)          NOT NULL,
  `file_res_y`    INT(11)          NOT NULL,
  `file_comments` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `file_ext`      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`file_id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmpicture_cat` (
  `cat_id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_pid`     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `cat_title`   VARCHAR(50)      NOT NULL DEFAULT '',
  `cat_date`    INT(11)          NOT NULL DEFAULT '0',
  `cat_text`    TEXT,
  `cat_img`     VARCHAR(100)              DEFAULT NULL,
  `cat_weight`  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `cat_display` INT(1)           NOT NULL DEFAULT '0',
  `cat_uid`     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `cat_index`   INT(1) UNSIGNED  NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmpicture_pl` (
  `pl_id`      MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pl_uid`     INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_file`    INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_album`   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_artiste` INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_genre`   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_num`     INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_title`   VARCHAR(255)          NOT NULL DEFAULT '',
  `pl_display` INT(1)                NOT NULL DEFAULT '0',
  `pl_hits`    INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_votes`   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_counts`  INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `pl_indate`  INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  PRIMARY KEY (`pl_id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmpicture_vote` (
  `vote_id`      INT(8) UNSIGNED  NOT NULL AUTO_INCREMENT,
  `vote_file`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_album`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_artiste` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_ip`      VARCHAR(20)               DEFAULT NULL,
  PRIMARY KEY (`vote_id`)
)
  ENGINE = MyISAM;
