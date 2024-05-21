<?php
include("includes/application_top.php");


//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Faculty Attendance";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$act2 = get_rdata("act2");

$fta_att_date = get_rdata('fta_att_date');
$fac_att_check = get_rdata('fac_att_check');

// echo "mayur".strtoupper(date("D"))."dhudasia";
// exit(0);

if ($fta_att_date == '')
{ $fta_att_date = DBtoDisp($cur_date);  }

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

    $fac_att_check = get_rdata("fac_att_check");
  // adding result for each categories

  for ($i = 0; $i <count($fac_att_check); $i++) {

// echo "**".$fac_att_check[$i]."**".get_rdata($fac_att_check[$i])."**";
// exit(0);
      $process_id_arr_ids = explode("_", $fac_att_check[$i]);

      if (count($process_id_arr_ids)>2)
      {
        $arr_data = array();
        $arr_data["fta_id"] = $process_id_arr_ids[0];
        $arr_data["fta_att_status"] = get_rdata($fac_att_check[$i]);
        $arr_data["fta_att_date"] = disptoDB($fta_att_date);
        $arr_data["fta_brt_id"] = 0;
        $arr_data["fta_co_id"] = 0;
        $arr_data["fta_be_id"] = 0;
        $arr_data["sc_id"] = 0;
        $arr_data["fta_fac_id"] = $process_id_arr_ids[1];
        $arr_data["fta_br_id"] = $process_id_arr_ids[2];
        $res_attendance =  add_update_attendance_faculty($arr_data);
        if ($res_attendance!='')
        {
           $errormsg = $res_attendance;
           break;
        }
      }

  }

   if ($errormsg == '')
   {
      $successmsg = "Faculty Attendance has been saved successfully";
   }
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' fac_name ');
$order = get_rdata('order', 'asc');
$fac_first_name_arrow = $fac_gr_no_arrow=  'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'fac_first_name') {
        $fac_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $fac_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'fac_gr_no') {
        $fac_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $fac_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

//searching and pagination


$condition = " fac_status = 'A' AND fac_br_id=".$tmp_admin_id;
// $condition.= " AND FIND_IN_SET ('".strtoupper(date("D"))."',UPPER(brt_working_days)) "	;
// if ($fac_batchtime  != 0) {
//     //  $condition = ' fac_status = "Y" AND fac_br_id='.$tmp_admin_id;
    
//     $condition.= " AND 	fac_batchtime =" . $fac_batchtime;
// }
    $condition.= " order by " . $order_by . ' ' . $order;
  //  exit(0);
    $table = " sm_faculty 
    LEFT JOIN sm_faculty_attendance ON (fta_fac_id  = fac_id AND fta_att_date  = '".disptoDB($fta_att_date)."')  ";

    $select_f = " fac_name, fac_id, fac_br_id, fac_identity_id, IF(fta_id IS NULL,0,fta_id) fta_id ,fta_att_status";
    for($i=0; $i<=6;$i++) {
        $select_f .= ",(Select fta_att_status as fta_att_status_".$i." FROM sm_faculty_attendance as sm_faculty_attendance_".$i." WHERE  sm_faculty_attendance_".$i.".fta_fac_id = fac_id  AND fta_att_date = date(DATE_SUB(NOW(), INTERVAL ".$i." DAY))) as fta_att_status_".$i."";
    }
    
    $pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&order by=" . $order_by . "&order=" . $order);
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
                                <!-- /.box-header -->
                                <!-- form start -->


                                    <div class="box-body">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Date</label>
                                            <div class="col-sm-9">


                                                <input required  type="text" readonly name="fta_att_date" id="fta_att_date"  placeholder="Attendance Date" value="<?php echo $fta_att_date; ?>" class="form-control" />

                                            </div>
                                        </div>
                                      </div>



                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'factulty_attendance.php'">Cancel</button>
                                    </div><!-- /.box-footer -->

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
                                                <th align="center">Identity id</th>
                                                <th align="center">Name</th>
		                                       <th align="center">Last Week</th>
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

                                                    $p_id= $db_row['fta_id']."_".$db_row['fac_id']."_".$db_row['fac_br_id'];
                                                    $fta_att_status  = $db_row["fta_att_status"];
                                                    
                                                    $fta_att_status_0  = $db_row["fta_att_status_0"];
                                                    $fta_att_status_1  = $db_row["fta_att_status_1"];
                                                    $fta_att_status_2  = $db_row["fta_att_status_2"];
                                                    $fta_att_status_3  = $db_row["fta_att_status_3"];
                                                    $fta_att_status_4  = $db_row["fta_att_status_4"];
                                                    $fta_att_status_5  = $db_row["fta_att_status_5"];
                                                    $fta_att_status_6  = $db_row["fta_att_status_6"];
                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['fac_identity_id']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['fac_name']; ?></td>
			                                         <td> <?=$fta_att_status_6?>,
                                                            <?=$fta_att_status_5?>,
                                                            <?=$fta_att_status_4?>,
                                                            <?=$fta_att_status_3?>,
                                                            <?=$fta_att_status_2?>,
                                                            <?=$fta_att_status_1?>,
                                                            <?=$fta_att_status_0?></td>
                                                        <td><center>
                                                          <input type="checkbox" checked class="hidden" value="<?php echo $p_id;?>" name="fac_att_check[]" />
                                                            <select class="cl_student_attendance" name="<?php echo $p_id;?>" >
                                                              <option <?php if($fta_att_status == '') echo 'selected'; ?> value="">-</option>
                                                              <option <?php if($fta_att_status == 'P') echo 'selected'; ?>  value="P">P</option>
                                                              <option <?php if($fta_att_status == 'A') echo 'selected'; ?>  value="A">A</option>
			            <option <?php if($fta_att_status == 'L') echo 'selected'; ?>  value="L">L</option>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 text-right"><button type="submit" onclick="do_exam_attendance();" class="btn btn-info">Process</button></div>
                    </div>
                 
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
    $("#fac_first_name").val('');
    $("#fac_last_name").val('');
    $("#fac_gr_no").val('');
   }
  if (action=='show' )
  {
     $("#by_batch").show();

   }
}

$("#fta_att_date ").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true, });

            </script>
            <?php include("includes/models.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
