<div class="areaTitle">
Blueprint Permissions
</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<a name="blueprintPermissions"></a>
<div><p>Name: 
<a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$userGroup->id}">{$userGroup->name}</a>
<a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$userGroup->id}"><img src="{$config.group_icon}"></a>
</p></div>
<form name="userpermissionsform" action="index.php?option=siteAdmin&cmd=updateUserGroupProjectTemplatePermissions#projectTemplatePermissions" method="post">
	<p>Users who are members of more than one group will need to have any given permissions in BOTH groups.
	All group permissions can be overridden by individual user permissions.
	When no permissions are set at either user or group level, permissions will default to Deny.</p>
	
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;">Edit Document Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}
		<select name="editdocumenttemplate" id="editdocumenttemplate">
			<option value="0" {if $permissions.EditDocumentTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.EditDocumentTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.EditDocumentTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.EditDocumentTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.EditDocumentTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.EditDocumentTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Start Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}	
		<select name="startproject" id="startproject">
			<option value="0" {if $permissions.StartProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.StartProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.StartProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.StartProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.StartProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.StartProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">End Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="endproject" id="endproject">
			<option value="0" {if $permissions.EndProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.EndProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.EndProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.EndProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.EndProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.EndProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Archive Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="archiveproject" id="archiveproject">
			<option value="0" {if $permissions.ArchiveProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.ArchiveProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.ArchiveProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.ArchiveProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.ArchiveProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.ArchiveProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Edit Blueprint:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="edittemplate" id="edittemplate">
			<option value="0" {if $permissions.EditTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.EditTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.EditTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.EditTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.EditTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.EditTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}				
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">View Blueprint:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="viewtemplate" id="viewtemplate">
			<option value="0" {if $permissions.ViewTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $permissions.ViewTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $permissions.ViewTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $permissions.ViewTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $permissions.ViewTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $permissions.ViewTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
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
	<input type="hidden" name="blueprintId" value="{$blueprintId}" />
	{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
		($currentUser->projectTemplatePermissions[$blueprintId].ChangeUserPermissions==ALLOW))  || 
		$currentUser->superadmin==ALLOW }			
		<input type="submit" value="Update Permissions">
	{/if}	
	</div>
	</form>
</div>
</div>