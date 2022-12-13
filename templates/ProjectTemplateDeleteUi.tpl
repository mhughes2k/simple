<script language="javascript">
{literal}
	function canceldelete(deleteDialog) {
		document.getElementById('dialogBlock').style.display='none';
		$("#"+deleteDialog).fadeOut('slow');
		//document.getElementById(deleteDialog).style.display='none';
		document.getElementById('delete_itemid').value = '';
		document.getElementById('delete_containerid').value = '';
		
	}
	function validatedelete(deleteDialog,delete_confirm) {

    if(document.getElementById(delete_confirm).value != '1') {
		//	alert('not deleting '+document.getElementById('delete_itemid').value);
			canceldelete(deleteDialog);
			return false;
		}
	//	alert('deleting '+document.getElementById('delete_itemid').value);
		return true;
	}
	function deleteItem(userid) {
	  
   	//document.getElementById('delete_confirm').value='0';
		document.getElementById('delete_itemid').value = userid;
		document.getElementById('dialogBlock').style.display='block';
		/*
		$("#deleteDialog").css('top','50%');
		$("#deleteDialog").css('margin-top',0-($("#deleteDialog").css('height')/2));
		$("#deleteDialog").css('left','50%');
		$("#deleteDialog").css('margin-left',0-($("#deleteDialog").css('width')/2));
		*/
		$("#deleteDialog").fadeIn('slow');
		//document.getElementById('deleteDialog').style.display='block';
	}
	function deleteProject(projectid){

  	document.getElementById('delete_containerid').value = projectid;
		document.getElementById('dialogBlock').style.display='block';
		/*
		$("#deleteProjectDialog").css('top','50%');
		$("#deleteProjectDialog").css('margin-top',0-($("#deleteProjectDialog").css('height')/2));
		$("#deleteProjectDialog").css('left','50%');
		$("#deleteProjectDialog").css('margin-left',0-($("#deleteProjectDialog").css('width')/2));
		*/
		//document.getElementById('deleteProjectDialog').style.display='block';
		$("#deleteProjectDialog").fadeIn('slow');
		//$("#deleteProjectDialog").show();
		
  }
	function testMultiSubmit() {
		if (confirm("Are you SURE you wish to do this? All simulations associated with this blueprint will also be deleted."))
			return true;
		else 
			return false;		
	}
{/literal}
</script>
	<div class="pop_up" id="deleteDialog">
		<div class="pop_up_title">{$strings.MSG_DELETE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="projectTemplateAdmin"/>
			<input type="hidden" name="cmd" value="listProjectTemplates"/>
			<input type="hidden" name="method" value="deleteTemplate"/>
			<input type="hidden" name="delete_itemid" id="delete_itemid" value=""/>
			<!--
      <select name="delete_confirm" id="delete_confirm">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			//-->
			<input type="submit" value="{$strings.MSG_DELETE}"/>
		<a href="javascript:canceldelete('deleteDialog');">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
	<div class="pop_up" id="deleteProjectDialog" style="width:200px">
		<div class="pop_up_title">{$strings.MSG_PROJECT_REMOVE_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="projectTemplateAdmin"/>
			<input type="hidden" name="cmd" value="listprojecttemplates"/>
			<input type="hidden" name="method" value="removeproject"/>
			<input type="hidden" name="containerid" id="delete_containerid" value=""/>
			<!--
      <select name="delete_confirm" id="delete_confirm">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			//-->
			<input type="submit" value="{$strings.MSG_DELETE}"/>
		<a href="javascript:canceldelete('deleteProjectDialog');">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
