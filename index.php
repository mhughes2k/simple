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
 * SimPLE Main Script.
 * @author Michael Hughes
 * @package TLE2
 */

if (ini_get('register_globals') == 1)
{
	die("Error: register_globals is on. Please turn this off in php.ini in order to access SIMPLE.");
}
ini_set('session_name','simple_session');
//ini_set("display_errors", E_ALL);
//ini_set('error_reporting', E_ALL);
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
ini_set("memory_limit","50M");
ini_set ('mssql.textlimit','65536');
ini_set ('mssql.textsize','65536');
define('TLE2',true,false);	//define a constant that all the other includes must check as a gatekeeper.
require_once('include/Constants.php');
require_once('include/DefaultSettings.php');
/*
Redirect to the SSL version earlier.
ideally we'll only do the SSL bits where we have to.
*/
if ($config['redirectToSSL'] && $_SERVER['SERVER_PORT'] !=443) {
	redirect('https://'. $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
}
if (file_exists('LocalSettings.php')) {
	include_once('LocalSettings.php');
} else {
	include_once('installer/NotConfigured.html');
	die();
}	
$offlineAdminMode=false;
if ($config['offline']) {
  if (!in_array($_SERVER['REMOTE_ADDR'],$config['adminIps'])) {
    include($config['offline_file']);
    print($_SERVER['REMOTE_ADDR']);
    die();
  }
  else {
    $offlineAdminMode=true;
  }
}
	include_once('include/GraphicsAndStrings.php');
    require_once('include/Metrics.class.php');
	$metrics = new Metrics($config['recordMetrics']);	
	require_once('include/Debug.php');	
	require_once('include/User.class.php');
	require_once('include/UserGroup.class.php');
	session_start();
	header ('Cache-Control: cache, must-revalidate');
	header ('Pragma: public');
	require_once('include/TLE2.php');
	require_once('include/Licensing.php');
	//$serverMode = DecryptServerLicense();
	require_once('include/Variabliser.class.php');
	require_once('include/Database.class.php');
	require_once('include/ContentAndSettings.php');		
	require_once('include/Item.class.php');
	require_once('include/DirectoryItem.class.php');
	require_once('include/Document.class.php');
	require_once('include/IPlugin.php');
	require_once('include/PluginHandler.php');
	require_once('include/Security.php');
	require_once('include/smarty/Smarty.class.php');
	require_once('include/Project.class.php');
	require_once('include/ProjectTemplate.class.php');
	require_once('include/Container.class.php');	
	require_once('include/Page.class.php');
	require_once('include/Folder.class.php');
	require_once('include/CalendarItem.class.php');
	require_once('include/Alert.class.php');
	require_once('include/Comment.class.php');
	require_once('include/Commentary.class.php');
	require_once('include/ProjectGroup.class.php');
	//require_once('Mail.php');

    //require_once('include/Languages.php');
	$sessionUser = GetSessionUser();
	if ($sessionUser === false || is_null($sessionUser)) {
	    $user= new User();
		SetSessionUser($user);
		$lang = isset($user->Properties['language'])?$user->Properties['language']:$lang;
		//echo("creating new user object...");
	}  
	$sessionUser = GetSessionUser();
	
	require_once("lang/$lang/language.php");
	
	/**
	* @global PluginHandler $_PLUGINS
	*/
	$_PLUGINS = new PluginHandler();
	PluginHandler::loadPlugins();
	$_PLUGINS->registerFunction('onAuthenticateUser','SIMPLE','TleAuthenticate');
	//include_once('modules/coreDebug.php');
	/**
	 * The Wrapper Page holds the "outside" parts of the UI that is shared
	 * amongst all of the ProjectTemplates. It will take on the style that is defined
	 * by the active Project's underlying style, providing there is an active project.
	 */
	$wrapper = new Page("TleUi.php.tpl");
	// The Page object displays the actual page content.
	$page = new Page();
	// We require a valid database object and connection.
	if (is_null($database)) {
		die("E1. Unable to create database connection.");
		//$metrics->recordMetric('dbFailure', getdate());
	}
	/**
	* The "option" that is being asked to handle the request.
	* @global string $option
	* @name $option
	*/
	//print_r($_POST); print "<br/>";
	//print_r($_REQUEST); print "<br/>";
	$option = strtolower(GetParam("option",DEFAULT_OPTION));
	//print "Option: ".$option."<br/>";
	
	/**
	* The "command" that is being executed.
	* @global string $command
	* @name $command
	*/
	$command = strtolower(GetParam("cmd",DEFAULT_COMMAND));
	//print "Command: ".$command."<br/>";

	if (!$sessionUser->IsAuthenticated()) { 

		$msg = GetParam('msg',NULL);
		switch ($msg) {
			default:
				$message = "Please Login";
		}
		$page = new Page('login.php.tpl');
		$page->assign('message',$message);
		$plugins = $_PLUGINS->getEventPlugins('onAuthenticateUser');
		sort($plugins);
		$page->assign('siteSettings',$siteSettings);
		$page->assign('authPlugins',$plugins);
		if ($option != "login") $option="showlogin"; // make sure user not trying to login
	}
	
	// If the user is not logged on but we are trying to authenticate them.
	if (($option == "login") && (!$sessionUser->IsAuthenticated())) {     
		SetSessionUser($sessionUser = Authenticate());
		
		// If the user's credentials fail we re-direct back to the login page.
		if ($sessionUser===false || !$sessionUser->IsAuthenticated()) {
			$page = new Page('login.php.tpl');
			$page->Title=$strings['MSG_LOGIN'];
			$plugins = $_PLUGINS->getEventPlugins('onAuthenticateUser');
			trace("<pre>".print_r($_PLUGINS, true)."</pre>");
			$page->assign('authPlugins',$plugins);
			$page->assign('siteSettings',$siteSettings);
			$page->assign('message','Login Failed');
		}else {
			$option = "dashboard";
			$cmd="view";
			trace("<pre>".print_r($_SESSION,true)."</pre>");
		}
	}
	// ensure that refreshing after login does not lead to blank page
	if (($option=="login") && (isset($sessionUser)) && ($sessionUser!== false) && ($sessionUser->IsAuthenticated())) $option="dashboard";
	// Handle the user logging out
	if (isset($sessionUser) && $sessionUser!== false && $sessionUser->IsAuthenticated() and $option == "logout") {
		Logout();
		$option = "";
		$command ="";
	}
/*
	if ($_SESSION[USER]->IsAuthenticated()) {
		$_SESSION[USER] = User::RetrieveUser($_SESSION[USER]->id); // get data from db, in case it has changed
 		$sessionUser->isProjectStaff(null,true);//==true?"yes":"no";
 		$sessionUser->isProjectTemplateStaff(null,true);//==true?"yes":"no";
	}
 */	
	/**
	 * The $Project variable holds a referece to the User's currenly active project.
	 * @global Project $project
	 */
	$project = null;
	/**
	 * The $ProjectId variable is the ID of the user's currently
	 * selected (active) project.
	 * @global int $ProjectId
	 */
	$projectId = isset($_SESSION[PROJECT_ID])?$_SESSION[PROJECT_ID]:-1;
	trace("index.php>Project ID is :$projectId");
	$project = Project::getProject($projectId);
	if (is_null($project)) {

		//echo('Index.php>No Global Project information');
		$project = null;
		$projectId = changeProject(-1);//$_SESSION[PROJECT_ID] = -1;
	}
	else {
		//echo('Index.php>Set Global Project to '.$project->Name);
	}
	/*
	 * If we restrict the selection of projects to Authenticated answers.
	 */
	 /*
	if ($sessionUser->IsAuthenticated()){
		$OpIsSelectProject = ($option == "coretl" and $command == "select")?true:false;
		trace("projectId;".$projectId);
		/*
		 * If we have been told to select a project we either display a list of projects
		 * using the "coreTl" module.
		 *
		 * If however we are told to select a project but the projectId variable is already set
		 * we should have a Project object already, so we just go and retrieve it.
		 */
		 /*
		if (!$OpIsSelectProject){
			if ($projectId == -1)  {
				trace("Displaying List of Available projects.");
				$option="tl";
				$command="list";
			} else {
				trace("Project has already been selected, retriving Project information.");
				$project = Project::getProject($projectId);
				trace("Global Project ID:" .$project->projectId);
			}
		}
	}
	*/
	// select what component should be loaded.
	switch($option) {
		case 'projecttemplateadmin':
			include('modules/coreProjectTemplateAdmin.php');
			break;
		case 'projectadmin':
			include('modules/coreProjectAdmin.php');
			break;
		case 'siteadmin':
			include('modules/coreManagement.php');
			break;
		case 'tl':
			include('modules/coreTl.php');
			break;
		case 'directory':
			include('modules/coreDirectory.php');
			break;
		case 'map':
			include('modules/coreMap.php');
			break;
		case 'showlogin':
		case 'login':
			break;
		case 'garbage':
			include('modules/coreGarbage.php');
			break;
		case 'error':
		case 'message':
			$page= null;
			break;
		case 'api':
		    $page->Template='blank.tpl';
		    $wrapper->Template='blank.tpl';
		    include('modules/coreApi.php');   
		  break;
		case 'dav':
		  $page->Template='blank.tpl';
		  $wrapper->Template='blank.tpl';
		  include('modules/coreWebDav.php');
		  break;
		case 'credits':
		  include('modules/coreCredits.php');
		  break;
		case 'news':
		  include('modules/coreHome.php');
		  break;
		case 'download':
		  break;
		case 'office':
		  include('modules/coreOffice.php');
  		  break;
		default:
			/**
			 * @todo We want to add some code in here to enable 3rd party
			 * components that can hooking using other 'option=' urls.
			 */
			 $args = array();
			 /*$args[] = $_GET;
			$args[] = $_POST;
			*/
			 $customContent = $_PLUGINS->ExecuteVerb($option,array());
			 //print_r($customContent)
			 if ($customContent===false) {
			   //echo 'customContent is not set';
			   //include('modules/coreHome.php');
			   include('modules/coreDashboard.php');
			   $option = "dashboard";
			 }
			 else {
				//echo 'custom content set';
				//Redirect("/");
				//$page->Template='blank.tpl';
				$page->assign('contents',$customContent);
			}
			/*
			}
			 */
	}
	
	/*
	 * We want to check that we aren't displaying an item from the Project for download.
	 * as this is the only case where the output is not going to have been handled by
	 * a module.
	 */
	if ($option =='download'){
		include ('modules/download.php');
	} elseif ($option=='Directory' && $command='xml') {
		/**
		 * This is a bit of a hack to get rid of all of the normal "bumpf" that is 
		 * outputted normally so that the XML for map viewers works properly.
		 */
		include('modules/coreDirectory.php');
		exit();
	}
	else{
		/*
		 * Setup the page for display. We should try to keep all page
		 * related stuff to the same place, as this should make it easier to
		 * upkeep things.
		 */
		if (isset($sessionUser)) {
			$wrapper->assign('authenticated',$sessionUser!==false?$sessionUser->IsAuthenticated():false);
		}
		$wrapper->assign('config',$config);
 		//$wrapper->assign('strings',GetAllStrings());
		$wrapper->assign('strings',$strings);
		
		if (isset($sessionUser) && $sessionUser !== false && $sessionUser->IsAuthenticated() ) {
			// We setup the "jumplist".
			if ($sessionUser->superadmin==ALLOW) {
			 	$projects = Project::GetProjectsList(); // all projects
			} else {
			 	$projects = $sessionUser->GetProjects();
			}
			ksort($projects);
			/*
			 * We need this "hack" to keep the active project, selected in the
			 * jumplist.
			 */
			$wrapper->assign('currentProject',$project);			
			$wrapper->assign('projects',$projects);
			/*
			 * Restrict various page elements depending on the user's credentials
			 */
			 $wrapper->assign('user',$sessionUser);

			$sessionUser->GetSitewidePermissions();
			$is_Sadmin= false;
			$wrapper->assign('hasArchivedProjects',$sessionUser->GetNumberOfArchivedProjects()>0?true:false);

			$userprefs = $sessionUser->getPreferences();

			if (!is_null($page)) {
				$page->assign('userprefs',$userprefs);
			}
			$wrapper->assign('userprefs',$userprefs);
			/*
			if ($sessionUser->sitewidePermissions['SuperAdmin']) {
				if (!is_null($page)){
					$page->assign('is_superAdmin',true);	
				}
				$wrapper->assign('is_superAdmin',true);
				$wrapper->assign('showSettingsLink',true);
			}
			*/
			if (!is_null($project)) {
				$projectPerms= $sessionUser->GetProjectPermissions($project->id);
				if ($projectPerms['UseStaffTools']){
					$wrapper->assign('showSettingsLink',true);
				} else {
					$wrapper->assign('showSettingsLink',false);
				}
			}
		}
		// We should have a valid object ref for $page,
		if (!is_null($page) and (!is_null($page->Template))) {
			/*
			 * The next line takes the HTML output from the component
			 * that is handling the request and inserts it into the
			 * "chrome" provided by the Wrapper.
			 */
			$page->assign('config',$config);
			$page->assign('strings',$strings);
			$page->assign('title',$page->Title);
			//$page->assign('serverMode',$serverMode);
			$content = $page->fetch($page->Template);
			$wrapper->assign('content',$content);
			$wrapper->assign('title',$page->Title);
		} else { // but if we don't at this point we want to set it up to an "error" page.
			if ($option =='error'){
				require_once('include/ErrorMessages.php');
				$message= $errs[$command];
				$wrapper->assign('content',"<div class=\"areaTitle\">Message</div><div class=\"manageSectionContent\"><div class=\"sectionBox_manage\"><div class=\"sectionTitle\">Message</div>$message</div></div>");
				$wrapper->assign('title','Error');
			} else {
				$message=($command=='')?'Nothing to display':GetParam('cmd','');
				trace ($message);
				$message= stripslashes($message);
				$wrapper->assign('content',"<div class=\"areaTitle\">Message</div><div class=\"manageSectionContent\"><div class=\"sectionBox_manage\"><div class=\"sectionTitle\">Message</div>$message</div></div>");
				$wrapper->assign('title','Message');		
			}
		}
	}
	
	 // We're now setting up the "Wrapper's" UI elements.
	 //override styles:
	 $styles = '';
	 if (!is_null($project)){
	  $ptStyles ='';
	 	$pt =$project->GetProjectTemplate();
    if (!is_null($pt)){
     $ptStyles = $pt->Stylesheet;
    }
	 	//print_r($project);
	 	//$ptStyles = $project->GetProjectTemplate()->Stylesheet;
	 	$pStyles = $project->StyleSheet;
	 	$styles =$ptStyles ."\n". $pStyles ;
	 }
	$jQscripts = implode("\n", $wrapper->JQueryScripts);
	$scripts = implode("\n",$wrapper->scripts);
	
	//$jQscripts = "alert(\"test\");"; // add dashboard jquery scripts here
	$randomnum = rand(20, 10000); 
	$wrapper->assign('randomnum',$randomnum);
	$wrapper->assign('option',$option);
	$wrapper->assign('siteSettings',$siteSettings);
	$wrapper->assign('jQueryCode',$jQscripts);
	$wrapper->assign('scripts',$scripts);
	$wrapper->assign('overrideStyles',$styles);
	$wrapper->assign('home',$config['home']);
	$wrapper->assign('AppName',$config[PLATFORM_NAME]);
	if (isset($sessionUser->displayName)) {
		$wrapper->assign('username',$sessionUser->displayName);
	}
	$wrapper->assign('user',$sessionUser);
	$wrapper->assign('trace',dumpTrace());
	//$wrapper->assign('serverMode',$serverMode);
	$wrapper->assign('wcagNavigation',$page->WcagNavigation);

	//$page->assign('staffResourcesExtension',$esrText);
	//$wrapper->assign('extendRightSideBarContents',$ersbText); //this may have a different name int etemplate
	
	$editblueprintlink = DENY;
	if ($sessionUser !== false){
    if ((isset($project) && ($sessionUser->isProjectTemplateStaff($project->templateId))) 
  		|| ($sessionUser->isProjectTemplateStaff(null,true))) $editblueprintlink = ALLOW;
  	$wrapper->assign('editblueprintlink',$editblueprintlink);
  	$editsimulationlink = DENY;
  	//if ((isset($project) && ($sessionUser->isProjectStaff($project->id))) 
  	//	|| ($sessionUser->isProjectStaff(null,true))) $editsimulationlink = ALLOW;
	if ($sessionUser->superadmin==ALLOW) $editsimulationlink = ALLOW;
  	$wrapper->assign('editsimulationlink',$editsimulationlink);
  }			
	
	// Clean up the database.
	$database->Disconnect();
  
	$wrapper->assign('offlineAdminMode',$offlineAdminMode);
  	
	// Display the output.	 
	$wrapper->display($wrapper->Template);
?>