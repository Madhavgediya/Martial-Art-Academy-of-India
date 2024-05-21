<?php
include("includes/application_top.php");
// include("../includes/class/branch.php");
if ($tmp_type != 'admin')
{    header("location:index.php"); }
//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Branch";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$br_id = get_rdata('br_id');
$br_name = get_rdata('br_name');
$br_contact_p_name1 = get_rdata('br_contact_p_name1');
$br_contact_p_name1 = get_rdata('br_contact_p_name1');
$br_contact_p_phone1 = get_rdata('br_contact_p_phone1');
$br_contact_p_email1 = get_rdata('br_contact_p_email1');
$br_contact_p_name2 = get_rdata('br_contact_p_name2');
$br_contact_p_phone2 = get_rdata('br_contact_p_phone2');
$br_contact_p_email2 = get_rdata('br_contact_p_email2');
$br_add_1 = get_rdata('br_add_1');
$br_add_2 = get_rdata('br_add_2');
$br_city = get_rdata('br_city');
$br_district = get_rdata('br_district');
$br_state_id = get_rdata('br_state_id',0);
$br_country = get_rdata('br_country','India');
$br_postalcode = get_rdata('br_postalcode');
$br_status = get_rdata('br_status','A');
$br_create_date = $cur_date;
$br_create_by_id =session_get("admin_id");
$br_update_date = $cur_date;
$br_update_by_id = session_get("admin_id");



// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Branch Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Branch Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Branch Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'br_name');
$order = get_rdata('order', 'asc');
$client_arrow = $br_name_arrow = $br_name_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'br_name') {
        $br_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $br_name_arrow = 'glyphicon glyphicon-chevron-up';
    }

    if ($order_by == 'br_login') {
        $br_login_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $br_login_arrow = 'glyphicon glyphicon-chevron-up';
    }

    if ($order_by == 'br_contact_p_name1') {
        $br_contact_p_name1_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $br_contact_p_name1_arrow = 'glyphicon glyphicon-chevron-up';
    }

    if ($order_by == 'br_contact_p_phone1') {
        $br_contact_p_phone1_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $br_contact_p_phone1_arrow = 'glyphicon glyphicon-chevron-up';
    }

    if ($order_by == 'br_contact_p_email1') {
        $br_contact_p_email1_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $br_contact_p_email1_arrow = 'glyphicon glyphicon-chevron-up';
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
/*
    $arr_check_delete = validate_before_delete("sm_student", "stu_br_id = " . $id);
    if ($arr_check_delete["error_message"] != '') {
        $errormsg = $arr_check_delete["error_message"];
    } else if ($arr_check_delete["found_reference"] == true) {
        $errormsg = "Delete process can not be completed because of found Branch reference with student";
    }
    */
    /*
    if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_attendance", "att_br_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of found Branch reference with attendance";
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
     if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_timetable", "tt_br_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of found standard reference with timetable";
        }
    }
*/

    if ($errormsg == '') {
        $sm_branch = new branch();
        $sm_branch->action = 'delete';
        $del_where = "br_id = " . $id;

        $sm_branch->where = $del_where;

        $result = $sm_branch->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            $successmsg = "Branch Has Been Deleted Successfully";
        }
    }
}


//searching and pagination
// $condition = '1';
$condition = " br_type= 'branch'  ";
if ($br_name != '') {
    $condition.=" and br_name LIKE '%" . $br_name . "%'";
}

/*
if (session_get('admin_login_type') == 'school') {
    $condition.=" and sc_id= " . session_get('admin_sc_id');
}
*/

$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_branch ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, '*', "$condition", $per_page, 10, "per_page=" . $per_page . "&br_name=" . $br_name . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

//get total records from project table
// $sm_branch = new standard();
//$sm_branch->cquery = "select * from $table WHERE $condition";
//$sm_branch->action = 'get';
//$result = $sm_branch->process();
//if ($result['status'] == 'failure') {
//    $errormsg = $result['errormsg'];
//} else {
//    if ($result['count'] > 0) {
//        $total_rows = $result['count'];
//    }
//}
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
                        Manage Branch
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Branch</li>
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
                                                        <label class="col-sm-3 control-label">Branch Name</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="br_name" id="br_name"  placeholder="Enter Branch Name" value="<?php echo $br_name; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.box-body -->
                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-info">Search</button>
                                                <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_branch.php'">Cancel</button>
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
                                                                        <th align="center"><a href="manage_branch.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=br_name&order=<?php echo $order; ?>">Name <span class="<?php echo $br_name_arrow; ?>"></span></a></th>
                                                                        <th align="center"><a href="manage_branch.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>">Login</a></th>
                                                                        <th align="center"><a href="manage_branch.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>">Contact Person</a></th>
                                                                        <th align="center"><a href="manage_branch.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>">Contact No</a></th>
                                                                        <th align="center"><a href="manage_branch.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>">Contact Email</a></th>
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
                                                                                <td style="padding-left:10px;"><?php echo $db_row['br_name']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['br_login']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['br_contact_p_name1']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['br_contact_p_phone1']; ?></td>
                                                                                <td style="padding-left:10px;"><?php echo $db_row['br_contact_p_email1']; ?></td>

                                                                        <td style="padding-left:10px;"><?php echo ($db_row['br_status']=='A'?'Active':'Inactive'); ?></td>
                                                                                <td><center>
                                                                                        <a href="add_edit_branch.php?id=<?php echo $db_row['br_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                                                        <a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" data-toggle="modal" data-target="#ConfirmDelete" onclick="delete_record(<?php echo $db_row['br_id']; ?>, 'Branch')"></a></center></td>
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
