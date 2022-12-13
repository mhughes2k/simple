<!--
    Copyright 2007, 2008 University of Strathclyde
        
    This file is part of the SIMPLE Platform.

    The SIMPLE Platform is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    The SIMPLE Platform is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the SIMPLE Platform.  If not, see <http://www.gnu.org/licenses/>.
    
//-->	
<?php 
/**
 * Page asks for various pieces of database information.
 * @package Install
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SIMPLE Platform - Step 1</title>
</head>

<body>
	<div id="container">	
		<div id="mainContent" style="margin-left:210px;position:relative;">
			<div id="systemToolbars">
				<div id="systemToolbar">
				</div>
			</div><!--end toolbars //-->
				
		<!-- This is the main content area! //-->
			<div id="mainContentArea">
				<h1>Configure your Database</h1>
				<p>SIMPLE requires use of the MySQL database.</p>
				
				<form action="index.php" method="post">
				<input type="hidden" name="step" value="3"/>
				<p>
				Database Server Host: <input type="text" name="dbhost" value="<?php echo $_SESSION['setupinfo']['dbhost'];?>"/><br />
				Database Name: <input type="text" name="dbname" value="<?php echo $_SESSION['setupinfo']['dbname'];?>"/><br />
				Port: <input type="text" name="dbport" value="<?php echo $_SESSION['setupinfo']['dbport'];?>"/><br/>
				Username: <input type="text" name="dbuser" value="<?php echo $_SESSION['setupinfo']['dbuser'];?>"/><br />
				Password: <input type="password" name="dbpassword" value="<?php echo $_SESSION['setupinfo']['dbpassword'];?>"/><br />
				</p>
				<!--
				<p>Create Database user.<br /><br/>
				If the user above does not have permission to create databases on the 
				server please specify a username &amp; password for a user that does.</p>
				<p>
				Username: <input type="text" name="createdbuser" value="<?php echo $_SESSION['setupinfo']['createdbuser'];?>"/><br />
				Password: <input type="password" name="createdbpassword" value="<?php echo $_SESSION['setupinfo']['createdbpassword'];?>"/><br />
				</p>
				//-->
				<input type="submit" value="next"/>
				</form>
				<div class="install_navigation">
				
				</div>
			</div>
		</div>


		<!-- This is the right hand side bar! //-->
		<div style="width:200px;float:right;position:absolute;top:0px;z-index:0">
			<img src="../themes/default/images/logo.png" border="0">
		</div>
		
	</div>
</body>
</html>
