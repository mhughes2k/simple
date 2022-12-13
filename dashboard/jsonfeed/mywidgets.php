<?php 
ini_set("display_errors", E_ALL);
error_reporting(E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);
define('TLE2',true,false);
require_once('../../include/Constants.php');
require_once('../../include/User.class.php');
require_once('../../include/UserGroup.class.php');
session_start();
require_once('../../include/Project.class.php');
require_once('../../include/ProjectTemplate.class.php');
require_once('../../include/Debug.php');
require_once('../../include/TLE2.php');
require_once('../../include/DefaultSettings.php');
if (file_exists('../../LocalSettings.php')) {
	include_once('../../LocalSettings.php');
}
require_once('../../include/Database.class.php');
// get string from user table in db
$query = sprintf("SELECT dashboard_json FROM users WHERE userid=%s", $_SESSION[USER]->id);
$results = $database->queryAssoc($query);
if ($results[0]['dashboard_json']!="") {
	print $results[0]['dashboard_json'];
} else {
	include 'default_widgets.json';
}
?>
