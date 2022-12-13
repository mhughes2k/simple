<div class="areaTitle" id="header">
<strong>Simulation Plugins</strong>
</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
	<p>This page allows you to configure the plugins for the simulation <strong>{$project->name}</strong>.</p>
    <table id="plugintable">
    <tr><th>Name</th><th>Permission Level</th><th>Enabled</th><th></th></tr>

{foreach from=$plugins key=key item=plugin}

	<tr> 
	<td>{$plugin.plugin->pluginName}</td> 
	<td>{$plugin.range}</td>
	<td>
	{if $plugin.plugin->enabled==1}
		enabled<img src="{$config.system_icon_enabled}">
	{else}
		disabled<img src="{$config.system_icon_disabled}">
	{/if}
	</td>
	<td>
	{if $plugin.range=='sitewide'}
	<a href="index.php?option=projectAdmin&cmd=addProjectPluginpage&pluginId={$plugin.plugin->pluginName}&projectId={$projectId}">
		override site defaults<img src="{$config.plugin_icon_edit}"></a>
	{elseif $plugin.range=='template'}
	<a href="index.php?option=projectAdmin&cmd=addProjectPluginPage&pluginId={$plugin.plugin->pluginName}&projectId={$projectId}">
		override template defaults<img src="{$config.plugin_icon_edit}"></a>
	{elseif $plugin.range=='project'}
	<a href="index.php?option=projectAdmin&cmd=clearPlugin&pluginId={$plugin.plugin->pluginName}&projectId={$projectId}">
		revert to defaults<img src="{$config.plugin_icon_revert}"></a> | 
	<a href="index.php?option=projectAdmin&cmd=editProjectPluginPage&pluginId={$plugin.plugin->pluginName}&projectId={$projectId}">
		edit<img src="{$config.plugin_icon_edit}"></a>
	{/if}
	</td>
	</tr>
			
{/foreach}
</table>
</div>
</div>