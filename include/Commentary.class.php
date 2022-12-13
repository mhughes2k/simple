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
 * The Idea of commentary is to allow any Player (or really an user) in a project to
 * add notes to an item in the project. 
 * 
 * These notes can be used to form a discussion about the item, or collaborate. It
 * may even be used to provide feedback from staff about the quality of the item. 
 */
 /**
  * Manages the commentary for an item in a project.
  */
class Commentary {

	/**
	 * Construct the item.
	 */
    function __construct($dbRow= null) {
    	if (!is_null($dbRow)){
    		foreach($dbRow as $fieldname=>$value){
    			$this->$fieldname = $value;
    		}
    	}
    }
    /**
     * Adds a new comment to the item.
     * 
     * @param integer $UserId ID of the user that adds the comment.
     * @param string $displayName Display name of the user
     * @param string $ItemType The type of source item that the comment is attached to.
     * @param integer $ItemId Unique ID of the source item.
     * @param string $Subject Subject line for the comment.
     * @param string $Comment Body of the comment.
     * @param bool $AdminComment Indicates the comment is for administrative users only.
     */
    static function AddComment($UserId,$displayName,$ItemType,$ItemId,$Subject,$Comment,$AdminComment = false) {
    	global $database,$config;
    	$ts = date($config['dbdatetimeformat']);
    	$sql = sprintf(
			'INSERT INTO commentary (userid,displayName,subject,comment,itemtype,itemid,admincomment,commentcreated) ' .
			'VALUES ' .
			'(%s,\'%s\',\'%s\',\'%s\',\'%s\',%s,%s,\'%s\')',
			$UserId,
			$displayName,
			$Subject,
			$Comment,
			$ItemType,
			$ItemId,
			(integer)$AdminComment,
			$ts
		);
		trace("Adding Comment:".$sql);
		$database->execute($sql);
    }
    /**
     * Retrieves the commentary on an item in a data store.
     * 
     * Commentaries may be attached to any item so we need to be able to
     * differentiate between the different data stores available. These currently are
     * - doc 
     * - calendar
     * - doctemplate
     * These should not be mistaken with the different types of file that can be stored in 
     * the system (the <code>ContentType</code> field in the <code>documents</code> table)
     * 
     * @param string $ItemType The item type. See {@link Constants.php} DOC_TYPE_* constants.
     * @param int $ItemId The ID of the item.
     * @return array An array of {@see Comment} objects.
     */
    static function GetCommentary($ItemType,$ItemId) {
    	global $database;
    	$sql = sprintf(
			"SELECT * FROM commentary WHERE itemtype='%s' AND itemid = %s AND deleted =0 AND admincomment = 0 ORDER BY commentId DESC",
			$ItemType,
			$ItemId
			);
		trace($sql);
		$comments = array();
		$commentRows = $database->queryAssoc($sql);
		trace("# comments:". count($comments));
		foreach($commentRows as $row) {
			$comments[]= new Comment($row);
		}
		return $comments;
    }
    /**
     * Pulls out a specific users comments on an item. (Includes the flag)
     * 
     * @param string $ItemType Type of the source item.
     * @param integer $ItemId ID of the source Item.
     * @param integer ID of the creating/owning user.
     * @return array Array of Comment objects. 
     */
    static function GetUsersCommentary($ItemType,$ItemId,$userId){
		global $database;
		
		$sql = sprintf(
			"SELECT * FROM commentary WHERE itemtype='%s' AND itemid = %s AND userid = %s AND deleted =0 AND admincomment = 0 ORDER BY commentId DESC",
			$ItemType,
			$ItemId,
			$userId
			);
		$comments = array();
		$commentRows = $database->queryAssoc($sql);
		trace("# comments:". count($comments));
		foreach($commentRows as $row) {
			$comments[]= new Comment($row);
		}
		return $comments;
    }
    /**
     * Gets the administrative commentary for an item.
     * 
     * @param string $ItemType Type of the item.
     * @param Integer $ItemId Unique Id of the item.
     * @return array Array of Comment objects
     */
    static function GetAdministrativeCommentary($ItemType,$ItemId){
    	  global $database;
    	$sql = sprintf(
			"SELECT * FROM commentary WHERE itemtype='%s' AND itemid = %s AND deleted =0 AND admincomment =1 ORDER BY commentId DESC",
			$ItemType,
			$ItemId
			);
			trace($sql);
		$comments = array();
		$commentRows = $database->queryAssoc($sql);
		trace("# comments:". count($comments));
		foreach($commentRows as $row) {
			$comments[]= new Comment($row);
		}
		return $comments;
    }
    /**
     * Deletes a specified comment
     * 
     * @integer $commentId ID of the comment to delete.
     */
    static function DeleteComment($commentId){
    	global $database;
    	$sql = sprintf(
			"UPDATE commentary SET deleted = 1 WHERE commentId = %s",
			$commentId
			);
		trace($sql);
		$database->execute($sql);
    }
    
    static function GetHomePageComments($userId) {
      global $database;
      
      $latestComments =array();

      $sql = sprintf(
        'select * from commentary c LEFT JOIN readitems r ON r.itemid =c.itemid AND r.itemtype = c.itemtype  where r.isread=0 and c.userid=%s',
        $userId
      );
      //echo $sql;
      $results = $database->queryAssoc($sql);
      return $results;
    }
}
?>
