<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>

<body>-->
<div class="areaTitle">{$title}</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<table>
  <tr>
    <th>Name</th><th>Address</th><th>Email</th><th>Link</th>
    {if false}<th>LinkedProjects(remove)</th>{/if}
  </tr>
  
  {section name=entry loop=$directoryEntries}
  {assign var='props' value=`$directoryEntries[entry]->Properties`}
  {if $directoryEntries[entry]->name!='' & $directoryEntries[entry]->name!=' '}
    <tr>
      <td>
        
        {if $props.infolink!=''}
          {if strtolower(substr($props.infolink,0,4))=='http'}
            <a target="_blank" href='{$props.infolink}'>{$directoryEntries[entry]->name}</a>          
          {else}          
          	<a target="_blank" href='{$config.home}index.php?option=directory&cmd=viewitem&id={$props.infolink}&pid={$projectId}'>{$directoryEntries[entry]->name}</a>
        	{/if}    	
        {else}
        	{$directoryEntries[entry]->name}
        {/if}
        {*$directoryEntries[entry]->projectrole*}
      </td>
      <td>
        {$directoryEntries[entry]->location}
      </td>
      <td>
      {*<a href="index.php?option=office&cmd=writeenvelope&to={$directoryEntries[entry].address|escape:'url'}">{$directoryEntries[entry].address}</a>*}
      {$directoryEntries[entry]->address}
      </td>
      <td>
        {if $props.infolink!=''}
          {if strtolower(substr($props.infolink,0,4))=='http'}
            <a target="_blank" href='{$props.infolink}'><IMG src="{$config.directory_link}" title="View {$directoryEntries[entry]->name}"></a>          
          {else}          
        	 <a target="_blank" href='{$config.home}index.php?option=directory&cmd=viewitem&id={$props.infolink}&pid={$projectId}'><IMG src="{$config.directory_link}" title="View {$directoryEntries[entry]->name}"></a>
        	{/if}
        {/if}
      </td>
      {if $directoryEntries[entry]->extension}
      {$directoryEntries[entry]->extension}

      {/if}
      {if false}
        {assign var='linkedProjects' value=`$directoryEntries[entry]->LinkedProjects`}
        {if count($linkedProjects)>0} 
          <td>
          {assign var='linkedProjects' value=`$directoryEntries[entry]->LinkedProjects`}
          {foreach from=$linkedProjects key=id item=proj name=lps}
          <A href="unlink Item{$directoryEntries[entry]->ID} from sim {$id}" title="UnLink ">{$id}</a> 
          {/foreach}
          </td>
        {/if}
      {/if}  
    </tr>
  {/if}
  {sectionelse}
    <tr>
      <td colspan="4">
        No Entries Found.
    </td>
    </tr>
  {/section}
</table>
<div id="directoryBackFunction"><br /><a href="{$config.home}index.php?option=office">Back</a></div>
</div>
</div>
</body>
</html>
