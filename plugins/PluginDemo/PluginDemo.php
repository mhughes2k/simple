<?php

/*
 * Implementing a new Sidebar palette for the "Office" view
 
*/
function DemoSideBar() {
  global $config; //Allows us to access the config settings if we want
  $result = "";   //A variable to hold the HTML we want outputted.
  $result.=GenerateLeftSideBarBoxOpening("","Palette 1"); //This generates all of the UI bits normally associated with a sidebar palette
  /*
  GenerateLeftSideBarBoxOpening($BoxId,$BoxName, $StaffBox = false) {
  $BoxId = the ID attribute of the box
  $BoxName = The text displayed in the box's title area
  $StaffBox = Set to 'true' to highlight in red. Default is 'false'. NOTE this does not do any checking of the USE_STAFF_TOOLS permission. YOU NEED TO DO THIS!
  
  */
  
  $result.="<P>Palette Content goes here</p>";
  $result.=GenerateLeftSideBarBoxClosing();  
  return $result; //Return Sidebar HTML
}

function DemoSideBar2() {
  global $config; //Allows us to access the config settings if we want
  $result = "";   //A variable to hold the HTML we want outputted.
  $result.=GenerateLeftSideBarBoxOpening("","Palette 2",true);
  $result.="<P>Palette Content goes here</p>";
  $result.=GenerateLeftSideBarBoxClosing();  
  return $result; //Return Sidebar HTML
}
/*
$_PLUGINS->registerFunction(HOOK,PLUGIN_NAME,FUNCTIOn);
HOOK = the name of the hook you want your code to run on.
PLUGIN_NAME = Normally the same name as your plugin (normally onlyl used for logging)
FUNCTION = the name of the function execute.

You'll need to check the Plugin documentation to see exactly what parameters you should implement for what hooks.

You can also use the registerFunction to register a new variable instruction so you can also use #HOOK() in variables!

/* This registers the function that generates the UI for the sidebar palette
 * to run, inserting the HTML *AFTER* the last sidebar item
 */ 
$_PLUGINS->registerFunction('extendOfficeSidebarBefore','DemoSideBar','DemoSideBar');
/*
 * This time the side bar appears *BEFORE* the built in palettes.
*/
$_PLUGINS->registerFunction('extendOfficeSidebarAfter','DemoSideBar2','DemoSideBar2');

/*
Adding NEW Features

If you have a lot of new stuff you can implement a new "Verb". Built-in verbs include:
*office e.g. index.php?option=office
*projectTemplateAdmin e.g. index.php?option=projectTemplateAdmin
*siteAdmin e.g. index.php?option=siteAdmin

A new verb is registered using the $_PLUGINS->registerVerb(VERB,FUNCTION); function. 
VERB is the verb you want to use, and FUNCTION is the name of the function that will be executed.

Your verb is executed by going to a URL e.g.

http://yoursimpleServer.com/index.php?option=VERB 

*/
function DemoVerbHandler(){
  global $page,$project; //you need at least global $page to access the page component for outputting your UI
  //global $project allows you to access the global PROJECT object for the currently active project (if there is one)
  //die('demo');
  //You can generate the "office" UI using GenerateOfficeUi(PROJECT_ID); providing you have a project ID & an active project!
  $yourHTMLContent="";
  if (is_null($project)) {
    //we don't have an active project
    $yourHTMLContent .= '<div class="sectionbox">Your Stuff goes here!</div>We don\'t have an active project'; 
  }
  else {
    GenerateOfficeUi($project->id);
    $yourHTMLContent.="We <strong>have</strong> an active project.";
  }
  $yourHTMLContent .= "Your UI code goes here!";
  
  $page->assign('sectionTitle',"Verb Title");
  $page->assign('foldername',"Subtitle");
  $page->assign('content',$yourHTMLContent);
  
  return true;
}
//Finally call the plugin handler's registerVerb function to insert the verb in to SIMPLE's handling path.
$_PLUGINS->registerVerb('demoverb','DemoVerbHandler');

?>
