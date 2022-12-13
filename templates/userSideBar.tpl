{if isset($projectUserArray)}
  <div id="userLinks" class="sidebarBox">
      <div id="userList" class="sidebarTitle">{$strings.MSG_MEMBERS}</div>
  	<div id="ui_members" style="display:none" class="sidebarContent">
	<ul class="nolist">
      {foreach from=$projectUserArray key=key item=projectUser}
        <li><a href="index.php?option=office&cmd=viewpublicprofile&id={$projectUser->id}">
          <img src="ImageHandler.php?context=avatar&userId={$projectUser->id}&type={$projectUser->imagetype}" 
          width="20" border="0"> {$projectUser->displayName}</a></li>
      {/foreach}
	  </ul>
    </div>
  </div>
{else}

{/if}
