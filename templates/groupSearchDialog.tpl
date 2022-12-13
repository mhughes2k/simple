	<form action="index.php" method="get">
	<input type="hidden" name="option" value="siteAdmin" />
	<input type="hidden" name="cmd" value="searchGroups" />
	{$strings.MSG_SEARCH_MESSAGE_1} <input type="text" name="gsearchTerm" value="{$gsearchTerm}"/> 
	{$strings.MSG_SEARCH_MESSAGE_2} <select name="gsearchBy">
		<option value="0" {if $searchBy==0} selected{/if}>Name</option>
	</select>
	
	<input type="submit" value="Search"/> <a href="index.php?option=siteAdmin&cmd=listUserGroups">Show All</a>
	</form>
	<p>{$strings.MSG_SEARCH_MESSAGE_3}</p>
