<?php
include("includes/application_top.php");
include("../includes/class/attendance.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Attendance";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$att_id= get_rdata('att_id');
$att_identity_id= get_rdata('att_identity_id');
$att_date= get_rdata('att_date');
$att_phone_1= get_rdata('att_phone_1');
$att_phone_2= get_rdata('att_phone_2');
$att_email= get_rdata('att_email');
$att_address_1= get_rdata('att_address_1');
$att_address_2= get_rdata('att_address_2');
$att_city= get_rdata('att_city');
$att_photo= get_rdata('att_photo');
$att_st_id= get_rdata('att_st_id');
$att_subject= get_rdata('att_subject');
$att_experience= get_rdata('att_experience');
$att_sc_id= get_rdata('att_sc_id');
$att_status= get_rdata('att_status','A');
$att_create_date= get_rdata('att_create_date');
$att_create_by_id= get_rdata('att_create_by_id');
$att_update_date= get_rdata('att_update_date');
$att_update_by_id= get_rdata('att_update_by_id');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Attendance Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Attendance Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Attendance Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'att_id');
$order = get_rdata('order', 'asc');
$client_arrow = $att_date_arrow = $att_date_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'att_date') {
        $sc_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $client_arrow = 'glyphicon glyphicon-chevron-up';
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
    
            $sm_attendance_master = new attendance();
            $sm_attendance_master->action = 'delete';
            $del_where = "att_id = ". $id;
            if (session_get('admin_login_type') == 'school') {
                    $del_where.=" and att_sc_id= " . session_get('admin_sc_id');
            }
            $sm_attendance_master->where = $del_where;
            $result = $sm_attendance_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                    $successmsg = "Attendance Has Been Deleted Successfully";
            }
}

//searching and pagination
$condition = '1';
if ($att_date != '') {
    $condition.=" and 	att_date LIKE '%" . $att_date . "%'";
}

if (session_get('admin_login_type') == 'school') {
    $condition.=" and sc_id= " . session_get('admin_sc_id');
}

$condition.=" order by " . $order_by . ' ' . $order;
$select = " att_date, att_id, att_stu_medium, cl_name, std_name , att_attended , att_absent ";
$table = "sm_attendance_b INNER JOIN sm_school_master ON (sc_id=att_sc_id) INNER JOIN sm_standard ON (std_id = att_std_id) INNER JOIN sm_class  ON (cl_id = att_cl_id ) ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select, $condition, $per_page, 10, "per_page=" . $per_page . "&att_date=" . $att_date . "&order by=" . $order_by . "&order=" . $order);
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
                        Manage Attendance
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Attendance</li>
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
                                                <label class="col-sm-3 control-label">Attendance Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="att_date" id="att_date"  placeholder="Enter Attendance Name" value="<?php echo $att_date; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>                                                                             
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">                                        
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_attendance.php'">Cancel</button>
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
                                                <th align="center" >Medium</th>
                                                <th align="center" >Standard</th>
                                                <th align="center" >Class</th>
                                                <th align="center" >Date</th>
                                                <th align="center" >Attended</th>
                                                <th align="center" >Absent</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['att_stu_medium']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['std_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['cl_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo convert_db_to_disp_date($db_row['att_date']); ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['att_attended']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['att_absent']; ?></td>
                                                        <td><center>
                                                              <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="delete glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['att_id']; ?>,'Attendance')"></a></center></td>
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
