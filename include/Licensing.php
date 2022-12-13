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

  if (!defined("TLE2")) die ("Invalid Entry Point");
	/**
	 * Works out the server mode based on the license key.
	 */
  
     	
  function DecryptServerLicense(){
    global $config;
    $licenseParts = array();
	
	      $licenseParts['mode']= MODE_ENTERPRISE_UNLIMITED;
      $licenseParts['bpLimit'] = 0; //no limit
      $licenseParts['simLimit'] = 0; //no limit
	/*
    $key = $config['serverLicense'];
    $rawKeyParts=split('-',$key);
    
    /* Parse Server Mode 
    $licenseParts['mode'] = MODE_COMMUNITY;
    if(strlen($rawKeyParts[0])>=3) {
      $licenseParts['mode']= MODE_ENTERPRISE_NORMAL;
    }
    if (strlen($rawKeyParts[0])>=6) {
      $licenseParts['mode']= MODE_ENTERPRISE_UNLIMITED;
      $licenseParts['bpLimit'] = 0; //no limit
      $licenseParts['simLimit'] = 0; //no limit
    }
    
         /* parse the 2nd part for the key limit 
    $licenseParts['bpLimit']= $rawKeyParts[1];
    $licenseParts['simLimit']= $rawKeyParts[2];
    
    */
    return $licenseParts;
  }
  
	/**
	 * Gets a string description of the current server mode.
	 */   	
  function GetServerModeString($mode=null) {
    global $serverMode;
    if (is_null($mode)) {
    	$smode = $serverMode['mode'];
    }
    switch($smode) {
      case MODE_COMMUNITY:
        return "Community";
        break;
      case MODE_ENTERPRISE_NORMAL:
        return "Enterprise";
        break;
      case MODE_ENTERPRISE_UNLIMITED:
        return "Enterprise-Unlimited";
        break;     
      default:
        return "Unknown";
        break;           
    }
  }
  
  /**
   * Returns the "bare" user account cap before CALs are applied
   */     
  function GetDefaultUserAccountLimit() {
    global $serverMode;
    $defaultUserLimit = COMMUNITY_USER_LIMIT;
    switch($serverMode){
     case (MODE_ENTERPRISE_NORMAL) :
      $defaultUserLimit = ENTERPRISE_USER_LIMIT;
      break;
     case (MODE_ENTERPRISE_UNLIMITED) :
      $defaultUserLimit = -1;
      break; 
    }
    return $defaultUserLimit;
  }
  /**
   * Returns the "actual" user account cap.
   */     
  function GetUserAccountLimit() {
    global $serverMode,$config;
    $defaultUserLimit = GetDefaultUserAccountLimit();
    
    //get number of CALs installed from the database;
    $cals = $config['clientLicenses'];
   //print_r($cals);
    $calCount = 0;
    foreach($cals as $cal) {
      $c = decryptCal($cal);
    
      $calCount += $c;
    } 
    
    return $defaultUserLimit+$calCount;
  }
  
  function decryptCal($calString){
    return strlen($calString);
  }
?>
