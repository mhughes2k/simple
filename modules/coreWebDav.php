<?php

if (!defined('TLE2')) die ('Invalid Entry Point');

require_once "include/webdav_srv/inc/HTTP/WebDAV/Server.php";

class SIMPLE_DavServer extends HTTP_WebDAV_Server {

  var $user;
  var $dav_powered_by = "SIMPLE Web DAV Connector";
  
  function __construct($user=false) {
    $this->user = $user;
    
    //print_r($this);
  }
  function ServeRequest() {
    global $metrics,$errors;
    //$metrics->recordmetric("DAV allowed functions ",print_r($this->_allow(),true));
    $metrics->recordmetric("DAV-------","---------------------");
    $metrics->recordmetric("DAV Start Request -->", print_r($_SERVER,true),print_r($_REQUEST,true));
    
    parent::ServeRequest();
    if (count($errors)>0) {
      $metrics->recordmetric("DAV php errors",print_r($errors,true));
    }
    $metrics->recordmetric("DAV LOCK DISCOVERY",parent::lockdiscovery($_SERVER['PATH'])); 
    $metrics->recordmetric("<-- DAV End Request");
  }

  function GET(&$options) 
  {
    global  $metrics;
    $metrics->recordmetric("DAV GET options",print_r($options,true),print_r($this,true));
//    print_r($options);
    $fspath=$options["path"];
  //  print "Path:".$fspath ."<br />";
    $pathOptions = explode("/",$fspath);
    //print "Sim:". $pathOptions[1]."<br />";
    //print "Folder:". $pathOptions[2]."<br />";
    //lsprint_r($pathOptions);
    //print_r($pathOptions);
    if ($fspath=='/') {
      //return list of simulations for the user
      return $this->GetSimulations($options);
    }
    if (count($pathOptions) == 3) {
      return $this->GetSimulationFolders($pathOptions[1],$options);
    }
    if (count($pathOptions)==4 && $pathOptions[3]=='') {
    //nction GetFolder($simId,$folderName,$opt
      return $this->getFolder($pathOptions[1],$pathOptions[2],$options);
    }
    if (count($pathOptions)==4 && $pathOptions[3]!='') {
    //nction GetFolder($simId,$folderName,$opt
      return $this->GetDocument($pathOptions[1],$pathOptions[2],$pathOptions[3],$options);
    }

    //$fspath="/media/Local Disk/ApacheRoot/";
    //return $this->GetDir($fspath, $options);
    if (is_dir($fspath)) {
      return $this->GetDir($fspath, $options);
    }        
    return true;
  } 
  function GetSimulations($options) {
    $user = $this->user;
    $projects=$user->GetProjects();
    //print_r($projects);
    $format = "%15s  %-19s  %-s\n";
    
            echo "<html><head><title>Index of ".htmlspecialchars($options['path'])."</title></head>\n";
            
            echo "<h1>Index of {$this->user->displayName}</h1>\n";
            
            echo "<pre>";
            //printf($format, "ID", "Filename");
            printf($format, "Size", "Last modified", "Filename");
            echo "<hr>";

foreach($projects as $id=>$project) {
  printf("$format","-","-","<a href='$this->base_uri/$id/'>$id - $project</a>");
}
/*
echo "<hr/>";
print_r($this);
*/
            echo "</pre>";
    
            

            echo "</html>\n";
            return true;
  }
  
  function GetSimulationFolders($simId,$options){
   // print($simId);
    $user = $this->user;
    
    $project = Project::GetProject($simId);
    //print_r($project);
    $folders = $project->getFolders();
    //print_r($projects);
    $format = "%15s  %-19s  %-s\n";
    
            echo "<html><head><title>Index of ".htmlspecialchars($options['path'])."</title></head>\n";
            
            echo "<h1>Index of {$project->name}</h1>\n";
            
            echo "<pre>";
            //printf($format, "ID", "Folder");
            printf($format, "Size", "Last modified", "Filename");
            echo "<hr>";

foreach($folders as $id=>$folder) {
  printf("$format","-","-","<a href='$this->base_uri/$simId/{$folder->name}/'>".$folder->name."</a>");
}
/*
echo "<hr/>";
print_r($this);
*/
            echo "</pre>";
    
            

            echo "</html>\n";
            return true;
            //exit();
  }
  function GetFolder($simId,$folderName,$options) {
       // print($simId);

    $user = $this->user;
    $project = Project::GetProject($simId);
    //print_r($project);
  //  print("name:".$folderName);
    $folderid = $project->GetFolderIdByName($folderName);
    //print ("ID:".$folderid);
    $folder = Folder::getFolder($folderid);
    //print_r($folder);ls
    $format = "%15s  %-19s  %-s\n";
    
            echo "<html><head><title>Index of  ".htmlspecialchars($folder->name)."</title></head>\n";
            
            echo "<h1>Index of {$folder->name}</h1>\n";
            
            echo "<pre>";
            //printf($format, "Date", "Filename", "Sender");
             printf($format, "Size", "Last modified", "Filename");
            echo "<hr>";
  $items = Document::GetItems($folderid);

//print_r($items);
foreach($items as $id=>$doc) {

    printf($format, 
                           $doc->timestamp,
                           "-",
                           "<a href='$this->base_uri/$simId/{$folder->name}/{$doc->filename}'>".$doc->filename."</a>"
                           );

  //printf("$format",$id,"<a href='$this->base_uri/$simId/{$doc->name}/'>".$doc->name."</a>");
}

/*
echo "<hr/>";
print_r($this);
*/
            echo "</pre>";
    
            

            echo "</html>\n";
            return true;
    //exit();
  }
  
  function GetDocument($simId,$folderName,$documentName,&$options) {
    global $metrics;
        $user = $this->user;
        $project = Project::GetProject($simId);
    
        $folderid = $project->GetFolderIdByName($folderName);
    
        $folder = Folder::getFolder($folderid);
        $doc = Document::GetDocumentByName($documentName);
        $metrics->recordMetric("DAV GETdocument", print_r($options,true),print_r($doc,true));
        if ($doc->documentuid==-1) {
          return false;
        }
  			//$doc = Document::GetDocument($docuid);
				//print_r($doc);
        $doc->MarkRead();
				$name = $doc->filename;
				//print_r($doc);
				$doc->GetContent();
				$content =$doc->content;
				//print_r($doc);
				$ctype = $doc->contenttype==""?"text/html":$doc->contenttype;
				
				$header['Content-Disposition'] = "attachment;filename=$filename";
				foreach($header as $k=>$v){
			   header($k.':'.$v);
    		}
    		
    		$options['mimetype']=$ctype;
    		$options['size']= strlen($content);
				$options['data'] =$content;
	 return true;			
  }
  
  
  
  /*
  function COPY(&$options) {
    debug('Copy handler');
  }
  */
  function DELETE(&$options) {
    //debug('delete handler');
    global $metrics;
    $fspath=$options["path"];
    $pathOptions = explode("/",$fspath);
    $user = $this->user;
    $project = Project::GetProject($pathOptions[1]);
    $folderid = $project->GetFolderIdByName($pathOptions[2]);    
    $folder = Folder::getFolder($folderid);
    $doc = Document::GetDocumentByName(urldecode($pathOptions[3]));
    $metrics->recordMetric("DAV DELETE",print_r($doc,true));
    if ($doc->documentuid==-1) {
      return false;
    }
    $doc->Delete();
    return true;
  }
  
  
  function LOCK(&$options) {
    //debug('lock handler');
    return "200 OK";
  }
  
  function UNLOCK(&$options) {
    //debug('unlock handler');
    return "204 No Content";
  }
  
  function checkLock($path) {
    global $metrics;
    return false;
/*    $metrics->recordmetric("DAV CHECKLOCK",$path);
    $locks = array();
    return $locks;
    */
  }
  
  /*
  function MKCOL(&$options) {
    debug('MKCOL handler');
  }
  
  function MOVE(&$options) {
    debug('move handler');
  }
  */
  function PROPFIND(&$options,&$files) {
    global $metrics;
    $sep="";
    $metrics->recordmetric("DAV PROPFIND options",print_r($options,true),print_r($this,true));
    
    $fspath=$options["path"];
    
    if ($_SERVER['HTTP_USER_AGENT'] =="Microsoft Data Access Internet Publishing Provider DAV") {
      $metrics->recordmetric("DAV PROPFIND Windows box");
      $fspath.="/";
      //$sep="/";
    }
    
    $pathOptions = explode("/",$fspath);
    $user = $this->user;
    if ($fspath=='/') {
        $metrics->recordmetric("DAV PROPFIND Simulation list");
        $projects=$user->GetProjects();
        $files["files"] = array();
        //infor for te requested path
        $pathinfo = array();
        $pathinfo["path"]=$fspath;
        $pathinfo["props"] = array();
        $pathinfo["props"][] = $this->mkprop("displayname", "SIMPLE");
        $pathinfo["props"][] = $this->mkprop("resourcetype","collection");
        $fileinfo["props"][] = $this->mkprop("creationdate", "2009-01-29T09:12:Z");
        $pathinfo["props"][] = $this->mkprop("getcontenttype","httpd/unix-directory");
        $pathinfo["props"][] = $this->mkprop("simcount",count($projects));
        $files["files"][] = $pathinfo;
         
        foreach ($projects as $id=>$projectName) {
          $project = Project::getProject($id);
          //print_r($project);  
          $fileinfo = array();
          $pname =str_replace(" ","_",$project->Name);
          $pname = str_replace("#","",$pname);
          //$fileinfo["path"] = "$fspath{$project->id}-$pname/";
          $fileinfo["path"] = "$fspath{$project->id}$sep";//-$pname/";
          $fileinfo["props"] = array();
          $fileinfo["props"][] = $this->mkprop("displayname", "{$project->id}-{$project->Name}");
          $fileinfo["props"][] = $this->mkprop("creationdate", "1970-01-01T00:33:29Z");
          $fileinfo["props"][] = $this->mkprop("resourcetype","collection");
          $fileinfo["props"][] = $this->mkprop("getcontenttype","httpd/unix-directory");
          //$fileinfo["props"][] = $this->mkprop("projectid",$project->id);
          $files["files"][]=$fileinfo;
        }
        return true; 
    }
    if (count($pathOptions) == 3) {
        $len = strlen($fspath);
        if ($fspath[$len] !="/") {
          $sep="/";
        }
        
        $metrics->recordmetric("DAV PROPFIND Sim Contents",$fspath);
        //die($pathOptions[1]);
        //$projects=$user->GetProjects();
        $project = Project::GetProject($pathOptions[1]);
        $folders = $project->getFolders();
        //print_r($project);
        $files["files"] = array();
        //infor for te requested path
        $pathinfo = array();
        $pathinfo["path"]=$fspath;
        
        $pathinfo["props"] = array();
        $pathinfo["props"][] = $this->mkprop("displayname", "{$project->Name}");
        $pathinfo["props"][] = $this->mkprop("resourcetype","collection");
        $pathinfo["props"][] = $this->mkprop("creationdate", "2009-01-29T09:12:Z");
        $pathinfo["props"][] = $this->mkprop("getcontenttype","httpd/unix-directory");
        $pathinfo["props"][] = $this->mkprop("folderCount",count($folders));
        if ($folder->addItem | $user->superadmin) {
          $pathinfo["props"][] = $this->mkprop("editable",'yes');
        }
        else {
          $pathinfo["props"][] = $this->mkprop("editable",'no');
        }
        $files["files"][] = $pathinfo;
        
        
        foreach($folders as $id=>$folder) {
        //  printf("$format","-","-","<a href='$this->base_uri/$simId/{$folder->name}/'>".$folder->name."</a>");
          $folderInfo = array();
          $folderInfo['path']=$fspath.urlencode($folder->name).$sep;
          //$folderInfo['path']=$fspath. $this->sanitise($folder->name);
          $folderInfo['props']= array();
          $folderInfo['props'][] = $this->mkprop("displayname",$folder->name);
          $folderInfo["props"][] = $this->mkprop("resourcetype","collection");
          $folderInfo["props"][] = $this->mkprop("getcontenttype","httpd/unix-directory");
          $files["files"][] = $folderInfo;
        }
        
      
      return true; //$this->GetSimulationFolders($pathOptions[1],$options);
    }
    //return $this->getFolder($pathOptions[1],$pathOptions[2],$options
    if (count($pathOptions)==4 && $pathOptions[3]=='') {
      //die("here");
      $metrics->recordmetric("DAV PROPFIND Folder Contents");
       $user = $this->user;
        $project = Project::GetProject($pathOptions[1]);
        
        $folderid = $project->GetFolderIdByName(urldecode($pathOptions[2]));
        //$folderid = $project->GetFolderIdByName($pathOptions[2]);
        $folder = Folder::getFolder($folderid);
        $items = Document::GetItems($folderid);
        
        $files["files"] = array();
        
        $pathinfo = array();
        $pathinfo["path"]=$fspath;
        $pathinfo["props"] = array();
        $pathinfo["props"][] = $this->mkprop("displayname", $folder->name );
        $pathinfo["props"][] = $this->mkprop("resourcetype","collection");
        $pathinfo["props"][] = $this->mkprop("creationdate", "2009-01-29T09:12:Z");
        $pathinfo['props'][] = $this->mkprop("getlastmodified",'2009-01-29T00:00:00Z');
        $pathinfo['props'][] = $this->mkprop("getcontentlanguage","en-gb");
        $pathinfo["props"][] = $this->mkprop("getcontenttype","httpd/unix-directory");
        $pathinfo["props"][] = $this->mkprop("itemcount",count($items));
        if ($folder->addItem | $user->superadmin) {
          $pathinfo["props"][] = $this->mkprop("editable",'yes');
        }
        else {
          $pathinfo["props"][] = $this->mkprop("editable",'no');
        }
        $pathinfo["props"][] = $this->mkprop("dump", print_r($folder,true));
        $pathinfo["props"][] = $this->mkprop("dump2", print_r($this->user,true));
        $files["files"][] = $pathinfo;
        
        //return true;
        foreach($items as $id=>$doc) {
        //  printf("$format","-","-","<a href='$this->base_uri/$simId/{$folder->name}/'>".$folder->name."</a>");
        //print_r($doc);
          if ($doc->contenttype=='') {
            $ctype="text/html";
          }
          else {
            $ctype=$doc->contenttype;
          }
          $doc->getContent();
          
          $len = strlen($doc->content); 
          $itemInfo = array();
          
          $itemInfo['path']=$fspath.urlencode($doc->filename);
          //$itemInfo['path']=$fspath. $this->sanitise($doc->filename);
          //print ($itemInfo['path']);
          $itemInfo['props']= array();
          $itemInfo['props'][] = $this->mkprop("displayname",$doc->filename);
          $itemInfo['props'][] = $this->mkprop("resourcetype",'');
          $itemInfo['props'][] = $this->mkprop("creationdate",'2009-01-29T00:00:00Z');
          $itemInfo['props'][] = $this->mkprop("getlastmodified",'2009-01-29T00:00:00Z');
          $itemInfo['props'][] = $this->mkprop("getcontentlanguage","en-gb");
          $itemInfo['props'][] = $this->mkprop("getcontenttype",$ctype);
          $itemInfo['props'][] = $this->mkprop("getcontentlength",$len);
          $files["files"][] = $itemInfo;
        }
        return true;
    }
    if(count($pathOptions)==4 && $pathOptions[3]!='') {
        $user = $this->user;
        $project = Project::GetProject($pathOptions[1]);
        
        $folderid = $project->GetFolderIdByName($pathOptions[2]);
        
        $folder = Folder::getFolder($folderid);
        $doc = Document::GetDocumentByName(urldecode($pathOptions[3]));
        $metrics->recordMetric("PROPFIND doc",print_r($doc,true));
        if ($doc->documentuid==-1) {
          
          return false;
        }
        //$doc = Document::GetDocumentByName($pathOptions[3]);
  			//$doc = Document::GetDocument($docuid);
				//print_r($doc);
        //$doc->MarkRead();
				$name = $doc->filename;
				//print_r($doc);
				$doc->GetContent();
				$content =$doc->content;
				//print_r($doc);
				$ctype = $doc->contenttype==""?"text/html":$doc->contenttype;
				$itemInfo = array();
          
        $itemInfo['path']=$fspath;//.urlencode($doc->filename);
        //$itemInfo['path']=$fspath. $this->sanitise($doc->filename);
        //print ($itemInfo['path']);
        $itemInfo['props']= array();
        $itemInfo['props'][] = $this->mkprop("displayname",$doc->filename);
        $itemInfo['props'][] = $this->mkprop("resourcetype",'');
        $itemInfo['props'][] = $this->mkprop("creationdate",'2009-01-29T00:00:00Z');
        $itemInfo['props'][] = $this->mkprop("getlastmodified",'2009-01-29T00:00:00Z');
        $itemInfo['props'][] = $this->mkprop("getcontentlanguage","en-gb");
        $itemInfo['props'][] = $this->mkprop("getcontenttype",$ctype);
        $itemInfo['props'][] = $this->mkprop("getcontentlength",$len);
        $files["files"][] = $itemInfo;
				//$header['Content-Disposition'] = "attachment;filename=$filename";
			//	foreach($header as $k=>$v){
			   //header($k.':'.$v);
    		//ls}
    		/*
    		$options['mimetype']=$ctype;
    		$options['size']= strlen($content);
				$options['data'] =$content;
				*/
				$metrics->recordMetric("PROPFIND mycode results",print_r($options,true));
				return true;
    }

  }
  
  function PUT(&$options) {
    global $metrics;
    $metrics->recordMetric("DAV PUT options",print_r($options,true),print_r($this,true));
    $user = $this->user;
    $path = $this->base .$options["path"];
    $pathOptions = explode("/",$path);
    $metrics->recordMetric("DAV PUT initialising",$path,print_r($pathOptions,true));
    $metrics->recordMetric("DAV PUT options",print_r($options,true));
    if (count($pathOptions)==4 && $pathOptions[3]!='') {
      $metrics->recordMetric('DAV PUT starting');
       if (substr($pathOptions[3],0,2) =="._") {
	//we have an OS x ._<filename> file
	//silently go OK, but don't store it!
	$metrics->recordMetric("DAV PUT","detected ._ file");
	//return true;
       }
       $project = Project::GetProject($pathOptions[1]);
       $folderid = $project->GetFolderIdByName($pathOptions[2]);
       $folder = Folder::getFolder($folderid);
       
       if ($folder->addItem | $user->superadmin) {
	  if ($user->superadmin) {
	    $metrics->recordMetric("DAV PUT","write permission granted as SUPERADMIN");
	  }
	  else{ 
	    $metrics->recordMetric("DAV PUT","write permission granted as normal");
	  }
	  /*
          $existenceCheck = $this->GetDocumentByName($pathOptions[3],$folderid);
          if ($existenceCheck != null && $existenceCheck->documentuid!=-1) {
            //item already exists
            $metrics->recordMetric("DAV PUT","failed","DAV PUT FAILED");
            return "409 Conflict";
          }
	  */
          $metrics->recordMetric("DAV PUT","running at p1");
          $options["new"] = true;
          //$contentStream = $options["stream"];
          //string fread  ( resource $handle  , int $length  )
          $metrics->recordMetric("DAV PUT","content length",print_r($options["content_length"],true));
          $bytelength = $options["content_length"] * 8;
          //$newContent = fread($contentStream,$options["content_length"]);//$option["content_length"]
	  $newContent="";
          //$metrics->recordMetric("DAV PUT","Errcheck 0",print_r(error_get_last(),true));
          //$stream = $options["stream"];
          //rewind($stream);
          //$newContent = fread($stream,$options["content_length"]); 
          //$newContent = stream_get_contents($stream);
	  $iter = 0;
          while (!feof($options["stream"])) {
            $newContent.=fread($options["stream"],4096);
	    $metrics->recordMetric("Reading stream",$iter);
	    $iter+=1;
          }
	  fclose($options['stream']);
          //$metrics->recordMetric("DAV PUT","Errcheck 1",print_r(error_get_last(),true));
          $metrics->recordMetric("DAV PUT","(RAW) content",urlencode($newContent));
          /*
          if ($newContent===false) {
            $metrics->recordMetric("DAV PUT","content","failed to read");
          }
          else {
          //$altContent = $HTTP_RAW_POST_DATA;
          //$metrics->recordMetric("DAV PUT alt content",$altContent);
            $metrics->recordMetric("DAV PUT","content",'fakecontent');
          }*/
          $metrics->recordMetric("DAV PUT","running at p1.1",'');
          $newItem = new Document();
          $metrics->recordMetric("DAV PUT","running at p2",print_r($newItem,true));
          $newItem->filename=$pathOptions[3];
          $metrics->recordMetric("DAV PUT","running at p3");
          $newItem->content=$newContent;
          $metrics->recordMetric("DAV PUT","running at p4");
          $newItem->contenttype="text/html";//$options["content_type"];//"text/html";
          $metrics->recordMetric("DAV PUT","running at p5");
          $newItem->folderid = $folderid;
          $metrics->recordMetric("DAV PUT","running at p6",print_r($newItem,true));
          $newItem->Save();
          $metrics->recordMetric("DAV PUT","running at p7",print_r($newItem,true));
          
          return true;
       }
       else {
	$metrics->recordMetric("DAV PUT","Write permissions not available for user",$print_r($user,true));
        return false;
       }
    }
    else {
      $metrics->recordMetric("DAV PUT","invalid path",$path);
      //you can't add to other paths.
      return false;
    }
  }
  
  
  function sanitise($input){
    $input=str_replace(" ","\ ",$input);
    
    //$input= str_replace("#","",$input);
    return $input;
  }
  function desanitise($input){
    $input=str_replace("\ "," ",$input);
    //$input = stripslashes($input);
    //$input= str_replace("#","",$input);
    return $input;
  }
  function GetDocumentByName($documentname,$folderid){
		  global $database;
		  $user=$this->user;
		  //return $documentname;
		 // trace("Document::GetDocumentByName($documentname)");
		  /*
		  if ($DocumentId == -1 ){
  			trace('Document::GetDocument()>Returning Blank document');
	 		  return new Document();
		  }
		  */
		  $sql = sprintf(
				'SELECT d.folderid, documentuid, filename, d.icon,contenttype, recipient, sender, hidden,timestamp ' .
				'FROM documents d '.
				'WHERE filename = "%s" AND folderid= %s ',
				$documentname,
				$folderid
				);//,
				//($_SESSION[USER]->superadmin==ALLOW)?'':'AND hidden = 0'
			//);
		//trace ($sql);
		//print ($sql);
		$results = $database->queryAssoc($sql);
		$item = null;
		if (count($results) >0){
			$item = new Document($results[0]);
		}
		else {
		  return null;
		}
  }
}

$errors= array();
function error_handler($errno,$errstring,$errfile,$errline,$errcontext){
    $errorInfo = array();
    $errorInfo['errorno']=$errno;
    $errorInfo['errorstring']=$errstring;
    $errorInfo['file']=$errfile;
    $errorInfo['line']=$errline;
    $errorInfo['context']=$errcontext;
}
  //set_error_handler(error_handler,E_ALL); 
?>