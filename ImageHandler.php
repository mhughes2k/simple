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
define('TLE2',true,false);
error_reporting(0);
require_once('include/Constants.php');
require_once('include/TLE2.php');
require_once('include/User.class.php');
require_once('include/UserGroup.class.php');
session_start();
require_once('include/Debug.php');
require_once('include/DefaultSettings.php');
if (file_exists('LocalSettings.php')) {
	include_once('LocalSettings.php');
}
require_once('include/Database.class.php');
require_once('include/Project.class.php');
require_once('include/ProjectTemplate.class.php');
require_once('include/GraphicsAndStrings.php');
require_once('include/ContentAndSettings.php');

$imageType=getParam('type',NULL);
$context = getParam('context',NULL);
switch ($context) {
	case 'avatar':
		$userId = GetParam('userId',NULL);
		$user = User::RetrieveUser($userId);
		$user->GetAvatar();
		// next line commented out for now as bin2hex condition causing jpegs not to display
		// if (is_null($user->avatar) | $user->avatar=='' | (bin2hex($user->avatar)==00)) {
		if (is_null($user->avatar) | $user->avatar=='') {
		 // echo 'null av';
			if ($imageType==""){
	  	    	// echo 'type is null';
				$imageType = 'image/gif';
      		}  			
			$user->avatar = file_get_contents('themes/'.$config['THEME'].'/images/defaultavatar.gif');
		}
		
		header("Content-Type: ".$imageType) ; 
		print $user->avatar;	
		break;
	default:
		break;
}
?>