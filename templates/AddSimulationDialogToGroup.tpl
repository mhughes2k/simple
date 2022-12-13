<script language="javascript">
{literal}
	function cancelAddSimDialog() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('addSimDialog').style.display='none';
	}
	function ShowAddSimDialog(option,command,otherargs) {
		document.getElementById('dialogBlock').style.display='block';
		
		var dialog =document.getElementById('addSimDialog');
		var vOffset = document.getElementById('groupSimulationPermissions').offsetTop;
    //alert(vOffset);		
		document.getElementById('addSimDialog').style.display='block';
		dialog.style.top = vOffset+"px";
		document.getElementById('addSimDialog').style.display='block';
	}

{/literal}
</script>
<div id="addSimDialogBlock" class="pop_up"></div>
<div class="pop_up" id="addSimDialog">
<!-- style="
position:absolute;
	top:200px;
	left:90px;
	width:400px;
	height:260px;	
	border:solid 1px black;
	display:none;
	background-color:white;
	z-index:1000;
	padding:5px;" -->
<div class="pop_up_title">Add Simulation To User/group</div>
<form action="index.php?option=siteAdmin&cmd=viewUserGroup&method=addSimulation2UserGroup&userGroupId={$usergroup->id}#simulationPermissions" method="post">
<p>Please select a Simulation to add to this User/Group</p>
<div style="overflow:scroll;height:180px;">
{foreach from=$addsimulationdialogprojects item=project}
<input type="checkbox" name="simulationIds[]" value="{$project->id}" />{$project->GetName()}<br/>
{/foreach}
</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddSimDialog()">Cancel</a>

</form>
</div>
