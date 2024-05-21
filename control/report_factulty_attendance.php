<?php
include("includes/application_top.php");
include("../includes/class/faculty.php");

$page_title = "Faculty Attendance";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$fta_fac_id= get_rdata('fac_id');
//print_r($fta_fac_id);exit(0);
$att_month = get_rdata('att_month',date('m'));
$att_year = get_rdata('att_year',date('Y'));
$stu_status= get_rdata('fac_status','A');
//print_r($stu_status);exit(0);
$fac_br_id= get_rdata('fta_br_id',1);
$export_data = get_rdata('export_data','');



// Set success message based on msg ID
$msg = get_rdata('msg', '');
$successmsg = '';


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' fac_id ');
$order = get_rdata('order', 'asc');
$stu_first_name_arrow = $stu_gr_no_arrow=  'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'fac_name') {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'fac_id') {
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
$condition = ' DATE_FORMAT(fta_att_date,"%Y") = '.$att_year.' AND DATE_FORMAT(fta_att_date,"%m") = '.$att_month;


if ($fta_fac_id != '') {
    $condition.=" and 	fta_fac_id = '" . $fta_fac_id . "'";
}


if ($tmp_type != 'admin') {
    $condition.=" and fac_br_id= " . $tmp_admin_id;
}

$condition.=' ORDER BY DATE_FORMAT(fta_att_date,"%d") ASC';

$table = " sm_faculty_attendance  INNER JOIN sm_faculty ON (fta_fac_id = fac_id) ";
// $q = 'SELECT  FROM   WHERE DATE_FORMAT(fta_att_date,"%Y") = '.$year.' AND DATE_FORMAT(fta_att_date,"%m") = '.$month.'  AND fta_br_id = '.$br_id.' ORDER BY DATE_FORMAT(fta_att_date,"%d") ';

$select_f = ' CONCAT(DATE_FORMAT(fta_att_date,"%d"),"-",fta_fac_id) as check_key,fta_att_status, fac_name ';

// echo "SELECT $select_f FROM $table WHERE $condition";
if ($export_data == 'Export')
{
    $mfinalquery = "SELECT $select_f FROM $table WHERE ".$condition;
    $data_header= array("Sr.","Gr No.","Name");
    
    export_data("Report Faculty Attendance","report_faculty_attendance",$data_header,$mfinalquery);
    exit(0);
}
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&att_month=" . $att_month."&att_year=" . $att_year . "&fta_fac_id=" . $fta_fac_id . "&order by=" . $order_by . "&order=" . $order);
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
                        <a href="report_student_attendance.php" class="btn btn btn-default">Student Attendance</a>
                        <a href="report_manage_event.php" class="btn btn btn-default">Event Attendance</a>
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
                                            <label class="col-sm-3 control-label">Faculty</label>
                                            <div class="col-sm-9">

                                            <select id="fta_fac_id" name="fta_fac_id" class="form-control">
                                               <option value="" >-- All --</option>
                                                  <?php
                                                  $data_arr_input = array();
                                                  $data_arr_input['select_field'] = 'fac_name ,fac_id';
                                                  $data_arr_input['table'] = 'sm_faculty';
                                                  $data_arr_input['where'] = " fac_status  = 'A' ";
                                                  // fac_br_id = ".$tmp_admin_id."
                                                  if ($tmp_type != 'admin') 
                                                  {
                                                    $data_arr_input['where'] .=" AND fac_br_id= " . $tmp_admin_id;
                                                  }
                                                  $data_arr_input['key_id'] = 'bt_id';
                                                  $data_arr_input['key_name'] = 'fac_name';
                                                  $data_arr_input['current_selection_value'] = $fta_fac_id;
                                                  $data_arr_input['order_by'] = 'fac_id';
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
                                                <!-- <th align="center" width="20px" ><a href="manage_faculty.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=fac_id&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $stu_gr_no_arrow; ?>"></span></a></th> -->
                                                <th align="center" width="20px"><a href="manage_faculty.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=fac_name&order=<?php echo $order; ?>">Name <span class="<?php echo $stu_first_name_arrow; ?>"></span></a></th>
                                                <!-- <th align="center" width="20px">B. Type</th>
                                                <th align="center" width="20px">B. Time</th> -->
                                                <?php 
                                               // $today_year = date('Y');
                                               // $today_month = date('m');
                                                

                                                $faculty_attendance =  get_faculty_attendance($att_month,$att_year,$tmp_admin_id);
                                                
                                                //print_r($faculty_attendance);exit(0);
                                                $faculty_attendance_array = array();    
                                                if ($faculty_attendance["status"]=="success" && $faculty_attendance["count"] > 0)
                                                {
                                                    foreach($faculty_attendance["res"] as $key=>$val) 
                                                    { 
                                                        $faculty_attendance_array[$val["check_key"]] = $val["fta_att_status"];
                                                       // print_r($faculty_attendance_array[$val["check_key"]]);
                                                    }
                                                    
                                                }
                                                
                                                $total_days =   cal_days_in_month(CAL_GREGORIAN, $att_month, $att_year);
                                                //print_r($total_days);exit();
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
$unique_records = array();
if ($objData) {
    //for ($i = 1; $db_row = $objData->fetch(); $i++)
    while ($db_row = $objData->fetch()) {
        $srNo++;
        if ($srNo % 2 == 0) {
            $class = 'even';
        } else {
            $class = 'odd';
        }
        // Check if the current record has already been displayed
        $record_identifier = $db_row['fac_name']; // Change this to the appropriate identifier for your records
        if (in_array($record_identifier, $unique_records)) {
            // If the record has already been displayed, skip displaying it again
            continue;
        }

        // Add the current record to the array of displayed records
        $unique_records[] = $record_identifier;
        ?> 
        <tr class="<?php echo $class; ?>">
            <td><center><?php echo $srNo; ?></center></td>
            <td style="padding-left:10px;"><?php echo $db_row['fac_name']; ?></td>
            <?php
            // Initialize an array to keep track of displayed dates
            $displayed_dates = array();
            for ($cal_i = 1; $cal_i <= $total_days; $cal_i++) {
                $formatted_cal_i = sprintf("%02d", $cal_i);
                $find_key = $formatted_cal_i . "-" . substr($db_row['check_key'], -2);
                $timestamp = strtotime($att_year . '-' . $att_month . '-' . $cal_i);
                $day = strtolower(date('D', $timestamp));
                $default_value = '-';
                if (!in_array($find_key, $displayed_dates)) {
                    // Add the current date to the displayed dates array
                    $displayed_dates[] = $find_key;
                    // Display the attendance for this date
                   // echo "find_key: " . $find_key . ", Attendance: " . (isset($faculty_attendance_array[$find_key]) ? $faculty_attendance_array[$find_key] : '') . "<br>";
                    echo '<td style="padding-left:10px;" class="att_' . (isset($faculty_attendance_array[$find_key]) ? $faculty_attendance_array[$find_key] : '') . '">' . (isset($faculty_attendance_array[$find_key]) ? $faculty_attendance_array[$find_key] : $default_value) . '</td>';
                } else {
                    // If the date has already been displayed, output an empty cell
                    echo "<td></td>";
                }
            }
            ?>
        </tr>
        <?php
       // $displayed_records[] = $db_row['fac_name'];
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