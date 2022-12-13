{assign var='option_target' value='projectadmin'}
<div class="areaTitle">Manage simulation</div>
<div class="manageSectionContent">	
<div class="sectionBox_manage">
	<form action="index.php" method="post" name="DoSaveProject">
<div class="sectionTitle">Simulation Information <input type="submit" value="Save"/><a href="index.php?option=projectAdmin&cmd=viewproject&projectId={$project->id}" class="toggleButton">Cancel</a><br /></div>

		<input type="hidden" name="option" value="projectAdmin">
		<input type="hidden" name="cmd" value="dosaveproject">
		<input type="hidden" name="id" value="{$project->id}">		
		<span class="sectionItemTitle">Simulation Name:</span><input type="text" name="project_name" value="{$project->Name}"/></strong><br/>
		<span class="sectionItemTitle">Blueprint:</span>
		{$tAdmin}
		{if $tAdmin}
		<a href="index.php?option=projecttemplateadmin&cmd=viewProjectTemplate&projectTemplateId={$projecttemplate->projecttemplateuid}">
		{/if}
		{$projecttemplate->Name}
		{if $tAdmin}
		</a>
		{/if}
		<br/>

{if (($currentUser->projectPermissions[$projectId].EditPlugin==ALLOW) ||
	($currentUser->superadmin!=ALLOW)) }
	<span class="sectionItemTitle">Plugins:</span> <a href="index.php?option=projectAdmin&cmd=listplugins&projectId={$project->id}">
View</a><a href="index.php?option=projectAdmin&cmd=listplugins&projectId={$project->id}"><img src="{$config.plugin_icon}"></a>
{/if}
	<div class="sectionBox">
	<div class="sectionTitle">Administer</div>
	<A href="index.php?option=projectadmin&cmd=doarchiveproject&id={$project->id}">Archive Simulation</a> | 
	<A href="index.php?option=projectadmin&cmd=dodeleteproject&id={$project->id}">Delete Simulation</a>
	<p>
	<strong>Note:</strong> Deleting a simulation will make it unavailable in the future! If you wish to leave the Simulation available use "archive". 
	This will only remove the simulations from the list of active simulations for every user.
	</p>
	</div>

</div>
	</form>
{include file="characterList.tpl"}

{assign var='variablelist_Message' value='These are the variables and values for this simulation.'}
<!-- EV:{$editVariables|string_format:"%d"} -->
{if $editVariables}

	{$deleteDialog_optionValue}
	{include file="deleteVariableDialog.tpl"}
{/if}
{assign var='deleteDialog_optionValue' value='projectAdmin'}
{include file="variableList.tpl"}

{assign var='ProjectUserList_AllowPermissionChange' value='true'}
{include file="projectUserList.php.tpl"}
</div>