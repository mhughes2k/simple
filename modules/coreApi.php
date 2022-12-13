<?php

if (!defined('TLE2')) die ('Invalid Entry Point');
$_GET['username']= "super";
$_GET['password']="super";
//$_GET[''];TleAuthenticate
$_GET["authType"]="TleAuthenticate";
//print_r($_GET);
Authenticate();
//print_r(GetSessionUser());
//die();
$option = GetParam('api_option','');

switch (strtolower($option)) {
    case 'locationtracker':
    LocTracker();
    break;
  case 'tl':
    HandleTl();
    break;
  default:
    print(json_encode($option));
    break;
}

function HandleTl() {
  //print('handletl');
  $command = GetParam('cmd','');
  switch($command) {
    case 'inbox':
      getInbox();
      break;
    case 'list':
      listprojects();
      break;
  }
}

function getInbox() {

  $pid = GetParam('projectid',-1);
  //print($pid);
  $project = Project::GetProject($pid);

  $inbox= $project->GetDeliveryFolder();

  $items = Document::GetItemsByProject($pid);
  $counter = 0;
  foreach($items as $item) {
    if (!$item->isread){ 
      $counter +=1;
    }
  }
  print $counter;
  return;
  /*
  print json_encode($items);
  return;
  print "[";
  $outItems= array();
  foreach($items as $item) {
    $out="[";
    $out.="\"$item->documentuid\",\"$item->filename\",\"$item->isread\",\"\"";
    $out.="]";
    $outItems[]=$out;
  }
  print (join($outItems,','));
  print "]";
  */
  //print (json_encode($items));
 }

function listprojects() {
  //print('listprojects');
  $projects = Project::GetProjectsList();
  //print_r($projects);
  print(json_encode($projects));
}

function LocTracker() {
  $command = GetParam('cmd','');
  
  switch(strtolower($command)) {
    case 'log':
    default:
      logLocation();
      break;
  }
}
function logLocation() {
  global $metrics;
  $pos = $_SERVER['HTTP_X_SECONDLIFE_LOCAL_POSITION'];
  $region=$_SERVER['HTTP_X_SECONDLIFE_REGION'];
  //print_r($_SERVER);
  $date = date('r');
  $metrics->recordMetric('GeoPosition',$date,$region,$pos);
  print("$date : Logging location in $region at $pos");
}

?>