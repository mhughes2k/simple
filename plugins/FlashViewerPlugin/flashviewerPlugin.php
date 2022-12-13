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
$_PLUGINS->registerFunction('onDisplayContent','GenerateFlashPlayer','GenerateFlashPlayer');

/**
 * Displays a flash movie player in the browser window if the item is flash content!
 */
function GenerateFlashPlayer($ContentType, $ProjectResourceId = null ,$Content){
	global $config,$metrics;
	//echo('Media Plugin'.$ContentType);
	switch(strTolower($ContentType)){
		case 'application/x-shockwave-flash':
		case 'image/jpeg':
		case 'image/gif':
			break;
		default:
			return false;	
	}
	
	$sourceParam = '';
	$sourceObj = null;
	if (!is_null($ProjectResourceId)){
		//we're using a resource in a project
		$ProjectResource = explode('.',$ProjectResourceId);
		$resourceType = $ProjectResource[1];
		switch($resourceType){
			case 'doc':
				$sourceObj = Document::GetDocument($ProjectResource[2]);
				$sourceParam = $config['home'].'index.php?option=download&download=0&docuid='.$ProjectResource[2];
				break;
		}
	}
	$output ='';
	switch(strTolower($ContentType)){
		case 'image/jpeg':
		case 'image/gif':
			//echo 'displaying jpeg';
	$output .= "<img src=\"$sourceParam\"" ;
	if (!is_null($sourceObj)) {
		$output .= ' alt="'.$sourceObj->filename.'"';
		$output .= ' title="'.$sourceObj->filename.'"';
	}
	$output .="/>";
//echo $output;
			break;
		case 'application/x-shockwave-flash':
			
			$output .='<object width="550" height="400">';
			$output .='<param name="movie" value="'.$sourceParam.'">';
			$output .='<EMBED src="'.$sourceParam.'" type=application/x-shockwave-flash width=100% height=100%>';
			$output .='</embed>';
			$output .='</object>';

			break;
		default:
			return false;	
	}
	return $output;
	
}
?>