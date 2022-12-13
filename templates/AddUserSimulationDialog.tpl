<script language="javascript">
{literal}
	function cancelAddSimDialog(type) {
		document.getElementById('dialogBlock').style.display='none';
		if (type=='user') {
			document.getElementById('addUserSimDialog').style.display='none';
		} else {
			document.getElementById('addGroupSimDialog').style.display='none';
		}
	}
	function ShowAddUserSimulationDialog(type) {
		if (type=='user') {
			document.getElementById('dialogBlock').style.display='block';
			var dialog =document.getElementById('addUserSimDialog');
    		var vOffset = document.getElementById('SimulationUsers').offsetTop;
		    //alert(vOffset);		
			document.getElementById('addUserSimDialog').style.display='block';
			dialog.style.top = vOffset+"px";
		} else {
			document.getElementById('dialogBlock').style.display='block';
			var dialog =document.getElementById('addGroupSimDialog');
			var vOffset = document.getElementById('SimulationUsers').offsetTop;
			document.getElementById('addGroupSimDialog').style.display='block';	
			dialog.style.top = vOffset+"px";	
		}
		
	}

{/literal}
</script>
<div id="SimDialogBlock" class="pop_up"></div>
<div class="pop_up" id="addUserSimDialog">
<!-- 
style="
position:absolute;
	top:200px;
	left:90px;
	width:400px;
	height:260px;	
	border:solid 1px black;
	display:none;
	background-color:white;
	z-index:1000;
	padding:5px;"-->
<div class="popup_title">Add User To Simulation</div>
<form action="index.php?option=projectAdmin&cmd=viewProject&method=addUser2Simulation&projectId={$project->id}#users" method="post">

<p>Please select a User to add to this Simulation</p>
<div class="addUserDialog">

{foreach from=$users item=user}
<input type="checkbox" name="userIds[]" value="{$user->id}" />{$user->id} {$user->displayName}<br/>
{/foreach}

</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddSimDialog('user')">Cancel</a>

</form>
</div>

<div class="pop_up" id="addGroupSimDialog">
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

<div class="pop_up_title">Add Group To Simulation</div>
<form action="index.php?option=projectAdmin&cmd=viewProject&method=addGroup2Simulation&projectId={$project->id}#users" method="post">
<p>Please select a Group to add to this Simulation</p>
<div class="addUserDialog">

{foreach from=$groups item=group}
<input type="checkbox" name="groupIds[]" value="{$group->id}" />{$group->id} {$group->name}<br/>
{/foreach}

</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddSimDialog('group')">Cancel</a>

</form>
</div>
