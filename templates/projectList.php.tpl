<div class="areaTitle">active projects</div>
<div class="manageSectionContent">
<div id="projects" >
<p><strong>{$title}</strong></p>

<div>
    <table>
    <tr>
    	<th>
    		Simulation ID
    	</th>
    	<th>Name (Blueprint)</th>
    </tr>
{foreach from=$projects key=key item=project}
	<tr>
		<td style="text-align:center; width:120px">
		
				{$key}
		</td>
		<td>
			<a href="index.php?option=tl&cmd=select&projectid={$key}">{$project}</a>
		</td>
	</tr>
{foreachelse}
<tr><td>No Projects</td></tr>
{/foreach}
</table>
</div>
</div>
</div>