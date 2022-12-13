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

	$config['THEME'] = "metallic";
	/*
	 * This section are all the icon's that can be changed.
	 */	
	$config['defaultDocumentIcon'] = "themes/".$config['THEME']."/images/default_file.png";
	$config['defaultFolderIcon'] = "themes/".$config['THEME']."/images/default_folder.png";
	//$config['trashEmptyIcon'] = "images/bin_empty.png";
	//$config['trashFullIcon'] = "images/bin_full.png";
	
	$config['dateformat'] ='Y-M-d';
	$config['datetimeformat'] ='Y-M-d H:i';
	$config['timeformat'] ='H:i';
	/**
	 * The format to use for datetimes in the database.
	 */
	$config['dbdatetimeformat'] ='Y-m-d H:i:s';
	
	$config['iconpath']=$config['home'].'themes/'.$config['THEME'].'/icons/';
	
	$config['alert_icon_snooze']=$config['iconpath'].'clock_pause.png';
	$config['alert_icon_dismiss']=$config['iconpath'].'clock_delete.png';
	
	$config['calendar_icon'] = $config['iconpath'].'calendar_link.png';
	$config['calendar_icon_add'] = $config['iconpath'].'calendar_add.png';
	$config['calendar_icon_delete'] = $config['iconpath'].'calendar_delete.png';
	$config['calendar_icon_edit'] = $config['iconpath'].'calendar_edit.png';
	
	$config['task_icon_small'] =$config['iconpath'].'tag_blue.png';
	$config['task_icon_add'] =$config['iconpath'].'tag_blue_add.png';
	$config['task_icon_delete'] =$config['iconpath'].'tag_blue_delete.png';
	$config['task_icon_edit'] =$config['iconpath'].'tag_blue_edit.png';
	
	$config['system_icon_close'] = $config['iconpath'].'cross.png';
	$config['system_icon_userprofile'] = $config['iconpath'].'user.png';
	$config['system_icon_edit'] = $config['iconpath'].'pencil.png';
	$config['system_icon_delete'] = $config['iconpath'].'delete.png';
	$config['system_icon_enabled'] = $config['iconpath'].'flag_green.png';
	$config['system_icon_disabled'] = $config['iconpath'].'flag_red.png';	
	$config['system_icon_descending'] = $config['iconpath'].'bullet_arrow_up.gif';
	$config['system_icon_ascending'] = $config['iconpath'].'bullet_arrow_down.gif';
	$config['system_icon_right'] = $config['iconpath'].'resultset_next.png';
	$config['system_icon_left'] = $config['iconpath'].'resultset_previous.png';
	
	
	$config['system_icon_exportcsv'] = $config['iconpath'].'page_go.png';
	
	$config['permissions_icon_allow'] = $config['iconpath'].'flag_green.png';
	$config['permissions_icon_deny'] = $config['iconpath'].'flag_red.png';
	$config['permissions_icon_notset'] = $config['iconpath'].'flag_yellow.png';
	
	$config['map_icon'] = $config['iconpath'].'map.png';
	$config['directory_icon'] = $config['iconpath'].'book_addresses.png';
	
	$config['url_resource_icon'] = $config['iconpath'].'world_link.png';
	$config['doc_resource_icon'] = $config['iconpath'].'book.png';
	
	$config['user_icon'] = $config['iconpath'].'user.png';
	$config['user_icon_add'] = $config['iconpath'].'user_add.png';
	$config['user_icon_edit'] = $config['iconpath'].'user_edit.png';
	$config['user_icon_delete'] = $config['iconpath'].'user_delete.png';
	$config['user_icon_assign'] = $config['iconpath'].'user_go.png';
	
	$config['user_icon_external'] = $config['iconpath'].'link.png';
	
	$config['office_folder_icon']  =$config['iconpath'].'folder.gif';
	$config['office_folder_icon_open']  =$config['iconpath'].'folder_page_white.png';
	
	$config['office_flag_set']  =$config['iconpath'].'file_yellow.png';
	$config['office_document_edit']  =$config['iconpath'].'application_form_edit.png';
	$config['office_document_icon']  =$config['iconpath'].'file_grey.png';
	$config['office_send']  =$config['iconpath'].'email_go.png';
	$config['office_copy']  =$config['iconpath'].'copy.png';
	$config['office_move']  =$config['iconpath'].'book_next.png';
	$config['office_delete'] = $config['iconpath'].'cross.png';
	$config['office_undelete'] = $config['iconpath'].'arrow_rotate_clockwise.png';
	$config['office_download']  =$config['iconpath'].'book_go.png';
	$config['office_print']  =$config['iconpath'].'printer.png';
	
	$config['directory_link']  =$config['iconpath'].'world_link.png';
	
	$config['table_icon_arrow'] = $config['iconpath'].'arrow_ltr.png';

	$config['plugin_icon'] = $config['iconpath'].'plugin.png';
	$config['plugin_icon_add'] = $config['iconpath'].'plugin_add.png';
	$config['plugin_icon_edit'] = $config['iconpath'].'plugin_edit.png';
	$config['plugin_icon_delete'] = $config['iconpath'].'plugin_delete.png';
	$config['plugin_icon_up'] = $config['iconpath'].'arrow_up.png';
	$config['plugin_icon_down'] = $config['iconpath'].'arrow_down.png';
	$config['plugin_icon_revert'] = $config['iconpath'].'arrow_undo.png';

	$config['group_icon'] = $config['iconpath'].'group.png';
	$config['group_icon_add'] = $config['iconpath'].'group_add.png';
	$config['group_icon_edit'] = $config['iconpath'].'group_edit.png';
	$config['group_icon_delete'] = $config['iconpath'].'group_delete.png';	
	$config['group_icon_members'] = $config['iconpath'].'group_link.png';

	$config['ned_icon_trigger']= $config['iconpath'].'email_go.png';
	$config['newwindow_icon'] =$config['iconpath'].'application_form_add.png';
?>