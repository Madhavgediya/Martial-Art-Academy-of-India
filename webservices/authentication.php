<?php

include("../includes/database.php");
include("../includes/functions.php");
include("../includes/class/login.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$req_school = isset($_POST['school']) ? $_POST['school'] : '';
$req_login_id = isset($_POST['login_id']) ? $_POST['login_id'] : '';
$req_login_password = isset($_POST['login_password']) ? $_POST['login_password'] : '';
$req_method = isset($_POST['method']) ? $_POST['method'] : '';
$validate = true;
$response_array = array();
$remote_ip = "";
$cur_date = date('Y-m-d H:i:s');
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $remote_ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $remote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $remote_ip = $_SERVER['REMOTE_ADDR'];
}
$post_data_request = "";
if (isset($_POST)) {
    $post_data_request = serialize($_POST);
    // $post_data_request = implode(",",$_POST);
}
$wrl_id = 0;
$log_ins = m_process("insert", "INSERT INTO sm_web_request_log (wrl_id, wrl_ip, wrl_method, wrl_request_data, wrl_datetime) VALUES (NULL, '" . $remote_ip . "', '" . $req_method . "', '" . $post_data_request . "', '".$cur_date."')");
$wrl_id =  $log_ins["id"] ;
if ($req_method == '') {
    $response_array['error_code'] = '004';
    $response_array['error_message'] = 'Incorrect value of method';
    $validate = false;
} else if ($req_school == '') {
    $response_array['error_code'] = '004';
    $response_array['error_message'] = 'Incorrect value of school';
    $validate = false;

} else if ($req_login_id == '') {
    $response_array['error_code'] = '004';
    $response_array['error_message'] = 'Incorrect value of user name';
    $validate = false;
} else if ($req_login_password == '') {
    $response_array['error_code'] = '004';
    $response_array['error_message'] = 'Incorrect value of password';
    $validate = false;
}
if ($validate == true) {
    if ($req_method == 'studentlogin') {
        /*
        $login = new login();
        $login->data["*"] = "lo_id";
        $login->where = " lo_user_name = '" . $req_login_id . "' AND  lo_password  = '" . md5($req_login_password) . "' AND lo_access_type = 'student' ";
        $login->action = 'get';
        $result = $login->process();
        if ($result['status'] == 'failure') {
            $response_array['error_code'] = '002';
            $response_array['error_message'] = $result['errormsg'];
        } else {
            if ($result['count'] > 0) {
                $response_array['login_id'] = $result['res'][0]['lo_id'];
                $response_array['success_message'] = 'You have logged in successfully.';
            } else {
                $response_array['error_code'] = '003';
                $response_array['error_message'] = 'Invalid login credential or your profile is not active';
            }
        }
         */
        $q = "SELECT lo.lo_id as session_id, s.stu_photo, s.stu_gr_no ,s.stu_first_name,s.stu_middle_name,s.stu_last_name,s.stu_phone,s.stu_current_course,s.stu_add_1,s.stu_add_2,s.stu_city,	s.stu_postal_code,	s.stu_parent1_name, s.stu_parent1_phone	,s.stu_parent2_name,	s.stu_parent2_phone,s.stu_parent3_name,s.stu_parent3_phone , stand.std_name , c.cl_name FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) INNER JOIN sm_standard stand ON (stand.std_id = s.stu_sc_id) INNER JOIN sm_class c ON (c.cl_id = s.stu_cl_id)
WHERE sm.sc_status = 'A' AND lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' AND s.stu_status = 'A'  AND lo.lo_status = 'A' AND lo.lo_user_name =  '" . $req_login_id  ."' AND  lo_password  = '" . md5($req_login_password) . "'";
      //   echo $q;
        $result = m_process("get_data", $q);

        if ($result['errormsg'] != '') {
            $response_array['error_code'] = '002';
            $response_array['error_message'] = $result['errormsg'];
        } else {
            if ($result['count'] > 0) {
                $rows_ret = array();
                /*
                echo '<pre>';
                print_r($result['res']);
                echo '</pre>';
                echo '</br>start';
                 json_encode($result['res']);
                echo $result['res'][0]["[stu_first_name"];
                echo '</br>end';
                 */
                foreach ($result['res'] as $region_row) {
                    $rows_ret[] = $region_row;
                }
                $response_array['success_code'] = 1;
                $response_array['response'] = $rows_ret;
            } else {
                $response_array['error_code'] = '003';
                $response_array['error_message'] = 'Invalid login credential or your profile is not active';
            }
        }
         
    } else {
        $response_array['error_code'] = '001';
        $response_array['error_message'] = 'Invalid method name';
    }
} else {
    
}
// $wrl_response_data = json_encode($response_array);
$q_response = "UPDATE sm_web_request_log SET wrl_response_datetime = '".date('Y-m-d H:i:s')."' WHERE  wrl_id =  " . $wrl_id  ;
m_process("update", $q_response);
db_dispose_connection();
echo json_encode($response_array);
exit;
?>