<div class="areaTitle">Add User Group</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
 <form name="usergroupform" action="index.php?option=siteAdmin&cmd=addUserGroup" method="post">
  	{if $message!=''}
	 	<p>{$message}</p>
 	{/if}
	<div>
		<div style="position:relative;top:0px;height:20px;width:190px;">Name*:</div>
		<div style="position:relative;top:-20px;left:200px;height:20px">
			<input type="text" name="groupName" id="groupName" 
			{if ($missingFields.groupName==1)} class="missingField"{/if}>
		</div>
	</div>
	{if (($currentUser->sitewidePermissions->EditUser==ALLOW) &&
		($currentUser->sitewidePermissions->MakeLevelZeroUser==ALLOW)) ||
		($currentUser->superadmin==ALLOW) }	
 	<p>If users are members of more than one group, they will need to have the appropriate permissions
	in BOTH groups or the system will default to Deny. Individual permissions can also be set for specific users,
	 which override all group permissions.</p>	 
	<div>
	<div style="position:relative;top:0px;height:20px;width:190px;">Add User:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="adduser" id="adduser">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Edit User:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="edituser" id="edituser">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Make Level Zero User:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="makelevelzerouser" id="makelevelzerouser">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Add Plugin:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="addplugin" id="addplugin">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Install Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="installtemplate" id="installtemplate">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:0px;left:0px;height:20px">Edit Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="edittemplate" id="edittemplate">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>
	
	<div>
	<div style="position:relative;top:00px;left:0px;height:20px">Remove Template:</div>
	<div style="position:relative;top:-20px;left:200px;height:20px;width:90px;">
		<select name="removetemplate" id="removetemplate">
			<option value="0">Unset</option>
			<option value="1">Allow</option>
			<option value="-1">Deny</option>
		</select>
	</div>
	</div>	
	{/if}
			
	<p {if ($missingFields)} class="highlightText"{/if}>Items marked * are required.</p>		
	<div>
	<input type="hidden" name="method" value="add" />
	<input type="submit" value="Create User Group"></div>
	</form>
</div>
</div>