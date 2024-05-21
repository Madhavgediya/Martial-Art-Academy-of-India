<?php
include("includes/application_top.php");
$page_type = get_rdata("type", "");
$id = get_rdata("id", 0);
$act = get_rdata("act");

if ($page_type == "Purchase") {
    include("../includes/class/invoice.php");
    $caption = "Print Purchase";
    $btn_caption = "Print Purchase";
    $purchaser_caption = "Dealer";
    $caption = "Print Purchase";
    $btn_caption = "Print Purchase";
    $table_invoice = "sm_invoice";
    $table_invoice_products = "sm_invoice_products";
    $manage_url = "manage_purchase.php";
} else {
    include("../includes/class/invoice_sale.php");
    $caption = "Print Sale";
    $btn_caption = "Print Sale";
    $purchaser_caption = "Student";
    $caption = "Print Sale";
    $btn_caption = "Print Sale";
    $table_invoice = "sm_invoice_sale";
    $table_invoice_products = "sm_invoice_products_sale";
    $manage_url = "manage_sale.php";
}
$bln_dealear = false;
$bln_dealear_igst = false;
$purchase_inv_ids = "";
if ($id != 0) {
    $bln_dealear = true;
}

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
$inv_net_amount = $inv_total_amount = $inv_igst_amount = $inv_gst_amount = $inv_cgst_amount = $inv_sgst_amount = 0;
$invpro_id = get_rdata('invpro_id');
$inv_status = get_rdata('inv_status');
$inv_payment_status = get_rdata('inv_payment_status');
$inv_purchase_invoice_no = get_rdata('inv_purchase_invoice_no');
$inv_additional_amount = get_rdata('inv_additional_amount', 0);
$inv_discount_amount = get_rdata('inv_discount_amount');
$inv_payment_notes = get_rdata('inv_payment_notes');
$inv_payment_method = get_rdata('inv_payment_method');
$inv_shipping_amount = get_rdata('inv_shipping_amount', 0);
$inv_generate_date = get_rdata('inv_generate_date', $cur_date_only);
$inv_generate_admin_id = $admin_id;
$inv_date = $cur_date_only;
$inv_admin_id = $admin_id;
$inv_additional_amount = ($inv_additional_amount == "") ? 0 : $inv_additional_amount;
$inv_discount_amount = ($inv_discount_amount == "") ? 0 : $inv_discount_amount;


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
    $successmsg = $page_type . " has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT * FROM  $table_invoice WHERE inv_id = " . $id;
    if ($page_type == "Purchase") {
        $invoice = new invoice();
    } else {
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
                if (get_dealer_gst_type($inv_purchase_del_id) == 'Y') {
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
$dd_products = "";
if ($id != 0) {
    $arr_products_key_value = get_product_as_array(1, '', $page_type);
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
        <style>
            .addtional_label { text-align:left !important; font-weight:normal; }
            .cbt { border:1px solid #f4f4f4; }
            .cbb { border:1px solid #f4f4f4; }
            .cbl { border:1px solid #f4f4f4; }
            .cbr { border:1px solid #f4f4f4; }
        </style>
        <?php
        if ($page_type == "Purchase") {
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
        } else {
            $c_where = '';
            if ($id == 0)
                $c_where = ' invpro_used = "NA" AND inv_status = "G" AND (invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead) > 0 AND inv_admin_id = ' . $tmp_admin_id;
            else
                $c_where = ' invpro_used = "NA" AND inv_status = "G" AND (invpro_pro_qty - invpro_pro_qty_dead) > 0 AND inv_admin_id = ' . $tmp_admin_id;

            $data_arr_input = array();
            $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2,'##',invpro_final_pro_price,'##',invpro_pro_qty) as mid  ";
            $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2) as mid  ";
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

        $dd_office_used = display_dd_options_from_array_old_return($product_used_array, "");
        ?>
    </head>
    <body class="skin-green sidebar-mini">
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $purchaser_caption; ?></label>
                                                    <label class="col-sm-9 control-label addtional_label" id="inv_purchase_del_id_label">
                                                        <select required id="inv_purchase_del_id" onchange="check_dealer_igst();" name="inv_purchase_del_id" class="form-control select2">
                                                            <option value="">-Please select-</option>
<?php
if ($page_type == 'Purchase') {
    $data_arr_input = array();
    $data_arr_input['select_field'] = 'del_company_name ,del_id';
    $data_arr_input['table'] = 'sm_dealer';
    $data_arr_input['where'] = " del_br_id = " . $tmp_admin_id . " AND del_status  = 'A' ";
    $data_arr_input['key_id'] = 'del_id';
    $data_arr_input['key_name'] = 'del_company_name';
    $data_arr_input['current_selection_value'] = $inv_purchase_del_id;
    $data_arr_input['order_by'] = 'del_company_name';
    display_dd_options($data_arr_input);
} else {
    $data_arr_input = array();
    $data_arr_input['select_field'] = " CONCAT(stu_gr_no,'-',stu_first_name,' ', stu_last_name) as stu_name, stu_id ";
    $data_arr_input['table'] = 'sm_student';
    $data_arr_input['where'] = " stu_br_id = " . $tmp_admin_id;
    $data_arr_input['key_id'] = 'stu_id';
    $data_arr_input['key_name'] = 'stu_name';
    $data_arr_input['current_selection_value'] = $inv_purchase_del_id;
    $data_arr_input['order_by'] = ' stu_gr_no, stu_first_name, stu_last_name';
    display_dd_options($data_arr_input);
}
?>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $page_type; ?> Date</label>
                                                    <div class="col-sm-9">
                                                        <label class="col-sm-9 addtional_label control-label"><?php echo $inv_generate_date; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Payment Terms</label>
                                                    <div class="col-sm-9" > 
                                                        <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label">
<?php echo selected_value_return($arr_payment_status, $inv_payment_status); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $page_type; ?> Invoice No</label>
                                                    <div class="col-sm-9">
                                                        <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $inv_purchase_invoice_no; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>
                                                <div class="row">
                                                    <div class="col-md-12 center">
                                                        <p class="lead text-center mid_caption">Products Details</p>
                                                    </div>
                                                </div>
                                                <div id="section_product">
                                                    <div class="row" id="" style="" >
                                                        <div class="col-md-12 parent_class">

<?php
if ($page_type == 'Purchase') {
    ?>
                                                                <div class="col-md-2 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Name</div>
                                                                <div class="col-md-2  cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Size</div>
                                                                <div class="col-md-2  cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Color</div>
    <?php
} else {
    ?>
                                                                <div class="col-md-7 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Name</div>
    <?php
}
?>
                                                            <?php if ($page_type == 'Purchase') { ?>
                                                                <div class="col-md-1  cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Office/<br>Reselling</div>
                                                            <?php } ?>
                                                            <div class="col-md-1 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Quantity</div>
                                                            <div class="col-md-1 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Price</div>
                                                            <div class="col-md-1 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">Net Price</div>
                                                            <div class="col-md-1 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">GST (%)</div>
                                                        </div>
                                                    </div>
                                                            <?php
                                                            if ($id != 0) {

                                                                $q_invoice_products = "SELECT  invpro_pro_qty_return, invpro_pro_qty_dead, invpro_pro_qty_sold , invpro_pro_id, invpro_id_purchase, invpro_sgst_amount, invpro_cgst_amount, invpro_po_id,invpro_po_id_2, invpro_used, invpro_gst, invpro_inv_id,invpro_id, invpro_po_id ,invpro_pro_qty,invpro_final_pro_price ,invpro_final_price_tot  FROM " . $table_invoice_products . "  WHERE  invpro_inv_id= " . $id;
                                                                $res_invoice_products = m_process("get_data", $q_invoice_products);

                                                                if ($res_invoice_products["errormsg"] == "" && $res_invoice_products["count"] > 0) {
                                                                    $i = 0;
                                                                    $product_option_class = '';

                                                                    foreach ($res_invoice_products["res"] as $products_row) {
                                                                        $i++;
                                                                        ?>
                                                                <div class="row product-options" id="div_product_option_<?php echo $i; ?>" >
                                                                    <div class="col-md-12 parent_class">
                                                                <?php if ($page_type == 'Purchase') { ?>
                                                                            <div class="col-md-2 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">
                                                                                <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo selected_value_return($arr_products_key_value, $products_row['invpro_pro_id']); ?></label>
                                                                            </div>
                                                                            <div class="col-md-2 cbt cbb cbl cbr"  style="padding-left:2px; padding-right:2px;">
                                                                                <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo selected_value_return($arr_products_options_key_value, $products_row['invpro_po_id']); ?></label>
                                                                            </div>
                                                                            <div class="col-md-2 cbt cbb cbl cbr"  style="padding-left:2px; padding-right:2px;">
                                                                                <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo selected_value_return($arr_products_options_key_value, $products_row['invpro_po_id_2']); ?></label>
                                                                            </div>
                                                                <?php } else {
                                                                    ?>
                                                                            <div class="col-md-7 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">
                                                                                <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo selected_value_return($arr_products_key_value, $products_row['invpro_id_purchase'] . '##' . $products_row['invpro_pro_id'] . '##' . $products_row['invpro_po_id'] . '##' . $products_row['invpro_po_id_2']); ?></label>
                                                                            </div>
                                                                        <?php } ?>

            <?php if ($page_type == 'Purchase') { ?>
                                                                            <div class="col-md-1 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;">
                                                                                <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo selected_value_return($product_used_array, $products_row['invpro_used']); ?></label>
                                                                            </div>
            <?php } ?>
                                                                        <div class="col-md-1 cbt cbb cbl cbr"  style="padding-left:2px; padding-right:2px;"><label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $products_row['invpro_pro_qty']; ?></label></div>
                                                                        <div class="col-md-1 cbt cbb cbl cbr"  style="padding-left:2px; padding-right:2px;"><label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $products_row['invpro_final_pro_price']; ?></label></div>
                                                                        <div class="col-md-1 cbt cbb cbl cbr"  style="padding-left:2px; padding-right:2px;"><label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $products_row['invpro_final_price_tot']; ?></label></div>
                                                                        <div class="col-md-1 cbt cbb cbl cbr" style="padding-left:2px; padding-right:2px;"><label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $products_row['invpro_gst']; ?></label></div>

                                                                    </div>
                                                                </div>
            <?php
        }
        echo '<script type="text/javascript"> var next_id= ' . ($i + 1) . ';' . $product_option_class . ' </script>';
    }
}
?>
                                                </div>
                                                <div id="section_payment">
                                                    <div class="row">
                                                        <div class="col-md-12 center">
                                                            <p class="lead text-center mid_caption">Other Details</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">Addition Amount</label>

                                                                <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $inv_additional_amount; ?></label>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">Discount<span class="req">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <label class="col-sm-9 addtional_label control-label" id="inv_payment_status_label"><?php echo $inv_discount_amount; ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
<?php if ($inv_payment_notes != '') { ?>
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Notes</label>
                                                                    <div class="col-sm-9">
    <?php echo nl2br($inv_payment_notes); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
<?php } ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 right">
                                                        <p style="margin-bottom:0px; font-size:18px;" class="lead text-right mid_caption">Sub Total: <span id="grand_total"><?php echo $inv_total_amount; ?></span> INR</p>
                                                        <p style="margin-bottom:0px; font-size:18px;" id="" class="lead text-right mid_caption">Additional Amount: <span id="clone_additional_amount"><?php echo ($inv_additional_amount); ?></span> INR</p>
                                                        <p style="margin-bottom:0px; font-size:18px;" id="" class="lead text-right mid_caption">Discount Amount: <span id="clone_discount_amount"><?php echo ($inv_discount_amount); ?></span> INR</p>
                                                        <p style="margin-bottom:0px; font-size:18px;" id="main_final_igst" class="lead text-right mid_caption">Total IGST: <span id="clone_final_igst"><?php echo ($inv_cgst_amount + $inv_sgst_amount); ?></span> INR</p>
                                                        <p style="margin-bottom:0px; font-size:18px;" id="main_final_cgst" class="lead text-right mid_caption">Total CGST: <span id="clone_final_cgst"><?php echo ($inv_cgst_amount); ?></span> INR</p>
                                                        <p style="margin-bottom:0px; font-size:18px;" id="main_final_sgst" class="lead text-right mid_caption">Total SGST: <span id="clone_final_sgst"><?php echo ($inv_sgst_amount); ?></span> INR</p>
                                                        <p style="margin-bottom:0px; font-size:18px;" class="lead text-right mid_caption">Total: <span id="clone_final_price"><?php echo ($inv_net_amount); ?></span> INR</p>
                                                    </div>
                                                </div>
                                                </div><!-- /.box-body -->
                                                <div class="box-footer">
                                                    <input type="button" value="Cancel" class="btn btn-default leftbtn" id="btnResetPurchase" name="btnResetPurchase" onclick="window.location.href = '<?php echo $manage_url; ?>'"/>
                                                    <button type="button" class="ml5 btn btn-info pull-right leftbtn marginl0" id="btnAddPurchase" name="btnAddPurchase" onclick="validate_data_invoice();"><?php echo $caption; ?></button>
                                                </div><!-- /.box-footer -->
                                                </div>
                                                </div><!-- /.box -->
                                                <!-- general form elements disabled -->
                                                </div></div>
                                                </section>
                                                </div>


<?php include("includes/footer.php"); ?>
<?php include("includes/add_product.php"); ?>
<?php include("includes/return_product.php"); ?>
<?php include("includes/add_product_option.php"); ?>
                                                <script type="text/javascript">
                                                    // function product_return_process()
                                                    // {
                                                    //     alert("MAYUR");
                                                    // }
                                                    $("#main_final_igst").hide();
                                                    $("#main_final_cgst").hide();
                                                    $("#main_final_sgst").hide();
<?php
if ($bln_dealear_igst == true) {
    ?>
                                                        $("#main_final_igst").show();
                                                    <?php
                                                } else {
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
                                                        if ($("#inv_additional_amount").val() != '' && !Number.isNaN($("#inv_additional_amount").val()) && $("#inv_additional_amount").val() > 0)
                                                        {
                                                            $("#clone_additional_amount").text($("#inv_additional_amount").val());
                                                        } else
                                                        {
                                                            $("#clone_additional_amount").text(0);
                                                        }
                                                        if ($("#inv_discount_amount").val() != '' && !Number.isNaN($("#inv_discount_amount").val()) && $("#inv_discount_amount").val() > 0)
                                                        {
                                                            $("#clone_discount_amount").text($("#inv_discount_amount").val());
                                                        } else
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

                                                    $("#inv_generate_date").datepicker({
                                                        format: 'dd-mm-yyyy',
                                                        autoclose: true, });

                                                    $("#inv_purchase_del_id").select2();
                                                    // console.log($("#inv_purchase_del_id").text());
                                                    // console.log($("#inv_purchase_del_id option:selected").html());
                                                    $("#inv_purchase_del_id_label").text($("#inv_purchase_del_id option:selected").html());
                                                    $("#inv_payment_status_label").text($("#inv_payment_status option:selected").html());
                                                    $("#inv_payment_status").remove();
                                                </script>
                                                </div>
                                                </body>
                                                </html>