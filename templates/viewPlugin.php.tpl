<div class="areaTitle">
Plugin
</div>
<div class="manageSectionContent">
<p>Sitewide plugins are those that cannot be edited at a Project or Project Template level.</p>
 <form name="pluginform" action="index.php?option=siteAdmin&cmd=updatePlugin" method="post">
	<div>
		<div style="position:relative;top:0px;height:20px;width:190px;">Name:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
		{$plugin->pluginName|default:''}</div>	
	
		<div style="position:relative;top:0px;height:20px;width:190px;">Enabled:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
			{if $method=='edit'}
				<select name="enabled">
					<option value="1" {if ($plugin->enabled==1) } selected="selected" {/if}>yes</option>
					<option value="0" {if ($plugin->enabled==0) } selected="selected" {/if}>no</option>
				</select>		
			{else}
				{if ($plugin->enabled==1)}
					Yes
				{else}
					No
				{/if}
			{/if}			
		</div>	
	
		<div style="position:relative;top:0px;height:20px;width:190px;">Sitewide:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
			{if $method=='edit'}
				<select name="sitewide">
					<option value="1" {if ($plugin->sitewide==1) } selected="selected" {/if}>yes</option>
					<option value="0" {if ($plugin->sitewide==0) } selected="selected" {/if}>no</option>
				</select>		
			{else}
				{if ($plugin->sitewide==1)}
					Yes
				{else}
					No
				{/if}
			{/if}					
		</div>
	</div>
	{if $method=='edit'}
	<input type="hidden" name="submitted" value="yes" />
	{/if}
	<input type="hidden" name="pluginName" value="{$plugin->pluginName}" />
	<input type="submit" Value="Update" name="submit" />

 </form>
</div>
