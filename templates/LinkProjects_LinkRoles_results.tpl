<div class="areaTitle">Link Projects Results</div>
<div class="manageSectionContent">
<p>You attempted to cast the Players of <strong>{$sourceSim->Name}</strong> in to the character <em>{$masterSimulationCharacter->name} ({$masterSimulationCharacter->projectrole})</em> in the simulation <strong>{$linkedSim->Name}</strong>.</p>
<p>You attempted to cast the Players of <strong>{$linkedSim->Name}</strong> in to the character <em>{$linkedSimulationCharacter->name} ({$linkedSimulationCharacter->projectrole})</em> in the simulation <strong>{$sourceSim->Name}</strong>.</p>

<div style="text-align:center">
<a href="index.php?option=projectadmin">Back to Manage Simulations</a>
</div>

{if (count($linkedVars)>0)} 
<p>The following variables in the Linked Simulation were synced with variables in the Master Simulation.</p>

<ul>
{foreach from=$linkedVars item=var}
<li>{$var}</li>
{/foreach}
</ul>

{else}
<p>No variables in the Linked Simulation were synced with variables in the Master Simulation.</p>
{/if}
<div style="text-align:center">
<a href="index.php?option=projectadmin">Back to Manage Simulations</a>
</div>
</div>
