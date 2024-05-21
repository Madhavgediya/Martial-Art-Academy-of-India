<?php
include("includes/application_top.php");
include("../includes/class/invoice_sale.php");
// get_product_for_sale();
$page_type = "Sale";
$caption = "Add Sale";
$btn_caption = "Add Sale";
$ajax_page_url = "add_edit_sale.php";
$success_page_url = "manage_sale.php";
$manage_url = "manage_sale.php";
$purchaser_caption = "Student/Customer";
$id = get_rdata("id", 0);
$act = get_rdata("act");
if ($id != 0) 
{
    $caption = "Edit Sale";
    $btn_caption = "Edit Sale";
}
$table_invoice = "sm_invoice_sale";
$table_invoice_products = "sm_invoice_products_sale";
include("purchase_sale.php"); 
?>