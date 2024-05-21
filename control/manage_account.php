<?php
include("includes/application_top.php");
include("../includes/class/account.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Account";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$ac_name = get_rdata('ac_name');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Account Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Account Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Account Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'ac_name');
$order = get_rdata('order', 'asc');
$ac_name_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'ac_name') {
        $ac_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ac_name_arrow = 'glyphicon glyphicon-chevron-up';
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

    
        $q_d_check = "SELECT 1 FROM sm_payment_transaction WHERE pt_tran_u_type IN ('Expance','Income') AND pt_br_id = $tmp_admin_id AND pt_sc_id=$id";
        $r_d_check =m_process("get_data",$q_d_check);
        if ($r_d_check["status"] == "failure" )
        {
            $errormsg  = $r_d_check["errormsg"];
        }
        else
        {
          if ($r_d_check["count"]>0)
          {
              $errormsg  = "Account has transaction's so you can't delete it";
          }
          else 
          {
              


            $account_master = new account();
            $account_master->action = 'delete';
            $del_where = "ac_id = ". $id . " AND ac_br_id = ".$tmp_admin_id;

            $account_master->where = $del_where;
            $result = $account_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                $successmsg = "Account Has Been Deleted Successfully";
            }
          }
        }  
       
}


//searching and pagination
$condition = '  ac_br_id = '.$tmp_admin_id ;
  if ($ac_name != '') {
    $condition.=" and ac_name LIKE '%" . $ac_name . "%'";
}
$condition.=" GROUP BY  ac_id";
$condition.=" order by " . $order_by . ' ' . $order;
$table = "  sm_account LEFT JOIN sm_payment_transaction ON (ac_id=pt_sc_id AND (pt_tran_u_type = 'Income' OR  pt_tran_u_type = 'Expance') ) ";
$select = " SUM(IF(pt_tran_u_type = 'Income', pt_tran_amount, 0)) total_income, SUM(IF(pt_tran_u_type = 'Expance', pt_tran_amount, 0)) total_expance, ac_name , ac_id, ac_status ";
// echo "SELECT $select FROM ".$table. " WHERE " .$condition;

 
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 10, "per_page=" . $per_page . "&ac_name=" . $ac_name . "&order by=" . $order_by . "&order=" . $order);
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
                        Manage Account
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Account</li>
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
                                                    <input type="text" name="ac_name" id="ac_name"  placeholder="Enter Account Name" value="<?php echo $ac_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_account.php'">Cancel</button>
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
                                                <th align="center"><a href="manage_account.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=ac_name&order=<?php echo $order; ?>">Account Name <span class="<?php echo $ac_name_arrow; ?>"></span></a></th>
                                                <!-- <th align="center" width="auto" >Type</th> -->
                                                <th style="text-align:right;" >Income</th>
                                                <th style="text-align:right;" >Expance</th>
                                                <th style="text-align:right;" >Net</th>
                                                <th align="center" width="80px">Status</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['ac_name']; ?></td>
                                                        <td  style="text-align:right;"><?php echo $db_row['total_income']; ?></td>
                                                <td style="text-align:right;" ><?php echo $db_row['total_expance']; ?></td>
                                                <td style="text-align:right;" ><?php echo ($db_row['total_income']-$db_row['total_expance']); ?></td>
                                                        <!-- <td style="padding-left:10px;"><?php echo $db_row['ac_type']; ?></td> -->
                                                        <td style="padding-left:10px;"><?php echo ($db_row['ac_status']=='A'?'Active':'Inactive'); ?></td>
                                                        <td><center><a href="add_edit_account.php?id=<?php echo $db_row['ac_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                          <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['ac_id']; ?>,'Account')"></a></center></td>
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
