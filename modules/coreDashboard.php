<?php 

if (!defined('TLE2')) die ('Invalid Entry Point');
  
$page = new Page('Dashboard.php.tpl');

switch (strtolower($command) ) {
	case 'revertdashboard':
		RevertDashboard();
		break;
	default:
		break;
}
function RevertDashboard() {
	global $database;
	// delete dashboard string from user profile, and refresh
	$query = sprintf("UPDATE users SET dashboard_json='' WHERE userid=%s", 
		$_SESSION[USER]->id);
	$result = $database->execute($query);
}

$page->assign('contents', 'Dashboard');
	
 ?>
