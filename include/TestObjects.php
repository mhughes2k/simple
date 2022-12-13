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
 * Test functions
 *
 * Provides various test functions.
 *
 * @package TLE2
 * @author Michael Hughes
 * 
 */
if (!defined("TLE2")) die ("Invalid Entry Point");
	class TestObject {
		static function getFolders($projectid) {
			$folders = null;
			switch($projectid) {
				case 1:
					$folders = array(
						"Sent and Received"=>new Folder($projectid,"Sent and Received",false),
						"Drafts"=>new Folder($projectid,"Drafts",true)
					);
					break;
				case 104:
					$folders = array(
						"Sent and Received"=>new Folder($projectid,"Sent and Received",false),
						"Drafts"=>new Folder($projectid,"Drafts",true),
						"Other stuff"=>new Folder($projectid,"Other stuff",true)
					);
					break;
			}
			return $folders;
		}
		
		static function getResources($projectid) {
			$resources = null;
			switch($projectid) {
				case 1:
					$resources = array(
						0=>array("url"=>"http://www.yahoo.com","title"=>"Yahoo"),
						1=>array("url"=>"http://www.google.com","title"=>"Google"),
						2=>array("url"=>"http://intranet.law.strath.ac.uk","title"=>"Intranet")
					);
					break;
				case 104:
					$resources = array(
						0=>array("url"=>"http://www.yahoo.com","title"=>"Yahoo"),
						1=>array("url"=>"http://www.google.com","title"=>"Google"),
						2=>array("url"=>"http://www.microsoft.com","title"=>"Microsoft"),
					);
					break;
			}
			return $resources;
		}
		
		static function getPiFolderContents($folder) {
			$items = array();
			switch (strtolower($folder)) {
				case "sent and received":
					$items = array(
						0=>array("link"=>"e","subject"=>"Pi Client Letter","from"=>"Senior Partner","to"=>"This firm","icon"=>"http://technologies.law.strath.ac.uk/tle2/images/M_images/pdf_button.png"),
						1=>array("link"=>"e","subject"=>"Pi Client Letter1","from"=>"Senior Partner","to"=>"This firm","icon"=>"http://technologies.law.strath.ac.uk/tle2/images/M_images/printButton.png"),
						2=>array("link"=>"e","subject"=>"Pi Client Letter2","from"=>"Senior Partner","to"=>"This firm","icon"=>""),
						3=>array("link"=>"e","subject"=>"Pi Client Letter 4","from"=>"Senior Partner","to"=>"This firm","icon"=>"")
					);
					break;
				case "drafts":
					$items = array();
					break;
				case "other stuff":
					$items = array();
					break;
			}
			return $items;
		}
		
		static function getPcFolderContents($folder) {
			$items = array();
			switch (strtolower($folder)) {
				case "sent and received":
					$items = array(
						0=>array("link"=>"e","subject"=>"Pc Client Letter","from"=>"Senior Partner","to"=>"This firm","icon"=>"http://technologies.law.strath.ac.uk/tle2/images/M_images/pdf_button.png"),
						1=>array("link"=>"e","subject"=>"Pc Client Letter1","from"=>"Senior Partner","to"=>"This firm","icon"=>"http://technologies.law.strath.ac.uk/tle2/images/M_images/printButton.png"),
						2=>array("link"=>"e","subject"=>"Pc Client Letter2","from"=>"Senior Partner","to"=>"This firm","icon"=>""),
						3=>array("link"=>"e","subject"=>"Pc Client Letter 4","from"=>"Senior Partner","to"=>"This firm","icon"=>"")
					);
					break;
				case "drafts":
					$items = array();
					break;
				case "other stuff":
					$items = array();
					break;
			}
			return $items;
		}
		
		static function getFolderContents($projectid,$folder) {
			echo "Getting fc $folder";
			switch($projectid) {
				case 1:
					return TestObject::getPiFolderContents($folder);
					break;
				case 104:
					return TestObject::getPcFolderContents($folder);
					break;
			}
		}
		
		static function getPiFolder($folderName) {
			switch ($folderName) {
				case "Sent and Received":
					return new Folder("Sent and Received",false);
					break;
				case "Drafts":
					return new Folder("Sent and Received",true);
					break;
			}
		}
		
		static function getPcfolder($folderName){
			switch ($folderName) {
				case "Sent and Received":
					return new Folder("Sent and Received",false);
					break;
				case "Drafts":
					return new Folder("Drafts",true);
					break;
				case "Other stuff":
					return new Folder("Other Stuff",true);
					break;
			}
		}
		static function getCannedDocuments($ProjectTemplateId) {
			switch($ProjectTemplateId) {
				case 1:
					return array("doc1"=>"Blank Document","doc2"=>"Headed Document","doc3"=>"Initial Writ");
					break;
				case 104:
					return array("doc1"=>"Blank Document","doc2"=>"Headed Document","doc3"=>"Initial Writ");
					break;
			}
		}
		static function getTemplates($ProjectTemplateId) {
			switch($ProjectTemplateId) {
				case 1:
					return array("doc1"=>"Blank Document","doc2"=>"Headed Document","doc3"=>"Initial Writ");
					break;
				case 104:
					return array("doc1"=>"Blank Document","doc2"=>"Headed Document","doc3"=>"Initial Writ");
					break;
			}
		}
		static function getTestDirectoryEntries() {

		}
		static function getProject($projectid) {
			switch($projectid) {
			case 1:
				return new Project(1,1,"Personal Injury Project");
				break;
			case 104:
				$p = new Project(1,1,"Personal Injury Project");
				$p->TutorsSeeTools= false;
				return $p;
				break;
			}
		}
	}

?>