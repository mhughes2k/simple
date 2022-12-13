<?php 
/**
 * @package Install
 */

/*
    Copyright 2007, 2008 University of Strathclyde
        
    This file is part of the SIMPLE Platform.

    The SIMPLE Platform is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    The SIMPLE Platform is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the SIMPLE Platform.  If not, see <http://www.gnu.org/licenses/>.
    
*/

if (!defined('doDbCreate')) {
	die('This script should only be run as part of your installation. Running this
	script now will <b>destroy</b> any configured SIMPLE:plaform installation!');
}
 
try {
	$dbh = new PDO('mysql:host='.$_SESSION['setupinfo']['dbhost'].';port='.$_SESSION['setupinfo']['dbport'], $_SESSION['setupinfo']['dbuser'], $_SESSION['setupinfo']['dbpassword']);  
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}	

creating('Dropped DB (this step should be removed from the live script!)');
 try {
	$dbh->exec("DROP DATABASE IF EXISTS ".$_SESSION['setupinfo']['dbname'].";");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}

creating('Database');
try {
	$dbh->exec("CREATE DATABASE ".$_SESSION['setupinfo']['dbname'].";");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}
  
creating('Selecting Created Database');
try {
	$dbh->exec("USE ".$_SESSION['setupinfo']['dbname'].";");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}

 echo '<hr>';
 //System Authentication stuff
 //Create users table
 $usersOpts = array();
 $usersDef = array(
    'userid' => array('type'=>'integer','unsigned'=>1,'notnull'=>1),
    'username' => array('type'=>'text','length'=>100,'notnull'=>1),
    'password' => array('type'=>'text','length'=>100,'notnull'=>1),
    'displayname' => array('type'=>'text','length'=>200),
    'blurb' => array('type'=>'clob'),
    'active' => array('type'=>'boolean','default'=>1),
    'email' => array('type'=>'text','length'=>200),
    'regnumber' => array('type'=>'text','length'=>10),
    'properties' => array('type'=>'clob'),
	'dashboard_json' => array('type'=>'clob'),
    'superadmin' => array('type'=>'boolean','default'=>0),
    'salt' => array('type'=>'text'),
    'avatar' => array('type'=>'blob'),
    'imagetype' =>array('type'=>'text','length'=>20,'notnull'=>0),
    'deleted' => array('type'=>'boolean','default'=>0),
    'notifyonsend' => array('type'=>'boolean','default'=>1),
    'notifyonreceive' =>array('type'=>'boolean','default'=>1)
 );
 
creating('Users table');
try {
	$dbh->exec("CREATE TABLE users (
	userid int(11) NOT NULL AUTO_INCREMENT, 
	username varchar(100) NOT NULL,
	password varchar(100) NOT NULL,
	displayname varchar(200),
	blurb text,
	active boolean default '1',		
	email varchar(200),
	regnumber varchar(20),
	properties text,
	dashboard_json text,
	superadmin boolean default '0',
	salt varchar(255),
	avatar blob,
	imagetype varchar(20),
	deleted boolean default '0',
	notifyonsend boolean default '1',
	notifyonreceive boolean default '1',
	PRIMARY KEY (`userid`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}
 
creating('usergroups');
try {
	$dbh->exec("CREATE TABLE usergroups (
	groupid int(11) NOT NULL AUTO_INCREMENT, 
	name varchar(100) NOT NULL,
	active boolean default '1',		
	deleted boolean default '0',		
	PRIMARY KEY (`groupid`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}
 
creating('userauth'); 
try {
	$dbh->exec("CREATE TABLE userauth (
	authmethod varchar(100) NOT NULL, 
	externalid varchar(100) NOT NULL,
	internalid int(11) NOT NULL,		
	deleted boolean default '0',		
	PRIMARY KEY (`authmethod`,`externalid`,`internalid`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}

creating('user2usergroup');
try {
	$dbh->exec("CREATE TABLE user2usergroup (
	groupid int(11) NOT NULL, 
	userid int(11) NOT NULL,
	deleted boolean default '0',		
	PRIMARY KEY (`groupid`,`userid`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

//create site_news table
creating('site_news');
try {
	$dbh->exec("CREATE TABLE site_news (
	id int(11) NOT NULL AUTO_INCREMENT, 
	timestamp varchar(50),
	title varchar(255),
	text text,
	PRIMARY KEY (`id`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}   

creating('site_settings');
try {
	$dbh->exec("CREATE TABLE site_settings (
	id int(11) NOT NULL AUTO_INCREMENT, 
	language varchar(20),
	theme varchar(20),
	group_name varchar(30),
	group_name_plural varchar(30),
	simulation_name varchar(30),
	simulation_name_plural varchar(30),
	val_help_url varchar(255),
	login_page_content text,
	emailmodule_mentors int,
	emailmodule_learners int,
	PRIMARY KEY (`id`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}    

try {
	$dbh->exec("INSERT INTO site_settings VALUES(1, 'en-gb', 'metallic', 'firm', 'firms', 'transaction', 'transactions', '', '', '1', '0');");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}

creating('sitewidepermissions');
try {
	$dbh->exec("CREATE TABLE sitewidepermissions (
	userid int(11), 
	usertype varchar(10) default 'user',
	adduser boolean default '0',
	edituser boolean default '0',
	makelevelzerouser boolean default '0',
	addplugin boolean default '0',
	installtemplate boolean default '0',
	edittemplate boolean default '0',
	removetemplate boolean default '0',
	usestafftools boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (`userid`,`usertype`));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}   

creating('plugins');
try {
	$dbh->exec("CREATE TABLE plugins (
	pluginname varchar(100) NOT NULL,
	pluginfile varchar(100),
	pluginorder int(11) default '0',
	enabled boolean default '0',
	sitewide boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (pluginname));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

creating('metrics');
try {
	$dbh->exec("CREATE TABLE metrics (
	id INT(11) NOT NULL AUTO_INCREMENT,
	projectid INT(11),
	timestamp VARCHAR(50),
	metricname VARCHAR(100),
	value TEXT,
	PRIMARY KEY (id));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

creating('containers');
try {
	$dbh->exec("CREATE TABLE containers (
	containerid INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(50),
	deleted boolean default '0',
	PRIMARY KEY (containerid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

creating('projecttemplates');
try {
	$dbh->exec("CREATE TABLE projecttemplates (
	projecttemplateuid INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(100),
	properties TEXT,
	variables TEXT,
	stylesheet TEXT,
	deleted boolean default '0',
	isactive boolean default '1',
	container VARCHAR(255),
	version INT(11) default '0',
	PRIMARY KEY (projecttemplateuid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

creating('projecttemplateroles'); 
try {
	$dbh->exec("CREATE TABLE projecttemplateroles (
	id INT(11) NOT NULL AUTO_INCREMENT,
	projecttemplateid INT(11),
	projecttemplateroleid VARCHAR(255),
	rolename VARCHAR(200),
	namerule VARCHAR(200),
	addressrule VARCHAR(200),
	locationrule VARCHAR(200),
	directoryrule VARCHAR(200),
	properties TEXT,
	deleted boolean default '0',
	PRIMARY KEY (id));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

creating('projecttemplateplugins');
try {
	$dbh->exec("CREATE TABLE projecttemplateplugins (
	projecttemplateuid INT(11) NOT NULL,
	pluginname VARCHAR(50),
	enabled boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (projecttemplateuid, pluginname));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}  

creating('projecttemplatepermissions');
try {
	$dbh->exec("CREATE TABLE projecttemplatepermissions (
	userid INT(11) default '0',
	usertype VARCHAR(10) default 'user',
	projecttemplateuid INT(11),
	editdocumenttemplate boolean default '0',
	archiveproject boolean default '0',
	startproject boolean default '0',
	endproject boolean default '0',
	viewtemplate boolean default '0',
	editplugin boolean default '0',
	changeuserpermissions boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (userid, usertype, projecttemplateuid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 

creating('projectsequence');
try {
	$dbh->exec("CREATE TABLE projectsequence (
	id INT(11) NOT NULL AUTO_INCREMENT,
	projecttemplateid INT(11),
	projecttemplateeventid VARCHAR(50),
	name VARCHAR(100),
	itemtype INT(11),
	performerrole VARCHAR(255),
	nexteventid VARCHAR(50),
	previouseventid VARCHAR(50),
	deleted boolean default '0',
	PRIMARY KEY (id));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 

creating('eventresources table');
try {
	$dbh->exec("CREATE TABLE eventresources (
	eventresourceid INT(11) NOT NULL AUTO_INCREMENT,
	projecttemplateid INT(11),
	projecttemplateeventid VARCHAR(20),
	projecttemplateresourceid VARCHAR(200),
	fromrole VARCHAR(50),
	torole VARCHAR(50),
	deleted boolean default '0',
	PRIMARY KEY (eventresourceid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 

creating('documenttemplates');
try {
	$dbh->exec("CREATE TABLE documenttemplates (
	doctemplateuid INT(11) NOT NULL AUTO_INCREMENT,
	projecttemplateid INT(11),
	documentid VARCHAR(200),
	filename VARCHAR(200),
	visiblename VARCHAR(200),
	contenttype VARCHAR(200),
	content MEDIUMBLOB,
	creatoruserid INT(11) default '0',
	icon VARCHAR(100),
	playercansee boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (doctemplateuid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 

creating('projects');
try {
	$dbh->exec("CREATE TABLE projects (
	projectuid INT(11) NOT NULL AUTO_INCREMENT,
	projecttemplateid INT(11),
	name VARCHAR(100),
	inbox VARCHAR(50),
	sentitems VARCHAR(50),
	variables TEXT,
	isactive boolean default '1',
	stylesheet TEXT,
	deleted boolean default '0',
	creatorid INT(11) default '0',
	createddate DATETIME,
	PRIMARY KEY (projectuid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 

creating('projectvariables');
try {
	$dbh->exec("CREATE TABLE projectvariables (
	projectid INT(11),
	rolename VARCHAR(50),
	value TEXT,
	deleted boolean default '0',
	PRIMARY KEY (projectid,rolename));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 	

creating('projectpermissions'); 
try {
		$dbh->exec("CREATE TABLE projectpermissions (
		userid INT(11),
		usertype VARCHAR(10),
		projectid INT(11),
		usestafftools boolean default '0',
		deleteanyitem boolean default '0',
		deleteitem boolean default '1',
		viewitem boolean default '1',
		additem boolean default '1',
		editanyitem boolean default '0',
		edititems boolean default '1',
		stopproject boolean default '0',
		editplugin boolean default '0',
		changeuserpermissions boolean default '0',
		deleted boolean default '0',
		PRIMARY KEY (userid,usertype,projectid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 		

creating('projectprogress');
try {
	$dbh->exec("CREATE TABLE projectprogress (
	eventid VARCHAR(50),
	projectid INT(11),
	state VARCHAR(50),
	color VARCHAR(6),
	deleted boolean default '0',
	PRIMARY KEY (eventid,projectid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 		

creating('projectplugins');
try {
	$dbh->exec("CREATE TABLE projectplugins (
	projectid INT(11),
	pluginname VARCHAR(50),
	enabled boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (projectid,pluginname));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 		

creating('projectgroups');
try {
	$dbh->exec("CREATE TABLE projectgroups (
	projectgroupid INT(11) NOT NULL AUTO_INCREMENT,
	projectgroupname VARCHAR(50),
	members TEXT,
	creatorid INT(11) default '0',
	deleted boolean default '0',
	PRIMARY KEY (projectgroupid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 			

creating('folders');
try {
	$dbh->exec("CREATE TABLE folders (
	folderid INT(11) NOT NULL AUTO_INCREMENT,
	projectid INT(11),
	name TEXT,
	allowdeletes boolean default '0',
	additem boolean default '0',
	canbedeleted boolean default '0',
	icon VARCHAR(255),
	trashcan boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (folderid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 	

creating('documents');
try {
	$dbh->exec("CREATE TABLE documents (
	documentuid INT(11) NOT NULL AUTO_INCREMENT,
	folderid INT(11),
	filename VARCHAR(200),
	icon VARCHAR(200),
	content MEDIUMBLOB,
	contenttype VARCHAR(200),
	sender VARCHAR(200),
	recipient VARCHAR(200),
	timestamp VARCHAR(20),
	hidden boolean default '0',
	deleted boolean default '0',
	PRIMARY KEY (documentuid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 		

creating('directory');
try {
	$dbh->exec("CREATE TABLE directory (
	directoryid INT(11) NOT NULL AUTO_INCREMENT,
	projectid INT(11),
	name VARCHAR(255),
	address VARCHAR(255),
	location VARCHAR(255),
	directoryvisible VARCHAR(255) default '1',
	vrvisible VARCHAR(255) default '1',
	projectrole VARCHAR(255),
	linkedprojects VARCHAR(255),
	properties TEXT,
	deleted boolean default '0',
	PRIMARY KEY (directoryid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 		

creating('commentary');
try {
	$dbh->exec("CREATE TABLE commentary (
	commentid INT(11) NOT NULL AUTO_INCREMENT,
	userid INT(11),
	subject VARCHAR(200),
	comment TEXT,
	itemtype VARCHAR(10),
	itemid INT(11),
	commentcreated DATETIME,
	deleted boolean default '0',
	admincomment boolean default '0',
	displayname VARCHAR(45),
	PRIMARY KEY (commentid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 		

creating('calendarassignments');
try {
	$dbh->exec("CREATE TABLE calendarassignments (
	calitemid INT(11) NOT NULL,
	userid INT(11),
	assignmentid INT(11) NOT NULL AUTO_INCREMENT,
	deleted boolean default '0',
	PRIMARY KEY (assignmentid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 

creating('calendar');
try {
	$dbh->exec("CREATE TABLE calendar (
	id INT(11) NOT NULL AUTO_INCREMENT,
	projectid INT(11),
	title VARCHAR(200),
	content TEXT,
	createddate VARCHAR(20),
	createdby INT(11),
	startdate VARCHAR(20),
	enddate VARCHAR(20),
	istask boolean default '0',
	location VARCHAR(200),
	alarmdelta VARCHAR(20),
	deleted boolean default '0',
	completed boolean default '0',
	PRIMARY KEY (id));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 	

creating('alerts');
try {
$dbh->exec("CREATE TABLE alerts (
	alertid INT(11) NOT NULL AUTO_INCREMENT,
	itemid INT(11),
	itemtype VARCHAR(45),
	message TEXT,
	userid INT(11),
	alerttime DATETIME,
	ext_notified boolean default '0',
	state INT(11),
	deleted boolean default '0',
	title VARCHAR(100),
	PRIMARY KEY (alertid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
} 	

creating('itemlinks');
try {
	$dbh->exec("CREATE TABLE itemlinks (
	id INT(11) NOT NULL AUTO_INCREMENT,
	sourceitemtype VARCHAR(10),
	sourceitemid INT(11),
	destitemtype VARCHAR(10),
	destitemid INT(11),
	PRIMARY KEY (id));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}	

creating('readitems');
try {
	$dbh->exec("CREATE TABLE readitems (
	itemid INT(11),
	itemtype VARCHAR(10),
	userid INT(11),
	isread boolean default '0',
	PRIMARY KEY (itemid,itemtype,userid));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}	

creating('userprefs');
try {
$dbh->exec("CREATE TABLE userprefs (
	userid INT(11),
	prefname VARCHAR(45),
	value VARCHAR(100),
	PRIMARY KEY (userid,prefname));");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}	

message('Creating super user account');
try {
	$superID = "1";
	$dbh->exec("INSERT INTO users VALUES (".
	$superID.", 'super', '6fce215b4eac5407a0b361eab55d1974', 'Super Admin','', 1, 'super@yourdomain.com', 0, '', '', 1, '833ee99cc2e2b2fe8b82ee7daf86aed9', '', '',0,0,0)");
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br/>";
	die();
}

message('Phew! Completed');
?>
