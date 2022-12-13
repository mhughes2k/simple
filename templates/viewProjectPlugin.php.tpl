<div class="areaTitle">
Project Plugins
</div>
<div class="sectionBox">
    <form name="projectpluginform" action="index.php?option=projectAdmin&cmd=updateProjectPlugin" method="post">
    <div>
		<div style="position:relative;top:0px;height:20px;width:190px;">Plugin Name:</div>    
		<div style="position:relative;top:-20px;left:200px;height:20px">
		{$plugin->pluginName|default:''}</div>
 		<div style="position:relative;top:0px;height:20px;width:190px;">Plugin File:</div>
 		<div style="position:relative;top:-20px;left:200px;height:20px">
 		{$plugin->pluginFile|default:''}</div>
 		<div style="position:relative;top:0px;height:20px;width:190px;">Order:</div>
 		<div style="position:relative;top:-20px;left:200px;height:20px">
 		{$plugin->order|default:''}</div>
		<div style="position:relative;top:0px;height:20px;width:190px;">Enabled:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
		{if $method=='edit'}
			<select name="enabled">
				<option value="1" {if ($plugin->enabled==1) } selected="selected" {/if}>enabled</option>
				<option value="0" {if ($plugin->enabled==0) } selected="selected" {/if}>disabled</option>
			</select>
		{else}
			{if ($plugin->enabled==1) }
				yes
			{else}
				no
			{/if}
		{/if}
		</div>
		<div style="position:relative;top:0px;height:20px;width:190px;"></div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
			<input type="hidden" name="method" value="{$method}" />
			<input type="hidden" name="pluginId" value="{$plugin->pluginName}" />
			<input type="hidden" name="projectId" value="{$projectId}" />
			<input type="submit" Value="override" name="submit" />
		</div>
	</div>
	<a href="index.php?option=projectAdmin&cmd=listPlugins&projectId={$projectId}">Back to List</a>
	</form>
</div>