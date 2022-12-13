{literal}
<script language="JavaScript">
	function showAllSimGroups() {
	 //alert('clearing sellection');
		var obj = document.getElementById('ui_simgroups');
		//alert(obj.rows.length);
		var objLen= obj.rows.length;
		for(i = 0;i<objLen; i++) {
			alert(obj.rows[i].innerhtml);
      obj.rows[i].style.display='';
		}
	}
	function searchByName() {
		var filterTerm = document.getElementById('findGroupByName').value;
		//alert('Filter term: '+filterTerm);
		
		var obj = document.getElementById("ui_simgroups");
		var objLen= obj.rows.length;

			
		//alert("Length: "+objLen);
		//display all items
		for(i = objLen -1;i>0; i--) {
			obj.rows[i].style.display='';
		}
		if (filterTerm =='') {
			//alert('reseting');
			return;
		}

		for(i = objLen-1;i>=0; i--) {
			var currentOption = obj.rows[i].id;
			
//alert(currentOption.substr(9));
			if (currentOption.indexOf(filterTerm,9)==-1) {
				obj.rows[i].style.display='none';
			}
		}
	}
</script>
{/literal}
<div class="areaTitle">
	{$strings.MSG_MANAGE_P}
</div>
<div class="manageSectionContent">

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">{$strings.MSG_SIMULATIONS}</a></li>
		<li><a href="#tabs-2">{$strings.MSG_SIMULATION_GROUPS}</a></li>
	</ul>
	<div id="tabs-1">
	<div id="contentAccordion">
	{assign var=bpName value=`$projects[0]->ptName`}
	{foreach from=$projects item=project name="simlist"}
		{if ($bpName!=$project->ptName)}
			{if $smarty.foreach.simlist.index !=0}
				</table>
			{/if}
		<h3><a href="#">{$project->ptName}</a></h3>
		<table style="width:100%;">
		<tr>
			<th>{$strings.MSG_SIMULATION_NAME}</th>
			<th>{$strings.MSG_BASED_ON_BLUEPRINT}</th>
			<th>{$strings.MSG_CREATOR_TERM}</th>
			<th>Link</th>
		</tr>
		{/if}
		
		<tr>
			<td><a href="index.php?option=projectAdmin&cmd=viewProject&projectId={$project->id}">{$project->Name}</a></td>
			<td><A href="index.php?option=projecttemplateadmin&cmd=viewProjectTemplate&projectTemplateId={$project->templateId}">{$project->ptName}</a></td>
			<td>{$project->GetCreatorName()}</td>
			<td><a href="?option=projectAdmin&cmd=linkproject&pid={$project->id}">Link</a></td>
		</tr>
			
	
		{assign var=bpName value=`$project->ptName`}
				
	{foreachelse}
		<p>No Projects</p>	
	{/foreach}		
	</table>
	</div>
	</div>


	<div id="tabs-2">
	<div>
			<form method="post" action="index.php">
			<input type="hidden" name="option" value="projectadmin" />
			<input type="hidden" name="cmd" value="addprojectgroup" />
			Add Group: <input type="text" name="newgroupname" />
			<input type="submit" value="{$strings.MSG_ADD_GROUP}" name="addnewgroup"/>
			</form>
			Search: <input type="text" id="findGroupByName"/><input type="button" value="{$strings.MSG_SEARCH_TERM}" onclick="searchByName();"/>
			<A href="javascript:showAllSimGroups()">{$strings.MSG_SHOW_ALL}</a>
		    <table>
			    <tr>
				    <th>{$strings.MSG_SIMULATION_GROUP_NAME}</th>
				    <th>{$strings.MSG_NUMBER_OF_SIMULATIONS_IN_GROUP}</th>
			    </tr>
			    <tbody id="ui_simgroups">
				{foreach from=$projectgroups item=projectgroup}
					<tr id='simGroup_{$projectgroup->Name}'>
						<td>
							<a href="index.php?option=projectAdmin&cmd=viewProjectGroup&projectGroupId={$projectgroup->id}">
							{$projectgroup->Name}
							</a>
						</td>
						<td>{$projectgroup->MemberCount()}</td>
						<td><a href="index.php?option=projectAdmin&cmd=deleteProjectGroup&projectGroupId={$projectgroup->id}"><img src="{$config.user_icon_delete}"></a>
					</tr>
				{foreachelse}
					<tr>
						<td class="center">{$strings.MSG_NO_SIMULATION_GROUPS}</td>
					</tr>
				{/foreach}
				</tbody>
			</table>
	</div>	
	</div>	
</div>

</div>