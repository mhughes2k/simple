<div id="dialogBlock" name="copyform" ></div>
{include file="UserGroupDeleteUi.tpl"}
{literal}
<script type="text/javascript" language="javascript" src="methods.js"></script>
<script type="text/javascript" language="javascript">
/**
* checks the permissions of the current user against those of the selected simulation to see whether or not to
* grey out the buttons
* @param	menu	dropdown id
* @param 	allowed	whether or not the user has the permission
*/
function CheckSimPerms(menu,manageButton,enterButton) {
	var simId = GetDropdown(menu);
	var manageButton = document.getElementById(manageButton);	
	var enterButton = document.getElementById(enterButton);

	// create arrays of permissions
	var superadmin = {/literal}{$currentUser->superadmin};
	var viewItemPermissions = new Array();
	var useStaffToolsPermissions = new Array();
 	{foreach from=$currentUser->projectPermissions key=key item=perm name=permlist}
			viewItemPermissions["{$key}"]='{$perm.ViewItem}';
			useStaffToolsPermissions["{$key}"]='{$perm.UseStaffTools}';
	{/foreach}	
	{literal}
	
	// now grey out/disable buttons if no permissions
	if ((viewItemPermissions[simId]==1) || (superadmin==1)) {
		enterButton.disabled=false;
	} else {
		enterButton.disabled=true;
	}
	if ((useStaffToolsPermissions[simId]==1) || (superadmin==1)) {
		manageButton.disabled=false;
	} else {
		manageButton.disabled=true;
	}
}

</script>
{/literal}	
<div class="areaTitle">User Groups</div>
<div class="manageSectionContent">
{include file="groupSearchDialog.tpl"}
<div class="sectionBox_manage">
	<div class="sectionTitle">User Groups</div>		
	<p>This page allows you to create and edit groups of users in order to bundle the allocation of 
	user permissions. Note that deleting a user group does not delete the users that are members of that group -
	these must be deleted separately.</p>
	<a href="index.php?option=siteAdmin&cmd=addUserGroup">Add a new group<img src="{$config.group_icon_add}"></a><br />
	{if ($someresults==1)}
	<form method="post" name="fieldsform" id="fieldsform" 
	action="index.php?option=siteAdmin&cmd=multiUserGroupSubmit&method=multiSubmit{if ($searchTerm) }&searchTerm={$searchTerm}{/if}{if ($searchBy) }&searchBy={$searchBy}{/if}"
	>	
	    <table id="grouptable" style="width:100%;">
	    	{if $previous >=0 or $next >= 0}
			    <tr>
				    <td>
						{if $previous!= -1}
				    	<a href="?option=siteAdmin&cmd=listUserGroups&start={$previous}
	    				{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
						{if ($searchBy) }&searchBy={$searchBy}{/if}">	    	
				    	">Previous</a>
				    	{/if}
				    </td>
				    <td colspan="5"></td>
				    <td style="text-align:right;">
				    	{if $next != -1}
					    <a href="?option=siteAdmin&cmd=listUserGroups&start={$next}
						{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
						{if ($searchBy) }&searchBy={$searchBy}{/if}">
						">Next</a>
					    {/if}
				    </td>
			    </tr>
	    {/if}
		{foreach from=$groups key=key item=group name=grouptable}
			{if $smarty.foreach.grouptable.index % 20 == 0} 
				<tr>
				<th></th>
				<th>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=active ASC
				{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}">				
				<img src="{$config.system_icon_ascending}"></a>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=active DESC
				{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}">
				<img src="{$config.system_icon_descending}"></a>					
				</th>
				<th style="width:80px">Group ID</th>
				<th>Group Name
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=name ASC
				{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}">				
				<img src="{$config.system_icon_ascending}"></a>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=name DESC
				{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}">
				<img src="{$config.system_icon_descending}"></a>							
				</th>
				<th>Simulations</th>
				</tr>
			{/if}

			<tr {if $smarty.foreach.grouptable.index % 2 ==0} class="grouptable_alternate_row" {/if}>
			<td><input type="checkbox" name="selected_fld[]" value="{$group->id}" id="checkbox_row_{$smarty.foreach.grouptable.index}"/></td>			
			<td style="text-align:center">{if ($group->active==1) }<img src="{$config.system_icon_enabled}">{else}<img src="{$config.system_icon_disabled}">{/if}</td>
			<td style="text-align:center">{$group->id}</td>
			<td><a href="index.php?option=siteAdmin&cmd=viewUserGroup&userGroupId={$group->id}">
			{$group->name}</a></td>			
			<td>
			
			{foreach from=$sims key=key item=sim name=simlist}
				{if ($key==$group->id) }
					{if (count($sim)>0) }
					<select name="simulation" id="sim_dropdown_{$smarty.foreach.grouptable.index}" 
					onchange="Javascript:CheckSimPerms('sim_dropdown_{$smarty.foreach.grouptable.index}',
						'managesim_button_{$smarty.foreach.grouptable.index}',
						'entersim_button_{$smarty.foreach.grouptable.index}');">
					{/if}
					{foreach from=$sim key=key2 item=s}
						<option value="{$key2}">{$s}</option>
					{/foreach}
					{if (count($sim)>0) }
					</select>
					<input type="button" id="managesim_button_{$smarty.foreach.grouptable.index}" value="Manage" onclick="Javascript:ManageSim('sim_dropdown_{$smarty.foreach.grouptable.index}');"/>
					<input type="button" id="entersim_button_{$smarty.foreach.grouptable.index}" value="Go..." onclick="Javasrcipt:EnterSim('sim_dropdown_{$smarty.foreach.grouptable.index}');"/>					
					<script type="text/javascript" language="javascript">
					CheckSimPerms('sim_dropdown_{$smarty.foreach.grouptable.index}',
						'managesim_button_{$smarty.foreach.grouptable.index}',
						'entersim_button_{$smarty.foreach.grouptable.index}');
					</script>
					{else}
					None assigned
					{/if}
				{/if}
			{/foreach}
					
			</td>
			</tr>
		{/foreach}
			<tr>
			<td colspan="10">
				<img src="{$config.table_icon_arrow}" title="arrow"/>
				<a onClick='javascript:markAllRows("grouptable");' style='cursor: pointer;'>Check all</a> /
				<a onClick='javascript:unMarkAllRows("grouptable");' style='cursor: pointer;'>Uncheck all</a>
				With selected:
				{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || 
					($currentUser->sitewidePermissions.MakeLevelZeroUser==ALLOW) || 
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="delete" title="delete"
				onClick="return testMultiSubmit();" />
					<img src="{$config.system_icon_delete}" title="delete" alt="delete" />
				{/if}
				{if ($currentUser->sitewidePermissions.EditUser==ALLOW) ||
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="enable" title="enable"
				onClick="return testMultiSubmit();" />
					<img src="{$config.system_icon_enabled}" title="enable" alt="enable" />
				<input name="multi_submit" type="submit" value="disable" title="disable" 
				onClick="return testMultiSubmit();" />
					<img src="{$config.system_icon_disabled}" title="disable" alt="disable"/>
				{/if}				
				{if ($currentUser->sitewidePermissions.MakeLevelZeroUser==ALLOW) || 
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="exportcsv" title="export as CSV" 
				onClick="return testMultiSubmit();" />
					<img src="{$config.system_icon_exportcsv}" title="export as CSV" alt="export as CSV"/>
				{/if}				
			</td>
		</tr>		
		{if $previous >=0 or $next >= 0}
			    <tr>
				    <td>
						{if $previous!= -1}
				    	<a href="?option=siteAdmin&cmd=listUserGroups&start={$previous}
						{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
						{if ($searchBy) }&searchBy={$searchBy}{/if}">
				    	{$strings.MSG_PREVIOUS}</a>
				    	{/if}
				    </td>
				    <td colspan="5"></td>
				    <td style="text-align:right;">
				    	{if $next != -1}
					    <a href="?option=siteAdmin&cmd=listUserGroups&start={$next}
						{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
						{if ($searchBy) }&searchBy={$searchBy}{/if}">					    
					    {$strings.MSG_NEXT}</a>
					    {/if}
				    </td>
			    </tr>
	    {/if}
		</table>
		</form>
	{else}
	No matches.
	{/if}
	<br>

</div>
<div class="sectionBox_manage">
  <div class="sectionTitle">Import User Groups File</div>
	<p>Upload a CSV file to create user groups.</p>
	{if $csverror}<p class="highlightText">{$csverror}</p>{/if}
	<form name="deleteMulti" action="index.php?option=siteAdmin&cmd=importMultiUserGroups&method=importMultiUserGroups" method="post" enctype="multipart/form-data">
	<input type="file" size="40" name="importusergroups"/>
	<input type="submit" value="Import">
	</form>
</div>

<div class="sectionBox_manage">
	<div class="sectionTitle">Delete Group(s)</div>
	<p>Enter the Name or ID of group you wish to delete, separated by a comma(","). Do not include
	a space between the comma and the group name.</p>
	<p>Note: Deleting a group <strong>does not</strong> delete the members from the system!</p>
	<form name="deleteMulti" action="index.php?option=siteAdmin&cmd=deleteMultiUserGroups&method=deleteMultiUserGroups{if ($searchTerm) }&searchTerm={$searchTerm}{/if}{if ($searchBy) }&searchBy={$searchBy}{/if}" method="post">
	<textarea rows="5" cols="40" name="deleteusergroups"></textarea>
	<input type="submit" value="Delete">
	</form>
</div>
</div>

