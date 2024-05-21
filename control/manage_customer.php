<?php
include("includes/application_top.php");
include("../includes/class/customer.php");

$page_title = "Manage Customer";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$del_id= get_rdata('del_id');
$del_first_name= get_rdata('del_first_name');
$del_company_name= get_rdata('del_company_name');
$del_last_name= get_rdata('del_last_name');


$del_status= get_rdata('del_status','All');
$del_br_id= get_rdata('del_br_id');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Customer Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Customer Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Customer Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Customer has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
      $successmsg = "Customer has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' del_company_name ');
$order = get_rdata('order', 'asc');
$del_first_name_arrow = $del_company_name_arrow=  'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'del_first_name') {
        $del_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $del_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'del_company_name') {
        $del_company_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $del_company_name_arrow = 'glyphicon glyphicon-chevron-up';
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
    if (check_customer_invoice($id) == true) 
    {
        $sm_dealer = new customer();
        $sm_dealer->action = 'delete';
        $del_where = "del_br_id = ".$tmp_admin_id." AND del_id = ". $id;
        $sm_dealer->where = $del_where;
        $result = $sm_dealer->process();
        if ($result['status']=='failure') {
            $errormsg = $result['errormsg'];
        } else {
            $successmsg = "Customer Has Been Deleted Successfully";
        }
    }
    else
    {
        $errormsg = 'Dealear Has active invoices. Please delete it first.';
    }
}


//searching and pagination
$condition = '1';
if ($del_company_name != '') {
    $condition.=" and 	del_company_name LIKE '%" . $del_company_name . "%'";
}
if ($del_first_name != '') {
    $condition.=" and 	del_first_name LIKE '%" . $del_first_name . "%'";
}
if ($del_last_name != '') {
    $condition.=" and 	del_last_name LIKE '%" . $del_last_name . "%'";
}
if ($del_status != 'All') {
    $condition.=" and del_status = '" . $del_status . "'";
}

if ($tmp_type != 'admin') {
    $condition.=" and del_br_id= " . $tmp_admin_id;
}

$condition.=" order by " . $order_by . ' ' . $order;
$table = " sm_customer  ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$select_f = " del_company_name ,del_first_name, del_phone,del_status,del_id,del_last_name";
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&del_first_name=" . $del_first_name . "&order by=" . $order_by . "&order=" . $order);
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
                        <?php echo $page_title;?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $page_title;?></li>
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
                                                <label class="col-sm-3 control-label">First Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="del_first_name" id="del_first_name"  placeholder="Enter First Name" value="<?php echo $del_first_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Last Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="del_last_name" id="del_last_name"  placeholder="Enter Last Name" value="<?php echo $del_last_name; ?>" class="form-control" />
                                                </div>
                                            </div>



                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Company Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="del_company_name" id="del_company_name"  placeholder="Enter Company Name" value="<?php echo $del_company_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9">
                                                  <select required id="del_status" name="del_status" class="form-control">
                                                      <?php
                                                      $data_arr_input = array();
                                                      $data_arr_input['data_array'] =  $arr_status_global;
                                                      $data_arr_input['consider'] =  "key";
                                                      $data_arr_input['current_selection_value'] = $del_status;
                                                      display_dd_options_from_array($data_arr_input);
                                                      ?>
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_customer.php'">Cancel</button>
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
                                                <th align="center" ><a href="manage_customer.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=del_company_name&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $del_company_name_arrow; ?>"></span></a></th>
                                                <th align="center"><a href="manage_customer.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=del_first_name&order=<?php echo $order; ?>">Name <span class="<?php echo $del_first_name_arrow; ?>"></span></a></th>
                                                <th align="center" >Phone</th>

                                                <th align="center" width="10px">Status</th>
                                                <th align="center" class="t_align_center"  width="120px">Action</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['del_first_name'].' '.$db_row['del_last_name'] ; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['del_phone']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['del_status']=='A'?'Active':'Inactive'); ?></td>
                                                        <td><center><a href="add_edit_customer.php?id=<?php echo $db_row['del_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                          <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['del_id']; ?>,'Customer')"></a>&nbsp;<a href="manage_sale.php?inv_purchase_del_id=<?php echo "C-".$db_row['del_id']; ?>" class="fa fa-shopping-cart" ></a></center></td>

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
            <?php include("includes/models.php"); ?>
            <?php include("includes/change_batch_type.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
