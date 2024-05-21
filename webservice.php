<?php
// main calling api is student_exam_details
error_reporting(0);
include('includes/application_top.php');

if(isset($_POST['json'])){
       $data_array=json_decode(stripslashes($_POST['json']));
       if($data_array->name=='login')
       {
	     	echo student_login($data_array);
       }
       else if($data_array->name=='personal_profile')
       {
        echo student_profile($data_array);
       }
       else if($data_array->name=='add_contact_request')
       {
        echo add_contact_request($data_array);
       }
       else if($data_array->name=='get_branch_details')
       {
        echo get_branch_details_ws($data_array);
       }
       else if($data_array->name=='student_exam_details')
       {
        echo student_exam_details($data_array);
       }
       else if($data_array->name=='student_event_details')
       {
        echo student_event_details($data_array);
       }
       else if($data_array->name=='student_course_details')
       {
        echo student_course_details($data_array);
       }
       else if($data_array->name=='student_book_details')
       {
        echo student_book_details($data_array);
       }
       else 
       {
        $array=array("status"=>"fail","message"=>"invalue request or argument");
        return json_encode($array);
	   }
}
function add_contact_request($data_array)
{
    include("includes/class/contact.php");
    $sm_contact = new Contact();

        $sm_contact->data["con_name"] = escape($data_array->cname);
        $sm_contact->data["con_email"] = escape($data_array->email);
        $sm_contact->data["con_phone"] = escape($data_array->phone);
        $sm_contact->data["con_message"] = escape($data_array->message);
        $sm_contact->data["con_date"] = date('Y-m-d');
        $sm_contact->data["con_br_id"] = 1;
        $sm_contact->data["con_status"] = "Open";
        $sm_contact->data["con_create_date"] = date('Y-m-d');
        $sm_contact->data["con_create_by_id"] = 1;
        $sm_contact->data["con_update_date"] = date('Y-m-d');
        $sm_contact->data["con_update_by_id"] = 1;

        $sm_contact->action = 'insert';
        $result = $sm_contact->process();
        if ($result['status'] == 'failure') {
           return $result['errormsg'];
        } else {
            return "conact us has been added successfully";
        }
}
function get_branch_details_ws($data_array)
{
    $response_array =array("status"=>"fail","message"=>"");
    $q = "SELECT br_add_1 , br_add_2 , br_city,  br_district ,  br_contact_p_phone1,	st_name,br_postalcode , br_country FROM sm_branch LEFT JOIN sm_state ON (br_state_id= st_id ) WHERE br_type= 'branch' AND br_status = 'A' " ;
        $r = m_process("get_data",$q);

        if ($r["status"] == 'error') 
        {
            $response_array["status"]= "fail";
            $response_array["message"]=$r["errormsg"];
        } 
        else if ($r["count"]> 0) 
        {
                $response_array["data"] = $r['res'];
                $response_array["status"]= "success";
                $response_array["message"]="";
        }
        return json_encode($response_array);
}
function student_login($data_array)
{
    $response_array =array("status"=>"fail","message"=>"","id"=>0,"stu_gr_no"=>"");
       $q = "SELECT stu_id, stu_status, stu_gr_no FROM sm_student WHERE stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."'"; 
      
        $r = m_process("get_data",$q);
//        print_r($r);
 //       exit(0);
        if ($r["status"] == 'error') {
            $response_array["status"]= "fail";
            $response_array["message"]=$r["errormsg"];
        } 
        else if ($r["count"]> 0) 
        {
            // if ($r["res"][0]['stu_status']!='A')
            // {
            //     $response_array["status"]= "fail";
            //     $response_array["message"]="Your account is not active";
            // }
            // else
            // {
                $response_array["status"]= "success";
                $response_array["id"]= $r["res"][0]['stu_id'];
                $response_array["stu_gr_no"]= $r["res"][0]['stu_gr_no'];
                $response_array["message"]="Login successful";
            // }
        }
        else
        {
            $response_array["status"]= "fail";
            $response_array["message"]="Invalid login details";
        }
        return json_encode($response_array);
}
function student_profile($data_array)
{
    $response_array =array("status"=>"fail","message"=>"");

    $q = "SELECT bt_name, br_name, br_contact_p_name1,br_contact_p_phone1,br_contact_p_email1,br_add_1,br_add_2,br_country,br_postalcode, stu_gr_no, stu_first_name, stu_middle_name, stu_last_name,  DATE_FORMAT(stu_birth_date,'%d-%b-%Y') as stu_birth_date , stu_phone, stu_email, stu_home_address, stu_office_address, stu_city, stu_state_id, stu_postal_code, stu_mother_name, stu_parent_mobile_no, stu_aadharno, stu_whatsappno, stu_batchtime, stu_parent3_phone, stu_status, stu_medium, stu_sc_id, stu_current_course, stu_photo, stu_user_type, stu_br_id,  DATE_FORMAT(stu_admission_date,'%d-%b-%Y') as stu_admission_date, DATE_FORMAT(stu_deactivation_date,'%d-%b-%Y') as stu_deactivation_date FROM sm_student LEFT JOIN sm_branch ON (stu_br_id = br_id) LEFT JOIN sm_batch_time ON (stu_batchtime=bt_id)  WHERE stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."'"; 
    $r = m_process("get_data",$q);

    if ($r["status"] == 'error') {
        $response_array["status"]= "fail";
        $response_array["message"]=$r["errormsg"];
    } else if ($r["count"]> 0) {
        // if ($r["res"][0]['stu_status']!='A')
        // {
        //     $response_array["status"]= "fail";
        //     $response_array["message"]="Your account is not active";
        // }
        // else
        // {
            $response_array["data"] = $r['res'];
            $response_array["status"]= "success";
            $response_array["message"]="";
        // }
    }
    return json_encode($response_array);
}

function student_exam_details($data_array)
{
    $response_array =array("status"=>"fail","message"=>"");
// stu_status, sc_remarks, sc_total_fee,sc_total_paid, sc_is_current, sc_id, brt_name,be_name,be_name_for, sc_half_course, co_name, DATE_FORMAT(sc_joined_date,'%d-%b-%Y') as sc_joined_date
// keep // $q = "SELECT DISTINCT ex_date, DATE_FORMAT(ex_date,'%d-%b-%Y') as ex_date_display ,  sc_half_course , ex_name, exs_certificate, exs_already_paid, exs_result_status,exs_result_marks,exs_enroll_next, exs_total_marks, exs_fee,exs_paid, exs_total_marks as eca_total_marks, be_name_for, be_name, be_belt_duration ,be_belt_exam_fee, DATE_FORMAT(sc_joined_date,'%d-%b-%Y') as sc_joined_date, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name, stu_br_id, brt_name FROM sm_exam INNER JOIN sm_exam_student_entrolled ON (ex_id = exs_ex_id) INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id ) INNER JOIN sm_course ON (co_id = sc_co_id) INNER JOIN sm_belt ON (be_id = sc_be_id) INNER JOIN sm_student ON (stu_id = sc_stu_id ) INNER JOIN sm_branch_type ON (sc_brt_id = brt_id ) WHERE exs_result_status = 'P' AND stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."' ORDER BY ex_date ASC "; 

    $q = "SELECT DISTINCT sbe.be_name as be_name_exam, sbe.be_name_for as be_name_for_exam , ex_date, DATE_FORMAT(ex_date,'%d-%b-%Y') as ex_date_display ,  sc_half_course , ex_name, exs_certificate, exs_already_paid, exs_result_status,exs_result_marks,exs_enroll_next, exs_total_marks, exs_fee,exs_paid, exs_total_marks as eca_total_marks, be.be_name_for, be.be_name, be.be_belt_duration ,be.be_belt_exam_fee, DATE_FORMAT(sc_joined_date,'%d-%b-%Y') as sc_joined_date_display, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name, stu_br_id, brt_name 
    FROM 
    sm_student_course 
    LEFT JOIN sm_exam_student_entrolled ON (exs_stu_id = sc_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id ) 
    LEFT JOIN sm_exam  ON (ex_id = exs_ex_id)  
    INNER JOIN sm_course ON (co_id = sc_co_id) 
    INNER JOIN sm_belt be ON (be.be_id = sc_be_id) INNER JOIN sm_student ON (stu_id = sc_stu_id ) 
    INNER JOIN sm_belt sbe ON (sbe.be_id = exs_be_id) 
    INNER JOIN sm_branch_type ON (sc_brt_id = brt_id ) 
    WHERE (exs_result_status = '' OR exs_result_status = 'P' OR exs_result_status IS NULL)  AND stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."' ORDER BY sc_joined_date ASC "; 
    // $q = "SELECT DISTINCT ex_date, DATE_FORMAT(ex_date,'%d-%b-%Y') as ex_date_display ,  sc_half_course , ex_name, exs_certificate, exs_already_paid, exs_result_status,exs_result_marks,exs_enroll_next, exs_total_marks, exs_fee,exs_paid, exs_total_marks as eca_total_marks, be_name_for, be_name, be_belt_duration ,be_belt_exam_fee, DATE_FORMAT(sc_joined_date,'%d-%b-%Y') as sc_joined_date, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name, stu_br_id, brt_name FROM sm_exam INNER JOIN sm_exam_student_entrolled ON (ex_id = exs_ex_id) INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id ) INNER JOIN sm_course ON (co_id = sc_co_id) INNER JOIN sm_belt ON (be_belt_duration != 0 AND be_id = sc_be_id) INNER JOIN sm_student ON (stu_id = sc_stu_id ) INNER JOIN sm_branch_type ON (sc_brt_id = brt_id ) WHERE exs_result_status = 'P' AND stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."' ORDER BY ex_date ASC "; 
    $r = m_process("get_data",$q);

    if ($r["status"] == 'error') {
        $response_array["status"]= "fail";
        $response_array["message"]=$r["errormsg"]
        ;
    } else if ($r["count"]> 0) {
        // if ($r["res"][0]['stu_status']!='A')
        // {
        //     $response_array["status"]= "fail";
        //     $response_array["message"]="Your account is not active";
        // }
        // else
        // {
            $response_array["data"] = $r['res'];
            $response_array["status"]= "success";
            $response_array["message"]="";
        // }
    }
    return json_encode($response_array);
}

function student_event_details($data_array)
{
    $response_array =array("status"=>"fail","message"=>"");

    $q = "SELECT DISTINCT stu_status, ev_date, ev_name, DATE_FORMAT(ev_date,'%d-%b-%Y') as ev_date_display , evs_fee,  evs_paid  FROM sm_event INNER JOIN sm_event_student_entrolled ON (ev_id = evs_ev_id) INNER JOIN sm_student ON (evs_stu_id = stu_id) WHERE  stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."' ORDER BY ev_date ASC "; 
    $r = m_process("get_data",$q);

    if ($r["status"] == 'error') {
        $response_array["status"]= "fail";
        $response_array["message"]=$r["errormsg"]
        ;
    } else if ($r["count"]> 0) {
        // if ($r["res"][0]['stu_status']!='A')
        // {
        //     $response_array["status"]= "fail";
        //     $response_array["message"]="Your account is not active";
        // }
        // else
        // {
            $response_array["data"] = $r['res'];
            $response_array["status"]= "success";
            $response_array["message"]="";
        // }
    }
    return json_encode($response_array);
}

function student_course_details($data_array)
{
    $response_array =array("status"=>"fail","message"=>"");
    
    $q = "SELECT stu_status, sc_remarks, sc_total_fee,sc_total_paid, sc_is_current, sc_id, brt_name,be_name,be_name_for, sc_half_course, co_name, DATE_FORMAT(sc_joined_date,'%d-%b-%Y') as sc_joined_date   FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id ) WHERE stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."' ORDER BY sc_id ASC "; 
    $r = m_process("get_data",$q);

    if ($r["status"] == 'error') {
        $response_array["status"]= "fail";
        $response_array["message"]=$r["errormsg"];
    } else if ($r["count"]> 0) {
        // if ($r["res"][0]['stu_status']!='A')
        // {
        //     $response_array["status"]= "fail";
        //     $response_array["message"]="Your account is not active";
        // }
        // else
        // {
            $response_array["data"] = $r['res'];
            $response_array["status"]= "success";
            $response_array["message"]="";
        //}
    }
    return json_encode($response_array);
}

function student_book_details($data_array)
{
    $response_array =array("status"=>"fail","message"=>"");

    $q = "SELECT bt_name, br_name, stu_gr_no , stu_first_name, stu_middle_name, stu_last_name,  DATE_FORMAT(stu_birth_date,'%d-%b-%Y') as stu_birth_date , stu_phone, stu_email, stu_home_address, stu_office_address, stu_city, stu_state_id, stu_postal_code, stu_mother_name, stu_parent_mobile_no, stu_aadharno, stu_whatsappno, stu_batchtime, stu_parent3_phone, stu_status, stu_medium, stu_sc_id, stu_current_course, stu_photo, stu_user_type, stu_br_id,  DATE_FORMAT(stu_admission_date,'%d-%b-%Y') as stu_admission_date, DATE_FORMAT(stu_deactivation_date,'%d-%b-%Y') as stu_deactivation_date FROM sm_student LEFT JOIN sm_branch ON (stu_br_id = br_id) LEFT JOIN sm_batch_time ON (stu_batchtime=bt_id)  WHERE stu_gr_no = '".$data_array->gr_no."' AND stu_password = '".md5($data_array->password)."'"; 
    $r = m_process("get_data",$q);

    if ($r["status"] == 'error') {
        $response_array["status"]= "fail";
        $response_array["message"]=$r["errormsg"];
    } else if ($r["count"]> 0) {
        // if ($r["res"][0]['stu_status']!='A')
        // {
        //     $response_array["status"]= "fail";
        //     $response_array["message"]="Your account is not active";
        // }
        // else
        // {
            $response_array["data"] = $r['res'];
            $response_array["status"]= "success";
            $response_array["message"]="Login successful";
        // }
    }
    return json_encode($response_array);
}
?>