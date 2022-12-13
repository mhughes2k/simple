<div id="header">
<strong>Project Template Plugins</strong>
</div>
<div>
	<p>This page allows you to configure the plugins for the project template <strong>{$projectTemplate->name}</strong>.</p>
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
	<a href="index.php?option=projectTemplateAdmin&cmd=addProjectTemplatePluginpage&pluginId={$plugin.plugin->pluginName}&projectTemplateId={$projectTemplateId}">
		override site defaults<img src="{$config.plugin_icon_edit}"></a>
	{elseif $plugin.range=='template'}
	<a href="index.php?option=projectTemplateAdmin&cmd=clearPlugin&pluginId={$plugin.plugin->pluginName}&projectTemplateId={$projectTemplateId}">
		revert to defaults<img src="{$config.plugin_icon_revert}"></a> | 
	<a href="index.php?option=projectTemplateAdmin&cmd=editProjectTemplatePluginPage&pluginId={$plugin.plugin->pluginName}&projectTemplateId={$projectTemplateId}">
		edit<img src="{$config.plugin_icon_edit}"></a>
	{/if}
	</td>
	</tr>
			
{/foreach}
</table>
</div>