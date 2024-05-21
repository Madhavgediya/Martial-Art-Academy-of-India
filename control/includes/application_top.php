<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$tmp_default_batch_time =  $tmp_name= $tmp_pass= $tmp_type= $tmp_status= $tmp_batch_time = $tmp_invoice_prefix = "";
$arr_status_global = array("All"=>"All","A"=>"Active","I"=>"Inactive");
$arr_master_open = array('add_edit_account.php','manage_account.php','add_edit_category.php','manage_category.php','add_edit_manufacturer.php','manage_manufacturer.php','add_edit_products.php','manage_products.php','manage_belt.php','add_edit_belt.php','manage_course.php','add_edit_course.php','manage_course_belt.php','add_edit_course_belt.php','add_edit_product_option.php','manage_product_option.php');
$arr_purchase_sale_open = array('purchase_sale_return.php ','add_edit_sale.php','manage_sale.php','manage_return_sale.php', 'manage_return_item_sale.php','add_edit_purchase.php','manage_purchase.php','manage_return_purchase.php','manage_return_item_purchase.php','add_edit_dealer.php','manage_dealer.php','add_edit_customer.php','manage_customer.php');
$arr_event_sale_open = array('manage_event.php','add_edit_event.php','event_student_entrolled.php','manage_event_others.php','add_edit_event_others.php');
$arr_report_stock = array('report_manage_event.php','report_factulty_attendance.php','report_stock.php','report_student_attendance.php','report_receipt.php','report_dealer_payment.php','report_income_expance.php');
$arr_others = array('pending_fees.php','student_missing_details.php');
// included necessary files. 
include("../includes/connection.php");
include("../includes/database.php");
include("../includes/functions.php");

// include helpers file
include("../includes/helpers/session_helper.php");
include("../includes/helpers/date_helper.php");

// include class files
include("../includes/class/project.php");
include("../includes/class/user.php");
include("../includes/class/log_master.php");
include("../includes/class/log_email.php");
include("../includes/class/mail_template.php");
include("../includes/class/branch.php");
include("../includes/class/user_access.php");

$product_used_array = array();
$product_used_array["NA"]="NA";
$product_used_array["In office use Yes"]="In office use Yes";


$arr_only_admin_files = array();
$arr_only_main_branch_files = array("manage_course.php","add_edit_course.php","manage_belt.php","add_edit_belt.php","manage_course_belt.php","add_edit_course_belt.php","add_edit_examcategories.php","manage_examcategories.php");
// include pagination class
include("ps_pagination.php");

// include log message file
include("log_msg.php");

$c_file = basename($_SERVER['SCRIPT_FILENAME']);

$tmp_admin_id = session_get("id");

//define global variables.
$errormsg = '';
$successmsg = '';

//set Page Title with Default Value
$page_title = SITE_TITLE_LONG;
$master_file = '';

$cur_date = date('Y-m-d H:i:s');
$cur_date_only = date('d-m-Y');
$cur_date_only_db = date('Y-m-d');


if ($tmp_admin_id == 0 && $c_file !='login.php') {
    header("location:login.php");
    exit;
}
$arr_banned = array("manage_events.php",
    "add_edit_events.php",
    "manage_gallery.php",
    "add_edit_gallery.php",
    "manage_news.php",
    "add_edit_news.php");
if (($tmp_admin_id != 1 && $tmp_admin_id != 2) && in_array($c_file,$arr_banned )) {
    header("location:index.php");
    exit;
}

$arr_allow_file_type = array("png","jpg","jpeg","gif");
$arr_allow_file_type_document = array("pdf","doc","docx","xls","xlsx","png","jpg","jpeg","gif","txt");
define('EVENTS_IMAGE','../images/events/');
define('STUDENT_DOCUMENT_IMAGE','../images/student_document/');
define('FACULTY_DOCUMENT_IMAGE','../images/faculty_document/');
define('FACULTIES_IMAGE','../images/faculties/');
define('GALLERY_IMAGE','../images/gallery/');
define('NEWS_IMAGE','../images/news/');
define('STUDENT_IMAGE','../images/student/');
define('TIMETABLE_IMAGE','../images/timetable/');
define('RESULTS_IMAGE','../images/results/');
define('ARTICLE_DOC','../document/article/');
define('CIRCULAR_IMAGE','../images/circular/');
define('CIRCULAR_IMAGE_URL','images/circular/');
define('DAILYDARSHAN_IMAGE','../images/dailydarshan/');
define('DAILYDARSHAN_IMAGE_URL','images/dailydarshan/');
define('ADD_ABSENT_OR_FAIL_DAYS',25);
define('BOOK_ISSUE_PERIOD','+15 days');
define('BIRTHDAY_PERIOD','+1 month');
define('BRANCH_IMAGE','../images/branch/');
define('BRANCH_IMAGE_URL','images/branch/');
define('JQUERY_VERSION','4');

$tmp_admin_id = session_get("id");
  if ($tmp_admin_id > 0)
  {
    $get_session_data = get_user_details();
    if ($get_session_data != "")
    {
      session_unset("id");
      session_destroy();
      header("Location:login.php?msg_id=3");
      exit(0);
    }
    }
    check_rights_main_branch();
if ($c_file == "purchase_sale_print.php" ||  $c_file == "add_edit_purchase.php" || $c_file == "add_edit_sale.php"|| $c_file == "purchase_sale_return.php")
{
  $arr_payment_status = array();
  $arr_payment_status["Paid Full"] = "Paid Full";
  $arr_payment_status["Paid Deposit"] = "Paid Deposit";
  $arr_payment_status["Cash on delivery"] = "Cash on delivery";
  $arr_payment_status["Paid Deposit + C.O.D."] = "Paid Deposit + C.O.D.";
  $arr_payment_status["Pending Credit"] = "Pending Credit";

  $arr_payment_status_purchase = array();
  $arr_payment_status_purchase["Cash"] = "Cash";
  $arr_payment_status_purchase["Paid Deposit"] = "Paid Deposit";
  $arr_payment_status_purchase["Cash on delivery"] = "Cash on delivery";
  $arr_payment_status_purchase["Pending Credit"] = "Pending Credit";
  $arr_payment_status_purchase["Bank"] = "Bank";

  $arr_payment_status_sale = array();
  $arr_payment_status_sale["Cash"] = "Cash";
  $arr_payment_status_sale["Paid Deposit"] = "Paid Deposit";
  $arr_payment_status_sale["Cash on delivery"] = "Cash on delivery";
  $arr_payment_status_sale["Pending Debite"] = "Pending Debite";
  $arr_payment_status_sale["Bank"] = "Bank";
  
  $arr_payment_method = array();
  $arr_payment_method["Credit"] = "Credit";
  $arr_payment_method["Debit"] = "Debit";
  $arr_payment_method["NA"] = "NA";
}
?>
