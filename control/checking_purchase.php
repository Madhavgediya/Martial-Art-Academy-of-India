<?php
include("includes/application_top.php");
include("../includes/class/invoice.php");
// Set the caption of button
$id = get_rdata("id", 0);
$caption = "Add Purchase";
$btn_caption = "Add Purchase";
if ($id != 0) {
    $caption = "Edit Purchase";
    $btn_caption = "Edit Purchase";
}
// Set Page Title
$page_title = $caption;
$inv_purchase_del_id = get_rdata('inv_purchase_del_id', 0);
$add_more = get_rdata('add_more', '');
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');
$admin_id = $tmp_admin_id;
// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$act = get_rdata("act");
$admin_id = $tmp_admin_id;
$inv_store_id = $tmp_admin_id;
$inv_net_amount = $inv_total_amount =  $inv_gst_amount = 0;
$invpro_id = get_rdata('invpro_id');
$inv_status = get_rdata('inv_status');
$inv_payment_status = get_rdata('inv_payment_status');
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
    $successmsg = "Purchase has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT * FROM sm_invoice WHERE inv_id = " . $id;
    $user = new invoice();
    $user->cquery = $cquery;
    $user->action = 'get';
    $result = $user->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
        echo "E##" . $errormsg;
        exit(0);
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $inv_purchase_del_id = $db_row['inv_purchase_del_id'];
                $inv_payment_method = $db_row['inv_payment_method'];
                $inv_payment_status = $db_row['inv_payment_status'];
                $inv_payment_notes = $db_row['inv_payment_notes'];
                $inv_discount_amount = $db_row['inv_discount_amount'];
                $inv_additional_amount = $db_row['inv_additional_amount'];
                $inv_net_amount = $db_row['inv_net_amount'];
                $inv_total_amount = $db_row['inv_total_amount'];
               
                $inv_sgst_amount = $db_row['inv_sgst_amount'];
                $inv_cgst_amount = $db_row['inv_cgst_amount'];
                $inv_generate_date = convert_db_to_disp_date($db_row['inv_generate_date']);
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
    $invoice = new invoice();
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
        
        $arr_amount = array("inv_sgst_amount" => 0, "inv_cgst_amount" => 0,"inv_total_amount" => 0, "inv_net_amount" => 0, "inv_total_paid_amount" => 0, "inv_gst_amount" => 0);
        // add code to insert entry for invoice product
        if (isset($product_name) && is_array($product_name)) {
            $ip = 0;
            foreach ($product_name as $key => $value) {
                if ($ip != 0 && (int) $product_name[$ip] > 0) {
                    $arr_input_invoice_product = array();
                    $arr_input_invoice_product["invpro_id"] = 0;
                    $arr_input_invoice_product["action"] ="add";
                    $arr_input_invoice_product["invpro_inv_id"] = $inv_id;
                    $arr_input_invoice_product["invpro_pro_id"] = $product_name[$ip];
                    $arr_input_invoice_product["invpro_po_id"] = $product_option[$ip];
                    $arr_input_invoice_product["invpro_po_id_2"] = $product_option_2[$ip];
                    $arr_input_invoice_product["invpro_pro_qty"] = $product_qty[$ip];
                    $arr_input_invoice_product["invpro_used"] = $invpro_used[$ip];
                    $arr_input_invoice_product["invpro_final_pro_price"] = $product_price[$ip];
                    $arr_input_invoice_product["invpro_final_price_tot"] = $product_net_price[$ip];
                    $arr_input_invoice_product["invpro_gst"] = $product_gst[$ip];
/*
                grand_gst_local = $(this).find(".pro_net_price").val() *100;
                console.log("0-"+grand_gst_local);
                grand_gst_local_division = parseFloat(100+parseFloat($(this).find(".inv_gst").val()));
                console.log("1-"+grand_gst_local_division);
                grand_gst_local = (grand_gst_local/grand_gst_local_division).toFixed(2);
                console.log("2-"+grand_gst_local);
                grand_gst_local = parseFloat($(this).find(".pro_net_price").val())-grand_gst_local;

                // console.log("A-"+$(this).find(".pro_net_price").val());
                // console.log("B-"+(($(this).find(".pro_net_price").val()) * 100 / (100+$(this).find(".inv_gst").val())).toFixed(2));
                // grand_gst_local =  $(this).find(".pro_net_price").val()-(($(this).find(".pro_net_price").val()) * 100 / (100+$(this).find(".inv_gst").val())).toFixed(2);
                // console.log("C"+grand_gst_local);
                // console.log(grand_gst_local);
                grand_gst = grand_gst + grand_gst_local.toFixed(2);
*/                

                    $invpro_cgst_amount  = $invpro_sgst_amount  =  0;
                    if ($product_net_price[$ip] !="" && $product_net_price[$ip] !=0 && $product_net_price[$ip] > 0 && $product_gst[$ip] !="" && $product_gst[$ip] !=0 && $product_gst[$ip] > 0)
                    {
                        $top = $product_net_price[$ip] * 100;
                        $division = 100+$product_gst[$ip];
                        $invpro_cgst_amount = ROUND(($top/$division),2);
                        $invpro_cgst_amount = ROUND(($product_net_price[$ip]-$invpro_cgst_amount)/2,2);
                        $invpro_sgst_amount = $invpro_cgst_amount ;
                    }
                    $arr_input_invoice_product["invpro_cgst_amount"] = $invpro_cgst_amount;
                    $arr_input_invoice_product["invpro_sgst_amount"] = $invpro_sgst_amount;
                    $arr_amount["inv_cgst_amount"] += $invpro_cgst_amount;
                    $arr_amount["inv_sgst_amount"] += $invpro_sgst_amount;
                    $arr_amount["inv_total_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_net_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_total_paid_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_gst_amount"] += $product_gst[$ip];
                    
                    $arr_invoice_product = add_invoice_product($arr_input_invoice_product);
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


        echo "S##M##manage_invoice.php?msg=3&page=1&per_page=" . $per_page;
        // If success then redirect to manage user page
        // header('Location:manage_invoice.php?msg=2&page=1&per_page=' . $per_page);
        exit(0);
    }
}
if ($act == 'update') {
    $invoice = new invoice();
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
        $arr_amount = array("inv_total_amount" => 0, "inv_net_amount" => 0, "inv_total_paid_amount" => 0, "inv_gst_amount" => 0);
        // add code to insert entry for invoice product
        if (isset($product_name) && is_array($product_name)) {
            $ip = 0;
            $invpro_id_string = "";
            foreach ($product_name as $key => $value) {
                if ($ip != 0 && (int) $product_name[$ip] > 0) {
                    $arr_input_invoice_product = array();

                    $arr_input_invoice_product["invpro_id"] = (isset($invpro_id[$ip])?$invpro_id[$ip]:0);
                    $arr_input_invoice_product["action"] = ($arr_input_invoice_product["invpro_id"]==0)?"add":"edit";
                    $arr_input_invoice_product["invpro_inv_id"] = $inv_id;
                    $arr_input_invoice_product["invpro_pro_id"] = $product_name[$ip];
                    $arr_input_invoice_product["invpro_po_id"] = $product_option[$ip];
                    $arr_input_invoice_product["invpro_po_id_2"] = $product_option_2[$ip];
                    $arr_input_invoice_product["invpro_pro_qty"] = $product_qty[$ip];
                    $arr_input_invoice_product["invpro_used"] = $invpro_used[$ip];
                    $arr_input_invoice_product["invpro_final_pro_price"] = $product_price[$ip];
                    $arr_input_invoice_product["invpro_final_price_tot"] = $product_net_price[$ip];
                    $arr_input_invoice_product["invpro_gst"] = $product_gst[$ip];
                    $invpro_cgst_amount  = $invpro_sgst_amount  =  0;
                    $arr_input_invoice_product["invpro_cgst_amount"] = $invpro_cgst_amount;
                    $arr_input_invoice_product["invpro_sgst_amount"] = $invpro_sgst_amount;
                    $arr_amount["inv_total_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_net_amount"] += $product_net_price[$ip];
                    $arr_amount["inv_total_paid_amount"] += $product_net_price[$ip];
                    // $arr_amount["inv_gst_amount"] += $product_gst[$ip];
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
                remove_invoice_product($invpro_id_string,$id);
            }
        }


        $arr_amount["inv_discount_amount"] = $inv_discount_amount;
        $arr_amount["inv_additional_amount"] = $inv_additional_amount;
        $arr_amount["inv_id"] = $inv_id;
        $arr_amount["inv_shipping_amount"] = $inv_shipping_amount;

        update_invoice_amount($arr_amount);
        // end of code adding entry for invoice product
        // Add log entry


        echo "S##M##manage_invoice.php?msg=3&page=1&per_page=" . $per_page;
        // If success then redirect to manage user page
        // header('Location:manage_invoice.php?msg=2&page=1&per_page=' . $per_page);
        exit(0);
    }
}
$dd_products = "";
if ($id != 0) {
    $arr_products_key_value = get_product_as_array(1);
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
                <select id="first-select" class="select2" >
                <option value="yes">yes</option>
                <option value="no">no</option>
                </select>
                <select id="second-select" class="select2" >
                <option value="yes">yes</option>
                <option value="no">no</option>
                <option value="process">process</option>
                </select>
                <a href="javascript:void(0);" onclick="check_m_select_process()">check select 2</a>
        </div>
            <script type="text/javascript">
            function check_m_select_process()
            {
                alert("checking select second ");
                $('#second-select').select2();
            }
            $('#first-select').select2();
        </script>
        </div>
    </body>
</html>