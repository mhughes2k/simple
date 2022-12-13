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
* Radius Plugin.
* @package TLE2
* @subpackage SecurityPlugins
* This plugin requires that the php radius extension be installed. See
* http://uk2.php.net/radius for more details. Works with php_radius.dll (php-5.1.6(5_1))
* from http://pecl4win.php.net/ext.php/php_radius.dll
* Remember to add the line:
* 	extension=php_radius.dll
* to php.ini
*/

	/**
	* Attempts to authenticate against RADIUS
	* @param string $username Username to authenticate.
	* @param string $password Password to authenticate.
	*/
	function coreRadius($username,$password,$authType) {
		global $config, $metrics, $database;
		if ($authType!='coreRadius'){
			return false;		
		}

		$radius = radius_auth_open();
		$numServers = count($config['radiusServer']);
		$server_added = FALSE;
		$s = 0;
		while ((!$server_added) && ($s<$numServers)) {
			if (radius_add_server($radius,$config["radiusServer"][$s],0,$config["radiusSecret"],5,3)) {
				$server_added = TRUE;
				$s++;			
			}
		}
		if (!$server_added)  {
			die ("Radius Error :" . radius_strerror($radius));
		}
		
		if (! radius_create_request($radius , RADIUS_ACCESS_REQUEST)) 
		{
			die ("Radius Error: ". radius_strerror($radius));
		}
		
		radius_put_attr($radius, RADIUS_USER_NAME, $username);
		radius_put_attr($radius, RADIUS_USER_PASSWORD, $password);
		$radius_result = radius_send_request($radius);
		switch ($radius_result) {
			case RADIUS_ACCESS_ACCEPT:
				trace("RADIUS AUthenticated");
				$metrics->recordMetric('RADIUS',"Authenticated: $username");
				return $username;
				break;
			case RADIUS_ACCESS_REJECT:
				$metrics->recordMetric('RADIUS',"Failed: $username");
        			trace('bad login');
				break;
			case RADIUS_ACCESS_CHALLENGE:
				trace('challenge requested');
				$metrics->recordMetric('RADIUS','Challenge');
				break;
			default:
				//die('Fall through');
				if ($radius_result === false) {
					$metrics->recordMetric('RADIUS',"Invalid Radius result".(int)$radius_result);
				}
				else {
					$metrics->recordMetric('RADIUS',"Unknown RADIUS Error");
				}
		}
		trace("Not RADIUS AUthenticated");
		return false;
	}
	$_PLUGINS->registerFunction('onAuthenticateUser','RadiusAuthentication','coreRadius');

	/**
	* Would handle any thing that has to be done to clear up a RADIUS authentication.
	*/
	function coreRadiusLogout($userid) {
		return true;
	}	
	$_PLUGINS->registerFunction('onUserLogout','RadiusAuthentication','coreRadiusLogout');
	
?>
