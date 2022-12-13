<html>
<head>
<link rel="stylesheet" type="text/css" href="default2.css" />
{literal}
<script language="javascript" type="text/javascript" src="{$home}/include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "specific_textareas",
	editor_selector : "mceEditor",
	theme : "advanced",
	plugins : "table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu",
	theme_advanced_buttons1_add : "fontselect,fontsizeselect",
	theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
	theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
	theme_advanced_buttons3_add_before : "tablecontrols,separator",
	theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,separator,print",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	plugin_insertdate_dateFormat : "%Y-%m-%d",
	plugin_insertdate_timeFormat : "%H:%M:%S",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	external_link_list_url : "example_data/example_link_list.js",
	external_image_list_url : "example_data/example_image_list.js",
	flash_external_list_url : "example_data/example_flash_list.js"
});
</script>
<script language="javascript">
function clearTable(ft){
	var l = ft.rows.length;
	if (l>0){
		for(var i = l; i>0;i--) {
			ft.deleteRow(0);
		}
	}
}
function ShowDoc(docid) {
	window.open("index.php?option=office&cmd=viewdoc&documentid="+docid);
}
function GetDocuments(folderId) {
	document.getElementById('documentTable').innerHTML= "Loading...";
	agent.call('','getDocuments','GetDocuments_Callback',folderId);
}
function GetDocuments_Callback(obj) {
//	var documentTable = document.getElementById('documentTable');
//	clearTable(documentTable);
	if (obj.length<1){
		document.getElementById('documentTable').innerHTML= "No Items";
	}
	else {
		var strDiv = "";
		for(var i = 0; i<obj.length; i++){
			strDiv += "<div style='border:solid 1px blue;' id='item_" +obj[i].documentuid +"' onmousedown='makeDraggable(item_" +obj[i].documentuid +")' ondblclick='ShowDoc("+obj[i].documentuid+")'>";
			var iconHtml = "";
			if (obj[i].icon!=null) {
				iconHtml = "<img src=\""+obj[i].icon +"\" onmousedown='makeDraggale('item_" +obj[i].documentuid + ")'>";
			}
			strDiv+= iconHtml;
			strDiv+= obj[i].filename;
			strDiv+= "</div>";
		}
		document.getElementById('documentTable').innerHTML= strDiv;
	}
}
function GetFolders(projectId) {
//	alert(projectId);
	var projectId = document.getElementById('selectedProject').value;
	
	var ft = document.getElementById('folderTable');
	clearTable(ft);
	var cell = ft.insertRow(0).insertCell(0);
	cell.innerHTML = "Loading...";	
	agent.call('','getFolders','getFolders_callback',projectId);
}
function getFolders_callback(obj) {
	var str ="";
	var ft = document.getElementById('folderTable');
	clearTable(ft);
	if (obj.length<=1){
		var cell = ft.insertRow(0).insertCell(0);
		cell.innerHTML = "No Items";		
//		ft.insertRow(0).insertCell(0).innerHTML = "No Items.";
		document.getElementById('documentTable').innerHTML= "No Items";
	}
	else {
		GetDocuments(obj[0].folderId);
		for(var i = 0; i<obj.length; i++){
			var row = ft.insertRow(ft.rows.length);
			row.insertCell(0);
			row.cells[0].innerHTML= "<A href=\"javascript:GetDocuments("+obj[i].folderId+")\">"+ obj[i].name +"</a>";
		}
	}
	
}
</script>
{/literal}
</head>
<body onload="GetFolders();">
<div>
	<div style="font-size:18pt;font-weight:bold;float:left;">TLE 2.0</div>
	<div style="left:400px;position:relative">
		{if $isStaff}<a href="index.php?option=projectAdmin">Project Administation</a> | {/if}
		<a href="index.php?option=map">Map</a> | <a href="index.php?option=directory">Directory</a>
		<input type="hidden" name="option" value="tl" />
		<input type="hidden" name="cmd" value="select" />
		<input type="hidden" name="redirect" value="office" />
		<select name="projectid" id="selectedProject" onchange="GetFolders()";>
			{foreach from=$projects key=key item=project}
			<option value="{$key}" {if $key==$currentProject}selected{/if}>{$project}</option>
			{/foreach}
		</select>
		<input type="submit" value="Go..." name="jump" />
		{if $authenticated}<a href="index.php?option=logout">Logout</a>{else}<a href="index.php?option=showlogin">Login</a>{/if}
	</div>
</div>
<br clear="all"/>
<div id="browser">
	<div id="folders">
		Folders:
		<table id="folderTable" border="1">
		</table>
	</div>
	<div id="documents">
	<div>
		Documents:
			<div id="documentTable">
			</div>
		</div>
	</div>
</div>
<div id="debug">
</div>
</body>
</html>