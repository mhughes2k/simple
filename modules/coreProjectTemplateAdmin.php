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
 * 
 * @package Core
 */
 if (!defined("TLE2")) die ("Invalid Entry Point");
 /*
 echo "Is Tempalte staff:";
 echo $_SESSION[USER]->isProjectTemplateStaff();
 echo 'is auth';
 echo (integer)$_SESSION[USER]->IsAuthenticated();
*/
 if ($_SESSION[USER]->IsAuthenticated() ){
	/*
	 * we need to do a sanity check!
	 * The user <b>must</b> be authenticated and MUST have 
	 * projectadmin rights
	 */
	 trace("Permissions");
  	switch (strtolower($command))  {
  		case 'updateresource':
			UpdateResource();
			break;
		case 'addresource':
			AddResource();
			break;
		case 'testned':
			TestNed();
			break;
		case 'addvariable';
  			AddVar();
  			break;
  		case 'updatevar':
  			UpdateVar();
  			break;
  		case 'deletevar':
			DeleteVariable();
			break;
  		case 'doupdatetemplaterole':
  			DoUpdateTemplateRole();
  			break;
		case 'createcontainerinstance':
			CreateContainerInstance();
			break;
  		case 'createproject':
  			CreateProject();
  			break;
  		case 'docreateproject':
  			DoCreateProject();
  			break;
  		case 'uploadtemplate':
  			uploadTemplate();
			break;
  		case 'editdocument':
			editDocument();
			break;
		case 'addtemplate':
			AddTemplate();
			break;
		case 'doaddTemplate':
			break;
		case 'listprojects':
			break;
		case 'viewprojecttemplate':
		case 'editprojecttemplate':
			editProjectTemplate();
			break;
		case 'saveproject':
			break;
		case 'listplugins':
			ListProjectTemplatePlugins();
			break;			
		case 'editprojecttemplatepluginpage':
			EditProjectTemplatePluginPage();
			break;
		case 'addprojecttemplatepluginpage':
			AddProjectTemplatePluginPage();
			break;		
		case 'updateprojecttemplateplugin':
			UpdateProjectTemplatePlugin();
			break;
		case 'clearplugin':
			ClearProjectTemplatePlugin();
			break;	
		default:
		case 'listprojectemplates':
			listProjectTemplates();
	}
 }
 else {
	trace("No permissions");
	Redirect('index.php?option=message&cmd=Insufficient Permissions');
 }
	//----Project Template Management Code
	// Project management code follows after.
	
/**
 * Does not actually create container instance, but creates an instance of each blueprint in the container
 * and then links them if it is an adversarial sim
 */
function CreateContainerInstance() {
	global $page;
	$user = $_SESSION[USER];
	$containerId = GetParam('containerId',-1);		
	if ($user->superadmin!=ALLOW) InsufficientPermissions();
	$page->Template="CreateContainer.php.tpl";
	$blueprints = Container::GetBlueprints($containerId);
	foreach ($blueprints as $bp) {
		/**$template = ProjectTemplate::getTemplate($bp->id);
		$variables = $template->Variables;
		ksort($variables,SORT_STRING); 
		foreach($variables as $key=>$value) {
			$oldValue = $value;
			$value = str_replace($counterName,$counterValue,$value);
			$variables[$key]=$value;
		}
		$page->assign("template",$template);
		$page->assign('variableslist',$variables);	
	*/
	$page->assign("blueprints",$blueprints);
	print "<pre>";
	print_r($blueprints);
	print "</pre>";
	}
}

function doCreateContainerInstance() {
	// for each blueprint in container, create a blueprint
	
	// with lastinsertid, link the new simulations
}
	
/**
 * Displays form to edit the settings for a simulation.
 */
	function CreateProject() {
		global $page;
		$user = $_SESSION[USER];
		$projectTemplateId = GetParam('projectTemplateId',-1);		
		//echo 'Superuser:'.$user->superadmin . "allow:". ALLOW;
		//print_r($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]);
 		if (
 		    ($user->superadmin!=ALLOW)
        &&
        (
          isset($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]) 
          & 
          !($user->projectTemplatePermissions[$projectTemplateId]['StartProject'])
 			  )
       ) {
      InsufficientPermissions();
    }				
		$page->Template="CreateProject.php.tpl";
		if ($projectTemplateId <0 ){
			$page->Messages[] = "No Template Specified";
			$page->Messages[] = "Unable to create Project";
			return;
		}
		$template = ProjectTemplate::getTemplate($projectTemplateId);
		$variables = $template->Variables;
		ksort($variables,SORT_STRING); 
		//array_multisort($variables[0],SORT_ASC,SORT_STRING);
		//print_r($variables);
		$counterName = GetParam('counterName','');
		$counterValue = GetParam('counter','');
		$counterStepValue = GetParam('counterStep',1);
		$skipSimCreatedEvent=GetParam('skipSimCreatedEvent',false);
		//print_r($variables);
		if ($counterName!='' && $counterValue!='') {
		  foreach($variables as $key=>$value) {
		    $oldValue = $value;
		    $value = str_replace($counterName,$counterValue,$value);
		    $variables[$key]=$value;
		    //print("$key =  $oldValue => $value<br />");
		  }
		}
		$page->assign("template",$template);
		$page->assign('id',$template->id);
		$page->assign('editVariablesOption',"projecttemplateadmin");
		$page->assign('editVariablesCmd',"docreateproject");
		$page->assign('editVariablesOkButtonLabel',"Create");
		$page->assign('counterName',$counterName);
		$page->assign('counter',$counterValue);
		$page->assign('counterStep',$counterStepValue);
		$page->assign('skipSimCreatedEvent',$skipSimCreatedEvent);
		$page->assign('variableslist',$variables);
	}
/**
 * Performs the instantiation of a simulation.
 */
	function DoCreateProject(){
		global $page,$config,$_PLUGINS,$metrics;
		trace("Creating Project");
		$projectName = GetParam('projectName','');
		//print $projectName;
		$projectTemplateId = GetParam('id',-1);
		//print $projectTemplateId;
		if ($projectTemplateId <0 ){
			//DisplayMessage("No Template Specified. Unable to create Project");
			//return;
		}
		$counterName=GetParam('counterName','');
		$counterValue=GetParam('counter','');
		$counterStepValue=GetParam('counterStep',1);
		if (!is_numeric($counterStepValue)) {
		  $counterStepValue=1;
		}
		$createAnother = GetParam('createAnother',false);
		$skipSimCreatedEvent=GetParam('skipSimCreatedEvent',false);
		//die("Skip Post Create Event: $skipSimCreatedEvent");
		//die ("$counterName:$counterValue");
		//print($createAnother);
		$overrides = array();
		foreach($_POST as $postVar=>$value) {
			//echo $postVar.":".$value ."<br>";
			if (strtolower(substr($postVar,0,4)) =="var_") {
				$varName = substr($postVar,4);
				$varVal = $value;
				//echo "Variable Setting $varName:$varVal";
				$overrides[$varName] = $varVal;
			}
		}
		
		$projectTemplate = ProjectTemplate::getTemplate($projectTemplateId);
		//print_r($projectTemplate);
		//echo $projectTemplateId;
		$userId = $_SESSION[USER]->id;
		$project = $projectTemplate->CreateProject($projectName,$overrides,$userId);
		//print_r($project);
		if($project !== false) {
		////				  $metrics->recordMetric('Sender not found',$projectId,$releaseItem['performerrole']))
		  if(!$skipSimCreatedEvent) {
		    //die('executing sim created event');
		    $metrics->recordMetric('simulationCreated',$project->id,$projectTemplateId,$userId);
		    $_PLUGINS->trigger('simulationCreated',array($project));
		  }
		  else {
		     $metrics->recordMetric('simulationCreated','skipped', $project->id,$projectTemplateId,$userId);
		   // die('skipping sim created event');
		  }
		  
		}
		else {
		  $metrics->recordMetric('simulationCreated','false','failed to run');
		}
		$redirectUrl = "index.php?option=projecttemplateadmin";
		if ($createAnother!==false) {
		  //die('create another');
		  $newCounterValue=$counterValue+$counterStepValue;
		  $redirectUrl = "index.php?option=projecttemplateadmin&cmd=createproject&projectTemplateId=$projectTemplateId&counterName=$counterName&counter=$newCounterValue&counterStep=$counterStepValue&skipSimCreatedEvent=$skipSimCreatedEvent";
		}
		else {
	  
		}
		//die($redirectUrl);
		Redirect($redirectUrl);
	}
	/**
	 * Displays a list of all of the project templates installed.
	 */
	function listProjectTemplates() {
		global $page,$database,$serverMode;
		$page->Template = "listProjectTemplates.php.tpl";
		$page->assign('user',$_SESSION[USER]);
		$method = getParam('method', NULL);
		/**
		if ($method=='deleteTemplate') {			
			$templateId = getParam('delete_itemid', NULL);
			if ((isset($_SESSION[USER]->projectTemplatePermissions[$templateId]) && $_SESSION[USER]->projectTemplatePermissions[$templateId]['RemoveTemplate']!=ALLOW) &&
 				($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
 			
			ProjectTemplate::DeleteBlueprint($templateId);
		}**/
		if ($method=='removeproject') {
			$containerid = getParam('containerid',NULL);
			if ($_SESSION[USER]->superadmin!=ALLOW) InsufficientPermissions();	
			if ($containerid >'') {
				$query = sprintf("UPDATE containers SET deleted=1 WHERE containerid=%s",
					$containerid);
			 	$result = $database->execute($query);
			 	$query = sprintf("SELECT projecttemplateuid FROM projecttemplates WHERE container=%s AND deleted=0",
			 				$containerid);
			 	$results = $database->queryAssoc($query);
			 	foreach ($results as $r) {
			 		ProjectTemplate::DeleteBlueprint($r['projecttemplateuid']);
			 	}
			}
			Redirect('index.php?option=projectTemplateAdmin');
			return;
			
		}
		$containers = Container::GetContainers();
		foreach ($containers as $key=>$c) {
			$containers[$key]['blueprints']=Container::GetBlueprints($c['containerid']);
		}
		
		$zipInstalled = (extension_loaded('zip'))? 1:0;
		$page->assign('zipinstalled', $zipInstalled);
		$page->assign('containersArray',$containers);
		//$page->assign('projectCount',ProjectTemplate::GetInstalledProjectCount());
		//$page->assign('bpLimit',$serverMode['bpLimit']);
	}
	function TestNed() {
  	global $page;
		$projectTemplateId = getParam('projectTemplateId',-1);
		$page->Template = 'ned.tpl';
		$user= $_SESSION[USER];
		$projectTemplate = ProjectTemplate::getTemplate($projectTemplateId);
  	echo "<pre>";
    $events =$projectTemplate->GetEvents();
			//dumpArray($events);
		print_r($events);
		echo "</pre>";
		$page->assign('ned_EnableTriggers',false);
		$page->assign('ned_EnableDocuments',false);
		$page->assign('events',$events);
  }
	/**
	 * Allows an admin user to make changes to a running project.
	 */
	function editProjectTemplate() {
		global $page;
		$projectTemplateId = getParam('projectTemplateId',-1);
		$user= $_SESSION[USER];
 		if (
 		  ($user->superadmin!=ALLOW) & 
 		  ($user->sitewidePermissions['EditTemplate']!=ALLOW) ||
 		  isset($user->projectTemplatePermissions[$projectTemplateId]) && 
      (
 			  ($user->projectTemplatePermissions[$projectTemplateId]['EditTemplate']) ||
 			  ($user->projectTemplatePermissions[$projectTemplateId]['ViewTemplate'])
      ) 			
    )        
    {
       InsufficientPermissions();		
    }
		$page->Template = 'viewProjectTemplate.php.tpl';
		if ($projectTemplateId >=0 ){
			$projectTemplate = ProjectTemplate::getTemplate($projectTemplateId);
			$page->assign('project',$projectTemplate);
			$page->assign('pid',$projectTemplateId);
			$page->assign('roles',$projectTemplate->GetRoles());
			$dts = $projectTemplate->getDocumentTemplates(false);
			//print_r($dts);
			$page->assign('docTemplates', $dts);
			$page->assign('option_target','projectTemplateAdmin');
			$page->assign('editVariables',true);
			$page->assign('variablelist',$projectTemplate->Variables);
			$page->assign('variablelist_message','');
			$page->assign('variablelist_message2','');
			$page->assign('PostSaveVarRedirect','index.php?option=projectTemplateAdmin&cmd=editProjectTemplate&projectTemplateId='.$projectTemplateId);
			$tmpRoles =$projectTemplate->GetRoles(); 
			/*array(
				array('projectrole'=>'Client','name'=>'{CHAR_client}','directoryvisible'=>true),
				array('projectrole'=>'Defending Firm','name'=>'{CHAR_defending_firm}','address'=>'{CHAR_defending_firm_address}','location'=>'{CHAR_defending_firm_location}','directoryvisible'=>true),
				array('projectrole'=>'PLAYER','name'=>'{CHAR_PLAYER}','address'=>'{CHAR_player_address}','location'=>'{CHAR_player_location}','directoryvisible'=>true),
				array('projectrole'=>'Claimant','name'=>'{CHAR_claimant_name}','address'=>'{CHAR_claimant_address}','location'=>'{CHAR_claimant_location}','directoryvisible'=>true),
			);
			*/
			//echo traceArray($tmpRoles);
			$page->assign('rolelist_characters',$tmpRoles);
			//function GetPermission($permissionName,$userId){
			if ($projectTemplate->GetPermission(PERMISSION_EDIT_TEMPLATE,$user->id)) {
				$page->assign('rolelist_edit',true);
			}
			else {
				$page->assign('rolelist_edit',false);
			}
			
			$events =$projectTemplate->GetEvents();
			//dumpArray($events);
			//print_r($events[PROJECT_EVENT_START_EVENT]);
			$page->assign('ned_EnableTriggers',false);
			$page->assign('ned_EnableDocuments',false);
			$page->assign('events',$events);
			//dumparray($projectTemplate->getDocumentTemplates());
		}
	}
	/**
	 * Save changes made to a project
	 */
	function saveProject(){
		global $database;
		$documentContent = getParam('documentContent');
		/*
		 * Create a new template associated with the currrent project.
		 */
	}
	/**
	 * Displays either a Tiny MCE editor for HTML files or a download & upload form for
	 * other types of file.
	 */
	function editDocument() {
		global $page;
		$documentUid = getParam('docUid',-1);
	//	print_r($_SESSION);
		if ($documentUid >0) {
			$documentMetaData = ProjectTemplate::getDocumentTemplate($documentUid);
			$page->Template = "editDocument.php.tpl";
			$page->assign('item',new Document());
			$page->assign('projectName',$_SESSION['projectId']);
			$page->assign('documentName','A Document');
			$page->assign('option','projectAdmin');
			//$page->assign('content',$documentMetaData['contenttype']);
			$page->assign('filename',$documentMetaData['filename']);
			$page->assign('docuid',$documentMetaData['doctemplateuid']);
			$page->assign('contenttype',$documentMetaData['contenttype']);
			$page->assign('showeditor',false);
			if (strtolower($documentMetaData['contenttype']) =='text/html' 
				| strtolower($documentMetaData['contenttype']) =='application/rtf'
			){
				$page->assign('documentContent',$documentMetaData['content']);
				$page->assign('command','saveDocumentTemplate');
			}
			else {
				$page->assign('command','uploadTemplate');
				$page->assign('enctype','enctype="multipart/form-data"');
			}
		}
		else {
			DisplayMessage('Document Not found!');
		}
	}
	function uploadTemplate() {
		global $database;
		//echo "overriding template document";
		$user=$_SESSION[USER];
 		if (!(($user->sitewidePermissions['InstallTemplate']==ALLOW) ||
 			($user->superadmin==ALLOW))) InsufficientPermissions();
		$fileSize = $_FILES['uploadDocument']['size'];
		//echo "FS:$fileSize";
		//dumparray($_FILES['uploadDocument']);
		if ($fileSize ==0) {
			die( 'Did not upload file successfully');
		}
		$documentUid = getParam('docuid');
		$documentMetaData = ProjectTemplate::getDocumentTemplate($documentUid);
		$pid = $documentMetaData['projecttemplateid'];
		$filename = $_FILES['uploadDocument']['name'];
		$tmpName = $_FILES['uploadDocument']['tmp_name'];
		$type= $_FILES['uploadDocument']['type'];
		$fp = fopen($tmpName,'r');
		$content = fread($fp,filesize($tmpName));
		$content = mysql_real_escape_string($content);
		fclose($fp);
//UPDATE THIS!
		$sql = "UPDATE documenttemplates SET content = '$content' " .
				"WHERE doctemplateuid = $documentUid";
				//echo $sql;
		$result = $database->execute($sql);
		header("Location:index.php?option=projectAdmin&cmd=viewProjectTemplate&projectTemplateId=$pid");
	}
	/**
	 * Displays information about a project template.
	 */
	function viewProjectTemplate() {
	}
	/**
	 * Allows user to add a new project template to the system.
	 */
	function AddTemplate() {
		global $database, $page,$metrics;
		$metrics->recordMetric('starting project installation');
		if (!(($_SESSION[USER]->sitewidePermissions['InstallTemplate']==ALLOW) ||
			($_SESSION[USER]->superadmin==ALLOW))) InsufficientPermissions();
		$projectError = '';
		$succeeded = TRUE;
		if ($_FILES['bpofile']['error'] > 0) {
  			$metrics->recordMetric('Error Caught:'.$_FILES['bpofile']['error']);
        switch ($_FILES['bpofile'] ['error']) {  
  				case 1:
            		$projectError =  'The file is bigger than this PHP installation allows.<br>';
            		$succeeded = FALSE;
                   	break;
            	case 2:
                	$projectError = 'The file is bigger than this form allows.<br>';
                	$succeeded = FALSE;
                   	break;
            	case 3:
                	$projectError = 'Only part of the file was uploaded.<br>';
                	$succeeded = FALSE;
                   	break;
            	case 4:
                   	$projectError = 'No file was uploaded.<br>';
                   	$succeeded = FALSE;
                   	break;
              default:
                    $projectError =$_FILES['bpofile']['error']."<br>";
                    $succeeded = FALSE;
             		break;
         	}
  		} else {
			// get the manifest.xml file from the zip archive then get contents
			$metrics->recordMetric('file uploaded ok, starting unpack');
			$zip = new ZipArchive();
			if ($zip->open($_FILES['bpofile']['tmp_name'])) {
			 $metrics->recordMetric('Install File opened');
      //	echo "numFiles: " . $zip->numFiles . "\n";
			//	echo "status: " . $zip->status  . "\n";
			//	echo "statusSys: " . $zip->statusSys . "\n";
			//	echo "filename: " . $zip->filename . "\n";
			//	echo "comment: " . $zip->comment . "\n";
				// try and open manifest file
				$manifest = $zip->getFromName('manifest.xml');
			//	print "manifest is "; print_r($manifest);
				try {
					$xml = new SimpleXMLElement($manifest);
					
				} catch (Exception $e) {
					$metrics->recordMetric('Invalid manifest.xml file');
					die("XML document not valid ".print_r($e));
				}
				$metrics->recordMetric('Manifest found...starting');

				$query = sprintf("INSERT INTO containers (containerid,name) VALUES ('','%s')",
								$xml->name);
				$result = $database->execute($query);
				$containerid = $database->database->lastInsertId();
				$metrics->recordMetric('Containers configured');
				$metrics->recordMetric('Installing Blueprints');
				foreach ($xml->Blueprints->Blueprint as $bp) {
					//print "installing blueprint ".$bp->ID."<br/>";
					$metrics->recordMetric('installing bp');
					$variables = array();
					$metrics->recordMetric('setting up variables');
					foreach ($bp->Variables->VariableDef as $v) {
						$variables[''.SafeDb($v->Name).''] = SafeDb($v->Value);
						//print($variables[''.SafeDb($v->Name).''] .'='. SafeDb($v->Value) .'<br>');
					}
					
					// if folders not set, set to default
					$properties = array();
					$metrics->recordMetric('Setting up Blueprint properties');
					foreach ($bp->Properties->item as $item) {
						$properties[''.$item->key->string.''] = $item->value->string;
					}
					if (sizeof($properties)==0) {
						$properties['folders'] = 'Received,Sent,Drafts';
						$properties['INBOX'] = 'Received';
						$properties['SENT'] = 'Sent';
						$properties['DRAFTS'] = 'Drafts';
					} 
					//print_r($properties);
					$metrics->recordMetric('Map resource set to '.$bp->MapResourceId);
					$properties['mapResourceId'] = $bp->MapResourceId;
					
					$query = sprintf("INSERT INTO projecttemplates (name,".
								"properties,variables,stylesheet,deleted,isactive,container,".
								"version) VALUES ('%s','%s','%s','%s',%s,%s,'%s','%s')",
								(SafeDb($bp->Name)=='') ? '' : SafeDb($bp->Name),
								str_replace('Recieved','Received',SerialiseArray($properties)), // properties
								SerialiseArray($variables),
								'', // stylesheet
								0,
								1,
								$containerid,
								(SafeDb($bp->Version)=='') ? '' : SafeDb($bp->Version)
								);
					$result = $database->execute($query);
					if ($result!== true) {
					 $metrics->recordMetric('Failed to install '.$bp->Name);
						$projectError.= "Problem inserting Blueprint: ".$bp->Name."<br>";
					}
					$projecttemplateid = $database->database->lastInsertId('projecttemplateuid');
					$projectTemplate = ProjectTemplate::getTemplate($projecttemplateid);
					$metrics->recordMetric('Setting up roles');
					foreach ($bp->roles as $r) {
						//echo '<p>Configuring Role</p>';
						$ptrProps = array();
						foreach($r->Properties->item as $ptrProp){
							$ptrProps[''.$ptrProp->key->string.''] = $ptrProp->value->string;
						}
						$isSharedRole = strtolower($r->SharedRole)=='false'?0:1;
						$ptrProps['shared'] = $isSharedRole;
						$query = sprintf("INSERT INTO projecttemplateroles (".
									"id,".
									"projecttemplateid,".
									"projecttemplateroleid,".
									"rolename,".
									"namerule,".
									"addressrule,".
									"locationrule,".
									"directoryrule,".
									"properties,".
									"deleted) VALUES (".
									"'',%s,'%s','%s','%s','%s','%s','%s','%s',0)",
									$projecttemplateid,
									$r->ID,
									(SafeDb($r->Name)=='') ? '' : SafeDb($r->Name),
									(SafeDb($r->NameVariable)=='') ? '' : SafeDb($r->NameVariable),
									(SafeDb($r->AddressVariable)=='') ? '' : SafeDb($r->AddressVariable),
									(SafeDb($r->PhysicalAddressVariable)=='') ? '' : SafeDb($r->PhysicalAddressVariable),
									(SafeDb($r->DirectoryVariable)=='') ? '1' : SafeDb($r->DirectoryVariable),
									SerialiseArray($ptrProps),
									0);	
									//cho($query);
						if ($r->Name>'') {
							$result = $database->execute($query);
							if ($result !== true) {
								$projectError.= "Problem inserting role: ".$r->ID."<br>";
							}							
						} else {
							$projectError.= "Name not set in blueprint role.<br>";
						}
						
					}		
					// documents/resources
					$metrics->recordMetric('Setting up documents/resources');
					foreach ($bp->resources as $r) {
						$contents = '';
					  $ct=strtolower($r->ContentType);
						switch ($ct){
            			  case "url":
                			$contents = base64_encode("{$r->Content}");
                			break;
              			  default:
           					$term = $bp->ID.'/'.$r->Content; 
    						$term = str_replace('\\','/',$term);
    				
    						$fp = $zip->getStream($term);
        					if(!$fp) {
        						$projectError.= "Resource not included: ".$term."<br>";
        					} else {
        						while (!feof($fp)) {
            						$contents .= fread($fp, 2);
        						}
        					fclose($fp);
        					}
        					break;
            			}		
						$playerVisible = ($r->PlayerVisible)=='true'?1:0;	
						$safeContents = ($ct=='url') ? $contents : base64_encode($contents);            						
						$sql = sprintf("INSERT INTO documenttemplates (projecttemplateid,documentid,filename,visiblename,".
										"contenttype,content,creatoruserid,icon,playercansee,deleted) VALUES ".
										"('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
										$projecttemplateid,
										SafeDb($r->ID),// document id
										(SafeDb($r->ReferenceName)=='') ? '' : SafeDb($r->ReferenceName),
										(SafeDb($r->Name)=='') ? '' : SafeDb($r->Name),
										(SafeDb($r->ContentType)=='') ? 'unknown' : SafeDb($r->ContentType),
										$safeContents,
										SafeDb(0),
										(SafeDb($r->Icon)=='') ? "-" : SafeDb($r->Icon),
										$playerVisible,
										0);				
						//print $sql;									
						$result = $database->database->query($sql);
			
					} //end resource loop

					$metrics->recordMetric('Setting up structure');
					for ($i=0; $i<count($bp->structure); $i++) {	
						//echo "<pre>".print_r($bp->structure[$i],true)."</pre>";
						if (!is_null($bp->structure[$i-1]->ID)){
						  $prevEventId =($bp->structure[$i-1]->ID!=$bp->structure[$i]->ID)?$bp->structure[$i-1]->ID:0;
						}
						else {
							$prevEventId = 0;
						}
						if (!is_null($bp->structure[$i+1])){
							$nextEventId =($bp->structure[$i+1]->ID!='')?$bp->structure[$i+1]->ID:0;
						} else {
							$nextEventId = 0;
						}						
						$performerRole = ($bp->structure[$i]->Performer=='') ? '' : SafeDb($bp->structure[$i]->Performer);
						if ($bp->structure[$i]->ID=='') {
							$projectError.= "ID not set in blueprint: ".$bp->Name." - ".$bp->structure[$i]->Name."<br>";
						}
						if ($bp->structure[$i]->Level=='') {
							$projectError.= "Level not set in blueprint: ".$bp->Name." - ".$bp->structure[$i]->Name."<br>";
						}
						if($bp->structure[$i]->Level==-100){
							$bp->structure[$i]->Level=100;
							$performerRole = 'CHAR_NONE';
						}
						if ($bp->structure[$i]->Level<0) {
						//this bit handles all of the diagramatic only elements (bars repeats etc.)
						$bp->structure[$i]->Level=0;
						  $performerRole = 'CHAR_NONE';
						}
						if ($bp->structure[$i]->Level==0) {
							$performerRole = $bp->structure[$i]->Performer;
						}
						if (($bp->structure[$i]->Performer=='Multiple NPCs') ||
							($bp->structure[$i]->Performer=='Multiple Parties') ||
							($bp->structure[$i]->Performer==''))
							$performerRole = 'CHAR_NONE';
						if (($bp->structure[$i]->Level==3) ||
							($bp->structure[$i]->Level==301)) {
								$bp->structure[$i]->Level=4;
								$performerRole = 'CHAR_STAFF';
						}
						//if (($bp->structure[$i]->Level==301))			
						if ($bp->structure[$i]->Level==1)
							$performerRole = 'CHAR_PLAYER';
						if ($performerRole=='') {
							$projectError.= "Performer role not set in blueprint: ".$bp->Name." - ".$bp->structure[$i]->Name."<br>";
						}							
						$query = sprintf("INSERT INTO projectsequence (".
									"id,".
									"projecttemplateid,".
									"projecttemplateeventid,".
									"name,".
									"itemtype,".
									"performerrole,".
									"nexteventid,".
									"previouseventid) VALUES (".
									"'',%s,'%s','%s',%s,'%s','%s','%s')",
									$projecttemplateid,
									SafeDb($bp->structure[$i]->ID),
									SafeDb($bp->structure[$i]->Name),
									SafeDb($bp->structure[$i]->Level),
									SafeDb($performerRole), 
									SafeDb($nextEventId),
									SafeDb($prevEventId));
						$result = $database->execute($query);
						
						//do setup "Release docs"
						$resources = $bp->structure[$i]->Resources;

						//echo 'Adding Resources:'. count($resources->item);
						for ($k = 0; $k<count($resources);$k++) {
						  
              				$items = $resources[$k]->item;
						    for($j = 0; $j<count($resources[$k]->item);$j++){
              					//echo '<p>Linking Resource to structure (creating a release doc)</p>';
                 				$xmlRes = $resources[$k]->item[$j];
                 				// echo '***XML Resource:';
                  				//print_r($xmlRes);
				                //	echo '<br/>';
				                if ($xmlRes->key->string>'') {
                  					$resourceId = $xmlRes->key->string;
									$ptID = $projectTemplate->GetDocumentTemplateIdFromDocumentId($resourceId);
                  					$resource = ProjectTemplate::getDocumentTemplate($ptID);
								//	print "<pre>";
								//	print "resource id is ".$resourceId."<br/>";
								//	print "template id is ".$ptID."<br/>";
								//	print_r($resource);
								//	print "</pre>";
				                } else {
				                	$projectError.= "Resource ID not set in Resource Event.<br>";
				                	$succeeded = FALSE;
				                }
				                if ($xmlRes->value->string>'') {
				                    $projectRoleId = $xmlRes->value->string;
									$query = sprintf("SELECT rolename FROM projecttemplateroles p where projecttemplateroleid='%s' and projecttemplateid='%s'",
										$projectRoleId, $projecttemplateid);
				                    $result = $database->query($query);
                  					$senderRoleId = $result ;
                  			
				                } else {
				                	$projectError.= "Sender role for <em>".$resource['filename']."</em> not set in resourceevent, setting to Element's performer.<br>";
				                	$senderRoleId = $performerRole;
				                	//$succeeded = FALSE;
				                }
                  				if ($succeeded) {
					                $query = sprintf("INSERT INTO projectsequence (".
  										"id,".
  										"projecttemplateid,".
  										"projecttemplateeventid,".
  										"name,".
  										"itemtype,".
  										"performerrole,".
  										"nexteventid,".
  										"previouseventid) VALUES (".
  										"'',%s,'%s','%s',%s,'%s','%s','%s')",
  										$projecttemplateid,
  										(SafeDb($resource['documentid'])=='') ? '' : SafeDb($resource['documentid']),
  										(SafeDb($resource['filename'])=='') ? '' : SafeDb($resource['filename']),
  										SafeDb(3),
  										SafeDb($senderRoleId), 
  										SafeDb($resource['documentid']),
  										SafeDb($bp->structure[$i]->ID));
  										//echo('**Query:'.$query.'<br>');
  									$result = $database->execute($query);
            					}
							}
						}
								
					}
				}		
			} else {
				$metrics->recordMetric('Failed to op installation file');
				$projectError.= "Failed to open zip file. <br>";
			}
			}
			if (!$succeeded) {
				$projectError.= "<br>Project not imported. Please see errors above. ";
			} else {
				$projectError.= "<br>Project successfully imported. ";
			}
			$projectError.= '<a href="index.php?option=projectTemplateAdmin">Click here</a> to manage Blueprints.';
			$page->Template = "installTemplate.php.tpl";
			$page->assign('user',$_SESSION[USER]);
			$templates= ProjectTemplate::getTemplates();
			$page->assign('templatesArray',$templates);		
			$page->assign('projectError',$projectError."<br>");
		
	}
	/**
	 * Parses the uploaded templatefile
	 */
	function ParseTemplate() {
	}
	/**
	* List the plugins that have been specifically defined for a given template
	*/
	function ListProjectTemplatePlugins() {
		global $page;
		$projectTemplateId = getParam('projectTemplateId', -1);
		if (($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$page->Template = "listProjectTemplatePlugins.php.tpl";
		$plugins = Plugin::GetProjectTemplatePlugins($projectTemplateId);
		trace("<pre>".print_r($plugins, true)."</pre>");
		$page->assign("plugins", $plugins);
		$page->assign("projectTemplateId", $projectTemplateId);
		$projectTemplate = ProjectTemplate::getTemplate($projectTemplateId);
		$page->assign('projectTemplate', $projectTemplate);			
	}
	/**
	* Removes the record of this plugin for this template, thereby reverting to the sitewide default value
	*/
	function ClearProjectTemplatePlugin() {
		global $page;
		$pluginId = getParam('pluginId', -1);
		$projectTemplateId = getParam('projectTemplateId', -1);
		if (($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		Plugin::ClearProjectTemplatePlugin($pluginId, $projectTemplateId);
		$page->Template = "listProjectTemplatePlugins.php.tpl";
		$plugins = Plugin::GetProjectTemplatePlugins($projectTemplateId);
		$page->assign('plugins', $plugins);
		$page->assign("projectTemplateId", $projectTemplateId);
	}
	/**
	* Display form to allow user to create a new plugin record for this template
	*/
	function AddProjectTemplatePluginPage() {
		global $page;
		$projectTemplateId = getParam('projectTemplateId', -1);
		$pluginId = getParam('pluginId', -1);
		if (($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$plugin = Plugin::GetPlugin($pluginId);
		$page->Template = "viewProjectTemplatePlugin.php.tpl";
		$page->assign('plugin', $plugin);
		$page->assign('projectTemplateId', $projectTemplateId);
		$page->assign('method', 'add');
	}
	/**
	* Adds or edits a plugin record for this template
	*/
	function UpdateProjectTemplatePlugin() {
		global $page;
		$method = getParam('method', -1);
		$projectTemplateId = getParam('projectTemplateId', -1);
		if (($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$pluginId = getParam('pluginId', -1);
		$enabled = getParam('enabled', -1);
		if ($method == 'add') {
			Plugin::AddProjectTemplatePlugin($projectTemplateId, $pluginId, $enabled);
		} else {
			// need to implement method
			Plugin::EditProjectTemplatePlugin($projectTemplateId, $pluginId, $enabled);
		}
		$plugin = Plugin::GetProjectTemplatePlugin($pluginId, $projectTemplateId);
		$page->Template = 'viewProjectTemplatePlugin.php.tpl';
		$page->assign('plugin', $plugin);
		$page->assign('projectTemplateId', $projectTemplateId);
	}
	/**
	* View a plugin that has been specifically defined for a given template
	*/
	function EditProjectTemplatePluginPage() {
		global $page;
		$pluginId = getParam('pluginId', -1);
		$projectTemplateId = getParam('projectTemplateId', -1);
		if (($_SESSION[USER]->projectTemplatePermissions[$projectTemplateId]['EditPlugin']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$page->Template = 'viewProjectTemplatePlugin.php.tpl';
		$plugin = Plugin::GetProjectTemplatePlugin($pluginId, $projectTemplateId);
		//trace("plugin is <pre>".print_r($plugin, true)."</pre>");
		$page->assign('plugin', $plugin);
		$page->assign('method', 'edit');
		$page->assign('projectTemplateId', $projectTemplateId);		
	}
	/**
	 * Updates a single template role.
	 */
	function DoUpdateTemplateRole() {
		//name="roleid" value="{$role.projecttemplateroleid}" />
//			<input type="hidden" name="templateid"
		$oldRoleId = GetParam('oldroleid','');
		$templateId = GetParam('templateid','');
		$redir = GetParam('redir');
		echo 'Updating Template Role!';
		//Redirect($redir);
	}
	/*
	 * Removes a variable from a PT 
	 * 
	 * Redirects back to the PT edit page. 
	 */
	function DeleteVariable() {
		global $config,$database;
		$projecttemplateid = GetParam('pid',-1);
		$redir =$config['home'].'index.php?option=projectTemplateAdmin&cmd=editProjectTemplate&projectTemplateId='.$projecttemplateid;
		
		$varToDelete = GetParam('varname','');
		
		$projectTemplate = ProjectTemplate::GetTemplate($projecttemplateid);
		if (!is_null($projectTemplate)){
//echo 'Unsettting '.$varToDelete;
			unset($projectTemplate->Variables[$varToDelete]);
//print_r($project->Variables);
			$projectTemplate->Save();
		}
		if ($projecttemplateid == -1) {
			//go back to the project list page.
			$redir =$config['home'].'index.php?option=projectTemplateAdmin';
		}
//		echo $redir;
		Redirect($redir);
	}
		/**
	 * Changes the value of a Project variable. 
	 */
	function UpdateVar() {
		global $config,$database;
		//echo 'udpate var';
		$projecttemplateid = GetParam('pid',-1);
		$varToUpdate = GetParam('update_var_name','');
		$newVarValue = GetParam('update_var_value','');
		$project = ProjectTemplate::getTemplate($projecttemplateid); 
		if ($varToUpdate !='' and !is_null($project)) {
	//		echo 'updating variable:'.$varToUpdate .':'.$newVarValue;
			$project->Variables[$varToUpdate] = $newVarValue;
			$project->Save();
		}
		Redirect($config['home'].'index.php?option=projectTemplateAdmin&cmd=editProjectTemplate&projectTemplateId='.$projecttemplateid);
	}
	function AddVar() {
		global $config,$database;
		//echo 'Add var';
		$projecttemplateid = GetParam('pid',-1);
	//	echo $projectid;
		$varToUpdate = GetParam('update_var_name','');
		$newVarValue = GetParam('update_var_value','');
		$project = ProjectTemplate::getTemplate($projecttemplateid); 
		//Project::GetProject($projectid);
		if ($varToUpdate !='' and !is_null($project)) {
		//	echo 'adding variable:'.$varToUpdate .':'.$newVarValue;
			$project->Variables[$varToUpdate] = $newVarValue;
			$project->Save();
		}
		Redirect($config['home'].'index.php?option=projectTemplateAdmin&cmd=editProjectTemplate&projectTemplateId='.$projecttemplateid);
	}
	//----end of Project Template Management Code
	//
	function UpdateResource(){
  		global $database;
    	$projecttemplateid = GetParam('pid',null);    
    	$resourceid= GetParam('resourceId',null);
    	$contentType= GetParam('contentType2',null);
    	$playervisible = GetParam('playervisible',null);
    	($playervisible=='yes') ? $playervisible = 1 : $playervisible = 0;
    
    	if (is_null($resourceid))
    	{
      	displayMessage('You must enter a resource id.');
    	}
    	if (is_null($contentType))
    	{
      	displayMessage('You must select a content type.');
    	}
    	echo $contentType;
    	if ($contentType=='url') {
    		$content = GetParam('urlcontent',null);
    		$filename= $content;
    	} else if ($contentType=='file') {
      		if($_FILES['newcontent']['error'] == UPLOAD_ERR_OK) {
      			//file is uploaded ok
      			$tempFileName = $_FILES['newcontent']['tmp_name'];
      			$content = file_get_contents($tempFileName);
      			$contentType = $_FILES['newcontent']['type'];
      			$filename = $_FILES['newcontent']['name'];
      		}
    	} else {
    		//        displayMessage('Content type not recognised.');
    		//exit;
    	}
    	echo $content;
    	die();

      $safeContents = base64_encode($content);
      $sql = sprintf("UPDATE documenttemplates SET content='%s',contenttype='%s', playercansee='%s' WHERE doctemplateuid='%s'",
					$safeContents,
					$contentType,
					$playervisible,
					$resourceid);     
      $result = $database->database->query($sql);

	redirect("index.php?option=projecttemplateadmin&cmd=viewProjectTemplate&projectTemplateId=$projecttemplateid");

  }
  
   	function AddResource() {
 		global $database;
 		$projecttemplateid = GetParam('pid',null);    
    	$resourcename= GetParam('resourceName',null);
    	$contentType= GetParam('contentType',null);
    	$playervisible= GetParam('playervisible',null);
    	($playervisible=='yes') ? $playervisible = 1 : $playervisible = 0;
    	$contentType= GetParam('contentType',null);
    	
    	if (is_null($resourcename))
    	{
      	displayMessage('You must enter a resource name.');
    	}
    	if (is_null($contentType))
    	{
      	displayMessage('You must select a content type.');
    	}
    	if ($contentType=='url') {
    		$content = GetParam('urlcontent',null);
    		$filename= $content;
    	} else if ($contentType=='file') {
      		if($_FILES['newcontent']['error'] == UPLOAD_ERR_OK) {
      			//file is uploaded ok
      			$tempFileName = $_FILES['newcontent']['tmp_name'];
      			$content = file_get_contents($tempFileName);
      			$contentType = $_FILES['newcontent']['type'];
      			$filename = $_FILES['newcontent']['name'];
      		}
    	} else {
    		displayMessage('Content type not recognised.');
    	}
 		$safeContents = base64_encode($content);
		$sql = sprintf("INSERT INTO documenttemplates (projecttemplateid,".
				"documentid,filename,visiblename,contenttype,content,creatoruserid,icon,".
				"playercansee,deleted) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
				$projecttemplateid,
				$guid = md5(uniqid(rand(), true)),
				(sprintf($filename)=='') ? '' : sprintf($filename),
				(sprintf($resourcename)=='') ? '' : sprintf($resourcename), 
				sprintf($contentType),
				$safeContents,
				sprintf(0),
				'',
				$playervisible,
				0);
		$result = $database->execute($sql);		
		Redirect("index.php?option=projecttemplateadmin&cmd=viewProjectTemplate&projectTemplateId=".$projecttemplateid);
 	}
?>
