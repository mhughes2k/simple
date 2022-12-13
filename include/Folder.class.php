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
 * Folder Object
 * Object to hold information about Folders.
 * Folders can be "user created" or "project defined".
 * The "Correspondence/Sent & Received items" should always be READ ONLY ($canDelete=false)
 * @author Michael Hughes
 * @package SIMPLE
 * @subpackage Objects
 */
 /**
 * Represents a location in which Items can be kept.
 */
 	class Folder {
 			
 		var $folderid = -1;
		var $projectid = -1;
		var $name = "";
		var $allowdeletes = false;
		var $additem = true;
		var $icon = "";
		var $trashcan = false;
		var $newitems = 0;

		 /**
		 * Creates a folder object.
		 * @param array $dbRow an array representing a row from the Folders table. 
		 */
		function Folder($dbRow = null) {
			global $config;
			if (!is_null($dbRow)){
				trace("Folder Constructor");
				foreach($dbRow as $fieldName=>$field) {
					trace($fieldName .":". $field);
					$this->$fieldName = $field;
				}
				// hack to fix legacy problem from earlier version of tools
				if ($this->name=="Recieved") {
					$this->name="Received";
				}
				// hack to fix problem of spaces after commas
				//$this->name=trim($this->name);
				
			}
			if ($this->icon == "") {
				$this->icon = $this->icon = $config['defaultFolderIcon'];
			}
			if ($this->trashcan){
				$this->icon = $this->icon = $config['trashEmptyIcon'];
			}
			$this->newitems = $this->GetNewItems();
		}
		/**
		 * Saves changes back to the database.
		 * 
		 */
		function Save() {
			global $database;
			//dumpArray(debug_backtrace(),"Folder->Save()");
			if ($this->folderid<0){
				//insert
				//print_r($this);
				
				//echo $this->folderid;
				$sql = sprintf(
						"INSERT INTO folders " .
						"(".
						"projectId," .
						"name," .
						"allowdeletes," .
						"additem," .
						"canbedeleted," .
						"icon," .
						"trashcan) " .
						"VALUES " .
						"(%s,'%s',%s,%s,%s,'%s',%s)",
						$this->projectid,
						$this->name,
						($this->allowdeletes)?$this->allowdeletes:0,
						($this->additem)?$this->additem:0,
						($this->canbedeleted)?$this->canbedeleted:0,
						$this->icon,
						($this->trashcan)?$this->trashcan:0
					);
			}
			else {
				$sql = sprintf('UPDATE folders SET ' .
						'name = \'%s\', ' .
						'icon = \'%s\' WHERE folderid=%s',
						$this->name,
						$this->icon,
						$this->folderid
						);
			}
			//echo("Saving Folder: ".$sql);
			$result = $database->execute($sql);
			$this->folderid = $database->database->lastInsertID();
		}
		/**
		 */		
		function Delete() {
			global $database;
			$sql = sprintf('UPDATE FOLDERS SET deleted = 1 WHERE folderid =%s',
				$this->folderid 
			);
			$database->execute($sql);
		}
		/**
		 * Gets a list of items contained within a folder.
		 * @return array Array containing an associative array of the properties of the contents.
		 */
		function GetContents($sort=''){
			return Document::GetItems($this->folderid,$sort);
		}
		/**
		 * Returns the number of items in the folder.
		 * 
		 * @return int Number of items in the folder.
		 */
		function GetItemCount() {
			global $database;
			$sql = sprintf('SELECT count(*) FROM documents ' .
					'WHERE folderid = %s ' .
					'AND hidden = 0',
					$this->folderid
				);
			//echo $sql;
			$results = $database->query($sql);
			$result = $results;
			return $result;
		}
		/**
		 * get unread items for current user
		 */
		function GetNewItems() {
			global $database;
			// get number of documents in folder
			$sql = sprintf('SELECT documentuid FROM documents '.
				'WHERE folderid = %s AND hidden=0 AND deleted=0',
				$this->folderid
			);
			$results = $database->queryAssoc($sql);
			$totalDocs = count($results);
			// get readitems for current user
			$sql = sprintf("SELECT itemid FROM readitems ".
				"WHERE userid='%s' AND isread=1",
				$_SESSION[USER]->id
			);
			$readItems = $database->queryAssoc($sql);
			
			foreach($results as $r) {
				foreach($readItems as $read) {
					if ($r['documentuid']==$read['itemid']) {				
						$totalDocs--;				
					}
				}
			}
			return $totalDocs;
		}
		/**
		 * Retrieves a list of Folders that belong to a Project.
		 * 
		 * @param int $ProjectId ID of the Project.
		 * @param $force Not used.
		 */
		static function getFolders($ProjectId,$force = false) {
			global $database;			
			$sql = sprintf("SELECT * FROM folders WHERE projectId = %s AND deleted=0",
				$ProjectId
				);
				//echo("getFolders: ". $sql);
				$results = $database->queryAssoc($sql);
				$mFolders = array();
				foreach($results as $key=>$result) {
					//trace($result);
					
					$f = new Folder($result);
					//print_r($f);
					$f->addItem = $result['additem'];
					$mFolders[$result['folderid']] = $f;	
				}
			return $mFolders;
		}

		/**
		 * Retrieves a specific folder from the data store.
		 * 
		 * @param int $folderId ID of the folder to retrieve.
		 */
		static function getFolder($folderId) {
			global $database;			
			$sql = "SELECT * FROM folders WHERE folderid = ". $folderId;
			//echo("getFolders: ". $sql);
			$results = $database->queryAssoc($sql);
			$f = null;
			foreach($results as $key=>$result) {
				//trace($result);
				$f = new Folder($result);
				$f->addItem = $result['additem'];
			}
			return $f;
		}
	}

?>
