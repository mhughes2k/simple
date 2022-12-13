<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-GB" xml:lang="en-GB">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$AppName}-{$title}</title>
<link rel="stylesheet" type="text/css" href="themes/{$config.THEME}/default.css" />
{if $option eq "dashboard"}
	<link rel="stylesheet" type="text/css" href="dashboard/themes/default/dashboardui.css" />
{/if}
<link rel="stylesheet" type="text/css" href="dashboard/themes/default/jquery-ui-1.8.2.custom.css" />
<script type="text/javascript" src="themes/{$config.THEME}/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="dashboard/lib/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="dashboard/lib/jquery-ui-1.8.2.custom.min.js"></script>
{if $option eq "dashboard"}
	<script type="text/javascript" src="dashboard/jquery.dashboard.js"></script>
	<script type="text/javascript" src="dashboard/lib/themeroller.js"></script>
{/if}
	
<script>
{literal}
  $(document).ready(function () {
		$('#tabs').tabs();
{/literal}
{if ($groupstab)}
{literal}
		$('#tabs').tabs( "option", "selected", 3 );			
{/literal}
{/if}
{if $option eq "projectadmin"}
{literal}
	$( "#contentAccordion" ).accordion({
		autoHeight: false
	});
{/literal}
{/if}
{if $option eq "siteadmin"}
{literal}
	$( "#contentAccordion" ).accordion();
	$ ("#newsAccordion" ).accordion({
		autoHeight: false
	});
{/literal}
{/if}
{if $option eq "dashboard"}
{literal}
		// load the templates
        $('body').append('<div id="templates"></div>');
        $("#templates").hide();
        $("#templates").load("dashboard/templates.html", initDashboard);

        // call for the themeswitcher
        $('#switcher').themeswitcher();
		
        function initDashboard() {

          // to make it possible to add widgets more than once, we create clientside unique id's
          // this is for demo purposes: normally this would be an id generated serverside
          //var startId = 100;
		  {/literal}
		  var startId = {$randomnum};
		  {literal}
          var dashboard = $('#dashboard').dashboard({
            // layout class is used to make it possible to switch layouts
            layoutClass:'layout',
            // feed for the widgets which are on the dashboard when opened
            json_data : {
              url: "dashboard/jsonfeed/mywidgets.php"
            },
			
			// URL which is called (via a POST request) when there is a change in the dashboard (eg widget is moved)
            //stateChangeUrl : "/web/wcbservlet/com.gxsoftware.solutions.dashboardsettings.servlet",			
			stateChangeUrl : "dashboard/dashboardchanged.php",
			
            // json feed; the widgets whcih you can add to your dashboard
            addWidgetSettings: {
              widgetDirectoryUrl:"dashboard/jsonfeed/widgetcategories.json"
            },

            // Definition of the layout
            // When using the layoutClass, it is possible to change layout using only another class. In this case
            // you don't need the html property in the layout

            layouts :
              [
                { title: "Layout1",
                  id: "layout1",
                  image: "dashboard/layouts/layout1.png",
                  html: '<div class="layout layout-a"><div class="column first column-first"></div></div>',
                  classname: 'layout-a'
                },
                { title: "Layout2",
                  id: "layout2",
                  image: "dashboard/layouts/layout2.png",
                  html: '<div class="layout layout-aa"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                  classname: 'layout-aa'
                },
                { title: "Layout3",
                  id: "layout3",
                  image: "dashboard/layouts/layout3.png",
                  html: '<div class="layout layout-ba"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                  classname: 'layout-ba'
                },
                { title: "Layout4",
                  id: "layout4",
                  image: "dashboard/layouts/layout4.png",
                  html: '<div class="layout layout-ab"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                  classname: 'layout-ab'
                },
                { title: "Layout5",
                  id: "layout5",
                  image: "dashboard/layouts/layout5.png",
                  html: '<div class="layout layout-aaa"><div class="column first column-first"></div><div class="column second column-second"></div><div class="column third column-third"></div></div>',
                  classname: 'layout-aaa'
                }
              ]

          }); // end dashboard call

          // binding for a widgets is added to the dashboard
          dashboard.element.live('dashboardAddWidget',function(e, obj){
            var widget = obj.widget;

            dashboard.addWidget({
              "id":startId++,
              "title":widget.title,
              "url":widget.url,
              "metadata":widget.metadata
              }, dashboard.element.find('.column:first'));
          });
		
          // the init builds the dashboard. This makes it possible to first unbind events before the dashboard is built.
          dashboard.init();
        } // end of dashboard stuff
		{/literal}
		{/if}

  {literal}
	$('#dashboard').live('dashboardSuccessfulSaved', function() {
		//alert("call succeeded");
	});	


    $(".sectionTitle").click(function () {
      $(this).siblings().slideToggle("normal", function () {
      }
     )
     }
    );    
    $(".sidebarTitle").click(function () {
      $(this).parent().children(".sidebarContent").slideToggle("normal", function () {
      }
      ); //end slide
      
      //alert($(this).height());
      }
      ); //end click 
      
   $("div.sidebarTitle").parent().parent().children(".sidebarContent").parent().each( function () {
      //alert("reading from "+$(this).attr("id"));
      return;
      var elid = $(this).attr("id");
      
      if (elid != '') {
        var state = readCookie(elid);
        //alert(state);
        if (state !=null) {
          $(this).children(".sidebarContent").parent().height(state);
        }
        else {
          //alert('state is snull');
        }
      }
    }  
   )
  {/literal}
  {$jQueryCode}
  {literal}
    }); //end doc ready

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
function setElementStateCookie(element,state) {
    createCookie(element.attr("id"),element.height(),10);
}
{/literal}
 
 {$scripts}
</script>
<style>
{$overrideStyles}
</style>

{$sajaxjs}
{literal}
<script language="javascript" type="text/javascript">
function toggleElement(elementId) {

		if (document.getElementById) {
			var el = document.getElementById(elementId);
			if (el.style.display != 'none') {
				el.style.display = 'none';
			}
			else {
				el.style.display = '';	
			}
		}
		else{
			if (document.layers) {
				if (document[elementId].display == 'none') {
					document[elementId].display = 'none';
				}
				else 
				{
					document[elementId].display = '';
				}
			}
			else {
				if(document.all[elementId].style.display !='none') {
					document.all[elementId].style.display='none';
				}
				else {
					document.all[elementId].style.display = '';
				}
			}
		}
}
</script>
{/literal}
</head>

<body>
{if $offlineAdminMode}
<div style='background-color:red;text-align:center'>{$strings.MSG_SITE_OFFLINE_ADMIN_MODE}</div>
{/if}
<div id="NavigationLinks" name="Navigation Links">
{$wcagNavigation}
</div>
	<div id="container">	
		<div id="mainContent">			
			<div id="systemToolbars">
			<div id="logo">
			<a href="{$home}" id="homelink" title="Go to Dashboard"><img src="{$home}themes/{$config.THEME}/images/logo.png" border="0" alt="Site Logo"></a>
			</div>
			<div id="systemToolbar">
			<div id="navigation">
			{if $authenticated}
			<a href="{$home}" class="small blue dashButton" id="btnDashboard" title="Dashboard">{$strings.MSG_HOME}</a>  
			{/if}
            
			{if $authenticated and count($projects)>0}		
			&nbsp;&nbsp;
			<form id="select_simulation_form" action="{$home}index.php" name="jumpform"  method="get">														
					<input type="hidden" name="option" value="tl" />
					<input type="hidden" name="cmd" value="select" />
					<input type="hidden" name="redirect" value="index.php%3Foption%3Doffice" />
					<select name="projectid" style="width:auto" title="Simulations" onchange="this.form.submit();">
						<option style="width:100%" value="-1">{$strings.MSG_JUMP_BUTTON}</option>
						{foreach from=$projects key=key item=project}
							<option style="width:100%" value="{$key}" title="{$project}">
								{$project}
							</option>
						{/foreach}
					</select>
			</form> 
			{/if}
			</div> <!-- end navigation -->
			<div id="login_out">		
			{if $authenticated}
				<a href="?option=siteadmin&cmd=viewprofile" title="{$strings.MSG_VIEW_YOUR_PROFILE}"> 
				<img src="ImageHandler.php?context=avatar&userId={$user->id}&type={$user->imagetype}" align="absmiddle" height="20">
				{$user->displayName}</a> | <a href="{$home}index.php?option=logout" title="Logout">{$strings.MSG_LOGOUT}</a>
			{/if}
			&nbsp;<a href="{$siteSettings.help_url}" target="_blank" title="help"><img src="themes/{$config.THEME}/images/help.png" alt="help" align="absmiddle" /></a>
			</div> <!-- end login_out -->
            </div> <!-- end system_toolbar -->						
			</div> <!-- end system_toolbars -->
			<div id="adminToolbar">
				{if $editblueprintlink==ALLOW}
				<a href="index.php?option=projectTemplateAdmin" title="{$strings.MSG_MANAGE_PT_TOOLTIP}">{$strings.MSG_MANAGE_PT}</a> &nbsp; {/if}
				{if $editsimulationlink==ALLOW}
				<a href="{$home}index.php?option=projectAdmin" title="{$strings.MSG_MANAGE_P_TOOLTIP}">{$strings.MSG_MANAGE_P}</a> &nbsp; {/if}
				{if ($user->sitewidePermissions.EditUser==ALLOW) ||
					 	($user->sitewidePermissions.AddUser==ALLOW) || 
					 	($user->sitewidePermissions.MakeLevelZeroUser==ALLOW) ||
					 	($user->superadmin==ALLOW)}				
						<a href="{$home}index.php?option=siteAdmin&cmd=listUsers" title="{$strings.MSG_MANAGE_USERS_TOOLTIP}">{$strings.MSG_MANAGE_USERS}</a> 
				{/if}
				{if ($user->superadmin==ALLOW)}
				    &nbsp; <a href="{$home}index.php?option=siteAdmin" title="{$strings.MSG_MANAGE_SITE}"}>{$strings.MSG_MANAGE_SITE}</a>
				{/if}
			</div><!-- end adminToolbar -->
			</div><!--end toolbars //-->
				
		<!-- This is the main content area! //-->
		{$config.announce}
			<div id="mainContentArea">
				{$content}
			</div>
		</div>
	</div>  <!-- end container //-->
	<div id="debug">
		{$trace}
	</div>
	
</body>
</html>
