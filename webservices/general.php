<?php

include("../includes/database.php");
include("../includes/functions.php");
include("../includes/class/faculty.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * faculty
 * faculty-login
 * complain
 * complain-reply 
 * student
 * school-contactus
 * school-news
 * school-events
 * school-gallery
 * school-gallery-details
 * get-complain
 * get-complain-details
 * get-event-details
 * get-attendance-details
 * get-result-details 
 * get-time-table
 * school-article
 * add_gcm
 */
$req_school = isset($_POST['school']) ? $_POST['school'] : '';
$req_session_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';
$req_method = isset($_POST['method']) ? $_POST['method'] : '';
$req_gallery_id = isset($_POST['gallery_id']) ? $_POST['gallery_id'] : 0;
$req_complain_description_reply = isset($_POST['complain_description_reply']) ? $_POST['complain_description_reply'] : '';
$req_cm_identy_id = isset($_POST['cm_identy_id']) ? $_POST['cm_identy_id'] : 0;
$req_cm_id = isset($_POST['cm_id']) ? $_POST['cm_id'] : 0;
$req_event_id = isset($_POST['event_id']) ? $_POST['event_id'] : 0;
$req_std_name = isset($_POST['std_name']) ? $_POST['std_name'] : '';
$req_att_month = isset($_POST['att_month']) ? $_POST['att_month'] : 0;
$req_att_year = isset($_POST['att_year']) ? $_POST['att_year'] : 0;
$req_ci_id = isset($_POST['ci_id']) ? $_POST['ci_id'] : 0;
$req_gcm_id = isset($_POST['ci_id']) ? $_POST['ci_id'] : '';
$req_dailydarshan_id = isset($_POST['dailydarshan_id']) ? $_POST['dailydarshan_id'] : '';
//$req_gcm_id = isset($_POST['gcm_id']) ? $_POST['gcm_id'] : '';

$cur_date = date('Y-m-d H:i:s');

//echo '<pre>';
//print_r($_POST);
$validate = true;
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
//m_process("insert", "INSERT INTO sm_web_request_log (wrl_id, wrl_ip, wrl_method, wrl_request_data, wrl_datetime) VALUES (NULL, '" . $remote_ip . "', '" . $req_method . "', '" . $post_data_request . "', '" . $cur_date . "')");
$wrl_id = 0;
$log_ins = m_process("insert", "INSERT INTO sm_web_request_log (wrl_id, wrl_ip, wrl_method, wrl_request_data, wrl_datetime) VALUES (NULL, '" . $remote_ip . "', '" . $req_method . "', '" . $post_data_request . "', '" . $cur_date . "')");
$wrl_id = $log_ins["id"];

if ($req_method == '') {
    $response_array['error_code'] = '001';
    $response_array['error_message'] = 'Incorrect value for method';
    $validate = false;
//} else if ($req_method != 'register-gcm') {
//    $response_array['error_code'] = '002';
//    $response_array['error_message'] = 'Incorrect value for school';
//    $validate = false;
} else if (($req_method != 'school-article' && $req_method != 'register-gcm' && $req_method != 'school-news' && $req_method != 'school-events' && $req_method != 'dailydarshan' && $req_method != 'dailydarshan-details' && $req_method != 'school-gallery' && $req_method != 'school-gallery-details' && $req_method != 'get-event-details' && $req_method != 'faculty' && $req_method != 'school-circular' && $req_method != 'get-circular-details' ) && $req_session_id == '') {
    $response_array['error_code'] = '003';
    $response_array['error_message'] = 'Incorrect value for session';
    $validate = false;
}
if ($validate == true && $req_method == 'school-gallery-details' && ($req_gallery_id == 0 || $req_gallery_id == '')) {
    $response_array['error_code'] = '004';
    $response_array['error_message'] = 'Incorrect value for gallery';
    $validate = false;
}
if ($validate == true && $req_method == 'dailydarshan-details' && ($req_dailydarshan_id == 0 || $req_dailydarshan_id == '')) {
    $response_array['error_code'] = '005';
    $response_array['error_message'] = 'Incorrect value for daily darshan';
    $validate = false;
}
if ($validate == true && $req_method == 'complain-reply' && ($req_cm_identy_id == '0' || $req_cm_identy_id == '')) {
    $response_array['error_code'] = '006';
    $response_array['error_message'] = 'Incorrect value for complain no';
    $validate = false;
}
if ($validate == true && $req_method == 'complain-reply' && ($req_complain_description_reply == '')) {
    $response_array['error_code'] = '007';
    $response_array['error_message'] = 'Incorrect value for complain reply';
    $validate = false;
}
if ($validate == true && $req_method == 'get-complain-details-' && ($req_cm_identy_id == '' || $req_cm_identy_id == 0)) {
    $response_array['error_code'] = '008';
    $response_array['error_message'] = 'Incorrect value for complain input param';
    $validate = false;
}
if ($validate == true && $req_method == 'get-event-details' && ($req_event_id == '' || $req_event_id == '0')) {
    $response_array['error_code'] = '009';
    $response_array['error_message'] = 'Incorrect value for event details';
    $validate = false;
}
// if ($validate == true && $req_method == 'get-result-details' && ($req_std_name == '' || $req_std_name == '0')) {
if ($validate == true && $req_method == 'get-attendance-details' && ($req_att_month == '' || $req_att_month == '0' || $req_att_year == '' || $req_att_year == '0')) {
    $response_array['error_code'] = '010';
    $response_array['error_message'] = 'Incorrect value for attendance';
    $validate = false;
}
if ($validate == true && $req_method == 'faculty-login' && ($req_session_id == '' || $req_session_id == '0')) {
    $response_array['error_code'] = '011';
    $response_array['error_message'] = 'Incorrect value for faculty login';
    $validate = false;
}
if ($validate == true && $req_method == 'get-circular-details' && ($req_ci_id == '' || $req_ci_id == '0')) {
    $response_array['error_code'] = '012';
    $response_array['error_message'] = 'Incorrect value for circular details';
    $validate = false;
}



if ($validate == true) {
    if ($req_method == 'faculty') {
        $q = "SELECT f.fac_identity_id,f.fac_name, f.fac_experience ,f.fac_photo FROM sm_faculty f INNER JOIN sm_school_master sm ON (fac_sc_id=sc_id)
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
    } else if ($req_method == 'faculty-login') {
        $q = "SELECT f.fac_identity_id,f.fac_name, f.fac_subject, f.fac_experience, f.fac_photo  
FROM sm_faculty f 
INNER JOIN sm_school_master sm ON (f.fac_sc_id=sm.sc_id) 
INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id) 
INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)  
INNER JOIN sm_standard stand  ON (s.stu_std_id = stand.std_id)  
INNER JOIN sm_class cl  ON (s.stu_cl_id = cl.cl_id)  
INNER JOIN sm_faculty_standard  fs ON (fs.facs_fac_id = f.fac_id AND fs.facs_std_id = stand.std_id  AND fs.facs_cl_id  = s.stu_cl_id AND fs.facs_medium = s.stu_medium )
WHERE f.fac_status = 'A' AND sm.sc_name='" . $req_school . "' AND lo.lo_status = 'A'  AND lo.lo_id =  " . $req_session_id;
//echo    $q;     
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
    }
    /*
      retrive complain details
     */ else if ($req_method == 'complain') {

        $req_complain_title = isset($_POST['complain_title']) ? $_POST['complain_title'] : '';
        $req_complain_description = isset($_POST['complain_description']) ? $_POST['complain_description'] : '';
        $cm_identy_id = randomPrefix(10);
        $reg_student_id_arr = get_student_id_from_login_id($req_session_id);
        if ($reg_student_id_arr["error_message"] != '') {
            $response_array['error_code'] = '001';
            $response_array['error_message'] = 'Invalid session id';
        } else if ($reg_student_id_arr["id"] == 0) {
            $response_array['error_code'] = '0011';
            $response_array['error_message'] = 'Invalid session id';
        } else {
            $q = "INSERT INTO sm_complain (cm_id, cm_identy_id, cm_stu_id, cm_title, cm_description, cm_status, cm_create_date, cm_create_by_id, cm_update_date, cm_update_by_id) VALUES (NULL, '" . $cm_identy_id . "', '" . $reg_student_id_arr["id"] . "', '" . $req_complain_title . "', '" . $req_complain_description . "', 'A', '" . $cur_date . "', '" . $req_session_id . "', '" . $cur_date . "', '" . $req_session_id . "')";
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
        }
    } else if ($req_method == 'complain-reply') {
        $reg_student_id_arr = get_student_id_from_login_id($req_session_id);
        if ($reg_student_id_arr["error_message"] != '') {
            $response_array['error_code'] = '001';
            $response_array['error_message'] = 'Invalid session id';
        } else if ($reg_student_id_arr["id"] == 0) {
            $response_array['error_code'] = '0011';
            $response_array['error_message'] = 'Invalid session id';
        } else {

            $q_0 = "SELECT cm_id FROM sm_complain WHERE cm_stu_id =  " . $reg_student_id_arr["id"] . " AND cm_identy_id = '" . $req_cm_identy_id . "'";
            $result_0 = m_process("get_data", $q_0);
            if ($result_0['errormsg'] != '') {
                $response_array['error_code'] = '002';
                $response_array['error_message'] = $result_0['errormsg'];
            } else {
                if ($result_0['count'] == 0) {
                    $response_array['error_code'] = '002';
                    $response_array['error_message'] = 'invalid identity no';
                } else {
                    $cm_id = $result_0['res'][0]['cm_id'];

                    // $q = "INSERT INTO sm_complain (cm_id, cm_identy_id, cm_stu_id, cm_title, cm_description, cm_status, cm_create_date, cm_create_by_id, cm_update_date, cm_update_by_id) VALUES (NULL, '" . $cm_identy_id . "', '" . $reg_student_id_arr["id"] . "', '" . $req_complain_title . "', '" . $req_complain_description . "', 'A', '" . $cur_date . "', '" . $req_session_id . "', '" . $cur_date . "', '" . $req_session_id . "')";
                    $q = "INSERT INTO sm_complain_details (cmd_id, cmd_cm_id, cmd_text, cmd_status, cmd_create_by_stu_id, cmd_create_by_admin_id, cmd_create_date, cmd_create_by_id, cmd_update_date, cmd_update_by_id) VALUES (NULL, '" . $cm_id . "', '" . $req_complain_description_reply . "', 'A', '" . $reg_student_id_arr["id"] . "', '0', '" . $cur_date . "', '" . $req_session_id . "', '" . $cur_date . "', '" . $req_session_id . "')";
                    $result = m_process("insert", $q);

                    if ($result['errormsg'] != '') {
                        $response_array['error_code'] = '002';
                        $response_array['error_message'] = $result['errormsg'];
                    } else {
                        if ($result['id'] > 0) {
                            $response_array['success_code'] = 1;
                            $response_array['success_message'] = "complain reply added successfully. ## Ref no. " . $result['id'];
                        } else {
                            $response_array['success_code'] = 1;
                            $response_array['success_message'] = 'no records found';
                        }
                    }
                }
            }
        }
    } else if ($req_method == 'student') {
        $q = "SELECT s.stu_gr_no ,s.stu_photo,	s.stu_first_name,s.stu_middle_name,s.stu_last_name,s.stu_phone,s.stu_current_course,s.stu_add_1,s.stu_add_2,s.stu_city,	s.stu_postal_code,	s.stu_parent1_name, s.stu_parent1_phone	,s.stu_parent2_name,	s.stu_parent2_phone,s.stu_parent3_name,s.stu_parent3_phone , stand.std_name , c.cl_name   FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) INNER JOIN sm_standard stand ON (stand.std_id = s.stu_sc_id) INNER JOIN sm_class c ON (c.cl_id = s.stu_cl_id) 
WHERE sm.sc_status = 'A' AND lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' AND s.stu_status = 'A'  AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id;
        // echo $q;
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
    } else if ($req_method == 'school-contactus') {
        $q = "SELECT sm.sc_name, sm.sc_address_1, sm.sc_address_2 , sm.sc_city , sm.sc_phone_1 , sm.sc_phone_2 , sm.sc_email , sm.sc_website , sm.sc_latitude, sm.sc_longitude  FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)
WHERE sm.sc_status = 'A' AND lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' AND s.stu_status = 'A'  AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id;
        // echo $q;
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
    } else if ($req_method == 'school-news') {
        $q = "SELECT n.ne_title, n.ne_cover_image, n.ne_text , DATE_FORMAT(n.ne_update_date,'%d-%c-%Y') as ne_update_date   FROM  sm_school_master sm INNER JOIN sm_news n ON (sm.sc_id = n.ne_sc_id)
WHERE n.ne_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "'  ORDER BY ne_update_date DESC ";
        // echo $q;
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
    } else if ($req_method == 'school-events') {
        $q = "SELECT e.ev_id, e.ev_title, e.ev_text,e.ev_cover_image , DATE_FORMAT(e.ev_start_date,'%d-%c-%Y') as ev_start_date , DATE_FORMAT(e.ev_end_date,'%d-%c-%Y') as ev_end_date    FROM  sm_school_master sm INNER JOIN sm_events e ON (sm.sc_id = e.ev_sc_id)
WHERE e.ev_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "' ORDER BY ev_start_date DESC ";
        //echo $q;
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
    } else if ($req_method == 'school-gallery') {
        $q = "SELECT g.ga_id, g.ga_title,ga_cover_image  FROM sm_school_master sm INNER JOIN sm_gallery g ON (sm.sc_id = g.ga_sc_id) WHERE g.ga_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "' ";
        //    echo $q;
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
    } else if ($req_method == 'school-gallery-details') {
        $q = "SELECT g.ga_title as gp_title, gp.gp_image,gp.gp_image_alt, g.ga_id, g.ga_title,ga_cover_image  FROM sm_school_master sm INNER JOIN sm_gallery g ON (sm.sc_id = g.ga_sc_id) INNER JOIN sm_gallery_photos gp ON (g.ga_id=gp.gp_ga_id)
WHERE g.ga_status = 'A' AND gp.gp_status = 'A' AND  sm.sc_status = 'A'  AND sm.sc_name='" . $req_school . "' AND g.ga_id = " . $req_gallery_id . "  ORDER BY gp.gp_update_date ASC";
        //   echo $q;
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
    } else if ($req_method == 'get-complain') {
        $reg_student_id_arr = get_student_id_from_login_id($req_session_id);
        if ($reg_student_id_arr["error_message"] != '') {
            $response_array['error_code'] = '001';
            $response_array['error_message'] = 'Invalid session id';
        } else if ($reg_student_id_arr["id"] == 0) {
            $response_array['error_code'] = '0011';
            $response_array['error_message'] = 'Invalid session id';
        } else {
            $q = "SELECT  cm_identy_id , cm_id ,cm_title ,cm_description , count(*) as no_of_reply , DATE_FORMAT(cm_create_date,'%d-%c-%Y') as complain_date FROM sm_complain comp  LEFT JOIN sm_complain_details compdet ON (comp.cm_id = compdet.cmd_cm_id) WHERE cm_stu_id = " . $reg_student_id_arr["id"] . "  GROUP BY cm_identy_id , cm_id ,cm_title ,cm_description ORDER BY 	cm_create_date  ";
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
        }
    } else if ($req_method == 'get-complain-details') {
        $reg_student_id_arr = get_student_id_from_login_id($req_session_id);
        if ($reg_student_id_arr["error_message"] != '') {
            $response_array['error_code'] = '001';
            $response_array['error_message'] = 'Invalid session id';
        } else if ($reg_student_id_arr["id"] == 0) {
            $response_array['error_code'] = '0011';
            $response_array['error_message'] = 'Invalid session id';
        } else {

            $q = "SELECT  IF(cmd_create_by_admin_id !=0,admin_uname,CONCAT(stu_first_name,' ', stu_last_name)) as action_by_name , cmd_text , DATE_FORMAT(cmd_create_date,'%d-%c-%Y') as complain_date FROM sm_complain comp  LEFT JOIN sm_complain_details compdet ON (comp.cm_id = compdet.cmd_cm_id) LEFT JOIN sm_student st ON (st.stu_id = compdet.cmd_create_by_stu_id) LEFT JOIN sm_admin ad ON (ad.admin_id = compdet.cmd_create_by_admin_id) WHERE cm_stu_id = " . $reg_student_id_arr["id"] . " AND 	cm_identy_id = '" . $req_cm_identy_id . "'  ORDER BY  cmd_create_date   ";
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
        }
    } else if ($req_method == 'get-event-details') {
        $q = "SELECT ed.ei_title, ed.ei_image,ed.ei_ev_id  FROM sm_school_master sm INNER JOIN sm_events e ON (sm.sc_id = e.ev_sc_id) INNER JOIN sm_events_images ed ON (e.ev_id=ed.ei_ev_id)
WHERE e.ev_status = 'A' AND ed.ei_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "' AND e.ev_id = " . $req_event_id . "  ORDER BY ed.ei_update_date ASC";
        //    echo $q;
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
    } else if ($req_method == 'get-attendance-details') {
        $arr_attendance = array();
        $arr_attendance['att_attended'] = '';
        $arr_attendance['att_absent'] = '';
        /*
          $q = "SELECT stdm.std_name, s.stu_gr_no, s.stu_first_name, s.stu_middle_name ,s.stu_last_name ,  att.att_month, att.att_year, att.att_attended , att.att_absent  FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) INNER JOIN sm_attendance att ON (s.stu_id = att.att_stu_id) INNER JOIN sm_standard stdm  ON (stdm.std_id=att.att_std_id)
          WHERE  stdm.std_status='A' AND  sm.sc_status = 'A' AND lo.lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' AND s.stu_status = 'A'   AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id . " AND stdm.std_name = '" . $req_std_name . "'  ORDER BY att.att_month, att.att_year ASC";
          //  echo $q;
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
         */
        $q_att_attended = "SELECT   GROUP_CONCAT(DAY(att_date))  as att_attended
                FROM sm_student s  
                INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) 
                INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) 
                INNER JOIN sm_standard stdm  ON (stdm.std_id=s.stu_std_id)
                INNER JOIN sm_class cl  ON (cl.cl_id=s.stu_cl_id)
                INNER JOIN sm_attendance_b att ON (att.att_sc_id = sm.sc_id AND att.att_stu_medium = s.stu_medium AND att.att_std_id = stdm.std_id  AND att.att_cl_id = cl.cl_id  )
                WHERE  sm.sc_status = 'A'  AND cl.cl_id = s.stu_cl_id AND lo.lo_access_type = 'student'  AND s.stu_status = 'A'  AND lo.lo_status = 'A'  AND FIND_IN_SET(s.stu_roll_no,att.att_attended)
                AND sm.sc_name='" . $req_school . "' AND lo.lo_id =  " . $req_session_id . " AND MONTH(att_date) = " . (int) $req_att_month . " AND YEAR(att_date) =" . (int) $req_att_year;
        $result_att_attended = m_process("get_data", $q_att_attended);

        if ($result_att_attended['errormsg'] != '') {
            $response_array['error_code'] = '002';
            $response_array['error_message'] = $result_att_attended['errormsg'];
        } else {
            if ($result_att_attended['count'] > 0) {
                $arr_attendance['att_attended'] = $result_att_attended['res'][0]['att_attended'];
            }
        }

        $q_att_absent = "SELECT   GROUP_CONCAT(DAY(att_date))  as att_absent
                FROM sm_student s  
                INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) 
                INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) 
                INNER JOIN sm_standard stdm  ON (stdm.std_id=s.stu_std_id)
                INNER JOIN sm_class cl  ON (cl.cl_id=s.stu_cl_id)
                INNER JOIN sm_attendance_b att ON (att.att_sc_id = sm.sc_id AND att.att_stu_medium = s.stu_medium AND att.att_std_id = stdm.std_id  AND att.att_cl_id = cl.cl_id  )
                WHERE  sm.sc_status = 'A'  AND cl.cl_id = s.stu_cl_id AND lo.lo_access_type = 'student'  AND s.stu_status = 'A'  AND lo.lo_status = 'A'  AND FIND_IN_SET(s.stu_roll_no,att.att_absent)
                AND sm.sc_name='" . $req_school . "' AND lo.lo_id =  " . $req_session_id . " AND MONTH(att_date) = " . (int) $req_att_month . " AND YEAR(att_date) =" . (int) $req_att_year;

        $result_att_absent = m_process("get_data", $q_att_absent);

        if ($result_att_absent['errormsg'] != '') {
            $response_array['error_code'] = '002';
            $response_array['error_message'] = $result_att_absent['errormsg'];
        } else {
            if ($result_att_absent['count'] > 0) {
                $arr_attendance['att_absent'] = $result_att_absent['res'][0]['att_absent'];
            }
        }

        if (!isset($response_array['error_code'])) {
            $response_array['success_code'] = 1;
            $response_array['response'][0] = $arr_attendance;
        }
        /*
          echo $q_att_present;
          echo '<br>';
          echo $q_att_absent;
          exit;
          $q = "SELECT stdm.std_name, s.stu_gr_no, s.stu_first_name, s.stu_middle_name ,s.stu_last_name ,  att.att_month, att.att_year, att.att_attended , att.att_absent  FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) INNER JOIN sm_attendance att ON (s.stu_id = att.att_stu_id) INNER JOIN sm_standard stdm  ON (stdm.std_id=att.att_std_id)
          WHERE  stdm.std_status='A' AND  sm.sc_status = 'A' AND lo.lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' AND s.stu_status = 'A'   AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id . " AND stdm.std_name = '" . $req_std_name . "'  ORDER BY att.att_month, att.att_year ASC";
         */
    } else if ($req_method == 'get-result-details') {
        /*
          $q = "SELECT stdm.std_name, s.stu_gr_no, s.stu_first_name, s.stu_middle_name ,s.stu_last_name ,  att.att_month, att.att_year, att.att_attended , att.att_absent  FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) INNER JOIN sm_result res ON (s.stu_id = res.res_st_id) INNER JOIN sm_standard stdm  ON (stdm.std_id=res.res_std_id)
          WHERE  stdm.std_status='A' AND  sm.sc_status = 'A' AND lo.lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' AND s.stu_status = 'A' AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id .  " AND stdm.std_name = '" . $req_std_name . "'  ORDER BY att.att_month, att.att_year ASC";
         */
        $q = "SELECT res.res_image, res.res_total_marks, DATE_FORMAT(res.res_examdate,'%e-%b-%Y') res_examdate, res.res_obtain_marks , IF(res_total_marks!=0,round((res.res_obtain_marks/res.res_total_marks*100),2),0) as res_percent ,  res.res_title , res.res_description ";
        $q .= "FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) ";
        $q .= "INNER JOIN sm_results res ON (s.stu_id = res.res_stu_id) INNER JOIN sm_standard stdm  ON (stdm.std_id=res.res_std_id)";
        $q .= "WHERE  sm.sc_status = 'A' AND lo.lo_access_type = 'student' AND sm.sc_name='" . $req_school . "'";
        $q .= " AND s.stu_status = 'A' AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id . " ORDER BY res.res_id DESC ";

        /*
          $q = "SELECT res.res_image ";
          $q .= "FROM sm_student s  INNER JOIN sm_school_master sm ON (s.stu_sc_id=sm.sc_id) INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id) ";
          $q .= "INNER JOIN sm_results res ON (s.stu_id = res.res_stu_id) INNER JOIN sm_standard stdm  ON (stdm.std_id=res.res_std_id)";
          $q .= "INNER JOIN sm_subject sub  ON (sub.sub_id=res.res_sub_id)";
          $q .= "WHERE  stdm.std_status='A' AND  sm.sc_status = 'A' AND lo.lo_access_type = 'student' AND sm.sc_name='" . $req_school . "' ";
          $q .= "AND sub.sub_sc_id = sm.sc_id AND s.stu_status = 'A' AND sub.sub_status = 'A' AND lo.lo_status = 'A' AND lo.lo_id =  " . $req_session_id . " AND stdm.std_name = '" . $req_std_name . "'  ORDER BY sub.sub_name ASC";
         */
        //    echo $q;
//          exit;
        /*
          $result_arr_student_grad = array();
          $result_arr_student_grad["std_name"] = "";
          $result_arr_student_grad["stu_first_name"] = "";
          $result_arr_student_grad["stu_middle_name"] = "";
          $result_arr_student_grad["stu_last_name"] = "";
          $result_arr_student_grad["stu_spi"] = "";
          $result_arr_student_grad["stu_cpi"] = "";
          $result_arr_student_grad["stu_current_sam_blocking"] = "";
         */

        $result = m_process("get_data", $q);

        if ($result['errormsg'] != '') {
            $response_array['error_code'] = '002';
            $response_array['error_message'] = $result['errormsg'];
        } else {
            if ($result['count'] > 0) {
                $rows_ret = array();
                $i = 0;
                //    echo "<pre>";

                foreach ($result['res'] as $region_row) {
                    /*
                      if ($i == 0) {
                      $result_arr_student_grad["std_name"] = $region_row["std_name"];
                      $result_arr_student_grad["stu_first_name"] = $region_row["stu_first_name"];
                      $result_arr_student_grad["stu_middle_name"] = $region_row["stu_middle_name"];
                      $result_arr_student_grad["stu_last_name"] = $region_row["stu_last_name"];
                      }
                     */
                    $rows_ret[] = $region_row;
                    /*
                      unset($rows_ret[$i][6]);
                      unset($rows_ret[$i][7]);
                      unset($rows_ret[$i][8]);
                      unset($rows_ret[$i][9]);
                      unset($rows_ret[$i][10]);
                      unset($rows_ret[$i]["stu_gr_no"]);
                      unset($rows_ret[$i]["stu_first_name"]);
                      unset($rows_ret[$i]["stu_middle_name"]);
                      unset($rows_ret[$i]["stu_last_name"]);
                      unset($rows_ret[$i]["std_name"]);
                      $i++;
                     */
                }
                /*
                  $result_arr_student_grad["stu_spi"] = "NA";
                  $result_arr_student_grad["stu_cpi"] = "NA";
                  $result_arr_student_grad["stu_current_sam_blocking"] = "0";
                 */
                $response_array['success_code'] = 1;
                $response_array['response'] = $rows_ret;
                //$response_array['response'][1] = $result_arr_student_grad;
            } else {
                $response_array['success_code'] = 1;
                $response_array['success_message'] = 'no records found';
            }
        }
    } else if ($req_method == 'get-time-table') {
        $q = "SELECT tt.tt_image,tt.tt_title
FROM sm_school_master sm 
INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id) 
INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)  
INNER JOIN sm_standard stand  ON (s.stu_std_id = stand.std_id)  
INNER JOIN sm_class cl  ON (s.stu_cl_id = cl.cl_id)  
INNER JOIN sm_timetable  tt ON (tt.tt_std_id = stand.std_id  AND tt.tt_cl_id  = s.stu_cl_id AND tt.tt_medium = s.stu_medium  AND tt.tt_sc_id = sm.sc_id)
WHERE tt.tt_status = 'A' AND sm.sc_name='" . $req_school . "' AND lo.lo_status = 'A'  AND lo.lo_id =  " . $req_session_id;
        /*
          $q = "SELECT t.tt_image,f.tt_title
          FROM sm_timetable tt
          INNER JOIN sm_school_master sm ON (tt.tt_sc_id=sm.sc_id)
          INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id)
          INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)
          INNER JOIN sm_standard stand  ON (s.stu_std_id = stand.std_id)
          INNER JOIN sm_class cl  ON (s.stu_cl_id = cl.cl_id)
          INNER JOIN sm_faculty_standard  fs ON (fs.facs_fac_id = f.fac_id AND fs.facs_std_id = stand.std_id  AND fs.facs_cl_id  = s.stu_cl_id AND fs.facs_medium = s.stu_medium )
          WHERE f.fac_status = 'A' AND sm.sc_name='" . $req_school . "' AND lo.lo_status = 'A'  AND lo.lo_id =  ".$req_session_id;
         */
//echo $q;     
//exit;
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
    } else if ($req_method == 'school-article') {
        $q = "SELECT a.art_title, a.art_document, a.art_text , DATE_FORMAT(a.art_update_date,'%d-%c-%Y') as art_date   FROM  sm_school_master sm INNER JOIN sm_article a ON (sm.sc_id = a.art_sc_id)
WHERE a.art_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "'  ORDER BY a.art_update_date DESC ";
        // echo $q;
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
    } else if ($req_method == 'school-circular') {
        $q = "SELECT ci.ci_id, ci.ci_title, ci.ci_text,ci.ci_cover_image  FROM  sm_school_master sm INNER JOIN sm_circular ci ON (sm.sc_id = ci.ci_sc_id)
WHERE ci.ci_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "' ORDER BY ci.ci_update_date DESC ";
        //echo $q;
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
    } else if ($req_method == 'get-circular-details') {
        $q = "SELECT cid.cid_image  FROM sm_school_master sm INNER JOIN sm_circular ci ON (sm.sc_id = ci.ci_sc_id) INNER JOIN sm_circular_details cid ON (ci.ci_id=cid.cid_ci_id)
WHERE ci.ci_status = 'A' AND cid.cid_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "' AND ci.ci_id = " . $req_ci_id . "  ORDER BY cid.cid_id ASC";
        //    echo $q;
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
    } else if ($req_method == 'register-gcm') {
        $action_to_process = "";
        if ($req_school == '' && $req_session_id == 0) {
            $q_0 = "SELECT DISTINCT gcm_gcm_id ,s.stu_id , sm.sc_id FROM 
            sm_gcm g  LEFT JOIN sm_school_master sm  ON (g.gcm_sc_id = sm.sc_id )  
            LEFT JOIN sm_student s ON (s.stu_sc_id=sm.sc_id AND g.gcm_stu_id = s.stu_id ) 
            LEFT JOIN  sm_login lo  ON (lo.lo_access_id = s.stu_id)  
            WHERE g.gcm_gcm_id =  '" . $req_gcm_id . "'";

            ///
            $result_0 = m_process("get_data", $q_0);


            if ($result_0['errormsg'] != '') {
                $response_array_0['error_code'] = '002';
                $response_array_0['error_message'] = $result_0['errormsg'];
            } else {
                if ($result_0['count'] > 0) {
                    // found in db checking GCM id in DB 
                    if ($result_0["res"][0]["gcm_gcm_id"] != "" && $result_0["res"][0]["gcm_gcm_id"] == $req_gcm_id) {
                        // No action needed here
                        $response_array['error_code'] = 1;
                        $response_array['error_message'] = 'already registered';
                    }
                } else {
                    // not found in db
                    $action_to_process = "add-new";
                    $stu_id = 0;
                    $sc_id = 0;
                }
            }
            ///
        } else {
            // case when GCM and SESSION ID is not blank
            ////////
           $q_0 = "SELECT DISTINCT gcm_gcm_id ,s.stu_id , sm.sc_id , sm.sc_name ,lo.lo_id FROM 
            sm_gcm g  LEFT JOIN sm_school_master sm  ON (g.gcm_sc_id = sm.sc_id )  
            LEFT JOIN sm_student s ON (s.stu_sc_id=sm.sc_id AND g.gcm_stu_id = s.stu_id ) 
            LEFT JOIN  sm_login lo  ON (lo.lo_access_id = s.stu_id)  
            WHERE g.gcm_gcm_id =  '" . $req_gcm_id . "'";
          
            ///
            $result_0 = m_process("get_data", $q_0);

            //   WHERE s.stu_status = 'A' AND sm.sc_name='" . $req_school . "' AND lo.lo_status = 'A'  AND lo.lo_id =  " . $req_session_id;

            if ($result_0['errormsg'] != '') {
                $response_array_0['error_code'] = '002';
                $response_array_0['error_message'] = $result_0['errormsg'];
            } else {
                if ($result_0['count'] > 0) {
                    // found in db checking GCM id in DB 
                    if ($result_0["res"][0]["gcm_gcm_id"] != "" && $result_0["res"][0]["stu_id"] != 0 && $result_0["res"][0]["sc_id"] != 0 && $result_0["res"][0]["gcm_gcm_id"] == $req_gcm_id && $req_school == $result_0["res"][0]["sc_name"] && $req_session_id == $result_0["res"][0]["lo_id"]) {
                        // No action needed here
                        $response_array['error_code'] = 1;
                        $response_array['error_message'] = 'already registered';
                        $action_to_process = "do-nothing";
                    } else {
                        // not found in db with school and other details.
                        $action_to_process = "update-existing";
                    }
                    $school_student_details = get_student_id_school_id($req_school, $req_session_id);
                    $stu_id = $school_student_details["stu_id"];
                    $sc_id = $school_student_details["sc_id"];
                }
 else {
                    $action_to_process = "add-new";
                    $school_student_details = get_student_id_school_id($req_school, $req_session_id);
                    $stu_id = $school_student_details["stu_id"];
                    $sc_id = $school_student_details["sc_id"];
 }
                //////////
            }

            // Code to add a new GCM to system
            
        }
        
        if ($action_to_process == "add-new") {
                //  echo "INSERT INTO sm_gcm(gcm_id, gcm_stu_id, gcm_gcm_id, gcm_sc_id, gcm_datetime) VALUES (NULL,$stu_id,'$req_gcm_id',$sc_id,'$cur_date')";
                $gcm_ins_q = "INSERT INTO sm_gcm(gcm_id, gcm_stu_id, gcm_gcm_id, gcm_sc_id, gcm_datetime) VALUES (NULL,$stu_id,'$req_gcm_id',$sc_id,'$cur_date')";
                $gcm_ins = m_process("insert", $gcm_ins_q);
                if ($log_ins["errormsg"] != "") {
                    $response_array['error_code'] = '005';
                    $response_array['error_message'] = $gcm_ins['errormsg'];
                } else {
                    $response_array['success_code'] = 1;
                    $response_array['success_message'] = 'registered successfully';
                }
            } else if ($action_to_process == "update-existing") {
                //  echo "INSERT INTO sm_gcm(gcm_id, gcm_stu_id, gcm_gcm_id, gcm_sc_id, gcm_datetime) VALUES (NULL,$stu_id,'$req_gcm_id',$sc_id,'$cur_date')";
                $u_q = "UPDATE sm_gcm SET gcm_stu_id = " . $stu_id . ", gcm_sc_id = " . $sc_id . ", gcm_datetime = '" . $cur_date . "' WHERE gcm_gcm_id = '" . $req_gcm_id . "'";
                $gcm_ins = m_process("update", $u_q);
                if ($log_ins["errormsg"] != "") {
                    $response_array['error_code'] = '005';
                    $response_array['error_message'] = $gcm_ins['errormsg'];
                } else {
                    $response_array['success_code'] = 1;
                    $response_array['success_message'] = 'registered successfully';
                }
            }
            // end of add new GCM to system
            /*
              $q = "SELECT DISTINCT IFNULL(g.gcm_id,0) gcm_id, IFNULL(gcm_gcm_id,'') gcm_gcm_id ,s.stu_id , sm.sc_id FROM sm_school_master sm
              INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id)
              INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)
              LEFT JOIN sm_gcm g  ON (g.gcm_sc_id = sm.sc_id AND g.gcm_stu_id = s.stu_id )
              WHERE s.stu_status = 'A' AND sm.sc_name='" . $req_school . "' AND lo.lo_status = 'A'  AND lo.lo_id =  " . $req_session_id;

              //   echo $q;
              //   exit;
              $insert_new = true;
              $result = m_process("get_data", $q);

              $stu_id = 0;
              $sc_id = 0;

              if ($result['errormsg'] != '') {
              $response_array['error_code'] = '002';
              $response_array['error_message'] = $result['errormsg'];
              } else {
              if ($result['count'] > 0) {
              if ($result["res"][0]["gcm_gcm_id"] != "" && $result["res"][0]["gcm_gcm_id"] == $req_gcm_id) {
              $insert_new = false;
              $response_array['error_code'] = 1;
              $response_array['error_message'] = 'already registered';
              } else {
              //                    print_r($result["res"][0]);
              //                    echo "called me";
              $stu_id = $result["res"][0]["stu_id"];
              $sc_id = $result["res"][0]["sc_id"];
              }
              if ($insert_new == true) {
              //  echo "INSERT INTO sm_gcm(gcm_id, gcm_stu_id, gcm_gcm_id, gcm_sc_id, gcm_datetime) VALUES (NULL,$stu_id,'$req_gcm_id',$sc_id,'$cur_date')";
              $gcm_ins = m_process("insert", "INSERT INTO sm_gcm(gcm_id, gcm_stu_id, gcm_gcm_id, gcm_sc_id, gcm_datetime) VALUES (NULL,$stu_id,'$req_gcm_id',$sc_id,'$cur_date')");
              if ($log_ins["errormsg"] != "") {
              $response_array['error_code'] = '005';
              $response_array['error_message'] = $gcm_ins['errormsg'];
              } else {
              $response_array['success_code'] = 1;
              $response_array['success_message'] = 'registered successfully';
              }
              }
              } else {
              $response_array['error_code'] = 1;
              $response_array['error_message'] = 'Invalid login ';
              }
              }
             */
        } else if ($req_method == 'dailydarshan') {
            $q = "SELECT g.ga_id, g.ga_title,ga_cover_image  FROM sm_school_master sm INNER JOIN sm_dailydarshan g ON (sm.sc_id = g.ga_sc_id) WHERE g.ga_status = 'A' AND  sm.sc_status = 'A' AND sm.sc_name='" . $req_school . "' ";
            //    echo $q;
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
        } else if ($req_method == 'dailydarshan-details') {
            $q = "SELECT gp.gp_title, gp.gp_image,gp.gp_image_alt, g.ga_id, g.ga_title,ga_cover_image  FROM sm_school_master sm INNER JOIN sm_dailydarshan g ON (sm.sc_id = g.ga_sc_id) INNER JOIN sm_dailydarshan_photos gp ON (g.ga_id=gp.gp_ga_id)
WHERE g.ga_status = 'A' AND gp.gp_status = 'A' AND  sm.sc_status = 'A'  AND sm.sc_name='" . $req_school . "' AND g.ga_id = " . $req_dailydarshan_id . "  ORDER BY gp.gp_update_date ASC";
            //   echo $q;
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
        } else {
            $response_array['error_code'] = '001';
            $response_array['error_message'] = 'Invalid method name';
        }
    }

    /*
      retrive faculty details
     */
// $wrl_response_data = json_encode($response_array);
    $q_response = "UPDATE sm_web_request_log SET wrl_response_datetime = '" . date('Y-m-d H:i:s') . "' WHERE  wrl_id =  " . $wrl_id;
    m_process("update", $q_response);
    db_dispose_connection();
    echo json_encode($response_array);
    exit;
?>