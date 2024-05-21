<?php
//echo 'expired';
define("SITE_TITLE_LONG",'Martial Art Academy Of India');
define("SITE_TITLE_SORT",'Martial Art');
error_reporting(E_ALL); ini_set('display_errors', '1'); 
//exit(0);
define("IS_LOCAL", 0);
#define("SITE_URL", "http://maccess.martialartacademyofindia.com/");
define("SITE_URL", "http://localhost/martial_arts/");
date_default_timezone_set('Asia/Kolkata');
// Database configuration
define("DB_HOST", "localhost");
define("DB_NAME", "dbkkw4rfsaxdu5");
// define("DB_USERNAME", "martialart");
// define("DB_PASSWROD", 'AVS3n8hmHv1dIadWg0VD');
define("DB_USERNAME", "root");
define("DB_PASSWROD", '');
//define("DB_NAME", "dbkkw4rfsaxdu5");
//define("DB_USERNAME", "u824dvtsn288c");
// define( 'API_ACCESS_KEY', 'AIzaSyBN8f1QLxfLG6Ov0VoEHBrodaH7_3Q4Z7g' );
define( 'API_ACCESS_KEY', '' );
//define("DB_PASSWROD", "qfv4ymmyyvss");



// Set session id for project
define("SES_ID", "ma");

// Per page defined
define("PER_PAGE", "50");

// Get Root Directory
define('DOCUMENT_ROOTS', dirname(__FILE__));

// Upload file path
define("FILE_UPLOAD_PATH", DOCUMENT_ROOTS . 'uploads/');

// For Log Entry Enable/Disable
define('ENABLE_LOG', 1);

// Define date formate
define("BASE_DATE_FORMATE", 'd-m-Y');

// For Enable/Disable Remember Me functionality
define("REMEMBER_ME_ENABLE", 0);
define("REMEMBER_ME_TIME", 3600);

// Mail parameters
define('MAIL_FROM_NAME', 'PHP-BASE');
define('MAIL_FROM_EMAIL', 'abc@dmail.com');
define('MAIL_TO_NAME', 'PHP-BASE');
define('MAIL_TO_EMAIL', 'xyz@dmail.com');
define('MAIL_PORT', 465);
define('MAIL_SWITCH',FALSE);
define('MAIL_SMTP_SECURE', 'ssl');
define('MANDRILL_SUBACCOUNT','php_base_test');
define('CON_MAIL_HOST', 'mail.host');
define('CON_MAIL_USER', 'admin email');
define('CON_MAIL_PASS', 'password');

// Set default time zone for project
date_default_timezone_set('Asia/Kolkata');

// Set login with username or email id
define('EMAIL_LOGIN', 1);

/*
 * ---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * ---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

define('ENVIRONMENT', 'development');

/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL);
            break;

        case 'testing':
        case 'production':
            error_reporting(0);
            break;

        default:
            exit('The application environment is not set correctly.');
    }
}

/*
  $host = 'localhost';
  $user = 'root';
  $password = '';
  $db_name = 'hinesh_century21_prefix';

  mysql_connect($host,$user,$password);
  mysql_select_db($db_name);
 */

try {
    $dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWROD, array(PDO::ATTR_PERSISTENT => true));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
    // echo "Connected\n";
} catch (Exception $e) {
    die("Unable to connect: " . $e->getMessage());
}
?>
