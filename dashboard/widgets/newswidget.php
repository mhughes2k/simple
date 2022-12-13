<script type="text/javascript">
	function NewsWidgetReload()
		{
			var
				$http,
				$self = arguments.callee;
			if (window.XMLHttpRequest) {
				$http = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				try {
					$http = new ActiveXObject('Msxml2.XMLHTTP');
				} catch(e) {
					$http = new ActiveXObject('Microsoft.XMLHTTP');
				}
			}
			if ($http) {
				$http.onreadystatechange = function()
				{
					if (/4|^complete$/.test($http.readyState)) {
						document.getElementById('NewsReload').innerHTML = $http.responseText;
						setTimeout(function(){$self();}, 30000); // update every 30 seconds
					}
				};
				$http.open('GET', 'dashboard/widgets/newsdiv.php' + '?' + new Date().getTime(), true);
				$http.send(null);
			}
		}
</script>
<script type="text/javascript">
	setTimeout(function() {NewsWidgetReload();}, 500); // initial update after 0.5 second
</script>
<div id="NewsReload"><img src="dashboard/themes/default/loading.gif"/></div>

