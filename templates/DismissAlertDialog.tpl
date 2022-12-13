<script language="javascript">
{literal}
	function cancelDismissAlertDialog() {
		//document.getElementById('dismissDialogBlock').style.display='none';
		//document.getElementById('dismissAlertDialog').style.display='none';
	}
{/literal}
</script>
<div class="pop_up_alert" id="dismissAlertDialog">
<div class="pop_up_title">{$strings.MSG_DISMISS_DIALOG_TITLE}</div>
<p>{$strings.MSG_CONFIRM_YN}</p>

<form action="index.php" method="post">
			<input type="hidden" name="option" value="office"/>
			<input type="hidden" name="cmd" value="dismissAlert"/>
			<input type="hidden" name="method" value="dismissAlert"/>
			<input type="hidden" name="dismiss_itemid" id="dismiss_itemid" value=""/>
      <input type="hidden" name="redir" id="redir" value="{$redir}"/>
			<input type="submit" value="{$strings.MSG_DISMISS}" onclick="return validatedismissalert();"/>
		  <a href="javascript:cancelDismissAlertDialog();" class="cancel">{$strings.MSG_CANCEL}</a>
</form>
</div>
<script language="javascript">
{literal}
	function validatedismissalert() {
		if(document.getElementById('dismiss_confirm').value != '1') {
			//alert('not deleting '+document.getElementById('dismiss_itemid').value);
			canceldelete();
			return false;
		}
		//alert('deleting '+document.getElementById('dismiss_itemid').value);
		return true;
	}
	function dismissAlertDialog(alertid) {
		//document.getElementById('dismiss_confirm').value='0';
		//document.getElementById('dismiss_itemid').value = alertid;
		//alert(document.getElementById('dismiss_itemid').value);
		//document.getElementById('dismissDialogBlock').style.display='block';
		//document.getElementById('dismissAlertDialog').style.display='block';
	}
{/literal}
</script>

