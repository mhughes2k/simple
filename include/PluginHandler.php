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
* Plugin Handler
* @author Michael Hughes
 * @package SIMPLE
 * @subpackage Objects
* @tutorial TLE2/PluginHandler.cls
*/
/**
 * @package TLE2
 * @tutorial TLE2/PluginHandler.cls
*/
	class PluginHandler {
		/**
		* @var array Array to store plugin function information.
		*/
		private $_events = null;
		/**
		* @var array Array to store the bots
		* @deprecated Hang over from Joomla
		*/
		private $_bots = null;
    
    private $_verbs = null;
		/**
		* Constructor
		*/
		function PluginHandler() {
			$this->_events = array();
			$this->_verbs = array();
		}

		/**
		* Plugins must call this function to register themselves in the system when they are "included".
		* @param string $event The event that triggers the plugin.
		* @param string $pluginName the Name of the plugin.
		* @param string $function Name of the function that implements the plugin code.
		*/
		function registerFunction($event,$pluginName,$function) {
			trace("Registering plugin <b>$function</b> for <b>$event</b>");
			$this->_events[$event][] = array($function,$pluginName);
		}

    function registerVerb($verb,$function){
      $this->_verbs[$verb]= $function;
    }
		/**
		* Dumps plugin information via the Trace function.
		*/
		function dumpPlugins() {
			trace("Dumping Plugins: ");
			foreach($this->_events as $key=>$value) {
				trace($key);
				foreach($this->_events[$key] as $func) {
					trace(join(" : ",$func));
				}
			}
		}

		/**
		* Function to call plugins when appropriate event is triggered.
		* @global Metrics Allows the function to record metrics when plugin is triggered.
		* @param string $event Name of the event to trigger plugins for
		* @param array $args Array of arguments to be passed to each plugin.
		* @param boolean True if ALL plugins returned true or false if ANY plugin returned false.
		* array Array containing the results of each plugin.
		*/
		function trigger($event,$args=array()) {
			//print "triggering";
			//print_r($args);
			global $metrics;
			$result = array();
			$bResult = true;
			if (isset( $this->_events[$event] )) {
				trace("Event <b>$event</b> triggered");
				foreach($this->_events[$event] as $func) {
					//$metrics->recordMetric("Excuting Plugin code: <b>".$func[1]."</b>");
					//print_r($args);
					$r= call_user_func_array($func[0],$args);	
					$bResult = $bResult and $r;
					//$metrics->recordMetric("Result code: <b>".$bResult."</b>");
					$result[$func[0]] = $r;
				}
			}
			return $result;
		}
		
		function ExecuteVerb($verb,$args=array()) {
		  
      if (isset($this->_verbs[$verb])){
        
        $r = call_user_func_array($this->_verbs[$verb],$args);
        //echo $r;
      }
      else{
        return false;
      }
      return $r;
    }
    
    static function PluginResultIsOk($results){
      $bResult = true;
    		foreach($results as $result){
      //echo($result);
        $bResult = $bResult & $result;
      }
      return $bResult;
    }

		/**
		* Loads the active plugins.
		* @global array holds configuration data
		* @global Database database object.
		* @global PluginHandler plugin handler object.
		*/
		static function loadPlugins() {
			global $config,$database,$_PLUGINS;
			$plugins = array();
			if ((isset($_SESSION['projectId'])) && ($_SESSION['projectId']!=-1)) {
				// project plugins have priority so get these first
				$query = sprintf("SELECT * FROM projectplugins WHERE projectid=%s",$_SESSION['projectId']);
				$result = $database->queryAssoc($query);
				foreach ($result as $r) {
					$plugin = Plugin::GetPlugin($r['pluginname']);
					$plugins[] = array('pluginname'=>$plugin->pluginName,
										'pluginfile'=>$plugin->pluginFile,
										'order'=>$plugin->order,
										'enabled'=>$r['enabled']);
				}
				// then template plugins
				$project = Project::GetProject($_SESSION['projectId']);
				if (!is_null($project)){
  				$query = sprintf("SELECT * FROM projecttemplateplugins WHERE projecttemplateuid=%s",$project->templateId);
  				$result = $database->queryAssoc($query);
  				foreach ($result as $r) {
  					// check if result is already in plugins array
  					$exists = false;
  					foreach ($plugins as $p) {
  						if ($r['pluginname']==$p['pluginname']) {
  							$exists = true;
  							break;
  						}
  					}
  					if (!$exists) {
  						$plugin = Plugin::GetPlugin($r['pluginname']);
  						$plugins[] = array('pluginname'=>$plugin->pluginName,
  									'pluginfile'=>$plugin->pluginFile,
  									'order'=>$plugin->order,
  									'enabled'=>$r['enabled']);
  					}
  				}
				}
			}

			// 	finally sitewide plugins
			$result = $database->queryAssoc("SELECT * FROM plugins WHERE enabled = 1 ORDER BY pluginorder");
			foreach ($result as $r) {

				// 	check if result is already in plugins array
				$exists = false;
				foreach ($plugins as $p) {
					if ($r['pluginname']==$p['pluginname']) {
						$exists = true;
						break;
					}
				}
				if (!$exists) {
					$plugins[] = array('pluginname'=>$r['pluginname'],
							'pluginfile'=>$r['pluginfile'],
							'order'=>$r['pluginorder'],
							'enabled'=>$r['enabled']);
				}
			}

			// get order column as array for sorting
			$order = array();
			foreach ($plugins as $key=>$p) {
				trace("key*".$key.":$p");
				$order[$key] = $p['order'];
			}
      if (is_array($order)) {
  			array_multisort($order, SORT_ASC, $plugins);
  			foreach($plugins as $plugin) {
  				if ($plugin['enabled']=='1') {
  					if (file_exists($config['pluginsDir'].$plugin['pluginname']."/".$plugin["pluginfile"])) {
  						include($config['pluginsDir'].$plugin['pluginname']."/".$plugin["pluginfile"]);
  					}
  				}
  			}
			}
		}

		/**
 		* gets all plugin info for a specific event
		*/
		function getEventPlugins($event) {
			global $database;
			$plugins = array();
			foreach($this->_events[$event] as $plugin) {
				$plugins[] = array('pname'=>$plugin[0],'pdesc'=>$plugin[1]);
			}
			return $plugins;
		}

	}


?>
