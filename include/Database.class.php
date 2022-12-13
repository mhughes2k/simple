<?php
	class Database{
		var $database = null;

		function __construct() {
			global $config;	
			try {
				$this->database =  new PDO('mysql:host='.$config[DATABASE_HOST].';port='.$config[DATABASE_PORT].';dbname='.$config[DATABASE_NAME], 
				$config[DATABASE_USERNAME], $config[DATABASE_PASSWORD]);		
				//foreach($this->query('SELECT * from users') as $row) {
				//	print_r($row);
				//}
			} catch (PDOException $e) {
				print "PDO Error!: " . $e->getMessage() . "<br/>";
				die();
			}		
		}
		
		function __destruct() {
			$this->disconnect();
		}
		function dump($val) {
			echo '<pre>';
			print_r($val);
			echo '</pre>';
		}
		/**
		 * Performs a query against the database.
		 * @param string $query SQL query to perform against database.
		 */
		function query($query) {
			//print $query;
			if (is_null($this->database)) {
				//print_r($this->database);
				die('Database object is null');
			}
			try {
				$result = $this->database->query($query);
				if($result) {
					$array = $result->fetchAll();
				} else {
					return FALSE;
				}
			} catch (PDOException $e) {
				die($e->getMessage());
			}
			return $array;
		}
		/**
		 * Performs a query against the database and returns the results as an associative array.
		 * @param string $query SQL query to perform against database.
		 * @return array Associative array containing all the rows returned by the query.
		 */
		function queryAssoc($query,$start=null,$limit=null) {			
			//print $query;
			if (is_null($this->database)) {
				die('Database object is null');
			}
			if (!is_null($limit)){
			//	$this->database->setLimit($limit,$start);
			}
			$statement = $this->database->query($query);	
			$array = $statement->fetchAll();
			return $array;
		}
		/**
		* @return bool True if the query is executed or the error if it wasn't
		*/
		function execute($query) {
			//print $query."<br/>";
			if (is_null($this->database)) {
				//print_r($this->database);
				die('Database object is null');
			}
			$result = $this->database->exec($query);
			//print_r($result);
			return true;
		}
		
		/*
		* quotes the string for safe insertion into database
		*/
		function quote($s) {
			$s = SafeDB($s);
			return $s;
		}
				
		/** 
		 * Disconnects from the database.
		 */
		function disconnect(){
			if (is_null($this->database)) {
				//print_r($this->database);
				die('Database object is null');
			}
			if(!is_null($this->database)) {
				//$this->database->disconnect();
			}
		}

	}
	/**
	* @global Database $database
	*/
	$database = new Database();
?>
