<?php
include("includes/application_top.php");
include("../includes/class/account.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title

// Database connection
$conn = mysqli_connect("localhost", "root", "", "dbkkw4rfsaxdu5");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch data from sm_expence_payment table
$query = "SELECT * FROM sm_expance_payment";
$result = mysqli_query($conn, $query);

// Check if query executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$pt_type = get_rdata('pt_type');
$page_title = "Manage Expance";
$page_open = 'add_edit_expance.php';
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$pt_sc_id = get_rdata('pt_sc_id');
$pt_iet_id = get_rdata('pt_iet_id', '');

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = (($pt_type == 'Credit') ? "Income" : "Expance") . " Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = (($pt_type == 'Credit') ? "Income" : "Expance") . " Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = (($pt_type == 'Credit') ? "Income" : "Expance") . " Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'pt_tran_date');
$order = get_rdata('order', 'desc');
$pt_tran_remarks = get_rdata('pt_tran_remarks', '');

$pt_sc_id_arrow = 'glyphicon glyphicon-chevron-up';
if ($order == 'desc') {
    if ($order_by == 'pt_sc_id') {
        $pt_sc_id_arrow = 'glyphicon glyphicon-chevron-down';
    } else {
        $pt_sc_id_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {
    $delete_q = "DELETE FROM sm_expance_payment WHERE pt_br_id = $tmp_admin_id AND pt_id = $id";
    $delete_r = m_process("delete", $delete_q);
    if ($delete_r['status'] == 'failure') {
        $errormsg = $delete_r['errormsg'];
    } else {
        $successmsg = "Expance Has Been Deleted Successfully";
    }
}


//searching and pagination
$condition = ' (pt_tran_u_type = "Expance" ) AND pt_br_id = ' . $tmp_admin_id;
if ($pt_sc_id != '') {
    $condition .= " and pt_sc_id = '" . $pt_sc_id . "'";
}
if ($pt_tran_remarks != '') {
    $condition .= " and pt_tran_remarks LIKE '%" . $pt_tran_remarks . "%'";
}
if ($pt_iet_id != '') {
    $condition .= " and pt_iet_id = '" . $pt_iet_id . "'";
}



$condition .= " order by " . $order_by . ' ' . $order;
$table = "  sm_expance_payment INNER JOIN sm_account ON (ac_id=pt_sc_id) INNER JOIN sm_income_expance_type ON (pt_iet_id = iet_id) ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, '*', "$condition", $per_page, 10, "per_page=" . $per_page . "&pt_iet_id=" . $pt_iet_id . "&pt_sc_id=" . $pt_sc_id . "&pt_tran_remarks=" . $pt_tran_remarks . "&order by=" . $order_by . "&order=" . $order);
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
                    Manage Expance
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Manage Expance</li>
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
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal" name="form1" id="form1" method="post" onsubmit="return validate_add_edit_form();">
                                <input type="hidden" name="act" id="act">
                                <input type="hidden" value="0" name="id" id="id">
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Account</label>
                                            <div class="col-sm-9">
                                                <select id="pt_sc_id" name="pt_sc_id" class="form-control">
                                                    <option value="">--All--</option>
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'ac_name ,ac_id';
                                                    $data_arr_input['table'] = 'sm_account';
                                                    $data_arr_input['where'] = " ac_status  = 'A' and ac_br_id= $tmp_admin_id";
                                                    $data_arr_input['key_id'] = 'ac_id';
                                                    $data_arr_input['key_name'] = 'ac_name';
                                                    $data_arr_input['current_selection_value'] = $pt_sc_id;
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Category</label>
                                            <div class="col-sm-9">
                                                <select id="pt_iet_id" name="pt_iet_id" class="form-control">
                                                    <option value="">--All--</option>
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'iet_name ,iet_id';
                                                    $data_arr_input['table'] = 'sm_income_expance_type';
                                                    $data_arr_input['where'] = " iet_status  = 'A' and iet_br_id= $tmp_admin_id";
                                                    $data_arr_input['key_id'] = 'iet_id';
                                                    $data_arr_input['key_name'] = 'iet_name';
                                                    $data_arr_input['current_selection_value'] = $pt_iet_id;
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none;">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Type</label>
                                            <div class="col-sm-9">
                                                <select id="pt_type" name="pt_type" class="form-control">
                                                    <option value="">All</option>
                                                    <option <?php if ($pt_type == 'Debit') echo ' selected="selected" '; ?> value="Debit">Expance</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Remarks</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="pt_tran_remarks" name="pt_tran_remarks" class="form-control" value="<?php echo $pt_tran_remarks; ?>" />
                                            </div>
                                        </div>

                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_expance.php'">Cancel</button>
                                    <button type="button" style="float:right;" class="btn btn-success" onclick="window.location.href='<?php echo $page_open; ?>'">ADD</button>
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
                                            <th align="center" width="70px">Sr.No</th>
                                            <th align="center" width="auto">Voucher No</th>
                                            <th align="center" width="auto">Remark</th>
                                            <th align="center" width="auto">Date</th>
                                            <th align="center" width="auto">Mode</th>
                                            <th align="center" width="auto">Bank</th>
                                            <th align="center" width="auto">Txn. No</th>
                                            <?php if ($pt_type == 'Credit') { ?>
                                                <th align="center" width="auto">Inc. Amount</th>
                                            <?php } else if ($pt_type == 'Debit') { ?>
                                                <th align="center" width="auto">Exp. Amount</th>
                                            <?php } ?>
                                            <th align="center" width="80px">Amount</th>
                                            <th align="center" class="t_align_center" width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // $class = '';
                                        // if ($objData) {
                                        //     for ($i = 1; $db_row = $objData->fetch(); $i++) {
                                        //         $srNo++;
                                        //         if ($i % 2 == 0) {
                                        //             $class = 'even';
                                        //         } else {
                                        //             $class = 'odd';
                                        //         }

                                        while ($db_row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr class="<?php echo $class; ?>">
                                                <td>
                                                    <center><?php echo $db_row['pt_id']; ?></center>
                                                </td>
                                                <td style="padding-left:10px;"><?php echo $db_row['pt_voucher_no']; ?></td>
                                                <td style="padding-left:10px;"><?php echo $db_row['pt_tran_remarks']; ?></td>

                                                <td style="padding-left:10px;">
                                                    <?php echo DBtoDisp($db_row['pt_tran_date']); ?></td>
                                                <td style="padding-left:10px;">
                                                    <?php echo $db_row['pt_tran_mode_of_payent']; ?></td>
                                                <td style="padding-left:10px;"><?php echo $db_row['pt_tran_bank']; ?></td>
                                                <td style="padding-left:10px;"><?php echo $db_row['pt_tran_no']; ?></td>
                                                <td style="padding-left:10px;"><?php echo $db_row['pt_tran_amount']; ?></td>


                                                <td style="padding-left:10px;">
                                                    <center><a href="add_edit_<?php echo strtolower($db_row['pt_tran_u_type']); ?>.php?id=<?php echo $db_row['pt_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil"></a>&nbsp;
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['pt_id']; ?>,'Expance')"></a>

                                                        <a href="javascript:void(0);" class="text-success fa fa-fw fa-print" onclick="print_fee_receipt('Expance',<?php echo $db_row['pt_id']; ?>,0)"></a>

                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
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
    </div>
</body>

</html>