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
/**
 * Provides common functionality to items (Comments, Calendar & Documents etc)
 */
abstract class Item {
	public $itemType; // com, doc, flag, task, cal
	public $id;
	/**
	 * Is null if there is no readRecord, true if read and false if unread.
	 */
	private $_isRead = null;
	/**
	 * Finds out if the specified item has been read by a user.
	 * 
	 * This information is cached the first time it is accessed.
 	*/
	public function IsRead($UserId=null,$refresh=false) {
		global $database;
		$returnValue = true;
		trace("Item ".$this->id ." ReadState: ");
		trace(is_null($this->_isRead)?"in:true":"in:false");
		trace($this->_isRead?"it:true":"it:false");
		if($refresh  or is_null($this->_isRead)){
			trace("ReadUnread Data unknown, retrieving from database");
			if (is_null($UserId)){
				$UserId = $_SESSION[USER]->id;
			}
			$sql = sprintf("SELECT * FROM readitems " .
					"WHERE itemtype ='%s' AND itemid = '%s' AND userid =%s",
					$this->itemType,
					$this->id,
					$UserId
				);
			trace($sql);
			$results = $database->queryAssoc($sql);
			//dumpArray($results);
			if(count($results) >0) {
				$readRecord = $results[0];
				if($readRecord['isread']){
					trace("Found Read record stating item is read.");
					$returnValue = true;
				}
				else{
					trace("Found Read record stating item is unread.");
					$returnValue = false;
				}
				trace("readUnread data available item is ". ($returnValue?"true":"false"));
				$this->_isRead =$returnValue;	
			}
			else {
				trace("No readUnread data available, item is UNREAD");
				$this->_isRead =null;	
				$returnValue = false;
			}
			//echo $returnValue?"read":"unread";
			
		}else {
			$returnValue = $this->_isRead;
		}
		trace("IsRead:[".$returnValue."]");
		return $returnValue;
	}
	/**
	 * Finds out if the a record of this item being read exists.
	 * 
	 */
	function ReadRecordExists($UserId = null){
		global $database;
		if (!is_null($this->_isRead) and $this->IsRead()) {
			/*
			 * the read record must exist if _isRead is anything other than null.
			 */
			
			return true;
		}
		if (is_null($UserId)){
			$UserId = $_SESSION[USER]->id;
		}
		$sql = sprintf("SELECT * FROM readitems " .
				"WHERE itemtype ='%s' AND itemid = '%s' AND userid =%s",
				$this->itemType,
				$this->id,
				$UserId
			);
		$results = $database->queryAssoc($sql);
		if(count($results) >0) {
			return true;
		}
		return false;
	}
	function MarkRead($UserId= null){
		trace("Marking Item as read");
		if (is_null($UserId)) {
			$UserId = $_SESSION[USER]->id;
		}
		$this->MarkItemAs(true,$UserId);
	}
	function MarkUnRead($UserId= null){
		trace("Marking Item as unread");
		if (is_null($UserId)) {
			$UserId = $_SESSION[USER]->id;
		}
		$this->MarkItemAs(false,$UserId);
	}
    /**
     * Marks an item as being "read" by the current user.
     */
    function MarkItemAs($ItemState,$UserId) {
		global $database;
		$sql = "";
		trace("id:".$this->id);
		trace("itemType:".$this->itemType);
		if ($ItemState == $this->IsRead()) {
			trace("the Itemstate is being set to the current state so don't bother updating");
			return;
		}
		if (is_null($UserId)) {
			$UserId = $_SESSION[USER]->id;
		}
		trace("Updating Read State");
		if (!$this->ReadRecordExists($UserId)) {
			$sql = sprintf(
				"INSERT INTO readitems (itemid,itemtype,userid,isread) " .
				"VALUES (%s,'%s',%s,%s)",
				$this->id,$this->itemType,$UserId,$ItemState
			);
		}
		else {
			$sql = sprintf(
				"UPDATE readitems SET isread = %s " .
				"WHERE itemid = %s AND itemtype='%s' AND userid = %s",
				$ItemState,
				$this->id,	
				$this->itemType,
				$UserId
			);			
		}
		trace("ReadState Update Sql:$sql");
		$database->execute($sql);
    }
    function MarkItemAsUnReadForAll() {
    	global $database;
    	$ItemState = 0;
		$sql = "";
		trace("id:".$this->id);
		trace("itemType:".$this->itemType);
		if ($ItemState == $this->IsRead()) {
			trace("the Itemstate is being set to the current state so don't bother updating");
			return;
		}
		/*
		 * We only have to update the items which are in the table (either marking the 
		 * item as specifically read or unread) 
		 * If the item hasn't been read by a user, there is no record, thus
		 * is already unread!
		 */
		$sql = sprintf(
			'UPDATE readitems SET isread = %s ' .
			'WHERE itemid = %s AND itemtype=\'%s\'',
			$ItemState,
			$this->id,	
			$this->itemType
		);			
		trace("ReadState Update Sql:$sql");
		$database->execute($sql);
    }
    /**
     * Flags are a special type of comment.
     * 
     * We can only have 1 flag set on a document for a user at one time.
     */
    function SetFlag($FlagText) {
    	$flags=  Commentary::GetUsersCommentary('flag',$this->id,$_SESSION[USER]->id);
    	foreach($flags as $flag) {
    		Commentary::DeleteComment($flag->commentid);
    	}
    	Commentary::AddComment($_SESSION[USER]->id,'','flag',$this->id,'Flagged',$FlagText,false);
    }
    function UnsetFlag() {
		$flags=   Commentary::GetUsersCommentary('flag',$this->id,$_SESSION[USER]->id);
    	foreach($flags as $flag) {
    		Commentary::DeleteComment($flag->commentid);
    	}
    	
    }
    /**
     * Retrieves the flag
     */
    function GetFlag(){
    	$flags=  Commentary::GetUsersCommentary('flag',$this->id,$_SESSION[USER]->id);
    	if (count($flags)>0) {
    	 return $flags[0]->comment;
    	}
    	return '';
    }
    
     /**
      * links an item to this item
      * @param int the id of the item being linked to
      * @param string the itemtype (doc,cal,task,com) of the item being linked to
      */
      function AddLinkedItem($LinkItemId,$LinkItemType) {
      	global $database;
      	// $this->type == 'calendar' or 'doc' or 'task' or 'com'
      	$query = sprintf("SELECT COUNT(*) AS numlinks FROM itemlinks WHERE sourceitemtype='%s' ".
      					"AND sourceitemid=%s AND destitemtype='%s' AND destitemid=%s",
      					$this->itemType,
      					$this->id,
      					$LinkItemType,
      					$LinkItemId
      					);
      	$results = $database->queryAssoc($query);
      	if ($results[0]['numlinks'] < 1) {
      		$query = sprintf("INSERT INTO itemlinks (sourceitemtype,sourceitemid,".
      						"destitemtype,destitemid) VALUES ('%s',%s,'%s',%s)",
      					$this->itemType,
      					$this->id,
      					$LinkItemType,
      					$LinkItemId);
      		$result = $database->execute($query);
      	}
      }    
    
    /**
     * returns a list of linked items
     */
     function GetLinkedItems() {
     	global $database;
     	$linkeditems = array();
     	$query = sprintf("SELECT * FROM itemlinks WHERE sourceitemid=%s",$this->id);
     	$results = $database->queryAssoc($query);
     	foreach ($results as $r) {
     		$linkeditems[] = array('id'=>$r['destitemid'],'itemType'=>$r['destitemtype']);
     	}
     	return $linkeditems;
     }
    
    /**
     * gets the id of the project the item belongs to 
     * @param int $itemId 
     * @param mixed $itemType
     * @return int $projectId  
     */
     public static function GetProjectId($itemId, $itemType) {
     	global $database;
     	switch ($itemType) {
     		case 'com':
     		// get comment, then any (recursive) parent comments, then the item that the comment is of
     		 
     			$sql = sprintf("");
     			break;
     		case 'task':
     			break;
     		case 'cal':
     			break;
     		case 'doc':
     		case 'flag':
     			$sql = sprintf("SELECT folderid FROM documents WHERE documentuid=%s",
     							$itemId);
     			$results = $databaase->queryAssoc($sql);
     			$folderId = $results[0]['folderid'];
     			$sql = sprintf("SELECT projectid FROM folders WHERE folderid=%s",
     							$folderId);
     			$results = $database->queryAssoc($sql);
     			$projectId = $results[0]['projectid'];
     			break;
     		default:
     	}
     	return $projectId;
     }
     
}
?>
