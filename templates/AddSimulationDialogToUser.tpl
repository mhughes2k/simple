<script language="javascript">
{literal}
	function cancelAddSimDialog() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('addSimDialog').style.display='none';
	}
	function ShowAddSimDialog(option,command,otherargs) {
;
		document.getElementById('dialogBlock').style.display='block';
		var dialog =document.getElementById('addSimDialog');
    var vOffset = document.getElementById('usersSimulationPermissions').offsetTop;
    //alert(vOffset);		
		document.getElementById('addSimDialog').style.display='block';
		dialog.style.top = vOffset-250+"px";
	}

{/literal}
</script>
<div id="addSimDialogBlock" class="pop_up"></div>
<div class="pop_up" id="addSimDialog">
<!-- style="
position:absolute;
	left:90px;
	width:400px;
	height:260px;	
	border:solid 1px black;
	display:none;
	background-color:white;
	z-index:1000;
	padding:5px;" -->
<div class="pop_up_title">Add Simulation To User/group</div>
<form action="index.php?option=siteAdmin&cmd=viewUser&method=addSimulation2User&userId={$user->id}#simulationPermissions" method="post">

<p>Assign to transaction as a Student or Tutor</p>
<div style="overflow:scroll;height:300px;">
<table width="100%">
<tr><th>Student</th><th>Tutor</th><th>Transaction</th></tr>
{foreach from=$addsimulationdialogprojects item=project}
<tr>
<td><input type="checkbox" name="simulationIds[]" value="{$project->id}" /></td>
<td><input type="checkbox" name="tutorSimulationIds[]" value="{$project->id}" /></td>
<td>{$project->GetName()}</td>
</tr>
{/foreach}
</table>
</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddSimDialog()">Cancel</a>

</form>
</div>
