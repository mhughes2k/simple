<script language="javascript">
{literal}
	function cancelBrowser() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('BrowserDialog').style.display='none';
	}
	function showBrowser() {
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('BrowserDialog').style.display='block';
	}

{/literal}
</script>
<div id="dialogBlock" class="pop_up"></div>
<div class="pop_up" id="BrowserDialog" style="
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


<form action="index.php?option=office&cmd=editcalendaritem&method=addLinkedItem2Event&id={$eventitem->id}" method="post">
<div class="pop_up_title">Add Linked Item</div>
<p>Please Choose a Document to Link to this {if $eventitem->istask}Task{else}Event{/if}</p>
<div style="overflow:scroll;height:180px;">

{foreach from=$listitems item=doc}
<input type="radio" name="linkItemId" value="{$doc->id}" />{$doc->filename} ({$doc->id})<br/>
{/foreach}

</div>
<input type="hidden" name="linkItemType" value="doc" />
<input type="submit" value="Add" />
<a href="javascript:cancelBrowser()">Cancel</a>

</form>
</div>
