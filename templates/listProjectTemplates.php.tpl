	{include file="ProjectTemplateDeleteUi.tpl"}
<div class="areaTitle">
	{$strings.MSG_MANAGE_PT}
</div>
<div class="manageSectionContent">
<div id="dialogBlock" >
&nbsp;
</div>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Projects</a></li>
		<li><a href="#tabs-2">Install Project</a></li>
	</ul>
	<div id="tabs-1">
			
	<table style="width:100%;">
	<tr>
		<th>Project Name</th>
		<th>Contains Blueprints</th>
		<!--<th>Create Instance</th>-->
		<th>Delete Project</th>
	</tr>
	{foreach from=$containersArray item=container}
	{if $container.deleted==0}
	<tr>
		<td>{$container.name}</td>
		<td>
		{foreach from=$container.blueprints item=bp}
			<a href="?option=projectTemplateAdmin&cmd=viewProjectTemplate&projectTemplateId={$bp->id}">{$bp->Name}</a> (<a href="?option=projectTemplateAdmin&cmd=createProject&projectTemplateId={$bp->id}">create</a>)<br/>
		{/foreach}	
		</td>
		<!-- <td><a href="?option=projectTemplateAdmin&cmd=createContainerInstance&containerId={$container.containerid}">Create Instance</a></td> -->
		<td><a href="Javascript:deleteProject({$container.containerid});">Delete Project</a></td>
	</tr>
	{/if}
	{/foreach}
	</table>

	</div>
	<div id="tabs-2">
	{if $zipinstalled==0}
		<p><strong>{$strings.MSG_PROJECT_INSTALL_NOZIP}</strong></p>
	{/if}	
	<p>{$projectError} {$strings.MSG_PROJECT_INSTALL_PROMPT}</p>
	<form action="index.php?option=projectTemplateAdmin&cmd=AddTemplate" enctype="multipart/form-data" method="post">
	<input type="file" name="bpofile">
	<input type="submit" value="{$strings.MSG_INSTALL_TERM}">
	</form>
	</div>
</div>
</div>