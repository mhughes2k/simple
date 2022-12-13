<div class="areaTitle">
Simulation Permissions
</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<a name="simulationPermissions"></a>
<div><p>Name: 
<a href="index.php?option=siteAdmin&cmd=viewUser&userId={$user->id}">{$user->displayName}</a>
<a href="index.php?option=siteAdmin&cmd=viewUser&userId={$user->id}"><img src="{$config.user_icon}"></a>
</p></div>
<form name="userpermissionsform" action="index.php?option=siteAdmin&cmd=updateUserProjectPermissions#sitewidePermissions" method="post">
	<p>Individual user permissions do not include any permissions the user may have been assigned as part of a group. 
	Unset individual permissions will default to those allocated at User Group level. 
	When no permissions are set at either level, permissions will default to Deny.</p>
	
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;"></div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;"><strong>Individual</strong></div>
	<div style="position:relative;top:-40px;left:300px;height:20px"><strong>Overall</strong></div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;">Use Staff Tools:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}
		<select name="usestafftools" id="usestafftools">
			<option value="0" {if $individualPermissions.UseStaffTools==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.UseStaffTools==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.UseStaffTools==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.UseStaffTools==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.UseStaffTools==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.UseStaffTools==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}
	</div>
	<div style="position:relative;top:-40px;left:300px;height:20px">
	{if ($projectPermissions.UseStaffTools==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
	{if ($projectPermissions.UseStaffTools==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
	{if ($projectPermissions.UseStaffTools==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}	
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Delete Any Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}	
		<select name="deleteanyitem" id="deleteanyitem">
			<option value="0" {if $individualPermissions.DeleteAnyItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.DeleteAnyItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.DeleteAnyItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.DeleteAnyItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.DeleteAnyItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.DeleteAnyItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.DeleteAnyItem==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.DeleteAnyItem==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.DeleteAnyItem==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Delete Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="deleteitem" id="deleteitem">
			<option value="0" {if $individualPermissions.DeleteItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.DeleteItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.DeleteItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.DeleteItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.DeleteItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.DeleteItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.DeleteItem==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.DeleteItem==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.DeleteItem==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">View Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="viewitem" id="viewitem">
			<option value="0" {if $individualPermissions.ViewItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.ViewItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.ViewItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.ViewItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.ViewItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.ViewItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.ViewItem==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.ViewItem==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.ViewItem==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Add Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="additem" id="additem">
			<option value="0" {if $individualPermissions.AddItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.AddItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.AddItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.AddItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.AddItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.AddItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}				
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.AddItem==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.AddItem==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.AddItem==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Edit Any Item:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="editanyitem" id="editanyitem">
			<option value="0" {if $individualPermissions.EditAnyItem==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.EditAnyItem==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.EditAnyItem==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.EditAnyItem==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.EditAnyItem==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.EditAnyItem==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.EditAnyItem==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.EditAnyItem==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.EditAnyItem==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Edit Items:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="edititems" id="edititems">
			<option value="0" {if $individualPermissions.EditItems==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.EditItems==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.EditItems==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.EditItems==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.EditItems==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.EditItems==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.EditItems==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.EditItems==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.EditItems==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}			
		</div>
	</div>		
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Stop Simulation:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="stopproject" id="stopproject">
			<option value="0" {if $individualPermissions.StopProject==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $individualPermissions.StopProject==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $individualPermissions.StopProject==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $individualPermissions.StopProject==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $individualPermissions.StopProject==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $individualPermissions.StopProject==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
		<div style="position:relative;top:-40px;left:300px;height:20px">
		{if ($projectPermissions.StopProject==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.StopProject==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.StopProject==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
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
		{if ($projectPermissions.ChangeUserPermissions==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.ChangeUserPermissions==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.ChangeUserPermissions==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
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
		{if ($projectPermissions.EditPlugin==NOTSET) && ($user->superadmin!=ALLOW)} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if ($projectPermissions.EditPlugin==ALLOW) || ($user->superadmin==ALLOW)} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if ($projectPermissions.EditPlugin==DENY) && ($user->superadmin!=ALLOW)} Deny<img src="{$config.permissions_icon_deny}">{/if}		
		</div>
	</div>				
	<div>	
	<input type="hidden" name="redirect" value="{$redirect}" />
	{if $method=='editpermissions'}
	<input type="hidden" name="submitted" value="yes" />
	{/if}
	<input type="hidden" name="userId" value="{$user->id}" />
	<input type="hidden" name="projectId" value="{$projectId}" />
	{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
		($currentUser->projectPermissions[$projectId].ChangeUserPermissions==ALLOW))  || 
		$currentUser->superadmin==ALLOW }	
		{if $method=='editpermissions'}
		<input type="submit" value="Save">
		{else}
		  {if !is_null($redirect)}
		    <A href="{$redirect}">Back</a>
		  {/if}
		{/if}
		
	{/if}
	</div>
	</form>
</div>
</div>