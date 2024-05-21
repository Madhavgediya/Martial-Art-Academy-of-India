<?php
include("includes/application_top.php");
include("../includes/class/student.php");
if ($tmp_admin_id == 0) {
    echo "invalid request";
    exit(0);
}

$id = get_rdata('stu_id');
$stu_id = get_rdata('stu_id');
$stu_gr_no = get_rdata('stu_gr_no');
$stu_first_name = get_rdata('stu_first_name');
$stu_middle_name = get_rdata('stu_middle_name');
$stu_last_name = get_rdata('stu_last_name');
$stu_mother_name = get_rdata('stu_mother_name');
$stu_birth_date = get_rdata('stu_birth_date');
$stu_home_address = get_rdata('stu_home_address');
$stu_office_address = get_rdata('stu_office_address');
$stu_email = get_rdata('stu_email');
$stu_parent_mobile_no = get_rdata('stu_parent_mobile_no');
$stu_phone = get_rdata('stu_phone');
$stu_whatsappno = get_rdata('stu_whatsappno');
$stu_aadharno = get_rdata('stu_aadharno');
$stu_batchtime = get_rdata('stu_batchtime');
$stu_status = get_rdata('stu_status');
$stu_status_old = get_rdata('stu_status_old');
$stu_deactivation_date = get_rdata('stu_deactivation_date');
$stu_update_date = $cur_date;
$stu_update_by_id = $tmp_admin_id;
$stu_create_date = $cur_date;
$stu_create_by_id = $tmp_admin_id;
$stu_br_id = $tmp_admin_id;
$stu_photo_old = get_rdata('stu_photo_old');

if ($_FILES['stu_photo']['error'] == 0) {  /// Image
    $file_array = explode(".", $_FILES['stu_photo']['name']);
    $file_ext = $file_array[count($file_array) - 1];
    $file_ext = strtolower($file_ext);
    if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
        $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
    } else {
        $stu_photo = "student_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['stu_photo']['name']);
        $DestPath = STUDENT_IMAGE . $stu_photo;
        move_uploaded_file($_FILES['stu_photo']['tmp_name'], $DestPath);
        $stu_photo = "http://maccess.martialartacademyofindia.com//images/student/" . $stu_photo;
    }
} else {
    $stu_photo = $stu_photo_old;
}

if (!$errormsg) {
    $sm_student = new student();
    $sm_student->data["stu_first_name"] = $stu_first_name;
    $sm_student->data["stu_middle_name"] = $stu_middle_name;
    $sm_student->data["stu_last_name"] = $stu_last_name;
    $sm_student->data["stu_mother_name"] = $stu_mother_name;
    $sm_student->data["stu_birth_date"] = disptoDB($stu_birth_date);
    $sm_student->data["stu_home_address"] = escape($stu_home_address);
    $sm_student->data["stu_office_address"] = escape($stu_office_address);
    $sm_student->data["stu_email"] = $stu_email;
    $sm_student->data["stu_parent_mobile_no"] = $stu_parent_mobile_no;
    $sm_student->data["stu_phone"] = $stu_phone;
    $sm_student->data["stu_whatsappno"] = $stu_whatsappno;
    $sm_student->data["stu_aadharno"] = $stu_aadharno;
    $sm_student->data["stu_batchtime"] = $stu_batchtime;
    $sm_student->data["stu_status"] = $stu_status;
    if ($stu_deactivation_date != '') {
        $sm_student->data["stu_deactivation_date"] = disptoDB($stu_deactivation_date);
    }
    $sm_student->data["stu_photo"] = $stu_photo;
    $sm_student->data["stu_update_date"] = $stu_update_date;
    $sm_student->data["stu_update_by_id"] = $stu_update_by_id;
    $sm_student->data["stu_br_id"] = $stu_br_id;
    $sm_student->action = 'update';
    $sm_student->process_id = $id;
    $result = $sm_student->process();

    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($stu_status != $stu_status_old) {
            $q_log = 'INSERT INTO sm_activeinactive (ac_stu_id, ac_date, ac_status, ac_br_id, ac_create_date, ac_create_by_id, ac_update_date, ac_update_by_id) VALUES (' . $id . '.,"' . disptoDB($stu_deactivation_date) . '","' . $stu_status . '",' . $stu_br_id . ',"' . $stu_create_date . '",' . $stu_create_by_id . ',"' . $stu_update_date . '",' . $stu_update_by_id . ')';
            $r_log = m_process("insert", $q_log);
            if ($r_log["status"] == "failure") {
                $errormsg = $r_log["errormsg"];
            } else {
                $errormsg = 'success';
            }
        } else {
            $errormsg = 'success';
        }
    }    
}
echo $errormsg;
