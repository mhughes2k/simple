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
 * Core Map Module
 * 
 * @package TLE2
 * @subpackage Core
 */
 if (!defined('TLE2')) die ('Invalid Entry Point');
  
  include_once('include/SimplePie1.0.1/simplepie.inc');
  include_once('include/SimplePie1.0.1/simplepie_file_strath.inc');
	$page = new Page('Homepage.php.tpl');
	$page->assign('contents', 'Home page');
	
	$newsError = true;
	$newsHtml ='';
	$feeds = array();
	$defaultfeeds= array();
	if (is_array($config['newsfeeds'])) {
		$defaultfeeds = $config['newsfeeds'];
	}else {
		$defaultfeeds = explode('|',$config['newsfeeds']);
	}
	if ($sessionUser!== false && $sessionUser->IsAuthenticated()){
		/*
		 * Authenticated user feeds!
		 * Authenticated user feeds are displayed before the unauthenticated 
		 * user feeds
		 */
		if (!is_null($project)) {
			$pt = $project->GetProjectTemplate($project->id);
			if (isset($pt->Properties['newsfeeds'])){
			 //print($pt->Properties['newsfeeds']);
		  	$newsfeedsSetting = $pt->Properties['newsfeeds'];
	   		if(!is_null($newsfeedsSetting) && $newsfeedsSetting != ''){
  				$feeds = explode(',',$newsfeedsSetting);
			 }
			}
		}
		//if we have no feeds, then display the public feed.
	}
	/*
	 * Merge the Authenticated and UnAuthed feeds together.
	 */
	$feeds  = array_merge($feeds,$defaultfeeds);
	//$sp_final = new SimplePie();
	
  $sp_final = new SimplePie();
  $sp_final->set_feed_url($feeds);
  $sp_final->set_file_class("SimplePie_File_Strath");
    
  $result = $sp_final->init();
  $items = $sp_final->get_items();
  $newsHtml='';
  if (count($items)>0) {
  foreach($items as $item) {
    $newsHtml .= "<div class='newsitem'>";
    $newsHtml .= "<div class='newsitem_header'>";
    $newsHtml .= "<a href='".$item->get_permalink()."'>".$item->get_title().'</a>';
    $newsHtml .= " (<a href='".$item->get_feed()->get_permalink()."'>". $item->get_feed()->get_title() .'</a>)';
    $newsHtml .= "</div>";
    $newsHtml .= "<div class='newsitem_body'>";
    $newsHtml .= $item->get_description();
    $newsHtml .= "</div>";
    $newsHtml .= "</div>";
  }
  
  $newsError=false;
	if ($newsError){
		$page->assign('news','News is currently unavailable');
	}else {	
		$page->assign('news',$newsHtml);
	}
    }
    else {
      $page->assign('showNews',false);
    }
    $news_results_post= $_PLUGINS->trigger('extendNewsAfter',array()); 
		
    $news_results_post_ext='';
    foreach($news_results_post as $result){
      //echo($result);
      $news_results_post_ext.=$result;
    }	
    $page->assign('post_news_extensions',$news_results_post_ext);	
 ?>
