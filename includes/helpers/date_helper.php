<?php

/**
 * Get "now" time
 *
 * Returns time() or its GMT equivalent based on the config file preference
 *
 * @access	public
 * @return	integer
 */
if (!function_exists('now')) {

    function now() {
        $CI = & get_instance();

        if (strtolower($CI->config->item('time_reference')) == 'gmt') {
            $now = time();
            $system_time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));

            if (strlen($system_time) < 10) {
                $system_time = time();
                log_message('error', 'The Date class could not set a proper GMT timestamp so the local time() value was used.');
            }

            return $system_time;
        } else {
            return time();
        }
    }

}

// ------------------------------------------------------------------------

/**
 * Convert MySQL Style Datecodes
 *
 * This function is identical to PHPs date() function,
 * except that it allows date codes to be formatted using
 * the MySQL style, where each code letter is preceded
 * with a percent sign:  %Y %m %d etc...
 *
 * The benefit of doing dates this way is that you don't
 * have to worry about escaping your text letters that
 * match the date codes.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @return	integer
 */
if (!function_exists('mdate')) {

    function mdate($datestr = '', $time = '') {
        if ($datestr == '')
            return '';

        if ($time == '')
            $time = now();

        $datestr = str_replace('%\\', '', preg_replace("/([a-z]+?){1}/i", "\\\\\\1", $datestr));
        return date($datestr, $time);
    }

}

// ------------------------------------------------------------------------

/**
 * Standard Date
 *
 * Returns a date formatted according to the submitted standard.
 *
 * @access	public
 * @param	string	the chosen format
 * @param	integer	Unix timestamp
 * @return	string
 */
if (!function_exists('standard_date')) {

    function standard_date($fmt = 'DATE_RFC822', $time = '') {
        $formats = array(
            'DATE_ATOM' => '%Y-%m-%dT%H:%i:%s%Q',
            'DATE_COOKIE' => '%l, %d-%M-%y %H:%i:%s UTC',
            'DATE_ISO8601' => '%Y-%m-%dT%H:%i:%s%Q',
            'DATE_RFC822' => '%D, %d %M %y %H:%i:%s %O',
            'DATE_RFC850' => '%l, %d-%M-%y %H:%i:%s UTC',
            'DATE_RFC1036' => '%D, %d %M %y %H:%i:%s %O',
            'DATE_RFC1123' => '%D, %d %M %Y %H:%i:%s %O',
            'DATE_RSS' => '%D, %d %M %Y %H:%i:%s %O',
            'DATE_W3C' => '%Y-%m-%dT%H:%i:%s%Q'
        );

        if (!isset($formats[$fmt])) {
            return FALSE;
        }

        return mdate($formats[$fmt], $time);
    }

}

// ------------------------------------------------------------------------

/**
 * Timespan
 *
 * Returns a span of seconds in this format:
 * 	10 days 14 hours 36 minutes 47 seconds
 *
 * @access	public
 * @param	integer	a number of seconds
 * @param	integer	Unix timestamp
 * @return	integer
 */
if (!function_exists('timespan')) {

    function timespan($seconds = 1, $time = '') {
        $CI = & get_instance();
        $CI->lang->load('date');

        if (!is_numeric($seconds)) {
            $seconds = 1;
        }

        if (!is_numeric($time)) {
            $time = time();
        }

        if ($time <= $seconds) {
            $seconds = 1;
        } else {
            $seconds = $time - $seconds;
        }

        $str = '';
        $years = floor($seconds / 31536000);

        if ($years > 0) {
            $str .= $years . ' ' . $CI->lang->line((($years > 1) ? 'date_years' : 'date_year')) . ', ';
        }

        $seconds -= $years * 31536000;
        $months = floor($seconds / 2628000);

        if ($years > 0 OR $months > 0) {
            if ($months > 0) {
                $str .= $months . ' ' . $CI->lang->line((($months > 1) ? 'date_months' : 'date_month')) . ', ';
            }

            $seconds -= $months * 2628000;
        }

        $weeks = floor($seconds / 604800);

        if ($years > 0 OR $months > 0 OR $weeks > 0) {
            if ($weeks > 0) {
                $str .= $weeks . ' ' . $CI->lang->line((($weeks > 1) ? 'date_weeks' : 'date_week')) . ', ';
            }

            $seconds -= $weeks * 604800;
        }

        $days = floor($seconds / 86400);

        if ($months > 0 OR $weeks > 0 OR $days > 0) {
            if ($days > 0) {
                $str .= $days . ' ' . $CI->lang->line((($days > 1) ? 'date_days' : 'date_day')) . ', ';
            }

            $seconds -= $days * 86400;
        }

        $hours = floor($seconds / 3600);

        if ($days > 0 OR $hours > 0) {
            if ($hours > 0) {
                $str .= $hours . ' ' . $CI->lang->line((($hours > 1) ? 'date_hours' : 'date_hour')) . ', ';
            }

            $seconds -= $hours * 3600;
        }

        $minutes = floor($seconds / 60);

        if ($days > 0 OR $hours > 0 OR $minutes > 0) {
            if ($minutes > 0) {
                $str .= $minutes . ' ' . $CI->lang->line((($minutes > 1) ? 'date_minutes' : 'date_minute')) . ', ';
            }

            $seconds -= $minutes * 60;
        }

        if ($str == '') {
            $str .= $seconds . ' ' . $CI->lang->line((($seconds > 1) ? 'date_seconds' : 'date_second')) . ', ';
        }

        return substr(trim($str), 0, -1);
    }

}

// ------------------------------------------------------------------------

/**
 * Number of days in a month
 *
 * Takes a month/year as input and returns the number of days
 * for the given month/year. Takes leap years into consideration.
 *
 * @access	public
 * @param	integer a numeric month
 * @param	integer	a numeric year
 * @return	integer
 */
if (!function_exists('days_in_month')) {

    function days_in_month($month = 0, $year = '') {
        if ($month < 1 OR $month > 12) {
            return 0;
        }

        if (!is_numeric($year) OR strlen($year) != 4) {
            $year = date('Y');
        }

        if ($month == 2) {
            if ($year % 400 == 0 OR ( $year % 4 == 0 AND $year % 100 != 0)) {
                return 29;
            }
        }

        $days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return $days_in_month[$month - 1];
    }

}

// ------------------------------------------------------------------------

/**
 * Converts a local Unix timestamp to GMT
 *
 * @access	public
 * @param	integer Unix timestamp
 * @return	integer
 */
if (!function_exists('local_to_gmt')) {

    function local_to_gmt($time = '') {
        if ($time == '')
            $time = time();

        return mktime(gmdate("H", $time), gmdate("i", $time), gmdate("s", $time), gmdate("m", $time), gmdate("d", $time), gmdate("Y", $time));
    }

}

// ------------------------------------------------------------------------

/**
 * Converts GMT time to a localized value
 *
 * Takes a Unix timestamp (in GMT) as input, and returns
 * at the local value based on the timezone and DST setting
 * submitted
 *
 * @access	public
 * @param	integer Unix timestamp
 * @param	string	timezone
 * @param	bool	whether DST is active
 * @return	integer
 */
if (!function_exists('gmt_to_local')) {

    function gmt_to_local($time = '', $timezone = 'UTC', $dst = FALSE) {
        if ($time == '') {
            return now();
        }

        $time += timezones($timezone) * 3600;

        if ($dst == TRUE) {
            $time += 3600;
        }

        return $time;
    }

}

// ------------------------------------------------------------------------

/**
 * Converts a MySQL Timestamp to Unix
 *
 * @access	public
 * @param	integer Unix timestamp
 * @return	integer
 */
if (!function_exists('mysql_to_unix')) {

    function mysql_to_unix($time = '') {
        // We'll remove certain characters for backward compatibility
        // since the formatting changed with MySQL 4.1
        // YYYY-MM-DD HH:MM:SS

        $time = str_replace('-', '', $time);
        $time = str_replace(':', '', $time);
        $time = str_replace(' ', '', $time);

        // YYYYMMDDHHMMSS
        return mktime(
                substr($time, 8, 2), substr($time, 10, 2), substr($time, 12, 2), substr($time, 4, 2), substr($time, 6, 2), substr($time, 0, 4)
        );
    }

}

// ------------------------------------------------------------------------

/**
 * Unix to "Human"
 *
 * Formats Unix timestamp to the following prototype: 2006-08-21 11:35 PM
 *
 * @access	public
 * @param	integer Unix timestamp
 * @param	bool	whether to show seconds
 * @param	string	format: us or euro
 * @return	string
 */
if (!function_exists('unix_to_human')) {

    function unix_to_human($time = '', $seconds = FALSE, $fmt = 'us') {
        $r = date('Y', $time) . '-' . date('m', $time) . '-' . date('d', $time) . ' ';

        if ($fmt == 'us') {
            $r .= date('h', $time) . ':' . date('i', $time);
        } else {
            $r .= date('H', $time) . ':' . date('i', $time);
        }

        if ($seconds) {
            $r .= ':' . date('s', $time);
        }

        if ($fmt == 'us') {
            $r .= ' ' . date('A', $time);
        }

        return $r;
    }

}

// ------------------------------------------------------------------------

/**
 * Convert "human" date to GMT
 *
 * Reverses the above process
 *
 * @access	public
 * @param	string	format: us or euro
 * @return	integer
 */
if (!function_exists('human_to_unix')) {

    function human_to_unix($datestr = '') {
        if ($datestr == '') {
            return FALSE;
        }

        $datestr = trim($datestr);
        $datestr = preg_replace("/\040+/", ' ', $datestr);

        if (!preg_match('/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr)) {
            return FALSE;
        }

        $split = explode(' ', $datestr);

        $ex = explode("-", $split['0']);

        $year = (strlen($ex['0']) == 2) ? '20' . $ex['0'] : $ex['0'];
        $month = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];
        $day = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];

        $ex = explode(":", $split['1']);

        $hour = (strlen($ex['0']) == 1) ? '0' . $ex['0'] : $ex['0'];
        $min = (strlen($ex['1']) == 1) ? '0' . $ex['1'] : $ex['1'];

        if (isset($ex['2']) && preg_match('/[0-9]{1,2}/', $ex['2'])) {
            $sec = (strlen($ex['2']) == 1) ? '0' . $ex['2'] : $ex['2'];
        } else {
            // Unless specified, seconds get set to zero.
            $sec = '00';
        }

        if (isset($split['2'])) {
            $ampm = strtolower($split['2']);

            if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
                $hour = $hour + 12;

            if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
                $hour = '00';

            if (strlen($hour) == 1)
                $hour = '0' . $hour;
        }

        return mktime($hour, $min, $sec, $month, $day, $year);
    }

}

// ------------------------------------------------------------------------

/**
 * Timezone Menu
 *
 * Generates a drop-down menu of timezones.
 *
 * @access	public
 * @param	string	timezone
 * @param	string	classname
 * @param	string	menu name
 * @return	string
 */
if (!function_exists('timezone_menu')) {

    function timezone_menu($default = 'UTC', $class = "", $name = 'timezones') {
        $CI = & get_instance();
        $CI->lang->load('date');

        if ($default == 'GMT')
            $default = 'UTC';

        $menu = '<select name="' . $name . '"';

        if ($class != '') {
            $menu .= ' class="' . $class . '"';
        }

        $menu .= ">\n";

        foreach (timezones() as $key => $val) {
            $selected = ($default == $key) ? " selected='selected'" : '';
            $menu .= "<option value='{$key}'{$selected}>" . $CI->lang->line($key) . "</option>\n";
        }

        $menu .= "</select>";

        return $menu;
    }

}

// ------------------------------------------------------------------------

/**
 * Timezones
 *
 * Returns an array of timezones.  This is a helper function
 * for various other ones in this library
 *
 * @access	public
 * @param	string	timezone
 * @return	string
 */
if (!function_exists('timezones')) {

    function timezones($tz = '') {
        // Note: Don't change the order of these even though
        // some items appear to be in the wrong order

        $zones = array(
            'UM12' => -12,
            'UM11' => -11,
            'UM10' => -10,
            'UM95' => -9.5,
            'UM9' => -9,
            'UM8' => -8,
            'UM7' => -7,
            'UM6' => -6,
            'UM5' => -5,
            'UM45' => -4.5,
            'UM4' => -4,
            'UM35' => -3.5,
            'UM3' => -3,
            'UM2' => -2,
            'UM1' => -1,
            'UTC' => 0,
            'UP1' => +1,
            'UP2' => +2,
            'UP3' => +3,
            'UP35' => +3.5,
            'UP4' => +4,
            'UP45' => +4.5,
            'UP5' => +5,
            'UP55' => +5.5,
            'UP575' => +5.75,
            'UP6' => +6,
            'UP65' => +6.5,
            'UP7' => +7,
            'UP8' => +8,
            'UP875' => +8.75,
            'UP9' => +9,
            'UP95' => +9.5,
            'UP10' => +10,
            'UP105' => +10.5,
            'UP11' => +11,
            'UP115' => +11.5,
            'UP12' => +12,
            'UP1275' => +12.75,
            'UP13' => +13,
            'UP14' => +14
        );

        if ($tz == '') {
            return $zones;
        }

        if ($tz == 'GMT')
            $tz = 'UTC';

        return (!isset($zones[$tz])) ? 0 : $zones[$tz];
    }

}

// ------------------------------------------------------------------------

/**
 * Returns a date formatted of databse.
 *
 * @access	public
 * @param	string the chosen format
 * @return	date
 */
if (!function_exists('disptoDB')) {

    function disptoDB($InDate) {
        $standard_date = date('Y-m-d', strtotime(str_replace("/", "-", $InDate)));
        return $standard_date;
    }

}

// ------------------------------------------------------------------------

/**
 * Takes a UNIX timestamp and returns a string representing how long ago that date was, like "moments ago", "2 weeks ago", etc.
 *
 * @param $timestamp int A UNIX timestamp
 *
 * @return string A human-readable amount of time 'ago'
 */
if (!function_exists('relative_time')) {

    function relative_time($timestamp) {
        if ($timestamp != "" && !is_int($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        if (!is_int($timestamp)) {
            return "never";
        }

        $difference = time() - $timestamp;

        $periods = array("moment", "min", "hour", "day", "week",
            "month", "year", "decade");

        $lengths = array("60", "60", "24", "7", "4.35", "12", "10", "10");

        if ($difference >= 0) {
            // this was in the past
            $ending = "ago";
        } else {
            // this was in the future
            $difference = -$difference;
            $ending = "to go";
        }

        for ($j = 0; $difference >= $lengths[$j]; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j].= "s";
        }

        if ($difference < 60 && $j == 0) {
            $text = "$periods[$j] $ending";
        } else {
            $text = "$difference $periods[$j] $ending";
        }

        return $text;
    }

//end relative_time()
}

//---------------------------------------------------------------

/**
 * Returns the difference between two dates.
 *
 * @param $start mixed The start date in either unix timestamp or a format that can be used within strtotime().
 * @param $end mixed The ending date in either unix timestamp or a format that can be used within strtotime().
 * @param $interval string A string with the interval to use. Choices 'week', 'day', 'hour', 'minute'.
 * @param $reformat boolean If TRUE, will reformat the time using strtotime().
 *
 * @return int A number representing the difference between the two dates in the interval desired.
 */
if (!function_exists('date_difference')) {

    function date_difference($start = null, $end = null, $interval = 'day', $reformat = false) {
        if (is_null($start)) {
            return false;
        }

        if (is_null($end)) {
            $end = date('Y-m-d H:i:s');
        }

        $times = array(
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60
        );

        if ($reformat === true) {
            $start = strtotime($start);
            $end = strtotime($end);
        }

        $diff = $end - $start;

        return round($diff / $times[$interval]);
    }

//end date_difference()
}

//---------------------------------------------------------------

/**
 * Converts unix time to a human readable time in user timezone
 * or in a given timezone.
 *
 * For supported timezones visit - http://php.net/manual/timezones.php
 * For accepted formats visit - http://php.net/manual/function.date.php
 *
 * @example echo user_time();
 * @example echo user_time($timestamp, 'EET', 'l jS \of F Y h:i:s A');
 *
 * @param int    $timestamp A UNIX timestamp. If non is given current time will be used.
 * @param string $timezone  The timezone we want to convert to. If none is given a current logged user timezone will be used.
 * @param string $format    The format of the outputted date/time string
 *
 * @return string A string formatted according to the given format using the given timestamp and given timezone or the current time if no timestamp is given.
 */
if (!function_exists('user_time')) {

    function user_time($timestamp = NULL, $timezone = NULL, $format = 'r') {
        if (!$timezone) {
            $CI = & get_instance();
            $CI->load->library('users/auth');
            if ($CI->auth->is_logged_in()) {
                $timezone = standard_timezone($CI->auth->user()->timezone);
            }
        }

        $timestamp = ($timestamp) ? $timestamp : time();

        $dtzone = new DateTimeZone($timezone);
        $dtime = new DateTime();

        return $dtime->setTimestamp($timestamp)->setTimeZone($dtzone)->format($format);
    }

//end user_time()
}

//---------------------------------------------------------------

/**
 * Convert CodeIgniter's time zone strings to standard PHP time zone strings
 *
 * @param String $ciTimezone A time zone string generated by CodeIgniter
 *
 * @return String    A PHP time zone string
 */
if (!function_exists('standard_timezone')) {

    function standard_timezone($ciTimezone) {
        switch ($ciTimezone) {
            case 'UM12':
                return 'Pacific/Kwajalein';
            case 'UM11':
                return 'Pacific/Midway';
            case 'UM10':
                return 'Pacific/Honolulu';
            case 'UM95':
                return 'Pacific/Marquesas';
            case 'UM9':
                return 'Pacific/Gambier';
            case 'UM8':
                return 'America/Los_Angeles';
            case 'UM7':
                return 'America/Boise';
            case 'UM6':
                return 'America/Chicago';
            case 'UM5':
                return 'America/New_York';
            case 'UM45':
                return 'America/Caracas';
            case 'UM4':
                return 'America/Sao_Paulo';
            case 'UM35':
                return 'America/St_Johns';
            case 'UM3':
                return 'America/Buenos_Aires';
            case 'UM2':
                return 'Atlantic/St_Helena';
            case 'UM1':
                return 'Atlantic/Azores';
            case 'UP1':
                return 'Europe/Berlin';
            case 'UP2':
                return 'Europe/Kaliningrad';
            case 'UP3':
                return 'Asia/Baghdad';
            case 'UP35':
                return 'Asia/Tehran';
            case 'UP4':
                return 'Asia/Baku';
            case 'UP45':
                return 'Asia/Kabul';
            case 'UP5':
                return 'Asia/Karachi';
            case 'UP55':
                return 'Asia/Calcutta';
            case 'UP575':
                return 'Asia/Kathmandu';
            case 'UP6':
                return 'Asia/Almaty';
            case 'UP65':
                return 'Asia/Rangoon';
            case 'UP7':
                return 'Asia/Bangkok';
            case 'UP8':
                return 'Asia/Hong_Kong';
            case 'UP875':
                return 'Australia/Eucla';
            case 'UP9':
                return 'Asia/Tokyo';
            case 'UP95':
                return 'Australia/Darwin';
            case 'UP10':
                return 'Australia/Melbourne';
            case 'UP105':
                return 'Australia/LHI';
            case 'UP11':
                return 'Asia/Magadan';
            case 'UP115':
                return 'Pacific/Norfolk';
            case 'UP12':
                return 'Pacific/Fiji';
            case 'UP1275':
                return 'Pacific/Chatham';
            case 'UP13':
                return 'Pacific/Samoa';
            case 'UP14':
                return 'Pacific/Kiritimati';
            case 'UTC':
            default:
                return 'UTC';
        }
    }

}

// ------------------------------------------------------------------------

/**
 * Returns a date as per defined formate.
 * convert yyyy-mm-dd to dd/mm/yyyy
 * @access	public
 * @param	string the chosen format
 * @return	date
 */
if (!function_exists('DBtoDisp')) {

    function DBtoDisp($InDate, $format = BASE_DATE_FORMATE) {
        $standard_date = date($format, strtotime(str_replace("/", "-", $InDate)));
        return $standard_date;
    }

}

// ------------------------------------------------------------------------

/* End of file date_helper.php */