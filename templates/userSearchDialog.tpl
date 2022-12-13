<form action="index.php" method="get">
<input type="hidden" name="option" value="siteAdmin" />
<input type="hidden" name="cmd" value="searchUsers" />
	{$strings.MSG_SEARCH_MESSAGE_1} 
	<input type="text" name="searchTerm" value="{$searchTerm}"/> 
	{$strings.MSG_SEARCH_MESSAGE_2}
	 <select name="searchBy">
		<option value="0" {if $searchBy==0} selected{/if}>UserName</option>
		<option value="1" {if $searchBy==1} selected{/if}>Display Name</option>
		<option value="2" {if $searchBy==2} selected{/if}>User ID</option>
		<option value="3" {if $searchBy==3} selected{/if}>Email</option>
		<option value="4" {if $searchBy==4} selected{/if}>Registration Number</option>
	</select>
	<input type="submit" value="Search"/>
	<a href="index.php?option=siteAdmin&cmd=listUsers">Show All</a>
</form>
<p>{$strings.MSG_SEARCH_MESSAGE_3}</p>
