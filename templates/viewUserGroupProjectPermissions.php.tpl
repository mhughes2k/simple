<div class="areaTitle">
Simulation Permissions
</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<a name="simulationPermissions"></a>
<div><p>Name: 
<a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$userGroup->id}">{$userGroup->name}</a>
<a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$userGroup->id}"><img src="{$config.group_icon}"></a>
</p></div>
<form name="userpermissionsform" action="index.php?option=siteAdmin&cmd=updateUserGroupProjectPermissions#projectPermissions" method="post">
	<p>Users who are members of more than one group will need to have any given permissions in BOTH groups.
	All group permissions can be overridden by individual user permissions.
	When no permissions are set at either user or group level, permissions will default to Deny.</p>
	
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;">Use Staff Tools:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}
		<select name="usestafftools" id="usestafftools">
			<option value="0" {if $permissions.UseStaffTools==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.UseStaffTools==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.UseStaffTools==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.UseStaffTools==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.UseStaffTools==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.UseStaffTools==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Delete Any Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}	
		<select name="deleteanyitem" id="deleteanyitem">
			<option value="0" {if $permissions.DeleteAnyItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.DeleteAnyItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.DeleteAnyItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.DeleteAnyItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.DeleteAnyItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.DeleteAnyItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Delete Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="deleteitem" id="deleteitem">
			<option value="0" {if $permissions.DeleteItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.DeleteItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.DeleteItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.DeleteItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.DeleteItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.DeleteItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">View Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="viewitem" id="viewitem">
			<option value="0" {if $permissions.ViewItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.ViewItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.ViewItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.ViewItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.ViewItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.ViewItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Add Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="additem" id="additem">
			<option value="0" {if $permissions.AddItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.AddItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.AddItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.AddItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.AddItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.AddItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}				
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Edit Any Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="editanyitem" id="editanyitem">
			<option value="0" {if $permissions.EditAnyItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.EditAnyItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.EditAnyItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.EditAnyItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.EditAnyItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.EditAnyItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Edit Items:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="edititems" id="edititems">
			<option value="0" {if $permissions.EditItems==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.EditItems==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.EditItems==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.EditItems==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.EditItems==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.EditItems==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
	</div>	
		
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Stop Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="stopproject" id="stopproject">
			<option value="0" {if $permissions.StopProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.StopProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.StopProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.StopProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.StopProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.StopProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>	
		
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Change User Permissions:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="changeuserpermissions" id="changeuserpermissions">
			<option value="0" {if $permissions.ChangeUserPermissions==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.ChangeUserPermissions==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.ChangeUserPermissions==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.ChangeUserPermissions==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.ChangeUserPermissions==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.ChangeUserPermissions==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>		
		
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Edit Plugin:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="editplugin" id="editplugin">
			<option value="0" {if $permissions.EditPlugin==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.EditPlugin==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.EditPlugin==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.EditPlugin==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.EditPlugin==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.EditPlugin==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>	
		
	<div>	
	{if $method=='editpermissions'}
	<input type="hidden" name="submitted" value="yes" />
	{/if}
	<input type="hidden" name="userGroupId" value="{$userGroup->id}" />
	<input type="hidden" name="simulationId" value="{$simulationId}" />
	{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
		($currentUser->projectPermissions[$simulationId].ChangeUserPermissions==ALLOW))  || 
		$currentUser->superadmin==ALLOW }			
		<input type="submit" value="Update Permissions">
	{/if}
	</div>
	</form>
</div>
</div>