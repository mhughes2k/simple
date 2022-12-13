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
 * @subpackage Objects
*/

/**
 * Provides metrics and logging functionality
  */
 	class Metrics {
		public $recordMetrics = true;
		/**
		*
		*/
		function __constructor($recordMetics = true) {
			//ensure that we can record if metric recording is disabled.
			$this->recordMetrics = true;
			if (!$this->recordMetrics) {
				recordMetric('MetricRecording',"disabled");
			}
			//disable/enable metric recording.
			$this->recordMetrics=$recordMetics;
		}
		/**
		* @param string $metric Name of the metric to record
		* @param mixed $value Value recorded for the metric
		*/
		function recordMetric() {
//return;
			global $_PLUGINS;
			$arguments = array();
			$saveValue = '';
			$errLevel = error_reporting();
			error_reporting(0);
			//echo 'doing record';
			if ($this->recordMetrics) {
				$arguments = func_get_args();
				$num_args = func_num_args();
				$saveValue = implode(",",$arguments );
				$saveValue=SafeDb($saveValue);
				if ($num_args <2 ) {
	//insufficient arguments;
					trace("Insufficient arguments for recordMetric");
				}
				if ($num_args == 2) {
					$this->RecordToDatabase($arguments[0],$arguments[1]);
				}
				else {
					$this->RecordToDatabase($arguments[0],$saveValue);
				}
				error_reporting($errLevel);
				//$result = $_PLUGINS->trigger('onSaveMetric',$arguments);
			}
			//echo 'end recordMetric()';
		}
		
		
		
		/**
		* When this plugin is triggered, it records information to the Metrics table in the Default Database.
		* @param string $metric the Name of the metric to record
		* @param mixed $value the value to record.
		*/
		private function RecordToDatabase($metric,$value) {
			global $database;
			//should really check if the default database actually has a "metrics" table
			
			if ($this->recordMetrics){
				
				$quoteMet =	$database->database->quote($metric);
				$quoteValue = $database->database->quote($value);
				$quoteDate = $database->database->quote(date('c')); 

				//echo($quoteDate);
				$query = sprintf(
				"INSERT INTO metrics (id,metricName,value,timestamp,projectid) VALUES ('',%s,'%s',%s,-1)",
					$quoteMet,
					$value,
					$quoteDate
				);
				//echo("Recording Metric: ". $query);
				$result = $database->execute($query);
				if ($result !== true) {
					
				}
			}
		}
			
	}
