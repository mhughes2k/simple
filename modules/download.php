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
 * Implements functionality to download a document from the database.
 */
error_reporting(E_ALL);
//error_reporting(0);
$user= $_SESSION[USER];
require_once('include/MimeTypes.php');
$doctype= GetParam('docType', 'doc');
$docuid = GetParam("docuid",null);
$download = GetParam('download',1);
$format = GetParam('format','');
$projectId = GetParam('pid',null);
	
/* functions */
	function formatAsSource($content,$contenttype) {
		global $header,$download,$name;	 
		switch(strtolower($contenttype)){
			case 'url':
				redirectAsUrl($content);
				exit();
				break;
			default:
				setContentType($contenttype);
				$ext = '.'.GetMimeExt($contenttype);
				//setDisposition($download,$name);
				setDisposition($download,$name.$ext);
				break;
		}		
		return $content;
	}
	function redirectAsUrl($content) {
		$url = stripslashes($content);
		$url = str_replace("'","",$url);
		Redirect($url);
	}
	function formatWord($content) {
		global $header,$name,$download;
		setDisposition($download,$name.'.doc');
		setContentType("application/msword");
		return $content;
	}
	function formatRtf($content){
		global $header,$name,$download;
		setDisposition($download,$name.'.rtf');
		setContentType("application/rtf");
		return $content;	
	}
	function formatAsInfoPath($content) {
		global $header,$name,$download;
		setDisposition(false,$name);
		setContentType("application/ms-infopath.xml");
    }
	function setContentType($contenttype) {
		global $header;
		$header['Content-Type']="$contenttype";
	}
	function setDisposition($download,$filename) {
		global $header;
		if ($download){
			$header['Content-Disposition'] = "attachment;filename=$filename";
		} else {
			$header['Content-Disposition'] = "inline;filename=$filename";
		}	
	}
	function formatPdf($content){
		global $header,$name,$download,$metrics;
		$domPdfInstalled=include_once('include/dompdf-0.5.1/dompdf_config.inc.php');
		if($domPdfInstalled) {
			$dompdf = new DOMPDF();
			$dompdf->load_html($content);
			$dompdf->render();
			if($download == 0){
				$dompdf->stream($name.'.pdf',array('Attachment'=>0));
			} else {
				$dompdf->stream($name.'.pdf',array('Attachment'=>1));
			}
		} else {
			$metrics->recordMetric('PDFGenerationFailed');
			displayMessage('Unable to generate PDF. DomPDF 0.5.1 Library not installed in "include/dompdf-0.5.1/"');
			exit();
		}
	}
	/*end functions*/
	
	if (is_null($docuid)){
		$wrapper->Template="blank.tpl";
		$wrapper->assign("contents", "Unable to prepare file for download");
	} else {
		//$wrapper->Template("blank.tpl");
		$ctype ="";
		$name = "";
		$content= "";
		switch ($doctype) {
			case DOC_TYPE_DOC:
				//$item= Document::getDocument($documentUid);
				$doc = Document::GetDocument($docuid);
				//print_r($doc);
				$doc->MarkRead();
				$name = $doc->filename;
				//print_r($doc);
				$doc->GetContent();
				$content =$doc->content;
				//print_r($doc);
				$ctype = $doc->contenttype;
				$oldErrorLevel = error_reporting(0);
			
				// edited out next statement as was interpreting html output in openoffice as xml/infopath
				if ((simplexml_load_string($doc->content)!== false) && ($ctype!="text/html")) {
					//xml file
					$format='application/ms-infopath.xml';
				}
				error_reporting($oldErrorLevel);
				if (strtolower($ctype)=='application/xml') {
					//formatAsInfoPath($content);
					//die('xml data');
					$format='application/ms-infopath.xml';
				}
				break;
			case DOC_TYPE_TEMPLATE:
			  $doc=array();
			  if (!is_null($projectId)){
				$project = Project::GetProject($projectId);
			  }
			  if(strpos($docuid,"-")!==false) {
				$pt = $project->GetProjectTemplate();
				$docuid= $pt->GetDocumentTemplateIdFromDocumentId($docuid);
			  }
			$doc = ProjectTemplate::getFullDocumentTemplate($docuid);
			$ctype= $doc['contenttype'];
			$name = $doc['filename'];
			$content = base64_decode($doc['content']);

         if(!is_null($project) & 
         (
           $ctype=="text/html" | 
           $ctype=='application/vnd.google-earth.kml+xml' | 
           $ctype=='url' |
           $ctype=='application/xml'|
           $ctype=='application/ms-infopath.xml'
         )
         ){
            $vb = $project->GetVariabliser();
            $content = $vb->Substitute($content);
          }

          //print($content);
        //}
	break;
	     default:
	       die('Invalid document type to display');
	       break;
		}
		
		$size = "";
		$header = array();
		
		$name = str_replace(" ","_",$name);
		
		switch(strtolower($format)){
			case 'word':
				$content = formatWord($content);
				break;
			case 'pdf':
				if ($ctype =="text/html"){
					formatPdf($content);
				}
				else {
				  DisplayMessage('Sorry cannot convert that file to PDF format.');
				  exit();
				}
				break;
			/*case 'gif':
				echo $content;
				break;
				*/
				
			case 'application/ms-infopath.xml':
			//die('formatting as infopath');
			   formatAsInfoPath($content);
			break;
			default:
				$content = formatAsSource($content,$ctype);
		}
		/*
		header('Pragma:public');
		header('Cache-Control:max-age=0');
		*/

		foreach($header as $k=>$v){
			header($k.':'.$v);
			//echo "$k:$v<br/>";
		}
		
		$metrics->recordMetric('Download size:'. strlen($content));
        $R = $_PLUGINS->trigger('onViewDocument',array($docuid));
        if (!PluginHandler::PluginResultIsOk($R)) {
            die('Handled');
        }
        else {
            echo $content;
			//print_r($header);
        }
		exit;
		
	}
?>