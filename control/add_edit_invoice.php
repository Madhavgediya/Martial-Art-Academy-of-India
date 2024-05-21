<?php
include("includes/application_top.php");
// Set the caption of button
$id = get_rdata("id", 0);

$caption = "Add Invoice";
$btn_caption = "Add Invoice";
if ($id != 0) {
    $caption = "Edit Invoice";
    $btn_caption = "Edit Invoice";
}
// Set Page Title
$page_title = $caption;
$add_more = get_rdata('add_more', '');
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');
$admin_id = session_get("admin_id");
// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$act = get_rdata("act");
// $inv_ref_no = randomPrefix(10);

$inv_store_id = $admin_store_id;
$chkshippingdiff = get_rdata('chkshippingdiff', 0);
$inv_customers_id = get_rdata('inv_customers_id', 0);
$inv_billing_name = get_rdata('inv_billing_name');
$inv_billing_email = get_rdata('inv_billing_email');
$inv_type = get_rdata('inv_type', 'Cash');
$inv_billing_contact1 = get_rdata('inv_billing_contact1');
$inv_billing_contact2 = get_rdata('inv_billing_contact2');
$inv_billing_address1 = get_rdata('inv_billing_address1');
$inv_net_amount = $inv_total_amount = 0;
$inv_billing_address2 = get_rdata('inv_billing_address2');
$inv_billing_city = get_rdata('inv_billing_city');
$inv_billing_state = get_rdata('inv_billing_state');
$inv_billing_country = get_rdata('inv_billing_country');
$inv_billing_postal = get_rdata('inv_billing_postal');
$inv_shipping_name = get_rdata('inv_shipping_name');
$inv_shipping_contact1 = get_rdata('inv_shipping_contact1');
$inv_shipping_contact2 = get_rdata('inv_shipping_contact2');
$inv_shipping_address1 = get_rdata('inv_shipping_address1');
$inv_shipping_address2 = get_rdata('inv_shipping_address2');
$inv_shipping_city = get_rdata('inv_shipping_city');
$inv_shipping_state = get_rdata('inv_shipping_state');
$inv_shipping_country = get_rdata('inv_shipping_country');
$inv_shipping_postal = get_rdata('inv_shipping_postal');
$inv_status = get_rdata('inv_status');
$inv_payment_method = get_rdata('inv_payment_method');
$inv_payment_status = get_rdata('inv_payment_status');
$inv_payment_notes = get_rdata('inv_payment_notes');
$inv_shipping_amount = get_rdata('inv_shipping_amount', 0);
$inv_delivery_date = get_rdata('inv_delivery_date');
$inv_delivery_specify = get_rdata('inv_delivery_specify');
$inv_delivery_mode = get_rdata('inv_delivery_mode', 'Picked up from store');
$inv_delivery_notes = get_rdata('inv_delivery_notes', '');
$inv_generate_date = get_rdata('inv_generate_date', $today);
$inv_generate_admin_id = $admin_id;
$inv_date = $cur_date;
$inv_admin_id = $admin_id;

// $inv_ref_no = get_rdata('inv_ref_no',  randomPrefix(10));
if ($id == 0) {
    $arr_generate_invoice = generate_invoice_number($inv_type);
    if ($arr_generate_invoice["errormsg"] != "") {
        $errormsg = $arr_generate_invoice["errormsg"];
    } else {
        $inv_ref_no = $arr_generate_invoice["inv_ref_no"];
    }
} else {
    $inv_ref_no = get_rdata('inv_ref_no');
}

// invoice product option table
$product_name = get_rdata('product_name');
$product_qty = get_rdata('product_qty');
$product_price = get_rdata('product_price');
$product_net_price = get_rdata('product_net_price');
// end of invoice product option table

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 2) {
    $successmsg = "Invoice has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT * FROM invoice WHERE inv_id = " . $id;
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
                $inv_ref_no = $db_row['inv_ref_no'];
                $inv_store_id = $db_row['inv_store_id'];
                $inv_customers_id = $db_row['inv_customers_id'];
                $inv_billing_name = $db_row['inv_billing_name'];
                $inv_billing_email = $db_row['inv_billing_email'];
                $inv_type = $db_row['inv_type'];
                $inv_billing_contact1 = $db_row['inv_billing_contact1'];
                $inv_billing_contact2 = $db_row['inv_billing_contact2'];
                $inv_billing_address1 = $db_row['inv_billing_address1'];
                $inv_billing_address2 = $db_row['inv_billing_address2'];
                $inv_billing_city = $db_row['inv_billing_city'];
                $inv_billing_state = $db_row['inv_billing_state'];
                $inv_billing_country = $db_row['inv_billing_country'];
                $inv_billing_postal = $db_row['inv_billing_postal'];
                $inv_shipping_name = $db_row['inv_shipping_name'];
                $inv_shipping_contact1 = $db_row['inv_shipping_contact1'];
                $inv_shipping_contact2 = $db_row['inv_shipping_contact2'];
                $inv_shipping_address1 = $db_row['inv_shipping_address1'];
                $inv_shipping_address2 = $db_row['inv_shipping_address2'];
                $inv_shipping_city = $db_row['inv_shipping_city'];
                $inv_shipping_state = $db_row['inv_shipping_state'];
                $inv_shipping_country = $db_row['inv_shipping_country'];
                $inv_shipping_postal = $db_row['inv_shipping_postal'];
                $inv_payment_method = $db_row['inv_payment_method'];
                $inv_payment_status = $db_row['inv_payment_status'];
                $inv_payment_notes = $db_row['inv_payment_notes'];
                $inv_delivery_date = convert_db_to_disp_date($db_row['inv_delivery_date']);
                $inv_delivery_specify = $db_row['inv_delivery_specify'];
                $inv_delivery_mode = $db_row['inv_delivery_mode'];
                $inv_delivery_notes = $db_row['inv_delivery_notes'];
                $inv_shipping_amount = $db_row['inv_shipping_amount'];
                $inv_net_amount = $db_row['inv_net_amount'];
                $inv_total_amount = $db_row['inv_total_amount'];
                $inv_generate_date = convert_db_to_disp_date($db_row['inv_generate_date']);
            }
        }
    }
}

// Add user entry
if ($act == 'add' || $act == 'update') {


    $validate['required'] = array(
        //  'inv_ref_no' => ' Invoice No. is required',
        'inv_store_id' => ' Store is required',
        'inv_billing_name' => ' Name is required',
//        'inv_billing_email' => ' Email is required',
        'inv_billing_contact1' => ' Contact1 is required',
        'inv_billing_address1' => ' Address1 is required',
        'inv_billing_city' => ' City is required',
//        'inv_billing_state' => ' State is required',
//        'inv_billing_country' => ' Country is required',
        'inv_billing_postal' => ' Postal is required',
//        'inv_shipping_name' => ' Name is required',
//        'inv_shipping_contact1' => ' Contact1 is required',
//        'inv_shipping_address1' => ' Address1 is required',
//        'inv_shipping_city' => ' City is required',
//        'inv_shipping_state' => ' State is required',
//        'inv_shipping_country' => ' Country is required',
//        'inv_shipping_postal' => ' Postal is required',
        'inv_payment_method' => ' Payment Method is required',
        'inv_payment_status' => ' Payment Status is required',
        'inv_delivery_date' => ' Delivery Date is required',
        'inv_delivery_mode' => ' Delivery Mode is required',
        'inv_delivery_specify' => ' Delivery Specity is required',
        'inv_generate_date' => ' Generate Date is required');

    $j_msg = validate($validate, $_POST);
    if (count($j_msg) > 0) {
        $errormsg = implode("<br>", $j_msg);
        echo "E##" . $errormsg;
        exit(0);
        $j_msg_class = 'danger';
    } else {




        if ($inv_customers_id == 0) {
            $arr_input = array();
            $customers_firstname = $customers_lastname = "";
            if ($inv_billing_name != "") {
                $arr_name = explode(" ", $inv_billing_name);

                if (count($arr_name) > 1) {
                    $customers_firstname = $arr_name[0];
                    $customers_lastname = $arr_name[1];
                } else {
                    $customers_firstname = $customers_lastname = $arr_name[0];
                }
            }

            $arr_input["table"] = "customers";
            $arr_input["data"] = array("customers_firstname" => $customers_firstname,
                "customers_lastname" => $customers_lastname,
                "customers_email_address" => $inv_billing_email,
                "customers_contact_1" => $inv_billing_contact1,
                "customers_contact_2" => $inv_billing_contact2,
                "customers_address_1" => $inv_billing_address1,
                "customers_address_2" => $inv_billing_address2,
                "customers_city" => $inv_billing_city,
                "customers_state" => $inv_billing_state,
                "customers_country" => $inv_billing_country,
                "customers_postalcode" => $inv_billing_postal,
                "customers_password" => "2016",
                "customers_status" => 1,
                "customers_admin_id" => session_get("admin_id"),
                "customers_date" => $cur_date,
            );

            $arr_return = add_record_to_database($arr_input);

            if ($arr_return["errormsg"] == "") {
                $inv_customers_id = $arr_return["id"];
            } else {
                $errormsg = $arr_return["errormsg"];
                echo "E##" . $errormsg;
                exit(0);
            }
        }

        if ($act == 'update') {
            $old_ref_no = $inv_ref_no;
            $inv_ref_no = randomPrefix(20);
            $old_inv_id = $id;
        }
        if ($errormsg == '') {
            $invoice = new invoice();
            $invoice->data["inv_store_id"] = $inv_store_id;
            $invoice->data["inv_customers_id"] = $inv_customers_id;
            $invoice->data["inv_ref_no"] = $inv_ref_no;
            $invoice->data["inv_billing_name"] = $inv_billing_name;
            $invoice->data["inv_billing_email"] = $inv_billing_email;
            $invoice->data["inv_type"] = $inv_type;
            $invoice->data["inv_billing_contact1"] = $inv_billing_contact1;
            $invoice->data["inv_billing_contact2"] = $inv_billing_contact2;
            $invoice->data["inv_billing_address1"] = $inv_billing_address1;
            $invoice->data["inv_billing_address2"] = $inv_billing_address2;
            $invoice->data["inv_billing_city"] = $inv_billing_city;
            $invoice->data["inv_billing_state"] = $inv_billing_state;
            $invoice->data["inv_billing_country"] = $inv_billing_country;
            $invoice->data["inv_billing_postal"] = $inv_billing_postal;
            if ($chkshippingdiff == 0) { // means Billing and shipping info is the same
                $invoice->data["inv_shipping_name"] = $inv_billing_name;
                $invoice->data["inv_shipping_contact1"] = $inv_billing_contact1;
                $invoice->data["inv_shipping_contact2"] = $inv_billing_contact2;
                $invoice->data["inv_shipping_address1"] = $inv_billing_address1;
                $invoice->data["inv_shipping_address2"] = $inv_billing_address2;
                $invoice->data["inv_shipping_city"] = $inv_billing_city;
                $invoice->data["inv_shipping_state"] = $inv_billing_state;
                $invoice->data["inv_shipping_country"] = $inv_billing_country;
                $invoice->data["inv_shipping_postal"] = $inv_billing_postal;
            } else {  // billing and shipping info are the different one
                $invoice->data["inv_shipping_name"] = $inv_shipping_name;
                $invoice->data["inv_shipping_contact1"] = $inv_shipping_contact1;
                $invoice->data["inv_shipping_contact2"] = $inv_shipping_contact2;
                $invoice->data["inv_shipping_address1"] = $inv_shipping_address1;
                $invoice->data["inv_shipping_address2"] = $inv_shipping_address2;
                $invoice->data["inv_shipping_city"] = $inv_shipping_city;
                $invoice->data["inv_shipping_state"] = $inv_shipping_state;
                $invoice->data["inv_shipping_country"] = $inv_shipping_country;
                $invoice->data["inv_shipping_postal"] = $inv_shipping_postal;
            }

            $invoice->data["inv_payment_method"] = $inv_payment_method;
            $invoice->data["inv_payment_status"] = $inv_payment_status;
            $invoice->data["inv_payment_notes"] = $inv_payment_notes;
            $invoice->data["inv_delivery_date"] = convert_disp_to_db_date($inv_delivery_date);
            $invoice->data["inv_delivery_specify"] = $inv_delivery_specify;
            $invoice->data["inv_delivery_mode"] = $inv_delivery_mode;
            $invoice->data["inv_delivery_notes"] = $inv_delivery_notes;
            $invoice->data["inv_shipping_amount"] = $inv_shipping_amount;
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
                $arr_amount = array("inv_total_amount" => 0, "inv_net_amount" => 0, "inv_total_paid_amount" => 0);
                // add code to insert entry for invoice product
                if (isset($product_name) && is_array($product_name)) {
                    $ip = 0;
                    foreach ($product_name as $key => $value) {
                        if ($ip != 0 && (int) $product_name[$ip] > 0) {
                            $arr_input_invoice_product = array();
                            $arr_input_invoice_product["invpro_inv_id"] = $inv_id;
                            $arr_input_invoice_product["invpro_pro_id"] = $product_name[$ip];
                            $arr_input_invoice_product["invpro_pro_qty"] = $product_qty[$ip];
                            $arr_input_invoice_product["invpro_final_pro_price"] = $product_price[$ip];
                            $arr_input_invoice_product["invpro_final_price_tot"] = $product_net_price[$ip];
                            $arr_amount["inv_total_amount"] += $product_net_price[$ip];
                            $arr_amount["inv_net_amount"] += $product_net_price[$ip];
                            $arr_amount["inv_total_paid_amount"] += $product_net_price[$ip];
                            $arr_invoice_product = add_invoice_product($arr_input_invoice_product);
                            if ($arr_invoice_product != '') {
                                $errormsg = $arr_invoice_product;
                                echo "E##" . $errormsg;
                                exit(0);
                                break;
                            } else {
                                $arr_update_product = array("pro_id" => $product_name[$ip], "pro_qty" => $product_qty[$ip]);
                                update_product_qty($arr_update_product);
                            }
                        }
                        $ip++;
                    }
                }
                $arr_amount["inv_id"] = $inv_id;
                $arr_amount["inv_shipping_amount"] = $inv_shipping_amount;

                update_invoice_amount($arr_amount);

                if ($errormsg == '' && $act == 'update') {
                    $edit_existing_order = update_exiting_remove_older_invoice_data($new_inv_id, $old_inv_id, $old_ref_no);
                    if ($edit_existing_order  !='')
                    {
                         echo "E##" . $edit_existing_order; exit(0);
                    }
                }

                // end of code adding entry for invoice product
                // Add log entry
               
//                if ($act = "update")
//                {  m_print($_POST); echo "**".$add_more."**"; exit(0); }
                if ($add_more == 1) {
                    echo "S##SaveandAdd";
                    //header('Location:add_edit_invoice.php?msg=2');
                    exit(0);
                } else {
                    echo "S##Next##manage_invoice.php?msg=3&page=1&per_page=" . $per_page;
                    // If success then redirect to manage user page
                    // header('Location:manage_invoice.php?msg=2&page=1&per_page=' . $per_page);
                    exit(0);
                }
            }
        }
    }
}
$dd_products = "";
if ($id != 0) {
    $arr_products_key_value = get_product_as_array(1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
        <meta http-equiv="Pragma" content="no-cache" />
        <title><?php echo $page_title; ?></title>
<?php include("includes/include_files.php"); ?>
<?php
$data_arr_input = array();
$data_arr_input['select_field'] = 'pro_name ,pro_id';
$data_arr_input['table'] = 'products';
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
?>
    </head>
    <body class="<?php echo ADMIN_SKIN; ?> box sidebar-mini">

        <div class="overlay" id="m_process">
            <i class="fa fa-refresh fa-spin"></i>
            Please wait, request is under process
        </div>
        <div class="wrapper">
<?php include("includes/header.php"); ?>
<?php include("includes/left_menu.php"); ?>
            <!-- our page -->

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
<?php include("includes/breadcrumb.php"); ?>

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
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <!--                                 onsubmit="return validate_data_ajax();" -->
                                <form name="frmAddEditInvoice" id="frmAddEditInvoice" method="post" class="form-horizontal form-employee" onsubmit="return validate_data_ajax();">
                                    <input type="hidden" id="add_more" name="add_more" value="" />
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="inv_customers_id" name="inv_customers_id" value="0" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="inv_ref_no" name="inv_ref_no" value="<?php echo $inv_ref_no; ?>" />
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12 center">
                                                <p class="lead text-center mid_caption">Personal Details</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Invoice Type</label>
                                                    <label class="col-sm-9" style="padding-top:7px; font-weight: normal;">
                                                        <select id="inv_type" name="inv_type" class="form-control inv_product" >
                                                            <option <?php if ($inv_type == 'Cash') echo 'selected="selected"'; ?>  value="Cash">Cash</option>
                                                            <option <?php if ($inv_type == 'Other') echo 'selected="selected"'; ?> value="Other">Other</option></select>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Store</label>
                                                    <label class="col-sm-9" style="padding-top:7px; font-weight: normal;"><?php echo session_get("admin_store_name"); ?></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Invoice Date<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_generate_date" id="inv_generate_date"  placeholder="Invoice Date" value="<?php echo $inv_generate_date; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Name<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_billing_name" id="inv_billing_name"  placeholder="Name" value="<?php echo $inv_billing_name; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Email</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_billing_email" id="inv_billing_email"  placeholder="Email" value="<?php echo $inv_billing_email; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Phone No.<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_billing_contact1" id="inv_billing_contact1"  placeholder="Phone No" value="<?php echo $inv_billing_contact1; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Street Address<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_billing_address1" id="inv_billing_address1"  placeholder="Street Address" value="<?php echo $inv_billing_address1; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Suburb<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_billing_city" id="inv_billing_city"  placeholder="Suburb" value="<?php echo $inv_billing_city; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Postal<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="inv_billing_postal" id="inv_billing_postal"  placeholder="Postal" value="<?php echo $inv_billing_postal; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">&nbsp;</div>
                                            <div class="col-md-6" style="padding-bottom: 7px;">
                                                <input type="checkbox" class="" <?php if ($chkshippingdiff == 1) echo 'checked="checked"'; ?> id="chkshippingdiff" value="1"  name="chkshippingdiff"/> <b><label for="chkshippingdiff" class="lead mid_caption_200">Is Delivery Address is different from Billing Address?</label></b>
                                            </div>

                                        </div>
                                        <div id="shipping_info_div" <?php if ($chkshippingdiff == 0) echo 'style="display:none;"'; ?> >
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Name<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="inv_shipping_name" id="inv_shipping_name"  placeholder="Name" value="<?php echo $inv_shipping_name; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Phone No<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="inv_shipping_contact1" id="inv_shipping_contact1"  placeholder="Phone No" value="<?php echo $inv_shipping_contact1; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Street Address<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="inv_shipping_address1" id="inv_shipping_address1"  placeholder="Street Address" value="<?php echo $inv_shipping_address1; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Suburb<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="inv_shipping_city" id="inv_shipping_city"  placeholder="Suburb" value="<?php echo $inv_shipping_city; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Postal<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="inv_shipping_postal" id="inv_shipping_postal"  placeholder="Postal" value="<?php echo $inv_shipping_postal; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="section_product">



                                            <div class="row">
                                                <div class="col-md-12 center">
                                                    <p class="lead text-center mid_caption">Invoice Products <input type="button" value=" Add More" class="btn btn-success" onclick="product_invoice_append()" /></p>
                                                </div>
                                            </div>


                                            <div class="row product-options mb5" id="product_option_0" style="display:none;">

                                                <div class="col-md-12 parent_class">
                                                    <div class="col-md-2"><input type="text" id="product_barcode" name="product_barcode[]" class="form-control inv_pro_barcode" placeholder="Barcode" /></div>
                                                    <div class="col-md-3"><select  id="product_name" name="product_name[]" class="form-control inv_product" placeholder="Product Name">
                                                            <option value="0">--Please Select--</option><?php echo $dd_products; ?>
                                                        </select></div>
                                                    <div class="col-md-1"><input type="text" id="product_qty" name="product_qty[]" class="form-control inv_qty" placeholder="Qty"  /></div>
                                                    <div class="col-md-2"><input type="text" id="product_price" name="product_price[]" class="form-control inv_pro_price" readonly="readonly" placeholder="Price" value="" /></div>
                                                    <div class="col-md-2"><input type="text" id="product_net_price" name="product_net_price[]" class="form-control pro_net_price money_js" placeholder="Net Price" /></div>
                                                    <div class="col-md-2"><input type="button" value="Remove" class="btn btn-danger" onclick="product_remove_invoice(this)" />  </div>
                                                </div>
                                            </div>
<?php if ($id == 0) { ?>
                                                <div class="row product-options mb5" id="product_option_1" >

                                                    <div class="col-md-12 parent_class">
                                                        <div class="col-md-2"><input type="text" id="product_barcode" name="product_barcode[]" class="form-control inv_pro_barcode" placeholder="Barcode" /></div>
                                                        <div class="col-md-3"><select  id="product_name" name="product_name[]" class="form-control inv_product" placeholder="Product Name"><option value="0">--Please Select--</option><?php echo $dd_products; ?></select></div>
                                                        <div class="col-md-1"><input type="text" id="product_qty" name="product_qty[]" class="form-control inv_qty" placeholder="Qty" /></div>
                                                        <div class="col-md-2"><input type="text" id="product_price" name="product_price[]" class="form-control" readonly="readonly" placeholder="Price" /></div>
                                                        <div class="col-md-2"><input type="text" id="product_net_price" name="product_net_price[]" class="form-control pro_net_price money_js" placeholder="Net Price" /></div>
                                                        <div class="col-md-2"><input type="button" value="Remove" class="btn btn-danger" onclick="product_remove_invoice(this)" />  </div>
                                                    </div>
                                                </div>
<?php } ?>

<?php
if ($id != 0) {
    $q_invoice_products = "SELECT invpro_pro_id, invpro_pro_name ,invpro_pro_qty,invpro_final_pro_price ,invpro_final_price_tot  FROM invoice_products  WHERE  invpro_inv_id= " . $id;
    $res_invoice_products = m_process("get_data", $q_invoice_products);

    if ($res_invoice_products["errormsg"] == "" && $res_invoice_products["count"] > 0) {
        $i = 0;
        foreach ($res_invoice_products["res"] as $products_row) {
            $i++;
            ?>
                                                        <div class="row product-options mb5" id="product_option_<?php echo $i; ?>" >

                                                            <div class="col-md-12 parent_class">

                                                                <div class="col-md-2"><input type="text" id="product_barcode" name="product_barcode[]" class="form-control inv_pro_barcode" placeholder="Barcode" /></div>
                                                                <div class="col-md-3"><select  id="product_name" name="product_name[]" class="form-control inv_product" placeholder="Product Name">
                                                                        <option value="0">--Please Select--</option><?php display_dd_options_from_array($arr_products_key_value, $products_row['invpro_pro_id']); ?>
                                                                    </select></div>
                                                                <div class="col-md-1"><input type="text" id="product_qty" name="product_qty[]" class="form-control inv_qty" placeholder="Qty" value="<?php echo $products_row['invpro_pro_qty']; ?>"  /></div>
                                                                <div class="col-md-2"><input type="text" id="product_price" name="product_price[]" class="form-control inv_pro_price" readonly="readonly" placeholder="Price" value="<?php echo $products_row['invpro_final_pro_price']; ?>"/></div>
                                                                <div class="col-md-2"><input type="text" id="product_net_price" name="product_net_price[]" class="form-control pro_net_price money_js" placeholder="Net Price" value="<?php echo $products_row['invpro_final_price_tot']; ?>" /></div>
                                                                <div class="col-md-2"><input type="button" value="Remove" class="btn btn-danger" onclick="product_remove_invoice(this)" />  </div>


                                                            </div>
                                                        </div>
            <?php
        }
    }
}
?>

                                            <div class="row">
                                            </div>
                                            <div class="row">
                                            </div>
                                            <div class="row">
                                            </div>
                                            <div class="row" >
                                                <div class="col-md-12 center">
                                                    <p class="lead text-right mid_caption">Total: $<span id="grand_total"><?php echo $inv_total_amount; ?></span></p>
                                                </div>
                                            </div>

                                        </div>
                                        <div id="section_delivery">

                                            <div class="row">
                                                <div class="col-md-12 center">
                                                    <p class="lead text-center mid_caption">Delivery Details</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Date<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="inv_delivery_date" id="inv_delivery_date"  placeholder="Date" value="<?php echo $inv_delivery_date; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Option<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <select name="inv_delivery_mode" id="inv_delivery_mode"   class="form-control" onchange="delivery_mode_change()" >
<?php
display_dd_options_from_array($arr_delivery_mode, $inv_delivery_mode);
?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Notes</label>
                                                        <div class="col-sm-9">
                                                            <textarea id="inv_delivery_notes" name="inv_delivery_notes" class="form-control" > <?php echo $inv_delivery_notes; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="delivery_specify" <?php
if ($inv_delivery_mode == "Delivery") {
    echo '';
} else {
    echo 'style="display:none;"';
}
?>>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Specify<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <select id="inv_delivery_specify" name="inv_delivery_specify"  class="form-control"  >
<?php
display_dd_options_from_array($arr_delivery_specify, $inv_delivery_specify);
?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Shipping Amount<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="inv_shipping_amount" id="inv_shipping_amount"  placeholder="Shipping Amount" value="<?php echo $inv_shipping_amount; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="section_payment">
                                            <div class="row">
                                                <div class="col-md-12 center">
                                                    <p class="lead text-center mid_caption">Payment Details</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Status<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <select name="inv_payment_status" id="inv_payment_status"   class="form-control" >
<?php
display_dd_options_from_array($arr_payment_status, $inv_payment_status);
?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Method<span class="req">*</span></label>
                                                        <div class="col-sm-9">
                                                            <select name="inv_payment_method" id="inv_payment_method"   class="form-control" >
<?php
display_dd_options_from_array($arr_payment_method, $inv_payment_method);
?>
                                                            </select>

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
                                            <div class="col-md-12 center">
                                                <p class="lead text-center mid_caption">Total: $<span id="clone_final_price"><?php echo ($inv_net_amount+$inv_shipping_amount); ?></span> </p>
                                            </div>
                                        </div>



                                    </div><!-- /.box-body -->
                                    <div class="box-footer">

                                        <input type="button" value="Cancel" class="btn btn-default leftbtn" id="btnResetInvoice" name="btnResetInvoice" onclick="window.location.href = 'manage_invoice.php'"/>
                                        <button type="button" class="ml5 btn btn-info pull-right leftbtn marginl0" id="btnAddInvoice" name="btnAddInvoice"><?php echo $caption; ?></button>
<?php if ($id == 0) { ?>
                                            <button type="button" class="btn btn-info pull-right leftbtn" id="btnSaveandAdd" name="btnSaveandAdd">Save and Add Another Invoice</button>
<?php } ?>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div><!-- /.box -->
                            <!-- general form elements disabled -->
                        </div>
                    </div>
                </section>
            </div>
            <!-- end of our page-->
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#frmAddEditInvoice').bootstrapValidator({
                        message: 'This value is not valid',
                        submitButton: 'button[type="submit"]',
                        live: 'disabled',
                        excluded: ':disabled',
                        feedbackIcons: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {
                            inv_store_id: {
                                message: 'Select Store',
                                validators: {
                                    notEmpty: {
                                        message: 'Store is required and please choose store'
                                    }
                                }
                            },
//                            inv_ref_no: {
//                                message: 'Invoice No. is not valid',
//                                validators: {
//                                    notEmpty: {
//                                        message: 'Invoice No. is required and cannot be empty'
//                                    }
//                                }
//                            },
                            inv_billing_name: {
                                message: 'Name is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Name is required and cannot be empty'
                                    }
                                }
                            }, inv_billing_contact1: {
                                message: 'Phone No is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Phone No is required and cannot be empty'
                                    }
                                }
                            }, inv_billing_address1: {
                                message: 'Street Address is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Street Address is required and cannot be empty'
                                    }
                                }
                            }, inv_billing_city: {
                                message: 'Suburb is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Suburb is required and cannot be empty'
                                    }
                                }
                            }, inv_billing_postal: {
                                message: 'Postal is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Postal is required and cannot be empty'
                                    }
                                }
                            }, inv_payment_method: {
                                message: 'Payment Method is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Payment Method is required and cannot be empty'
                                    }
                                }
                            }, inv_payment_status: {
                                message: 'Payment Status is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'Payment Status is required and cannot be empty'
                                    }
                                }
                            },
                            inv_shipping_name: {
                                enabled: false,
                                validators: {
                                    notEmpty: {
                                        message: 'The shipping name is required'
                                    }
                                }
                            },
                            inv_shipping_contact1: {
                                enabled: false,
                                validators: {
                                    notEmpty: {
                                        message: 'The phone no is required'
                                    }
                                }
                            },
                            inv_shipping_address1: {
                                enabled: false,
                                validators: {
                                    notEmpty: {
                                        message: 'The address is required'
                                    }
                                }
                            },
                            inv_shipping_city: {
                                enabled: false,
                                validators: {
                                    notEmpty: {
                                        message: 'The city is required'
                                    }
                                }
                            },
                            inv_shipping_postal: {
                                enabled: false,
                                validators: {
                                    notEmpty: {
                                        message: 'The postal is required'
                                    }
                                }
                            },
                            inv_delivery_date: {
                                enabled: false,
                                validators: {
                                    notEmpty: {
                                        message: 'Delivery Date is required'
                                    }
                                }
                            }

                        }
                    }).on('success.field.bv', function (e, data) {
                        // If the field is empty
                        if (data.element.val() === '') {
                            var $parent = data.element.parents('.form-group');
                            $parent.removeClass('has-success');
                            data.element.data('bv.icon').hide();
                        }
                    }).on('inv_payment_method', function (e, data) {
                        // If the field is empty
                        if (data.element.val() === '') {
                            var $parent = data.element.parents('.form-group');
                            $parent.removeClass('has-success');
                            data.element.data('bv.icon').hide();
                        }
                    })
                            .on('change', '[name="chkshippingdiff"]', function (e) {
                                if ($("#chkshippingdiff").is(':checked')) {
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_name', true);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_contact1', true);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_address1', true);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_city', true);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_postal', true);
                                }
                                else {
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_name', false);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_contact1', false);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_address1', false);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_city', false);
                                    $('#frmAddEditInvoice').bootstrapValidator('enableFieldValidators', 'inv_shipping_postal', false);
                                }



                            })
                    page_after_process('frmAddEditInvoice', 'btnAddInvoice', 'btnSaveandAdd', 'add_edit_invoice.php');
                });

                $("#inv_generate_date").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    endDate: '+d'
                });

                $("#inv_delivery_date").datepicker({
                    format: 'dd-mm-yyyy',
                    startDate: new Date(),
                    autoclose: true, }).on('changeDate', function (ev) {
                    $('#frmAddEditInvoice').formValidation('revalidateField', 'inv_delivery_date');
                });

                $(".money_js").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            // Allow: Ctrl+A, Command+A
                                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                    // Allow: home, end, left, right, down, up
                                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                                // let it happen, don't do anything
                                return;
                            }
                            // Ensure that it is a number and stop the keypress
                            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                e.preventDefault();
                            }
                        });

            </script>
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>