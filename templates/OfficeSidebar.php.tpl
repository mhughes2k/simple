
<div id="projectSidebar">	
  {if !$projectInActive & $showStaffTools}
    {include file="staffToolsSideBar.tpl" }
  {/if}
  
  {$pre_sidebar_extensions}
  
	{if isset($folders)}
		<div class="sidebarBox">
	    <a name="folders"></a>		
      <div class="sidebarTitle">{$strings.MSG_FOLDERS}</div>
			<div id="ui_folders" class="sidebarContent">  
				<ul id="folderList" class="nolist">
	      			{foreach from=$folders key=key item=folder}
					{if $key!=$deliveryFolder && $key!=$sentFolder}
						<li><img src="{$config.office_folder_icon}" border="0">
						{if $folder->newitems > 0}
							<strong><a href="index.php?option=office&cmd=view&folder={$folder->folderid}">{$folder->name} 
							({$folder->newitems})</a></strong>
						{else}
							<a href="index.php?option=office&cmd=view&folder={$folder->folderid}">{$folder->name} 
							</a>
						{/if}
						</li>
					{/if}
	    	  		{/foreach}
					<li><a id="showfoldermanager"  class="tooltip">{$strings.MSG_MANAGE_FOLDERS}</a></li>
		    	</ul>
		    	
				
			<div style="margin-left: 5px;">
			<form action="index.php" enctype="multipart/form-data" method="post">
			<input type="hidden" name="option" value="office">
			<input type="hidden" name="cmd" value="uploaddocument">
			<input type="hidden" name="redir" value="">
			<input type="file" name="uploadDocument" >
			<select name="destfolderid">
			{foreach from=$folders key=id item=item}
			{if $item->additem}
			<option value="{$item->folderid}">{$item->name}</option>
			{/if}
			{/foreach}
			</select>
			<input type="submit" value="Upload"/>
			</form>
			</div>				
			
		    </div>
	 </div>
	 
	 <div class="sidebarBox">
		<a name="correspondence"></a>		
		<div class="sidebarTitle">{$strings.MSG_CORRESPONDENCE}</div>
		<div id="ui_correspondence" class="sidebarContent">  
		<ul id="correspondenceList" class="nolist">
	      	{foreach from=$correspondence key=key item=folder}
				<li><img src="{$config.office_folder_icon}" border="0">
				{if $folder->newitems > 0}
					<strong><a href="index.php?option=office&cmd=view&folder={$folder->folderid}">{$folder->name} 
					({$folder->newitems})</a></strong>
				{else}
					<a href="index.php?option=office&cmd=view&folder={$folder->folderid}">{$folder->name}</a>
				{/if}
				</li>
	    	{/foreach}
	   </ul>
	</div>
	</div>
	 
  {/if}
  {if $calendarEnabled}
	  {include file="taskslists.tpl"} 
  {/if}
  {include file="resourceSideBar.tpl" }
  {include file="userSideBar.tpl" }
  {$post_sidebar_extensions}
</div>
