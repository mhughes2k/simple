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
 * Represents a notification for a user.
 * 
 * Alerts are attached to items, and an item can only have one alert per user.
 * 
 * Users may only create alerts for themselves!
 * @package SIMPLE
 */
 
/**
 * Implements helper functions to do with browser capabilities.
 */

class BrowserCap {
    static function get_browser() {
        $browser = array();
        $noBrowserCap = false;
        $errlevel = error_reporting(0);
        try{
            $browser =get_browser(null,true);
            if (!is_array($browser)) {
                $noBrowserCap = true;
            }
        }
        catch (Exception $e) {
            $noBrowserCap = true;
        }
        if ($noBrowserCap) {

            return BrowserCap::_browser();
        }
        error_reporting($errlevel);
        //print_r($browser);
        return $browser;
    }
    
    static function w($a = '')
    {
        if (empty($a)) return array();
   
        return explode(' ', $a);
    }
    
    static function _browser($a_browser = false, $a_version = false, $name = false)
    {
    $browser_list = 'msie firefox konqueror safari netscape navigator opera mosaic lynx amaya omniweb chrome avant camino flock seamonkey aol mozilla gecko';
    $user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
    $this_version = $this_browser = '';
   
    $browser_limit = strlen($user_browser);
    foreach (BrowserCap::w($browser_list) as $row)
    {
        $row = ($a_browser !== false) ? $a_browser : $row;
        $n = stristr($user_browser, $row);
        if (!$n || !empty($this_browser)) continue;
       
        $this_browser = $row;
        $j = strpos($user_browser, $row) + strlen($row) + 1;
        for (; $j <= $browser_limit; $j++)
        {
            $s = trim(substr($user_browser, $j, 1));
            $this_version .= $s;
           
            if ($s === '') break;
        }
    }
   
    if ($a_browser !== false)
    {
        $ret = false;
        if (strtolower($a_browser) == $this_browser)
        {
            $ret = true;
           
            if ($a_version !== false && !empty($this_version))
            {
                $a_sign = explode(' ', $a_version);
                if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false)
                {
                    $ret = false;
                }
            }
        }
       
        return $ret;
    }
   
    //
    $this_platform = '';
    if (strpos($user_browser, 'linux'))
    {
        $this_platform = 'linux';
    }
    elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x'))
    {
        $this_platform = 'mac';
    }
    else if (strpos($user_browser, 'windows') || strpos($user_browser, 'win32'))
    {
        $this_platform = 'windows';
    }
   
    if ($name !== false)
    {
        return $this_browser . ' ' . $this_version;
    }
   
    return array(
        "browser"      => $this_browser,
        "version"      => $this_version,
        "platform"     => $this_platform,
        "useragent"    => $user_browser
    );
}   
}

