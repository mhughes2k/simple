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
 * Controller script for installation
 * 
 * This script doesn't use Smarty just in case it's broke!
 * @package Install
 */
 session_start();
 ini_set("display_errors", E_ALL);
 error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
 if (file_exists('../LocalSettings.php')) {
 	//include('alreadySetup.php');
 	//die(); 	
 }
 
 
 	/**
	* Tries to Retrieve a parameter from the $_GET supervariable first then, $_POST.
	* @param $Parameter the name of the parameter to retrieve
	* @param $DefaultValue a default value if the parameter cannot be found (defaults to NULL)
	* @return the value of the requested parameter or the default value if not found
	*/
	function GetParam($Parameter,$DefaultValue=null) {
		$val = "";
		if (isset($_GET[$Parameter]) ){
			$val = $_GET[$Parameter];
		}
		if ($val != "") {
			return $val;
		}

		if (isset($_POST[$Parameter]) ){
			$val = $_POST[$Parameter];
		}
		if ($val == "") {
			return $DefaultValue;
		}

		return $val;
	}
	
	/**
	* Redirects to a page
	*/
	function Redirect($url){
		Header("Location:$url");
	}
	
	$step = GetParam('step','');
	
	switch ($step) {
		case 'install':
			install();
			break;
		case '1':
			Step1();
			break;
		case '2':
			Step2();
			break;
		case '3':
			Step3();
			break;
		case '4':
		  Step4();  //post install
      break;
		default:
			Step0();
			break;	
	}
	
	function Step0() {
		//setup a blank array in the session for storing all the info we need
		$_SESSION['setupinfo'] =null;
		$_SESSION['setupinfo'] = array();
		
		include('step0.php');
	} 
	
	function Step1() {
		include('GetSiteInfo.php');
	}
	
	function Step2() {
		SetSiteInfo();
		if (!isset($_SESSION['setupinfo']['dbhost'])) {
			$_SESSION['setupinfo']['dbhost'] ='localhost';
		}
		if (!isset($_SESSION['setupinfo']['dbport'])) {
			$_SESSION['setupinfo']['dbport'] =3306;
		}
		if (!isset($_SESSION['setupinfo']['dbname'])) {
			$_SESSION['setupinfo']['dbname']='SIMPLE_DB';
		}
		if (!isset($_SESSION['setupinfo']['dbuser'])) {
			$_SESSION['setupinfo']['dbuser']='';
		}
		if (!isset($_SESSION['setupinfo']['dbpassword'])) {
			$_SESSION['setupinfo']['dbpassword']='';
		}

		include('GetDbInfo.php');
	}
	function Step3() {
		if (!DoConfigureDatabase()) {
			include('GetDbInfo.php');
			return;
		}
		include('preCommit.php');
	}
	function SetSiteInfo() {
		if (!isset($_SESSION['setupinfo']['sitename'])) {$_SESSION['setupinfo']['sitename']='';}
		if (!isset($_SESSION['setupinfo']['siteurl'])) {$_SESSION['setupinfo']['siteurl']='';}
		$_SESSION['setupinfo']['sitename'] = GetParaM('sitename',$_SESSION['setupinfo']['sitename']);
		$_SESSION['setupinfo']['siteurl'] = GetParaM('siteurl',$_SESSION['setupinfo']['siteurl']);
		if ($_SESSION['setupinfo']['siteurl'][strlen($_SESSION['setupinfo']['siteurl'])-1] != '/') {
      $_SESSION['setupinfo']['siteurl'].='/';
    }
	}
	
	/**
	 * Sets up the database as specified by the user.
	 */
	function DoConfigureDatabase()
	{
		$_SESSION['setupinfo']['dbname']= GetParam('dbname',$_SESSION['setupinfo']['dbname']);
		$_SESSION['setupinfo']['dbhost']= GetParam('dbhost',$_SESSION['setupinfo']['dbhost']);
		$_SESSION['setupinfo']['dbport']= GetParam('dbport',$_SESSION['setupinfo']['dbport']);
		$_SESSION['setupinfo']['dbuser']= GetParam('dbuser',$_SESSION['setupinfo']['dbuser']);
		$_SESSION['setupinfo']['dbpassword']= GetParam('dbpassword',$_SESSION['setupinfo']['dbpassword']);
		/*
		$_SESSION['setupinfo']['createddbuser']= GetParam('createddbuser',$_SESSION['setupinfo']['createddbuser']);
		$_SESSION['setupinfo']['createdbpassword']= GetParam('createdbpassword',$_SESSION['setupinfo']['createdbpassword']);
		*/
	
	/**
		$dsn_dbtype= $_SESSION['setupinfo']['dbtype'].'://';
		$dsn_credentials ='';
		if ($_SESSION['setupinfo']['dbuser']!='') {
			$dsn_credentials = $_SESSION['setupinfo']['dbuser'].':'.$_SESSION['setupinfo']['dbpassword'].'@';
		}
		
		$dsn_host = $_SESSION['setupinfo']['dbhost'];
		$dsn_db = $_SESSION['setupinfo']['dbname'];
		
		$dsn = $dsn_dbtype.$dsn_credentials.$dsn_host.'/'.$dsn_db;
		$_SESSION['dsn']=$dsn;
**/
		return true;
		
	}
  /**
   * Total number of DB steps!  
   */  
  $totalDbInstallSteps = 30;
	function message($msg) {
    echo "<li>$msg</li>";
  }
  function message2($msg){
    echo("<li>$msg");
  }
	function creating($msg) {
    echo ("<li>Creating $msg");
    ob_flush();
    flush();
  }

  function Step4() {
    include('SetupComplete.php');
  }	
	/**
	 * The Actual installation script.
	 */
	function install() 
	{
		include('install_header.php');
		echo '<ol>';
    define('doDbCreate',1);
		/*
		 * We need to add database creation stuff here once we've worked it out.
		 */
		 require_once('setupDatabase.php');
    
    if (file_exists('../LocalSettings.php')){
			message("Overwriting existing LocalSettings.php");//die('Please delete ../LocalSetting.php first');
		}
		if (!$configFile = fopen('../LocalSettings.php','w')) {
			die('unable to create ../LocalSettings.php');
		}
		message('Generating LocalSettings.php');
	 if ($_SESSION['setupinfo']['siteurl'][strlen($_SESSION['setupinfo']['siteurl'])-1] != '/') {
      $_SESSION['setupinfo']['siteurl'].='/';
      message('Adding trailing slash to siteurl');
    }
		
		
		  echo '</ol>';
		$uniqueSeed = rand(1000,9999999);
		$cDate = Date('r');
		$uk= $cDate . $uniqueSeed;
		$uk = md5($uk);
		$uniqueSessionCookie='simpleCookie_$cDate_$uk';
		fwrite($configFile,'<?php ');
		fwrite($configFile,"//See <helpurl>\r");
		fwrite($configFile,"\$config[PLATFORM_NAME] ='".$_SESSION['setupinfo']['sitename']."';\r");
		fwrite($configFile,"\$config[DATABASE_HOST] = '".$_SESSION['setupinfo']['dbhost']."';\r");
		fwrite($configFile,"\$config[DATABASE_PORT] = '".$_SESSION['setupinfo']['dbport']."';\r");
		fwrite($configFile,"\$config[DATABASE_NAME] = '".$_SESSION['setupinfo']['dbname']."';\r");
		fwrite($configFile,"\$config[DATABASE_USERNAME] = '".$_SESSION['setupinfo']['dbuser']."';\r");
		fwrite($configFile,"\$config[DATABASE_PASSWORD] = '".$_SESSION['setupinfo']['dbpassword']."';\r");		
		fwrite($configFile,"\$config['home'] = '".$_SESSION['setupinfo']['siteurl']."';\r");
		fwrite($configFile,"\$config['sessionName']='$uniqueSessionCookie';\r");
		fwrite($configFile,"\$config['recordMetrics'] = true;\r");
		fwrite($configFile,"\$config['salt'] ='I am a shaggy dog story';\r");
		fwrite($configFile,"\$config['radiusServer']=array('');\r");
		fwrite($configFile,"\$config['radiusSecret']='';\r");
		fwrite($configFile,"\$config['listPageSize']=10;\r");
		fwrite($configFile,"\$config['installDir']='';\r");
		fwrite($configFile,' ?>');
		fclose($configFile);
		 include('install_footer.php');
  }
?>
