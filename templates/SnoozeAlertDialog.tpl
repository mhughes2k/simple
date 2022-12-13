<script language="JavaScript" type="text/javascript" src="datetimepicker.js">
</script>
<script language="javascript">
{literal}
	function cancelSnoozeAlertDialog() {
		//document.getElementById('snoozeDialogBlock').style.display='none';
		//document.getElementById('snoozeDialogBlock').style.display='none';
		//document.getElementById('snoozeAlertDialog').style.display='none';
	}
	$(document).ready(
    function () {
      $(".snoozeAlert").click(
        function () {
          //alert('snoozing');
          // href="javascript:dismissAlertDialog('{$alert->id}');"
          //document.getElementById('snooze_itemid').value = snooze_id;
          var itemid=$(this).attr("itemid");
          //alert(itemid);
          $("#snooze_itemid").val(itemid);          
          $("#snoozeAlertDialog").fadeIn();
        }
      ) 
      $(".dismissAlert").click(
        function () {
          var itemid=$(this).attr("itemid");
          //alert(itemid);
          $("#dismiss_itemid").val(itemid);          
          $("#dismissAlertDialog").fadeIn();
        }  // href="javascript:dismissAlertDialog('{$alert->id}');
      )
    }
  )
{/literal}
</script>

<div id="snoozeAlertDialog" class="pop_up_alert">

<form action="index.php?option=office&cmd=snoozeAlert" method="post">
<input type="hidden" name="snooze_itemid" id="snooze_itemid" value="" />
<div class="pop_up_title">Snooze Alert</div>
<p>Please select a new time for the alert</p>
<div >


Remind Me at: <input type="text"id="alert_time" value="
{if $alertitem->id == -1}
{else}
{$alertitem->AlertTime}
{/if}" name="alert_time"><a href="javascript:NewCal('alert_time','ddmmmyyyy',true,24)"><img src="themes/{$config.THEME}/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><br />
Message: <input type="text" value="{if $alertitem->id == -1}

{else}
{$alertitem->Message}
{/if}" name="alert_message" />

</div>
<input type="submit" value="Snooze" />
<a class="cancel" href="javascript:cancelSnoozeAlertDialog()">Cancel</a>

</form>
</div>
{literal}
<script language="javascript">
	function snoozeAlertDialog(snooze_id) {
		document.getElementById('snooze_itemid').value = snooze_id;
		//document.getElementById('snoozeDialogBlock').style.display='block';
		//document.getElementById('snoozeDialogBlock').style.display='block';
		document.getElementById('snoozeAlertDialog').style.display='block';
	}
</script>
{/literal}

