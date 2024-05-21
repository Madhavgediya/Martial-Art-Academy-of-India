<?php
include("includes/application_top.php");
// include("../includes/class/complain.php");
//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Batch Attendance";
$errormsg = get_rdata('errormsg', '');


$stu_medium = get_rdata("stu_medium", "");
$std_id = get_rdata("std_id", 0);
$cl_id = get_rdata("cl_id", 0);
$std_name = get_rdata("std_name", "");
$cl_name = get_rdata("cl_name", "");

$attend_date = get_rdata("attend_date", "");
$abs_ids = get_rdata("abs_ids", "");

$act = get_rdata("act");


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Complain Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Complain Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Complain Has Been Updated Successfully";
} else {
    $successmsg = '';
}

if ($act == 'process-attendance') {
    $att_date = convert_disp_to_db_date($attend_date);
    $q_att = "SELECT att_id,att_attended,att_absent FROM sm_attendance_b WHERE att_date = '" . $att_date . "' AND att_std_id = " . $std_id . " AND 	att_cl_id =" . $cl_id . " AND att_sc_id = " . $tmp_admin_sc_id . " AND att_stu_medium= '" . $stu_medium . "' ";
    $result_att = m_process("get_data", $q_att);

    $att_create_date = $att_update_date = $cur_date;
    $att_create_by_id = $att_update_by_id = $tmp_admin_id;

    if ($result_att['errormsg'] != '') {
        $errormsg = $result_att['errormsg'];
    } else {
        if ($result_att['count'] <= 0) {
            // code for insert

            $q_att_i = "INSERT INTO sm_attendance_b(att_id, att_date, att_stu_medium, att_sc_id, att_attended, att_absent, att_cl_id, att_std_id, att_create_date, att_create_by_id, att_update_date, att_update_by_id) VALUES (NULL,'$att_date','$stu_medium',$tmp_admin_sc_id,'','$abs_ids',$cl_id,$std_id,'$att_create_date', $att_create_by_id, '$att_update_date', $att_update_by_id)";
            $result_att_i = m_process("insert", $q_att_i);

            if ($result_att_i['errormsg'] != '') {
                $errormsg = $result_att_i['errormsg'];
            } else {
                // update other roll no's that are not in insert
                $q_1 = "SELECT GROUP_CONCAT(stu.stu_roll_no) as c_att  FROM sm_student stu 
INNER JOIN sm_school_master sm ON (sm.sc_id = stu.stu_sc_id) INNER JOIN sm_standard std ON (std.std_sc_id = sm.sc_id) 
INNER JOIN sm_class cl ON (cl.cl_sc_id = sm.sc_id) 
LEFT JOIN sm_attendance_b att ON (att.att_sc_id = sm.sc_id AND att.att_std_id = std.std_id AND att.att_cl_id = cl.cl_id AND att.att_date= '$attend_date' )
WHERE stu.stu_std_id = std.std_id AND stu.stu_cl_id = cl.cl_id  AND stu.stu_status = 'A' AND sm.sc_id = $tmp_admin_sc_id
AND stu.stu_medium = 'English' AND stu_roll_no NOT IN ($abs_ids)  ";

                $result_1 = m_process("get_data", $q_1);

                if ($result_1['errormsg'] != '') {
                    $errormsg = $result_1['errormsg'];
                } else {
                    //
                    if ($result_1['count'] > 0) {
                        $att_attended = $result_1['res'][0]['c_att'];
                        if ($att_attended != '') {
                            $q_update = "UPDATE sm_attendance_b SET att_update_by_id = $att_update_by_id, att_update_date = '$att_update_date', att_attended = '$att_attended' WHERE att_id= " . $result_att_i['id'];
                            $result_2 = m_process("update", $q_update);
                            if ($result_2['errormsg'] != '') {
                                $errormsg = $result_2['errormsg'];
                            } else {
                                $successmsg = "Attendance has been updated successfully for $stu_medium $std_name $cl_name ";
                            }
                        }
                    }


                    //
                }
            }
        } else {
            // code for update
            $att_id = $result_att['res'][0]['att_id'];
            $q_att_u = "UPDATE sm_attendance_b SET att_update_by_id = $att_update_by_id, att_update_date = '$att_update_date', att_absent = '$abs_ids' WHERE att_id= " . $att_id;
            $result_att_u = m_process("update", $q_att_u);

            if ($result_att_u['errormsg'] != '') {
                $errormsg = $result_att_u['errormsg'];
            } else {
                // update other roll no's that are not in insert
                $q_1 = "SELECT GROUP_CONCAT(stu.stu_roll_no) as c_att  FROM sm_student stu 
INNER JOIN sm_school_master sm ON (sm.sc_id = stu.stu_sc_id) INNER JOIN sm_standard std ON (std.std_sc_id = sm.sc_id) 
INNER JOIN sm_class cl ON (cl.cl_sc_id = sm.sc_id) 
LEFT JOIN sm_attendance_b att ON (att.att_sc_id = sm.sc_id AND att.att_std_id = std.std_id AND att.att_cl_id = cl.cl_id  AND att.att_date= '$attend_date' )
WHERE stu.stu_std_id = std.std_id AND stu.stu_cl_id = cl.cl_id AND stu.stu_status = 'A' AND sm.sc_id = $tmp_admin_sc_id
AND stu.stu_medium = 'English' AND stu_roll_no NOT IN ($abs_ids)  ";

                $result_1 = m_process("get_data", $q_1);

                if ($result_1['errormsg'] != '') {
                    $errormsg = $result_1['errormsg'];
                } else {
                    //
                    if ($result_1['count'] > 0) {
                        $att_attended = $result_1['res'][0]['c_att'];
                        if ($att_attended != '') {
                            $q_update = "UPDATE sm_attendance_b SET att_attended = '$att_attended' WHERE att_id= " . $att_id;
                            $result_2 = m_process("update", $q_update);
                            if ($result_2['errormsg'] != '') {
                                $errormsg = $result_1['errormsg'];
                            } else {
                                $successmsg = "Attendance has been updated successfully for $stu_medium $std_name $cl_name ";
                            }
                        }
                    }


                    //
                }
            }
            // end of code for update
        }
    }
}
//get total records from project table

$q_medium = "SELECT DISTINCT stu_medium FROM sm_student stu INNER JOIN sm_school_master sm ON (sm.sc_id = stu.stu_sc_id) WHERE stu_medium != '' ";
if (session_get('admin_login_type') == 'school') {
    $q_medium .=" and  sm.sc_id= " . session_get('admin_sc_id');
}
// echo $q_medium;
$result_medium = m_process("get_data", $q_medium);

if ($result_medium['errormsg'] != '') {
    $errormsg = $result_medium['errormsg'];
} else {
    if ($result_medium['count'] <= 0) {
        $errormsg = 'No value found for medium';
    }
}

if ($stu_medium != '') {
    $q_standard = "SELECT std_id , std_name , cl_id , cl_name FROM sm_student stu INNER JOIN sm_school_master sm ON (sm.sc_id = stu.stu_sc_id) INNER JOIN sm_standard std ON (std.std_sc_id = sm.sc_id) INNER JOIN sm_class cl ON (cl.cl_sc_id = sm.sc_id) WHERE stu.stu_std_id = std.std_id AND stu.stu_cl_id = cl.cl_id AND stu.stu_medium = '" . $stu_medium . "' ";
    if (session_get('admin_login_type') == 'school') {
        $q_standard .=" and  sm.sc_id= " . session_get('admin_sc_id');
    }
    $q_standard .=" GROUP BY std_id , std_name , cl_id , cl_name";
    $q_standard .=" ORDER BY std_name , cl_name";
// echo $q_standard;
    $result_standard = m_process("get_data", $q_standard);

    if ($result_standard['errormsg'] != '') {
        $errormsg = $result_standard['errormsg'];
    } else {
        if ($result_standard['count'] <= 0) {
            $errormsg = 'No value found for standard ';
        }
    }



// echo $q_medium;
}

if ($successmsg != '') {
    $stu_medium = $std_id = $cl_id = $std_name = $cl_name = $act = "";
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
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">                                
                                <div class="box-header with-border">
                                    <h3 class="box-title">Select Medium/Standard/Class </h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form class="form-horizontal" name="form1" id="form1" method="post" onsubmit="return process_batch_processing();" >
                                    <input type="hidden" name="act" id="act">
                                        <input type="hidden" value="<?php echo $stu_medium; ?>" name="stu_medium" id="stu_medium">
                                            <input type="hidden" value="<?php echo $std_id; ?>" name="std_id" id="std_id">
                                                <input type="hidden" value="<?php echo $cl_id; ?>" name="cl_id" id="cl_id">
<?php                                                    
                                                    if (session_get('admin_login_type') == 'school') {
?>                                                        
              <input type="hidden" value="<?php echo session_get('admin_sc_id'); ?>" name="sc_id" id="sc_id">
<?php     }  ?>

                                                    <input type="hidden" value="<?php echo $std_name; ?>" name="std_name" id="std_name">
                                                        <input type="hidden" value="<?php echo $cl_name; ?>" name="cl_name" id="cl_name">

                                                            <div class="box-body">
                                                                <?php
// print_r($result_medium);
                                                                if (($result_medium['errormsg'] == '') && ($result_medium['count'] > 0)) {
                                                                    //   echo 'one';
                                                                    ?>

                                                                    <?php
                                                                    $sr_i = 0;
                                                                    foreach ($result_medium['res'] as $db_row) {
                                                                        if (($stu_medium != '' && $db_row['stu_medium'] == $stu_medium) || $stu_medium == '') {
                                                                            $sr_i++;
                                                                            ?>
                                                                            <div class="col-md-6 mb5" style="margin-bottom: 5px;">
                                                                                <button onclick="set_var_value('stu_medium', '<?php echo $db_row['stu_medium'] ?>', 1)" type="button" class="btn btn-block btn-success btn-lg"><?php echo $db_row['stu_medium'] ?></button>
                                                                            </div>

                                                                            <?php
                                                                            /*
                                                                            if ($sr_i % 2 == 0) {
                                                                                echo '<div class="row">&nbsp;</div><div class="row">&nbsp;</div>';
                                                                            }
                                                                             */
                                                                        }
                                                                    }
                                                                    ?>

                                                                <?php } ?>
                                                                <div class="row">&nbsp;</div>
                                                                <?php
// print_r($result_medium);
                                                                // std_id , std_name , cl_id , cl_name
                                                                $sr_i = 0;
                                                                if (($stu_medium != '') && ($result_standard['errormsg'] == '') && ($result_standard['count'] > 0)) {

//   echo 'one';
                                                                    ?>
                                                                    <div class="row">&nbsp;</div>

                                                                    <?php
                                                                    foreach ($result_standard['res'] as $db_row) {
                                                                        $sr_i++;
                                                                        // if ($sr_i%2 == 0) { echo '<div class="row">'; } 
                                                                        if (($std_name != '' && $db_row['std_name'] == $std_name && $db_row['cl_name'] == $cl_name ) || $std_name == '') {
                                                                            
                                                                            ?>
                                                                    <div class="col-md-6" style="margin-bottom: 5px;">
                                                                                <button onclick="set_standard_class(<?php echo $db_row['std_id'] ?>, '<?php echo $db_row['std_name']; ?>',<?php echo $db_row['cl_id'] ?>, '<?php echo $db_row['cl_name'] ?>')" type="button" class="btn btn-block btn-warning btn-lg"><?php echo $db_row['std_name'] . "-" . $db_row['cl_name']; ?></button>
                                                                            </div>

                                                                        <?php
                                                                        }
                                                                        /*
                                                                        if ($sr_i % 2 == 0) {
                                                                            echo '<div class="row">&nbsp;</div><div class="row">&nbsp;</div>';
                                                                        }
                                                                         
                                                                         */
                                                                    }
                                                                    ?>

<?php } ?>

                                                                <?php if ($act == 'allow-attendance') { ?>
                                                                    <div class="row"></div>
                                                                    <div class="col-md-6">Select Date:<input required type="text" name="attend_date" id="attend_date"  class="form-control datepicker"></div> 
                                                                    <div class="col-md-6">Please enter absent Roll nos: <textarea required name="abs_ids" id="abs_ids" class="form-control"></textarea> Enter ids comma separate (,)</div> 
                                                                    <div class="col-md-12"><button type="button" onclick="get_attendance_details();" class="btn btn-warning">View Existing Attendance Details</button>&nbsp; <span id="attendance_data"></span> </div>  
                                                                <?php } ?>
                                                            </div><!-- /.box-body -->

                                                            <div class="box-footer">                                        
<?php if ($act == 'allow-attendance') { ?>
                                                                    <button type="submit" onclick="set_var_value('act', 'process-attendance');" class="btn btn-info">Submit</button>
<?php } ?>
                                                                <button type="button" class="btn btn-default" onclick="window.location.href = 'batch_attendance.php'">Clear Selection</button>
                                                            </div><!-- /.box-footer -->

                                                            </form>                                
                                                            </div>

                                                            </div>
                                                            </div>


                                                            </section>
                                                            </div>
<?php include("includes/footer.php"); ?>
                                                            <script type="text/javascript" language="javascript">
                                                                $('.datepicker').datepicker({
                                                                    format: 'dd/mm/yyyy',
                                                                    autoclose: true,
                                                                });
                                                            </script>
                                                            </div>

                                                            </script>
                                                            </body>
                                                            </html>
