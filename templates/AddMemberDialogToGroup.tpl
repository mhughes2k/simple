{literal}
<script type="text/javascript" language="javascript" src="methods.js"></script>
{/literal}
<script language="javascript">
{literal}
	function cancelAddMemberDialog() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('addMemberDialog').style.display='none';
	}
	function ShowAddMemberDialog(option,command,otherargs) {
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('addMemberDialog').style.display='block';
	}
	function searchMembers() {
		searchby = document.getElementById('searchBy').value;
		searchstring = document.getElementById('searchString').value;
		var userresults = new Array();
		var users = new Array();
{/literal}
	{foreach from=$addusersdialog item=user}
		users[{$user->id}]=new Array('{$user->userName|escape:"html"}','{$user->displayName|escape:"html"}','{$user->id}','{$user->email}','{$user->regNumber}');
	{/foreach}		
{literal}		
		for (keyVar in users) {	
			var str = users[keyVar][searchby];
			var reg = new RegExp(searchstring, 'i');
			if (reg.exec(str)) {
				userresults[keyVar]= users[keyVar];
			} else {
			//	alert("not found when searching "+searchby+" by "+searchstring);
			}
		}
		var checkboxeshtml = "";
		for (keyVar in userresults) {
			checkboxeshtml+= '<input type="checkbox" name="userId[]" value="'+userresults[keyVar][2]+'" />';
			checkboxeshtml+= userresults[keyVar][2]+' '+userresults[keyVar][1]+'<br/>';
		}
		document.getElementById('userCheckboxes').innerHTML = checkboxeshtml;
	}
	function showAllUsers() {
		userresults = users;
		var checkboxeshtml = "";
		for (keyVar in userresults) {
			checkboxeshtml+= '<input type="checkbox" name="userId[]" value="'+userresults[keyVar][2]+'" />';
			checkboxeshtml+= userresults[keyVar][2]+' '+userresults[keyVar][1]+'<br/>';
		}
		document.getElementById('userCheckboxes').innerHTML = checkboxeshtml;
	}
	
	var users = new Array();
	var userresults = new Array();
{/literal}
	{foreach from=$addusersdialog item=user}
		users[{$user->id}]=new Array('{$user->userName}','{$user->displayName}','{$user->id}','{$user->email}','{$user->regNumber}');
	{/foreach}
	userresults = users;
</script>
<div id="addMemberDialogBlock" class="pop_up"></div>
<div class="pop_up_large" id="addMemberDialog">
<!--
style="
position:absolute;
	top:200px;
	left:90px;
	width:500px;
	height:560px;	
	border:solid 1px black;
	display:none;
	background-color:white;
	z-index:1000;
	padding:5px;"
	-->

<div class="pop_up_title">Add User(s) To User Group</div>
<form action="index.php?option=siteAdmin&cmd=viewUserGroup&method=AddUser2UserGroup&userGroupId={$usergroup->id}#members" method="post">
<br>

	{$strings.MSG_SEARCH_MESSAGE_1} <select name="searchBy" id="searchBy">
		<option value="0" {if $searchBy==0} selected{/if}>UserName</option>
		<option value="1" {if $searchBy==1} selected{/if}>Display Name</option>
		<option value="2" {if $searchBy==2} selected{/if}>User ID</option>
		<option value="3" {if $searchBy==3} selected{/if}>Email</option>
		<option value="4" {if $searchBy==4} selected{/if}>Registration Number</option>
	</select><br>
	{$strings.MSG_SEARCH_MESSAGE_2}
	<input type="text" name="searchTerm" value="{$searchTerm}" id="searchString" /> 
	<input type="button" value="Search" onclick="javascript:searchMembers();"/> 
	<a href="javascript:showAllUsers();">Show All</a>


<p>Select User(s) to add to this User Group<br />
<a onClick='javascript:markAllRows("addMemberTable");' style='cursor: pointer;'>Check all</a> /
<a onClick='javascript:unMarkAllRows("addMemberTable");' style='cursor: pointer;'>Uncheck all</a>
</p>
<div style="overflow:scroll;height:420px;" id="userCheckboxes">
<table name="addMemberTable" id="addMemberTable">
<tr><td></td><td><strong>id</strong></td><td><strong>username</strong></td><td><strong>name</strong</td></tr>
<script language="javascript">
{literal}
for ( keyVar in userresults ) {
	//alert(users[keyVar]);
	document.writeln('<tr><td><input type="checkbox" name="userId[]" value="'+users[keyVar][2]+'" /></td>');
	document.writeln('<td>'+users[keyVar][2]+'</td><td>'+users[keyVar][0]+'</td>');
	document.writeln('<td>'+users[keyVar][1]+'</td></tr>');
}
{/literal}
</script>
</table>
</div>
<p>
<input type="submit" value="Add" />
<a href="javascript:cancelAddMemberDialog()">Cancel</a>
</p>


</form>
</div>
