{*
This displays the list of "roles" in a ProjectTemplate.
Roles are used when instantiating a project to create the "characters".
*}
<div class="sectionBox_manage">
	<div class="sectionTitle">Roles</div>
	<div>
		<p>Roles are used when instantiating a project to create the "characters".</p>
		<table border="1">
		<tr>
		<th>Role</th>
		<th>Character Name</th>
		<th>Address</th>
		<th>Location</th>
		<th>Visible in directory?</th>
		</tr>
		
		{foreach from=$rolelist_characters key=key item=role}
			{if $rolelist_edit}
			<form action="index.php" method="post" name="edit_character_{$key}">
  			<tr>
  				<td>
  				<input type="hidden" name="option" value="projecttemplateadmin"/>
  				<input type="hidden" name="cmd" value="doupdatetemplaterole" />
  				<input type="hidden" name="oldroleid" value="{$role.projecttemplateroleid}" />
  				<input type="hidden" name="templateid" value="{$role.projecttemplateid}" />
  				<input type="hidden" 
  				value="index.php?option=projecttemplateadmin&cmd=viewProjectTemplate&projectTemplateId={$project->projecttemplateuid}"
  				name="redir" />
  				<input type="hidden" value="{$role.projecttemplateroleid}" name="projectrole"/>
  				{$role.rolename}
  				</td>
  				<td><input type="text" value="{$role.namerule}" name="name"/></td>
  				<td><input type="text" value="{$role.addressrule}" name="address" /></td>
  				<td><input type="text" value="{$role.locationrule}" name="location" /></td>
  				<td align="center"><input type="text" value="{$role.directoryrule}"/></td>
  				<td><input type="submit" value="Save"/>
  				</tr>
  			</form>
			{else}
  			<tr>
  				<td>{$role.rolename}</td>
  				<td><b>{$role.namerule}</b></td>
  				<td>{$role.addressrule}</td>
  				<td>{$role.locationrule}</td>
  				<td>{if ($role.directoryrule!='0')}Yes{else}No{/if}</td>
  			</tr>
			{/if}
		{foreachelse}
		<tr>
		<td colspan="6">
			No Characters Found.
		</td>
		</tr>
		{/foreach}
		</table>
<!--		<a href="#" class="tooltip">Add Character</a>//-->
		<div>
			<ol>
			<li class="warning"><strong>Note</strong> Changes to characters will only affect <strong>new</strong> projects. Currently running projects
			are not affected by changes made here</li>
			<li><span class="italic">Role</span> is the name that the role is known as within the project. <br/>Roles can only be (re)defined when developing the template.</li>
			<li><span class="italic">Character Name</span> is the value or variable used to specify the actual name when a project is created</li>
			<li><span class="italic">Address</span> is the value or variable used to specify the actual address (e-mail) when a project is created</li>
			<li><span class="italic">Location</span> is the value or variable used to generate the actual physical location of the role in the map when a project is created</li>
			</ol>
		</div>
	</div>
</div>
