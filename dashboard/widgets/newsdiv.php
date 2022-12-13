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

//print "<pre>";
//print_r($_SESSION[USER]);
//print "</pre>";

$query = sprintf("SELECT * FROM site_news ORDER BY timestamp DESC");
$results = $database->queryAssoc($query);
foreach ($results as $r) {
?>
<strong><?php print $r['title']; ?> - <?php print date("jS M Y", $r['timestamp']); ?></strong><br/>
<?php print nl2br($r['text']); ?>
<br/><br/>
<?php
}
?>