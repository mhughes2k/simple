<script language="javascript">
{literal}
	function cancelEvBrowser() {
		document.getElementById('dialogBlock').style.display='none';
		document.getElementById('BrowserEvDialog').style.display='none';
	}
	function showEvBrowser() {
		document.getElementById('dialogBlock').style.display='block';
		document.getElementById('BrowserEvDialog').style.display='block';
	}

{/literal}
</script>
<div id="dialogEvBlock" class="pop_up"></div>
<div class="pop_up" id="BrowserEvDialog" style="
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


<form action="index.php?option=office&cmd=editcalendaritem&method=addLinkedEvent2Event&id={$eventitem->id}" method="post">
<div class="pop_up_title">Add Linked Item</div>
<p>Please Choose a Task/Event to Link to this {if $eventitem->istask}Task{else}Event{/if}</p>

<div style="overflow:scroll;height:180px;">
{foreach from=$evlistitems item=ev}
<input type="radio" name="linkItemId" value="{$ev->id}" />{$ev->title}   
[{$ev->startdate}]<br/>
{/foreach}

</div>
<input type="hidden" name="linkItemType" value="event" />
<input type="submit" value="Add" />
<a href="javascript:cancelEvBrowser()">Cancel</a>

</form>
</div>
