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
 * @package SIMPLE
 * @subpackage Interfaces
*
*/

interface iPlugin {

	public function EnablePlugin();
	public function DisablePlugin();
	public function GetNextPlugin();
	public function GetPrevPlugin();
	public function SetOrder($order);
	public static function ReorderPlugins($plugin1,$plugin2);
	public static function AddPlugin();
	public static function UpdatePlugin($pluginId, $enabled, $sitewide);
	public static function RemovePlugin($pluginName);
	public static function ListPlugins();
	public static function PluginInstalled($pluginName);
	public static function GetProjectPlugins($projectId);
	public static function GetProjectPlugin($pluginId, $projectId);
	//public static function EnableProjectPlugin($pluginId, $projectId);
	//public static function DisableProjectPlugin($pluginId, $projectId);
}

interface IPluginInstaller{ 
  //var $errors;
  //var $msgs;
  public function GetConfig();
  public function SetConfig($newConfigOptions);
  /**
   * Attempts to install the Plugin.
   * 
   * You can check the $errors and $msgs variables for further information.
   * @return YES if the plugin is installed. NO if there are errors
   * 
   */
  public function Install();
  public function Uninstall();
}
/**
 * 
 */
class Plugin implements iPlugin {

	/**
	* The unique name of the plugin
	* @var string
	*/
	public $pluginName = "";

	/**
	* The plugin file
	* @var string
	*/
	public $pluginFile = "";

	/**
	* The place of the plugin in the order of execution
	* @var int
	*/
	public $order = -1;

	/**
	* Whether or not the plugin is set to active
	* @var int
	*/
	public $enabled = -1;

	/**
	* Whether the plugin is a sitewide-only plugin, e.g. authentication plugins
	* @var sitewide 
	*/
	public $sitewide = -1;

	public function __construct($pluginName=NULL,$pluginFile=NULL,$order=NULL,$enabled=NULL,$sitewide=NULL) {
		$this->pluginName = $pluginName;
		$this->pluginFile = $pluginFile;
		$this->order = $order;
		$this->enabled = $enabled;
		$this->sitewide = $sitewide;
	}

	/**
	* Sets an installed plugin to active
	*/
	public function EnablePlugin() {
		global $database;
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 		
		$this->enabled = 1;
		$query = sprintf("UPDATE plugins SET enabled=1 WHERE pluginName='%s'",$this->pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* Sets an installed plugin to inactive
	*/
	public function DisablePlugin() {
		global $database;		
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$this->enabled = 0;
		$query = sprintf("UPDATE plugins SET enabled=0 WHERE pluginName='%s'",$this->pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* Gets the next plugin in the current order
	* @return Plugin the next plugin (or false if doesnt exist)
	*/
	public function GetNextPlugin() {
		global $database;		
		$query = sprintf("SELECT * FROM plugins WHERE `pluginorder` > %s AND deleted=0 ORDER BY `pluginorder` LIMIT 1",$this->order);
		$result = $database->queryAssoc($query);
		if (count($result) < 1 ) {
			return false;
		}
		$plugin = new Plugin($result[0]['pluginname'],$result[0]['pluginfile'],$result[0]['pluginorder'],$result[0]['enabled'],$result[0]['sitewide']);
		//trace("<pre>".print_r($result,true)."</pre>");
		return $plugin;
	}

	/**
	* Gets the previous plugin in the current order
	* @return Plugin the previous plugin (or false if doesnt exist)
	*/
	public function GetPrevPlugin() {
		global $database;
		$query = sprintf("SELECT * FROM plugins WHERE `pluginorder` < %s AND deleted=0 ORDER BY `pluginorder` DESC LIMIT 1",$this->order);
		$result = $database->queryAssoc($query);
		if (count($result) < 1 ) {
			return false;
		}
		$plugin = new Plugin($result[0]['pluginname'],$result[0]['pluginfile'],$result[0]['pluginorder'],$result[0]['enabled'],$result[0]['sitewide']);
		return $plugin;
	}


	/**
	* Sets the order number of a plugin
	* @param int $order the new order number
	*/
	public function SetOrder($order) {
		global $database;
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$this->order = $order;
		$query = sprintf("UPDATE plugins SET `pluginorder`=%s WHERE pluginName='%s'",$order,$this->pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* Swap the order numbers of two consecutive plugins
	* param Plugin $plugin1 first plugin object
	* param Plugin $plugin2 second plugin object
	*/
	public static function ReorderPlugins($plugin1, $plugin2) {
		//trace("<pre>".print_r($plugin1,true).print_r($plugin2,true)."</pre>");
		global $database, $_PLUGINS;
		$query = sprintf("UPDATE plugins SET `pluginorder`=%s WHERE pluginName='%s'",$plugin1->order,$plugin2->pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$query = sprintf("UPDATE plugins SET `pluginorder`=%s WHERE pluginName='%s'",$plugin2->order,$plugin1->pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$po1 = $plugin1->order;
		$plugin1->SetOrder($plugin2->order);
	//	$plugin2->SetOrder($po1);
	}

	/**
	* Installs a new plugin.
	* This function requires that the php_zip extension be installed.
	* @return boolean false if the plugin is already installed, true otherwise
	*/
	public static function AddPlugin() {
		global $database, $config;
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$pluginName = substr($_FILES['pluginZip']['name'], 0, -4); // strip .zip extension to get plugin name
		//trace("plugin name is ".$pluginName);
		if(Plugin::PluginInstalled($pluginName)) {
			trace("plugin already installed - send feedback");
			return false;
		}
		// unzip uploaded zip file and copy to plugins dir
		$zip = zip_open($_FILES['pluginZip']['tmp_name']);
		$dir = $config[PLUGIN_DIRECTORY];
	   	//trace("dir is ".$dir);
	   	while($zip_entry = zip_read($zip)) {
	       $entry = zip_entry_open($zip,$zip_entry);
	       $filename = zip_entry_name($zip_entry);
       	   $target_dir = $dir.substr($filename,0,strrpos($filename,'/'));
	       $filesize = zip_entry_filesize($zip_entry);
       	   if (is_dir($target_dir) || mkdir($target_dir)) {
           		if ($filesize > 0) {
               		$contents = zip_entry_read($zip_entry, $filesize);
		            file_put_contents($dir.$filename,$contents);
	           	}
       		}
   		}

		$query = sprintf("SELECT MAX(`pluginorder`) AS max_order FROM plugins WHERE deleted=0");
		$result = $database->queryAssoc($query);
		$order = $result[0]['max_order']+1; // new plugins default to last place
		$pluginFile = $pluginName.".php";
		// find whether plugin iexists in deleted form
		$query = sprintf("SELECT count(*) as pluginexists FROM plugins WHERE pluginname='%s' AND deleted=1",$pluginName);
		$results = $database->queryAssoc($query);
		if ($results[0]['pluginexists']>0) {
			$query = sprintf("UPDATE plugins SET deleted=0 WHERE pluginname='%s'",$pluginName);
		} else {
			$query = sprintf("INSERT INTO plugins (pluginname, pluginfile, `pluginorder`, enabled, sitewide) VALUES ('%s','%s',%s,%s,1)",$pluginName,$pluginFile,$order,0);
		}
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		require_once("$home/plugins/$pluginName/$pluginFile");
		$installerFile = $pluginName.'_PluginInstaller';
		$installer = new $installerFile();
		//we should really display the config and let the user "commit" the config.
		$result = $installer->Install();
		
		return $result;

	}

	/** 
	 * updates a plugin record
	 */
	public static function UpdatePlugin($pluginName, $enabled, $sitewide){
		global $database;
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$query = sprintf("UPDATE plugins SET enabled=%s, sitewide=%s WHERE pluginName='%s'",
		$enabled, $sitewide, $pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}
	
	/**
	* check to see if there is a plugin by this name already installed
	* @param string $pluginName the unique plugin name to check for
	* @return boolean whether or not the plugin is already installed
	*/
	public static function PluginInstalled($pluginName) {
		global $database;
		$query = sprintf("SELECT * FROM plugins WHERE pluginName='%s' AND deleted=0",$pluginName);
		$result = $database->queryAssoc($query);
		if (count($result)>0) {
			return true;
		}
		return false;
	}

	/**
	* Deletes the plugin folder and files, and removes the entry from the database.
	* @param string $pluginName the unique name of the plugin to be deleted
	*/
	public static function RemovePlugin($pluginName) {
		//trace("removeplugin called with ".$pluginName);
		global $database, $config;
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$plugin = Plugin::GetPlugin($pluginName);
		if (!$config[DEBUG]) {
			//trace($config['pluginsDir'].$plugin->pluginName."/".$plugin->pluginFile);
			fclose($config['pluginsDir'].$plugin->pluginName."/".$plugin->pluginFile);
			unlink($config['pluginsDir'].$plugin->pluginName."/".$plugin->pluginFile);
			rmdir($config['pluginsDir'].$plugin->pluginName);
		} else {
			trace("removing plugin - files not deleted (in debug mode)");
		}
		$query = sprintf("UPDATE plugins SET deleted=1 WHERE pluginName='%s'",$pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$query = sprintf("UPDATE projectplugins SET deleted=1 WHERE pluginName='%s'",$pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
		$query = sprintf("UPDATE projecttemplateplugins SET deleted=1 WHERE pluginName='%s'",$pluginName);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* Gets a plugin object
	* @param string $pluginName the unique name of the plugin
	* @return Plugin a plugin object
	*/
	public static function GetPlugin($pluginName) {
		global $database;
		$query = sprintf("SELECT * FROM plugins WHERE pluginname='%s' AND deleted=0",$pluginName);
		$result = $database->queryAssoc($query);
		$plugin = new Plugin($pluginName, $result[0]['pluginfile'], $result[0]['pluginorder'], $result[0]['enabled'], $result[0]['sitewide']);
		//trace("plugin is <pre>".print_r($plugin, true)."</pre>");
		return $plugin;
	}


	/**
	* list all installed plugins by order
	* @return array array of Plugin objects in order
	*/
	public static function ListPlugins($startFrom=0) {
		global $database,$config;
		if (($_SESSION[USER]->sitewidePermissions['AddPlugin']!=ALLOW) &&
 			($_SESSION[USER]->superadmin!=ALLOW)) InsufficientPermissions(); 			
		$plugins = array();
		$query = sprintf("SELECT * FROM plugins WHERE deleted=0 ".
						"ORDER BY pluginorder"
		);
		/*
		" LIMIT %s,%s",
						$startFrom,
						$config['listPageSize']
    */
    
		$result = $database->queryAssoc($query,$startFrom,$config['listPageSize']);
		foreach ($result as $r) {
			$plugins[] = new Plugin($r['pluginname'], $r['pluginfile'], $r['pluginorder'], $r['enabled'], $r['sitewide']);
		}
		return $plugins;
	}

	/**
	 * return the total number of plugins 
 	*/
	public static function GetPluginCount() {
		global $database;
		$query = "SELECT COUNT(*) AS plugintotal FROM plugins WHERE deleted=0";
		$result = $database->queryAssoc($query);
		return $result[0]['plugintotal'];
	}

	/**
	* get a list of all plugins for a given project
	* @todo this should return all plugins, inlcuding sitewide plugins and whether or not the plugin is being overridden. Is this done?-MH
	* @param int $projectId project to get plugins for
	* @return array array of plugins
	*/
	public static function GetProjectPlugins($projectId) {
		global $database;
		$plugins = array();
		$query = sprintf("SELECT * FROM plugins WHERE sitewide=0 ".
						"AND deleted=0 ORDER BY `pluginorder`");
		$siteplugins = $database->queryAssoc($query);
		$project = Project::GetProject($projectId);
		trace("<pre>".print_r($project, true)."</pre>");
		$query = sprintf("SELECT * FROM projecttemplateplugins WHERE projecttemplateuid=%s ".
						"AND deleted=0", $project->templateId);
		$projecttemplateplugins = $database->queryAssoc($query);		
		$query = sprintf("SELECT * FROM projectplugins WHERE projectid=%s ".
						"AND deleted=0", $projectId);
		$projectplugins = $database->queryAssoc($query);	
		// initialize with site plugin details	
		foreach ($siteplugins as $p) { 
			$plugin = new Plugin($p['pluginname'],$p['pluginfile'],$p['pluginorder'],$p['enabled'],0);
			//trace("<pre> ".print_r($plugin, true)."</pre>");
			$range = 'sitewide';
			// check to see if overridden by template
			foreach ($projecttemplateplugins as $ptp) {
				if ($plugin->pluginName == $ptp['pluginname']) {
					//trace("setting ".$ptp['pluginname']." to ".$ptp['enabled']." at template level");	
					$plugin->enabled = $ptp['enabled'];
					$range = 'template';
					break;					
				}
			}
			// check to see if overriden by project	
			foreach ($projectplugins as $pp) {
				if ($plugin->pluginName == $pp['pluginname']) {
						//trace("setting ".$pp['pluginname']." to ".$pp['enabled']." at project level");	
					$plugin->enabled = $pp['enabled'];
					$range = 'project';							
					break;
				}
			}			
			// write plugin listing
			$plugins[] = array('plugin'=>new Plugin($plugin->pluginName, $plugin->pluginFile, $plugin->order, $plugin->enabled),
					'range'=>$range);
		}
		//trace("<pre>".print_r($plugins,true)."</pre>");
		return $plugins;
	}

	/**
	* get a specific plugin for a given project
	* @param string $pluginId unique name of the plugin
	* @param int $projectId
	* @return Plugin the plugin
	*/
	public static function GetProjectPlugin($pluginId, $projectId) {
		global $database;
		$query = sprintf("SELECT * FROM projectplugins WHERE pluginname='%s' ".
						"AND projectid=%s AND deleted=0",$pluginId,$projectId);
		$result = $database->queryAssoc($query);
		foreach ($result as $r) {
			$plugin = Plugin::GetPlugin($pluginId);
			$projectplugin = new Plugin($pluginId, $plugin->pluginFile, $plugin->order, $r['enabled'], 0);
		}
		//trace("<pre>".print_r($projectplugin, true)."</pre>");
		return $projectplugin;
	}

	/**
	* removes the record of the plugin for the project, thereby reverting the details back to the sitewide defaults
	* @param string $pluginId
	* @param $projectId
	*/
	public static function ClearProjectPlugin($pluginId, $projectId) {
		global $database;
		$query = sprintf("DELETE FROM projectplugins WHERE projectid=%s ".
						"AND pluginname='%s' AND deleted=0",$projectId, $pluginId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* adds a new record in order to override the sitewide default values for the plugin for this project
	* @param int $projectId
	* @param string $pluginId
	* @param int $enabled 1 or 0 dependin on whether to set the plugin to active or inactive
	*/
	public static function AddProjectPlugin($projectId, $pluginId ,$enabled) {
		global $database;
		$query = sprintf("INSERT INTO projectplugins (projectid, pluginname, enabled) VALUES (%s, '%s', %s)", $projectId, $pluginId, $enabled);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* edits a project-specific plugin record
	* @param int $projectId
	* @param string $pluginId
	* @param int $enabled 1 or 0 dependin on whether to set the plugin to active or inactive
	*/
	public static function EditProjectPlugin($projectId, $pluginId ,$enabled) {
		global $database;
		$query = sprintf("UPDATE projectplugins SET enabled=%s WHERE projectid=%s AND pluginname='%s'", $enabled, $projectId, $pluginId);
		//trace($query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	/**
	* get a list of all plugins for a given project
	* @todo this should return all plugins, inlcuding sitewide plugins and whether or not the plugin is being overridden
	* @param int $projectId project to get plugins for
	* @return array array of plugins
	*/
	public static function GetProjectTemplatePlugins($projectTemplateId) {
		global $database;
		$plugins = array();
		$query = sprintf("SELECT * FROM plugins WHERE sitewide=0 AND deleted=0 ORDER BY `pluginorder`");
		$siteplugins = $database->queryAssoc($query);
		$query = sprintf("SELECT * FROM projecttemplateplugins WHERE projecttemplateuid=%s ".
						"AND deleted=0", $projectTemplateId);
		$projecttemplateplugins = $database->queryAssoc($query);			
		// initialize with site plugin details	
		foreach ($siteplugins as $p) { 
			$plugin = new Plugin($p['pluginname'],$p['pluginfile'],$p['pluginorder'],$p['enabled'],0);
			//trace("<pre> ".print_r($plugin, true)."</pre>");
			$range = 'sitewide';
			// check to see if overridden by template
			foreach ($projecttemplateplugins as $ptp) {
				if ($plugin->pluginName == $ptp['pluginname']) {
					//trace("setting ".$ptp['pluginname']." to ".$ptp['enabled']." at template level");	
					$plugin->enabled = $ptp['enabled'];
					$range = 'template';
					break;					
				}
			}
			// write plugin listing
			$plugins[] = array('plugin'=>new Plugin($plugin->pluginName, $plugin->pluginFile, $plugin->order, $plugin->enabled),
					'range'=>$range);
		}
		//trace("<pre>getprojecttemplateplugins returning: ".print_r($plugins,true)."</pre>");
		return $plugins;
	}

	/**
	* get a specific plugin for a given project template
	* @param string $pluginId unique name of the plugin
	* @param int $projectTemplateUid
	* @return Plugin the plugin
	*/
	public static function GetProjectTemplatePlugin($pluginId, $projectTemplateUid) {
		global $database;
		$query = sprintf("SELECT * FROM projecttemplateplugins WHERE pluginname='%s' ".
						"AND projecttemplateuid=%s AND deleted=0",$pluginId,$projectTemplateUid);
		$result = $database->queryAssoc($query);
		foreach ($result as $r) {
			$plugin = Plugin::GetPlugin($pluginId);
			$projectTemplatePlugin = new Plugin($pluginId, $plugin->pluginFile, $plugin->order, $r['enabled'], 0);
		}
		//trace("<pre>".print_r($projectTemplatePlugin, true)."</pre>");
		return $projectTemplatePlugin;
	}

	/**
	* removes the record of the plugin for the project template, thereby reverting the details back to the sitewide defaults
	* @param string $pluginId
	* @param $projectTemplateUid
	*/
	public static function ClearProjectTemplatePlugin($pluginId, $projectTemplateUid) {
		global $database;
		$query = sprintf("DELETE FROM projecttemplateplugins WHERE projecttemplateuid=%s ".
						"AND pluginname='%s' AND deleted=0",$projectTemplateUid, $pluginId);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}
	
	/**
	* adds a new record in order to override the sitewide default values for the plugin for this template
	* @param int $projectTemplateId
	* @param string $pluginId
	* @param int $enabled 1 or 0 dependin on whether to set the plugin to active or inactive
	*/
	public static function AddProjectTemplatePlugin($projectTemplateId, $pluginId ,$enabled) {
		global $database;
		$query = sprintf("INSERT INTO projecttemplateplugins (projecttemplateuid, pluginname, enabled) VALUES (%s, '%s', %s)", $projectTemplateId, $pluginId, $enabled);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}
	
	/**
	* edits a template-specific plugin record
	* @param int $projectTemplateId
	* @param string $pluginId
	* @param int $enabled 1 or 0 depending on whether to set the plugin to active or inactive
	*/
	public static function EditProjectTemplatePlugin($projectTemplateId, $pluginId ,$enabled) {
		global $database;
		$query = sprintf("UPDATE projecttemplateplugins SET enabled=%s WHERE projecttemplateuid=%s AND pluginname='%s'", $enabled, $projectTemplateId, $pluginId);
		//trace($query);
		$result = $database->execute($query);
		if ($result !== true) {
			die($result);
		}
	}

	public function __destruct() {
	}

}

?>
