<?php
include("includes/application_top.php");
include("../includes/class/course_belt.php");


$page_title = "Manage Course Belt";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Course Belt Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Course Belt Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Course Belt Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'be_sort_order');
$order = get_rdata('order', 'asc');
$be_sort_order_arrow = $be_name_arrow = 'glyphicon glyphicon-chevron-down';
/*
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
*/
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {

    if ($errormsg == '') {
        $course_belt = new course_belt();
        $course_belt->action = 'delete';
        $del_where = "cb_br_id = ".$tmp_admin_id." AND cb_id = " . $id;

        $course_belt->where = $del_where;

        $result = $course_belt->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            $successmsg = "Course Belt Has Been Deleted Successfully";
        }
    }
}


//searching and pagination
// $condition = '1';
$condition = " co_br_id = cb_br_id AND be_br_id= cb_br_id ";


$select_c = " be_name, co_name , cb_id ";
// $condition.=" order by co_name, be_name ASC " . $order_by . ' ' . $order;
$condition.=" order by co_name, be_name ASC ";
$table = " sm_course_belt INNER JOIN sm_belt ON (be_id = cb_be_id) INNER JOIN sm_course ON (cb_co_id = co_id)  ";
// echo "SELECT ".$select_c." FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select_c, "$condition", $per_page, 10, "per_page=" . $per_page );
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

//get total records from project table
// $course_belt = new standard();
//$course_belt->cquery = "select * from $table WHERE $condition";
//$course_belt->action = 'get';
//$result = $course_belt->process();
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
                        <?php echo $page_title; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $page_title; ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
<?php include("includes/messages.php"); ?>
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                      <form class="form-horizontal" name="form1" id="form1" method="post" onsubmit="return validate_add_edit_form();">
                          <input type="hidden" name="act" id="act">
                              <input type="hidden" value="0" name="id" id="id">
                                  </form>

                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="box">
                                                        <div class="box-body">
                                                            <table id="example2" class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th align="center" width="70px">Sr.No</th>
                                                                        <th align="center">Course</th>
                                                                        <th align="center">Belt</th>
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
                                                                                <td style="padding-left:10px;"><?php echo $db_row['co_name']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['be_name']; ?></td>


                                                                                <td><center>
                                                                                        <a href="add_edit_course_belt.php?id=<?php echo $db_row['cb_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                                                        <a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" data-toggle="modal" data-target="#ConfirmDelete" onclick="delete_record(<?php echo $db_row['cb_id']; ?>, 'Course Belt')"></a></center></td>
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
