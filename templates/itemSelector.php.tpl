<form>
{literal}
<script>
	var retVal= -1;

	function selectItem(itemId) {
		retVal = itemId;
//		window.close();
		return false;	
	}	
</script>

{/literal}
{if isset($folders)}
  <div id="folderList">Folders<br />
    <ul>
      {foreach from=$folders key=key item=folder}
      <li><a href="index.php?option=office&cmd=selectItem&folder={$folder->folderId}">{$folder->name}</a></li>
      {/foreach}
    </ul>
  </div>
  {/if}
</div>
<div id="folderContents">
  <table>
    <tr>
      <th></th>
      <th>Subject</th>
      <th>From</th>
      <th>To</th>
      <th>Received/Sent</th>
    </tr>
	{if count($contents) > 0}
	    {foreach from=$contents key=id item=i}
	    <tr>
	      <td><input type="button" name="select" onClick="javascript:selectItem({$id});" value="Select"></td>
	      <td>{if $i.icon != ""}<a target="_blank" href="index.php?option=office&cmd=viewdoc?documentid={$i.documentuid}"><img  border="0"src="{$i.icon}" /></a>{/if}</td>
	      <td><a target="_blank" href="index.php?option=office&cmd=viewdoc&documentid={$i.documentuid}">{$i.filename}</a></td>
	      <td>{$i.from} </td>
	      <td>{$i.to} </td>
	      <td>{$i.date}</td>
	    </tr>
	    {/foreach}
	      {else}
	      <tr>
			<td colspan="4">No items found.</td>
	      </td>
  {/if}
</table>

</form>