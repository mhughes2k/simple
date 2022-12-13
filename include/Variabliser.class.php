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
 * Variabliser.class.php
 * @package SIMPLE
 * @subpackage Objects
 */
 
 /**
  * Performs replacement of ProjectTemplate variables with actual values.
  *
  * Project Templates may define a list of variables (such as CHAR_PLAYER, CHAR_STAFF etc).
  * These are replaced with actual values just before the string is displayed.
  * The Variabliser class performs this replacement.
  * 
  * Variables are indicated in strings using braces { }. Thus 
  * <code>
  * "{CHAR_NAME} vs. {CHAR_defending_firm}"
  * </code>
  * becomes
  * <code>
  * "M Hughes Associates vs. A Defending Firm."
  * </code>
  * 
  * We can use this class in a number of ways
  * 1 With a ProjectTemplate object to generate all of the variables when instantiating a Project.
  * 2 With a DocumentTemplate's contents to insert the variable's value in to the document.
  * 3 With a DocumentTemplate's contents to process a Dynamic variable (such as the item's posted date).
  * 
  * When we are generating a new Project, the $_variable array contains an array containing the 
  * name of the variable as the index and the value or processing instruction. 
  * 
  * <code>$_variable['CHAR_defending_firm']="Dave"
  * $_variable['CHAR_client_name']="SELECT(\"Dave\", \"Ralph\", \"Paul\")"
  * $_variable['CHAR_client_interview_date']="DATE(\"dd/mm/yyyy\",\"today now\",\"-3 days\")"
  * </code>  
  * 
  * In contexts 2 and 3:
  * <code>
  * Welcome {CHAR_client_name}, this document was posted on {=DateDATE("dd/mm/yyyy","03/11/1980 00:00:00","-3 days")}.
  * </code>
  * Would produce
  * <code>Welcome Dave, this document was posted on 31/10/1980.
  * </code>
  * This is of course assuming that "Dave" was chosen for the variable {CHAR_client_name} when the Project 
  * was created. 
  * 
  * Dates and times can use "x" to denote a field that should be calculated:
  * <code>
  * DATE("dd/mm/yyyy h:m:s","x/12/1980 x:x:00","");
  * </code> 
  * Would create a random day in December 1980, at a random time (with 0 seconds).
  * 
  * We also have a "#include(PROJECT_RESOURCE_ID)" function
  * 
  * Commands:
  *   #SELECT()
  *   #DATE()
  *   #INCLUDE()
  *   #RAND()
  *   #EVAL()  
  *   
  * #RAND(lowerLimit,Upperlimit)
  * Generates a random number between lowerLimit & Upperlimt (inclusive)
  * 
  * #EVAL()
  * Evalates the code in the brackets as PHP. You can use {tags} to subsitute variable values in
  * however these must be of an appropriate type to perform the calculation.                      
 * @package SIMPLE
 * @subpackage Objects
  */
  class Variabliser {
  	/**
  	 * List (lookup table basically) of variables & values.
  	 * @internal
  	 */
  	public $Variables = array();
  	private $Bp;
  	/**
  	 * Constructs a new Variabliser object that will use the lookuptable provided.
  	 * 
  	 * Each Variable item should have 2 elements.
  	 * The first is the variable name and must be unique.
  	 * The second is the static value,if the project is not having different values 
  	 * for each project, or a bit of PHP code that evaluates to a value.
  	 */
  	function __construct($Bp,$Variables) {
  		$this->Bp = $Bp;
  		$this->Variables = $Variables;
  		
  		//print_r($Variables,"VB Constructor variables");
  	}
  	/**
  	 * Generates values for each variable in the table.
  	 * 
  	 * Caution: If you save the table it will override any existing values!
  	 */
  	function InitialiseVariables() {
  	   global $metrics;
  		 foreach($this->Variables as $variable=>$value) {
  			$metrics->recordMetric('Initialising Variable',$variable,$value);
  			$v = $this->ProcessValueInstruction($value);
  			$metrics->recordMetric('Initialised Variable',$variable,$v);
  			trace("Setting $variable to:$v");
  			$this->Variables[$variable] = $v;
  		}
  	}
  	/**
  	 * Generates values for any variables that are currently empty.
  	 */
  	function InitialisedUnsetVariables() {
  		 foreach($this->Variables as $variable=>$value) {
  			if ($value==""){
  				$this->Variables[$variable] = ProcessValueInstruction($value);
  			}
  		}
  	}
  	function DumpVariableTable() {
		trace('Variabliser>There are '.count($this->Variables)." variables");
  		//print_r($this->Variables);
  	}
  	/**
  	 * Processes a value and either returns a static value, or calculates the value.
  	 * 
  	 * Functions appear: =Date("dd/mm/yyyy hh:mm:ss","03/11/1980 09:00:00","")
  	 * @param string $Value Either a static value, or evaluatable function.
  	 * @return mixed A value to display.
  	 * @see TLE2/Variables.pkg
  	 */
	function ProcessValueInstruction($Value){
		global $metrics,$_PLUGINS;
//28/08/2007 11:15:16		echo('<br/>Executing ProcessValueInstruction: <b>'.$Value.'</b>');
		//$metrics->recordMetric('PVI Start',$Value);
		$returnValue = stripslashes($Value);
		//$metrics->recordMetric('instruction',$Value);
		trace($Value);
		
	  	if (isset($returnValue[0]) && $returnValue[0]=="#") {
	  		//we have a function
	  		//echo '<br/>Have Function';
	  		$matches = array();
			$function =preg_match("/(\w*)\((.*)\)/",$returnValue,$matches);
			//echo 'count:'.count($matches);
			$fname = '';
			if (count($matches) == 3) {
				$fname = $matches[1];
			}
			//$metrics->recordMetric('something else',"run");
			switch (strtolower($fname)){
				case 'date':
				  //$metrics->recordMetric('DATE',$Value);
					$params =explode(',',$matches[2]);
					$returnValue = $this->GenerateDate( 
							isset($params[0])|$params[0]==''?$params[0]:'d/m/Y H:i:s',
							isset($params[1])?$params[1]:strtotime(),
							isset($params[2])?$params[2]:'');
					break;
				case 'select':
				  //$metrics->recordMetric('SELECT',$Value);
					$returnValue = '!!No List Provided!!';
					$params =preg_split('/","/',$matches[2]);
		      //$params =explode(',',$matches[2]);
					$returnValue = $this->Select($params);
					break;
				case 'include':
				  //$metrics->recordMetric('INCLUDE',$Value);
				  $returnValue = '!!Inclusion Failed!!';
				  $params = $matches[2];
				  $returnValue = $this->DoInclude($params);
				  break;
				case 'rand':
				  //$metrics->recordMetric('RAND',$Value);
				  $returnValue='!!Unable to generate Random number!!';
				  $params = $matches[2];
				  $returnValue = $this->DoRand($params);
				  break;
				case 'eval':
				  $params=$matches[2];
				  $returnValue = $this->EvaluateStatement($params);
				  break;
				case 'resource':
					//$metrics->recordMetric('Resource',$Value);
				  $returnValue='!!Unable to generate resrource url!!';
				  $params = $matches[2];
				  $returnValue = $this->LinkResource($params);
				  break;
				case 'include':
				  $params = $matches[2];
				  $returnValue = $this->DoInclude($params);
				  break;
				default:
				  if (count($matches)>1){
						$params =explode(',',$matches[2]);
						$result = $_PLUGINS->trigger($matches[1],$params);
						//print ("Running ".$matches[2]);
						if ($result) {
							foreach($result as $authAttemp=>$outcome) {
								if ($outcome !== false) {
									$returnValue= $outcome;
								}
							}
						} else {
							//$returnValue = $Value;
							$metrics->recordMetric('piNotFound',$this->Bp,$fname);
						}
					} else {
						$metrics->recordMetric('unable to process PI');
					}
				}
			
			//return $returnValue;
		}
	  	else {
	  		//we do nothing as it is static.
	  	}
	  	//echo 'return:'.$returnValue;
	  	return $returnValue;
	}
	function DoInclude($param) {
    global $metrics;
/*
 * We have to get the DocumentTemplate from the database using the 
 * Blueprint Unique ID (not the DocumentUID).
 * We then run that through the *same* variabliser as this one and 
 * return the output from that as the inclusion text for this function   
*/      
//			$cannedtemplates =$project->GetDocumentTemplates(ALL_TEMPLATES);
			//print_r($cannedtemplates);
//			$blanktemplates=$project->GetDocumentTemplates(ALL_TEMPLATES);
    ///echo 'Parsing '+$param+ '<br>';
    //$metrics->recordMetric('DoInclude()',"Resource ID Pre sub: $param");
    
    $ResourceId = $this->Substitute($param);
//    echo "ResourceId: $ResourceId";
    //$metrics->recordMetric('DoInclude()',"Resource ID Post sub: $ResourceId");	
     
    $ptId = Project::GetProjectTemplateId($this->Bp->id);
    $pt = ProjectTemplate::getTemplate($ptId);
    $ResourceTemplateUid =$pt->GetDocumentTemplateFromName($ResourceId);
    $ResourceTemplate = $pt->getFullDocumentTemplate($ResourceTemplateUid);
    $ResourceTemplateContent = base64_decode($ResourceTemplate['content']);
//print_r($ResourceTemplateContent);
    if (!is_null($ResourceTemplateContent)) {
      
      $output = $this->Substitute($ResourceTemplateContent);
//    die($output);
      
      return $output;
    }
    $metrics->recordMetric('Variabliser','Failed to include '.$ResourceId);
    //die('no Content');
    return '';
  }
  function GetResourceTemplateContentByResourceId($ResourceId){
  			global $database;
			$sql2 = sprintf(
				'SELECT content ' .
				'FROM documenttemplates ' .
				'WHERE filename = \'%s\' AND ' .
				'projecttemplateid =\'%s\'',
				$ResourceId,
				Project::GetProjectTemplateId($this->Bp->id)
			);
			echo $sql2;
			$results_with_content= $database->queryAssoc($sql2);
			//print_r($results_with_content);
			if (count($results_with_content)>0) {
				return $results_with_content[0]['content'];
			}
			return null;
  }
  /**
   * Implement a look by resource name first then, assume UID
   */     
  function LinkResource($TemplateUniqueID){
    if ($TemplateUniqueID !=''){
      return "index.php?option=directory&cmd=viewitem&id=$TemplateUniqueID";
    }
    return 'NOT FOUND';
  }
  function EvaluateStatement($Statement) {
    $result =0;
    $subbedStatement = $this->Substitute($Statement); //should replace any {tags}.
    //we really should do some processing on this to ensure that it is "nice" code that is executed.
    $result = eval($subbedStatement);
    if ($result ===false ) {
      //error in code
      return "Unable to evaulate code.";
    }
    if (is_null($result)) {
      return "Code did not return a value.";
    }
    return $result;
  }
  /**
   * Returns a random number.  
   */  
  function DoRand($Parameters) {
    if ($Parameters !=''){
      $limits = split(",",$Parameters);
      $lowerLimit = (int)$limits[0];
      $upperLimit = (int)$limits[1];
      return $this->GenerateRandomNumber($lowerLimit,$upperLimit);
    }
    return '!!Unable to generate random number, invalid arguments!!';
  }
  
	/**
	 * Implements the "select 1 from a list" instruction.
	 * @param array The List to select 1 from.
	 */
	function Select($list) {
	  global $metrics;
    //return 'Select function not Implemented';
//print_r($list);
		$key = $this->GenerateRandomNumber(0,(count($list)-1));
	 	//$metrics->recordMetric('SELECT-SelectedItemIndex',$key);
	 	$value = $list[$key];
    if ($value != ""){
      if ($value[0]=='"' | $value[0]=='\"')
      {
        $value = substr($value,1);
      }
      //print(strlen($value));
      //die();
      
      if ($value[strlen($value)-1]=='"' | $value[strlen($value)-1]=='\"')
      {
        $value = substr($value,0,strlen($value)-1);
      }
    }
    //die('<p>'.$value.'</p>');
		return $value;//$list[$key];
	}
  	/**
  	 * Implements the "Date()" function.
  	 * 
  	 * Call is Date("dd/mm/yyyy hh:mm:ss", "dd/mm/yyyy hh:mm:ss","+1 hours")
  	 * Using this we can mark up documents with <code>Date("","","-5 days")</code> and the system
  	 * will automatically insert the date as 5 days before the current date.
  	 *  
  	 * @param string $format Formatting for output
  	 * @param string $input The datetime information we have been given. Defaults to current time.
  	 * @param string $modification The amount to adjust the date by
  	 */
  	function GenerateDate($format,$input,$modification=''){
  		//echo("<br>GenerateDate:format:$format<br>input:$input<br>Mod:$modification");
  		$parts = split(" ",$input);	//should give us the date & time part separately
  		$currentDateTime = getDate();
  		$error = false; //set this to true if we find some thing wrong
  		$errors = array();
  		$datePart = null;
  		//dumpArray($parts);
  		////echo '<br><br>Parts[0]='.$parts[0].'*<br>';
  		if ($parts[0] != "today" && $parts[0]!='') {
	  		$date = $parts[0];
			$datePart = $this->CalcDate($date);
			//echo '<br>Calculated date as: '. $datePart;
  		}
  		else {
  			$datePart = array(
  							$currentDateTime["mday"]<10?'0'.$currentDateTime["mday"]:$currentDateTime["mday"],
  							$currentDateTime["mon"]<10?'0'.$currentDateTime["mon"]:$currentDateTime["mon"],
  							$currentDateTime["year"]<10?'0'.$currentDateTime["year"]:$currentDateTime["year"]
  						);
  			//echo '<br>Calculated date from current date as : '. join('/',$datePart);	
  		}
  		if ($parts[1] != "now" & $parts[1]!='') {
			//echo "<br>generating time part from " .$parts[1];
			$timePart = $this->CalcTime($parts[1]);
  		}
  		else {
  			//echo "<br>generating time part as current time";
  			 $timePart = array(
  							$currentDateTime["hours"],
  							$currentDateTime["minutes"],
  							$currentDateTime["seconds"]
  						);	
  		}
  		//dumpArray($datePart);
  		//dumpArray($timePart);
  		//$wDate = $datePart[0]."/".$datePart[1]."/".$datePart[2]." ".
  		$wDate = $datePart[2]."-".$datePart[1]."-".$datePart[0]." ".
  				$timePart[0].":".$timePart[1].":".$timePart[2]."";
  		////echo "wdate:".$wDate; 
  		//echo "mod:".$modification;
  		
  		//echo '<br>Calculated Date:'.$wDate;
  		//$outDate = new DateTime($wDate);
  		//echo '<br>TIMESTAMP:'.strtotime($wDate);
  		$intDate = strtotime($wDate);
  		if ($modification != ""){
  		//	$outDate->modify($modification);
  		$intDate = strtotime($modification,$intDate);
  			//date_modify($wDate,$modification);
  		}
  		else {
  			
  		}
  		if ($format == '') {
  			$format ='d/m/Y H:i:s';
  		}
  		return date($format,$intDate);
  	}
  	/**
  	 * Calculates a time.
  	 * @param string $time String representing a time/time template.
  	 */
  	protected function CalcTime($time){
		$currentDateTime = getDate();
	  	$timeParts = split(":",$time);
	  	////echo '<br>count tim eparts: '.count($timeParts);
	  	switch (count($timeParts)){
			case 3:
				//echo '<br>Hours and minutes & seconds';
				//we have h:m:s
				if ($timeParts[2] == "x"){
					$timeParts[2] = $this->GenerateRandomNumber(0,60);
				}	  		
			case 2:
				//echo '<br>Hours and minutes only';
				if ($timeParts[0] == "x"){
					$timeParts[0] = $this->GenerateRandomNumber(0,24);
				}
				if ($timeParts[1] == "x"){
					$timeParts[1] = $this->GenerateRandomNumber(0,60);
				}			
				break;
			default:
				$timeParts[0] = $currentDateTime['hours'];
				$timeParts[1] = $currentDateTime['minutes'];
				$timeParts[2] = $currentDateTime['seconds'];
	  	}
	  	return $timeParts;
  	}
  	/**
  	 * Calculates a date.
  	 * 
  	 * @param string $date String representing a date/date template. 
  	 */
  	protected function CalcDate($date){
 		$dateParts = split("/",$date);
	  	$currentDateTime = getDate();
	  	//echo '<br>DP2:'.$dateParts[2];
 		if (strtolower($dateParts[2]=="x")) {
  			$dateParts[2] = $this->GenerateRandomNumber(1900,$currentDateTime['year']);
  		}
  		//echo '<br>DP1:'.$dateParts[1];
  		if (strtolower($dateParts[1]) == "x"){
  			$dateParts[1] = $this->GenerateRandomNumber(1,12);
  			//echo '<br>setting dp1:'.$dateParts[1];
  		}
		else {
  			if ($dateParts[1]<1 or $dateParts[1]>12){
  				$error = true;
  				$errors[] = "Invalid Month.";
  			}
  		}
  		if (strtolower($dateParts[0]) == "x"){
  			$dateParts[0] = $this->GenerateRandomNumber(1,$this->MaxDays($currentDateTime['mon']));
  		}
		else {  		
  			if ($dateParts[0]<0 or $dateParts[0 > $this->MaxDays($dateParts[1])]){
				$error= true;
				$errors[] = "Too many or too few days in month." ; 			
  			}
  		}
  		return $dateParts;
  	}
  	/**
  	 * Returns the number of days in a month.
  	 * 
  	 * Ignores February in leap years and only ever returns 28
  	 * 
  	 * @todo Replace with PHP Calendar functions (as in coreOffice).
  	 */
  	function MaxDays($monthNum){
  		switch ($monthNum){
  			case 8:
  			case 4:
  			case 6:
  			case 11:
  				return 30;
  				break;
  			case 2:
  				return 28;
  				break;
  			default:
  				return 30;
  		}
  	}
  	/**Generates a random number between 2 limits.
  	 */  	
  	function GenerateRandomNumber($start, $max){
  	   global $metrics;
  	   //srand(time());
		  $random = rand($start,$max);//(rand()%$max)+$start;
		  //$metrics->recordMetric('RandomNumber',$random);
		// print("random number between 0 and 9 is: $random");
		  return $random;
  	}
  	
  	/**
  	 * Performs substitutions on a string.
  	 * 
  	 * @link http://technologies.law.strath.ac.uk/TLE2/wiki/index.php/Project_Resources Wiki
  	 * @l
  	 * @param string $InString The String in which to replace variables.
  	 * @return string 
  	 */
  	function Substitute($InString,$Highlight = false) {
  		global $metrics;
  		$workingString = $InString;
				
      //print_r($this);
  		$linkPattern="/(href|src)=(\"|\')(.*)(\"|\')/";
  		//$metrics->recordMetric('matching srcs');
          
  		$workingString = preg_replace_callback(
  							$linkPattern,
  							array(get_class($this),'Linksub_Callback'),
  							$workingString
  						);

 
  		$regexPattern = "/\{([a-zA-Z_:+(),\s0-9.]+)\}/";

  		$workingString = preg_replace_callback($regexPattern,
  		array(get_class($this), 'Substitution_Callback'),
  		$workingString);
		
  		$regexPattern2 = "
      /
      \{
      ([a-zA-Z_#:+(),\s0-9.]+)
      \}
      /";
      $regexPattern2 = "/\{(.*)\}/";
	  // this line is causing some documents to fail
  		$workingString = preg_replace_callback($regexPattern2,
  		array(get_class($this), 'Substitution_Callback'),
  		$workingString);
  		
      //$metrics->recordMetric('something else',$InString, $workingString);
      		// print "workingString is now ".$workingString;

  		return $workingString;
  	}
  	/**
  	 * Replaces any links starting with ! as links to 
  	 * a Project-Resource.
  	 */
  	function Linksub_Callback($matches){
  		global $config,$metrics;
  		$rv = $matches[0];
  		// if the match is a name of a resource then replace the link with a link to that resource
  		$filename = $matches[3];
  		//$metrics->recordMetric("filename:".$filename);
  		
  		$docTemplateUid = $this->Bp->GetProjectTemplate()->GetDocumentTemplateFromName($filename);
  		if (is_null($docTemplateUid)){
        //$rv = "Blueprint resource not defined with name \"$filename\"";
        return $rv;
      }
  		if (!is_null($docTemplateUid)){
  		  $rv = $matches[1].'="'.$config['home']."index.php?option=download&docuid=$docTemplateUid&download=0&docType=doc_templ&pid=".$this->Bp->id.'"';
  		}
  		//return print_r($matches);
  		return $rv;
  	}
  	/**
  	 * Performs substitutions for PI formulas
  	 */
  	function Substitution_Callback($matches){
  		global $metrics;
  		//print ('<br><br>matches found');
  		//print_r($matches);
      //$metrics->recordMetric('Processing',$matches[1]);
        //$metrics->recordMetric('performing substitution-2',debug_backtrace());
      $firstLetter = '';
      if (isset($matches[1][0])){
        $firstLetter = $matches[1][0];
      }
  		if ($firstLetter=="#"){
  		  //print('PVI');
        //$metrics->recordMetric('performing PVI -1',join(" ",$matches));
  			$rv = $this->ProcessValueInstruction($matches[1]);
			//$rv = htmlspecialchars($rv);
  			return $rv;
  		} 
  		else {
  		//print('var');
  		  //$metrics->recordMetric('performing substitution-1',join(" ",$matches));
  		  //rint $this->Variables[$matches[1]];
        if (isset($this->Variables[$matches[1]]) && $this->Variables[$matches[1]]!=""){
  				//$metrics->recordMetric("Variable ".$matches[1]." found");
  				//print('found');
  				$rv = $this->Variables[$matches[1]];
				//$rv = htmlspecialchars($rv);
  				return $rv;
  			}
  			else {
  			 //print('not found '.$matches[1]);
  				//$metrics->recordMetric("Variable ".$matches[1]." not found");
				
				switch($matches[1]) {
				  case 'project_id':
				    $rv = $this->Bp->id;
				    break;
				  case 'project_name':
				    $rv = $this->Bp->Name;
				    break;
				  default:
				    {
				      if ($matches[1] =='current.date') {
					$rv = $this->GenerateDate('d/m/Y','today now','');
				      }
				      else {
					      $rv = $matches[0];
				      }
				     }
				}
				//$rv = htmlspecialchars($rv);
  				return $rv;
  			}
  		}
  		//echo("Subbing ".$matches[1] . " with ".$rv);
  	return 'Failed to Sub';
  	}
  }
?>
