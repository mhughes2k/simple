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
  * Maintains information about an item in a project's dirctory.
  * 
  * A Project's directory is the place where Players go to find out addresses and other
  * useful information.
  */
class DirectoryItem {
	
	public $ID = -1;
	public $name = "";
	public $address = "";
	public $location  ="";
	public $directoryvisible = false;
	public $projectid = -1;
	public $projectrole = "";
	public $vrvisible=1;
	/**
	 * Array of Projects that this DirectoryItem is linked to.
	 * 
	 * DirectoryItems provide the mechanism to link projects together. Every NPC in a project may
	 * be a Player-role for another project. 
	 */
	public $LinkedProjects  = null;
	public $Properties = null;
	private $databaseRow =null;
	/**
	 * Constructs a new DirectoryItem either using data retrieved from the datastore or 
	 * with no values.
	 * 
	 * @param array $dbRow An array representing a row in the underlying data table. 
	 */
    function __construct($dbRow = null) {
    	$this->LinkedProjects = array();
    	trace( 'DI Construct');
    	if (!is_null($dbRow)) {
    		$this->databaseRow = $dbRow;
    		$this->ID =$dbRow['directoryid'];
    		foreach($dbRow as $fieldName=>$value){
    			trace('Adding:'.$fieldName . ';');
    			$this->$fieldName = stripslashes($value);
    		}
    		if (is_null($this->directoryvisible)) {
          $this->directoryvisible = false;
        }
    		//echo $this->linkedprojects;
  			$projects  = explode(",",$this->linkedprojects);
  			
  			//$pid = strtok($this->linkedprojects,',');
    		//dumpArray($projects);
    		foreach($projects as $pid){
    		//while ($pid !== false){
    			$p = Project::GetProject($pid);
    			//echo $pid.'<br/>';
    			$this->LinkedProjects[$pid] = $p;
    			//$pid = strtok(',');
    		}
    		//dumpArray($this->LinkedProjects);
    		//print_r($this->properties);
    		$this->Properties = DeserialiseArray($this->properties);
    	}	
    }
    function Copy() {
    	$d = new DirectoryItem($this->databaseRow);
    	
    	$d->ID = -1;
    	return $d;
    }
    function GetRole() {
      global $database;
      
      $BpRoleId = $this->projectrole;
      $BpId = Project::GetProjectTemplateId($this->projectid);
      
      $roleNameSql= sprintf("SELECT rolename FROM projecttemplateroles WHERE projecttemplateid='%s' AND projecttemplateroleid='%s'",
			$BpId,$BpRoleId);
      $result = $database->database->query($roleNameSql);
      return $result;
    
    }
    function Save() {
    	global $database;	
      $lpsIdsonly =array();
    	foreach($this->LinkedProjects as $projectId=>$project) {
         $lpsIdsonly[] = $projectId;
      }  
    	if ($this->ID > 0){

        $char_update_sql = sprintf(
					'UPDATE directory ' .
					'SET ' .
					'name =\'%s\', ' .
					'address =\'%s\',' .
					'location =\'%s\',' .
					'directoryvisible =%s,' .
					'vrvisible =%s,' .
				//	'projectid =%s,' .
					'projectrole =\'%s\',' .
					'linkedprojects = \'%s\' ' .
					'WHERE directoryid = %s',
					SafeDb($this->name),
					SafeDb($this->address),
					$this->location,
					(int)$this->directoryvisible,
					(int)$this->vrvisible,
				//	$this->projectid,
					SafeDb($this->projectrole,"text"),
					implode(',',$lpsIdsonly),
					$this->ID
				);
				//echo($char_update_sql.'<br/>');
				$database->execute($char_update_sql);
    	}
    	else {
    		$char_insert_sql = sprintf(
					'INSERT INTO directory ' .
					'(projectid,name,address,location,directoryvisible,vrvisible,projectrole,linkedprojects) ' .
					'VALUES ' .
					'(%s,\'%s\',\'%s\',\'%s\',%s,%s,\'%s\',\'%s\')',
					$this->projectid,
					SafeDb($this->name),
					SafeDb($this->address),
					$this->location,
					($this->directoryvisible?1:0),
					(isset($this->vrvisible)?1:0),
					SafeDb($this->projectrole),
					implode(',',$lpsIdsonly)
				);
			
				$database->execute($char_insert_sql);
    	}
    }
    
    /**
     * Gets a Directory Item using its Address and Project Id.
     * 
     * We need to be able to pull out Directory items for a Project quickly. 
     * The most common time to do this is when we want to send a Document to the entity represented
     * by the DirectoryItem. This method allows us to pull out the correct DirectoryItem quickly.
     * 
     * @param string $address The address to which we are sending the item.
     * @param int $projectId The ID of the project.
     * @return DirectoryItem A DirectoryItem or null if the address doesn't belong to a DirectoryItem
     */
    static function GetDirectoryItemByAddressAndProject($address,$projectId){
    	global $database;
    	$sql = sprintf("SELECT * FROM directory WHERE address = '%s' AND projectid=%s",
				$address,
				$projectId
				);
				// if project id in array
				//echo $sql;
		$results = $database->queryAssoc($sql);
		//print_r($results);
		$item = null;
		foreach($results as $r) {
      $item = new DirectoryItem($r);
		}
		
		//$item = null;
		//if(count($results)>0){
		//	if (!is_null($results[0])){
		//		$item = new DirectoryItem($results[0]);
		//	}else {
		//		$item = null;
		//	}
		//}
		return $item;
    }
    static function GetVrItems($projectId){
          global $database;
      $sql = sprintf("SELECT * FROM directory WHERE vrvisible =1 AND projectid=".$projectId . ' ORDER BY name ASC');
  		//echo("GetDirectoryItems($projectId): $sql");
  		$results = $database->queryAssoc($sql);
  		trace("GetDirectoryItems($projectId) count: ".count($results));
  		$item = null;
  		$items =array();
  		foreach($results as $result){
  		  $item = new DirectoryItem($result);
  			$item->LinkedProjects = DeserialiseArray($result['linkedprojects']);
  			$items[] =$item;
  		}
  		return $items;
    
    }
    /**
     * Gets the directory items for specified project.    
    */
    static function GetDirectoryItems($projectId) {
      global $database;
      $sql = sprintf("SELECT * FROM directory WHERE directoryvisible =1 AND projectid=".$projectId . ' ORDER BY name ASC');
  		//echo("GetDirectoryItems($projectId): $sql");
  		$results = $database->queryAssoc($sql);
  		trace("GetDirectoryItems($projectId) count: ".count($results));
  		$item = null;
  		$items =array();
  		foreach($results as $result){
  		  $item = new DirectoryItem($result);
  			$item->LinkedProjects = DeserialiseArray($result['linkedprojects']);
  			$items[] =$item;
  		}
  		return $items;
    }
    static function GetDirectoryItemByProjectIdAndRoleName($projectid,$rolename){
        global $database;
    	$sql = sprintf(
				"SELECT directoryid FROM directory WHERE projectrole ='%s' AND projectid= %s",
				$rolename,$projectid
				);
				//echo $sql;
  		//trace("GetDirectoryItems($projectId): $sql");
  		$results = $database->queryAssoc($sql);
  		//trace("GetDirectoryItems($projectId) count: ".count($results));
  		$item = null;
  		$items =array();
  		if (count($results)>0){
        	return DirectoryItem::GetDirectoryItem($results[0]['directoryid']);
      	}		
    }
    static function GetDirectoryItem($itemId ){
       	global $database;
    	$sql = sprintf(
				"SELECT * FROM directory WHERE directoryid = %s",
				$itemId
				);
//		trace("GetDirectoryItems($projectId): $sql");
		$results = $database->queryAssoc($sql);
//		trace("GetDirectoryItems($projectId) count: ".count($results));
		$item = null;
		$items =array();
		foreach($results as $result){
			if (!is_null($result)){
				$item = new DirectoryItem($result);
				
			}else {
				$item = null;
			}
		}
		return $item;
    }
}
?>
