<div class="areaTitle">Add User</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
 <form name="userform" action="index.php?option=siteAdmin&cmd=addUser" method="post">
 	{if $message!=''}
	 	<p>{$message}</p>
 	{/if}
 	
	<div>
		<div class="float-left">Username*:</div>
		<div class="right">
			<input type="text" name="username" id="username"
			{if ($username)} value="{$username}" {/if}
			{if ($missingFields.username==1) || ($usernameExists)} class="missingField"{/if}>
			{if $usernameExists}<span class="highlightText">Username is already taken.</span>{/if}			
			</div>
	</div>
	
		<div>
		<div class="float-left">Authentication Type:</div>
		<div class="right">
			<select name="authtype">
				{foreach from=$authtypes key=key item=resource}
					<option  value="{$resource.pname}" {if $resource.pname=='TleAuthenticate'}SELECTED{/if}>{$resource.pdesc}</option>
				{/foreach}
			</select>
		</div>
	</div>
	
	<div>
		<div class="float-left">Password*:</div>
		<div class="right">
			<input type="password" name="pwd1" id="pwd1"
			{if ($pwd1)} value="{$pwd1}" {/if}
			{if ($missingFields.pwd1==1) || ($passwordsDiffer)} class="missingField"{/if}>
			</div>
	</div>
	
	<div>
		<div class="float-left">Retype Password*:</div>
		<div class="right">
			<input type="password" name="pwd2" id="pwd2"
			{if ($pwd2)} value="{$pwd2}" {/if}
			{if ($missingFields.pwd2==1) || ($passwordsDiffer)} class="missingField"{/if}>
			{if $passwordsDiffer}<span class="highlightText">Passwords are different.</p>{/if}			
			</div>
	</div>		 	
 	
	<div>
		<div class="float-left">Display Name*:</div>
		<div class="right">
			<input type="text" name="displayName" id="displayName"
			{if ($displayName)} value="{$displayName}" {/if}			
			{if ($missingFields.displayName==1)} class="missingField"{/if}></div>
	</div>
	<div>
		<div class="float-left">E-mail*:</div>
		<div class="right">
			<input type="text" name="email" id="email" 
			{if ($email)} value="{$email}" {else} value="None given" {/if}			
			{if ($missingFields.email==1)} class="missingField"{/if}></div>
	</div>
	<div>
		<div class="float-left">Registration Number:</div>
		<div class="right">
			<input type="text" name="regnumber" id="regnumber" 
			{if ($regNumber)} value="{$regNumber}" {/if}
			{if ($missingFields.regNumber==1)} class="missingField"{/if}></div>
	</div>	
	<div>
		<div class="float-left">Active:</div>
		<div class="right">
			<select name="active">
				<option value="1" {if !isset($active) || $active==1} selected="selected" {/if}>yes</option>
				<option value="0" {if isset($active) && $active==0} selected="selected" {/if}>no</option>
			</select>		
		</div>
	</div>
	<div>
		<div class="float-left">Superadmin:</div>
		<div class="right">
			<select name="superadmin">
				<option value="0" {if ($superadmin==0)} selected="selected" {/if}>no</option>
				<option value="1" {if ($superadmin==1)} selected="selected" {/if}>yes</option>				
			</select>		
		</div>
	</div>	
	
	<div>	
	<p {if ($missingFields)} class="highlightText"{/if}>Items marked * are required.</p>
	<input type="hidden" value="{$userGroupId}" name="userGroupId" />
	<input type="hidden" value="add" name="method" />
	<input type="submit" value="Create User"></div>
	</form>
</div>
</div>