<script language="javascript">
{literal}
	function canceldelete() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('deleteDialog').style.display='none';
		document.getElementById('userGroupId').value = '';
		document.getElementById('projectid').value = '';
	}
{/literal}
</script>
	<div class="pop_up" id="deleteDialog">
		<div class="pop_up_title">{$strings.MSG_DELETE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="siteAdmin"/>
			<input type="hidden" name="cmd" value="deleteprojectfromusergroup"/>
			<input type="hidden" name="userGroupId" id="userGroupId" value=""/>
			<input type="hidden" name="projectid" id="projectid" value=""/>
			<select name="delete_confirm" id="delete_confirm">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			<input type="submit" value="{$strings.MSG_DELETE}" onclick="return validatedelete();"/>
		<a href="javascript:canceldelete();">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
<script language="javascript">
{literal}
	function validatedelete() {
		if(document.getElementById('delete_confirm').value != '1') {
		//	alert('not deleting '+document.getElementById('userGroupId').value+','+document.getElementById('projectid').value);
			canceldelete();
			return false;
		}
	//	alert('deleting '+document.getElementById('userGroupId').value+','+document.getElementById('projectid').value);
		return true;
	}
	function deleteUserGroupProject(userid,projectid) {
		document.getElementById('userGroupId').value = userid;
		document.getElementById('projectid').value = projectid;
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('deleteDialog').style.display='block';
	}
{/literal}
</script>