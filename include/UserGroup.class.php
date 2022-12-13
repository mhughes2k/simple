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
 * @package SIMPLE
 * @subpackage Objects
 */
 
 /**
  * Manages groups of users
  */
class UserGroup {
	public $id = null;
	public $name = "";	
	public $active = 0;	
	public $members = array();
	public $siteWidePermissions = array(); 
	/**
	 * @param array An array of User Ids.
	 */
    function __construct($id=null, $GroupName="", $members=null, $permissions=null, $active=0) {
    	$this->id = $id;
    	$this->name = $GroupName;
    	$this->active = $active;
    	if (!is_null($members) and is_array($members)){
	    	foreach ($members as $userId) {
	    		$user = User::RetrieveUser($userId,TRUE);
	    		$members[] =$user;
	    	}
    	}
    	$this->siteWidePermissions = array(
				'AddUser'=>0,
				'EditUser'=>0,
				'MakeLevelZeroUser'=>0,
				'AddPlugin'=>0,
				'InstallTemplate'=>0,
				'EditTemplate'=>0,
				'RemoveTemplate'=>0
			);
			//print_r($this->siteWidePermissions);
    	if (!is_null($permissions)){
    		foreach($this->siteWidePermissions as $swPerm=>$value) {
    			if (isset($permissions[$swPerm])){
            $this->siteWidePermissions[$swPerm] = $permissions[$swPerm];
          }
          else {
            $this->siteWidePermissions[$swPerm] = NOTSET;
          }
    		} 
    		//repeat for ProjectTemplate Permissions.
    		//repeat for Project Permissions.
    	}
    	else {

    	}
    }
    
    /**
     * get a list of all members of the group
     */
	function GetMembers() {
 		global $database;
	  	$query = sprintf("SELECT u.userid, u.displayname ".
	  					"FROM user2usergroup g, users u ".
	  					"WHERE g.groupid=%s ".
	  					"AND g.userid=u.userid ".
	  					"AND g.deleted=0 ".
	  					"AND u.deleted=0",
	  					$this->id);
	  	$results = $database->queryAssoc($query);
	  	$users = array();
	  	foreach ($results as $result) {
	  		if ($result['displayname']=='') $result['displayname'] = 'User #'.$result['userid'];
	  		$users[] = new User($result['userid'],$result['displayname']); 
	  	}
	  	$this->members = $users;
	  	return $this->members;
	}
    
   /**
    *  get a list of all members of the groups ids
    */
	function GetMemberIds() {
 		global $database;
	  	$query = sprintf("SELECT u.userid, u.displayname ".
	  					"FROM user2usergroup g, users u ".
	  					"WHERE g.groupid=%s ".
	  					"AND g.userid=u.userid ".
	  					"AND g.deleted=0 ".
	  					"AND u.deleted=0",
	  					$this->id);
	  	$results = $database->queryAssoc($query);
	  	$users = array();
	  	foreach ($results as $result) {
	  		$users[] = $result['userid']; 
	  	}
	  	$this->members = $users;
	  	return $this->members;
	}    
    
    /**
     * remove a member from the group
     */
    function RemoveMember($userId) {
    	global $database;
    	$query = sprintf("UPDATE user2usergroup SET deleted=1 WHERE groupid=%s AND userid=%s",
    					$this->id,
    					$userId);
    	$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	/*	$projects = $this->GetProjects();    	
		foreach ($projects as $p) {
			$hasPermission = FALSE;
			// check that user does not have individual project permissions
			
			$iPermissions = User::GetIndividualProjectPermissions($userId,$p->id);
			foreach ($iPermissions as $ip) {
				if ($ip!=NULL) $hasPermission = TRUE;
			}
			if (!$hasPermission) {
				$query = sprintf("UPDATE calendar SET deleted=1 WHERE userid=%s AND projectid=%s",
								$userId,
								$p->id);
			//	$result = $database->execute($query);
			}
		}*/
   }
    
    /**
     * Sets a Permission for the Whole group
     * 
     * @param string $name Name of the Permission to set/unset.
     * @param string $val Value to set Permission to. 
     */
    function SetPermission($name,$val) {
		global $database;
		foreach($this->members as $user) {
			$user->SetPermission($name,$val);
		}
	}
	
	/**
	 * get the group sitewide permissions from the database
	 */
	public static function GetUserGroupSitewidePermissions($userGroupId) {
		global $database;
		$sitewidePermissions = array();
		$query = sprintf("SELECT * FROM sitewidepermissions WHERE userid=%s AND usertype='group' AND deleted=0", $userGroupId);
		$result = $database->queryAssoc($query);
		if (sizeof($result)>0) {
			$sitewidePermissions['AddUser'] = $result[0]['adduser'];
			$sitewidePermissions['EditUser'] = $result[0]['edituser'];
			$sitewidePermissions['MakeLevelZeroUser'] = $result[0]['makelevelzerouser'];
			$sitewidePermissions['AddPlugin'] = $result[0]['addplugin'];		
			$sitewidePermissions['InstallTemplate'] = $result[0]['installtemplate'];
			$sitewidePermissions['EditTemplate'] = $result[0]['edittemplate'];
			$sitewidePermissions['RemoveTemplate'] = $result[0]['removetemplate'];			
		} else {
			// default to NOTSET
			$sitewidePermissions['AddUser'] = NOTSET;
			$sitewidePermissions['EditUser'] = NOTSET;
			$sitewidePermissions['MakeLevelZeroUser'] = NOTSET;
			$sitewidePermissions['AddPlugin'] = NOTSET;			
			$sitewidePermissions['InstallTemplate'] = NOTSET;
			$sitewidePermissions['EditTemplate'] = NOTSET;
			$sitewidePermissions['RemoveTemplate'] = NOTSET;
		}	 	
		return $sitewidePermissions;		
	}

	/**
	 * update te user group sitewide permissions in the db
	 */
	public static function UpdateUserGroupSitewidePermissions($userGroupId,$addUser,$editUser,$makeLevelZeroUser,
	$addPlugin,$installTemplate,$editTemplate,$removeTemplate) {
		global $database;
		// check if record exists
		$query = sprintf("SELECT COUNT(*) AS numrec FROM sitewidepermissions WHERE ".
						"userid=%s AND usertype='group' AND deleted=0",
						$userGroupId);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrec']<1) {
			// check if deleted record exists
			$query = sprintf("SELECT COUNT(*) AS numrec FROM sitewidepermissions WHERE ".
					"userid=%s AND usertype='group' AND deleted=1",
					$userGroupId);
			$results2 = $database->queryAssoc($query);
			if ($results2[0][numrec]>0) {
				$query = sprintf("UPDATE sitewidepermissions SET deleted=0 ".
								"WHERE userid=%s AND usertype='group'",$userGroupId);
				$result = $database->execute($query);
			} else {
				$query = sprintf("INSERT INTO sitewidepermissions (userid,usertype,deleted) ".
							"VALUES (%s,'group',0)",
							$userGroupId);
				$result = $database->execute($query);
			}
		}					
		
		$query = sprintf("UPDATE sitewidepermissions SET " .
				"adduser=%s, " .
				"edituser=%s, " .
				"makelevelzerouser=%s, " .
				"addplugin=%s, " .
				"installtemplate=%s, " .
				"edittemplate=%s, " .
				"removetemplate=%s " .
				"WHERE userid=%s AND usertype='group' AND deleted=0",$addUser,$editUser,$makeLevelZeroUser, 
					$addPlugin,$installTemplate,$editTemplate,$removeTemplate,$userGroupId);				
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}	
	}	 
	 
	/**
	 * gets a list of all groups
	 */
	 public static function GetUserGroups($startFrom=0,$orderBy='name ASC',$limit=TRUE) {
	 	global $database, $config;
	 	//if ($limit==FALSE) {
	 		$query = sprintf("SELECT * FROM usergroups WHERE deleted=0 ORDER BY %s",
	 				$orderBy);
	 	
     /*} else {
	 		$query = sprintf("SELECT * FROM usergroups WHERE deleted=0 ORDER BY %s LIMIT %s,%s",
	 				$orderBy,
					$startFrom,
					$config['listPageSize']);
	 	}
	 	*/
	 	$results = $database->queryAssoc($query);
	 	$groups = array();
	 	foreach($results as $result) {
	 		$query = sprintf("SELECT * FROM user2usergroup WHERE groupid=%s AND deleted=0", $result['groupid']);
	 		$u_results = $database->queryAssoc($query);
	 		$users = array();
	 		foreach($u_results as $u_res) {
	 			$users[] = $u_res['userid'];
	 		}
	 		$permissions = array();
	 		$groups[] = new UserGroup($result['groupid'], $result['name'], $users, $permissions, $result['active']);
	 	}
	 	return $groups;
	 	
	 }
	 
	 /**
	  * gets a group by id 
	  */
	  public static function GetUserGroup($groupId) {
	  	global $database;
	  	$query = sprintf("SELECT * FROM usergroups WHERE groupid=%s AND deleted=0", $groupId);
	  	$results = $database->queryAssoc($query);
	  	$group = new UserGroup($groupId, $results[0]['name']);
	  	$group->active = $results[0]['active'];	  	
	  	$query = sprintf("SELECT * FROM user2usergroup WHERE groupid=%s AND deleted=0",$groupId);
	  	$results = $database->queryAssoc($query);
	  	$members = array();
	  	foreach($results as $result) {
	  		$members[] = $result['userid'];
	  	}
	  	$group->members = $members;
	  	//trace("<pre>GROUP IS".print_r($group, true)."</pre>");
	  	return $group;
	  }
	  
	  /**
	   * updates a user gruop record in the database
	   */
	   public static function UpdateUserGroup($id, $name, $active) {
	   		global $database;
			/*
			 * We should check that the current user has the appropriate permissions here.
		 	*/
			$query = sprintf("UPDATE usergroups SET name='%s', active=%s WHERE groupid=%s",
							 $name,
							 $active,
							 $id);
			trace($query);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}			   		
	   }

/**
	* enables a usergroup
	*/
	function EnableUserGroup() {
		global $database;
		$query = sprintf("UPDATE usergroups SET active=1 WHERE groupid=%s", $this->id);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}		
	}
	
	/**
	* disables a user group
	*/
	function DisableUserGroup() {
		global $database;
		$query = sprintf("UPDATE usergroups SET active=0 WHERE groupid=%s", $this->id);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}		
	}
	 
	/**
	 * get the number of user groups for a certain search query (defaults to all)
	 */
	public static function GetUserGroupCount($condition='') {
		global $database;
		$sql = sprintf("SELECT count(groupid) FROM usergroups WHERE deleted=0 %s",
						$condition);
		$results = $database->query($sql);
		$result = $results;
		return $result;
	}	 
	 
	 /**
	  * removes the usergroup and dependant records from the database
	  */
	 function RemoveUserGroup() {
	 	global $database;
	 	// remove from dependent tables
		$query = sprintf("UPDATE sitewidepermissions SET deleted=1 WHERE userid=%s AND usertype='group'", $this->id);
		$result = $database->execute($query);
		// remove users entries in other tables as a result of having group project permissions
		$projects = $this->GetProjects();
		$members = $this->GetMembers();
		foreach ($members as $m) {
			foreach ($projects as $p) {
				$hasPermission = FALSE;
				// check that user does not have individual project permissions
				$iPermissions = User::GetIndividualProjectPermissions($m->id,$p->id);
				foreach ($iPermissions as $ip) {
					if ($ip!=NULL) $hasPermission = TRUE;
				}
				if (!$hasPermission) {
					$query = sprintf("UPDATE calendar SET deleted=1 WHERE userid=%s AND projectid=%s",
									$m->id,
									$p->id);
				//	$result = $database->execute($query);
				}
			}
		}		
		// remove from dependant tables
		$query = sprintf("UPDATE projecttemplatepermissions SET deleted=1 WHERE userid=%s AND usertype='group'", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE projectpermissions SET deleted=1 WHERE userid=%s AND usertype='group'", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE user2usergroup SET deleted=1 WHERE groupid=%s", $this->id);
		$result = $database->execute($query);

		
		// remove from users table
		$query = sprintf("UPDATE usergroups SET deleted=1 WHERE groupid=%s", $this->id);
		$result = $database->execute($query);
	 }
	 
	/**
	 * Searches the usergroups table.
	 * 
	 * @param $query string The Query. 
	 */
	public static function SearchGroups($query ='',$order='name ASC',$startFrom=0){
		global $database,$config;
		$users = array();
		if ($query == '') {
			DisplayMessage('No query provided!');
			return;
		} 
		if ($order == '') {
			$order='name ASC';
		}
		$query = sprintf(
					'SELECT * FROM usergroups WHERE deleted=0 %s ORDER BY %s',
					$query,
					$order
					
					);
		//echo '<br>'.$query;
		trace($query,'Search User Group Query.');
		//$database->database->setLimit($startFrom,$config['listPageSize']);
		$result = $database->queryAssoc($query);
		foreach ($result as $r) {
			$users[] = new UserGroup($r['groupid'],$r['name'],null,null,$r['active']);
		}
		return $users;
	}	 
	 
	 /**
	  * adds a group to the database
	  */
	  public static function AddUserGroup($name) {
	  	global $database;
	  	$query = sprintf("INSERT INTO usergroups(name) VALUES ('%s')",$name);
	  	$result = $database->execute($query);
	  	if ($result !== true) {
	  		die($result);
	  	}
		$id = $database->database->lastInsertID();
		
	  	$query = sprintf("INSERT INTO sitewidepermissions (userid,usertype) VALUES (%s,'group')",$id);
	  	$result = $database->execute($query);
	  	if ($result !== true) {
	  		die($result);
	  	}
	  	$userGroup = new UserGroup($id, $name);
	  	return $userGroup;
	  }
	 
	 /**
	  * adds a user to a user group
	  */
	  public static function AddUser2UserGroup($userId, $groupId) {
	  	global $database;
	  	// first check to ensure that the link doesn't already exist as a deleted record
	  	$query = sprintf("SELECT count(*) AS numrecords FROM user2usergroup ".
	  					"WHERE groupid=%s AND userid=%s",
	  					$groupId,
	  					$userId);
	  	$result = $database->queryAssoc($query);
	  	if ($result[0]['numrecords']>0) {
	  		$query = sprintf("UPDATE user2usergroup SET deleted=0 ".
	  						"WHERE groupid=%s AND userid=%s",
	  						$groupId,$userId);	
	  		$result = $database->execute($query);
	  		if ($result !== true) {
	  			die($result);
	  		}
	  	} else {
	  	  	$query = sprintf("INSERT INTO user2usergroup (groupid,userid) VALUES (%s,%s)",
	  					$groupId,
	  					$userId);
	  		$result = $database->execute($query);
	  		if ($result !== true) {
		  		die($result);
	  		}
	  	}
	  	trace('AddUser2UserGroup Update query:'.$query);
	  }
	/*public static function SearchGroups($query,$order) {
	  	global $database,$config;
		$groups = array();
		if ($query == '') {
			DisplayMessage('No query provided!');
			return;
		} 
		if ($order == '') {
			$order='name ASC';
		}
		$query = sprintf(
					'SELECT * FROM usergroups WHERE %s AND deleted=0 ORDER BY %s',
					$query,
					$order
					);
		//echo '<br>'.$query;
		trace($query,'Search Groups Query.');
		$result = $database->queryAssoc($query);
		foreach ($result as $r) {
			//$users[] = new User($r['userid'],$r['displayname'],null,$r['username'],$r['active']);
			$groups[] = UserGroup::GetUserGroup($r['groupid']);
		}
		//print_r($groups);
		return $groups;
	  }*/
	  
  private $mProjects = null;
	/**
	* Gets a list of projects that the group has permissions for.
	* @return array Array of Project objects.
	*/
	function GetProjects($startFrom=0){
		global $database,$config;
		$this->mProjects = null;
		$sql = sprintf("SELECT userid, projectid " .
				"FROM projectpermissions " .
				"WHERE userid=".$this->id.
				" AND usertype='group' ".
				"AND deleted=0 ")
				;
		//				$startFrom,				$config['listPageSize'])
    //$database->setLimit($conf)
		$results = $database->queryAssoc($sql);
		$out = array();
		foreach($results as $result){
			if ($result['projectid']!=""){
				$p = Project::GetProject($result['projectid']);
				//print_r($p);
				if ($p->IsActive){
				$out[$result['projectid']] = $p->Name . '('.$p->TemplateName.')';
				}
			}
		}
		$this->mProjects = $out;
		return $this->mProjects;
	}	  
	  
  private $mProjectTemplates = null;
	/**
	* Gets a list of projects that the group has permissions for.
	* @return array Array of Project objects.
	*/
	function GetProjectTemplates($startFrom=0){
		global $database,$config;
		$this->mProjectTemplates = null;
		$sql = sprintf("SELECT userid, projecttemplateuid " .
				"FROM projecttemplatepermissions " .
				"WHERE userid=".$this->id.
				" AND usertype='group' ".
				"AND deleted=0 ");
				//"LIMIT %s,%s",				$startFrom,				$config['listPageSize']
		$results = $database->queryAssoc($sql,$startFrom,$config['listPageSize']);
		$out = array();
		foreach($results as $result){
			if ($result['projecttemplateuid']!=""){
				$p = ProjectTemplate::GetTemplate($result['projecttemplateuid']);
				if ($p->IsActive){
				$out[$result['projecttemplateuid']] = $p->Name;
				}
			}
		}
		$this->mProjectTemplates = $out;
		return $this->mProjectTemplates;
	}	  	  
	  
	/**
	 * delete a project from the user group
	 */
	function DeleteProjectPermissions($projectId) {
		global $database;
		$query = sprintf("UPDATE projectpermissions SET deleted=1 ".
						"WHERE userid=%s ".
						"AND usertype='group' ".
						"AND projectid=%s",
						$this->id,
						$projectId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$members = $this->GetMembers();
		foreach ($members as $m) { 
			// first check to see if the user has individual permissions for the simulation
			$permissions = User::GetIndividualProjectPermissions($m->id, $projectId);
			$hasIndPermissions = FALSE;
			foreach ($permissions as $p) {
				if ($p!=NULL) $hasIndPermissions = TRUE;
			}
			if (!$hasIndPermissions) {
				// remove any calendar items for each member for this simulation
				$query = sprintf("UPDATE calendar SET deleted=1 WHERE userid=%s AND projectid=%s",
						$m->id,
						$projectId);
			//	$result = $database->execute($query);
				if ($result !== true) {
					die($result);
				}
			}
		}
		
	}	  
	  
	/**
	 * delete a project template from the user group
	 */
	function DeleteProjectTemplatePermissions($projectTemplateId) {
		global $database;
		$query = sprintf("UPDATE projecttemplatepermissions SET deleted=1 ".
						"WHERE userid=%s ".
						"AND usertype='group' ".
						"AND projecttemplateuid=%s",
						$this->id,
						$projectTemplateId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}	 
	}	   
	  
	/**
	 * adds a record in the db for project permissions for a specific user group and project
	 */
	public static function AddProject2UserGroup($projectId, $userGroupId) {
		global $database;
		$query = sprintf("SELECT count(*) AS numrecords FROM projectpermissions ".
						"WHERE userid=%s AND usertype='group' ".
						"AND projectid=%s",
						$userGroupId,
						$projectId);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrecords']>0) {
			$query = sprintf("UPDATE projectpermissions SET deleted=0 ".
							"WHERE userid=%s AND usertype='group' AND projectid=%s",
							$userGroupId,$projectId);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		} else {
			$query = sprintf("INSERT INTO projectpermissions (userid,usertype,projectid) ".
							"VALUES (%s,'group',%s)",
							$userGroupId,
							$projectId);
			trace($query);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		}
	}	  

	/**
	 * adds a record in the db for project template permissions for a specific user group and project template
	 */
	public static function AddProjectTemplate2UserGroup($projectTemplateId, $userGroupId) {
		global $database;
		$query = sprintf("SELECT count(*) AS numrecords FROM projecttemplatepermissions ".
						"WHERE userid=%s AND usertype='group' ".
						"AND projecttemplateuid=%s ".
						"AND deleted=1",
						$userGroupId,
						$projectTemplateId);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrecords']>0) {
			$query = sprintf("UPDATE projecttemplatepermissions SET deleted=0 ".
							"WHERE userid=%s AND usertype='group' AND projecttemplateuid=%s",
							$userGroupId,$projectTemplateId);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		} else {
			$query = sprintf("INSERT INTO projecttemplatepermissions (userid,usertype,projecttemplateuid) ".
							"VALUES (%s,'group',%s)",
							$userGroupId,
							$projectTemplateId);
			trace($query);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		}
	}	  
	
	/**
	 * returns the groups permissions for a given project
	 */
	public static function GetProjectPermissions($userGroupId, $projectId) {
		global $database;
		$userGroupProjectPermissions = array();
		$sql = sprintf(
				"SELECT * from projectpermissions pp, projects p " .
				"WHERE pp.userid = %s AND " .
				"pp.usertype = 'group' AND " .
				"pp.projectid = %s " .
				"AND pp.projectid = p.projectuid " .
				"AND p.isactive=1 " .
				"AND pp.deleted=0 ".
				"AND p.deleted=0",
				$userGroupId,
				$projectId
				);
		$result = $database->queryAssoc($sql);
		if (sizeof($result)>0) {
			$userGroupProjectPermissions['UseStaffTools'] = $result[0]['usestafftools']; 
			$userGroupProjectPermissions['DeleteAnyItem'] = $result[0]['deleteanyitem'];
			$userGroupProjectPermissions['DeleteItem'] = $result[0]['deleteitem'];
			$userGroupProjectPermissions['ViewItem'] = $result[0]['viewitem'];
			$userGroupProjectPermissions['AddItem'] = $result[0]['additem'];
			$userGroupProjectPermissions['EditAnyItem'] = $result[0]['editanyitem'];
			$userGroupProjectPermissions['EditItems'] = $result[0]['edititems'];
			$userGroupProjectPermissions['StopProject'] = $result[0]['stopproject'];
			$userGroupProjectPermissions['ChangeUserPermissions'] = $result[0]['changeuserpermissions'];
			$userGroupProjectPermissions['EditPlugin'] = $result[0]['editplugin'];
		} else {
			// default to null (not set)
			$userGroupProjectPermissions['UseStaffTools'] = NULL; 
			$userGroupProjectPermissions['DeleteAnyItem'] = NULL;
			$userGroupProjectPermissions['DeleteItem'] = NULL;
			$userGroupProjectPermissions['ViewItem'] = NULL;
			$userGroupProjectPermissions['AddItem'] = NULL;
			$userGroupProjectPermissions['EditAnyItem'] = NULL;
			$userGroupProjectPermissions['EditItems'] = NULL;
			$userGroupProjectPermissions['StopProject'] = NULL;
			$userGroupProjectPermissions['ChangeUserPermissions'] = NULL;	
			$userGroupProjectPermissions['EditPlugin'] = NULL;			
		}
		return $userGroupProjectPermissions;		
	}
	
	/**
	 * returns the groups permissions for a given project template
	 */
	public static function GetProjectTemplatePermissions($userGroupId, $projectTemplateId) {
		global $database;
		$userGroupProjectTemplatePermissions = array();
		$sql = sprintf(
				"SELECT * from projecttemplatepermissions pp, projecttemplates p " .
				"WHERE pp.userid = %s AND " .
				"pp.usertype = 'group' AND " .
				"pp.projecttemplateuid = %s " .
				"AND pp.projecttemplateuid = p.projecttemplateuid " .
				"AND p.isactive=1 " .
				"AND pp.deleted=0 ".
				"AND p.deleted=0",
				$userGroupId,
				$projectTemplateId
				);
		$result = $database->queryAssoc($sql);
		if (sizeof($result)>0) {
			$userGroupProjectTemplatePermissions['EditDocumentTemplate'] = $result[0]['editdocumenttemplate']; 
			$userGroupProjectTemplatePermissions['StartProject'] = $result[0]['startproject'];
			$userGroupProjectTemplatePermissions['EndProject'] = $result[0]['endproject'];
			$userGroupProjectTemplatePermissions['ArchiveProject'] = $result[0]['archiveproject'];
			$userGroupProjectTemplatePermissions['EditTemplate'] = $result[0]['edittemplate'];
			$userGroupProjectTemplatePermissions['ViewTemplate'] = $result[0]['viewtemplate'];
			$userGroupProjectTemplatePermissions['ChangeUserPermissions'] = $result[0]['changeuserpermissions'];
			$userGroupProjectTemplatePermissions['EditPlugin'] = $result[0]['editplugin'];
		} else {
			// default to null (not set)
			$userGroupProjectTemplatePermissions['EditDocumentTemplate'] = NULL; 
			$userGroupProjectTemplatePermissions['StartProject'] = NULL;
			$userGroupProjectTemplatePermissions['EndProject'] = NULL;
			$userGroupProjectTemplatePermissions['ArchiveProject'] = NULL;
			$userGroupProjectTemplatePermissions['EditTemplate'] = NULL;
			$userGroupProjectTemplatePermissions['ViewTemplate'] = NULL;
			$userGroupProjectTemplatePermissions['ChangeUserPermissions'] = NULL;	
			$userGruopProjectTemplatePermissions['EditPlugin'] = NULL;			
		}
		return $userGroupProjectTemplatePermissions;		
	}		
		
	/**
	 * updates permissions for a given user group and project in the database
	 */	
	public static function UpdateProjectPermissions($userGroupId, $projectId, $permissions) {
		global $database;
		$query = sprintf("UPDATE projectpermissions SET ".
						"usestafftools=%s," .
						"deleteanyitem=%s,".
						"deleteitem=%s,".
						"viewitem=%s,".
						"additem=%s,".
						"editanyitem=%s,".
						"edititems=%s,".
						"stopproject=%s,".
						"editplugin=%s,".
						"changeuserpermissions=%s ".
						"WHERE userid=%s ".
						"AND usertype='group' ".
						"AND projectid=%s",
						$permissions['usestafftools'],
						$permissions['deleteanyitem'],
						$permissions['deleteitem'],
						$permissions['viewitem'],
						$permissions['additem'],
						$permissions['editanyitem'],
						$permissions['edititems'],
						$permissions['stopproject'],
						$permissions['editplugin'],
						$permissions['changeuserpermissions'],
						$userGroupId,
						$projectId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}
	  
	/**
	 * updates permissions for a given user group and project template in the database
	 */	
	public static function UpdateProjectTemplatePermissions($userGroupId, $projectTemplateId, $permissions) {
		global $database;
		$query = sprintf("UPDATE projecttemplatepermissions SET ".
						"editdocumenttemplate=%s," .
						"startproject=%s,".
						"endproject=%s,".
						"archiveproject=%s,".
						"edittemplate=%s,".
						"viewtemplate=%s,".
						"changeuserpermissions=%s,".
						"editplugin=%s ".
						"WHERE userid=%s ".
						"AND usertype='group' ".
						"AND projecttemplateuid=%s",
						$permissions['editdocumenttemplate'],
						$permissions['startproject'],
						$permissions['endproject'],
						$permissions['archiveproject'],
						$permissions['edittemplate'],
						$permissions['viewtemplate'],
						$permissions['changeuserpermissions'],
						$permissions['editplugin'],
						$userGroupId,
						$projectTemplateId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}	  
	  
}
?>
