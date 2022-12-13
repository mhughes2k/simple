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
 * Default Settings
 * 
 * These are the default settings for TLE2.
 * To change them, re-assign the setting in "LocalSettings.php";
 * @author Michael Hughes
 * @package TLE2
 * @tutorial TLE2/Configuration.pkg
 */
	if (!defined("TLE2")) die ("Invalid Entry Point");

	/**
	* @global array $config
	* @name $config
	*/
	$config = array();	

	//$config['simpleversion']='CE0.9';

	$config[INSTALL_DIRECTORY] = './';
	$config[PLUGIN_DIRECTORY] = "./plugins/";
	
	$config[DEBUG] = false;
	$config[DEBUG_ALLOW_DUMPS] = false;
	$config[DEBUG_LOCKSTEP] = false;
	
	$config[PLATFORM_NAME] = "SIMPLE_DEV-Change this";
	$config['listPageSize'] =25;
	
	$config['defaultAuthMethod'] = 'TleAuthenticate';
	
	$config['sessionName'] = 'simpleLaw1111.cookie';

	// Radius variables (to be moved to Local Settings file)
	$config['radiusServer'] = array();
	$config['radiusSecret'] = '';	
	
	$config['avatarmaxsize'] = 500000; // avatar image no bigger than 5kb
	
	$config['redirectToSSL'] = false;
	
	$config['proxyhost'] ='';
	$config['proxyport'] ='';
	//http://trac.edgewall.org/ticket/7128
	$config[TICKETS_URL]='http://simplecommunity.org/trac/ticket/';
	$config[DEFAULT_CALENDAR_STATE] = true;
	$config['DragAndDropEnabled']=true;
	
	$config['adminIps']=array();
	$config['offline']=false;
	$config['offline_message']='SIMPLE Server is currently Offline.';
	$config['offline_file']='offline.html';
	$config['adminIps'][]='127.0.0.1';
?>
