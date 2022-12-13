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
	if (!defined("TLE2")) die ("Invalid Entry Point");
/**
 * Provides user functions and properties, including permissions
  */

class User {

	public $id = NULL;
	public $displayName = "";
	public $blurb = "";
	public $email = "";
	public $regNumber;
	public $active = 0;
	public $notifyOnSend = 1;
	public $notifyOnReceive =1;
	/**
	 * the overall sitewide permissions, once group permissions have been taken into account
	 */
	public $sitewidePermissions = array();
	public $userName = "";
	public $Password = "";
	public $superadmin = DENY;
	public $Properties = array();
	public $Salt = '';
	public $authMethod = '';
	public $avatar = '';
	public $imagetype = '';

  
	
	public function __construct($id=NULL,$displayName=NULL,$blurb='',$sitewidePermissions=NULL,$username=NULL,
	$active=0,$superadmin=DENY) {
		$this->InitPrefs();
    //trace('<h3>User Constructor</h3>');
		//trace('User->constructor>Setting isProjectStaff to null');
		$this->id = $id;
		$this->displayName = $displayName;
		$this->blurb = $blurb;
		$this->sitewidePermissions = $sitewidePermissions;
		$this->userName = $username;
		$this->active = $active;
		$this->superadmin = $superadmin;
		//$this->isProjectStaff();
		
	}
	private $prefs;
    /**
     * This eventually has to be generated from info stored in a db
    */
  function InitPrefs() {
	global $database;
	if (!is_null($this->id)) {
	$qry = "SELECT * FROM userprefs WHERE userid = ".$this->id;
	$results = $database->queryAssoc($qry);
	foreach($results as $pref) {
		print_r($pref);
	}
	}
//    $this->prefs=array(); 
//    $this->prefs[PREF_SHOW_POPUP_LINKS]=true;

  }
	/**
	 * Check to see if we have a valid authenticated Session
	 * This should only be used on a user object stored in the $_SESSION
	 * @return boolean
	*/
	public function IsAuthenticated() {
		if ((isset($this->id)) && ($this->id != "")) {
			return true;
		}
		return false;
	}
	
	 /**
	 * Get user details from database using id
	 * called by authentication methods (GetInternalUser, GetExternalUser) after authentication takes place
	 * @param int the user id
	 * @return User the user details or null if the user is not found!
	 * @return lw the lightweight version (only returns id,username,displayname,etc.)
	 */
	 public static function RetrieveUser($id,$lw=FALSE) {
	 	global $database,$metrics;
		if (!$lw) {
			$query =sprintf("SELECT * FROM users WHERE userid ='%s' AND deleted=0",$id);
			$result = $database->queryAssoc($query,0,1);
			$count = count($result);
  			if ($count>0){
  			 if ($count>1) {
          $metrics->recordMetric('WARNING',"userid collision on $id");
         }
	  			$user = new User();
  				$user->id = $result[0]['userid'];
  				$user->displayName = $result[0]['displayname'];
  				$user->blurb = isset($result[0]['blurb'])?$result[0]['blurb']:'';
  				$user->email = $result[0]['email'];
	  			$user->regNumber = $result[0]['regnumber'];
  				$user->active = $result[0]['active'];
  				$user->userName = $result[0]['username'];
	  			$user->superadmin = $result[0]['superadmin'];
	  			$user->notifyOnSend = isset($result[0]['notifyonsend'])?$result[0]['notifyonsend']:true;
	  			$user->notifyOnRecieve=isset($result[0]['notifyonreceive'])?$result[0]['notifyonreceive']:true;
	  			
  				if (isset($result['properties'])){
  					$user->Properties = DeserialiseArray($result['properties']);
  				}
	  			$user->Password = $result[0]['password'];
  				$user->Salt = $result[0]['salt'];
  				$user->GetGroups();
  				$user->isSitewideStaff(true);
	  			$user->isProjectStaff(true);
  				$user->isProjectTemplateStaff(true);
  				$user->GetSitewidePermissions();
  				$user->projectPermissions=array();
	  			$user->projectTemplatePermissions=array();
  				$projectsList = $user->GetUsersProjects();
  				foreach($projectsList as $key=>$p) {
  					 $user->projectPermissions[$key] = $user->GetProjectPermissions($key);
  				}
	  			$projectTemplatesList = $user->GetUsersProjectTemplates();
  				foreach($projectTemplatesList as $key=>$pt) {
  					$user->projectTemplatePermissions[$key] = $user->GetProjectTemplatePermissions($key);
  				}
	  			//$user->projectPermissions = $user->GetUserProjectsPermissions();
  				//$user->projectTemplatePermissions = $user->GetUserProjectTemplatesPermissions();
  				$user->authMethod = $user->GetAuthMethod();
  				$user->GetAvatar();
	  			//	trace("<pre>User object is".print_r($user, true)."</pre>");
  				return $user;
  			}
  			return null;
		} else { // if lw paramater == TRUE only get the id,displayname and username
			$query =sprintf("SELECT userid,displayname,username FROM users WHERE userid ='%s' AND deleted=0",$id);
			$result = $database->queryAssoc($query,0,1);
			$count = count($result);
          if ($count>0){
            if ($count>1) {
            $metrics->recordMetric('WARNING',"userid collision on $id");
            }
        		$user= new User();
		        $user->id = $result[0]['userid'];
  				$user->displayName = $result[0]['displayname'];
        		$user->userName = $result[0]['username'];
		        return $user;
      		}
      		return null;
    	}
	 }

	
	function GetAvatar() {
		global $database;
		$query = sprintf("SELECT avatar,imagetype FROM users WHERE userid=%s",
						$this->id);
		$results = $database->queryAssoc($query);
		
		$this->avatar = $results[0]['avatar'];
		$this->imagetype = $results[0]['imagetype'];
	}	 	
	 	
	private $mUserGroups = null;
	function GetGroups() {
		global $database;
		$this->mUserGroups = null;
		if (true) {
			$query = sprintf("SELECT g.name, g.groupid ".
							"FROM user2usergroup u, usergroups g ".
							"WHERE u.userid=%s ".
							"AND u.groupid=g.groupid ".
							"AND u.deleted=0 ".
							"AND g.deleted=0",
							$this->id);
			$results = $database->queryAssoc($query);
			$groups = array();
			foreach($results as $result) {
				$groups[] = new UserGroup($result['groupid'],$result['name']);
			}
			$this->mUserGroups = $groups;
		}
		return $this->mUserGroups;
	}	 	

	/**
	 * gets the authentication method of user
	 * if no record exists in db, assumes native authentication
	 * @return string authentication method
	 */
	 function GetAuthMethod() {
	 	global $database;
	 	$query = sprintf("SELECT * FROM userauth WHERE internalid=%s AND deleted=0",
	 			$this->id);
	 	$results = $database->queryAssoc($query);
	 	if (count($results) >0) {
	 		return ($results[0]['authmethod']!=NULL)?$results[0]['authmethod']:'TleAuthenticate';
	 	}
	 	return 'TleAuthenticate';
	 }
	 function GetAuthenticationId() {
 	 	global $database;
	 	$query = sprintf("SELECT * FROM userauth WHERE internalid=%s AND deleted=0",
	 			$this->id);
	 	$results = $database->queryAssoc($query);
	 	if (count($results) >0) {
	 		return ($results[0]['externalid']!=NULL)?$results[0]['externalid']:'';
	 	}
	 	return '';
   }

	/**
	 * gets the users sitewide permissions from the database, taking into account any user groups
	 * of which they are a member
	 * @see GetUserSitewidePermissions()
	 */	 
	 function GetSitewidePermissions() {
	 	global $database;
	 	if (!is_array($this->mUserGroups)) {
	 		$this->mUserGroups = array();
	 	}
	 	$groupSitePermissions = array();
	 	$this->sitewidePermissions = User::GetUserSitewidePermissions($this->id);
		if(!is_null($this->mUserGroups)){
		foreach ($this->mUserGroups as $g) {
			// loop through groups whilst
			// ANDing each result to get permissions array
			$query = sprintf("SELECT * FROM sitewidepermissions ".
				"WHERE userid=%s AND usertype='group' AND deleted=0", $g->id);
			$p_result = $database->queryAssoc($query);
			foreach ($p_result as $p_res) {
				if (isset($groupSitePermissions['AddUser'])) {
					$groupSitePermissions['AddUser']=AndPermissions($groupSitePermissions['AddUser'],$p_res['adduser']);	
				} else {
					$groupSitePermissions['AddUser'] = $p_res['adduser'];
				}
				if (isset($groupSitePermissions['EditUser'])) {
					$groupSitePermissions['EditUser']=AndPermissions($groupSitePermissions['EditUser'],$p_res['edituser']);
				} else {
					$groupSitePermissions['EditUser'] = $p_res['edituser'];
				}
				if (isset($groupSitePermissions['MakeLevelZeroUser'])) {
					$groupSitePermissions['MakeLevelZeroUser']=AndPermissions($groupSitePermissions['MakeLevelZeroUser'],$p_res['makelevelzerouser']);
				} else {
					$groupSitePermissions['MakeLevelZeroUser'] = $p_res['makelevelzerouser'];
				}
				if (isset($groupSitePermissions['AddPlugin'])) {
					$groupSitePermissions['AddPlugin']=AndPermissions($groupSitePermissions['AddPlugin'],$p_res['addplugin']);
				} else {
					$groupSitePermissions['AddPlugin'] = $p_res['addplugin'];
				}			
				if (isset($groupSitePermissions['InstallTemplate'])) {
					$groupSitePermissions['InstallTemplate']=AndPermissions($groupSitePermissions['InstallTemplate'],$p_res['installtemplate']);
				} else {
					$groupSitePermissions['InstallTemplate'] = $p_res['installtemplate'];
				}
				if (isset($groupSitePermissions['EditTemplate'])) {
					$groupSitePermissions['EditTemplate']=AndPermissions($groupSitePermissions['EditTemplate'],$p_res['edittemplate']);
				} else {
					$groupSitePermissions['EditTemplate'] = $p_res['edittemplate'];
				}
				if (isset($groupSitePermissions['RemoveTemplate'])) {
					$groupSitePermissions['RemoveTemplate']=AndPermissions($groupSitePermissions['RemoveTemplate'],$p_res['removetemplate']);
				} else {
					$groupSitePermissions['RemoveTemplate'] = $p_res['removetemplate'];
				}
				if (isset($groupSitePermissions['UseStaffTools'])) {
					$groupSitePermissions['UseStaffTools']=AndPermissions($groupSitePermissions['UseStaffTools'],$p_res['UseStaffTools']);
				} else {
					$groupSitePermissions['UseStaffTools'] = $p_res['UseStaffTools'];
				}				
			}
		}
		// default group permissions to DENY if not yet set (i.e. if no records were returned)
		if ((!isset($groupSitePermissions['AddUser'])) || ($groupSitePermissions['AddUser']==NOTSET)) {
			$groupSitePermissions['AddUser'] = DENY;
		}
		if ((!isset($groupSitePermissions['EditUser'])) || ($groupSitePermissions['EditUser']==NOTSET)) {
			$groupSitePermissions['EditUser'] = DENY;
		}
		if ((!isset($groupSitePermissions['MakeLevelZeroUser'])) || ($groupSitePermissions['MakeLevelZeroUser']==NOTSET)){
			$groupSitePermissions['MakeLevelZeroUser'] = DENY;
		}
		if ((!isset($groupSitePermissions['AddPlugin'])) || ($groupSitePermissions['AddPlugin']==NOTSET)) {
			$groupSitePermissions['AddPlugin'] = DENY;
		}	
		if ((!isset($groupSitePermissions['InstallTemplate'])) || ($groupSitePermissions['InstallTemplate']==NOTSET)) {
			$groupSitePermissions['InstallTemplate'] = DENY;
		}
		if ((!isset($groupSitePermissions['EditTemplate'])) || ($groupSitePermissions['EditTemplate']==NOTSET)) {
			$groupSitePermissions['EditTemplate'] = DENY;
		}
		if ((!isset($groupSitePermissions['RemoveTemplate'])) || ($groupSitePermissions['RemoveTemplate']==NOTSET)) {
			$groupSitePermissions['RemoveTemplate'] = DENY;
		}
		if ((!isset($groupSitePermissions['UseStaffTools'])) || ($groupSitePermissions['UseStaffTools']==NOTSET)) {
			$groupSitePermissions['UseStaffTools'] = DENY;
		}		

		// resolve user and group permissions - if user permissions are NOTSET, take the group permissions
		if ((!isset($this->sitewidePermissions['AddUser'])) || ($this->sitewidePermissions['AddUser']==NOTSET)) {
			$this->sitewidePermissions['AddUser'] = $groupSitePermissions['AddUser'];
		}
		if ((!isset($this->sitewidePermissions['EditUser'])) || ($this->sitewidePermissions['EditUser']==NOTSET)){
			$this->sitewidePermissions['EditUser'] = $groupSitePermissions['EditUser'];
		}
		if ((!isset($this->sitewidePermissions['MakeLevelZeroUser'])) || ($this->sitewidePermissions['MakeLevelZeroUser']==NOTSET)) {
			$this->sitewidePermissions['MakeLevelZeroUser'] = $groupSitePermissions['MakeLevelZeroUser'];
		}
		if ((!isset($this->sitewidePermissions['AddPlugin'])) || ($this->sitewidePermissions['AddPlugin']==NOTSET)) {
			$this->sitewidePermissions['AddPlugin'] = $groupSitePermissions['AddPlugin'];
		}	
		if ((!isset($this->sitewidePermissions['InstallTemplate'])) || ($this->sitewidePermissions['InstallTemplate']==NOTSET)) {
			$this->sitewidePermissions['InstallTemplate'] = $groupSitePermissions['InstallTemplate'];
		}
		if ((!isset($this->sitewidePermissions['EditTemplate'])) || ($this->sitewidePermissions['EditTemplate']==NOTSET)) {
			$this->sitewidePermissions['EditTemplate'] = $groupSitePermissions['EditTemplate'];
		}
		if ((!isset($this->sitewidePermissions['RemoveTemplate'])) || ($this->sitewidePermissions['RemoveTemplate']==NOTSET)) {
			$this->sitewidePermissions['RemoveTemplate'] = $groupSitePermissions['RemoveTemplate'];
		}	 	
        if ((!isset($this->sitewidePermissions['UseStaffTools'])) || ($this->sitewidePermissions['UseStaffTools']==NOTSET)) {
            $this->sitewidePermissions['UseStaffTools'] = $groupSitePermissions['UseStaffTools'];
        }   
		}
	 }
	 	
	 /**
	  * gets the sitewide permissions for a user, NOT taking into account any groups of which they are a member
	  * @param int $userId
	  * @see GetSitewidePermissions()
	  */
	 public static function GetUserSitewidePermissions($userId) {
	 	global $database;
	 	$sitewidePermissions = array();
		$query = sprintf("SELECT * FROM sitewidepermissions WHERE userid=%s AND usertype='user' AND deleted=0", $userId);
		$result = $database->queryAssoc($query);
		if (sizeof($result)>0) {
			$sitewidePermissions['AddUser'] = $result[0]['adduser'];
			$sitewidePermissions['EditUser'] = $result[0]['edituser'];
			$sitewidePermissions['MakeLevelZeroUser'] = $result[0]['makelevelzerouser'];
			$sitewidePermissions['AddPlugin'] = $result[0]['addplugin'];		
			$sitewidePermissions['InstallTemplate'] = $result[0]['installtemplate'];
			$sitewidePermissions['EditTemplate'] = $result[0]['edittemplate'];
			$sitewidePermissions['RemoveTemplate'] = $result[0]['removetemplate'];
            $sitewidePermissions['UseStaffTools'] = $result[0]['usestafftools'];
						
		} else {
			// default to NOTSET
			$sitewidePermissions['AddUser'] = NOTSET;
			$sitewidePermissions['EditUser'] = NOTSET;
			$sitewidePermissions['MakeLevelZeroUser'] = NOTSET;
			$sitewidePermissions['AddPlugin'] = NOTSET;			
			$sitewidePermissions['InstallTemplate'] = NOTSET;
			$sitewidePermissions['EditTemplate'] = NOTSET;
			$sitewidePermissions['RemoveTemplate'] = NOTSET;
            $sitewidePermissions['UseStaffTools'] = NOTSET;
		}	 	
		return $sitewidePermissions;
	 }
	 	
	/**
	 * 
	 */
	private $mIsPrStaff = NULL;
	/**
	 * Check to see if user has access to at least one of the project staff tools
	 * 
	 * We can use without paramters to see if the user is an admin on any project, or
	 * with the $projectId to see if the use is an admin on a specific project.
	 * 
	 * @param int $projectId ID of a project to check for the "usestafftools" permission.
	 * @param boolean $force Forces the object to check against the database if true, otherwise uses cached information.
	 * @return boolean
	 * 
	 */
	public function isProjectStaff($projectId = null,$force = false) {
		if ($this->superadmin == ALLOW) {
			$mIsPrStaff = TRUE;
			return TRUE;
		}
		if (!$this->IsAuthenticated()) {
			$mIsPrStaff = FALSE;
			return FALSE;
		}
		$projectPermissions=array();
		if ((is_null($projectId)) || ($projectId==-1)) {	
				$projectsList = $this->GetUsersProjects();
				foreach($projectsList as $key=>$p) {
				 	$projectPermissions[$key] = $this->GetProjectPermissions($key);
				}				
			foreach ($projectPermissions as $perm) {
				if ($perm['UseStaffTools']==ALLOW) {
					$mIsPrStaff = TRUE;
					return TRUE;
				}
			}
			$mIsPrStaff = FALSE;
			return FALSE;
		} else {
				$projectsList = $this->GetUsersProjects();
				foreach($projectsList as $key=>$p) {
				 	$projectPermissions[$key] = $this->GetProjectPermissions($key);
				}				
			if (count($projectPermissions)>0){
  				if ($projectPermissions[$projectId]['UseStaffTools']==ALLOW) {
  					$mIsPrStaff = TRUE;
  					return TRUE;
  				}
			}
		} 
		$mIsPrStaff = FALSE;
		return FALSE;		

	}

	private $mIsPtStaff = NULL;
	/**
	 * check to see if user has access to at least one of the project template staff tools
	 * @param int $projectTemplateId ID of a project to check permissions on
	 * @param boolean $force Forces the object to check against the database if true, otherwise uses cached information.
	 * @return boolean
	 */
	public function isProjectTemplateStaff($projectTemplateId = null,$force = false) {
		if ($this->superadmin == ALLOW) {
			$mIsPtStaff = TRUE;
			return TRUE;
		}
		if (($this->sitewidePermissions['EditTemplate']==ALLOW) || 
			($this->sitewidePermissions['InstallTemplate']==ALLOW) ||
			($this->sitewidePermissions['RemoveTemplate']==ALLOW)) {
			$mIsPtStaff = TRUE;
			return TRUE;	
		}
		if (!$this->IsAuthenticated()) {
			$mIsPtStaff = FALSE;
			return FALSE;
		}
  		$projectTemplatePermissions = array();
		if (is_null($projectTemplateId)) {
			if ($force){ 
        		$projectTemplatePermissions = $this->GetUserProjectTemplatesPermissions();
      		}
			foreach ($projectTemplatePermissions as $perm) {
				if (($perm['EditTemplate']==ALLOW) || ($perm['ViewTemplate']==ALLOW)) {
					$mIsPtStaff = TRUE;	
					return TRUE;
				}
			}
			$mIsPtStaff = FALSE;
			return FALSE;
		} else {
		//	print "projectTemplateId is ".$projectTemplateId."<br>";
			if ($force) {
        		$projectTemplatePermissions = $this->GetUserProjectTemplatesPermissions();
      		}
      		if (count($projectTemplatePermissions)>0) {
				if ((isset($projectTemplatePermissions[$projectTemplateId])) && 
					($projectTemplatePermissions[$projectTemplateId]['ViewTemplate']==ALLOW)
				|| (isset($projectTemplatePermissions[$projectTemplateId])) &&
					($projectTemplatePermissions[$projectTemplateId]['EditTemplate']==ALLOW)) {
				  $mIsPtStaff = TRUE;
				 // print "returning true <br>";
				  return TRUE;
				}
			}
		}
		
		
		$mIsPtStaff = FALSE;
		return FALSE;
	}
	
	private $mIsSwStaff = null;
	/**
	 * check to see if user has access to at least one of the site tools
	 * @param boolean $force Forces the object to check against the database if true, otherwise uses cached information.
	 * @return boolean
	 */
	public function isSitewideStaff($force = false ) {
		
		$result = false; 
		if (1){
			if (($this->sitewidePermissions['AddUser']==ALLOW) ||
				($this->sitewidePermissions['MakeLevelZeroUser']==ALLOW) ||
				($this->sitewidePermissions['AddPlugin']==ALLOW) ||				
				($this->sitewidePermissions['InstallTemplate']==ALLOW) ||
				($this->sitewidePermissions['EditTemplate']==ALLOW) ||
				($this->sitewidePermissions['RemoveTemplate']==ALLOW) ) {
				$result = true;
			} else {
				$result = false;
			}
		}
		return $result;
	}
	 
	 /**
	  * Get user login details from database using id
	  */
	  public static function RetrieveUserLogin($id) {
	  	global $database;
	  	$query = sprintf("SELECT username, password,salt FROM users WHERE userid=%s AND deleted=0", $id);
	  	$result = $database->queryAssoc($query);
	  	$user = new User();
	  	$user->id = $id;
	  	$user->userName = $result[0]['username'];
	  	$user->password = $result[0]['password'];
	  	$user->salt = $result[0]['salt'];
	  	$query = sprintf("SELECT * FROM userauth WHERE internalid=%s", $id);
	  	$result = $database->queryAssoc($query);
	  	$user->authType = "";
	  	foreach ($result as $r) {
	  		$user->authType.= $r['authmethod']." ";
	  	}
	  	if (empty($user->authType)) {
	  		$user->authType = "Native";
	  	}
	  	return $user;
	  }

	/**
	* Check to see if an external user id exists in the TLE database
	* @param string $authType the authentication method being used
	* @param string $externalId the unique use id in the external authentication method
	* @return boolean external user is in database
	*/
	 public static function ExternalUserExists($authType, $externalId) {
		global $database;
		$query =sprintf("SELECT internalId FROM userauth WHERE authMethod ='%s' AND externalId = '%s' AND deleted=0",$authType,$externalId);

		$result = $database->queryAssoc($query);
		return (count($result)>0) ? true : false;
	 }

	 /**
	 * Get Internal user id from database using auth type and external id
	 * @param string $authType the authentication method being used
	 * @param string $externalId the unique use id in the external authentication method
	 * @return int internal user id
	 */
	 public static function RetrieveExternalUser($authType, $externalId) {
 		global $database;

		$query =sprintf(
			'SELECT internalId FROM userAuth ' .
			'WHERE authMethod =\'%s\' AND externalId = \'%s\' AND deleted=0',
			$authType,$externalId
		);
	//	echo("retrieve external user: ".$query);
		//trace ($query);
		$result = $database->queryAssoc($query);
		//print_r($result);
		$id = $result[0]['internalid'];
		$user = new User();
		$user = self::RetrieveUser($id);
		$user->authMethod = $authType;
		return $user;
	 }

	 /**
	 * Get Internal user from database using username and password
	 * @param string $username internal username
	 * @param string $password internal password
	 * @return User currently logged in user object
	 */
	 public static function RetrieveInternalUser($username, $password) {
 		global $database;
		//$hashedPwd =md5($salt.md5($password));
		$query =sprintf(
			'SELECT userid FROM users ' .
			'WHERE username =\'%s\' AND deleted=0',
			$username
		);
		//trace ($query);
		$result = $database->queryAssoc($query);
		$id = $result[0]['userid'];
		$user = new User();
		$user = self::RetrieveUser($id);
		$user->authMethod = 'TleAuthenticate';
		return $user;
	 }

	/**
	* Create new user in database. Called when user is authenticated for the first time.
	* @param string $authType the authentication method being used
	* @param string $externalId the unique use id in the external authentication method
	* @return User new user id or NULL if the we could not create the User.
	*/
	public static function CreateAuthenticatedUser($authType, $externalId) {
		global $database,$metrics;
		/*
		 * We should check that the current user has the appropriate permissions here.
		 */
		
		$nameString = $externalId." (".$authType.")";
		$passString = md5(uniqid(rand(),1));
		$query = sprintf(
			"INSERT INTO users " .
			"(username,displayname,password) " .
			"VALUES " .
			"('%s','%s','%s')",
			"New User",
			$nameString,
			$passString
		);
		$result = $database->execute($query);
		if ($result !== true) {
			$metrics->recordMetric('AccountNotCreated','Could not create User Account',SafeDb($authType),SafeDb($externalId));
			return null;
		}
		
		$query = sprintf(
			"INSERT INTO sitewidepermissions " .
			"(userid,usertype) " .
			"VALUES " .
			"(%s,'user')",
			$userid
		); // permissions default to NOTSET in DB		
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}		
		$userid = $database->database->lastInsertID();
		
		$query =sprintf(
			'INSERT INTO userAuth ' .
			'(authMethod,externalId,internalId) ' .
			'VALUES (\'%s\',\'%s\',\'%s\')',
			$authType,
			$externalId,
			$userid
		);
		trace('Inserting User: '.$query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$user = self::RetrieveUser($userid);
		$metrics->recordMetric('AccountCreated',SafeDb($authType),SafeDb($externalId),SafeDb($userid));		
		return $user;
	}

	/**
	* Create new user in database. Called when user created by someone with the correct privilege.
	* @param string $authType the authentication method being used
	* @param string $externalId the unique use id in the external authentication method
	* @return int new user id or NULL if we couldn't create the user.
	*/
	//public static function AddUser($authType, $externalId) {
//		global $database;
		/*
		 * We should check that the current user has the appropriate permissions here.
		 */
//		$uniqueString = md5(uniqid(rand(),1));
		// create sensible username and password
//		$query = sprintf("INSERT INTO users (password) VALUES ('%s')",$uniqueString);
	//	trace('Inserting User: '.$query);
//		$result = $database->execute($query);
	//	if ($result !== true) {
//			return null;
		//}

		//$id = $database->database->lastInsertID('users','userid');
//		$id=mysql_insert_id();
	//	trace ("Inserted id: $id");

//		$query = sprintf(
		//	"INSERT INTO sitewidePermissions " .
	//		"(userid,usertype) " .
//			"VALUES " .
		//	"(,%s'user')",
	//		$id
//		); // permissions default to NOTSET in DB		
		//$result = $database->execute($query);
		//if ($result !== true) {
		//	die($result);
	//	}	

//		$query =sprintf("INSERT INTO userAuth (authMethod,externalId,internalId) VALUES ('%s','%s','%s')",
	//					$authType,$externalId,$id);
//		trace('Inserting User: '.$query);
		//trace ($query);
		//$result = $database->execute($query);
	//	if ($result !== true) {
//			die($result);
		//}
		
		// @todo insert into sitewidepermissions ***
		
//		$user = self::RetrieveUser($id);
//		return $user;
//	}

	/**
	 * function to add a user to the db when manually created, rather than automatically
	 * by logging 
 	*/
	public static function ManualAddUser($username,$password,$salt,$displayName,$email,$regNumber,$active) {
		global $database;
		$query = sprintf("INSERT INTO users (username, password, displayname, active, email, regnumber, salt) ".
						"VALUES ('%s','%s','%s','%s','%s','%s','%s')",
						$username,
						$password,
						$displayName,
						$active,
						$email,
						$regNumber,
						$salt);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$id =  $database->database->lastInsertID();
		
		$user = new User($id, $displayName);
		return $user;
	}

 	/**
	* Gets a list of all users
	* Called in order to list users on the main user admin page, so just returns
	* some user properties (displayname, username, active), not all the permissions etc.
	* @return array an array of User objects, ordered by username
	*/
	public static function GetUsers($startFrom=0,$orderBy='username ASC',$limit=TRUE) {
		global $database,$config;
		$users = array();
		if ($limit==FALSE) {
			$query = sprintf(
					'SELECT * FROM users WHERE deleted=0 ORDER BY %s',
					$orderBy
					);
		} else {
			$query = sprintf(
					'SELECT * FROM users WHERE deleted=0 ORDER BY %s',
					$orderBy
					);
		}
		//$database->database->setLimit($startFrom,$config['listPageSize']);
		$result = $database->queryAssoc($query);//,$startFrom,$config['listPageSize']);
		foreach ($result as $r) {
			$users[] = new User($r['userid'],$r['displayname'],null,$r['username'],$r['active']);
		}
		return $users;
	}
	/**
	 * Searches the user table.
	 * 
	 * @param $query string The Query. 
	 */
	public static function SearchUsers($query ='',$order='username ASC',$startFrom=0){
		global $database,$config;
		$users = array();
		if ($query == '') {
			DisplayMessage('No query provided!');
			return;
		} 
		if ($order == '') {
			$order='username ASC';
		}
		$rawQuery ='SELECT * FROM users u LEFT JOIN userauth ua ON u.userid = ua.internalid WHERE u.deleted=0 %s ORDER BY %s';
     
//'SELECT * FROM users WHERE deleted=0 %s ORDER BY %s LIMIT %s,%s'
		$query = sprintf(
					$rawQuery,
					$query,
					$order
					);
		//echo '<br>'.$rawQuery;
		//die($query .' limit:'. $startFrom .'->'.$config['listPageSize']);
		trace($query,'Search User Query.');
		
		//$database->database->setLimit($startFrom,$config['listPageSize']);
		$result = $database->queryAssoc($query,$startFrom,$config['listPageSize']);
		//print_r($result);
		foreach ($result as $r) {
		  //print_r($r);
		  /*
      $id=NULL,
      $displayName=NULL,
      $blurb='',
      $sitewidePermissions=NULL,
      $username=NULL,
	    $active=0,
      $superadmin=DENY
	*/
			$u = new User(
      $r['userid'],
      $r['displayname'],
      null,
      null,
      $r['username'],
      $r['active']);
		//	print_r($u);
			$users[] = $u;
		}
		return $users;
	}

	/**
	 * get the number of users for a certain search query (defaults to all)
	 */
	public static function GetUserCount($condition='') {
		global $database;
		$sql = sprintf("SELECT count(userid) AS user_count FROM users u LEFT JOIN userauth ua ON u.userid = ua.internalid WHERE u.deleted=0 %s",
						$condition);
	   //echo $sql;
		$results = $database->query($sql);
		return $results[0]['user_count'];
	}


	/**
	* removes a user from the database
	*/
	function RemoveUser() {
		global $database;
		/*
		 * We should check that the current user has the appropriate permissions here.
		 */
		
		// remove from dependent tables
		$query = sprintf("UPDATE userauth SET deleted=1 WHERE internalid=%s", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE sitewidepermissions SET deleted=1 WHERE userid=%s AND usertype='user'", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE projecttemplatepermissions SET deleted=1 WHERE userid=%s AND usertype='user'", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE projectpermissions SET deleted=1 WHERE userid=%s AND usertype='user'", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE user2usergroup SET deleted=1 WHERE userid=%s", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE alerts SET deleted=1 WHERE userid=%s", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE calendarassignments SET deleted=1 WHERE userid=%s", $this->id);
		$result = $database->execute($query);
		$query = sprintf("UPDATE commentary SET deleted=1 WHERE userid=%s", $this->id);
		$result = $database->execute($query);
		//$query = sprintf("UPDATE readitems SET deleted=1 WHERE userid=%s", $this->id);
		//$result = $database->execute($query);
		//$query = sprintf("UPDATE taskassignments SET deleted=1 WHERE userid=%s", $this->id);
		//$result = $database->execute($query);
		
		// remove from users table
		//$query = sprintf("UPDATE users SET deleted=1 WHERE userid=%s", $this->id);
		$query = sprintf("DELETE FROM users WHERE userid=%s", $this->id);
		$result = $database->execute($query);
	}

	/**
	* edits a users details
	*/
	function UpdateUser($id,$displayName,$blurb,$email,$regNumber,$active) {
		global $database;
		/*
		 * We should check that the current user has the appropriate permissions here.
		 */
		 
		// first check that new email address does not already exist
		$query = sprintf("SELECT COUNT(*) AS user_count FROM users WHERE email='%s' AND userid<>'%s'", $email, $id);
		$results = $database->query($query);
		if ($results[0]['user_count'] > 0) {
			return "Another user with the email address '".$email."' already exists. Please choose a different address.";
		}
		 
		$query = sprintf("UPDATE users SET displayname='%s', blurb='%s', email='%s', ".
						"regnumber='%s', active=%s WHERE userid=%s", 
						$displayName, $blurb, $email, $regNumber, $active, $id);
		trace($query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}		
		return "ok";
	}
	
	/**
	* enables a user
	*/
	function EnableUser() {
		global $database;
		$query = sprintf("UPDATE users SET active=1 WHERE userid=%s", $this->id);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}		
	}
	
	/**
	* disables a user
	*/
	function DisableUser() {
		global $database;
		$query = sprintf("UPDATE users SET active=0 WHERE userid=%s", $this->id);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}		
	}
		
	
	/**
	 * sets the superadmin permission of a user
	 * 
	 * @param integer Unique ID of the user
	 * @param string Supder Admin flag
	 */
	 public static function SetSuperadmin($id,$superadmin) {
	 	global $database;
	 	if ($_SESSION[USER]->superadmin!=ALLOW) InsufficientPermissions();
	 	$query = sprintf("UPDATE users SET superadmin=%s WHERE userid=%s",
	 		$superadmin,
	 		$id);
	 	$result = $database->execute($query);
	 	if ($result !== true) {
	 		die($result);
	 	}
	 }
	 
	/**
	 * edits a users login details
	 * 
	 * @param integer User's unique ID
	 * @param string New Password
	 * @param string Password for comparison
	 */
	 function UpdateUserLogin($id,$password,$password2) {
		global $database,$config;
		$message=null;
		if ($password!=$password2) {
			$message.= "Passwords do not match.";
			return false;
		}
		$salt = GetSalt();
		$hashed_pwd = HashPassword($password,$salt);
		$query = sprintf('UPDATE users SET password=\'%s\',salt=\'%s\'  WHERE userid=%s', $hashed_pwd, $salt, $id);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		} else {
			$message.= "Password successfully changed.";
		}
	 }

	/**
	 * updates the sitewide permissions for a user
	 * @param int $id the user id
	 * @param int addUser the addUser permission
 	 * @param int editUser the editUser permission
	 * @param int makeLevelZeroUser the makeLevelZeroUser permission
	 * @param int addPlugin the addPlugin permissions
	 * @param int installTemplate the installTemplate permission
	 * @param int editTemplate the editTemplate permission
	 * @param int removeTemplate the removeTemplate permission
 	*/
	public static function UpdateUserSitewidePermissions($id,$addUser,$editUser,$makeLevelZeroUser,$addPlugin,
		$installTemplate,$editTemplate,$removeTemplate) {
		global $database;
		$query = sprintf("SELECT COUNT(*) AS numrec FROM sitewidepermissions WHERE ".
						"userid=%s AND usertype='user' AND deleted=0",
						$id);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrec']<1) {
			// check if deleted record exists
			$query = sprintf("SELECT COUNT(*) AS numrec FROM sitewidepermissions WHERE ".
					"userid=%s AND usertype='user' AND deleted=1",
					$id);
			$results2 = $database->queryAssoc($query);
			if ($results2[0][numrec]>0) {
				$query = sprintf("UPDATE sitewidepermissions SET deleted=0 ".
								"WHERE userid=%s AND usertype='user'",$id);
				$result = $database->execute($query);
			} else {
				$query = sprintf("INSERT INTO sitewidepermissions (userid,usertype,deleted) ".
							"VALUES (%s,'user',0)",
							$id);
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
				"WHERE userid=%s AND usertype='user'",$addUser,$editUser,$makeLevelZeroUser, 
					$addPlugin,$installTemplate,$editTemplate,$removeTemplate,$id);				
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}	
	}

	/**
	* sets a permission for the user
	* @param string $name permission name
	* @param int $val permission value (1 or zero)
	*/
	function SetPermission($name,$val) {
		global $database;
		$query = sprintf("UPDATE sitewidepermissions SET %s=%d WHERE userid=%d AND usertype='user",strtolower($name),$val,$this->id);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}

	}
	
	/**
	 * get an array of all project permissions for the user
	 * 
	 * @return array Array of User Project Permissions.
	 */
	 function GetUserProjectsPermissions() {
	 	global $database;
	 	
	 	$projectsPermissions = array();
	 	$sql = sprintf("SELECT * FROM projectpermissions WHERE userid=%s ".
	 					"AND usertype='user' ".
	 					"AND deleted=0",$this->id);
	 	$results = $database->queryAssoc($sql);
	 	foreach ($results as $result) {
	 		$projectsPermissions[$result['projectid']] = array(
	 			'UseStaffTools'=>$result['usestafftools'],
	 			'DeleteAnyItem'=>$result['deleteanyitem'],
	 			'DeleteItem'=>$result['deleteitem'],
	 			'ViewItem'=>$result['viewitem'],
	 			'EditAnyItem'=>$result['editanyitem'],
	 			'EditItems'=>$result['edititems'],
	 			'StopProject'=>$result['stopproject'],
	 			'EditPlugin'=>$result['editplugin'],
	 			'ChangeUserPermissions'=>$result['changeuserpermissions']
	 		);
	 	}
	 	return $projectsPermissions;
	 }
	 
	 /**
	  * get an array of all project template permissions for the user
	  * 
	  * @return array A ProjectTemplatePermissions array.
	  */
	  function GetUserProjectTemplatesPermissions() {
	  	global $database;
	  	$projectTemplatePermissions = array();
	  	$sql = sprintf("SELECT * FROM projecttemplatepermissions WHERE userid=%s ".
	  					"AND usertype='user' ".
	  					"AND deleted=0", $this->id);
	  	$results = $database->queryAssoc($sql);
	  	foreach ($results as $result) {
	  		$projectTemplatePermissions[$result['projecttemplateuid']] = array(
	  			'EditDocumentTemplate'=>$result['editdocumenttemplate'],
	  			'StartProject'=>$result['startproject'],
	  			'EndProject'=>$result['endproject'],
	  			'ArchiveProject'=>$result['archiveproject'],
	  			'EditTemplate'=>$result['edittemplate'],
	  			'ViewTemplate'=>$result['viewtemplate'],
	  			'EditPlugin'=>$result['editplugin'],
	  			'ChangeUserPermissions'=>$result['changeuserpermissions']
	  		);
	  	}
	  	return $projectTemplatePermissions;
	  }
	
	
/**
 * Gets a list of the user's Active simulations.
 * 
 * @return array Array of Projects
 */
	function GetProjects() {
		return $this->GetUsersProjects();
	}
	

	/**
	 * Gets an array of archived simulations for the user
	 * 
	 * @return array Array of Projects
	 */
	function GetArchivedProjects() {
		//echo 'Getting Archived projects';
		return $this->GetUsersProjects(0);
	}
	/**
	 * Returns the number of archived projects a user has
	 * 
	 * @return integer Number of Archived Projects for the user. 
	 */
	function GetNumberOfArchivedProjects() {
		global $database;
		$sql = 'SELECT count(*) ' .
					'FROM projectpermissions pp ' .
					'LEFT JOIN projects ON ' .
					'pp.projectid = projects.projectuid ' .
					'WHERE userid='.$this->id.
					' AND usertype=\'user\' '.
					'AND projects.deleted=0 ' .
					'AND projects.isactive = 0';
//echo $sql;
			$results = $database->query($sql);
			$count = $results;
			$groups = $this->GetGroups();
			foreach($groups as $group) {
				//echo $group->name;
				$ugProjects = $group->GetProjects();
				foreach($ugProjects as $projectId=>$name){
					if(!isset($out[$projectId])) {
						$p = Project::GetProject($projectId);
						if (!$p->IsActive)
						//$out[$projectId] = $name;// . '('.$p->TemplateName.')';
							$count++;
					}
				}
			}
			
			return $count;
	}
	
   private $mUserProjects = null;
	/**
	* Gets a list of projects that the specified userid is doing.
	* @param int $userid ID of the user.
	* @return array Array of Project objects.
	*/
	function GetUsersProjects($IsActive = 1){
		global $database, $config;
	//	echo 'IsACtive:'.$IsActive;
		$this->mUserProjects = null;
		if (true) {//is_null($this->mUserProjects)){
			$sql = sprintf('SELECT pp.userid, pp.projectid ' .
					'FROM projectpermissions pp ' .
					'LEFT JOIN projects ON ' .
					'pp.projectid = projects.projectuid ' .
					'WHERE userid='.$this->id.
					' AND usertype=\'user\' '.
					'AND projects.deleted=0 ' .
					'AND projects.isactive = %s ' .
					'AND pp.deleted=0',
					$IsActive);
			
			$results = $database->queryAssoc($sql);
			//print_r($results);
			$out = array();
			foreach($results as $result){
				if ($result['projectid']!=""){
					$p = Project::GetProject($result['projectid']);
					if ($p->IsActive == $IsActive){
					$out[$result['projectid']] = $p->Name;
					}
				}
			}
			
			// get blueprints and corresponding sims
			$projectTemplates = $this->GetProjectTemplates();
			foreach ($projectTemplates as $templateId=>$name) {
				$projectList = ProjectTemplate::GetProjects($templateId);
				foreach ($projectList as $p) {
					$out[$p->id] = $p->Name;
				}
			}
		
			//get All of the user's groups and find out what sims they have.
			$groups = $this->GetGroups();
			foreach($groups as $group) {
				//echo $group->name;
				$ugProjects = $group->GetProjects();
				foreach($ugProjects as $projectId=>$name){
					if(!isset($out[$projectId])) {
						$p = Project::GetProject($projectId);
						if ($p->IsActive ==$IsActive){
							$out[$projectId] = $name;// . '('.$p->TemplateName.')';
						}
					}
				}
			}
			
			// get all of the user's groups blueprints and corresponding sims			
			foreach ($groups as $group) {
				$ugBlueprints = $group->GetProjectTemplates();
				foreach($ugBlueprints as $templateId=>$name) {
					$projectList = ProjectTemplate::GetProjects($templateId);
					foreach ($projectList as $p) {
						$out[$p->id] = $p->Name . '('.$p->TemplateName.')';
					}
				}
			}
			
			
			$this->mUserProjects = $out;
		}  
		{
			trace('Re-using Userprojects.');
		}
		return $this->mUserProjects;
	}

	function GetProjectTemplates() {
		return User::GetUsersProjectTemplates();
	}

   private $mUserProjectTemplates = null;
	/**
	* Gets a list of project templates that the specified userid is doing.
	* @param int $userid ID of the user.
	* @return array Array of Project objects.
	*/
	function GetUsersProjectTemplates($startFrom = 0){
		global $database, $config;
		$this->mUserProjectTemplates = null;
		if (true) {
			$sql = sprintf("SELECT userid, projecttemplateuid " .
					"FROM projecttemplatepermissions " .
					"WHERE userid=".$this->id.
					" AND usertype='user' ".
					"AND deleted=0 ")					
					;
					//trace($sql);
			$results = $database->queryAssoc($sql);
			$out = array();
			foreach($results as $result){
				if ($result['projecttemplateuid']!=""){
					$p = ProjectTemplate::GetTemplate($result['projecttemplateuid']);
					if ($p->IsActive){
						$out[$result['projecttemplateuid']] = $p->Name;
						//trace("<pre>".print_r($out, true)."</pre>");
					}
				}
			}
			
			/** dont need to bother with groups in this method
			//check to see if any of the users groups have records
			if (sizeof($results) == 0) {
				$groups = array();
				$sql = sprintf("SELECT * FROM user2usergroup WHERE userid=%s AND deleted=0", $this->id);
				$results = $database->queryAssoc($sql);
				foreach($results as $result) {
					$groups[] = $result['groupid'];
					
				}
			} 
			// if so, AND the results			
			// @todo
			
			*/
			$this->mUserProjectTemplates = $out;
			//trace("<pre>".print_r($this->mUserProjectTemplates,true)."</pre>");
		}  
		{
			trace('Re-using Userprojects.');
		}
		return $this->mUserProjectTemplates;
	}

        function ApplySitePermissions($permissionset) {
			//print_r($permissionset);
            $sitePermissions = $this->GetSitewidePermissions();
            if (is_array($sitePermissions)) {
                foreach($sitePermission as $key=>$value) {
                    if (isset($permissionset[$key])){
                        $permissionset[$key] = $value;
                    }
                }
                return $permissionset;
            }
            return $permissionset;
        }

	/**
	 * returns the users permissions for a givem project, taking groups into account
	 */
	public function GetProjectPermissions($projectId) {
		return Project::GetUserProjectPermissions($this->id,$projectId);
	}
	
	/**
	 * returns the users permissions for a given project template, taking groups into account
	 */
	public function GetProjectTemplatePermissions($projectTemplateId) {
		return ProjectTemplate::GetUserProjectTemplatePermissions($this->id,$projectTemplateId);
	}	
	
	/**
	 * returns the users permissions for a given project, not taking groups into account
	 */
	public static function GetIndividualProjectPermissions($userId, $projectId) {
		global $database;
		$userProjectPermissions = array();
		// first check to see if user is superadmin
		$sql = sprintf("SELECT superadmin FROM users WHERE userid=%s AND deleted=0",
						$userId);
		$results = $database->queryAssoc($sql);
		if ($results[0]['superadmin']==ALLOW) {
			$userProjectPermissions['UseStaffTools'] = ALLOW; 
			$userProjectPermissions['DeleteAnyItem'] = ALLOW;
			$userProjectPermissions['DeleteItem'] = ALLOW;
			$userProjectPermissions['ViewItem'] = ALLOW;
			$userProjectPermissions['AddItem'] = ALLOW;
			$userProjectPermissions['EditAnyItem'] = ALLOW;
			$userProjectPermissions['EditItems'] = ALLOW;
			$userProjectPermissions['StopProject'] = ALLOW;
			$userProjectPermissions['ChangeUserPermissions'] = ALLOW;		
			$userProjectPermissions['EditPlugin'] = ALLOW;		
			return $userProjectPermissions;		
		}
		$sql = sprintf(
				"SELECT * from projectpermissions pp, projects p " .
				"WHERE pp.userid = %s AND " .
				"pp.usertype = 'user' AND " .
				"pp.projectid = %s " .
				"AND pp.projectid = p.projectuid " .
				"AND p.isactive=1 " .
				"AND pp.deleted=0 ".
				"AND p.deleted=0",
				$userId,
				$projectId
				);
		$result = $database->queryAssoc($sql);
		if (sizeof($result)>0) {
			$userProjectPermissions['UseStaffTools'] = $result[0]['usestafftools']; 
			$userProjectPermissions['DeleteAnyItem'] = $result[0]['deleteanyitem'];
			$userProjectPermissions['DeleteItem'] = $result[0]['deleteitem'];
			$userProjectPermissions['ViewItem'] = $result[0]['viewitem'];
			$userProjectPermissions['AddItem'] = $result[0]['additem'];
			$userProjectPermissions['EditAnyItem'] = $result[0]['editanyitem'];
			$userProjectPermissions['EditItems'] = $result[0]['edititems'];
			$userProjectPermissions['StopProject'] = $result[0]['stopproject'];
			$userProjectPermissions['ChangeUserPermissions'] = $result[0]['changeuserpermissions'];
			$userProjectPermissions['EditPlugin'] = $result[0]['editplugin'];
		} else {
			// default to null (not set)
			$userProjectPermissions['UseStaffTools'] = NULL; 
			$userProjectPermissions['DeleteAnyItem'] = NULL;
			$userProjectPermissions['DeleteItem'] = NULL;
			$userProjectPermissions['ViewItem'] = NULL;
			$userProjectPermissions['AddItem'] = NULL;
			$userProjectPermissions['EditAnyItem'] = NULL;
			$userProjectPermissions['EditItems'] = NULL;
			$userProjectPermissions['StopProject'] = NULL;
			$userProjectPermissions['ChangeUserPermissions'] = NULL;		
			$userProjectPermissions['EditPlugin'] = NULL;		
		}
		return $userProjectPermissions;		
	}
	
	/**
	 * returns the users permissions for a given project template, not taking groups into account
	 */
	public static function GetIndividualProjectTemplatePermissions($userId, $projectTemplateId) {
		global $database;
		$userProjectTemplatePermissions = array();
		$sql = sprintf(
				"SELECT * from projecttemplatepermissions pp, projecttemplates p " .
				"WHERE pp.userid = %s AND " .
				"pp.usertype = 'user' AND " .
				"pp.projecttemplateuid = %s " .
				"AND pp.projecttemplateuid = p.projecttemplateuid " .
				"AND p.isactive=1 " .
				"AND pp.deleted=0 ".
				"AND p.deleted=0",
				$userId,
				$projectTemplateId
				);
		$result = $database->queryAssoc($sql);
		if (sizeof($result)>0) {
			$userProjectTemplatePermissions['EditDocumentTemplate'] = $result[0]['editdocumenttemplate']; 
			$userProjectTemplatePermissions['StartProject'] = $result[0]['startproject'];
			$userProjectTemplatePermissions['EndProject'] = $result[0]['endproject'];
			$userProjectTemplatePermissions['ArchiveProject'] = $result[0]['archiveproject'];
			$userProjectTemplatePermissions['EditTemplate'] = $result[0]['edittemplate'];
			$userProjectTemplatePermissions['ViewTemplate'] = $result[0]['viewtemplate'];
			$userProjectTemplatePermissions['ChangeUserPermissions'] = $result[0]['changeuserpermissions'];
			$userProjectTemplatePermissions['EditPlugin'] = $result[0]['editplugin'];
		} else {
			// default to null (not set)
			$userProjectTemplatePermissions['EditDocumentTemplate'] = NULL; 
			$userProjectTemplatePermissions['StartProject'] = NULL;
			$userProjectTemplatePermissions['EndProject'] = NULL;
			$userProjectTemplatePermissions['ArchiveProject'] = NULL;
			$userProjectTemplatePermissions['EditTemplate'] = NULL;
			$userProjectTemplatePermissions['ViewTemplate'] = NULL;
			$userProjectTemplatePermissions['ChangeUserPermissions'] = NULL;
			$userProjectTemplatePermissions['EditPlugin'] = NULL;				
		}
		return $userProjectTemplatePermissions;		
	}	
	
	
	/**
	 * updates the avatar image of the specified user
	 * @param userId 		the id of the user
	 * @param tmpfilepath	the temp path of the uploaded file	
	 */
	 public static function UpdateUserAvatar($userId, $tmpfilepath, $imagetype) {
	 	global $database;
	 	$sql = sprintf("UPDATE users SET avatar='%s', imagetype='%s' WHERE userid='%s'",
						file_get_contents($tmpfilepath),
						$imagetype,
						$userId);
		$result = $database->database->query($sql);
						
	 }
	
	/**
	 * updates permissions for a given user and project in the database
	 */	
	public static function UpdateIndividualProjectPermissions($userId, $projectId, $permissions) {
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
						"changeuserpermissions=%s, ".
						"editplugin=%s ".
						"WHERE userid=%s ".
						"AND usertype='user' ".
						"AND projectid=%s",
						$permissions['usestafftools'],
						$permissions['deleteanyitem'],
						$permissions['deleteitem'],
						$permissions['viewitem'],
						$permissions['additem'],
						$permissions['editanyitem'],
						$permissions['edititems'],
						$permissions['stopproject'],
						$permissions['changeuserpermissions'],
						$permissions['editplugin'],
						$userId,
						$projectId);
						//trace($query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	public static function UpdateIndividualProjectPermission($userId, $projectId, $permission,$value) {
		global $database;
		$query = sprintf("UPDATE projectpermissions SET ".
						"%s=%s " .
						"WHERE userid=%s ".
						"AND usertype='user' ".
						"AND projectid=%s",
						$permission,
						$value,
						$userId,
						$projectId);
						//trace($query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}
		
	/**
	 * updates permissions for a given user and project template in the database
	 */	
	public static function UpdateIndividualProjectTemplatePermissions($userId, $projectTemplateId, $permissions) {
		global $database;
		$query = sprintf("UPDATE projecttemplatepermissions SET ".
						"editdocumenttemplate=%s," .
						"startproject=%s,".
						"endproject=%s,".
						"archiveproject=%s,".
						"edittemplate=%s,".
						"viewtemplate=%s,".
						"changeuserpermissions=%s, ".
						"editplugin=%s ".
						"WHERE userid=%s ".
						"AND usertype='user' ".
						"AND projecttemplateuid=%s",
						$permissions['editdocumenttemplate'],
						$permissions['startproject'],
						$permissions['endproject'],
						$permissions['archiveproject'],
						$permissions['edittemplate'],
						$permissions['viewtemplate'],
						$permissions['changeuserpermissions'],
						$permissions['editplugin'],
						$userId,
						$projectTemplateId);
						//trace($query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}		
		
	/**
	 * adds a record in the db for project permissions for a specific user and project
	 */
	public static function AddProject2User($projectId, $userId, $tutor=FALSE) {
		global $database;
		$query = sprintf("SELECT count(*) AS numrecords FROM projectpermissions ".
						"WHERE userid=%s AND usertype='user' ".
						"AND projectid=%s ".
						"AND deleted=0",
						$userId,
						$projectId);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrecords']>0) {
			// already exists
			return FALSE;
		}		
		
		$query = sprintf("SELECT count(*) AS numrecords FROM projectpermissions ".
						"WHERE userid=%s AND usertype='user' ".
						"AND projectid=%s ".
						"AND deleted=1",
						$userId,
						$projectId);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrecords']>0) {
			$query = sprintf("UPDATE projectpermissions SET deleted=0 ".
							"WHERE userid=%s AND usertype='user' AND projectid=%s",
							$userId,$projectId);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		} else {
			$query = sprintf("INSERT INTO projectpermissions (userid,usertype,projectid) ".
							"VALUES (%s,'user',%s)",
							$userId,
							$projectId);
			//	trace($query);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		}
		if ($tutor) {
			$query = sprintf("UPDATE projectpermissions SET usestafftools=1, deleteanyitem=1, deleteitem=1, ".
							"viewitem=1, additem=1, editanyitem=1, edititems=1 ".
							"WHERE userid=%s AND usertype='user' AND projectid=%s",
							$userId,$projectId);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		}
		
	}

	/**
	 * adds a record in the db for project template permissions for a specific user and template
	 */
	public static function AddProjectTemplate2User($projectTemplateId, $userId) {
		global $database;
		$query = sprintf("SELECT count(*) AS numrecords FROM projecttemplatepermissions ".
						"WHERE userid=%s AND usertype='user' ".
						"AND projecttemplateuid=%s ".
						"AND deleted=1",
						$userId,
						$projectTemplateId);
		$results = $database->queryAssoc($query);
		if ($results[0]['numrecords']>0) {
			$query = sprintf("UPDATE projecttemplatepermissions SET deleted=0 ".
							"WHERE userid=%s AND usertype='user' AND projecttemplateuid=%s",
							$userId,$projectTemplateId);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		} else {
			$query = sprintf("INSERT INTO projecttemplatepermissions (userid,usertype,projecttemplateuid) ".
							"VALUES (%s,'user',%s)",
							$userId,
							$projectTemplateId);
			//trace($query);
			$result = $database->execute($query);
			if ($result !== true) {
				die($result);
			}
		}
	}

	/**
	 * delete a project from the user
	 */
	function DeleteProjectPermissions($projectId) {
		global $database;
		//$query = sprintf("UPDATE projectpermissions SET deleted=1 ".
		//				"WHERE userid=%s ".
		//				"AND usertype='user' ".
		//				"AND projectid=%s",
		//				$this->id,
		//				$projectId);
		$query = sprintf("DELETE FROM projectpermissions ".
						"WHERE userid=%s ".
						"AND usertype='user' ".
						"AND projectid=%s",
						$this->id,
						$projectId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}	 
		// first check that do not have permissions as part of group
		$groups = $this->GetGroups();
		$hasGroupPerm = FALSE;
		foreach ($groups as $g) {
			$permissions = UserGroup::GetProjectPermissions($g->id, $projectId);
			foreach ($permissions as $p) {
				if ($p!=NULL) $hasGroupPerm = TRUE;
			}
		}
		if (!$hasGroupPerm) {
			// 	remove any calendar items for this user and simulation
			$query = sprintf("UPDATE calendar SET deleted=1 WHERE userid=%s AND projectid=%s",
						$this->id,
						$projectId);
		//	$result = $database->execute($query);
		//	if ($result !== true) {
		//		die($result);
		//	}
		}
	}

	/**
	 * delete a project template from the user
	 */
	function DeleteProjectTemplatePermissions($projectTemplateId) {
		global $database;
		/*$query = sprintf("DELETE FROM projectpermissions ".
						"WHERE userid=%s ".
						"AND usertype='user' ".
						"AND projectid=%s",
						$this->id,
						$projectId); */
		$query = sprintf("UPDATE projecttemplatepermissions SET deleted=1 ".
						"WHERE userid=%s ".
						"AND usertype='user' ".
						"AND projecttemplateuid=%s",
						$this->id,
						$projectTemplateId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}	 
	}

	public function __destruct() {
	}
  
  function GetAlerts() {
    return Alert::GetAlerts($this->id);
  }
  
  /*
  New Stuff!
  */
  function GetPrefs($prefname='') {
    return $this->GetPreferences($prefname);
  }
  function GetPreferences($prefname='') {
	global $database;
	$prefs = array();
	$defaults = array();
	$defaults[PREF_SHOW_POPUP_LINKS] = true;
	$defaults['showdocumentflag'] = 1;
	$qry='';
	if ($this->id=='') {
		return $defaults;
	}
	if ($prefname!='') {
		$qry = "SELECT * FROM userprefs WHERE userid = ".$this->id . " AND prefname='$prefname'";
		$results = $database->queryAssoc($qry);
		if (count($results)>0) {
			$prefs[$results[0]['prefname']] = $results[0]['value'];
		}	
	}
	else {
		$qry = "SELECT * FROM userprefs WHERE userid=".$this->id;
		$results = $database->queryAssoc($qry);
		$prefs = array();
		foreach($results as $result) {
			$prefs[$result['prefname']] =$result['value'];
		}
	}
	$prefs = array_merge($defaults,$prefs);
	if ($prefname!=''){
		if (isset($prefs[$prefname])) {
			return $prefs[$prefname];
		}
		else {
			return false;
		}
	}

	return $prefs;
	}
}

?>
