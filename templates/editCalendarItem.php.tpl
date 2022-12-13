{include file="AddLinkedItemDialogToEvent.tpl"}
{include file="AddLinkedEventDialogToEvent.tpl"}

{literal}
<script language="JavaScript">
function toggleTaskStatus() {
	return;
	var taskStatusRow = document.getElementById('taskstatus');
	var isTaskControl = document.getElementById('isTask');
	
	if (isTaskControl.checked) {
		taskStatusRow.style.display='table-row';
	}
	else {
		taskStatusRow.style.display='none';
	}
}
</script>
<script language="JavaScript" type="text/javascript" src="datetimepicker.js">
</script>
{/literal}
<div class="areaTitle">event</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<form action="index.php?option=office&cmd=saveCalendarItem" method="post" >
<input type="hidden" name="id" value="{$eventitem->id}"/>

	
	<div class="sectionTitle">
		{if $eventitem->istask} 
			Task
		{else}	
			Event
		{/if}
	</div>
	<table>
		<tr>
			<td>
				Subject:
			</td>
			<td>
				{if $readonly}
				{$eventitem->title}
				{else}
				<input type="text" name="title" id="title" value="{$eventitem->title}" />
				{/if}
			</td>
		</tr>
		<tr>
			<td>Location</td>
			<td>
				{if $readonly}
				{$eventitem->location}
				{else}
				<input type="text" name="location" id="location" value="{$eventitem->location}" />
				{/if}
			</td>
		</tr>
		<tr>
			<td>Description</td>
			<td>
				{if $readonly}
				{$eventitem->content}
				{else}
				<textarea id="content" name="content" class="mceEditor">{$eventitem->content}</textarea>
				{/if}
			</td>
		</tr>
		<tr>
			<td>Start Time</td>
			<td>
				{if $readonly}
				{$eventitem->startdate}
				{else}
				<input type="text" name="starttime" id="starttime"  value="{$eventitem->startdate}"/>
				<a href="javascript:NewCal('starttime','ddmmmyyyy',true,24)"><img src="themes/{$config.THEME}/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><br />
				{/if}
			</td>
		</tr>
		<tr>
			<td>End Time</td>
			<td>
				{if $readonly}
				{$eventitem->enddate}
				{else}
				<input type="text" name="endtime" id="endtime" value="{$eventitem->enddate}"/>
				<a href="javascript:NewCal('endtime','ddmmmyyyy',true,24)"><img src="themes/{$config.THEME}/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><br />
				{/if}
			</td>
		</tr>
		<tr>
			<td>Reminder</td>
			<td>
				{if $readonly}
					{if $alertitem->id != -1}
						{$alertitem->AlertTime}
					{else}
						No Alert Set.
					{/if}
					
				{else}
				<div>
					Remind Me at: 
					<input type="text" id="reminder" value="
{if $alertitem->id == -1}
{else}
{$alertitem->AlertTime}
{/if}" name="alert_time"><a href="javascript:NewCal('reminder','ddmmmyyyy',true,24)"><img src="themes/{$config.THEME}/images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><br />
Message: <input type="text" value="{if $alertitem->id == -1}

{else}
{$alertitem->Message}
{/if}" name="alert_message" />
					
				</div>
				{/if}
			</td>
		</tr>
		{if $eventitem->id > -1}
		<tr>
			<td>Linked Items</td>
			<td>
				{foreach from=$linkitems key=id item=item name=items}
					<a href='index.php?option=office&cmd={if $item.itemType=='doc' }viewdoc{else}viewcalendaritem{/if}
					&id={$item.id}'>{$item.name}
					 ({if $item.itemType=='doc' }doc{else}{$item.type}{/if}
					  id: {$item.id})</a><br>
				{/foreach}
				{if $readonly}

				{else}<br>
				<A href="#" onclick="javascript:showBrowser()";>Link Document</a> | 
				<a href="#" onclick="javascript:showEvBrowser()";>Link Event/Task</a>
				{/if}
			</td>
		</tr>	
		{/if}
		{if !$readonly}
		<tr>
			<td>Task</td>
			<td>
				<input type="checkbox" name="isTask" id="isTask" {if $eventitem->istask} checked{/if}
				onclick='toggleTaskStatus()';>

			</td>
		</tr>
		{/if}
		<tr>
			<td>Assigned To:</td>
			<td>
				
				{foreach from=$eventitem->members key=id item=member name=members}
					{if $readonly}
						{if $member.assigned}
							{$member.displayname}({$id}){if !$smarty.foreach.members.last},{/if}
						{/if}
					{else}	
						<input type="checkbox" name="assignedTo_{$id}" {if $member.assigned}checked {/if} id="assignedTo" value="1">{$member.displayname}
					{/if}

				{/foreach}
			</td>
		</tr>
		{if $eventitem->istask}
		<!-- It would be nice if this sort of stuff was client side hidden/visible! //-->
		<tr id='taskStatus' style="display:table-row">
			<td>Status:</td>
			<td>
				{if $readonly}
					{if $eventitem->completed}Completed{else}Not Completed{/if}
				{else}
					<input type="checkbox" name="completed" {if $eventitem->completed}checked {/if} 
					id="completed" value="1">Completed
				{/if}
			</td>
		</tr>
		{/if}
				<tr>
			<td colspan="2">

				{if $readonly}
					<A href="index.php?{$editCalendarPath|default:'option=office&cmd=editcalendaritem'}&id={$eventitem->id}"><img src="{$config.calendar_icon_edit}">Edit</a> | 
					<A href="index.php?{$deleteCalendarPath|default:'option=office&cmd=deletecalendaritem'}&id={$eventitem->id}"><img src="{$config.calendar_icon_delete}">Delete</a> | 
					<a href="index.php?{$closeCalendarPath|default:'option=office&cmd=viewcalendar'}"><img src="{$config.system_icon_close}">Close</a>
				{else}
				<input type="submit" value="Save"/>
				<a href="index.php?option=office&cmd=viewcalendar"><img src="{$config.system_icon_close}" />Close</a>
				{/if}
			</td>
		</tr>
		</table>
	
</form>
</div>
</div>