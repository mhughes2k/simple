<div id="dialogBlock" name="copyform" ></div>
	{include file="PluginDeleteUi.tpl"}
{literal}
<script type="text/javascript" language="javascript" src="methods.js"></script>
{/literal}		

	<p>This page allows you to enable plugins at a sitewide level. 
	Some plugins may be enabled/disabled at a Project or Project Template level, 
	thereby overriding these sitewide defaults.</p>

{if $zipinstalled==0}
	<p><strong>{$strings.MSG_PLUGIN_INSTALL_NOZIP}</strong></p>
{/if}
{if ($currentUser->sitewidePermissions.AddPlugin==ALLOW) ||
	($currentUser->superadmin==ALLOW) }	
	{if $zipinstalled==1}
	<form action="index.php" name="installPlugin" method="post" enctype="multipart/form-data">
		<input type="hidden" name="option" value="siteAdmin" />
		<input type="hidden" name="cmd" value="installPlugin" />
		Add new Plugin<img src="{$config.plugin_icon_add}">
		<input type="file" name="pluginZip" id="pluginZip" />
		<input type="submit" name="submit" value="submit" />
	</form>
	<br />
	{/if}
{/if}	
<form method="post" name="fieldsform" id="fieldsform" action="index.php?option=siteAdmin&cmd=multiPluginSubmit">
    <table id="usertable" style="width:100%;">
	    {if $previous >=0 or $next >= 0}
	    <tr>
		    <td>
				{if $previous!= -1}
		    	<a href="?option=siteAdmin&cmd=listPlugins&start={$previous}">{$strings.MSG_PREVIOUS}</a>
		    	{/if}
		    </td>
		    <td colspan="6"></td>
		    <td style="text-align:right;">
		    	{if $next != -1}
			    <a href="?option=siteAdmin&cmd=listPlugins&start={$next}">{$strings.MSG_NEXT}</a>
			    {/if}
		    </td>
	    </tr>
	    {/if}    
    <tr>
    	<th></th>
    	<th>Name</th>
    	<th>Sitewide</th>
    	<th>Remove</th>
    	<th>Enabled</th>
    	<th>Order</th>
    </tr>
{foreach from=$pluginsArray key=key item=plugin}

	<tr {if $smarty.foreach.plugin.index % 2 ==0} class="usertable_alternate_row" {/if}>
<td><input type="checkbox" name="selected_fld[]" value="{$plugin->pluginName}" id="checkbox_row_{$smarty.foreach.plugin.index}"/></td>	
	<td><a href="index.php?option=siteAdmin&cmd=viewPlugin&pluginId={$plugin->pluginName}">{$plugin->pluginName}</a></td>
	<td>{if $plugin->sitewide==1}
		yes<img src="{$config.system_icon_enabled}">
	{else}
		no<img src="{$config.system_icon_disabled}">
	{/if}</td>

	<td><a href="Javascript:deletePlugin('{$plugin->pluginName}')">
		delete<img src="{$config.plugin_icon_delete}"></a></td>
	<td>{if $plugin->enabled==1}
		<a href="index.php?option=siteAdmin&cmd=disablePlugin&pluginId={$plugin->pluginName}">
			disable<img src="{$config.system_icon_enabled}"></a> 
	{else}
		<a href="index.php?option=siteAdmin&cmd=enablePlugin&pluginId={$plugin->pluginName}">
			enable<img src="{$config.system_icon_disabled}"></a> 
	{/if}</td>
	<td style="text-align:center"><a href="index.php?option=siteAdmin&cmd=shiftpluginup&pluginId={$plugin->pluginName}">
		<img src="{$config.plugin_icon_up}"></a><a href="index.php?option=siteAdmin&cmd=shiftplugindown&pluginId={$plugin->pluginName}">
		<img src="{$config.plugin_icon_down}"></a></td>
	</tr>
{foreachelse}	
		<tr>
			<td colspan="7" style="text-align:center">
				{$strings.MSG_NO_PLUGINS_FOUND}
			</td>
		</tr>
{/foreach}
		<tr>
			<td colspan="8">
				<img src="{$config.table_icon_arrow}" title="arrow"/>
<a onClick='javascript:markAllRows("usertable");' style='cursor: pointer;'>Check all</a> /
				<a onClick='javascript:unMarkAllRows("usertable");' style='cursor: pointer;'>Uncheck all</a>				
				With selected:
				{if ($currentUser->sitewidePermissions.AddPlugin==ALLOW) || 
					($currentUser->superadmin==ALLOW) }
				<button name="multi_submit" type="submit" value="delete" title="delete">delete</button>
				<button name="multi_submit" type="submit" value="enable" title="enable">enable</button>
				<button name="multi_submit" type="submit" value="disable" title="disable">disable</button>
				{/if}
			</td>
		</tr>
	    {if $previous>=0 or $next >=0}
	    <tr>
		    <td>
				{if $previous!= -1}
		    	<a href="?option=siteAdmin&cmd=listPlugins&start={$previous}">Previous</a>
		    	{/if}
		    </td>
		    <td colspan="6"></td>
		    <td style="text-align:right;">
		    	{if $next != -1}
			    <a href="?option=siteAdmin&cmd=listPlugins&start={$next}">Next</a>
			    {/if}
		    </td>
	    </tr>
	    {/if}

	</table>


