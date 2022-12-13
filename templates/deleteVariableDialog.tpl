	<div id="dialogBlock" name="copyform" ></div>
	<script language="javascript">
	{literal}
		function canceldelete() {
			document.getElementById('dialogBlock').style.display='none';
			document.getElementById('deleteDialog').style.display='none';
			document.getElementById('delete_itemid').value = '';
	
		}
	{/literal}
	</script>
		<div class="pop_up" id="deleteDialog" style="display:none;">
			<div class="popup_title">Delete Variable</div>
			<p>Are you sure?</p>
			<form action="index.php" method="post">
				<input type="hidden" name="option" value="{$option_target}"/>
				<input type="hidden" name="cmd" value="deletevar"/>
				<input type="hidden" name="pid" id='pid' value="{$pid}"/>
				<input type="hidden" name="varname" id="delete_itemid" value=""/>
				<select name="delete_confirm" id="delete_confirm">
					<option value="0">No</option>
					<option value="1">Yes</option>					
				</select>
				<input type="submit" value="Delete" onclick="return validatedelete();"/>
			<a href="javascript:canceldelete();">Cancel</a>
			</form>
		</div>
		
	<script language="javascript">
	{literal}
		function deleteVariableDialog(varid) {
			document.getElementById('delete_itemid').value = varid;
			document.getElementById('dialogBlock').style.display='block';
			var dialog = document.getElementById('deleteDialog');
			var vOffset = document.getElementById('variablelist').offsetTop;
			document.getElementById('deleteDialog').style.display='block';
			dialog.style.top = vOffset+"px";			
		}
		function validatedelete() {
			if(document.getElementById('delete_confirm').value != '1') {
				alert('not deleting '+document.getElementById('delete_itemid').value);
				canceldelete();
				return false;
			}
			//alert('deleting '+document.getElementById('delete_itemid').value);
			return true;
		}
	{/literal}
	</script>
