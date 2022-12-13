<div id="header">
<strong>Users</strong>
</div>
<div>
 <form name="userform" action="index.php?option=siteAdmin&cmd=updateUserLogin" method="post">
    <ul>
		<li>Native Username: <input type="text" name="username" value="{$user->userName}" /></li>
		<li>Native Password: <input type="text" name="password" value="{$user->password}" /></li>
		<li>Authentication Method: {$user->authType}</li>
		<li><input type="hidden" name="userId" value="{$user->id}" />
		<input type="submit" Value="update" name="submit" /></li>
	</ul>
 </form>
</div>