<?php
require_once('../vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
$addresult = get_rdata('addresult', 0);
$addcertificate = get_rdata('addcertificate', 0);
$export_data = get_rdata('export_data','');
$generate_pdf = get_rdata('generate_pdf','');


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
            
            if (!empty($arr_student_categories["errormsg"])) {
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
$per_page = get_rdata('per_page', 1000);
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
    $ex_name = $arr_exam_details["data"]["ex_name"]. " [".convert_db_to_disp_date($arr_exam_details["data"]["ex_date"])."]";
}

$select_f = " DISTINCT exs_finalized, exs_already_paid,  exs_co_id,  exs_be_id, stu_id, exs_ex_id, be_name_for, exs_result_status,exs_result_marks,exs_enroll_next,	sc_joined_date , stu_br_id, M.be_id, exs_total_marks, co_name, M.eca_total_marks , be_belt_exam_fee, exs_fee,exs_paid, IF(exs_id IS NULL,0,exs_id) as exs_id,  co_name, DATEDIFF(now(),sc_joined_date) n_j_diff , be_belt_duration , stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name_for be_name";
$select_f_non_enrolled = " e1.exs_finalized, e1.exs_already_paid, e1.exs_co_id,  e1.exs_be_id, stu_id,  e1.exs_ex_id, be_name_for, e1.exs_result_status, e1.exs_result_marks, e1.exs_enroll_next,	sc_joined_date , stu_br_id, M.be_id, e1.exs_total_marks, co_name, M.eca_total_marks , be_belt_exam_fee, e1.exs_fee, e1.exs_paid, IF(e1.exs_id IS NULL,0,e1.exs_id) as exs_id,  co_name, DATEDIFF(now(),sc_joined_date) n_j_diff , be_belt_duration , stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name_for be_name";

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
   $select_f = " DISTINCT exs_finalized, exs_certificate, exs_belt, exs_co_id, exs_be_id,exs_already_paid,stu_id, exs_id,exs_ex_id, exs_result_status,exs_result_marks,exs_enroll_next,	 exs_total_marks, exs_fee,exs_paid,  exs_total_marks as eca_total_marks,
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
    
  //  echo $mfinalquery;
}


$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}
if ($export_data == 'Export')
{
    $excelHeading = ['Gr No.', 'Name', 'B. Type', 'Belt', 'Course'];
    if ($enroll == 1) {
        array_push($excelHeading, 'Join D.');
    }
    if ($addresult == 1) {
        array_push($excelHeading, 'T. Marks.');
    }
    if ($pay_fee == 1) {
        array_push($excelHeading, 'Fee');
    }
    if ($pay_fee == 1 || $enroll == 1) {
        array_push($excelHeading, 'Paid?');
    }
    if ($addresult == 1) {
        array_push($excelHeading, 'R. Marks');
        array_push($excelHeading, 'R. Status');
        array_push($excelHeading, 'Finalized');
    }
    if ($addcertificate == 1) {
        array_push($excelHeading, 'Result');
    }
    array_push($excelHeading, 'Certificate');
    array_push($excelHeading, 'Belt');

    $excelData = [];
    for ($i = 1; $db_row = $objData->fetch(); $i++) {
        $exs_result_status = "";
        if ($db_row['exs_result_status'] == "F") {
            $exs_result_status = "Fail";
        } else if ($db_row['exs_result_status'] == "P") {
            $exs_result_status = "Pass";
        } else if ($db_row['exs_result_status'] == "A") {
            $exs_result_status = "AB";
        }

        $exs_paid = ($db_row['exs_paid'] == 0) ? "No" : "Yes";
        if($enroll ==1 && isset($db_row['exs_already_paid']) && $db_row['exs_already_paid'] == 'Y' ) { $exs_paid = 'NA'; }

        $new = [
            $db_row['stu_gr_no'],
            $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name'],
            $db_row['brt_name'],
            $db_row['be_name_for'],
            $db_row['co_name']
        ];
        if ($enroll == 1) {
            array_push($new, DBtoDisp($db_row['sc_joined_date']));
        }
        if ($addresult == 1) {
            array_push($new, $db_row['eca_total_marks']);
        }
        if ($pay_fee == 1) {
            array_push($new, $db_row['exs_fee']);
        }
        if (isset($addresult) && $addresult == 1) {
            array_push($new, $db_row['exs_result_marks']);
            array_push($new, $exs_result_status);
            array_push($new, $db_row['exs_finalized']);
        }
        if ($pay_fee == 1 || $enroll == 1) {
            array_push($new, $exs_paid);
        }
        if ($addcertificate == 1) {
            array_push($new, $exs_result_status);
        }

        array_push($excelData, $new);        
    }

    $styleArray = [
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $spreadsheet = new Spreadsheet();
    
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Student Exam Export");    

    $index = 0;
    $temp_index = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N"];
    foreach($excelHeading as $value) {
        $sheet->getStyle($temp_index[$index].'1')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(16);
        $sheet->setCellValue($temp_index[$index].'1',$value);
        $index++;
    }
    $index = 0;
    $outer_index = 2;
    foreach($excelData as $value) {
        $index = 0;
        foreach($value as $value1) {
            $sheet->getStyle($temp_index[$index].$outer_index)->applyFromArray($styleArray)->getFont()->setSize(16);
            $sheet->setCellValue($temp_index[$index].$outer_index,$value1);
            $index++;
        }
        $outer_index++;
    }
     foreach ($sheet->getColumnIterator() as $column) {
       $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    $spreadsheet->setActiveSheetIndex(0);
    $writer = new Xlsx($spreadsheet); 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Result.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit(0);
    // export excel
    $filename = "student_exam_certificate_belt_" . date('d-m-Y') . ".xlsx";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo implode("\t", $excelHeading) . "\n";
    foreach ($excelData as $row) {
        echo implode("\t", array_values($row)) . "\n";
    }
    exit(0);
}



if ($export_data == 'detail')
{  
    $styleArray = [
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $spreadsheet = new Spreadsheet();
    
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Detail MarkSheet");    
    $sheet->mergeCells('A1:S1');
    $sheet->getStyle('A1:U1')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(48);
    $sheet->setCellValue("A1", "Martial Art Academy Of India");

    $sheet->mergeCells('A2:A3')->getStyle('A2:A3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("A2", "Enrol No");
    
    $sheet->mergeCells('B2:B3')->getStyle('B2:B3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(20);
    $sheet->setCellValue("B2", "Student Name");

    $sheet->getStyle('C2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(20);
    $sheet->setCellValue("C2", "Current Belt");

    $sheet->getStyle('C3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(20);
    $sheet->setCellValue("C3", "");

    $sheet->mergeCells('D2:D3')->getStyle('D2:D3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("D2", "Discipline (10 Marks)");

    $sheet->mergeCells('E2:G2')->getStyle('E2:G2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("E2", "Stamina Test (50 Marks)");

    $sheet->getStyle('E3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("E3", "Running (20 Marks)");

    $sheet->getStyle('F3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("F3", "Jump (15 Marks)");

    $sheet->getStyle('G3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("G3", "Free Exercise (15 Marks)");


    $sheet->mergeCells('H2:K2')->getStyle('H2:K2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("H2", "Fitness Test (85 Marks)");

    $sheet->getStyle('H3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("H3", "Deeps (25 Marks)");

    $sheet->getStyle('I3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("I3", "Keep Up (25 Marks)");

    $sheet->getStyle('J3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("J3", "Sit Up (15 Marks)");

    $sheet->getStyle('K3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("K3", "Side Sit Up (20 Marks)");


    $sheet->mergeCells('L2:Q2')->getStyle('L2:Q2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("L2", "Technique Test (235 Marks)");

    $sheet->getStyle('L3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("L3", "Punch (60  Marks)");

    $sheet->getStyle('M3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("M3", "Kick (60 Marks)");

    $sheet->getStyle('N3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("N3", "Block (60 Marks)");

    $sheet->getStyle('O3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("O3", "Kata (80 Marks)");

    $sheet->getStyle('P3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("P3", "Lock Teq. (30 Marks)");

    $sheet->getStyle('Q3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("Q3", "Throw (30 Marks)");

    $sheet->mergeCells('R2:R3')->getStyle('R2:R3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("R2", "Stance Test (20 Marks)");

    $sheet->mergeCells('S2:S3')->getStyle('S2:S3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("S2", "Oral Test (20 Marks)");

    $sheet->getStyle('T2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("T2", "Total");

    $sheet->getStyle('T3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("T3", "Marks");

    $sheet->mergeCells('U2:U3')->getStyle('U2:U3')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("U2", "Status");

    $index = 4;
    $tempArr = ["2","7","8","9","10","11","12","13","13","18","19","21","22","23","5","6"];
    $tempArr1 = ["D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S"];
    $second_sheet_data = [];
    for($i=1;$data=$objData->fetch();$i++) {
        $r_stu_id = $data['stu_id'];
        $r_ex_id = $data['exs_ex_id'];
        $r_stu_br_id = $data['stu_br_id'];
        $check0_q = "SELECT exre_eca_exc_id,exre_total_marks_obtain
        FROM sm_exam_result 
        INNER JOIN sm_exam_categories ON(exre_eca_exc_id = exc_id) 
        INNER JOIN sm_exam ON (ex_id=exre_ex_id) 
        INNER JOIN sm_exam_student_entrolled ON (exs_ex_id = ex_id AND exs_stu_id = $r_stu_id) 
        INNER JOIN sm_student_course ON (sc_stu_id = exs_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id AND sc_stu_id = $r_stu_id) 
        INNER JOIN sm_student ON (sc_stu_id = stu_id)
        WHERE exre_ex_id = $r_ex_id AND exre_stu_id = $r_stu_id AND exre_br_id = $r_stu_br_id";

        $check_student_course_result = db_perform("sm_exam_result",[], 'get', '', '', $check0_q);
        $temp1 = [];
        foreach ($check_student_course_result["res"] as $value) {
            $temp1[$value["exre_eca_exc_id"]] = $value['exre_total_marks_obtain']; 
        }
        $sheet->getStyle('A'.$index)->getFont()->setSize(12);
        $sheet->setCellValue('A'.$index,$data['stu_gr_no']);        
        $sheet->setCellValue('B'.$index,$data['stu_first_name']." ".$data['stu_middle_name']." ".$data['stu_last_name']);        
        $sheet->setCellValue('C'.$index,$data['be_name_for']);        
        $total = 0;
        foreach ($tempArr1 as $key=>$value3) {
            $total += $temp1[$tempArr[$key]] ?? 0;
            $sheet->setCellValue($value3.$index,$temp1[$tempArr[$key]] ?? 0);        
        }
        $sheet->setCellValue('T'.$index,$total);        
        $exs_result_status = $data["exs_result_status"]=="P" ? "Pass" : ($data["exs_result_status"]=="F" ? "Fail" : "Absent");
        $sheet->setCellValue('U'.$index,$exs_result_status);                
        $index++;
       
    } 

     foreach ($sheet->getColumnIterator() as $column) {
       $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    $spreadsheet->setActiveSheetIndex(0);
    $writer = new Xlsx($spreadsheet); 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Detail-MarkSheet.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit(0);
}
if ($export_data == 'head') {  
    $styleArray = [
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Head MarkSheet");    
    $sheet->mergeCells('A1:I1')->getStyle('A1:I1')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(48);
    $sheet->setCellValue("A1", "Martial Art Academy Of India");

    $sheet->mergeCells('A2')->getStyle('A2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("A2", "Enrol No");

    $sheet->mergeCells('B2')->getStyle('B2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("B2", "Student Name");

    $sheet->mergeCells('C2')->getStyle('C2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("C2", "Discipline (10 Marks)");

    $sheet->mergeCells('D2')->getStyle('D2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("D2", "Stamina Test (50 Marks)");

    $sheet->mergeCells('E2')->getStyle('E2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("E2", "Fitness Test (85 Marks)");

    $sheet->mergeCells('F2')->getStyle('F2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("F2", "Technique Test (215 Marks)");

    $sheet->mergeCells('G2')->getStyle('G2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("G2", "Stance Test (20 Marks)");

    $sheet->mergeCells('H2')->getStyle('H2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("H2", "Oral Test (20 Marks)");

    $sheet->mergeCells('I2')->getStyle('I2')->applyFromArray($styleArray)->getFont()->setBold(true)->setSize(12);
    $sheet->setCellValue("I2", "Total  (420 Marks)");

    $index = 4;
    $tempArr = ["2","7","8","9","10","11","12","13","13","18","19","21","22","23","5","6"];
    $tempArr1 = ["D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S"];
    $second_sheet_data = [];
    for($i=1;$data=$objData->fetch();$i++) {
        $r_stu_id = $data['stu_id'];
        $r_ex_id = $data['exs_ex_id'];
        $r_stu_br_id = $data['stu_br_id'];
        $check0_q = "SELECT exre_eca_exc_id,exre_total_marks_obtain
        FROM sm_exam_result 
        INNER JOIN sm_exam_categories ON(exre_eca_exc_id = exc_id) 
        INNER JOIN sm_exam ON (ex_id=exre_ex_id) 
        INNER JOIN sm_exam_student_entrolled ON (exs_ex_id = ex_id AND exs_stu_id = $r_stu_id) 
        INNER JOIN sm_student_course ON (sc_stu_id = exs_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id AND sc_stu_id = $r_stu_id) 
        INNER JOIN sm_student ON (sc_stu_id = stu_id)
        WHERE exre_ex_id = $r_ex_id AND exre_stu_id = $r_stu_id AND exre_br_id = $r_stu_br_id";

        $check_student_course_result = db_perform("sm_exam_result",[], 'get', '', '', $check0_q);
        $temp1 = [];
        foreach ($check_student_course_result["res"] as $value) {
            $temp1[$value["exre_eca_exc_id"]] = $value['exre_total_marks_obtain']; 
        }       
        $total = 0;
        foreach ($tempArr1 as $key=>$value3) {
            $total += $temp1[$tempArr[$key]] ?? 0;      
        }      
        $index++;
        $second_sheet_data[] = [
            "stu_gr_no"=>$data["stu_gr_no"],
            "name"=>$data['stu_first_name']." ".$data['stu_middle_name']." ".$data['stu_last_name'],
            "discipline"=>$temp1[2] ?? 0,
            "stamina"=>(($temp1[7] ?? 0) + ($temp1[8] ?? 0) + ($temp1[9] ?? 0)),
            "fitness"=>(($temp1[10] ?? 0) + ($temp1[11] ?? 0) + ($temp1[12] ?? 0)  + ($temp1[13] ?? 0)),
            "technique"=>(($temp1[13] ?? 0) + ($temp1[18] ?? 0) + ($temp1[19] ?? 0)  + ($temp1[21] ?? 0) + ($temp1[22] ?? 0) + ($temp1[23] ?? 0)),
            "stance"=>$temp1[5] ?? 0,
            "oral"=>$temp1[6] ?? 0,
            "total"=>$total,
        ];
    } 

    $index = 3;   
    foreach ($second_sheet_data as $value) {
        $sheet->mergeCells('A'.$index)->getStyle('A'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('A'.$index,$value['stu_gr_no']);

        $sheet->mergeCells('B'.$index)->getStyle('B'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('B'.$index,$value['name']);

        $sheet->mergeCells('C'.$index)->getStyle('C'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('C'.$index,$value['discipline']);

        $sheet->mergeCells('D'.$index)->getStyle('D'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('D'.$index,$value['stamina']);

        $sheet->mergeCells('E'.$index)->getStyle('E'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('E'.$index,$value['fitness']);

        $sheet->mergeCells('F'.$index)->getStyle('F'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('F'.$index,$value['technique']);

        $sheet->mergeCells('G'.$index)->getStyle('G'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('G'.$index,$value['stance']);

        $sheet->mergeCells('H'.$index)->getStyle('H'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('H'.$index,$value['oral']);

        $sheet->mergeCells('I'.$index)->getStyle('I'.$index)->applyFromArray($styleArray)->getFont()->setSize(12);
        $sheet->setCellValue('I'.$index,$value['total']);
        $index++;
    }

    foreach ($sheet->getColumnIterator() as $column) {
       $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    $spreadsheet->setActiveSheetIndex(0);
    $writer = new Xlsx($spreadsheet); 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Head-MarkSheet.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit(0);
}

if($generate_pdf == 'PDF') {
    require_once __DIR__ . '../../vendor/autoload.php';
    
    include("exam_student_entrolled_pdf.php");    
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 5,
        'margin_right' => 5,
        'margin_top' => 5,
        'margin_bottom' => 5,
    ]);
    $mpdf->WriteHTML($html);
    $filename = "student_exam_certificate_belt_" . date('d-m-Y') . ".pdf";
    $mpdf->Output($filename, 'D');
    exit(0);
}
if($generate_pdf == 'PDF-2') {
    require_once __DIR__ . '../../vendor/autoload.php';
    include("exam_student_entrolled_pdf.php");    
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 5,
        'margin_right' => 5,
        'margin_top' => 5,
        'margin_bottom' => 5,
    ]);
    $mpdf->WriteHTML($html);
    $filename = "student_exam_certificate_belt_" . date('d-m-Y') . ".pdf";
    $mpdf->Output($filename, 'D');
    exit(0);
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
<?php echo $page_title.'    <small>'. $ex_name.'</small>'; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $page_title; ?></li>
                        <!-- <li class="active"><a href="javascript:void(0);" style="color:orange; font-size:14px; font-weight:bold;"  onclick="product_qty_manager_toggle('Certificate-Belt');">Deduct Qty</a></li> -->
                    </ol>
                    
                </section>

                <!-- Main content -->
                <section class="content">
<?php include("includes/messages.php"); ?>
                    <!-- Small boxes (Stat box) -->

                    <form id="form_enrollment" name="form_enrollment" method="post" >
                        <input type="hidden" id="act" name="act" />
                        <input type="hidden" id="pay_fee" name="pay_fee" />
                        <input type="hidden" name="export_data" id="export_data">
                        <input type="hidden" name="generate_pdf" id="generate_pdf">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <button type="button" onclick="selectExportType2();" class="btn btn-success">Result</button>
                                                <button type="button" onclick="selectExportType('form_enrollment');" class="btn btn-info">Export</button>
                                            </div> 
                                        </div>
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th align="center" width="70px">Sr.No</th>
                                                    <th align="center" >Gr No.</th>
                                                    <th align="center">Name</th>
                                                    <th align="center">B. Type</th>
                                                    <th align="center" >Belt</th>
                                                    <th align="center" >Couse</th>
<?php if ($enroll == 1) { ?>
                                                        <th align="center" >Join D.</th>
<?php } ?>
<?php if ($addresult == 1) { ?>
                                                    <th align="center">T. Marks</th>
                                                    <?php } ?>
                    <?php if ($pay_fee == 1) { ?>
                                                        <th align="center">Fee</th>
<?php } ?>
<?php if ($pay_fee == 1 || $enroll == 1) { ?>
                                                        <th align="center">Paid?</th>
<?php } ?>
<?php if ($addresult == 1) { ?>
                                                        <th align="center">R. Marks</th>
                                                        <th align="center">R. Status</th>
                                                        <th align="center">Finalized</th>
<?php } ?>
<?php if ($addcertificate == 1) { ?>
                                                        <th align="center">Result</th>
<?php } ?>

                                                    <th align="center" class="t_align_center"  width="120px">Action
<?php if ($enroll == 1) { ?> <input type="checkbox" id="enrollment_check_all" name="" onchange="check_uncheck_enrollment_check();" > <?php } ?>
<?php if (isset($addcertificate) && $addcertificate == 1) { echo "<br/>Cer./Belt"; }?>

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
        // Dont delete this commented code its required 
        // if ($db_row['exs_paid'] == 0)
        // {
        //       if (validate_fees_paid_or_not($ex_id,$db_row['stu_id'],$db_row['exs_co_id'],$db_row['exs_be_id']) == true)
        //       {
        //         $db_row['exs_paid'] = 1;
        //       }
        // }
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

                                                            <td style="padding-left:10px;"><?php echo $db_row['brt_name']; ?></td>
                                                            <td style="padding-left:10px;"><?php echo $db_row['be_name_for']; ?></td>
                                                            <td style="padding-left:10px;"><?php echo $db_row['co_name']; ?></td>
                                                        <?php if ($enroll == 1) { ?>
                                                                <td style="padding-left:10px;"><?php echo DBtoDisp($db_row['sc_joined_date']); ?></td>
                                                        <?php } ?>
                                                        <?php if ($addresult == 1) { ?>
                                                            <td style="padding-left:10px;"><?php echo $db_row['eca_total_marks']; ?></td>
                                                        <?php } ?>
                                                        <?php if ($pay_fee == 1) { ?>
                                                                <td style="padding-left:10px;"><?php echo $db_row['exs_fee']; ?></td>

                                                        <?php } ?>
                                                        <?php if (isset($addresult) && $addresult == 1) { ?>
                                                                <td style="padding-left:10px;"><?php echo $db_row['exs_result_marks']; ?></td>
                                                                <td style="padding-left:10px;" class="process_<?php echo $exs_result_status; ?>"><?php echo $exs_result_status; ?></td>
                                                                <td style="padding-left:10px;" class="finalized_<?php echo $db_row['exs_finalized'];?>"><?php echo $db_row['exs_finalized']; ?></td>
                                                        <?php } ?>
                                                        <?php if ($pay_fee == 1 || $enroll == 1) { 
                                                            // echo "<pre>";
                                                            // print_r($db_row);
                                                            // echo "</pre>";
                                                            // exit(0);
                                                            // if($pay_fee ==1 && isset($db_row['exs_already_paid']) && $db_row['exs_already_paid'] == 'Y' ) { $exs_paid = ''; }
                                                            if($enroll ==1 && isset($db_row['exs_already_paid']) && $db_row['exs_already_paid'] == 'Y' ) { $exs_paid = 'NA'; }
                                                            ?>
                                                                <td style="padding-left:10px;" class="process_<?php echo $exs_paid; ?>"  ><?php echo $exs_paid; ?> </td>
                                                        <?php } ?>
                                                        <?php if ($addcertificate == 1) { ?>
                                                            <td style="padding-left:10px;"  class="process_<?php echo $exs_result_status; ?>"  ><?php echo $exs_result_status; ?> </td>
                                                        <?php } ?>
                                                            <td>
                                                                <center>
                                                        <?php if (isset($enroll) && $enroll == 1) { ?>
                                                                        <input type="checkbox" class="enrollment_check" <?php echo $checked . ' ' . $read_only; ?> id="chk_process" name="chk_process[]" value="<?php echo $db_row['stu_id']; ?>"  />&nbsp;&nbsp;<a href="" class="fa fa-forward" ></a>
                                                        <?php } else if (isset($pay_fee) && $pay_fee == 1 && $db_row['exs_paid'] == 0 && $db_row['exs_already_paid'] != 'Y') { ?>
                                                            <a id="pay_button_<?php echo $db_row['exs_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="pay_fee_student_exam(<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['exs_id']; ?>,<?php echo $db_row['stu_br_id']; ?>,<?php echo $db_row['exs_fee']; ?>,<?php echo $db_row['exs_paid']; ?>, 'Pay Student Exam Fee', '<?php echo $db_row['stu_first_name'] . '  ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?>');">Pay Exam Fee</a>
                                                <?php } else if (isset($pay_fee) && $pay_fee == 1 && $db_row['exs_paid'] == 1) 
                                                {
                            if ($db_row['exs_already_paid'] == 'N') { ?>
                            <a href="javascript:void(0);" class="text-info" onclick="print_fee_receipt('Exam fee',0,<?php echo $db_row['exs_id']; ?>)">Receipt</a>
                <?php
                                } else { echo 'No Payment'; }
                            } else if (isset($pay_fee) && $pay_fee == 1 && $db_row['exs_paid'] == 0 && $db_row['exs_already_paid'] == 'Y')  {
                                echo 'No Payment';
                            } else if (isset($addresult) && $addresult == 1) { ?>

                                                                        <a id="result_button_<?php echo $db_row['exs_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="add_student_exam_result(<?php echo $db_row['exs_ex_id']; ?>,<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['exs_id']; ?>,<?php echo $db_row['stu_br_id']; ?>, 'Add Student Result', '<?php echo $db_row['stu_first_name'] . '  ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?>');">Add Exam Result</a>
        <?php } ?>
        <?php if (isset($addcertificate) && $addcertificate == 1) {
            $checked_certificate  = $checked_belt = "";
            if ($db_row['exs_certificate'] == 'Y' ) { $checked_certificate = "checked "; }
            if ($db_row['exs_belt'] == 'Y' ) { $checked_belt = "checked "; }
            ?>
            <input type="checkbox" class="addcertificate_check" <?php echo $checked_certificate; ?> id="chk_process" name="chk_certificate[]" value="<?php echo $db_row['exs_id']; ?>"  />
            <input type="checkbox" class="addbelt_check" <?php echo $checked_belt; ?> id="chk_process" name="chk_belt[]" value="<?php echo $db_row['exs_id']; ?>"  />
            <a href="javascript:void(0);" class="fa fa-fw fa-arrows" onclick="product_qty_manager_toggle('Certificate-Belt');"></a>
                                                        <?php } ?>
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
                                                    <?php // echo $pageObj->renderFullNav(); ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php } ?>
                                                <?php if ($enroll == 1) {
                                                    ?>
                                            <div style="text-align:right;"><button type="button" onclick="student_exam_enrollment_process();" class="btn btn-info">Process</button></div>
                                                <?php } else if ($addcertificate == 1) {
                                                    ?>
                                            <div style="text-align:right;"><button type="button" onclick="student_certification_belt_process();" class="btn btn-info">Process</button></div>
                                                <?php } ?>
                                    </div>
                                    <!-- start of greed 2 -->
                                    <!-- end of greed 2 -->

                                </div>
                            </div>
                            </section>
                        </div>
                    </form>
                    <?php
                    if ($addcertificate == '1') 
                     include("includes/product_qty_manager.php");
                    ?>

                                                <?php include("includes/models.php"); ?>
                                                <?php include("includes/footer.php"); ?>

                <div class="modal export-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body export-content text-center">
                            </div>      
                        </div>
                    </div>
                </div>

                <div class="modal export-modal-2" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body export-content-2 text-center">
                            </div>      
                        </div>
                    </div>
                </div>

            </div>
            <script>
                $(document).on("change",".addcertificate_check,.addbelt_check",function () {
                    $(this).prop("disabled",true);
                });
            </script>
    </body>
</html>
