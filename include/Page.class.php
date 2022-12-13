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

/**
 * @author Michael Hughes
 * @package SIMPLE
 * @subpackage Objects
 */
 
 /**
 * @package SIMPLE
 * @subpackage Objects
 */
 class Page extends Smarty {
 	/**
	* @var string Template file name.
	*/
 	var $Template = "";
	/**
	* Constructs a new page object with a template specified.
	* @param string $Template Template File Name.
	*/
 	function Page($Template = null) {
 		global $config;	
		$this->Template = $Template;
		//echo TLE2_dir ."templates";
		$this->template_dir = $config[INSTALL_DIRECTORY] ."templates";
		$this->compile_dir = $config[INSTALL_DIRECTORY] ."templates_c";
		$this->config_dir = $config[INSTALL_DIRECTORY] ."config";
		$this->cache_dir = $config[INSTALL_DIRECTORY] ."cache";		
	}
	/**
	* @var string Title to display in the home page bar.
	*/
	var $Title="";
	/**
	 * Allows us to change the template the page uses to render its content.
	 */   	
	function setTemplate($template) {
		$this->Template = $template;
	}
	/**
	 * @var array scripts Array of javascript script blocks to be inserted in the "chrome".
	 */    	
	var $scripts = array();
	
	/**
	 * @var array JQueryScripts Array of Jquery script blocks to register at the top of the page.
	 */   	
	var $JQueryScripts = array();
	
	var $Messages = array();
	
	/** 
	 * @var string WcagNavigation HTML to render right at the top of the BODY for use by screen readers to bypasss navigation items or to skip to sections.
	 */   	
	var $WcagNavigation='';
 }

?>