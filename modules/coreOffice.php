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
 * Core Office Module
 * @author Michael Hughes
 * 
 * @package TLE2
 * @subpackage Core
 */
 
	if (!defined("TLE2")) die ("Invalid Entry Point");
	if (is_null($page)) {
		$page = new Page();
	}
	$sectionTitle = (isset($project->Name)) ? $project->Name : '';
	$page->assign("sectionTitle",$sectionTitle);
	$u =$_SESSION[USER];
	if (is_null($u) | $u->id == '') {
		DisplayMessage("Not logged in or user-session information not available");
	  
	} else {
		if (isset($project)) {
			$page->assign('tasklist_tasks',CalendarItem::GetTaskItems($u->id,$project->id));
		}
/*
 * Choose a command to execute.
 */
	switch (strtolower($command) ) {
		
		case 'doaddfolder':
			doAddFolder();
			break;
		case 'dosavefolder':
			doSaveFolder();
			break;
		case 'dodeletefolder':
			doDeleteFolder();
			break;
		case 'dosendmulti':
			doSendToMultipleContent();
			break;
		case 'sendmultifile':
			SendToMultipleFiles();
			break;
		case 'sendmultitemplate':
			SendToMultipleTemplates();
			break;
		case 'sendmulticustom':
			SendToMultipleCustom();
			break;
		case 'addusingtemplate':
			AddUsingTemplate();
			break;
		case 'deleteitem':
			DeleteItem();
			break;
		case 'undeleteitem':
			UndeleteItem();
			break;
		case 'addcomment':
			AddComment();
			break;
		case 'copy':
			CopyItem();
			break;
		case 'moveitem':
			MoveItem();
			break;
		case 'viewcalendar':
			ViewCalendar();
			break;
		case 'deletecalendaritem':
			DeleteCalendarItem();
			break;
		case 'savecalendaritem':
			SaveCalendarItem();
			break;
		case 'viewcalendaritem':
			ViewCalendarItem();
			break;
		case 'editcalendaritem':
			EditCalendarItem();
			break;
		case 'newtaskitem':
			NewTaskItem();
			break;
		case 'newcalendaritem':
			NewCalendarItem();
			break;
		case 'composeemail':
			ComposeEmail();
			break;
		case 'saveemail':
			SaveEmail();
			break;
		case 'selectitem':
			ShowSelectItem();
			break;
		case 'addhtml':
			if ($config['htmleditor']!==false){
			 AddHtml();
			} else {
			DisplayMessage("HTML Editor is not enabled on this server");
			}
			break;
		case 'savehtml':
			SaveHtml();
			break;
		case 'addfile':
			AddFile();
			break;
		case 'uploaddocument':
			UploadDocument();
			break;
		case 'writeenvelope':
			$page->Title='Send Message';
			WriteEnvelope();
			break;
		case 'send':
			Send();
			break;
		case 'sendfile':
		  SendFile();
		  break;
		case 'npcsendfile':
			break;
		case 'sendcustom':
			SendCustom();
			break;
		case 'sendcanned':
			SendCanned();
			break;
		case 'sendemail':
			SendEmail();
			break;
		case 'viewdoc':
			ViewDoc();
			break;
		case 'markasunread':
			DoMarkAsUnread();
			break;
		case 'setflag':
			setflag();
			break;
		case 'unsetflag':
			unsetflag();
			break;
		case 'printdoc':
			printItem();
			break;
		case 'dismissalert':
			DismissAlert();
			break;
		case 'snoozealert':
			SnoozeAlert();
			break;
		case 'viewpublicprofile':
			ViewPublicProfile();
			break;
    case 'dismissallalerts':
		  DismissAllAlerts();
		case 'view':
		default:
		/*
		 * If no command is provided then use the "global" projectId.
		 */
		 
			View($projectId);
	     }
	
	}
	
	/**
	 * Sets the flag on a document for a user.
	 */
function setflag() {
	global $project;
	if ((!$_SESSION[USER]->projectPermissions[$project->id]) &&
		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
	$id = GetParam('documentid',-1);
	$fText = GetParam('text','Flagged');
	$doc = Document::GetDocument($id);
	if ($doc->id >-1) {
		$doc->SetFlag($fText);
	}
	Redirect('index.php?option=office&cmd=view&folder='.$doc->folderid);
}
/**
 * Unsets a user's flag on a document.
*/
function unsetflag() {
	global $project;
	if ((!$_SESSION[USER]->projectPermissions[$project->id]) &&
		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();	
	$id = GetParam('documentid',-1);
	$doc = Document::GetDocument($id);
	//need to check validity of the doucment object 1st!
	if ($doc->id >-1){
		$doc->UnsetFlag();
	//$redir=GetParam('redir',
	}
	Redirect('index.php?option=office&cmd=view&folder='.$doc->folderid);
	
}
	/**
	 * dismisses (deletes) a given alert
	 */
	 function DismissAlert() {
	 	global $page,$projectId;
	 	$id = getParam('dismiss_itemid',NULL);
	 	$alert = Alert::GetAlertById($id);
	 	if (($alert->UserId!=$_SESSION[USER]->id) && ($_SESSION[USER]->superadmin!=ALLOW)) 
    {
	 		InsufficientPermissions();
	 		return;
	 	}
	 	$alert->Delete();
	 	View($projectId);
	 	/*
	 	if (!is_null($projectId)) {
	 	 
	 	}
	 	else {
	 	 Redirect("/index.php?option=tl&cmd=list");
	 	}
	 	*/
	 }
	/**
	 * Snoozes a given alert until a specified date
	 */
	 function SnoozeAlert() {
	 	global $page,$projectId,$config;
	 	$id = getParam('snooze_itemid',NULL);
	 	$alert_message = getParam('alert_message',NULL);
	 	$alert_time = getParam('alert_time',NULL);
	 	$alert = Alert::GetAlertById($id);
	 	if (($alert->UserId!=$_SESSION[USER]->id) && ($_SESSION[USER]->superadmin!=ALLOW)){
		 	InsufficientPermissions();
		 	return;
	  }
		$alert_time= date($config['dbdatetimeformat'],strtotime($alert_time));
	 	$alert->Snooze($alert_time,$alert_message);
	 	View($projectId);
	 }
	/**
	 * Displays the contents of a folder.
	 * @global Page The output page object.
	 * @global Project Current Project object
	 * @global Metrics Metric recorder
	 * @global PluginHandler Plugin Handler object
	 * @global array System Configuration Array.
	 * @todo Check User Permissions	
	 */
	function View($projectId) {
		global $page, $wrapper, $metrics, $_PLUGINS,$project,$config,$strings,$siteSettings;

		if (!is_null($project)) {
			$pt = $project->GetProjectTemplate();
			$pt_props = $pt->Properties; //$properties['folders']
			if (isset($pt_props['homeoption']) && $pt_props['homeoption'] !='') {
				Redirect($redir="index.php?".$pt_props['homeoption']);
				exit();
			}
		} else {
			$redir = $config['home']."index.php";
			Redirect($redir);
			exit();
			die('no project');
		}
		GenerateOfficeUi($projectId);

	$folderId= getParam("folder",-1);
	$folders = Folder::getFolders($project->id);
	$folder = null;
	foreach($folders as $f) {
		trace("Folder:".$f->name);
		// should defaut to different folder depending on student or tutor/superadmin
		if ($folderId <0 and is_null($folder)) {
			trace('Setting default folder>'. $f->name);
			$folder = $f; // sets to first folder
		}
		if ($f->trashcan) {
			$trash = $f;
			break;
		}	
	}

	// get correspondence folders
	$correspondence = array();
	$deliveryFolder = $project->GetDeliveryFolder();
	$sentFolder = $project->GetSentItemsFolder();
	$correspondence[$deliveryFolder] = $folders[$deliveryFolder];
	$correspondence[$sentFolder] = $folders[$sentFolder];

	// set current folder
	if ($_SESSION[USER]->superadmin==ALLOW || $permissions['usestafftools']==ALLOW) {
		//print "folder is ".$project->GetSentItemsFolder();
		$folder = $folders[$project->GetSentItemsFolder()]; // default to sent items
	
	}	
	if ($folderId >0 ) {
		$folder = $folders[$folderId];
	}
	 
	 $page->Title=$folder->name .' Folder';
	 $sort= GetParam('sort','');
	 $fc= array();//null;	//foldercontents array;
	 if (!is_null($fc)) {
		$fc = $folder->GetContents($sort);
		foreach($fc as $i){
			$i->ui_flag=$i->GetFlag(); 
		} 
		if (is_null($page)) {
			echo "Page is not set";
		}
		$page->assign('contents',$fc);
	} else {
		$page->assign('contents',array());
	}
	$permissions = $project->GetProjectPermissions($_SESSION[USER]->id);
	$itemDelete = (($folder->allowdeletes && $permissions['DeleteItem'])
					or $permissions['DeleteAnyItem']?1:0);
	$permissions['canDelete'] = $itemDelete;
	  
	$vb = $project->GetVariabliser();
    $page->Title=$vb->Substitute($strings['MSG_OFFICE_WORKSPACE']);
    $wcagNav = "<a href='#folders'>Skip to Folders</a>";
    $page->WcagNavigation=$wcagNav;
    $page->assign('permissions',$permissions);
	$page->assign('correspondence',$correspondence);
	$page->assign('deliveryFolder',$deliveryFolder);
	$page->assign('sentFolder',$sentFolder);
	$page->assign('siteSettings', $siteSettings);
	
}
	
	function SideBarTest(){
		return "<div class=\"sidebarBox\"><a href='javascript:toggleElement(\"ui_sb_test\");'><div class=\"sidebarTitle\">Sidebar Test</div></a><div id='ui_sb_test'>Content</div></div>";
	}
	
	function ViewPublicProfile() {
		global $page, $wrapper, $metrics, $_PLUGINS,$project,$config,$strings;
		
		$page->Template="ViewPublicProfile.php.tpl";
	
		$profileUserId = getParam('id',-1);
		$profileUser = User::RetrieveUser($profileUserId,FALSE);
		$profileUser->blurb = nl2br($profileUser->blurb);
		$page->assign('profile',$profileUser);
	
		$sort= GetParam('sort','');
		$page->assign('sort',$sort);
		$page->assign('projectInActive',!$project->IsActive);
		
/*
 * Find out what folder we should be looking at! 
 */
		$folderId= getParam("folder",-1);
	//	trace("FOlderId:$folderId");
		
/*
 * Check the user has a project selected.
 */
		if (is_null($project)) {
			trace("CoreOffice:View()>Global Project is null");
			Redirect('index.php?option=message&cmd='.$strings['MSG_NO_PROJECT_SELECTED']);
			return;
		}
/*
 * Pull out any "student document" templates
 */			
		$PlayerTemplates = $project->GetDocumentTemplates(); 
		
/*
 * Set up folders.
 */
		$folders = Folder::getFolders($project->id);
		//$folderName = $folder->name;
		$trash = null;
		$folder = null;
		foreach($folders as $f) {
			trace("Folder:".$f->name);
			if ($folderId <0 and is_null($folder)) {
				trace('Setting default folder>'. $f->name);
				$folder = $f;
			}
			if ($f->trashcan) {
				$trash = $f;
				break;
			}	
		}
		$trashItems = 0;
		if (!is_null($trash)){
			$trashItems = $trash->GetItemCount();
			trace("trash count:".$trashItems);
			if ($trashItems>0) {
				$trash->icon= $config["trashFullIcon"];
			}
		}
		else {
			trace('Trash not set');
		}
		if ($folderId >0 ) {
			$folder = $folders[$folderId];
		}
/*
 * Setup permissions
 */
		$permissions = $project->GetProjectPermissions($_SESSION[USER]->id);
		trace('User Staff Tools Permission:'. (integer)$permissions['UseStaffTools']);
		dumpArray($permissions,"User Permissions");
		trace('Project lets tutors see tools:'. (integer)$project->tutorsSeeTools);
		if (
			($permissions['UseStaffTools'] and $project->tutorsSeeTools) 
			or $_SESSION[USER]->superadmin==ALLOW
			) {			
			trace("Displaying staff tools");
			//$cannedtemplates = TestObject::getCannedDocuments($project->id);
			//$blanktemplates = TestObject::getTemplates($project->id);
			$page->assign('showStaffTools',true);
/*
 * Initialise Templates since we might use them now....
 */		
			$cannedtemplates =$project->GetDocumentTemplates(ALL_TEMPLATES,FALSE);
			//print_r($cannedtemplates);
			$blanktemplates=$project->GetDocumentTemplates(ALL_TEMPLATES);		
			$page->assign('cannedDocuments',$cannedtemplates);			
			$page->assign('customDocuments',$cannedtemplates);
		}
		else {
			trace("Hiding Staff tools");
			$page->assign('showStaffTools',false);
		}
		$page->assign('documentTemplates',$PlayerTemplates);
/*
 * Work out if the user and folder permissions allow items to be
 * deleted from the folder.
 */
 		//print_r($folder);
 		//echo '<br>';
 		//print_r($permissions);
		$itemDelete = (
					($folder->allowdeletes and $permissions['DeleteItem'])
					or $permissions['DeleteAnyItem']
					?1
					:0
		);
		
		settype($itemDelete,'integer');
		//echo "<br>CD:$itemDelete";
		$permissions['canDelete'] = $itemDelete;
	/*
 * Setup display based on User's Project Template Permissions.
 */
		$page->assign('permissions',$permissions);
		$showSettingsLink = Project::IsProjectAdministrator($_SESSION[USER]->id, $project->id);	
		trace("showSettingsLink is ".$showSettingsLink);	
		$wrapper->assign('showSettingsLink',$showSettingsLink);	
		/*
		$page->assign('delete',$permissions['deleteitem']);
		$page->assign('deleteany',$permissions['deleteanyitem']);
		//$page->assign('additem',$permissions['additem']);
		$page->assign('editanyitem',$permissions['editanyitem']);
		$page->assign('edititems',$permissions['edititems']);
		*/		
/*
 * Setup the page's title with no Folder, just incase we endup with no
 * folder selected (which we shouldn't)!
 */		$sectionTitle = $project->Name;
		$page->assign("sectionTitle",$sectionTitle);
		//$pt=$project->GetProjectTemplate();
		$projectRoles = $project->GetCharacters();
		//print_r($projectRoles);
		$page->assign('projectRoles',$projectRoles);
    $page->assign('pid',$project->id);
     
		if($permissions['UseStaffTools']==ALLOW){
			
			$page->assign('ned_redir','index.php?option=office');
			$page->assign('ned_EnableTriggers',true);
			$page->assign('ned_EnableDocuments',false);
			$page->assign('ned_EnableStates',true);
			$events = $project->GetEvents();
			/*echo '<pre>';
			print_r($events);
			echo '</pre>';*/
			$page->assign('events',$events);
			//print_r($events);
			
		}
//die('here');
		/*
		 * Display the folders
		 */
		
		if (!is_null($folder)) {
			trace('Assigning Folder to Template');
			//print_r($folders);
			$page->assign('folder',$folder);
			$page->assign('folders',$folders);
			$page->assign('foldername',$folder->name);
			$page->assign('folderId',$folder->folderid);
			$sectionTitle .= " ".$folder->name;	
			$page->assign('additem',$folder->addItem);
			$page->assign('canDelete',$itemDelete);
			//echo is_null($folder)?"yes":"no";
			$sort= GetParam('sort','');
			$fc = $folder->GetContents($sort);
			foreach($fc as $i){
				$i->ui_flag=$i->GetFlag(); 
			}
			if (is_null($page)) {
				echo "Page is not set";
			}
			$page->assign('calendarEnabled',true);
			//dumpArray($fc);
			//cho "count:".count($fc);
			$page->assign('contents',$fc);
		}
		else {
			trace("Selected Folder not found!!!");
			$page->assign('folder',null);
		}
		
		$resources = $project->getResources();
		//die(print_r($resources));
		foreach ($resources as $key=>$r) {
			//$resources[$key]
      if ($r['contenttype']=='url') {
				$resources[$key]['content'] = "fff";//base64_decode(trim($project->getUrlResourceById($r['doctemplateuid']),"'"));
			}
		}
		
		//print_r($resources);
		$page->assign('resourceArray',$resources);
		$page->assign('projectUserArray',$project->getMembers());
		//print_r($project->getMembers());
		$cenabled = isset($project->GetProjectTemplate()->Properties['calendarEnabled'])?$project->GetProjectTemplate()->Properties['calendarEnabled']:true;
		$page->assign('calendarEnabled',$cenabled );
		/*
		 * Insert the list of people in to the directory for sending.
		 */
		$directoryItems =DirectoryItem::GetDirectoryItems($project->id);
		$vb = $project->GetVariabliser();

		foreach($directoryItems as &$item){
		
				if (strpos($item->name,'{') !== false) {
					$item->name=$vb->Substitute($item->name);
				}
				if (strpos($item->address,'{') !== false) {
					$item->address=$vb->Substitute($item->address);
				}
				if (strpos($item->location,'{') !== false) {
					$item->location=$vb->Substitute($item->location);
				}
				if (strpos($item->directoryvisible,'{') !== false) {
					$item->directoryvisible=$vb->Substitute($item->directoryvisible);
				}
		}
		
		$page->assign('directory',$directoryItems);
		trace("<h3>End View()</h3>");
		
	}	
	
	
	/**
	 * Displays dialog to send a document
	 * {@tutorial TLE2/coreOffice.WriteEnvelope.proc}
	 */
	function WriteEnvelope() {
		global $page,$project, $metrics, $_PLUGINS;
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
		$metrics->recordMetric('messageStarted',"<player>,<recipient>,".getDate());
		$itemid= getParam("itemid",-1);
		$to = getParam("to","");
		
		$page->Template = "OfficeWriteEnvelope.php.tpl";
		$page->Title = "Write Message";
		if ($to != "") {
			$page->assign('to',$to);
		}
		trace($_SESSION["projectRole"]);
		if ($_SESSION["projectRole"] == "tutor" or $_SESSION["projectRole"] == "admin") {
			$page->assign("staff",true);
		}
		if ($itemid >-1) {
			$body = "";
			$page->assign('body',$body);
		}
		$page->assign("documentid",$itemid);
	}
	/**
 	 * Routes a document from the current Player to another entity in the TLE.
	 * {@tutorial TLE2/coreOffice.Send.proc}
	 */
	function Send() {
		global  $metrics, $_PLUGINS,$project,$config;
		// projectpermissions record must exist but no specific permissions required
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
		$sendTs = time();
		$user= $_SESSION[USER];
		//print_r($_POST);
		$vb = $project->GetVariabliser();
		$rawSender= GetParam('sender','');
		$permissions = $project->GetProjectPermissions($_SESSION[USER]->id);
		if($permissions['UseStaffTools']==ALLOW & $rawSender!='') {
		  $sender= $rawSender;
		}else {
		  $sender = $vb->Substitute('{CHAR_PLAYER}');
		}
		$recipient = GetParam('to','');
		$vb = $project->GetVariabliser();
		$sender = $vb->Substitute('{CHAR_PLAYER}');
		$documentId = GetParam('documentid',-1);
		if ($documentId <0) {
			echo 'Invalid docid';
			return;
		}
		
		$document = Document::GetDocument($documentId); 
		
		$r = DirectoryItem::GetDirectoryItemByAddressAndProject($recipient,$project->id);
		//print_r($r);
		
		if (is_null($r)) {
			$formattedRecipient=$recipient;
		} else {
			$formattedRecipient =$r->name . ' -'.$r->address.'- ';
		}	
						
		$args = array('playerId'=>'pid','recipient'=>'recipient','sendTime'=>$sendTs,'projectid'=>$project->id);
		$origFolder = $document->folderid;
		$result = $_PLUGINS->trigger('onBeforeSendDocument',$args);
		$cancelSend = false;
		foreach($result as $r) {
			$cancelSend = $cancelSend and $r;
		}
		//echo 'User:'.$user->id;
		//echo 'recipient:'.$recipient;
		$noCopy = false;
		if (($permissions['UseStaffTools']==ALLOW) && ($rawSender!='')) {	//prevents the copy being held in the Sent items folder.
			$noCopy=true;
		}

		if (!$cancelSend) {
			if (!$noCopy) {
				$_PLUGINS->trigger('onSendDocument',$args);
				trace("Doing Send");
				//save a copy
				$sentItemsFolderId = $project->GetSentItemsFolder();
				$copy = $document->CopyDocument($sentItemsFolderId);
				$copy->sender= $user->displayName;
				$copy->recipient = $formattedRecipient;//$recipient;
				$copy->timestamp=date($config['datetimeformat'],$sendTs);
				//print_r($copy);
				$copy->Save();
				$metrics->recordMetric('messageSent',implode(",",$args),$copy->recipient);
				
				Alert::Notify($project->id,
				  "<a href=\"index.php?option=tl&cmd=select&projectid=".$project->id."\">.$document->filename.</a> has been sent in ".$project->Name,
				  NOTIFY_SEND);
			}
      
      /*
			echo '<pre>';
      print_r($user);
		  echo '</pre>';
		  */
			//die();
			
			
			//look up linked characters, using the recipient address.
			$DirectoryItem = DirectoryItem::GetDirectoryItemByAddressAndProject($recipient,$project->id);
			$LinkedProjects = $DirectoryItem->LinkedProjects;
			
		//echo '<pre>';
		//print_r($LinkedProjects);
		//echo '</pre>';
		//	echo 'Linked projects:'.count($LinkedProjects);
			//echo $document->content;
			$cLps = count($LinkedProjects);
			//echo 'Projects linked to role:'.$cLps;
			if ($cLps>0){
				foreach($LinkedProjects as $pid=>$linkedProject) {
					$pr = Project::GetProject($pid);
				//	echo 'Linked Project:'.$pr->name .'('.$pid.')';
					if (!is_null($pr)){
					//	echo 'sending...';
						
						$destFolderId = $pr->GetDeliveryFolder();
					//	echo 'Delivery Folder :'.$destFolderId;
						if ($destFolderId == -1) {
              $metrics->recordMetric('MailDeliveryFailed','Delivery Folder Not found for project.',$pr->id);
            }
						$copy = $document->CopyDocument($destFolderId);
						$copy->recipient = $formattedRecipient;
						$copy->sender=$sender;
						$copy->timestamp=date($config['datetimeformat'],$sendTs);
						$copy->Save();
						Alert::Notify(
              $pr->id,
	      "<A href=\"index.php?option=download&docuid=$copy->id&download=0\">$document->filename</a> has been received in <a href=\"index.php?option=tl&cmd=select&projectid=".$pr->id."\"".$pr->Name."</a>",
              NOTIFY_RECEIVE,
	      $copy
            );
					}
				}
			}
		  Redirect('index.php?option=office&cmd=view');
		}
		
		
		//Trigger any plugins or suchlike and record the Send document metric.
		$result = $_PLUGINS->trigger('onAfterSendDocument',$args);
		//add a comment indicating it was sent! (crafty!);
		//AddComment($UserId,$ItemType,$ItemId,$Subject,$Comment,$AdminComment = false) 
		$msg = sprintf('A copy of this document was sent to <strong>'. $formattedRecipient . '</strong> on '. date($config['datetimeformat'],$sendTs) . ' by '. $user->displayName);
		//AddComment($UserId,$displayName,$ItemType,$ItemId,$Subject,$Comment,$AdminComment = false) {	
		Commentary::AddComment($user->id,$user->displayName,'doc',$document->id,'Copy Sent',$msg);
		
		Redirect('index.php?option=office&cmd=view&folder='.$origFolder);
	}
	/**
	 * 
	 */
	function SendCustom() {
		//we should be provided with to, from and content
		global $page,$project;
		// projectpermissions record must exist but no specific permissions required
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
		
    $docTemplateId = GetParam('custom_base',-1);
    redirect("index.php?option=download&docuid=$docTemplateId&download=&docType=doc_templ&pid=".$project->id);
    /*
    $vb = $project->GetVariabliser();//new Variabliser($project->Variables);
		$sender = GetParam('sender','');
		$recipient = '{CHAR_PLAYER}';
		$folder =getParam('folder',-1);
		trace('DestFolder:' . $folder);
		$page->Template="editDocument.php.tpl";
		$page->assign('contenttype','text/html');
		$page->assign('docuid',-1);
		$page->assign('option','office');
		$page->assign('command','savehtml');
		$page->assign('showStaffTools',true);
		$page->assign('recipient',$recipient);
		$page->assign('sender',$sender);
		$page->assign('variablelist',$project->Variables);
		$page->assign('destfolderid',$folder);
		$page->assign('readonly',false);
		$page->assign('item',new Document());
		
		if ($docTemplateId >-1 ){
			$pt = $project->GetProjectTemplate();
			$docTemplate = $pt->getFullDocumentTemplate($docTemplateId);
			
			$content = $vb->Substitute($docTemplate['content']);
			$page->assign('documentContent',$content);
			echo 'fn:'.$docTemplate['filename'];
			$page->assign('filename',$docTemplate['filename']);
		}*/
	}
	/**
	 * Requires to, from, documentTemplateUid
	 * 
	 */
	function SendCanned(){
		global $database,$project,$config;
		// projectpermissions record must exist but no specific permissions required
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
		$result['contenttype']=='application/xml'
		|
		$result['contenttype']=='application/ms-infopath.xml'
        ) {
        	$contents = $vb->Substitute($contents);
				}
				$sender = GetParam('sender','');
				trace('Sender is:'.$sender); 
				$sender= $vb->Substitute($sender);
				$recipient= $vb->Substitute('{CHAR_PLAYER}');
				
				$doc= new Document() ;
				$doc->folderid = $folderId;
				$doc->filename = $vb->Substitute($result['visiblename']);
				$doc->icon =' ';
				$doc->content = $contents;
				$doc->contenttype= $result['contenttype'];
				$doc->sender = $sender;
				$doc->recipient = $vb->Substitute('{CHAR_PLAYER}');
				$doc->Save();

				$msg = sprintf('A copy of this document was sent on '. date($config['datetimeformat']) . ' by '. $user->displayName);	
				Commentary::AddComment($user->id,$user->displayName,'doc',$doc->id,'Copy Sent',$msg,true);
				
			}
			Redirect('index.php?option=office&cmd=view&folder='.$folderId);
		}
	}
	
	function SendEmail() {
		global $database,$project,$config;
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
		$email_contents = getParam("compose_email","");
		$email_subject = getParam("email_subject","");
		$user = $_SESSION[USER];
		$folderId = $project->GetDeliveryFolder();
		$vb = $project->GetVariabliser();
       	$contents = $vb->Substitute($email_contents);
		$sender = GetParam('sender','');
		$sender= $vb->Substitute($sender);
		$recipient= $vb->Substitute('{CHAR_PLAYER}');
				
		$doc= new Document() ;
		$doc->folderid = $folderId;
		$doc->filename = $email_subject;
		$doc->icon =' ';
		$doc->content = $contents;
		$doc->contenttype= "text/html";
		$doc->sender = $sender;
		$doc->recipient = $vb->Substitute('{CHAR_PLAYER}');
		$doc->Save();

		$msg = sprintf('A copy of this document was sent on '. date($config['datetimeformat']) . ' by '. $user->displayName);	
		Commentary::AddComment($user->id,$user->displayName,'doc',$doc->id,'Copy Sent',$msg,true);
				
		Redirect('index.php?option=office&cmd=view&folder='.$folderId);
			
	}
	/**
	 * adds a document using a document template
	 */
	function AddUsingTemplate() {
		global $page,$projectId;
		if (($_SESSION[USER]->projectPermissions[$projectId]['AddItem']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 
		$templateInfo = GetParam('documentTemplateInfo','');
		
		if ($templateInfo != '') {
			
			//echo $templateInfo;	
			$templateInfoParts = explode('|',$templateInfo);
			if (count($templateInfoParts==2)) {
				$addType=$templateInfoParts[1];
				//echo $addType;
				if ($addType=='text/html') {
					AddHtml($templateInfoParts[0]);
				}
				else {
					/* 
					 * we have a non-native file as a template so
					 * we copy it into the users folder!
					 */ 
					$docTemplateId = $templateInfoParts[0];
					DisplayMessage('Not Implmented non-html files as templates yet!');
				}
			}
			else {
				//can't find template so just do a basic add!
				AddHtml();
			}
		}
	}
	/**
	 * 
	 */
	function AddHtml($docTemplateId = -1) {
		global $page,$project,$projectId;		
		if (($_SESSION[USER]->projectPermissions[$projectId]['AddItem']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 
		$destFolderId=getParam('destfolderid',-1);
		$page->Template="editDocument.php.tpl";
		$page->assign('contenttype','text/html');
		$page->assign('docuid',-1);
		$page->assign("option","office");
		$page->assign('command','savehtml');
		$page->assign('filename','File Created on '.date('Y-m-d G:i'));
		$page->assign('destfolderid',$destFolderId);
		$page->assign('redir','index.php?option=office&cmd=view&folder='.$destFolderId);
		$page->assign('item',new Document());
		$page->assign('showeditor',true);
		$page->assign('documentContent','');
		/*
		if ($docTemplateId >0) {	//we have a template to display!
			$pt = $project->GetProjectTemplate();
			$docTemplate = $pt->getFullDocumentTemplate($docTemplateId);
			$vb =$project->GetVariabliser();// new Variabliser($project->Variables);
			$content = $vb->Substitute($docTemplate['content']);
			$page->assign('documentContent',$content);
		}
		*/
		
		
	}
	/**
	 * Pulls out values required to successfully add an HTML file to the system.
	 *	 */
	function SaveHtml() {
		global $project,$config;
		$filename = getParam("filename","");
		$content =getParam("documentContent","");
		$folderId = getParam("destfolderid",-1);
		$docid =GetParam("docuid",-1);
		$sender = GetParam('sender','');
		$recipient = GetParam('recipient','');
		dumpArray($project->Variables,"SaveHTML()>project Variables");
		//restrict any substitution to staff only!
		trace("Restricting variable substitution");
		/*
    if (
			$_SESSION[USER]->isProjectStaff($project->projecttemplateuid) 
			or $_SESSION[USER]->isProjectTemplateStaff($project->projecttemplateuid)
		) {
			trace("doing subs");
			$vb = $project->GetVariabliser();//new Variabliser($project->Variables);
			$vb->DumpVariableTable();
			$filename = $vb->Substitute($filename);
			$recipient = $vb->Substitute($recipient);
			$sender= $vb->Substitute($sender);
			$content= $vb->Substitute($content);
		}
		*/
	//	die($content);
		$canSend = true;
		if ($filename=="") {
			$canSend = false;
			DisplayMessage('Cannot save document with no name!');
			
		}
		if ($folderId==-1) {
			$canSend = false;
			trace("FolderId is -1");
			DisplayMessage('Error! Trying to save document with into unknown folder!!');
		}
		if ($canSend){
			//doSaveHtml($filename,$content,$folderId,$docid);
			$ts = time();
			
			trace("Saving");
			$item = Document::GetDocument($docid);
			$item->filename = $filename;
			$item->content = $content;
			$item->folderid = $folderId;
			$item->contenttype="text/html";
			$item->sender=$sender;
			$item->recipient = $recipient;
			$item->timestamp = date($config['datetimeformat'],$ts);
			$item->Save();
			//print_r($item);
			if (GetParam('savecontButton','')=='')
			{
				//echo 'return to folder';		
				Redirect('index.php?option=office&folder='. $item->folderid);
			}
			else {
				//echo 're-open doc';
				Redirect('index.php?option=office&cmd=viewdoc&id='. $docid);
			}
		}
		//trace("Not saving..this is a problem");
	}
	/**
	 * Displays a form to allow a player to upload a new document into the system.
	 */
	function AddFile() {
		global $page, $projectId;
		if (($_SESSION[USER]->projectPermissions[$projectId]['AddItem']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) {
				InsufficientPermissions();
			}  
		$page->Template="editDocument.php.tpl";
		$i = new Document();
		$i->content='';
		$i->contenttype='';
		$page->assign('item',$i);
		$page->assign('contenttype',$i->contenttype);
		$page->assign('documentContent','');
		$page->assign('option','office');
		$page->assign('hidedownload',true);
		$page->assign('command','uploaddocument');
		$page->assign('destfolderid',getparam("destfolderid",-1));
		$page->assign('enctype','enctype="multipart/form-data"');
		$page->assign('redir','JavaScript:history.go(-1);');
	}
	/**
	 * Pulls out the values required to insert an uploaded document in to the datbase.
	 */
	function UploadDocument()
	{
		global $project,$_PLUGINS;
//		trace("$_SESSION[USER]->projectPermissions[$project->id] is : ".$_SESSION[USER]->projectPermissions[$project->id]);
	//	if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
	//		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
			trace("<pre>project id is ".$project->id." <br> ".print_r($_SESSION[USER],true)."</pre>");
		
		$folderId = GetParam('destfolderid',-1);	//todo should be passed in.
		//$filename = GetParam('filename','');
          
		$filename = $_FILES['uploadDocument']['name'];
		$docuid = GetParam('docuid',-1);
		//print_r($_POST);
		$canUpload= true;
		if ($folderId ==-1 ){
			$canUpload = false;
			$page->Messages[]="Cannot upload to that folder.";
		}
		if ($filename == "" ) {
            $canUpload = false;
			$page->Messages[] ="You must provide name for the file.";
		}
		if ($canUpload) {
			SaveDocument($filename,$folderId,$docuid);
			$result = $_PLUGINS->trigger('onAfterUploadDocument',array($_FILES,$filename,$folderId,$docuid));
			if (PluginHandler::PluginResultIsOk($result)) {
			 Redirect("index.php?option=office&folder=$folderId");
			 //header("Location:index.php?option=office&folder=$folderId");
			}
            else {
                DisplayMessage("Plugin should have redirected you!");
            }
		}
        else {
            DisplayMessage(join($page->Messages,"<br />"));//"You can't upload in to that folder");
        }   
	}
	/**
	 * Performs the SQL steps to either insert a new document into the database or update
	 * an existing one.
	 * 
	 * @todo Check User Permissions
	 * @param string $filename The name of the file once in the system.
	 * @param integer $folderId Id of the folder that the file is stored in.
	 * @param integer $docid ID of an existing document to update (or -1 if inserting a new one.)
	 */
	function SaveDocument($filename="",$folderId=-1,$docid=-1){
		global $database,$_PLUGINS,$metrics;
	 
		 //die('ID:'.$docid);
		$sql ="";
		
		if ($folderId == -1) {
			
			die("You cannot upload to that folder.");
		}
		//dumpArray($_POST);
		//dumpArray($_FILES);
		$item = Document::GetDocument($docid);
		//print_r($item);
		$item->filename = $filename;
		//print_r($_FILES);
  	if ($_FILES['uploadDocument']['error'] == UPLOAD_ERR_OK){
  		
		$fileSize = $_FILES['uploadDocument']['size'];
  		if ($fileSize ==0) {
  			$page->Messages[] = "Did not upload file successfully";
  		}
  		$filename = $_FILES['uploadDocument']['name'];
  		$tmpName = $_FILES['uploadDocument']['tmp_name'];
  		//echo $_FILES['updloadDocument']['error'];
  		
  		$type= $_FILES['uploadDocument']['type'];
  		//echo "*$fileSize*";
  		$content = file_get_contents($tmpName);
  		//$data =unpack("H*hex",$rawcontent);
  		//$content = "0x".$data['hex'].")";
  		//echo $content;
  		/*$fp = fopen($tmpName,'r');
  		$content = fread($fp,filesize($tmpName));
  		
  		if ($content !== FALSE){
  		*/

  		/*
  		  fclose($fp);
  		  }
  		  */
  		
  		$item->content = $content;
  		$item->contenttype=$type;
  		$metrics->recordMetric('FileUploaded',"Filename:$filename","Size:$fileSize");
		}
		if ($_FILES['uploadDocument']['error'] == UPLOAD_ERR_NO_FILE) {
			//trace('No file for uploading, using existing content as new content');
			$item->GetContent();
		}
		$item->folderid = $folderId;
		
		$item->Save();
		$_PLUGINS->trigger('onAfterUploadDocument',array(&$filename,&$folderId,&$docid));
		return $item->id;
		//print_r($item);
		//die();
	}
	/**
	 * Displays either an TINY MCE editor to edit HTML files or a Download/upload for non-html
	 * files.
	 * 
	 * Hooks: 
	 * onViewDocument, onDisplayContent
	 */
	function ViewDoc() {
		global $page,$project,$database,$_PLUGINS,$metrics,$command,$option,$config;
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
					
		$documentUid = GetParam("id",-1);
		if(is_null($project)) {
			DisplayError(102);
			return;
		}
		if ($documentUid >0) {
			//$documentMetaData = ProjectTemplate::getDocumentTemplate($documentUid);
			/*$sql = sprintf("SELECT * FROM documents WHERE documentuid = %s",$documentUid);
			$results = $database->queryAssoc($sql);
			*/
			$item= Document::getDocument($documentUid);

			if ($command == "viewdoc" and ($item->id ==-1)){
				trace("Item doesn't exist any more ;-)");
				DisplayMessage('Item does not exist.');
				return;
			}
			if (!is_null($item)){
			
				$directoryItems =DirectoryItem::GetDirectoryItems($project->id);
				$vb = $project->GetVariabliser();

				foreach($directoryItems as &$d_item){
				
					if (strpos($item->name,'{') !== false) {
						$d_item->name=$vb->Substitute($d_item->name);
					}
					if (strpos($item->address,'{') !== false) {
						$d_item->address=$vb->Substitute($d_item->address);
					}
					if (strpos($item->location,'{') !== false) {
						$d_item->location=$vb->Substitute($d_item->location);
					}
					if (strpos($item->directoryvisible,'{') !== false) {
						$d_item->directoryvisible=$vb->Substitute($d_item->directoryvisible);
					}
				}
				$page->assign('directory',$directoryItems);
				$user = $_SESSION[USER];
				$permissions = $project->GetProjectPermissions($_SESSION[USER]->id);		
				
				if (
					($permissions['UseStaffTools'] and $project->tutorsSeeTools) 
					or $_SESSION[USER]->superadmin==ALLOW
					) {	
					
					$cannedtemplates =$project->GetDocumentTemplates(ALL_TEMPLATES);
					$blanktemplates=$project->GetDocumentTemplates(ALL_TEMPLATES);		
					$projectRoles = $project->GetCharacters();
					
					$page->assign('showStaffTools',true);
					$page->assign('cannedDocuments',$cannedtemplates);			
					$page->assign('customDocuments',$cannedtemplates);					
					$page->assign('projectRoles',$projectRoles);
					$page->assign('pid',$project->id);
					$page->assign('ned_redir','index.php?option=office');
					$page->assign('ned_EnableTriggers',true);
					$page->assign('ned_EnableDocuments',false);
					$page->assign('ned_EnableStates',true);
					$page->assign('events',$project->GetEvents());
				}
				//dumpArray($result);

				$folder = Folder::getFolder($item->folderid);
				
				$itemDelete = (
					($folder->allowdeletes and $permissions['DeleteItem'])
					or $permissions['DeleteAnyItem']
					?1
					:0
				);
		
				settype($itemDelete,'integer');
				$permissions['canDelete'] = $itemDelete;				
				
				$item->MarkRead();
				//print_r($item);
				$page->assign('user',$user);
				//				/echo "**".$item->IsRead()."**";
				$metrics->recordMetric('documentOpened',$item->id,date('c'));
				$_PLUGINS->trigger('onOpenDocument',$item);
				$page->Template = "editDocument.php.tpl";
				$page->assign('item',$item);
				$page->assign('readonly',!$folder->additem);
				$page->assign('projectName',$project->Name);
				
				$page->assign('projectIsActive',$project->IsActive);
				$page->assign('projectInActive',!$project->IsActive);
				$page->assign('documentName','A Document');
				$page->assign('option','office');
				$page->assign('recipient',$item->recipient);
				$page->assign('sender',$item->sender);
				$page->assign('contenttype',$item->contenttype);
				$page->assign('filename',$item->filename);
				$page->assign('docuid',$item->id);
				$page->assign('flag',$item->GetFlag());
				$page->assign('destfolderid',$item->folderid);
				$page->assign('downloadhooks','');//this is for hooking in links to allow downloads in different version
				$page->assign('permissions',$permissions);
				
			$resources = $project->getResources();
			foreach ($resources as $key=>$r) {
				if ($r['contenttype']=='url') {
					$resources[$key]['content'] = trim($project->getUrlResourceById($r['doctemplateuid']),"'");
				}
			}
			//	print_r($resources);
			$page->assign('resourceArray',$resources);
			$page->assign('projectUserArray',$project->getMembers());				
				
				
				if (($permissions['UseStaffTools'] and $project->tutorsSeeTools) 
					or $_SESSION[USER]->superadmin==ALLOW) {			
					trace("Displaying staff tools");
					//	$cannedtemplates = TestObject::getCannedDocuments($project->id);
					//$blanktemplates = TestObject::getTemplates($project->id);
					$page->assign('showStaffTools',true);
				/*
				 * Initialise Templates since we might use them now....
 				*/		
					$cannedtemplates =$project->GetDocumentTemplates(ALL_TEMPLATES,FALSE);
					//print_r($cannedtemplates);
					$blanktemplates=$project->GetDocumentTemplates(ALL_TEMPLATES);		
					$page->assign('cannedDocuments',$cannedtemplates);			
					$page->assign('customDocuments',$cannedtemplates);
				} else {
					trace("Hiding Staff tools");
					$page->assign('showStaffTools',false);
				}				
				
				$folders = Folder::getFolders($project->id);
				//$folderName = $folder->name;
				$trash = null;
				$folder = null;
				foreach($folders as $f) {
				//	trace("Folder:".$f->name);
				//	if ($folderId <0 and is_null($folder)) {
				//		trace('Setting default folder>'. $f->name);
				//		$folder = $f;
				//	}
					if ($f->trashcan) {
						$trash = $f;
						break;
					}	
			}
			$trashItems = 0;
			
			if (!is_null($trash)){
				$trashItems = $trash->GetItemCount();
				trace("trash count:".$trashItems);
				if ($trashItems>0) {
					$trash->icon= $config["trashFullIcon"];
				}
			} else {
				trace('Trash not set');
			}
				
			// get correspondence folders
			$correspondence = array();
			$deliveryFolder = $project->GetDeliveryFolder();
			$sentFolder = $project->GetSentItemsFolder();
			$correspondence[$deliveryFolder] = $folders[$deliveryFolder];
			$correspondence[$sentFolder] = $folders[$sentFolder];
			
			$page->assign('folders',$folders);	
			$page->assign('correspondence',$correspondence);
			$page->assign('deliveryFolder',$deliveryFolder);
			$page->assign('sentFolder',$sentFolder);			
				
				
			$cenabled = isset($project->GetProjectTemplate()->Properties['calendarEnabled'])?$project->GetProjectTemplate()->Properties['calendarEnabled']:true;
			$page->assign('calendarEnabled',$cenabled );				
				
				
				if (strtolower($item->contenttype) =="text/html") {
					$item->GetContent();
					$page->assign('documentContent',$item->content);
					$page->assign('command','savehtml');
				}	
				else {
					/*
					 * See if we have any plugins that can handle this item!
					 */
					$resid = sprintf('%s.%s.%s',$project->id,'doc',$item->id);					 
					$outputContent = $_PLUGINS->trigger('onDisplayContent',array($item->contenttype,$resid,$item->content));
					//print_r($outputContent);
					//and display the plugins output as the content of the file.
					$outHtml ='';
					foreach($outputContent as $key=>$value){
						if ($value !== false and $value != '') {
							$outHtml .= $value;
						}
					}
					if ($outHtml != ''){
						$page->assign('documentContent',$outHtml);
					}					
					$page->assign('command','uploaddocument');
					$page->assign('enctype','enctype="multipart/form-data"');
				}
				
				$comments = Commentary::GetCommentary($item->itemType,$item->id);
				//dumpArray($comments);		
				$page->assign('commentary',$comments);	
				$page->assign('comment_itemtype',"doc");
				$page->assign('comment_id',$item->id);
				$page->assign('comment_redir',"index.php?option=office&cmd=viewdoc&id=".$item->id);
				//echo 'PermL'.$project->GetProjectPermission('UseStaffTools',$user->id);
				if (
					($project->GetProjectPermission('UseStaffTools',$user->id)) ||
					($user->superadmin==ALLOW)
				) {				
					$page->assign('admincommentary_enabled', true);
					$adminComments = Commentary::GetAdministrativeCommentary($item->itemType,$item->id);
					$page->assign('admincommentary_comments',$adminComments);	
					$page->assign('admincommentary_itemtype',"doc");
					$page->assign('admincommentary_id',$item->id);
					$page->assign('admincommentary_redir',"index.php?option=office&cmd=viewdoc&id=".$item->id);
				}
				else {
					$page->assign('admincommentary_enabled', false);
				}
				if ($project->GetProjectPermission('UseStaffTools',$user->id)) {
					$page->assign('canMaskerade',true);
					$page->assign('commentary_projectRoles',$project->GetCharacters());
				}
			}
			$redir = GetParam("redir","index.php?option=office&cmd=view&folder=".$item->folderid);
			if ($redir != "") {
				$page->assign("redir",$redir);
			}
			$_SESSION['previouspage'] = $_SERVER['PHP_SELF'];
			$_SESSION['exitpage']= $redir;
			
			/*
			 Setup Plugin Hooks
			*/
			/* Sidebar Hook */
			$sb_results= $_PLUGINS->trigger('extendOfficeSidebar',array($project->id,$item->folderid));
			$sideBar_extension='';
			foreach($sb_results as $result) {
				$sideBar_extension.=$result;
			}
			$page->assign('sidebar_extensions',$sideBar_extension);
		  
			/* Document Properties Hook */
			$docProps_results= $_PLUGINS->trigger('extendDocumentProperties',array($item));
			$docProps_extension='';
			foreach($docProps_results as $result){
				if ($result!==false) {
					$docProps_extension.=$result;
				}
			}
			$page->assign('docProperties_extensions',$docProps_extension);
		} else {
			DisplayMessage('Item does not exist.');
		}
	}
	/**
	 * Displays the calendar view.
	 */
	function ViewCalendar(){
		global $page,$database,$project,$config,$_PLUGINS;
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
		$cenabled = isset($project->GetProjectTemplate()->Properties['calendarEnabled'])?$project->GetProjectTemplate()->Properties['calendarEnabled']:true;
		if (!$cenabled) {
			DisplayMessage('Calendar module is disabled for this simulation.');
			return;
		}
		//$page->assign('calendarEnabled',$cenabled );
		$permissions = $project->GetProjectPermissions($_SESSION[USER]->id);
		$page->Template= 'viewCalendar.php.tpl';
		//$page->assign('resourceArray',$project->getResources());
		//$page->assign('projectUserArray',$project->getMembers());
				
		$fd = GetParam("fromdate","");
		$td = GetParam("todate","");
		if($fd =='') {
			$fromDate = time();
		}else {
			$fromDate = strtotime($fd);
		}
		
		$today=date('Y-m-d');
		//$moveToday = date('Y-m-d',time());
		$movePrevMo = date('Y-m-d',strtotime('-1 month',$fromDate));
		$moveNextMo = date('Y-m-d',strtotime('+1 month',$fromDate));
		$movePrevYr= date('Y-m-d',strtotime('-1 year',$fromDate));
		$moveNextYr = date('Y-m-d',strtotime('+1 year',$fromDate));
		
		$thismonth = (int)date('m',$fromDate);
		$thisyear = (int)date('Y',$fromDate);
		
		$numdaysinmonth = cal_days_in_month(CAL_GREGORIAN,$thismonth,$thisyear);
		$jd = cal_to_jd(CAL_GREGORIAN,$thismonth,date(1),$thisyear);
		$startday =jddayofweek($jd,0);
		$monthname = jdmonthname($jd,1);
		/*
		 * Generate the HTML for the view
		 */
		$html = '';
		$emptycells = 0;
		for($counter =0; $counter<$startday;$counter++){
			$html.='<td class=\'calendar_no_day\'>&nbsp;</td>';
			$emptycells++;
		}
		$rowcounter = $emptycells;
		$numinrow = 7;
		for($counter = 1; $counter <= $numdaysinmonth;$counter++) {
			$rowcounter++;
			$date = date('Y-m-d', strtotime("$thisyear-$thismonth-$counter"));
			$items= CalendarItem::GetItemsForDate($project->id,$date);
			
			if ($date == $today) {
				$html.="<td class='calendar_today' valign='top'>";
				
			}
			else{
				$html.="<td class='calendar_day' valign='top'>";
			}
			//$html.=$date.':'.$today;
			$html.="<A href='index.php?option=office&cmd=newcalendaritem&startdate=$thisyear-$thismonth-$counter'>";
			if ($date == $today) {
				$html.="<strong>$counter</strong>";
			}
			else
			{
				$html.="$counter";
			}
			$html.="</a><br/>";
			foreach($items as $item){
				$itemTime = strtotime($item->startdate);
				$html.= date('H:i',$itemTime) .'> <br/>';
				//$html.=$item->id;
				if($item->istask) {
					$html.=$config['task_icon_small']!=''?'<img title="Task" src="'.$config['task_icon_small'].'">':'[t]';
				}
				if($item->completed){
					$html.='<span class=\'taskcomplete\'>';
				}
				$html.= '<a href="';
				$html.=$config['home'];
				$html.='index.php?option=office&cmd=viewcalendaritem&id='.$item->id.'">';
				$html.=$item->title;
				$html.='</a>';
				if($item->completed){
					$html.='</span>';
				}
				$html.='<br/>';
			}
			$html.="</td>";
			if($rowcounter % $numinrow ==0 ){
				$html.='</tr>';
				if ($counter<$numdaysinmonth) {
					$html.= '<tr>'; 
				}
				$rowcounter = 0;
			}
		}
		$numcellsleft = $numinrow -$rowcounter;
		if($numcellsleft != $numinrow) {
			for ($counter= 0 ; $counter<$numcellsleft;$counter++) {
				$html.='<td class=\'calendar_no_day\'>&nbsp;</td>';
				$emptycells++;
			}
		}
		
				$folders = Folder::getFolders($project->id);
		//$folderName = $folder->name;
		$trash = null;
		$folder = null;
		foreach($folders as $f) {
		//	trace("Folder:".$f->name);
		//	if ($folderId <0 and is_null($folder)) {
		//		trace('Setting default folder>'. $f->name);
		//		$folder = $f;
		//	}
			if ($f->trashcan) {
				$trash = $f;
				break;
			}	
		}
		$trashItems = 0;
		if (!is_null($trash)){
			$trashItems = $trash->GetItemCount();
			trace("trash count:".$trashItems);
			if ($trashItems>0) {
				$trash->icon= $config["trashFullIcon"];
			}
		}
		else {
			trace('Trash not set');
		}
		$page->assign('folders',$folders);
		// get correspondence folders
		$correspondence = array();
		$deliveryFolder = $project->GetDeliveryFolder();
		$sentFolder = $project->GetSentItemsFolder();
		$correspondence[$deliveryFolder] = $folders[$deliveryFolder];
		$correspondence[$sentFolder] = $folders[$sentFolder];
		
		$page->assign('correspondence',$correspondence);
		$page->assign('sentFolder',$sentFolder);
		$page->assign('deliveryFolder',$deliveryFolder);
		//$page->assign('moveToday',$moveToday);
		$page->assign('movePrevYr',$movePrevYr);
		$page->assign('moveNextYr',$moveNextYr);
		$page->assign('movePrevMo',$movePrevMo);
		$page->assign('moveNextMo',$moveNextMo);
		$page->assign('monthname',$monthname);
		$page->assign('thisyear',$thisyear);
		$page->assign('fromdate',date('r',$fromDate));
		$page->assign('calhtml',$html);
		$resources = $project->getResources();
		foreach ($resources as $key=>$r) {
		  //print_r($r);
    		if ($r['contenttype']=='url') {
			  	$url = trim($project->getUrlResourceById($r['doctemplateuid']),"'");
			  	$url = base64_decode($url);
				$v = $project->GetVariabliser();
				$url = $v->substitute($url);
				//echo "URL:$url";
      			$resources[$key]['content'] =$url; 
			}
		}
		$page->assign('resourceArray',$resources);
		$page->assign('projectUserArray',$project->getMembers());
		$page->assign('calendarEnabled',true);
		$page->assign('projectInActive',!$project->IsActive);
		$page->assign('pid',$project->id);
		
		
			if (
			($permissions['UseStaffTools'] and $project->tutorsSeeTools) 
			or $_SESSION[USER]->superadmin==ALLOW
			) {			
			trace("Displaying staff tools");
			//$cannedtemplates = TestObject::getCannedDocuments($project->id);
			//$blanktemplates = TestObject::getTemplates($project->id);
			$page->assign('showStaffTools',true);
			$page->assign('ned_redir','index.php?option=office');
			$page->assign('ned_EnableTriggers',true);
			$page->assign('ned_EnableDocuments',false);
			$page->assign('ned_EnableStates',true);
			$page->assign('events',$project->GetEvents());				
/*
 * Initialise Templates since we might use them now....
 */		
			$cannedtemplates =$project->GetDocumentTemplates(ALL_TEMPLATES,FALSE);
			//print_r($cannedtemplates);
			$blanktemplates=$project->GetDocumentTemplates(ALL_TEMPLATES);		
			$page->assign('cannedDocuments',$cannedtemplates);			
			$page->assign('customDocuments',$cannedtemplates);
		}
		else {
			trace("Hiding Staff tools");
			$page->assign('showStaffTools',false);
		}
		//$_PLUGINS->registerFunction('extendCalendarSidebar','SidebarTest','SidebarTest');
		$results= $_PLUGINS->trigger('extendCalendarSidebar');
		$sideBar_extension='';
		foreach($results as $result){
      //echo($result);
      $sideBar_extension.=$result;
    }
		$page->assign('sidebar_extensions',$sideBar_extension);
	}
	/**
	 * @todo Check User Permissions	
	 * 
	 */
	function NewTaskItem() {
		global $page,$project;
		$page->Template="editCalendarItem.php.tpl";
		$startDate = GetParam('startdate','');
		
		$members = $project->getMembers();
		$item = new CalendarItem(null,$project->id);
		//$page->assign('groupMembers',$members);
		if ($startDate !='') {
			$item->startdate=date('Y-m-d H:i',strtotime($startDate));
			$item->enddate=date('Y-m-d H:i',strtotime($startDate));
		}
		
		$t = time();
		$item->istask=1;
		$listitems = $project->GetDocuments();
		$evlistitems = $project->GetCalendar();
		$page->assign('eventitem',$item);
		$page->assign('listitems',$listitems);
		$page->assign('evlistitems',$evlistitems);
	}
	/**
	 * Displays form to create new calendar item.
	*/
	function NewCalendarItem(){
		global $page,$project;
		$page->Template="editCalendarItem.php.tpl";
		$startDate = GetParam('startdate','');
		$overrideProjectId=GetParam('id',0);
		if ($overrideProjectId !=0) { //should check user is member of the project too!
		  $item = new CalendarItem(null,$overrideProjectId);
		} else {
		  $item = new CalendarItem(null,$project->id);
		}
		$page->assign('eventitem',$item);
		if ($startDate !='') {
			$item->startdate=date('Y-m-d H:i',strtotime($startDate));
			$item->enddate=date('Y-m-d H:i',strtotime($startDate));
		}
		$t = time();
		$listitems = $project->GetDocuments();
		$evlistitems = $project->GetCalendar();
		$page->assign('listitems',$listitems);
		$page->assign('evlistitems',$evlistitems);
	}

	function ComposeEmail() {
		global $page,$project;
		$folderId = GetParam('destfolderid','');
		$page->Template="composeEmail.php.tpl";
		$page->assign('folderid',$folderId);
	}
	
	function SaveEmail() {
		global $page,$project;
		$folderId = GetParam('folderid','');
		$subject = GetParam('email_subject','');
		$content = GetParam('email_content','');
		$item = Document::GetDocument(-1);
		$item->filename = $subject;
  		$item->content = $content;
  		$item->contenttype = 'text/html';
		$item->folderid = $folderId;
		$item->Save();
		Redirect('index.php?option=office&cmd=view&folder='.$folderId);
	}
	
	function ViewCalendarItem() {
		global $page,$project;
		$context = getParam('context',NULL);
		$alertid = getParam('id',NULL);
		if ($context=='alert') {
			// check user id is correct
			$alert = Alert::GetAlertById($alertid);
			if ($_SESSION[USER]->superadmin!=ALLOW && $alert->UserId != $_SESSION[USER]->id) {
                InsufficientPermissions();
                return;
            }
			// get project id from alert	
			if ($alert->ItemType=='calendar') {
				$item = CalendarItem::getCalendarItem($alert->ItemId);
				$project = Project::GetProject($item->projectid);
				changeProject($project->id);
			}
		} else {
			// do standard check
			if ((!$_SESSION[USER]->projectPermissions[$project->id]) &&
				($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
		}	
		editCalendarItem();
		$page->assign('readonly',1);
	}
		/**
	 * Displays form to create new calendar item.
	 */
	function EditCalendarItem(){
		global $page,$project;
		if (($_SESSION[USER]->projectPermissions[$project->id]['EditItems']!=ALLOW) &&
			($_SESSION[USER]->projectPermissions[$project->id]['EditAnyItem']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$page->Template="editCalendarItem.php.tpl";
		$itemId = GetParam("id",-1);
		$user = $_SESSION[USER];
		$userId = $user->id;
		
		$method = getParam('method',NULL);
		if ($method=='addLinkedItem2Event') {
			$linkItemId = getParam('linkItemId',NULL);
			$linkItemType = getParam('linkItemType',NULL);
			//get 'doc' type (could be comment, cal, etc.)
			$calendaritem = CalendarItem::getCalendarItem($itemId);
			//print_r($calendaritem);
			$calendaritem->AddLinkedItem($linkItemId,$linkItemType); //not always docs
		}
		
		if ($method=='addLinkedEvent2Event') {
			$linkItemId = getParam('linkItemId',NULL);
			$linkItemType = getParam('linkItemType',NULL);
			$calendaritem = CalendarItem::getCalendarItem($itemId);
			$calendaritem->AddLinkedItem($linkItemId,$linkItemType);
		}
		
		
		//echo "Calendar Item:$itemId";
		//echo 'ProjectId :'. $project->id;
		if ($itemId != -1 ){
			$item = CalendarItem::getCalendarItem($itemId);//,$project->id);
			
			if ($item->projectid>0 && $item->projectid != $project->id) {
				//we have to switch the user to the correct project.
				$newproject = Project::getProject($item->projectid);
				if (!is_null($newproject)) {
				  changeProject($item->projectid);
				  Redirect('index.php?option=office&cmd=viewcalendaritem&id='.$itemId);
				}
				else {
          return;
        }
		}
			
			
			
			//print_r($item);
			//$members = $project->getMembers();
			//print_r($item->members);
			$page->assign('eventitem',$item);
			$alertItem = Alert::GetAlert('calendar',$itemId,$userId);
			//print_r($alertItem);
			$page->assign('alertitem',$alertItem);
			$listitems = $project->GetDocuments();
			$page->assign('listitems',$listitems);
			$evlistitems = $project->GetCalendar();
			$page->assign('evlistitems',$evlistitems);
			$linkitems = $item->GetLinkedItems();
			foreach ($linkitems as $key=>$l) {
				if ($l['itemType']=='doc') {
					$doc = Document::GetDocument($l['id']);
					$linkitems[$key]['name'] = $doc->filename;
				} else {
					$ev = CalendarItem::GetCalendarItem($l['id']);
					$linkitems[$key]['name'] = $ev->title;
					$linkitems[$key]['type'] = ($ev->istask) ? 'task' : 'event';
				}
			}
			$page->assign('linkitems',$linkitems);
			//$page->assign('groupMembers',$item->members);
			//$page->assign('subject',$item->subject);
			//$page->assign('location',$item->location);
			$t = time();
			//$page->assign('starttime',$item->startTime);
			//$page->assign('endtime',$item->endTime);
			//$page->assign('id',$item->id);
		}
	}
	function DeleteCalendarItem() {
		global $page,$project;
		$itemId = GetParam("id",-1);
		trace ("Deleting Calendar Item:$itemId");
		if ($itemId != -1 ){
			$item = CalendarItem::getCalendarItem($itemId);
			$item->Delete();
		}
		ReDirect('index.php?option=office&cmd=viewcalendar');
	}
	/*
	function SaveCalendarItem() {
	 global $project;
    SaveProjectCalendarItem($project->id,$project->id);
  }
  */
	/**
	 * 
	 * @todo Check User Permissions	
	 */
	function SaveCalendarItem() {
		global $project,$config;
		//dumpArray($_POST);
		$itemId = GetParam('id',-1);
		if ($itemId == -1) {
			//insert record
			//echo "Inserting PID: ".$project->id;
			$item = new CalendarItem(null,$project->id);
			//$item->projectId = $project->id;
		}
		else {	
			//echo "Updating PID: ".$project->id;
			$item = CalendarItem::getCalendarItem($itemId);
		}	
		//echo "SUBECJT: ".$_POST['subject'];
		$item->title = GetParam('title',"New Event");	
		$item->content = GetParam('content','');
		$item->istask = GetParam('isTask',false);
		$item->location = GetParam('location','');
		$item->startdate = GetParam('starttime','');
		$item->enddate = GetParam('endtime','');
		$item->completed=GetParam('completed',0);
		//$item->alarmTime = strtotime(GetParam('reminder',"-1 hours"),$item->startTime);
		//$project = Project::getProject($membersProjectId);
		$project = Project::getProject($project->id);

		$memList = $project->getMembers();
		$assignments = array();
		foreach($memList as $member) {
			$assigned = GetParam("assignedTo_".$member->id,"0");
			//echo "Assigned: $assigned";
			$assigned= ($assigned);
			$assignments[$member->id] = array("displayname"=>$member->displayName,"assigned"=>$assigned);
		}
		//print_r($assignments);
		$item->members = $assignments;
		/*
		foreach($memberList as $member){
				$out[$member['userid']] = array("displayname"=>$member['displayname'],"assigned"=>false);
				
				foreach($assigned as $assignee){
					if($assignee['userid'] == $member['userid']) {
						$out[$member['userid']]["assigned"] = true;
						break;
					}
				}
			}*/
		//echo print_r($item);
		$item->save();
		//Update record
		$viewDate= date('Y-m-d',strtotime($item->startdate));
		//update any attached alert.
		$user= $_SESSION[USER];
		//echo GetParam('alert_time','NA');
		DoUpdateAlert($item->id,'calendar',$user->id,$item->title,GetParam('alert_message',''),GetParam('alert_time'));
		//Redirect
		ReDirect("index.php?option=office&cmd=viewCalendar&fromdate=$viewDate");
	}
	function DoUpdateAlert($itemId,$itemType,$userId,$Title,$message,$alerttime){
		global $config;
		trace( "IT:$itemType");
		trace("ID:$itemId");
		$alertItem = Alert::GetAlert($itemType,$itemId,$userId);
		if ($message!='' & $alerttime !='') {
			//echo "DT:$alerttime";
			$date= date($config['dbdatetimeformat'],strtotime($alerttime));
			//echo "Date:$date";
			if (
				$alertItem->AlertTime != $date | 
				$alertItem->Title != $Title | 
				$alertItem->Message != $message
				) 
			{
				trace('Alert details changed, updating');
				$alertItem->ItemType = $itemType;
				$alertItem->AlertTime = $date;
				$alertItem->Title = $Title;
				$alertItem->UserId = $userId;
				$alertItem->Message = $message;
				$alertItem->Save();	
			}
			else{
				trace('Alert has not changed! Not updating');
			}
		}
		else {
			//echo "No update $Title:$message:$alerttime";
			if ($alertItem->id != -1 & $message=='' & $alerttime =='') {
				//echo 'Deleting item';
				$alertItem->Delete();
			}
		}
	}
	/**
	 * Displays a browser to allow the user to select an existing item.
	 */
	function ShowSelectItem() {
		global $page,$project;
		if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();
		$selectedFolderId = GetParam("folder");
		//echo is_null($project)?"yes":"no";
		if (!is_null($project)){
		$folders=$project->getFolders();
		$folder = $folders[$selectedFolderId];
		if (!is_null($folder)){
			$page->assign('folder',$folder);
			$fc = $project->getFolderContents($selectedFolderId);
			$page->assign('contents',$fc);
		}
		else {
			$page->assign('folder',null);
		}
		
		$page->assign("folders",$folders);
		}
		$page->Template="itemSelector.php.tpl";
	}
	/**
	 *  
	 */
	function CopyItem(){
		global $project,$projectId;	
		//DisplayMessage("Not Fully Implemented! Folder Select UI required");
		//return;
		$itemType = GetParam("itemtype","");
		$itemId = GetParam("itemid",-1);
		if ($itemId >0){
			switch($itemType){
				case DOC_TYPE_DOC:
					$destFolderId = GetParam("folderid",-1);
					$destFolders = $project->GetFolders();
					$destFolder = $destFolders[$destFolderId];
					if ($destFolder->additem) {	
						// get project template id
						$projectTemplateId = $project->templateId;
						if (($_SESSION[USER]->projectPermissions[$projectId]['AddItem']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();	
					} else { 
						if (($_SESSION[USER]->projectPermissions[$projectId]['AddItem']!=ALLOW) &&
							($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 					
					}
										
					if ($destFolderId <0) {
						DisplayMessage("No Destination folder provided. ($destFolderId)");
						return;
					}
					
					$item = Document::GetDocument($itemId);
					//echo "DF:".$destFolder->name;
					$canCopy = true;
					if (!$destFolder->addItem){
						$canCopy = false;
						//echo "You cannot remove add items to the ". $destFolder->name . " folder.";
						DisplayMessage("You cannot copy items into the ". $destFolder->name . " folder.");					
					}
					if ($canCopy){
						$item->CopyDocument($destFolderId);
					}
					break;
				default:
					DisplayMessage("Copy operation not implemented for type $itemType.");
			}
		}
		else {
			DisplayMessage("I don't know what to copy: no Item provided.");
		}
		Redirect('index.php?option=office&cmd=view&folder='.$destFolderId);
	}
	/**
	 * 
	 * @todo Check User Permissions	 
	 */
	function MoveItem(){
		global $project,$page,$metrics;
		//DisplayMessage("Not Fully Implemented! Folder Select UI required");
		//return;
		$itemType = GetParam("itemtype","no type");
		$itemId = GetParam("itemid",-1);
		//echo $itemType;
		if ($itemId >0){
			switch($itemType){
				case DOC_TYPE_DOC:
					
				//	print_r($_SESSION['user']);
					$user= $_SESSION['user'];
					//if($user->superadmin==1 | $user->projectPermissions[$project->id]['')
					$item = Document::GetDocument($itemId);
					$destFolderId = GetParam("folderid",-1);
					if ($destFolderId <0) {
						 echo "No Destination folder provided.";
						return;
					}
					$destFolders = $project->GetFolders();
					$sourceFolder = $destFolders[$item->folderid];
					$destFolder = $destFolders[$destFolderId];
					
					//echo "DF:".$destFolder->name;
					//echo "SF:".$sourceFolder->name;
					$canMove = true;
					//print_r($sourceFolder);
	
					if (!$sourceFolder->allowdeletes & 
            (
              $user->projectPermissions[$project->id]['DeleteAnyItem']!=1 
              & 
              $user->superadmin!=1
            )
          ) {
						$canMove = false;
						//echo "You cannot remove items from the " .$sourceFolder->name ." folder.";
					 $metrics->recordMetric('FailedDeleteAttempt',
					'Folder name:'.$sourceFolder->name,
          'Folder id:'.$sourceFolder->folderid,
          'Folder Deletes Setting:'.$sourceFolder->allowdeletes.' User\'s Project Delete Permission'.$user->projectPermissions[$project->id]['DeleteAnyItem'].' User Superadmin flag:'.$user->superadmin,
          'ItemName:'.$item->filename
          );
						DisplayMessage("You cannot remove items from the " .$sourceFolder->name ." folder.");
					}
					if (!$destFolder->additem & 
            (
              $user->projectPermissions[$project->id]['EditAnyItem']!=1 
              &
              $user->superadmin!=1
            )
          ){
						$canMove = false;
					$metrics->recordMetric('FailedAddAttempt',
          'Folder name:'.$sourceFolder->name,
          'Folder id:'.$sourceFolder->folderid,
          'Folder Additions Setting:'.$sourceFolder->additem.' User\'s Project Delete Permission'.$user->projectPermissions[$project->id]['DeleteAnyItem'].' User Superadmin flag:'.$user->superadmin,
          'ItemName:'.$item->filename
          );
						//echo "You cannot remove add items to the ". $destFolder->name . " folder.";
						DisplayMessage("You cannot move items into the ". $destFolder->name . " folder.");					
					}
					if ($canMove){
//					   echo 'Moving Item';
						$item->MoveDocument($destFolderId);
					}
					else {
						echo 'Can\'t Move';
					}
					break;
				default:
					echo "Copy operation not implemented for type $itemType.";
			}	
		}
		else {
			echo "I don't know what to move: no Item provided.";
		}
		Redirect('index.php?option=office&cmd=view&folder='.$destFolderId);
	}
	/**
	 * Handles a user adding a comment
	 */
	function AddComment() {
		global $database,$page,$projectId;
		trace("doing AddComment");
		$itemId = GetParam("id",-1);
		$itemType = strtolower(GetParam("itemtype",""));
		$user = $_SESSION[USER];
		$projectPermissions = $user->GetProjectPermissions($projectId);
		if (($_SESSION[USER]->projectPermissions[$projectId]['AddItem']!=ALLOW) &&
			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 
		$redir = GetParam("redir","");
		if ($itemId== -1 or $itemType == "") {
			$page->Messages[]="ItemId was -1 or item type not specified.";
			return;
		}
		$subject = GetParam("comment_subject","No Subject");
		$comment = GetParam("comment_comment","");
		$commenter = GetParam('commenter',$_SESSION[USER]->displayName);
		$admincomment = GetParam('admincomment', NULL);
		Commentary::AddComment($_SESSION[USER]->id,$commenter,$itemType,$itemId,$subject,$comment,$admincomment);
		$item = Document::GetDocument($itemId);
		$item->MarkItemAsUnReadForAll();
		//echo $redir;
		Redirect($redir);
	}
	/**
	 * Deletes (hides) and item from the student or moves it in to the waste basket.
	 */
	function DeleteItem() {
		global $database,$project;
		trace("Doing Delete");
		$itemid = GetParam('itemid',-1);
		trace($itemid);
		$returnFolder = -1;
		if ($itemid >0) {
			$item = Document::GetDocument($itemid);
			if ($item->id==-1) {
				//No item found
				trace("No Item found");
				return;
			}
			$returnFolder = $item->folderid;
			//find out what type of user we have
			if ($_SESSION[USER]->isProjectStaff($project->id) or 
				$_SESSION[USER]->isProjectTemplateStaff($project->projectemplateuid)
				){
				//do a hide
				trace("User is non-player");
				$item->Delete();
			}
			else {
				//do a move to wastebasket
				trace("Moving to wastebasket");
				$wasteBasketId = $project->GetWasteBasketId();
				if ($wasteBasketId>0) {
					$item->MoveDocument($wasteBasketId);
				}
				else {
					//we hide it if there isn't a trashcan.
					trace("Couldn't move to trash, hiding!");
					$item->Delete();
				}
			}
		}
		else{
			trace("Item ID not passed");
		}
		Redirect('index.php?option=office&cmd=view&folder='.$returnFolder);
	}
	
	/**
	 * 
	 */
	 function UndeleteItem() {
	 	global $database,$project;
	 	$itemid = GetParam('itemid',-1);
		$returnFolder = -1;
	 	if ($itemid >0) {
			$item = Document::GetDocument($itemid);
			if ($item->id==-1) {
				//No item found
				trace("No Item found");
				return;
			}
			$returnFolder = $item->folderid;
			if ($_SESSION[USER]->superadmin==ALLOW) {
				$item->Undelete();
			} else {
				DisplayMessage("You do not have sufficient permissions for this action");
			}
		} else {
			trace("Item ID not passed");
		}
		Redirect('index.php?option=office&cmd=view&folder='.$returnFolder);
	 }
	 
	/**
	 * Handles a user marking an item as unread.
	 */
	function DoMarkAsUnread(){
		$itemId = GetParam('id',-1);
		if ($itemId >0) {
			$doc = Document::GetDocument($itemId);
			$doc->MarkItemAs(0,$_SESSION[USER]->id);
		}
		Redirect('index.php?option=office&cmd=view&folder='.$doc->folderid);
	}
	/**
	 * 
	 */
	function printItem() {
		global $wrapper,$page,$project,$strings; 	
		$page= null;
		//echo 'ViewItem';
		
		if (is_null($project)){
			Redirect('?option=message&cmd='.$strings['MSG_NO_PROJECT_SELECTED']);
			return;
		}
		$wrapper->Template='blank.tpl';
		$documentUid = GetParam("documentid",-1);
		
		$content='Not Set';
		//echo $resourceId;
		if ($documentUid != -1) {
			$item= Document::getDocument($documentUid);
		
			if (strtolower($item->contenttype)!= 'text/html'){
					header('Content-type:'.$item->contenttype);
					header('Content-Disposition: inline;filename='.$item->filename);
					//header('Content-Length:');
					$content=$item->GetContent();
					
					//$content = $docTemplate->contenttype;
			}
			else {
				$vb = $project->GetVariabliser();//new Variabliser($project->Variables);
				$content = $vb->Substitute($item->GetContent());
			}
		} 
		else {
			//echo 'Page is not available';
			$content='Page is not available';	
		}
		$wrapper->assign('contents',$content);
	}
	/**
	 * Handles Staff sending a custom file from their computer
	 * (Upload and send)   	
	 */	
	function SendFile() {
  	global $project,$_PLUGINS, $metrics;
  		//echo 'doing Staff Send File';
	//	trace("$_SESSION[USER]->projectPermissions[$project->id] is : ".$_SESSION[USER]->projectPermissions[$project->id]);
	//	if ((!isset($_SESSION[USER]->projectPermissions[$project->id])) &&
	//		($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions();		
			trace("<pre>project id is ".$project->id." <br> ".print_r($_SESSION[USER],true)."</pre>");
		$folderId = $project->GetDeliveryFolder();
		$folderId = GetParam('intoFolder',$project->GetDeliveryFolder());
		//$filename = GetParam('uploadDocument','');
		$sender = GetParam('sender','');
		$recipient =GetParam('recipient','{CHAR_PLAYER}');
 		/*
    echo '<pre>';
 		print_r($_POST);
 		print_r($_FILES);
 		echo '</pre>';
 		*/
		$canUpload= true;
		if ($folderId ==-1 ){
			$canUpload = false;
			$page->Messages[]="Cannot upload to that folder.";
		}
		if ($canUpload) {
  		$sql ="";
  		
  		$item = new Document();
  		
  		//echo 'Error:'.$_FILES['uploadDocument']['error'];
    	if ($_FILES['uploadDocument']['error'] == UPLOAD_ERR_OK){
        $fileSize = $_FILES['uploadDocument']['size'];
    		if ($fileSize ==0) {
    			$page->Messages[] = "Did not upload file successfully";
    		}
    		$filename = $_FILES['uploadDocument']['name'];
    		$tmpName = $_FILES['uploadDocument']['tmp_name'];
    		//echo $_FILES['updloadDocument']['error'];
    		
    		$type= $_FILES['uploadDocument']['type'];
    		//echo "*$fileSize*";
    		$fp = fopen($tmpName,'r');
    		//echo "filename: $tmpName*";
    		$content = fread($fp,filesize($tmpName));
    		if ($content !== FALSE){

    		  fclose($fp);
    		}
    		$item->content = $content;
    		$item->filename = $filename;
  		}
  		$item->folderid = $folderId;
	   	$item->contenttype=$type;

		  $_PLUGINS->trigger('onAfterStaffReleaseDocument',array(&$filename,&$folderId,&$docid));
		
			//we now have uploaded the document so lets set the sender.
			$vb = $project->GetVariabliser();
			$item->sender = $vb->Substitute($sender);
			$item->recipient = $vb->Substitute($recipient);
			$item->Save();
      Redirect("index.php?option=office&cmd=view&folder=$folderId");
    /*
    echo '<pre>';
 		//print_r($item);
 		echo '</pre>';
 		*/
		}
		else {
		  //print_r($page->Messages);
    }
  }
	/**
	 * Displays Ui to send an uploaded file to multiple simulations at
	 * once 
	 */
	function SendToMultipleFiles(){
		global $metrics, $database;
		echo 'SendMultiFiles';
		$projectGroupId = GetParam('projectgroup',-1);
		$user = $_SESSION[USER];
		
		if ($projectGroupId>0){
			$projectGroup = ProjectGroup::Load($projectGroupId);
			
				
			
		}
		else {
			//we can't do that
		}
	}
	/**
	 * Displays UI to send a Template to multiple simulations at once
	 */
	function SendToMultipleTemplates() {
		/*
		 * Implement a window to display the select template options;
		 */
		
	}	
	/**
	 * Displays UI to send a freeform document to multiple 
	 * simulations at once
	 */
	function SendToMultipleCustom() {
		global $page;
		$projectGroupId = GetParam('projectgroup',-1);
		$user = $_SESSION[USER];
		$projectGroup = ProjectGroup::Load($projectGroupId);
		if (is_null($projectGroup)){
			return;
		}
		$project =Project::GetProject($projectGroup->Projects[0]);
		
		$sender = GetParam('sender','');
		$recipient = '{CHAR_PLAYER}';
		$folder =GetParam('folder',-1);
		trace('DestFolder:' . $folder);
		$page->Template="editDocument.php.tpl";
		$page->assign('contenttype',"text/html");
		$page->assign('docuid',-1);
		$page->assign('option','office');
		$page->assign('command','dosendmulti');
		$page->assign('showStaffTools',true);
		$page->assign('recipient',$recipient);
		$page->assign('sender',$sender);
		$page->assign('variableMessage','');
		$page->assign('variablelist_noValues',true);
		$page->assign('variablelist',$project->Variables);
		$page->assign('destfolderid',$projectGroupId);
		$docTemplateId = GetParam('custom_base',-1);
		$page->assign('variablelist',$project->Variables);
		$page->assign('item',new Document());
		if ($docTemplateId >-1 ){
			$pt = $project->GetProjectTemplate();
			$docTemplate = $pt->getFullDocumentTemplate($docTemplateId);
			$page->assign('documentContent',$docTemplate['content']);
			//echo 'fn:'.$docTemplate['filename'];
			$page->assign('filename','filename');
		}
		else {
			$page->assign('documentContent','');
			$page->assign('filename','filename');
		}
	}
	/**
	 * Sends provided content to multiple simulations at once.
	 */
	function doSendToMultipleContent() {
		global $metrics, $database;
		foreach($_POST as $k=>$v){
			echo "$k:$v<br>";
		}
		$projectGroupId = GetParam('destfolderid',-1);
		$content = GetParam('documentContent','');
		$filetype = GetParam('contenttype','');
		$sender = GetParam('sender','');
		$recipient = GetParam('recipient','');
		$filename = GetParam('filename','File');
		$user = $_SESSION[USER];
		
		if ($projectGroupId>0){
			$projectGroup = ProjectGroup::Load($projectGroupId);
			echo $projectGroup->Name;
			foreach($projectGroup->Projects as $p){
			echo "$p<br>";
		}
		foreach($projectGroup->Projects as $projectid){
			$project = Project::GetProject($projectid);
			$vb = $project->GetVariabliser();
			$doc = new Document();
			$doc->contenttype=$filetype;
			if($filetype == 'text/html'){
				$doc->content=$vb->Substitute($content);
			}
			else {
				$doc->content = $content;
			}
			$doc->recipient = $vb->Substitute($recipient); //look up Sim Player's name;
			$doc->sender = $vb->Substitute($sender);
			$doc->filename = $vb->Substitute($filename);
			$doc->folderid = $project->GetDeliveryFolder();//Look up Sim's received items folder;
			echo 'Saving! ';
			print_r($doc);
			echo '<br/>';
			echo 'to Project:'. $project->id . ':'.$project->Name;
			$doc->Save();
			echo 'Saved! ';
			echo '<hr/>';
		}
	}
	else {
		//we can't do that
	}
}
function doAddFolder() {
  $newFolderName = GetParam('foldername','');
  $projectid = GetParam('projectid',-1);
  if ($projectid <0) {
    DisplayMessage('Can\'t find project to add folder to.');
    return;
  }
  if ($newFolderName=='') {
    DisplayMessage('You must provide a name for the folder.');
    return;
  }
  $project = Project::GetProject($projectid);
  if (is_null($project)) {
    DisplayMessage('There was a problem retriving simulation information.');
    return;
  }
  else {
   //echo "Adding New Folder $newFolderName";
    $newFolder = $project->AddFolder($newFolderName);
    Redirect('index.php?option=office');
  }
}
function doSaveFolder() {
  $folderid = GetParam('folderid',-1);
  $newFolderName = GetParam('foldername','');
  if ($folderid <0){
    DisplayMessage('Unable to find folder');
    return;
  }
  if ($newFolderName =='') {
    DisplayMessage('You haven\'t provided a new folder name.');
    return;
  }
  
  $folder = Folder::getFolder($folderid);
  $folder->name = $newFolderName;
  $folder->Save();
  
  Redirect('index.php?option=office');
}
/**
 *
 */
function doDeleteFolder() {
  $folderid = GetParam('folderid',-1);
  if ($folderid<0) {
    return;
  }else {
    $folder  = Folder::getFolder($folderid);
    if ($folder->GetItemCount() >0) {
      DisplayMessage('Folder is not empty. Please remove all items first.');
      return;
    }
    else {
      $folder->Delete();
      Redirect('index.php?option=office');
    }
  }
}
function DismissAllAlerts(){
  global $page,$u;
  //$currentUser= $u;//$_SESSSION[USER];
  if (!is_null($u)) {
    //print_r($u);
    $alerts = $u->GetAlerts();
    //print_r($alerts);
    foreach($alerts as $alert) {
      $alert->Delete();
    }
    Redirect('index.php?option=office');
  }
  else {
  }
  
  
}
?>
