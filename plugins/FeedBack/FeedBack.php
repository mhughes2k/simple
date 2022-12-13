<?php
/**
 * Implements a replacement document viewer that displays a file with an assessment from in a side-by-side fashion
 */
 
 $_PLUGINS->registerFunction('onViewDocument','FeedBack','DisplayFeedbackUi');

/**
 * This function intercepts the onViewDocument event and displays the document being viewed within a 
 */
	function DisplayFeedbackUi($documentUniqueId) {
		global $project,$config;
        require_once($config['installDir'].'include/BrowserCapability.class.php');
		$browser = BrowserCap::get_browser();
        $displayInline =  strtolower($browser['platform']) =='linux'?false:true;

//print_r($browser);
//die();
		$disablefeedbackoverlay = GetParam('disablefeedbackoverlay',false);
//	return false; 
	
		$currentUser= GetSessionUser();
		$projectPermissions = $currentUser->GetProjectPermissions($project->id);
		if (
			$projectPermissions['UseStaffTools'] == true
			&&
			$disablefeedbackoverlay != true
		) {
			//return true;
			//print_r($currentUser);
			$page = new Page('FeedBackIndex.tpl');
			$page->Title="Feedback provider";
			
			
			//print_r($page);
			//echo ('intercepting display feedback ui');
            if ($displayInline) {
			    $documentDisplayUrl = "{$config['home']}index.php?option=download&docuid=$documentUniqueId&download=0&disablefeedbackoverlay=true";
            }
            else {
                $documentDisplayUrl = "{$config['home']}index.php?option=download&docuid=$documentUniqueId&download=1&disablefeedbackoverlay=true";
            }
			
			$doc = Document::GetDocument($documentUniqueId);
			$page->assign('documentTitle',$doc->filename);
			$page->assign('folderName',$doc->folderName);
			$page->assign('projectName',$project->Name);
            $page->assign('displayinline',$displayInline);
			/*//print_r($doc);*/
//            if ($displayInline) {
//			$content =$doc->GetContent();
			//$page->assign('documentContent',$content);
			$page->assign('projectid',$project->id);
			$page->assign('documentUrl',$documentDisplayUrl);
	//		print_r($page);
	//		print($page->template);
	//		$c=
			$page->display('FeedBackIndex.tpl');
	//		echo $c;
	//		return true;
			
			return false;
		}
		return true;
	}

	$_PLUGINS->registerVerb('feedback','FeedBackHandler');	
	function FeedBackHandler() {
//		die('fb marshalling');
		$cmd = GetParam('cmd','getprojectassessments');
		//die($cmd);
		switch (strtolower($cmd)) {
		    case 'submitformdata':
		      HandleAssessmentFormPost();
		      break;
		    case 'getassessmentformdata':
			EncodeAssessmentFormData();
			break;
		    case 'getprojectassessments':
		    default:
		      GetAssessmentsForCurrentProject();
		}
		return false;
	
	}
	//$_PLUGINS->registerVerb('getprojectassessments','GetAssessmentsForCurrentProject');	
	/**
	 * Returns an JSON encoded array of "assessment" type files containing the unique documentID of the resource and the filename.
	*/
	function GetAssessmentsForCurrentProject() {
		global $project;
        $template = $project->GetProjectTemplate();
        $assessments = $template->getDocumentTemplates(null,false);
        $out =array();
        foreach($assessments as $assessment) {
            if (strpos($assessment['filename'],'.aml')!==false) 
            {
                $out[] = $assessment;
            }
            
        }
//		$assessments = array(array('id'=>1,'name'=>"Will Assessment"),array('id'=>2,'name'=>"Estate Assessment"));
		print json_encode($out);//$assessments);
		die();
	}
	
	/**
	 * Wraps the GetAssessmentFormData method so that it is outputted as JSON encoded data for AJAX calls from the overlay.
	 */
	function EncodeAssessmentFormData() {
	  $formid = GetParam('assessmentid',null);
	  $data = GetAssessmentFormData($formid);
	  print (json_encode($data));
	  exit();
	}
	//$_PLUGINS->registerVerb('getassesmentformdata','GetAssessmentFormData');
	/**
 	 * Retrieves the contents of a assessment resource and parses it into a JSON data structure for the UI to display the forms.
 	 */	
	function GetAssessmentFormData($formid=null) {
		global $project;
        $template = $project->GetProjectTemplate();
        $doc = $template->getFullDocumentTemplate($formid);

if (is_null($doc) or $doc == -1) {
    return json_encode(false);
}
//print_r($doc);
        $assessmentdata = base64_decode($doc['content']);//>GetContent();
        $out = json_decode($assessmentdata);
        //$out = stripslashes($assessmentdata);
//        $out = str_replace('\"','"',$assessmentdata);
//        $$out = str_replace('\n','',$out);
		return $out;
	}
	
	/**
	 * Handles the html form from the feedback overlay and uses it to generate a user-friendly piece of feed back.
	 */

	function HandleAssessmentFormPost() {
	  $questionResponses = array();
	  $aId = null;
	  $content = null;
	  $projectid=null;
      $sender = null;
	  //print('<br />');
	  foreach($_GET as $key=>$input) {
	    //print("$key:$input<br />");
	    switch (strtolower($key)) {
	      case 'projectid':
		    $projectid=$input;
		    break;
	      case 'assessmentid':
		    $aId = $input;
		    break;
          case 'sender':
            $sender = $input;
            break;
	      case 'content':
		    $content = $input;
		    break;
	      default:
    		if (strpos($key,'q_')!== false) {
    		  //we have a question response
    		 // echo ('have qestion');
    		  $qNo = substr($key,2);
    		  //echo($qNo);
    		  $questionResponses[$qNo] = array('question'=>'','response'=>$input);
    		}
	    }
	  }
      
       // print("Project:$projectid Content:$content");
    
//        return true; 
	  //$content =GenerateFeedbackArtifact($aId,$questionResponses,$comments,$aData);
if (true) {
	  $project = Project::GetProject($projectid);
	  $inbox = $project->GetDeliveryFolder();
	  $doc = new Document();
	  $doc->content=$content;
	  $doc->filename="Feedback";
	  $doc->folderid = $inbox;
	  $doc->contenttype='text/html';
	  $doc->recipient = '';
	  $doc->sender= $sender==""?'Feedback system':$sender;
	  $doc->save();
}
print json_encode(true);
//	  DisplayMessage("Feed back should now appear in the appropriate inbox folder for this simulation");
//	  return;
	  //print_r($doc);
	  //die();
exit();
	}
	/**
	 * Generates an HTML page that can be either displayed or put in to a document and saved in to a folder.
	 */
	function GenerateFeedbackArtifact($assessmentid,$questionResponses,$additionalComments,$assessmentData) {
	//  print_r($assessmentData);
		$page = new Page('FeedBackIndex.tpl');
		$page->assign('assessmentName',$assessmentData['name']);
		$page->assign('qrs',$questionResponses);
		$page->assign('comments',$additionalComments);
		$content = $page->fetch('FeedBackTemplate.tpl');
		return $content;
	}
?>
