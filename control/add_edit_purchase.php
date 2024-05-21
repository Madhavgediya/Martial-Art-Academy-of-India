<?php
include("includes/application_top.php");
include("../includes/class/invoice.php");
$page_type = "Purchase";
$caption = "Add Purchase";
$btn_caption = "Add Purchase";
$ajax_page_url = "add_edit_purchase.php";
$success_page_url = "manage_purchase.php";
$manage_url = "manage_purchase.php";
$purchaser_caption = "Dealer";
$id = get_rdata("id", 0);
$act = get_rdata("act");
if ($id != 0) 
{
    $caption = "Edit Purchase";
    $btn_caption = "Edit Purchase";
}
$table_invoice = "sm_invoice";
$table_invoice_products = "sm_invoice_products";
include("purchase_sale.php"); 
?>