<script language="javascript">
{literal}
	function cancelremoveBP() {
		document.getElementById('dialogBlockBP').style.display='none';
		document.getElementById('deleteDialogBP').style.display='none';
		document.getElementById('useridBP').value = '';
	}
{/literal}
</script>
	<div class="pop_up" id="deleteDialogBP">
		<div class="pop_up_title">{$strings.MSG_REMOVE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="siteAdmin"/>
			<input type="hidden" name="cmd" value="deleteblueprintfromuser"/>
			<input type="hidden" name="blueprintId" id="blueprintId" value=""/>
			<input type="hidden" name="useridBP" id="useridBP" value=""/>
			<input type="hidden" name="userId" id="userId" value="{$user->id}"/>
			<input type="hidden" name="method" id="method" value="deleteBlueprintFromUser"/>
			<!--<select name="delete_confirmBP" id="delete_confirmBP">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>-->
			<input type="submit" value="{$strings.MSG_REMOVE}" onclick="return validateremoveBP();"/>
		<a href="javascript:cancelremoveBP();">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
<script language="javascript">
{literal}
	function validateremoveBP() {
		if(document.getElementById('delete_confirmBP').value != '1') {
			alert('not removing '+document.getElementById('blueprintId').value);
			cancelremoveBP();
			return false;
		}
		alert('removing '+document.getElementById('blueprintId').value);
		return true;
	}
	function deleteUserBlueprint(userid,blueprintId) {
		document.getElementById('useridBP').value = userid;
		document.getElementById('blueprintId').value = blueprintId;
		document.getElementById('dialogBlockBP').style.display='block';
		var dialog = document.getElementById('deleteDialogBP');
		var vOffset = document.getElementById('usersBlueprintPermissions').offsetTop;
		document.getElementById('deleteDialogBP').style.display='block';
		dialog.style.top = vOffset+"px";
	}
{/literal}
</script>