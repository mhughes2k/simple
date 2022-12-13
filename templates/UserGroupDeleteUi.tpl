<script language="javascript">
{literal}
	function canceldelete() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('deleteDialog').style.display='none';
		document.getElementById('delete_documentid').value = '';
	}
{/literal}
</script>
	<div class="pop_up" id="deleteDialog">
		<div class="pop_up_title">{$strings.MSG_DELETE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="siteAdmin"/>
			<input type="hidden" name="cmd" value="deleteusergroup"/>
			<input type="hidden" name="method" value="deleteUserGroup"/>
			<input type="hidden" name="delete_usergroupid" id="delete_usergroupid" value=""/>
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
		//	alert('not deleting '+document.getElementById('delete_usergroupid').value);
			canceldelete();
			return false;
		}
		//alert('deleting '+document.getElementById('delete_usergroupid').value);
		return true;
	}
	function deleteUserGroup(usergroupid) {
		document.getElementById('delete_usergroupid').value = usergroupid;
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('deleteDialog').style.display='block';
	}
	function testMultiSubmit() {
		if (confirm("Are you sure you wish to do this?"))
			return true;
		else 
			return false;		
	}	
{/literal}
</script>