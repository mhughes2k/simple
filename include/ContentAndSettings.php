<?php

$siteSettings= array();

$query = "SELECT * FROM site_settings WHERE id=1";
$result = $database->queryAssoc($query);
$siteSettings['loginpagetext'] = nl2br($result[0]['login_page_content']);
$siteSettings['language'] = ($result[0]['language']!="")?$result[0]['language']:"en-gb";
$siteSettings['theme'] = ($result[0]['theme']!="")?$result[0]['theme']:"default";
$siteSettings['group_name'] = $result[0]['group_name'];
$siteSettings['group_name_plural'] = $result[0]['group_name_plural'];	
$siteSettings['simulation_name'] = $result[0]['simulation_name'];
$siteSettings['simulation_name_plural'] = $result[0]['simulation_name_plural'];
$siteSettings['help_url'] = (trim($result[0]['val_help_url'])!="")?$result[0]['val_help_url']: "help/student_guide.doc"; 
$siteSettings['emailmodule_mentors'] = $result[0]['emailmodule_mentors'];
$siteSettings['emailmodule_learners'] = $result[0]['emailmodule_learners'];


// need to tidy these up so just using $siteSettings array
$config['DEFAULT_LANGUAGE'] = $siteSettings['language'];
$lang = $siteSettings['language'];
$theme = $siteSettings['theme'];
$config['THEME'] = $theme;

//news feed


?>