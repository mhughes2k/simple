<div class="areaTitle">Manage simulation
</div>
<div class="manageSectionContent">	{include file="AddUserSimulationDialog.tpl"}
  <div class="sectionBox_manage" id="projectInfo">	
    <div class="sectionTitle">Simulation Information 
      <a href="index.php?option=projectAdmin&cmd=editproject&id={$project->id}" class="toggleButton">Edit</a><br />
    </div>	
    <span class="sectionItemTitle">Unique Simulation ID:
    </span>{$project->id}
    <br/>	
    <span class="sectionItemTitle">Simulation Name:
    </span>{$project->Name}
    <br/>	
    <span class="sectionItemTitle">Blueprint:
    </span>		{if ($tAdmin) } 			
    <a href="index.php?option=projecttemplateadmin&cmd=viewProjectTemplate&projectTemplateId={$projecttemplate->id}">		{/if}		{if $projecttemplate->Name ==''}			Blueprint {$projecttemplate->id}		{else}			{$projecttemplate->Name}		{/if}		{if ($tAdmin) } 			</a>		{/if}
    <br/>{if (($currentUser->projectPermissions[$projectId].EditPlugin==ALLOW) ||	($currentUser->superadmin!=ALLOW)) } 	
    <span class="sectionItemTitle">Plugins:
    </span> 
    <a href="index.php?option=projectAdmin&cmd=listplugins&projectId={$project->id}"> View</a>
    <a href="index.php?option=projectAdmin&cmd=listplugins&projectId={$project->id}">
      <img src="{$config.plugin_icon}"></a>{/if}
  </div>{include file="characterList.tpl"}
{assign var='variableMessage' value='These are the variables and values for this project.'}
{include file="variableList.tpl"}
{include file="projectUserList.php.tpl"}
  <div class="sectionBox_manage">	
    <div class="sectionTitle">Narrative Event Diagram
    </div>  {include file="ned.tpl"}
  </div>
  
  {$simulationManagementPageExtensions}
</div>