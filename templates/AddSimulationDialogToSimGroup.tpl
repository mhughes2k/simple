<script language="javascript">
{literal}
	function cancelAddSimDialog() {
		document.getElementById('addSimDialogBlock').style.display='none';
		document.getElementById('addSimDialog').style.display='none';
	}
	function ShowAddSimDialog(option,command,otherargs) {
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('addSimDialog').style.display='block';
	}

{/literal}
</script>
<div id="addSimDialogBlock" class="pop_up"></div>
<div class="pop_up" id="addSimDialog" style="
position:absolute;
	top:200px;
	left:90px;
	width:400px;
	height:260px;	
	border:solid 1px black;
	display:none;
	background-color:white;
	z-index:1000;
	padding:5px;">


<form action="index.php" method="get">
<div class="pop_up_title">Add Simulation To Group</div>
<p>Please select a Simulation to add to this group</p>

<input type="hidden" name="option" value="projectadmin" />
<input type="hidden" name="cmd" value="doaddprojecttogroup" />
<input type="hidden" name="projectgroupid" value="{$ProjectGroup->id}"/>
<div style="overflow:scroll;height:180px;">
{foreach from=$allprojects item=project}
<input type="checkbox" name="ids[]" value="{$project->id}" />{$project->GetName()}<br/>
{/foreach}
</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddSimDialog()">Cancel</a>

</form>
</div>
