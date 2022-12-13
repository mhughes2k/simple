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
	* Takes a document template and merges it with data available within the system or a project.
	*/
	class DocumentBuilder {
		
		/**
		*
		*/
		public $Template = "";
		
		/**
		*
		*/
		public $project = null;
		
		/**
		*
		* @param string $strTemplate Contents of a Template.
		* @param Project $project Project object.
		*/
		function __construct($strTemplate,$project) {
			
		}
		
		function getDocument() {
			return $this->Template;
		}
	
	}

?>