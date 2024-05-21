<?php
include("includes/application_top.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Return Sale";
$manage_url = "manage_return_sale.php";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");

$inv_del_id = explode('-', get_rdata('inv_purchase_del_id'));
if (count($inv_del_id) > 0 && ($inv_del_id[0] == 'S' || $inv_del_id[0] == 'C')) {
    $inv_purchase_del_id = $inv_del_id[1];
} else {
    $inv_purchase_del_id = get_rdata('inv_purchase_del_id', '');
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'pro_name');
$order = get_rdata('order', 'asc');
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

$select_2 = $condition_2 = $table_2 = "";

$condition = 'R.proret_isreturn = "true" AND R.proret_pro_type = "S" ';
$condition .= ' AND I.inv_sale_type = "S" ';

$condition_2 = 'R.proret_isreturn = "true" AND R.proret_pro_type = "S" ';
$condition_2 .= ' AND I.inv_sale_type = "C" ';

$select = " inv_purchase_invoice_no, P.pro_name, R.proret_final_pro_price , R.proret_return_pro_qty, R.proret_create_date, D.stu_gr_no cname , D.stu_first_name first_name, D.stu_last_name last_name";
$table = " sm_invoice_products_return R INNER JOIN sm_invoice_sale I ON (I.inv_id = R.proret_inv_id) INNER JOIN sm_products P ON (P.pro_id = R.proret_pro_id) INNER JOIN sm_student D ON (I.inv_purchase_del_id = D.stu_id ) ";

$select_2 = " inv_purchase_invoice_no, P.pro_name, R.proret_final_pro_price , R.proret_return_pro_qty, R.proret_create_date, D.del_company_name cname , D.del_first_name first_name, D.del_last_name last_name";
$table_2 = " sm_invoice_products_return R INNER JOIN sm_invoice_sale I ON (I.inv_id = R.proret_inv_id) INNER JOIN sm_products P ON (P.pro_id = R.proret_pro_id) INNER JOIN sm_customer D ON (I.inv_purchase_del_id = D.del_id )";

if ($inv_purchase_del_id != '') {
    $sctype = explode('-', get_rdata('inv_purchase_del_id'));
    if ($sctype[0] == 'S') {
        $condition .= " AND I.inv_purchase_del_id = " . $sctype[1] . "";
    } else if ($sctype[0] == 'C') {
        $condition_2 .= " AND I.inv_purchase_del_id = " . $sctype[1] . "";
    }
}
$other_param =  " order by " . $order_by . ' ' . $order;

if ($inv_purchase_del_id != '') {
    if ($inv_del_id[0] == 'S') {
        $full_query = "SELECT " . $select . " FROM " . $table . " WHERE " . $condition . " " . $other_param;
    } else {
        $full_query = "SELECT " . $select_2 . " FROM " . $table_2 . " WHERE " . $condition_2 . " " . $other_param;
    }
} else {
    $full_query = "SELECT " . $select . " FROM " . $table . " WHERE " . $condition . " UNION SELECT " . $select_2 . " FROM " . $table_2 . " WHERE " . $condition_2 . " " . $other_param;
}

// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 10, "per_page=" . $per_page . "&order by=" . $order_by . "&order=" . $order, $full_query);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
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
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Manage Return Sale
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Manage Return Sale</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php include("includes/messages.php"); ?>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-12 col-xs-12 ma-btn-wrap">
                        <button type="button" class="btn btn-primary" onclick="window.location.href = 'manage_return_item_sale.php'">Return Item</button>
                    </div>
                </div>
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
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">Sr.No</th>
                                            <th width="10%">Invoice No</th>
                                            <th width="15%">Name</th>
                                            <th width="10%">Company</th>
                                            <th width="10%">
                                                <a href="manage_return_sale.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=pro_name&order=<?php echo $order; ?>">Product Name <span class="<?php echo $pro_name_arrow; ?>"></span></a>
                                            </th>
                                            <th width="10%">Quantity</th>
                                            <th width="10%">Price</th>
                                            <th width="10%">Return Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $class = '';
                                        if ($objData) {
                                            for ($i = 1; $db_row = $objData->fetch(); $i++) {
                                                $srNo++;
                                                if ($i % 2 == 0) {
                                                    $class = 'even';
                                                } else {
                                                    $class = 'odd';
                                                }
                                        ?>
                                                <tr class="<?php echo $class; ?>">
                                                    <td align="center"><?php echo $srNo; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['inv_purchase_invoice_no']; ?></td>
                                                    <td><?php echo $db_row['first_name'] . " " . $db_row['last_name']; ?></td>
                                                    <td><?php echo $db_row['cname']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['pro_name']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['proret_return_pro_qty']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['proret_final_pro_price']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['proret_create_date']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="11">No records found or you have not permission to access these records.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php if ($objData) { ?>
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
            </section>
        </div>
        <?php include("includes/footer.php"); ?>
        <script type="text/javascript">
            $("#inv_purchase_del_id").select2();
        </script>
    </div>
</body>

</html>