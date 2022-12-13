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
 * TLE2 helper functions
 * @author Michael Hughes
 * 
 * @package TLE2
 */

	if (!defined("TLE2")) die ("Invalid Entry Point");
	
	/**
	* Tries to Retrieve a parameter from the $_GET supervariable first then, $_POST.
	* @param $Parameter the name of the parameter to retrieve
	* @param $DefaultValue a default value if the parameter cannot be found (defaults to NULL)
	* @return the value of the requested parameter or the default value if not found
	*/
	function GetParam($Parameter,$DefaultValue=null) {
		$val = "";
		if (isset($_GET[$Parameter]) ){
			$val = $_GET[$Parameter];
		}
		if ($val != "") {
			return $val;
		}

		if (isset($_POST[$Parameter]) ){
			$val = $_POST[$Parameter];
		}
		if ($val == "") {
			return $DefaultValue;
		}
		//if (gettype($val)!='array') $val=SafeDb($val);
		return $val;
	}

  /**
   * Updates the User object in the session.
   */     
  function SetSessionUser($user) {
    $_SESSION[USER] = $user;
   }
   /**
    * Returns the User Object from the Session
    */
  function GetSessionUser() {
    global $_SESSION;
    if (isset($_SESSION[USER])) {
      if ($_SESSION[USER] instanceof User) {
		return $_SESSION[USER];
      }
    }  
    return false;
  }  
  
  
         
	/**
	* Ensures all data is safe for using with database queries.
	* @param $val a string which may contain malicious content
	* @return string safe content
	*/
	function SafeDb($val) {
		global $database;
		//$val = CleanContent($val); // strip dangerous html to prevent XSS
		//$val = StripMagicQuotes($val); // compensate for magic_quotes being on
		if ((!is_numeric($val)) && (!is_array($val))) {
		//	$val = $database->quote($val); // escape to prevent SQL injection
			
			$val = (string)$val; // cast to string
			//print_r($val);
			if (get_magic_quotes_gpc()==FALSE) {
				$val = addslashes($val);
			} 
			//print_r($val);
			//print "<br/>";
		}
		// need to escape arrays too
		if (is_array($val)) {
			$val = escape_string($val);
		}
		return $val;
	}
	
	// function to deal with arrays
	function escape_string($q) {
		if (is_array($q))
			foreach($q as $k => $v)
				$q[$k] = escape_string($v); //recursive
		elseif(is_string($q))
			$q = addslashes($q); 
		return $q;
	}
	
	/**
	* Strips dangerous HTML content from a string.
	* @param $html a string which may contain malicious content
	* @return string XSS-safe content
	*/
	function CleanContent($html) {
		//$safeHtml =& new safehtml();
		//$cleanHtml = $safeHtml->parse($html);
		return $html;
	}
	
	/**
	* Checks whether magic_quotes is turned on and strip slashes if yes (string is subsequently escaped properly by SafeDb method).
	* $param $val a string which may contain malicious content and may have been escaped by magic quotes
	* @return string text with slashes removed if magic_quotes_gpc is set to on
	*/
	function StripMagicQuotes($val) {
		if (get_magic_quotes_gpc()) {
	    	return stripslashes($val);
		} 
		return $val;
	}

	function FormatDateTime($dateFormat=null) {
	
		if (isset($dateFormat)){
			trace("Formatting: ".$dateFormat);
			$i = strtotime($dateFormat);
		}
		else {
			$i = time();
			trace("Formatting: ".$i);
		}
		$o = date("Y-m-d H:i:s",$i);
		trace("Output: ".$o);
		return $o;
		
	}
	/**
	 * Deserialises a string in to key-value pairs
	 * 
	 * The input string should be in the format 
	 * <code>Name=Value|Name=Value|</code>
	 * 
	 * @return array Associative Array containing the values indexed by the key.
	 */
	function DeserialiseArray($data,$stripslashes = false) {
		if($data ==''){
			return array();
		}
		$item =strtok($data,"|");
		$vars = array();
		//print_r($data);
		while ($item !== false){
			$p = explode("=",$item);
			if ($p[0]!= "" & isset($p[1])){
				if ($stripslashes) {
          //print ('stripping:'.$p[1].'<br>');
          $var =stripslashes(base64_decode($p[1]));
          $var = preg_replace('/\\\"/',"\"",$var);
          $vars[$p[0]] = $var;				  
        }
        else {
       // print ('not stripping');
          $vars[$p[0]]= base64_decode($p[1]);
        }
			} 
			else {
        $vars[$p[0]]='';
      }
			$item =strtok("|");
		}
		return $vars;
		/*
		This is the old "DeserialiseArray()" implmentation
		$props = explode("|",$data);
 		$vars = array();
		foreach($props as $prop){
			$p = explode("=",$prop);
			if ($p[0]!= ""){
				$vars[$p[0]]= ($stripslashes)?SafeDb($p[1]):$p[1];
			} 
 		}
 		return $vars;
 		*/
	}		
	
	function DeserialiseArray_NoBase64($data,$stripslashes = false) {
		if($data ==''){
			return array();
		}
		$item =strtok($data,"|");
		$vars = array();
		//print_r($data);
		while ($item !== false){
			$p = explode("=",$item);
			if ($p[0]!= "" & isset($p[1])){
				if ($stripslashes) {
          //print ('stripping:'.$p[1].'<br>');
          $var =stripslashes(($p[1]));
          $vars[$p[0]] = $var;				  
        }
        else {
       // print ('not stripping');
          $vars[$p[0]]= ($p[1]);
        }
			} 
			else {
        $vars[$p[0]]='';
      }
			$item =strtok("|");
		}
		return $vars;
		/*
		This is the old "DeserialiseArray()" implmentation
		$props = explode("|",$data);
 		$vars = array();
		foreach($props as $prop){
			$p = explode("=",$prop);
			if ($p[0]!= ""){
				$vars[$p[0]]= ($stripslashes)?SafeDb($p[1]):$p[1];
			} 
 		}
 		return $vars;
 		*/
	}
	function SerialiseArray($array,$base64=TRUE) {
			if (is_null($array)) {
				return '';
			}
			$work = array();
			foreach($array as $Name=>$Value){
				if ($Name !== "") {
					if ($base64) {
						$work[]="$Name=".base64_encode(SafeDb($Value));
					} else {
						$work[]="$Name=".SafeDb($Value);
					}
				}
			}
			return join("|",$work);
		}
	/**
	* Redirects to a page
	*/
	function Redirect($url=''){
		global $config;
		if ($url =='reopen') {
			$url =$_SESSION['previouspage'];
			//echo $url;
		}
		if ($url=='close'){
			$url = $_SESSION['exitpage'];
		}
		$redirectparam = urldecode(GetParam('redirect',""));
		if ($url=='' && $redirectparam != '') {
      
      $url = $redirectparam;
    }
		if ($config[DEBUG] and $config[DEBUG_LOCKSTEP]){
			trace("<A id=\"debugContinue\" href=\"$url\">Continue</a>");	
		} else {
			Header("Location:$url");
		}
	}
	/**
	 * Displays a message to the user.
	 */
	function DisplayMessage($message = '') {
		//$message= urlencode($message);
		Redirect("index.php?option=message&cmd=$message");
	}
	/**
	 * Displays errors.
	 */
	function DisplayError($ErrorNo){
		global $metrics;
		$metrics->recordMetric("Error","ErrorNumber:$ErrorNo","Time:".date('r'),debug_backtrace());
		
		Redirect("index.php?option=error&cmd=$ErrorNo");
	}
	
	/**
	 * performs an AND operation on permissions in order to combine group permissions
	 */
	function AndPermissions($permission1, $permission2) {
		if ($permission1 == NOTSET) $permission1 = DENY;
		if ($permission2 == NOTSET) $permission2 = DENY;
		if ($permission1 == DENY) $permission1 = NULL;
		if ($permission2 ==DENY) $permission2 = NULL;
		$returnPermission = ($permission1 && $permission2) ? ALLOW : DENY;
		return $returnPermission;
	}
	
	/**
	 * logs the user out and displays a page saying that they had insufficient permissions
	 */
	 function InsufficientPermissions() {
	 	global $page,$_PLUGINS,$config;
	 	trace("InsufficientPermissions()");
	 	//session_destroy();
	 	$msg = "You did not have sufficent permissions for this action.";
	 	Redirect("index.php?option=message&cmd=$msg");
	 	exit();
	 }
	 
	/**
	* Sets the current active project to the one specified.
	* @param int $projectid the Unique Runtime Id of the project that has been chosen.
	*/
	function changeProject($projectid){
	 global $_SESSION;
		$_SESSION[PROJECT_ID]=$projectid;
		trace('Setting Project to '.$projectid);
		//die('Setting Project to '.$projectid);
	}
	/**
	 * getActiveProjectId
	 * 
	 * returns the id of the project that system thinks is currently active.   	 
	 */   	
	function getActiveProjectId() {
    global $_SESSION;
    return isset($_SESSION[PROJECT_ID])?$_SESSION[PROJECT_ID]:-1;
    
  } 
	
	/**
	 * returns the passed number of bytes in a human friendly format
	 * @param int		the number in bytes
	 * @return string	readable number
	 */
	function bytes($a) {
    	$unim = array("B","KB","MB","GB","TB","PB");
    	$c = 0;
    	while ($a>=1024) {
	        $c++;
        	$a = $a/1024;
    	}
    	return number_format($a,($c ? 2 : 0),",",".")." ".$unim[$c];
	}
  
  /**
   * Generates the correct HTML for the Left hand side bar
   * @param string ID for the box (optional)
   * @param string The Title displayed in the box      
   * @param bool Indicates the sidebar box is staff only (so gets different chrome)
   * @param Collapsed Indicates the start state for the box (false is default)      
   */     
  function GenerateLeftSideBarBoxOpening($BoxId,$BoxName, $StaffBox = false,$Collapsed = false) {
      $staffBoxClass='';
      if ($StaffBox) {
        $staffBoxClass=' staffOnly';
      }
      if ($BoxId=="") {
      	$BoxId = "simpleControl_ID_". rand();
      }
      $CollStyle='';
      if ($Collapsed) {
        $CollStyle='display:none; ';
      }           
      if ($CollStyle!='') {
        $style = "style=\"$CollStyle\"";
      }
      $result='';
      $result.="<div class=\"sidebarBox\">";
      $result.="<div class=\"sidebarTitle ${staffBoxClass}\" style=\"overflow:hidden;\">${BoxName}</div>";
  	  $result.="<div id=\"${BoxId}_Content\" class='sidebarContent'>";
  	  return $result;
  }
  /**
   * Generates the correct closing tags for a sidebar box created using GEnerateLeftSideBarBoxOpening()
   */     
  function GenerateLeftSideBarBoxClosing() {
    return '</div> <!--close content div //--></div><!--close box div//-->';
  }
  
  function ImplodeAssocArray($arr) {
    $out="";
    foreach($arr as $k=>$v) {
      $out.= $k.'="'.$v.'" ';
    }
    return $out;
  }
  /**
   * Generates a link to a URL that opens in a new (_blank) window.
   * 
   * Link is ALWAYS a window icon.      
   * 
   * This is a user configurable option through User->GetPrefs().       
   * 
   * @param string URL to open
   * @Title string Tooltip text to display         
   */     
  function GenerateOpenInNewWindowLink($url,$Title="Open in New Window") {
    global $config;
    $user = GetSessionUser();

    if(! (bool)$user->GetPrefs(PREF_SHOW_POPUP_LINKS)) {
        return '';//(int)$user->GetPrefs(PREF_SHOW_POPUP_LINKS);
    }
    return "<a href=\"$url\" target=\"_blank\" title=\"$Title\"><img src=\"{$config["newwindow_icon"]}\" border=\"0\"></a>";
  }
  
  function GenerateLink($url,$UrlText,$attributes = array()) {
    global $config;
    $user = GetSessionUser();

    $titleText="Open $UrlText"; 
    $targetWin='';
    if((bool)$user->GetPrefs(PREF_ALWAYS_OPEN_IN_NEW_WINDOW)) {
        $targetWin='target="_blank"';
        $titleText.= ' in new window';
    }
    //print_r($attributes);
    $attribs= ImplodeAssocArray($attributes);
    //print($attribs);
    $result = "<a href=\"$url\" $targetWin title=\"$UrlText\" $attribs>$UrlText</a>";
    //echo($result);
    return $result;
  }
    
  /**
   * Generates a link to the TICKETs (bugs) database
   **/     
  function Ticket($ticketNumber) {
    global $config;
    return "<a href=\"{$config[TICKETS_URL]}{$ticketNumber}\" target=\"_blank\">View Ticket</a>";
  }
  
  function GenerateOfficeUi($projectId){
		global $page, $wrapper, $metrics, $_PLUGINS,$project,$config;
		$permissions = $project->GetProjectPermissions($_SESSION[USER]->id);
		$page->Template="Office.php.tpl";
		
		/* Disabled per project calendars */
		$page->assign('calendarEnabled',false);
		
		$sort= GetParam('sort','');
		$page->assign('sort',$sort);
		$wrapper->assign('redir',$_SERVER['REQUEST_URI']);
		$page->assign('projectInActive',!$project->IsActive);
		
/*
 * Find out what folder we should be looking at! 
 */
		$folderId= getParam("folder",-1);
		trace("FOlderId:$folderId");
		
/*
 * Check the user has a project selected.
 */
		if (is_null($project)) {
			Redirect('index.php?option=message&cmd='.$strings[MSG_NO_PROJECT_SELECTED]);
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
		$trash = null;
		$folder = null;
		foreach($folders as $f) {
			if ($folderId <0 and is_null($folder)) {
				$folder = $f; // default to first folder (received)
			}
			if ($f->trashcan) {
				$trash = $f;
				break;
			}	
		}
		if ($_SESSION[USER]->superadmin==ALLOW || $permissions['usestafftools']==ALLOW) {
			$folder = $folders[$project->GetSentItemsFolder()]; // default to sent items
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
	
		if (($permissions['UseStaffTools'] and $project->tutorsSeeTools) 
			or $_SESSION[USER]->superadmin==ALLOW) {			
			$page->assign('showStaffTools',true);
/*
 * Initialise Templates since we might use them now....
 */		
			$cannedtemplates =$project->GetDocumentTemplates(ALL_TEMPLATES,FALSE);
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

		$itemDelete = (
					($folder->allowdeletes and $permissions['DeleteItem'])
					or $permissions['DeleteAnyItem']
					?1
					:0
		);
		
		settype($itemDelete,'integer');
		$permissions['canDelete'] = $itemDelete;
	/*
 * Setup display based on User's Project Template Permissions.
 */
		$page->assign('permissions',$permissions);
		$showSettingsLink = Project::IsProjectAdministrator($_SESSION[USER]->id, $project->id);	
		trace("showSettingsLink is ".$showSettingsLink);	
		$wrapper->assign('showSettingsLink',$showSettingsLink);	
	
/*
 * Setup the page's title with no Folder, just incase we endup with no
 * folder selected (which we shouldn't)!
 */		$sectionTitle = $project->Name;
		$page->assign("sectionTitle",$sectionTitle);
		//$pt=$project->GetProjectTemplate();
		$projectRoles = $project->GetCharacters();
		ksort($projectRoles); 
		foreach  ($projectRoles as $key=>$pr) {
			if (strcmp($pr->projectrole,$pr->name)==0) { // if strings are the same
				$projectRoles[$key]->name = '';
			}	
		}
		
		$page->assign('projectRoles',$projectRoles);
		$page->assign('pid',$project->id);
		if($_SESSION[USER]->superadmin | $permissions['UseStaffTools']==ALLOW){
			$showned = getParam('showned',false);
			$page->assign('showned',$showned);
			$page->assign('ned_redir','index.php?option=office');
			$page->assign('ned_EnableTriggers',true);
			$page->assign('ned_EnableDocuments',false);
			$page->assign('ned_EnableStates',true);
			$events = $project->GetEvents();
			//echo '<pre>';
			//print_r($events);
			//echo '</pre>';
			$page->assign('events',$events);
			//$page->assign('json_events',json_encode($events)); part of updated NED work
		} else {
			//print('not staff');
		}

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
			
			/*
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
			*/
			//$page->assign('calendarEnabled',false);
			/*
			extend above & below the folders
			*/
			$extendStaffResources_results = $_PLUGINS->trigger('extendStaffResources',array($project,$folder));
			$esrText ='';
			foreach($extendStaffResources_results as $r){
				$esrText.="<li>$r</li>";
			}
			$page->assign('staffResourcesExtension',$esrText);
			$extendStaffTools_results = $_PLUGINS->trigger('extendStaffTools',array($project,$folder));
			$estText ='';
			foreach($extendStaffTools_results as $r){
				$estText.=$r;//"<div id=\"\" class=\"sidebarBox\">$r</div>";
			}
			$page->assign('staffToolsExtension',$estText);

			$preFolder_results= $_PLUGINS->trigger('extendOfficeFoldersBefore',array($folder));
			$preFolder='';
			foreach($preFolder_results as $result){
				$preFolder.=$result;
			}
			$page->assign('prefolder_extension',$preFolder);
			$postFolder_results= $_PLUGINS->trigger('extendOfficeFoldersAfter',array($folder));
			$postFolder='';
			foreach($postFolder_results as $result){
				$postFolder.=$result;
			}
			$page->assign('postfolder_extension',$postFolder);
		  /*
		  $prebuildInExtension_results= $_PLUGINS->trigger('extendBeforeBuiltInTools',array($project,$folder));
		  
      $postbuiltInExtension_results= $_PLUGINS->trigger('extendAfterBuiltInTools',array($project,$folder));
		  $postBuiltInExtensionHtml='';
		  foreach($postbuiltInExtension_results as $result){
      //echo($result);
        $postBuiltInExtensionHtml.=$result;
      }
		  $page->assign('postBuildIn_extension',$postBuiltInExtensionHtml);
		  */
		} else {
			trace("Selected Folder not found!!!");
			$page->assign('folder',null);
		}
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
		//print_r($resources);
		$page->assign('resourceArray',$resources);
		//$page->assign('projectUserArray',array());//$project->getMembers());
		$page->assign('projectUserArray',$project->getMembers());
		$cenabled = isset($project->GetProjectTemplate()->Properties['calendarEnabled'])?$project->GetProjectTemplate()->Properties['calendarEnabled']:$config[DEFAULT_CALENDAR_STATE];
		$page->assign('calendarEnabled',$cenabled );
		/*
		 * Insert the list of people in to the directory for sending.
		 */
		$vb = $project->GetVariabliser();
$directoryItems =DirectoryItem::GetDirectoryItems($project->id);
//die(print_r($directoryItems));
		foreach($directoryItems as &$item){

				if (strpos($item->name,'{')!==false) {
					$item->name=$vb->Substitute($item->name);
				}
				if (strpos($item->address,'{')!==false) {
					$item->address=$vb->Substitute($item->address);
				}
				if (strpos($item->location,'{')!==false) {
					$item->location=$vb->Substitute($item->location);
				}
				if (strpos($item->directoryvisible,'{')!==false) {
					$item->directoryvisible=$vb->Substitute($item->directoryvisible);
				}
				//$characters[$role] =$d;
		}
		$page->assign('directory',$directoryItems);

//		$page->assign('directory',DirectoryItem::GetDirectoryItems($project->id));
		//$result = $_PLUGINS->trigger('onAuthenticateUser',array($username,$password,$a
		/*
		*hook for custom side bar items.
		*/
		$sb_results_pre= $_PLUGINS->trigger('extendOfficeSidebarBefore',array($project,$folder)); 
		
    $pre_sideBar_extension='';
		foreach($sb_results_pre as $result){
      //echo($result);
      $pre_sideBar_extension.=$result;
    }
		$page->assign('pre_sidebar_extensions',$pre_sideBar_extension);
				
		$sb_results_post= $_PLUGINS->trigger('extendOfficeSidebarAfter',array($project,$folder)); //this supercedes the "extendOfficeSideBar hook"
		$sb_results= $_PLUGINS->trigger('extendOfficeSidebar',array($project->id,$folder->folderid));           //"old" extendOfficeSidebar hook kept for BW compatibility
    $post_sideBar_extension='';
		foreach($sb_results as $result){
      //echo($result);
      $post_sideBar_extension.=$result;
    }
    foreach($sb_results_post as $result){
      //echo($result);
      $post_sideBar_extension.=$result;
    }
		$page->assign('post_sidebar_extensions',$post_sideBar_extension);
	  $page->assign('showMap',true);
		
    $pt = $project->GetProjectTemplate();
		$pt_props = $pt->Properties; //$properties['folders']
		if (isset($pt_props['hidefolders']) && $pt_props['hidefolders'] == 1) {
      $page->assign('folders',null);
    }
    if (isset($pt_props['hidemap']) && $pt_props['hidemap'] == 1) {
      $page->assign('showMap',null);
    }
    if (isset($pt_props['hideresources']) && $pt_props['hideresources'] == 1) {
      $page->assign('resourceArray',null);
    }

	
		trace("<h3>End View()</h3>");		
	}
	function RegisterJQueryCode($Code) {
    global $wrapper;
    if (strtolower($wrapper->Template) == 'tleui.php.tpl') {
      $wrapper->JQueryScripts[] = $Code;
    }
  }
  function RegisterScript($Code) {
    global $wrapper;
    if (strtolower($wrapper->Template) == 'tleui.php.tpl') {
      $wrapper->scripts[] = $Code;
    }
  }
?>
