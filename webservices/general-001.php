<?php

include("../includes/database.php");
include("../includes/functions.php");
include("../includes/class/faculty.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$req_school = isset($_POST['school']) ? $_POST['school'] : '';
$req_session_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';
$req_method = isset($_POST['method']) ? $_POST['method'] : '';
$cur_date = date('Y-m-d H:i:s');

echo '<pre>';
print_r($_POST);
$response_array = array();
$remote_ip = "";
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
m_process("insert", "INSERT INTO sm_web_request_log (wrl_id, wrl_ip, wrl_method, wrl_request_data, wrl_datetime) VALUES (NULL, '" . $remote_ip . "', '" . $req_method . "', '" . $post_data_request . "', '" . $cur_date . "')");
if ($req_method == 'faculty') {
    $q = "SELECT f.fac_identity_id,f.fac_name, f.fac_subject, f.fac_experience  FROM sm_faculty f INNER JOIN sm_school_master sm ON (fac_sc_id=sc_id)
WHERE f.fac_status = 'A' AND sm.sc_name='" . $req_school . "'";
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $response_array['error_code'] = '002';
        $response_array['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            $rows_ret = array();
            foreach ($result['res'] as $region_row) {
                $rows_ret[] = $region_row;
            }
            $response_array['success_code'] = 1;
            $response_array['response'] = $rows_ret;
        } else {
            $response_array['success_code'] = 1;
            $response_array['success_message'] = 'no records found';
        }
    }
} else if ($req_method == 'complain') {
    $req_complain_title = isset($_POST['complain_title']) ? $_POST['complain_title'] : '';
    $req_complain_description = isset($_POST['complain_description']) ? $_POST['complain_description'] : '';
    $cm_identy_id = randomPrefix(10);
    $q = "INSERT INTO sm_complain (cm_id, cm_identy_id, cm_stu_id, cm_title, cm_description, cm_status, cm_create_date, cm_create_by_id, cm_update_date, cm_update_by_id) VALUES (NULL, '" . $cm_identy_id . "', '" . $req_session_id . "', '" . $req_complain_title . "', '" . $req_complain_description . "', 'A', '" . $cur_date . "', '" . $req_session_id . "', '" . $cur_date . "', '" . $req_session_id . "')";

    $result = m_process("insert", $q);

    if ($result['errormsg'] != '') {
        $response_array['error_code'] = '002';
        $response_array['error_message'] = $result['errormsg'];
    } else {
        if ($result['id'] > 0) {
            $rows_ret = array();
            $rows_ret['complain_no'] = $cm_identy_id;
            $response_array['success_code'] = 1;
            $response_array['response'] = $rows_ret;
        } else {
            $response_array['success_code'] = 1;
            $response_array['success_message'] = 'no records found';
        }
    }
} else {
    $response_array['error_code'] = '001';
    $response_array['error_message'] = 'Invalid method name';
}
db_dispose_connection();
echo json_encode($response_array);
exit;
?>