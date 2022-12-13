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
  * Groups a number of projects in to 1 logical group.
  * 
  * Can only contain projects based on the same project template.
  */
class ProjectGroup {

	public $Projects;
	public $Name = '';
	public $id = -1;
	private $error = '';
	/**
	 * 
	 */
    function __construct($Name='',$ID=-1) {
    	$this->Name = StripMagicQuotes($Name);
    	$this->id = $ID;
    	$this->Projects = array();	
    }
    /**
     * Loads an existing ProjectGroup from the database.
     */
    static function Load($ProjectGroupId) {
    	global $database,$metrics;
    	$pg = null;
    	$sql = sprintf(
			'SELECT * FROM projectgroups WHERE projectgroupid = %s;'
			,
			$ProjectGroupId
			);
			//echo($sql);
		$result = $database->queryAssoc($sql);
		$pg= null;
		if (count($result)>0){
			$result = $result[0];
			$pg = new ProjectGroup($result['projectgroupname'],$result['projectgroupid']);
			//$metrics->recordMetric('Loading Project group members:',$result);
//			print_r($result);
			if ($result['members']=="") {
				$members = array();
			} else {
				$members = explode('|',$result['members']);//DeserialiseArray($result['members']);
			}
//			print_r($members);
			$pg->Projects = $members;
//      print_r($pg->Projects); 
			//$pg->Projects = strtok($result['members'],"|");
			/*
			foreach($pg->Projects as $projectId){
			//echo $result['members'];				
			//$projectId =strtok($result['members'],"|");
			//while ($projectId !== false){
				//echo '*'.$projectId.'<br>';
				if ($projectId != ''){		
					//echo 'Getting '.$projectId;	
					$p = Project::GetProject($projectId);
					if (is_null($p)) {
						//Project does not exist!
						//echo 'Project does not exist';
						$pg->RemoveProjectFromGroup($projectId);
						//$pg->Save();
					}
					else {
						if ($pg->Projects[$projectId]==$projectId){							
							$pg->RemoveProjectFromGroup($projectId);
							//$pg->Save();
						}else {
							//$pg->Projects[]=$projectId;
						}
					}
				}
				//$projectId =strtok("|");
				*/
			}
		
		return $pg;
    
    }
    /**
     * Returns the number of member simulations in the group.
     */         
    function MemberCount() {
    	global $database,$metrics;
      
		return count($this->Projects);
    }
    /***
     * Saves the ProjectGroup back to the database.
     *  
     * If the group has not been given a name we give it 
	 * an automatically generated one. This however can only be done
	 * once it has been written to the DB as we'll use the Row ID as the
	 * group number: e.g. "Group 31".
	 * 
     */
	function Save() {
		global $database;
		$user = $_SESSION[USER];
		$sql = '';
		$sqlGpName = $this->Name;
		
		//echo 'Save(): '. $sqlGpName;
		$members = implode('|',$this->Projects) ;
		//SerialiseArray($this->Projects);
		$needRename = false;
		if ($this->id <0) {
			if ($sqlGpName == '') {
				$needRename = true;
			}
			$sql = sprintf(
				'INSERT INTO projectgroups ' .
				'(projectgroupname,members,creatorid) ' .
				'VALUES (%s,\'%s\',\'%s\')',
				$database->database->quote($sqlGpName),
				$members,
				$user->id
			);
		}
		else {
			//echo 'updating';
			if ($sqlGpName =='') {
				$sqlGpName = 'Group '.$this->id;
			}
			$sql = sprintf(
				'UPDATE projectgroups ' .
				'SET ' .
				'projectgroupname = %s, ' .
				'members = \'%s\' ' .
				'WHERE projectgroupid = %s',
				$database->database->quote($sqlGpName),
				$members,
				$this->id
			);
		}
		$result= $database->execute($sql);
		if ($this->id <0) {
			$this->id = $database->database->lastInsertID();
		}
		
		/*
		 * If the group has not been given a name we give it 
		 * an automatically generated one. This however can only be done
		 * once it has been written to the DB as we'll use the Row ID as the
		 * group number: e.g. "Group 31"
		 */
		 /*
		$lastGroupId = $database->database->lastInsertID();
		if ($this->id <0) {
			$this->id = $lastGroupId;
		}
		*/
		if ($needRename) {
			//echo 'Renaming Empty project name';
			$sqlGpName = 'Group '. $this->id;
			$renameSql = sprintf(
					'UPDATE projectgroups SET projectgroupname =\'%s\' ' .
					'WHERE projectgroupid = %s',
					$sqlGpName,
				  $this->id
					);
			//echo $renameSql;
			$database->execute($renameSql);
		}
		trace('ProjectGroup->Save() completed');
    }
    /**
     * Marks the PG as deleted.
     * 
     * Sets the id to -1 indicating that a SAve() operation will create a NEW pg.
     */
    function Delete() {
    	global $database;
    	$sql = sprintf('UPDATE projectgroups SET deleted = 1 WHERE projectgroupid =%s',$this->id);
    	$database->execute($sql);
    	$this->id = -1;
    }
    /**
     * Adds an existing project to the group.
     * 
     * @param $ProjectId int The ID of the project to add.
     */
    function AddProjectToGroup($ProjectId) {
    	if (!isset($this->Projects[$ProjectId])) {
    		$this->Projects[$ProjectId] = $ProjectId;
    		return true;
    	}
    	else {
    	   if ($ProjectId ==$this->Projects[$ProjectId]) {
    		  $this->error = "Project #$ProjectId is already in this group";
    		  print_r(debug_backtrace());
    		  return false;
    		}
    		else {
          $this->Projects[$ProjectId] = $ProjectId;
    		  return true;
        }
    	}
    }
    
    function GetError() {
    	return $this->error;
    }
    /**
     * Removes a Project from the group.
     * 
     * This is going to be a little inefficient as we're not using
     * and associative array for the list of member project ID's. 
     * @param $ProjectID int ID of the project to remove.
     */
    function RemoveProjectFromGroup($ProjectId) {
    	//echo 'Removing '.$ProjectId;
    	//if ($this->Projects[$ProjectId]== $ProjectId) {
    	foreach($this->Projects as $key=>$id) {
    		if ($this->Projects[$key]== $ProjectId) {
    			unset($this->Projects[$key]);
    			$this->Projects = array_values($this->Projects);
    			break;
    		}
    	}
    }

    /**
     * Gets a list of the ProjectGroups on the server.
     */
    static function GetProjectGroups() {
    	global $database;
    	$groups = array();
    	$sql = 'SELECT * FROM projectgroups WHERE deleted = 0 ORDER BY projectgroupname ASC ';
//echo $sql;
    	$results = $database->queryAssoc($sql);

    	foreach($results as $result) {
    		$pg = new ProjectGroup($result['projectgroupname'],$result['projectgroupid']);
			$pg->Projects = DeserialiseArray($result['members']);
			$groups[$pg->id] = $pg;
			//trace("Count:". count($pg->Projects));
    	}
    	return $groups;
    }

}
?>
