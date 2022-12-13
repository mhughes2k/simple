<div class="areaTitle">login</div>
<div id="loginform" class="sectionBox">
	<p>{$siteSettings.loginpagetext}</p>
	<p> <strong>{$message}</strong></p>
	<form name="loginform" action="index.php" method="post">
		<div>
			<div class="float-left">Username:</div>
			<div class="right"><input type="text" name="username" /></div>
		</div>
		<div>
			<div class="float-left">Password:</div>
			<div class="right"><input type="password" name="password" /></div>
		</div>
		<div>
	   {if count($authPlugins)>1}
  		<div class="float-left">Authentication Method:</div>
			<div class="right">			
        <select name="authType">
				{foreach from=$authPlugins key=key item=resource}
					<option value="{$resource.pname}" 
          {if $resource.pdesc==$config.defaultAuthMethod}SELECTED{/if}>
          {$resource.pdesc} {if $resource.pdesc==$config.defaultAuthMethod}*{else}{/if}
          </option>
				{/foreach}
				</select>
				</div>
		{else}
	   <input type="hidden" name="authType" value="{$config.defaultAuthMethod}" />
	   {/if}
		</div>
	<div>			
			<div class="float-left">
			<input type="submit" Value="Login" name="option" />
			</div>
			{if count($authPlugins)>1}
			<div class="right">
		  * Indicates the <strong>default</strong> authentication method.
			</div>
			{/if}
		</div>
	</form>
</div>
