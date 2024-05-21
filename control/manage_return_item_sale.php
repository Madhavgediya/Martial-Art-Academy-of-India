<?php
include("includes/application_top.php");
$page_type = "Sale";
$caption = $page_title = "Manage Sale Item";
$btn_caption = "Manage Sale Item";
$success_page_url = "manage_return_item_sale.php";
$manage_url = "manage_return_item_sale.php";
$errormsg = get_rdata('errormsg', '');
$id = get_rdata("id", 0);
$act = get_rdata("act");

$msg = get_rdata('msg', '');
$inv_del_id = explode('-', get_rdata('inv_purchase_del_id'));
if (count($inv_del_id) > 0 && ($inv_del_id[0] == 'S' || $inv_del_id[0] == 'C')) {
    $inv_purchase_del_id = $inv_del_id[1];
} else {
    $inv_purchase_del_id = get_rdata('inv_purchase_del_id', '');
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'invpro_id');
$order = get_rdata('order', 'desc');
$pro_name_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'pro_name') {
        $pro_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $pro_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

$query_2 =  $select_2 = $condition_2 = $table_2 = "";
//searching and pagination
$condition = 'I.inv_admin_id=' . $tmp_admin_id;
$condition .= ' AND I.inv_sale_type = "S" ';

$condition_2 = 'I.inv_admin_id=' . $tmp_admin_id;
$condition_2 .= ' AND I.inv_sale_type = "C" ';

$select = " I.inv_id, I.inv_purchase_invoice_no, D.stu_gr_no cname, D.stu_first_name first_name,D.stu_last_name last_name, P.pro_name, PO.po_name option1, PO1.po_name option2 , IP.invpro_final_pro_price ,IP.invpro_used, IP.invpro_pro_qty, IFNULL(SUM(R.proret_return_pro_qty), 0) proret_return_pro_qty, IP.invpro_id, IP.invpro_pro_qty_sold";
$select_2 = " I.inv_id, I.inv_purchase_invoice_no, D.del_company_name cname, D.del_first_name first_name,D.del_last_name last_name, P.pro_name, PO.po_name option1, PO1.po_name option2 , IP.invpro_final_pro_price ,IP.invpro_used, IP.invpro_pro_qty, IFNULL(SUM(R.proret_return_pro_qty), 0) proret_return_pro_qty, IP.invpro_id, IP.invpro_pro_qty_sold";

$table = " sm_invoice_products_sale IP INNER JOIN sm_invoice_sale I ON (I.inv_id = IP.invpro_inv_id) INNER JOIN sm_product_option PO ON (IP.invpro_po_id= PO.po_id ) INNER JOIN sm_product_option PO1 ON (IP.invpro_po_id_2 = PO1.po_id ) INNER JOIN sm_products P ON (P.pro_id = IP.invpro_pro_id) INNER JOIN sm_student D ON (I.inv_purchase_del_id = D.stu_id ) LEFT JOIN sm_invoice_products_return R ON (R.proret_invpro_id = IP.invpro_id )";

$table_2 = " sm_invoice_products_sale IP INNER JOIN sm_invoice_sale I ON (I.inv_id = IP.invpro_inv_id) INNER JOIN sm_product_option PO ON (IP.invpro_po_id= PO.po_id ) INNER JOIN sm_product_option PO1 ON (IP.invpro_po_id_2 = PO1.po_id ) INNER JOIN sm_products P ON (P.pro_id = IP.invpro_pro_id) INNER JOIN sm_customer D ON (I.inv_purchase_del_id = D.del_id ) LEFT JOIN sm_invoice_products_return R ON (R.proret_invpro_id = IP.invpro_id )";

if ($inv_purchase_del_id != '') {
    $sctype = explode('-', get_rdata('inv_purchase_del_id'));
    if ($sctype[0] == 'S') {
        $condition .= " AND I.inv_purchase_del_id = " . $sctype[1] . "";
    } else if ($sctype[0] == 'C') {
        $condition_2 .= " AND I.inv_purchase_del_id = " . $sctype[1] . "";
    }
}

$other_param = " GROUP BY R.proret_invpro_id, IP.invpro_id";
$other_param .= " HAVING IP.invpro_pro_qty > SUM(IFNULL(R.proret_return_pro_qty, 0))";
$other_param .= " order by " . $order_by . ' ' . $order;

if ($inv_purchase_del_id != '') {
    if ($inv_del_id[0] == 'S') {
        $full_query = "SELECT " . $select . " FROM " . $table . " WHERE " . $condition . " " . $other_param;
    } else {
        $full_query = "SELECT " . $select_2 . " FROM " . $table_2 . " WHERE " . $condition_2 . " " . $other_param;
    }
} else {
    $full_query = "SELECT " . $select . " FROM " . $table . " WHERE " . $condition . " UNION SELECT " . $select_2 . " FROM " . $table_2 . " WHERE " . $condition_2 . " " . $other_param;
}

$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 10, "inv_purchase_del_id=" . $inv_purchase_del_id . "&per_page=" . $per_page . "&order by=" . $order_by . "&order=" . $order, $full_query);

$invProductData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

// if ($order == 'asc') {
//     $order = 'desc';
// } else {
//     $order = 'asc';
// }

if (isset($_GET['page']) && $_GET['page'] > 0 && ($_GET['page'] > ceil($total_rows / $per_page))) {
    $srNo = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport" />
    <title><?php echo $page_title; ?></title>
    <?php include("includes/include_files.php"); ?>
</head>

<body class="skin-green sidebar-mini">
    <div class="wrapper">
        <?php include("includes/header.php"); ?>
        <?php include("includes/left_menu.php"); ?>
        <div class="content-wrapper">
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
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-12 col-xs-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Search</h3>
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal" name="form1" id="form1" method="post">
                                <input type="hidden" id="act" name="act" />
                                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Student/Customer</label>
                                            <div class="col-sm-9">
                                                <select required id="inv_purchase_del_id" name="inv_purchase_del_id" class="form-control">
                                                    <option value="0">--Please select--</option>
                                                    <?php
                                                    // student
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = " CONCAT(stu_gr_no,'-',stu_first_name,' ', stu_last_name) as stu_name, CONCAT('S-',stu_id) as stu_id ";
                                                    $data_arr_input['table'] = 'sm_student';
                                                    $data_arr_input['where'] = " stu_br_id = " . $tmp_admin_id;
                                                    $data_arr_input['key_id'] = 'stu_id';
                                                    $data_arr_input['key_name'] = 'stu_name';
                                                    if ($inv_del_id[0] == 'S') {
                                                        $data_arr_input['current_selection_value'] = 'S-' . $inv_purchase_del_id;
                                                    }
                                                    $data_arr_input['order_by'] = ' stu_gr_no, stu_first_name, stu_last_name';
                                                    display_dd_options($data_arr_input);

                                                    // customer
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'del_company_name, CONCAT("C-",del_id) as del_id ';
                                                    $data_arr_input['table'] = 'sm_customer';
                                                    $data_arr_input['where'] = " del_br_id = " . $tmp_admin_id . " AND del_status  = 'A' ";
                                                    $data_arr_input['key_id'] = 'del_id';
                                                    $data_arr_input['key_name'] = 'del_company_name';
                                                    if ($inv_del_id[0] == 'C') {
                                                        $data_arr_input['current_selection_value'] = 'C-' . $inv_purchase_del_id;
                                                    }
                                                    $data_arr_input['order_by'] = 'del_company_name';
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default" onclick="window.location.href = '<?php echo $manage_url; ?>'">Cancel</button>
                                </div><!-- /.box-footer -->
                            </form>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="return_item_purchase" class="table table-bordered table-hover table-condensed">
                                        <thead>
                                            <tr>
                                                <th width="5%">Sr No</th>
                                                <th width="10%">Invoice No</th>
                                                <th width="15%">Name</th>
                                                <th width="10%">Company</th>
                                                <th width="15%">Product Name</th>
                                                <th width="8%">Size</th>
                                                <th width="8%">Color</th>
                                                <th width="10%">Office/Reselling</th>
                                                <th width="10%">Total Purchase Quantity</th>
                                                <th width="10%">Available Quantity</th>
                                                <th width="10%">Price</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $class = '';
                                            if ($invProductData) {
                                                for ($i = 1; $db_row = $invProductData->fetch(); $i++) {
                                                    $srNo++;
                                                    if ($i % 2 == 0) {
                                                        $class = 'even';
                                                    } else {
                                                        $class = 'odd';
                                                    }
                                                    $inv_id = $db_row['inv_id'];
                                            ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><?php echo $srNo; ?></td>
                                                        <td><?php echo $db_row['inv_purchase_invoice_no']; ?></td>
                                                        <td><?php echo $db_row['first_name'] . " " . $db_row['last_name']; ?></td>
                                                        <td><?php echo $db_row['cname']; ?></td>
                                                        <td><?php echo $db_row['pro_name']; ?></td>
                                                        <td><?php echo $db_row['option1']; ?></td>
                                                        <td><?php echo $db_row['option2']; ?></td>
                                                        <td><?php echo $db_row['invpro_used']; ?></td>
                                                        <td><?php echo $db_row['invpro_pro_qty']; ?></td>
                                                        <td><?php echo $db_row['invpro_pro_qty'] -  $db_row['invpro_pro_qty_sold'] -  $db_row['proret_return_pro_qty']; ?></td>
                                                        <td><?php echo $db_row['invpro_final_pro_price']; ?></td>
                                                        <td>
                                                            <input type="button" value="Return" class="btn btn-info" id="return_inv_pro_<?php echo $db_row['invpro_id']; ?>" onclick="return_process('<?php echo $db_row['invpro_id']; ?>', 'S');" />
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="5">No records found or you have not permission to access these records.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if ($invProductData) { ?>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mt20 mb20">
                                            <?php
                                            $page = 1;
                                            if (isset($_GET['page'])) {
                                                $page = $_GET['page'];
                                            }
                                            if ($page > 0 && ($page > ceil($total_rows / $per_page))) {
                                                $f_line = 1;
                                                $page = 1;
                                            } else {
                                                $f_line = ($page - 1) * $per_page + 1;
                                            }
                                            ?>
                                            <?php $l_line = $page * $per_page; ?>
                                            Showing
                                            <?php
                                            if ($f_line < $total_rows) {
                                                echo ($page - 1) * $per_page + 1;
                                            } else {
                                                echo $total_rows;
                                            }
                                            ?>
                                            to
                                            <?php
                                            if ($l_line < $total_rows) {
                                                echo $page * $per_page;
                                            } else {
                                                echo $total_rows;
                                            }
                                            ?>
                                            of <?php echo $total_rows ?> entries
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <div class="pull-right">
                                                <ul class="pagination">
                                                    <?php echo $pageObj->renderFullNav(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include("includes/footer.php"); ?>
        <?php include("includes/return_product.php"); ?>
        <script type="text/javascript">
            $("#inv_purchase_del_id").select2();
        </script>
    </div>
</body>

</html>