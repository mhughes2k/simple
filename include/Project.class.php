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
	* This should actually be named "Simulation" but that will break too many things.
	* 
	* Thus internally SIMPLE classes map on to the "Formal" structures as follows:
	*   Container === Project
	*   Project === Simulation
	*   ProjectTemplate === Player-Role Blueprint(BP)
	*
	* @package TLE2
	*/
	class Project {

		/**
		* Unique runtime identifier for the project.
		* @var int
		*/
		public $id = -1;

		/**
		* The ID of the Project template that was used to create the project.
		* @var int
		*/
		public $templateId = -1;

		/**
		* The Name of the Project.
		* @var string
		*/
		public $Name = "";

		/**
		* Are communications tools shown to tutors?
		* @var bool
		*/
		public $tutorsSeeTools = true;

		/**
		 * Holds various properties for the project.
		 */
		public $properties = array();
		/**
		 * The Id of (the current) event/task of the Project.
		 */
		public $CurrentPosition=-1;
		/**
		 * The Id of the user that created the project.
		 */
		public $CreatorId = -1;
		 /**
		  * Name of the folder into which received items are placed.
		  */
		public $Inbox ='';
		/**
		 * Name of the folder which copies of sent items are kept.
		 */
		public $SentItems = "Sent"; // need to ensure that cannot change this in tools!
		public $TemplateName = "";
		public $Variables = array();
		public $trashcan = -1;
		public $IsActive = 1;
		public $Deleted = false;
		public $Stylesheet ='';
		
		/**
		 * Mainly used to hold the user's permissions when we are rendering 
		 * the project's information.
		 * 
		 * Is set in the Project::GetAdminstrableProjects() function.
		 * If we need the Users Permission information you should call the GetProjectPermissions(); 
		 */
		 public $ProjectUserPermissions;
		
		/**
		* Constructs a Project object.
		*
		* This has been "concreted" and no longer just loops through the result and adds 
		* fields at will!
		* 
		* @param int $Id The runtime Id of the project.
		* @param int $templateId Unique identifier of the Project Template used to create the project.
		* @param string $name The Name of the Project.
		*/
		function __construct($dbRow = null) {
			$this->Variables = array();
			if (!is_null($dbRow)){
				
				$this->id = (int)$dbRow['projectuid'];
				$this->templateId = $dbRow['projecttemplateid'];
				$this->Name = $dbRow['name'];
				$this->Inbox = $dbRow['inbox'];
				$this->SentItems = $dbRow['sentitems'];
				$this->IsActive = (bool)$dbRow['isactive'];
				$this->Deleted = (bool)$dbRow['deleted'];
				$this->StyleSheet= $dbRow['stylesheet'];
				$this->Variables = DeserialiseArray($dbRow['variables'],true);
				$this->CreatorId = (integer)$dbRow['creatorid'];
				//dumpArray($this->Variables,"Project construtor Variables");
				$t = $this->GetProjectTemplate();
				if (isset($t)) {
				  $this->TemplateName = $t->Name;
				}
				//print_r($t);
				//trace("T Name: ".$t."<BR>".$this->TemplateName);
			}
		}
		/**
		 * Returns the name of the creator.
		 * 
		 * The Creator is the person who created the simulation from a BP.
		 */
		public function GetCreatorName() {
			$creator = User::RetrieveUser($this->CreatorId,true);
			if (is_null($creator)) {
				return 'No Creator found!';
			}
			return $creator->displayName!=''?$creator->displayName:'User #'.$creator->id;
		}
		private $mResources =null;
		/**
		 * Gets the resouces for the project
		 */
		function getResources(){
			global $database;
			$resources = array();
			$resources =$this->GetDocumentTemplates(PLAYER_TEMPLATES_ONLY);
		//	echo 'P resources:';
      //print_r($resources);
			return $resources;
		}
		/**
		 * gets the url for a url resource in the project
		 */
		 function getUrlResourceById($id) {
		 	global $database;
		 	$pt = ProjectTemplate::getTemplate($this->templateId);
			if (!is_null($pt)){
				$url=$pt->getUrlResourceById($id);
			} else {
				return NULL;
			}
			$vb= $this->GetVariabliser();
			$url = $vb->Substitute($url);
			return $url;
		 }
		private $mFolders = null;
		/**
		 * Returns an array containing all of the events, tasks and materials in 
		 * the project.
		 * 
		 * @return array An array containing all of the events, tasks and materials in 
		 * the project 
		 */
		function GetEvents() {
			global $database;
			$eventSql = sprintf(
							'SELECT ' .
							'p.*, pp.state,pp.color ' .
							'FROM projectsequence p ' .
							'left join projectprogress pp ' .
							'ON ' .
							'p.projecttemplateeventid = pp.eventid ' .
							'AND pp.projectid = %s '. 
							'WHERE ' .
							'projecttemplateid = %s',
							$this->id,
							$this->templateId
						);
			
			$results = $database->queryAssoc($eventSql);

			$startEventId =-1;
			$events = array();
			foreach($results as $result) {
				$events[$result['projecttemplateeventid']]= $result;
				//print_r($result);
				if ($result['previouseventid']=='0'){
					$startEventId = $result['projecttemplateeventid'];
				}
				//$events[$result['projecttemplateeventid']]['hasChildren']=false;
			}
			$orderedEventList = array();
			//echo "Start event id :$startEventId<br>"; 
			//$this->build($events,$startEventId,$orderedEventList);
			
			//die();
			if (count($results)>0){
			 return $this->build($events,$startEventId,$orderedEventList);
			}
			return array();
		}
		/**
		 * Internal function to build a list representing a N.E.D.
		 * 
		 * The resulting list also contains any "tags" that have been set 
		 * for each N.E.D. element for the simulation.
		 * 
		 * @param array $eventList Raw List of Events and tasks
		 * @param string $eventId The BP Event ID we are at.
		 * @param array $orderedEventList the output list.
		 */
		private function build(&$eventList, $eventId,&$orderedEventList) {
			global $database;
			$event = (isset($eventList[$eventId]))? $eventList[$eventId] : NULL;
			//echo '<br><br>Looking at event '. $event['name'];
			
			
			/*
			 * find out if this event has any children
			 */
			 $childrenSql = sprintf(
							'SELECT p.*, pp.state, pp.color ' .
							'FROM projectsequence p ' .
							'LEFT JOIN projectprogress pp ' .
							'ON p.projecttemplateeventid = pp.eventid AND pp.projectid=%s ' .
							'WHERE p.projecttemplateid =%s ' .
							'AND ' .
							'p.itemtype = 3 AND ' .
							'p.previouseventid = \'%s\'',
							$this->id,
							$this->templateId,
							$eventId
							);
			$children = $database->queryAssoc($childrenSql);
			//echo($childrenSql);
			//echo 'Count'.count($children);
			$event['hasChildren']=false;
			if (count($children)>0) {
				//echo $eventId.' hasChildren';
        $event['hasChildren']=true;
        $orderedEventList[] = $event;
        foreach($children as $child){
					//print_r($child);
					//echo '<br>';
					$orderedEventList[] = $child; 
				}
			}
			else {
        $event['hasChildren']=false;
        $orderedEventList[] = $event;
      }
			//echo 'Next Event Id'. $event['nexteventid'].'<br>';
			
			if ((isset($event['nexteventid']) &&
				$event['nexteventid']!='0') && ($event['nexteventid']!=$eventId )){
				//echo 'Doing recursion<br>';
				$this->build($eventList,$event['nexteventid'],$orderedEventList);
			}
			if ((isset($event['nexteventid'])) && ($event['nexteventid']==$eventId)) {
          //echo '<p>'.$event['name'] .$event['projecttemplateeventid']. ' links to itself!'.$event['nexteventid'].'</p>';
      }
			return $orderedEventList; 
		}
		
		
		/**
		 * Returns the folders for the Project.
		 * The folder list is cached once loaded.
		 * 
		 * @return array An Array of Folder items.
		 */
		function getFolders($force = false) {
			global $database;
			if ($force or is_null($this->mFolders)) {
				$this->mFolders=Folder::GetFolders($this->id);
			}else {

			}
			return $this->mFolders;
		}
		
		function AddFolder($name) {
      $folder = new Folder();
      $folder->name = $name;
      $folder->projectid=$this->id;
      $folder->canbedeleted= true;
      $folder->additem =true;
      $folder->allowdeletes=true;
      $folder->Save();
      return $folder;
    }
		/**
		 * Returns the ID of the Folder which new mail should be added to
		 * 
		 * @return integer Internal ID of the folder for new items to appear in.
		 */
		function GetDeliveryFolder(){
		  return $this->GetFolderIdByName($this->Inbox);
		}
		function GetFolderIdByName($name) {
			$folders = $this->getFolders();
			foreach($folders as $folder){
				if ($folder->name == $name){
					return $folder->folderid;
				}
			}
			return -1;
		}
		/**
		 * Gets the Unique Internal ID of the the "Sent Items"
		 * folder.
		 * 
		 * @return integer Unique Internal ID of the the "Sent Items" folder.
		 */
		function GetSentItemsFolder() {
			return $this->GetFolderIdByName($this->SentItems);
		}
		/**
		 * Returns the Id of the folder which is the trash can.
		 * 
		 * @return int The Id of the trashcan folder for the project or -1 if no trashcan.
		 */
		function GetWasteBasketId() {
			$folders = $this->getFolders();
			if($folder->folderid<0) {
				foreach($folders as $folder){
					if ($folder->trashcan){
						$this->trashcan = $folder->folderid;
						return $folder->folderid;
					}
				}
				return -1;
			}
			else {
				return $this->trashcan;
			}
		}
		/**
		 * Gets the template that the project is based on.
		 * @return ProjectTemplate ProjectTemplate that project is based on.
		 */
		function GetProjectTemplate() {
			$template = ProjectTemplate::getTemplate($this->templateId);
			return $template;
		}
		/**
		 * Gets the name of the Projects Template.
		 */
		function GetTemplateName() {
			//trace(dumpArray(debug_backtrace()));
			//print_r($this);
			trace('Getting Project\'s template name:'.$this->templateId);
			$r = ProjectTemplate::getTemplate($this->templateId);
			//print_r($r);
			trace('Getting Project\'s template name:'.$r->name);
			return $r->name;
		}

		/**
		 * Gets an array of Directory Items, representing the Characters 
		 * in a simulation.
		 * 
		 * @return array An array of Directory Items
		 */
		function GetCharacters() {
			global $database;
			// get linked projects array for each directory entry
			$sql = sprintf("SELECT * FROM directory WHERE projectid =" .$this->id . " order by name asc"); 
			$results = $database->queryAssoc($sql);
			$characters = array();
			foreach($results as $directoryItem){
					$d = new DirectoryItem($directoryItem);
					$role = stripslashes($directoryItem['projectrole']);
					$characters[$role] =$d;
			}
			return $characters;
		}
		/**
		 * Hides/Shows a project.
		 */
		function SetProjectVisibility($visible = 0) {
			global $database;	
			$sql = sprintf('UPDATE projects SET isactive = %s WHERE projectuid = %s',
						$visible,
						$this->id
					);
			$database->execute($sql);
			$this->isactive= $visible;
		}
		/**
		 * Synonym for saveProject();
		 * 
		 * For consistancy with other classes!
		 */
		function Save() {
			$this->saveProject();
		}
		/**
		 * Saves a Project object back to the database.
		 */
		function saveProject() {
			global $database;
			$sql ="";
			if ($this->id<0)	{

				$sql = sprintf(
						'INSERT INTO projects ' .
						'(projecttemplateid, ' .
						'name,' .
						'inbox,' .
						'sentitems,' .
						'variables,' .
						'creatorid,' .
						'createddate, '. 
            			'stylesheet, '.
						'deleted) ' .
						'VALUES ' .
						'(' .
						'\'%s\',' .
						'\'%s\',' .
						'\'%s\',' .
						'\'%s\',' .
						'\'%s\',' .
						'%s, \'%s\', \'%s\', %s)',
						$this->templateId,
						addslashes($this->Name),
						$this->Inbox,
						$this->SentItems,
						$this->SerialiseVariables(),
						$this->CreatorId,
						date('Y-m-d H:i:s'),
						$this->Stylesheet,
						(integer)$this->Deleted
					);
		
				$database->execute($sql);
				$lastId = $database->database->lastInsertID();
				$this->id = $lastId;

			}
			else {
				$sql = sprintf(
						'UPDATE projects SET ' .
						'projecttemplateid = %s,' .
						'name = \'%s\',' .
						'inbox = \'%s\',' .
						'sentitems = \'%s\',' .
						'variables = %s,' .
						'isactive = %s, ' .
						'deleted = %s , '. 
            'stylesheet = %s ' .
						'WHERE projectuid = %s',
						$this->templateId,
						addslashes($this->Name),
						$this->Inbox,
						$this->SentItems,
						$database->database->quote($this->SerialiseVariables()),
						(integer)$this->IsActive,
						(integer)$this->Deleted,
						$database->database->quote($this->Stylesheet),
						$this->id
						);
				trace("Updating existing project<br>$sql");
				trace("Project->SaveProject:<br>".$sql);
				$database->execute($sql);
			}
		}
		
		
		
		/**
		 * Gets a list of documentTemplates that belong to the project.
		 *
		 * Projects don't actually have any documentTemplates associated with them,
		 * it is ProjectTemplates that have documentTemplate items, so this will actually retrieve that
		 * information from the underlying ProjectTemplate.
		 * 
		 * @param string $filter Restricts the returned items to either only player-visible or all templates. Defaults to Player-visible.
		 * @return array Array of database rows, representing the Simulation's Templates
		 */
		function GetDocumentTemplates($filter=PLAYER_TEMPLATES_ONLY,$includeUrls = TRUE) {
			$pt = ProjectTemplate::getTemplate($this->templateId);
			if (!is_null($pt)){
				$documents=$pt->getDocumentTemplates($filter,$includeUrls);
			}
			else {
				//echo "PT is null. ".$this->projecttemplateid;
				return array();
			}
			//dumparray($documents);
			
			return $documents;
		}

		/**
		 * gets a list of all documents in the simulation
		 */
		function GetDocuments() {
			global $database;
			$docs = array();
			$folders = $this->GetFolders();
			foreach ($folders as $f) {
				$query = sprintf("SELECT * FROM documents WHERE folderid=%s ".
								"AND deleted=0",
								$f->folderid);
				$results = $database->queryAssoc($query);
				foreach ($results as $r) {
					$docs[] = new Document($r);
				}
			}
			return $docs;
		}

		var $permissions = null;
		/**
		 * Retrieves the permissions that a user has for a project.
		 * 
		 * @param integer $userId Internal ID of the user to check.
		 * @return array Array containing permissions 
		 */
		function GetProjectPermissions($userId) {
			global $database;
			//update permissions from db.
			trace(sprintf('Retrieving Permissions for Project <strong>%s</strong> from Database',$this->Name));
			$permissions = Project::GetUserProjectPermissions($userId,$this->id);
			dumpArray($permissions,'GetProjectPermission dump');
			return $permissions;
		}
		/**
		 * Returns an array of all users and groups that have permissions on the project.
		 * @return array
		 */
		function GetUsersAndGroups() {
			global $database;
			$ugs = array();
			$sql = sprintf("SELECT * FROM projectpermissions WHERE projectid=%s AND deleted=0",
							$this->id);
			$results = $database->queryAssoc($sql);
			foreach ($results as $r) {
				if ($r['usertype']=='user') {
					$sql = sprintf("SELECT displayname AS name,superadmin FROM users WHERE userid=%s",$r['userid']);	
				} else {
					$sql = sprintf("SELECT name FROM usergroups WHERE groupid=%s",$r['userid']);
				}
				$results2 = $database->queryAssoc($sql);
				if (($r['usertype']!='user') || ($results2[0]['superadmin']!=ALLOW)) {
					$ugs[] = array('id' => $r['userid'],
								'type' =>$r['usertype'],
								'name' => $results2[0]['name'],
								'UseStaffTools' => $r['usestafftools'],
								'DeleteAnyItem' => $r['deleteanyitem'],
								'DeleteItem'=> $r['deleteitem'],
								'ViewItem'=> $r['viewitem'],
								'AddItem'=> $r['additem'],
								'EditItems'=> $r['edititems'],
								'EditAnyItem'=> $r['editanyitem'],
								'StopProject'=> $r['stopproject'],
								'EditPlugin'=>$r['editplugin'],
								'ChangeUserPermissions'=>$r['changeuserpermissions']);				
				}
			}				
			$sql = "SELECT userid, displayname FROM users WHERE superadmin=1";
			$results = $database->queryAssoc($sql);
			foreach ($results as $r) {
				$ugs[] = array('id' => $r['userid'],
								'type' =>'user',
								'name' => $r['displayname'],
								'UseStaffTools' => ALLOW,
								'DeleteAnyItem' => ALLOW,
								'DeleteItem'=> ALLOW,
								'ViewItem'=> ALLOW,
								'AddItem'=> ALLOW,
								'EditItems'=> ALLOW,
								'EditAnyItem'=> ALLOW,
								'StopProject'=> ALLOW,
								'EditPlugin'=> ALLOW,
								'ChangeUserPermissions'=> ALLOW);
			}
			return $ugs;
		}
		/**
		 * Gets the value of a specific Simulation Permission for a given user.
		 * 
		 * @param string $permissionName Name of the Permission to check
		 * @param integer $userId Internal ID of the user to check.
		 * @param integer The value set for the permission.
		 */
		function GetProjectPermission($permissionName,$userId){
			
			$permissions = $this->GetProjectPermissions($userId);
			return (integer) $permissions[$permissionName];
		}
		/**
		 * Returns the value of a specific BP permission.
		 * 
		 * @param string $permissionName The Name of the Permission
		 * @param int $userId The Internal ID of the user to check the permission of.
		 * @return integer A true/false based on the permission.
		 */
		function GetTemplatePermission($permissionName,$userId) {
			//echo "Getting Permission: $permissionName";
			$t = $this->GetProjectTemplate();
			
			if (is_null($t)){
				return NOTSET;//die ('GetTemplatePermission returned null');
			}
			return (integer)$t->GetPermission($permissionName,$userId);
			
		}
		/**
		 * @return 
		 */
		function GetCalendar(){
			return CalendarItem::GetItems($this->id);
		}
		public $members = null;
		/**
		 * Gets an array of all the users doing this project 
		 * (not including staff members)
		 * @return array Array of User objects representing Users doing the project.
		 */
		function getMembers($AllMembers=false){
			if (is_null($this->members)) {
				trace('Retriving member list for first time from Database.');
				$memberList = array();
				$members = Project::GetProjectUsers($this->id);
				foreach ($members as $m) {
				 if ($AllMembers){ 
				  $memberList[] = $m;
				}
				else {
          if (!$m->isProjectStaff($this->id,true)) { 
						$memberList[] = $m;
					}
				}
			}
			$this->members = $memberList;
		}
		else {
			trace('Re-using member list');
		}

		return $this->members;
	}
		

		/**
		 * Serialises variables into a format for storage in the database.
		 */
		function SerialiseVariables() {
			$out = "";
			foreach($this->Variables as $Name=>$Value){
				if ($Name != '') {
					$out.="$Name=". base64_encode($Value)."|";
				}
			}
			return $out;
		}
		/**
		 *
		 */
		 /*
		function DeserialiseVariables($data) {
			$props = explode("|",$data);
 			$vars = array();
			foreach($props as $prop){
				$p = explode("=",$prop);
				$vars[$p[0]]= $p[1];
 			}
 			return $vars;
		}
		*/
		/**
		 * Returns a variabliser object for the simulation.
		 * 
		 * @return Variabliser A Variabliser object for the simulation. 
		 */
		function GetVariabliser() {
			$vb = new Variabliser($this,$this->Variables);
			return $vb;
		}
		/**
		 * Sets the project to inactive.
		 */
		function Archive(){
			global $database;
			$this->IsActive= false;
			$this->Save();
			
			$query = sprintf("SELECT * FROM projectpermissions ".
							"WHERE usertype='group' ".
							"AND projectid=%s",
							$this->id);
			$results = $database->queryAssoc($query);
			foreach ($results as $r) {
				// get all users
				$query = sprintf("SELECT * FROM user2usergroup WHERE groupid=%s",$r['userid']);
				$results2 = $database->queryAssoc($query);
				foreach ($results2 as $r2) {
					// insert view entry for each user 
					//(existing entries are updated below)
					$query = sprintf("SELECT COUNT(*) as numentries FROM projectpermissions ".
									"WHERE usertype='user' AND userid=%s AND projectid=%s",
									$r2['userid'],
									$this->id);
					$results3 = $database->queryAssoc($query);
					if ($results3[0]['numentries']==0) {
						$query = sprintf("INSERT INTO projectpermissions(userid,usertype,projectid,".
										"usestafftools,deleteanyitem,deleteitem,viewitem,additem,".
										"editanyitem,edititems,stopproject,editplugin,".
										"changeuserpermissions,deleted) VALUES ".
										"(%s,'user',%s,0,0,0,1,0,0,0,0,0,0,0)",
										$r2['userid'],
										$this->id
										);
						$result = $database->execute($query);
					} else {
						// update user permissions so that can only view
						$query = sprintf("UPDATE projectpermissions SET deleted=0,".
							"usestafftools=0,deleteanyitem=0,deleteitem=0,".
							"viewitem=1,additem=0,editanyitem=0,edititems=0,".
							"stopproject=0,editplugin=0,changeuserpermissions=0 ".
							"WHERE usertype='user' AND userid=%s ".
							"AND projectid=%s",
							$r2['userid'],
							$this->id);
						$result = $database->execute($query);	
					}
				}
				// delete group entry
				$query = sprintf("UPDATE projectpermissions SET deleted=1 ".
								"WHERE usertype='group' AND userid=%s AND projectid=%s",
								$r['userid'],
								$this->id);
				$result = $database->execute($query);
			}
														
			// update user permissions so that can only view
			$query = sprintf("UPDATE projectpermissions SET deleted=0,".
							"usestafftools=0,deleteanyitem=0,deleteitem=0,".
							"viewitem=1,additem=0,editanyitem=0,edititems=0,".
							"stopproject=0,editplugin=0,changeuserpermissions=0 ".
							"WHERE usertype='user' ".
							"AND projectid=%s",
							$this->id);
			$result = $database->execute($query);									
		}
		
		function Delete() {
			global $database;
			$this->IsActive= false;
			$this->Deleted=SIMSTATE_DELETED;
			$this->Save();
			
			// delete project permissions etc.
			$query = sprintf("UPDATE projectpermissions SET deleted=1 WHERE projectid=%s",
							$this->id);
			$result = $database->execute($query);
			
			// project progress
			$query = sprintf("UPDATE projectprogress SET deleted=1 WHERE projectid=%s",
							$this->id);
			$result = $database->execute($query);
			
			// project variables
			$query = sprintf("UPDATE projectvariables SET deleted=1 WHERE projectid=%s",
							$this->id);
			$result = $database->execute($query);
			
			// calendar
			$query = sprintf("SELECT id FROM calendar WHERE projectid=%s",
							$this->id);
			$results = $database->queryAssoc($query);
			foreach ($results as $r) {
				// calendar assignments
				$query = sprintf("UPDATE calendarassignments SET deleted=1 ".
							"WHERE calitemid=%s",
							$r['id']);
				$result = $database->execute($query);
			}	
			
			$query = sprintf("UPDATE calendar SET deleted=1 ".
							"WHERE projectid=%s",
							$this->id);						
			//Llook at this again in the future. we're just orphaning records at the momemnt.
			$result = $database->execute($query);

			// directory
			$query = sprintf("UPDATE directory SET deleted=1 WHERE projectid=%s",
							$this->id);
			$result = $database->execute($query);
			
			// folders
			$query = sprintf("SELECT folderid from folders WHERE projectid=%s",
						$this->id);
			$results = $database->queryAssoc($query);
			foreach ($results as $folder) {
				// get documents in folders
				$query = sprintf("SELECT documentuid FROM documents WHERE folderid=%s",
							$folder['folderid']);
				$results2 = $database->queryAssoc($query);
				foreach ($results2 as $doc) {
					// get initial comments 
					$query = sprintf("SELECT commentid FROM commentary WHERE itemtype='doc' ".
								"AND itemid=%s",$doc['documentuid']);
					$results3 = $database->queryAssoc($query);				
					foreach ($results3 as $com) {
						// delete child comments and then the root comment 
						Project::DeleteChildComments($com['commentid']);
						$query = sprintf("UPDATE commentary SET deleted=1 WHERE commentid=%s",
							$com['commentid']);
						$result = $database->execute($query);
						if ($result !== true) {
							die($result);
						}	
					}
					// 	delete document
					$query = sprintf("UPDATE documents SET deleted=1 WHERE documentuid=%s",$doc['documentuid']);
					$result = $database->execute($query);
					if ($result !== true) {
						die($result);
					}
				}					
				// delete folder
				$query = sprintf("UPDATE folders SET deleted=1 WHERE folderid=%s", $folder['folderid']);
				$result = $database->execute($query);
				if ($result !== true) {
					die($result);
				}	
			}				
			
			// projectplugins
			$query = sprintf("UPDATE projectplugins SET deleted=1 WHERE projectid=%s",
							$this->id);
			$result = $database->execute($query);
		}
		function GetName() {
			return $this->Name . ' ('.$this->TemplateName.')';
		}
		/**
		* Gets the project object from the database.
		* 
		* This function will return inactive projects.
		* @param int $projectId The Id of the Projec to retrieve.
		* @static
		*/
		static function GetProject($projectId) {
			global $database,$project;
			//turn TestObject::getProject($projectId);
			if (is_numeric($projectId)) {

				if (!is_null($project) && $projectId == $project->id) {
					trace("Reusing Global Project Object");
/*
* DO NOT RE-ENABLE THE NEXT LINE
* Seems to cause a hissy fit! when appended to the "trace" above!
					* .traceArray(debug_backtrace()));
*/
					return $project;
				}
				trace("Retrieving project object from database");//.traceArray(debug_backtrace()));
				$sql = "SELECT * FROM projects WHERE projectuid = $projectId";

				$results = $database->queryAssoc($sql);
				//ho "<p>SQL:$sql</p>";
				//echo "There are ". count($results) ." projects with that ID";
				if (count($results)>0) {

//print_r($results);
					$p = new Project($results[0]);
					return $p;
				}
			}
			else {
			 
				trace("getProject(): ProjectID was not a number");
			}
			return null;
		}
/**
 * Gets a list of administrable projects for the specified user.
 * 
 * Adds ProjectUserPermissions property to the Project objects. This contains
 * the user's permissions on the project and means we don't have to keep querying
 * the database when we display the information.
 */
		static function GetAdministerableProjects($userId) {
			global $database,$project;
			$user = User::RetrieveUser($userId);
			if (is_null($user)) {
				DisplayMessage('Unable to display administerable projects for '.$userId .'. ID returned null user.');
			}
			$user->GetSitewidePermissions();
			//if ($user->sitewidePermissions['SuperAdmin']) {
				//we can administer any project!
			//	$sql='SELECT p.* ' .
			//		'FROM projects p';
			//}
			//else {
				
			if ($user->superadmin==ALLOW) {
				$sql = "SELECT * FROM projects WHERE deleted=0 ORDER BY projecttemplateid asc,name asc";
			} else {
				$sql=sprintf('SELECT p.* ' .
					'FROM projects p, projectpermissions pp ' .
					'WHERE p.projectuid = pp.projectid ' .
					'AND pp.userid =%s ' .
					'AND pp.deleted=0 '.
					'AND p.deleted=0 ' .
					'AND p.IsActive=1 order by projecttemplateid asc,name asc',
					$userId
					);
			}
			//}
			//echo $sql;
			trace("GetAdministerableProjects().$sql");
			$results = $database->queryAssoc($sql);
			
			$projects = array();
			if (count($results)>0) {
				foreach($results as $result){
					$p= new Project($result);
					$pt = $p->GetProjectTemplate();
					//$p->ptId = $pt->projecttemplateuid;
					if (!is_null($pt)) {
  					$p->ptName = $pt->Name;
  					/**
  					 * THIS IS A HACK!!!!
  					 * Should find a better way to do this!
  					 */
  					$p->ProjectUserPermissions =$p->GetProjectPermissions($userId);
  					//print_r($p->ProjectUserPermissions);
					}
					$projects[$p->id] = $p;
				}
			}
			return $projects;	
		}
		
		static function DeleteProject($projectId) {
			$project = Project::GetProject($projectId);
			if (!is_null($project)) {
				return $project->Delete();
			}
			return false;
		}
		static function ArchiveProject($projectId) {
			$project = Project::GetProject($projectId);
			if (!is_null($project)){
				$project->Archive();
			}
		}
		static function GetProjects($restrict='') {
			global $database,$project;
			$sql = "SELECT * FROM projects p WHERE p.isactive=1 AND p.deleted=0";
			if ($restrict != "") {
				$sql.=" $restrict";
			}
			//echo $sql;
			$results = $database->queryAssoc($sql);
			//die("GetProjects()>$sql");
			$projects = array();
			if (count($results)>0) {
				foreach($results as $result){
					$projects[] = new Project($result);
				}
			}
			return $projects;
		}

	/**
	 * returns list of all project names indexed by id
	 */
		static function GetProjectsList($restrict='') {
			global $database,$project;
			$sql = "SELECT projectuid, name FROM projects p WHERE p.isactive=1 AND p.deleted=0";
			if ($restrict != "") {
				$sql.=" $restrict";
			}
			//echo $sql;
			$results = $database->queryAssoc($sql);
			//die("GetProjects()>$sql");
			$projects = array();
			if (count($results)>0) {
				foreach($results as $result){
					$projects[$result['projectuid']] = $result['name'];
				}
			}
			return $projects;			
		}

		/**
		 * Gets a list of permissions for a specified project and user
		 * Does this by:
		 * 1. get users individual permissions or default to null if no record exists
		 * 2. get users groups and go through each one, ANDing to get permissions
		 * 3. default group permissions to false if not yet set (i.e. if no group record exists)
		 * 4. resolve user and group permissions (i.e. if individual permissions are null, 
		 * take group permissions)
		 */
		static function GetUserProjectPermissions($userid,$projectId){
			global $database;
			$userProjectPermissions = User::GetIndividualProjectPermissions($userid, $projectId);
			trace("GetUserProjectPermissions called with ".$userid." and ".$projectId.": <pre>".print_r($userProjectPermissions, true)."</pre>");
			$groupProjectPermissions = array();	
			$user = new User($userid);
			$groups = $user->GetGroups();
			foreach ($groups as $group) {
				$query = sprintf("SELECT * FROM projectpermissions pp, projects p ".
								"WHERE pp.userid=%s ".
								"AND pp.projectid = p.projectuid ".
								"AND pp.usertype='group' " .
								"AND pp.deleted=0 ".
								"AND p.deleted=0", $group->id);
		/*		$query = sprintf(
        "select * from projectpermissions pp left join user2usergroup u2ug on pp.userid=groupid where u2ug.userid=%s"
        ,$userid);
        */
				
				$results = $database->queryAssoc($query);
				// loop through all groups and AND results
				foreach ($results as $r) {
					if (isset($groupProjectPermissions['UseStaffTools'])) {
						$groupProjectPermissions['UseStaffTools'] = 
						($groupProjectPermissions['UseStaffTools'] && $r['usestafftools'])? 1:0;
					} else {
						$groupProjectPermissions['UseStaffTools'] = $r['usestafftools'];
					}
					if (isset($groupProjectPermissions['DeleteAnyItem'])) {
						$groupProjectPermissions['DeleteAnyItem'] = 
						($groupProjectPermissions['DeleteAnyItem'] && $r['deleteanyitem'])? 1:0;
					} else {
						$groupProjectPermissions['DeleteAnyItem'] = $r['deleteanyitem'];
					}
					if (isset($groupProjectPermissions['DeleteItem'])) {
						$groupProjectPermissions['DeleteItem'] = 
						($groupProjectPermissions['DeleteItem'] && $r['deleteitem'])? 1:0;
					} else {
						$groupProjectPermissions['DeleteItem'] = $r['deleteitem'];
					}
					if (isset($groupProjectPermissions['ViewItem'])) {
						$groupProjectPermissions['ViewItem'] = 
						($groupProjectPermissions['ViewItem'] && $r['viewitem'])? 1:0;
					} else {
						$groupProjectPermissions['ViewItem'] = $r['viewitem'];
					}
					if (isset($groupProjectPermissions['AddItem'])) {
						$groupProjectPermissions['AddItem'] =
						($groupProjectPermissions['AddItem'] && $r['additem'])? 1:0;
					} else {
						$groupProjectPermissions['AddItem'] = $r['additem'];
					}
					if (isset($groupProjectPermissions['EditAnyItem'])) {
						$groupProjectPermissions['EditAnyItem'] =
						($groupProjectPermissions['EditAnyItem'] && $r['editanyitem'])? 1:0;
					} else {
						$groupProjectPermissions['EditAnyItem'] = $r['editanyitem'];
					}		
					if (isset($groupProjectPermissions['EditItems'])) {
						$groupProjectPermissions['EditItems'] =
						($groupProjectPermissions['EditItems'] && $r['edititems'])? 1:0;
					} else {
						$groupProjectPermissions['EditItems'] = $r['edititems'];
					}
					if (isset($groupProjectPermissions['StopProject'])) {
						$groupProjectPermissions['StopProject'] =
						($groupProjectPermissions['StopProject'] && $r['stopproject'])? 1:0;
					} else {
						$groupProjectPermissions['StopProject'] = $r['stopproject'];
					}							
					if (isset($groupProjectPermissions['ChangeUserPermissions'])) {
						$groupProjectPermissions['ChangeUserPermissions'] =
						($groupProjectPermissions['ChangeUserPermissions'] && $r['changeuserpermissions'])? 1:0;
					} else {
						$groupProjectPermissions['ChangeUserPermissions'] = $r['changeuserpermissions'];
					}	
					if (isset($groupProjectPermissions['EditPlugin'])) {
						$groupProjectPermissions['EditPlugin'] =
						($groupProjectPermissions['EditPlugin'] && $r['editplugin'])? 1:0;
					} else {
						$groupProjectPermissions['EditPlugin'] = $r['editplugin'];
					}						
					// default group permissions to false if not yet set
					if (!isset($groupProjectPermissions['UseStaffTools'])) {
						$groupProjectPermissions['UseStaffTools'] = 0;
					}	
					if (!isset($groupProjectPermissions['DeleteAnyItem'])) {
						$groupProjectPermissions['DeleteAnyItem'] = 0;
					}		
					if (!isset($groupProjectPermissions['DeleteItem'])) {
						$groupProjectPermissions['DeleteItem'] = 0;
					}		
					if (!isset($groupProjectPermissions['ViewItem'])) {
						$groupProjectPermissions['ViewItem'] = 0;
					}		
					if (!isset($groupProjectPermissions['AddItem'])) {
						$groupProjectPermissions['AddItem'] = 0;
					}		
					if (!isset($groupProjectPermissions['EditAnyItem'])) {
						$groupProjectPermissions['EditAnyItem'] = 0;
					}		
					if (!isset($groupProjectPermissions['EditItems'])) {
						$groupProjectPermissions['EditItems'] = 0;
					}		
					if (!isset($groupProjectPermissions['StopProject'])) {
						$groupProjectPermissions['StopProject'] = 0;
					}							
					if (!isset($groupProjectPermissions['ChangeUserPermissions'])) {
						$groupProjectPermissions['ChangeUserPermissions'] = 0;
					}		
					if (!isset($groupProjectPermissions['EditPlugin'])) {
						$groupProjectPermissions['EditPlugin'] = 0;
					}						
					// resolve user and group permissions - if user permissions are null, 
					// take the group permissions
					if (!isset($userProjectPermissions['UseStaffTools'])) {
						$userProjectPermissions['UseStaffTools'] = 
							$groupProjectPermissions['UseStaffTools'];
					}
					if (!isset($userProjectPermissions['DeleteAnyItem'])) {
						$userProjectPermissions['DeleteAnyItem'] = 
							$groupProjectPermissions['DeleteAnyItem'];
					}
					if (!isset($userProjectPermissions['DeleteItem'])) {
						$userProjectPermissions['DeleteItem'] = 
							$groupProjectPermissions['DeleteItem'];
					}
					if (!isset($userProjectPermissions['ViewItem'])) {
						$userProjectPermissions['ViewItem'] = 
							$groupProjectPermissions['ViewItem'];
					}
					if (!isset($userProjectPermissions['AddItem'])) {
						$userProjectPermissions['AddItem'] = 
							$groupProjectPermissions['AddItem'];
					}
					if (!isset($userProjectPermissions['EditAnyItem'])) {
						$userProjectPermissions['EditAnyItem'] = 
							$groupProjectPermissions['EditAnyItem'];
					}
					if (!isset($userProjectPermissions['EditItems'])) {
						$userProjectPermissions['EditItems'] = 
							$groupProjectPermissions['EditItems'];
					}
					if (!isset($userProjectPermissions['StopProject'])) {
						$userProjectPermissions['StopProject'] = 
							$groupProjectPermissions['StopProject'];
					}					
					if (!isset($userProjectPermissions['ChangeUserPermissions'])) {
						$userProjectPermissions['ChangeUserPermissions'] = 
							$groupProjectPermissions['ChangeUserPermissions'];
					}
					if (!isset($userProjectPermissions['EditPlugin'])) {
						$userProjectPermissions['EditPlugin'] = 
							$groupProjectPermissions['EditPlugin'];
					}					
				}
			} // end for each group
			trace("GetUserProjectPermissions called with ".$userid." and ".$projectId.": <pre>".print_r($userProjectPermissions, true)."</pre>");
			return $user->ApplySitePermissions($userProjectPermissions);
		}
        

		/**
		 * Crates a project using the $ProjectTemplate object supplied.
		 * Basically copies all the templates properties.
		 * @param ProjectTemplate $projectTemplate A ProjectTemplate object to use as a basis.
		 * @param string $name An optional name. This will override the ProjectTemplate's name.
		 */
		static function createProject($projectTemplate,$name = null) {
			trace("Creating New project from template");
			$project = new Project(
				null,
				$projectTemplate->getProperty("projecttemplateuid"),
				(is_null($name)?$projectTemplate->getProperty("name"):$name)
			);
			$project->saveProject();
		}
		/**
		 * Gets a list of users on a specified project (including those as part of a group)
		 * @param int $projectId Unique ID of the project.
		 * @return array Array of User objects.
		 */
		static function GetProjectUsers($projectId){
			global $database;

			$sql = sprintf("SELECT userid, usertype " .
					"FROM projectpermissions " .
					"WHERE projectid=%s " .
					"AND deleted=0",
					$projectId
					)
					;
			$results = $database->queryAssoc($sql);
			//dumparray($results);
			//echo $sql.'<br>';
			$out = array();
			foreach($results as $result){
				if ($result['usertype']=='user') {
					$u = User::RetrieveUser($result['userid'],TRUE);
					//	print_r($u);
					if ($u->displayName=='') {
						$u->displayName= 'User #'.$result['userid'];
					}
					$out[$u->id] = $u;
				} else {
					// get and add users in group (that are not already in list)
					$sql = sprintf("SELECT userid FROM user2usergroup ".
								"WHERE groupid=%s ".
								"AND deleted=0",
								$result['userid']);
					$results2 = $database->queryAssoc($sql);
					foreach ($results2 as $result2) {			
						if (!array_key_exists($result2['userid'],$out)) {
							$u = User::RetrieveUser($result2['userid'],TRUE);
if (!is_null($u)) {
							if ($u->displayName=='') {
								$u->displayName= 'User #'.$result2['userid'];
							}
							$out[$u->id] = $u;
}
						}
					}
				}
			}

			return $out;
		}

	/**
	 * Asks whether a user has any administrative rights on a project.
	 * @param int $userId
	 * @param int $projectId
	 * @return boolean
	 */
	public static function IsProjectAdministrator($userId, $projectId) {
		$allowed = false;
		$permissions = Project::GetUserProjectPermissions($userId,$projectId);
		if ($permissions['UseStaffTools']==1 ||
			$permissions['DeleteAnyItem']==1 ||
			$permissions['EditAnyItem']==1 ||
			$permissions['StopProject']==1 ||
			$permissions['ChangeUserPermissions']==1 ||
			$permissions['EditPlugin']) {
			$allowed = true;		
		}
		$projectTemplateUid = Project::GetProjectTemplateId($projectId);
		$templatePermissions = ProjectTemplate::GetUserProjectTemplatePermissions($userId,$projectTemplateUid); 
		if ($templatePermissions['StartProject']==1 ||
			$templatePermissions['EndProject']==1 ||
			$templatePermissions['ArchiveProject']==1 ||
			$templatePermissions['EditTemplate']==1 ||
			$templatePermissions['ChangeUserPermissions']==1 ||
			$templatePermissions['EditPlugin'] ) {
			$allowed = true;
		}
		return $allowed;
	}
	
	/**
	 * returns the template id of a project from the database
	 * @param int $projectId
	 * @return int templateId
	 */
	public static function GetProjectTemplateId($projectId) {
		global $database;
		$sql = sprintf("SELECT projecttemplateid FROM projects WHERE projectuid=%s AND deleted=0",$projectId);
		$results = $database->queryAssoc($sql);		
		return $results[0]['projecttemplateid'];
	}

	/**
	 * return the total number of projects in the system
	 */
	 public static function GetSimulationCount() {
	 		global $database;
	 		$sql = sprintf("SELECT count(*) AS totalsims FROM projects WHERE deleted=0");
	 		$results = $database->queryAssoc($sql);
	 		return $results[0]['totalsims'];
	 }
	 
	 /**
	  * delete a project from the database
	  */
	
/*	public static function DeleteProject($projectId) {
		global $database;
		$sql = sprintf("UPDATE projects SET deleted=1 WHERE projectUid=%s",
						$projectId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}
		$sql = sprintf("UPDATE projectvariables SET deleted=1 WHERE projectid=%s",
						$projectId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}
		$sql = sprintf("UPDATE calendar c, calendarassignments ca SET deleted=1 ".
						"WHERE c.projectId=%s ".
						"AND c.id=ca.calItemId",
						$projectId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}		
		$sql = sprintf("UPDATE projectplugins SET deleted=1 WHERE projectId=%s",
						$projectId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}		
		$sql = sprintf("UPDATE directory SET deleted=1 WHERE projectId=%s",
						$projectId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}		
		// get folders
		$sql = sprintf("SELECT folderId from folders WHERE projectId=%s," .
						$projectId);
		$results = $database->queryAssoc($sql);
		foreach ($results as $folder) {
			// get documents in folders
			$sql = sprintf("SELECT documentUid FROM documents WHERE folderId=%s",
							$folder);
			$results2 = $database->queryAssoc($sql);
			foreach ($results as $doc) {
				// get initial comments 
				$sql = sprintf("SELECT commentId FROM commentary WHERE itemType='doc' ".
								"AND itemID=%s",$doc);
				$results3 = $database->queryAssoc($sql);
				foreach ($results3 as $com) {
					// delete child comments and then the root comment 
					Project::DeleteChildComments($com);
					$sql = sprintf("UPDATE commentary SET deleted=1 WHERE commentId=%s",
							$com);
					$result = $database->execute($sql);
					if ($result !== true) {
						die($result);
					}	
				}
				// delete document
				$sql = sprintf("UPDATE documents SET deleted=1 WHERE documentId=%s",$doc);
				$result = $database->execute($sql);
				if ($result !== true) {
					die($result);
				}
			}
			// delete folder
			$sql = sprintf("UPDATE folders SET deleted=1 WHERE folderId=%s", $folder);
			$result = $datbase->execute($sql);
			if ($result !== true) {
				die($result);
			}	
		}
		$sql = sprintf("UPDATE projectpermissions SET deleted=1 WHERE projectid=%s",
						$projectId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}		
	}*/
	
	/**
	 * deletes all child comments of a comment (recursive) 
	 * @param int $comId the id of the parent comment
	 */
	public static function DeleteChildComments($comId) {
		global $database;
		$sql = sprintf("SELECT commentId FROM commentary WHERE itemType='com' AND itemID=%s",
									$comId);
		$results = $database->queryAssoc($sql);
		foreach ($results as $r) {
			Project::DeleteChildComments($r);
			$sql = sprintf("UPDATE commentary SET deleted=1 WHERE commentId=%s",$r);
			$result = $database->execute($sql);
		}
	}
	
}
?>
