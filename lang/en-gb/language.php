<?php
/*
	 * Various bits of vendor contact information.
	 */	 
	//$strings['VENDOR'] = '<a href="mailto:simple@law.strath.ac.uk?subject=CAL Purchase">LTDU&nbsp;SIMPLE&nbsp;Team&nbsp;&lt;simple@law.strath.ac.uk</a>&gt;';
	//$strings['CAL_INFO'] ='http://technologies.law.strath.ac.uk/simple/index.php?option=com_content&task=view&id=10&Itemid=17';
	//$strings['SERVER_MODE_INFO']='http://technologies.law.strath.ac.uk/simple/index.php?option=com_content&task=view&id=11&Itemid=16';
	/*
	 * The next part are all the localisable strings!
	 */
    $strings = array();
	$strings['MSG_GREETING'] ='Hello';
	$strings['MSG_HOME'] ='Dashboard';
    $strings['MSG_NEWS'] ='News';
    $strings['MSG_CREDITS'] ='Credits';   
	$strings['MSG_SIM_HOME'] = 'Home';
	$strings['MSG_MAP'] ='Map';
	$strings['MSG_DIRECTORY'] ='Directory';
	$strings['MSG_PROJECTS'] = ucfirst($siteSettings['simulation_name_plural']);
	$strings['MSG_MANAGE_PROJECT'] = 'Manage '.ucfirst($siteSettings['simulation_name']);
	$strings['MSG_LOGIN'] ='Login';
	$strings['MSG_LOGOUT'] ='Logout';
	$strings['MSG_VIEW_YOUR_PROFILE'] = "View your profile";
	$strings['MSG_JUMP_BUTTON'] ='Go to...';
	$strings['MSG_MANAGE_SITE'] ='Site Admin';
	$strings['MSG_SITE_OFFLINE_ADMIN_MODE']='Offline Mode: Admin User';
	$strings['MSG_PAGE_BUILT_IN']='Page built in';
	$strings['MSG_PAGE_BUILT_BY']=' by';
	$strings['MSG_SIMPLE_VERSION']='SIMPLE Version';

	/*
	 * Common terms 
	 */
  $strings['MSG_NO_PROJECT_SELECTED'] = 'No Project Selected';

	$strings['TIME_SECONDS']='seconds';
	$strings['MSG_CREATE_TERM']='Create';
	$strings['MSG_BP_TERM'] = 'Blueprint';
	$strings['MSG_PROJECT_TERM'] = 'Simulation';
	$strings['MSG_SCENARIO_TERM'] = 'Project';
	$strings['MSG_DELETE']='Delete';
	$strings['MSG_UNDELETE']='Undelete';
	$strings['MSG_DISMISS']='Dismiss';
	$strings['MSG_REMOVE']='Remove';
	$strings['MSG_INSTALL_TERM']='Install';
	$strings['MSG_NO_ITEMS_FOUND']='Folder is empty';

  $strings['MSG_MOVE']='Move';
  $strings['MSG_COPY']='Copy';
	$strings['MSG_ASCENDING']='ASC';
	$strings['MSG_DESCENDING']='DESC';

	$strings['MSG_DOWNLOAD']='Download';
	
	$strings['MSG_GROUP'] = ucwords($siteSettings['group_name']);
	$strings['MSG_GROUPS'] = ucwords($siteSettings['group_name_plural']);
	$strings['MSG_IMPORT_GROUPS'] = "Import ".ucwords($siteSettings['group_name_plural']);
	$strings['MSG_DELETE_GROUPS'] = "Delete ".ucwords($siteSettings['group_name_plural']);
			
	/* Confirmation Labels */
	$strings['MSG_CONFIRM_YN']='Are you sure?';
	$strings['MSG_CONFIRM_N']='No';
	$strings['MSG_CONFIRM_Y']='Yes';
	


	$strings['MSG_SIMULATIONS']=ucwords($siteSettings['simulation_name_plural']);
	$strings['MSG_SIMULATION_GROUPS']=ucwords($siteSettings['simulation_name']).' Groups';

	$strings['MSG_SIMULATIONS_ADMIN_RIGHTS']='';
	$strings['MSG_VIEW_SIMULATION_NUMBER'	]='View simulation by ID';

	$strings['MSG_SIMULATIONS_ID']='ID';
$strings['MSG_SIMULATION_NAME']=ucwords($siteSettings['simulation_name']).' Name';
$strings['MSG_BASED_ON_BLUEPRINT']='Based on Blueprint';
	$strings['MSG_CREATOR_TERM']='Creator';

$strings['MSG_VIEW']='View';
	
	/* Alerts Labels */
	$strings['MSG_DISMISS_DIALOG_TITLE']='Dismiss Alert';
	$strings['MSG_NO_ALERTS']='No Alerts';
	/* Calendar/Task Labels*/
	$strings['MSG_VIEW_CALENDAR'] = 'View';
	$strings['MSG_NEW_EVENT'] = 'New Event...';
	$strings['MSG_NEW_TASK'] = 'New Task...';
	
	$strings['MSG_PROJECT_INSTALL_TITLE']='Install New Project';
	$strings['MSG_PROJECT_INSTALL_PROMPT']='Please select the Project file you wish to install. '.
											'Please be patient, this may take several minutes.';
	$strings['MSG_PROJECT_INSTALL_NOZIP']='You do not have the php_zip extension installed! You must install it in order to
 install projects.';											
	$strings['MSG_SIMS_BASED_HEADER'] = '# Sims based on BP';
	$strings['MSG_PROJECT_REMOVE_TITLE']='Remove Project';
	$strings['MSG_PROJECT_REMOVE_PROMPT']='This will remove all BPs belonging to the Project (id) specified';
	$strings['MSG_SIMS_BASED_HEADER'] = '# Sims based on BP';
	$strings['MSG_PLUGIN_INSTALL_NOZIP']='You do not have the php_zip extension installed! You must install it in order to add new plugins.';
	/*
	 * Admin toolbar links!
	 */
	
	$strings['MSG_MANAGE_PT'] ='Manage Blueprints';
	$strings['MSG_MANAGE_PT_TOOLTIP'] ='Manage Blueprints';
	$strings['MSG_MANAGE_P'] = 'Manage '.ucwords($siteSettings['simulation_name_plural']);
	$strings['MSG_MANAGE_P_TOOLTIP'] ='Manage '.ucwords($siteSettings['simulation_name_plural']);
	$strings['MSG_MANAGE_PLUGINS'] ='Plugins';
	$strings['MSG_MANAGE_PLUGINS_TOOLTIP'] ='Plugins';
	$strings['MSG_MANAGE_USERS'] ='Users & '.ucwords($siteSettings['group_name_plural']);
	$strings['MSG_MANAGE_USERS_TOOLTIP'] ='Users & '.ucwords($siteSettings['group_name_plural']);
	//$strings['MSG_MANAGE_USERGROUPS'] ='User Groups';
	//$strings['MSG_MANAGE_USERGROUPS_TOOLTIP'] ='User Groups';
	
	$strings['MSG_ALERTS']='Alerts';
	$strings['MSG_FOLDERS']='Documents';
	$strings['MSG_CORRESPONDENCE']='Correspondence';
	$strings['MSG_CALENDAR']='Calendar';
	$strings['MSG_TASKS']='Tasks';
	$strings['MSG_ICONS']='Icons';
	$strings['MSG_RESOURCES'] = 'Resources';
	$strings['MSG_MEMBERS'] = ucwords($siteSettings['simulation_name']).' Members';
	
	
	/* Staff Resources area strings*/
	$strings['MSG_SELECT_ONE']='-Select one-';
	$strings['MSG_SEND_DOCUMENTS_LIST']='Select a resource.';
	$strings['MSG_SEND_DOCUMENT'] = 'Send Resource';
	$strings['MSG_SEND_DOCUMENT_ALT'] = 'Send an existing resource unaltered.';
	$strings['MSG_SEND_CUSTOM_DOCUMENT'] = 'Send Custom Resource';
	$strings['MSG_SEND_CUSTOM_DOCUMENT_ALT'] = 'Download existing resource to alter.';
	$strings['MSG_UPLOAD_FILE'] = 'Add Resource';
	$strings['MSG_COMPOSE_EMAIL'] = 'Compose Email';
	$strings['MSG_SEND_EMAIL'] = "Send Email";
	$strings['MSG_UPLOAD_FILE_ALT'] = 'Upload a resource from your computer to send.';
	$strings['MSG_SEND_AS_LABEL'] = 'Send As:';
	$strings['MSG_CUSTOM_SENDER_ALT']='Select a Sender.';
	$strings['MSG_CUSTOM_SENDER_OPTION']= 'Custom Sender - **Enter Details Below**';
	$strings['MSG_CUSTOM_SENDER_LABEL'] = 'Custom Sender:';
	$strings['MSG_CUSTOM_SENDER_HELP'] = 'Either just enter a <code>name</code> or use <code>Name -contactaddress-</code>';
	$strings['MSG_SEND_BUTTON'] = 'Send';
	
	/* NED Labels */
	$strings['MSG_STAFF_ACTIVITIES'] ='Staff Activities';
	$strings['MSG_NPC_ACTIVITIES']='Non-Player Character';
	$strings['MSG_PLAYER_ACTIVITIES']='Player Character';
	$strings['MSG_CRITICAL_EVENTS']='Critical Events';
	$strings['MSG_TRIGGER_LABEL']= 'Trigger ';
	$strings['MSG_RETRIGGER_LABEL']= 'Re-Trigger ';
	$strings['MSG_NED_TAG_LABEL']= 'Tag ';
	$strings['MSG_CHANGE_TAG_LABEL']= 'Change ';
	$strings['MSG_NO_PROJECT_STRUCTURE_FOUND']='Project structure not found!';
	
	/* Office/File related */
	$strings['MSG_OFFICE_WORKSPACE'] = ucwords($siteSettings['simulation_name'])." Workspace: ";
	//$strings['MSG_OFFICE_WORKSPACE_TITLE']='Workspace for {CHAR_PLAYER}';
	
	$strings['MSG_CREATE_MESSAGE'] = 'Create New Message';
	$strings['MSG_ADD_SIMPLE_FILE'] = 'Add HTML File';
	
	$strings['MSG_DOCUMENTS_LABEL']='Current Folder';
	
  $strings['MSG_DOCUMENT_TABLE_ALT']='Documents';
	$strings['MSG_VIEW_DOCUMENT_PROPERTIES']='Document Properties';
	$strings['MSG_USE_TEMPLATE_BUTTON_TEXT'] = 'Use Template';
	$strings['MSG_STAFF_TOOLS_TEXT'] = 'Staff Tools';
	$strings['MSG_STAFF_RESOURCES_TEXT'] = 'Staff Resources';
	$strings['MSG_STAFF_EVENTS_TEXT'] = 'Events';
	
	$strings['MSG_MANAGE_FOLDERS'] = 'Manage Folders';
	
	$strings['MSG_NO_PROJECT_SELECTED'] = 'No Project Selected';
	$strings['MSG_UNABLE_TO_ADD_USER']='Unable to Add User';
	$strings['MSG_NO_USER_FOUND']='No User Found';
	$strings['MSG_NO_USERS_FOUND']='No Users Found';
	$strings['MSG_NO_USERSIMULATIONS_FOUND']='No User '.ucwords($siteSettings['simulation_name_plural']).' Found';
	$strings['MSG_NO_USERBLUEPRINTS_FOUND']='No User Blueprints Found';
	$strings['MSG_NO_USERGROUPSIMULATIONS_FOUND']='No User Group '.ucwords($siteSettings['simulation_name_plural']).' Found';
	$strings['MSG_NO_USERGROUPBLUEPRINTS_FOUND']='No '.ucwords($siteSettings['group_name']).' Blueprints Found';		
	$strings['MSG_NO_USERGROUPPROJECTS_FOUND']='No '.ucwords($siteSettings['group_name']).' Projects Found';	
	$strings['MSG_NO_PLUGINS_FOUND']='No Plugins Found';	
	$strings['MSG_UPDATE_PROFILE']='Update your profile.';
	$strings['MSG_MISSING_SEARCH_TERM']='You must provide a search term.';
	
	$strings['MSG_UNABLE_TO_ADD_USERGROUP']='Unable to Add '.ucwords($siteSettings['group_name']);
	
	$strings['MSG_SUBJECT_HEADER']='Subject';
	$strings['MSG_FROM_HEADER']='From';
	$strings['MSG_RECIPIENT_HEADER']='To';
	$strings['MSG_RECIEVE_SENT_MOD_HEADER']='Date';
	
	$strings['MSG_ASCENDING']='ASC';
	$strings['MSG_DESCENDING']='DESC';
	
	$strings['MSG_SEARCH_MESSAGE_1']='Find';
	$strings['MSG_SEARCH_MESSAGE_2']='in';
	$strings['MSG_SEARCH_MESSAGE_3']='You can use % to match incomplete terms, e.g. "jo%" to find Joe, John, Jonathan, etc. Searches are case-insensitive.';
	
	$strings['MSG_NEXT'] = 'Next';
	$strings['MSG_PREVIOUS'] = 'Previous';
	
	$strings['MSG_CANCEL']='Cancel';
	
	$strings['MSG_COPY_DIALOG_TITLE']='Copy File';
	$strings['MSG_COPY_DIALOG_MESSAGE']='Please select a destination folder.';
	$strings['MSG_COPY_DIALOG_BUTTON']='Copy';
	
	$strings['MSG_MOVE_DIALOG_TITLE']='Move File';
	$strings['MSG_MOVE_DIALOG_MESSAGE']='Please select a destination folder.';
	$strings['MSG_MOVE_DIALOG_BUTTON']='Move';
	
	$strings['MSG_SEND_DIALOG_TITLE']='Send File';
	$strings['MSG_SEND_DIALOG_SELECT_MESSAGE']='Please select a recipient';
	
	$strings['MSG_NAME_HEADER']='Name';
	$strings['MSG_EADDRESS_HEADER']='E-Address';
	$strings['MSG_ADDRESS_HEADER']='Address';
	$strings['MSG_DIRECTORY_EMPTY']='There is no one in the Directory!';
	
	$strings['MSG_DELETE_DIALOG_TITLE']='Delete File';
	$strings['MSG_UNDELETE_DIALOG_TITLE']='Undelete File';
	$strings['MSG_REMOVE_DIALOG_TITLE']='Remove';
	
	/* Manage users text */
  $strings['MSG_UNABLE_TO_ADD_USER']='Unable to Add User';
	$strings['MSG_NO_USER_FOUND']='No User Found';
	$strings['MSG_NO_USERS_FOUND']='No Users Found';
	$strings['MSG_NO_USERSIMULATIONS_FOUND']='No User Simulations Found';
	$strings['MSG_NO_USERBLUEPRINTS_FOUND']='No User Blueprints Found';
	$strings['MSG_NO_USERGROUPSIMULATIONS_FOUND']='No User Group Simulations Found';
	$strings['MSG_NO_USERGROUPBLUEPRINTS_FOUND']='No User Group Blueprints Found';		
	$strings['MSG_NO_USERGROUPPROJECTS_FOUND']='No User Group Projects Found';	
	$strings['MSG_NO_PLUGINS_FOUND']='No Plugins Found';	
	$strings['MSG_UPDATE_PROFILE']='Update your profile.';
	$strings['MSG_MISSING_SEARCH_TERM']='You must provide a search term.';
	
	$strings['MSG_UNABLE_TO_ADD_USERGROUP']='Unable to Add User Group';

	
	/* Project Administration text */
		
	$strings['MSG_CREATE_PROJECT'] = 'Create A Simulation';
	$strings['MSG_CREATE_PROJECT_TOOLTIP'] = 'Click to create a new simulation';
	//string to use for the "BLUEPRINT"
	
	$strings['MSG_PROJECT_INSTALL_TITLE']='Install New Project';
	$strings['MSG_PROJECT_INSTALL_PROMPT']='Please select the Project file you wish to install. '.
											'Please be patient, this may take several minutes.';
	$strings['MSG_PROJECT_INSTALL_NOZIP']='You do not have the php_zip extension installed! You must install it in order to
 install projects.';											
	$strings['MSG_SIMS_BASED_HEADER'] = '# Sims based on BP';
	$strings['MSG_PROJECT_REMOVE_TITLE']='Remove Project';
	$strings['MSG_PROJECT_REMOVE_PROMPT']='This will remove all BPs belonging to the Project (id) specified';
	$strings['MSG_SIMS_BASED_HEADER'] = '# Sims based on BP';
	$strings['MSG_PLUGIN_INSTALL_NOZIP']='You do not have the php_zip extension installed! You must install it in order to add
	new plugins.';
	
		

	
	$strings['MSG_CONFIRM_YN']='Are you sure?';
	$strings['MSG_CONFIRM_N']='No';
	$strings['MSG_CONFIRM_Y']='Yes';
	
	$strings['MSG_DOWNLOAD']='Download';
	
	$strings['MSG_VIEW_CALENDAR'] = 'View';
	$strings['MSG_NEW_EVENT'] = 'New Event...';
	$strings['MSG_NEW_TASK'] = 'New Task...';
	
	/* Permissions related */
	$strings['MSG_GROUP_PERMISSIONS_TOGGLE_ERROR']= 'Cannot toggle group permissions yet.';
	$strings['MSG_INSUFFICIENT_PRIVELEDGES']='You do not have sufficient priveledges do this.';
	$strings['MSG_GROUP_PERMISSIONS_TOGGLE_MISSING_PARAMS_ERROR']='No Project, Permission, User or type supplied.';
	
?>
