
<div class="areaTitle">
	{$strings.MSG_PROJECT_INSTALL_TITLE}
</div>
<div>
	
</div>
{if ($user->sitewidePermissions.InstallTemplate==ALLOW) || $user->superadmin==ALLOW}
<p>{$projectError}</p>
{/if}
