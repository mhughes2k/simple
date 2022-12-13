<?php
/*
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
    
*/
/**
* @package TLE2
* @subpackage SecurityPlugins
*/
/**
* In order for this plugin to work, the php_ldap extension must be installed.
* @param string $username Username to authenticate
* @param string $password Password to use to authenticate
*/
	function coreLdap($username, $password, $authType) {

		if ($authType!='coreLdap') return false;

	if (0) {
		// configurable for organisation
	    $dn = "OU=People,OU=staff,DN=ad,DN=strath,DN=ac,DN=uk";
		$ldap_server = "ldaps://ldap.strath.ac.uk";
		$ldap_rdn = "uname"; // ldap rdn or dn
		$ldap_pass = "password"; // ldap password

		// search variables
	    $attributes = array("displayname", "tle_id"); // possible to create a tle_id field?
    	$filter = "(objectCategory=user)"; // also search by tle_id

	    $ad = ldap_connect($ldap_server)
    	      or die("Couldn't connect to {$ldap_server}");

	    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3); // may need this setting
    	$bd = ldap_bind($ad,$ldap_rdn,$ldap_pass)
        	  or die("Couldn't bind to {$ldap_server}");
	    $result = ldap_search($ad, $dn, $filter, $attributes);
    	$entries = ldap_get_entries($ad, $result);

		/*$tle_id = false;
	    for ($i=0; $i<$entries["count"]; $i++) // should only be one
    	{
	       $tle_id = $entries[$i]["tle_id"][0];
	    }
		*/


    	ldap_unbind($ad);
	}
		if ($username=='ldap') {
			trace("LDAP Authenticated");
			// this will return unique dn for user
			$externalId=rand(0,100000); // an imaginary id for the time being
			return $externalId;
		}
		trace("Not LDAP Authenticated");
		return false;
	}
	if (is_null($_PLUGINS)) {
		echo "PH is null";
	}
	$_PLUGINS->registerFunction('onAuthenticateUser','LdapAuthentication','coreLdap');

	/**
	* Would handle any thing that has to be done to clear up a LDAP authentication.
	*/
	function coreLdapLogout($userid) {
		return true;
	}
	$_PLUGINS->registerFunction('onUserLogout','LdapAuthentication','coreLdapLogout');
?>
