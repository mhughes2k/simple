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
 * Represents a notification for a user.
 * 
 * Alerts are attached to items, and an item can only have one alert per user.
 * 
 * Users may only create alerts for themselves!
 * @package SIMPLE
 * @subpackage Objects
 */
 
/**
 * Represents a notification for a user.
 * 
 * Alerts are attached to items, and an item can only have one alert per user.
 * 
 * Users may only create alerts for themselves!
 * 
 */
class Alert {

	public $Title;
	public $Message;
	public $AlertTime;
	public $ExternalNotificationTriggered=0;
	public $State=0;
	public $id;
	public $ItemId;
	public $ItemType;
	public $UserId;
	
	/**
	 * Constructs a new alert or populates one from a database.
	 * 
	 * @param array $Values An array containing values for the Alert.
	 */
    function __construct($Values=null) {
    	if (isset($Values)){
	    	$this->id = $Values['alertid'];
	    	$this->Title = stripslashes($Values['title']);
	    	$this->ItemId = $Values['itemid'];
	    	$this->ItemType = $Values['itemtype'];
	    	$this->Message= stripslashes($Values['message']);
			 $this->UserId = $Values['userid'];
			 $this->ExternalNotificationTriggered = $Values['ext_notified'];
			 $this->AlertTime = $Values['alerttime'];
    	}    	
    }
    /**
     * Deletes the alert.
     * 
     * Actually sets the deleted flag to true.
     */
    function Delete() {
		global $database;
		if (!isset($this->id)) {
      return;
    } 
		$sql = sprintf(
					'UPDATE alerts ' .
					'SET ' .
					'deleted = 1 ' .
					'WHERE alertid = %s',
					$this->id
				);
		$database->execute($sql);
    }  
     
    /**
     * Snoozes and alert by setting a new alerttime
     * @param string 	new alert time (date)
	*/
      function Snooze($alertTime = NULL,$message = NULL) {
     	global $database;
     	
     	if (!isset($this->id)) {
        return;
      }
     	
     	$sql = sprintf("UPDATE alerts SET alerttime='%s', ".
     					"message='%s' ".
     					"WHERE alertid=%s",
     					$alertTime,
     					$message,
     					$this->id);
     	$result = $database->execute($sql);
     }
     
    /**
     * Saves the Alert to the database
     * 
     * Saves the alert if it already exists or Inserts it if it is a new one.
     */
    function Save(){
    	global $database,$config;
    	if (!isset($this->id) | $this->id == -1) {
    		
	    	$sql = sprintf(
					'INSERT INTO alerts ' .
					'(itemid,itemtype,title,message,userid,alerttime,ext_notified,state) ' .
					'VALUES ' .
					'(%s,%s,%s,%s,%s,%s,%s,%s)',
					$database->database->quote($this->ItemId),
					$database->database->quote($this->ItemType),
					$database->database->quote($this->Title),
					$database->database->quote($this->Message),
					$this->UserId,
					$database->database->quote($this->AlertTime),
					$this->ExternalNotificationTriggered,
					$this->State
					
				);
    	} else {
    		$sql = sprintf(
					'UPDATE alerts ' .
					'SET ' .
					'title = %s, ' .
					'message = %s,' .
					'alerttime = %s,' .
					'ext_notified = %s,' .
					'state= %s ' .
					'WHERE alertid = %s',
					$database->database->quote($this->Title),
					$database->database->quote($this->Message),
					$database->database->quote($this->AlertTime)	,
					$this->ExternalNotificationTriggered,
					$this->State,
					$this->id
				);
    	}
    	trace($sql);
    	$database->execute($sql);
    	if (!isset($this->id)) {
			$this->id = $database->database->lastInsertID();
    	}
    } 
    
    static function GetAlerts($userid) {
      global $database;
      
      if (is_numeric($userid)) {
        $sql = sprintf
    			(
					'SELECT ' .
					'* ' .
					'FROM ' .
					'alerts ' .
					'WHERE ' .
					'userId = %s AND ' .
					'deleted = 0 ' .
					'ORDER BY alertid DESC',
          $userid 
    			);
    			$results = $database->queryAssoc($sql);
    			$as = array();
    	    foreach($results as $r){
    		    $a = new Alert($r);
    		    $as[] = $a;
    	    }
    	    
    	    return $as;
      }
      else {
      
      }
    }
    /**
     * Retrieves the alert data for an item for a specific user. 
     * 
     * If the is no alert, a blank one is returned, so the system should always
     * check if the returned object has a id==-1;
     * 
     * @param string $ItemType Type of the underlying item (doc or calendar at present).
     * @param integer $ItemId The ID of the underlying item.
     * @param integer $UserId An Internal User ID. 
     */
    static function GetAlert($ItemType, $ItemId, $UserId) {
    	global $database;
    	if ($ItemType=='' | !is_numeric($ItemId) | !is_numeric($UserId)) {
        return ;
      }
    	$sql = sprintf
    			(
					'SELECT ' .
					'* ' .
					'FROM ' .
					'alerts ' .
					'WHERE ' .
					'itemid = %s AND ' .
					'itemtype = \'%s\' AND 	' .
					'userId = %s AND ' .
					'deleted = 0 ' .
					'ORDER BY alertid DESC',
					$ItemId,
					$ItemType,
					$UserId
    			);
    	//echo $sql.'<br/>';
    	$results = $database->queryAssoc($sql);
    	//print_r($results);
    	if (count($results)>0){
    		$a = new Alert($results[0]);
    		//echo 'Alert();';	
    		
    	}
    	else {
    		$a = new Alert();
    		$a->id = -1;
    		$a->ItemId = $ItemId;
    		$a->ItemType = $ItemType;
    		$a->UserId = $UserId;
    	}
    	return $a;
    }

	/**
	 * retrieve an alert by its id
	 */    
	 public static function GetAlertById($id) {
	 	global $database;
	 	if ($id > '') {
	 		$query = sprintf("SELECT * FROM alerts WHERE alertid=%s",$id);
	 		$results = $database->queryAssoc($query);
	 		if (count($results)>0){
	    		$a = new Alert($results[0]);
    		}
    		return $a;
	 	}
	 } 
    
    /**
     * Gets the next 5 alerts for a user.
     * @param integer $UserId Internal ID of a User.
     * @return array An Array of Alert items.
     */
    static function GetHomePageAlerts($UserId) {
    	global $database,$config;
    	if (is_null($UserId) | $UserId == ''){
        return array();
      }
    	$currentdate = date($config['dbdatetimeformat']);
    	$sql = sprintf
    			(
					'SELECT ' .
					'* ' .
					'FROM ' .
					'alerts ' .
					'WHERE ' .
					'userId = %s AND ' .
					'deleted = 0 AND ' .
					'alerttime <= \'%s\' ' .
					'ORDER BY alerttime ASC ',
					$UserId,
					$currentdate
    			);
    	//echo $sql.'<br/>';
    	$results = $database->queryAssoc($sql);
    	//print_r($results);
    	$as = array();
    	foreach($results as $r){
    		$a = new Alert($r);
    		$as[] = $a;
    	}
    	return $as;
    } 
    
    static function Notify($projectId= null,$message="",$operation = NOTIFY_SEND,$item = null){
      global $config,$lang, $_PLUGINS;
      
      $project = Project::GetProject($projectId);
      $hookname='';
	  
      $sendTime= date($config['dbdatetimeformat']); 
	  if(file_exists("lang/$lang/mailResources.php")) {
      require_once("lang/$lang/mailResources.php");
      switch($operation){
            case NOTIFY_SEND:
              //$sendToMember = $member->notifyOnSend;
              $mailResourceClassname="Notify_Send_Message";
	      $hookname='onSendNotification';
              break;
            case NOTIFY_RECIEVE:
              //$sendToMember = $member->notifyOnRecieve;
	      $mailResourceClassname="Notify_Recieve_Message";
	      $hookname='onRecieveNotification';
              break;
      }
	  }
	  else {
		  return;
	  }
      if ($mailResourceClassname=='') {
	return;
      }
      if (!is_null($project)){
	
	
	$vb = $project->GetVariabliser();
	//die($mailResourceClassname);
	$mailObject= new $mailResourceClassname();
	$mailObject->Subject;
	$mailObject->Body;

      
      //echo("ID:$projectId, Message:$message, Operation:$operation");
      
	
      	$members = $project->getMembers(true);
	$recipients = array();
	$outmessage=$vb->Substitute($message);
	if (!is_null($item)) {
	//  $outmessage=str_replace("{item_id}",$item->id,$outmessage);
	}
        foreach($members as $member) {
          $AlertSideBarDestination = false;
	  $MailDestination = false;
	  $mailResourceClassname="";

	  switch($operation){
            case NOTIFY_SEND:
	      if ($member->notifyOnSend == true) {
		$AlertSideBarDestination = isset($member->Properties['sidebarnotification_onSend'])?$member->Properties['sidebarnotification_onSend']:false;
		$MailDestination= isset($member->Properties['emailnotification_onSend'])?$member->Properties['emailnotification_onSend']:false;
	      }
              break;
            case NOTIFY_RECIEVE:
	      if ($member->notifyOnReceive == true) {
		$AlertSideBarDestination = isset($member->Properties['sidebarnotification_onRecieve'])?$member->Properties['sidebarnotification_onRecieve']:false;
		$MailDestination=isset($member->Properties['emailnotification_onRecieve'])?$member->Properties['emailnotification_onRecieve']:false;
	      }
              break;
	    }
	   }
/* Override for testing. Should be disabled in final!*/
/*
	    $AlertSideBarDestination=true;
	    $MailDestination = true;
*/
	    if ($AlertSideBarDestination)
	    {
//            echo "Message:$message, userId:".$member->id.", alerttime:$sendTime";
	      
	      $notification = new Alert();
	      $notification->Message=$outmessage;
	      $notification->UserId=$member->id;
	      $notification->AlertTime=$sendTime;
	      $notification->Save();
	    }
	    if ($MailDestination) {
		$recipients[]=$member->email;	//add user to outgoing mail queue.
		if ($mailResult ===false) {
		  //die("MailResult =". (integer)$mailResult);
		}
		else {
		  //die('Mail result OK');
		}
	    }
	    else {
	      //die('Unable to send notification');
	    }
	    /*
	    Do any notifications that are implemented as plugins
	    */
	    if ($hookname !='') {
	      $_PLUGINS->trigger($hookNname,array($outmessage,$member,$sendTime,$project,$copy));
	    }
  	   }
	  
	  if (count($recipients)>0) {
	    $postoffice = &Mail::factory($config['mailBackend'],$config['mailBackendParams']);
	    $headers= array();
	    $headers['From']=$config['mailSender'];
	    $headers['Subject']= $outmessage;
	    //print("<div>$outmessage</div>");
	    //die();

	    $result = $postoffice->send($recipients,$headers,$outmessage);
	    if ($result) {
	    }
	    else {
	      die($result->getMessage());
	    }
	  }

      } 
}
?>
