<script language="javascript">
{literal}
	function cancelAddLinkedSimDialog() {
		document.getElementById('addLinkedSimDialogBlock').style.display='none';
		document.getElementById('addLinkedSimDialog').style.display='none';
	}


{/literal}
</script>
<div id="addLinkedSimDialogBlock" class="pop_up"></div>
<div class="pop_up" id="addLinkedSimDialog" style="
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


<form action="index.php?option=projectAdmin&cmd=editproject&method=addLinkedSimulation2Simulation&id={$project->id}" method="post">
<div class="pop_up_title">Add Linked Simulation To Simulation</div>
<p>Please select a Simulation to link to this Character</p>
<div style="overflow:scroll;height:180px;">
{foreach from=$linksimulationdialogprojects item=project}
<input type="radio" name="simulationId" value="{$project->id}" />{$project->id}{$project->GetName()}<br/>
{/foreach}
<input type="hidden" name="role" id="role" value="" />
</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddLinkedSimDialog()">Cancel</a>

</form>
</div>
<script language="javascript">
{literal}
	function ShowAddLinkedSimDialog(option,command,otherargs) {
		document.getElementById('addLinkedSimDialogBlock').style.display='block';
		document.getElementById('addLinkedSimDialog').style.display='block';
		document.getElementById('role').value=option;
	}
{/literal}
</script>