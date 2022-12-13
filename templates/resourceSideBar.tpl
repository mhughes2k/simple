{if isset($showMap)}
<div id="mapDirLink" class="sidebarBox">
  <div id="resourceList" class="sidebarTitle">Map & Directory</div>
    <div id="ui_mapAndDir" style="display:none" class="sidebarContent">
     <ul class="nolist">
        <li><img src="{$config.directory_icon}"/> <a href="{$home}index.php?option=directory">{$strings.MSG_DIRECTORY}</a></li>
        <li><img src="{$config.map_icon}"/> <a href="{$home}index.php?option=map">{$strings.MSG_MAP}</a></li>
     </ul>
    </div>
</div>
{/if}
{if isset($resourceArray)}
<div id="resourceLinks" class="sidebarBox"> 
  <div id="resourceList" class="sidebarTitle">{$strings.MSG_RESOURCES}</div>
  <div id="ui_resources" style="display:none" class="sidebarContent">
   <ul class="nolist">
    {foreach from=$resourceArray key=key item=resourceItem}
      <li>
        {if $resourceItem.contenttype=='url'}
          <img src="{$config.url_resource_icon}"/>
          <a href="{$resourceItem.content}" target="_blank">
            {$resourceItem.visiblename}
          </a>
        {else}
          <img src="{$config.doc_resource_icon}"/>
            <a href="index.php?option=download&docuid={$resourceItem.doctemplateuid}&docType=doc_templ" target="_blank">
              {$resourceItem.filename}
            </a>
          {/if}
          </li>
        {/foreach}
  </ul>    
  </div>
</div>
{/if}