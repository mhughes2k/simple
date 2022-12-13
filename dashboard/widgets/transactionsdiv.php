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
require_once('../../include/Folder.class.php');
require_once('../../include/Debug.php');
require_once('../../include/TLE2.php');
require_once('../../include/DefaultSettings.php');
if (file_exists('../../LocalSettings.php')) {
	include_once('../../LocalSettings.php');
}
require_once('../../include/Database.class.php');

if ($_SESSION[USER]->superadmin==ALLOW) {
 	$projects = Project::GetProjectsList(); // all projects
} else {
 	$projects = $_SESSION[USER]->GetProjects();
}

foreach ($projects as $key=>$title) {
	// get sum of new files
	$newFiles = 0;
	$folders = Folder::getFolders($key);
	foreach ($folders as $f) {
		if ($f->newitems > 0) {
			$newFiles+=$f->newitems;
		}
	}
?>
<?php if ($newFiles>0) { ?><strong><?php } ?>
<a href="index.php?option=tl&cmd=select&projectid=<?php print $key; ?>&redirect=index.php%3Foption%3Doffice"><?php print $title; ?><?php if ($newFiles>0) print " (".$newFiles." new)"; ?></a>
<?php if ($newFiles>0) { ?></strong><?php } ?>
<br/>
<?php
}
?>
<br/>