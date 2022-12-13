<script language="javascript">
{literal}
	function cancelDialog() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('newDialog').style.display='none';
	}
	function ShowDialog() {
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('newDialog').style.display='block';
	}
  function downloadResource() {
    var resourceId = document.getElementById('custom_base').value;
    window.open('index.php?option=download&docuid=' + resourceId+ '&download=&docType=doc_templ');
  }
{/literal}
</script>
<div id="dialogBlock" class="pop_up"></div>
<div class="pop_up" id="newDialog" style="
position:absolute;
	top:200px;
	left:90px;
	width:400px;
	height:260px;	
	border:solid 1px black;
	display:none;
	background-color:white;
	z-index:1000;
	padding:5px;">


<form action="index.php" method="post">
<div class="pop_up_title">Add Simulation To Group</div>
<p>Please select a simulation to add to this group</p>

<input type="hidden" name="option" value="projectadmin" />
<input type="hidden" name="cmd" value="doaddprojecttogroup" />
<input type="hidden" name="projectgroupid" value="{$ProjectGroup->id}"/>
<div style="overflow:scroll;height:180px;">
{foreach from=$allprojects item=project}
<input type="checkbox" name="ids[]" value="{$project->id}" />{$project->id} - {$project->GetName()}<br/>
{/foreach}
</div>
<input type="submit" value="Add" />
<a href="javascript:cancelDialog()">Cancel</a>

</form>
</div>

<div class="areaTitle">Simulation Group Information</div>
<div id="manageSectionContent" >
<div class="sectionBox_manage">
<div><span class="bold">Simulation Group:</span> {$ProjectGroup->Name}</div>
<div><span class="bold"># of Simulations in this Group:</span>{$memberCount}</div>
</div>

<div class="sectionBox_manage">
<a href="javascript:ShowDialog();">Add Simulation(s) to Group</a>
<table>
<tr>
<th>
Simulation ID
</th>
<th>
Name
</th>
</tr>
{foreach from=$projects item=project}
</tr>
  <td>
  {$project->id}
  </td>
	<td>
	{if $project->ProjectUserPermissions.UseStaffTools}
		<a href="index.php?option=projectAdmin&cmd=viewProject&projectId={$project->id}">
	{/if}
	<span class="{if !$project->isactive}italic{/if}">{$project->GetName()}</span>
	{if $project->ProjectUserPermissions.UseStaffTools}</a>{/if}
	
	</td>
	<td><a href="index.php?option=projectadmin&cmd=removefromgroup&gid={$ProjectGroup->id}&id={$project->id}"><img src="{$config.office_delete}"/></a>
	</tr>
{/foreach}
</table>
<!-- <a href="javascript:ShowDialog();">Add Simulation(s) to Group</a> -->
</div>

<div class="sectionBox_manage">
<div class="sectionTitle">Tools</div>
<p>These tools will apply to <strong>every</strong> Simulation in this group! Use with care!</p>
<p>User, Group and Simulation Permissions are still in effect. If you do not have permission to perform this action on a simulation 
in this group, the action <strong>will not be performed on that simulation</strong>.</P>
<div class="sectionTitle">Release/Send Message to Players</div>
<p>This tool will allow you to send a message (either a file you have uploaded, or a 
"canned" or a "custom" message) to the <strong>Players</strong>.</p>
<p>Bear in mind that:</p>
<ul>
<li>all of the Players will receive the <strong>same</strong> message unless you use variables.</li>
<li>if the group is made up of different projects (i.e. based on different templates) not all variables may be defined! <br>
In this case the system <strong>will not</strong> send the message to that Player.</li>
</ul>
<div class="sectionBox_manage">
    			<div class="sectionTitle">Staff Tools
    			<a href="JavaScript:toggleElement('ui_allStafftools');">^</a>
    			</div>
    			<div id="ui_allStafftools">
   			
   			    <div id="ui_stafftools" style="">
    				    <div class="sectionTitle">Resources</div>
    				    <form name="releaseactions" action="index.php" onsubmit="return validateStaffAction();" method="post" enctype="multipart/form-data">
    			  		<input type="hidden" name="option" value="projectadmin" />
    				  	<input type="hidden" name="folder" value="{$folderId}"/>
    				  	<input type="hidden" name="projectGroupId" value="{$ProjectGroup->id}"/>
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
    				              {$strings.MSG_SEND_CUSTOM_DOCUMENT}
                          </td>
    				            <td>
    				            <select name="custom_base" id="custom_base">
    								{foreach from=$customDocuments key=key item=doc}
    									<option value="{$doc.doctemplateuid}">{$doc.filename}</option>
    								{/foreach}
    				            </select>
    				            <a href="JavaScript:downloadResource()">Download</a>
                        <a href="http://technologies.law.strath.ac.uk/TLE2/wiki/index.php/Send_Custom_Document_%28help%29" target="_blank">?</a>
    				            </td>
    				          </tr>
    				          <tr>
    				            <td><input type="radio"  id="sendfile" name="cmd" value="sendfile" onclick="disableReleaseActionsControls('uploaddocument');"  />
    				              {$strings.MSG_UPLOAD_FILE}</td>
    				            <td>
    					            <input type="file" name="uploadDocument" id="uploadDocument"/>
    				            </td>
    				           </tr>
    				           <tr>
    							<td> Send As:
    				              <select name="sender" id="sender" style="width:100px">
    				                <option value="">-Select one-</option>
    								{foreach from=$projectRoles key=key item=role}
    									<option value="{$role.projectrole}">{$role.blueprint} - {$role.projectrole}</option>
    		
    								{/foreach}
    				              </select></td>
    				            <td align="right"><input type="submit" value="{$strings.MSG_SEND_BUTTON}" /></td>
    				          </tr>
    				        </table>
    			      	</form>
    		    	</div> <!--staff toolbar content//-->
    		    	</div>
    		    </div>
    		    <div>
    		      <div>Release Via N.E.D</div>
      		    {if $bpcount==1}
      		      <div style="width:100%;overflow:auto;">
		{if count($events) >0}
			<table border="1" class="ned">
			<tr style="background-color:lightgray" title="Staff Activities">
				<th>Staff Activities</th>
			{foreach from=$events item=event name=sa}
			<!--	{if $smarty.foreach.sa.index % 6  ==0}
					<th>Staff Activities</th>
				{/if}-->
				{if $event.itemtype==3}						
					<td rowspan="4" style="overflow:auto;background-color:#{$event.color}">
					
            {if $ned_EnableDocuments}
						<a href="index.php?option=projectTemplateAdmin&cmd=editDocument&docUid={$event.nexteventid}"><strong>{$event.name}</strong></a>
						{else}
							<strong>{$event.name}</strong>
						{/if}
						{if $event.state!=''}
							<br>[{$event.state}]
						{/if}
						{if $ned_EnableTriggers & $event.hasChildren}
							<br><A href="?option=projectadmin&cmd=triggerevent&id={$event.projecttemplateeventid}&pid={$pid}" title="Trigger '{$event.name}'"><img src="{$config.ned_icon_trigger}"></a>
						{/if}
					</td>
				{elseif $event.itemtype==4|$event.itemtype==301}
					<td style="overflow:auto;background-color:#{$event.color}">
					
              {if $event.state==""}
                <A href="javascript:setNedDialog('{$event.projecttemplateeventid}','')" title="Tag '{$event.name}'"><strong>{$event.name}</strong></a><br/>
               
              {else}
              	<A href="javascript:setNedDialog('{$event.projecttemplateeventid}','{$event.state}')" title="Change '{$event.name}' tag"><strong>{$event.name}</strong></a>
              	[{$event.state}]
              {/if}
						<!--<a href="{$event.performerrole}" target="_blank">Go...</a>//-->
						{if $ned_EnableTriggers & $event.hasChildren}
							<br><A href="?option=projectadmin&cmd=triggerevent&id={$event.projecttemplateeventid}&pid={$pid}" title="Trigger '{$event.name}'"><img src="{$config.ned_icon_trigger}"></a>
						{/if}
					</td>
				{else}
				<td ></td>
				{/if}
			{/foreach}
			</tr>
			<tr title="Non-Player Character">
				<th>Non-Player Character</th>
			{foreach from=$events item=event name=npc}
			<!--	{if $smarty.foreach.npc.index % 6  ==0}
					<th>Non-Player Character</th>
				{/if}-->
			
				{if $event.itemtype==3}	
					
				{elseif $event.performerrole!="CHAR_PLAYER" and $event.itemtype!=0 and $event.itemtype!=3 and $event.itemtype!=4 and $event.itemtype!=301}
					<td class="ned" style="background-color:#{$event.color};overflow:auto">
						&lt;{$event.performerrole}&gt;
            {if $event.state==''}
							{if $ned_EnableStates}
								<A href="javascript:setNedDialog('{$event.projecttemplateeventid}','')" title="Tag '{$event.name}'"><strong>{$event.name}</strong></a>
							{else}
								{$event.name}
							{/if}
						{else}
							{if $ned_EnableStates}
								<A href="javascript:setNedDialog('{$event.projecttemplateeventid}','{$event.state}')" title="Change '{$event.name}' tag"><strong>{$event.name}</strong></a>
								<br/>[{$event.state}]
							{else}
								{$event.name}
							{/if}			
						{/if}
						{if $ned_EnableTriggers and $event.hasChildren}
						
							<br><A href="?option=projectadmin&cmd=triggerevent&id={$event.projecttemplateeventid}&pid={$pid}" title="Trigger '{$event.name}'">T<img src="{$config.ned_icon_trigger}"></a>
						{/if}
	
					</td>		
				{else}
					<td></td>
				{/if}
			</td>
			{/foreach}
			</tr>
			<tr style="background-color:lightgray" title="Player Character">
			<th>Player Character</th>
			{foreach from=$events item=event name=pc}
			<!--	{if $smarty.foreach.pc.index % 6  ==0}
					<th>Player Character</th>
			{/if} -->
				{if $event.itemtype==3}						
				{elseif $event.performerrole=="CHAR_PLAYER" }
					<td class="ned" style="background-color:#{$event.color};overflow:auto">
						{if $event.state==''}
							{if $ned_EnableStates}
							<A href="javascript:setNedDialog('{$event.projecttemplateeventid}','')" title="Tag '{$event.name}'"><strong>{$event.name}</strong></a>
							{else}
								<strong>{$event.name}</strong>
							{/if}
						{else}
							{if $ned_EnableStates}
								<A href="javascript:setNedDialog('{$event.projecttemplateeventid}','{$event.state}')" title="Change '{$event.name}' note"><strong>{$event.name}</strong></a>
								<br/>[{$event.state}]
							{else}
								<strong>{$event.name}</strong>
							{/if}
						{/if}
					</td>
				{else}
					<td />
				{/if}
			</td>
			{/foreach}
			</tr>
			<tr title="Critical Events">
			<th>Critical Events</th>
			{foreach from=$events item=event name=ce}
			<!--	{if $smarty.foreach.ce.index % 6 ==0}
					<th>Critical Events</th>
				{/if}-->
				{if $event.itemtype==3}						
	
				{elseif $event.itemtype==0}
					<td class="ned {if $event.itemtype==0}criticalEvent{/if}" style="overflow:auto;background-color:#{$event.color}">
						{if $event.state==''}
							{if $ned_EnableTriggers}
								<A href="?option=projectadmin&cmd=triggereventforgroup&id={$event.projecttemplateeventid}&projectGroupId={$pid}" title="Trigger '{$event.name}'"><strong>{$event.name}</strong><img src="{$config.ned_icon_trigger}"></a>
							{else}
								<strong>{$event.name}</strong>
							{/if}
						{else}
							<strong>{$event.name}</strong><br/>[{$event.state}]<A href="?option=projectadmin&cmd=triggerevent&id={$event.projecttemplateeventid}&pid={$pid}" title="Re-Trigger '{$event.name}'"><img src="{$config.ned_icon_trigger}"></a>
						{/if}
					</td>
				{else}
					<td/>
				{/if}
			</td>
			{/foreach}
			</tr>
			</table>
		{else}
			Project structure not found!
		{/if}

</div>
      		    {else}
      		      <p>Simulations are based of multiple blueprints, so cannot use N.E.D. tool</P>
      		    {/if}
    	     </div>
    		</div>
    	</div>
    	
<!--
<div class="sectionTitle">Reminder</div>
<p>Reminders are sent to Users or Groups, please use this <A href="">page</a> to send reminders.</p>
//-->

</div>
