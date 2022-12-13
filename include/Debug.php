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
 * TLE2 debug functions
 * @author Michael Hughes
 * 
 * @package TLE2
 */	
 	$traceMessages = array();
	/**
	 * Dumps an array out the stdout.
	 */
	function dumpArray($array,$title = "") {
		global $config;
		$out="";
		if ($title != ""){
			$out.= "<h3>$title</h4>";
		}
		$out.='There are '. count($array).' items'; 
		if ($config['allowArrayDumps']) {
			$out.="<ul>";
			if (is_array($array)){
				foreach($array as $item=>$value) {
					if (is_array($value )) {
						$out.= "<li>";
						$out.= dumpArray($value);
						$out.="</li>";
					}
					else {
						$out.="<li>$item:$value</li>";
					}
				}
			}
			else {
				$out.= $array;
			}
			$out.= "</ul>";
		}
		trace($out);
	}
	
	function traceArray($array) {
		global $config;
		$strout="";
		
		if ($config['allowArrayDumps']) {
			$strout.= "<ul>";
			foreach($array as $item=>$value) {
				if (is_array($value )) {
					$strout.= "<li>";
					$strout.=traceArray($value);
					$strout.= "</li>";
				}
				else {
					try {
print_r(debug_backtrace());
						$strout.= "<li>$item:".$value."</li>";
					}
					catch(Exception $e) {
echo 'caugh exception:' . $e->getMessage();
echo debug_backtrace();
					}
				}
			}
			$strout.= "</ul>";
		}
		return($strout);
	}
	
	/*
	 * Sends trace messages to the browser.
	 */
	function trace($message) {
		global $traceMessages,$metrics;
		$traceToMetrics= isset($config['traceToMetricsTable'])?$config['traceToMetricsTable']:false;
		$traceToMetrics=true;   //should remove this prior live release.
		if (is_array($message)){
			dumpArray($message);
		}
		else 
		{
			$traceMessages[] = $message;	
			if ($traceToMetrics && isset($metrics)){
      //print($message);
        //$metrics->recordMetric('trace',$message);
      }
		}
		
	}
	function dumpTrace() {
		global $config,$traceMessages;
		$strout = "";
		if ($config['debug']) {
			$strout.= "<div class=\"debug\"><b>Trace</b>";
			$strout.= "<ol>";
			foreach($traceMessages as $trace) {
				$strout.= "<li>$trace";
				/*if ((isset($config['allowStackTrace']) and $config['allowStackTrace']?true:false)) {
					$strout.=traceArray(debug_backtrace());
				}
				*/
				$strout.="</li>";
			}
			$strout.= "</ol></div>";
		}
		return $strout;
	}
?>
