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
  * Represents an individual comment within a commentary.
  */
class Comment extends Item{

	public $id = -1;
	public $userId = -1;
	public $subject ="";
	public $comment = "";
	public $itemType = "";
	public $itemId = -1;
	public $commentCreated = "";
	
	/**
	 * 
	 */
    function __construct($Row = null) {
    	foreach($Row as $fieldName=>$field) {
			$this->$fieldName = $field;
		}
    }
/**
 * Returns the name of the author.
 * 
 * This returns the author's current displayname not the name recorded in the item.
 * @return string Name of the Author.
 */
    function GetAuthorName(){
    	trace("Comment:GetAuthorName(".$this->userid.")");
    	return User::RetrieveUser($this->userid)->displayName;
    }
}
?>