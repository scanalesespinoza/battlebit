CREATE TABLE IF NOT EXISTS `#__fabanner` (
  `bannerid` int(11) NOT NULL auto_increment,
  `clientid` int(11) default NULL,
  `linkid` int(11) default NULL,
  `sizeid` int(11) default NULL,
  `imageurl` varchar(255) default NULL,
  `imagealt` varchar(255) default NULL,
  `customcode` text,
  `restrictbyid` tinyint(1) NOT NULL default '0',
  `frontpage` tinyint(1) default NULL,
  `clicks` int(11) NOT NULL default '0',
  `impressions` int(11) NOT NULL default '0',
  `startdate` date default NULL,
  `enddate` date default NULL,
  `maximpressions` int(11) default NULL,
  `maxclicks` int(11) default NULL,
  `dailyimpressions` int(11) default '0',
  `lastreset` date default NULL,
  `published` tinyint(1) default '0',
  `finished` tinyint(1) default '0',
  `checked_out` tinyint(1) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  `juserid` INTEGER default NULL,
  PRIMARY KEY  (`bannerid`)
) ENGINE=MyISAM;
ALTER TABLE `#__fabanner` MODIFY COLUMN `imagealt` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci default NULL;
CREATE TABLE IF NOT EXISTS `#__fabannerin` (
  `bannerid` int(11) default NULL,
  `sectionid` int(11) default NULL,
  `categoryid` int(11) default NULL,
  `contentid` int(11) default NULL,
  PRIMARY KEY  (`bannerid`)
  ) ENGINE=MyISAM;
CREATE TABLE IF NOT EXISTS `#__fabannerlang` (
  `bannerid` int(11) default NULL,
  `languageid` varchar(5) default NULL,
  PRIMARY KEY  (`bannerid`)
  ) ENGINE=MyISAM;
ALTER TABLE `#__fabannerlang` MODIFY `languageid` varchar(5);
CREATE TABLE IF NOT EXISTS `#__fabannerlocation` (
  `bannerid` int(11) NOT NULL,
  `locationid` int(11) NOT NULL,
  PRIMARY KEY  (`bannerid`,`locationid`),
  KEY `both` (`bannerid`,`locationid`),
  KEY `backwards` (`locationid`,`bannerid`)
) ENGINE=MyISAM;
CREATE TABLE IF NOT EXISTS `#__faclient` (
  `clientid` int(11) NOT NULL auto_increment,
  `clientname` varchar(255) default NULL,
  `contactname` varchar(255) default NULL,
  `contactemail` varchar(255) default NULL,
  `barred` tinyint(1) NOT NULL default '0',
  `checked_out` tinyint(1) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  PRIMARY KEY  (`clientid`),
  UNIQUE KEY `uniquename` (`clientname`)
) ENGINE=MyISAM;
CREATE TABLE IF NOT EXISTS `#__falink` (
  `linkid` int(11) NOT NULL auto_increment,
  `clientid` int(11) NOT NULL,
  `linkurl` text,
  `checked_out` tinyint(1) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  PRIMARY KEY  (`linkid`)
) ENGINE=MyISAM;
CREATE TABLE IF NOT EXISTS `#__falocation` (
  `locationid` int(11) NOT NULL auto_increment,
  `locationname` varchar(50) default NULL,
  `checked_out` tinyint(1) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  PRIMARY KEY  (`locationid`)
) ENGINE=MyISAM;
DROP TABLE IF EXISTS `#__falocationsize`;
CREATE TABLE IF NOT EXISTS `#__fasize` (
  `sizeid` int(11) NOT NULL auto_increment,
  `sizename` varchar(50) default NULL,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  `maxfilesize` int(11) default NULL,
  `checked_out` tinyint(1) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  PRIMARY KEY  (`sizeid`)
) ENGINE=MyISAM;
INSERT IGNORE INTO `#__fasize` VALUES ('1', 'Full Banner', '468', '60', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('2', 'Half Banner', '234', '60', '15000', '0', '0000-00-00 00:00:00', null);
INSERT IGNORE INTO `#__fasize` VALUES ('3', 'Micro Bar', '88', '31', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('4', 'Button 1', '120', '90', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('5', 'Button 2', '120', '60', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('6', 'Vertical Banner', '120', '240', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('7', 'Square Button', '125', '125', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('8', 'Leaderboard', '728', '90', '20000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('9', 'Wide Skyscraper', '160', '600', '20000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('10', 'Skyscraper', '120', '600', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('11', 'Half Page Ad', '300', '600', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('12', 'Medium Rectangle', '300', '250', '20000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('13', 'Square Pop-up', '250', '250', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('14', 'Vertical Rectangle', '240', '400', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('15', 'Large Rectangle', '336', '280', '15000', '0', '0000-00-00 00:00:00', '');
INSERT IGNORE INTO `#__fasize` VALUES ('16', 'Rectangle', '180', '150', '15000', '0', '0000-00-00 00:00:00', '');
