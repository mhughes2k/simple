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
 * Core Transactional Learning Module
 *
 * This module implements the functionality for a "lobby" where players enter and decide upon the game they are going to play.
 *
 * @author Michael Hughes
 * @package TLE2
 */
	if (!defined("TLE2")) die ("Invalid Entry Point");

 	switch (strtolower($command))  {
		case "select":
			selectProject(getParam('projectid',-1));
			break;
		case 'listarchivedprojects':
			listArchivedProjects();
			break;
		case 'list':
		default:
			listProjects();
	}

	function selectProject($projectid) {
		global $config;
		//$_SESSION[PROJECT_TEMPLATE_ID] = $projectid;
		//$selectedProjectRole = "admin";
		//$_SESSION["projectRole"] = $selectedProjectRole;
		$redir = $config['home'].'?option=office';
		changeProject($projectid);
		
		
		$project = Project::GetProject($projectid);
		
		$pt = $project->GetProjectTemplate();
		/*
		if (!is_null($pt)){
			$pt_props = $pt->Properties; //$properties['folders']
			//print_r($pt_props);
			$redir = getParam('redirect',"") ;
			if (isset($pt_props['homeoption']) && $pt_props['homeoption'] != '') {
  		  
			  $redir= $config['home']."index.php?".$pt_props['homeoption'];

			  //die($redir);
			  Redirect($redir);
			  exit;
		  }
		} else {
		  
		}
		*/

		if ($redir!="") {
			Redirect($redir);
		} else {
			$redir = $config['home'].'?option=office';
			//die('Redirecting to Office '. $redir);
			Redirect($redir);
		}
	}
	 
	 	/**
	* Generates a list of all of the projects that the student/user can play.
	*/
	function listProjects() {
		global $page,$database;
		$page->Template= 'projectList.php.tpl';
		$noSkipOn1Project= GetParam('noskip',0);
	 	if ($_SESSION[USER]->superadmin==ALLOW) {
		 	$projects = Project::GetProjectsList(); // all projects
		} else {
			$projects = $_SESSION[USER]->GetProjects();
		}
		
		if (!$noSkipOn1Project && count($projects) ==1) {
      //there is *exactly* one project listed
      $p = each($projects);
      //selectProject($p->id);
      
      $redir=$config['home'].'?option=tl&cmd=select&projectid='.$p['key'];
      //die($redir);
      Redirect($redir);
      return;
    }
		$page->Title='Active Projects';
		//$page->assign('title','Active Projects');
		$page->assign('projects',$projects);
		//$page->Template="blank.tpl";
		//$page->assign('contents',"This would be a list of projects that you are doing but it's not done yet!");
	}
	
	function listArchivedProjects() {
		global $page,$database;
		$page->Template= 'projectList.php.tpl';

		$projects = $_SESSION[USER]->GetArchivedProjects();
		$page->Title='Archived Projects';
		//$page->assign('title','Archived Projects');
		$page->assign('projects',$projects);
	}
 ?>
