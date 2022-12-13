<?php
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

/**
 * Contains Define statements for all of the constants used internally.
 *
 * @author Michael Hughes
 * @package TLE2
 */
 
 if (!defined("TLE2")) die ("Invalid Entry Point");
 /** SIMPLE Version
  */
  define('SIMPLE_VERSION','2.6');   
 
/**
* The ProjectId constant. Used to retrieve the identifier of the current project from the $_SESSION variable.
*/
	define('PROJECT_ID','projectId');
/**
*	ProjectTemplateId constant, used to retrieve the Primary key of the current project's template.
*/	define('PROJECT_TEMPLATE_ID','projectTemplateId');
/**
* 	User constant. Used to retrieve the current user object from the $_SESSION variable.
*/	
	define('USER','user', false);		
/**
* 	DisplayName constant. Used to retrieve the display name of the current user from the user object .
*/	
	define('DISPLAY_NAME','displayName', false);		

/**
* Directory that the Smarty Template System is installed.
*/
	define('SMARTY_DIR','include/smarty/');
/**
* The constant to retrieve directory that SIMPLE is installed in from $config.
*/
	define('INSTALL_DIRECTORY','installDir');
/**
 * 
 */
 	define('PLUGIN_DIRECTORY','pluginsDir');	
/**
 * 
 */
	define('XML_HTMLSAX3', "include/");
	
 /**
 *
 */	
	define('DEFAULT_OPTION',"");
 /**
 * 
 */
	define('DEFAULT_COMMAND',"");
	/**
	 * Projects which are archived have the IsActive flag set to 0;
	 */	
	define("ARCHIVED",0);
/**
 * Configuration Key to store the name of the application.
 */
	define('PLATFORM_NAME','platform_name');
	
	define('DATABASE_TYPE','dbType');
	define('DATABASE_HOST','dbHost');
	define('DATABASE_NAME','dbDatabase');
	define('DATABASE_USERNAME','dbUser');
	define('DATABASE_PASSWORD','dbPassword');
	define('DATABASE_PORT','dbPort');

	/**
	 * Turns on a load of debugging information
	 */
	define('DEBUG','debug');
	define('DEBUG_ALLOW_DUMPS','allowArrayDumps');

	/**
	 * 	Disables automatic redirection.
	 */
	define('DEBUG_LOCKSTEP','lockstep');
	
	define('TOWN_NAME','townName'); //is this unused?
	
	/**
	 * Defines for different document types
	 */    	
	define('DOC_TYPE_DOC','doc');
	define('DOC_TYPE_CALENDAR','calendar');
	define('DOC_TYPE_TEMPLATE','doc_templ');
	
	define('WASTE_BASKET',-2);
	
	define('PROJECT_TEMPLATE_ITEM_TYPE_CRITICAL_EVENT',0);
	define('PROJECT_TEMPLATE_ITEM_TYPE_TASK',1);
	define('PROJECT_TEMPLATE_ITEM_TYPE_EXTERNAL',2);
	
	define('PROJECT_TEMPLATE_CHARACTER_PLAYER',"CHAR_PLAYER");
	define('PROJECT_TEMPLATE_CHARACTER_STAFF',"CHAR_STAFF");
	define('PROJECT_TEMPLATE_CHARACTER_NO_ONE',"CHAR_NO_ONE");
	define('PROJECT_EVENT_START_EVENT', "--ProjectStartEvent--");
	
	define('PROJECT_TEMPLATE_FOLDER_INBOX',"INBOX");
	define('PROJECT_TEMPLATE_FOLDER_SENT_ITEMS',"SENT");
	define('PROJECT_TEMPLATE_FOLDER_DRAFTS',"DRAFTS");
	
	define('PROJECT_VISIBILITY_HIDDEN',0);
	define('PROJECT_VISIBILITY_VISIBLE',1);
	
	define('NOTSET',0);
	define('DENY',-1);
	define('ALLOW',1);
	
	/**
	 * Constant to restrict list of Document Tempaltes pulled out 
	 * to only ones that the Player can see.
	 */
	define('PLAYER_TEMPLATES_ONLY','player');
	define('ALL_TEMPLATES','all');
	/*
	 * Permission Names
	 * 
	 */
	 /**
	  * Use may view (butnot change) a Template.
	  */
	define('PERMISSION_VIEW_TEMPLATE','ViewTemplate');
	/**
	 * Use may edit a Template's settings.
	 */	
	define('PERMISSION_EDIT_TEMPLATE','EditTemplate');
	/**
	 * Use may edit a template's documents
	 */
	define('PERMISSION_EDIT_DOCUMENT_TEMPLATE','EditDocumentTemplate');
	/**
	 * Use may start/create projects using template.
	 */
	define('PERMISSION_START_PROJECT','StartProject');
	/**
	 * User may end all projects based on a template.
	 */
	define('PERMISSION_END_PROJECT','EndProject');
	/**
	 * Use may archive projects based on template.
	 */
	define('PERMISSION_ARCHIVE_PROJECT','ArchiveProject');
	
	define('DEFAULT_EXCERPT_LENGTH',100);
	define('DEFAULT_NEWS_FEED_ITEMS',5);
	/**
	 * Defines the maximum # user accounts in Community mode
	 */   	
	define('COMMUNITY_USER_LIMIT',50000);
	/**
	 * Defines the default maximum # user accounts in Enterprise-Normal mode
	 */
	define('ENTERPRISE_USER_LIMIT',10);
	
	define('MODE_COMMUNITY',0);
	define('MODE_ENTERPRISE_NORMAL',1);
	define('MODE_ENTERPRISE_UNLIMITED',2);
	
	/**
	 * the SIMSTATE constants define the state a simulation can be in.
	 * When created the sim is put in to a staged state. Users without the "VIEW STAGED" cannot see it
	 * When made live, all permitted users can see it
	 * And when deleted no one can see it (and it will eventually be removed from the DB).      	 
	 */   	
	define('SIMSTATE_STAGED',0);
	define('SIMSTATE_DELETED',1);
  define('SIMSTATE_LIVE',2);
	define('SIMSTATE_ARCHIVED',4);
	
  define('NOTIFY_SEND',1);
  define('NOTIFY_RECEIVE',2);
  
  define('TICKETS_URL','TICKETS_URL');
  
  define('PREF_SHOW_POPUP_LINKS','PREF_SHOW_POPUP_LINKS');
  define('PREF_ALWAYS_OPEN_IN_NEW_WINDOW','PREF_ALWAYS_OPEN_IN_NEW_WINDOW');
  
  define('DEFAULT_CALENDAR_STATE','DEFAULT_CALENDAR_STATE');
  
  define('DEFAULT_HOME_REDIRECT','?option=office');
?>