<?php
include("includes/application_top.php");
include("../includes/class/student.php");

$page_title = "Student Event Enrollment";
$errormsg = get_rdata('errormsg', '');

// start of search criteria 
$id = get_rdata("id", 0);
$act = get_rdata("act");
$act_search = get_rdata("act_search");
$act1 = get_rdata("act1");
$payby = get_rdata('payby','all');
$stu_first_name = get_rdata('stu_first_name');
$stu_last_name = get_rdata('stu_last_name');

// $stu_gr_no = get_rdata('stu_gr_no');

// end of search criteria 


$ev_id = get_rdata('ev_id');
$stu_gr_no = get_rdata('stu_gr_no');
$stu_first_name = get_rdata('stu_first_name');
$stu_last_name = get_rdata('stu_last_name');
$chk_process = get_rdata('chk_process');
$pay_fee = get_rdata('pay_fee', 0);
$enroll = get_rdata('enroll', 0);
$all_ids = get_rdata('all_ids', '');
$addresult = get_rdata('addresult', 0);
$addcertificate = get_rdata("addcertificate",0);
$ev_name  = "";
if ($enroll == 1) {
    $page_title = "Student Event Enrollment";
} else if ($pay_fee == 1) {
    $page_title = "Student Event Fees";
} else if ($addresult == 1) {
    $page_title = "Student Event Result";
}

if ($ev_id == '' OR $ev_id == 0) {
    $ev_id = 0;
  //  echo "invalid request";
   // exit(0);
}
// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Event Student Allocation Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Event Student Allocation Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Event Student Allocation Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Event Student Allocation has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
    $successmsg = "Event Student Allocation has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}
// code for entrolling student in the exam
if ($act == "enrollstudent") {
    $evs_ids = "";
    $not_removed_ids = "";
    $not_removed_ids_others = "";
   // print_r($chk_process);
   // die;    echo "<pre>"; 
    foreach ($chk_process as $enrolled_student_k => $enrolled_student_v) {
        $enrolled_student_v_arr =  explode("_",$enrolled_student_v); 
        $f_stu_id =  $enrolled_student_v_arr[0];
        $f_stu_status =  $enrolled_student_v_arr[1];
        $res_entrolled = enroll_student_event($ev_id, $f_stu_id,$f_stu_status);
        //print_r($res_entrolled);
        add_log_txt(json_encode($res_entrolled));    
        if ($res_entrolled["errormsg"] != '') {
            $errormsg = $res_entrolled["errormsg"];
            break;
        } else {
            if ($f_stu_status == 'student')
            { $not_removed_ids .= $res_entrolled["id"] . ","; }
            else if ($f_stu_status == 'other')
            { $not_removed_ids_others .= $res_entrolled["id"] . ",";  }
        }
    }


    add_log_txt($c_file . '--' . json_encode($not_removed_ids_others));
    if ($not_removed_ids != '') 
    {
       $event_student_query = 'DELETE FROM sm_event_student_entrolled 
        WHERE evs_stu_or_other ="student" AND  evs_ev_id ='.$ev_id.' AND 
                    evs_id NOT IN (' . $not_removed_ids . '0) AND evs_stu_id IN ('.$all_ids.',0)';
       
        m_process("delete", $event_student_query);

       /* $none_removal_ids_res = remove_enroll_student_event($not_removed_ids, $ev_id,'student');
        if ($none_removal_ids_res != '') {
            $errormsg = $none_removal_ids_res;
        } */
    }
        
    if ($errormsg == '' && $not_removed_ids_others != '') 
    {
        $not_removed_ids_others_res = remove_enroll_student_event($not_removed_ids_others, $ev_id,'other');
        if ($not_removed_ids_others_res != '') {
            $errormsg = $not_removed_ids_others_res;
        } 
    }

    if ($errormsg == '')
    {
        header('Location:manage_event.php?msg=4&page=1');
        exit(0);
    }
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', 50);
$order_by = get_rdata('order_by', ' stu_gr_no ');
$order = get_rdata('order', 'asc');
$stu_first_name_arrow = $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-down';
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


$arr_event_details = get_event_details($ev_id);
$ev_fee = 0;

$ev_date = $cur_date;
if ($arr_event_details["errormsg"] == "") {
    $ev_date = $arr_event_details["data"]["ev_date"];
    $ev_fee = $arr_event_details["data"]["ev_eu_exam_fee"];
    $ev_fee_other = $arr_event_details["data"]["ev_nu_exam_fee"];
    $ev_name = $arr_event_details["data"]["ev_name"]. " [".convert_db_to_disp_date($arr_event_details["data"]["ev_date"])." , Event Fee: $ev_fee INR] , Event Fee Other: $ev_fee_other INR]";
}

$condition = '';

$condition.=" stu_br_id= " . $tmp_admin_id;

if ($act_search == 'search')
{
    if ($stu_first_name !='')
    {
        $condition.=" AND stu_first_name LIKE '%".$stu_first_name."%' ";
    }
    if ($stu_last_name !='')
    {
        $condition.=" AND stu_last_name LIKE '%".$stu_last_name."%' ";
    }
    if ($stu_gr_no !='')
    {
        $condition.=" AND stu_gr_no LIKE '%".$stu_gr_no."%' ";
    }
}

$j_type = "";
$j_is_current = "";

$j_type = " LEFT JOIN ";
if ((isset($pay_fee) && $pay_fee == 1 ) || ( isset($addresult) && $addresult == 1 )) {
    $j_type = " INNER JOIN ";
}

$select_f = " DISTINCT evs_total_paid, evs_discount_amount, evs_fee, stu_br_id, stu_id, evs_ev_id,  evs_result_status,evs_result_marks,evs_enroll_next,  evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name, brt_name,be_name_for be_name, be_name_for,co_name, 'student' as stu_or_other  ";
$table = " sm_student
LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1 )
LEFT JOIN sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id) 
".$j_type." sm_event_student_entrolled ON (evs_stu_or_other = 'student'  AND stu_id = evs_stu_id AND evs_ev_id  = ".$ev_id." ) ";

$non_student_query = "SELECT  DISTINCT evs_total_paid, evs_discount_amount, evs_fee, stu_br_id, stu_id, evs_ev_id, evs_result_status,evs_result_marks,evs_enroll_next,evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name , '' brt_name, '' be_name, '' be_name_for,'' co_name, 'other' as stu_or_other  FROM  sm_student_other ";
$non_student_query .= $j_type." sm_event_student_entrolled ON (evs_stu_or_other = 'other' AND stu_id = evs_stu_id AND evs_ev_id  = ".$ev_id." ) ";

$condition2 = " order by " . $order_by . ' ' . $order;

if ((isset($pay_fee) && $pay_fee == 1 ) || ( isset($addresult) && $addresult == 1 ) || ( isset($addcertificate) && $addcertificate == 1 ) ) {
    $condition.=" AND evs_id !=0 "; 
if (isset($addresult) && $addresult == 1) {
}

    $mfinalquery = "SELECT ".$select_f. " FROM ".$table. " UNION ".$non_student_query. " WHERE ". $condition. $condition2;
    $pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&stu_first_name=" . $stu_first_name . "&stu_last_name=" . $stu_last_name. "&stu_gr_no=" . $stu_gr_no. "&order by=" . $order_by . "&order=" . $order, $mfinalquery);
} else if (isset($enroll) && $enroll == 1) {
    $mfinalquery = "SELECT ".$select_f. " FROM ".$table . " WHERE ". $condition ;
    
    $mfinalquery .= " UNION ".$non_student_query. " WHERE ". $condition.$condition2;

    $pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&ev_id=" . $ev_id . "&stu_first_name=" . $stu_first_name . "&stu_last_name=" . $stu_last_name. "&stu_gr_no=" . $stu_gr_no . "&order by=" . $order_by . "&order=" . $order. "&enroll=1", $mfinalquery);
}
// echo $select_f. " ". $table. " ". $condition;
$objData = !empty($pageObj) ? $pageObj->paginate() : [];
$total_rows = !empty($pageObj) ? $pageObj->totRows() : 0;

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport" />
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
                    <?php echo $page_title.'    <small>'. $ev_name.'</small>'; ?>
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
                <!-- start of search form -->

                <div class="row">
                    <div class="col-lg-12 col-xs-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Search</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal" name="form1" id="form1" method="post"
                                action="event_student_entrolled.php?enroll=1&ev_id=<?php echo $ev_id; ?>">
                                <input type="hidden" name="act_search" id="act_search" value="search">
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">First Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="stu_first_name" id="stu_first_name"
                                                    placeholder="Enter First Name"
                                                    value="<?php echo $stu_first_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Last Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="stu_last_name" id="stu_last_name"
                                                    placeholder="Enter Last Name" value="<?php echo $stu_last_name; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>



                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">GR No</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="stu_gr_no" id="stu_gr_no"
                                                    placeholder="Enter GR No" value="<?php echo $stu_gr_no; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default"
                                        onclick="window.location.href = 'event_student_entrolled.php?enroll=1&ev_id=<?php echo $ev_id; ?>'">Cancel</button>
                                </div><!-- /.box-footer -->
                            </form>
                        </div>

                    </div>
                </div>

                <!-- end of search form -->
                <form id="form_enrollment" name="form_enrollment" method="post">
                    <input type="hidden" id="act" name="act" />
                    <input type="hidden" id="pay_fee" name="pay_fee" />
                    <input type="hidden" id="all_ids" name="all_ids" value="" />

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center">Gr No.</th>
                                                <th align="center">Name</th>
                                                <th align="center">Type</th>
                                                <?php if ($pay_fee == 1 || $enroll == 1) { ?>
                                                <th align="center">Fee</th>
                                                <th align="center">Total Paid</th>
                                                <th align="center">Is Paid?</th>
                                                <?php } ?>
                                                <th align="center" class="t_align_center" width="120px">Action
                                                    <?php if ($enroll == 1) { ?> <input type="checkbox"
                                                        id="enrollment_check_all" name=""
                                                        onchange="check_uncheck_enrollment_check();"> <?php } ?>
                                                </th>
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
        $fee = $paid = 0;
      
        $evs_paid = ($db_row['evs_total_paid'] +$db_row['evs_discount_amount'] == $db_row['evs_fee']) ?  "Yes": "No";
        $read_only = '';
        if ($db_row['evs_paid'] == 1 && $db_row['evs_ev_id'] == $ev_id) {
            $read_only = ' onclick="return false;"  ';
        }

        // $read_only = ($db_row['evs_paid']==0)?'':' onclick="return false;"  ';
        $checked = "";
        if ($db_row['evs_id'] != 0 && $db_row['evs_ev_id'] == $ev_id) {
            $checked = ' checked="checked"';
        }
        $evs_fee = $db_row['evs_fee'];
        $evs_stu_or_other = $db_row['stu_or_other'];
        ?>
                                            <tr class="<?php echo $class; ?>">
                                                <td>
                                                    <center><?php echo $srNo; ?></center>
                                                </td>
                                                <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>
                                                <td style="padding-left:10px;">
                                                    <?php echo $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?>
                                                </td>
                                                <td style="padding-left:10px;"><?php echo $db_row['stu_or_other']; ?>
                                                </td>
                                                <?php if ($pay_fee == 1 || $enroll == 1) { ?>
                                                <td style="padding-left:10px;" class=""><?php echo $evs_fee; ?> </td>
                                                <td style="padding-left:10px;" class="">
                                                    <?php echo ($db_row['evs_total_paid']+$db_row['evs_discount_amount']); ?>
                                                </td>
                                                <td style="padding-left:10px;" class="process_<?php echo $evs_paid; ?>">
                                                    <?php echo $evs_paid; ?> </td>

                                                <?php } ?>

                                                <td>
                                                    <center>
                                                        <?php if (isset($enroll) && $enroll == 1) { ?>
                                                        <input type="checkbox" class="enrollment_check"
                                                            <?php echo $checked . ' ' . $read_only; ?> id="chk_process"
                                                            name="chk_process[]"
                                                            value="<?php echo $db_row['stu_id']."_".$db_row['stu_or_other']; ?>" />
                                                        <?php } else if (isset($pay_fee) && $pay_fee == 1 && $db_row['evs_paid'] == 0) { ?>

                                                        <a id="pay_button_<?php echo $db_row['evs_id']; ?>"
                                                            href="javascript:void(0);" class="text-info"
                                                            onclick="pay_fee_student_event('<?php echo $evs_stu_or_other?>',<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['evs_id']; ?>,<?php echo $db_row['stu_br_id']; ?>,<?php echo $evs_fee; ?>,<?php echo $db_row['evs_total_paid']; ?>, 'Pay Student Event Fee', '<?php echo $db_row['stu_first_name'] . '  ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?>','<?php echo $db_row['evs_discount_amount']; ?>');">Pay
                                                            Event Fee</a>
                                                        <?php } else if (isset($pay_fee) && $pay_fee == 1 && $db_row['evs_paid'] == 1) 
    { ?>
                                                        <a id="pay_button_<?php echo $db_row['evs_id']; ?>"
                                                            href="javascript:void(0);" class="text-info"
                                                            onclick="pay_fee_student_event('<?php echo $evs_stu_or_other?>',<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['evs_id']; ?>,<?php echo $db_row['stu_br_id']; ?>,<?php echo $evs_fee; ?>,<?php echo $db_row['evs_total_paid']; ?>, 'Pay Student Event Fee', '<?php echo $db_row['stu_first_name'] . '  ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?>','<?php echo $db_row['evs_discount_amount']; ?>');">Pay
                                                            Event Fee</a>
                                                        <?php }  ?>
                                                    </center>
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
                                    <?php if ($enroll == 1) {
                                                    ?>
                                    <div style="text-align:right;"><button type="button"
                                            onclick="student_exam_enrollment_process();"
                                            class="btn btn-info">Process</button></div>
                                    <?php } ?>
                                </div>
                                <!-- start of greed 2 -->
                                <!-- end of greed 2 -->

                            </div>
                        </div>
            </section>
        </div>
        </form>
        <?php include("includes/student_event_fee.php"); ?>
        <?php include("includes/footer.php"); ?>
    </div>
    <script type="text/javascript">
        $( document ).ready(function() {
            $("#example2").DataTable();
        });
    </script>
</body>

</html>