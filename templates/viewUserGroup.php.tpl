{include file="AddSimulationDialogToGroup.tpl"}
{include file="AddBlueprintDialogToGroup.tpl"}
{include file="AddMemberDialogToGroup.tpl"}
	{include file="UserGroupMemberDeleteUi.tpl"}
<div id="dialogBlockBP" name="copyformBP" ></div>
	{include file="UserGroupViewBPDeleteUi.tpl"}	
<div id="dialogBlockS" name="copyformS" ></div>
	{include file="UserGroupViewSDeleteUi.tpl"}	
		
<div class="areaTitle">
User Group
</div>
<div class="manageSectionContent">
<div id="dialogBlock" 
style="
position:absolute;
top:0px;
left:0px;
width:100%;
height:100%;
display:none;
z-index:100;
">
&nbsp;
</div>
<div class="sectionBox_manage">
 <form name="usergroupform" action="index.php?option=siteAdmin&cmd=updateUserGroup" method="post">
	<div>
		<div style="position:relative;top:0px;height:20px;width:190px;">Name*:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
			{if $method=='edit'}
				<input type="text" name="groupName" id="groupName" value="{$usergroup->name}"
				{if ($missingFields.groupName==1)} class="missingField"{/if}>
				<input type="hidden" name="editState" value="editDetails">
			{else}
				{$usergroup->name|default:''}
				<input type="hidden" name="groupName" value="{$usergroup->name}" />
			{/if}
		</div>
	</div>
	
	<div>
		<div style="position:relative;top:0px;height:20px;width:190px;">Active:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
			{if $method=='edit'}
				<select name="active">
					<option value="1" {if ($usergroup->active==1) } selected="selected" {/if}>yes</option>
					<option value="0" {if ($usergroup->active==0) } selected="selected" {/if}>no</option>
				</select>						
			{else}
				{if ($usergroup->active==1)}
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
	<input type="hidden" name="method" value="updateUserGroup"/>
	<input type="hidden" name="userGroupId" value="{$usergroup->id}" />
	{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || 
		($currentUser->superadmin==ALLOW)}	
		<input type="submit" Value="Update" name="submit" />
	{/if}
	<p {if $missingFields} class="highlightText" {/if}>Items marked * are required.</p>
 </form>
</div>

<div class="sectionBox_manage" id="groupSimulationPermissions">
<a name="simulationPermissions"></a>
<div class="sectionTitle">Simulation Permissions</div>
{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->superadmin==ALLOW)}
<p>
<a href="javascript:ShowAddSimDialog();">Add</a>
<!--
<form action="index.php?option=siteAdmin&cmd=viewUserGroup&method=addSimulation2UserGroup&userGroupId={$usergroup->id}#simulationPermissions" method="post">
<select name="simulationId">
	{foreach from=$allsimulations key=key item=simulation}
	<option value="{$simulation->id}">{$simulation->Name}</option>
	{/foreach}
</select>
<input type="submit" value="Add Simulation to User Group" name="submit"><br />
</form>//-->
</p>
{/if}
<p><table id="usersimulationtable" style="width:100%;">
	{if $sPrevious>=0 or $sNext >=0}
		<tr>
		    <td>
				{if $sPrevious!= -1}
		    	<a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$usergroup->id}&sStart={$sPrevious}">{$strings.MSG_PREVIOUS}</a>
		    	{/if}
		    </td>
		    <td></td>
		    <td style="text-align:right;">
		    	{if $sNext != -1}
			    <a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$usergroup->id}&sStart={$sNext}">{$strings.MSG_NEXT}</a>
			    {/if}
		    </td>
	    </tr>
	{/if}	
	<tr>
	<th>Simulation Name</th>
	<th>Edit Permissions</th>
	<th>Delete All Permissions</th>
	</tr>
	{foreach from=$simulations key=key item=simulation name=usersimulationtable}		
		<tr {if $smarty.foreach.usersimulationtable.index % 2 ==0} class="usertable_alternate_row" {/if}>
		<td><a href="index.php?option=projectAdmin&cmd=viewProject&projectId={$key}">
		{$simulation}</a></td>
		<td>
		{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
			($currentUser->projectPermissions[$key].ChangeUserPermissions==ALLOW)) ||
			($currentUser->superadmin==ALLOW)  }		
			<a href="index.php?option=siteAdmin&cmd=viewUserGroupProjectPermissions&simulationId={$key}&userGroupId={$usergroup->id}">edit</a>
		{else}
			<a href="index.php?option=siteAdmin&cmd=viewUserGroupProjectPermissions&simulationId={$key}&userGroupId={$usergroup->id}">view</a>
		{/if}
		</td>
		<td>
		{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
			($currentUser->projectPermissions[$key].ChangeUserPermissions==ALLOW)) ||
			($currentUser->superadmin==ALLOW)  }			
			<a href="Javascript:deleteUserGroupSimulation({$usergroup->id},{$key})">{$strings.MSG_DELETE}</a>
		{else}
			Not permitted
		{/if}</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" style="text-align:center">
			{$strings.MSG_NO_USERGROUPSIMULATIONS_FOUND}
			</td>
		</tr>
	{/foreach}
	{if $sPrevious>=0 or $sNext >=0}
		<tr>
		    <td>
				{if $sPrevious!= -1}
		    	<a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$usergroup->id}&sStart={$sPrevious}">{$strings.MSG_PREVIOUS}</a>
		    	{/if}
		    </td>
		    <td></td>
		    <td style="text-align:right;">
		    	{if $sNext != -1}
			    <a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$usergroup->id}&sStart={$sNext}">{$strings.MSG_NEXT}</a>
			    {/if}
		    </td>
	    </tr>
	{/if}		
	</table></p>
</div>
<div class="sectionBox_manage" id="usergroupmembers">
<a name="members"></a>
<div class="sectionTitle">Members</div>
{if ($currentUser->sitewidePermissions.AddUser==ALLOW) || ($currentUser->superadmin==ALLOW)}
<p><a href="index.php?option=siteAdmin&cmd=addUser&userGroupId={$usergroup->id}">Create and Add</a></p>
{/if}
{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->superadmin==ALLOW)}
<p><a href="javascript:ShowAddMemberDialog();">Add</a></p>
{/if}



<p><table id="usergrouptable" style="width:100%;">
	{foreach from=$members key=key item=member name=usergrouptable}
		{if $smarty.foreach.usergrouptable.index % 20 == 0} 
			<tr>
			<th></th>
			<th>Member Name</th>
			<th>Remove From Group</th>
			</tr>
		{/if}
		<tr {if $smarty.foreach.usergrouptable.index % 2 ==0} class="usergrouptable_alternate_row" {/if}>
		<td>
		<img src="ImageHandler.php?context=avatar&userId={$member->id}&type={$member->imagetype}" width="20">
		</td>
		<td><a href="index.php?option=siteAdmin&cmd=viewUser&userId={$member->id}">
		{$member->displayName}</a></td>
		<td>
		{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || 
			($currentUser->superadmin==ALLOW)}
			<a href="Javascript:removeMember({$member->id},{$usergroup->id})">remove from group</a>
		{else}
			Not permitted
		{/if}
		</td>
		</tr>
	{foreachelse}
		<tr><td>This group currently has no members.</td></tr>
	{/foreach}
</table></p>
</div>

<div class="sectionBox_manage">
<a name="sitewidePermissions"></a>
<div class="sectionTitle">Sitewide Permissions</div>
<form name="usergrouppermissionsform" action="index.php?option=siteAdmin&cmd=updateUserGroupSitewidePermissions#sitewidePermissions" method="post">
	<p>If users are members of more than one group, they will need to have the appropriate permissions
	in BOTH groups or the system will default to Deny. Individual permissions can also be set for specific users,
	 which override all group permissions.</p>

	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;">Add User:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}
		<select name="adduser" id="adduser">
			<option value="0" {if $sitewidePermissions.AddUser==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.AddUser==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.AddUser==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.AddUser==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.AddUser==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.AddUser==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Edit User:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}	
		<select name="edituser" id="edituser">
			<option value="0" {if $sitewidePermissions.EditUser==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.EditUser==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.EditUser==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.EditUser==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.EditUser==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.EditUser==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Make Level Zero User:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="makelevelzerouser" id="makelevelzerouser">
			<option value="0" {if $sitewidePermissions.MakeLevelZeroUser==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.MakeLevelZeroUser==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.MakeLevelZeroUser==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.MakeLevelZeroUser==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.MakeLevelZeroUser==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.MakeLevelZeroUser==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Add Plugin:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="addplugin" id="addplugin">
			<option value="0" {if $sitewidePermissions.AddPlugin==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.AddPlugin==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.AddPlugin==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.AddPlugin==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.AddPlugin==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.AddPlugin==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Install Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="installtemplate" id="installtemplate">
			<option value="0" {if $sitewidePermissions.InstallTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.InstallTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.InstallTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.InstallTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.InstallTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.InstallTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}				
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Edit Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="edittemplate" id="edittemplate">
			<option value="0" {if $sitewidePermissions.EditTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.EditTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.EditTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.EditTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.EditTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.EditTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}			
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Remove Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
	{if $method=='editpermissions'}		
		<select name="removetemplate" id="removetemplate">
			<option value="0" {if $sitewidePermissions.RemoveTemplate==NOTSET} selected="selected" {/if}>Unset</option>
			<option value="1" {if $sitewidePermissions.RemoveTemplate==ALLOW} selected="selected" {/if}>Allow</option>
			<option value="-1" {if $sitewidePermissions.RemoveTemplate==DENY} selected="selected" {/if}>Deny</option>
		</select>
	{else}
		{if $sitewidePermissions.RemoveTemplate==NOTSET} Unset<img src="{$config.permissions_icon_notset}">{/if}
		{if $sitewidePermissions.RemoveTemplate==ALLOW} Allow<img src="{$config.permissions_icon_allow}">{/if}
		{if $sitewidePermissions.RemoveTemplate==DENY} Deny<img src="{$config.permissions_icon_deny}">{/if}				
	{/if}		
	</div>
	</div>	
				
	<div>
	{if $method=='editpermissions'}
	<input type="hidden" name="submitted" value="yes" />
	{/if}
	<input type="hidden" name="method" value="updateUserGroupSitewidePermissions" />
	<input type="hidden" name="userGroupId" value="{$usergroup->id}" />
	{if (($currentUser->sitewidePermissions.EditUser==ALLOW) && 
		($currentUser->sitewidePermissions.MakeLevelZeroUser==ALLOW)) ||
		($currentUser->superadmin==ALLOW)}		
		<input type="submit" value="Update Permissions">
	{/if}
	</div>
	</form>
</div>

<div class="sectionBox_manage" id="groupBlueprintPermissions">
<a name="blueprintPermissions"></a>
<div class="sectionTitle">Blueprint Permissions</div>
{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->superadmin==ALLOW)}
<p><a href="javascript:ShowAddBPDialog();">Add</a></p>
{/if}
<p><table id="userblueprinttable" style="width:100%;">
	{if $bPrevious>=0 or $bNext >=0}
		<tr>
		    <td>
				{if $bPrevious!= -1}
		    	<a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$userGroup->id}&bStart={$bPrevious}">{$strings.MSG_PREVIOUS}</a>
		    	{/if}
		    </td>
		    <td></td>
		    <td style="text-align:right;">
		    	{if $bNnext != -1}
			    <a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$userGroup->id}&bStart={$bNext}">{$strings.MSG_NEXT}</a>
			    {/if}
		    </td>
	    </tr>
	{/if}	
	<tr>
	<th>Blueprint Name</th>
	<th>Edit Permissions</th>
	<th>Delete All Permissions</th>
	</tr>
	{foreach from=$blueprints key=key item=blueprint name=userblueprinttable}		
		<tr {if $smarty.foreach.userblueprinttable.index % 2 ==0} class="usertable_alternate_row" {/if}>
		<td><a href="index.php?option=projectTemplateAdmin&cmd=viewProjectTemplate&projectTemplateId={$key}">
		{$blueprint}</a></td>
		<td>
		{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
			($currentUser->projectTemplatePermissions[$key].ChangeUserPermissions==ALLOW)) ||
			($currentUser->superadmin==ALLOW)  }		
			<a href="index.php?option=siteAdmin&cmd=viewUserGroupProjectTemplatePermissions&blueprintId={$key}&userGroupId={$usergroup->id}">edit</a>
		{else}
			<a href="index.php?option=siteAdmin&cmd=viewUserGroupProjectTemplatePermissions&blueprintId={$key}&userGroupId={$usergroup->id}">view</a>
		{/if}
		</td>
		<td>
		{if (($currentUser->sitewidePermissions.EditUser==ALLOW) &&
			($currentUser->projectTemplatePermissions[$key].ChangeUserPermissions==ALLOW)) ||
			($currentUser->superadmin==ALLOW)  }			
			<a href="Javascript:deleteUserGroupBlueprint({$usergroup->id},{$key})">delete</a>
		{else}
			Not permitted
		{/if}
		</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" style="text-align:center">
			{$strings.MSG_NO_USERGROUPBLUEPRINTS_FOUND}
			</td>
		</tr>
	{/foreach}
	{if $bPrevious>=0 or $bNext >=0}
		<tr>
		    <td>
				{if $bPrevious!= -1}
		    	<a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$usergroup->id}&bStart={$bPrevious}">{$strings.MSG_PREVIOUS}</a>
		    	{/if}
		    </td>
		    <td></td>
		    <td style="text-align:right;">
		    	{if $bNext != -1}
			    <a href="?option=siteAdmin&cmd=viewUserGroup&userGroupId={$usergroup->id}&bStart={$bNext}">{$strings.MSG_NEXT}</a>
			    {/if}
		    </td>
	    </tr>
	{/if}		
	</table></p>
</div>


</div>
