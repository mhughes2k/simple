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
 * Implements project administration facilities.
 * 
 * This is effectively a pre-processor, it works out what needs to happen and then
 * generates the appropriate HTML output, or handles the input it has received in a
 * specific manner.
 *
 * This option allows a project template to be modified whilst it is in the system.
 * @package Core
 */
 if (!defined('TLE2')) die ('Invalid Entry Point');
	if ($_SESSION[USER]->IsAuthenticated() and $_SESSION[USER]->isProjectStaff()){
  	$page->assign('user',$_SESSION[USER]);
  	switch (strtolower($command))  {
		case 'addvariable':
		  UpdateVar();
		  break;
    case 'removefromgroup':
			DoRemoveFromGroup();
			break;
		case 'doaddprojecttogroup':
			DoAddProjectToGroup();
			break;
		case 'doarchiveproject':
			doArchiveProject();
			break;
		case 'dodeleteproject':
			doDeleteProject();
			break;
		case 'addprojectgroup':
			AddProjectGroup();
			break;
		case 'deleteprojectgroup':
			DeleteProjectGroup();
			break;
		case 'triggerevent':
			TriggerEvent();
			break;
		case 'seteventstate':
			doUpdateNedState();
			break;	
		case 'updatevar':
			UpdateVar();
			break;
		case 'deletevar':
			DeleteVariable();
			break;
		case 'stopproject':
			StopProject();
			break;
		case 'doenableproject':
			DoEnableProject();
			break;
		case 'dostopproject':
			DoStopProject();
			break;
		case 'editproject':
			editProject();
			break;
		case 'viewproject':
			viewProject();
			break;
		case 'dosaveproject':
			DoSaveProject();
			break;
		case 'listplugins':
			ListProjectPlugins();
			break;
		case 'editprojectpluginpage':
			EditProjectPluginPage();
			break;
		case 'addprojectpluginpage':
			AddProjectPluginPage();
			break;		
		case 'updateprojectplugin':
			UpdateProjectPlugin();
			break;
		case 'clearplugin':
			ClearProjectPlugin();
			break;		
		case 'togglepermission':
			TogglePermission();
			break;
		case 'viewprojectgroup':
			ViewProjectGroup();
			break;
		case 'linkproject':
			LinkProject();
			break;
		case 'linkroles':
			LinkRoles();
			break;
		case 'dolinkroles':
			doLinkRoles();
			break;
		case 'savecharacterdetails':
			doSaveCharacterDetails();
			break;
		case 'adduser2simulation':
			viewProject();
			break;
		case 'addGroup2Simulation':
			viewProject();
			break;
		case 'sendcanned':
		  SendCanned();
		  break;
		case 'sendfile':
		  SendFile();
		  break;
		case 'triggereventforgroup':
		  TriggerEventForGroup();
		  break;
		default:
		case 'listproject':
			listProjects();
			//listProjects();
		}
	}
	else {
		trace('No permissions');
		Redirect('index.php?option=message&cmd=Insufficient Permissions');
	}
	// Project management code follows after.
	function StopProject() {
		
	}
	/*	define(NOTSET,0);
	define(DENY,-1);
	define(ALLOW,1);
	*/
	/**
	 * Toggles a permission for a user.
	 * @todo Implement code to actually do the toggle! 
	 */
	function TogglePermission() {
		global $strings;
		$projectId = GetParam('projectId',-1);
		$type = strtolower(GetParam('type',''));
		$id = GetParam('id',-1);
		$permissionName = GetParam('permission','');
		$user = $_SESSION[USER];

		if ($projectId > -1 and $type !='' and $id > -1 and $permissionName != '') {
			if ($type=='group') {
				DisplayMessage($strings['MSG_GROUP_PERMISSIONS_TOGGLE_ERROR']);
				return;
			}	
			$project = Project::GetProject($projectId);
			$isSuperAdmin = false; 
			$projectAllowsUserToChangePerms =$project->GetProjectPermission('ChangeUserPermissions',$user->id); 
			$ptAllowsUsersToChangePerms =$project->GetTemplatePermission('ChangeUserPermissions',$user->id);
			$canChangePermissions = false;
/*
 * Since we assume a DENY, we only need rules that check for 
 * ALLOW conditions!
 */
			if ($isSuperAdmin==ALLOW || $projectAllowsUserToChangePerms==ALLOW) {
				$canChangePermissions = true;
			}
			else {
				if ($projectAllowsUserToChangePerms > DENY and $ptAllowsUsersToChangePerms == ALLOW) {
					$canChangePermissions = true;
				}
				if ($projectAllowsUserToChangePerms > DENY and $ptAllowsUsersToChangePerms < ALLOW ) {
					$canChangePermissions = false;
				}
			}
			if (!$canChangePermissions) {
				DisplayMessage($strings['MSG_INSUFFICIENT_PRIVELEDGES']);	
			}
			else {
$currentPermissions = User::GetIndividualProjectPermissions($id, $projectId);
$currentPermissionValue = $currentPermissions[$permissionName];
$newPermissionValue= DENY;
switch ($currentPermissionValue) {
	case ALLOW:
		$newPermissionValue=DENY;
		break;
	case NOTSET:
		$newPermissionValue=ALLOW;
		break;
	case DENY:
		$newPermissionValue=NOTSET;
		break;
	default:
//		$newPermissionValue=DENY;
die("$currentPermissionValue: New value:$newPermissionValue");
		break;
}
//die("$currentPermissionValue: New value:$newPermissionValue");
				User::UpdateIndividualProjectPermission($id, $projectId, $permissionName,$newPermissionValue);
//UpdateIndividualProjectPermission($userId, $projectId, $permission,$value)
				//DisplayMessage('I\'d be happy to change their permissions, if i was implemented!');
Redirect("index.php?option=projectAdmin&cmd=viewProject&projectId=$projectId#users");
			}
			//Should probably redirect back to our referrer.
			
		}
		else {
DisplayMessage($strings['MSG_GROUP_PERMISSIONS_TOGGLE_MISSING_PARAMS_ERROR']);	
//			Redirect('index.php?option=message&cmd=No Project, Permission, User or type supplied.');
			return;	
		}
	}
	/**
	 * Hides a project from Users.
	 */
	function DoStopProject() {
		global $database;
		$projectId = GetParam('id',-1);
		if (!(($currentUser->projectPermissions[$projectId]['StopProject']==ALLOW) ||
 			($_SESSION[USER]->superadmin==ALLOW))) InsufficientPermissions();	
		$redir = GetParam('redir','index.php');
		if ($projectId >=0){
			$project = Project::GetProject($projectId);
			$project->SetProjectVisibility(PROJECT_VISIBILITY_HIDDEN);
			Redirect($redir);
		}
		else {
			Redirect('index.php?message=Unable to make project inactive.');
		}
		
	}
		function DoEnableProject() {
		global $database;
		$projectId = GetParam('id',-1);
		$redir = GetParam('redir','index.php');
		if ($projectId >=0){
			$project = Project::GetProject($projectId);
			$project->SetProjectVisibility(PROJECT_VISIBILITY_VISIBLE);
			Redirect($redir);
		}
		else {
			Redirect('index.php?message=Unable to make project active.');
		}
		
	}
	/**
	 * Displays a list of all of the project templates installed.
	 * 
	 * We are relying on Project::GetAdministrableProjects() to 
	 * add 'ProjectUserPermissions' property to the Project object
	 * to reduce the load on the database.
	 */
	function listProjects() {
		global $page;
		$page->Template = 'listProjects.php.tpl';
		$userid = $_SESSION[USER]->id;
		$page->assign('projects',Project::GetAdministerableProjects($userid));
		
		$pgs = ProjectGroup::GetProjectGroups();
		$page->assign('projectgroups',$pgs);
		
		//Project::GetProjects());//',userprojects up WHERE p.projectuid = up.projectid AND up.userid=$userid;'));
	}
	function ViewProjectGroup(){
		global $page;
		$projectGroupId = getParam('projectGroupId',-1);
		$page->Template = 'viewProjectGroup.php.tpl';
		if ($projectGroupId >=0 ){
			$userId = $_SESSION[USER]->id;
			$pg = ProjectGroup::Load($projectGroupId);
			$page->assign('ProjectGroup',$pg);
			//print $pg->MemberCount();
			$page->assign('memberCount',$pg->MemberCount());
			$allProjects =Project::GetAdministerableProjects($userId );
			//$_SESSION[USER]->GetUsersProjects();
			$projects = array();
			//print_r($pg);
			if (count($pg->Projects)>0) {
				foreach($pg->Projects as $projectId){
					//echo 'Getting Project:$projectId';
					$p = Project::GetProject($projectId);
					if (!is_null($p)){
						$p->ProjectUserPermissions =$p->GetProjectPermissions($userId);
						if (isset($allProjects[$projectId])) {
							//echo 'Project in group so removing from all projects';
							unset($allProjects[$projectId]);
						}
						$projects[] = $p;
					}
				}
			}
			else {
				//echo 'No Projects in group';
			
			}
			
			//prepare the resources for all of the sim bps in the group
			$Blueprints = array();
			$Resources = array();
			$Roles = array();
			$BpId =-1; 
			foreach($projects as $project) {
        if (isset($Blueprints[$project->templateId])) {
         // echo 'already added resources for bp';
        }
        else { 
          $pt = $project->GetProjectTemplate();
          $Blueprints[$project->templateId]=$project->templateId;
          $BpId=$project->templateId;
          $RawResources= $pt->getDocumentTemplates(false); //gets ALL the resources for the bp
          $Resources[]=array('doctemplateuid'=>'','filename'=>'---'.$pt->Name.'---');
          foreach($RawResources as $Resource){
            $Resources[]=array('doctemplateuid'=>$Resource['doctemplateuid'],'filename'=>$Resource['filename']. '('.$pt->Name.')');
          }
          $Resources[]=array('doctemplateuid'=>'','filename'=>'');
          $RawRoles = $pt->GetRoles();
          foreach($RawRoles as $Role){
            $Roles[] = array('projectrole'=>$Role['rolename'],'blueprint'=>$pt->Name);
          }
        }
      }
      if (count($Blueprints)==1) {
       $projectTemplate = ProjectTemplate::getTemplate($BpId);
    	 $events =$projectTemplate->GetEvents();
			 $page->assign('ned_EnableTriggers',true);
			 $page->assign('ned_EnableDocuments',false);
			 $page->assign('events',$events);
      }
      $page->assign('pid',$projectGroupId);
      $page->assign('bpcount',count($Blueprints));
      $page->assign('cannedDocuments',$Resources);
      $page->assign('customDocuments',$Resources);
      $page->assign('projectRoles',$Roles);
			//print_r($allProjects);
			//print_r($projects);
			$page->assign('allprojects',$allProjects);
			$page->assign('projects',$projects);
		}
	}
	/**
	 * Allows an admin user to make changes to a running project.
	 */
	function viewProject() {
		global $page,$_PLUGINS;
		$projectId = getParam('projectId',-1);
		$page->Template = 'viewProject.php.tpl';
		if ($projectId >=0 ){
			$project = Project::GetProject($projectId);
			//echo 'PID:$projectId';
			if (is_null($project)) {
				DisplayError(101);
				return;
			}
			$method = getParam('method',NULL);
			switch ($method) {
				case 'addUser2Simulation':
					$userIds = getParam('userIds',NULL);
					foreach ($userIds as $userId) {
						User::AddProject2User($projectId, $userId);
					}
					break;
				case 'addGroup2Simulation':
					$groupIds = getParam('groupIds',NULL);
					foreach ($groupIds as $groupId) {
						UserGroup::AddProject2UserGroup($projectId, $groupId);
					}
					break;
				default:
			}
			
			$user = $_SESSION[USER];
			$page->assign('user',$user);
			
			$page->assign('project',$project);
			$page->assign('projecttemplate',$project->GetProjectTemplate());
			$currentUser = $_SESSION[USER];
			$page->assign('currentUser',$currentUser);
			$page->assign('tAdmin',	$user->isProjectTemplateStaff($project->GetProjectTemplateId($project->id),true));
			$chars =$project->GetCharacters();
			
			$page->assign('characters',$chars);
			//dumpArray($chars,'Characters');
			//print_r($chars[1]->LinkedProjects);
			//dumpArray($chars);
			$page->assign('characters',$chars);
			$variables = $project->Variables;
			$vb = $project->GetVariabliser();
			foreach($variables as $name=>$value) {
			//  $result =$vb->ProcessValueInstruction($value); 
       // echo "<p>$name=$value=>$result</p>";
      }
			$page->assign('variablelist',$variables);
			dumpArray($project->GetUsersAndGroups(),'USERS AND GROUPS');
			$page->assign('ProjectUserList_UsersAndGroups',$project->GetUsersAndGroups());
			// assign full user list
			$dialogUsers = User::GetUsers(0,'displayname');
 	 		$assignDialogUsers  = array();
 			foreach ($dialogUsers as $d) {
 				$assignDialogUsers[] = User::RetrieveUser($d->id,true); 
 			}
			$page->assign('users',$assignDialogUsers);
			// assign full group list
			$dialogGroups = UserGroup::GetUserGroups(0,'name ASC',FALSE);
			$assignDialogGroups = array();
			foreach ($dialogGroups as $g) {
				$assignDialogGroups[] = UserGroup::GetUserGroup($g->id);
			}
			$page->assign('groups',$assignDialogGroups);
			$page->assign('pid',$project->id);
			$page->assign('ned_EnableTriggers',true);
			$page->assign('ned_EnableDocuments',false);
			$page->assign('ned_EnableStates',true);
			$page->assign('events',$project->GetEvents());
			
			$simulationManagementPageExtensionsResults= $_PLUGINS->trigger('extendSimulationManagementPage',array());
			$simulationManagementPageExtensions='';
			//int_r()
		  foreach($simulationManagementPageExtensionsResults as $result){
      //echo($result);
        $simulationManagementPageExtensions.=$result;
      }
		  $page->assign('simulationManagementPageExtensions',$simulationManagementPageExtensions);
		 
			
			//print_r($project->GetEvents());
		}
		else {
			DisplayError(101);
		}
	}
	/**
	 * Displays the edit project page.
	 */
	function editProject() {
		global $page;
		$projectId = getParam('id',-1);
		$page->Template = 'editProject.php.tpl';
		$method = getParam('method',NULL);
		if ($projectId >=0 ){
			
			if ($method=='addLinkedSimulation2Simulation') {
				$project = Project::GetProject($projectId);
				$targetId = getParam('simulationId',NULL);
				$rolename = getParam('role','');
				$di = DirectoryItem::GetDirectoryItemByProjectIdAndRoleName($project->id,$rolename);
				// add a project to this directory item
				$di->LinkedProjects[$targetId] = Project::GetProject($targetId);
				$di->Save();
			//	print "<pre>"; print_r($di); print "</pre>";
	
		//	print "<pre>";
		//	print_r($_POST);
		//	print "source id is ".$sourceId."<br>target id is ".$targetId;
		//	print "</pre>";
			}			
			
			$page->assign('saveCharacterRedirectLocation','index.php?option=projectadmin&cmd=editproject&id='.$projectId);
			$page->assign('PostSaveVarRedirect','index.php?option=projectadmin&cmd=editproject&id='.$projectId);
			$user = $_SESSION[USER];
			$project = Project::GetProject($projectId);
			if (is_null($project)){
				DisplayError(101);
				return;
			}
			$chars =$project->GetCharacters();
			$variables = $project->Variables;
			
			$page->assign('user',$user);
			$page->assign('project',$project);
			$page->assign('pid',$project->id);
			$page->assign('projecttemplate',$project->GetProjectTemplate());
			$page->assign('characters',$chars);
			$page->assign('editCharacters',true);
			$page->assign('editVariables',true);
			$page->assign('variablelist',$variables);
			
			$page->assign('option_target','projectadmin');
			dumpArray($project->GetUsersAndGroups(),'USERS AND GROUPS');
			$page->assign('ProjectUserList_UsersAndGroups',$project->GetUsersAndGroups());
			$page->assign('linksimulationdialogprojects',Project::GetAdministerableProjects($user->id));
		}
	}
	/**
	 * Puts user supplied variables into a Project object and sends them back to the
	 * database.
	 */
	function DoSaveProject() {
		global $page;
		trace('Saving changes to  Project');
		
		$projectId = GetParam('id',-1);
		if ($projectId <0 ){
			$page->Messages[] = 'No Project Specified';
			//$page->Messages[] = 'Unable to create Project';
			return;
		}
		$project = Project::GetProject($projectId);
		$overrides = array();
		foreach($_POST as $postVar=>$value) {
			//echo $postVar.':'.$value .'<br>';
			if (strtolower(substr($postVar,0,4)) =='var_') {
				$varName = substr($postVar,4);
				$varVal = $value;
				//echo 'Variable Setting $varName:$varVal';
				$overrides[$varName] = $varVal;
			}
		}
		$newPName = GetParam('project_name',$project->Name);
		if (strtolower($newPName) != strtolower($project->Name)) {
			//echo 'changing P name';
			$project->Name = $newPName;
		}
		if (!is_null($overrides)) {
			foreach($overrides as $overrideVariable=>$value) {
				$project->Variables[$overrideVariable] = $value;
			}
		}
		$project->saveProject();
		//print_r($project);
		Redirect('index.php?option=projectadmin&cmd=viewproject&projectId='.$projectId);
	}
	/**
	* List the plugins that have been specifically defined for a given project
	*/
	function ListProjectPlugins() {
		global $page;
		$projectId = getParam('projectId', -1);
		if (($_SESSION[USER]->projectPermissions[$projectId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$page->Template = 'listProjectPlugins.php.tpl';
		$plugins = Plugin::GetProjectPlugins($projectId);
		trace('<pre>'.print_r($plugins, true).'</pre>');
		$page->assign('plugins', $plugins);
		$page->assign('projectId', $projectId);
		$project= Project::GetProject($projectId);
		$page->assign('project', $project);		
	}
	/**
	* Removes the record of this plugin for this project
	*/
	function ClearProjectPlugin() {
		global $page;
		$pluginId = getParam('pluginId', -1);
		$projectId = getParam('projectId', -1);
		if (($_SESSION[USER]->projectPermissions[$projectId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		Plugin::ClearProjectPlugin($pluginId, $projectId);
		$page->Template = 'listProjectPlugins.php.tpl';
		$plugins = Plugin::GetProjectPlugins($projectId);
		$page->assign('plugins', $plugins);
		$page->assign('projectId', $projectId);
	}
	/**
	* Display form to allow user to create a new plugin record for this project
	*/
	function AddProjectPluginPage() {
		global $page;	
		$projectId = getParam('projectId', -1);
		$pluginId = getParam('pluginId', -1);
		if (($_SESSION[USER]->projectPermissions[$projectId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$plugin = Plugin::GetPlugin($pluginId);
		$page->Template = 'viewProjectPlugin.php.tpl';
		$page->assign('plugin', $plugin);
		$page->assign('projectId', $projectId);
		$page->assign('method', 'add');
	}
	/**
	* Adds or edits a plugin record for this project
	*/
	function UpdateProjectPlugin() {
		global $page;
		$method = getParam('method', -1);
		$projectId = getParam('projectId', -1);
		$pluginId = getParam('pluginId', -1);
		$enabled = getParam('enabled', -1);
		if (($_SESSION[USER]->projectPermissions[$projectId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		if ($method == 'add') {
			Plugin::AddProjectPlugin($projectId, $pluginId, $enabled);
		} else {
			Plugin::EditProjectPlugin($projectId, $pluginId, $enabled);
		}
		$plugin = Plugin::GetProjectPlugin($pluginId, $projectId);
		$page->Template = 'viewProjectPlugin.php.tpl';
		$page->assign('plugin', $plugin);
		$page->assign('projectId', $projectId);
		$page->assign('method', 'edit');
	}
	/**
	* View a plugin that has been specifically defined for a given project
	*/
	function EditProjectPluginPage() {
		global $page;
		$pluginId = getParam('pluginId', -1);
		$projectId = getParam('projectId', -1);
		if (($_SESSION[USER]->projectPermissions[$projectId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$page->Template = 'viewProjectPlugin.php.tpl';
		$plugin = Plugin::GetProjectPlugin($pluginId, $projectId);
		//trace('plugin is <pre>'.print_r($plugin, true).'</pre>');
		$page->assign('plugin', $plugin);
		$page->assign('method', 'edit');
		$page->assign('projectId', $projectId);		
	}
	
	/**
	 * Displays a list of ProjectGroups
	 * @todo Implement ProjectGroup functionality.
	 */
	 function ViewProjectGroups() {
	 	global $page;
	 	
	 	$groups = ProjectGroup::GetProjectGroups();
	 	/*Do any pre-processing on the Groups*/
	 	
	 	/* Setup page output */
	 	$page->assign('projectgroups',$groups);
	 }
	/**
	 * Creates a new Project group.
	 */
	function AddProjectGroup() {
		$projectGroupName = GetParam('newgroupname','');
		if ($projectGroupName == '') {
			DisplayMessage('You must provide a Name for the group!');
			return;
		}
		$group = new ProjectGroup($projectGroupName);
		$group->Save();
		ReDirect('index.php?option=projectAdmin');
	}
	/**
	 * Removes a ProjectGroup.
	 */
	function DeleteProjectGroup() {
		$projectGroupId = GetParam('projectGroupId',-1);
		if ($projectGroupId <0) {
			DisplayMessage('Please select a valid simulation group.');
			return;
		}
		$pg = ProjectGroup::Load($projectGroupId);
		if(is_null($pg)){
			DisplayMessage('Unable to locate that simulation group!');
			return;
		}
		$pg->Delete();
		ReDirect('index.php?option=projectAdmin');
	}
	 /**
	  * Displays form to create/edit project group.
	  * @todo IMplement edit Project |Group
	  */
	 function EditProjectGroup(){
	 	
	 }
	 /**
	  * Saves changes to a ProjectGRoup
	  */
	 function DoSaveProjectGroup() {
	 	
	 }
	 /**
	  * Adds a project to a ProjectGroup.
	  */
	 function DoAddProjectToGroup() {
	 
	 	$projectIds = GetParam('ids',-1);
		$projectGroupId = GetParam('projectgroupid',-1);
		foreach ($projectIds as $projectId) {
			if ($projectId <0 | $projectGroupId <0){
				echo "PID:$projectID GID:$projectGroupId";
				return;
			}
			$pg = ProjectGroup::Load($projectGroupId);
			$project = Project::GetProject($projectId);
			if (is_null($project)) {
				DisplayError(101);
				return;
			}
			if (is_null($pg)) {
				DisplayMessage('Project group does not exists');
				return;
			}
			if (!$pg->AddProjectToGroup($projectId)) {
				echo 'Error:<br/>';
				die($pg->GetError());
			}
			$pg->Save();	
			//die();
			//print_r($pg);
		}
		Redirect('index.php?option=projectadmin&cmd=viewprojectgroup&projectGroupId='.$projectGroupId);							
	 }
	 
	 /**
	 * @todo This needs permission check added
	 */
	function DoRemoveFromGroup() {
		$projectId = GetParam('id',-1);
		$groupId = GetParam('gid',-1);
		$group = ProjectGroup::Load($groupId);
		//print_r($group->Projects);
		$group->RemoveProjectFromGroup($projectId);
		//print_r($group->Projects);
		$group->Save();
		Redirect('index.php?option=projectadmin&cmd=viewprojectgroup&projectGroupId='.$groupId);
	}
	
	 /**
	  * Removes a Project from a ProjectGroup. 
	  */
	 function DoRemoveProjectFromGroup() {
	 	
	 }
	/**
	 * Removes a variable from a Project 
	 * 
	 * Redirects back to the Project edit page. 
	 */
	function DeleteVariable() {
		global $config,$database;
		$projectid = GetParam('pid',-1);
		$redir =$config['home'].'index.php?option=projectAdmin&cmd=editproject&id='.$projectid;
		
		$varToDelete = GetParam('varname','');
		
		$project = Project::GetProject($projectid);
		if (!is_null($project)){
//echo 'Unsettting '.$varToDelete;
			unset($project->Variables[$varToDelete]);
//print_r($project->Variables);
			$project->saveProject();
		}
		if ($projectid == -1) {
			//go back to the project list page.
			$redir =$config['home'].'index.php?option=projectAdmin';
		}
//		echo $redir;
		Redirect($redir);
	}
	/**
	 * Changes the value of a Project variable. 
	 */
	function UpdateVar() {
		global $config,$database;
		//print_r($_POST);
		$projectid = GetParam('pid',-1);
		$varToUpdate = GetParam('update_var_name','');
		$newVarValue = GetParam('update_var_value','');
		$project = Project::GetProject($projectid);
		if ($varToUpdate !='' and !is_null($project)) {
		//	echo 'updating variable:'.$varToUpdate .':'.$newVarValue;
			$project->Variables[$varToUpdate] = $newVarValue;
			$project->saveProject();
		}
		$redir = GetParam('redir','index.php');
		//echo $redir;
		ReDirect($redir);
	}
	/**
	 * Updates the 'info' associated with a NED element.
	 */
	function doUpdateNedState() {
		global $database, $config;
		$message = '';
		$pid = GetParam('pid',-1);
		$redir = GetParam('redir','index.php?option=projectAdmin&cmd=viewProject&projectId='.$pid);
		$nedEventId= GetParam('ned_eventid','');
		$note = GetParam('state','');
		$color = GetParam('color','ffffff');
		if ($pid == -1) {
			$message .= '<p>Project not found.</p>';	
		}
		if ($nedEventId == '') {
			$message .= '<p>Event not found.</p>';
		}
		if ($message != '') {
			DisplayMessage($message);
			return;
		}
		else 
		{
			$updateResult=UpdateNedState($pid,$nedEventId,$note,$color);
			if ($updateResult===true) {
				Redirect($config['home'].$redir);
			}
			else {
				DisplayMessage($updateResult);				
			}
		}
	}
	/**
	 * Updates the note and color for a NED.
	 * 
	 * @param integer $ProjectId the Project to update.
	 * @param string $EventId The event to update
	 * @param string $Note the note attached
	 * @param string $Color A valid hex color
	 * @return mixed either a true or an error message.
	 */
	function UpdateNedState($ProjectId,$EventId,$Note,$Color) {
		
		global $database;
		//do the actual update!
		$project = Project::GetProject($ProjectId);
		if (is_null($project)) {
			return 'Invalid project selected.';		
		} 
		$checkExisting = sprintf("SELECT eventid FROM projectprogress WHERE eventid ='%s' AND projectid=%s",
							$EventId,$ProjectId);
		//echo $checkExisting;
		$count = 0;
		$result = $database->query($checkExisting);
		//print_r($result);
		$count = count($result);
		
		$sql = '';
		if ($count >0) {
			//do an update
			$sql = sprintf(
					'UPDATE projectprogress ' .
					'SET ' .
					'state =\'%s\', ' .
					'color = \'%s\' ' .
					'WHERE ' .
					'eventid = \'%s\' AND ' .
					'projectid =%s',
					$Note,
					$Color,
					$EventId,
					$ProjectId
				);
				//print $sql."<br/>";
			
		} else {
			//do an insert!
			$sql =sprintf(
					'INSERT INTO projectprogress ' .
					'(eventid,projectid,state,color) ' .
					'VALUES ' .
					'(\'%s\',%s,\'%s\',\'%s\')',
					$EventId,
					$ProjectId,
					$Note,
					$Color
				);
		} 
		trace('nedupdate:'.$sql);
		if ($sql != ''){
			$database->execute($sql);
		} else {
			return 'Unable to update note.';
		}	
		return true;
	}
	/**
	 * Triggers a NED event for all of the simulation instances in a simulation (project) group.
	 */
	function TriggerEventForGroup() {
    global $page;
		$projectGroupId = getParam('projectGroupId',-1);
		$nedEventId = getParam('id','');
		
		//$page->Template = 'viewProjectGroup.php.tpl';
		if ($projectGroupId >=0 ){
		  $pg = ProjectGroup::Load($projectGroupId);
			if (count($pg->Projects)>0) {
				foreach($pg->Projects as $projectId){
		      echo 'Triggereing for $projectId';
    		  DoTriggerEvent($nedEventId,$projectId);
				}
			}
		}
  }
  
  /**
	 * 'Kicks' off a Critical Event!
	 */
	function TriggerEvent() {
		
		/**
		 * @todo Add security check to ensure that the user has 
		 * permission to trigger events in this project.
		 */
		 $user = $_SESSION[USER];
		if (false) {
			DisplayMessage('You do not have permission to do this!');
			return;
		}
		//from here we have established that the user has permission.
		$nedEventId = GetParam('id',-1);
		$projectId = GetParam('pid',-1);
		DoTriggerEvent($nedEventId,$projectId);
	}
	
	/**
	 * Performs an actual Narrative Event action.
	 *
	 * @param integer $nedEventId the Unique ID of the NED event being triggered
	 * @param integer $projectId the unique ID of the project which will receive the event's resources.
	 */
	function DoTriggerEvent($nedEventId,$projectId) {
		global $database,$config,$metrics;
		if ($nedEventId == -1) {
			DisplayMessage('Invalid Event Id.');
			return;
		}
		if ($projectId == -1) {
			DisplayMessage('Invalid project id.');
		}
		$project = Project::GetProject($projectId);
		if (is_null($project)) {
			DisplayMessage('Unable to find that project!');
			return;
		}	
		/*
		 * 1. pull out the event
		 */
		 $eventName = '';
		 /*
		 * 2. pull out the events that follow
		 * 3. process 'links' 
		 * 4
		 */
		 
		 //2.
		 $childrenSql = sprintf("SELECT * FROM projectsequence WHERE previouseventid='%s' AND projecttemplateid=%s AND itemtype=3",
						$nedEventId, $project->templateId);
			//			print $childrenSql;
		$children = $database->queryAssoc($childrenSql);
		//print_r($children);
		if (count($children)==0) {
			//DisplayMessage('This item doesnt release anything!');
			$r = UpdateNedState($project->id,$nedEventId,'','00FF00');
		} else {
			$vb = $project->GetVariabliser();///new Variabliser($project->Variables);
			foreach($children as $releaseItem) {
				//print_r($releaseItem);
				//echo 'Name (subject for item):'.$releaseItem['name'].'<br>';
				//echo 'Document ID: '.$releaseItem['nexteventid'].'<br>';
				$recipient =$vb->Substitute('{CHAR_PLAYER}');
				//echo 'Recipient is always {CHAR_PLAYER}: '.$recipient. '<br>';
				$sender = $vb->Substitute('{'.$releaseItem['performerrole'].'}');
				$senderObj = DirectoryItem::GetDirectoryItemByProjectIdAndRoleName($projectId,$releaseItem['performerrole']);		
				if (!is_null($senderObj)){
					$sender = $senderObj->name;
				} else {
					$sender='Not Available';
				}
				//This must change as the sender should be the NAME of the performer role from the directory (but not yet implemented in tools)
				//echo 'Sender: '.$releaseItem['performerrole']. '->'.$sender.'<br>';
				//echo 'Delivery folder id:'. $project->GetDeliveryFolder();
				$itemUid = $project->GetProjectTemplate()->GetDocumentTemplateIdFromDocumentId($releaseItem['nexteventid']);	
				//echo 'Item UID:'.$itemUid;
				$SourceItem =  $project->GetProjectTemplate()->getFullDocumentTemplate($itemUid);
				if (!is_null($SourceItem) & strtolower($SourceItem['contenttype'])!='user') {
					$filename =$vb->Substitute($SourceItem['visiblename']);
					$sendTime = date($config['dbdatetimeformat']);
					
					$deliveryItem = new Document();
					$deliveryItem->folderid = $project->GetDeliveryFolder();
					//die('Folderid:'.$deliveryItem->folderid);
					$deliveryItem->filename = $filename;
					$deliveryItem->contenttype= $SourceItem['contenttype'];
					/*
					echo 'Subbing Content:<br>';
					echo $SourceItem['content'];
					echo '<br>***<br>';
					*/
					$rawContent =base64_decode($SourceItem['content']);
					$metrics->recordMetric('Raw Content',safeDb($rawContent));		

					// if html or text file, do variable substitution
					if (($deliveryItem->contenttype=='text/html') || ($deliveryItem->contenttype=='text/plain')) {
						$mergedContent=$vb->Substitute($rawContent);
					} else {
						$mergedContent = $rawContent;
					}
         			$metrics->recordMetric('Merged Content',safeDb($mergedContent));
					$deliveryItem->content = $mergedContent;
					/*
					echo 'Post-Subbing Content:<br>';
					echo $deliveryItem->content;
					echo '<br>***<br>';
					*/
					$deliveryItem->sender = $sender;
					$deliveryItem->recipient = $recipient;
					//echo '<br>';
					//print_r($deliveryItem);
					$deliveryItem->Save();
					$metrics->recordMetric(
						'resourcereleased',
						$project->id,
						$project->templateId,
						$SourceItem['projecttemplateid'],
						$sendTime,
						$recipient,
						$sender,
						$_SESSION[USER]->id,
						$_SESSION[USER]->displayName
					);
					$r = UpdateNedState($project->id,$releaseItem['projecttemplateeventid'],'Released','00FF00');
					if ($r !== true) {	//die ($r);
					} 
								
			  Alert::Notify($project->id,"<a href=\"index.php?option=tl&cmd=select&projectid=".$project->id."\">$filename</a> has been recieved in ".$project->Name,
			  NOTIFY_RECEIVE);
		} else {
					$r = UpdateNedState($project->id,$releaseItem['projecttemplateeventid'],'Source Item not found in system!','FF0000');
					if ($r!== true) {
						$metrics->recordMetric(
						'resourcereleasefailure',
						$project->id,
						$project->templateId,
						$SourceItem['projecttemplateid'],
						date($config['dbdatetimeformat']),
						$recipient,
						$sender,
						$_SESSION[USER]->id,
						$_SESSION[USER]->displayName);
					}
				}
			}
		}
		$metricName = 'taskeventtriggered';
		$metrics->recordMetric(
			$metricName,
			$project->id,
			$project->templateId,
			$nedEventId,
			$eventName,
			date($config['dbdatetimeformat']),
			$_SESSION[USER]->id,
			$_SESSION[USER]->displayName
		);
		//we have to mark the event as 'triggered' or 'green'.
		UpdateNedState($project->id,$nedEventId,'Triggered','00FF00');

		//DisplayMessage('I would be starting Critical Event <strong>'. $nedEventId.'</strong> in the N.E.D.');
		Redirect("index.php?option=office&cmd=view&pid=".$project->id."&showned=true");
		//DisplayMessage("Event/Task triggered, <A href='JavaScript:history.go(-1);'>click 'Back' to return</a>");	
		}
	/**
	 * This is effectively the same as LinkRoles just with no UI!
	 * 
	 * Oh and it commits the actions.
	 */
	function doLinkRoles() {
		global $page,$metrics;
		$page->Template = 'LinkProjects_LinkRoles_results.tpl';
		$metrics->recordMetric('Starting Link Simulations operation');

		$masterSimulationRoleInLinkedSim = GetParam('linkedToMasterRole','');
		$linkedSimulationRoleInMasterSim = GetParam('masterToLinkedRole','');
		
		$SyncRoles = GetParam('syncroles',0);
		$SyncVariables = GetParam('syncvars',0);
		$sourceSimId = GetParam('sourceSimId',-1);
		
		$linkSimId = GetParam('linkSimId',-1);
		if ($sourceSimId == -1 | $linkSimId == -1) {
			DisplayMessage('Source Or link sim ID not provided.');
			return;
		}
		$sourceSim = Project::GetProject($sourceSimId);
		$linkedSim = Project::GetProject($linkSimId);
		
		$metrics->recordMetric('Master Sim',$sourceSim->Name,$sourceSim->id);
		$metrics->recordMetric('Linked Sim:',$linkedSim->Name,$linkedSim->id);
		
		if ($sourceSimId == -1 | $linkSimId == -1) {
			$metrics->recordMetric('Sim not found');
			DisplayMessage('Source Or link sim not found');
			return;
		}
		if ($SyncRoles) {
			SyncSimulationRoles($sourceSim->id,$linkedSim->id);
		}
		
		$mRoles = $sourceSim->GetCharacters();
		$lnRoles = $linkedSim->GetCharacters();

		/**
		print "<pre>";
		print_r($mRoles);
		print_r($lnRoles);
		print "</pre>";
		**/
		
		if (isset($mRoles[$linkedSimulationRoleInMasterSim])) {
			$LinkedRoleInMaster = $mRoles[$linkedSimulationRoleInMasterSim];
			//print "<pre>";
			//print_r($LinkedRoleInMaster);
			//print "</pre>";
			$LinkedRoleInMaster->LinkedProjects[$linkedSim->id]=$linkedSim->id;
			$LinkedRoleInMaster->Save();
		} else {
			if (!$SyncRoles){
				echo 'Role is not defined in the Master Simulation, and Role Syncing is OFF.';
			} else {
				echo 'Role not found in Master Simulation - '.$linkedSimulationRoleInMasterSim."<br/>";
				//print "<pre>";
				//print_r($mRoles);
				//print "</pre>";
			}
		}
    //print_r($LinkedRoleInMaster);
	if (isset($lnRoles[$masterSimulationRoleInLinkedSim])) {
		$LinkedRoleInLinked = $lnRoles[$masterSimulationRoleInLinkedSim];
		$LinkedRoleInLinked->LinkedProjects[$sourceSim->id]=$sourceSim->id;
		$LinkedRoleInLinked->Save();
    } else {
      if (!$SyncRoles){
			echo 'Role is not defined in the Linked Simulation, and Role Syncing is OFF.';
      } else {
			echo 'Role not found in Linked Simulation - '.$masterSimulationRoleInLinkedSim."<br/>";
			print "<pre>";
			print_r($lnRoles);
			print "</pre>";
      }
    }
    
    $linkedVars=array();
    if ($SyncVariables){
		$linkedVars = SyncSimulationVariables($sourceSim->id,$linkedSim->id);
    }
    
	$page->assign('linkedSim',$linkedSim);
	$page->assign('sourceSim',$sourceSim);
	$page->assign('linkedVars',$linkedVars);
}
	
function SyncSimulationVariables($MasterSimulationId,$LinkedSimulationId){
    global $metrics;
    $MasterSimulation = Project::GetProject($MasterSimulationId);
    $LinkedSimulation = Project::GetProject($LinkedSimulationId);
    
    $variableList = array();
    foreach($MasterSimulation->Variables as $VariableName=>$Value) {
      if (substr($VariableName,0,4)!="CHAR"){
        if (!isset($variableList[$VariableName])) {
          $variableList[$VariableName]=$Value;
        }
      }
    }
    
    foreach($LinkedSimulation->Variables as $VariableName=>$Value) {
      if (substr($VariableName,0,4)!="CHAR"){
        if (!isset($variableList[$VariableName])) {
          $variableList[$VariableName]=$Value;
        }
      }
    }
    foreach($variableList as $VariableName=>$Value) {
      $MasterSimulation->Variables[$VariableName]=$Value;
      $LinkedSimulation->Variables[$VariableName]=$Value;
    }
    $MasterSimulation->Save();
    $LinkedSimulation->Save();
    /*
    echo '<pre>';
    print_r($variableList);
    echo '</pre>';
    */
    return $variableList;
  
  }
  
  /**
   * Syncs 2 simulations' roles.
   *
   * Takes 2 simulation IDs, and then ensures that by the end of the process both sims have exactly the same roles in them.
   */
	function SyncSimulationRoles($MasterSimulationId,$LinkedSimulationId){
    global $metrics;
    $MasterSimulation = Project::GetProject($MasterSimulationId);
    $LinkedSimulation = Project::GetProject($LinkedSimulationId);
    //print 'Creating Master Role List from Master simulation<br>';
    foreach($MasterSimulation->GetCharacters() as $RoleName=>$Character) {
      if (!isset($roleList[$RoleName])) { //This will always happen since we have no defined characters!
        
        if ($Character->name !=''){
          //print ("adding $RoleName to master list from MasterSim" );
          $roleList[$RoleName] = $Character;
        }
        else {
          //print ("skipping $RoleName to master list from MasterSim as has no details" );
        }
      }
      else {
      //fsprint ("skipping $RoleName to master list from MasterSim as not set" );
      }
    }
    foreach($LinkedSimulation->GetCharacters() as $RoleName=>$Character) {
      if (!isset($roleList[$RoleName])) {
        $roleList[$RoleName] = $Character; //this *might* not happen as the Role might have been defined in the master sim.
      }
    }
    $MasterSimCharacters = $MasterSimulation->GetCharacters();
    $LinkedSimCharacters = $LinkedSimulation->GetCharacters();
   // print '<pre>';
    //print_r($roleList);
   // print 'Starting Sync';
    foreach($roleList as $RoleName=>$def){
     // print "Syncing $RoleName <br>";
      $Character = null;
      if (isset($MasterSimCharacters[$RoleName])) {
      //  print "Changing $RoleName in Master Simulation<br>";
        $Character = $MasterSimCharacters[$RoleName];   //$ROleName should be escaped.
      }
      else {
        //we have to create the character
       //  print "Creating $RoleName in Master Simulation<br>";
        $Character = new DirectoryItem();       
      }
      if (!is_null($Character)){
        $Character->name = $def->name;
        $Character->address = $def->address;
        $Character->location = $def->location;
        $Character->directoryvisible = $def->directoryvisible;
        $Character->Properties = $def->Properties;
        $Character->projectid = $MasterSimulation->id;
        $Character->projectrole = $RoleName; //this should be escaped!
      //  print_r($Character);
        $Character->Save();
      }
      else {
        $metrics->recordMetric('Unable to master sync character');
      }
    //  print ('<hr>');
      $sCharacter = null;
      if (isset($LinkedSimCharacters[$RoleName])) {
        $sCharacter = $LinkedSimCharacters[$RoleName];	//Rolename should be escaped
      }
      else {
        //we have to create the character
        $sCharacter = new DirectoryItem();       
      }
      if (!is_null($sCharacter)){
        $sCharacter->name = $def->name;
        $sCharacter->address = $def->address;
        $sCharacter->location = $def->location;
        $sCharacter->directoryvisible = $def->directoryvisible;
        $sCharacter->Properties = $def->Properties;
        $sCharacter->projectid = $LinkedSimulation->id;        
        $sCharacter->projectrole = $RoleName; //this should be escaped
      //  print_r($sCharacter);
        $sCharacter->Save();
      }
      else {
        $metrics->recordMetric('Unable to sync linked sim character');
      }
    }
  }
	/**
	 * Displays the list of roles from 2 simulations to allow for linking.
	 */
	function LinkRoles() {
		global $page;
		$changes = array();
		$page->Template = 'LinkProjects_LinkRoles.tpl';
		$sourceSimId = GetParam('sourceSimId',-1);
		
		$linkSimId = GetParam('linkSimId',-1);
		if ($sourceSimId == -1 | $linkSimId == -1)
		{
			echo 'Source Or link sim ID not provided.';
			return;
		}
		$sourceSim = Project::GetProject($sourceSimId);
		$linkedSim = Project::GetProject($linkSimId);
		if ($sourceSimId == -1 | $linkSimId == -1)
		{
			echo 'Source Or link sim not found';
			return;
		}
		
		$page->assign('linkSim',$linkedSim);
		$page->assign('sourceSim',$sourceSim);
		$sourceSimCharacters = $sourceSim->GetCharacters();
		$linkedSimCharacters= $linkedSim->GetCharacters();
		/*echo '<pre>';
		//print_r($sourceSimCharacters);
		$char =$sourceSimCharacters['Senior Partner'];
		print_r($char);
		echo "Role:".$char->GetRole();
		echo '</pre>';
		*/
		$page->assign('sourceSimCharacters',$sourceSimCharacters);
		$page->assign('linkedSimCharacters',$linkedSimCharacters);
		$page->assign('linkSimPlayer',$linkedSim->Variables['CHAR_PLAYER']);
	}
	/**
	 * @todo This needs permission check added
	 */
	function LinkProject() {
		global $page,$database;
		//include('include/Container.class.php');//move this to index.php eventually.
		$page->Template = 'LinkProjects.tpl';
		$containerId = GetParam('containerid',-1);
		$sourceSimId = GetParam('pid',-1);
		$projectTemplate= null;
		$containers = null;
		$sourceSims = array();
		
		if ($sourceSimId>-1){
			//echo 'Source Sim'.$sourceSimId;
			$sourceSim = Project::GetProject($sourceSimId);	
			$projectTemplate = $sourceSim->GetProjectTemplate();
			//print_r($projectTemplate);
			$scenarioName =$projectTemplate->ContainerName;
			//echo 'Container:'.$scenarioName;
			$bps = Container::GetBlueprints($projectTemplate->ContainerId);
			//print_r($bps);
			$projs = array();
			foreach($bps as $bp){
				
				$ps = ProjectTemplate::GetProjects($bp->id);
				foreach($ps as $p){
					$projs[]=$p;
				}
			}
			$linkSims =$projs; 		
		}
		else {
			$containers = Container::GetContainers();
			if ($containerId == -1) {
				$containerId = $containers[0]['containerid'];
			}
			$sourceBps=Container::GetBlueprints($containerId);
			//print_r($sourceBps);
			
			// instead of getting list, should find linksim created at the same time
			// so adapt method to take 2 ids, which are generated at instantiation of project
			
			foreach($sourceBps as $bp) {
				$sims = ProjectTemplate::GetProjects($bp->id);
				$sourceSims = array_merge($sourceSims,$sims);
			}
			$linkSims = $sourceSims;
		}
		//echo $projectTemplate->id;
		//print_r($containers);
		
		$page->assign('scenarios',$containers);
		$page->assign('scenarioid',$containerId);
		$page->assign('sourceSimId',$sourceSimId);
		$page->assign('sourceSims',$sourceSims);
		$page->assign('scenarioName',$scenarioName);
		$page->assign('sourceSimName',$sourceSim->Name);	
		$page->assign('linkedSims',$linkSims); 
	
	}
	/**
	 * Updates the details for a single character 
	 * @todo This needs permission check added
	 */
	function doSaveCharacterDetails() {
		$directoryItemId = GetParam('directoryItemId',-1);
		//print_r($_POST);
		//echo $directoryItemId;
		$redir = GetParam('redir','index.php');
		if ($directoryItemId <0) {
			DisplayMessage('Invalid Character ID.');
			return;
		}
		
		$directoryItem = DirectoryItem::GetDirectoryItem($directoryItemId);
		if (is_null($directoryItem)) {
			DisplayMessage('Character not found.');
			return;
		}
		
		$newName = GetParam('name','');
		$newAddress = GetParam('address','');
		$newLocation =GetParam('location','');
		$dirVisible = GetParam('dirvisible',1);
		$directoryItem->name = $newName;
		$directoryItem->address = $newAddress;
		$directoryItem->location = $newLocation;
		$directoryItem->directoryvisible = $dirVisible;
		
		$directoryItem->Save();
		ReDirect($redir);
	}
	/**
	 * Archives the project
	 * 
	 * The archive operation merely hides the project from the student's list of active projects.
	 * @todo This needs permission check added
	 */
	function doArchiveProject(){
		$projectId = GetParam('id',-1);
		$redir = GetParam('redir','index.php?option=projectadmin');
		if ($projectId<0) {
			return;
		}
		Project::ArchiveProject($projectId);
		ReDirect($redir);
	}
	/**
	 * Mark a project as OK for garbage collection.
	 * @todo This needs permission check added
	 */
	function doDeleteProject(){
		$projectId = GetParam('id',-1);
		$redir = GetParam('redir','index.php?option=projectadmin');
		if ($projectId<0) {
			return;
		}
		Project::DeleteProject($projectId);
		ReDirect($redir);	
	}
  function SendFile() {
    die('Sending custom resource to multiple projects in a group.');
  }
  /**
   * Send a pre-prepared resource to some project without any edits.
   */
  function SendCanned() {
       global $page,$database;
		$projectGroupId = getParam('projectGroupId',-1);
		$nedEventId = getParam('id','');
		
		//$page->Template = 'viewProjectGroup.php.tpl';
		if ($projectGroupId >=0 ){
		  $pg = ProjectGroup::Load($projectGroupId);
			if (count($pg->Projects)>0) {
				foreach($pg->Projects as $projectId){
		      echo 'Triggereing for $projectId';
		      $project = Project::GetProject($projectId);
    		  if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
		        ($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
		      $docUid = getParam("canneddoc",-1);
		      if ($docUid == -1) {
			       DisplayMessage('Unable to send canned item. Could not locate document.');
		      }else {
  			    $user = $_SESSION[USER];
      			$folderId = $project->GetDeliveryFolder();
      			$sql = sprintf("SELECT * FROM documenttemplates WHERE doctemplateuid = '%s'",$docUid);
      			$results = $database->queryAssoc($sql);
      			if ($results >0) {
      				$result = $results[0];
      				$contents = base64_decode($result['content']);
      				$vb = $project->GetVariabliser();//new Variabliser($project->Variables);
      				//print ('Resource content type:'.$result['contenttype']);
      				if (
                $result['contenttype']=='html'
                |
                $result['contenttype']=='application/rtf'
                | 
                $result['contenttype']=='text/html'
		|
		$result['contenttype']=='application/ms-infopath.xml'
		|
		$result['contenttype']=='application/xml'
              ) {
      				  //print('Subving contents');
              	$contents = $vb->Substitute($contents);
      				}
      				$sender = GetParam('sender','');
      				trace('Sender is:'.$sender); 
      				$sender= $vb->Substitute($sender);
      				/*
      				 * Not sure if this next bit should be "hard coded" in!?! 
      				 */
      				$recipient= $vb->Substitute('{CHAR_PLAYER}');
      				
      				//print($recipient);
              //die('Not delivering at present'); 
      				$doc= new Document() ;
      				$doc->folderid = $folderId;
      				$doc->filename = $vb->Substitute($result['visiblename']);
      				$doc->icon =' ';
      				$doc->content = $contents;
      				$doc->contenttype= $result['contenttype'];
      				$doc->sender = $sender;
      				$doc->recipient = $vb->Substitute('{CHAR_PLAYER}');
      				$doc->Save();
      			}
      		}
				}
			}
		}
		else {
      die('Project Group not set');
    }
  }
?>
