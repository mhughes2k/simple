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

/*
 * Created on 3 Apr 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 $mimetypes= array();
 $mimetypes['application/rtf']='rtf';
 $mimetypes['application/msword']='doc';
 $mimetypes['application/vnd.openxmlformats-officedocument.wordprocessingml.document']='docx';
 $mimetypes['application/vnd.openxmlformats-officedocument.wordprocessingml.template']='dotx';
 $mimetypes['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']='xlsx';
 $mimetypes['application/vnd.openxmlformats-officedocument.spreadsheetml.template']='xlst';
 $mimetypes['application/vnd.openxmlformats-officedocument.presentationml.presentation']='pptx';
 $mimetypes['application/pdf']='pdf';
 $mimetypes['text/html']='htm';
 $mimetypes['text/plain']='txt';
 $mimetypes['image/jpeg']='jpg';
 $mimetypes['image/png']='png';
 $mimetypes['video/x-ms-wmv']='wmv';
 $mimetypes['application/vnd.google-earth.kml+xml']='kml';
 $mimetypes['application/vnd.ms-excel']='xls';
 $extensionTypes['png']='image/png';
 $extensionTypes['wmv']='video/x-ms-wmv';
 $extensionTypes['kml']='application/vnd.google-earth.kml+xml';
  
 function GetMimeExt($contenttype){
	global $mimetypes;
	if (isset($mimetypes[$contenttype])) {
		 return $mimetypes[strtolower($contenttype)];
    }  
    return '';			
 }
	
	function GetMimeFromExtension($extensionTypes) {
    	global $extensionTypes;
			if (isset($extensionTypes[strtolower($extensionTypes)])) {
			 return $extensionTypes[strtolower($extensionTypes)];
      } 
      return '';
    }
	
    function IsSubbable($extension) {
      $type = GetMimeFromExtension($extension);
      switch(strtolower($type)) {
        case 'text/html':
        case 'text/plain':
          return true;
          break;
      }
      return false;
    }
?>
