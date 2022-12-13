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
 * Core Directory Module
 * @author Michael Hughes
 * 
 * @package TLE2
 * @subpackage Core
 */
 if (!defined("TLE2")) die ("Invalid Entry Point");
 	trace( "Directory for ".$projectId );
	if (is_null($page)) {
		$page = new Page("Directory.php.tpl");
	}
	
	$page->Title="Directory Listing";
	//echo "Option:$option<br>";
	//echo "Command:$command<br>";
  	switch (strtolower($command))  {
		case 'xml':
			DumpXml();
			break;
		case 'viewitem':
			ViewItem();
			break;
		case 'view':
		default:
			View();
	}
	
	/**
	* Displays the directory.
	* 
	* Displays the directory for the current project.
	*/
	function View() {
		global $page,$database,$project,$strings,$_PLUGINS;
		//echo 'View Directory';
		$page->Template='Directory.php.tpl';
		if (!is_null($project)){
			$projectId = $project->id;
			$items = DirectoryItem::GetDirectoryItems($projectId);
			$vb=$project->GetVariabliser();
			foreach($items as $item){
				
			//echo '<p>';
				//print_r($item);
				$item->name=$vb->Substitute($item->name);
				$item->address=$vb->Substitute($item->address);
				$item->location=$vb->Substitute($item->location);
				$item->directoryvisible=$vb->Substitute($item->directoryvisible);
				$results =$_PLUGINS->trigger('displayDirectoryItem',array($item));
				$ext ='';
				foreach($results as $r){
          $ext.=$r;
        }
        $item->extension=$ext;
			//echo '</p>';	
			}
			$page->assign('projectId',$projectId);
			$page->assign('directoryEntries',$items);
			//print_r($items);
		}
		else {
			Redirect('?option=message&cmd='.$strings['MSG_NO_PROJECT_SELECTED']);
		}
//		dumpArray($items);
	}
	/**
	 * Displays a resource.
	 * 
	 * This kinda replicates the 'download' option...but is a bit better implemented
	 * and geared up towards displaying any format of file. Also download provides and 
	 * actual file download, this just tries to display in browser :-).
	 */
	 function ViewItem() {
		global $wrapper,$page,$project,$strings,$config; 	
		$page= null;
		//echo 'ViewItem';
		
		if (is_null($project)){
			Redirect('?option=message&cmd='.$strings['MSG_NO_PROJECT_SELECTED']);
			return;
		}
		$wrapper->Template='blank.tpl';
		$projectId = GetParam('pid',-1);
		$resourceId = GetParam('id',-1);
		$content='Not Set';
		//echo "resid:$resourceId";
		if ($resourceId != -1) {
			//echo 'displaying resource';
			$pt = $project->GetProjectTemplate();
			$docTemplateId = $pt->GetDocumentTemplateIdFromDocumentId($resourceId);
			if (($docTemplateId)<0) {
        		echo('DIT is null');
      		}
      		redirect($config['home']."index.php?option=download&docuid=$docTemplateId&download=0&docType=doc_templ&pid=$projectId");
      		return;
      /*
			//echo($docTemplateId);
			//$content = $docTemplate->contenttype;
			//echo 'ctype:'.$docTemplate['contenttype'];
			//print_r($docTemplate);
			$docTemplate = ProjectTemplate::getFullDocumentTemplate($docTemplateId);
			//echo $docTemplate['contenttype'];
			//print_r($docTemplate);
			if (strtolower($docTemplate['contenttype'] != 'text/html')) {
					header('Content-Type:'.$docTemplate['contenttype']);
					header("Content-Disposition: filename=".$docTemplate['filename']);
					//header('Content-Length:');
					$content=($docTemplate['content']);
					//$content = $docTemplate->contenttype;
			}
			else {
				$vb = new Variabliser($project,$project->Variables);
				//echo "Content:".$docTemplate['content'];
				$content = $vb->Substitute(($docTemplate['content']));			}
				*/
		} else {
			//echo 'Page is not available';
			$content='Page is not available';	
		}
		$wrapper->assign('contents',$content);
	 }
	 /**
	  * Generates XML output for rendering in the Map plugin.
	  * 
	  * There's a bit of a hack in Index.php@L341 to allow us to dump
	  * xml straight out.
	  * @todo Implement
	  */
	 function DumpXml(){
	 	global $page,$database,$project,$strings,$config;
		//echo 'View Directory';
		$page->Template=null;
		header('Content-type:text/xml');
		if (!is_null($project)){
			$projectId = $project->id;
			$items = DirectoryItem::GetVrItems($projectId);
			$template = $project->GetProjectTemplate();
			$TemplateMapResourceId = $template->Properties['mapResourceId'];
			$query = "SELECT doctemplateuid FROM documenttemplates WHERE documentid = '$TemplateMapResourceId' AND projecttemplateid =".$template->id;
			$result=$database->query($query);
			$mapResourceId =$result;
			//print ("Map ResourceID:$mapResourceId");
			$vb=$project->GetVariabliser();
			echo '<?xml version="1.0"?>';
			//echo '<map mapPath="'.$config['home'].'index.php?option=directory&amp;cmd=viewitem&amp;id='. $mapResourceId.'&amp;docType=doc_templ">';
			echo '<map mapPath="'.$config['home'].'index.php?option=directory&amp;cmd=viewitem&amp;id='. $TemplateMapResourceId.'">';
			//echo '<items>';
			foreach($items as $item){
				
			/*
			 * this controls whether we even bother to dump the item to XMl feed for the 
			 * map
			 */
				$item->directoryvisible=$vb->Substitute($item->directoryvisible);
        $x = isset($item->Properties['x'])?$vb->Substitute($item->Properties['x']):'';
        $y = isset($item->Properties['y'])?$vb->Substitute($item->Properties['y']):'';

				$hasCoords =$x!='' & $y !=''; 

				if ($item->vrvisible & $hasCoords){
					$name=$vb->Substitute($item->name);
					$name = str_replace("&","&amp;",$name);
					$address=$vb->Substitute($item->address);
					$location=$vb->Substitute($item->location);
          $description = $vb->Substitute(isset($item->Properties['description'])?$item->Properties['description']:'');
          
          $name = $name !=''?$name:'';
          $address = $address !=''?$address:'';
          $location = $location !=''?$location:'';
          $x = $x !='' ?$x:'';
          $y = $y !='' ?$y:'';	
          $description = $description !=''?$description:'';
          if (isset($item->Properties['smallIcon']) && !is_null($item->Properties['smallIcon']) & $item->Properties['smallIcon'] !=''){
            $smallIcon = $item->Properties['smallIcon'];
          }					
          else {
            $smallIcon = '';
          }
					if (isset($item->Properties['infolink']) && !is_null($item->Properties['infolink']) & $item->Properties['infolink']!=''){
            $ilink =$item->Properties['infolink'];
          }
          else {
            $ilink = 'None';          
          }
                    
					echo '<entry id="'.$item->ID.'">';
					echo '<name>'.$name.'</name>';
					echo '<address>'.$location.'</address>';
					echo '<email>'.$address.'</email>';
					echo '<properties>';
					echo '<property name="x">'.$x.'</property>';
					echo '<property name="y">'.$y.'</property>';
					echo '<property name="z">0</property>';
					echo '<property name="description">'.$description.'</property>';

          echo '<property name="smallIcon">'.$smallIcon.'</property>';

					echo '<property name="infolink">'.$ilink.'</property>';
					/*
          foreach($item->Properties as $name=>$value) {
						switch(strtolower($name)){
						  case 'description':
              case 'x':
						  case 'y':
						  case 'z':
						  case 'smallicon':
						  case 'infolink': 
						    break;
              default:
                echo '<property name="'.$name.'">'.$value.'</property>';
                break;
					}
					*/
					echo '</properties>';
					echo '</entry>';	
				}
			}
			//echo '</items>';
			echo '</map>';
		exit();
		}
		else {
			Redirect('?option=message&cmd='.$strings['MSG_NO_PROJECT_SELECTED']);
		}
//		dumpArray($items);
	 }
 ?>
