<script language="javascript">


{literal}
	function cancelcopy() {
	/*
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('copyDialog').style.display='none';
		*/
		document.getElementById('copy_itemid').value = '';

	}
	function cancelmove() {
		/*
    document.getElementById('dialogBlock').style.display='none';
		document.getElementById('moveDialog').style.display='none';
		*/
		document.getElementById('move_itemid').value = '';

	}
	function cancelsend() {
		/*document.getElementById('dialogBlock').style.display='none';
		document.getElementById('sendDialog').style.display='none';
		*/
		document.getElementById('send_documentid').value = '';
	}
	function canceldelete() {
		/*
    document.getElementById('dialogBlock').style.display='none';
		document.getElementById('deleteDialog').style.display='none';
		*/
		document.getElementById('delete_documentid').value = '';
	}
	function cancelundelete() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('undeleteDialog').style.display='none';
		document.getElementById('undelete_documentid').value = '';
	}
	function selectAddress(address) {
		document.getElementById('to').value=address;
	}
{/literal}
</script>
{literal}
<script type="text/javascript">
 $(document).ready(function () {
  $("#sendDialog_submitButton").attr('disabled',false);
  $("#sendDialog_form").submit(function() {
      //alert('test');
      $("#sendDialog_submitButton").attr('disabled',true);
      //evt.preventDefault();
      return true;
  });
 
 });
 </script>
 {/literal}
	<div class="pop_up" id="copyDialog">
		<div class="pop_up_title">{$strings.MSG_COPY_DIALOG_TITLE}</div>
		<p>{$strings.MSG_COPY_DIALOG_MESSAGE}</p>
			<form action="index.php" method="post">
				<input type="hidden" name="option" value="office"/>
				<input type="hidden" name="cmd" value="copy"/>
				<input type="hidden" name="itemtype" value="doc"/>
				<input type="hidden" name="itemid" id="copy_itemid" value=""/>
				<select name="folderid" id="copy_destfolderid">
				{foreach from=$folders key=key item=folder}
					{if $folder->additem}
					<option value="{$folder->folderid}">{$folder->name}</option>
					{/if}
				{/foreach}
				</select>
				<input type="submit" value="{$strings.MSG_COPY_DIALOG_BUTTON}" />
				<a class="cancel" href="javascript:cancelcopy();">{$strings.MSG_CANCEL}</a>
			</form>
	</div>
	<div class="pop_up" id="moveDialog">
		<div class="pop_up_title">{$strings.MSG_MOVE_DIALOG_MESSAGE}</div>
		<p>{$strings.MSG_MOVE_DIALOG_MESSAGE}</p>
			<form action="index.php" method="post">
				<input type="hidden" name="option" value="office"/>
				<input type="hidden" name="cmd" value="moveitem"/>
				<input type="hidden" name="itemtype" value="doc"/>
				<input type="hidden" name="itemid" id="move_itemid" value=""/>
				<select name="folderid" id="move_destfolderid">
				{foreach from=$folders key=key item=folder}
					{if $folder->additem}
					<option value="{$folder->folderid}">{$folder->name}</option>
					{/if}
				{/foreach}
				</select>
				<input type="submit" value="{$strings.MSG_MOVE_DIALOG_BUTTON}" />
				<a class="cancel" href="javascript:cancelmove();">{$strings.MSG_CANCEL}</a>
			</form>
	</div>
	
	<div id="sendDialog" class="pop_up">	
	<div class="pop_up_title" id="sendDialog_Title">{$strings.MSG_SEND_DIALOG_TITLE}</div>
	<p>{$strings.MSG_SEND_DIALOG_SELECT_MESSAGE}</p>
	{if count($directory) >0}
		<form action="index.php" method="post" id="sendDialog_form">
		<input type="hidden" name="option" value="office" />
		<input type="hidden" name="cmd" value="send" />
		<input type="hidden" name="documentid" id="send_documentid" value="" />
		<div class="sendDialog">
			<table >
			<thead class="fixedheader">
			<tr>
			<th>{$strings.MSG_NAME_HEADER}</th><th>{$strings.MSG_EADDRESS_HEADER}</th><th>{$strings.MSG_ADDRESS_HEADER}</th>
			</thead>
			<tbody class="scrollcontent">
			{foreach from=$directory item=value}
			<tr>
				<td><a href="javascript:selectAddress('{$value->address}');">{$value->name}</a></td>
				<td >{$value->address}</td>
				<td>{$value->location}</td>
			</tr>
			{/foreach}
			</tbody>
			</table>
		</div>
		<div>
		<p>&nbsp;</p>			
			{$strings.MSG_RECIPIENT_HEADER}:<input type="text" name="to" id="to" value="{$to}"/><br />
			{if $showStaffTools}
			{$strings.MSG_FROM_HEADER}: <select name="sender" id="sender">
			  <option value="">**Send As Self**</option>
			  {foreach from=$directory item=value}
			  <option value="{$value->address}">{$value->projectrole} - {$value->name}</option>
			{/foreach}
		              </select>
				{else}
				
				{/if}
		</div>
		
		
			<input type="submit" value="{$strings.MSG_SEND_BUTTON}" id="sendDialog_submitButton"/>
      
      <a class="cancel" href="javascript:cancelsend();">{$strings.MSG_CANCEL}</a>
		
	{else}
		<p>&nbsp;</p>
		<p>{$strings.MSG_DIRECTORY_EMPTY}</p>
	{/if}
	</form>
	</div>
	<div class="pop_up" id="deleteDialog">
		<div class="pop_up_title">{$strings.MSG_DELETE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="office"/>
			<input type="hidden" name="cmd" value="deleteitem"/>
			<input type="hidden" name="itemtype" value="doc"/>
			<input type="hidden" name="itemid" id="delete_itemid" value=""/>
			<!--
      <select name="delete_confirm" id="delete_confirm">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			//-->
			<input type="submit" value="{$strings.MSG_DELETE}"/>
		<a class="cancel" onclick="javascript:canceldelete()">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
	<div class="pop_up" id="undeleteDialog">
		<div class="pop_up_title">{$strings.MSG_UNDELETE_DIALOG_TITLE}</div>
		<p>{$strings.MSG_CONFIRM_YN}</p>
		<form action="index.php" method="post">
			<input type="hidden" name="option" value="office"/>
			<input type="hidden" name="cmd" value="undeleteitem"/>
			<input type="hidden" name="itemtype" value="doc"/>
			<input type="hidden" name="itemid" id="undelete_itemid" value=""/>
			<!--
      <select name="delete_confirm" id="delete_confirm">
				<option value="0">{$strings.MSG_CONFIRM_N}</option>
				<option value="1">{$strings.MSG_CONFIRM_Y}</option>					
			</select>
			//-->
			<input type="submit" value="{$strings.MSG_UNDELETE}"/>
		<a class="cancel" href="javascript:cancelundelete();">{$strings.MSG_CANCEL}</a>
		</form>
	</div>
<script language="javascript">
{literal}
	function validatedelete() {
		/*
    if(document.getElementById('delete_confirm').value != '1') {
			alert('not deleting '+document.getElementById('delete_itemid').value);
			canceldelete();
			return false;
		}
		alert('deleting '+document.getElementById('delete_itemid').value);
		return true;
		*/
	}
	function sendDoc(docid) {
		document.getElementById('send_documentid').value = docid;
		document.getElementById('dialogBlock').style.display='block';
		var triggele ='folder_row_id_'+docid;

		var vOffset = document.getElementById(triggele).offsetTop;
		
		vOffset =  vOffset + document.getElementById('projectSectionContent').offsetTop +document.getElementById('document_table').offsetTop;
		//alert(vOffset);
		var dialog = document.getElementById('sendDialog');
		dialog.style.top = vOffset+"px";
		dialog.style.left = document.getElementById('projectSectionContent').offsetLeft+"px";
		document.getElementById('sendDialog').style.display='block';	
	
	}
	function copyDoc(docid) {
		document.getElementById('copy_itemid').value = docid;
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('copyDialog').style.display='block';		
	}
	function moveDoc(docid) {
		document.getElementById('move_itemid').value = docid;
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('moveDialog').style.display='block';
	}
	function deleteDoc(docid) {
		document.getElementById('delete_itemid').value = docid;
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('deleteDialog').style.display='block';
	}
	function undeleteDoc(docid) {
		document.getElementById('undelete_itemid').value = docid;
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('undeleteDialog').style.display='block';
	}
{/literal}
</script>
