<?php
include("includes/application_top.php");
include("../includes/class/student_course.php");

$page_title = "Manage Student Course";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");

$sc_stu_id = get_rdata('sc_stu_id');
$sc_cd_id = get_rdata('sc_cd_id');
$cd_name = get_rdata('cd_name');
$sc_joined_date = get_rdata('sc_joined_date');
$sc_total_fee = get_rdata('sc_total_fee');
$sc_total_paid = get_rdata('sc_total_paid');
$sc_full_fee_paid = get_rdata('sc_full_fee_paid');
$sc_is_current = get_rdata('sc_is_current');
$sc_create_date = $cur_date;
$sc_create_by_id = $tmp_admin_id;
$sc_update_date = $cur_date;
$sc_update_by_id = $tmp_admin_id;

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Student Course Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Student Course Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Student Course Has Been Updated Successfully";
} else {
    $successmsg = '';
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' cd_name ');
$order = get_rdata('order', 'asc');
$cd_name_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'cd_name') {
        $cd_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $cd_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
} else {
    $order = 'asc';
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {

    $sm_student_course = new studentcourse();
    $sm_student_course->action = 'delete';

    $del_where = "sc_id = " . $id;
    $sm_student_course->where = $del_where;

    $result = $sm_student_course->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        $successmsg = "Student Course Has Been Deleted Successfully";
    }
}


//searching and pagination
$condition = '1';
//if ($stu_gr_no != '') {
//    $condition.=" and 	stu_gr_no LIKE '%" . $stu_gr_no . "%'";
//}
//if ($stu_first_name != '') {
//    $condition.=" and 	stu_first_name LIKE '%" . $stu_first_name . "%'";
//}
//if ($stu_last_name != '') {
//    $condition.=" and 	stu_last_name LIKE '%" . $stu_last_name . "%'";
//}
//
//if ($tmp_type != 'admin') {
//    $condition.=" and stu_br_id= " . $tmp_admin_id;
//}
$select_f = " sc_id,  cd_name, date_format(sc_joined_date,'%d-%b-%Y') as sc_joined_date_d ,  sc_total_fee, sc_total_paid, IF(sc_total_fee=sc_total_paid,'Yes','No') pay_status , IF(sc_is_current=1,'Yes','No') course_status  ";
$condition.=" order by " . $order_by . ' ' . $order;
$table = " sm_student_course INNER JOIN sm_course_details ON (sc_cd_id=cd_id) ";
// echo "SELECT $select_f FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&cd_name=" . $cd_name . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();
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
                        Manage Student Course
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Student Course</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
<?php include("includes/messages.php"); ?>
                    <!-- Small boxes (Stat box) -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">                                
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center"><a href="manage_student.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=cd_name&order=<?php echo $order; ?>">Course <span class="<?php echo $cd_name_arrow; ?>"></span></a></th>
                                                <th align="center" >Joined Date</th>
                                                <th align="center" >Fee</th>
                                                <th align="center" >Total Paid</th>
                                                <th align="center" >Full Paid</th>
                                                <th align="center" width="80px" >Is Current?</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['cd_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['sc_joined_date_d']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['sc_total_fee']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['sc_total_paid']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['pay_status']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['course_status']; ?></td>
                                                       <td><center>
                                                                                        <a href="add_edit_student_course.php?id=<?php echo $db_row['sc_id']; ?>&per_page=<?php echo $per_page; ?>" class="edit glyphicon glyphicon-pencil" ></a>&nbsp;
                                                                                        <a href="javascript:void(0);" class="delete glyphicon glyphicon-remove" data-toggle="modal" data-target="#ConfirmDelete" onclick="delete_record(<?php echo $db_row['sc_id']; ?>, 'Student Course')"></a></center></td></td>
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
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
