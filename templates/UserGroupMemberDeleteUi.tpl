<script language="javascript">
{literal}
	function cancelremove() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('deleteDialog').style.display='none';
		document.getElementById('userid').value = '';
		document.getElementById('userGroupId').value = '';
	}
{/literal}
</script>
	<div class="pop_up" id="deleteDialog">
		<div class="pop_up_title">{$strings.MSG_REMOVE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="siteAdmin"/>
			<input type="hidden" name="cmd" value="removememberfromgroup"/>
			<input type="hidden" name="method" value="RemoveMemberFromGroup"/>
			<input type="hidden" name="userGroupId" id="userGroupId" value=""/>
			<input type="hidden" name="userid" id="userid" value=""/>
			<select name="delete_confirm" id="delete_confirm">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			<input type="submit" value="{$strings.MSG_REMOVE}" onclick="return validateremove();"/>
		<a href="javascript:cancelremove();">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
<script language="javascript">
{literal}
	function validateremove() {
		if(document.getElementById('delete_confirm').value != '1') {
			//alert('not removing '+document.getElementById('userid').value);
			cancelremove();
			return false;
		}
		//alert('removing '+document.getElementById('userid').value);
		return true;
	}
	function removeMember(userid,userGroupId) {
		document.getElementById('userid').value = userid;
		document.getElementById('userGroupId').value = userGroupId;
		document.getElementById('dialogBlock').style.display='block';	
		var dialog = document.getElementById('deleteDialog');
		var vOffset = document.getElementById('usergroupmembers').offsetTop;		
		document.getElementById('deleteDialog').style.display='block';
		dialog.style.top = vOffset+"px";		
		
		
	}
{/literal}
</script>