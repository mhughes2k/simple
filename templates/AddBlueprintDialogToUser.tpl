<script language="javascript">
{literal}
	function cancelAddBPDialog() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('addBPDialog').style.display='none';
	}
	function ShowAddBPDialog(option,command,otherargs) {
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('addBPDialog').style.display='block';
		var dialog =document.getElementById('addBPDialog');
    var vOffset = document.getElementById('usersBlueprintPermissions').offsetTop;
    //alert(vOffset);		
		document.getElementById('addBPDialog').style.display='block';
		dialog.style.top = vOffset+"px";
	}

{/literal}
</script>
<div id="addBPDialogBlock" class="pop_up"></div>
<div class="pop_up" id="addBPDialog" >
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

<div class="popup_title">Add Blueprint To User/group</div>
<form action="index.php?option=siteAdmin&cmd=viewUser&method=addBlueprint2User&userId={$user->id}#blueprintPermissions" method="post">
<p>Please select a Blueprint to add to this User/Group</p>
<div style="overflow:scroll;height:180px;">
{foreach from=$addblueprintdialogtemplates item=projecttemplate}
<input type="checkbox" name="blueprintIds[]" value="{$projecttemplate->id}" />{$projecttemplate->id}{$projecttemplate->Name}<br/>
{/foreach}
</div>
<input type="submit" value="Add" />
<a href="javascript:cancelAddBPDialog()">Cancel</a>

</form>
</div>
