<?php
include("includes/application_top.php");
include("../includes/class/exam.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Exam Report";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$ex_name = get_rdata('ex_name');
$ex_date = get_rdata('ex_date',DBtoDisp($cur_date));


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Exam Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Exam Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Exam Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Student Exam Enrollment has been done successfully.";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'ex_date');
$order = get_rdata('order', 'desc');
$ex_name_arrow = $ex_date_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
      if ($order_by == 'ex_name') {
        $ex_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ex_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
      if ($order_by == 'ex_date') {
        $ex_date_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ex_date_arrow = 'glyphicon glyphicon-chevron-up';
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
  if ($errormsg == '') {
     $arr_check_delete = validate_before_delete("sm_exam_student_entrolled INNER JOIN sm_student ON ( exs_stu_id =stu_id ) ", " stu_br_id = ".$tmp_admin_id." AND exs_ex_id = " . $id);
     if ($arr_check_delete["error_message"] != '') {
         $errormsg = $arr_check_delete["error_message"];
     } else if ($arr_check_delete["found_reference"] == true) {
         $errormsg = "Delete process can not be completed because of exam is already used in student exam enrollment";
     }
  }

      if ($errormsg == '') {
            $exam_master = new exam();
            $exam_master->action = 'delete';
            $del_where = " ex_br_id = $tmp_admin_id AND ex_id = ". $id;
            $exam_master->where = $del_where;
            $result = $exam_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                $successmsg = "Exam Has Been Deleted Successfully";
            }

          }
}


//searching and pagination
$condition = ' ex.ex_br_id = '. $tmp_admin_id;
  if ($ex_name != '') {
    $condition.=" and ex.ex_name LIKE '%" . $ex_name . "%'";
}

/*
SELECT IF(M.count_exam IS NULL,0,M.count_exam) count_exam , ex.* FROM
sm_exam  ex LEFT JOIN (select count(1) as count_exam , exs_ex_id FROM sm_exam_student_entrolled
GROUP BY exs_ex_id ) as M ON (ex.ex_id = M.exs_ex_id)
WHERE  ex.ex_br_id = 1 order by ex.ex_name asc
*/
$select = "IF(M.count_exam IS NULL,0,M.count_exam) count_exam , ex.*";
$condition.=" order by ex." . $order_by . ' ' . $order;
$table = "  sm_exam  ex LEFT JOIN (select count(1) as count_exam , exs_ex_id FROM sm_exam_student_entrolled
GROUP BY exs_ex_id ) as M ON (ex.ex_id = M.exs_ex_id) ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 10, "per_page=" . $per_page . "&ex_name=" . $ex_name . "&ex_date=" . $ex_date . "&order by=" . $order_by . "&order=" . $order);
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
                        Manage Exam
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Exam</li>
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
                                                <label class="col-sm-3 control-label">Exam Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ex_name" id="ex_name"  placeholder="Enter Exam Name" value="<?php echo $ex_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Exam Date</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="ex_date" id="ex_date"  placeholder="Enter Exam Date" value="<?php echo $ex_date; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_exam.php'">Cancel</button>
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
                                                <th align="center"><a href="manage_exam.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=ex_name&order=<?php echo $order; ?>">Exam Name <span class="<?php echo $ex_name_arrow; ?>"></span></a></th>
                                                <th align="center"><a href="manage_exam.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=ex_date&order=<?php echo $order; ?>">Exam Date <span class="<?php echo $ex_date_arrow; ?>"></span></a></th>
                                                <th align="center" class="t_align_center" width="300px">Report</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['ex_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php  echo  DBtoDisp($db_row['ex_date']); ?></td>
                                                        
                                                        <td>

                                                          <a href="exam_student_entrolled.php?pagetype=report&enroll=1&ex_id=<?php echo $db_row['ex_id']; ?>" class="text-info">Enrollment</a>&nbsp;
                                                        
                                                          <a href="exam_student_entrolled.php?pagetype=report&addresult=1&ex_id=<?php echo $db_row['ex_id']; ?>" class="text-danger">Result</a>&nbsp;
                                                        
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
            <script>
            $("#ex_date").datepicker({
                                            format: 'dd-mm-yyyy',
                                            autoclose: true, });
            </script>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
