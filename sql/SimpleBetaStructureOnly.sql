-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.24a-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema tle2_dev
--

CREATE DATABASE IF NOT EXISTS simplebetaone;
USE simplebetaone;

--
-- Definition of table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
CREATE TABLE `alerts` (
  `alertId` int(10) unsigned NOT NULL auto_increment,
  `itemID` int(10) unsigned NOT NULL default '0',
  `itemType` varchar(45) NOT NULL default '',
  `message` text NOT NULL,
  `userId` int(10) unsigned default NULL,
  `alerttime` datetime NOT NULL default '0000-00-00 00:00:00',
  `ext_notified` tinyint(1) NOT NULL default '0',
  `state` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`alertId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='stores alerts';

--
-- Dumping data for table `alerts`
--

/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;


--
-- Definition of table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE `calendar` (
  `Id` int(11) NOT NULL auto_increment,
  `projectId` int(11) NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `content` text NOT NULL,
  `createdDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `createdBy` int(11) NOT NULL default '0',
  `startDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `endDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `isTask` tinyint(1) NOT NULL default '0',
  `Location` varchar(200) NOT NULL default '',
  `alarmDelta` varchar(20) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `completed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `calendar`
--

/*!40000 ALTER TABLE `calendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar` ENABLE KEYS */;


--
-- Definition of table `calendarassignments`
--

DROP TABLE IF EXISTS `calendarassignments`;
CREATE TABLE `calendarassignments` (
  `calItemId` int(11) NOT NULL default '0',
  `userId` int(11) NOT NULL default '0',
  `assignmentId` int(10) unsigned NOT NULL auto_increment,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`assignmentId`),
  KEY `ItemIndex` (`calItemId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `calendarassignments`
--

/*!40000 ALTER TABLE `calendarassignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendarassignments` ENABLE KEYS */;


--
-- Definition of table `commentary`
--

DROP TABLE IF EXISTS `commentary`;
CREATE TABLE `commentary` (
  `commentId` int(11) NOT NULL auto_increment,
  `userId` int(11) NOT NULL default '0',
  `subject` varchar(200) NOT NULL default '',
  `comment` text NOT NULL,
  `itemType` varchar(10) NOT NULL default '',
  `itemID` int(11) NOT NULL default '0',
  `commentCreated` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleted` tinyint(1) NOT NULL default '0',
  `admincomment` tinyint(1) NOT NULL default '0',
  `displayname` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`commentId`),
  KEY `ItemLookup` (`itemType`,`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commentary`
--

/*!40000 ALTER TABLE `commentary` DISABLE KEYS */;
/*!40000 ALTER TABLE `commentary` ENABLE KEYS */;


--
-- Definition of table `containers`
--

DROP TABLE IF EXISTS `containers`;
CREATE TABLE `containers` (
  `containerId` varchar(255) NOT NULL default '',
  `Name` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`containerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Containers the Project Defs';

--
-- Dumping data for table `containers`
--

/*!40000 ALTER TABLE `containers` DISABLE KEYS */;
/*!40000 ALTER TABLE `containers` ENABLE KEYS */;


--
-- Definition of table `directory`
--

DROP TABLE IF EXISTS `directory`;
CREATE TABLE `directory` (
  `directoryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `directoryvisible` tinyint(1) NOT NULL default '1',
  `vrvisible` tinyint(1) NOT NULL default '1',
  `projectId` int(10) unsigned NOT NULL default '0',
  `projectRole` varchar(255) NOT NULL default '',
  `linkedprojects` varchar(200) NOT NULL default '',
  `properties` text NOT NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`directoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `directory` MODIFY COLUMN `properties` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;

--
-- Dumping data for table `directory`
--

/*!40000 ALTER TABLE `directory` DISABLE KEYS */;
/*!40000 ALTER TABLE `directory` ENABLE KEYS */;


--
-- Definition of table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `documentUid` int(10) unsigned NOT NULL auto_increment,
  `folderId` int(11) NOT NULL default '0',
  `filename` varchar(100) NOT NULL default '',
  `icon` varchar(200) NOT NULL default '',
  `content` longblob NOT NULL,
  `contenttype` varchar(30) NOT NULL default 'html',
  `sender` varchar(45) NOT NULL default '',
  `recipient` varchar(45) NOT NULL default '',
  `timestamp` varchar(45) NOT NULL default '',
  `hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`documentUid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documents`
--

/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;


--
-- Definition of table `documenttemplates`
--

DROP TABLE IF EXISTS `documenttemplates`;
CREATE TABLE `documenttemplates` (
  `docTemplateUid` int(11) NOT NULL auto_increment,
  `projectTemplateId` int(11) NOT NULL default '0',
  `documentId` varchar(200) NOT NULL default '',
  `filename` varchar(200) NOT NULL default '',
  `contenttype` varchar(200) NOT NULL default '',
  `content` mediumblob NOT NULL,
  `creatorUserId` int(11) NOT NULL default '0',
  `icon` varchar(100) NOT NULL default '',
  `playercansee` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`docTemplateUid`),
  KEY `ProjectResourceIndex` (`projectTemplateId`,`documentId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documenttemplates`
--

/*!40000 ALTER TABLE `documenttemplates` DISABLE KEYS */;

/*!40000 ALTER TABLE `documenttemplates` ENABLE KEYS */;


--
-- Definition of table `eventresources`
--

DROP TABLE IF EXISTS `eventresources`;
CREATE TABLE `eventresources` (
  `eventResourceID` int(10) unsigned NOT NULL auto_increment,
  `projectTemplateID` int(10) unsigned NOT NULL default '0',
  `projectTemplateEventId` varchar(20) NOT NULL default '0',
  `projectTemplateResourceId` varchar(200) NOT NULL default '',
  `fromRole` varchar(45) NOT NULL default '',
  `toRole` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`eventResourceID`),
  KEY `EventResourceIndex` (`projectTemplateID`,`projectTemplateEventId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eventresources`
--

/*!40000 ALTER TABLE `eventresources` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventresources` ENABLE KEYS */;


--
-- Definition of table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE `folders` (
  `folderId` int(10) unsigned NOT NULL auto_increment,
  `projectId` int(10) unsigned NOT NULL default '0',
  `name` varchar(45) NOT NULL default '',
  `allowDeletes` tinyint(1) NOT NULL default '0',
  `additem` tinyint(1) NOT NULL default '1',
  `canBeDeleted` tinyint(1) NOT NULL default '1',
  `icon` varchar(255) NOT NULL default '',
  `trashcan` tinyint(1) default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`folderId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `folders`
--

/*!40000 ALTER TABLE `folders` DISABLE KEYS */;
/*!40000 ALTER TABLE `folders` ENABLE KEYS */;


--
-- Definition of table `itemlinks`
--

DROP TABLE IF EXISTS `itemlinks`;
CREATE TABLE `itemlinks` (
  `id` int(11) NOT NULL auto_increment,
  `sourceItemType` varchar(10) NOT NULL default '',
  `sourceItemId` int(11) NOT NULL default '0',
  `destItemType` varchar(10) NOT NULL default '',
  `destItemId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `itemlinks`
--

/*!40000 ALTER TABLE `itemlinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `itemlinks` ENABLE KEYS */;


--
-- Definition of table `metrics`
--

DROP TABLE IF EXISTS `metrics`;
CREATE TABLE `metrics` (
  `id` int(11) NOT NULL auto_increment,
  `projectId` int(11) default NULL,
  `timestamp` varchar(50) NOT NULL default '',
  `metricName` varchar(100) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `metrics`
--

/*!40000 ALTER TABLE `metrics` DISABLE KEYS */;
/*!40000 ALTER TABLE `metrics` ENABLE KEYS */;


--
-- Definition of table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `pluginName` varchar(100) NOT NULL default '',
  `pluginFile` varchar(100) NOT NULL default '',
  `order` int(11) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '1',
  `sitewide` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pluginName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plugins`
--

/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
INSERT INTO `plugins` (`pluginName`,`pluginFile`,`order`,`enabled`,`sitewide`,`deleted`) VALUES
 ('AthensAuthentication','Athens.php',5,0,1,0),
 ('flashviewerPlugin','flashviewerPlugin.php',3,1,1,0),
 ('LawRssParser','LawRssParser.php',6,1,1,0),
 ('LdapAuthentication','Ldap.php',1,1,1,0),
 ('RadiusAuthentication','Radius.php',2,1,1,0),
 ('testPlugin','testPlugin.php',4,0,0,0);
 --
-- Definition of table `projectgroups`
--

DROP TABLE IF EXISTS `projectgroups`;
CREATE TABLE `projectgroups` (
  `projectGroupId` int(10) unsigned NOT NULL auto_increment,
  `projectgroupname` varchar(45) NOT NULL default '',
  `members` text NOT NULL,
  `creatorId` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`projectGroupId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectgroups`
--

/*!40000 ALTER TABLE `projectgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `projectgroups` ENABLE KEYS */;


--
-- Definition of table `projectpermissions`
--

DROP TABLE IF EXISTS `projectpermissions`;
CREATE TABLE `projectpermissions` (
  `userid` int(11) NOT NULL default '0',
  `usertype` enum('user','group') NOT NULL default 'user',
  `projectid` int(11) NOT NULL default '0',
  `usestafftools` tinyint(1) NOT NULL default '0',
  `deleteanyitem` tinyint(1) NOT NULL default '0',
  `deleteitem` tinyint(1) NOT NULL default '1',
  `viewitem` tinyint(1) NOT NULL default '1',
  `additem` tinyint(1) NOT NULL default '1',
  `editanyitem` tinyint(1) NOT NULL default '0',
  `edititems` tinyint(1) NOT NULL default '1',
  `stopproject` tinyint(1) NOT NULL default '0',
  `editplugin` tinyint(1) NOT NULL default '0',
  `changeuserpermissions` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`,`projectid`,`usertype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
/*!40000 ALTER TABLE `projectpermissions` ENABLE KEYS */;


--
-- Definition of table `projectplugins`
--

DROP TABLE IF EXISTS `projectplugins`;
CREATE TABLE `projectplugins` (
  `projectId` int(10) unsigned NOT NULL default '0',
  `pluginName` varchar(45) NOT NULL default '',
  `enabled` int(1) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`projectId`,`pluginName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectplugins`
--

/*!40000 ALTER TABLE `projectplugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `projectplugins` ENABLE KEYS */;


--
-- Definition of table `projectprogress`
--

DROP TABLE IF EXISTS `projectprogress`;
CREATE TABLE `projectprogress` (
  `eventId` varchar(45) NOT NULL default '',
  `projectid` int(10) unsigned NOT NULL default '0',
  `state` varchar(45) NOT NULL default '',
  `color` varchar(6) NOT NULL default 'ffffff',
  PRIMARY KEY  (`eventId`,`projectid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectprogress`
--

/*!40000 ALTER TABLE `projectprogress` DISABLE KEYS */;

/*!40000 ALTER TABLE `projectprogress` ENABLE KEYS */;


--
-- Definition of table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `projectUid` int(11) NOT NULL auto_increment,
  `projectTemplateId` int(11) NOT NULL default '0',
  `Name` varchar(100) NOT NULL default '',
  `inbox` varchar(45) NOT NULL default '',
  `sentitems` varchar(45) NOT NULL default '',
  `variables` text NOT NULL,
  `IsActive` tinyint(1) NOT NULL default '1',
  `stylesheet` text NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `creatorId` int(10) unsigned NOT NULL default '0',
  `createdDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`projectUid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `projects` MODIFY COLUMN `stylesheet` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;

--
-- Dumping data for table `projects`
--

/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;


--
-- Definition of table `projectsequence`
--

DROP TABLE IF EXISTS `projectsequence`;
CREATE TABLE `projectsequence` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `projectTemplateId` int(10) unsigned NOT NULL default '0',
  `projectTemplateEventId` varchar(20) NOT NULL default '0',
  `Name` varchar(45) NOT NULL default '',
  `itemType` int(10) unsigned NOT NULL default '0',
  `performerRole` varchar(255) NOT NULL default '0',
  `nextEventId` varchar(20) NOT NULL default '0',
  `previousEventId` varchar(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ProjectSequence` (`projectTemplateId`,`projectTemplateEventId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectsequence`
--

/*!40000 ALTER TABLE `projectsequence` DISABLE KEYS */;

/*!40000 ALTER TABLE `projectsequence` ENABLE KEYS */;


--
-- Definition of table `projecttemplatepermissions`
--

DROP TABLE IF EXISTS `projecttemplatepermissions`;
CREATE TABLE `projecttemplatepermissions` (
  `userId` int(10) unsigned NOT NULL default '0',
  `usertype` enum('user','group') NOT NULL default 'user',
  `projectTemplateUid` int(10) unsigned NOT NULL default '0',
  `editdocumenttemplate` tinyint(1) NOT NULL default '0',
  `startproject` tinyint(1) NOT NULL default '0',
  `endproject` tinyint(1) NOT NULL default '0',
  `archiveproject` tinyint(1) NOT NULL default '0',
  `edittemplate` tinyint(1) unsigned NOT NULL default '0',
  `viewtemplate` tinyint(1) NOT NULL default '0',
  `editplugin` tinyint(1) NOT NULL default '0',
  `changeuserpermissions` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userId`,`projectTemplateUid`,`usertype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='stores user rights for templates';

--
-- Dumping data for table `projecttemplatepermissions`
--

/*!40000 ALTER TABLE `projecttemplatepermissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `projecttemplatepermissions` ENABLE KEYS */;


--
-- Definition of table `projecttemplateplugins`
--

DROP TABLE IF EXISTS `projecttemplateplugins`;
CREATE TABLE `projecttemplateplugins` (
  `projectTemplateUid` int(10) unsigned NOT NULL default '0',
  `pluginName` varchar(45) NOT NULL default '',
  `enabled` int(1) unsigned NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`projectTemplateUid`,`pluginName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projecttemplateplugins`
--

/*!40000 ALTER TABLE `projecttemplateplugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `projecttemplateplugins` ENABLE KEYS */;


--
-- Definition of table `projecttemplateroles`
--

DROP TABLE IF EXISTS `projecttemplateroles`;
CREATE TABLE `projecttemplateroles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `projectTemplateId` int(10) unsigned NOT NULL default '0',
  `projectTemplateRoleId` varchar(45) NOT NULL default '',
  `RoleName` varchar(45) NOT NULL default '',
  `namerule` varchar(45) NOT NULL default '',
  `addressrule` varchar(45) NOT NULL default '',
  `locationrule` varchar(45) NOT NULL default '',
  `directoryrule` varchar(45) NOT NULL default '',
  `properties` text NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projecttemplateroles`
--

/*!40000 ALTER TABLE `projecttemplateroles` DISABLE KEYS */;
/*!40000 ALTER TABLE `projecttemplateroles` ENABLE KEYS */;


--
-- Definition of table `projecttemplates`
--

DROP TABLE IF EXISTS `projecttemplates`;
CREATE TABLE `projecttemplates` (
  `projectTemplateUid` int(11) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `properties` text NOT NULL,
  `variables` text NOT NULL,
  `stylesheet` text NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `IsActive` tinyint(1) unsigned NOT NULL default '1',
  `container` varchar(255) NOT NULL default '' COMMENT 'The "Project" this BP belongs to.',
  `version` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`projectTemplateUid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Actually BPs!';

--
-- Dumping data for table `projecttemplates`
--

/*!40000 ALTER TABLE `projecttemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `projecttemplates` ENABLE KEYS */;


--
-- Definition of table `projectvariables`
--

DROP TABLE IF EXISTS `projectvariables`;
CREATE TABLE `projectvariables` (
  `projectid` int(10) unsigned NOT NULL auto_increment,
  `roleName` varchar(45) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`projectid`,`roleName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectvariables`
--

/*!40000 ALTER TABLE `projectvariables` DISABLE KEYS */;
/*!40000 ALTER TABLE `projectvariables` ENABLE KEYS */;


--
-- Definition of table `readitems`
--

DROP TABLE IF EXISTS `readitems`;
CREATE TABLE `readitems` (
  `itemID` int(10) unsigned NOT NULL default '0',
  `ItemType` varchar(10) NOT NULL default '',
  `userId` int(10) unsigned NOT NULL default '0',
  `isRead` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`itemID`,`ItemType`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `readitems`
--

/*!40000 ALTER TABLE `readitems` DISABLE KEYS */;

/*!40000 ALTER TABLE `readitems` ENABLE KEYS */;


--
-- Definition of table `sitewidepermissions`
--

DROP TABLE IF EXISTS `sitewidepermissions`;
CREATE TABLE `sitewidepermissions` (
  `userid` int(11) NOT NULL default '0',
  `usertype` enum('user','group') NOT NULL default 'user',
  `adduser` tinyint(1) NOT NULL default '0',
  `edituser` tinyint(1) NOT NULL default '0',
  `makelevelzerouser` tinyint(1) NOT NULL default '0',
  `addplugin` tinyint(1) NOT NULL default '0',
  `installtemplate` tinyint(1) NOT NULL default '0',
  `edittemplate` tinyint(1) NOT NULL default '0',
  `removetemplate` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`,`usertype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sitewidepermissions`
--

/*!40000 ALTER TABLE `sitewidepermissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sitewidepermissions` ENABLE KEYS */;


--
-- Definition of table `taskassignments`
--

DROP TABLE IF EXISTS `taskassignments`;
CREATE TABLE `taskassignments` (
  `taskId` int(11) NOT NULL default '0',
  `userId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`taskId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskassignments`
--

/*!40000 ALTER TABLE `taskassignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskassignments` ENABLE KEYS */;


--
-- Definition of table `user2usergroup`
--

DROP TABLE IF EXISTS `user2usergroup`;
CREATE TABLE `user2usergroup` (
  `groupid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`groupid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user2usergroup`
--

/*!40000 ALTER TABLE `user2usergroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `user2usergroup` ENABLE KEYS */;


--
-- Definition of table `userauth`
--

DROP TABLE IF EXISTS `userauth`;
CREATE TABLE `userauth` (
  `authMethod` varchar(100) NOT NULL default '',
  `externalId` varchar(100) NOT NULL default '',
  `internalId` int(11) NOT NULL default '0',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`authMethod`,`externalId`,`internalId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userauth`
--

/*!40000 ALTER TABLE `userauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `userauth` ENABLE KEYS */;


--
-- Definition of table `usergroups`
--

DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE `usergroups` (
  `groupid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(45) NOT NULL default '',
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usergroups`
--

/*!40000 ALTER TABLE `usergroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `usergroups` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `password` text NOT NULL,
  `displayname` varchar(200) NOT NULL default '',
  `active` int(1) NOT NULL default '1',
  `email` varchar(200) default NULL,
  `regnumber` int(10) unsigned default NULL,
  `properties` text NOT NULL,
  `superadmin` tinyint(1) NOT NULL default '0',
  `salt` text NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`),
  KEY `Login` USING BTREE (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `simplebetaone`.`users` MODIFY COLUMN `properties` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
ALTER TABLE `simplebetaone`.`users` MODIFY COLUMN `salt` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`userid`,`username`,`password`,`displayname`,`active`,`email`,`regnumber`,`properties`,`superadmin`,`salt`,`deleted`) VALUES 
 (1,'michael','33ebe67772d09dc06f623c764976b850','Michael Hughes',1,'michaelhughes@strath.ac.uk',NULL,'tooltips=0',1,'ce25310f592809325fce0295f64e7f1f',0),
 (2, 'paul', 'bd659de731ee2aa57a517e944d381c41', 'Paul Maharg', 1, 'paul.maharg@strath.ac.uk', 0, '', 1, '833ee99cc2e2b2fe8b82ee7daf86aed9', 0),
 (3, 'super', '6fce215b4eac5407a0b361eab55d1974', 'Super Admin', 1, 'super@yourdomain.com', 0, '', 1, '833ee99cc2e2b2fe8b82ee7daf86aed9', 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
