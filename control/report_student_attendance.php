<?php
include("includes/application_top.php");
include("../includes/class/student.php");

$page_title = "Student Attendance";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$stu_id= get_rdata('stu_id');
$stu_gr_no= get_rdata('stu_gr_no');
$stu_first_name= get_rdata('stu_first_name');
$stu_middle_name= get_rdata('stu_middle_name');
$stu_batchtime= get_rdata('stu_batchtime');
$stu_last_name = get_rdata('stu_last_name');
$att_month = get_rdata('att_month',date('m'));
$att_year = get_rdata('att_year',date('Y'));

$stu_status= get_rdata('stu_status','A');
$stu_br_id= get_rdata('stu_br_id');
$stu_current_course= get_rdata('stu_current_course');
$export_data = get_rdata('export_data','');

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Student Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Student Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Student Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Student has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
      $successmsg = "Student has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' bt_name, stu_gr_no ');
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

// step 3: make new object of user class
// delete user code
/*
if ($act == 'delete' && $id != 0) {

            $sm_student = new student();
            $sm_student->action = 'delete';

            $del_where = "stu_br_id = ".$tmp_admin_id." AND stu_id = ". $id;

            $sm_student->where = $del_where;

            $result = $sm_student->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                    $successmsg = "Student Has Been Deleted Successfully";
            }
}
*/

//searching and pagination
$condition = '1';
if ($stu_gr_no != '') {
    $condition.=" and 	stu_gr_no LIKE '%" . $stu_gr_no . "%'";
}

if ($stu_batchtime != '') {
    $condition.=" and 	stu_batchtime = '" . $stu_batchtime . "'";
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
$table = "sm_student LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   LEFT JOIN sm_batch_time ON (stu_batchtime = bt_id )  ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$select_f = "brt_working_days, stu_parent_mobile_no, stu_whatsappno, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name,be_name_for,  	sc_half_course, co_name, bt_name ";
if ($export_data == 'Export')
{
    $mfinalquery = "SELECT $select_f FROM $table WHERE ".$condition;
    $data_header= array("Sr.","Gr No.","Name","B. Type","Belt","B. Time");
    
    export_data("Report Student Attendance","report_student_attendance",$data_header,$mfinalquery);
    exit(0);
}
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&att_month=" . $att_month."&att_year=" . $att_year . "&stu_batchtime=" . $stu_batchtime . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}
$arr_branch_details =  get_branch_details(session_get("id"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
        <title><?php echo $page_title; ?></title>
        <?php include("includes/include_files.php"); ?>
        <style>
        #tbl_student_attendance tr td { white-space:nowrap; }
        .att_A { color:red; }
        .att_P { color:green; }
        </style>
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
                        <a href="report_manage_event.php" class="btn btn btn-default">Event Attendance</a>
                        <a href="report_factulty_attendance.php" class="btn btn btn-default">Faculty Attendance</a>
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
                                <input type="hidden" name="export_data" id="export_data">
                                    <input type="hidden" name="act" id="act">
                 		<input type="hidden" value="0" name="id" id="id">
                                    <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Month</label>
                                            <div class="col-sm-9">
                                            <select  class="form-control"  id="att_month" name="att_month" style="width: 100px;
display: inline-block;" >
                                                    <option value="1" <?php if($att_month==1) echo 'selected="selected"'; ?>>Jan</option>
                                                    <option value="2" <?php if($att_month==2) echo 'selected="selected"'; ?>>Feb</option>
                                                    <option value="3" <?php if($att_month==3) echo 'selected="selected"'; ?>>Mar</option>
                                                    <option value="4" <?php if($att_month==4) echo 'selected="selected"'; ?>>Apr</option>
                                                    <option value="5" <?php if($att_month==5) echo 'selected="selected"'; ?>>May</option>
                                                    <option value="6" <?php if($att_month==6) echo 'selected="selected"'; ?>>Jun</option>
                                                    <option value="7" <?php if($att_month==7) echo 'selected="selected"'; ?>>Jul</option>
                                                    <option value="8" <?php if($att_month==8) echo 'selected="selected"'; ?>>Aug</option>
                                                    <option value="9" <?php if($att_month==9) echo 'selected="selected"'; ?>>Sep</option>
                                                    <option value="10" <?php if($att_month==10) echo 'selected="selected"'; ?>>Oct</option>
                                                    <option value="11" <?php if($att_month==11) echo 'selected="selected"'; ?>>Nov</option>
                                                    <option value="12" <?php if($att_month==12) echo 'selected="selected"'; ?>>Dec </option>
                                                    </select>
                                                    &nbsp;&nbsp;
                                                    <select   class="form-control" id="att_year" style="width: 100px; display: inline-block;" name="att_year" >
                                                    <?php
                                                    for($yi=2017; $yi<=date('Y'); $yi++ ) {
                                                    ?>
                                                    <option value="<?php echo $yi; ?>" <?php if($att_year==$yi) echo 'selected="selected"'; ?>><?php echo $yi; ?></option>
                                                    <?php } ?>
                                                    </select>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Batch Time</label>
                                            <div class="col-sm-9">

                                            <select id="stu_batchtime" name="stu_batchtime" class="form-control">
                                               <option value="" >-- All --</option>
                                                  <?php
                                                  $data_arr_input = array();
                                                  $data_arr_input['select_field'] = 'bt_name ,bt_id';
                                                  $data_arr_input['table'] = 'sm_batch_time';
                                                  $data_arr_input['where'] = " bt_br_id = ".$tmp_admin_id." AND bt_status  = 'A' ";
                                                  $data_arr_input['key_id'] = 'bt_id';
                                                  $data_arr_input['key_name'] = 'bt_name';
                                                  $data_arr_input['current_selection_value'] = $stu_batchtime;
                                                  $data_arr_input['order_by'] = 'bt_id';

                                                  display_dd_options($data_arr_input);
                                                  ?>
                                              </select>

                                            </div>
                                        </div>
                                      </div>

                                      
                                        
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'report_student_attendance.php'">Cancel</button>
                                        <!-- <button type="button" onclick="print_attendance('tbl_student_attendance','<?php echo $arr_branch_details["name"]; ?>','report_student_attendance','');" class="btn btn-warning">Print</button>
                                        <button type="button" onclick="export_data_js('form1');" class="btn btn-info">Export</button> -->
                                        <!-- <div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Export
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#" onclick="print_attendance('tbl_student_attendance', '<?php echo $arr_branch_details["name"]; ?>', 'report_student_attendance', '');">PDF</a></li>
        <li><a class="dropdown-item" href="#" onclick="export_data_js('form1');">Excel</a></li>
                                                    </ul>
</div> -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Export <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="print_attendance('tbl_student_attendance', '<?php echo $arr_branch_details["name"]; ?>', 'report_student_attendance', '');">Export to PDF</a></li>
                                                <li><a href="#" onclick="exportToExcel()">Export to Excel</a></li>
                                            </ul>
                                        </div>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body" style="overflow-x:auto; padding-right:5px; ">
                                    <table id="tbl_student_attendance"  class="table table-bordered table-hover"  >
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center" width="20px" ><a href="manage_student.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_gr_no&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $stu_gr_no_arrow; ?>"></span></a></th>
                                                <th align="center" width="20px"><a href="manage_student.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_first_name&order=<?php echo $order; ?>">Name <span class="<?php echo $stu_first_name_arrow; ?>"></span></a></th>
                                                <th align="center" width="20px">B. Type</th>
                                                <th align="center" width="20px">B. Time</th>
                                                <?php 
                                                $today_year = date('Y');
                                                $today_month = date('m');
                                                

                                                $student_attendance =  get_attendance_result_set($att_month,$att_year,$tmp_admin_id);
                                                $student_attendance_array = array();    
                                                if ($student_attendance["status"]=="success" && $student_attendance["count"] > 0)
                                                {
                                                    foreach($student_attendance["res"] as $key=>$val) 
                                                    { 
                                                        $student_attendance_array[$val["check_key"]] = $val["sta_att_status"];
                                                    }
                                                }
                                                
                                                $total_days =   cal_days_in_month(CAL_GREGORIAN, $att_month, $att_year);
                                                // echo "*-".$att_month."-*-".$att_year-"--*--".$total_days."*"; exit(0);
                                                for ($cal_i= 1; $cal_i <=$total_days; $cal_i++)
                                                {
                                                    $timestamp = strtotime($att_year.'-'.$att_month.'-'.$cal_i);
                                                    $day = strtolower(date('D', $timestamp));
                                                    $timestamp = strtoupper( substr($day,0,1));
                                                    echo '<th align="center">'.$timestamp.'<br>'.$cal_i.'</th>';
                                                }
                                                ?>
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
                                                    $stu_brt_name= $stu_be_name= $stu_co_name = '';
                                                    if ($db_row['brt_name'] == '' && $db_row['be_name'] == '' && $db_row['co_name'] == '')  
                                                    {
                                                        $arr_get_student_course =  get_student_lastet_course($db_row['stu_id']);
                                                        if ($arr_get_student_course["error_message"] == '')
                                                        {
                                                            $stu_brt_name= $arr_get_student_course['brt_name'] ;
                                                            $stu_be_name= $arr_get_student_course['be_name'] ;
                                                            $stu_co_name = $arr_get_student_course['co_name'] ;
                                                        }
                                                        
                                                    } 
                                                    else
                                                    {
                                                        $stu_brt_name= $db_row['brt_name'] ;
                                                         $stu_be_name= $db_row['be_name'] ;
                                                         if ($db_row["sc_half_course"]==1)
                                                            $stu_be_name= $db_row['be_name_for'] ;
                                                         $stu_co_name = $db_row['co_name'] ;
                                                    }
                                                    



                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>

                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>
                                                        
                                                        <td style="padding-left:10px;"><?php echo $stu_brt_name; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['bt_name']; ?></td>
                                                <?php                                             
                                                // print_r($student_attendance_array);               
                                                for ($cal_i= 1; $cal_i <=$total_days; $cal_i++)
                                                {
                                                    $find_key = substr("0".$cal_i,-2)."-".$db_row['stu_id'];
                                                    $brt_working_days = strtolower($db_row['brt_working_days']);
                                                    $brt_working_days_array = explode(",",$brt_working_days);
                                                    $timestamp = strtotime($att_year.'-'.$att_month.'-'.$cal_i);
                                                    $day = strtolower(date('D', $timestamp));
                                                    $default_value = '';
                                                    if (!(in_array ( $day, $brt_working_days_array)))
                                                    {
                                                        $default_value = '-';
                                                    }
                                                   
                                                    echo '<td style="padding-left:10px;" class="att_'.(isset($student_attendance_array[$find_key])?$student_attendance_array[$find_key]:'').'">'.(isset($student_attendance_array[$find_key])?$student_attendance_array[$find_key]:$default_value).'</td>';
                                                }
                                                ?>
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
        <script>
            function exportToExcel()
            {
                var excelData = '<table border="1">';
                var tableHeaders = document.querySelectorAll('#tbl_student_attendance thead tr th');
                excelData += '<tr>';
                tableHeaders.forEach(function (header) {
                excelData += '<th>' + header.textContent + '</th>';
                });
                excelData += '</tr>';
                var tableRows = document.querySelectorAll('#tbl_student_attendance tbody tr');
                tableRows.forEach(function (row) {
                excelData += '<tr>';
                 var rowCells = row.querySelectorAll('td');
                rowCells.forEach(function (cell) {
                    excelData += '<td>' + cell.textContent + '</td>';
                });
                excelData += '</tr>';
                 });
                excelData += '</table>';
                var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'student_attemdamce_data.xls';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
        </script>
    </body>
</html>