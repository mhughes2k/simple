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
header("Content-Type: text/comma-separated-values");
header("Content-Disposition: inline; filename=file.csv");

define('TLE2',true,false);
require_once('include/Constants.php');
require_once('include/User.class.php');
require_once('include/UserGroup.class.php');
session_start();
require_once('include/Project.class.php');
require_once('include/ProjectTemplate.class.php');
require_once('include/Debug.php');
require_once('include/TLE2.php');
require_once('include/DefaultSettings.php');
if (file_exists('LocalSettings.php')) {
	include_once('LocalSettings.php');
}
require_once('include/Database.class.php');
require_once('include/safehtml.php');

if (($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
	($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();

$out = fopen('php://output', 'w');
$fieldsArray = DeserialiseArray(getParam('selected_fld',NULL));
$listType = getParam('listType',NULL);

switch ($listType) {
	case 'userlist':
		foreach ($fieldsArray as $f) {
			$user = User::RetrieveUser($f);
			$query = sprintf("SELECT * FROM users WHERE userid=%s AND deleted=0",$f);
			$results = $database->queryAssoc($query);
			foreach ($results as $r) {
				$permissions = SerialiseArray($user->sitewidePermissions,FALSE);
				if ($permissions=='') $permissions = 'none';
				$query = sprintf("SELECT * FROM userauth WHERE deleted=0 AND internalid=%s",
								$r['userid']);
				$results = $database->queryAssoc($query);
				$authMethodsArray = array();
				foreach ($results as $am) {
					$authMethodsArray[] = array('authmethod'=>base64_decode($am['authmethod']),'externalid'=>base64_decode($am['externalid']));
				}
				//$authMethods = SerialiseArray($authMethodsArray);
				if (sizeof($authMethodsArray)==0) 
					$authMethodsArray[] = array('authmethod'=>'TleAuthenticate','externalid'=>'none');
				$testauth = array();
				foreach ($authMethodsArray as $a) {
					$testauth[] = SerialiseArray($a,FALSE);
				}
				$authMethods = '';
				for ($i=0; $i < sizeof($testauth); $i++) {
					$authMethods.= $testauth[$i];
					if ($i < (sizeof($testauth)-1)) $authMethods.= "***auth***";
				}
				
				$userArray = array();
				$userArray[] = $r['username'];
				$userArray[] = ""; // blank password (defaults to random on import)
				$userArray[] = $r['displayname'];
				$userArray[] = $r['active'];
				$userArray[] = $r['email'];
				$userArray[] = $r['regnumber'];
				$userArray[] = $r['properties'];
				$userArray[] = $r['superadmin'];
				$userArray[] = $permissions;
				$userArray[] = $authMethods;
				
				fputcsv($out, $userArray);
			}
		}		
		break;
	case 'usergrouplist':
		foreach ($fieldsArray as $f) {
			$group = UserGroup::GetUserGroup($f);
			$query = sprintf("SELECT groupid,name,active FROM usergroups WHERE groupid=%s AND deleted=0",$f);
			$results = $database->queryAssoc($query);
			
			foreach ($results as $r) {
				$permissions = SerialiseArray(UserGroup::GetUserGroupSitewidePermissions($f));
				if ($permissions=='') $permissions = 'none';
				array_push($r, $permissions);
				$members = SerialiseArray($group->GetMemberIds());
				if ($members=='') $members = 'none';
				array_push($r, $members);
				fputcsv($out, $r);
			}
		}			
		break;
	default:
}



fclose($out);	
	
?>
