<?php
include("includes/application_top.php");
include("../includes/class/invoice.php");
$page_type = "Purchase";
$caption = "Purchase Return";
$btn_caption = "Return";
$ajax_page_url = "add_edit_purchase.php";
$success_page_url = "manage_purchase.php";
$manage_url = "manage_purchase.php";
$purchaser_caption = "Dealer";
$id = get_rdata("id", 0);
$act = get_rdata("act");

$table_invoice = "sm_invoice";
$table_invoice_products = "sm_invoice_products";
// Set the caption of button
// invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2,'##',invpro_final_pro_price,'##',invpro_pro_qty
$bln_dealear = false;
$bln_dealear_igst = false;
$purchase_inv_ids = "";
if ($id != 0) 
{
    $bln_dealear = true;
}
// $c_file = "qlog.sql";
// add_log_txt($c_file. '--'.$_REQUEST);
// Set Page Title
$page_title = $caption;
$return_order = get_rdata('return_order', 0);

$inv_purchase_del_id = get_rdata('inv_purchase_del_id', 0);
$add_more = get_rdata('add_more', '');
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');
$admin_id = $tmp_admin_id;
// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$admin_id = $tmp_admin_id;
$inv_store_id = $tmp_admin_id;
$inv_net_amount = $inv_total_amount = $inv_igst_amount =  $inv_gst_amount = $inv_cgst_amount =  $inv_sgst_amount = 0;
$invpro_id = get_rdata('invpro_id');
$inv_status = get_rdata('inv_status');
$inv_payment_status = get_rdata('inv_payment_status');
$inv_purchase_invoice_no = get_rdata('inv_purchase_invoice_no');
$inv_additional_amount = get_rdata('inv_additional_amount',0);
$inv_discount_amount = get_rdata('inv_discount_amount');
$inv_payment_notes = get_rdata('inv_payment_notes');
$inv_payment_method = get_rdata('inv_payment_method');
$inv_shipping_amount = get_rdata('inv_shipping_amount',0);
$inv_generate_date = get_rdata('inv_generate_date', $cur_date_only);
$inv_generate_admin_id = $admin_id;
$inv_date = $cur_date_only;
$inv_admin_id = $admin_id;
$inv_additional_amount = ($inv_additional_amount=="")?0:$inv_additional_amount;
$inv_discount_amount = ($inv_discount_amount=="")?0:$inv_discount_amount;


// invoice product option table
$product_name = get_rdata('product_name');
$product_option = get_rdata('product_option');
$product_option_2 = get_rdata('product_option_2');
$product_qty = get_rdata('product_qty');
$invpro_used = get_rdata('invpro_used');
$product_price = get_rdata('product_price');
$product_net_price = get_rdata('product_net_price');
$product_gst = get_rdata('product_gst');

// end of invoice product option table

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 2) {
    $successmsg = $page_type." has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT * FROM  $table_invoice WHERE inv_id = " . $id;
    if ($page_type == "Purchase")
    {
        $invoice = new invoice();    
    }
    else
    {
        $invoice = new invoice_sale();
    }
    $invoice->cquery = $cquery;
    $invoice->action = 'get';
    $result = $invoice->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
        echo "E##" . $errormsg;
        exit(0);
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $inv_purchase_del_id = $db_row['inv_purchase_del_id'];
                $bln_dealear_igst = false;
                if (get_dealer_gst_type($inv_purchase_del_id) == 'Y')
                {
                    $bln_dealear_igst = true;
                }
                $inv_payment_method = $db_row['inv_payment_method'];
                $inv_payment_status = $db_row['inv_payment_status'];
                $inv_payment_notes = $db_row['inv_payment_notes'];
                $inv_discount_amount = $db_row['inv_discount_amount'];
                $inv_additional_amount = $db_row['inv_additional_amount'];
                $inv_net_amount = $db_row['inv_net_amount'];
                $inv_total_amount = $db_row['inv_total_amount'];
                $inv_sgst_amount = $db_row['inv_sgst_amount'];
                $inv_cgst_amount = $db_row['inv_cgst_amount'];
                $inv_igst_amount = $db_row['inv_igst_amount'];
                $inv_purchase_invoice_no = $db_row['inv_purchase_invoice_no'];
                $inv_generate_date = convert_db_to_disp_date($db_row['inv_generate_date']);
            }
        }
    }
}

// Add user entry
if ($act == 'add') 
{
    // echo "<pre>";
    // print_r($_REQUEST);
    // echo "</pre>";

    if ($page_type == "Purchase")
    {
        $invoice = new invoice();    
    }
    else
    {
        $invoice = new invoice_sale();
    }
    $invoice->data["inv_purchase_invoice_no"] = $inv_purchase_invoice_no;
    $invoice->data["inv_purchase_del_id"] = $inv_purchase_del_id;
    $invoice->data["inv_payment_method"] = $inv_payment_method;
    $invoice->data["inv_payment_status"] = $inv_payment_status;
    $invoice->data["inv_payment_notes"] = escape($inv_payment_notes);
    $invoice->data["inv_discount_amount"] = $inv_discount_amount;
    $invoice->data["inv_additional_amount"] = $inv_additional_amount;
    $invoice->data["inv_generate_date"] = convert_disp_to_db_date($inv_generate_date);
    $invoice->data["inv_generate_admin_id"] = $admin_id;
    $invoice->data["inv_date"] = $cur_date;
    $invoice->data["inv_admin_id"] = $admin_id;
    $invoice->data["inv_status"] = "G";
    $invoice->action = 'insert';

    $result = $invoice->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
        echo "E##" . $errormsg;
        exit(0);
    } else {
        $inv_id = $new_inv_id = $result['id'];
        
        $arr_amount = array("inv_sgst_amount" => 0, "inv_igst_amount" =>0, "inv_cgst_amount" => 0,"inv_total_amount" => 0, "inv_net_amount" => 0, "inv_total_paid_amount" => 0, "inv_gst_amount" => 0);
        // add code to insert entry for invoice product
        if (isset($product_name) && is_array($product_name)) {
            $ip = 0;
            foreach ($product_name as $key => $value) {
                if ($ip != 0 && (int) $product_name[$ip] > 0) {

                    if ($page_type == "Purchase")
                    {
                        $invpro_id_purchase= 0;
                        $invpro_pro_id= $product_name[$ip];
                        $invpro_po_id= $product_option[$ip];
                        $invpro_po_id_2= $product_option_2[$ip];
                        $invpro_pro_qty= $product_qty[$ip];
                        $invpro_used_var= $invpro_used[$ip];
                    }
                    else
                    {
                        $product_name_arr = explode("##",$product_name[$ip]);
                        $invpro_id_purchase= $product_name_arr[0];
                        $purchase_inv_ids = $purchase_inv_ids . $invpro_id_purchase.",";
                        $invpro_pro_id = $product_name_arr[1];
                        $invpro_po_id = $product_name_arr[2];
                        $invpro_po_id_2 = $product_name_arr[3];
                        $invpro_pro_qty = $product_qty[$ip];
                        $invpro_used_var= "NA";
                       // invpro_id-pro_id-invpro_po_id-invpro_po_id_2-invpro_final_pro_price-invpro_pro_qty  
                    }

           
                    $invpro_final_pro_price = $product_price[$ip];
                    $invpro_final_price_tot = $product_net_price[$ip];
                    $invpro_gst_var = $product_gst[$ip];
                    $product_gst_var = $product_gst[$ip];

                    $arr_input_invoice_product = array();
                    $arr_input_invoice_product["invpro_id"] = 0;
                    $arr_input_invoice_product["type"] = $page_type;
                    $arr_input_invoice_product["invpro_id_purchase"] = $invpro_id_purchase;
                    $arr_input_invoice_product["action"] ="add";
                    $arr_input_invoice_product["invpro_inv_id"] = $inv_id;
                    $arr_input_invoice_product["invpro_pro_id"] = $invpro_pro_id;
                    $arr_input_invoice_product["invpro_po_id"] = $invpro_po_id;
                    $arr_input_invoice_product["invpro_po_id_2"] = $invpro_po_id_2;
                    $arr_input_invoice_product["invpro_pro_qty"] = $invpro_pro_qty;
                    $arr_input_invoice_product["invpro_used"] = $invpro_used_var;
                    $arr_input_invoice_product["invpro_final_pro_price"] = $invpro_final_pro_price;
                    $arr_input_invoice_product["invpro_final_price_tot"] = $invpro_final_price_tot;
                    $arr_input_invoice_product["invpro_gst"] = $invpro_gst_var;

                    $invpro_cgst_amount  = $invpro_sgst_amount  = $inv_pro_igst_amount= 0;
                    if ($invpro_final_price_tot !="" && $invpro_final_price_tot !=0 && $invpro_final_price_tot > 0 && $product_gst_var !="" && $product_gst_var !=0 && $product_gst_var > 0)
                    {
                        $top = $invpro_final_price_tot * 100;
                        $division = 100 + $product_gst_var;
                        $invpro_cgst_amount = ROUND(($top/$division),2);
                        $invpro_cgst_amount = ROUND(($invpro_final_price_tot-$invpro_cgst_amount)/2,2);
                        $inv_pro_igst_amount = ROUND(($invpro_final_price_tot-$invpro_cgst_amount),2);
                        $invpro_sgst_amount = $invpro_cgst_amount ;
                    }
                    $arr_input_invoice_product["invpro_cgst_amount"] = $invpro_cgst_amount;
                    $arr_input_invoice_product["invpro_sgst_amount"] = $invpro_sgst_amount;
                    $arr_input_invoice_product["invpro_igst_amount"] = $inv_pro_igst_amount;
                    $arr_amount["inv_igst_amount"] += $inv_pro_igst_amount;
                    $arr_amount["inv_cgst_amount"] += $invpro_cgst_amount;
                    $arr_amount["inv_sgst_amount"] += $invpro_sgst_amount;
                    $arr_amount["inv_total_amount"] += $invpro_final_price_tot;
                    $arr_amount["inv_net_amount"] += $invpro_final_price_tot;
                    $arr_amount["inv_total_paid_amount"] += $invpro_final_price_tot;
                    $arr_amount["inv_gst_amount"] += $product_gst_var;
                    
                    $arr_invoice_product = add_invoice_product($arr_input_invoice_product,$page_type);
                    if ($arr_invoice_product["errormsg"] != '') {
                        $errormsg = $arr_invoice_product["errormsg"];
                        echo "E##" . $errormsg;
                        exit(0);
                        break;
                    }
                }
                $ip++;
            }
        }


        $arr_amount["inv_discount_amount"] = $inv_discount_amount;
        $arr_amount["inv_additional_amount"] = $inv_additional_amount;
        
        $arr_amount["inv_id"] = $inv_id;
        $arr_amount["inv_shipping_amount"] = $inv_shipping_amount;

        update_invoice_amount($arr_amount);
        // end of code adding entry for invoice product
        // Add log entry

        $update_purchase_order_result = "";
        if ($page_type == "Sale" && $purchase_inv_ids !='')
        {
            $update_purchase_order_result =  update_purchase_order($purchase_inv_ids);
        }
        if ($update_purchase_order_result !='')
        {
            echo "E##" . $update_purchase_order_result;
            exit(0);
        }
        else 
        {
            // echo "E##rrr" . $per_page;
            echo "S##M##manage_invoice.php?msg=3&page=1&per_page=" . $per_page;
        }

        // If success then redirect to manage user page
        // header('Location:manage_invoice.php?msg=2&page=1&per_page=' . $per_page);
        exit(0);
    }
}
if ($act == 'update') {
/*
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
*/
    if ($page_type == "Purchase")
    {
        $invoice = new invoice();    
    }
    else
    {
        $invoice = new invoice_sale();
    }
    $invoice->data["inv_purchase_invoice_no"] = $inv_purchase_invoice_no;
    $invoice->data["inv_purchase_del_id"] = $inv_purchase_del_id;
    $invoice->data["inv_payment_method"] = $inv_payment_method;
    $invoice->data["inv_payment_status"] = $inv_payment_status;
    $invoice->data["inv_payment_notes"] = escape($inv_payment_notes);
    $invoice->data["inv_discount_amount"] = $inv_discount_amount;
    $invoice->data["inv_additional_amount"] = $inv_additional_amount;
    $invoice->data["inv_generate_date"] = convert_disp_to_db_date($inv_generate_date);
    $invoice->data["inv_generate_admin_id"] = $admin_id;
    $invoice->data["inv_date"] = $cur_date;
    $invoice->data["inv_admin_id"] = $admin_id;
    $invoice->data["inv_status"] = "G";
    $invoice->process_id = $id;
    $invoice->action = 'update';

    $result = $invoice->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
        echo "E##" . $errormsg;
        exit(0);
    } else {
        $inv_id = $new_inv_id = $id;
        $arr_amount = array("inv_total_amount" => 0, "inv_igst_amount" =>0, "inv_net_amount" => 0, "inv_total_paid_amount" => 0, "inv_gst_amount" => 0,"inv_sgst_amount" => 0, "inv_cgst_amount" => 0);  
        // add code to insert entry for invoice product
        if (isset($product_name) && is_array($product_name)) {
            $ip = 0;
            $invpro_id_string = $invpro_id_purchase_string = "";
            // validation must be processed here
            foreach ($product_name as $key => $value) {
                if ($ip != 0 && (int) $product_name[$ip] > 0) {
                    $arr_input_invoice_product = array();
                    // for purchase it the same as following
                    // for sell we need to split the data of product and invpro_id-pro_id-invpro_po_id-invpro_po_id_2-invpro_final_pro_price-invpro_pro_qty
                    
                    if ($page_type == "Purchase")
                    {
                        $invpro_id_purchase= 0;
                        $invpro_pro_id= $product_name[$ip];
                        $invpro_po_id= $product_option[$ip];
                        $invpro_po_id_2= $product_option_2[$ip];
                        $invpro_pro_qty= $product_qty[$ip];
                        $invpro_used_var= $invpro_used[$ip];
                    }
                    else
                    {
                        $product_name_arr = explode("##",$product_name[$ip]);
                        $invpro_id_purchase= $product_name_arr[0];
                        $purchase_inv_ids = $purchase_inv_ids . $invpro_id_purchase.",";
                        $invpro_pro_id = $product_name_arr[1];
                        $invpro_po_id = $product_name_arr[2];
                        $invpro_po_id_2 = $product_name_arr[3];
                        $invpro_pro_qty = $product_qty[$ip];
                        $invpro_used_var= "NA";
                       // invpro_id-pro_id-invpro_po_id-invpro_po_id_2-invpro_final_pro_price-invpro_pro_qty  
                    }
                    $invpro_final_pro_price= $product_price[$ip];
                    $invpro_final_price_tot= $product_net_price[$ip];
                    $invpro_gst_var = $product_gst[$ip];
                    $product_gst_var = $product_gst[$ip];
                    
                    $arr_input_invoice_product["type"] = $page_type;
                    $arr_input_invoice_product["invpro_id_purchase"] = $invpro_id_purchase;
                    $arr_input_invoice_product["invpro_id"] = (isset($invpro_id[$ip])?$invpro_id[$ip]:0);
                    $arr_input_invoice_product["action"] = ($arr_input_invoice_product["invpro_id"]==0)?"add":"edit";
                    $arr_input_invoice_product["invpro_inv_id"] = $inv_id;
                    $arr_input_invoice_product["invpro_pro_id"] = $invpro_pro_id;
                    $arr_input_invoice_product["invpro_po_id"] = $invpro_po_id;
                    $arr_input_invoice_product["invpro_po_id_2"] = $invpro_po_id_2;
                    $arr_input_invoice_product["invpro_pro_qty"] = $invpro_pro_qty;
                    $arr_input_invoice_product["invpro_used"] = $invpro_used_var;
                    $arr_input_invoice_product["invpro_final_pro_price"] = $invpro_final_pro_price;
                    $arr_input_invoice_product["invpro_final_price_tot"] = $invpro_final_price_tot;
                    $arr_input_invoice_product["invpro_gst"] = $product_gst_var;
                    $invpro_cgst_amount  = $invpro_sgst_amount  = $inv_pro_igst_amount= 0;
                    if ($invpro_final_price_tot !="" && $invpro_final_price_tot !=0 && $invpro_final_price_tot > 0 && $product_gst_var !="" && $product_gst_var !=0 && $product_gst_var > 0)
                    {
                        $top = $product_net_price[$ip] * 100;
                        $division = 100 + $product_gst_var;
                        $invpro_cgst_amount = ROUND(($top/$division),2);
                        $invpro_cgst_amount = ROUND(($product_net_price[$ip]-$invpro_cgst_amount)/2,2);
                        $invpro_sgst_amount = $invpro_cgst_amount ;
                        $inv_pro_igst_amount = ROUND(($product_net_price[$ip]-$invpro_cgst_amount),2);
                    }
                    $arr_input_invoice_product["invpro_cgst_amount"] = $invpro_cgst_amount;
                    $arr_input_invoice_product["invpro_sgst_amount"] = $invpro_sgst_amount;
                    $arr_input_invoice_product["invpro_igst_amount"] = $inv_pro_igst_amount;

                    $arr_amount["inv_igst_amount"] += $inv_pro_igst_amount;
                    $arr_amount["inv_total_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_net_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_total_paid_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_gst_amount"] += ($invpro_cgst_amount + $invpro_sgst_amount);
                    $arr_amount["inv_sgst_amount"] += $invpro_sgst_amount;
                    $arr_amount["inv_cgst_amount"] += $invpro_cgst_amount;
                    $arr_invoice_product = add_invoice_product($arr_input_invoice_product);

                    if ($arr_invoice_product["errormsg"] != '') {
                        $errormsg = $arr_invoice_product["errormsg"];
                        echo "E##" . $errormsg;
                        exit(0);
                        break;
                    }
                    else
                    {
                      $invpro_id_string .= $arr_invoice_product["id"].",";
                    }
                    
                }
                $ip++;
            }
            if ($invpro_id_string !='')
            {
                // code to get ids
                if ($page_type == "Sale")
                {
                    // code for removal
                    $invpro_id_purchase_string = update_invoice_product_stock($invpro_id_string,$id);     
                } 
                remove_invoice_product($invpro_id_string,$id);
               if ($page_type == "Sale" && $invpro_id_purchase_string !='')
                {
                        $res_update_product_stock = update_purchase_order($invpro_id_purchase_string.",");

                    // code for removal
                 //   update_invoice_product_stock($invpro_id_string,$id);     
                }
            }
        }


        $arr_amount["inv_discount_amount"] = $inv_discount_amount;
        $arr_amount["inv_additional_amount"] = $inv_additional_amount;
        $arr_amount["inv_id"] = $inv_id;
        $arr_amount["inv_shipping_amount"] = $inv_shipping_amount;

        update_invoice_amount($arr_amount);
        // end of code adding entry for invoice product
        // Add log entry
        $update_purchase_order_result = "";
        if ($page_type == "Sale" && $purchase_inv_ids !='')
        {
            $update_purchase_order_result =  update_purchase_order($purchase_inv_ids);
        }
        if ($update_purchase_order_result !='')
        {
            echo "E##" . $update_purchase_order_result;
            exit(0);
        }
        else 
        {
            echo "S##M##manage_invoice.php?msg=3&page=1&per_page=" . $per_page;
        }
        // If success then redirect to manage user page
        // header('Location:manage_invoice.php?msg=2&page=1&per_page=' . $per_page);
        exit(0);
    }
}
$dd_products = "";
if ($id != 0) {
    $arr_products_key_value = get_product_as_array(1,'',$page_type);
    $arr_products_options_key_value = get_product_options_as_array('A');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
        <title><?php echo $page_title; ?></title>
        <?php include("includes/include_files.php"); ?>
        <?php
         if ($page_type == "Purchase")
         {
            $data_arr_input = array();
            $data_arr_input['select_field'] = 'pro_name ,pro_id';
            $data_arr_input['table'] = 'sm_products';
            $data_arr_input['key_id'] = 'pro_id';
            $data_arr_input['key_name'] = 'pro_name';
            $data_arr_input['orderby'] = 'pro_name ASC';
            $data_arr_input['where'] = ' pro_status = 1 ';
            $data_arr_input['current_selection_value'] = 0;
            $result_products = get_products_invoice($data_arr_input);
            if ($result_products['error_message'] != '') {
                $errormsg = $result_products['error_message'];
            } else {
                $dd_products = $result_products['return_value'];
            }    
         }
         else
         {
            /*
            old working fine.
            $data_arr_input = array();
            $data_arr_input['select_field'] = ' DISTINCT pro_name, CONCAT(invpro_id,pro_id) as mid ';
            $data_arr_input['table'] = 'sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) ';
            $data_arr_input['key_id'] = 'mid';
            $data_arr_input['key_name'] = 'pro_name';
            $data_arr_input['orderby'] = 'pro_name ASC';
            $data_arr_input['where'] = ' inv_admin_id = '.$tmp_admin_id ;
            $data_arr_input['current_selection_value'] = 0;
            */
            /*
SELECT  DISTINCT CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,' (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,pro_id) as mid   
FROM sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) INNER JOIN sm_product_option po1 ON (po1.po_id = invpro_po_id) INNER JOIN sm_product_option po2 ON (po2.po_id = invpro_po_id_2)
WHERE  inv_admin_id = 1 AND (invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead) > 0
ORDER BY pro_name

            */
            $c_where = '';
            if ($id == 0)
                $c_where = ' inv_status = "G" AND (invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead) > 0 AND inv_admin_id = '.$tmp_admin_id ;
            else
                $c_where = ' inv_status = "G" AND (invpro_pro_qty - invpro_pro_qty_dead) > 0 AND inv_admin_id = '.$tmp_admin_id ;

            $data_arr_input = array();
//            $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2,'##',invpro_final_pro_price,'##',invpro_pro_qty) as mid  ";               $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2,'##',invpro_final_pro_price,'##',invpro_pro_qty) as mid  ";
            $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2,'##',invpro_final_pro_price,'##',invpro_pro_qty) as mid  ";               $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2) as mid  ";
            $data_arr_input['table'] = ' sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) INNER JOIN sm_product_option po1 ON (po1.po_id = invpro_po_id) INNER JOIN sm_product_option po2 ON (po2.po_id = invpro_po_id_2) ';
            $data_arr_input['key_id'] = 'mid';
            $data_arr_input['key_name'] = 'mname';
            $data_arr_input['order_by'] = 'pro_name ASC';
            $data_arr_input['where'] = $c_where;   
            $data_arr_input['current_selection_value'] = 0;
            $result_products = display_dd_options_return($data_arr_input);
            if ($result_products['errormsg'] != '') {
                $errormsg = $result_products['errormsg'];
            } else {
                $dd_products = $result_products['data'];
            }
         }
        
        $dd_products_option = "";
        $data_arr_input = array();
        $data_arr_input['select_field'] = 'po_name ,po_id';
        $data_arr_input['table'] = 'sm_product_option';
        $data_arr_input['key_id'] = 'po_id';
        $data_arr_input['key_name'] = 'po_name';
        $data_arr_input['orderby'] = 'po_name ASC';
        $data_arr_input['where'] = ' po_status = "Y" ';
        $data_arr_input['current_selection_value'] = 0;
        $result_products = get_products_options_invoice($data_arr_input);
        if ($result_products['error_message'] != '') {
            $errormsg = $result_products['error_message'];
        } else {
            $dd_products_option = $result_products['return_value'];
        }
        
        $dd_office_used = display_dd_options_from_array_old_return($product_used_array,"");
        ?>
    </head>
    <body class="skin-green sidebar-mini">
        <div class="preloader-overlay" style="display:none;"></div>
        <div class="box" id="m_process" style="display:none;">
            <div class="overlay" >
                <i class="fa fa-refresh fa-spin"></i><br>
                    Please wait, request is under process
            </div>
        </div>
        <div class="wrapper">

            <?php include("includes/header.php"); ?>
            <?php include("includes/left_menu.php"); ?>
            <!-- our page -->

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php echo $caption; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $caption; ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php include("includes/messages.php"); ?>
                    <?php include("includes/messages_ajax.php"); ?>
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Horizontal Form -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $caption; ?></h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <!-- onsubmit="return validate_data_ajax();" -->
                                    <form name="form1" id="form1" method="post" class="form-horizontal form-employee" >
                                    <input type="hidden" id="add_more" name="add_more" value="" />
                                    <input type="hidden" id="page_type" name="page_type" value="<?php echo $page_type; ?>" />
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="inv_customers_id" name="inv_customers_id" value="0" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="ajax_page_url" name="ajax_page_url" value="<?php echo $ajax_page_url;?>" />
                                    <input type="hidden" id="success_page_url" name="success_page_url" value="<?php echo $success_page_url;?>" />
                                    <div class="box-body">


                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $purchaser_caption;?></label>
                                                    <label class="col-sm-9">
                                                        <select disabled required id="inv_purchase_del_id" onchange="check_dealer_igst();" name="inv_purchase_del_id" class="form-control">
                                                            <option value="">-Please select-</option>
                                                            <?php

                                                           
                                                                $data_arr_input = array();
                                                                $data_arr_input['select_field'] = 'del_company_name ,del_id';
                                                                $data_arr_input['table'] = 'sm_dealer';
                                                                $data_arr_input['where'] = " del_br_id = " . $tmp_admin_id . " AND del_status  = 'A' ";
                                                                $data_arr_input['key_id'] = 'del_id';
                                                                $data_arr_input['key_name'] = 'del_company_name';
                                                                $data_arr_input['current_selection_value'] = $inv_purchase_del_id;
                                                                $data_arr_input['order_by'] = 'del_company_name';
                                                                display_dd_options($data_arr_input);
                                                           
                                                           
                                                            ?>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $page_type; ?> Date<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" disabled name="inv_generate_date" id="inv_generate_date"  placeholder="<?php $page_type; ?> Date" value="<?php echo $inv_generate_date; ?>" class="form-control" />
                                                        <!-- <a href="javascript:void(0);" onclick="check_m_select_process()">check select 2</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Payment Terms<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="inv_payment_status" id="inv_payment_status" disabled  class="form-control" >
                                                            <?php
                                                            display_dd_options_from_array_old($arr_payment_status, $inv_payment_status);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $page_type; ?> Invoice No</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" disabled id="inv_purchase_invoice_no" name="inv_purchase_invoice_no" class="form-control" value="<?php echo $inv_purchase_invoice_no; ?>" /> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="section_product">



                                        <div class="row">
                                                <div class="col-md-12 center">
                                                    <p class="lead text-center mid_caption">Product(s)</p>
                                                </div>
                                            </div>
                                            

                                                <div class="row mb5" id="" style="" >
                                                <div class="col-md-12 parent_class">
                                                <?php 
                                                
                                                    ?>
                                                    <div class="col-md-2" style="padding-left:2px; padding-right:2px;">Name</div>
                                                    <div class="col-md-2" style="padding-left:2px; padding-right:2px;">Size</div>
                                                        <div class="col-md-2" style="padding-left:2px; padding-right:2px;">Color</div>
                                                
                                                
                                                        <div class="col-md-1" style="padding-left:2px; padding-right:2px;">Office/<br>Reselling</div>
                                                
                                                    <div class="col-md-1" style="padding-left:2px; padding-right:2px;">Quantity</div>
                                                    <div class="col-md-1" style="padding-left:2px; padding-right:2px;">Price</div>
                                                    <div class="col-md-1" style="padding-left:2px; padding-right:2px;">Net Price</div>
                                                    <div class="col-md-1" style="padding-left:2px; padding-right:2px;">GST (%)</div>
                                                    <div class="col-md-1" style="padding-left:2px; padding-right:2px;"> &nbsp;</div>
                                                </div>
                                            </div>

                                            
                                            <?php
                                            if ($id != 0) {
                                                $q_invoice_products = "SELECT  invpro_pro_qty_return, invpro_pro_qty_dead, invpro_pro_qty_sold , invpro_pro_id, invpro_id_purchase, invpro_sgst_amount, invpro_cgst_amount, invpro_po_id,invpro_po_id_2, invpro_used, invpro_gst, invpro_inv_id,invpro_id, invpro_po_id ,invpro_pro_qty,invpro_final_pro_price ,invpro_final_price_tot  FROM ".$table_invoice_products."  WHERE  invpro_inv_id= " . $id;
                                                $res_invoice_products = m_process("get_data", $q_invoice_products);
                                                    
                                                if ($res_invoice_products["errormsg"] == "" && $res_invoice_products["count"] > 0) {
                                                    $i = 0;
                                                    $product_option_class = '';
                                                    
                                                    foreach ($res_invoice_products["res"] as $products_row) {
                                                        $i++;
                                                        $data_read_only = "";
                                                        if ($page_type == 'Purchase' && $products_row["invpro_pro_qty_sold"] > 0)  
                                                        {
                                                            $data_read_only = "row_overlay";
                                                        }
                                                        $product_option_class .= '
                                                        $("#product_option_'.$i.' #product_name").select2();
                                                        $("#product_option_'.$i.' #product_option").select2();
                                                        $("#product_option_'.$i.' #product_option_2").select2();
                                                        $("#product_option_'.$i.' #invpro_used").select2();
                                                        ';
                                                        ?>
                                                        <div class="row product-options mb5" id="product_option_<?php echo $i; ?>" >

                                                            <div class="col-md-12 parent_class <?php echo $data_read_only;?>">
                                                              <input type="hidden" id="invpro_id"  name="invpro_id[]" value="<?php echo $products_row["invpro_id"]; ?>" />
                                                             
                                                                <div class="col-md-2"  style="padding-left:2px; padding-right:2px;"><select  id="product_name" name="product_name[]" disabled class="form-control inv_product"  placeholder="Product Name">
                                                                      <option value="0">--Please Select--</option><?php display_dd_options_from_array_old($arr_products_key_value, $products_row['invpro_pro_id']); ?>
                                                                  </select></div>

                                                                <div class="col-md-2"  style="padding-left:2px; padding-right:2px;"><select  id="product_option" name="product_option[]" disabled class="form-control inv_product_option"  placeholder="Product Option">
                                                                        <option value="0">--Please Select--</option><?php display_dd_options_from_array_old($arr_products_options_key_value, $products_row['invpro_po_id']); ?>
                                                                    </select></div>
                                                                    <div class="col-md-2" style="padding-left:2px; padding-right:2px;"><select  id="product_option_2" name="product_option_2[]" disabled class="form-control inv_product_option_2"  placeholder="Product Option 2">
                                                                        <option value="0">--Please Select--</option><?php display_dd_options_from_array_old($arr_products_options_key_value, $products_row['invpro_po_id_2']); ?>
                                                                    </select></div>



                                                                    <div class="col-md-1" style="padding-left:2px; padding-right:2px;"><select disabled  id="invpro_used" name="invpro_used[]" class="form-control invpro_used"  placeholder="Office Used">
                                                                        <?php display_dd_options_from_array_old($product_used_array,$products_row['invpro_used']) ; ?>
                                                                    </select>
                                                                    </div>

                                                                <div class="col-md-1"  style="padding-left:2px; padding-right:2px;"><input type="text" disabled id="product_qty" name="product_qty[]" class="form-control inv_qty"  placeholder="Qty" value="<?php echo $products_row['invpro_pro_qty']; ?>"  /></div>
                                                                <div class="col-md-1"  style="padding-left:2px; padding-right:2px;"><input type="text"  disabled id="product_price" name="product_price[]" class="form-control inv_pro_price"  placeholder="Price" value="<?php echo $products_row['invpro_final_pro_price']; ?>"/></div>
                                                                <div class="col-md-1"  style="padding-left:2px; padding-right:2px;"><input type="text" disabled id="product_net_price" name="product_net_price[]" class="form-control pro_net_price money_js" placeholder="Net Price" value="<?php echo $products_row['invpro_final_price_tot']; ?>" /></div>
                                                                <div class="col-md-1" style="padding-left:2px; padding-right:2px;"><input type="text" disabled id="product_gst" name="product_gst[]" class="form-control inv_gst" placeholder="GST" value="<?php echo $products_row['invpro_gst']; ?>"  /></div>
<div class="col-md-1 text-center"  style="padding-left:2px; padding-right:2px;">
<?php if ($page_type == 'Purchase') { ?> 
<input type="hidden" id="invpro_pro_qty_sold" name="invpro_pro_qty_sold" class="invpro_pro_qty_sold" value="<?php echo $products_row['invpro_pro_qty_sold']; ?>"  />
<input type="hidden" id="invpro_pro_qty_dead" name="invpro_pro_qty_dead" class="invpro_pro_qty_dead" value="<?php echo $products_row['invpro_pro_qty_dead']; ?>"  />
<input type="hidden" id="invpro_pro_qty_return" name="invpro_pro_qty_return" class="invpro_pro_qty_dead" value="<?php echo $products_row['invpro_pro_qty_return']; ?>"  />

<input type="button" value="Ret." class="btn btn-info" id="return_inv_pro_<?php echo $products_row['invpro_id']; ?>" onclick="product_return_process('product_option_<?php echo $i; ?>','return_inv_pro_<?php echo $products_row['invpro_id']; ?>');" />  <?php } ?>
  </div>

                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    echo '<script type="text/javascript"> var next_id= '.($i+1).';'.$product_option_class.' </script>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    <div id="section_payment" style="display:none;">
                                        <div class="row">
                                            <div class="col-md-12 center">
                                                <p class="lead text-center mid_caption">Other Details</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Addition Amount</label>
                                                    <label class="col-sm-9">
                                                        <input type="text" name="inv_additional_amount" id="inv_additional_amount"  placeholder="Additional Amount" value="<?php echo $inv_additional_amount; ?>" class="form-control" />
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Discount<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_discount_amount" id="inv_discount_amount"  placeholder="Discount Amount" value="<?php echo $inv_discount_amount; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Notes</label>
                                                    <div class="col-sm-9">
                                                        <textarea id="inv_payment_notes" name="inv_payment_notes" class="form-control" > <?php echo $inv_payment_notes; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 right">
                                            <p style="margin-bottom:0px; font-size:18px;" class="lead text-right mid_caption">Sub Total: <span id="grand_total"><?php echo $inv_total_amount; ?></span> INR</p>
                                            <p style="margin-bottom:0px; font-size:18px;" id="" class="lead text-right mid_caption">Additional Amount: <span id="clone_additional_amount"><?php echo ($inv_additional_amount); ?></span> INR</p>
                                            <p style="margin-bottom:0px; font-size:18px;" id="" class="lead text-right mid_caption">Discount Amount: <span id="clone_discount_amount"><?php echo ($inv_discount_amount); ?></span> INR</p>
                                            <p style="margin-bottom:0px; font-size:18px;" id="main_final_igst" class="lead text-right mid_caption">Total IGST: <span id="clone_final_igst"><?php echo ($inv_cgst_amount+$inv_sgst_amount); ?></span> INR</p>
                                            <p style="margin-bottom:0px; font-size:18px;" id="main_final_cgst" class="lead text-right mid_caption">Total CGST: <span id="clone_final_cgst"><?php echo ($inv_cgst_amount); ?></span> INR</p>
                                            <p style="margin-bottom:0px; font-size:18px;" id="main_final_sgst" class="lead text-right mid_caption">Total SGST: <span id="clone_final_sgst"><?php echo ($inv_sgst_amount); ?></span> INR</p>
                                            <p style="margin-bottom:0px; font-size:18px;" class="lead text-right mid_caption">Total: <span id="clone_final_price"><?php echo ($inv_net_amount); ?></span> INR</p>
                                        </div>
                                    </div>
                            </div><!-- /.box-body -->
                            
                            </form>
                        </div><!-- /.box -->
                        <!-- general form elements disabled -->
                    </div></div>
            </section>
        </div>


        <?php include("includes/footer.php"); ?>
        <?php include("includes/return_product.php"); ?>
        <script type="text/javascript">
       
        $("#main_final_igst").hide();
        $("#main_final_cgst").hide();
        $("#main_final_sgst").hide();
        <?php  
           if ($bln_dealear_igst == true) 
           {
               ?>
                $("#main_final_igst").show();
            <?php
           } 
           else 
           {
               ?>    
                $("#main_final_cgst").show();
                $("#main_final_sgst").show();
            <?php
            }
            ?>
            $(".inv_pro_price, .inv_product_option, .inv_qty").change(function () {
                update_price($(this).closest(".parent_class").find(".inv_product"));
                update_gst(this);
            });

            $(".pro_net_price, #inv_additional_amount, #inv_discount_amount").change(function () {
                update_invoice_total();
                if ($("#inv_additional_amount").val() !='' && !Number.isNaN($("#inv_additional_amount").val()) && $("#inv_additional_amount").val() >0 )
                {
                    $("#clone_additional_amount").text($("#inv_additional_amount").val());
                }
                else
                {
                    $("#clone_additional_amount").text(0);
                }
                if ($("#inv_discount_amount").val() !='' && !Number.isNaN($("#inv_discount_amount").val()) && $("#inv_discount_amount").val() >0 )
                {
                    $("#clone_discount_amount").text($("#inv_discount_amount").val());
                }
                else
                {
                    $("#clone_discount_amount").text(0);
                }
                update_gst(this);
            });

            $(".inv_product").change(function () {
                update_gst(this);
            });

            $(".inv_gst").change(function () {
                update_invoice_total();
            });
            
           
           </script>
        </div>
    </body>
</html>