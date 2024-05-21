<?php
$tmp_admin_fname = $tmp_admin_mname = $tmp_admin_lname = $tmp_admin_uname = $tmp_admin_pass = $tmp_admin_email = $tmp_admin_status =$tmp_admin_login_type =  $tmp_invoice_prefix = "";
$tmp_admin_br_id = 0;
session_start();
 error_reporting(E_ALL); ini_set('display_errors', 1);
// included necessary files.
include("database.php");
include("functions.php");

// include helpers file
include("helpers/session_helper.php");
include("helpers/date_helper.php");

// include class files
include("class/project.php");
include("class/user.php");
include("class/user_access.php");
include("class/log_master.php");
include("class/log_email.php");
include("class/mail_template.php");

// include pagination class
include("ps_pagination.php");

// include log message file
include("log_msg.php");

$c_file = basename($_SERVER['SCRIPT_FILENAME']);
$access_files = array(
    'login.php',
    'forgot_password.php',
    'change_password.php',
    'logout.php',
    'registration.php',
    'webservice.php',
);
if(!in_array($c_file,$access_files)) {
	include("chk-session.php");
}

//define global variables.
$errormsg = '';
$successmsg = '';

//set Page Title with Default Value
$page_title = 'Martail Art';
?>
