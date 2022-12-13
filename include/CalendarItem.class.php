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
 * @subpackage  Objects
 */
include_once('Item.class.php');

define('ALERT_STATE_ACTIVE',1);
define('ALERT_STATE_EXPIRED',2);
define('ALERT_STATE_SOUNDED',3);
/**
* Implements functionality for event and task management.
*/
class CalendarItem extends Item{
	
	public $projectid = -1;
	public $id = -1;
	public $title  = "";
	public $content = "";
	public $createddate = "";
	public $createdby = -1;
	public $endTime = "";
	public $istask = false;
	public $location = "";
	public $members= array();
	public $alarmdelta = "";
	public $alarmTime = "";
	public $isRead = false;
	
	/**
	 * Builds an object from a database row (Associative array)
	 * 
	 * @param array $Row Array containing values
	 * @param integer $projectId An Internal Project ID, used to set the group members on new(blank) items.
	 */
	function __construct($Row = null,$projectId = null){
		global $database;

		if (is_null($Row) && is_null($projectId)) {
      return;
    }
		
		if (!is_null($Row)){
			$this->itemType = 'cal';
			foreach($Row as $fieldName=>$field) {
				//echo "$fieldName:$field";
				$this->$fieldName = $field;
			}
			$project = Project::GetProject($this->projectid);
			if (is_null($project)) {
				//DisplayError(100);
				//echo 'P is null: ';
				//return;
			}

			if (is_null($this->isRead)) {
				$this->isRead = false;	
			}

			//echo "ST:".$this->startdate;
		//	echo "AD:".$this->alarmdelta;		
			if ($this->alarmdelta != "") {
				$st = strtotime($this->startdate);
				$at = strtotime($this->alarmdelta,$st);
				$this->alarmTime = date("r",$at);
				//echo "AT:".date("r",$this->alarmTime);
			}
			$getAssignedMembersSql = sprintf(
				"SELECT c.calitemid, c.userid, users.displayname " .
				"FROM calendarassignments c " .
				"LEFT JOIN users ON users.userid = c.userid " .
				"WHERE calitemid =%s  and c.deleted=0",
				$this->id);
			$assigned = $database->queryAssoc($getAssignedMembersSql);
			//echo $getAssignedMembersSql.'<br>';
			//echo 'Assigned:';print_r($assigned);
  		$out = array();
			if (!is_null($project)){
  			$memberList = $project->getMembers();
  			$userPerms= User::GetIndividualProjectPermissions($_SESSION[USER]->id,$this->projectid);
  			$userIsStaff = $userPerms["UseStaffTools"]?true:false;
  			
  			foreach($memberList as $member){
  				//echo $member->GetIndividualProjectPermissions('UseStaffTools',$this->projectid);
  				$perms = User::GetIndividualProjectPermissions($member->id,$this->projectid);
  				$memberIsUser = $member->id == $_SESSION[USER]->id;
  				$memberIsStaff = $perms["UseStaffTools"]?true:false;
  				
  				/*
  				 * Display all members if
  				 *  user is a staff member 
  				 * Display only non-staff users if 
  				 *  user is not a staff
  				 *  
  				 */
  				if (($userIsStaff) | (!$memberIsStaff)){
  					/*
  					echo "<br>".$member->id ." is  set to displayname: ". $member->displayName . " Assigned:false<br>";
  					echo "<br>";
  					echo "<br>Project Member";
  					*/
  					//print_r($member);				
  					$out[$member->id] = array("displayname"=>$member->displayName,'assigned'=>0);
  					//echo 'preout';
  					//print_r($out);
  					foreach($assigned as $assignee){
  						//echo "<br>Assignee:";
  						//print_r($assignee);
  						//echo $assignee['id'] . " is  set to:".$out[$member->id]['assigned']."<br>";
  						//echo "a->".$assignee['userid'] .":b->" . $member->userId;
  						//echo $assignee['userid'] .':'. $member->id .'<br>';
  						if($assignee['userid'] == $member->id) {
  							//echo '<p>Changing Member\'s assignment</p>';
  							$out[$member->id]['assigned'] = 1;
  							break;
  						}
  						else {
  							//echo '<p> Not Changing Member\'s assignment</p>';
  						}
  					}
  				}
  			}
  			$this->members=$out;
			}
			//echo 'out';
			//print_r($out);
			//print_r($project->getMembers());
		}
		else {
			$t = FormatDateTime();		
			$this->startdate = $t;
			$this->enddate = $t;
			
			//build the "assignment" information
			$out = array();
			//echo "CalItem: Retrieving Project: $projectId";
			$project = Project::GetProject($projectId);
			//echo "CalItem: Project: ".$project->id;
			if (is_null($project)){
				DisplayError(100);		
				return;
			}
			if (!is_null($project)) {
  			$this->projectid= $project->id;
  			$memberList = $project->getMembers();
  						
  			$userPerms= User::GetIndividualProjectPermissions($_SESSION[USER]->id,$this->projectid);
  			$userIsStaff = $userPerms["UseStaffTools"]?true:false;
  			
  			foreach($memberList as $member){
  				//echo $member->GetIndividualProjectPermissions('UseStaffTools',$this->projectid);
  				$perms = User::GetIndividualProjectPermissions($member->id,$this->projectid);
  				$memberIsUser = $member->id == $_SESSION[USER]->id;
  				$memberIsStaff = $perms["UseStaffTools"]?true:false;
  
  				//echo "<br>".$member->userId ." is  set to displayname: ". $member->displayName . " Assigned:false<br>";
  				//echo "<br>";
  				//echo "<br>Project Member";
  				//print_r($member);		
  				if (($userIsStaff) | (!$memberIsStaff)){
  					
  					if ($memberIsStaff) {
  						$out[$member->id] = array("displayname"=>$member->displayName.'(staff)',"assigned"=>0);
  					}	
  					else {
  						$out[$member->id] = array("displayname"=>$member->displayName,"assigned"=>0);
  					}
  				}
  			}
  			$this->members = $out;		
			}
		}
	}
	/**
	 * Deletes an item from the system.
	 * 
	 * As in keeping with most activities, deleting a Calendar item does not actually 
	 * delete it, merely hides it from the user.
	 */
	function Delete() {
		global $database;
		$sql = sprintf(
			"UPDATE calendar SET deleted=1 WHERE id=%s",
			$this->id
		);
		$database->execute($sql);
	}
	/**
	 * Saves the object back to the database.
	 */
	function save() {
		global $database;
		$sql ="";
		if ($this->id>0) {
			$sql = sprintf(
				'UPDATE calendar SET ' .
				'title ="%s", ' .
				'content ="%s", ' .
				'startdate ="%s", ' .
				'enddate ="%s",' .
				'istask = %s, ' .
				'location ="%s", ' . 
				'completed = %s ' .
				'WHERE id =%s' ,
				$this->title,
				$this->content,
				FormatDateTime($this->startdate),
				FormatDateTime($this->enddate),
				($this->istask?1:0),
				$this->location,
				$this->completed,
				$this->id
			);	
		}
		else {

			$sql = sprintf(
				"INSERT INTO calendar " .
				"(projectid, title,content,  startdate, enddate, istask, location)" .
				"VALUES " .
				"(%s,%s,%s,%s,%s,%s,%s)",
				$this->projectid,
				$database->database->quote($this->title),
				$database->database->quote($this->content),
				$database->database->quote(FormatDateTime($this->startdate)),
				$database->database->quote(FormatDateTime($this->enddate)),
				($this->istask?1:0),
				$database->database->quote($this->location)								
			);
		} 

		$database->execute($sql);
		$this->id = $database->database->lastInsertID();

		//RebuildAlerts();
		$this->SaveAssignments();
	}
/**
 * Updates the CalendarAssignments table with the appropriate 
 * assigments.
 */
	function SaveAssignments() {
		global $database;
		//echo 'Updating Assignments'; 
		//if ($database->database->supports('transactions')) {
		//	$database->database->beginTransaction();
		//}
		//since the object has the list of items we can just delete all the old assigments:
		$removeOldAssignmentsSql = sprintf (
										"UPDATE calendarassignments " .
										"SET deleted = 1 " .
										"WHERE calitemid = %s",
										$this->id
									);
		//echo($removeOldAssignmentsSql);
		$database->execute($removeOldAssignmentsSql);
		foreach ($this->members as $userid=>$member){
			
			if ($member['assigned']) {
						$sql = sprintf(
							"INSERT INTO calendarassignments" .
							"(calitemid,userid)" .
							"VALUES " .
							"(%s,%s)",
							$this->id,
							$userid
						);
		
				$database->execute($sql);
			}
		}
		
		//if ($database->database->supports('transactions')) {
		//	$database->database->commit();
		//}
	}
	/**
	 * Marks the item as read
	 * 
	 * Uses the underlying Item::MarkItemAs() function. 
	 */
	function MarkAsRead(){
		$itemType = "calendar" ;//$this->isTask?"task":"event";
		$userId = $_SESSION[USER]->id;
		Item::MarkItemAs($this->isRead,$this->id,$itemType,$userId);
	}
	
	/**
	 * Retrieves a specific calendar item from the database.
	 * @param int $ItemId ID of the item to retreive
	 * @return A CalendarItem
	 */
	static function getCalendarItem($ItemId){
		global $database;
		$userId = $_SESSION[USER]->id;
		$sql = sprintf("SELECT c.*,readitems.isRead FROM calendar c " .
				"left join readitems " .
				"ON readitems.itemid = c.id AND readitems.userId = %s AND readitems.itemtype ='calendar' " .				
				"WHERE c.id = %s AND deleted=0",
				$userId,
				$ItemId);
		$results = $database->queryAssoc($sql);
		//echo $sql;
		//echo "Count: ". count($results);
		
		$c = new CalendarItem($results[0]);
		return $c;
	}
	/**
	 * Gets all of the Calendar Items for a particular date & project.
	 * 
	 * @param integer $projectId Internal Project ID.
	 * @param string $date A String representation of a date. Should be in Database format for dates.
	 * @return array An Array of CalendarItems 
	 */
	static function GetItemsForDate($projectId,$date) {
		global $database;
		$userId = $_SESSION[USER]->id;
		$sql = sprintf("SELECT c.*,readitems.isRead FROM calendar c " .
				"left join readitems " .
				"ON readitems.itemid = c.id AND readitems.userId = %s AND readitems.itemtype ='calendar' " .				
				"WHERE c.projectid = %s AND deleted=0 " .
				"AND startdate >= '%s 00:00:00' AND startdate <='%s 23:59:59'",
				$userId,
				$projectId,
				$date,
				$date
				);
		trace($sql);
		$results = $database->queryAssoc($sql);
		//print_r($results);
		trace('Calendar Items: '. count($results));
		$items = array();
		foreach($results as $result) {
			$c = new CalendarItem($result);
			$items[] =$c;
		}
		return $items;
	}
	/**
	 * Gets a list of Calendar items that exist between two dates
	 * 
	 * @param string $projectId
	 * @param string $FromDate
	 * @param string $ToDate 
	 * @return array Array of Calendar items.
	 */
	static function GetItems($projectId,$FromDate = "", $ToDate="") {
		global $database;
		$userId = $_SESSION[USER]->id;
		$fd =0 ;
		$fromClause = "";
		$td =0 ;
		$toClause = "";
		if ($FromDate != ""){
			$fd  = strtotime($FromDate);
			$fromClause = " startdate >= '" . FormatDateTime($fd) ."'";
		}
		if ($ToDate != "" ){
			$td= strtotime($ToDate);
			$toClause = " enddate <= '" . FormatDateTime($td)."'";
		}
		$join = "";
		$wjoin = "";
		if ($td >0 or $fd>0) {
			$wjoin = " AND ";
		}
		if ($td>0 and $fd>0){
			$join = " AND ";
		}
		$sql = sprintf("SELECT c.*,readitems.isRead FROM calendar c " .
				"left join readitems " .
				"ON readitems.itemid = c.id AND readitems.userId = %s AND readitems.itemtype ='calendar' " .				
				"WHERE c.projectid = %s AND deleted=0".$wjoin.$fromClause.$join.$toClause,
				$userId,
				$projectId);
		trace($sql);
		$results = $database->queryAssoc($sql);
		trace('Calendar Items: '. count($results));
		$items = array();
		foreach($results as $result) {
			$c = new CalendarItem($result);
			$items[] =$c;
		}
		return $items;
	}
	/**
	 * Returns all of the "Task" items for a user.
	 * 
	 * Task items are just Calendar items that have the "Task" flag set.
	 * @param integer $userid Internal ID of a user.
	 * @return array Array of CalendarItems
	 */
	static function GetTaskItems($userid,$projectId) {
		global $database,$project;
		$items = array();
		if (!isset($projectId) | is_null($projectId) | is_null($userid) | ''==$userid) {


		}
		else {
		  $sql = sprintf(
  				'select * from calendar c ' .
  				'left join calendarassignments ca ' .
  				'ON c.id = ca.calitemid ' .
  				'WHERE ca.userid = %s ' .
  				'AND ca.deleted = 0 ' .
  				'AND projectid = %s ' .
  				'AND istask =1 AND c.deleted=0',
  				$userid,
  				$project->id
  				);
  		//echo $sql;
		  $results = $database->queryAssoc($sql);
		  $items = array();
		  foreach($results as $result) {
  			$c = new CalendarItem($result);
  			$items[] =$c;
		  }
		}
		return $items;
	}

}
?>
