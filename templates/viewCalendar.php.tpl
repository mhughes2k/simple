
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

<div class="areaTitle">{if $projectInActive}(Archived) {/if}{$sectionTitle}</div>

{include file=OfficeSidebar.php.tpl}
<div id="projectSectionContent" >
<div class="sectionBox">



{if !$projectInActive & $showStaffTools}
<!--<div class="sectionBox"> -->
 		<div id="ui_allStafftools">
   			<div id="ui_ned" style="display:none">
   				<div class="sectionTitle">{$strings.MSG_STAFF_EVENTS_TEXT}</div>
   				{include file="ned.tpl"}
   			</div>
   		    <div id="ui_stafftools" style="display:none">
   			    <div class="sectionTitle">{$strings.MSG_STAFF_RESOURCES_TEXT}</div>
    			<form name="releaseactions" action="index.php" onsubmit="return validateStaffAction();" method="post" enctype="multipart/form-data">
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
    			        <td><input type="file" name="uploadDocument" id="uploadDocument"/></td>
    			     </tr>
    			     <tr>
    					<td> Send As:
    			          <select name="sender" id="sender" style="width:100px">
    			            <option value="">-Select one-</option>
    						{foreach from=$projectRoles key=key item=role}
    							<option value="{$role->name} -{$role->address}-">{$role->name} - {$role->projectrole}</option>
    						{/foreach}
    			              </select></td>
    			            <td align="right"><input type="submit" value="{$strings.MSG_SEND_BUTTON}" /></td>
    			          </tr>
    			        </table>
    		      	</form>
    	    	</div> 
    	   	</div>
   <!-- </div>--><!--staff toolbar content//--> 
    {/if}


<div class="sectionTitle">Calendar for {$sectionTitle}</div>
<!--<p>
<a href="index.php?option=office&cmd=newCalendarItem"><img src="{$config.calendar_icon_add}"></a><a href="index.php?option=office&cmd=newCalendarItem">New Event/Appointment</a></p>-->


<div style="width:550px;" style="margin-left:50px">
{$fromdate}
<table border="1">
<tr>
	<td style="text-align:left">
	<A href="index.php?option=office&cmd=viewcalendar&fromdate={$movePrevYr}">&lt;&lt;</a>
	<A href="index.php?option=office&cmd=viewcalendar&fromdate={$movePrevMo}">&lt;</a>
	</td>
	<td colspan="5"><div class="center">
	{$monthname} {$thisyear}
	</div>
	</td>
	<td style="text-align:right"><a href="index.php?option=office&cmd=viewcalendar&fromdate={$moveNextMo}">&gt;</a>
	<a href="index.php?option=office&cmd=viewcalendar&fromdate={$moveNextYr}">&gt;&gt;</a></td>
	</tr>
<tr>
<th>Sunday</th>
<th>Monday</th>
<th>Tuesday</th>
<th>Wednesday</th>
<th>Thursday</th>
<th>Friday</th>
<th>Saturday</th>
</tr>
<tr>{$calhtml}</tr>
<tr>
<th>Sunday</th>
<th>Monday</th>
<th>Tuesday</th>
<th>Wednesday</th>
<th>Thursday</th>
<th>Friday</th>
<th>Saturday</th>
</tr>
	<td style="text-align:left">
	<A href="index.php?option=office&cmd=viewcalendar&fromdate={$movePrevYr}">&lt;&lt;</a>
	<A href="index.php?option=office&cmd=viewcalendar&fromdate={$movePrevMo}">&lt;</a>
	</td>
	<td colspan="5"><div class="center">
	<A href="index.php?option=office&cmd=viewcalendar">Goto Today</a>
	</div>
	</td>
	<td style="text-align:right">
	<a href="index.php?option=office&cmd=viewcalendar&fromdate={$moveNextMo}">&gt;</a>
	<a href="index.php?option=office&cmd=viewcalendar&fromdate={$moveNextYr}">&gt;&gt;</a></td>
	</tr>
</table>
</div>
</div>
</div>


