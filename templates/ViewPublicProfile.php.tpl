<div class="areaTitle">Profile for "{$profile->displayName}"</div>
<div class="manageSectionContent">
<div class="sectionBox_manage">

	<div class="sectionBox" style="width:95%">
		<img src="ImageHandler.php?context=avatar&userId={$profile->id}&type={$profile->imagetype}">
	</div>
	<div class="sectionBox" style="width:95%">
		<div class="sectionTitle">About Me</div>
		<div>{$profile->blurb}</div>
	</div>

	
<div id="directoryBackFunction"><br /><a href="{$config.home}index.php?option=office">Back</a></div>
</div>
</div>
</body>
</html>