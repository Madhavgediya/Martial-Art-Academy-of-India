<?php

if (!defined('SES_ID')) {
    define('SES_ID', 'base');
}

/**
 * For get session variables
 * eg. $username = $_SESSION['username']; 
 * @access	public
 * @param	key	
 * @return	session variable
 */
if (!function_exists('session_get')) {

    function session_get($key) {
        $k = SES_ID . '.' . $key;

        if (isset($_SESSION[$k])) {
            return $_SESSION[$k];
        }

        return false;
    }

}

/**
 * For set session variables
 * eg. $_SESSION['username'] = $username; 
 * @access	public
 * @param	key and value
 * @return	session variable
 */
if (!function_exists('session_set')) {

    function session_set($key, $value) {
        $k = SES_ID . '.' . $key;
        $_SESSION[$k] = $value;

        return true;
    }

}

if (!function_exists('session_unset')) {

    function session_unset($key, $value) {
        $k = SES_ID . '.' . $key;
        unset($_SESSION[$k]) ;
        return true;
    }

}

/* End of file session_helper.php */