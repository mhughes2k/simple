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
/*
 * This is an example LocalSettings.php file
 * 
 * 
 */
	
	// if your php.ini file is not correctly configured, you may need to set your timezone here
	// date_default_timezone_set('America/New_york');
	
	/*
	 * You must complete all of the DATABASE_ settings 
	 */
	$config[DATABASE_TYPE] = "mysql";
	$config[DATABASE_HOST] = "";
	$config[DATABASE_NAME] = "";
	$config[DATABASE_USERNAME] = "";
	$config[DATABASE_PASSWORD] = "";

	/*
	 * This is the Home URL for your site
 	 */
	$config['home'] = 'http://Simple.somewhere.ac.uk/';
	/*
	 * 
	 */
	$config[PLATFORM_NAME] = "SIMPLE Development Install";
	
	
	/*
	 * If you want the lists to contain more items change this.
	 */
	$config['listPageSize']=10;//Items per page in lists.
	
	/*
	 * If you need to connect via a proxy.
	 */
	$config['proxyhost'] = '';
	$config['proxyport'] = 80;
	
	
	/*
	 * You can disable the recording of all metrics (not recommended);
	 */
	$config['recordMetrics'] = true;
	/*
	 * A salt value for encrypting passwords.
	 */
	$config['salt'] ='I am a shaggy dog story';
	/*
	 * The Site footer text
	 */
	$config['siteFooter'] = '<a href="http://simplecommunity.org/">SIMPLE Community</a>';
	
	/*
	 * If you are using the RADIUS authentication plugin, you will need to provide
	 * a list of servers and the shared secret e.g.
	 * 
	 *   $config['radiusServer']=array('Server1','server2');
	 */ 
	$config['radiusServer']=array();
	$config['radiusSecret']='';
		
	/*
	 * Set the 'enabledDebugPalette' to true enable a right hand palette that allows various debugging operations to be performed.
	 */
	$config['enableDebugPalette'] = false;	
?>