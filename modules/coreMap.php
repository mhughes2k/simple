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
 * Core Map Module
 * @author Michael Hughes
 * 
 * @package TLE2
 * @subpackage Core
 */
 if (!defined("TLE2")) die ("Invalid Entry Point");
    $cmd = isset($cmd)?$cmd:'' ;
  	switch (strtolower($cmd))  {
		case "view":
		default:
			ViewMap();
	}
/**
 * Displays Characters/Entities from a project on a map!
 * 
 * You can use the GET parameter "projectID" to change to a different
 * map. This will change the active simulation for the user howerver.    
 */	
function ViewMap() {
	global $page,$project,$config;
	$page->Template='map.php.tpl';
	$simulationid=GetParam('projectId',null);
	if (!is_null($simulationid)) {
	   changeProject($simulationid);
	}
	$page->assign('viewItemUrl',urlencode($config['home'].'index.php?option=directory&cmd=viewitem&id='));
}
 ?>
