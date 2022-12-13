<a name="users"></a>
<div class="sectionBox_manage" id="SimulationUsers">
	<div class="sectionTitle">
		Users
	</div>
	<p>The following Users & Groups have permissions for this Simulation.</p>
	
	{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->superadmin==ALLOW)}
	<p><a href="javascript:ShowAddUserSimulationDialog('user');">Add User to Simulation</a></p>
	<p><a href="javascript:ShowAddUserSimulationDialog('group');">Add User Group to Simulation</a></p>
	{/if}
	
	<table>
	<tr>
	<th>Icon</th>
	<th>
		Name
	</th>
	<th>Use Staff Tools</th>
	<th>Delete Any Item</th>
	<th>Delete Item</th>
	<th>Edit Any Item</th>
	<th>View Item</th>
	<th>Add Item</th>
	<th>Edit Item</th>
	<th>Stop Project</th>
	<th>Edit Plugin</th>
	<th>Change User Permissions</th>
	</tr>
	{foreach from=$ProjectUserList_UsersAndGroups key=key item=holder}
	<tr>
	<td>{if $holder.type=='group'}<img src="{$config.group_icon}">{else}
	<img src="ImageHandler.php?context=avatar&userId={$holder.id}&type={$holder.imagetype}" width="20">{/if}
	<td>

	{if $holder.type=='group'}
		<a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$holder.id}">{$holder.name}</a>
	{else}
{*		<a href="index.php?option=siteAdmin&cmd=updateUserProjectPermission&projectId={$project->id}&userId={$holder.id}">{$holder.name}</a> *}
		<a href="index.php?option=siteAdmin&cmd=viewUser&userId={$holder.id}">{$holder.name}</a>
	{/if}
	</td>
	{if ($currentUser->superadmin==ALLOW) || ($ProjectUserList_AllowPermissionChange and $user->isProjectStaff()) }
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=UseStaffTools&id={$holder.id}&type={$holder.type}">
		{if $holder.UseStaffTools==0}Not set{/if}
		{if $holder.UseStaffTools==1}Yes<img src="{$config.permissions_icon_allow}">
		{/if}
		{if $holder.UseStaffTools==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline" href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=DeleteAnyItem&id={$holder.id}&type={$holder.type}">
		{if $holder.DeleteAnyItem==0}Not set{/if}
		{if $holder.DeleteAnyItem==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.DeleteAnyItem==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline" href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=DeleteItem&id={$holder.id}&type={$holder.type}">
		{if $holder.DeleteItem==0}Not set{/if}
{if $holder.DeleteItem==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.DeleteItem==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>	
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=EditAnyItem&id={$holder.id}&type={$holder.type}">
		{if $holder.EditAnyItem==0}Not set{/if}
{if $holder.EditAnyItem==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.EditAnyItemt==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=ViewItem&id={$holder.id}&type={$holder.type}">
		{if $holder.ViewItem==0}Not set{/if}
{if $holder.ViewItem==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.ViewItem==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=AddItem&id={$holder.id}&type={$holder.type}">
		{if $holder.AddItem==0}Not set{/if}
{if $holder.AddItem==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.AddItem==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=EditItems&id={$holder.id}&type={$holder.type}">
		{if $holder.EditItems==0}Not set{/if}
{if $holder.EditItems==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.EditItems==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=StopProject&id={$holder.id}&type={$holder.type}">
		{if $holder.StopProject==0}Not set{/if}
		{if $holder.StopProject==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.StopProject==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline" href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=EditPlugni&id={$holder.id}&type={$holder.type}">
		{if $holder.UseStaffTools==0}Not set{/if}
{if $holder.EditPlugin==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.EditPlugin==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>
	<td>
	<a class="nounderline"  href="index.php?option=projectAdmin&cmd=togglePermission&projectId={$project->id}&permission=ChangeUserPermissions&id={$holder.id}&type={$holder.type}">
		{if $holder.UseStaffTools==0}Not set{/if}
{if $holder.ChangeUserPermissions==1}Yes<img src="{$config.permissions_icon_allow}">{/if}
		{if $holder.ChangeUserPermissions==-1}No<img src="{$config.permissions_icon_deny}">{/if}
	</a>
	</td>		
	{else}
	<td>{if $holder.UseStaffTools==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.DeleteAnyItem==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.DeleteItem==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.EditAnyItem==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.ViewItem==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.AddItem==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.EditItems==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.StopProject==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.EditPlugin==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	<td>{if $holder.ChangeUserPermissions==1}Yes<img src="{$config.permissions_icon_allow}">{else}No<img src="{$config.permissions_icon_deny}">{/if}</td>
	{/if}
	</tr>
	{foreachelse}
	<tr>
	<td colspan="9">
		No Users or Groups.
	</td>
	</tr>
	{/foreach}
	</table>
</div>
