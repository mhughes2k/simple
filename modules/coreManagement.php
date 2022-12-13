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
 * Core Management Module
 * @author Michael Hughes
 *
 * @package TLE2
 * @subpackage Core
 */
 if (!defined("TLE2")) die ("Invalid Entry Point");
 /**
 * Provides sitewide management (plugin management, etc.)
 */
ini_set('auto_detect_line_endings',true);//enables EOL support.
 	if (is_null($page)) {
		$page = new Page();
	}
 	switch (strtolower($command) ) {
		//case 'updateserverlicense':
		//	ServerLicense();
		//	break;
		//case 'addcals':
		//case 'remmovecals':
		//	CalLicense();
		//	break;
		case 'collectgarbage':
			CollectGarbage();			
			break;
		case 'searchgroups':
			$wrapper->assign('groupstab',"true");
			ListUserGroups();
			ListUsers();
			break;
		case 'searchusers':
			$wrapper->assign('groupstab',FALSE);
			ListUsers();
			ListUserGroups();
			break;
		case 'installplugin':
			ListPlugins();
			break;
		case 'removeplugin':
			ListPlugins();
			break;
		case 'listplugins':
			ListPlugins();
			break;
		case 'viewplugin':
			ViewPlugin();
			break;
		case 'updateplugin':
			UpdatePlugin();
			break;
		case 'disableplugin':
			ListPlugins();
			break;
		case 'enableplugin':
			ListPlugins();
			break;
		case 'shiftpluginup':
			ListPlugins();
			break;
		case 'shiftplugindown':
			ListPlugins();
			break;
		case 'multipluginsubmit':
			ListPlugins();
			break;

		case 'listusers':
			$wrapper->assign('groupstab',FALSE);
			ListUsers();
			ListUserGroups();
			break;
		case 'multiusersubmit':
			$wrapper->assign('groupstab',FALSE);
			ListUsers();
			ListUserGroups();
			break;
		case 'viewuser':
			ViewUser();
			break;
		case 'deleteuser':
			ListUsers();
			ListUserGroups();
			break;
		case 'deletemultiusers':
			$wrapper->assign('groupstab',FALSE);
			ListUsers();
			ListUserGroups();
			break;
		case 'importmultiusers':
			$wrapper->assign('groupstab',FALSE);
			ListUsers();
			ListUserGroups();
			break;
		case 'adduser':
			AddUser();
			break;
		case 'updateuser':
			ViewUser();
			break;
		case 'updateusersitewidepermissions':
			ViewUser();
			break;
		case 'updateuserlogin':
			ViewUser();
			break;
		case 'updateuseravatar':
			ViewUser();
			break;
		case 'addblueprint2user':
			AddBluePrint2User();
			break;			
		case 'addsimulation2user':
			AddSimulation2User();
			break;
		case 'viewuserblueprintpermissions':
			ViewUserBlueprintPermissions();
			break;
		case 'viewusersimulationpermissions':
			ViewUserSimulationPermissions();		
			break;
			
		case 'adduser2usergroup':
			ViewUserGroup();
			break;
		case 'createadduser2group':
			CreateAddUser2UserGroup();
			break;			
		case 'addusergroup2user':
			ViewUser();
			break;			
		case 'removememberfromgroup':
			ViewUserGroup();
			break;
		case 'removeusergroupfromuser':
			ViewUser();
			break;
		case 'listusergroups':
			$wrapper->assign('groupstab',"true");
			ListUserGroups();
			ListUsers();
			break;
		case 'multiusergroupsubmit':
			$wrapper->assign('groupstab',"true");
			ListUserGroups();
			ListUsers();
			break;			
		case 'viewusergroup':
			ViewUserGroup();
			break;
		case 'updateusergroup':
			ViewUserGroup();
			break;
		case 'deleteusergroup':
			$wrapper->assign('groupstab',"true");
			ListUserGroups();
			ListUsers();
			break;
		case 'deletemultiusergroups':
			$wrapper->assign('groupstab',"true");
			ListUserGroups();
			ListUsers();
			break;			
		case 'updateusergroupsitewidepermissions':
			ViewUserGroup();
			break;
		case 'addusergroup':
			AddUserGroup();
			break;
		case 'importmultiusergroups':
			$wrapper->assign('groupstab',"true");
			ListUserGroups();
			ListUsers();
			break;			
		case 'viewprofile':
			ViewUser();
			break;
			
		case 'viewuserprojectpermissions':
			ViewUserSimulationPermissions();
			break;
		case 'updateuserprojectpermissions':
			UpdateUserSimulationPermissions();
			break;
		case 'updateusergroupprojectpermissions':
			UpdateUserGroupSimulationPermissions();
			break;
		case 'deleteblueprintfromuser':
			ViewUser();
			break;			
		case 'deletesimulationfromuser':
			ViewUser();
			break;
		case 'updateuserprojecttemplatepermissions':
			UpdateUserBlueprintPermissions();
			break;
		case 'viewuserprojecttemplatepermissions':
			ViewUserBlueprintPermissions();
			break;
		case 'updateusergroupprojectpermissions':
			UpdateUserGroupSimulationPermissions();
			break;
		case 'viewusergroupprojectpermissions':
			ViewUserGroupSimulationPermissions();
			break;			
		case 'deletesimulationfromusergroup':
			ViewUserGroup();
			break;
		case 'updateusergroupprojecttemplatepermissions':
			UpdateUserGroupBlueprintPermissions();
			break;
		case 'viewusergroupprojecttemplatepermissions':
			ViewUserGroupBlueprintPermissions();
			break;	
		case 'deleteblueprintfromusergroup':
			ViewUserGroup();
			break;
    case 'viewpublic':
      DisplayPublicProfile();
      break;
		case 'view':
		default:
			SiteAdmin();
	}
 /**
  * General Site admin
  */   
  function SiteAdmin() {
    global $page,$config,$serverMode,$database,$command;
	ListPlugins();
    $page->Template='siteadmin.tpl';
	if($command=='setloginpagetext') {
		$query = sprintf("UPDATE site_settings SET login_page_content='%s' WHERE id=1",
			$_POST['loginpagetext']);
		$result = $database->execute($query);
	}
	if($command=='setvocabulary') {
		$query = sprintf("UPDATE site_settings SET group_name='%s', group_name_plural='%s', simulation_name='%s', simulation_name_plural='%s' WHERE id=1",
			$_POST['voc_group_text'],
			$_POST['voc_group_text_pl'],
			$_POST['voc_sim_text'],
			$_POST['voc_sim_text_pl']);
		$result = $database->execute($query);
		header("Location:index.php?option=siteAdmin"); // refresh to show new vocabulary
		die();		
	}
	if($command=='sethelpurl') {
		$query = sprintf("UPDATE site_settings SET val_help_url='%s' WHERE id=1",
			$_POST['txt_help_url']);
		$result = $database->execute($query);
		header("Location:index.php?option=siteAdmin"); // refresh to show new help link
		die();
	}
	if($command=='setlanguage') {
		$query = sprintf("UPDATE site_settings SET language='%s' WHERE id=1",
			$_POST['language']);
		$result = $database->execute($query);
		header("Location:index.php?option=siteAdmin"); // refresh to show new language
		die();
	}
	if($command=='settheme') {
		$query = sprintf("UPDATE site_settings SET theme='%s' WHERE id=1",
			mysql_real_escape_string($_POST['theme']));
		$result = $database->execute($query);
		header("Location:index.php?option=siteAdmin"); // refresh to show new theme
		die();
	}
	if($command=='emailmodule') {
		$emailModuleEnableMentors = (int)$_POST['emailmoduleenablementors'];
		$emailModuleEnableLearners =(int)$_POST['emailmoduleenablelearners'];
		$query = sprintf("UPDATE site_settings SET emailmodule_mentors='%s', emailmodule_learners='%s' WHERE id=1",
			$emailModuleEnableMentors,$emailModuleEnableLearners);
		$result = $database->execute($query);
		header("Location:index.php?option=siteAdmin"); // refresh to show new theme
		die();
	}	
	if($command=='addnewsstory') {
		$query = sprintf("INSERT INTO site_news (title,text,timestamp) VALUES ('%s','%s','%s')",
			$_POST['title'],$_POST['newstext'],time());
		$result = $database->execute($query);
	}
	if($command=='editnewsstory') {
		$query = sprintf("UPDATE site_news SET title='%s', text='%s' WHERE id=%s",
			$_POST['title'],$_POST['newstext'],$_POST['newsid']);
		$result = $database->execute($query);
	}
	if($command=='deletenewsstory') {
		$query = sprintf("DELETE FROM site_news WHERE id=%s",
			$_GET['id']);
		$result = $database->execute($query);
	}
	
	$query = "SELECT * FROM site_settings WHERE id=1";
	$result = $database->queryAssoc($query);
	$loginpagetext = $result[0]['login_page_content'];
	$language = $result[0]['language'];
	$theme = $result[0]['theme'];
	$group_name = $result[0]['group_name'];
	$group_name_plural = $result[0]['group_name_plural'];	
	$simulation_name = $result[0]['simulation_name'];
	$simulation_name_plural = $result[0]['simulation_name_plural'];
	$val_help_url = $result[0]['val_help_url'];
	$emailmodule_mentors = $result[0]['emailmodule_mentors'];
	$emailmodule_learners = $result[0]['emailmodule_learners'];
    
	$languageList = array();
	$langFolder = "lang/";
	if ($handle = opendir($langFolder)) {
		while (false !== ($file = readdir($handle))) {
			if (($file != "." && $file != "..") && (is_dir($langFolder."/".$file))) {
				$languageList[] = $file;
			}
		}	
		closedir($handle);
	} 
	
	$themeList = array();
	$themeFolder = "themes/";
	if ($handle = opendir($themeFolder)) {
		while (false !== ($file = readdir($handle))) {
			if (($file != "." && $file != "..") && (is_dir($themeFolder."/".$file))) {
				$themeList[] = $file;
			}
		}	
		closedir($handle);
	} 

	$query = "SELECT * FROM site_news ORDER BY timestamp DESC";
	$newsstories = $database->queryAssoc($query);
	
	$page->assign('newsstories',$newsstories);
	$page->assign('loginpagetext',$loginpagetext);
	$page->assign('language',$language);
	$page->assign('languageList',$languageList);
	$page->assign('theme',$theme);
	$page->assign('themeList',$themeList);
	$page->assign('group_name',$group_name);
	$page->assign('group_name_plural',$group_name_plural);
	$page->assign('simulation_name',$simulation_name);
	$page->assign('simulation_name_plural',$simulation_name_plural);
	$page->assign('val_help_url',$val_help_url);
	$page->assign('emailmodulementors',$emailmodule_mentors);
	$page->assign('emailmodulelearners',$emailmodule_learners);
    //$page->assign('defaultUserLimit',GetDefaultUserAccountLimit());
    //$page->assign('userLimit',GetUserAccountLimit());
    //$page->assign('usedAccountsCount',$ucount); 
    //$page->assign('cals',$cals);
    //$page->assign('bpLimit',$serverMode['bpLimit']);
    //$page->assign('simLimit',$serverMode['simLimit']);
	$page->assign('phpversion',phpversion());
	$page->assign('serversoftware',$_SERVER['SERVER_SOFTWARE']);
    return;
  }
    
  
  /**
   * Performs Garbage Collection on the database.
   */     
  function CollectGarbage() {
    DisplayMessage('Garbage Collection not yet implemented');
  }
    
  /**
   * Handles all of the server licensing stuff.
   */   
  function ServerLicense() {
    $cmd = strtolower(getParam('cmd',NULL));
    switch($cmd){
      case 'updateserverlicense':
        DisplayMessage("Updating Server License is not yet implemented");
        break;
    }
  }
  
  /**
   * Handles all of the Client Licensing Stuff
   */     
  function CalLicense() {
    $cmd = strtolower(getParam('cmd',NULL));
    switch($cmd){
      case 'addcal':
        DisplayMessage("Adding CALs is not implmented yet");
        break;
      case 'removecal':
        DisplayMessage("Removing CALs is not implmented yet");
        break;
      default;
    }
  }
 /**
 * displays list of plugins
 */
 function ListPlugins() {
 	global $page,$config;
	if (($_SESSION[USER]->sitewidePermissions['AddPlugin']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) { 	
 		$cmd = strtolower(getParam('cmd',NULL));
 		switch ($cmd) {
 			case 'multipluginsubmit':
 				$multi_submit = getParam('multi_submit',NULL);
 				switch ($multi_submit) {
 					case 'delete':
 						if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);
						foreach ($fieldsArray as $f) {
							Plugin::RemovePlugin($f);					
						}
 						break;
 					case 'enable':
						if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);
						foreach ($fieldsArray as $f) {
							$plugin = Plugin::GetPlugin($f);
							$plugin->EnablePlugin();					
						} 					
 						break;
 					case 'disable':
						if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);
						foreach ($fieldsArray as $f) {
							$plugin = Plugin::GetPlugin($f);
							$plugin->DisablePlugin();					
						} 					
 						break;
 				}
 				break;
 			case 'removeplugin':
 				$pluginId = getParam('pluginid', NULL);
				Plugin::RemovePlugin($pluginId);
 				break;
 			case 'installplugin':
 				Plugin::AddPlugin();
 				break;
 			case 'enableplugin':
 				$pluginId = getParam('pluginId', NULL);
				$plugin = Plugin::GetPlugin($pluginId);
				$plugin->EnablePlugin();
 				break;
 			case 'disableplugin':
				$pluginId = getParam('pluginId', NULL);
				$plugin = Plugin::GetPlugin($pluginId);
				$plugin->DisablePlugin(); 			
 				break;
 			case 'shiftpluginup':
 			 	$pluginId = getParam('pluginId', NULL);
 				$plugin = Plugin::GetPlugin($pluginId);
 				$prevPlugin = $plugin->GetPrevPlugin();
 				if ($prevPlugin) {
			 		Plugin::ReorderPlugins($plugin, $prevPlugin);
				}
 				break;
 			case 'shiftplugindown':
 				$pluginId = getParam('pluginId', NULL);
				$plugin = Plugin::GetPlugin($pluginId);
				$nextPlugin = $plugin->GetNextPlugin();
				if ($nextPlugin) {
					Plugin::ReorderPlugins($plugin,$nextPlugin);
				}
 				break;
 			default:
 		}	
 			 	
 		$pluginCount = Plugin::GetPluginCount();
 		$currentUser = $_SESSION[USER];
	 	$startFrom = GetParam('start',0);
		$previous = $startFrom - $config['listPageSize']>=0?$startFrom - $config['listPageSize']:-1;
		$next = $startFrom + $config['listPageSize']; 	
		if ($next>= $pluginCount) {
			$next = -1;
		}
		$page->assign('previous',$previous);
		$page->assign('next',$next);	
		$page->Template = 'listPlugins.php.tpl';
		$page->assign('pluginsArray',Plugin::ListPlugins($startFrom));
		$zipInstalled = (extension_loaded('zip'))? 1:0;
		$page->assign('zipinstalled', $zipInstalled);
		$page->assign('currentUser',$currentUser);
 	} else {
 		InsufficientPermissions();
 	}
 }

 /**
 * display plugin details
 */
 function ViewPlugin() {
 	global $page;
	if (($_SESSION[USER]->sitewidePermissions['AddPlugin']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) { 	 	
		$pluginId = getParam('pluginId', NULL);
		$page->Template = 'viewPlugin.php.tpl';
		$page->assign('plugin',Plugin::GetPlugin($pluginId));
 	} else {
 		InsufficientPermissions();
 	}
 }

/**
 * update and display plugin details
 */
 function UpdatePlugin() {
 	global $page;
	if (($_SESSION[USER]->sitewidePermissions['AddPlugin']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) { 	 	
 		$pluginName = getParam('pluginName', NULL);
 		$enabled = getParam('enabled', NULL);
 		$sitewide = getParam('sitewide', NULL);
 		$submitted = getParam('submitted',NULL);
 		if ($submitted=='yes') {
 			Plugin::UpdatePlugin($pluginName, $enabled, $sitewide);
 		}
 		$page->Template = 'viewPlugin.php.tpl';
 		$page->assign('method', 'edit');
 		$page->assign('plugin',Plugin::GetPlugin($pluginName));
 	} else {
 		InsufficientPermissions();
 	}
 }

 /**
 * gets list of users from the database
 */
 function ListUsers() {
 	global $page,$config,$database,$strings;
 	if (($_SESSION[USER]->sitewidePermissions['AddUser']==ALLOW) ||
 		($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) ||
 		($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {
		//trace("executing body of listUsers");
		//print_r($_POST);
		$method = getParam('method', NULL);
		$usersQuery = ' ';
		$order = '';		
		$searchBy = GetParam('searchBy',0);
		$searchTerm = GetParam('searchTerm','');
		if ($searchTerm!='') {
			switch($searchBy) {
				case 1:
					$usersQuery = 'AND displayname LIKE ';
					$order = 'displayname ASC';
					break;
				case 3:
					$usersQuery = 'AND email LIKE ';
					$order = 'email ASC';
					break;
				case 4:
					$usersQuery = 'AND regnumber LIKE ';
					$order = 'regnumber ASC';
					break;
				case 0:
				default:
					$quotedTerm = $database->database->quote($searchTerm);
          $usersQuery = ' AND (u.username LIKE '.$quotedTerm . ' OR ua.externalid like '.$quotedTerm. ')';
					$order = 'username ASC';
					break;			
			}
		}				
		$orderBy = GetParam('orderBy',NULL);
		if ($orderBy) {
			$order = $orderBy;
		} elseif ($order=='') {
			$order = 'displayname ASC';
		}			
		$page->assign('searchTerm',$searchTerm);
		$page->assign('searchBy',$searchBy);
		switch ($method) {
			case 'multiSubmit':
				$multi_submit = getParam('multi_submit',NULL);
				switch ($multi_submit) {		
					case 'delete':
						if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
							($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);
						if ($fieldsArray) {
							foreach ($fieldsArray as $f) {
								$user = User::RetrieveUser($f,TRUE);
								if ($user) $user->RemoveUser();					
							}
						}
						//trace("deleting multiple users: <pre>".print_r($fieldsArray,true)."</pre>");						
						break;
					case 'enable':
						if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);
						if ($fieldsArray) {
							foreach ($fieldsArray as $f) {
								$user = User::RetrieveUser($f,TRUE);
								$user->EnableUser();
							}					
						}						
						break;
					case 'disable':
						if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);
						if ($fieldsArray) {
							foreach ($fieldsArray as $f) {
								$user = User::RetrieveUser($f,TRUE);
								$user->DisableUser();					
							}
						}						
						break;		
					case 'exportcsv':
						if (($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$fieldsArray = getParam('selected_fld',NULL);			
						Redirect("CsvHandler.php?selected_fld=".SerialiseArray($fieldsArray)."&listType=userlist");
						break;									
					default:
				}
				break;
			case 'deleteMultiUsers':
				if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();			
				$csvList = getParam('deleteusers',NULL);
				$deleteArray = explode(",", $csvList);
				foreach ($deleteArray as $d) {
										
					$query = sprintf("SELECT * FROM users WHERE username='%s' AND deleted=0",$d);
					$results = $database->queryAssoc($query);
					foreach ($results as $r) {
						$user = User::RetrieveUser($r['userid']);
						if (($user->userName!='') && ($user->authMethod=='TleAuthenticate')) {
							$user->RemoveUser();
						}
					}
					$query = sprintf("SELECT * FROM users WHERE userid='%s' AND deleted=0",$d);
					$results = $database->queryAssoc($query);
					foreach ($results as $r) {
						$user = User::RetrieveUser($r['userid']);
						$user->RemoveUser();
					}	
				}
				break;
			case 'importMultiUsers':
				if (($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();			
				if (!empty($_FILES['importusers']['tmp_name'])) {
					$fileerror = FALSE;
					$csverror = FALSE;
					$existing = array();
					$permExisting = array();
					$authExisting = array();
					$handle = fopen($_FILES['importusers']['tmp_name'], "r");
					while (($data = fgetcsv($handle)) !== FALSE) {
   						$num = sizeof($data); 
   						if ($num != 10)  {
                $fileerror = "Wrong number of columns. ";
                DisplayMessage($fileerror);
                return;
               }
   						/* CSV format should be:
   						 Column	|	Content
   						 0		|	username (string - should not be blank. Should be 'none' for external users)
   						 1		|	plain text password (the user's internal password. string - can be blank, defaults to random string)
   						 2		| 	display name (string - can be blank)
   						 3		|	active (integer - can be blank, defaults to 1)
   						 4		|	email (string - can be blank)
   						 5		|	regnumber (string - can be blank)
   						 6		|	properties (string - can be blank)
   						 7		|	superadmin (integer - can be blank, defaults to DENY)
   						 8		|	permissions array (array or 'none') Example entry:
   						 	AddUser=0|EditUser=0|MakeLevelZeroUser=0|AddPlugin=0|InstallTemplate=0|EditTemplate=0|RemoveTemplate=0
   						 9		| 	authentication methods (array of serialised associative arrays of 
   						 			auth method and external id). Serialised array separated by '***auth***'
   						 			string if more than one.
   						 	e.g.  authmethod=RadiusAuthentication|externalid=igs12345***auth***authmethod=RadiusAuthentication|externalid=test1
   						 	or	  authmethod=LdapAuthentication|externalid=test123	
	 			
   						*/
   						// initialize any unset variables
   						$data[0] = (isset($data[0])) ? addslashes($data[0]) : "''";
   						$data[1] = (isset($data[1])) ? addslashes($data[1]) : md5(uniqid(rand(),1));
   						$data[2] = (isset($data[2])) ? addslashes($data[2]) : "''";
   						$data[3] = (isset($data[3]) && ($data[3]===0)) ? 0 : 1;
   						$data[4] = (isset($data[4])) ? addslashes($data[4]) : "''";
   						$data[5] = (isset($data[5])) ? addslashes($data[5]) : "''";
   						$data[6] = (isset($data[6])) ? addslashes($data[6]) : "''";
   						$data[7] = (isset($data[7])) ? addslashes($data[7]) : DENY;	
   						$data[8] = (isset($data[8])) ? addslashes($data[8]) : 'noPermissions';
   						$data[9] = (isset($data[9])) ? addslashes($data[9]) : 'none';
   						
   						$password = HashPassword($data[1]);
   						
   					// 1. Set flags depending on what is already in database
   						
   						// check user id not exists as deleted
   						//$query = sprintf("SELECT COUNT(*) AS numusers FROM users WHERE userid=%s AND deleted=1",
   						//				$userid);
   						//$results = $database->queryAssoc($query);
   						$fIdExistsAsDeleted = FALSE;
   						
   						// check username not exists as deleted
   						$query = sprintf("SELECT COUNT(*) AS numusers FROM users WHERE ".
   								"username='%s' AND deleted=1",
   								$data[0]);
   						$results = $database->queryAssoc($query); 
   						$fUExistsAsDeleted = ($results[0]['numusers']>0);
   						
   						// check user id exists as not deleted
   						//$query = sprintf("SELECT COUNT(*) AS numusers FROM users WHERE userid=%s AND deleted=0",
   						//				$userid);
   						//$results = $database->queryAssoc($query);
   						$fIDExistsNotDeleted = FALSE;
   						
   						// check username exists as not deleted
						$query = sprintf("SELECT COUNT(*) AS numusers FROM users WHERE username='%s' AND deleted=0",
   										$data[0]);
   						$results = $database->queryAssoc($query);   		
   						$fUPExistsNotDeleted = ($results[0]['numusers']>0);
   							
   						
   					// 2. Take action depending on flags		
   						
   						if ($fIdExistsAsDeleted) {
   							// remove old record before inserting
   							$query = sprintf("DELETE FROM users WHERE userid=%s",$userid);
							$database->execute($query);
   						}
   						if ($fUExistsAsDeleted) {
   							// remove old record before inserting
   							$query = sprintf("DELETE FROM users WHERE username='%s'", 
 											$data[0]);
   							$database->execute($query);
   						}
   						
   						if (($fIDExistsNotDeleted) || ($fUPExistsNotDeleted)) {
   							// a record exists -> return error message and do not insert
   							$existing[] = array($userid,$data[0],$data[2]);
   						} else {
							$query = sprintf("INSERT INTO users (username,`password`,displayname,".
										"active,email,regnumber,properties,superadmin) VALUES ".
										"('%s','%s','%s',%s,'%s','%s','%s','%s')",
										$data[0],
										$password,
										$data[2],
										$data[3],
										$data[4],
										$data[5],
										$data[6],
										$data[7]
										);
										//print $query;
							$database->execute($query);
							$userid = $database->database->lastInsertID();
						
							if (strtolower($data[8])!='none') {
								$permissionsArray = DeserialiseArray_NoBase64($data[8]);
								// update/insert permissions values
								$query = sprintf("SELECT COUNT(*) as permexist FROM sitewidepermissions WHERE userid=%s ".
												"AND usertype='user'",$userid);
								$results = $database->queryAssoc($query);
								if ($results[0]['permexist']>0) {
									// exists - leave values as is
									$permExisting[] = $userid;
								} else {
									$query = sprintf("INSERT INTO sitewidepermissions (userid,usertype,".
											"adduser,edituser,makelevelzerouser,addplugin,installtemplate,".
											"edittemplate,removetemplate,deleted) VALUES ".
											"(%s,'user',%s,%s,%s,%s,%s,%s,%s,0)",
											$userid,
											$permissionsArray['AddUser'],
											$permissionsArray['EditUser'],
											$permissionsArray['MakeLevelZeroUser'],
											$permissionsArray['AddPlugin'],
											$permissionsArray['InstallTemplate'],
											$permissionsArray['EditTemplate'],
											$permissionsArray['RemoveTemplate']);
									$result = $database->execute($query);
								}
							} // end if permissions array
							
							// check if 'none'
							if (strtolower($data[9])!='none') {
								// check if ***auth*** found
								if (strpos($data[9],'***auth***')===TRUE) {
									// explode
									$authMethods = explode('***auth***',$data[9]);
								} else {
									$authMethods = array();
									$authMethods[] = $data[9];
								}
								// foreach
								foreach ($authMethods as $a) {
									// 	deserialise
									$methodArray = DeserialiseArray_NoBase64($a);
									
									// if 'tleauthenticate' ignore
									if (strtolower($methodArray['authmethod'])!='tleauthenticate') {
										// do something (insert into db if doesnt already exist, or update)
										$query = sprintf("SELECT COUNT(*) AS authexists FROM userauth ".
												"WHERE internalid=%s AND authmethod='%s' AND externalid='%s' ".
												"AND deleted=1",
												$userid,
												$methodArray['authmethod'],
												$methodArray['externalid']
												);
										$results = $database->queryAssoc($query);
										if ($results[0]['authexists']>0) {
											$query = sprintf("UPDATE userauth SET deleted=0 ".
												"WHERE internalid=%s AND authmethod='%s' AND externalid='%s'",
												$userid,
												$methodArray['authmethod'],
												$methodArray['externalid']
												);
											$result = $database->execute($query);
										} else {
											$query = sprintf("SELECT COUNT(*) AS authexists FROM userauth ".
												"WHERE internalid=%s AND authmethod='%s' AND externalid='%s' ".
												"AND deleted=0",
												$userid,
												$methodArray['authmethod'],
												$methodArray['externalid']
												);
											$results = $database->queryAssoc($query);
											if ($results[0]['authexists']<1) {
												// insert into db
												$query = sprintf("INSERT INTO userauth (authmethod,externalid,".
													"internalid,deleted) VALUES ('%s','%s',%s,0)",
													$methodArray['authmethod'],
													$methodArray['externalid'],
													$userid
													);
												$result = $database->execute($query);
											}
										}
									} // end if not native	
								} // end foreach authmethod
							} // end if not none
							
   						} // end if user exists
					} // end while not end of file
					fclose($handle);
					if (count($existing)>0) { 
						$csverror.= "The user id or username/password combination of the following entries already exist ".
									"and have not been added: <ul>";
						foreach ($existing as $e) {
							$csverror.= "<li>User id: ".$e[0].", Username: ".$e[1].", Display Name: ".$e[2]."</li>";
						}	
						$csverror.="</ul>";
					}
					if (count($permExisting)>0) {
						$cvserror.= "The sitewide permissions for the following users already exist and have not been ".
									"added: <ul>";
						foreach ($permExisting as $pe){
							$cvserror.= "<li>User id: ".$pe."</li>";
						}
						$cvserror.="</ul>";
					}
					if (count($authExisting)>0) {
						$cvserror.= "The internal id, external id, and authentication method combination of the following ".
									"entries already exist and have not been added: <ul>";
						foreach($authExisting as $au) {
							$cvserror.="<li>Internal id: ".$au[0].", External id: ".$au[1].", Auth method: ".$au[2]."</li>";
						}
						$cvserror.="</ul>";
					} 
					if ($fileerror) $csverror = "Error: File is not in correct format. ".$fileerror;
					$page->assign('csverror',$csverror);
				}
				break;
			case 'deleteUser':
 				if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
 					($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
			 		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();	
				$userId = getParam('delete_userid', NULL);
				$user = User::RetrieveUser($userId,TRUE);
				if ($user) $user->RemoveUser();			
				break;
			case 'searchUsers':
			default:
		}
		$page->Template = "listUsers.php.tpl";
		$startFrom = GetParam('start',0);	
		//$orderBy = getParam('orderBy','username ASC');
		
		if ($searchBy!=0 & $searchTerm!='') {
			$usersQuery .=$database->database->quote($searchTerm) ;//'\''.$searchTerm.'\'';
		}
	//	echo $usersQuery;
		$userCount = User::GetUserCount($usersQuery);		
		$users = User::SearchUsers($usersQuery,$order,$startFrom);	
		
		$previous = $startFrom - $config['listPageSize']>=0?$startFrom - $config['listPageSize']:-1;
		$next = $startFrom + $config['listPageSize'];
		//echo $userCount .':'.$next;
		if ($next>= $userCount) {
			$next = -1;
		}
		$page->assign('previous',$previous);
		$page->assign('next',$next);
		foreach($users as $user){
			//print_r($user);
      if ($user->displayName == '') {
				$user->displayName = "User #".$user->id; 
				//Don't call $user->save() as we don't want to update the item!
			}
			if ($user->userName =='') {
				//$user->userName = "User #".$user->id;
			}
		}
		$page->assign('usersArray',$users);
		$currentUser = $_SESSION[USER];
		$page->assign('currentUser',$currentUser);
		
 	} else { 
 		InsufficientPermissions();
 	}
 	trace("ending listUsers()");
 }
 
 function SearchGroups() {
 	global $page,$strings,$database;
	$page->Template = "listUserGroups.php.tpl";
	//$userCount = User::GetUserCount();
	$searchBy = GetParam('gsearchBy',0);
	$searchTerm = GetParam('gsearchTerm','');
		
	if ($searchTerm == '') {
		DisplayMessage($strings['MSG_MISSING_SEARCH_TERM']);
		return;
	}
	$query = '';
	$order = '';
	switch($searchBy) {
		case 1:
			$query = 'groupid = '.$searchTerm;
			$order = 'groupid ASC';
			break;
		case 0:
		default:
			$query = 'name LIKE ';
			$query .=$database->database->quote($searchTerm) ;//'\''.$searchTerm.'\'';
			$order = 'name ASC';
			break;			
	}
	//$searchTerm = $database->database->quote($searchTerm);//addslashes($searchTerm);
	$page->assign('gsearchTerm',$searchTerm);
	$page->assign('gsearchBy',$searchBy);
	
	//echo $query;
	//$users = User::SearchUsers($query,$order);
	$groups = UserGroup::SearchGroups($query,$order);

	//disable paging
	$previous = -1;
	$next = -1;
	$page->assign('gprevious',$previous);
	$page->assign('gnext',$next);

	foreach($groups as $group){
		if ($group->name == "") {
			$group->name = "Group #".$user->id; 
		}
	}
	if (count($groups)>0) { $page->assign('someresults',1) ;}
	
	$page->assign('groups',$groups);
 }
   /**
  * Handles the SearchUser command
  */
 function SearchUsers() {
  	global $page,$strings,$database;
	$page->Template = "listUsers.php.tpl";
	$userCount = User::GetUserCount();
	$searchBy = GetParam('searchBy',0);
	$searchTerm = GetParam('searchTerm','');
		
	if ($searchTerm == '') {
		DisplayMessage($strings['MSG_MISSING_SEARCH_TERM']);
		return;
	}
	$query = '';
	$order = '';
	$quotedTerm = $database->database->quote($searchTerm);
	//echo('qt:'$quotedTerm);
  
	switch($searchBy) {
		case 1:
			$query = 'displayname LIKE '.$quotedTerm;
			$order = 'displayname ASC';
			break;
		case 3:
			$query = 'email LIKE '.$quotedTerm;
			$order = 'email ASC';
			break;
		case 0:
		default:
			$query = '(u.username LIKE '.$quotedTerm . ' OR ua.externalid like '.$quotedTerm. ')';
			$order = 'username ASC';
			echo $query;
			break;			
	}
	//$searchTerm = $database->database->quote($searchTerm);//addslashes($searchTerm);
	$page->assign('searchTerm',$searchTerm);
	$page->assign('searchBy',$searchBy);
	$users = User::SearchUsers($query,$order);

	//disable paging
	$previous = -1;
	$next = -1;
	$page->assign('previous',$previous);
	$page->assign('next',$next);

	foreach($users as $user){
		if ($user->displayName == "") {
			$user->displayName = "User #".$user->id; 
			//Don't call $user->save() as we don't want to update the item!
		}
		if ($user->userName =="") {
			$user->userName = "User #".$user->id;
		}
	}
	$currentUser = $_SESSION[USER];
	$page->assign('currentUser',$currentUser);
	$page->assign('usersArray',$users);
 }
 /**
  * Page to view and edit user details
  * 
  * Also allows a user with the appropriate permissions to view another user's profile.
  * If they have permission to edit users they may then change the user's profile.
  * A user can edit their own details and login but will not be able to control their 
  * permissions or groups, unless they have the editUsers permission.
  */
 function ViewUser() {
 	global $page,$metrics,$strings,$config;
 	$currentUser=$_SESSION[USER];
 	//$userId = GetParam('delete_userid',NULL);
 	//if ($userId==NULL)
 	$userId = GetParam('userId',$currentUser->id); 

 	if (($currentUser->sitewidePermissions['EditUser']==ALLOW) ||
 		($currentUser->id==$userId) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {
 		$page->Template = "viewUser.php.tpl"; 	 		

 		$displayUser = User::RetrieveUser($userId);
 		if(is_null($displayUser)) {
 			die("Display User is null (coreManagement::ViewUser($userId))");
 		};
		$logMessage= $currentUser->displayName .'(' .$currentUser->id . ') accessed '. $displayUser->displayName. '('.$displayUser->id .')';
		$metrics->recordMetric('viewprofile','viewprofile',$logMessage);
		$method = GetParam('method', NULL);
		$missingFields = array();
		$editState = getParam('editState',NULL);
		$page->assign('addsimulationdialogprojects',Project::GetProjects());
		$page->assign('addblueprintdialogtemplates',ProjectTemplate::GetAllBlueprints());
		//print_r($displayUser);
		switch($method) {
			case 'updateUser':
				$submitted = getParam('submitted',NULL);
				$displayName = getParam('displayName', NULL);
				if ((trim($displayName)=='') && ($editState=='editDetails')) $missingFields['displayName']=TRUE;
				$email = getParam('email', NULL);
				if ((trim($email)=='') && ($editState=='editDetails')) $missingFields['email']=TRUE;
				$regNumber = getParam('regnumber', NULL);
				$blurb = getParam('blurb', '');
				$active = getParam('active', 1);
				$userId = getParam('userId', NULL);
				if ((sizeof($missingFields)<1) && ($submitted=='yes')) {
					$update_message = User::UpdateUser($userId,$displayName,$blurb,$email,$regNumber,$active);
					if ($update_message != "ok") {
						$page->assign('message', $update_message);
					}
					if ($currentUser->superadmin==ALLOW) {
						$superadmin = getParam('superadmin', NULL);
						User::SetSuperadmin($userId,$superadmin);
					} else {
						// display submitted fields
					}
					$displayUser = User::RetrieveUser($userId);
				}
 				$page->assign('method', 'edit');			
				break;
			case 'updateUserLogin':
				$passwordsDiffer = NULL;
 				$password1 = getParam('newpwd', NULL);
 				if ((trim($password1)=='') && ($editState=='editPassword')) $missingFields['newpwd']=TRUE;
				$password2 = getParam('newpwd2', NULL);
				if ((trim($password2)=='') && ($editState=='editPassword')) $missingFields['newpwd2']=TRUE;
				$userId = getParam('userId', NULL);
				if ($password1!==$password2) {
					$passwordsDiffer=TRUE;
				} elseif ((sizeof($missingFields)<1) && ($editState=='editPassword')) {
					User::UpdateUserLogin($userId,$password1,$password2);
				}	
				$displayUser = User::RetrieveUser($userId);			
				$page->assign('method', 'editpassword');
				$page->assign('passwordsDiffer',$passwordsDiffer);
				break;
			case 'updateUserAvatar':
			//	if ((($currentUser->sitewidePermissions['EditUser']!=ALLOW) || 
			//		($currentUser->sitewidePermissions['MakeLevelZeroUser']!=ALLOW)) &&
			//		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
				$userId = getParam('userId', NULL);
				$avatar = (isset($_FILES['avatar'])) ? $_FILES['avatar'] : '';
			//	print_r($avatar);
				$avatarError = '';
				$editState = getParam('editState',NULL);
				if ($editState=='editAvatar') {
					if (($avatar['type']!=='image/png') && ($avatar['type']!=='image/gif') &&
						($avatar['type']!=='image/jpeg')) {
						$avatarError.= "File type must be png, gif, or jpeg<br>";		
					}
					if ($avatar['size']>$config['avatarmaxsize']) {
						$avatarError.= "File is too big (max size: ".bytes($config['avatarmaxsize']).")<br>";
					}			
					if ($avatarError=='') {
						User::UpdateUserAvatar($userId,$avatar['tmp_name'],$avatar['type']);
					} else {
						$avatarError.= "<strong>File not uploaded</strong><br>";
					}
				}
				$displayUser = User::RetrieveUser($userId);
				$displayUser->GetAvatar();
				$page->assign('method', 'editavatar');
				$page->assign('avatarError',$avatarError);
				break;
			case 'updateUserSitewidePermissions':
				if ((($currentUser->sitewidePermissions['EditUser']!=ALLOW) || 
					($currentUser->sitewidePermissions['MakeLevelZeroUser']!=ALLOW)) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();	
				$submitted = GetParam('submitted',NULL);
				if ($submitted=='yes') {					
					$userId = getParam('userId', NULL);
					$addUser = getParam('adduser', NULL);
					$editUser = getParam('edituser', NULL);
					$makeLevelZeroUser = getParam('makelevelzerouser', NULL);
					$addPlugin = getParam('addplugin', NULL);
					$installTemplate = getParam('installtemplate', NULL);
					$editTemplate = getParam('edittemplate', NULL);
					$removeTemplate = getParam('removetemplate', NULL);
					User::UpdateUserSitewidePermissions($userId,$addUser,$editUser,$makeLevelZeroUser,$addPlugin,
						$installTemplate,$editTemplate,$removeTemplate);
				}	
				$displayUser = User::RetrieveUser($userId);			
				$page->assign('method', 'editpermissions'); 				
 				break;
			case 'addBlueprint2User':
				if (($currentUser->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();						
				trace("adding blueprint 2 user");
				$blueprintIds = GetParam('blueprintIds', NULL);
				if(is_null($blueprintIds)) {
          			DisplayMessage("<p>Unable to add blueprint to user: No Blueprint Selected.</p>");
          			return;
        		}
        		if (is_array($blueprintIds)) {
					foreach ($blueprintIds as $blueprintId) {
						if (($currentUser->projectTemplatePermissions[$blueprintId]['ChangeUserPermissions']!=ALLOW) &&
						($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						User::AddProjectTemplate2User($blueprintId,$displayUser->id);
					}
        		} else {
        			User::AddProjectTemplate2User($blueprintIds,$displayUser->id);	
        		}
				$page->assign('method', 'read');
				break;
			case 'addSimulation2User':
				if (($currentUser->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();						
				trace("adding simulation 2 user");
				$simulationIds = GetParam('simulationIds', array()); 
				$tutorSimulationIds = GetParam('tutorSimulationIds',array());
				if(is_null($simulationIds) && is_null($tutorSimulationIds)) {
          			DisplayMessage("<p>Unable to add simulation to user: No Simulation Selected.</p>");
          			return;
        		}
        		if (is_array($simulationIds)) {
					foreach ($simulationIds as $simulationId) {
						if ((isset($currentUser->projectPermissions[$simulationId]['ChangeUserPermissions'])
           				& $currentUser->projectPermissions[$simulationId]['ChangeUserPermissions']!=ALLOW) &&
						($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						User::AddProject2User($simulationId,$displayUser->id);
					}
        		} else {
        			User::AddProject2User($simulationIds,$displayUser->id);
        		}
        		if (is_array($tutorSimulationIds)) {
					foreach ($tutorSimulationIds as $tutorSimulationId) {
						if ((isset($currentUser->projectPermissions[$tutorSimulationId]['ChangeUserPermissions'])
           				& $currentUser->projectPermissions[$tutorSimulationId]['ChangeUserPermissions']!=ALLOW) &&
						($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						User::AddProject2User($tutorSimulationId,$displayUser->id,'tutor');
					}
        		} else {
        			User::AddProject2User($tutorSimulationIds,$displayUser->id,'tutor');
        		}				

				//Redirect("index.php?option=siteAdmin&cmd=viewUserSimulationPermissions&simulationId=".$simulationId."&userId=".$displayUser->id);
				$page->assign('method', 'read');				
				break;
			case 'deleteBlueprintFromUser':						
				if (($currentUser->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();			
				$blueprintId = GetParam('blueprintId', NULL);
				if (($currentUser->projectTemplatePermissions[$blueprintId]['ChangeUserPermissions']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
				if(is_null($blueprintId)) {
          			DisplayMessage("<p>Unable to remove blueprint from user: No Blueprint Selected.</p>");
          			return;
        		}				
				$displayUser->DeleteProjectTemplatePermissions($blueprintId);
				$page->assign('method', 'read');				
				break;
			case 'deleteSimulationFromUser':
				if (($currentUser->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();						
				$simulationId = GetParam('simulationId', NULL);
				if (($currentUser->projectPermissions[$simulationId]['ChangeUserPermissions']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();	
				if(is_null($simulationId)) {
          			DisplayMessage("<p>Unable to remove simulation from user: No Simulation Selected.</p>");
          			return;
        		}		
				$displayUser->DeleteProjectPermissions($simulationId);
				$page->assign('method', 'read');				
				break;
			case 'AddUserGroup2User':
 				if (($currentUser->sitewidePermissions['EditUser']!=ALLOW) &&
 					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();			
				$groupId = getParam('groupId', NULL);
				if(is_null($groupId)) {
          			DisplayMessage("<p>Unable to add group to user: No Group Selected.</p>");
          			return;
        		}
				UserGroup::AddUser2UserGroup($displayUser->id,$groupId);
				$page->assign('method', 'read'); 				
				break;
 			case 'RemoveUserGroupFromUser':
				if (($currentUser->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
				$displayUser = User::RetrieveUser($userId);		
 				$groupId = getParam('userGroupId', NULL);
 				if(is_null($groupId)) {
          			DisplayMessage("<p>Unable to remove group from user: No Group Selected.</p>");
          			return;
        		}
 				$userGroup = UserGroup::GetUserGroup($groupId);
 				$userGroup->RemoveMember($displayUser->id); 
				$page->assign('method', 'read'); 				
				break;
		}

		// blueprint pager info
		$bStartFrom = GetParam('bStart',0);
		$bPrevious = $bStartFrom - $config['listPageSize']>=0?$bStartFrom - $config['listPageSize']:-1;
		$bNext = $bStartFrom + $config['listPageSize'];
		$blueprintCount = ProjectTemplate::GetBlueprintCount();
 		$blueprints = $displayUser->GetUsersProjectTemplates($bStartFrom);
		$allBlueprints = ProjectTemplate::GetAllBlueprints();		
		if ($bNext>= $blueprintCount) {
			$bNext = -1;
		}
 		
	 	// 	simulation pager info
 		$sStartFrom = GetParam('sStart',0);
	 	$sPrevious = $sStartFrom - $config['listPageSize']>=0?$sStartFrom - $config['listPageSize']:-1;
		$sNext = $sStartFrom + $config['listPageSize'];
		$simCount = Project::GetSimulationCount();
	 	$simulations = $displayUser->GetUserProjectsPermissions();
	 	$sims = array();
	 	foreach ($simulations as $key=>$s) {
			$p = Project::GetProject($key);
			if ($p->IsActive){
				$sims[$key] = $p->Name . '('.$p->TemplateName.')';
			} 		
		 }
 		$allSimulations = Project::GetProjects();		
		if ($sNext>= $simCount) {
			$sNext = -1;
		}
	
		// groups info
		$allGroups = UserGroup::GetUserGroups(0,'name ASC',FALSE);
 		$groups = $displayUser->GetGroups();	
 		
 		// sitewide permissions
		$individualPermissions = User::GetUserSitewidePermissions($displayUser->id);
 		$displayUser->GetSitewidePermissions();
 		 				 		
 		 trace("<pre>".print_r($displayUser,true)."</pre>");
		$page->assign('bPrevious',$bPrevious);
		$page->assign('bNext',$bNext); 		
		$page->assign('blueprints',$blueprints);
		$page->assign('allblueprints',$allBlueprints);
		$page->assign('sPrevious',$sPrevious);
		$page->assign('sNext',$sNext); 	
		$page->assign('simulations', $sims);
		$page->assign('allsimulations', $allSimulations);
		$page->assign('individualPermissions',$individualPermissions);
		$page->assign('groups', $groups);
		$page->assign('allgroups', $allGroups);
		$page->assign('user',$displayUser);
$page->assign('userprefs',$displayUser->GetPreferences());
		$page->assign('userId', $userId);
		$page->assign('currentUser', $currentUser);		
		$page->assign('missingFields',$missingFields);
		trace("<pre>missingFields: ".print_r($missingFields,true)."</pre>");
 	} else {
 		//InsufficientPermissions();
 		 DisplayPublicProfile();
 		 return;
 		/**
 		 ** IMplement a "public profile view"; 		
 		 */ 		
 	}
 }
function DisplayPublicProfile() {
  global $page;
  $userid = GetParam('userId',null);
  if (is_null($userid)) {
    DisplayMessage($strings['MSG_NO_USER_FOUND']);
    return;
  }
  $user = User::RetrieveUser($userid,false);
  $page->Template="ViewPublicProfile.php.tpl";
  $page->assign('profile',$user);
  //DisplayMessage('Public profiles are not currently implemented.' .urlencode(Ticket("")));
}
/**
 * adds a new user
 */ 
 function AddUser() {
 	global $page,$strings,$_PLUGINS;
	if (($_SESSION[USER]->sitewidePermissions['AddUser']!=ALLOW) &&
 		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 	
 	$method = getParam('method',NULL);
 	$message = NULL;
 	$userAdded = FALSE;
 	$passwordsDiffer = NULL;
 	$missingFields = NULL;
 	$usernameExists = NULL;
 	$currentUser = $_SESSION[USER];
 	$plugins = $_PLUGINS->getEventPlugins('onAuthenticateUser');
 	if ($method!=NULL) {
 		$missingFields = array();
 		 // should fail if username already taken
 		$username = getParam('username', NULL);
		$userCount = User::GetUserCount(sprintf("AND username='%s'",$username));
 		$usernameExists = ($userCount>0) ? TRUE : FALSE; 
 		if ($usernameExists) {
 			$page->assign('usernameExists',$usernameExists);
 		}		
 		
 		if (trim($username)=='') $missingFields['username']=TRUE;
 		$pwd1 = getParam('pwd1', NULL);
		if (trim($pwd1)=='') $missingFields['pwd1']=TRUE; 		
 		$pwd2 = getParam('pwd2', NULL);
 		if (trim($pwd2)=='') $missingFields['pwd2']=TRUE;
 		if ($pwd1!=$pwd2) {
			$passwordsDiffer = TRUE;	
			$page->assign('passwordsDiffer',$passwordsDiffer);
		}
		$authtype= getParam('authtype','');
 		$salt = GetSalt();
 		$password =  HashPassword($pwd1,$salt);
 		//trace("pwd1 is".$pwd1.", pwd2 is ".$pwd2.", password is".$password);
 		$displayName = getParam('displayName', NULL);
		if (trim($displayName)=='') $missingFields['displayName']=TRUE; 		
 		$email = getParam('email', NULL);
		if (trim($email)=='') $missingFields['email']=TRUE;
		$blurb = getParam('blurb', '');
		$regNumber = getParam('regnumber', NULL);	
 		$active = getParam('active', NULL); 		
 		if ((sizeof($missingFields)==0) && 
 			(!$usernameExists) &&
 			(!$passwordsDiffer)) {
 			if ($authtype=='TleAuthenticate'){
 				$newUser = User::ManualAddUser($username, $password, $salt, $displayName, $email, $regNumber, $active);
 				$userAdded = TRUE;
 			}else {
 				//echo 'Adding new external user';
 				$newUser = User::CreateAuthenticatedUser($authtype,$username);
 				//$newUser = User::RetrieveExternalUser($authtype,$userId);
 				$update_message = $newUser->UpdateUser($newUser->id,$displayName,$blurb,$email,$regNumber,$active);
				if ($update_message != "ok") {
					$page->assign('message', $update_message);
				}
 				$userAdded = TRUE;
 				Redirect('index.php?option=siteAdmin&cmd=listUsers');
 			}
 		} else { $userAdded = FALSE; }
 		//if (is_null($newUser)) {
	 		//	We failed to create a new user!
 		//	Redirect('index.php?option=message&cmd='.$strings['MSG_UNABLE_TO_ADD_USER']);
 		//}
		/**
		$addUser = getParam('adduser', NULL);
 		$editUser = getParam('edituser', NULL);
 		$makeLevelZeroUser = getParam('makelevelzerouser', NULL);
 		$addPlugin = getParam('addplugin', NULL);
 		$installTemplate = getParam('installtemplate', NULL);
 		$editTemplate = getParam('edittemplate', NULL);
 		$removeTemplate = getParam('removetemplate', NULL);
		if ((($currentUser->sitewidePermissions['EditUser']==ALLOW) &&
			($currentUser->sitewidePermissions['MakeLevelZeroUser']==ALLOW)) ||
			($currentUser->superadmin==ALLOW)) {
			if ($userAdded==TRUE) {
 				$newUser->updateUserSitewidePermissions($newUser->id,$addUser,$editUser,$makeLevelZeroUser,
 				$addPlugin,$installTemplate,$editTemplate,$removeTemplate);
			}
		}**/
		if ($currentUser->superadmin==ALLOW) {
			$superadmin = getParam('superadmin', NULL);
			if ($userAdded==TRUE) {
				User::SetSuperadmin($newUser->id,$superadmin);
			}
		}
		$page->assign('missingFields',$missingFields);
		$page->assign('userAdded',$userAdded);
		if ($userAdded==FALSE) {
			trace("active is ".$active.", superadmin is ".$superadmin);
			// prefill form
			$page->assign('username',$username);
			$page->assign('pwd1',$pwd1);
			$page->assign('pwd2',$pwd2);
			$page->assign('displayName',$displayName);
			$page->assign('email',$email);
			$page->assign('regnumber',$regNumber);
			$page->assign('active',$active);
			$page->assign('superadmin',$superadmin);
			//$page->assign('authtype',$userAuthType);
			//$individualPermissions = array();
			//$individualPermissions['AddUser'] = $addUser;
			//$individualPermissions['EditUser'] = $editUser;
			//$individualPermissions['MakeLevelZeroUser'] = $makeLevelZeroUser;
			//$individualPermissions['AddPlugin'] = $addPlugin;
			//$individualPermissions['InstallTemplate'] = $installTemplate;
			//$individualPermissions['EditTemplate'] = $editTemplate;
			//$individualPermissions['RemoveTemplate'] = $removeTemplate;			
			//$page->assign('individualPermissions',$individualPermissions);
		} else {
			$message = "User successfully added";
		}
 	}
 	$userGroupId = getParam('userGroupId',NULL);
 	if (($userGroupId !=NULL) && ($userAdded)) { // check if usergroup and added 
	  	// add to usergroup then go to view group page for that id		
	  	UserGroup::AddUser2UserGroup($newUser->id, $userGroupId);
	  	Redirect("index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId=".$userGroupId);
 	} else {
 		$page->Template = 'addUser.php.tpl';
 	} 
 	$page->assign('message',$message);
 	$page->assign('userGroupId', $userGroupId);
 	$page->assign('currentUser',$currentUser);
 	$page->assign('authtypes',$plugins);
 }

 /**
  * gives list of all user groups
  */
  function ListUserGroups() {
  	global $page,$wrapper,$config,$database;
 	if (($_SESSION[USER]->sitewidePermissions['AddUser']==ALLOW) ||
 		($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) ||
 		($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {  	
 		$gmethod = getParam('gmethod',NULL);
		$groupsQuery = ' ';
		$gorder = '';		
		$gsearchBy = GetParam('gsearchBy',0);
		$gsearchTerm = GetParam('gsearchTerm','');
		if ($gsearchTerm!='') {
			switch($gsearchBy) {
				case 0:
				default:
					$groupsQuery = 'AND name LIKE ';
					$gorder = 'name ASC';
					break;			
			}
		}				
		$gorderBy = GetParam('gorderBy',NULL);
		if ($gorderBy) {
			$gorder = $gorderBy;
		} elseif ($gorder=='') {
			$gorder = 'name ASC';
		}			
		$page->assign('gsearchTerm',$gsearchTerm);
		$page->assign('gsearchBy',$gsearchBy); 		
 		switch ($gmethod) {
 			case 'multiSubmit':
 			trace("multiSubmit");
 				$multi_submit = getParam('multi_submit',NULL);
				switch ($multi_submit) {
 					case 'delete':
						if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
							($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						trace("getting fldarray");
						$gfieldsArray = getParam('gselected_fld',NULL);
						foreach ($gfieldsArray as $f) {
							$group = UserGroup::GetUserGroup($f); // XXX
							$group->RemoveUserGroup();					
						}
						//trace("deleting multiple users: <pre>".print_r($fieldsArray,true)."</pre>");						
						break;
					case 'enable':
						if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$gfieldsArray = getParam('gselected_fld',NULL);
						foreach ($gfieldsArray as $f) {
							$group = UserGroup::GetUserGroup($f);
							$group->EnableUserGroup();					
						}						
						break;
					case 'disable':
						if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$gfieldsArray = getParam('gselected_fld',NULL);
						foreach ($gfieldsArray as $f) {
							$group = UserGroup::GetUserGroup($f);
							$group->DisableUserGroup();					
						}						
						break;			
					case 'exportcsv':
						if (($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
						$gfieldsArray = getParam('gselected_fld',NULL);			
						Redirect("CsvHandler.php?selected_fld=".SerialiseArray($gfieldsArray)."&listType=usergrouplist");
						break;														
					case '':
						break;					
					default:
				}
 				break;
			case 'deleteMultiUserGroups':
				if (($_SESSION[USER]->sitewidePermissions['EditUser']!=ALLOW) &&
					($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();			
				$csvList = getParam('deleteusergroups',NULL);
				$deleteArray = explode(",", $csvList);
				foreach ($deleteArray as $d) {
					$query = sprintf("SELECT * FROM usergroups WHERE name='%s'",$d);
					$results = $database->queryAssoc($query);
					foreach ($results as $r) {
						$group = UserGroup::GetUserGroup($r['groupid']);
						$group->RemoveUserGroup();
					}
					$query = sprintf("SELECT * FROM usergroups WHERE groupid='%s'",$d);
					$results = $database->queryAssoc($query);
					foreach ($results as $r) {
						$group = UserGroup::GetUserGroup($r['groupid']);
						$group->RemoveUserGroup();
					}
				
				}
				break; 	
			case 'importMultiUserGroups':
				if (($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']!=ALLOW) &&
					($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();				
				if (!empty($_FILES['importusergroups']['tmp_name'])) {
					$csverror = '';
					$fileerror = FALSE;
					$existing = array();
					
					$handle = fopen($_FILES['importusergroups']['tmp_name'], "r");
					while (($data = fgetcsv($handle)) !== FALSE) {
   						$num = count($data); // should always be the same (number of fields)
   						if ($num != 4)  $fileerror = "Wrong number of columns. "; // incorrect number of fields
						/* CSV format should be:
   						 Column	|	Content
   						 0		|	name
   						 1		| 	active
   						 2		|	permissions array or 'none'
   						 3		|	members array or 'none'
   						*/
   						
   						// intialise name
   						$data[0] = (isset($data[0])) ? addslashes($data[0]) : "''";
   						$data[1] = (isset($data[1])) ? addslashes($data[1]) : 1;
   						$data[2] = (isset($data[2])) ? addslashes($data[2]) : 'none';
   						$data[3] = (isset($data[3])) ? addslashes($data[3]) : 'none';
   						
   						//$userid = $database->database->nextID('users');
   						$query = sprintf("SELECT COUNT(*) AS numgroups FROM usergroups ".
   										"WHERE name='%s' AND deleted=0",
   										$data[0]);
   						$results = $database->queryAssoc($query);
   						if ($results[0]['numgroups'] > 0) { // exists in database
   							$existing[] = array($userid,$data[0]);
   						} else { 
   							$query = sprintf("SELECT COUNT(*) AS numgroups FROM usergroups WHERE name='%s' AND deleted=1",
   											$data[0]);
   							$results = $database->queryAssoc($query);
   							if ($results[0]['numgroups']>0) { // exists as deleted in database
   								$query = sprintf("UPDATE usergroups SET deleted=0 WHERE name='%s'",
   												$data[0]);
   							} else { // no record in database
   								$query = sprintf("INSERT INTO usergroups (name,".
										"deleted,active) VALUES ".
										"('%s',0,'%s')",
										$data[0],
										$data[1]
										);
   							}
							trace("importquery: ".$query);
							$database->execute($query);
   						}
   						$userid = $database->database->lastInsertID();
						
   						$permExisting = array();
   						// update permissions table
   						if ($data[2]!= 'none') {
   							$permissionsArray = DeserialiseArray_NoBase64($data[2]);
   						
   							$query = sprintf("SELECT COUNT(*) AS numentries ".
   											"FROM sitewidepermissions ".
   											"WHERE usertype='group' AND userid=%s ".
   											"AND deleted=1",$userid);
   							$results = $database->queryAssoc($query);
   							if ($results[0]['numentries']>0) {
   								$query = sprintf("DELETE FROM sitewidepermissions ".
   												"WHERE usertype='group' AND userid=%s ".
   												"AND deleted=1",$userid);
   								$results = $database->queryAssoc($query);
   							}
   							$query = sprintf("SELECT COUNT(*) AS numentries ".
   											"FROM sitewidepermissions ".
   											"WHERE usertype='group' AND userid=%s ".
   											"AND deleted=0",$userid);
   							$results = $database->queryAssoc($query);
   							if ($results[0]['numentries']>0) {
   								$permExisting[] = array($userid,$data[0]);
   							} else {
								$query = sprintf("INSERT INTO sitewidepermissions (userid,usertype,".
											"adduser,edituser,makelevelzerouser,addplugin,installtemplate,".
											"edittemplate,removetemplate,deleted) VALUES ".
											"(%s,'group',%s,%s,%s,%s,%s,%s,%s,0)",
											$userid,
											$permissionsArray['AddUser'],
											$permissionsArray['EditUser'],
											$permissionsArray['MakeLevelZeroUser'],
											$permissionsArray['AddPlugin'],
											$permissionsArray['InstallTemplate'],
											$permissionsArray['EditTemplate'],
											$permissionsArray['RemoveTemplate']);
									$result = $database->execute($query);   								
   							}
   						}
   						
   						
   						// update user2usergroup table
   						if ($data[3]!= 'none') {
   							$membersArray = DeserialiseArray_NoBase64($data[3]);
   							foreach ($membersArray as $m) {
   								
   								// 1) find if username in local database
   								$query = sprintf("SELECT userid FROM users ".
   												"WHERE username='%s' AND deleted=0",
   												$m);
   								$results = $database->queryAssoc($query);
   								if (sizeof($results)>0) {
   									// allocate user to group
   									// delete record if exists
   									$query = sprintf("DELETE FROM user2usergroup ".
   													"WHERE userid=%s AND groupid=%s",
   													$results[0]['userid'],
   													$userid);
   									$result = $database->execute($query);
   									
   									// insert record
   									$query = sprintf("INSERT INTO user2usergroup (groupid,userid,deleted) ".
   													"VALUES (%s,%s,0)",
   													$userid,
   													$results[0]['userid']);
   									$result = $database->execute($query);
   								} else {
   									// 2) else,  if username stored as external id
   									$query = sprintf("SELECT internalid FROM userauth ".
   													"WHERE externalid='%s' AND deleted=0",
   													$m);
   									$results = $database->queryAssoc($query);
   									if (sizeof($results)>0) {
   										// allocate user to group
   										// deete record if exists as deleted
   										$query = sprintf("DELETE FROM user2usergroup ".
   														"WHERE userid=%s AND groupid=%s",
   														$results[0]['internalid'],
   														$userid);
   										$result = $database->execute($query);
   										// insert record
   										$query = sprintf("INSERT INTO user2usergroup (groupid,userid,deleted) ".
   														"VALUES (%s,%s,0)",
   														$userid,
   														$results[0]['internalid']);
   										$result = $database->execute($query);
   									}
   								}
   								   								
   								/* importing by id - deprecated
   								$query = sprintf("DELETE FROM user2usergroup ".
   												"WHERE groupid=%s AND userid=%s AND deleted=1",
   												$userid,
   												$m);
   								$result = $database->execute($query);
   								
   								$query = sprintf("SELECT COUNT(*) AS numentries FROM user2usergroup ".
   												"WHERE groupid=%s AND userid=%s AND deleted=0",
   												$userid,
   												$m);
   								$results = $database->queryAssoc($query);
   								if ($results[0]['numentries']>0) {
   									$memberExisting = array($userid,$m);
   								} else {
   									$query = sprintf("INSERT INTO user2usergroup (groupid,userid,deleted) ".
   													"VALUES (%s,%s,0)",
   													$userid,
   													$m);
   									$results = $database->execute($query);
   								}
   								*/
   							}
   						}
					}
					fclose($handle);
					if (count($existing)>0) { 
						$csverror.= "The following group ids or names already exist and have not been added: <br />";
						foreach ($existing as $e) {
							$csverror.= "Group id: ".$e[0].", Name: ".$e[1]."<br />";
						}	
					}
					if (count($permExisting)>0) {
						$csverror.= "The following groups already have permissions set: <br>";
						foreach ($permExisting as $p) {
							$csverror.= "Group id: ".$p[0].", Name: ".$p[1]."<br />";
						}
					}
				//	if (count($memberExisting)>0) {
				//		$csverror.= "The following users are already assigned to these groups: <br>";
				//		foreach ($memberExisting as $m) {
				//			$cvserror.=  "Group id: ".$m[0].", Member id: ".$m[1]."<br />";
				//		}
				//	}
					if ($fileerror) $csverror = "Error: File is not in correct format. ".$fileerror;
					$page->assign('csverror',$csverror);
				}
				break;
			case 'deleteUserGroup':
				if (!(($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) ||
 					($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']==ALLOW) ||
 					($_SESSION[USER]->superadmin==ALLOW))) InsufficientPermissions();  		

				$userGroupId = getParam('delete_usergroupid', NULL);
				trace("usergroup id is ::".$userGroupId);
				$userGroup = UserGroup::GetUserGroup($userGroupId);
				$userGroup->RemoveUserGroup();
				break;
 			default:
 		}
		$gstartFrom = GetParam('gstart',0);	
		if ($gsearchTerm!='') {
			$groupsQuery .=$database->database->quote($gsearchTerm) ;//'\''.$searchTerm.'\'';
		}
		$currentUser = $_SESSION[USER];
		//echo $groupsQuery."<br/>";
		$userGroupCount = UserGroup::GetUserGroupCount($groupsQuery);		
		$userGroups = UserGroup::SearchGroups($groupsQuery,$order,$startFrom);	
		$sims = array();
		$simPerms = array();
		foreach ($userGroups as $g) {
			$sims[$g->id] = $g->GetProjects();			
			foreach ($sims[$g->id] as $key=>$name) {
				$simPerms[$g->id][$key] = $currentUser->getProjectPermissions($key);
			}
		}		
		trace("<pre>".print_r($sims,true)."</pre>");
		trace("<pre>".print_r($simPerms,true)."</pre>");
		//$userGroupCount = UserGroup::GetUserGroupCount();
  		$gorderBy = getParam('gorderBy','name ASC');			
		$gprevious = $gstartFrom - $config['listPageSize']>=0?$gstartFrom - $config['listPageSize']:-1;
		$gnext = $gstartFrom + $config['listPageSize'];
		//echo $userCount .':'.$next;
		if ($gnext>= $userGroupCount) {
			$gnext = -1;
		}
		$page->assign('gprevious',$gprevious);
		$page->assign('gnext',$gnext);  		
  		//$page->Template = 'listUserGroups.php.tpl';
		$page->Template = 'listUsers.php.tpl';
		// assigning current user so have superadmin and project permissions for groups
  		$page->assign('groups', $userGroups);
  		$page->assign('sims', $sims);
  		$page->assign('simPerms', $simPerms);
  		$someResults = (sizeof($userGroups) > 0) ? 1 : 0;
  		$page->assign('currentUser',$currentUser);
  		$page->assign('someresults', $someResults);
 	} else {
 		InsufficientPermissions();
 	}
  }
 
  /**
  * page to view and edit user group details
  */
 function ViewUserGroup() {
 	global $page,$config;
 	if (($_SESSION[USER]->sitewidePermissions['AddUser']==ALLOW) ||
 		($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) ||
 		($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {

 		$currentUser = $_SESSION[USER];
 		$page->Template = 'viewUserGroup.php.tpl';
 		$method = GetParam('method',NULL);
 		$missingFields = array();
 		$editState = getParam('editState',NULL);
 		$page->assign('addsimulationdialogprojects',Project::GetProjects());
 		$page->assign('addblueprintdialogtemplates',ProjectTemplate::GetAllBlueprints());
 		$dialogUsers = User::GetUsers(0,'displayname');
 		// need to get all user fields then assign
 		$assignDialogUsers  = array();
 		foreach ($dialogUsers as $key=>$d) {
 			$assignDialogUsers[$key] = User::RetrieveUser($d->id,true); 
 			$assignDialogUsers[$key]->displayName = str_replace('\'','`',$assignDialogUsers[$key]->displayName);
 		}
 		$page->assign('addusersdialog',$assignDialogUsers);
 		
		switch($method) {
			case 'updateUserGroup':
				$submitted = getParam('submitted',NULL);
				$userGroupId = getParam('userGroupId', NULL);
				if ($submitted=='yes') {
 					$name = getParam('groupName', NULL);
 					trace("name is ".$name);
 					if ((trim($name)=='') && ($editState=='editDetails')) $missingFields['groupName']=TRUE;
 					$active = getParam('active', NULL);
 					if (sizeof($missingFields)<1) {
	 					UserGroup::UpdateUserGroup($userGroupId, $name, $active);
 					}
				}
 				$userGroup = UserGroup::GetUserGroup($userGroupId);
				$page->assign('method', 'edit');
				break;
			case 'updateUserGroupSitewidePermissions':
				$submitted = getParam('submitted',NULL);
				$userGroupId = getParam('userGroupId', NULL);
				if ($submitted=='yes') {
					$addUser = getParam('adduser', NULL);
					$editUser = getParam('edituser', NULL);
					$makeLevelZeroUser = getParam('makelevelzerouser', NULL);
					$addPlugin = getParam('addplugin', NULL);
					$installTemplate = getParam('installtemplate', NULL);
					$editTemplate = getParam('edittemplate', NULL);
					$removeTemplate = getParam('removetemplate', NULL);
					UserGroup::UpdateUserGroupSitewidePermissions($userGroupId,$addUser,$editUser,$makeLevelZeroUser,$addPlugin,
						$installTemplate,$editTemplate,$removeTemplate);
				}
				$userGroup = UserGroup::GetUserGroup($userGroupId);
				$page->assign('method', 'editpermissions');		
				break;
			case 'addBlueprint2UserGroup':
				trace("adding blueprint 2 user group");
				$userGroupId = GetParam('userGroupId', NULL);
				if(is_null($userGroupId)) {
          			DisplayMessage("<p>Unable to add blueprint to group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);				
				$blueprintIds = GetParam('blueprintIds', NULL);
				if(is_null($blueprintIds)) {
          			DisplayMessage("<p>Unable to add blueprint to group: No Blueprint Selected.</p>");
          			return;
        		}
        		if (is_array($blueprintIds)) {
					foreach ($blueprintIds as $blueprintId) {
						UserGroup::AddProjectTemplate2UserGroup($blueprintId,$userGroupId);
					}
        		} else {
        				UserGroup::AddProjectTemplate2UserGroup($blueprintIds,$userGroupId);
        		}
				$page->assign('method', 'read');
				break;
			case 'addSimulation2UserGroup':
				trace("adding simulation 2 user group");
				$userGroupId = GetParam('userGroupId', NULL);
				if(is_null($userGroupId)) {
          			DisplayMessage("<p>Unable to add simulation to group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);				
				$simulationIds = GetParam('simulationIds', NULL);
				if(is_null($simulationIds)) {
          			DisplayMessage("<p>Unable to add simulation to group: No Simulation Selected.</p>");
          			return;
        		}
        		if (is_array($simulationIds)) {
					foreach ($simulationIds as $simulationId) {
						UserGroup::AddProject2UserGroup($simulationId,$userGroupId);
					}
        		} else {
        			UserGroup::AddProject2UserGroup($simulationIds,$userGroupId);
        		}
				$page->assign('method', 'read');
				break;
			case 'deleteBlueprintFromUserGroup':
				$userGroupId = GetParam('userGroupIdBP', NULL);	
				if(is_null($userGroupId)) {
          			DisplayMessage("<p>Unable to delete blueprint from group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);
				$blueprintId = GetParam('blueprintId', NULL);
				if(is_null($blueprintId)) {
          			DisplayMessage("<p>Unable to delete blueprint from group: No Blueprint Selected.</p>");
          			return;
        		}
				$userGroup->DeleteProjectTemplatePermissions($blueprintId);
		 		$page->assign('method', 'read');
				break;
			case 'deleteSimulationFromUserGroup':
				$userGroupId = GetParam('userGroupId', NULL);	
		        if(is_null($userGroupId)) {

         			DisplayMessage("<p>Unable to delete simulation from group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);		
				$simulationId = GetParam('simulationId', NULL);
				if(is_null($simulationId)) {
          			DisplayMessage("<p>Unable to add delete simulation from group: No Simulation Selected.</p>");
          			return;
        		}
				$userGroup->DeleteProjectPermissions($simulationId);
				$page->assign('method', 'read');
				break;
			case 'AddUser2UserGroup':
				$userGroupId = GetParam('userGroupId', NULL);		
     		   	if(is_null($userGroupId)) {
          			DisplayMessage("<p>Unable to add user to group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);	
				$userId = getParam('userId', NULL);
				if(is_null($userId)) {
          			DisplayMessage("<p>Unable to add user to group</p><p>Are you sure you selected a user?</p>");
          			return;
        		}
				// if userid is array then do several times
				if (is_array($userId)) {
					foreach ($userId as $u) {
						UserGroup::AddUser2UserGroup($u,$userGroupId);
					}
				} else {
 					UserGroup::AddUser2UserGroup($userId,$userGroupId);
				}
 				$page->assign('method', 'read'); 			
 				break;
	 		case 'RemoveMemberFromGroup':
				$userGroupId = GetParam('userGroupId', NULL);	
			  	if(is_null($userGroupId)) {
          			DisplayMessage("<p>Unable to add remove member from group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);	 		
 				$userId = getParam('userid', NULL);
 				if(is_null($userId)) {
          			DisplayMessage("<p>Unable to remove member from group: No User Selected.</p>");
          			return;
        		}
 				$userGroup->RemoveMember($userId);
 				$page->assign('method', 'read'); 
	 			break;
 			default:
 				$userGroupId = GetParam('userGroupId',NULL);
 				 if(is_null($userGroupId)) {
          			DisplayMessage("<p>Unable to view group: No Group Selected.</p>");
          			return;
        		}
				$userGroup = UserGroup::GetUserGroup($userGroupId);	 
				$page->assign('method', 'read'); 			
 				break;
		}

		//	blueprint pager info
		$bStartFrom = GetParam('bStart',0);
		$bPrevious = $bStartFrom - $config['listPageSize']>=0?$bStartFrom - $config['listPageSize']:-1;
		$bNext = $bStartFrom + $config['listPageSize'];
		$blueprintCount = ProjectTemplate::GetBlueprintCount();
	 	$blueprints = $userGroup->GetProjectTemplates($bStartFrom);
	 	$allBlueprints = ProjectTemplate::GetAllBlueprints();		
		if ($bNext>= $blueprintCount) {
			$bNext = -1;
		}
 		
		// 	simulation pager info
	 	$sStartFrom = GetParam('sStart',0);
		$sPrevious = $sStartFrom - $config['listPageSize']>=0?$sStartFrom - $config['listPageSize']:-1;
		$sNext = $sStartFrom + $config['listPageSize'];
		$simCount = Project::GetSimulationCount();
 		$simulations = $userGroup->GetProjects($sStartFrom);
 		$allSimulations = Project::GetProjects();		
		if ($sNext>= $simCount) {
			$sNext = -1;
		}
	
		// sitewide permissions
		$sitewidePermissions = UserGroup::GetUserGroupSitewidePermissions($userGroupId);
		
 		// get members
 		$members = $userGroup->GetMembers();
 		$allUsers = User::GetUsers(0,'username ASC',FALSE);
	 		
		$page->assign('bPrevious',$bPrevious);
		$page->assign('bNext',$bNext); 		
		$page->assign('blueprints',$blueprints);
		$page->assign('allblueprints',$allBlueprints);
		$page->assign('sPrevious',$sPrevious);
		$page->assign('sNext',$sNext); 	
		$page->assign('simulations', $simulations);
 		$page->assign('allsimulations', $allSimulations); 	
 		$page->assign('usergroup', $userGroup);
 		$page->assign('userGroupId', $userGroupId);
 		$page->assign('members', $userGroup->members);
 		$page->assign('allusers', $allUsers);
 		$page->assign('sitewidePermissions', $sitewidePermissions);
 		$page->assign('currentUser',$currentUser);
 		$page->assign('missingFields',$missingFields);
 	} else {
 		InsufficientPermissions();
 	}
 }

/**
 * page to create a new user and add it to the specified group
 */
 function CreateAddUser2UserGroup() {
 	global $page,$strings;
 	$userGroupId = getParam('userGroupId', NULL);
 	$currentUser = $_SESSION[USER];

	// do checks as in adduser
	// jjst adapt adduser method to check for usergroupid and change template? ***

 	$page->Template="addUser.php.tpl";
 	$page->assign('userGroupId',$userGroupId);
 	$page->assign('currentUser',$currentUser);
 }

 /**
  * page to add a new user group
  */
function AddUserGroup() {
	global $page,$strings;
 	if (($_SESSION[USER]->sitewidePermissions['AddUser']==ALLOW) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {
 		$message = getParam('message',NULL);  			
 		$method = getParam('method',NULL);
 		$currentUser = $_SESSION[USER]; 		
 		if ($method!=NULL) {
 			$missingFields = array();
 			$name = getParam('groupName', NULL);
 			if (trim($name)=='') $missingFields['groupName']=TRUE;
 			$addUser = getParam('adduser', NULL);
 			$editUser = getParam('edituser', NULL);
 			$makeLevelZeroUser = getParam('makelevelzerouser', NULL);
 			$addPlugin = getParam('addplugin', NULL);
 			$installTemplate = getParam('installtemplate', NULL);
 			$editTemplate = getParam('edittemplate', NULL);
 			$removeTemplate = getParam('removetemplate', NULL);
 			if (sizeof($missingFields)==0) {
				$newUserGroup = UserGroup::AddUserGroup($name);
 				if (is_null($newUserGroup)) {
			 		//	We failed to create a new user!
 					Redirect('index.php?option=message&cmd='.$strings['MSG_UNABLE_TO_ADD_USERGROUP']);
	 			} 		
 				if (($_SESSION[USER]->sitewidePermissions['MakeLevelZeroUser']==ALLOW) ||
 					($_SESSION[USER]->superadmin==ALLOW)) {
 					$newUserGroup->updateUserGroupSitewidePermissions($newUserGroup->id,$addUser,$editUser,
 					$makeLevelZeroUser,$addPlugin,$installTemplate,$editTemplate,$removeTemplate);
	 			}			
	 			$message = "User Group successfully added";		
 			}
 			$page->assign('missingFields', $missingFields);
 		}
		$page->Template = 'addUserGroup.php.tpl';
		$page->assign('currentUser', $currentUser);
		$page->assign('message',$message);
 	} else {
 		InsufficientPermissions();
 	}
}

/**
 * display the permissions for a given project and user 
 */
 function ViewUserSimulationPermissions() {
 	global $page; 	 	
 	$userId = getParam('userId', NULL);
 	$projectId = getParam('simulationId', NULL);
 	$user = User::RetrieveUser($userId);	 
 	$currentUser = $_SESSION[USER];	
 	if (($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) ||
 		($_SESSION[USER]->id==$user->id) ||
 		($_SESSION[USER]->superadmin==ALLOW)) { 
 		$projectPermissions = $user->GetProjectPermissions($projectId);
 		$individualPermissions = User::GetIndividualProjectPermissions($user->id,$projectId);
		$redirect = getParam('redirect',null);
		
 		$page->Template = 'viewUserProjectPermissions.php.tpl';
 		$page->assign('user',$user);
 		$page->assign('projectPermissions', $projectPermissions);
 		$page->assign('individualPermissions', $individualPermissions);
 		$page->assign('projectId',$projectId);
 		$page->assign('currentUser',$currentUser);
		$page->assign('redirect',$redirect);
 	} else {
 		InsufficientPermissions();
 	}
 }
 
 /**
  * updates and displays the permissions for a given project and user
  */
function UpdateUserSimulationPermissions() {
	global $page;
	$userId = getParam('userId', NULL);
	$projectId = getParam('projectId', NULL);
	$user = User::RetrieveUser($userId);	
 	$currentUser = $_SESSION[USER];	
	if ((($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) &&
 		($_SESSION[USER]->projectPermissions[$projectId]['ChangeUserPermissions']==ALLOW)) ||
 		($_SESSION[USER]->superadmin==ALLOW)) { 	
		$permissionsArray = array();
		$permissionsArray['usestafftools'] = getParam('usestafftools', NULL);
		$permissionsArray['deleteanyitem'] = getParam('deleteanyitem', NULL);
		$permissionsArray['deleteitem'] = getParam('deleteitem', NULL);
		$permissionsArray['viewitem'] = getParam('viewitem', NULL);
		$permissionsArray['additem'] = getParam('additem', NULL);
		$permissionsArray['editanyitem'] = getParam('editanyitem', NULL);
		$permissionsArray['edititems'] = getParam('edititems', NULL);
		$permissionsArray['stopproject'] = getParam('stopproject', NULL);
		$permissionsArray['changeuserpermissions'] = getParam('changeuserpermissions', NULL);
		$permissionsArray['editplugin'] = getParam('editplugin', NULL);
		$submitted = getParam('submitted',NULL);
		$redirect = getParam('redirect',null);
		if ($submitted=='yes') { 	
			User::UpdateIndividualProjectPermissions($user->id,$projectId,$permissionsArray);
			if (!is_null($redirect)) {
			  //die($redirect);
			    Redirect($redirect);
			    return;
			}
		}
		
		$individualPermissions = $user->GetIndividualProjectPermissions($user->id, $projectId);
		$projectPermissions = $user->GetProjectPermissions($projectId);
		$page->Template = 'viewUserProjectPermissions.php.tpl';
		$page->assign('user', $user);
		$page->assign('projectPermissions', $projectPermissions);
		$page->assign('individualPermissions', $individualPermissions);
		$page->assign('projectId', $projectId);
		$page->assign('method', 'editpermissions');
		$page->assign('currentUser', $currentUser);
		$page->assign('redirect',$redirect);
 	} else {
 		InsufficientPermissions();		
 	}
}

/**
 * display the permissions for a given project template and user 
 */
 function ViewUserBlueprintPermissions() {
 	global $page;
 	$userId = getParam('userId', NULL);
 	$page->Template = 'viewUserProjectTemplatePermissions.php.tpl';
	$projectTemplateId = getParam('blueprintId', NULL);
  	$user = User::RetrieveUser($userId); 	
 	$currentUser = $_SESSION[USER];
	if (($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) ||
 		($_SESSION[USER]->id==$user->id) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {  	
	 	$projectTemplatePermissions = $user->GetProjectTemplatePermissions($projectTemplateId);
	 	$individualPermissions = User::GetIndividualProjectTemplatePermissions($user->id,$projectTemplateId);
	 	$page->assign('user',$user);
	 	$page->assign('projectTemplatePermissions', $projectTemplatePermissions);
 		$page->assign('individualPermissions', $individualPermissions);
 		$page->assign('projectTemplateId',$projectTemplateId);
 		$page->assign('currentUser',$currentUser);

 	} else {
 		InsufficientPermissions();
 	}
 }

 /**
  * updates and displays the permissions for a given project template and user
  */
function UpdateUserBlueprintPermissions() {
	global $page;
	$userId = getParam('userId', NULL);
	$projectTemplateId = getParam('projectTemplateId', NULL);
	//print "userid is ".$userId.", blueprintid is ".$projectTemplateId;
 	$currentUser = $_SESSION[USER];	
	if ((($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) &&
 		($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]['ChangeUserPermissions']==ALLOW)) ||
 		($_SESSION[USER]->superadmin==ALLOW)) { 	
		$user = User::RetrieveUser($userId);	
		$permissionsArray = array();
		$permissionsArray['editdocumenttemplate'] = getParam('editdocumenttemplate', NULL);
		$permissionsArray['startproject'] = getParam('startproject', NULL);
		$permissionsArray['endproject'] = getParam('endproject', NULL);
		$permissionsArray['archiveproject'] = getParam('archiveproject', NULL);
		$permissionsArray['edittemplate'] = getParam('edittemplate', NULL);
		$permissionsArray['viewtemplate'] = getParam('viewtemplate', NULL);
		$permissionsArray['changeuserpermissions'] = getParam('changeuserpermissions', NULL); 
		$permissionsArray['editplugin'] = getParam('editplugin', NULL);
		$submitted = getParam('submitted',NULL);	
		if ($submitted=='yes') {
			User::UpdateIndividualProjectTemplatePermissions($user->id,$projectTemplateId,$permissionsArray);
		}
		$individualPermissions = $user->GetIndividualProjectTemplatePermissions($user->id, $projectTemplateId);
		$projectTemplatePermissions = $user->GetProjectTemplatePermissions($projectTemplateId);
		$page->Template = 'viewUserProjectTemplatePermissions.php.tpl';
		$page->assign('user', $user);
		$page->assign('projectTemplatePermissions', $projectTemplatePermissions);
		$page->assign('individualPermissions', $individualPermissions);
		$page->assign('projectTemplateId', $projectTemplateId);
		$page->assign('method', 'editpermissions');
		$page->assign('currentUser', $currentUser);
 	} else {
 		InsufficientPermissions();		
 	}
}

/**
 * display the permissions for a given project and user group 
 */
 function ViewUserGroupSimulationPermissions() {
 	global $page;
 	$userGroupId = getParam('userGroupId', NULL);
 	$simulationId = getParam('simulationId', NULL);
 	$currentUser = $_SESSION[USER];
	if ((($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) &&
 		($_SESSION[USER]->projectPermissions[$simulationId]['ChangeUserPermissions']==ALLOW)) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {  	
 		$userGroup = UserGroup::GetUserGroup($userGroupId);
 		$permissions = UserGroup::GetProjectPermissions($userGroup->id,$simulationId);
 		$page->Template = 'viewUserGroupProjectPermissions.php.tpl';
 		$page->assign('userGroup',$userGroup);
 		$page->assign('permissions', $permissions);
 		$page->assign('simulationId',$simulationId);
 		$page->assign('currentUser',$currentUser);
 	} else {
 		InsufficientPermissions();
 	}
 }
 
  /**
  * updates and displays the permissions for a given project and user group
  */
function UpdateUserGroupSimulationPermissions() {
	global $page;
	$userGroupId = getParam('userGroupId', NULL);
	$simulationId = getParam('simulationId', NULL);
	$currentUser = $_SESSION[USER];
	if ((($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) &&
 		($_SESSION[USER]->projectPermissions[$simulationId]['ChangeUserPermissions']==ALLOW)) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {  		
		$userGroup = UserGroup::GetUserGroup($userGroupId);	
		$permissionsArray = array();
		$permissionsArray['usestafftools'] = getParam('usestafftools', NULL);
		$permissionsArray['deleteanyitem'] = getParam('deleteanyitem', NULL);
		$permissionsArray['deleteitem'] = getParam('deleteitem', NULL);
		$permissionsArray['viewitem'] = getParam('viewitem', NULL);
		$permissionsArray['additem'] = getParam('additem', NULL);
		$permissionsArray['editanyitem'] = getParam('editanyitem', NULL);
		$permissionsArray['edititems'] = getParam('edititems', NULL);
		$permissionsArray['stopproject'] = getParam('stopproject', NULL);
		$permissionsArray['changeuserpermissions'] = getParam('changeuserpermissions', NULL); 	
		$permissionsArray['editplugin'] = getParam('editplugin', NULL);
		$submitted = getParam('submitted',NULL);
		if ($submitted=='yes') {
			UserGroup::UpdateProjectPermissions($userGroup->id,$simulationId,$permissionsArray);
		}
		$permissions = $userGroup->GetProjectPermissions($userGroup->id, $simulationId);
		$page->Template = 'viewUserGroupProjectPermissions.php.tpl';
		$page->assign('userGroup', $userGroup);
		$page->assign('permissions', $permissions);
		$page->assign('simulationId', $simulationId);
		$page->assign('method', 'editpermissions');
		$page->assign('currentUser', $currentUser);
 	} else {
 		InsufficientPermissions();
 	}
}

/**
 * display the permissions for a given project template and user group 
 */
 function ViewUserGroupBlueprintPermissions() {
 	global $page;
 	$userGroupId = getParam('userGroupId', NULL);
 	$blueprintId = getParam('blueprintId', NULL);
 	$currentUser = $_SESSION[USER];
	if ((($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) &&
 		($_SESSION[USER]->projectTemplatePermissions[$blueprintId]['ChangeUserPermissions']==ALLOW)) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {  		 	
 		$userGroup = UserGroup::GetUserGroup($userGroupId);
 		$permissions = UserGroup::GetProjectTemplatePermissions($userGroup->id,$blueprintId);
	 	$page->Template = 'viewUserGroupProjectTemplatePermissions.php.tpl';
 		$page->assign('userGroup',$userGroup);
	 	$page->assign('permissions', $permissions);
 		$page->assign('blueprintId',$blueprintId);
 		$page->assign('currentUser',$currentUser);
 	} else {
 		InsufficientPermissions();
 	}
 }
 
  /**
  * updates and displays the permissions for a given project template and user group
  */
function UpdateUserGroupBlueprintPermissions() {
	global $page;
	$userGroupId = getParam('userGroupId', NULL);
	$blueprintId = getParam('blueprintId', NULL);
	$currentUser = $_SESSION[USER];
	if ((($_SESSION[USER]->sitewidePermissions['EditUser']==ALLOW) &&
 		($_SESSION[USER]->projectTemplatePermissions[$blueprintId]['ChangeUserPermissions']==ALLOW)) ||
 		($_SESSION[USER]->superadmin==ALLOW)) {	
		$userGroup = UserGroup::GetUserGroup($userGroupId);	
		$permissionsArray = array();
		$permissionsArray['editdocumenttemplate'] = getParam('editdocumenttemplate', NULL);
		$permissionsArray['startproject'] = getParam('startproject', NULL);
		$permissionsArray['endproject'] = getParam('endproject', NULL);
		$permissionsArray['archiveproject'] = getParam('archiveproject', NULL);
		$permissionsArray['edittemplate'] = getParam('edittemplate', NULL);
		$permissionsArray['viewtemplate'] = getParam('viewtemplate', NULL);
		$permissionsArray['changeuserpermissions'] = getParam('changeuserpermissions', NULL); 
		$permissionsArray['editplugin'] = getParam('editplugin', NULL);	
		$submitted = getParam('submitted', NULL);
		if ($submitted=='yes') {
			UserGroup::UpdateProjectTemplatePermissions($userGroup->id,$blueprintId,$permissionsArray);
		}
		$permissions = $userGroup->GetProjectTemplatePermissions($userGroup->id, $blueprintId);
		$page->Template = 'viewUserGroupProjectTemplatePermissions.php.tpl';
		$page->assign('userGroup', $userGroup);
		$page->assign('permissions', $permissions);
		$page->assign('blueprintId', $blueprintId);
		$page->assign('currentUser', $currentUser);
		$page->assign('method', 'editpermissions');
 	} else {
 		InsufficientPermissions();
 	}
}

 ?>
