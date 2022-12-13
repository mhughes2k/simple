{include file="FolderSelectUi.tpl"}
{include file="folderManager.php.tpl"}

{literal}
<script type="text/javascript">
function toggleStaff(it) {
	if(document.getElementById) {
//		if (document.getElementById('staffToolBar_content').style.display=="none") {
			var st = document.getElementById(it).style;
			st.display = st.display ? "" : "block";
	//		return;
//		}
//		document.getElementById('staffToolBar_content').style.display="none";
	}
	else if (document.all) {
		var style2 = document.all[it].style;
		style2.display = style2.display? "":"block";
	}
	else if (document.layers) {
		var st = document.layers[it].style.display ;
		st.display= st.display?"":block;
	}
}
function disableReleaseActionsControls(selectedControl) {
	document.getElementById("canneddoc").disabled = true;
	document.getElementById("custom_base").disabled = true;
	document.getElementById("uploadDocument").disabled = true;
			
///	alert(selectedControl);
	switch(selectedControl) {
		case "sendcanned":
			document.getElementById("canneddoc").disabled = false;;
			break;
		case "sendcustom":
			document.getElementById("custom_base").disabled = false;
			break;
		case "uploaddocument":
			document.getElementById("uploadDocument").disabled = false;
			break;
	}
}
function validateStaffAction() {
	if (!document.getElementById("sendcanned").checked && !document.getElementById("sendcustom").checked && !document.getElementById("sendfile").checked) {
		alert("Please select an action first");
		return false;
	}
	if (document.getElementById("sendfile").checked && document.getElementById("uploadDocument").value=="") {
		alert("You must choose a file first.");
		return false;
	}
	if (document.getElementById("sender").value == "") {
		alert("You must choose a sender first.");
		return false;
	}
	return true;
//	switch() {
//	}
}

</script>
{/literal}

<div>
	{literal}
	<script language="Javascript">
		function validateForm() {
			valid = true;
			if (document.editform.filename.value=="") {
				alert("You must provide a name for the document.");
				valid = false;
			}
			return valid;
		}
	</script>
	<script>
//	document.getElementById('saveButton').disabled = true;
	function enableSaveButton(selectedControl) {
		if (document.getElementById('filename').value != "") {
			document.getElementById('saveButton').disabled = false;
		}
	}
	</script>

	{/literal}
	<div class="areaTitle">{$sectionTitle}</div>	
	{include file=OfficeSidebar.php.tpl}
	
<div id="projectSectionContent" >	
  <div id="dialogBlock" 
style="
position:absolute;
top:0px;
left:0px;
width:100%;
height:100%;
display:none;
z-index:100;

">
&nbsp;
</div>
	<div id="documentItem">
			{if isset($item)}
			{if $showStaffTools}		
		
			
				<div id="ui_ned" style="display:none">
					<div class="sectionTitle">Events</div>
					{include file="ned.tpl"}
				</div>
			    <div id="ui_stafftools" style="display:none">
				    <div class="sectionTitle">Resources</div>
		    	  	<form name="releaseactions" action="index.php" onsbmit="return validateStaffAction();" method="post" enctype="multipart/form-data">
			  		<input type="hidden" name="option" value="office" />
				  	<input type="hidden" name="folder" value="{$folderId}"/>
		    	    <table>
		        	  	<tr>
		            		<td><input type="radio" name="cmd" id="sendcanned" value="sendcanned"  onclick="disableReleaseActionsControls('sendcanned');" />
		              		{$strings.MSG_SEND_DOCUMENT}</td>
			            	<td>
				            	<select name="canneddoc" id="canneddoc">
									{foreach from=$cannedDocuments key=key item=doc}
										<option value="{$doc.doctemplateuid}">{$doc.filename}</option>
									{/foreach}
				              </select>
				            </td>
				          </tr>
				          <tr>
				            <td>
				            	<input type="radio"  name="cmd" value="sendcustom" id="sendcustom" onclick="disableReleaseActionsControls('sendcustom');"  />
				              {$strings.MSG_SEND_CUSTOM_DOCUMENT}</td>
				            <td>
				            <select name="custom_base" id="custom_base">
								{foreach from=$customDocuments key=key item=doc}
									{if $doc.contenttype=="html"}
									<option value="{$doc.doctemplateuid}">{$doc.filename}</option>
									{/if}
								{/foreach}
				            </select>
				            <a href="http://technologies.law.strath.ac.uk/TLE2/wiki/index.php/Send_Custom_Document_%28help%29" target="_blank">?</a>
				            </td>
				          </tr>
				          <tr>
				            <td><input type="radio"  id="sendfile" name="cmd" value="sendfile" onclick="disableReleaseActionsControls('uploaddocument');"  />
				              {$strings.MSG_UPLOAD_FILE}</td>
				            <td>
					            <input type="file" name="uploaddoc" id="uploaddoc"/>
				            </td>
				           </tr>
				           <tr>
							<td> Send As:
				              <select name="sender" id="sender" style="width:100px">
				                <option value="">-Select one-</option>
								{foreach from=$projectRoles key=key item=role}
									<option value="{$role->name} -{$role->address}-">{$role->name} ({$role->projectrole})</option>
		
								{/foreach}
				              </select></td>
				            <td align="right"><input type="submit" value="{$strings.MSG_SEND_BUTTON}" /></td>
				          </tr>
				        </table>
			      	</form>
		    	</div> <!--staff toolbar content//-->
		
		  		    
	    {/if}
	   <div class="sectionBox">
				<form name="editform" method="post" action="index.php" {$enctype} onsubmit="return validateForm();">
					<input type="hidden" name="option" value="{$option}"/>
					<input type="hidden" name="cmd" value="{$command}"/>
					<input type="hidden" name="redir" value="{$redir}" />
					<input type="hidden" name="destfolderid" value="{$destfolderid}"/>
					<input type="hidden" name="docuid" value="{$docuid}"/>
					<input type="hidden" name="contenttype" value="{$contenttype}" />
	
		<div class="sectionTitle">Document Information </div>
			<div id="ui_docinfo">
				{if $flag!=''}	
				<p class='flagged'><img src="{$config.office_flag_set}" title="Flagged"/>&nbsp;<a href="index.php?option=office&cmd=unsetflag&text=flag&documentid={$item->id}&redir=reopen">This item is flagged</a></p>
				{/if}
				{if $docuid>0}
				{if !$hidedownload}
				<a href="?option=download&docuid={$docuid}&download=0">Open</a> | 
				<!-- <a href="?option=download&docuid={$docuid}&format=pdf&download=1">Download As PDF</a> |-->
				<!--<a href="?option=download&docuid={$docuid}&format=word">Download As Word</a>|//-->								
				{/if}
				<!-- <img src="{$config.office_print}"><a href="?option=office&cmd=printdoc&documentid={$docuid}">Print</a> | -->
				{/if}
				{if projectIsActive && $docuid>0}
				<!--<a href="Javascript:sendDoc({$docuid})"><img src="{$config.office_send}"></a>-->
				<!-- <a href="Javascript:sendDoc({$docuid})">Send</a> | --><a href="index.php?option=office&cmd=markasunread&id={$docuid}">Mark as Unread</a>  {/if}
				{if $redir!=""}
				<!--<a href="{$redir}"><img src="{$config.system_icon_close}" /></a><a href="{$redir}">Close</a>-->
				{/if}
				
				
   	
	     {if !$projectInActive}
        {if $showeditor & ($contenttype=='text/html')}
        <input type="submit" value="Save">
        {/if}
	      {if ($permissions.canDelete)}
	     |
	      <a href="Javascript:deleteDoc({$docuid})"><!--<img src="{$config.office_delete}" title="Delete"/>-->Delete</a>
	<!--      <a href="?option=office&cmd=deleteitem&itemid={$id}"><img src="{$config.office_delete}" title="Delete" /></a>	    -->  
	      {/if} 
	      
	      {if (($permissions.canDelete) && ($docuid>0))}
	      |
	      <a href="Javascript:moveDoc({$docuid})"><!--<img src="{$config.office_move}" title="Move"/>-->Move</a>
	<!--      <a href="?option=office&cmd=move&itemid={$id}&itemtype=doc"><img src="{$config.office_move}" title="Move"/></a> -->
	      {/if} 
	      
	      {if $docuid>0}
	      | <a href="Javascript:copyDoc({$docuid})"><!--<img src="{$config.office_copy}" title="Copy"/>-->Copy</a>
	  <!--    <a href="?option=office&cmd=copy&itemid={$id}&itemtype=doc"><img src="{$config.office_copy}" title="Copy"/></a> -->
	  	  {/if}
	      {/if}

	 <!--    | <a href="?option=download&docuid={$id}"><img src="{$config.office_download}" title="Download" /></a> -->
        {if $redir!=""}
					  | <a href="{$redir}"><!--<img src="{$config.system_icon_close}" /></a>//--><a href="{$redir}">Close</a>
				{/if}
				

				{if $showStaffTools & !readonly}
				<p>
				<strong>Recipient:</strong> <input type="text" name="recipient" value="{$recipient}" /></p>
				<p>
				<strong>Sender:</strong> <input type="text" name="sender" value="{$sender}"/>
				</p>
				{else}
								<p>
								{if $recipient!='' & $recipient !="-"}
								  <strong>Recipient:</strong>{$recipient}<br />
								{/if}
								{if $sender!='' & $sender !="-"}
								  <strong>Sender:</strong> {$sender}
								{/if}
								</p>
							{/if}		
						</div>
					<p>
								{if !$readonly & !$showeditor}		
									<br>Select a file to upload.<input type="file" name="uploadDocument"><input type="submit" value="Save" name="saveButton" id="saveButton"/> <br />

								{/if}
								{if $readonly}
									<br>You cannot edit this file.<br />
								{/if}
					</p>
					{if $showeditor} 
					Filename: <input type="text" name="filename" id="html_filename">
						<textarea id="elm1" name="documentContent" rows="15" cols="80" style="width: 100%">{$documentContent}</textarea>
					
					
					{/if}

						{if $readonly & contenttype!="text/html"}
						
						{else}
						<!--
							<input type="submit" value="Save" name="saveButton" id="saveButton"/>
							{if !$showStaffTools}<input type="submit" value="Save &amp; Continue" name="savecontButton" id="savecontButton"/>{/if}
						//-->
						{/if}
						<!--
						{if $redir!=""}
							<a href="{$redir}"><img src="{$config.system_icon_close}" /></a><a href="{$redir}">Close</a>
						{/if}
						//-->
					
				</form>	
			{else}
				<p>Item not found!</p>
			{/if}
			</div>
		</div> 
		<!--End DocItem //-->
		{if $docuid == -1}
			{assign var='commentary_disabled_message' value='You cannot make comments until the item is saved'}
			{assign var='admincommentary_disabled_message' value='You cannot make comments until the item is saved'}
		{/if}
		{if $docuid>0}
		{include file="commentary.php.tpl"}
		{/if}
    {$docProperties_extensions}
</div>
</div>
<!--End of whole section//-->
