	<div class="sidebarBox">
		<div class="sidebarTitle">{$strings.MSG_CALENDAR}</div>
		<div id="ui_calactions" style="display:none" class="sidebarContent">
			<ul class="nolist">
			<li><img src="{$config.calendar_icon}"><A href="index.php?option=office&cmd=viewcalendar">{$strings.MSG_VIEW_CALENDAR}</a></li>
			<li><img src="{$config.calendar_icon_add}"><A href="index.php?option=office&cmd=newcalendaritem">{$strings.MSG_NEW_EVENT}</a></li>
		<!--		<li><img src="{$config.task_icon_add}"><A href="index.php?option=office&cmd=newtask">New Task...</a></li>//-->
			</ul>
		</div>
	</div>
	<div class="sidebarBox">
		<div class="sidebarTitle">{$strings.MSG_TASKS}</div>
		<div id="ui_taskactions" style="display:none" class="sidebarContent">
			<ul class="nolist">
			<a href="index.php?option=office&cmd=newTaskItem"><img src="{$config.task_icon_add}"></a><a href="index.php?option=office&cmd=newTaskItem">{$strings.MSG_NEW_TASK}</a>
			{foreach from=$tasklist_tasks item=task}
			<li>
			<img src="{$config.task_icon_small}">{$task->startdate} <br/> <a href="/index.php?option=office&cmd=viewcalendaritem&id={$task->id}">{$task->title}</a></li>
			{/foreach}
			</ul>
		</div>
	</div>