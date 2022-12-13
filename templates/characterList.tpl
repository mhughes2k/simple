<div id="LSDialogBlock"  ></div>
{include file="AddLinkedSimulationDialogToSimulation.tpl"}

<div class="sectionBox_manage" id="charactersSection">
	<!--<style>

	{literal}
		html>body tbody.scrollcontent{
			display:block;
			height:160px;
			overflow:auto;
			width:100%;
		}
	
	{/literal}
	</style>-->

	<div class="sectionTitle">Characters</div>
	<div>
		<table style="width:100%;display:block">
			<thead><!-- class="fixedheader" -->
			<tr>
				<th>Project Role</th>			
				<th>Character Name</th>
				<th>Address</th>
				<th>Location</th>
				<th>Visible in directory?</th>
				<th>Linked Project(s)</th>
				{if $editCharacters}
				<th>Link Project</th>
				<th></th>
				{/if}

			</tr>	
			</thead>
			<tbody> <!-- class="scrollcontent" -->
			{foreach from=$characters key=key item=character}
				{if $editCharacters}
					<form action="index.php" method="post" name="edit_character_{$key}">
						<tr>
							<td>
					<input type="hidden" name="option" value="projectadmin" />
					<input type="hidden" name="cmd" value="saveCharacterDetails" />
					<input type="hidden" name="redir" value="{$saveCharacterRedirectLocation}"/>
					<input type="hidden" name="directoryItemId" value="{$character->directoryid}"/>
							<!-- 
              we probably want to do a look up to the BP with the projectrole attribute
							to find out the human readable Project Role
              //-->
							{$character->projectrole}</td>
							
							<td><input type="text" value="{$character->name}" name="name"/></td>
							<td><input type="text" value="{$character->address}" name="address" /></td>
							<td><input type="text" value="{$character->location}" name="location" /></td>
							<td align="center">
              <select name="dirvisible">
              <option value="1" 
              {if $character->directoryvisible==1}SELECTED{/if}>Yes</option>
              <option value="0"
              {if $character->directoryvisible==0}SELECTED{/if}>No</option>
              </select>
              
              </td>

							<td>
								{foreach from=$character->LinkedProjects key=k item=project name=lps}
								<a href="index.php?option=projectAdmin&cmd=viewproject&projectId={$project->id}">{$project->Name} ({$project->TemplateName})</a>
								{if !$smarty.foreach.lps.last}, {/if}
								{foreachelse}
								{/foreach}
							</td>
							<td><a href="javascript:ShowAddLinkedSimDialog('{$character->projectrole}');">Link Project</a></td>
							<td>
								<input type="submit" value="Save"/>
								<a href="javascript:void();" title="Delete '{$character->name}'">Delete</a>
							</td>
							
						</tr>
					</form>
					{else}
						<tr>
							<td>{$character->projectrole}</td>
							<td><b>{$character->name}</b></td>
							<td>{$character->address}</td>
							<td>{$character->location}</td>
							<td align="center">{if $character->directoryvisible}Yes{else}No{/if}</td>
			
							<td>
								{foreach from=$character->LinkedProjects item=project name=lps}
									<a href="index.php?option=projectAdmin&cmd=viewproject&projectId={$project->id}">{$project->Name} ({$project->TemplateName})</a>
									{if !$smarty.foreach.lps.last}, {/if}
								{foreachelse}
								{/foreach}
							</td>
						</tr>
					{/if}
			{foreachelse}
				<tr>
					<td colspan="6">
						No Characters Found.
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	{if $editCharacters}
<!--	<a href="#" class="tooltip">Add Character</a>//-->
	{/if}
	</div>
</div>
