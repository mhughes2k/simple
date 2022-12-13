<div class="areaTitle">manage blueprints</div>
<div class="manageSectionContent">	
<div class="sectionBox_manage">
	<div class="sectionTitle">Blueprint Information</div>
	Blueprint ID:<strong>{$pid}</strong><br/>
	Blueprint Name:<strong>{$project->Name}</strong><br />
	{if (($currentUser->projectTemplatePermissions[$projectTemplateId].EditPlugin==ALLOW) ||
		($currentUser->superadmin==ALLOW)) }
	Project Template Plugins:<a href="index.php?option=projectTemplateAdmin&cmd=listplugins&projectTemplateId={$project->id}">view</a>
	view<img src="{$config.plugin_icon}"></a>
	{/if}
</div>
	{include file="characterRoleList.tpl"}
	
	{assign var='variablelist_Message' value='These are the variables and values for this project.'}
	{assign var='variablelist_Message2' value='Values starting with a # are calculated when substitutions are made.'}
	

	{$deleteDialog_optionValue}
	{include file="deleteVariableDialog.tpl"}

	{include file="variableList.tpl"}
	
	{include file="documentTemplates.tpl"}
	
<div class="sectionBox_manage">
	 <div class="sectionTitle">Narrative Event Diagram</div>	
	{include file="ned.tpl"}
</div>
</div>
