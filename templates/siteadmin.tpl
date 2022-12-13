<div class="areaTitle">Site Administration</div>
<div class="manageSectionContent">
  <div style="padding-bottom:2px;">

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Content & Customisation</a></li>
		<li><a href="#tabs-2">News Content</a></li>
		<li><a href="#tabs-3">Plugins</a></li>
		<!-- <li><a href="#tabs-4">Garbage Collection</a></li> -->
		<li><a href="#tabs-5">Server Details</a></li>		
	</ul>
	<div id="tabs-1">
	<div id="contentAccordion">
		<h3><a href="#">Update Login Page Content</a></h3>
		<form action="index.php?option=siteAdmin&cmd=setloginpagetext" method="post">
		<textarea name="loginpagetext" cols="35" rows="4">{$loginpagetext}</textarea>
		<input type="submit" name="submit" value="submit"/>
		</form>
		<h3><a href="#">Set Vocabulary</a></h3>
		<div>
		<form action="index.php?option=siteAdmin&cmd=setvocabulary" method="post">
		Group Name: <input name="voc_group_text" value="{$group_name}"/> 
		Plural: <input name="voc_group_text_pl" value="{$group_name_plural}"/><br/>
		Simulation Name: <input type="text" name="voc_sim_text" value="{$simulation_name}"/> 
		Plural: <input type="text" name="voc_sim_text_pl" value="{$simulation_name_plural}"/><br/>
		<input type="submit" name="submit" value="submit"/>
		</form>
		</div>
		<h3><a href="#">Student Help URL</a></h3>
		<form action="index.php?option=siteAdmin&cmd=setHelpUrl" method="post">
		<p>To change where the help link goes, enter the full URL (including http://) below. Leaving this field blank
		will default to the Student Guide supplied with the software.</p>
		URL: <input type="text" name="txt_help_url" value="{$val_help_url}" size="50"/>
		<input type="submit" name="submit" value="submit"/>
		</form>

		<h3><a href="#">Set Language</a></h3>
		<form action="index.php?option=siteAdmin&cmd=setlanguage" method="post">
		<select name="language">
		{foreach from=$languageList key=key item=l}
			<option value="{$l}" {if ($l==$language)} selected="selected" {/if}>{$l}</option>
		{/foreach}
		</select>
		<input type="submit" name="submit" value="submit"/>
		</form>
		<h3><a href="#">Set Theme</a></h3>
		<form action="index.php?option=siteAdmin&cmd=settheme" method="post">
		<select name="theme">
		{foreach from=$themeList key=key item=t}
			<option value="{$t}" {if ($t==$theme)} selected="selected" {/if}>{$t}</option>
		{/foreach}
		</select>
		<input type="submit" name="submit" value="submit"/>
		</form>
		<h3><a href="#">Email Module</a></h3>
		<form action="index.php?option=siteAdmin&cmd=emailmodule" method="post">
		Enablling this module allows users to quickly compose email-style (HTML) communications within SIMPLE. <br/><br/>
		Enable for:<br/>
		<input type="checkbox" name="emailmoduleenablementors" value="1" {if ($emailmodulementors==1)} checked="yes" {/if} >Mentors<br/>
		<input type="checkbox" name="emailmoduleenablelearners" value="1" {if ($emailmodulelearners==1)} checked="yes" {/if}>Learners<br/>
		<input type="submit" name="submit" value="submit"/>
		</form>		
	</div>
	</div>
	<div id="tabs-2">
	<div id="newsAccordion">
	<h3><a href="#">Add New Story</a></h3>
	<form action="index.php?option=siteAdmin&cmd=addnewsstory" method="post">
	Title: <input type="text" name="title" /><br/>
	<textarea name="newstext" cols="35" rows="4"></textarea>
	<input type="submit" name="submit" value="submit"/>
	</form>
	
	{foreach from=$newsstories item=news}
		<h3><a href="#">{$news.title} - {$news.timestamp|date_format}</a></h3>
		<form action="index.php?option=siteAdmin&cmd=editnewsstory" method="post">
		<a href="?option=siteAdmin&cmd=deletenewsstory&id={$news.id}">[delete story]</a><br/><br/>
		Title: <input type="text" name="title" value="{$news.title}"/><br/>
		<textarea name="newstext" cols="35" rows="4">{$news.text}</textarea>
		<input type="hidden" name="newsid" value="{$news.id}"/><br/>
		<input type="submit" name="submit" value="submit"/> 
		</form>
	{/foreach}
	
	</div>
	</div>
	<div id="tabs-3">
    {include file="listPlugins.php.tpl"}
	</div>
	<!--
	<div id="tabs-4">
      <p>The SIMPLE Platform does not execute DELETE statements when deleting database items in order to 
      maintain a faster response. Items which are deleted have a <c>deleted</c> flag set.</p>
      <p>You should run the Garbage collection periodically to clear up these "deleted' items and 
      recover the space.</p>
      <div style='text-align:center'>
      <A href='index.php?option=siteAdmin&cmd=collectgarbage'>Start Garbage Collection</a>
	  </div>	
	</div -->
	<div id="tabs-5">
      <p><ul>
			<li>{$strings.MSG_SIMPLE_VERSION}: {$smarty.const.SIMPLE_VERSION}</li>
			<li>PHP version: {$phpversion}</li>
			<li>Server: {$serversoftware} </li>
		</ul>
	  </p>
	</div>
</div>

   </div>
</div>