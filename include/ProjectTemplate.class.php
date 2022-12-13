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
  * Represents the Blueprint for a Project.
  *
  * A ProjectTemplate consists of lots
  * different bits of information. The instantiation of a Project requires bits of
  * information to be copied and created etc..
  *
  * @tutorial tle2/DatabaseObjects.pkg
  * @tutorial tle2/ProjectTemplate.cls
  */
 	class ProjectTemplate{

		/**
		* The Unique Identifier of the ProjectTemplate. This is only unique on a
		* single installation.
	 	*/
 		//var $projecttemplateuid= -1;
 		public $id = -1;
 		/**
 		 * The name of the Project Template
 		 */
 		var $Name ="";
		public $IsActive = 0;
 		/**
 		 *
 		 */
 		 var $Properties = array();
		public $ContainerName = 'Not set';
		public $ContainerId = -1;
		public $Stylesheet='';
 		 /**
 		  *
 		  */
 		  var $Variables = array();
 		/**
 		 * @param integer $projectTemplateId ID of a project template to retrieve from the DB.
 		 * @param array $dbRow an Array containing the ProjectTemplate's properties.
 		 */
 		function __construct( $dbRow = null){
 			global $database;


 			if (!is_null($dbRow)) {
 			foreach($dbRow as $key=>$value) {
       //echo "$key=".substr($value,0,20).'<br/>';
       }
 				$this->id = $dbRow['projecttemplateuid'];
 				$this->Name = $dbRow['name'];
 				$this->Properties = DeserialiseArray($dbRow['properties'],true);
 				$this->Variables = DeserialiseArray($dbRow['variables'],true);
				$this->IsActive = (bool)$dbRow['isactive']; 				
 				if (!isset($this->Variables['CHAR_PLAYER'])){
 					//echo "PLayer property is not set";
 					$this->Variables['CHAR_PLAYER'] ="<Please Set This>";
 				}
 				$this->Stylesheet = $dbRow['stylesheet'];
 				if ($this->Stylesheet=='\''){
          $this->Stylesheet='';
        }
 				//echo 'ContainerID:'.$dbRow['cid'].'<br/>';
 				$this->ContainerId = isset($dbRow['cid'])?$dbRow['cid']:-1;
 				$this->ContainerName = isset($dbRow['cname'])?$dbRow['cname']:'';
 			}
 			//print_r($dbRow);
 		}
 		/**
 		 * Saves the PT back to the database.
 		 */
 		function Save() {
 			global $database,$config,$metrics;
 			
 			$sql = '';
 			
 			if ($this->id == -1) {
 				//insert
 				$sql = sprintf(
 						'INSERT INTO projecttemplates ' .
 						'(name, properties, variables,stylesheet,deleted,isactive,container) ' .
 						'VALUES ' .
 						'()',
 						$database->quote($this->Name),
						$database->quote(SerialiseArray($this->Properties)),
						$database->quote(SerialiseArray($this->Variables)),
						$database->quote($this->Stylesheet),
						0,
						1,
						$this->ContainerId
 					);
					
 			} else {
 				//update;
 				$sql = sprintf(
						'UPDATE projecttemplates SET ' .
						'name = \'%s\', ' .
						'properties = \'%s\', ' .
						'variables = \'%s\', ' .
						'stylesheet = \' %s \' '.
						'WHERE ' .
						'projecttemplateuid = %s',
						$database->quote($this->Name),
						$database->quote(SerialiseArray($this->Properties)),
						$database->quote(SerialiseArray($this->Variables)),
						$database->quote($this->Stylesheet),
						$this->id
 					);
					
 				$database->execute($sql);
 			}
 		}
		/**
		* Instantiates this project template to create a template that can have users assigned to it.
		*/
		/*
		function createProject() {
			global $database,$_PLUGINS;
			$sql = "INSERT INTO projects (projectTemplateId, Name) VALUES " .
					"($this->databaseRow['projectTemplateUid'])" .
					"($this->databaseRow['Name'])";
			$database->execute($sql);
			$_PLUGINS->trigger('onCreateProject',array($this->databaseRow['projectTemplateUid']));
		}*/
		/**
		 * Returns a property value from the project template.
		*/
		function getProperty($propName) {
			return $this->databaseRow[$propName];
		}

		/**
		 * gets an array containing the meta information about the project's document templates.
		 * SHOULD NOT QUERY THE content FIELD of the db for speed.
		 */
		 function getDocumentTemplates($filter = PLAYER_TEMPLATES_ONLY,$includeUrls = TRUE) {
			global $database;
			$pid = $this->id;//$this->getProperty("projecttemplateuid");
			//echo "PID:".$pid;
			$sql = '';
			$sql = "SELECT doctemplateuid,projecttemplateid,documentid,visiblename,filename,contenttype,creatoruserid FROM documenttemplates WHERE projecttemplateid = $pid ";
			if ($filter == PLAYER_TEMPLATES_ONLY) {
                $sql.= "AND playercansee = 1 ";
            }

			if ($includeUrls==FALSE) $sql.= "AND contenttype<>'url' ";
			$sql.= " order by filename ASC";			
			
//			/echo $sql;
			//echo ('GetDocument Templates: '.$filter.':'.$sql);
			$result = $database->queryAssoc($sql);
			
			return $result;
		}
		
		/** 
		 * gets a url resource by its id
		 * 
		 */
		 static function getUrlResourceById($id) {
		 	global $database;
		 	$sql = sprintf("SELECT content FROM documenttemplates WHERE ".
		 					"doctemplateuid=%s AND contenttype='url'",
		 					$id);
		 	$results = $database->queryAssoc($sql);
		 	return $results[0]['content'];	
		 }
		 
		 
		function GetDocumentTemplateFromName($documentTemplateName) {
      global $database;
			$sql2 = sprintf(
				'SELECT doctemplateuid ' .
				'FROM documenttemplates ' .
				'WHERE filename = \'%s\' AND ' .
				'projecttemplateid =%s',
				$documentTemplateName,
				$this->id
			);
			//echo $sql2;
			$results_with_content= $database->queryAssoc($sql2);
			//print_r($results_with_content);
			
			if (count($results_with_content)>0) {
				$document = $results_with_content[0]['doctemplateuid'];
				return $document;
			}
			return null;
    }
		/**
		 * Gets a Document Template using the DocumentId value.
		 * @param $documentId the ID as defined in the N.E.D. / PT. (not the UID as installed in the system!);
		 */
		function GetDocumentTemplateIdFromDocumentId($documentId){
			global $database;
			$sql2 = sprintf(
				'SELECT doctemplateuid ' .
				'FROM documenttemplates ' .
				'WHERE documentid = \'%s\' AND ' .
				'projecttemplateid =%s',
				$documentId,
				$this->id
			);
			//echo $sql2."<br/>";
			$results_with_content= $database->queryAssoc($sql2);
			//print_r($results_with_content);
			if (count($results_with_content)>0) {
				return $results_with_content[0]['doctemplateuid'];
			}
			return -1;
		}
		/**
		 * Checks that the BP has a structure, otherwise can't be created!
		 * and strictly shouldn't be installed!
		 */
		function HasStructure() {
			global $database;
			$eventSql = sprintf(
							'SELECT * FROM projectsequence WHERE projecttemplateid =%s',
							$this->id
						);
			$results = $database->queryAssoc($eventSql);
			if (count($results)>0){
				return true;
			}
			return false;
		}
		/**
		 *
		 */
		 function GetEvents() {
			global $database;
			$eventSql = sprintf(
							'SELECT * ' .
							'FROM projectsequence ' .
							'WHERE projecttemplateid =%s ' .
							'AND deleted=0',
							$this->id
						);
			
			$results = $database->queryAssoc($eventSql);
			//print_r($results);
			$startEventId =-1;
			$events = array();
			if (count($results)>0){
				foreach($results as $result) {
					//print_r($result);
          $events[$result['projecttemplateeventid']]= $result;
					//print_r($result);
					if ($result['previouseventid']=='0'){
						$startEventId = $result['projecttemplateeventid'];
					}
				}
				$orderedEventList = array();
				//echo "Start event id :$startEventId<br>"; 
				$events = $this->build($events,$startEventId,$orderedEventList);
				//die(print_r($events));
				return $events;
			}
			else {
				return array();
			}
		}
		private function build(&$eventList, $eventId,&$orderedEventList) {
			global $database;
			$event = (isset($eventList[$eventId])) ? $eventList[$eventId] : NULL;
			//echo 'Looking at event '. $eventId;
			
			$orderedEventList[] = $event;
			/*
			 * find out if this event has any children
			 */
			 $childrenSql = sprintf(
							'SELECT * ' .
							'FROM projectsequence ' .
							'WHERE projecttemplateid = %s ' .
							'AND ' .
							'itemtype = 3 AND ' .
							'previouseventid = \'%s\'',
							$this->id,
							$eventId
							);
			$children = $database->queryAssoc($childrenSql);
//			/echo $childrenSql .'<br>';
			//echo 'Count'.count($children);
			if (count($children)>0) {
				foreach($children as $child){
					//print_r($child);
					//echo '<br>';
					$resourcePId = $child['nexteventid'];
					$docUid = $this->GetDocumentTemplateIdFromDocumentId($resourcePId);
					$child['nexteventid'] = $docUid;
					//$child['nexteventid'] = $docUid;
					$orderedEventList[] = $child; 
				}
			}
			//echo 'Next Event Id'. $event['nexteventid'].'<br>';
			if ((isset($event['nexteventid'])) && ($event['nexteventid']!='0')) {
				//echo 'Doing recursion<br>';
				$this->build($eventList,$event['nexteventid'],$orderedEventList);
			}
			return $orderedEventList; 
		}
		/*
		function GetEvents() {
			global $database;
			$pEvents = array();
			$rawEvents = array();
			$sql = sprintf(
					"SELECT * FROM projectsequence WHERE projecttemplateid =%s",
					$this->id
			);
			$events = $database->queryAssoc($sql);
			$startEvent = 0;
//build a linked list of events!
			foreach($events as $key=>$event ){

				//dumpArray($event);
				if ($event['previouseventid'] =='0') {
					//echo "<br>Setting first event to ".$event['name']. ">".$key;
					$startEvent = $event['projecttemplateeventid'];
				}
				$rawEvents[$event['projecttemplateeventid']] = $event;
				//echo "<br>Event: $key:".$event['projecttemplateeventid'] ."=".$event['name'];
			}
			//dumpArray($rawEvents);
			//echo "<br>Starting at: $startEvent: ". $rawEvents[$startEvent]['name'];
			//dumpArray($rawEvents);
			$this->limit = count($events);
			$this->buildEvents($pEvents,$rawEvents,$startEvent);
			//echo "<br>";
			//dumpArray($pEvents);
			return $pEvents;
		}
		function buildEvents(&$stack,&$rawEventStack,$startEvent) {
			$this->limit--;
			//echo "<br>Limit:".$this->limit." Evalating #$startEvent:";
			//dumpArray($stack);
			if ($rawEventStack[$startEvent]['nexteventid'] =='0' ){
				//echo "End of List";
				return;//we've hit the end of the list
			}
			$stack[] = $rawEventStack[$startEvent];
			if ($this->limit<=0) {
				return;
			}
			$this->buildEvents($stack,$rawEventStack,$rawEventStack[$startEvent]['nexteventid']);
		}
		*/
		/**
		 * Retrieves a list of roles in the project Template
		 */
		function GetRoles(){

			global $database;
			$pRoles = array();
			$sql = sprintf(
					"SELECT * FROM projecttemplateroles WHERE projecttemplateid =%s",
					$this->id
			);
			//echo $sql;
			$roles= $database->queryAssoc($sql);

			foreach($roles as $role) {
				$pRoles[$role['projecttemplateroleid']] = $role;
			}
			//dumpArray($pRoles);
			return $pRoles;
		}

		/**
		 * Updates a single role for the Template.
		 * 
		 * @param string $roleName Name of the Role to update.
		 * @param array $Values Array containg the values to update.
		 */
		function UpdateRole($roleName,$Values){
			
		}
		function GetPermission($permissionName,$userId){
			//echo "Getting Template Permission: $permissionName";
			$permissions = ProjectTemplate::getUserTemplatePermissions($userId,$this->id);
			//print_r($permissions);
			if (isset($permissions[$permissionName])) {
			 return $permissions[$permissionName];
			}
			return NOTSET;
		}
		/**
		 * Creates a playable project, with the current user as a "admin" for it.
		 */
		function CreateProject($NewProjectName = "",$variables = null,$creatorId){
			global $database,$metrics;
			$project = new Project();
			$project->Name = $NewProjectName;
			
			/*
			 * Setup a variabliser to set the variables.
			 */
			$vb = new Variabliser($this->Name,$this->Variables);
			/*
			 *Then override the default variables with the user supplied ones.
			 */
			if (!is_null($variables)) {
				foreach($variables as $overrideVariable=>$value) {
					//we only include the variable if its name is not ''.
					if ($overrideVariable !='') {
					 $vb->Variables[$overrideVariable] = $value;
					}
				}
			}
			 /*
			  * Initialise the variables to the user's specified instructions
			  */
			$metrics->recordMetric('Initialising Variables',$this->Variables);
			//print_r($vb->Variables);
			$vb->InitialiseVariables();
			//print_r($vb->Variables);
			$metrics->recordMetric('Initialised Variables',$vb->Variables);
			/*
			 * Finally set them to the Project.
			 */
			$project->Variables = $vb->Variables;
			//print_r($project->Variables);
			$project->templateId = $this->id;
			$project->CreatorId = $creatorId;

			$NewProjectName = $vb->Substitute($this->Properties['projectNameFormat']);
			$project->Name = $NewProjectName;
			$project->saveProject();	//we have to save the project to get an ID.
			//print_r($project);
			if ($project->id <0) {
				trace("***Failed to save project to database");
				return;
			}
			
			trace("pId: ".$project->id);

/*
 * The Character entities need to be added to the directory, with their
 * names (which have been worked out already by the variabliser/creator).
 * 
 * We have to create a Directory Item for each character, set its name and role and project.
 * When a project is first created none of the DirectoryItems/Characters are linked to another 
 * project.
 */
 			/*
 			 * To setup the characters we need the information from the
 			 *  ProjectTemplateRoles table.
 			 * 
 			 * This table has all the info we need to create the projects.
 			 * 
 			 * The created characters get added to the Directory table.
 			*/
 			$roles_sql = sprintf('SELECT * FROM projecttemplateroles ' .
 			 		'WHERE ' .
 			 		'projecttemplateid = %s',
 			 		$this->id
 			 	);
 			trace($roles_sql);
 			$rolesResults = $database->queryAssoc($roles_sql);

 			foreach($rolesResults as $role){
// 				$vb <-varabliser to sub any variables in names/address etc.
				$metrics->recordMetric('CreateProject_CreateCharFromRole',$role);
				$char_name = $vb->Substitute($role['namerule']);
				if ($char_name=='' | is_null($char_name)) {
				$char_name = '';//$role['rolename'];
				//print_r($role);
				//die('character name not set');
			}
				$char_address=$vb->Substitute($role['addressrule']);
				$char_location = $vb->Substitute($role['locationrule']);
				$char_dirvisible = $vb->Substitute($role['directoryrule']);
        
				$char_props= $role['properties'];
				if (is_null($char_dirvisible) | $char_dirvisible =='') {
					$char_dirvisible = false;
				}
        
/*
				$char_name = $role['namerule'];
				$char_address=$role['addressrule'];
				$char_location = $role['locationrule'];
				$char_dirvisble = $role['directoryrule'];
*/
				$char_vrvisible=1;	//defaults
				$props = DeserialiseArray($char_props);
				if (isset($props['vrvisible'])) {
					$char_vrvisible =$props['vrvisible'];
				}
				
				$char_projectRole = $role['rolename'];	//same as the project's
				$char_insert_sql = sprintf(
					'INSERT INTO directory ' .
					'(projectid,name,address,location,directoryvisible,vrvisible,linkedprojects,projectrole,properties) ' .
					'VALUES' .
					'(%s,%s,%s,%s,%s,%s,%s,%s,%s)',
					$project->id,
					$database->database->quote(SafeDb($char_name)),
					$database->database->quote(SafeDb($char_address)),
					$database->database->quote(SafeDb($char_location)),
					(int)$char_dirvisible,
					(int)$char_vrvisible,
					'\'\'',
					$database->database->quote(SafeDb($char_projectRole)),
					$database->database->quote(SafeDb($char_props))
				);
				trace ($char_insert_sql.'<br/>');
				
				if ($char_projectRole!='') {
				  $result = $database->execute($char_insert_sql);
          	
				} else {
					$error.= "Character Role Name not set. <br>";
				}
				$directoryid = $database->database->lastInsertID();
			}
			//Save the transaction steps if supported.
			/*
			 if ($useTrans) {
 				$database->database->commit();
 			}
 			*/
trace("Configuring Folders");
			
			$inbox_name = $this->Properties[PROJECT_TEMPLATE_FOLDER_INBOX];
			trace("Inbox Name:".$inbox_name);
			$sent_name =  $this->Properties[PROJECT_TEMPLATE_FOLDER_SENT_ITEMS];
			trace("Sent Items Name:".$sent_name);
			$project->Inbox = $inbox_name;
			$project->SentItems = $sent_name;

			$folders = explode(",",$this->Properties['folders']);
			trace("Constructing New Folder");
			foreach($folders as $folder) {
				trace("Defined Folder: ".$folder);
				$f = new Folder();
				$f->projectid = $project->id;
				$f->name = trim($folder);
				$f->additem = true;
				$f->allowdeletes= true;
				$f->canbedeleted=false;
				
				if (strtolower($f->name) == strtolower($inbox_name)) {
					$f->allowdeletes= false;
					$f->additem = false;
					$f->canbedeleted=false;
				}
				elseif (strtolower($f->name) == strtolower($sent_name)) {
					$f->additem = false;
					$f->allowdeletes= false;
					$f->canbedeleted=false;
				}
				$f->Save();
			}
			trace("Folders configured");

			if (isset($this->Properties['projectNameFormat'])) {
				$ptNewProjectName = $vb->Substitute($this->Properties['projectNameFormat']);
			} else {
				trace("Project Name format: Not Set in blueprint!");
				$ptNewProjectName ='';
			}
			trace("Setting Project Name: ".$ptNewProjectName );
			$project->Name = ($ptNewProjectName=="")?$this->Name:$ptNewProjectName;
			$project->saveProject();
			{
				trace("Setting Creators permissions on Project");

			/*
			 * we need to record the fact that the user that created the project
			 * has rights to administer it, so we have to update the 'userprojects' table.
			 */
			 	$userProjectSql = sprintf(
			 						"INSERT INTO projectpermissions " .
			 						"(userid," .
			 						"usertype," .
			 						"projectid," .
			 						"usestafftools," .
			 						"deleteanyitem," .
			 						"deleteitem," .
			 						"viewitem," .
			 						"additem," .
			 						"editanyitem," .
			 						"edititems," .
									"changeuserpermissions,".
									"editplugin) " .
			 						"VALUES (" .
			 						"%s,'%s',%s,%s,%s,%s,%s,%s,%s,%s,%s,%s" .
			 						")",
			 						$_SESSION[USER]->id,
			 						'user',
			 						$project->id,
			 						1,1,1,1,1,1,1,1,1
			 						);
			 	trace("Updating projectpermissions table: ".$userProjectSql);
			 	$database->execute($userProjectSql);
			 	trace("Creator's permissions set.");
			 }
			 return $project;
		}
		function GetProjectCount() {
			return ProjectTemplate::CountExistingProjects($this->id);
		}

		/**
		 * Gets the complete document template record <b>including the content</b>.
		 * @returns array Array representing the document template record.
		 */
		static function getFullDocumentTemplate ($documentUid) {
			global $database;
			$sql2 = "SELECT doctemplateuid,projecttemplateid,documentid,filename,visiblename,contenttype,creatoruserid,content FROM documenttemplates WHERE doctemplateuid = '$documentUid'";
			$results_with_content= $database->queryAssoc($sql2);
			if (count($results_with_content)>0) {
				return $results_with_content[0];
			}
			return null;
		}
		
		/**
		 * Gets all of a document template record EXCEPT the content unless it is an HTML file.
		 */
		static function getDocumentTemplate($documentUid) {
			global $database;
			//$pid = $this->getProperty("projecttemplateuid");
			//echo 'Retrieving DocTempl UID:'.$documentUid;
			$sql = "SELECT doctemplateuid,projecttemplateid,documentid,filename,contenttype,creatoruserid FROM documenttemplates WHERE doctemplateuid = '$documentUid'";
			$results= $database->queryAssoc($sql);
			if (count($results)>0 ){
				$docMetaData = $results[0];
				
				if (strtolower($docMetaData['contenttype'])=="text/html") {
					return ProjectTemplate::getFullDocumentTemplate($documentUid);
				}
				else {
					return $docMetaData;
				}
			}
		}
		
		/**
		 * Gets an array of all of the Project Templates Installed.
		 */
		static function getTemplates() {
			global $database;
			//trace("Getting Templates");
			$sql = 'SELECT pt.*, containers.name AS cname, containers.containerid AS cid  ' .
					'FROM projecttemplates pt ' .
					'LEFT JOIN containers ' .
					'ON pt.container = containers.containerid ' .
					'WHERE pt.deleted=0 AND containers.deleted=0 ' .
					'ORDER BY containers.containerid ASC, pt.name ASC';
			$results = $database->queryAssoc($sql);
			//echo $sql;
			$templates = array();
			$user=$_SESSION[USER];
			$user->GetSitewidePermissions();		
			foreach($results as $result) {
			//dumparray($result);
				$t = new ProjectTemplate($result);
				if ($user->isProjectTemplateStaff($t->id,true)){
					$templates[] = $t;
				}
			}
			//print_r($results);
		//	echo "::GetTemplates():";
			//dumparray($templates);
			return $templates;
		}
	
		/**
		* gets a project template
		* @param int $projectTemplateUid The Id of the Project Template to retrieve.
		* @static
		*/
		static function getTemplate($projectTemplateUid) {
			global $database;
			//turn TestObject::getProject($projectId);
			if ($projectTemplateUid != NULL) {
				$sql = sprintf(
						'SELECT pt.*, containers.name AS cname, containers.containerid AS cid ' .
						'FROM projecttemplates pt ' .
						'LEFT JOIN containers ON ' .
						'pt.container = containers.containerid ' .
						'WHERE projecttemplateuid = %s ' .
						'AND pt.deleted=0 AND containers.deleted=0 ',
						$projectTemplateUid
						);
			// echo("GetTEmplateQiuyer:$sql<br/><br/>");
				//echo traceArray(debug_backtrace());
				//trace("ProjectTemplate->getTemplate($projectTemplateUid): .$sql");
				
				$results = $database->queryAssoc($sql);
				
				trace("There are ". count($results) ." projects with that ID");
				if (count($results)>0) {
					//dumpArray($results[0]);
					$p = new ProjectTemplate($results[0]);
				//	trace("<pre>".print_r($p, true)."</pre>");
	
					return $p;
				}
				else {
					//echo "No results for $sql";
				}
			}
			else {
			//	echo ("getTemplate(): $projectTemplateUid was null");
			}
			return null;
		}

		/**
		 * Gets a list of templates that the specified userid is doing.
		 */
		static function getUsersTemplates($userid){
			global $database;
			// doesnt take superadmin privileges into account
			//$sql = "SELECT userid, projectid, projects.name FROM projectpermissions , projects WHERE up.userid=$userid AND up.projectid=projects.Uid";
			$sql = "SELECT userid, projecttemplateuid " .
					"FROM projecttemplatepermissions " .
					"WHERE userid=".$userid." AND deleted=0";// AND up.projectid=projects.Uid";
			$results = $database->queryAssoc($sql);
			//dumparray($results);
			$out = array();
			foreach($results as $result){
				//dumparray($nameresult);
				//$name = $nameresult[0]['name'];
				//echo "**".$result['projectid'];
				if ($result['projecttemplateuid']!=""){
					$p = ProjectTemplate::getTemplate($result['projecttemplateuid']);
					//trace("<pre>".print_r($p, true)."</pre>");
					//echo $p->name;
					$out[$result['projecttemplateuid']] = $p->Name;
				//	trace("<pre>".print_r($out,true)."</pre>");
				}
			}
			//dumparray($out);
			return $out;
		}
		
		/**
		 * Gets a list of permissions for a specified project and user
		 */
		static function getUserTemplatePermissions($userid,$projecttemplateuid){
			global $database;
			$sql = 'SELECT * ' .
					'FROM projecttemplatepermissions ' .
					'WHERE userid='.$userid.' '.
					'AND projecttemplateuid='.$projecttemplateuid.' '.
					'AND deleted=0';
			trace('e:'.$sql);
//print_r( debug_backtrace());
			$results = $database->queryAssoc($sql);
			//dumparray($results);
			$out = '';
			foreach($results as $result){
				$out = array(	'ViewTemplate' => $result['viewtemplate'],
								'EditTemplate' => $result['edittemplate'],
								'EditDocumentTemplate'=>$result['editdocumenttemplate'],
								'StartProject'=>$result['startproject'],
								'EndProject'=>$result['endproject'],
								'ArchiveProject'=>$result['archiveproject'],
								'ChangeUserPermissions'=>$result['changeuserpermissions'],
								'EditPlugin'=>$result['editplugin']
						);
			}
			dumparray($out);
			return $out;
		}
		
		static function CountExistingProjects($templateId){
			global $database;
			$sql = sprintf(
						'SELECT count(*) FROM projects WHERE projecttemplateid = \'%s\' AND deleted=0',
						$templateId
			);
			$r = $database->query($sql);
			$result = $r;
			return 0;
		}
		
		static function GetProjects($templateId){			
			return Project::GetProjects('and projecttemplateid='.$templateId);
		}

		/**
		 * Parses a template file and converts it into a template object.
		 * @return ProjecTemplate a ProjectTemplate Object.
		 */
		static function ParseTemplateFile($ProjecTemplateFileContents){

		}

	/**
	 * Gets a list of permissions for a specified project template and user
	 * Does this by:
	 * 1. get users individual permissions or default to null if no record exists
	 * 2. get users groups and go through each one, ANDing to get permissions
	 * 3. default group permissions to false if not yet set (i.e. if no group record exists)
	 * 4. resolve user and group permissions (i.e. if individual permissions are null, 
	 * take group permissions)
	 */
	public static function GetUserProjectTemplatePermissions($userid,$projectTemplateUid) {
	global $database;
			trace("GetUserProjectTemplatePermissions: projectTemplateUid is ".$projectTemplateUid);
			$userTemplatePermissions = array();
			$groupTemplatePermissions = array();
			$sql = sprintf(
					"SELECT * from projecttemplatepermissions p, projecttemplates t " .
					"WHERE p.userid = %s AND " .
					"p.usertype = 'user' AND " .
					"p.projecttemplateuid = %s " .
					"AND p.projecttemplateuid = t.projecttemplateuid " .
					"AND t.isactive= 1 ".
					"AND p.deleted=0 ".
					"AND t.deleted=0",
					$userid,
					$projectTemplateUid
					);
			$result = $database->queryAssoc($sql);
			if (sizeof($result)>0) {
				$userTemplatePermissions['EditDocumentTemplate'] = $result[0]['editdocumenttemplate']; 
				$userTemplatePermissions['StartProject'] = $result[0]['startproject'];
				$userTemplatePermissions['EndProject'] = $result[0]['endproject'];
				$userTemplatePermissions['ArchiveProject'] = $result[0]['archiveproject'];
				$userTemplatePermissions['EditTemplate'] = $result[0]['edittemplate'];
				$userTemplatePermissions['ViewTemplate'] = $result[0]['viewtemplate'];
				$userTemplatePermissions['ChangeUserPermissions'] = $result[0]['changeuserpermissions'];
				$userTemplatePermissions['EditPlugin'] = $result[0]['editplugin'];
			} else {
				// default to null (not set)
				$userTemplatePermissions['EditDocumentTemplate'] = NULL; 
				$userTemplatePermissions['StartProject'] = NULL;
				$userTemplatePermissions['EndProject'] = NULL;
				$userTemplatePermissions['ArchiveProject'] = NULL;
				$userTemplatePermissions['EditTemplate'] = NULL;
				$userTemplatePermissions['ViewTemplate'] = NULL;
				$userTemplatePermissions['ChangeUserPermissions'] = NULL;
				$userTemplatePermissions['EditPlugin'] = NULL;				
			}
			$user = new User($userid);
			$groups = $user->GetGroups();
			foreach ($groups as $group) {
				$query = sprintf("SELECT * FROM projecttemplatepermissions pp, projecttemplates t ".
								"WHERE userid=%s ".
								"AND pp.projecttemplateuid = t.projecttemplateuid ".
								"AND usertype='group' ".
								"AND pp.deleted=0 ".
								"AND t.deleted=0", $group->id);
				$results = $database->queryAssoc($query);
				// loop through all groups and AND results
				foreach ($results as $r) {
					if (isset($groupTemplatePermissions['EditDocumentTemplate'])) {
						$groupTemplatePermissions['EditDocumentTemplate'] = 
						($groupTemplatePermissions['EditDocumentTemplate'] && $r['editdocumenttemplate'])? ALLOW:0;
					} else {
						$groupTemplatePermissions['EditDocumentTemplate'] = $r['editdocumenttemplate'];
					}
					if (isset($groupTemplatePermissions['StartProject'])) {
						$groupTemplatePermissions['StartProject'] = 
						($groupTemplatePermissions['StartProject'] && $r['startproject'])? 1:0;
					} else {
						$groupTemplatePermissions['StartProject'] = $r['startproject'];
					}
					if (isset($groupTemplatePermissions['EndProject'])) {
						$groupTemplatePermissions['EndProject'] = 
						($groupTemplatePermissions['EndProject'] && $r['endproject'])? 1:0;
					} else {
						$groupTemplatePermissions['EndProject'] = $r['endproject'];
					}
					if (isset($groupTemplatePermissions['ArchiveProject'])) {
						$groupTemplatePermissions['ArchiveProject'] = 
						($groupTemplatePermissions['ArchiveProject'] && $r['archiveproject'])? 1:0;
					} else {
						$groupTemplatePermissions['ArchiveProject'] = $r['archiveproject'];
					}
					if (isset($groupTemplatePermissions['EditTemplate'])) {
						$groupTemplatePermissions['EditTemplate'] =
						($groupTemplatePermissions['EditTemplate'] && $r['edittemplate'])? 1:0;
					} else {
						$groupTemplatePermissions['EditTemplate'] = $r['edittemplate'];
					}
					if (isset($groupTemplatePermissions['ViewTemplate'])) {
						$groupTemplatePermissions['ViewTemplate'] =
						($groupTemplatePermissions['ViewTemplate'] && $r['viewtemplate'])? 1:0;
					} else {
						$groupTemplatePermissions['ViewTemplate'] = $r['viewtemplate'];
					}		
					if (isset($groupTemplatePermissions['ChangeUserPermissions'])) {
						$groupTemplatePermissions['ChangeUserPermissions'] =
						($groupTemplatePermissions['ChangeUserPermissions'] && $r['changeuserpermissions'])? 1:0;
					} else {
						$groupTemplatePermissions['ChangeUserPermissions'] = $r['changeuserpermissions'];
					}	
					if (isset($groupTemplatePermissions['EditPlugin'])) {
						$groupTemplatePermissions['EditPlugin'] =
						($groupTemplatePermissions['EditPlugin'] && $r['editplugin'])? 1:0;
					} else {
						$groupTemplatePermissions['EditPlugin'] = $r['editplugin'];
					}					
					// default group permissions to false if not yet set
					if (!isset($groupTemplatePermissions['EditDocumentTemplate'])) {
						$groupTemplatePermissions['EditDocumentTemplate'] = 0;
					}	
					if (!isset($groupTemplatePermissions['StartProject'])) {
						$groupTemplatePermissions['StartProject'] = 0;
					}		
					if (!isset($groupTemplatePermissions['EndProject'])) {
						$groupTemplatePermissions['EndProject'] = 0;
					}		
					if (!isset($groupTemplatePermissions['ArchiveProject'])) {
						$groupTemplatePermissions['ArchiveProject'] = 0;
					}		
					if (!isset($groupTemplatePermissions['EditTemplate'])) {
						$groupTemplatePermissions['EditTemplate'] = 0;
					}		
					if (!isset($groupTemplatePermissions['ViewTemplate'])) {
						$groupTemplatePermissions['ViewTemplate'] = 0;
					}		
					if (!isset($groupTemplatePermissions['ChangeUserPermissions'])) {
						$groupTemplatePermissions['ChangeUserPermissions'] = 0;
					}		
					if (!isset($groupTemplatePermissions['EditPlugin'])) {
						$groupTemplatePermissions['EditPlugin'] = 0;
					}						
					// resolve user and group permissions - if user permissions are null, 
					// take the group permissions
					if (!isset($userTemplatePermissions['EditDocumentTemplate'])) {
						$userTemplatePermissions['EditDocumentTemplate'] = 
							$groupTemplatePermissions['EditDocumentTemplate'];
					}
					if (!isset($userTemplatePermissions['StatProject'])) {
						$userTemplatePermissions['StartProject'] = 
							$groupTemplatePermissions['StartProject'];
					}
					if (!isset($userTemplatePermissions['EndProject'])) {
						$userTemplatePermissions['EndProject'] = 
							$groupTemplatePermissions['EndProject'];
					}
					if (!isset($userTemplatePermissions['ArchiveProject'])) {
						$userTemplatePermissions['ArchiveProject'] = 
							$groupTemplatePermissions['ArchiveProject'];
					}
					if (!isset($userTemplatePermissions['EditTemplate'])) {
						$userTemplatePermissions['EditTemplate'] = 
							$groupTemplatePermissions['EditTemplate'];
					}
					if (!isset($userTemplatePermissions['ViewTemplate'])) {
						$userTemplatePermissions['ViewTemplate'] = 
							$groupTemplatePermissions['ViewTemplate'];
					}
					if (!isset($userTemplatePermissions['ChangeUserPermissions'])) {
						$userTemplatePermissions['ChangeUserPermissions'] = 
							$groupTemplatePermissions['ChangeUserPermissions'];
					}
					if (!isset($userTemplatePermissions['EditPlugin'])) {
						$userTemplatePermissions['EditPlugin'] = 
							$groupTemplatePermissions['EditPlugin'];
					}					
				}
			} // end for each group
			trace("<pre>usertemplatepermissions are ".print_r($userTemplatePermissions, true)."</pre>");
			return $userTemplatePermissions;		
	}

	/**
	 * return the total number of blueprints in the system
	 */
	 public static function GetBlueprintCount() {
	 	global $database;
	 	$sql = sprintf("SELECT count(*) AS totalbps FROM projecttemplates WHERE deleted=0");
	 	$results = $database->queryAssoc($sql);
	 	return $results[0]['totalbps'];
	 }

	/**
	 * return a list of all blueprints in the system
	 */
	 public static function GetAllBlueprints() {
	 	global $database;
	 	$sql = sprintf("SELECT * FROM projecttemplates WHERE deleted=0 ".
	 					"AND isactive=1 ORDER BY name");
	 	$results = $database->queryAssoc($sql);
	 	$blueprints = array();
		if (count($results)>0) {
			foreach($results as $result){
				$blueprints[] = new ProjectTemplate($result);
			}
		}
		return $blueprints;
	 }
	 
	/**
	 * Delete a blueprint from the database, along with dependant records 
 	*/	 
 	public static function DeleteBlueprint($blueprintId) {
		global $database;
		$sql = sprintf("UPDATE projecttemplates SET deleted=1 ".
						"WHERE projectTemplateUid=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}	
		$sql = sprintf("UPDATE projecttemplateroles SET deleted=1 ".
						"WHERE projectTemplateId=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}
		$sql = sprintf("UPDATE projecttemplateplugins SET deleted=1 ".
						"WHERE projectTemplateUid=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}
		$sql = sprintf("UPDATE projecttemplatepermissions SET deleted=1 ".
						"WHERE projecttemplateuid=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}
		$sql = sprintf("UPDATE projectsequence SET deleted=1 ".
						"WHERE projectTemplateId=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}
		$sql = sprintf("UPDATE documenttemplates SET deleted=1 ".
						"WHERE projectTemplateId=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}		
		$sql = sprintf("UPDATE eventresources SET deleted=1 ".
						"WHERE projecttemplateid=%s",
						$blueprintId);
		$result = $database->execute($sql);
		if ($result !== true) {
			die($result);
		}		
		// delete all projects associated with this template	
		$projects = self::GetProjects($blueprintId);
		foreach ($projects as $p) {
			// delete project
			Project::DeleteProject($p->id);
		}
 	}
 	
 	public static function GetInstalledProjectCount() {
    global $database;
    
    $query = "select count(*) as projectcount from containers where deleted=0";
    $result = $database->queryAssoc($query);
    
    return $result[0]['projectcount']; 
  }
}
?>
