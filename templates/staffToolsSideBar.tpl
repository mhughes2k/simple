  {if isset($resourceArray)}
  	<div id="staffResourceLinks" class="sidebarBox">
  	<div id="staffResourceList" class="sidebarTitle staffOnly">{$strings.MSG_STAFF_TOOLS_TEXT}</div>
  	<a href="javascript:toggleElement('ui_stafftoolsside');"></a>
  	 <div id="ui_stafftoolsside" style="display:none" class="sidebarContent">
	   <ul class="nolist">
	      <li><a id="showned">{$strings.MSG_STAFF_EVENTS_TEXT}</a></li>
	      <!--<li><a id="showned">NED Version 2</a></li> future better ned work//-->
	      <li><a id="showstafftools">{$strings.MSG_STAFF_RESOURCES_TEXT}</a></li>
	      <li><a href="index.php?option=projectAdmin&cmd=viewProject&projectId={$pid}">{$strings.MSG_MANAGE_PROJECT}</a></li>
	      <!--
		  	<li><a href="JavaScript:toggleElement('ui_ned');">{$strings.MSG_STAFF_EVENTS_TEXT}</a></li>
    		<li><a href="JavaScript:toggleElement('ui_stafftools');">{$strings.MSG_STAFF_RESOURCES_TEXT}</a></li>
    		<li><a href="index.php?option=projectAdmin&cmd=viewProject&projectId={$pid}">{$strings.MSG_MANAGE_PROJECT}</a></li>
    		//-->
    		{$staffResourcesExtension}
		  </ul>    
	   </div>
  </div>
  {$staffToolsExtension}
  {/if}
