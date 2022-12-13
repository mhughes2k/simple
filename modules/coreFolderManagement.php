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
 * Implements Folder management controller.
 * 
 * @package Core
 */ 
	if (!defined("TLE2")) die ("Invalid Entry Point");
	$cannedtemplates =null;
	$blanktemplates=null;


	if (is_null($page)) {
		$page = new Page();
	}

//	$page->Title="Professional Office Environment: ". $projectId;	//Should really display the name of the project.
	switch (strtolower($command) ) {
		case "addfolder":
			AddFolder();
			break;
		case "deletefolder":
			DeleteFolder();
			break;
		case "editfolder":
			EditFolder();
			break;
		case "savefolder":
			SaveFolder();
		default:
			ViewFolders();
	}
	
	/**
	 * Displays the folders in the current project & allows user to select them
	 * to change their properties.
	 */
	function ViewFolders() {
		
	}
	
	/**
	 * Displays form to create a new folder.
	 */
	function AddFolder() {
		
	}
	/**
	 * Displays confirmation to delete folder.
	 */
	function DeleteFolder() {
		
	}
	/**
	 * Displays form to edit a folder
	 */
	function EditFolder() {
		
	}
	/**
	 * Saves changes to the folder.
	 */
	function SaveFolder() {
		
	}
	
?>
