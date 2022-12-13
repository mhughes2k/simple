<div class="areaTitle">Link Projects</div>
<div class="manageSectionContent">

<form action="index.php" method="post">
<input type="hidden" name="option" value="projectadmin" />
<input type="hidden" name="cmd" value="dolinkroles" />
<input type="hidden" name="sourceSimId" value="{$sourceSim->id}" />
<input type="hidden" name="linkSimId" value="{$linkSim->id}" />
<p>You must now select which role/character in 
the source project is linked to other project.</p>

<p>The Master Simulation is <b>{$sourceSim->Name}</b></p>
<p>Please select the Character being played by the Linked Simulation's Player: 
<select id="masterToLinkedRole" name="masterToLinkedRole">
{foreach from=$sourceSimCharacters item=char}
<option value="{$char->projectrole}">{$char->name} ({$char->projectrole})</option>
{/foreach}
</select>
</p>
<p>The Linked Simulation is <b>{$linkSim->Name}</b></p> 


<p>Please select the Character being played by the Master Simulation's Player:
<select id="linkedToMasterRole" name="linkedToMasterRole">
{foreach from=$linkedSimCharacters item=char}
<option value="{$char->projectrole}">{$char->name} ({$char->projectrole})</option>
{/foreach}
</select></p>
<p>Do you want to Sync the roles ?<input type="checkbox" name="syncroles" value="1" checked/></p>

<p>Do you want to Sync the variables ?<input type="checkbox" name="syncvars" value="1" checked/></p><div>
</div>
<input type="submit" value="Link Projects" />
</form>
</div>

