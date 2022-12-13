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


class Container {

    function __construct() {
    }
    
    /**
     * Returns array of containers
     */
    static function GetContainers(){
    	global $database;
		$sql = sprintf('SELECT * FROM containers');
		$result = $database->queryAssoc($sql);
		return $result;
    }
    static function GetBlueprints($containerId) {
    	global $database;
			//trace("Getting Templates");
			$sql = 'SELECT pt.* ' .
					'FROM projecttemplates pt ' .
					'WHERE deleted=0 AND container=\''.$containerId.'\' ' .
					'ORDER BY pt.name ASC';
			$results = $database->queryAssoc($sql);
			//echo $sql;
			$templates = array();
			$user=$_SESSION[USER];
			$user->GetSitewidePermissions();
			$is_Sadmin= false;
			foreach($results as $result) {
			//dumparray($result);
				$t = new ProjectTemplate($result);
				if ($is_Sadmin or $user->isProjectTemplateStaff($t->id)){
					$templates[] = $t;
				}
			}
			//print_r($results);
		//	echo "::GetTemplates():";
			//dumparray($templates);
			return $templates;
    }
}
?>
