<div id="header">
<strong>Project Plugins</strong>
</div>
<div>
    <form name="projecttemplatepluginform" action="index.php?option=projectTemplateAdmin&cmd=updateProjectTemplatePlugin" method="post">
    <ul>
		<li>Plugin Name: {$plugin->pluginName}</li>
 		<li>Plugin File: {$plugin->pluginFile}</li>
 		<li>Order: {$plugin->order}</li>
		<li>Enabled: 
		<select name="enabled">
			<option value="1" {if ($plugin->enabled==1) } selected="selected" {/if}>enabled</option>
			<option value="0" {if ($plugin->enabled==0) } selected="selected" {/if}>disabled</option>
		</select>
		</li>
		<li>
		<input type="hidden" name="method" value="{$method}" />
		<input type="hidden" name="pluginId" value="{$plugin->pluginName}" />
		<input type="hidden" name="projectTemplateId" value="{$projectTemplateId}" />
		<input type="submit" Value="override" name="submit" /></li>
	</ul>
	<a href="index.php?option=projectTemplateAdmin&cmd=listPlugins&projectTemplateId={$projectTemplateId}">Back to List</a>
	</form>
</div>