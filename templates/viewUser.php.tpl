{include file="AddSimulationDialogToUser.tpl"}
{include file="AddBlueprintDialogToUser.tpl"}
	{include file="UserViewDeleteUi.tpl"}
<div id="dialogBlockBP" name="copyformBP" ></div>
	{include file="UserViewBPDeleteUi.tpl"}	
<div id="dialogBlockS" name="copyformS" ></div>
	{include file="UserViewSDeleteUi.tpl"}	
	
<div class="areaTitle">
User Profile
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
 <form name="userform" action="index.php?option=siteAdmin&cmd=updateUser" method="post">
 	{if $message!=''}
	 	<p>{$message}</p>
 	{/if}
	<div >
	 <div style="float:right"><A href="?option=siteadmin&cmd=viewpublic&userId={$user->id}">View Public Profile Page</a></div>
		<div class="float-left">User Name:</div>
		<div class="right">
				{$user->userName}
		</div>
	</div>
	<div >
		<div class="float-left">Display Name*:</div>
		<div class="right">
			{if $method=='edit'}
				<input type="text" name="displayName" id="displayName" value="{$user->displayName}"
				{if ($missingFields.displayName==1)} class="missingField"{/if}>
				<input type="hidden" name="editState" value="editDetails">
			{else}
				{$user->displayName|default:''}
			{/if}
		</div>
	</div>
	<div>
		<div class="float-left">E-mail*:</div>
		<div class="right">
			{if $method=='edit'}
				<input type="text" name="email" value="{$user->email}" 
				{if ($missingFields.email==1)} class="missingField"{/if}>
			{else}
				{$user->email|default:'*Email Not Available*'}
			{/if}
		</div>
	</div>
	<div>
		<div class="float-left">Registration Number:</div>
		<div class="right">
			{if $method=='edit'}
				<input type="text" name="regnumber" value="{$user->regNumber}" 
				{if ($missingFields.regNumber==1)} class="missingField"{/if}>
			{else}
				{$user->regNumber|default:'*Registration number Not Available*'}
			{/if}
		</div>
	</div>
	<div>
		<div class="float-left">Active:</div>
		<div class="right">
			{if ($user->superadmin==1 )&& $method=='edit'}
				<select name="active">
					<option value="1" {if ($user->active==1) } selected="selected" {/if}>yes</option>
					<option value="0" {if ($user->active==0) } selected="selected" {/if}>no</option>
				</select>						
			{else}
				{if ($user->active==1)}
					Yes
				{else}
					No
				{/if}
			{/if}
		</div>
	</div>
	{if $currentUser->superadmin==ALLOW}
	<div>
		<div class="float-left">Superadmin:</div>
		<div class="right">
			{if $method=='edit'}
				<select name="superadmin">
					<option value="1" {if ($user->superadmin==1) } selected="selected" {/if}>yes</option>
					<option value="0" {if ($user->superadmin==0) } selected="selected" {/if}>no</option>
				</select>		
			{else}
				{if ($user->superadmin==1)}
					Yes
				{else}
					No
				{/if}
			{/if}
		</div>
	</div>	
	{/if}
	
	<div>
	<div class="float-left">Public Info (visible by other users in your Simulation):</div>
	<div class="right">
		{if $method=='edit'}
			<textarea name="blurb" cols="20" rows="4">{$user->blurb}</textarea>					
		{else}
			<textarea name="blurb" cols="20" rows="4" disabled>{$user->blurb|default:''}</textarea>
		{/if}
	</div>
	</div>
<br />
<br />
<br />
	<div>
	<div class="float-left">
	{if $method=='edit'}
	<input type="hidden" name="submitted" value="yes" />
	{/if}
	<input type="hidden" name="method" value="updateUser"/>
	<input type="hidden" name="userId" value="{$user->id}" />
	{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || 
		($currentUser->id==$user->id) ||
		($currentUser->superadmin==ALLOW)}
		<input type="submit" Value="Update" name="submit" />
	{/if}
	</div>
	<div class="right">
	<p {if ($missingFields)} class="highlightText"{/if}>Items marked * are required.</p>
	</div>
	</div>
 </form>
</div>

<div class="sectionBox_manage">
<a name="avatar"></a>
<div class="sectionTitle">Avatar</div>
<form name="useravatarform" action="index.php?option=siteAdmin&cmd=updateuseravatar#avatar" method="post" enctype="multipart/form-data">
	{if $avatarError > ''}
	<p>{$avatarError}</p>
	{/if}
	<p>You can change your avatar.</p>
	{if $method=='editavatar'}	
	<div>
		<div class="float-left">
			<img src="ImageHandler.php?context=avatar&userId={$user->id}&type={$user->imagetype}">
		</div>
		<div class="right">
			<input type="file" name="avatar" id="avatar">
			<input type="submit" value="Change Avatar">
			<input type="hidden" name="editState" value="editAvatar">
		</div>
	</div>
	{else}
	<div>
		<div class="float-left">
			<img src="ImageHandler.php?context=avatar&userId={$user->id}&type={$user->imagetype}">
		</div>
		<div class="right">
			<input type="submit" value="Change Avatar">
		</div>
	</div>
	{/if}
	<div>
		<input type="hidden" name="method" value="updateUserAvatar"/>
		<input type="hidden" name="userId" value="{$user->id}" />	
	</div>
	</form>
</div>


{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || 
	($currentUser->id==$user->id) ||
	($currentUser->superadmin==ALLOW)}
{if ($user->authMethod=='TleAuthenticate') }
<div class="sectionBox_manage">
<a name="password"></a>
<div class="sectionTitle">Password</div>
<form name="userpasswordform" action="index.php?option=siteAdmin&cmd=updateuserlogin#password" method="post">
	<p>You can change your password.</p>
	<div>
	<div class="float-left">Username:</div>
	<div class="right">{$user->userName}</div>
	</div>	
	{if $method=='editpassword'}	
	<div>
	<div class="float-left">New Password*:</div>
	<div class="right">
	<input type="password" name="newpwd" id="newpwd"
	{if ($missingFields.newpwd==1) || ($passwordsDiffer) } class="missingField"{/if}></div>
	</div>
	<div>
	<div class="float-left">Re-Enter New Password*:</div>
	<div class="right">
	<input type="password" name="newpwd2" id="newpwd2"
	{if ($missingFields.newpwd2==1) || ($passwordsDiffer) } class="missingField"{/if}>
	{if $passwordsDiffer}<span class="highlightText">Passwords are different.</p>{/if}</div>
	</div>
	{/if}
	{if $method=='editpassword'}
	<input type="hidden" name="editState" value="editPassword">
	{/if}
	<div>
	<input type="hidden" name="method" value="updateUserLogin"/>
	<input type="hidden" name="userId" value="{$user->id}" />	
	<input type="submit" value="Change Password"></div>
	</form>
</div>
{/if}
{/if}


<br clear="all" />
<div class="sectionBox_manage">
<a name="groups"></a>
<div class="sectionTitle">{$strings.MSG_GROUPS}</div>
{if ($currentUser->sitewidePermissions.EditUser==ALLOW) }
<p>
<form action="index.php?option=siteAdmin&cmd=addUserGroup2User&userId={$user->id}" method="post">
<div class="tbc">To Be Looked at (need a better selector!)</div>
Group ID: <input type="text name="groupId">


<input type="hidden" name="method" value="AddUserGroup2User" />
<input type="submit" value="Add User to User Group" name="submit"><img src="{$config.user_icon_assign}"><br />
</form>
</p>
{/if}
<p><table id="usergrouptable" style="width:100%;">
	{foreach from=$groups key=key item=group name=usergrouptable}
		{if $smarty.foreach.usergrouptable.index % 20 == 0} 
			<tr><th>Group Name</th>
			{if $currentUser->sitewidePermissions.EditUser==ALLOW}			
				<th>Remove From Group</th>
			{/if}
			</tr>
		{/if}
		<tr {if $smarty.foreach.usergrouptable.index % 2 ==0} class="usergrouptable_alternate_row" {/if}>
		<td><a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$group->id}">
		{$group->name}</a></td>
		{if $currentUser->sitewidePermissions.EditUser==ALLOW }		
		<td><a href="Javascript:removeFromGroup({$user->id},{$group->id})">remove from {$strings.MSG_GROUP}</a></td>
		{/if}
		</tr>
	{foreachelse}
		<tr><td>This user is not currently in any {$strings.MSG_GROUPS}.</td></tr>
	{/foreach}
</table></p>
</div>
<br clear="all" />

<div class="sectionBox_manage" id="usersSimulationPermissions">
<a name="simulationPermissions"></a>
<div class="sectionTitle" >Simulation Permissions</div>

{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->superadmin==ALLOW)}
<p>
<a href="javascript:ShowAddSimDialog();">Add</a>
<!--<form action="index.php?option=siteAdmin&cmd=viewUser&method=addSimulation2User&userId={$user->id}#simulationPermissions" method="post">
<select name="simulationId">
	{foreach from=$allsimulations key=key item=simulation}
	<option value="{$simulation->id}">{$simulation->Name} ({$simulation->TemplateName})</option>
	{/foreach}
</select>

<input type="submit" value="Add Simulation to User" name="submit"><br />
</form>
//-->
</p>

{/if}

<table id="usersimulationtable" style="width:100%;">
	{if $sPrevious>=0 or $sNext >=0}
		<tr>
		    <td>
				{if $sPrevious!= -1}
		    	<a href="?option=siteAdmin&cmd=viewUser&userId={$user->id}&sStart={$sPrevious}">Previous</a>
		    	{/if}
		    </td>
		    <td></td>
		    <td style="text-align:right;">
		    	{if $sNext != -1}
			    <a href="?option=siteAdmin&cmd=viewUser&userId={$user->id}&sStart={$sNext}">Next</a>
			    {/if}
		    </td>
	    </tr>
	{/if}	
	<tr>
	<th>Simulation Name</th>
	<th>Edit Permissions</th>
	<th>Remove Simulation</th>
	</tr>
	{foreach from=$simulations key=key item=simulation name=usersimulationtable}		
		<tr {if $smarty.foreach.usersimulationtable.index % 2 ==0} class="usertable_alternate_row" {/if}>
		<td><a href="index.php?option=projectAdmin&cmd=viewProject&projectId={$key}">
		{$simulation}</a></td>
		<td>
		{if ((($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->id==$user->id)) &&
			($currentUser->projectPermissions[$key].ChangeUserPermissions==ALLOW)) ||
			($currentUser->superadmin==ALLOW)  }
			<a href="index.php?option=siteAdmin&cmd=updateUserProjectPermissions&projectId={$key}&userId={$user->id}&redirect={$smarty.server.REQUEST_URI|urlencode}">Edit</a>		
<!--			<a href="index.php?option=siteAdmin&cmd=viewUserSimulationPermissions&simulationId={$key}&userId={$user->id}">edit</a>//-->
		{/if}

			<a href="index.php?option=siteAdmin&cmd=viewUserSimulationPermissions&simulationId={$key}&userId={$user->id}&redirect={$smarty.server.REQUEST_URI|urlencode}">View</a>
		</td>
		<td>
		{if ((($currentUser->sitewidePermissions.EditUser==ALLOW) || ($currentUser->id==$user->id)) &&
			($currentUser->projectTemplatePermissions[$key].ChangeUserPermissions==ALLOW)) ||
			($currentUser->superadmin==ALLOW)  }		
			<a href="Javascript:deleteUserSimulation({$user->id},{$key})">Remove</a>
		{else}
			Not permitted
		{/if}
		</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" style="text-align:center">
			{$strings.MSG_NO_USERSIMULATIONS_FOUND}
			</td>
		</tr>
	{/foreach}
	{if $sPrevious>=0 or $sNext >=0}
		<tr>
		    <td>
				{if $sPrevious!= -1}
		    	<a href="?option=siteAdmin&cmd=viewUser&userId={$user->id}&sStart={$sPrevious}">Previous</a>
		    	{/if}
		    </td>
		    <td></td>
		    <td style="text-align:right;">
		    	{if $sNext != -1}
			    <a href="?option=siteAdmin&cmd=viewUser&userId={$user->id}&sStart={$sNext}">Next</a>
			    {/if}
		    </td>
	    </tr>
	{/if}		
	</table>

</div>
</div>
