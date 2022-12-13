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
 * Clean up script.
 * 
 * This script should be run at regular intervals.
 * 
 * Call it by accessing http://<installlocation/index.php?option=garbage
 * 
 * This script can only be run by super-users.
 * 
 * @package Core
 */
 
if ($_SESSION[USER]->id == 0){
	/*
	 * Clean up script
	 */
	 /**
	  * Clean out any expired alerts.
	  */
	 function cleanAlerts() {
	 	global $database;
	 	$sql = 'DELETE FROM alerts WHERE deleted =1';
	 }
	 /**
	  * Clean out any expired/dead calendar events/tasks.
	  */
	 function cleanCal(){
	 	global $database;
	 	//$cal = 'DELETE FROM calendar WHERE deleted =1';
	 	$cala ='DELETE FROM calendarassignments WHERE deleted = 1';
	 	
	 	$database->execute($cala);
	 }
	 
	 /**
	  * Remove any old comments.
	  */
	 function cleanCommentaries() {
	 	global $database;
	 }
	 /**
	  * Only clears out DEs that have been deleted on the fly.
	  * 
	  * Should not delete any DE's that still have a project! 
	  */
	 function cleanDirectory() {
	 	global $database;
	 }
	 /**
	  * Cleans up Document-Templates.
	  */
	 function cleanDts() {
	 	global $database;
	 }
	 /**
	  * Cleans up any dead links between Calendar items and files.
	  */
	 function cleanEventResources() {
	 	global $database;
	 }
	 /**
	  * Sort out dead ProjectGroups
	  */
	 function cleanProjectGroups() {
	 	global $database;
	 	//clean the permissions for the groups
	 	
	 	//remove the dead groups.
	 }
	 /**
	  * Looks for any project-templates marked as deleted and 
	  * removes them from the system.
	  * 
	  * Should only run on PTs that have no Projects! 
	  * Clean up the other related project-template tables.
	  */
	 function cleanDeadPts() {
	 
	 }
	 /**
   	  * Clean up User & group permissions.
   	  * 
   	  * Check that every permission still has a parent (User or Group)
	  * and remove any orphaned ones.
	  */
	 function checkPermissions() {
	 	
	 }
	 cleanAlerts();
	 
	 Redirect($config['home']);
}
?>