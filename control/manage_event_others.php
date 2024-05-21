<?php
include("includes/application_top.php");
include("../includes/class/eventother.php");

$page_title = "Manage Event Other";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$stu_id= get_rdata('stu_id');
$stu_gr_no= get_rdata('stu_gr_no');
$stu_first_name= get_rdata('stu_first_name');
$stu_middle_name= get_rdata('stu_middle_name');
$stu_last_name= get_rdata('stu_last_name');


$stu_status= get_rdata('stu_status','A');
$stu_br_id= get_rdata('stu_br_id');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Event Other Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Event Other Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Event Other Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Event Other has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
      $successmsg = "Event Other has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' stu_gr_no ');
$order = get_rdata('order', 'asc');
$stu_first_name_arrow = $stu_gr_no_arrow=  'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'stu_first_name') {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'stu_gr_no') {
        $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

//searching and pagination
$condition = '1';
if ($stu_gr_no != '') {
    $condition.=" and 	stu_gr_no LIKE '%" . $stu_gr_no . "%'";
}
if ($stu_first_name != '') {
    $condition.=" and 	stu_first_name LIKE '%" . $stu_first_name . "%'";
}
if ($stu_last_name != '') {
    $condition.=" and 	stu_last_name LIKE '%" . $stu_last_name . "%'";
}
if ($stu_status != 'All') {
    $condition.=" and stu_status = '" . $stu_status . "'";
}

if ($tmp_type != 'admin') {
    $condition.=" and stu_br_id= " . $tmp_admin_id;
}

$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_student_other";
$select_f = "stu_parent_mobile_no, stu_whatsappno, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name ";
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&stu_first_name=" . $stu_first_name . "&order by=" . $order_by . "&order=" . $order);
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
                        Manage Event Other
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Event Other</li>
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
                                                    <input type="text" name="stu_first_name" id="stu_first_name"  placeholder="Enter First Name" value="<?php echo $stu_first_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Last Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="stu_last_name" id="stu_last_name"  placeholder="Enter Last Name" value="<?php echo $stu_last_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">GR No</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="stu_gr_no" id="stu_gr_no"  placeholder="Enter GR No" value="<?php echo $stu_gr_no; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9">
                                                  <select required id="stu_status" name="stu_status" class="form-control">
                                                      <?php
                                                      $data_arr_input = array();
                                                      $data_arr_input['data_array'] =  $arr_status_global;
                                                      $data_arr_input['consider'] =  "key";
                                                      $data_arr_input['current_selection_value'] = $stu_status;
                                                      display_dd_options_from_array($data_arr_input);
                                                      ?>
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_event_others.php'">Cancel</button>
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
                                                <th align="center" ><a href="manage_event_others.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_gr_no&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $stu_gr_no_arrow; ?>"></span></a></th>
                                                <th align="center"><a href="manage_event_others.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_first_name&order=<?php echo $order; ?>">Name <span class="<?php echo $stu_first_name_arrow; ?>"></span></a></th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>
                                                        <td style="padding-left:10px;"><?php
                                                        if ($db_row['stu_phone'] !='') { echo "S: ".$db_row['stu_phone']."</br>";}
                                                        if ($db_row['stu_parent_mobile_no'] !='') { echo "P: ".$db_row['stu_parent_mobile_no']."</br>";}
                                                        if ($db_row['stu_whatsappno'] !='') { echo "W: ".$db_row['stu_whatsappno']."</br>";}
                                                        ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['stu_status']=='A'?'Active':'Inactive'); ?></td>
                                                        <td><center><a href="add_edit_event_others.php?id=<?php echo $db_row['stu_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                               <!-- <a href="javascript:void(0);" class="text-info fa fa-bars" onclick="show_add_course(<?php echo $db_row['stu_id']; ?>,'View Event Details')"></a>&nbsp; -->
                                                            </center></td>
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