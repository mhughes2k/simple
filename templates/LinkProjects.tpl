<div class="areaTitle">	Link Projects</div>
<div class="manageSectionContent">

<p>Any variable in the link sim that is defined in the source sim will be 
set to value of variable in the source simulation.</p>
<p>To link two projects, select a source project on the left, and a link project on the right
and click on "Link"</p>
<div>
{if $sourceSimId<0}
<form method="get" action="index.php">
<input type="hidden" name="option" value="projectAdmin"/>
<input type="hidden" name="cmd" value="linkproject"/>
Project:<select name="containerid" onchange="submit();">
{foreach from=$scenarios item=scenario}
<option value="{$scenario.containerid}"
{if $scenario.containerid ==$scenarioid} selected{/if}>{$scenario.name}</option>
{/foreach}
</select>

</form>
{else}
Project: {$scenarioName}
{/if}
<form name="linkprojectsform" name="linkform" method="post" action="index.php">
<input type="hidden" name="option" value="projectAdmin"/>
<input type="hidden" name="cmd" value="linkroles"/>
<script language="JavaScript" type="text/javascript">
{literal}

	function filter(filterObj,searchTerms) {
		var searchTerm = document.getElementById(searchTerms).value;

		var obj = document.getElementById(filterObj);
		var objLen= obj.options.length;
				
//		alert("Length: "+objLen);
		//display all items
		for(i = objLen -1;i>0; i--) {
			obj.options[i].style.display='block';
		}
		if (searchTerm =='') {
			//alert('reseting');
			return;
		}
//		alert('filtering '+searchTerm);
		for(i = objLen-1;i>=0; i--) {
			var currentOption = obj.options[i].text;
			if (currentOption.indexOf(searchTerm)==-1) {
				//alert('hiding : '+obj.options[i].text);
				obj.options[i].style.display='none';
			}
		}
	}

{/literal}
</script>
<div style="
height:200px;width:49%;border:solid 1px red
">
Source Project:<br/>
{if $sourceSimId>-1}
	<input type="hidden" name="sourceSimId" value="{$sourceSimId}" />{$sourceSimName}
{else}
<input type="text" id='searchsource' name="search" onChange="filter('sourceSimId','searchsource');"><br />
	<select id="sourceSimId" name="sourceSimId" size=10>
	{foreach from=$sourceSims item=sim}
	<option value="{$sim->id}">{$sim->id} {$sim->Name} ({$sim->TemplateName})</option>
	{foreachelse}
<option>No Simulations</option>
	
	{/foreach}
{/if}
</select>
</div>

<div style="
position:relative;
top: -200px;left:50%;height:200px;width:49%;border:solid 1px red
">
Linked Project:<br/>
<input type="text" id='searchlink' name="searchlink" onChange="filter('linkSimId','searchlink');"><br />
<select id="linkSimId" name="linkSimId" size=10>
{foreach from=$linkedSims item=sim}
{if $sim->id != $sourceSimId} 
<option value="{$sim->id}">{$sim->id} {$sim->Name} ({$sim->TemplateName})</option>
{/if}
{foreachelse}
<option>No Simulations</option>
{/foreach}
</select>
</div>

<input type="submit" value="Link" style="position:relative;top:-200px;"/>
</form>
</div>
</div>