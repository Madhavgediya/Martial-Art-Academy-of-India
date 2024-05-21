<?php
include("includes/application_top.php");
include("../includes/class/student.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$stu_id = get_rdata('stu_id');
$stu_gr_no = get_rdata('stu_gr_no');

$stu_first_name = get_rdata('stu_first_name');
$stu_middle_name = get_rdata('stu_middle_name');
$stu_last_name = get_rdata('stu_last_name');
$stu_phone = get_rdata('stu_phone');
$stu_birth_date = get_rdata('stu_birth_date');
$sc_course_type = get_rdata('sc_course_type','1 month');
$stu_admission_date = get_rdata('stu_admission_date');
$stu_deactivation_date = get_rdata('stu_deactivation_date');
if ($stu_admission_date == '') {
    $stu_admission_date = DBtoDisp($cur_date);
}
$stu_home_address = get_rdata('stu_home_address');
$stu_office_address = get_rdata('stu_office_address');
$stu_city = get_rdata('stu_city');
$stu_state_id = get_rdata('stu_state_id',0);
$stu_postal_code = get_rdata('stu_postal_code');
$stu_photo_old = get_rdata('stu_photo_old');
$stu_mother_name = get_rdata('stu_mother_name');
$stu_parent_mobile_no = get_rdata('stu_parent_mobile_no');
$stu_email = get_rdata('stu_email');
$stu_aadharno = get_rdata('stu_aadharno');
$stu_whatsappno = get_rdata('stu_whatsappno');
$stu_batchtime = get_rdata('stu_batchtime',$tmp_default_batch_time);
$stu_parent3_phone = get_rdata('stu_parent3_phone');
$stu_status = get_rdata('stu_status','A');
$stu_status_old = get_rdata('stu_status_old','A');
$stu_medium = get_rdata('stu_medium','English');
$stu_sc_id = get_rdata('stu_sc_id',0);
$sc_half_course = get_rdata('sc_half_course',0);
$stu_current_course = get_rdata('stu_current_course');
$stu_std_id = get_rdata('stu_std_id',0);
$stu_user_type = get_rdata('stu_user_type','N');
$stu_create_date = $cur_date;
$stu_create_by_id = $tmp_admin_id;
$stu_update_date = $cur_date;
$stu_update_by_id = $tmp_admin_id;
$lo_lastlogin_date = $cur_date;
$stu_br_id = $tmp_admin_id;

$sc_brt_id = get_rdata('sc_brt_id',0);
$sc_co_id = get_rdata('sc_co_id',0);
$sc_be_id = get_rdata('sc_be_id',0);
$sc_cd_id = get_rdata('sc_cd_id',0);
$sc_joined_date = get_rdata('sc_joined_date');
if ($sc_joined_date == '') {
    $sc_joined_date = DBtoDisp($cur_date);
}
$sc_is_current = get_rdata('sc_is_current',1);
if ($_FILES['stu_photo']['error'] == 0) {
    $file_array = explode(".", $_FILES['stu_photo']['name']);
    $file_ext = $file_array [count($file_array) - 1];
    $file_ext = strtolower($file_ext);
    if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
        $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
        die(json_encode(["success"=>false,"successmsg"=>$errormsg])); 
    } else {
        $stu_photo = "student_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['stu_photo']['name']);
        $DestPath = STUDENT_IMAGE . $stu_photo;
        move_uploaded_file($_FILES['stu_photo']['tmp_name'], $DestPath);
    }
} 
else {
    $stu_photo = 'student-default-no-image.jpg';
}
  
$sm_student = new student();
$sm_student->data["stu_gr_no"] = "";
$sm_student->data["stu_first_name"] = $stu_first_name;
$sm_student->data["stu_middle_name"] = $stu_middle_name;
$sm_student->data["stu_last_name"] = $stu_last_name;
$sm_student->data["stu_phone"] = $stu_phone;
$sm_student->data["stu_birth_date"] = disptoDB($stu_birth_date);
$sm_student->data["stu_admission_date"] = disptoDB($sc_joined_date);
if ($stu_deactivation_date !='')
{ $sm_student->data["stu_deactivation_date"] = disptoDB($stu_deactivation_date); }
$sm_student->data["stu_home_address"] = escape($stu_home_address);
$sm_student->data["stu_office_address"] = escape($stu_office_address);
$sm_student->data["stu_city"] = $stu_city;
$sm_student->data["stu_photo"] =  SITE_URL . "images/student/" . $stu_photo;
$sm_student->data["stu_state_id"] = $stu_state_id;
$sm_student->data["stu_postal_code"] = $stu_postal_code;
$sm_student->data["stu_mother_name"] = $stu_mother_name;
$sm_student->data["stu_parent_mobile_no"] = $stu_parent_mobile_no;
$sm_student->data["stu_email"] = $stu_email;
$sm_student->data["stu_aadharno"] = $stu_aadharno;
$sm_student->data["stu_whatsappno"] = $stu_whatsappno;
$sm_student->data["stu_batchtime"] = $stu_batchtime;
$sm_student->data["stu_parent3_phone"] = $stu_parent3_phone;
$sm_student->data["stu_status"] = $stu_status;
$sm_student->data["stu_medium"] = $stu_medium;
$sm_student->data["stu_sc_id"] = $stu_sc_id;
$sm_student->data["stu_current_course"] = $stu_current_course;
$sm_student->data["stu_std_id"] = $stu_std_id;
$sm_student->data["stu_user_type"] = $stu_user_type;
$sm_student->data["stu_create_date"] = $stu_create_date;
$sm_student->data["stu_create_by_id"] = $stu_create_by_id;
$sm_student->data["stu_update_date"] = $stu_update_date;
$sm_student->data["stu_update_by_id"] = $stu_update_by_id;
$sm_student->data["stu_br_id"] = $stu_br_id;
$sm_student->action = 'insert';
$result = $sm_student->process();
if ($result['status'] == 'failure') {
    die(json_encode(["success"=>false,"successmsg"=>$result['errormsg']])); 
} else {
    $arr_update_gr =  update_gr_no($result['id'],$tmp_admin_id,"sm_student");
    if ($arr_update_gr['status'] == 'failure') {
        die(json_encode(["success"=>false,"successmsg"=>$arr_update_gr['errormsg']]));
    } else {
        
              
        if (($sc_be_id !='' && $sc_be_id >0 ) && ($sc_brt_id !='' && $sc_brt_id >0) && ($sc_co_id !='' && $sc_co_id >0) ){
            $arr_course_data = array();
            $arr_course_data['sc_total_paid'] = 0;
            $arr_course_data['sc_full_fee_paid'] = 'N';
            $arr_course_data['sc_is_current'] = 1;
            $arr_course_data['sc_cd_id'] = 0;
            $arr_course_data['sc_be_id'] = $sc_be_id;
            $arr_course_data['sc_co_id'] = $sc_co_id;
            $arr_course_data['sc_brt_id'] = $sc_brt_id;
            $arr_course_data['sc_br_id'] = $tmp_admin_id;
            $arr_course_data['sc_course_type'] = $sc_course_type;
            $arr_course_data['sc_stu_id'] = $result["id"];
            $arr_course_data['sc_joined_date'] = disptoDB($sc_joined_date);
            $arr_course_data['sc_create_date'] = $cur_date;
            $arr_course_data['sc_update_date'] = $cur_date;
            $arr_course_data['sc_create_by_id'] = $tmp_admin_id;
            $arr_course_data['sc_update_by_id'] = $tmp_admin_id;
            $arr_course_data['sc_half_course'] = $sc_half_course;
            add_course_to_student($arr_course_data);
        }
       $data_arr_input = array();
        $data_arr_input['select_field'] = " CONCAT(stu_gr_no,'-',stu_first_name,' ', stu_last_name) as stu_name, stu_id ";
        $data_arr_input['table'] = 'sm_student';
        $data_arr_input['where'] = " stu_br_id = " . $tmp_admin_id ;
        $data_arr_input['key_id'] = 'stu_id';
        $data_arr_input['key_name'] = 'stu_name';
        $data_arr_input['current_selection_value'] = $result['id'];
        $data_arr_input['order_by'] = ' stu_gr_no, stu_first_name, stu_last_name';
        die(json_encode(["success"=>true,"successmsg"=>$stu_first_name." ".$stu_last_name ." (".$result['id']. ") has been added.","data"=>display_dd_options_return($data_arr_input)]));
        }
     }

echo $errormsg;