<div class="sectionBox_manage">
  <div class="sectionTitle">
    Resources
  </div>
  <p>
    You can click on a Resource to download/view the 
    <strong>raw</strong>
    resource (i.e. without any variable substitutions made).
  </p>
  <div>
    <table> 
    <tr>
    <th>Resource ID</th>
    <th>Resource Name</th>
    <th>Resource Filename</th>
    </tr>
    {foreach from=$docTemplates key=key item=docTemplate}
      <tr>
        <td>
        {$docTemplate.doctemplateuid}
        </td>
        <td>
          <!--<a href="index.php?option=projectTemplateAdmin&cmd=editDocument&docUid={$docTemplate.doctemplateuid}">//-->
          <a href="index.php?option=download&docuid={$docTemplate.doctemplateuid}&download=&docType=doc_templ"> {$docTemplate.visiblename}</a></td>
        <td>{$docTemplate.filename}</td>
      </tr> {/foreach}
    </table>
  </div>
  <div class="sectionTitle">Add Resource 
	<script language="javascript">
	{literal}
  	function showDiv(divName) {
  		document.getElementById(divName).style.display='block';
  	}
  	function hideDiv(divName) {
  		document.getElementById(divName).style.display='none';
  	}
  	{/literal}
  </script>
  <a href="Javascript:showDiv('addResourceDiv');">show</a> /
  <a href="Javascript:hideDiv('addResourceDiv');">hide</a></div>
  <div id="addResourceDiv" style="display:none;">
  <p>You can use this form to add a new resource.</p>
  <form name="addform" method="post" action="index.php" enctype="multipart/form-data">
  <input type="hidden" name="pid" value="{$pid}">
  <input type="hidden" name="option" value="projecttemplateadmin">
  <input type="hidden" name="cmd" value="addresource">
  <p>Resource Name:<input type="text" name="resourceName" style="position:absolute;left:300px"></p>
    <script language="javascript">
	{literal}
  	function showInputDiv(contentType) {
  		if (contentType=='file') {
	  		//alert('content type is file');
	  		document.getElementById('fileContent').style.display='block';
	  		document.getElementById('urlContent').style.display='none';
	  	} else if (contentType=='url') {
	  		//alert('content type is url');
	  		document.getElementById('urlContent').style.display='block';
	  		document.getElementById('fileContent').style.display='none';
	  	} 
  	}
  	{/literal}
  </script>
  <p>Content Type:<select name="contentType" style="position:absolute;left:300px" onchange="Javascript:showInputDiv(contentType.value);">
  	<option value="file" selected="selected">file</option>
  	<option value="url">url</option>
  </select></p>
  <p id="fileContent">Content (file):<input type="file" name="newcontent" style="position:absolute;left:300px"></p>
  <p id="urlContent" style="display:none;">Content (url):<input type="text" name="urlcontent" style="position:absolute;left:300px"></p>
  <p>Visible to Player:<select name="playervisible" style="position:absolute;left:300px">
  	<option value="yes">yes</option>
  	<option value="no">no</option>
  </select></p>
    <input type="submit" value="Add Resource">
  </form>
  </div>
  
  <div class="sectionTitle">Update Resource 
  <a href="Javascript:showDiv('editResourceDiv');">show</a> /
  <a href="Javascript:hideDiv('editResourceDiv');">hide</a></div>
  <div id="editResourceDiv" style="display:none;">
  <p>You can use this form to replace the contents of a resource. Enter the ID (the number to the right of a resource) and then select a file to upload from your PC to replace it.</p>
  <p>The resource will continue to be referred to by its original name & id.</p>
  <form name="editform" method="post" action="index.php" enctype="multipart/form-data">
  <input type="hidden" name="pid" value="{$pid}">
  <input type="hidden" name="option" value="projecttemplateadmin">
  <input type="hidden" name="cmd" value="updateresource">
  <p>Resource Id:<input type="text" name="resourceId" style="position:absolute;left:300px"></p>
  <script language="javascript">
	{literal}
  	function showInputDiv2(contentType) {
  		if (contentType=='file') {
	  		//alert('content type is file');
	  		document.getElementById('fileContent2').style.display='block';
	  		document.getElementById('urlContent2').style.display='none';
	  	} else if (contentType=='url') {
	  		//alert('content type is url');
	  		document.getElementById('urlContent2').style.display='block';
	  		document.getElementById('fileContent2').style.display='none';
	  	} 
  	}
  	{/literal}
  </script>
  <p>Content Type:<select name="contentType2" style="position:absolute;left:300px" onchange="Javascript:showInputDiv2(contentType2.value);">
  	<option value="file" selected="selected">file</option>
  	<option value="url">url</option>
  </select>
  <p id="fileContent2">Replacement Content (file):<input type="file" name="newcontent" style="position:absolute;left:300px"></p>
  <p id="urlContent2" style="display:none;">Replacement Content (url):<input type="text" name="urlcontent" style="position:absolute;left:300px"></p>

  <input type="submit" value="Update Resource">
  </form>
  </div>
</div>
