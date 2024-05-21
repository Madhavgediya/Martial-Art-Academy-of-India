<?php
include("includes/application_top.php");
include("../includes/class/student.php");

$page_title = "Student Exam Enrollment";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$ex_id = get_rdata('ex_id');
$stu_gr_no = get_rdata('stu_gr_no');
$stu_first_name = get_rdata('stu_first_name');
$stu_last_name = get_rdata('stu_last_name');
$chk_process = get_rdata('chk_process');
$chk_certificate = get_rdata('chk_certificate');
$chk_belt = get_rdata('chk_belt');
$pay_fee = get_rdata('pay_fee', 0);
$enroll = get_rdata('enroll', 0);
$addresult = get_rdata('addresult', 1);
$addcertificate = get_rdata('addcertificate', 0);

$ex_name  = "";
if ($enroll == 1) {
    $page_title = "Student Exam Enrollment";
} else if ($pay_fee == 1) {
    $page_title = "Student Exam Fees";
} else if ($addresult == 1) {
    $page_title = "Student Exam Result";
} else if ($addcertificate == 1) {
    $page_title = "Student Exam Certificate/Belt";
}
if ($ex_id == '' OR $ex_id == 0) {
    echo "invalid request";
    exit(0);
}
// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Exam Student Allocation Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Exam Student Allocation Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Exam Student Allocation Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Exam Student Allocation has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
    $successmsg = "Exam Student Allocation has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}

if ($act == "enrollstudent") {
    // echo "<pre>";
    // print_r($_REQUEST); 
    // exit(0);
    $exs_ids = "";
    add_log_txt($c_file . '--' . json_encode($chk_process));
    if ($chk_process !='')
    {
    $not_removed_ids = "";
    foreach ($chk_process as $enrolled_student_k => $enrolled_student_v) {

        $res_entrolled = enroll_student($ex_id, $enrolled_student_v);
        if ($res_entrolled["errormsg"] != '') {
            $errormsg = $res_entrolled["errormsg"];
            break;
        } else {
            $not_removed_ids .= $res_entrolled["id"] . ",";
        }
    }

    if ($not_removed_ids != '') {
        $none_removal_ids_res = remove_enroll_student($not_removed_ids, $ex_id);
        if ($none_removal_ids_res != '') {
            $errormsg = $none_removal_ids_res;
        } else {
            $arr_student_categories = insert_exam_result_categories_to_student($ex_id, 0, $exs_ids . "0");
            if ($arr_student_categories["errormsg"] != "") {
                $errrmsg = $arr_student_categories["errormsg"];
            } else {
                $successmsg = "Student Exam Enrollment has been done successfully.";
                header('Location:manage_exam.php?msg=4&page=1');
                exit(0);
            }
        }
    } else {
        header('Location:manage_exam.php?msg=4&page=1');
        exit(0);
    }
    }
    else
    {
        $not_removed_ids = "";
        $result_not_removal_id = get_not_removal_ids($ex_id);
        if ($result_not_removal_id["errormsg"] !='' )
        {
            $errrmsg = $result_not_removal_id["errormsg"];
        }
        else
        {
            $not_removed_ids = $result_not_removal_id["ids"];
        }
        $none_removal_ids_res = remove_enroll_student($not_removed_ids, $ex_id);
        if ($none_removal_ids_res != '') {
            $errormsg = $none_removal_ids_res;
        } else {
                $successmsg = "Student Exam Enrollment has been done successfully.";
                header('Location:manage_exam.php?msg=4&page=1');
                exit(0);
            }
        // remove those who are not paid the fee check the system if exam is done then are allowing to remove this enrollment or not.
    }
}
if ($act == "apply_belt_certificate") { 
    // chk_belt
    $exs_ids = "";
    // code for certification
    add_log_txt($c_file . '--' . json_encode($chk_certificate));
    if ($chk_certificate !='')
    { $chk_certificate_string = implode(",",$chk_certificate); }
    else
    $chk_certificate_string = "";
    $arr_certificate_string =  allocate_certificate_belt($ex_id,$chk_certificate_string,"certificate");
    if ($arr_certificate_string["errormsg"]!="" )
        $errormsg = $arr_certificate_string["errormsg"];
    else
        $successmsg = "Certificate has been updated successfully";

    // code for belt
    add_log_txt($c_file . '--' . json_encode($chk_belt));
    if ($chk_belt !='')
    { $chk_belt_string = implode(",",$chk_belt); }
    else
    $chk_belt_string = "";
    $arr_belt_string =  allocate_certificate_belt($ex_id,$chk_belt_string,"belt");
    if ($arr_belt_string["errormsg"]!="" )
        $errormsg = $arr_belt_string["errormsg"];
    else
        $successmsg = "Belt has been updated successfully";

}
$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', 10000);
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

if ($act == 'delete' && $id != 0) {

    $sm_student = new student();
    $sm_student->action = 'delete';

    $del_where = "stu_id = " . $id;
    if (session_get('admin_login_type') == 'school') {
        $del_where.=" and stu_sc_id= " . session_get('admin_sc_id');
    }
    $sm_student->where = $del_where;

    $result = $sm_student->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        $successmsg = "Student Has Been Deleted Successfully";
    }
}

$arr_exam_details = get_exam_details($ex_id);

$ex_date = $cur_date;
if ($arr_exam_details["errormsg"] == "") {
    $ex_date = $arr_exam_details["data"]["ex_date"];
    // var_dump($ex_date);
    $print_exam_date = convert_db_to_disp_date($ex_date);
    $ex_name = $arr_exam_details["data"]["ex_name"]. " [".convert_db_to_disp_date($arr_exam_details["data"]["ex_date"])."]";
}

$select_f = " DISTINCT  exs_co_id,  exs_be_id, stu_id, exs_ex_id, be_name_for, exs_result_status,exs_result_marks,exs_enroll_next,	sc_joined_date , stu_br_id, M.be_id, exs_total_marks, co_name, M.eca_total_marks , be_belt_exam_fee, exs_fee,exs_paid, IF(exs_id IS NULL,0,exs_id) as exs_id,  co_name, DATEDIFF(now(),sc_joined_date) n_j_diff , be_belt_duration , stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name_for be_name";
$select_f_non_enrolled = "  e1.exs_co_id,  e1.exs_be_id, stu_id,  e1.exs_ex_id, be_name_for, e1.exs_result_status, e1.exs_result_marks, e1.exs_enroll_next,	sc_joined_date , stu_br_id, M.be_id, e1.exs_total_marks, co_name, M.eca_total_marks , be_belt_exam_fee, e1.exs_fee, e1.exs_paid, IF(e1.exs_id IS NULL,0,e1.exs_id) as exs_id,  co_name, DATEDIFF(now(),sc_joined_date) n_j_diff , be_belt_duration , stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name_for be_name";

$stuent_non_enrolled_query_filter = " SELECT DISTINCT CONCAT(sc_stu_id,'-',sc_be_id,'-',sc_co_id) as m_id FROM sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id )
INNER JOIN sm_belt ON (be_belt_duration != 0  AND sc_be_id = be_id )
INNER JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id)
LEFT JOIN sm_exam_student_entrolled e1 ON (stu_id = e1.exs_stu_id AND sc_be_id = e1.exs_be_id AND sc_co_id = e1.exs_co_id  )
LEFT JOIN sm_exam_student_entrolled e2 ON (e2.exs_ex_id = " . $ex_id . " AND stu_id = e2.exs_stu_id AND sc_be_id = e2.exs_be_id AND sc_co_id = e2.exs_co_id  )
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_belt_duration != 0  AND be_id =  eca_be_id)
GROUP BY be_id ) as M ON (sc_be_id = M.be_id)
WHERE (e1.exs_id IS NULL OR  (e1.exs_result_status = 'F' OR e1.exs_result_status = 'A' ) )  AND (stu_status = 'A' OR (stu_status = 'I' AND (DATEDIFF(now(),stu_deactivation_date) < 30) )) AND e2.exs_be_id IS NULL AND e2.exs_stu_id IS NULL AND e2.exs_co_id IS NULL ";


$stuent_non_enrolled_query = " SELECT " . $select_f_non_enrolled . " FROM sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id )
INNER JOIN sm_belt ON (be_belt_duration != 0  AND sc_be_id = be_id )
INNER JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id)
LEFT JOIN sm_exam_student_entrolled e1 ON (stu_id = e1.exs_stu_id AND sc_be_id = e1.exs_be_id AND sc_co_id = e1.exs_co_id  )
LEFT JOIN sm_exam_student_entrolled e2 ON (e2.exs_ex_id = " . $ex_id . " AND stu_id = e2.exs_stu_id AND sc_be_id = e2.exs_be_id AND sc_co_id = e2.exs_co_id  )
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_belt_duration != 0  AND be_id =  eca_be_id)
GROUP BY be_id ) as M ON (sc_be_id = M.be_id)
WHERE sc_is_current = 1 AND (e1.exs_id IS NULL OR  (e1.exs_result_status = 'F' OR e1.exs_result_status = 'A' ) )  AND (stu_status = 'A' OR (stu_status = 'I' AND (DATEDIFF(now(),stu_deactivation_date) < 30) )) 
AND e2.exs_be_id IS NULL AND e2.exs_stu_id IS NULL AND e2.exs_co_id IS NULL ";

$student_entroll_query = " SELECT " . $select_f . " FROM sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id )
INNER JOIN sm_belt ON (be_belt_duration != 0  AND sc_be_id = be_id)
INNER JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id)
INNER JOIN sm_exam_student_entrolled ON (stu_id = exs_stu_id AND exs_ex_id  = " . $ex_id . ")
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_belt_duration != 0  AND be_id =  eca_be_id)
GROUP BY be_id ) as M ON (sc_be_id = M.be_id)
WHERE exs_be_id = sc_be_id AND exs_co_id = sc_co_id ";

$condition = '';

$condition.=" stu_br_id= " . $tmp_admin_id;

$condition1 = ' ((sc_half_course = 0 AND DATEDIFF("' . $ex_date . '",sc_joined_date) >= (be_belt_duration+sc_additional_days)) OR (sc_half_course = 1 AND DATEDIFF("' . $ex_date . '",sc_joined_date) >= '.ADD_ABSENT_OR_FAIL_DAYS.'))  ';
if ($stu_gr_no != '') {
    $condition.=" and 	stu_gr_no LIKE '%" . $stu_gr_no . "%'";
}
if ($stu_first_name != '') {
    $condition.=" and 	stu_first_name LIKE '%" . $stu_first_name . "%'";
}
if ($stu_last_name != '') {
    $condition.=" and 	stu_last_name LIKE '%" . $stu_last_name . "%'";
}




$j_type = "";
$j_is_current = "";


$table = " sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id )
INNER JOIN sm_belt ON (be_belt_duration != 0 AND sc_be_id = be_id $j_is_current )
INNER JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id)
" . $j_type . " JOIN sm_exam_student_entrolled ON (stu_id = exs_stu_id AND exs_ex_id  = " . $ex_id . ")
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_belt_duration != 0  AND be_id =  eca_be_id)
GROUP BY be_id ) as M ON (sc_be_id = M.be_id)  ";

$condition2 = " order by " . $order_by . ' ' . $order;
if ((isset($pay_fee) && $pay_fee == 1 ) || ( isset($addresult) && $addresult == 1 ) || ( isset($addcertificate) && $addcertificate == 1 ) ) {
   $select_f = " DISTINCT exs_certificate, exs_belt, exs_co_id, exs_be_id,exs_already_paid,stu_id, exs_id,exs_ex_id, exs_result_status,exs_result_marks,exs_enroll_next,	 exs_total_marks, exs_fee,exs_paid,  exs_total_marks as eca_total_marks,
co_name , be_id, be_name_for ,be_belt_duration  ,be_belt_exam_fee, sc_joined_date,
stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name, stu_br_id, brt_name ";
   $table = "  sm_exam_student_entrolled
INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND  exs_be_id = sc_be_id AND exs_co_id = sc_co_id )
INNER JOIN sm_course ON (co_id = sc_co_id)
INNER JOIN sm_belt ON (be_belt_duration != 0  AND be_id = sc_be_id)
INNER JOIN sm_student ON (stu_id = sc_stu_id )
INNER JOIN sm_branch_type ON (sc_brt_id = brt_id ) ";

   $condition .= " AND exs_ex_id = ". $ex_id;

   if (isset($addcertificate) && $addcertificate == 1) {
    $condition.=" AND exs_result_status ='P' ";
}
    $pageObj = new PS_Pagination($table, $select_f, $condition . " " . $condition2, $per_page, 10, "per_page=" . $per_page . "&stu_first_name=" . $stu_first_name . "&order by=" . $order_by . "&order=" . $order);
} else if (isset($enroll) && $enroll == 1) {
   $student_entroll_query = $student_entroll_query ." AND " . $condition;
    $condition3= " AND CONCAT(sc_stu_id,'-',sc_be_id,'-',sc_co_id) NOT IN (SELECT DISTINCT CONCAT(exs_stu_id , '-',exs_be_id,'-',exs_co_id) FROM sm_exam_student_entrolled WHERE exs_result_status = 'P' AND exs_ex_id != $ex_id ) ";
    $stuent_non_enrolled_query = $stuent_non_enrolled_query  ." AND " . $condition . $condition3. " AND " . $condition1 . " GROUP BY sc_co_id, sc_be_id , sc_stu_id " . $condition2;
     $mfinalquery = $student_entroll_query . " UNION " . $stuent_non_enrolled_query;
    $table = "";
    $pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&stu_first_name=" . $stu_first_name . "&order by=" . $order_by . "&order=" . $order, $mfinalquery);
}


$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}

// var_dump($_SESSION);
$arr_branch_details =  get_branch_details(session_get("id"));
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
<?php echo $page_title.'    <small>'. $ex_name.'</small>'; ?>
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

                    <form id="form_enrollment" name="form_enrollment" method="post" >
                        <input type="hidden" id="act" name="act" />
                        <input type="hidden" id="pay_fee" name="pay_fee" />

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-body">
                                        <button type="button" onclick="print_attendance('tbl_student_attendance','<?php echo $arr_branch_details["name"]; ?>','exam_student_enrolled_print','<?php echo $print_exam_date; ?>');" class="btn btn-warning pull-right">Print</button>
                                        <table id="tbl_student_attendance" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th align="center" width="70px">Sr.No</th>
                                                    <th align="center" >Gr No.</th>
                                                    <th align="center">Name</th>
                                                    
                                                    <th align="center" >Belt</th>
                                                    

                                                    <th align="center" class="t_align_center"  width="250px;">Sign
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
        if ($db_row['exs_paid'] == 0)
        {
              if (validate_fees_paid_or_not($ex_id,$db_row['stu_id'],$db_row['exs_co_id'],$db_row['exs_be_id']) == true)
              {
                $db_row['exs_paid'] = 1;
              }
        }
        $exs_paid = ($db_row['exs_paid'] == 0) ? "No" : "Yes";
        $read_only = '';
        if (($db_row['exs_paid'] == 1 || $db_row['exs_result_status'] !='' ) && $db_row['exs_ex_id'] == $ex_id)  {
            $read_only = ' readonly="readonly" onclick="return false;"  ';
        }

        // $read_only = ($db_row['exs_paid']==0)?'':' onclick="return false;"  ';
        $checked = "";
        if ($db_row['exs_id'] != 0 && $db_row['exs_ex_id'] == $ex_id) {
            $checked = ' checked="checked"';
        }

        $exs_result_status = "";
        if ($db_row['exs_result_status'] == "F") {
            $exs_result_status = "Fail";
        } else if ($db_row['exs_result_status'] == "P") {
            $exs_result_status = "Pass";
        } else if ($db_row['exs_result_status'] == "A") {
            $exs_result_status = "AB";
        }
        ?>
                                                        <tr class="<?php echo $class; ?>">
                                                            <td><center><?php echo $srNo; ?></center></td>
                                                            <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>
                                                            <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?></td>

                                                     
                                                            <td style="padding-left:10px;"><?php echo $db_row['be_name_for']; ?></td>
                                                     
                                                     
                                                            <td style="padding-left:10px;"></td>
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


                                    <!-- start of greed 2 -->
                                    <!-- end of greed 2 -->

                                </div>
                            </div>
                            <!-- <div class="box-footer">
                                        
                                        <button type="button" onclick="print_attendance('tbl_student_attendance','<?php echo $arr_branch_details["name"]; ?>','exam_student_enrolled_print','<?php echo $print_exam_date; ?>');" class="btn btn-warning">Print</button>
                                    </div> -->
                            </section>
                        </div>
                    </form>
                                                <?php include("includes/models.php"); ?>
                                                <?php include("includes/footer.php"); ?>
            </div>
    </body>
</html>
