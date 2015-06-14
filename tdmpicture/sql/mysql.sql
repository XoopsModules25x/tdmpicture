
CREATE TABLE `tdmpicture_file` (
  `file_id` int(11) unsigned NOT NULL auto_increment,
  `file_cat` int(11) unsigned NOT NULL default '0',
  `file_file` text,
  `file_title` varchar(255) NOT NULL default '',
  `file_text` text,
  `file_type` varchar(255) default NULL,
  `file_display` int(1) NOT NULL default '0',
  `file_hits` int(11) unsigned NOT NULL default '0',
  `file_dl` int(11) unsigned NOT NULL default '0',
  `file_votes` int(11) unsigned NOT NULL default '0',
  `file_counts` int(11) unsigned NOT NULL default '0',
  `file_indate` int(11) unsigned NOT NULL default '0',
  `file_uid` int(11) NOT NULL,
  `file_size` int(11) default NULL,
  `file_res_x` int(11) NOT NULL,
  `file_res_y` int(11) NOT NULL,
  `file_comments` int(11) unsigned NOT NULL default '0', 
`file_ext` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM;

CREATE TABLE `tdmpicture_cat` (
  `cat_id` int(11) unsigned NOT NULL auto_increment,
  `cat_pid` int(11) unsigned NOT NULL default '0',
  `cat_title` varchar(50) NOT NULL default '',
  `cat_date` int(11) NOT NULL default '0',
  `cat_text` text,
  `cat_img` varchar(100) default NULL,
  `cat_weight` int(11) unsigned NOT NULL default '0',
  `cat_display` int(1) NOT NULL default '0',
  `cat_uid` int(11) unsigned NOT NULL default '0',
  `cat_index` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM;

CREATE TABLE `tdmpicture_pl` (
  `pl_id` mediumint(8) unsigned NOT NULL auto_increment,
  `pl_uid` int(10) unsigned NOT NULL default '0',
  `pl_file` int(10) unsigned NOT NULL default '0',
  `pl_album` int(10) unsigned NOT NULL default '0',
  `pl_artiste` int(10) unsigned NOT NULL default '0',
  `pl_genre` int(10) unsigned NOT NULL default '0',
  `pl_num` int(10) unsigned NOT NULL default '0',
  `pl_title` varchar(255) NOT NULL default '',
  `pl_display` int(1) NOT NULL default '0',
  `pl_hits` int(10) unsigned NOT NULL default '0',
  `pl_votes` int(10) unsigned NOT NULL default '0',
  `pl_counts` int(10) unsigned NOT NULL default '0',
  `pl_indate` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pl_id`)
) ENGINE=MyISAM;

CREATE TABLE `tdmpicture_vote` (
  `vote_id` int(8) unsigned NOT NULL auto_increment,
  `vote_file` int(10) unsigned NOT NULL default '0',
  `vote_album` int(10) unsigned NOT NULL default '0',
  `vote_artiste` int(10) unsigned NOT NULL default '0',
  `vote_ip` varchar(20) default NULL,
  PRIMARY KEY  (`vote_id`)
) ENGINE=MyISAM;