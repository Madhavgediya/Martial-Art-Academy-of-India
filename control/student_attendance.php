<?php
include("includes/application_top.php");
include("../includes/class/batchtype.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Student Attendance";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$act2 = get_rdata("act2");

$stu_batchtime = get_rdata('stu_batchtime');
$sta_att_date = get_rdata('sta_att_date');
$stu_att_check = get_rdata('stu_att_check');
$selectedDate = get_rdata('sta_att_date', '');
// echo "mayur".strtoupper(date("D"))."dhudasia";
// exit(0);

if ($sta_att_date == '')
{ $sta_att_date = DBtoDisp($cur_date);  }

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

if ($act2 == "process_attendance")
{
    
    $stu_att_check = get_rdata("stu_att_check");
  // adding result for each categories

  for ($i = 0; $i <count($stu_att_check); $i++) {

// echo "**".$stu_att_check[$i]."**".get_rdata($stu_att_check[$i])."**";
// exit(0);
      $process_id_arr_ids = explode("_", $stu_att_check[$i]);

      if (count($process_id_arr_ids)>4)
      {
        $arr_data = array();
        $arr_data["sta_id"] = $process_id_arr_ids[0];
        $arr_data["sta_att_status"] = get_rdata($stu_att_check[$i]);
        $arr_data["sta_att_date"] = disptoDB($sta_att_date);
        $arr_data["sta_brt_id"] = $process_id_arr_ids[1];
        $arr_data["sta_co_id"] = $process_id_arr_ids[2];
        $arr_data["sta_be_id"] = $process_id_arr_ids[3];
        $arr_data["sc_id"] = $process_id_arr_ids[4];
        $arr_data["sta_stu_id"] = $process_id_arr_ids[5];
        $arr_data["sta_br_id"] = $process_id_arr_ids[6];
        $res_attendance =  add_update_attendance($arr_data);
        if ($res_attendance!='')
        {
           $errormsg = $res_attendance;
           break;
        }
      }

  }

   if ($errormsg == '')
   {
      $successmsg = "Student Attendance has been saved successfully";
   }
}



$total_rows = '';
$page = get_rdata('page',1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' stu_gr_no ');
$order = get_rdata('order', 'asc');
$batch_time = get_rdata('stu_batchtime', $stu_batchtime);
if(isset($_POST['submit'])) {
    header('Location:student_attendance.php?page='.$page."&per_page=" . $per_page . "&order by=" . $order_by . "&order=" . $order . "&stu_batchtime=". $batch_time. "&sta_att_date=". $sta_att_date);
} else {
    $page = get_rdata("page", 1);
}
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
if(isset($_POST['submit']) || $batch_time) {
    $find_day =  strtoupper(date('D',strtotime($sta_att_date)));

    $condition = " stu_status = 'A' AND stu_br_id=".$tmp_admin_id;
    $condition.= " AND FIND_IN_SET ('".$find_day."',UPPER(brt_working_days)) "	;
    if ($stu_batchtime  != 0) {
        //  $condition = ' stu_status = "Y" AND stu_br_id='.$tmp_admin_id;
        
        $condition.= " AND 	stu_batchtime =" . $stu_batchtime;
    }
        $condition.= " order by " . $order_by . ' ' . $order;
    //  exit(0);
        $table = " sm_student INNER JOIN sm_student_course
        ON (sc_stu_id = stu_id AND sc_is_current = 1)
        LEFT JOIN sm_belt ON (sc_be_id = be_id )
        LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id )
        LEFT JOIN sm_course ON (sc_co_id = co_id )
        LEFT JOIN sm_student_attendance ON (sta_stu_id  = stu_id AND sta_co_id = sc_co_id AND sta_be_id = sc_be_id AND sta_att_date  = '".disptoDB($sta_att_date)."')  ";

        $select_f = " sc_stu_id,stu_br_id, IF(sta_id IS NULL,0,sta_id) sta_id ,sta_att_status, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name,co_name, sc_joined_date, sc_total_fee, sc_total_paid, sc_full_fee_paid, sc_br_id, sc_brt_id, sc_co_id, sc_be_id, sc_id, sc_cd_id";

        for($i=0; $i<=7;$i++) {
            $select_f .= ",(Select sta_att_status as sta_att_status_".$i." FROM sm_student_attendance as sm_student_attendance_".$i." WHERE  sm_student_attendance_".$i.".sta_stu_id = sta_stu_id AND sm_student_attendance_".$i.".sta_co_id = sc_co_id AND sm_student_attendance_".$i.".sta_be_id = sc_be_id AND sta_att_date = date(DATE_SUB('".disptoDB($sta_att_date)."', INTERVAL ".$i." DAY)) LIMIT 1) as sta_att_status_".$i."";
        }

        $pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&order by=" . $order_by . "&order=" . $order . "&stu_batchtime=". $batch_time);
        $objData = $pageObj->paginate();
       // print_r($objData);exit(0);
        $total_rows = $pageObj->totRows();

       
        if ($order == 'asc') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }

        unset($_POST);

    }
?>
<input type="hidden" id="selectedDate" value="<?php echo $selectedDate; ?>">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
        <title><?php echo $page_title; ?></title>
        <?php include("includes/include_files.php"); ?>
        <!-- <?php
    $selectedDateTime = DateTime::createFromFormat('d-m-Y', $selectedDate);
    if($selectedDateTime){
    $selectedDateTime->modify('last Monday');
    $startOfWeek = $selectedDateTime->format('d-m-Y');

    // Create an array to store the range of days for one week (seven days)
    $dateRange = array();

    // Loop through the days within the one-week date range
    for ($i = 0; $i < 8; $i++) {
        $dateRange[] = $selectedDateTime->format('d');
        $selectedDateTime->modify('+1 day');
    }
}else{
    echo "Invalid Date Format.";
}
    ?> -->
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
                    <form class="form-horizontal" name="form1" id="form1" method="post" >
                        <input type="hidden" name="act" id="act">
                        <input type="hidden" name="act2" id="act2">
                 		<input type="hidden" value="0" name="id" id="id">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Search</h3>
                                    </div>
                                    <div class="box-body">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Batch Time</label>
                                            <div class="col-sm-9">
                                            <select required id="stu_batchtime" name="stu_batchtime" class="form-control">
                                               <option value="" >-- Select Batch Time --</option>
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
                                                <option value='all' <?php if($stu_batchtime=='all') { echo 'selected="selected"'; }?>>View All</option>
                                              </select>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Date</label>
                                            <div class="col-sm-9">
                                                <input required  type="text" readonly name="sta_att_date" id="sta_att_date"  placeholder="Attendance Date" value="<?php echo $sta_att_date; ?>" class="form-control" />
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" name="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'student_attendance.php'">Cancel</button>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($stu_batchtime  != 0 || $stu_batchtime == 'all') { ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center" >Gr No.</th>
                                                <th align="center">Name</th>
                                                <th align="center">B. Type</th>
                                                <th align="center" >Belt</th>
                                                <th align="center" >Couse</th>
		              <th align="center" id="dateRange">Last Week: <span id="dateRange"></span> 
                </div></div></th>
                                                <th align="center" class="t_align_center"  width="75px">Action <input type="button" class="btn btn-info" onclick="mark_all_attendance_present();" value="P"></th>
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

                                                    $p_id= $db_row['sta_id']."_".$db_row['sc_brt_id']."_". $db_row['sc_co_id']."_".$db_row['sc_be_id']."_".$db_row['sc_id']."_".$db_row['sc_stu_id']."_".$db_row['stu_br_id'];
                                                    $sta_att_status  = !empty($db_row["sta_att_status_0"]) ? $db_row["sta_att_status_0"] : '';
                                                    $sta_att_status_1  = !empty($db_row["sta_att_status_1"]) ? $db_row["sta_att_status_1"] : '-';
                                                    $sta_att_status_2  = !empty($db_row["sta_att_status_2"]) ? $db_row["sta_att_status_2"] : '-';
                                                    $sta_att_status_3  = !empty($db_row["sta_att_status_3"]) ? $db_row["sta_att_status_3"] : '-';
                                                    $sta_att_status_4  = !empty($db_row["sta_att_status_4"]) ? $db_row["sta_att_status_4"] : '-';
                                                    $sta_att_status_5  = !empty($db_row["sta_att_status_5"]) ? $db_row["sta_att_status_5"] : '-';
                                                    $sta_att_status_6  = !empty($db_row["sta_att_status_6"]) ? $db_row["sta_att_status_6"] : '-';
                                                    $sta_att_status_7  = !empty($db_row["sta_att_status_7"]) ? $db_row["sta_att_status_7"] : '-';
                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['brt_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['be_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['co_name']; ?></td>
			                                             <td>
                                                            <?=$sta_att_status_7?>,
                                                            <?=$sta_att_status_6?>,
                                                            <?=$sta_att_status_5?>,
                                                            <?=$sta_att_status_4?>,
                                                            <?=$sta_att_status_3?>,
                                                            <?=$sta_att_status_2?>,
                                                            <?=$sta_att_status_1?>
                                                        </td>
                                                        <td><center>
                                                          <input type="checkbox" checked class="hidden" value="<?php echo $p_id;?>" name="stu_att_check[]" />
                                                            <select class="cl_student_attendance" name="<?php echo $p_id;?>" >
                                                              <option <?php if($sta_att_status == '') echo 'selected'; ?> value="">-</option>
                                                              <option <?php if($sta_att_status == 'P') echo 'selected'; ?>  value="P">P</option>
                                                              <option <?php if($sta_att_status == 'A') echo 'selected'; ?>  value="A">A</option>
			       <option <?php if($sta_att_status == 'L') echo 'selected'; ?>  value="L">L</option>	
                                                            </select>
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
                    </div>
                    <div class="row">
                      <div class="col-xs-12 text-right">
                        <input type="submit" name="process_btn" onclick="do_exam_attendance();" class="btn btn-info" id="process_btn" value="process" />
                      </div>
                    </div>
                    <?php } ?>
                    </form>
                </section>
            </div>

            <script type="text/javascript">
function show_hide_fees(action)
{
  if (action=='hide' )
  {
    $("#by_batch").hide();
    $("#sc_brt_id").val(0);
    $("#sc_co_id").val(0);
    $("#sc_be_id").val(0);
    $("#stu_first_name").val('');
    $("#stu_last_name").val('');
    $("#stu_gr_no").val('');
   }
  if (action=='show' )
  {
     $("#by_batch").show();

   }
}

$("#sta_att_date ").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true, });

            </script>
            <?php include("includes/models.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
        <script>
            function updateDateRange() {
                const selectedDate = document.getElementById('selectedDate').value;
                const dateRangeContainer = document.getElementById('dateRange');

                const dateParts = selectedDate.split('-');
                const day = parseInt(dateParts[0], 10);
                const month = parseInt(dateParts[1], 10) - 1; 
                const year = parseInt(dateParts[2], 10);

                const selectedDateTime = new Date(year, month, day);

                if (!isNaN(selectedDateTime.getTime())) {
                    const lastWeekStartDate = new Date(selectedDateTime);
                    lastWeekStartDate.setDate(lastWeekStartDate.getDate() - 7);

                    const dateRange = [];
                    for (let i = 0; i < 8; i++) {
                        dateRange.push(lastWeekStartDate.getDate().toString().padStart(2, '0'));
                        lastWeekStartDate.setDate(lastWeekStartDate.getDate() + 1);
                    }

                    const startOfWeek = dateRange.join('-');

                    dateRangeContainer.textContent = `Last Week: ( ${startOfWeek} )`;
                }
                else {
                        dateRangeContainer.textContent = "Invalid date format";
                    }
            }

        const datepicker = document.getElementById('sta_att_date');
        datepicker.addEventListener('change', updateDateRange);


        updateDateRange();
    </script>

</body>
</html>
