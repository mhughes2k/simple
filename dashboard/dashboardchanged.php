<?php
//$userId = $_REQUEST['userid'];
ini_set("display_errors", E_ALL);
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
define('TLE2',true,false);
require_once('../include/Constants.php');
require_once('../include/User.class.php');
require_once('../include/UserGroup.class.php');
session_start();
require_once('../include/Project.class.php');
require_once('../include/ProjectTemplate.class.php');
require_once('../include/Debug.php');
require_once('../include/TLE2.php');
require_once('../include/DefaultSettings.php');
if (file_exists('../LocalSettings.php')) {
	include_once('../LocalSettings.php');
}
require_once('../include/Database.class.php');

$query = sprintf("UPDATE users SET dashboard_json='%s' WHERE userid=%s", 
		$_POST['settings'], $_SESSION[USER]->id);
$result = $database->execute($query);
/**
$myFile = "testFile.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$teststring = $_POST['dashboard']."\n";
fwrite($fh,$teststring);
$teststring = $_POST['settings']."\n"; //write this to user table
fwrite($fh,$teststring);
fclose($fh);
**/
?>