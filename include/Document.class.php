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
  * Manages a Document in the document store.
  */
class Document extends Item{
	public $documentuid = -1;
	public $filename = "";
	public $visiblename = "";
	public $icon = "";
	public $content = null;
	public $contenttype = "";
	public $recipient ="";
	public $sender= "";
	public $timestamp='';
	//public $isread = false;
	
	/**
	 * 
	 */
	function __construct($dbRow = null) {
		global $config;
    	if (!is_null($dbRow)){
    		trace("Constructing Item");
    		foreach($dbRow as $fieldName=>$field) {
				trace($fieldName ."=".$field);
				$this->$fieldName = $field;
			}
			if($this->filename == "") {
				$this->filename = "Unnamed file";
			}
			if ($this->icon == "" or is_null($this->icon)) {
				$this->icon = $config['defaultDocumentIcon'];
			}
			trace("Setting id and itemType");
			$this->id = $this->documentuid;
			trace("ID:".$this->id);
			$this->itemType=DOC_TYPE_DOC;
			trace("type:".$this->itemType);
			if (isset($dbRow['foldername'])) {
        $this->folderName = $dbRow['foldername'];
      }
		}
		else {
			$this->id = -1;
		}
    }
    /**
     * Moves the Document to a different folder.
     * 
     * @param int $DestinationFolderId The ID of the folder to move the document.
     */
    function MoveDocument($DestinationFolderId) {
		  if ($DestinationFolderId != $this->folderid){
        $this->folderid = $DestinationFolderId;
        
        $this->Save();
    	}
    }
    /**
     * Makes a complete copy of the object in a different folder.
     * 
     * @param int $DestinationFolderId The ID of the folder to copy the document into.
     */
    function CopyDocument($DestinationFolderId){
    	$copy = new Document(null);
    	$copy->id = -1;
    	$copy->filename = $this->filename;
    	$copy->content = $this->GetContent();
    	$copy->icon =$this->icon;
    	$copy->contenttype = $this->contenttype;
    	$copy->isread = false;
    	$copy->folderid = $DestinationFolderId;
    	$copy->timestamp = $this->timestamp;
    	//print_r($this);
    	//echo '-----<br/>';
    	$copy->Save();
    	//echo 'postcopy<br/>';
    	return $copy;
    }
    /**
     * Hides a document from student view
     */
    function Delete() {
    	global $database;
    	$sql = sprintf('UPDATE documents SET hidden = 1 WHERE documentuid = %s',
    	$this->id);
    	trace($sql);
    	$database->execute($sql);
    	
    }
    /**
     * Brings a document back to student view
     */
     function Undelete() {
     	global $database;
     	$sql = sprintf('UPDATE documents SET hidden = 0 WHERE documentuid = %s',
    	$this->id);
    	$database->execute($sql);
     }
     
    /**
     * Pulls the actual content in to the object.
     * 
     * We generally don't want the content part of the document when we are doing various
     * operations as it introduces an overhead. GetContent() explicitly loads the document's
     * contents when we need it.
     * 
     * The content is also then subsequently accessible via the {@link $content} attribute.
     * 
     * @return string The document's content. 
     */
    function GetContent() {
    	global $database,$metrics,$config;
    	 //$setq = 'SET TEXTSIZE 2147483647;';
    	// if ($config[DATABASE_TYPE]=='mssql') {
    	// 	$setq = 'SET TEXTSIZE 308736;';
    	// 	$database->execute($setq);
    	// }
    	$sql = sprintf("SELECT content FROM documents WHERE documentuid = %s",
			$this->documentuid);
    	$results = $database->queryAssoc($sql);

    	if (count($results)>0) {

    		$c = $results[0]['content'];
    		
    	 //echo 'OLE header:'. substr($c,0,78);
    	 //die();
      	/*
    		$c = stripslashes($results[0]['content']);
				$len = strlen(c);
				if ($c[0] == '\'' && $c[$len-1]=='\'') {
          //echo 'removing quotes';
          $c=substr($c,1,$len-2);
        }
        else {
          $c = $c;
        }
        */
        //$c= stripslashes($c);
        $metrics->recordMetric('retrieving Document','Base64 size:'.strlen($c));
        $c=base64_decode($c);
        //die("content length:" .strlen($c));
        //$c = convert_uudecode($this->content);
        $metrics->recordMetric('retrieving Document','size:'.strlen($c));
        $this->content = $c;
        return $this->content;
    	}
    	return null;
    }
    /**
     * Saves the Document object back to the database;
     * 
     * We may want to change this so that the actual content is stored on a disk, 
     * but this has other issues.
     */
    function Save(){
    	global $database,$_PLUGINS,$config,$metrics;
		$metrics->recordMetric('saving Document','size:'.strlen($this->content));
		$content = base64_encode($this->content);
		//$rawContent = unpack("H*hex",$this->content);
		//$content = "0x".$rawContent['hex'];
		//$metrics->recordMetric('saving Document','base 64 size:'.strlen($content));
		//$content = convert_uuencode($this->content);
		//$content = $database->database->quote($content);
		
		if ($this->id < 0 ) {
      			
			if ($this->filename ==  "") {
				$this->filename = "File Created: ". date('c');
			}
			/*
			 * We always need to generate a timestamp since the item doesn't exist.
			 */
			$this->timestamp= date($config['datetimeformat']);
			if ($this->sender==''){
				$this->sender='-';
			}
			if ($this->recipient==''){
				$this->recipient='-';
			}

			$sql = sprintf("INSERT INTO documents " .
					"(folderid,filename,contenttype,icon,sender,recipient,timestamp,content) " .
					"VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')",        
					$this->folderid,
					$this->filename,
					$this->contenttype,
					"noicon",
					$this->sender,
					$this->recipient,
					$this->timestamp,
					$content);
					//print $sql;
			$database->execute($sql);
	 	  	$this->id = $database->database->lastInsertID();
			
		} else {
			$_PLUGINS->trigger('onBeforeSaveHtmlDocument',array(&$filename,&$folderId,&$docid));
	
			$sql = sprintf("UPDATE documents SET " .
					"filename = '%s', " .
					"icon = '%s', " . 
					"folderid = '%s', " .
					"contenttype = '%s', " .
					"sender = '%s' , " .
					"recipient = '%s', " .
					"timestamp = '%s'," .
					"content = '%s' " . 
					"WHERE documentuid = '%s'",
					$this->filename,
					' ',
					$this->folderid,
					$this->contenttype,
					$this->sender,
					$this->recipient,
					$this->timestamp,
					$content,
					$this->id);

			$result = $database->execute($sql);
		}
		
		//echo "starting db work";
		/*
		 * if ($_PLUGINS->trigger('onAfterSaveHtmlDocument',array(&$filename,&$folderId,&$docid))) {
			echo "doing db work";
			*/			
		//$database->execute($sql);
		//$this->id = $database->database->lastInsertID();
		//}
		
    }
    /**
     * Retrieve a document from the data store using its ID.
     * @param int $DocumentId Document's Unique ID.
     * @return Document A Document object.
     */
    static function GetDocument($DocumentId){
		global $database;
		trace("Document::GetDocument($DocumentId)");
		if ($DocumentId == -1 ){
			trace('Document::GetDocument()>Returning Blank document');
			return new Document();
		}
		$sql = sprintf(
				'SELECT d.folderid,f.name as foldername, documentuid, filename, d.icon,contenttype, readitems.isRead, ' .
				'recipient, sender, hidden,timestamp ' .
				'FROM documents d ' .
				'left join readitems ' .
				'ON readitems.itemid =d.documentuid AND readitems.userId = %s AND ' .
				'itemType=\'doc\' '. 
        'left join folders f On '.
        'f.folderid  =d.folderid '.
				'WHERE documentuid = %s %s ',
				$_SESSION[USER]->id,
				$DocumentId,
				($_SESSION[USER]->superadmin==ALLOW)?'':'AND hidden = 0'
			);
		trace ($sql);
		$results = $database->queryAssoc($sql);
		$item = null;
		if (count($results) >0){
			$item = new Document($results[0]);
		}
		else {
			$item = new Document();
			$item->id = -1;
		}
		return $item;
    }
    
        static function GetDocumentByName($documentname){
		global $database;
		trace("Document::GetDocumentByName($documentname)");
		if ($DocumentId == -1 ){
			trace('Document::GetDocument()>Returning Blank document');
			return new Document();
		}
		$sql = sprintf(
				'SELECT d.folderid,f.name as foldername, documentuid, filename, d.icon,contenttype, readitems.isRead, ' .
				'recipient, sender, hidden,timestamp ' .
				'FROM documents d ' .
				'left join readitems ' .
				'ON readitems.itemid =d.documentuid AND readitems.userId = %s AND ' .
				'itemType=\'doc\' '. 
        'left join folders f On '.
        'f.folderid  =d.folderid '.
				'WHERE filename = "%s" %s ',
				$_SESSION[USER]->id,
				$documentname,
				($_SESSION[USER]->superadmin==ALLOW)?'':'AND hidden = 0'
			);
		trace ($sql);
		$results = $database->queryAssoc($sql);
		$item = null;
		if (count($results) >0){
			$item = new Document($results[0]);
		}
		else {
			$item = new Document();
			$item->id = -1;
		}
		return $item;
    }
    
    /**
     * Retrieves all of the Items in the specified folder.
     * @param int $FolderId ID of the folder to get contents of.
     * @return array An Array of {@link Document} objects
     */
    static function GetItems($FolderId,$sort=''){
    		global $database,$project;
    		$user= $_SESSION[USER];
		$api = false;
 		if (!$api){
	    		$showhidden = $project->GetProjectPermission('UseStaffTools',$user->id);
			}
    		$sql ='';
    		if (!$showhidden) {
				$sql = sprintf(
					'SELECT folderid, documentuid, filename, icon, readitems.isRead,' .
					'recipient, sender,hidden,timestamp ' .
					'FROM documents d ' .
					'left join readitems ' .
					'ON readitems.itemid =d.documentuid AND readitems.userId = %s ' .
					'AND itemType=\'doc\' ' .
					'WHERE folderid = %s AND hidden = 0 ',
					$_SESSION[USER]->id,
					$FolderId
				);
    		}
    		else {
    			$sql = sprintf(
					'SELECT folderid, documentuid, filename, icon, readitems.isRead,' .
					'recipient, sender,hidden,timestamp ' .
					'FROM documents d ' .
					'left join readitems ' .
					'ON readitems.itemid =d.documentuid AND readitems.userId = %s ' .
					'AND itemType=\'doc\' ' .
					'WHERE folderid = %s ',
					$_SESSION[USER]->id,
					$FolderId
				);
    		}
	//	echo $sql;
			if ($sort != '') {
				$sql .= ' ORDER BY '.$sort;
			}
			else {
				$sql .= ' ORDER BY documentuid DESC';
			}
			$results = $database->queryAssoc($sql);
//			echo "items:".count($results);
			$items = array();
			foreach($results as $result){
				$item = new Document($result);
				$items[$result['documentuid']] = $item; 
				
			}
			return $items;
    }

  static function GetItemsByProject($projectId,$sort='') {
      		global $database,$project;
    		$user= $_SESSION[USER];
    		$showhidden = $project->GetProjectPermission('UseStaffTools',$user->id);
    		$sql ='';
    		if (!$showhidden) {
				$sql = sprintf(
					'select f.projectid,d.documentuid '.
          'FROM documents d '.
          'left join folders f ON f.folderid=d.folderid ' .
          'where f.projectid=%s AND hidden=0',
					$projectId
				);
    		}
    		else {
    			$sql = sprintf(
            'select f.projectid,d.documentuid '.
            'FROM documents d '.
            'left join folders f ON f.folderid=d.folderid '.
            'where f.projectid=%s',
					$projectId
				);
    		}
	//	echo $sql;
			if ($sort != '') {
				$sql .= ' ORDER BY '.$sort;
			}
			else {
				$sql .= ' ORDER BY documentuid DESC';
			}
			$results = $database->queryAssoc($sql);
//			echo "items:".count($results);
			$items = array();
			foreach($results as $result){
				//$item = new Document($result);
				$item = Document::GetDocument($result['documentuid']);
				$items[$result['documentuid']] = $item; 
				
			}
			return $items;
    }
}
?>
