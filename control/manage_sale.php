<?php
include("includes/application_top.php");
include("../includes/class/invoice_sale.php");
$page_type = "Sale";
$caption = $page_title = "Manage Sale";
$btn_caption = "Manage Sale";
$edit_page_url = "add_edit_sale.php";
$success_page_url = "manage_sale.php";
$manage_url = "manage_sale.php";
$manage_add="add_edit_sale.php";
$purchaser_caption = "Student";
$errormsg = get_rdata('errormsg', '');
$id = get_rdata("id", 0);
$act = get_rdata("act");
if ($id != 0) 
{
    $caption = "Edit Sale";
    $btn_caption = "Edit Sale";
}
$table_invoice = "sm_invoice_sale";
$table_invoice_products = "sm_invoice_products_sale";
$table_dealer_student = "sm_student";
include("manage_purchase_sale.php"); 
?>