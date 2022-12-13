<div id="variablelist" class="sectionBox_manage">
	<div class="sectionTitle">Variables</div>
	{if $variablelist_Message!=''}<p>{$variablelist_Message}</p>{/if}
	<table id="variablelisttable">
		<tr>
			{if $editVariables}<th/>{/if}
			<th>Variable</th>
			{if !$variablelist_noValues}
				<th>Value</th>
			{/if}
		</tr>
		{foreach from=$variablelist key=name item=val name=variablelist} 
			{if $name!='' & $editVariables}
				<tr {if $smarty.foreach.variablelist.index % 2 == 0} class="variablelisttable_alternate_row" {/if}>
					<form action="index.php" method="post" name="update_{$name}">
						<td>
							<input type="hidden" name="option" value="{$option_target}"/>
							<input type="hidden" name="cmd" value="updatevar"/>
							<input type="hidden" name="pid" value="{$pid}"/>
							<input type="hidden" name="redir" value="{$PostSaveVarRedirect}"/>
						</td>
						<td><input type="hidden" name="update_var_name" value="{$name}"/>{ldelim}{$name}{rdelim}</td>
						<td><input type="text" name="update_var_value" value="{$val|escape:'htmlall'}"/></td>
						<td><input type="submit" value="Save"/><a href="javascript:deleteVariableDialog('{$name}');" title="Delete '{ldelim}{$name}{rdelim}'">Delete</a></td>
					</form>
				</tr>
			{else}
				<tr {if $smarty.foreach.variablelist.index % 2 == 0} class="variablelisttable_alternate_row" {/if}>
					<td>{ldelim}{$name}{rdelim}</td>
					{if !$variablelist_noValues}
						<td>{$val}</td>
					{/if}
				</tr>
			{/if}
		{/foreach}
		{if $editVariables}
			<tr >
				<form action="index.php" method="post" name="addvariable">
					<td>Add New:							
						<input type="hidden" name="option" value="{$option_target}"/>
						<input type="hidden" name="cmd" value="addvariable"/>
						<input type="hidden" name="pid" value="{$pid}"/>
					</td>
					<td><input type="text" name="update_var_name" value=""/></td>
					<td>
            <input type="text" name="update_var_value" value=""/>
            <input type="hidden" name="redir" value="{$PostSaveVarRedirect}"/>
          </td>
					<td><input type="submit" value="Add" name="add_new_var" />
				</form>
			</tr>
		{/if}
	</table>
	{$variablelist_Message2}
	{if $editVariables}

	{/if}
</div>
