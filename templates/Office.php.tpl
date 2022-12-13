
		{include file="FolderSelectUi.tpl"}

{literal}
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js" ></script >
<script type="text/javascript" >
tinyMCE.init({
        mode : "textareas",
        theme : "simple" 
});
</script >

<script type="text/javascript">
 $(document).ready(function () {
 
	if ($('#sendcanned').length > 0) {
		disableReleaseActionsControls('sendcanned');
	}
	
    $("a#showned").click( function() {
        $("#ui_ned").slideToggle();
      }
    )
    $("#ned_events").unbind("click");//we need to remove the scroll up for the NED label
    $("#ned_events").click(
      function() {
        $("#ui_ned").slideUp();
      }
    )
    $("a#showstafftools").click( function () {
      $("#ui_stafftools").slideToggle();
    }
    )
    $("a#showfoldermanager").click(function(){
      $("#dialogBlock").fadeIn();
      $("#ui_folderManager").fadeIn();
    }
    )
    $("button.senddoc").click( function () {
        //document.getElementById('dialogBlock').style.display='block';
        
        var docid =$(this).attr("docid");
        var triggerElement = $('#folder_row_id_'+ docid);
        triggerElement.children().addClass("highlight");
        $('#send_documentid').val(docid);
        //alert(triggerElement.html()); 
        /*
        alert(window.screen.offsetHeight);
        alert(window.outerHeight);
        return;
        //alert($(document).Offset());
        var Offset = triggerElement.offset();
        var vOffset = Offset.top -200;//document.body.scrollTop-Offset.top; 
        */
        //Offset.top -150;
        //alert('Offset '+ vOffset);
        $("#dialogBlock").fadeIn();
        //$("#sendDialog").css("top",vOffset+"px").fadeIn();
        $("#sendDialog").fadeIn();
      } 
    )
    $("button.movedoc").click( function () {
        //document.getElementById('dialogBlock').style.display='block';
        
        var docid =$(this).attr("docid");
        var triggerElement = $('#folder_row_id_'+ docid);
        $('#move_itemid').val(docid);
        triggerElement.children().addClass("highlight");
        //alert(triggerElement.html());
        //return;
        var Offset = triggerElement.offset();
        
        var vOffset = Offset.top -150;
        
        Offset.top -150;
        //alert('Offset '+ vOffset);
        $("#dialogBlock").fadeIn();
        $("#moveDialog").css("top",vOffset+"px").fadeIn();
      } 
    )
    $("button.deletedoc").click( function () {
        //document.getElementById('dialogBlock').style.display='block';
        
        var docid =$(this).attr("docid");
        var triggerElement = $('#folder_row_id_'+ docid);
        triggerElement.children().addClass("highlight");
        $('#delete_itemid').val(docid);
        //alert(triggerElement.html());
        //return;
        var Offset = triggerElement.offset();
        var vOffset = Offset.top -150;
        //alert('Offset '+ vOffset);
        $("#dialogBlock").fadeIn();
        $("#deleteDialog").css("top",vOffset+"px").fadeIn();
      } 
    )
    
    $("button.copydoc").click( function () {
        //document.getElementById('dialogBlock').style.display='block';
        
        var docid =$(this).attr("docid");
        var triggerElement = $('#folder_row_id_'+ docid);
        triggerElement.children().addClass("highlight");
        $('#copy_itemid').val(docid);
        //alert(triggerElement.html());
        //return;
        var Offset = triggerElement.offset();
        var vOffset = Offset.top -150;
        //alert('Offset '+ vOffset);
        $("#dialogBlock").fadeIn();
        $("#copyDialog").css("top",vOffset+"px").fadeIn();
      } 
    )
    $("a.cancel").click(function() {
      $(this).parent().parent().fadeOut();
      //cancelsend();
      $("#send_documentid").val('');
      //document.getElementById('send_documentid').value = '';
      $("#dialogBlock").fadeOut();
      $(".highlight").removeClass("highlight");
    } 
    )
    $(".tagned").click(function() {
      var eventid = $(this).attr("eventid");
      var eventstate =$(this).attr("eventstate");
      //alert (eventid +":"+eventstate);
      $("#ned_eventid").val(eventid);
      if (eventstate!=null) {
        $("#state").val(eventstate);
      }
      else {
        $("#state").val('');
      }
      $("#dialogBlock").fadeIn();
      $("#nedNoteDialog").fadeIn();
    }
    )
    $(".triggerned").click(function () {
      var eventid = $(this).attr("eventid");
      var projectid = $(this).attr("projectid");
      //alert(eventid);
      
      $("#triggereventid").val(eventid);
      
      
      //$("#projectid").val(projectid);
      $("#dialogBlock").fadeIn();
      $("#triggerEventDialog").fadeIn();
      }
    )
    
     $(".document_table_alternate_row").hover(
    function() {
      //alert($(this).children("office_documents_table_tools_row").html());
      $(this).children().addClass("highlight");
     // $(this).next().slideDown();
    },
    function() {
      $(this).children().removeClass("highlight");
      //$(this).next().slideUp();
    }
    );
   $(".document_table_row").hover(
    function() {
      //alert($(this).html());
      //alert($(this).children("office_documents_table_tools_row").html());
      $(this).children().addClass("highlight");
      //$(this).next().slideDown();
    },
    function() {
      $(this).children().removeClass("highlight");
      //$(this).next().slideUp();
    }
   );
   /*
   $(".document_table_row,.document_table_alternate_row").click( function() {
      var item = $(this).children("td").children(".documentitem");
      var docUrl = item.attr("href");
      item.removeClass("bold");      
      window.location=docUrl;
      //window.open(docUrl,'','status=0,menubar=0,location=0,toolbar=0');
   }
   );
   */
   $(".document_table_row,.document_table_alternate_row").dblclick( function() {
      //alert($(this).html());
      var item = $(this).children("td").children(".documentitem");
      var docUrl = item.attr("href");
      
      window.open(docUrl,'','status=0,menubar=0,location=0,toolbar=0');
      item.removeClass("bold");
      //window.location=docUrl;
      //alert($(this).children("td").children(".documentitem").attr("href"));;
    }
   )
  }
 );
 
 /*
 		var triggele ='folder_row_id_'+docid;
		
		var vOffset = document.getElementById(triggele).offsetTop;
		
		vOffset =  vOffset + document.getElementById('projectSectionContent').offsetTop +document.getElementById('document_table').offsetTop;
		//alert(vOffset);
		var dialog = document.getElementById('sendDialog');
		dialog.style.top = vOffset+"px";
		dialog.style.left = document.getElementById('projectSectionContent').offsetLeft+"px";
		document.getElementById('sendDialog').style.display='block';	
	*/
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
	document.getElementById("compose_email").disabled = true;
	document.getElementById("email_subject").disabled = true;

///	alert(selectedControl);
	switch(selectedControl) {
		case "sendcanned":
			document.getElementById("canneddoc").disabled = false;
			document.getElementById("staticsender").disabled = false;
			document.getElementById("sender").disabled = false;
      		document.getElementById("sendbutton").value="Send";
			break;
		case "sendcustom":
			document.getElementById("custom_base").disabled = false;
			document.getElementById("staticsender").disabled = true;
			document.getElementById("sender").disabled = true;
			document.getElementById("sendbutton").value="Download";
			break;
		case "uploaddocument":
			document.getElementById("uploadDocument").disabled = false;
			document.getElementById("staticsender").disabled = false;
			document.getElementById("sender").disabled = false;
			document.getElementById("sendbutton").value="Send";
			break;
		case "sendemail":
			document.getElementById("compose_email").disabled = false;
			document.getElementById("email_subject").disabled = false;
			document.getElementById("staticsender").disabled = false;
			document.getElementById("sender").disabled = false;
			document.getElementById("sendbutton").value="Send";
			break;
	}
}
function validateStaffAction() {
	if (!document.getElementById("sendcanned").checked && !document.getElementById("sendcustom").checked && 
		!document.getElementById("sendfile").checked && !document.getElementById("sendemail")) {
		alert("Please select an action first");
		return false;
	}
	if (document.getElementById("sendfile").checked && document.getElementById("uploadDocument").value=="") {
		alert("You must choose a file first.");
		return false;
	}
	if (!document.getElementById('sendcustom').checked){
    if(
      document.getElementById("staticsender").value == "_custom_"
    &
      document.getElementById("csender").value == ""
    ) 
    {
		  alert("You must enter a sender first.(1):"+document.getElementById("staticsender").value+":"+document.getElementById("csender").value);
		  return false;
		}
		if (document.getElementById("staticsender").value == "") {
    	alert("You must choose a sender first.(2)");
		  return false;
    }
    if (document.getElementById("staticsender").value !="_custom_" &&
    document.getElementById("staticsender").value !="")
    {
      document.getElementById("csender").value = document.getElementById("staticsender").value;
    }
	}
	
	return true;
//	switch() {
//	}
}

</script>
{/literal}

{include file="folderManager.php.tpl"}

<div class="areaTitle">{$strings.MSG_OFFICE_WORKSPACE} {if $projectInActive}{$strings.MSG_ARCHIVED} {/if}{$sectionTitle}</div>

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
{if !$projectInActive & $showStaffTools}
<!--<div class="sectionBox"> -->
 		<div id="ui_allStafftools">
   			<div id="ui_ned" {if !$showned} style="display:none"{/if}>
   				<div class="sectionTitle" id="ned_events">{$strings.MSG_STAFF_EVENTS_TEXT}</div>  			
	   			
   				{include file="ned.tpl"}
   			</div>
   		    <div id="ui_stafftools" style="display:none">
   			    <div class="sectionTitle">{$strings.MSG_STAFF_RESOURCES_TEXT}</div>
   			    <!-- TODO replace radio buttons with actual buttons to show/hide appropriate options instead
   			    of just disabling them.
   			    
   			    <div id="ui_stafftools_Send" style="float:left; border:solid 1px gray">Send</div>
   			    <div id="ui_stafftools_Download" style="float:left border:solid 1px gray">Download</div>
   			    <div id="ui_stafftools_Add" style="float:left border:solid 1px gray">Add</div>
   			    <br clear="all"/>
   			    //-->
    			<form name="releaseactions" action="index.php" onsubmit="return validateStaffAction();" method="post" enctype="multipart/form-data" accept-charset="utf-8">
    		  	<input type="hidden" name="option" value="office" />
    			<input type="hidden" name="folder" value="{$folderId}"/>
    	    	 <table>
    	          	<tr>
    	           		<td><input type="radio" name="cmd" id="sendcanned" value="sendcanned" checked onclick="disableReleaseActionsControls('sendcanned');" />
    	           		{$strings.MSG_SEND_DOCUMENT}</td>
    		           	<td>
    			          	<select name="canneddoc" id="canneddoc">
    							{foreach from=$cannedDocuments key=key item=doc}
    								<option value="{$doc.doctemplateuid}">{$doc.filename} - {$doc.visiblename}</option>
    							{/foreach}
    			             </select>
    			        </td>
    			     </tr>
    			     <tr>
    			        <td>
    			          	<input type="radio"  name="cmd" value="sendcustom" id="sendcustom" onclick="disableReleaseActionsControls('sendcustom');"  />
    			             {$strings.MSG_DOWNLOAD_DOCUMENT_TO_EDIT}Download Document to Edit</td>
    			        <td>
    			           <select name="custom_base" id="custom_base">
    						{foreach from=$customDocuments key=key item=doc}                
    								<option value="{$doc.doctemplateuid}">{$doc.filename} - {$doc.visiblename}</option>
    						{/foreach}
    			            </select>
    			           
    			        </td>
    			     </tr>
    			     <tr>
    			        <td><input type="radio"  id="sendfile" name="cmd" value="sendfile" onclick="disableReleaseActionsControls('uploaddocument');"  />
    			         {$strings.MSG_UPLOAD_FILE}</td>
    			        <td><input type="file" name="uploadDocument" id="uploadDocument"/></td>
    			     </tr>
					 
					 <tr {if ($siteSettings.emailmodule_mentors==0)} style="display:none;" {/if}>
						<td><input type="radio"  name="cmd" value="sendemail" id="sendemail" onclick="disableReleaseActionsControls('sendemail');"  />
						{$strings.MSG_SEND_EMAIL}</td>
						<td>Subject: <input type="text" name="email_subject" id="email_subject" /><br/>
						<textarea name="compose_email" id="compose_email"></textarea></td>
					 </tr>
					
    			     <tr>
    					<td> {$strings.MSG_SEND_AS_LABEL}
    					</td>
    					<td>
    			          <select name="staticsender" id="staticsender">
    			            <option value="">{$strings.MSG_SELECT_ONE}</option>
    			            <option value="_custom_">{$strings.MSG_CUSTOM_SENDER_OPTION}</option>
    						{foreach from=$projectRoles key=key item=role}
    							<option value="{$role->name} -{$role->address}-">{$role->projectrole} 
    							{if $role->name > ''} - {$role->name}{/if}
    							</option>
    						{/foreach}
    			              </select>
    			              </td></tr>
    			              <tr><td>
                        {$strings.MSG_CUSTOM_SENDER_LABEL} </td><td><input type="text" id="csender" name="sender"><br>
                        {$strings.MSG_CUSTOM_SENDER_HELP}
                        </td>
    			              </tr>

<tr>
<td>Recipient</td>
<td><input type="text" id="crecipient" name="recipient"  value="">
</tr>
    			              <tr>
    			              <td/>
    			            <td align="right"><input  id="sendbutton" name="sendbutton" type="submit" value="{$strings.MSG_SEND_BUTTON}"/></td>
    			          </tr>
    			        </table>
    		      	</form>
    	    	</div> 
    	   	</div>
   <!-- </div>--><!--staff toolbar content//--> 
 {/if}
	<div class="sectionBox">
		<div class="sectionTitle">{$foldername}</div>
		{if $content}
		<div>
      {$content}
    </div>
    {else}		
		{$prefolder_extension}
		<div>
		{if !$projectInActive}
			{if $additem }
				<form action="index.php">
<!--				<a href="index.php?option=office&cmd=addhtml&destfolderid={$folderId}">{$strings.MSG_ADD_SIMPLE_FILE}</a> |//-->

				{if false}
					<input type="hidden" name="option" value="office"/>
					<input type="hidden" name="cmd" value="addusingtemplate"/>
					<input type="hidden" name="destfolderid" value="{$folderId}"/>
					<select name="documentTemplateInfo">
					{foreach from=$documentTemplates key=key item=template} 
						<option value="{$template.doctemplateuid}|{$template.contenttype}">{$template.filename}</option>
					{/foreach}
					</select>
					<input type="submit" value="{$strings.MSG_USE_TEMPLATE_BUTTON_TEXT}"/> | 
				{/if}
				
				<a href="index.php?option=office&cmd=addfile&destfolderid={$folderId}">{$strings.MSG_UPLOAD_FILE}</a>
				{if ($siteSettings.emailmodule_learners)}
				| <a href="index.php?option=office&cmd=composeemail&destfolderid={$folderId}">{$strings.MSG_COMPOSE_EMAIL}</a>
				{/if}
				{if ($config.htmleditor=='simple')}
				|				
				<a href="index.php?option=office&cmd=addhtml&destfolderid={$folderId}">Create Note</a>{/if}
				</form>
				{/if}
			{if ($edititems and $additem) or $editanyitem}
				<a href="index.php?option=office&cmd=writeenvelope">{$strings.MSG_CREATE_MESSAGE}</a>
			{/if}
		{/if} <!--end !projectInActive //-->
		   	    
    </div>
	   
  <table id="document_table">
    <tr>
      <th></th>
      <th>{$strings.MSG_SUBJECT_HEADER} <a href="?option=office&folder={$folderId}&sort=filename DESC"><img src="{$config.system_icon_descending}"></a><a href="?option=office&folder={$folderId}&sort=filename ASC"><img src="{$config.system_icon_ascending}"></a></th>
      <th>{$strings.MSG_FROM_HEADER} <a href="?option=office&folder={$folderId}&sort=sender DESC"><img src="{$config.system_icon_descending}"></a> <a href="?option=office&folder={$folderId}&sort=sender ASC"><img src="{$config.system_icon_ascending}"></a></th>
      <th>{$strings.MSG_RECIPIENT_HEADER} <a href="?option=office&folder={$folderId}&sort=recipient DESC"><img src="{$config.system_icon_descending}"></a><a href="?option=office&folder={$folderId}&sort=recipient ASC"><img src="{$config.system_icon_ascending}"></a></th>
      <th>{$strings.MSG_RECIEVE_SENT_MOD_HEADER} <a href="?option=office&folder={$folderId}&sort=timestamp DESC"><img src="{$config.system_icon_descending}"></a> <a href="?option=office&folder={$folderId}&sort=timestamp ASC"><img src="{$config.system_icon_ascending}"></a></th>
    </tr>
	{if count($contents) > 0}
	    {foreach from=$contents key=id item=i name=doctable}
	    <tr id="folder_row_id_{$i->id}" 
	    class="
      {if $smarty.foreach.doctable.index % 2 ==0} document_table_alternate_row {else}document_table_row{/if}
      {if $i->hidden} isHidden {/if}"
      >
<!--	      <td>
        <a name="item_id_{$i->id}"></a>
	      {if $i->icon != ''}<a target="_blank" href="index.php?option=office&cmd=viewdoc?documentid={$i->id}" class="tooltip"><img  border="0"src="{$i->icon}" /></a>{/if}
	      
	      </td>
	      //-->
	      <td class="{if $i->hidden}isHidden {/if}">
	      {if $projectInActive}
	      		 <img  border="0"src="
	      {if $i->icon == ''}
			{$i->icon}
	      {else}
		      {$config.office_document_icon}
	      {/if}"
	       />
	      {else}
	{if !$userprefs.hidedocumentflag}
	      {if $i->ui_flag==''}
	      <A href="index.php?option=office&cmd=setflag&text=flag&documentid={$i->id}">
		 <img  border="0"src="
	      {if $i->icon == ''}
			{$i->icon}
	      {else}
		      {$config.office_document_icon}
	      {/if}"
	       /></a>{else}
	      <A href="index.php?option=office&cmd=unsetflag&text=flag&documentid={$i->id}" tooltip="{$i->ui_flag}"><img src="{$config.office_flag_set}" title="Flagged"/></a>
	      {/if}
	{/if}
	      {/if}
	      </td>
	      <td width="35%" class="{if $i->hidden}isHidden {/if}">
	      
	      <a id="folder_link_id_{$i->id}" class="{if $i->hidden}linethrough {/if}{if !$i->IsRead()}bold {/if}overflow documentitem"
        href="{$config.home}index.php?option=download&docuid={$i->id}&download=0" target="_blank" title='{$i->filename}'>
		     {$i->filename}
	      </a>
	      
	      </td>
	      <td width="20%" class="{if $i->hidden}isHidden {/if}"><span title="{$i->sender}">{$i->sender|truncate:50:"..."}</span></td>
	      <td width="20%" class="{if $i->hidden}isHidden {/if}"><span title="{$i->recipient}">{$i->recipient|truncate:50:"..."}</span></td>
	      <td width="20%" class="{if $i->hidden}isHidden {/if}">{$i->timestamp}</td>
		  </tr>
		  <tr class="office_documents_table_tools_row {if $i->hidden}isHidden {/if}">
		  <td class="office_documents_table_tools_row {if $i->hidden}isHidden {/if}"/>
      <td colspan="4" style="text-align:right" class="office_documents_table_tools_row {if $i->hidden}isHidden {/if}"><button onClick='location.href="index.php?option=office&cmd=viewdoc&id={$i->id}"'>Properties <img src="{$config.office_document_edit}" title="Open Properties" alt="Edit Properties" /></button>
        {if !$projectInActive}
	      <button class="senddoc" docid="{$id}" title="Send">Send <img src="{$config.office_send}" title="Send"/></button>
	      
	   <!--   Javascript:sendDoc({$id})<a href="?option=office&cmd=writeenvelope&itemid={$id}"><img src="{$config.office_send}" title="Send"/></a>-->
	      {if ($permissions.canDelete)}

	      {if $i->hidden}
	      <a href="Javascript:undeleteDoc({$id})">Undelete<img src="{$config.office_undelete}" title="Undelete"/></a>
	      {else}
	      <button class="deletedoc" docid="{$id}" >Delete <img src="{$config.office_delete}" title="Delete"/></button>
<!--	      <a href="?option=office&cmd=deleteitem&itemid={$id}"><img src="{$config.office_delete}" title="Delete" /></a>	      -->
		  {/if}
	      {/if} 
	      {if ($permissions.canDelete)}
	      <button class="movedoc" docid="{$id}" >Move <img src="{$config.office_move}" title="Move"/></button>
<!--	      <a href="?option=office&cmd=move&itemid={$id}&itemtype=doc"><img src="{$config.office_move}" title="Move"/></a></td>-->
	      {/if} 
        <button class="copydoc" docid="{$id}" >Copy <img src="{$config.office_copy}" title="Copy"/></button>
<!--	      <a href="?option=office&cmd=copy&itemid={$id}&itemtype=doc"><img src="{$config.office_copy}" title="Copy"/></a>-->
	      {/if}

	{if $userprefs.showdocumentflagintoolbar}
	      {if $i->ui_flag==''}
	      <A href="index.php?option=office&cmd=setflag&text=flag&documentid={$i->id}">
		 <img  border="0"src="
	      {if $i->icon == ''}
			{$i->icon}
	      {else}
		      {$config.office_document_icon}
	      {/if}"
	       /></a>{else}
	      <A href="index.php?option=office&cmd=unsetflag&text=flag&documentid={$i->id}" tooltip="{$i->ui_flag}"><img src="{$config.office_flag_set}" title="Flagged"/></a>
	      {/if}
	{/if}
	      
	      </td>
	    </tr>
	    {/foreach}
	      {else}
	      <tr>
			<td colspan="5">{$strings.MSG_NO_ITEMS_FOUND}</td>
	      </tr>
  {/if}
	</table>
	{$postfolder_extension}
</div> <!--Office  container//-->
{/if}


</div>
<!--</div>//-->
