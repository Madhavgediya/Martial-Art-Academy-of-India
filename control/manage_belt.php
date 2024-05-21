<?php
include("includes/application_top.php");
include("../includes/class/belt.php");


$page_title = "Manage Belt";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$be_name = get_rdata('be_name');
$be_status = get_rdata('be_status','A');
$be_sort_order = get_rdata('be_sort_order');

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Belt Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Belt Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Belt Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'be_sort_order');
$order = get_rdata('order', 'asc');
$be_sort_order_arrow = $be_name_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'be_name') {
        $be_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $be_name_arrow = 'glyphicon glyphicon-chevron-up';
    }

    if ($order_by == 'be_sort_order') {
        $be_sort_order_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $be_sort_order_arrow = 'glyphicon glyphicon-chevron-up';
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
/*
    $arr_check_delete = validate_before_delete("sm_student_course", "sc_be_id = " . $id);
    if ($arr_check_delete["error_message"] != '') {
        $errormsg = $arr_check_delete["error_message"];
    } else if ($arr_check_delete["found_reference"] == true) {
        $errormsg = "Delete process can not be completed because of found Belt is already associated with student";
    }
*/
    /*
    if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_attendance", "att_br_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of found Belt reference with attendance";
        }
    }
    */
    /*
    if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_faculty_standard", "facs_br_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of found standard reference with faculty";
        }
    }

    if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_result", "res_br_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of found standard reference with result";
        }
    }
*/
    /*
    if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_school_standard_subject", "sss_br_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of found standard reference with school standard subject";
        }
    }
    */
  /*

*/

if ($errormsg == '') {

   $arr_check_delete = validate_before_delete("sm_student_course", " sc_br_id = ".$tmp_admin_id." AND sc_be_id = " . $id);
   if ($arr_check_delete["error_message"] != '') {
       $errormsg = $arr_check_delete["error_message"];
   } else if ($arr_check_delete["found_reference"] == true) {
       $errormsg = "Delete process can not be completed because of belt is already used in student course";
   }
}

    if ($errormsg == '') {
        $belt = new belt();
        $belt->action = 'delete';
        $del_where = " be_id = " . $id;

        $belt->where = $del_where;

        $result = $belt->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            $successmsg = "Belt Has Been Deleted Successfully";
        }
    }
}


//searching and pagination
// $condition = '1';
$condition = " 1 ";
if ($be_name != '') {
    $condition.=" and be_name LIKE '%" . $be_name . "%'";
}


$select_c = "  M.total_marks_cat, sm_belt.* ";
$condition.=" order by " . $order_by . ' ' . $order;
$table = " sm_belt LEFT JOIN (SELECT SUM(eca_total_marks) as total_marks_cat ,eca_be_id FROM sm_exam_categories_allocation GROUP BY eca_be_id) as M ON(be_id=M.eca_be_id)  ";
// echo "SELECT ".$select_c." FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select_c, "$condition", $per_page, 10, "per_page=" . $per_page . "&be_name=" . $be_name . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

//get total records from project table
// $belt = new standard();
//$belt->cquery = "select * from $table WHERE $condition";
//$belt->action = 'get';
//$result = $belt->process();
//if ($result['status'] == 'failure') {
//    $errormsg = $result['errormsg'];
//} else {
//    if ($result['count'] > 0) {
//        $total_rows = $result['count'];
//    }
//}

if ($order == 'asc') {
    $order = 'desc'; }
    else {
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
                        Manage Belt
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Belt</li>
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
                                                        <label class="col-sm-3 control-label">Belt Name</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="be_name" id="be_name"  placeholder="Enter Belt Name" value="<?php echo $be_name; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.box-body -->
                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-info">Search</button>
                                                <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_belt.php'">Cancel</button>
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
                                                                        <th align="center"><a href="manage_belt.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=be_name&order=<?php echo $order; ?>">Name <span class="<?php echo $be_name_arrow; ?>"></span></a></th>
                                                                        <th align="center">Next Belt</th>
                                                                        <th align="center"><a href="manage_belt.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=be_sort_order&order=<?php echo $order; ?>">Sort Order <span class="<?php echo $be_sort_order_arrow; ?>"></span></a></th>
                                                                        <th align="center" width="80px">Marks</th>
                                                                        <th align="center">Course Duration</th>
                                                                        <th align="center">Ex. Fee</th>
                                                                        <!-- <th align="center">One M. Fee</th> -->

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
                                                                                <td style="padding-left:10px;"><?php echo $db_row['be_name']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['be_name_for']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['be_sort_order']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['total_marks_cat']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['be_belt_fee']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['be_belt_exam_fee']; ?></td>
                                                                                <!-- <td style="padding-left:10px;">
                                                                                    <?php echo $db_row['be_belt_onemonth_fee']; ?>
                                                                                </td> -->
                                                                                <td style="padding-left:10px;"><?php echo ($db_row['be_status']=='A'?'Active':'Inactive'); ?></td>
                                                                                <td><center>
                                                                                        <a href="add_edit_belt.php?id=<?php echo $db_row['be_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                                                        <a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" data-toggle="modal" data-target="#ConfirmDelete" onclick="delete_record(<?php echo $db_row['be_id']; ?>, 'Belt')"></a></center></td>
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
