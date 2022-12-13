<script language="javascript">
{literal}
	function cancelremoveS() {
		document.getElementById('dialogBlockS').style.display='none';
		document.getElementById('deleteDialogS').style.display='none';
		document.getElementById('userGroupIdS').value = '';
	}
{/literal}
</script>
	<div class="pop_up" id="deleteDialogS">
		<div class="pop_up_title">{$strings.MSG_REMOVE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="siteAdmin"/>
			<input type="hidden" name="cmd" value="deletesimulationfromusergroup"/>
			<input type="hidden" name="simulationId" id="simulationId" value=""/>
			<input type="hidden" name="userGroupId" id="userGroupIdS" value=""/>
			
			<input type="hidden" name="method" id="method" value="deleteSimulationFromUserGroup"/>
			<select name="delete_confirmS" id="delete_confirmS">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			<input type="submit" value="{$strings.MSG_REMOVE}" onclick="return validateremoveS();"/>
		<a href="javascript:cancelremoveS();">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
<script language="javascript">
{literal}
	function validateremoveS() {
		if(document.getElementById('delete_confirmS').value != '1') {
		//	alert('not removing '+document.getElementById('simulationId').value);
			cancelremoveS();
			return false;
		}
	//	alert('removing '+document.getElementById('simulationId').value);
		return true;
	}
	function deleteUserGroupSimulation(userGroupId,simulationId) {
		document.getElementById('userGroupIdS').value = userGroupId;
	
		document.getElementById('simulationId').value = simulationId;
		document.getElementById('dialogBlockS').style.display='block';
		var dialog = document.getElementById('deleteDialogS');
		var vOffset = document.getElementById('groupSimulationPermissions').offsetTop;		
		document.getElementById('deleteDialogS').style.display='block';
		dialog.style.top = vOffset+"px";
	}
{/literal}
</script>
