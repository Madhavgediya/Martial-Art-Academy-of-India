<?php
include("includes/application_top.php");
include("../includes/class/invoice.php");
$page_type = "Purchase";
$caption = $page_title = "Purchase Report";
$btn_caption = "Purchase Report";
$edit_page_url = "add_edit_purchase.php";
$success_page_url = "report_purchase.php";
$manage_url = "report_purchase.php";
$manage_add="add_edit_purchase.php";
$purchaser_caption = "Dealer";
$errormsg = get_rdata('errormsg', '');
$id = get_rdata("id", 0);
$act = get_rdata("act");
if ($id != 0) 
{
    $caption = "Edit Purchase";
    $btn_caption = "Edit Purchase";
}
$table_invoice = "sm_invoice";
$table_invoice_products = "sm_invoice_products";
$table_dealer_student = "sm_dealer";
include("report_manage_purchase_sale.php"); 
?>