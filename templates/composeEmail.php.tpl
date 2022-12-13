{literal}
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js" ></script >
<script type="text/javascript" >
tinyMCE.init({
        mode : "textareas",
        theme : "simple" 
});
</script >
{/literal}

<div class="areaTitle">compose email</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">
<form action="index.php?option=office&cmd=saveEmail" method="post" >
 <input type="hidden" name="folderid" value="{$folderid}"/> 

	<div class="sectionTitle">Compose Email</div>
	<table>
		<tr>
			<td>
				Subject:
			</td>
			<td>
				<input type="text" name="email_subject" id="email_subject" />
			</td>
		</tr>
		<tr>
			<td>Content</td>
			<td>
				<textarea name="email_content" id="email_content"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="Save Draft"/>
				<a href="index.php?option=office&cmd=view&folder={$folderid}"><img src="{$config.system_icon_close}" />Close</a>
			</td>
		</tr>
		</table>
</form>
</div>
</div>