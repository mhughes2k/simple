	<div id="dialogBlock" name="copyform" ></div>
	<script language="javascript">
	{literal}
		function cancel() {
		/*
			document.getElementById('dialogBlock').style.display='none';
			document.getElementById('nedNoteDialog').style.display='none';
			document.getElementById('ned_eventid').value = '';
	*/
		}
		function cancelTrigger() {
			/*document.getElementById('triggerEventDialog').style.display='none';
			document.getElementById('triggereventid').value = '';
			*/
		}
	{/literal}
	</script>
		<div id="nedNoteDialog" class="pop_up">
			<div class="pop_up_title">Set/Change Task note.</div>
			<p>Please enter a note.</p>
			<form action="index.php" method="post">
				<input type="hidden" name="option" value="projectAdmin"/>
				<input type="hidden" name="cmd" value="seteventstate"/>
				<input type="hidden" name="pid" id='pid' value="{$pid}"/>
				<input type="hidden" name="ned_eventid" id="ned_eventid" value=""/>
				<input type="hidden" name="redir" value="{$ned_redir}"/>
				<textarea name="state" id="state"></textarea>
				<br />
				Flag As:
				<select name="color">
					<option value='ffffff' style="background-color:#ffffff">None</a>
					<option value='00FF00' style="background-color:#00FF00">Completed</a>
					<option value='FF0000' style="background-color:#FF0000">Failed</a>
										
				</select>
				<br />
				<input type="submit" value="OK" onclick="return validatedelete();"/>
			<a class="cancel">Cancel</a>
			</form>
		</div>
		
		<div id="triggerEventDialog" class="pop_up" style="display:none;">
			<div class="popup_title">Trigger Event</div>
			<p>Are you sure you want to trigger this event?</p>
			<form action="index.php" method="post">
				<input type="hidden" name="option" value="projectAdmin"/>
				<input type="hidden" name="cmd" value="triggerevent"/>
				<input type="hidden" name="id"  id="triggereventid" value=""/>
				<input type="hidden" name="pid" id="projectid" value="{$pid}"/>
				<input type="submit" value="Trigger"/>
				<a  class="cancel" href="javascript:cancelTrigger();">Cancel</a>
				
			</form>
		</div>
		
		
	<script language="javascript">
	{literal}
		function setNedDialog(eventid,content) {
			document.getElementById('ned_eventid').value = eventid;
			document.getElementById('state').value = content;
			document.getElementById('dialogBlock').style.display='block';
			document.getElementById('nedNoteDialog').style.display='block';
		}
		function validatedelete() {
			if(document.getElementById('delete_confirm').value != '1') {
				canceldelete();
				return false;
			}
			return true;
		}
		function triggerNedEvent(id,pid) {
			document.getElementById('triggerEventDialog').style.display='block';
			document.getElementById('triggereventid').value = id;
			//if (confirm('are you sure you want to trigger this event?')) {
			//	window.location = '?option=projectadmin&cmd=triggerevent&id='+id+'&pid='+pid+'';
			// }
		}
	{/literal}
	</script>
