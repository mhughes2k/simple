{literal}
<script language="JavaScript">
  
</script>
{/literal}
<div id="ui_folderManager" class="pop_up">
<div class="pop_up_title">Folder Manager</div>

<p>You can add and remove folders here.</p>
<div class="whiteBG">
<table>
<tr>
<form id="addFolder" action="index.php" >
  <td>
  <input type="hidden" name="option" value="office"/>
  <input type="hidden" name="cmd" value="doaddfolder"/>
  <input type="hidden" name="projectid" value="{$pid}"/>Add new Folder:
  <input type="text" name="foldername" id="addFolderName" value=""/></td>
  <td><input type="submit" value="Add"/></td>
</form>
</tr>
{if (count($folders) >0)}
  <tr>
  	<th>
  		Name
  	</th>
  </tr>
  {foreach from=$folders key=id item=item}
  <tr>
  {if ($item->canbedeleted)}
  	<form id="editFolder_{$id}" action="index.php">
  		<td>
  		<input type="hidden" name="option" value="office"/>
  		<input type="hidden" name="cmd" value="dosavefolder"/>
      <input type="hidden" name="folderid" value="{$item->folderid}"/>
  		<input type="text" name="foldername" id="foldername" value="{$item->name}"/>
  		</td>
  		<td>
  			<input type="submit" id="button_save_{$id}" value="Save"/>
  		</td>
  	</form>
  	<td>
  	<form id="deleteFolder" action="index.php">
  		<input type="hidden" name="option" value="office"/>
  		<input type="hidden" name="cmd" value="dodeletefolder"/>
      <input type="hidden" name="folderid" value="{$item->folderid}"/>
      <input type="submit" value="Delete" name="deleteFolder"/>
      </td>
	 </form>
  {else}
	{if $item->folderid!=$sentFolder && $item->folderid!=$deliveryFolder}
	<td>{$item->name}</td>
	{/if}
  {/if}
  </tr>
  {foreachelse}
  
  {/foreach}
{else}
  <tr><td colspan="2">No folders in this project.</td></tr>
{/if}

</table>

</div>
<!--href="JavaScript:toggleStaff('ui_folderManager');"//-->
<div>
<a class="cancel" >Close</a></div>
</div>
