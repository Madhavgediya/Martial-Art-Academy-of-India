<?php
include("includes/application_top.php");
$page_type = get_rdata("type", "");
$id = get_rdata("id", 0);
$act = get_rdata("act");
$inv_id = $inv_br_id = 0;
$btn_caption = $caption = "Invoice";


if ($page_type == "Purchase") {
    include("../includes/class/invoice.php");
    $purchaser_caption = "Dealer";
    $table_invoice = "sm_invoice";
    $table_invoice_products = "sm_invoice_products";
    $manage_url = "manage_purchase.php";
} else {
    include("../includes/class/invoice_sale.php");

    $purchaser_caption = "Student";

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
                $inv_id = $db_row['inv_id'];
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
                $inv_admin_id = $inv_br_id = $db_row['inv_admin_id'];
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

$from_address = $to_address = "";
if ($page_type == 'Purchase') {
    $from_address = "";
    $arr_disributor_details = get_distributor_details($inv_br_id, $inv_purchase_del_id);

    if ($arr_disributor_details["errormsg"] == "")
        $from_address = "<address><strong>" . $arr_disributor_details["name"] . "</strong><br>" . $arr_disributor_details["address"] . "</address>";

    $arr_branch_details = get_branch_details($inv_admin_id);
    $to_address = "";
    if ($arr_branch_details["errormsg"] == "")
        $to_address = "<address><strong>" . $arr_branch_details["name"] . "</strong><br>" . $arr_branch_details["address"] . "</address>";
}
else {
    $arr_branch_details = get_branch_details($inv_admin_id);
    $from_address = "";
    if ($arr_branch_details["errormsg"] == "")
        $from_address = "<address><strong>" . $arr_branch_details["name"] . "</strong><br>" . $arr_branch_details["address"] . "</address>";

    $arr_student_details = get_student_details($inv_br_id, $inv_purchase_del_id);
    $to_address = "";
    if ($arr_student_details["errormsg"] == "")
        $to_address = "<address><strong>" . $arr_student_details["name"] . "</strong><br>" . $arr_student_details["address"] . "</address>";
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
    $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name) as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2,'##',invpro_final_pro_price,'##',invpro_pro_qty) as mid  ";
    $data_arr_input['select_field'] = " CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name) as mname , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2) as mid  ";
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
                                <div name="form1" id="form1" method="post" class="form-horizontal form-employee" >
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row invoice-info">
                                                    <div class="col-sm-4 invoice-col">
                                                        From
<?php echo $from_address; ?>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-sm-4 invoice-col">
                                                        To
                                                        <address>
<?php echo $to_address; ?>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-sm-4 invoice-col">
                                                        <b>Invoice #<?php echo $inv_id; ?></b><br>

                                                            <b>Date:</b> <?php echo $inv_generate_date; ?>

                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
<?php if ($page_type == 'Purchase') { ?>
                                                                <th>Name</th>
                                                                <th>Size</th>
                                                                <th>Color</th>
                                                                <th>Office/<br>Reselling</th>
<?php } else { ?>
                                                                <th>Name</th>
<?php } ?>
                                                            <th>Quantity</th>
                                                            <th>Price</th>
                                                            <th>Net Price</th>
                                                            <th>GST (%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

<?php
$q_invoice_products = "SELECT  invpro_pro_qty_return, invpro_pro_qty_dead, invpro_pro_qty_sold , invpro_pro_id, invpro_id_purchase, invpro_sgst_amount, invpro_cgst_amount, invpro_po_id,invpro_po_id_2, invpro_used, invpro_gst, invpro_inv_id,invpro_id, invpro_po_id ,invpro_pro_qty,invpro_final_pro_price ,invpro_final_price_tot  FROM " . $table_invoice_products . "  WHERE  invpro_inv_id= " . $id;
$res_invoice_products = m_process("get_data", $q_invoice_products);

if ($res_invoice_products["errormsg"] == "" && $res_invoice_products["count"] > 0) {
    $i = 0;
    $product_option_class = '';

    foreach ($res_invoice_products["res"] as $products_row) {
        ?>
                                                                <tr>
                                                                <?php if ($page_type == 'Purchase') { ?>
                                                                        <td><?php echo selected_value_return($arr_products_key_value, $products_row['invpro_pro_id']); ?></td>
                                                                        <td><?php echo selected_value_return($arr_products_options_key_value, $products_row['invpro_po_id']); ?></td>
                                                                        <td><?php echo selected_value_return($arr_products_options_key_value, $products_row['invpro_po_id_2']); ?></td>
                                                                        <td><?php echo selected_value_return($product_used_array, $products_row['invpro_used']); ?></td>
        <?php } else { ?>
                                                                        <td><?php echo selected_value_return($arr_products_key_value, $products_row['invpro_id_purchase'] . '##' . $products_row['invpro_pro_id'] . '##' . $products_row['invpro_po_id'] . '##' . $products_row['invpro_po_id_2']); ?></td>
        <?php } ?>
                                                                    <td><?php echo $products_row['invpro_pro_qty']; ?></td>
                                                                    <td><?php echo $products_row['invpro_final_pro_price']; ?></td>
                                                                    <td><?php echo $products_row['invpro_final_price_tot']; ?></td>
                                                                    <td><?php echo $products_row['invpro_gst']; ?></td>
                                                                </tr>
    <?php }
} ?> 
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <div class="row">
                                            <!-- accepted payments column -->
                                            <div class="col-xs-6">
                                                <p class="lead">Payment Methods:</p>
<?php echo selected_value_return($arr_payment_status, $inv_payment_status); ?>
                                                <p><?php echo nl2br($inv_payment_notes); ?></p>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-xs-6">


                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <th style="width:50%">Subtotal:</th>
                                                                <td><?php echo $inv_total_amount; ?></span> INR</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Additional Amount:</th>
                                                                <td><?php echo ($inv_additional_amount); ?></span> INR</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Discount Amount:</th>
                                                                <td><?php echo ($inv_discount_amount); ?></span> INR</td>
                                                            </tr>
<?php
if ($page_type == 'Purchase') {
    if (get_dealer_gst_type($inv_purchase_del_id) == 'Y') {
        ?>
                                                                    ?>
                                                                    <tr>
                                                                        <th style="width:50%">IGST Amount:</th>
                                                                        <td><?php echo ($inv_cgst_amount + $inv_sgst_amount); ?></span> INR</td>
                                                                    </tr>
    <?php } else { ?>
                                                                    <tr>
                                                                        <th>CGST Amount:</th>
                                                                        <td><?php echo ($inv_cgst_amount); ?></span> INR</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>SGST Amount:</th>
                                                                        <td><?php echo ($inv_sgst_amount); ?></span> INR</td>
                                                                    </tr>
    <?php } ?>
                                                            <?php } else { ?>

                                                                <tr>
                                                                    <th>CGST Amount:</th>
                                                                    <td><?php echo ($inv_cgst_amount); ?></span> INR</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>SGST Amount:</th>
                                                                    <td><?php echo ($inv_sgst_amount); ?></span> INR</td>
                                                                </tr>
<?php } ?>
                                                            <tr>
                                                                <th>Total:</th>
                                                                <td><?php echo ($inv_net_amount); ?></span> INR</td>
                                                            </tr>
                                                        </tbody></table>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <div class="row no-print">
                                            <div class="col-xs-12">
                                                <a href="javascript:void(0);" onclick="print_this_receipt();" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>

                                            </div>
                                        </div>



                                    </div><!-- /.box-body -->

                                </div>
                            </div><!-- /.box -->
                            <!-- general form elements disabled -->
                        </div></div>
                </section>
            </div>


<?php include("includes/footer.php"); ?>
           
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