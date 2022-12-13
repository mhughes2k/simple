<div class="areaTitle">
Blueprint Permissions
</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<a name="projectTemplatePermissions"></a>
<div><p>Name: 
<a href="index.php?option=siteAdmin&cmd=viewUser&userId={$user->id}">{$user->displayName}</a>
<a href="index.php?option=siteAdmin&cmd=viewUser&userId={$user->id}"><img src="{$config.user_icon}"></a>
</p></div>
<form name="userpermissionsform" action="index.php?option=siteAdmin&cmd=updateUserProjectTemplatePermissions#projectTemplatePermissions" method="post">
	<p>Individual user permissions do not include any permissions the user may have been assigned as part of a group. 
	Unset individual permissions will default to those allocated at User Group level. 
	When no permissions are set at either level, permissions will default to Deny.</p>
	
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;"></div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;"><strong>Individual</strong></div>
	<div style="position:relative;top:-40px;left:300px;height:20px"><strong>Overall</strong></div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;">Edit Document Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}
		<select name="editdocumenttemplate" id="editdocumenttemplate">
			<option value="0" {if $individualPermissions.EditDocumentTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.EditDocumentTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.EditDocumentTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.EditDocumentTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.EditDocumentTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.EditDocumentTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}
	</div>
	<div style="position:relative;top:-40px;left:300px;height:20px">
	{if ($projectTemplatePermissions.EditDocumentTemplate==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
	{if ($projectTemplatePermissions.EditDocumentTemplate==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
	{if ($projectTemplatePermissions.EditDocumentTemplate==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}	
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Start Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}	
		<select name="startproject" id="startproject">
			<option value="0" {if $individualPermissions.StartProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.StartProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.StartProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.StartProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.StartProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.StartProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.StartProject==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.StartProject==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.StartProject==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">End Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="endproject" id="endproject">
			<option value="0" {if $individualPermissions.EndProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.EndProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.EndProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.EndProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.EndProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.EndProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.EndProject==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.EndProject==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.EndProject==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Archive Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="archiveproject" id="archiveproject">
			<option value="0" {if $individualPermissions.ArchiveProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.ArchiveProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.ArchiveProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.ArchiveProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.ArchiveProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.ArchiveProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.ArchiveProject==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.ArchiveProject==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.ArchiveProject==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Edit Blueprint:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="edittemplate" id="edittemplate">
			<option value="0" {if $individualPermissions.EditTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.EditTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.EditTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.EditTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.EditTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.EditTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}				
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.EditTemplate==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.EditTemplate==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.EditTemplate==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">View Blueprint:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="viewtemplate" id="viewtemplate">
			<option value="0" {if $individualPermissions.ViewTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.ViewTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.ViewTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if ($individualPermissions.ViewTemplate==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($individualPermissions.ViewTemplate==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($individualPermissions.ViewTemplate==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.ViewTemplate==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.ViewTemplate==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.ViewTemplate==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Change User Permissions:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="changeuserpermissions" id="changeuserpermissions">
			<option value="0" {if $individualPermissions.ChangeUserPermissions==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.ChangeUserPermissions==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.ChangeUserPermissions==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.ChangeUserPermissions==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.ChangeUserPermissions==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.ChangeUserPermissions==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.ChangeUserPermissions==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.ChangeUserPermissions==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.ChangeUserPermissions==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>		
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Edit Plugin:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="editplugin" id="editplugin">
			<option value="0" {if $individualPermissions.EditPlugin==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.EditPlugin==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.EditPlugin==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.EditPlugin==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.EditPlugin==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.EditPlugin==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectTemplatePermissions.EditPlugin==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectTemplatePermissions.EditPlugin==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectTemplatePermissions.EditPlugin==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>			
	<div>	
	{if $method=='editpermissions'}
	<input type="hidden" name="submitted" value="yes" />
	{/if}
	<input type="hidden" name="userId" value="{$user->id}" />
	<input type="hidden" name="projectTemplateId" value="{$projectTemplateId}" />
	{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
		($currentUser->projectTemplatePermissions[$projectTemplateId].ChangeUserPermissions==ALLOW))  || 
		$currentUser->superadmin==ALLOW }		
		<input type="submit" value="Update Permissions">
	{/if}
	</div>
	</form>
</div>
</div>
