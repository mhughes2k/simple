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
 * Security Helper functions
 * 
 * @author Michael Hughes
 * @package TLE2
 */
	if (!defined("TLE2")) die ("Invalid Entry Point");

	/**
	* Internal/default TLE2 authentication.
	*/
	function TleAuthenticate($username,$password,$authType) {
		global $database;
		/*
		 * We can never get a valid authentication by calling this
		 * without the $authType set to "TleAuthenticate"
		 */
		 
		if ($authType!='TleAuthenticate') {
			//print("exiting!$authType");
			return false;
		}
		
		$query =sprintf("SELECT * FROM users WHERE username ='%s' ".
						"AND deleted=0 ".
						"AND active=1",$username);
		$result = $database->queryAssoc($query);
		$num_results=count($result);

		/*
		 * We need the results to equal EXACTLY one record,
		 * Other wise we have a username AND password collision!
		 */

		 /*$pwd='password';
		echo '<br>'.$salt.'>'.md5($pwd).'>'.$salt.md5($pwd).'>'.md5($salt.md5($pwd)).'<br>';
		$pwd = $password;
		echo '<br>'.$salt.'>'.md5($pwd).'>'.$salt.md5($pwd).'>'.md5($salt.md5($pwd)).'<br>';
		echo $pwd;
		*/
		//$hashedPwd =md5($salt.md5($password));
		if ($num_results ==1) {
			//we need the salt value for the account:
			$salt =$result[0]['salt'];
			$hashedPwd = HashPassword($password,$salt);

			if ($hashedPwd == $result[0]['password']) {
				//die('Authenticated');				
				return true;
			}
			else {
				//die('Not Authenticated');
				return false;
			}
		}
		if ($num_results>1){
			/*
			 * we should inform the administrator that some thing weird has occured!
			 */
		}
		return false;
	}
		
	/**
	 * Performs authentication against either the TLE2 database or a 3rd party.
	 * @return User A user object representing either an Authenticated or unauthenticated user.
	 */
	function Authenticate() {
		$username = GetParam("username","");
		$password = GetParam("password","");
		$authType = GetParam("authType","TleAuthenticate");
	//	die("$username:$password:$authType");
    return AuthenticateWithCredentials($username,$password,$authType);  
  }
	function AuthenticateWithCredentials($username,$password,$authType) {
		global $_PLUGINS,$metrics,$database;
		//$_SESSION['userid']= 1;
		//return true;
		//echo 'Authenticate';
		$username = GetParam("username","");
		$password = GetParam("password","");
		$authType = GetParam("authType","");
		//$username = SafeDb($username);
		//$password = SafeDb($password);
		//$authType = SafeDb($authType);
		$user = $_SESSION[USER];
		$result = $_PLUGINS->trigger('onAuthenticateUser',array($username,$password,$authType));
		$overallAuth = false;
		if (is_null($result)) {
		  trace("null result");
		  echo 'null result';
		}
//		echo 'Authenticate foreach';
		foreach($result as $authAttemp=>$outcome) {
			//$metrics->recordMetric('LoginAttemptResult', "Authenticated?: " .$authAttemp." - " . ($outcome==true?"true":"false").'<br/>');
			//$metrics->recordMetric('NativeAuthentication',$username);
			if ($outcome) {
				$overallAuth = true;
				if ($authAttemp=='TleAuthenticate') {
					$user = User::RetrieveInternalUser($username,$password);
					trace("tle_id set to ".$user->id." by ".$authAttemp.'<br/>');	
				} else {
					trace("authattempt is ".$authAttemp.", outcome is ".$outcome.'<br/>');
					if (User::ExternalUserExists($authAttemp,$outcome)) {
						trace('User exists in TLE?: yes'.'<br/>');
						$user = User::RetrieveExternalUser($authAttemp,$outcome);
					//	print_r($user);
					} else {
						trace('User Exists in TLE?: no'.'<br/>');
						// if plugin allows, automatically create user
							$user = User::CreateAuthenticatedUser($authAttemp,$outcome);
							if ($user === null){
                //$metrics->recordMetric('AccoutNotCreated','Not allowed to create user automatically.',$authAttemp);                
              }
              else{
                //$metrics->recordMetric('AccoutCreated','User Account created',$authAttemp);
              }
					}
				}
				//trace("user object set to <pre>".print_r($_SESSION[USER],true)."</pre> by ".$authAttemp);
				break;	
			}
		}
	//	echo 'End auth: '.(integer)$overallAuth;
	   $visitor_ip = $_SERVER['REMOTE_ADDR'];
		if ($overallAuth) {
			//setup the session as we are authenticated
			//trace("Successfully Authenticated");
			//echo 'passed auth';
			//print_r($user);
			$_PLUGINS->trigger('onSuccessfulUserAuthentication',array($user->id));
			//echo 'recording metric';
      SetSessionUser($user);
			//$_SESSION[USER] =$user;// User::RetrieveUser($user->id); // get data from db, in case it has changed
			if (is_null($user)) {
				return false;			
//	die('User object is null');
			}
 			$_SESSION[USER]->isProjectStaff(null,true);//==true?"yes":"no";
 			$_SESSION[USER]->isProjectTemplateStaff(null,true);//==true?"yes":"no";
					
			$metrics->recordMetric('userLogin',date('r'),$user->id,$username,$visitor_ip);
		} else {
		  //echo 'failed auth';
			$metrics->recordMetric('loginFailed',date('r'),'-',$username,$visitor_ip);
			//print_r($result);
			//die('bad');
			return false;
		}
		return $user;
	}
	
	/**
	 * Destroys a users session. This function will automatically rebuild an
	 * un-authenticated user that the main script expects, so that we should never 
	 * have to actually go through the check code in Index.php.
	 */
	function Logout() {
		global $metrics, $_PLUGINS, $config, $_COOKIE, $_SESSION;
		$metrics->recordMetric('userLogout',$_SESSION[USER]->id);			
		$_PLUGINS->trigger('onUserLogout',array($_SESSION[USER]->id));
		$authMethod = $_SESSION[USER]->GetAuthMethod();
		
		/*
		 * Reset all of the Session variables. We can't just call session_destroy()
		 * as that doesn't unset variables.
		 */
		foreach ($_SESSION as $VarName => $Value)  {
		   unset ($_SESSION[$VarName]);
		}		 
		$session_name = session_name();
		session_destroy();
		$_SESSION[USER] = new User;
		Redirect($config['home']);
	}
	function GetSalt(){
		global $config;
		return md5($config['salt']);
	} 
	function HashPassword($sourcePassword,$salt = null){
		if (is_null($salt)){
			$salt = GetSalt();	
		} 
		$hashed_pwd = md5($salt.md5($sourcePassword));
		return $hashed_pwd;
	}
?>
