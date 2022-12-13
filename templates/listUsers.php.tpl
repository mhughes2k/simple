 <div id="dialogBlock" name="copyform" ></div>
	{include file="UserDeleteUi.tpl"}
{literal}
<script type="text/javascript" language="javascript" src="methods.js"></script>
{/literal}
<div class="areaTitle">Users & Firms</div>
<div class="manageSectionContent">

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Users</a></li>
		<li><a href="#tabs-2">Import Users</a></li>
		<li><a href="#tabs-3">Delete Users</a></li>
		<li><a href="#tabs-4">{$strings.MSG_GROUPS}</a></li>
		<li><a href="#tabs-5">{$strings.MSG_IMPORT_GROUPS}</a></li>
		<li><a href="#tabs-6">{$strings.MSG_DELETE_GROUPS}</a></li>				
	</ul>
	<div id="tabs-1">
{include file="userSearchDialog.tpl"}
<a href="index.php?option=siteAdmin&cmd=addUser">Add a new user<img src="{$config.user_icon_add}"></a><br />
<form method="post" name="fieldsform" id="fieldsform" action="index.php?option=siteAdmin&cmd=multiUserSubmit&method=multiSubmit
{if ($searchTerm) }&searchTerm={$searchTerm}{/if}{if ($searchBy) }&searchBy={$searchBy}{/if}">
    <table id="usertable" style="width:100%;">
	    {if $previous >=0 or $next >= 0}
	    <tr>
		    <td colspan="3">
				{if $previous!= -1}
		    	<a href="?option=siteAdmin&cmd=listUsers&start={$previous}{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}
		    	"><img src="{$config.system_icon_left}" title="{$strings.MSG_PREVIOUS}"/>{$strings.MSG_PREVIOUS}</a>
		    	{/if}
		    </td>
		    <td colspan="5"></td>
		    <td style="text-align:right;">
		    	{if $next != -1}
			    <a href="?option=siteAdmin&cmd=listUsers&start={$next}{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}			    
			    ">{$strings.MSG_NEXT}<img src="{$config.system_icon_right}" title="{$strings.MSG_NEXT}"/></a>
			    {/if}
		    </td>
	    </tr>
	    {/if}
		<tr>
			<th></th>
			<th>Active<br/>
			<a href="index.php?option=siteAdmin&cmd=listUsers&orderBy=active ASC
			{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
			{if ($searchBy) }&searchBy={$searchBy}{/if}">
			<img src="{$config.system_icon_ascending}"></a>
			<a href="index.php?option=siteAdmin&cmd=listUsers&orderBy=active DESC
			{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
			{if ($searchBy) }&searchBy={$searchBy}{/if}">
			<img src="{$config.system_icon_descending}"></a>				
			</th>
			<th>User Name<br />
			<a href="index.php?option=siteAdmin&cmd=listUsers&orderBy=username ASC
			{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
			{if ($searchBy) }&searchBy={$searchBy}{/if}">
			<img src="{$config.system_icon_ascending}"></a>
			<a href="index.php?option=siteAdmin&cmd=listUsers&orderBy=username DESC
			{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
			{if ($searchBy) }&searchBy={$searchBy}{/if}">
			<img src="{$config.system_icon_descending}"></a>			
			</th>
			<th>Display Name<br /> 
			<a href="index.php?option=siteAdmin&cmd=listUsers&orderBy=displayName ASC
			{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
			{if ($searchBy) }&searchBy={$searchBy}{/if}">
			<img src="{$config.system_icon_ascending}"></a>
			<a href="index.php?option=siteAdmin&cmd=listUsers&orderBy=displayName DESC
			{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
			{if ($searchBy) }&searchBy={$searchBy}{/if}">
			<img src="{$config.system_icon_descending}"></a>
			</th>
			<th>{$strings.MSG_GROUPS}</th>
		</tr>
	{foreach from=$usersArray key=key item=user name=usertable}
	<tr {if $smarty.foreach.usertable.index % 2 ==0} class="usertable_alternate_row" {/if}>
	<td><input type="checkbox" name="selected_fld[]" value="{$user->id}" id="selected_fld[]"/>
	<img src="ImageHandler.php?context=avatar&userId={$user->id}&type={$user->imagetype}" width="20">
	</td>
	<td style="text-align:left">{if ($user->active==1) }<span style="color:green;">active</span>{else}<span style="color:red;">inactive</span>{/if}</td>
	<td><a href="index.php?option=siteAdmin&cmd=viewUser&userId={$user->id}">
	{if ($user->userName=='')}{$user->GetAuthenticationId()}{else}{$user->userName}{/if}</a>
	{if $user->userName==''}<img src="{$config.user_icon_external}" title="External User">{/if}
	</td>

	<td>{$user->displayName}</td>


	<td><a href="index.php?option=siteAdmin&cmd=viewUser&userId={$user->id}#groups">{$strings.MSG_GROUPS}<img src="{$config.group_icon}"></a></td>

	</tr>
	{foreachelse}
		<tr>
			<td colspan="7" style="text-align:center">
				{$strings.MSG_NO_USERS_FOUND}
			</td>
		</tr>
	{/foreach}
		<tr>
			<td colspan="9">
				<img src="{$config.table_icon_arrow}" title="arrow"/>
				<a onClick='javascript:markAllRows("usertable");' style='cursor: pointer;'>Check all</a> /
				<a onClick='javascript:unMarkAllRows("usertable");' style='cursor: pointer;'>Uncheck all</a>
				With selected:
				{if ($currentUser->sitewidePermissions.EditUser==ALLOW) || 
					($currentUser->sitewidePermissions.MakeLevelZeroUser==ALLOW) || 
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit"  value="delete" title="delete"
				onClick="javascript:return testMultiSubmit();" />
				
				{/if}
				{if ($currentUser->sitewidePermissions.EditUser==ALLOW) ||
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="enable" title="enable"
				onClick="javascript:return testMultiSubmit();" />
				
				<input name="multi_submit" type="submit" value="disable" title="disable"
				onClick="javascript:return testMultiSubmit();" />
				
				{/if}
				{if ($currentUser->sitewidePermissions.MakeLevelZeroUser==ALLOW) || 
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="export as CSV" title="export as CSV"
				onClick="javascript:return testMultiSubmit();" />								
				{/if}
			</td>
		</tr>
	    {if $previous>=0 or $next >=0}
	    <tr>
		    <td colspan="3">
				{if $previous!= -1}
		    	<a href="?option=siteAdmin&cmd=listUsers&start={$previous}{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}
		    	"><img src="{$config.system_icon_left}" title="Previous"/>Previous</a>
		    	{/if}
		    </td>
		    <td colspan="5"></td>
		    <td style="text-align:right;">
		    	{if $next != -1}
			    <a href="?option=siteAdmin&cmd=listUsers&start={$next}{if ($searchTerm) }&searchTerm={$searchTerm}{/if}
				{if ($searchBy) }&searchBy={$searchBy}{/if}
			    ">Next<img src="{$config.system_icon_right}" title="Next"/></a>
			    {/if}
		    </td>
	    </tr>
	    {/if}
</table>
<input type="hidden" name="testinput" value="testvalue">
</form>
	</div>
	
	<div id="tabs-2">
	<p>Upload a CSV file to create users. See docs for format.</p>
	{if $csverror}<p class="highlightText">{$csverror}</p>{/if}
	<form name="deleteMulti" action="index.php?option=siteAdmin&cmd=importMultiUsers&method=importMultiUsers" method="post" enctype="multipart/form-data">
	<input type="file" size="40" name="importusers"/>
	<input type="submit" value="Import">
	</form>
	</div>
	
	<div id="tabs-3">
	<p>Enter the Username or UserId of each user you wish to delete, separated by a comma(",").</p>
	<p>Note: External users cannot be deleted by username.</p>
	<form name="deleteMulti" action="index.php?option=siteAdmin&cmd=deleteMultiUsers&method=deleteMultiUsers" method="post">
	<textarea rows="10" cols="40" name="deleteusers"></textarea>
	<input type="submit" value="Delete">
	</form>
	</div>
	
	<div id="tabs-4">
	{include file="groupSearchDialog.tpl"}
	
	<p>This page allows you to create and edit groups of users in order to bundle the allocation of 
	user permissions. Note that deleting a user group does not delete the users that are members of that group -
	these must be deleted separately.</p>
	<a href="index.php?option=siteAdmin&cmd=addUserGroup">Add a new group<img src="{$config.group_icon_add}"></a><br />
	{if ($someresults==1)}
	<form method="post" name="fieldsform" id="fieldsform" 
	action="index.php?option=siteAdmin&cmd=multiUserGroupSubmit&gmethod=multiSubmit{if ($gsearchTerm) }&gsearchTerm={$gsearchTerm}{/if}{if ($gsearchBy) }&gsearchBy={$gsearchBy}{/if}"
	>	
	    <table id="grouptable" style="width:100%;">
	    	{if $previous >=0 or $next >= 0}
			    <tr>
				    <td>
						{if $previous!= -1}
				    	<a href="?option=siteAdmin&cmd=listUserGroups&gstart={$previous}
	    				{if ($searchTerm) }&gsearchTerm={$searchTerm}{/if}
						{if ($searchBy) }&gsearchBy={$searchBy}{/if}">Previous</a>
				    	{/if}
				    </td>
				    <td colspan="2"></td>
				    <td style="text-align:right;">
				    	{if $next != -1}
					    <a href="?option=siteAdmin&cmd=listUserGroups&gstart={$next}
						{if ($gsearchTerm) }&gsearchTerm={$gsearchTerm}{/if}
						{if ($gsearchBy) }&gsearchBy={$gsearchBy}{/if}">
						Next</a>
					    {/if}
				    </td>
			    </tr>
	    {/if}
		{foreach from=$groups key=key item=group name=grouptable}
			{if $smarty.foreach.grouptable.index % 20 == 0} 
				<tr>
				<th></th>
				<th>Active<br/>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=active ASC
				{if ($gsearchTerm) }&gsearchTerm={$gsearchTerm}{/if}
				{if ($gsearchBy) }&gsearchBy={$gsearchBy}{/if}">				
				<img src="{$config.system_icon_ascending}"></a>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=active DESC
				{if ($gsearchTerm) }&gsearchTerm={$gsearchTerm}{/if}
				{if ($gsearchBy) }&gsearchBy={$gsearchBy}{/if}">
				<img src="{$config.system_icon_descending}"></a>					
				</th>
				<th>Group Name<br/>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=name ASC
				{if ($gsearchTerm) }&gsearchTerm={$searchTerm}{/if}
				{if ($gsearchBy) }&gsearchBy={$searchBy}{/if}">				
				<img src="{$config.system_icon_ascending}"></a>
				<a href="index.php?option=siteAdmin&cmd=listUserGroups&orderBy=name DESC
				{if ($gsearchTerm) }&gsearchTerm={$searchTerm}{/if}
				{if ($gsearchBy) }&gsearchBy={$searchBy}{/if}">
				<img src="{$config.system_icon_descending}"></a>							
				</th>
				<th>Simulations</th>
				</tr>
			{/if}

			<tr {if $smarty.foreach.grouptable.index % 2 ==0} class="grouptable_alternate_row" {/if}>
			<td><input type="checkbox" name="gselected_fld[]" value="{$group->id}" id="checkbox_row_{$smarty.foreach.grouptable.index}"/></td>			
			<td style="text-align:left">{if ($group->active==1) }<span style="color:green;">active</span>{else}<span style="color:red;">inactive</span>{/if}</td>
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
				onClick="return testMultiSubmit();" />{/if}
				{if ($currentUser->sitewidePermissions.EditUser==ALLOW) ||
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="enable" title="enable"
				onClick="return testMultiSubmit();" />
				<input name="multi_submit" type="submit" value="disable" title="disable" 
				onClick="return testMultiSubmit();" />{/if}				
				{if ($currentUser->sitewidePermissions.MakeLevelZeroUser==ALLOW) || 
					($currentUser->superadmin==ALLOW) }
				<input name="multi_submit" type="submit" value="exportcsv" title="export as CSV" 
				onClick="return testMultiSubmit();" />{/if}				
			</td>
		</tr>		
		{if $gprevious >=0 or $gnext >= 0}
			    <tr>
				    <td>
						{if $gprevious!= -1}
				    	<a href="?option=siteAdmin&cmd=listUserGroups&start={$gprevious}
						{if ($gsearchTerm) }&gsearchTerm={$gsearchTerm}{/if}
						{if ($gsearchBy) }&gsearchBy={$gsearchBy}{/if}">
				    	{$strings.MSG_PREVIOUS}</a>
				    	{/if}
				    </td>
				    <td colspan="5"></td>
				    <td style="text-align:right;">
				    	{if $gnext != -1}
					    <a href="?option=siteAdmin&cmd=listUserGroups&start={$gnext}
						{if ($gsearchTerm) }&gsearchTerm={$gsearchTerm}{/if}
						{if ($gsearchBy) }&gsearchBy={$gsearchBy}{/if}">					    
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
	
	<div id="tabs-5">
	<p>Upload a CSV file to create {$strings.MSG_GROUPS}.</p>
	{if $csverror}<p class="highlightText">{$csverror}</p>{/if}
	<form name="deleteMulti" action="index.php?option=siteAdmin&cmd=importMultiUserGroups&gmethod=importMultiUserGroups" method="post" enctype="multipart/form-data">
	<input type="file" size="40" name="importusergroups"/>
	<input type="submit" value="Import">
	</form>
	</div>	
	
	<div id="tabs-6">
	<p>Enter the Name or ID of {$strings.MSG_GROUP} you wish to delete, separated by a comma(","). Do not include
	a space between the comma and the {$strings.MSG_GROUP} name.</p>
	<p>Note: Deleting a {$strings.MSG_GROUP} <strong>does not</strong> delete the members from the system!</p>
	<form name="deleteMulti" action="index.php?option=siteAdmin&cmd=deleteMultiUserGroups&gmethod=deleteMultiUserGroups{if ($searchTerm) }&searchTerm={$searchTerm}{/if}{if ($searchBy) }&searchBy={$searchBy}{/if}" method="post">
	<textarea rows="5" cols="40" name="deleteusergroups"></textarea>
	<input type="submit" value="Delete">
	</form>
	</div>	
	
</div>

</div>