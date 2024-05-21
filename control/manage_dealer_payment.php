<?php
include("includes/application_top.php");
include("../includes/class/account.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Dealer Payment";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$pt_sc_id = get_rdata('pt_sc_id');
$pt_type = get_rdata('pt_type');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Dealer Payment Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Dealer Payment Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Dealer Payment Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'pt_id');
$order = get_rdata('order', 'asc');
$pt_tran_remarks = get_rdata('pt_tran_remarks', '');

$pt_sc_id_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'pt_sc_id') {
        $pt_sc_id_arrow = 'glyphicon glyphicon-chevron-up';
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
if ($act == 'delete' && $id != 0) 
{
        $delete_q = "DELETE FROM sm_payment_transaction WHERE pt_br_id = $tmp_admin_id AND pt_id = $id";   
        $delete_r = m_process("delete", $delete_q);
        if ($delete_r['status'] == 'failure') 
        {
            $errormsg = $delete_r['errormsg'];
        } 
        else 
        {
            $successmsg = "Dealer Payment Has Been Deleted Successfully";
        }
}


//searching and pagination
$condition = ' (pt_tran_u_type = "DealerP") AND pt_br_id = '.$tmp_admin_id ;
  if ($pt_sc_id != '') {
    $condition.=" and pt_sc_id = '" . $pt_sc_id . "'";
}
if ($pt_type != '') {
    $condition.=" and pt_type = '" . $pt_type . "'";
}
if ($pt_tran_remarks != '') {
    $condition.=" and pt_tran_remarks LIKE '%" . $pt_tran_remarks . "%'";
}



$condition.=" order by " . $order_by . ' ' . $order;
$table = "  sm_payment_transaction INNER JOIN sm_dealer ON (del_id=pt_sc_id)  ";
//echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, '*', "$condition", $per_page, 10, "per_page=" . $per_page . "&pt_type=" . $pt_type . "&pt_sc_id=" . $pt_sc_id . "&pt_tran_remarks=" . $pt_tran_remarks . "&order by=" . $order_by . "&order=" . $order);
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
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
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
                        Manage Dealer Payment
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Dealer Payment</li>
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
                                                <label class="col-sm-3 control-label">Dealer Name</label>
                                                <div class="col-sm-9">
                                                <select  id="pt_sc_id" name="pt_sc_id" class="form-control">
                                          <option value="">--All--</option>
                                          <?php
                                          $data_arr_input = array();
                                          $data_arr_input['select_field'] = 'del_company_name ,del_id';
                                          $data_arr_input['table'] = 'sm_dealer';
                                          $data_arr_input['where'] = " del_status = 'A' and del_br_id= $tmp_admin_id";
                                          $data_arr_input['key_id'] = 'del_id';
                                          $data_arr_input['key_name'] = 'del_company_name';
                                          $data_arr_input['current_selection_value'] = $pt_sc_id;
                                          display_dd_options($data_arr_input);
                                          ?>
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
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_dealer_payment.php'">Cancel</button>
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
                                                <th align="center"><a href="manage_dealer_payment.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&pt_type=<?php echo $pt_type; ?>&pt_sc_id=<?php echo $pt_sc_id; ?>&pt_tran_remarks=<?php echo $pt_tran_remarks; ?>&order_by=pt_sc_id&order=<?php echo $order; ?>">Account <span class="<?php echo $pt_sc_id_arrow; ?>"></span></a></th>
                                                
                                                <th align="center" width="auto" >Date</th>
                                                <th align="center" width="auto" >Mode</th>
                                                <th align="center" width="auto" >Bank</th>
                                                <th align="center" width="auto" >Txn. No</th>
                                                <th align="center" width="auto" >Amount</th>
                                                <th align="center" width="auto">Remarks</th>
                                                <th align="center" class="t_align_center"  width="100px">Action</th>
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
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['del_company_name']; ?></td>
                                                        
                                                        <td style="padding-left:10px;"><?php echo DBtoDisp($db_row['pt_tran_date']); ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['pt_tran_mode_of_payent']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['pt_tran_bank']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['pt_tran_no']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['pt_tran_amount']; ?></td>
                                                        
                                                        <td style="padding-left:10px;"><?php echo nl2br($db_row['pt_tran_remarks']); ?></td>
                                                        <td><center><a href="dealer_payment.php?id=<?php echo $db_row['pt_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                          <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['pt_id']; ?>,'Dealer Payment')"></a>
                                                          <a href="javascript:void(0);" class="text-success fa fa-fw fa-print" onclick="print_fee_receipt('DealerP',<?php echo $db_row['pt_id']; ?>,0)"></a>
                                                          </center>
                                                          </td>
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
        </div>
    </body>
</html>
