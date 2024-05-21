<?php
include("includes/application_top.php");
include("../includes/class/student.php");
if ($tmp_admin_id == 0) {
    echo "invalid request";
    exit(0);
}
$action = get_rdata("action");
$p_id = get_rdata("p_id");
function checkEmpty($value){
    return !empty($value) && $value !=NULL && $value !=""  ? $value : "NULL";
}
if ($action == 'get-dealer-gst-type') 
{
    $inv_purchase_del_id = get_rdata("inv_purchase_del_id");
    echo get_dealer_gst_type($inv_purchase_del_id);
}
else if ($action == 'get-customer-gst-type') 
{
    $inv_purchase_del_id_other = get_rdata("inv_purchase_del_id_other");
    echo get_customer_gst_type($inv_purchase_del_id_other);
}
else if ($action == 'get-product-gst') 
{
    $pro_id = get_rdata("pro_id");
    $page_type = get_rdata("page_type");
    $gst_amount = 0;  
    if ($page_type == 'Sale') 
    {
        $pro_id_arr = explode("##",$pro_id);     
        if (count($pro_id_arr) > 1)
        {
            $pro_id = $pro_id_arr[1];
        }
        // invpro_id,'-',pro_id
        // $pro_id
        // 
    }
    if ((int)$pro_id > 0) 
    {
        $check0_q = "SELECT pro_gst from sm_products WHERE pro_id = $pro_id";
        $check0_r = m_process("get_data", $check0_q);
        if ($check0_r["status"] == 'success' && $check0_r["count"] > 0) {
            if ($check0_r["res"][0]["pro_gst"] !='') {
                $gst_amount = $check0_r["res"][0]["pro_gst"];
            }
        }
    }      
    echo $gst_amount;
}
else if ($action == 'get-student-exam-result') {
    global $tmp_admin_id ;
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "","exs_finalized"=>"");
    $r_stu_id = get_rdata("r_stu_id");
    $r_exs_id = get_rdata("r_exs_id");
    $r_ex_id = get_rdata("r_ex_id");
    $r_stu_br_id = get_rdata("r_stu_br_id");
    $p_id = get_rdata("p_id");
    $exs_result_status_db = '';
    $sc_brt_id = 0;
    $stu_batchtime = '';
    $exs_finalized = '';
    // $check0_q = "SELECT exs_result_status, exs_eca_exc_ids FROM sm_exam_student_entrolled WHERE exs_id = '".$r_exs_id."' AND exs_stu_id = ".$r_stu_id;
    $check0_q = "SELECT exs_finalized, sc_brt_id, stu_batchtime, sc_half_course , exs_result_status, ex_date, exre_total_marks, exre_total_marks_obtain , exc_name , exre_ex_id,exre_exs_id,exre_eca_exc_id,exre_stu_id,exre_br_id FROM
sm_exam_result INNER JOIN sm_exam_categories ON(exre_eca_exc_id = exc_id) INNER JOIN sm_exam ON (ex_id=exre_ex_id) INNER JOIN sm_exam_student_entrolled ON (exs_ex_id = ex_id AND exs_stu_id = $r_stu_id) INNER JOIN sm_student_course ON (sc_stu_id = exs_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id AND sc_stu_id = $r_stu_id) INNER JOIN sm_student ON (sc_stu_id = stu_id)
WHERE exre_ex_id = $r_ex_id AND exre_stu_id = $r_stu_id AND exre_br_id = $r_stu_br_id";

    
    $check0_r = m_process("get_data", $check0_q);
    if ($check0_r["status"] == 'error') {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } else if ($check0_r["count"] == 0) {
        $arr_response["errormsg"] = 'No Data to Process';
    } else {
        $srNo = 0;
        $arr_response["status"] = "success";
        $result_status = "Pass";
        $sc_half_course = 0;
        $total_marks = $total_marks_obtain =  0;
        foreach ($check0_r["res"] as $arr_db) {
            $srNo++;
            $arr_response["exs_finalized"] = $arr_db["exs_finalized"];
            $total_marks += $arr_db["exre_total_marks"];
            $sc_half_course = $arr_db["sc_half_course"];
            $total_marks_obtain += $arr_db["exre_total_marks_obtain"];
            $exs_result_status_db = $arr_db["exs_result_status"];
            $stu_batchtime = $arr_db["stu_batchtime"];
            $sc_brt_id = $arr_db["sc_brt_id"];
            $unique_id = $arr_db["exre_ex_id"] . "_" . $arr_db["exre_exs_id"] . "_" . $arr_db["exre_eca_exc_id"] . "_" . $arr_db["exre_stu_id"] . "_" . $arr_db["exre_br_id"];
            $arr_response["data"] .= '<tr class="">
            <td style="padding-left:10px;">' . $arr_db["exc_name"] . '</td>
            <td style="padding-left:10px;"><span id="exre_total_marks_actual" >' . $arr_db["exre_total_marks"] . '</span></td>
            <td style="padding-left:10px;"><input type="hidden" id="check_entry" name="check_entry[]" value="' . $unique_id . '" /><input type="text" class="mstudentresult" onchange="update_marks_total();" id="exre_total_marks_obtain" name="exre_total_marks_obtain_' . $unique_id . '" value="' . $arr_db["exre_total_marks_obtain"] . '" /></td>
          </tr>';
        }

        if (($total_marks_obtain / $total_marks) * 100 < 70) {
            $result_status = "Fail";
        }
        if ($total_marks_obtain == 0) { $result_status = "AB"; }
        $exs_result_status_display = 'A';
        
        $arr_response["data"] .= '<tr><td colspan="3">';
        $arr_response["data"] .= '<input type="hidden" id="sc_half_course_f" name="sc_half_course_f" value="'.$sc_half_course.'" />';
        if($result_status=="Fail")
        {
            $arr_response["data"] .= 'Result Satus:<span id="lbl_result_status" style="color:red;">'.$result_status.'</span><br /><br />Total Marks: <input style="width:50px;" type="text" readonly id="total_marks" name="total_marks" value="' . $total_marks . '" />';
        }
        else if($result_status=="Pass")
        {
            
            
            $arr_response["data"] .= 'Result Satus:<span id="lbl_result_status" style="color:green;">'.$result_status.'</span><br /><br />Total Marks: <input style="width:50px;" type="text" readonly id="total_marks" name="total_marks" value="' . $total_marks . '" />';
        }
        else
        {
            
            $arr_response["data"] .= 'Result Satus:<span id="lbl_result_status" style="color:black;">'.$result_status.'</span><br /><br />Total Marks: <input style="width:50px;" type="text" readonly id="total_marks" name="total_marks" value="' . $total_marks . '" />';
        }
        // $arr_response["data"] .= '<td>Total: ' . $total_marks . '</td>';
        $arr_response["data"] .= '<br /><br />Obtain Total: <input type="text" readonly id="total_marks_obtain" name="total_marks_obtain" style="width:50px;" value="' . $total_marks_obtain . '" />';
       
        // if ($exs_result_status_db == '')
        // {
            $data_arr_input = array();
            $data_arr_input['select_field'] = 'brt_name ,brt_id';
            $data_arr_input['table'] = 'sm_branch_type';
            $data_arr_input['where'] = " brt_br_id = " . $tmp_admin_id . " AND brt_status  = 'A' ";
            $data_arr_input['key_id'] = 'brt_id';
            $data_arr_input['key_name'] = 'brt_name';
            $data_arr_input['current_selection_value'] = $sc_brt_id;
            $batch_type_selection_box = '';
            $batch_type_selection_box_result = display_dd_options_return($data_arr_input);
            
            if ($batch_type_selection_box_result["status"]=='success')
            {
                $batch_type_selection_box = $batch_type_selection_box_result["data"];
            }
            $batch_type = '</br>Batch Type: <select required id="sc_brt_id_f" name="sc_brt_id_f">'.$batch_type_selection_box.'</select>';
            
            $data_arr_input = array();
            $data_arr_input['select_field'] = 'bt_name ,bt_id';
            $data_arr_input['table'] = 'sm_batch_time';
            $data_arr_input['where'] = " bt_br_id = ".$tmp_admin_id." AND bt_status  = 'A' ";
            $data_arr_input['key_id'] = 'bt_id';
            $data_arr_input['key_name'] = 'bt_name';
            $data_arr_input['current_selection_value'] = $stu_batchtime;
            $data_arr_input['order_by'] = 'bt_id';
            $batch_time_selection_box = '';
            $batch_time_selection_box_result = display_dd_options_return($data_arr_input);
            
            if ($batch_time_selection_box_result["status"]=='success')
            {
                $batch_time_selection_box = $batch_time_selection_box_result["data"];
            }
            $batch_time = 'Batch Time: <select  required id="stu_batchtime_f" name="stu_batchtime_f">'.$batch_time_selection_box.'</select>';
            $payment_terms = 'Payment Terms: <select name="sc_course_type" id="sc_course_type" >
            <option value="Monthly">Monthly</option>
            <option selected="selected" value="Quarterly">Quarterly</option>
            <option value="Half Yearly">Half Yearly</option>
            <option value="Yearly">Yearly</option>
        </select>';
            $arr_response["data"] .= '<br /><br />Next Course & Re examination:<select id="next_course" name="next_course" ><option selected value="Yes">Yes</option><option value="No">No</option></select><br /><br />'.$batch_type.'<br /><br />'.$batch_time.'<br /><br />'.$payment_terms.'&nbsp;&nbsp;</br><b class="text-info" >Note: To enroll in next course select yes, don\'t select twice</b>';
        // }
        // else
        // { 
        //     $arr_response["data"] .= '<input type="hidden" id="next_course" name="next_course" value="No" />'; 
        // }
        
        $arr_response["data"] .= '<input type="text" readonly id="ex_date_d" name="ex_date_d" style="display:none;" value="' . convert_db_to_disp_date($arr_db["ex_date"]) . '" />';
        $arr_response["data"] .= '</td></tr>';
    }
    echo json_encode($arr_response);
}
else if ($action == 'change-student-batch-type-ajax') {

    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $sc_id = get_rdata("sc_id");
    $sc_brt_id = get_rdata("sc_brt_id");
    $sc_stu_id = get_rdata("sc_stu_id");
    $total_fee = get_rdata("total_fee");
    $change_date = get_rdata("change_date");
    $batch_type = "";
    $res_batch_type = get_batch_type($sc_brt_id);
    if ($res_batch_type["errormsg"]=="")
    {
        $batch_type = $res_batch_type["brt_name"];
    }
    $check0_q = "UPDATE sm_student_course SET sc_full_fee_paid = 'N', sc_brt_id = ".$sc_brt_id. ",sc_total_fee = ".$total_fee. " WHERE sc_id=".$sc_id. " AND sc_stu_id = ".$sc_stu_id;

    $check0_r = m_process("update", $check0_q);
    if ($check0_r["status"] == 'error') {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } else {
    $check1_q = "UPDATE sm_student_course SET sc_full_fee_paid = 'Y' WHERE sc_total_fee = sc_total_paid AND  sc_id=".$sc_id;


    $check1_r = m_process("update", $check1_q);
    if ($check1_r["status"] == 'error') {
        $arr_response["errormsg"] = $check1_r['errormsg'];
     } else {
        $arr_response["status"] = "success";
        $arr_log = array();
            $arr_log["log_message"]= "Batch type has been changed to ".$batch_type. " as on ".$change_date;
            $arr_log["log_stu_id"]= $sc_stu_id;
            $arr_log["log_admin_id"]= $tmp_admin_id;
            $arr_log["log_action"]= "batch_type_change";
            $arr_log["log_course_change_date"]=  convert_disp_to_db_date($change_date);
            $res_log = add_log($arr_log);
    }

// end        

    }
    echo json_encode($arr_response);
} 
else if ($action == 'add-student-exam-result-ajax' || $action == 'save-result-only') {
global $tmp_admin_id, $cur_date;
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    // echo "<pre>";
    // print_r($_REQUEST);
    // echo "</pre>";
    
    $process_id = get_rdata("process_id");
    $sc_half_course_f = get_rdata("sc_half_course_f");

    $sc_brt_id_f = get_rdata("sc_brt_id_f");
    $stu_batchtime_f = get_rdata("stu_batchtime_f");

    
    $next_course = get_rdata("next_course");
    $total_marks = get_rdata("total_marks");
    $sc_course_type = get_rdata("sc_course_type");
    
    $ex_date_d = get_rdata("ex_date_d");
    $process_id = trim($process_id,"####");
    $process_id_arr = explode("####", $process_id);
    $total_obtain_marks = $exre_ex_id = $exre_stu_id =  0;

    // adding result for each categories
    for ($i = 0; $i <count($process_id_arr); $i++) {
      $process_id_check_value = str_replace("exre_total_marks_obtain_","",$process_id_arr[$i]);
        $process_id_arr_ids = explode("_", $process_id_check_value);
        $exre_ex_id = $process_id_arr_ids[0];
        $exre_exs_id = $process_id_arr_ids[1];
        $exre_eca_exc_id = $process_id_arr_ids[2];
        $exre_stu_id = $process_id_arr_ids[3];
        $total_obtain_marks += get_rdata($process_id_arr[$i]);
        $check0_q = "UPDATE sm_exam_result SET exre_total_marks_obtain =  " . get_rdata($process_id_arr[$i]) . " WHERE exre_ex_id = $exre_ex_id AND exre_stu_id = $exre_stu_id AND exre_eca_exc_id = $exre_eca_exc_id";
        $check0_r = m_process("update", $check0_q);
        if ($check0_r["status"] == 'error') {
            $arr_response["errormsg"] = $check0_r['errormsg'];
        }
    }

    $exs_result_status = "P";
    $sc_remarks = "";
    if (($total_obtain_marks / $total_marks) * 100 < 70) {
        $exs_result_status = "F";
        $sc_remarks  = " Failed so added one month fee , Exam Date: ".$ex_date_d." </br>";
    }
    if ($total_obtain_marks == 0)
    {
      $sc_remarks  = " Absent so added one month fee , Exam Date: ".$ex_date_d." </br>";
        $exs_result_status = "A";
    }
// $exs_result_status
  // updating result status and total marks to enrollment table.
    $exs_finalized = '';
    if ($action == 'add-student-exam-result-ajax')
    {
        $exs_finalized = 'Y';
    }
    else
    {
        $exs_finalized = get_exs_finalized($exre_ex_id,$exre_stu_id);
    }
  
    $check0_q = "UPDATE sm_exam_student_entrolled SET exs_finalized = '" . $exs_finalized . "', exs_result_status = '" . $exs_result_status . "', exs_result_date = now(), exs_result_marks =  " . $total_obtain_marks . " WHERE exs_ex_id = $exre_ex_id AND exs_stu_id = $exre_stu_id";
    $check0_r = m_process("update", $check0_q);
    if ($check0_r["status"] == 'error') {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    }
    else {
      $arr_log = array();
        $arr_log["log_message"]= "Stuent Exam Result has been added (Ex-$exre_ex_id).";
        $arr_log["log_stu_id"]= $exre_stu_id;
        $arr_log["log_admin_id"]= $tmp_admin_id;
        $arr_log["log_action"]= "add_result_to_exam_student";
        add_log($arr_log);
      $arr_response["status"] = "success";
    }

    
    // if ($action == 'add-student-exam-result-ajax' && $next_course == 'No')
    // {
    //     // need to deactive student and need to add the log for that.
        
    // }
    if ($action == 'add-student-exam-result-ajax')
    {   
    // if need to enroll student in next course then we are allowing it.
    if ( $next_course == "Yes" && (($exs_result_status == "F" && $sc_half_course_f == 0) || $exs_result_status == "A"))
    {
           $errormsg = add_one_month_fee_to_student($exre_stu_id, $sc_remarks);
           if ($errormsg !="") 
           {
               $arr_response["status"] = "failure";
               $arr_response["errormsg"] = $errormsg;
           }
    }
    else if ($next_course == "Yes" && $exs_result_status == "P")
    {
        // finding student current course details
        $check0_q = "SELECT be_sort_order, sc_stu_id, sc_cd_id, sc_br_id, sc_brt_id, sc_co_id, sc_be_id FROM sm_student_course INNER JOIN sm_belt ON (be_id =sc_be_id)  WHERE sc_is_current =1 AND sc_stu_id = $exre_stu_id";
        $check0_r = m_process("get_data", $check0_q);
        if ($check0_r["status"] == 'error') {
            $arr_response["errormsg"] = $check0_r['errormsg'];
            $arr_response["status"] = "failure";
        }
        else if ($check0_r["count"] == 0) {
          $arr_response["errormsg"] = "no current course found";
          $arr_response["status"] = "failure";
        }
        else
        {
          $a_sc_co_id  = $check0_r["res"][0]["sc_co_id"];
          // $a_sc_brt_id = $check0_r["res"][0]["sc_brt_id"];  old code
          $a_sc_brt_id = $sc_brt_id_f;
        
          // adding one SR to the belt
          
          $check0_q = "SELECT be_id, be_sort_order, be_name FROM sm_belt INNER JOIN sm_course_belt ON (cb_be_id = be_id AND cb_co_id = ".$a_sc_co_id." ) WHERE be_sort_order > ".$check0_r["res"][0]["be_sort_order"] . " ORDER BY be_sort_order LIMIT 0,1" ;
          $check0_r = m_process("get_data", $check0_q);
          if ($check0_r["status"] == 'error') {
              $arr_response["errormsg"] = $check0_r['errormsg'];
              $arr_response["status"] = "failure";
          }
          else if ($check0_r["count"] == 0) {
            $arr_response["errormsg"] = "no new belt found found";
          }
          else {
            // now enrolling student to course
            $arr_course_data = array();
            $arr_course_data['sc_total_paid'] = 0;
            $arr_course_data['sc_full_fee_paid'] = 'N';
            $arr_course_data['sc_is_current'] = 1;
            $arr_course_data['sc_half_course'] = 0;
            $arr_course_data['sc_cd_id'] = 0;
            $arr_course_data['sc_be_id'] = $check0_r["res"][0]["be_id"];
            $arr_course_data['sc_co_id'] = $a_sc_co_id;
            $arr_course_data['sc_brt_id'] = $a_sc_brt_id;
            $arr_course_data['sc_br_id'] = $tmp_admin_id;
            $arr_course_data['sc_stu_id'] =  $exre_stu_id;
            $arr_course_data['sc_joined_date'] = convert_disp_to_db_date($ex_date_d);
            $arr_course_data['sc_end_date']=convert_disp_to_db_date($ex_date_d);
            $arr_course_data['sc_create_date'] = $cur_date;
            $arr_course_data['sc_update_date'] = $cur_date;
            $arr_course_data['sc_create_by_id'] = $tmp_admin_id;
            $arr_course_data['sc_update_by_id'] = $tmp_admin_id;
            $arr_course_data['sc_course_type'] = $sc_course_type;
            $add_course_to_student = add_course_to_student($arr_course_data);
            if ($add_course_to_student !='')
            {
                $arr_response["errormsg"] = $add_course_to_student;
            }
            else
            {
                // update student batch type
                $arr_response["errormsg"] = update_student_batch_time($exre_stu_id,$stu_batchtime_f);
            }
          }
        }
    }
    else if (($exs_result_status == "P" OR $exs_result_status == "F" OR $exs_result_status == "A") && $next_course == "No")
    {
        $update_ex_date_d = convert_disp_to_db_date($ex_date_d);
        $update_ex_date_d = date('Y-m-d', strtotime($update_ex_date_d. ' + 1 days'));
        $inactive_employee_q = " UPDATE sm_student SET stu_status = 'I', stu_deactivation_date = '$update_ex_date_d' WHERE stu_id =  $exre_stu_id ";
        $r_inactive_employee= m_process("update",$inactive_employee_q);
         if ($r_inactive_employee["status"] == "failure")
         {
            $arr_response["errormsg"] = $r_inactive_employee["errormsg"];
         }
         else
         {
             $q_log = "INSERT INTO sm_activeinactive(ac_remarkrs, ac_stu_id, ac_date, ac_status, ac_br_id, ac_create_date, ac_create_by_id, ac_update_date, ac_update_by_id) ";
             $q_log .= " SELECT 'inactivated at the time of adding exam result', stu_id,  '$update_ex_date_d' , 'I',stu_br_id, '$cur_date',$tmp_admin_id, '$cur_date',$tmp_admin_id FROM sm_student WHERE stu_id = $exre_stu_id ";
             $r_log= m_process("insert", $q_log);
             if ($r_log["status"] == "failure") {
                 $arr_response["errormsg"] = $r_log["errormsg"];
             }
         }
    }
}
    echo json_encode($arr_response);
} else if ($action == 'validate-belt-details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $be_name = get_rdata("be_name");
    $be_id = get_rdata("be_id");
    $be_br_id = get_rdata("be_br_id");
    if ($be_id == 0) {
        $duplicate_q = "SELECT 1 FROM sm_belt WHERE be_name = '" . $be_name . "'";
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for belt name";
        } else {
            $arr_response["status"] = "success";
        }
    } else {
        $duplicate_q = "SELECT 1 FROM sm_belt WHERE be_name = '" . $be_name . "' AND be_id !=" . $be_id;
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for belt name";
        } else {
            $arr_response["status"] = "success";
        }
    }
    echo json_encode($arr_response);
} else if ($action == 'validate-exam-details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $ex_name = get_rdata("ex_name");
    $ex_date = get_rdata("ex_date");
    $ex_id = get_rdata("ex_id");
    $ex_br_id = get_rdata("ex_br_id");
    if ($ex_id == 0) {
        $duplicate_q = "SELECT 1 FROM sm_exam WHERE ex_name = '" . $ex_name . "' AND ex_date = '" . disptoDB($ex_date) . "' AND ex_br_id = " . $ex_br_id;
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for exam name and exam date";
        } else {
            $arr_response["status"] = "success";
        }
    } else {
        $duplicate_q = "SELECT 1 FROM sm_exam WHERE ex_name = '" . $ex_name . "' AND ex_date = '" . disptoDB($ex_date) . "' AND ex_br_id = " . $ex_br_id . " AND ex_id !=" . $ex_id;
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for exam name and exam date";
        } else {
            $arr_response["status"] = "success";
        }
    }
    echo json_encode($arr_response);
} else if ($action == 'validate-event-details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $ev_name = get_rdata("ev_name");
    $ev_date = get_rdata("ev_date");
    $ev_id = get_rdata("ev_id");
    $ev_br_id = get_rdata("ev_br_id");
    if ($ev_id == 0) {
        $duplicate_q = "SELECT 1 FROM sm_event WHERE ev_name = '" . $ev_name . "' AND ev_date = '" . disptoDB($ev_date) . "' AND ev_br_id = " . $ev_br_id;
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for event name and event date";
        } else {
            $arr_response["status"] = "success";
        }
    } else {
        $duplicate_q = "SELECT 1 FROM sm_event WHERE ev_name = '" . $ev_name . "' AND ev_date = '" . disptoDB($ev_date) . "' AND ev_br_id = " . $ev_br_id . " AND ev_id !=" . $ev_id;
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for event name and event date";
        } else {
            $arr_response["status"] = "success";
        }
    }
    echo json_encode($arr_response);
} else if ($action == 'get_student_batch_type_details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $stu_id = get_rdata("stu_id");
    $q_course_details = "SELECT sc_id, sc_stu_id,sc_br_id,sc_co_id,sc_be_id, sc_brt_id, CONCAT(stu_first_name,' ', stu_last_name) as stu_name , sc_remarks, sc_total_fee,sc_total_paid, sc_is_current, sc_id, brt_name,be_name,co_name, DATE_FORMAT(sc_joined_date,'%d-%m-%Y') as sc_joined_date   FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id ) WHERE sc_stu_id = $stu_id AND sc_is_current = 1 ORDER BY sc_id ASC ";
    $r_course_details = m_process("get_data", $q_course_details);
    if ($r_course_details["status"] == 'success') {
        $arr_response["status"] = "success";
        if ($r_course_details["count"] == 0) {
            $arr_response["message"] = 'No current course found';
        } else {
            $srNo = 0;
            foreach ($r_course_details["res"] as $arr_db) {
                $arr_response["sc_total_fee"] = $arr_db["sc_total_fee"];
                $arr_response["sc_total_paid"] = $arr_db["sc_total_paid"];
                $arr_response["sc_remarks"] = $arr_db["sc_remarks"];
                $arr_response["brt_name"] = $arr_db["brt_name"];
                $arr_response["be_name"] = $arr_db["be_name"];
                $arr_response["co_name"] = $arr_db["co_name"];
                $arr_response["sc_joined_date"] = $arr_db["sc_joined_date"];
                $arr_response["stu_name"] = $arr_db["stu_name"];
                $arr_response["sc_brt_id"] = $arr_db["sc_brt_id"];
                $arr_response["sc_id"] = $arr_db["sc_id"];
             

                $arr_response["sc_stu_id"] = $arr_db["sc_stu_id"];
                $arr_response["sc_br_id"] = $arr_db["sc_br_id"];
                $arr_response["sc_co_id"] = $arr_db["sc_co_id"];
                $arr_response["sc_be_id"] = $arr_db["sc_be_id"];
            }
        }
    } else {
        $arr_response["errormsg"] = $r_course_details["errormsg"];
    }
    echo json_encode($arr_response);
} else if ($action == 'get-student-existing-course-details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $stu_id = get_rdata("stu_id");
    // $q_course_details = "SELECT (SELECT count(*) FROM sm_exam_student_entrolled WHERE (exs_stu_id = stu_id AND exs_co_id = sc_co_id)) as count_entrolled,sc_remarks, sc_total_fee,sc_total_paid, sc_is_current, sc_id, brt_name,be_name,be_name_for, sc_half_course,    co_name, DATE_FORMAT(sc_joined_date,'%d-%m-%Y') as sc_joined_date ,DATE_FORMAT(ex_date,'%d-%m-%Y') as ex_date, sc_course_type  FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id ) LEFT JOIN
    // sm_exam_student_entrolled ON stu_id = exs_stu_id LEFT JOIN
    // sm_exam ON ex_id = exs_ex_id  WHERE sc_stu_id = $stu_id ORDER BY sc_id ASC ";

    $q_course_details="SELECT
    (SELECT COUNT(*) FROM sm_exam_student_entrolled WHERE exs_stu_id = stu_id AND exs_co_id = sc_co_id) as count_entrolled,
    sc_remarks,
    sc_total_fee,
    sc_total_paid,
    sc_is_current,
    sc_id,
    brt_name,
    be_name,
    be_name_for,
    sc_half_course,
    co_name,
    DATE_FORMAT(sc_joined_date, '%d-%m-%Y') as sc_joined_date,
    CASE
        WHEN LEAD(sc_joined_date) OVER (PARTITION BY stu_id ORDER BY sc_joined_date ASC) IS NOT NULL
            THEN DATE_FORMAT(LEAD(sc_joined_date) OVER (PARTITION BY stu_id ORDER BY sc_joined_date ASC), '%d-%m-%Y')
        ELSE NULL
    END AS end_date,
    sc_course_type
   
FROM
    sm_student
INNER JOIN
    sm_student_course ON (sc_stu_id = stu_id)
LEFT JOIN
    sm_belt ON (sc_be_id = be_id)
LEFT JOIN
    sm_branch_type ON (sc_brt_id = brt_id)
LEFT JOIN
    sm_course ON (sc_co_id = co_id)
WHERE
    sc_stu_id = $stu_id
ORDER BY
    sc_id ASC";
   

//     $q_course_details = "SELECT exs_ex_id,sc_remarks, sc_total_fee,sc_total_paid, sc_is_current, sc_id, brt_name,be_name,be_name_for, sc_half_course,    co_name, DATE_FORMAT(sc_joined_date,'%d-%m-%Y') as sc_joined_date , sc_course_type  FROM sm_student 
// INNER JOIN sm_student_course ON (sc_stu_id = stu_id) 
// LEFT JOIN sm_belt ON (sc_be_id = be_id ) 
// LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) 
// LEFT JOIN sm_course ON (sc_co_id = co_id ) 
// LEFT JOIN sm_exam_student_entrolled ON (exs_stu_id = stu_id AND exs_co_id = sc_co_id)
// WHERE sc_stu_id = $stu_id ORDER BY sc_id ASC";
    
    $r_course_details = m_process("get_data", $q_course_details);
    if ($r_course_details["status"] == 'success') {
        $arr_response["status"] = "success";
        if ($r_course_details["count"] == 0) {
            $arr_response["data"] = '<tr><td colspan="8"><center>No details found</center></td></tr>';
        } else {
            $srNo = 0;
            foreach ($r_course_details["res"] as $arr_db) {
                $srNo++;
                $be_name_d = $arr_db["be_name"];
                if ($arr_db["sc_half_course"]==1)
                {
                    $be_name_d = $arr_db["be_name_for"];
                }
                $sc_half_course = ($arr_db["sc_half_course"]==0)?"-Full":"-Half"; 
                $arr_response["data"] .= '<tr class="">
                <td><center>' . $srNo . '</center></td>
              <td style="padding-left:10px;">' . $arr_db["sc_joined_date"] . '</td>
              <td style="padding-left:10px;">' . $arr_db["brt_name"] . '</td>
              <td style="padding-left:10px;">' . $arr_db["co_name"] . '</td>
                <td style="padding-left:10px;">' . $be_name_d.$sc_half_course. '</td>
                <td style="padding-left:10px;">' . $arr_db["end_date"] . '</td>
                <td style="padding-left:10px;">' . ($arr_db["sc_is_current"] == 1 ? "Yes" : "No") . '</td>
                <td style="padding-left:10px;">' . $arr_db["sc_total_fee"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["sc_total_paid"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["sc_remarks"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["sc_course_type"] . '</td>
                <td><center>' . ($arr_db["sc_total_paid"] == 0 || $arr_db["count_entrolled"]==0 ? '<a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" onclick="delete_student_course(' . $arr_db["sc_id"] . ')"></a>' : '') . '</center></td><td></td></tr>';
            }
        }
    } else {
        $arr_response["errormsg"] = $r_course_details["errormsg"];
    }
    echo json_encode($arr_response);
} 
else if ($action == 'get-student-fees-payment-details') 
{
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $sc_id = get_rdata("sc_id");
    $q_payment_details = "SELECT pt_receipt_no, pt_discount_amount, pt_id, pt_sc_id, pt_tran_bank , pt_tran_mode_of_payent, pt_tran_no, pt_tran_amount , pt_tran_date , pt_tran_remarks , pt_sc_id FROM sm_payment_transaction INNER JOIN sm_student_course ON (sc_id = pt_sc_id)  WHERE pt_sc_id = $sc_id AND pt_tran_u_type = 'Course fee' ORDER BY pt_id ASC ";
    $r_payment_details = m_process("get_data", $q_payment_details);
    if ($r_payment_details["status"] == 'success') {
        $arr_response["status"] = "success";
        if ($r_payment_details["count"] == 0) {
            $arr_response["data"] = '<tr><td colspan="8"><center>No details found</center></td></tr>';
        } else {
            $srNo = 0;
            foreach ($r_payment_details["res"] as $arr_db) {
                $srNo++;
                $arr_response["data"] .= '<tr class="">
              <td style="padding-left:10px;">' . $arr_db["pt_receipt_no"] . '</td>
              <td style="padding-left:10px;">' . $arr_db["pt_tran_mode_of_payent"] . '</td>
              <td style="padding-left:10px;">' . $arr_db["pt_tran_bank"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["pt_tran_no"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["pt_tran_amount"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["pt_discount_amount"] . '</td>
                <td style="padding-left:10px;">' . DBtoDisp($arr_db["pt_tran_date"]) . '</td>
                <td style="padding-left:10px;">' . nl2br($arr_db["pt_tran_remarks"]) . '</td>
                <td><center>
                <a href="javascript:void(0);" class="text-success glyphicon glyphicon-pencil" onclick="get_student_fees_payment_details_for_edit(\'studentfee\',' . $arr_db["pt_id"] . ',' . $arr_db["pt_sc_id"] . ')"></a>
                <a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" onclick="delete_student_fee(' . $arr_db["pt_id"] . ',' . $arr_db["pt_sc_id"] . ')"></a>
                <a href="javascript:void(0);" class="text-success fa fa-fw fa-print" onclick="print_fee_receipt(\'Course fee\','. $arr_db["pt_id"] . ',0)"></a>
                    </center></td>
            </tr>';
            }
        }
    } else {
        $arr_response["errormsg"] = $r_payment_details["errormsg"];
    }
    echo json_encode($arr_response);
}
else if ($action == 'get-event-fees-payment-details') 
{
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $evs_id = get_rdata("evs_id");
    $evs_stu_or_other = get_rdata("evs_stu_or_other");
    
    $q_payment_details = "SELECT pt_tran_u_type, pt_receipt_no, pt_discount_amount, pt_id, pt_sc_id, pt_tran_bank , pt_tran_mode_of_payent, pt_tran_no, pt_tran_amount , pt_tran_date , pt_tran_remarks , pt_sc_id FROM sm_payment_transaction INNER JOIN sm_event_student_entrolled ON (evs_id = pt_sc_id)  WHERE pt_sc_id = $evs_id AND pt_tran_u_type = 'Event fee[".$evs_stu_or_other."]' ORDER BY pt_id ASC";
    $r_payment_details = m_process("get_data", $q_payment_details);
    if ($r_payment_details["status"] == 'success') {
        $arr_response["status"] = "success";
        if ($r_payment_details["count"] == 0) {
            $arr_response["data"] = '<tr><td colspan="9"><center>No details found</center></td></tr>';
        } else {
            $srNo = 0;
            foreach ($r_payment_details["res"] as $arr_db) {
                $srNo++;
                $arr_response["data"] .= '<tr class="">
              <td style="padding-left:10px;">' . $arr_db["pt_receipt_no"] . '</td>
              <td style="padding-left:10px;">' . $arr_db["pt_tran_mode_of_payent"] . '</td>
              <td style="padding-left:10px;">' . $arr_db["pt_tran_bank"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["pt_tran_no"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["pt_tran_amount"] . '</td>
                <td style="padding-left:10px;">' . $arr_db["pt_discount_amount"] . '</td>
                <td style="padding-left:10px;">' . DBtoDisp($arr_db["pt_tran_date"]) . '</td>
                <td style="padding-left:10px;">' . nl2br($arr_db["pt_tran_remarks"]) . '</td>
                <td><center>
                <a href="javascript:void(0);" class="text-success glyphicon glyphicon-pencil" onclick="get_event_fees_payment_details_for_edit(\'' . $arr_db["pt_tran_u_type"] . '\',' . $arr_db["pt_id"].','. $arr_db["pt_sc_id"] . ')"></a>
                <a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" onclick="delete_event_fee(' . $arr_db["pt_id"] . ',' . $arr_db["pt_sc_id"] .',\'' . $arr_db["pt_tran_u_type"] . '\',' . ')"></a>
                <a href="javascript:void(0);" class="text-success fa fa-fw fa-print" onclick="print_fee_receipt(\'' . $arr_db["pt_tran_u_type"] . '\','. $arr_db["pt_id"] . ','. $arr_db["pt_sc_id"] .')"></a>
                    </center></td>
            </tr>';
            }
        }
    } else {
        $arr_response["errormsg"] = $r_payment_details["errormsg"];
    }
    echo json_encode($arr_response);
}
else if ($action == 'get-student-fees-payment-details-for-edit') 
{
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $pt_id = get_rdata("pt_id");
    $pt_sc_id = get_rdata("pt_sc_id");
    $type = get_rdata("type");
    $q_payment_details = "SELECT pt_id,pt_sc_id,pt_tran_bank,pt_tran_mode_of_payent,pt_tran_amount,pt_tran_no,pt_tran_remarks, DATE_FORMAT(pt_tran_date,'%d-%m-%Y') as pt_tran_date , pt_discount_amount, pt_ac_id FROM sm_payment_transaction WHERE pt_sc_id = $pt_sc_id AND pt_id = $pt_id AND pt_tran_u_type = 'Course fee'";
    $r_payment_details = m_process("get_data", $q_payment_details);
    if ($r_payment_details["status"] == 'success') {
        $arr_response["status"] = "success";
        if ($r_payment_details["count"] == 0) {
            $arr_response["data"] = 'Payment details not found';
        } else {
            $srNo = 0;
            $arr_response["data"] = $r_payment_details["res"][0];
        }
    } else {
        $arr_response["errormsg"] = $r_payment_details["errormsg"];
    }
    echo json_encode($arr_response);
}
else if ($action == 'get-event-fees-payment-details-for-edit') 
{
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $pt_id = get_rdata("pt_id");
    $pt_sc_id = get_rdata("pt_sc_id");
    $type = get_rdata("type");
    $q_payment_details = "SELECT pt_id,pt_sc_id,pt_tran_bank,pt_tran_mode_of_payent,pt_tran_amount,pt_tran_no,pt_tran_remarks, DATE_FORMAT(pt_tran_date,'%d-%m-%Y') as pt_tran_date , pt_discount_amount , pt_ac_id FROM sm_payment_transaction WHERE pt_sc_id = $pt_sc_id AND pt_id = $pt_id AND pt_tran_u_type = '".$type."'";
    $r_payment_details = m_process("get_data", $q_payment_details);
    if ($r_payment_details["status"] == 'success') {
        $arr_response["status"] = "success";
        if ($r_payment_details["count"] == 0) {
            $arr_response["data"] = 'Payment details not found';
        } else {
            $srNo = 0;
            $arr_response["data"] = $r_payment_details["res"][0];
        }
    } else {
        $arr_response["errormsg"] = $r_payment_details["errormsg"];
    }
    echo json_encode($arr_response);
}
else if ($action == 'remove-student-existing-course-details') 
{
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $sc_id = get_rdata("sc_id");
    $q_course_details = "DELETE FROM sm_student_course WHERE  sc_id = $sc_id AND sc_br_id = $tmp_admin_id  ";
    $r_course_details = m_process("delete", $q_course_details);
    // echo "<pre>";
    //  print_r($r_course_details);
    if ($r_course_details["status"] == 'success') {
        $arr_response["status"] = "success";
        $arr_response["data"] = "Course has been deleted successfully.";
        $arr_log["log_message"]= "Stuent Removal From Course.";
        $arr_log["log_stu_id"]= 0;
        $arr_log["log_admin_id"]= $tmp_admin_id;
        $arr_log["log_action"]= "student_course_removal";
        add_log($arr_log);
    } else {
        $arr_response["errormsg"] = $r_course_details["errormsg"];
    }
    echo json_encode($arr_response);
} else if ($action == 'remove-student-existing-fees-details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $pt_id = get_rdata("pt_id");
    $pt_sc_id = get_rdata("pt_sc_id");
    $q_fee_details = "DELETE FROM sm_payment_transaction WHERE pt_id = $pt_id AND pt_sc_id = $pt_sc_id  ";
    $r_fee_details = m_process("delete", $q_fee_details);
    // echo "<pre>";
    //  print_r($r_course_details);
    if ($r_fee_details["status"] == 'success') {
        $resp_fee = update_fee_student_to_course($pt_sc_id);
        if ($resp_fee == '') {
            $arr_response["status"] = "success";
            $arr_response["data"] = "Fees has been deleted successfully";
        }
    } else {
        $arr_response["errormsg"] = $r_fee_details["errormsg"];
    }
    echo json_encode($arr_response);
} else if ($action == 'remove-event-existing-fees-details') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $pt_id = get_rdata("pt_id");
    $pt_sc_id = get_rdata("pt_sc_id");
    $q_fee_details = "DELETE FROM sm_payment_transaction WHERE pt_id = $pt_id AND pt_sc_id = $pt_sc_id  ";
    $r_fee_details = m_process("delete", $q_fee_details);
    if ($r_fee_details["status"] == 'success') 
    {
        // $q_update = "UPDATE sm_event_student_entrolled ese  INNER JOIN (SELECT evs_id, SUM(pt_discount_amount) as evs_discount_amount , SUM(pt_tran_amount) as evs_total_paid FROM sm_event_student_entrolled INNER JOIN sm_payment_transaction ON (evs_id=pt_sc_id) WHERE evs_id = " . $pt_sc_id ." AND pt_tran_u_type='".get_rdata("pt_tran_u_type")."' GROUP BY pt_sc_id, pt_tran_u_type) as M 
        // ON (ese.evs_id = M.evs_id)
        // SET ese.evs_discount_amount = M.evs_discount_amount , ese.evs_total_paid = M.evs_total_paid 
        // WHERE ese.evs_id  = " . $pt_sc_id ." AND  M.evs_id =  " . $pt_sc_id ;

        $s_query = "SELECT evs_id,SUM(pt_discount_amount) as evs_discount_amount , SUM(pt_tran_amount) as evs_total_paid FROM sm_event_student_entrolled INNER JOIN sm_payment_transaction ON (evs_id=pt_sc_id) WHERE evs_id='".$pt_sc_id."' AND pt_tran_u_type='Event fee[student]' GROUP BY pt_sc_id, pt_tran_u_type";    
        $select_result  = m_process("get_data", $s_query);        
        $evs_total_paid  =  !empty($select_result["res"][0]["evs_total_paid"]) ? $select_result["res"][0]["evs_total_paid"]  : 0;        
        $evs_discount_amount  =  !empty($select_result["res"][0]["evs_discount_amount"]) ? $select_result["res"][0]["evs_discount_amount"]  : 0;
        $q_update = "UPDATE sm_event_student_entrolled SET evs_discount_amount = '".$evs_discount_amount."', evs_total_paid = '".$evs_total_paid."' WHERE evs_id  = " . $pt_sc_id;
        $udpate_result = m_process("update", $q_update);
        if ($udpate_result["status"] == "failure") 
        {
            $arr_response["errormsg"] = $udpate_result2["errormsg"];
        } else {
            
            $query =  "UPDATE sm_event_student_entrolled SET evs_paid = 0 WHERE evs_id = " . $pt_sc_id;
            m_process("update", $query);
            $q_update1 = "UPDATE sm_event_student_entrolled SET evs_paid = 1 WHERE evs_fee = (evs_total_paid+evs_discount_amount) AND evs_id = " . $pt_sc_id;

            $udpate_result1 = m_process("update", $q_update1);
            
            if ($udpate_result1["status"] == "failure") 
            {
                $arr_response["errormsg"] = $udpate_result1["errormsg"];
            } else
            {
                $arr_response["status"] = "success";
                $arr_response["data"] = "Fees has been deleted  successfully";
            }
        }

        // if ($resp_fee == '') {
        //     $arr_response["status"] = "success";
        //     $arr_response["data"] = "Fees has been deleted successfully";
        // }
    } else {
        $arr_response["errormsg"] = $r_fee_details["errormsg"];
    }
    echo json_encode($arr_response);
}
else if ($action == 'pay_fee_student_ajax') {
    $pt_id = get_rdata("pt_id");
    $arr_response = array("status" => "failure", "errormsg" => "");
    // start of code
    // inserting the fee 
    if ($pt_id == 0  || $pt_id == "")
    {
        $arr_fees_data = array();
        $arr_fees_data["pt_tran_no"] = get_rdata("pt_tran_no");
        $arr_fees_data["pt_ac_id"] = get_rdata("pt_ac_id");
        $arr_fees_data["pt_discount_amount"] = get_rdata("pt_discount_amount");
        if ($arr_fees_data["pt_discount_amount"] == '')
            $arr_fees_data["pt_discount_amount"] = 0;
        $arr_fees_data["pt_tran_remarks"] = escape(get_rdata("pt_tran_remarks"));
        $arr_fees_data["pt_tran_mode_of_payent"] = get_rdata("pt_tran_mode_of_payent");
        $arr_fees_data["pt_tran_date"] = disptoDB(get_rdata("pt_tran_date"));
        $arr_fees_data["pt_tran_bank"] = get_rdata("pt_tran_bank");
        $arr_fees_data["pt_tran_amount"] = get_rdata("pt_tran_amount");
        if ($arr_fees_data["pt_tran_amount"] == '')
        $arr_fees_data["pt_tran_amount"] = 0;
        $arr_fees_data["pt_receipt_no"] = get_rdata("pt_receipt_no");
        $arr_fees_data["stu_id"] = get_rdata("d_stu_id");
        $arr_fees_data["pt_br_id"] = $tmp_admin_id;
        $arr_fees_data["pt_stu_id"] = get_rdata("d_stu_id");
        $arr_fees_data["sc_id"] = get_rdata("d_sc_id");
        $arr_fees_data["sc_co_id"] = get_rdata("d_sc_co_id");
        $arr_fees_data["sc_brt_id"] = get_rdata("d_sc_brt_id");
        $arr_fees_data["sc_br_id"] = get_rdata("d_sc_br_id");
        $arr_fees_data["sc_be_id"] = get_rdata("d_sc_be_id");
        $arr_fees_data["pt_tran_u_type"] = "Course fee";
        $arr_fees_data["pt_type"] = "Credit";

        if (( (int) $arr_fees_data["pt_discount_amount"] + (int) $arr_fees_data["pt_tran_amount"]) == 0) 
        {
            $arr_response["errormsg"] = "Invalid request";
        } 
        else 
        {

            $pay_student_fees = pay_fee_student($arr_fees_data);

            if ($pay_student_fees != '') {
                $arr_response["errormsg"] = $pay_student_fees;
            } else {
                $arr_response["status"] = "success";
            }

           
        }
    //    echo json_encode($arr_response);
    }
    else
    {
        $pt_discount_amount = get_rdata("pt_discount_amount");
        if ($pt_discount_amount == '')  $pt_discount_amount = 0;

        $pt_tran_amount = get_rdata("pt_tran_amount");
        if ($pt_tran_amount == '')  $pt_tran_amount = 0;

        $q_update_0 = "UPDATE sm_payment_transaction SET ";
        $q_update_0 .= " pt_tran_remarks = '".escape(get_rdata("pt_tran_remarks"))."', ";
        $q_update_0 .= " pt_tran_mode_of_payent = '".escape(get_rdata("pt_tran_mode_of_payent"))."', ";
        $q_update_0 .= " pt_tran_date = '".disptoDB(get_rdata("pt_tran_date"))."', ";
        $q_update_0 .= " pt_tran_bank = '".escape(get_rdata("pt_tran_bank"))."', ";
        $q_update_0 .= " pt_tran_amount = '".$pt_tran_amount."', ";
        $q_update_0 .= " pt_discount_amount = '".$pt_discount_amount."', ";
        $q_update_0 .= " pt_ac_id = '".get_rdata("pt_ac_id")."', ";
        $q_update_0 .= " pt_tran_no = '".escape(get_rdata("pt_tran_no"))."' ";
        $q_update_0 .= " WHERE pt_id = ". $pt_id ;
        
        $udpate_result_0 = m_process("update", $q_update_0);
        if ($udpate_result_0["status"] == "failure") {
            $arr_response["errormsg"] = $udpate_result_0["errormsg"];
        } else {
            // $q_update = "UPDATE sm_student_course INNER JOIN sm_payment_transaction ON (pt_sc_id = sc_id AND pt_tran_u_type = 'Course fee' ) SET sc_total_paid = SUM(pt_tran_amount) WHERE sc_id = ".get_rdata("d_sc_id");
            $q_update = "UPDATE sm_student_course sc 
            INNER JOIN (SELECT SUM(pt_tran_amount) as pt_tran_amount, SUM(pt_discount_amount) as pt_discount_amount , sc_id
            FROM sm_student_course INNER JOIN sm_payment_transaction ON (pt_sc_id = sc_id AND pt_tran_u_type = 'Course fee' ) 
            WHERE sc_id = ".get_rdata("d_sc_id")." GROUP BY pt_sc_id ) AS M ON (M.sc_id = sc.sc_id)
            SET sc_total_paid = M.pt_tran_amount , sc_discount_amount = M.pt_discount_amount
            WHERE sc.sc_id = ".get_rdata("d_sc_id");
            $udpate_result = m_process("update", $q_update);
            if ($udpate_result["status"] == "failure") {
                $arr_response["errormsg"] = $udpate_result["errormsg"];
            } else {
                $q_update0 = "UPDATE sm_student_course SET sc_full_fee_paid = 'N' WHERE sc_id = " . get_rdata("d_sc_id");
                $udpate_result0 = m_process("update", $q_update0);
                if ($udpate_result0["status"] == "failure") {
                    $arr_response["errormsg"] = $udpate_result0["errormsg"];
                } 
                else
                {
                    $q_update1 = "UPDATE sm_student_course SET sc_full_fee_paid = 'Y' WHERE sc_total_fee = (sc_total_paid + sc_discount_amount) AND sc_id = " . get_rdata("d_sc_id");
                    $udpate_result1 = m_process("update", $q_update1);
                    if ($udpate_result1["status"] == "failure") 
                    {
                        $arr_response["errormsg"] = $udpate_result1["errormsg"];
                    } 
                    else
                    {
                        $arr_response["status"] = "success";
                    }
                }

                
            }
        }
        
        ///
    }
    // end of code
    echo json_encode($arr_response);
} else if ($action == 'add_course_to_student_ajax') {
   // console.log($a_sc_end_date);

    $arr_response = array("status" => "failure", "errormsg" => "");

    // start of code
    $arr_course_data = array();
    $a_sc_be_id = get_rdata("a_sc_be_id");
    $a_sc_co_id = get_rdata("a_sc_co_id");
    $a_sc_brt_id = get_rdata("a_sc_brt_id");
    $a_sc_half_course = get_rdata("a_sc_half_course");
    $a_stu_id = get_rdata("a_stu_id");
    $a_sc_joined_date = get_rdata("a_sc_joined_date");
    $a_sc_end_date=get_rdata("a_sc_end_date");
    $sc_course_type = get_rdata("sc_course_type");

    if ((int) $a_sc_be_id == 0 || (int) $a_sc_co_id == 0 || (int) $a_sc_brt_id == 0 || (int) $a_stu_id == 0 || $a_sc_joined_date == '') {
        $arr_response["errormsg"] = "Invalid request";
    } else {
        $arr_course_data['sc_total_paid'] = 0;
        $arr_course_data['sc_full_fee_paid'] = 'N';
        $arr_course_data['sc_is_current'] = 1;
        $arr_course_data['sc_cd_id'] = 0;
        $arr_course_data['sc_be_id'] = $a_sc_be_id;
        $arr_course_data['sc_co_id'] = $a_sc_co_id;
        $arr_course_data['sc_brt_id'] = $a_sc_brt_id;
        $arr_course_data['sc_br_id'] = $tmp_admin_id;
        $arr_course_data['sc_course_type'] = $sc_course_type;
        $arr_course_data['sc_stu_id'] = $a_stu_id;
        $arr_course_data['sc_joined_date'] = disptoDB($a_sc_joined_date);
        $arr_course_data['sc_end_date']=disptoDB($a_sc_end_date);
        $arr_course_data['sc_create_date'] = $cur_date;
        $arr_course_data['sc_update_date'] = $cur_date;
        $arr_course_data['sc_create_by_id'] = $tmp_admin_id;
        $arr_course_data['sc_update_by_id'] = $tmp_admin_id;
        $arr_course_data['sc_half_course'] = $a_sc_half_course;

        $add_course_to_student = add_course_to_student($arr_course_data);

        if ($add_course_to_student != '') {
            $arr_response["errormsg"] = $add_course_to_student;
        } else {
            $arr_response["status"] = "success";
        }
        echo json_encode($arr_response);
    }

    // end of code
} else if ($action == 'pay_fee_student_exam_ajax') {

    $arr_response = array("status" => "failure", "errormsg" => "");
    // start of code
    $arr_fees_data = array();
    $arr_fees_data["pt_tran_no"] = checkEmpty(get_rdata("pt_tran_no"));
    $arr_fees_data["pt_ac_id"] = get_rdata("pt_ac_id",0);
    $arr_fees_data["pt_tran_remarks"] = escape(checkEmpty(get_rdata("pt_tran_remarks")));
    $arr_fees_data["pt_tran_mode_of_payent"] = checkEmpty(get_rdata("pt_tran_mode_of_payent"));
    $arr_fees_data["pt_tran_date"] = disptoDB(checkEmpty(get_rdata("pt_tran_date")));
    $arr_fees_data["pt_tran_bank"] = checkEmpty(get_rdata("pt_tran_bank"));
    $arr_fees_data["pt_tran_amount"] = checkEmpty(get_rdata("pt_tran_amount"));
    if ($arr_fees_data["pt_tran_amount"] == '') $arr_fees_data["pt_tran_amount"] = 0;
    $arr_fees_data["pt_receipt_no"] = checkEmpty(get_rdata("pt_receipt_no"));
    $arr_fees_data["stu_id"] = checkEmpty(get_rdata("d_stu_id"));
    $arr_fees_data["pt_br_id"] = $tmp_admin_id;
    $arr_fees_data["pt_stu_id"] = checkEmpty(get_rdata("d_stu_id"));
    $arr_fees_data["sc_id"] = checkEmpty(get_rdata("d_exs_id"));
    $arr_fees_data["exs_id"] = checkEmpty(get_rdata("d_exs_id"));
    $arr_fees_data["stu_br_id"] = checkEmpty(get_rdata("d_stu_br_id"));
    $arr_fees_data["pt_tran_u_type"] = "Exam fee";
    $arr_fees_data["pt_type"] = "Credit";
    //echo "<pre>";
   // print_r($arr_fees_data);
    //die;
    if ((int) $arr_fees_data["pt_tran_amount"] == 0) {
        $arr_response["errormsg"] = "Invalid request";
    } else {

        $pay_student_fees = pay_fee_student_exam($arr_fees_data);

        if ($pay_student_fees != '') {
            $arr_response["errormsg"] = $pay_student_fees;
        } else {
            $arr_response["status"] = "success";
        }

        echo json_encode($arr_response);
    }

    // end of code
} else if ($action == 'pay_fee_student_event_ajax') {
    $pt_id = get_rdata("pt_id");
    $arr_response = array("status" => "failure", "errormsg" => "");
    // start of code
    if ($pt_id == 0  || $pt_id == "") {
        $arr_fees_data = array();
        $arr_fees_data["pt_tran_no"] = get_rdata("pt_tran_no");
        $arr_fees_data["pt_ac_id"] = get_rdata("pt_ac_id");
        $arr_fees_data["pt_tran_remarks"] = escape(get_rdata("pt_tran_remarks"));
        $arr_fees_data["pt_tran_mode_of_payent"] = get_rdata("pt_tran_mode_of_payent");
        $arr_fees_data["pt_tran_date"] = disptoDB(get_rdata("pt_tran_date"));
        $arr_fees_data["pt_tran_bank"] = get_rdata("pt_tran_bank");
        $arr_fees_data["pt_tran_amount"] = get_rdata("pt_tran_amount");
        if ($arr_fees_data["pt_tran_amount"] =='') $arr_fees_data["pt_tran_amount"] =0;
        $arr_fees_data["pt_receipt_no"] = get_rdata("pt_receipt_no");
        $arr_fees_data["pt_discount_amount"] = get_rdata("pt_discount_amount");
        if ($arr_fees_data["pt_discount_amount"] == '')  $arr_fees_data["pt_discount_amount"] = 0;
        $arr_fees_data["stu_id"] = get_rdata("d_stu_id");
        $arr_fees_data["d_evs_stu_or_other"] = get_rdata("d_evs_stu_or_other");
        $arr_fees_data["pt_br_id"] = $tmp_admin_id;
        $arr_fees_data["pt_stu_id"] = get_rdata("d_stu_id");
        $arr_fees_data["sc_id"] = get_rdata("d_evs_id");
        $arr_fees_data["evs_id"] = get_rdata("d_evs_id");
        $arr_fees_data["stu_br_id"] = get_rdata("d_stu_br_id");
        $arr_fees_data["pt_tran_u_type"] = "Event fee[".$arr_fees_data["d_evs_stu_or_other"]."]";
        $arr_fees_data["pt_type"] = "Credit";

        
            $pay_student_fees = pay_fee_student_event($arr_fees_data);

            if ($pay_student_fees != '') {
                $arr_response["errormsg"] = $pay_student_fees;
            } else {
                $arr_response["status"] = "success";
                $arr_response["data"] = "Fees has been paid successfully";
            }
        
        
        echo json_encode($arr_response);
    }
    else
    {

        $pt_discount_amount = get_rdata("pt_discount_amount");
        if ($pt_discount_amount == '')  $pt_discount_amount = 0;

        $pt_tran_amount = get_rdata("pt_tran_amount");
        if ($pt_tran_amount == '')  $pt_tran_amount = 0;


        $q_update_0 = "UPDATE sm_payment_transaction SET ";
        $q_update_0 .= " pt_tran_remarks = '".escape(get_rdata("pt_tran_remarks"))."', ";
        $q_update_0 .= " pt_tran_mode_of_payent = '".escape(get_rdata("pt_tran_mode_of_payent"))."', ";
        $q_update_0 .= " pt_tran_date = '".disptoDB(get_rdata("pt_tran_date"))."', ";
        $q_update_0 .= " pt_tran_bank = '".escape(get_rdata("pt_tran_bank"))."', ";
        $q_update_0 .= " pt_tran_amount = '".$pt_tran_amount."', ";
        $q_update_0 .= " pt_discount_amount = '".$pt_discount_amount."', ";
        if(!empty(get_rdata("pt_tran_no"))) {
            $q_update_0 .= " pt_tran_no = '".escape(get_rdata("pt_tran_no"))."'";
        }
        $q_update_0 .= " pt_ac_id = '".get_rdata("pt_ac_id")."' ";
        $q_update_0 .= " WHERE pt_id = ". $pt_id ;
           
        $udpate_result_0 = m_process("update", $q_update_0);
        if ($udpate_result_0["status"] == "failure") 
        {
            $arr_response["errormsg"] = $udpate_result_0["errormsg"];
        } else 
        {
            $q_update = "UPDATE sm_event_student_entrolled ese  INNER JOIN (SELECT evs_id, SUM(pt_discount_amount) as evs_discount_amount , SUM(pt_tran_amount) as evs_total_paid FROM sm_event_student_entrolled INNER JOIN sm_payment_transaction ON (evs_id=pt_sc_id) WHERE evs_id = " . get_rdata("d_evs_id") ." AND pt_tran_u_type='Event fee[".get_rdata("d_evs_stu_or_other")."]' GROUP BY pt_sc_id, pt_tran_u_type) as M 
        ON (ese.evs_id = M.evs_id)
        SET ese.evs_discount_amount = M.evs_discount_amount , ese.evs_total_paid = M.evs_total_paid 
        WHERE ese.evs_id  = " . get_rdata("d_evs_id") ." AND  M.evs_id =  " . get_rdata("d_evs_id") ;
    
        $udpate_result = m_process("update", $q_update);
        if ($udpate_result["status"] == "failure") 
        {
            $arr_response["errormsg"] = $udpate_result["errormsg"];
        } else 
        {
            $q_update1 = "UPDATE sm_event_student_entrolled SET evs_paid = 1 WHERE evs_fee = (evs_total_paid+evs_discount_amount) AND evs_id = " . get_rdata("d_evs_id");
            $udpate_result1 = m_process("update", $q_update1);
            if ($udpate_result1["status"] == "failure") 
            {
                $arr_response["errormsg"] = $udpate_result1["errormsg"];
            } else
            {
                $arr_response["status"] = "success";
                $arr_response["data"] = "Fees has been paid successfully";
            }
        }
        }
        
        echo json_encode($arr_response);
    }
    // end of code

} else if ($action == 'get_course_wise_belts_ajax') {

    $a_sc_co_id = get_rdata("a_sc_co_id");
    $data_arr_input = array();
    $data_arr_input['select_field'] = ' CONCAT(be.be_name," [Full] - ",be.be_name_for," [Half]") as be_select  ,be.be_id ';
    $data_arr_input['table'] = 'sm_belt be INNER JOIN sm_course_belt cb ON (be.be_id = cb.cb_be_id) ';
    $data_arr_input['where'] = " cb.cb_co_id = ".$a_sc_co_id." AND be.be_status  = 'A' ";
    $data_arr_input['key_id'] = 'be_id';
    $data_arr_input['key_name'] = 'be_select';
    $data_arr_input['order_by'] = 'be.be_sort_order';
    $data_arr_input['current_selection_value'] = 0;
    $data_arr_input['first_root'] = '<option value="0">--Please select--</option>';


    $arr_response =  display_dd_options_return($data_arr_input);
    echo json_encode($arr_response);
} else if ($action == 'add_product_ajax') {
    global $tmp_admin_id;
    $arr_response = array("status" => "failure", "errormsg" => "");
    
    $pro_cat_id = get_rdata("pro_cat_id");
    $pro_name = get_rdata("pro_name");
    $pro_model = get_rdata("pro_model");
    $pro_manu_id = get_rdata("pro_manu_id");
    $pro_gst = get_rdata("pro_gst");

    $arr_check_delete = validate_before_delete("sm_products", "pro_admin_id = ".$tmp_admin_id." AND pro_name = '" . $pro_name . "'");
            if ($arr_check_delete["error_message"] != '') {
                $arr_response["errormsg"] = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $arr_response["errormsg"] = "Name is already exists";
            }
            else
            {
                $product_q = "INSERT INTO sm_products (pro_cat_id,pro_name,pro_model,pro_manu_id,pro_admin_id,pro_gst,pro_status) VALUES (";
                $product_q .= "$pro_cat_id,'".$pro_name."','".$pro_model."','".$pro_manu_id."','".$tmp_admin_id."','".$pro_gst."','1')";
                
                $r_product = m_process("insert", $product_q);
                // echo "<pre>";
                //  print_r($r_course_details);
                if ($r_product["status"] == 'success') {
                    $arr_response["status"] = "success";
                    $arr_response["data"] = "Product has been added successfully";
                    $dd_products_option = '<option value="0">-Please select-</option>';
                    $data_arr_input = array();
                    $data_arr_input['select_field'] = ' pro_name ,pro_id ';
                    $data_arr_input['table'] = 'sm_products';
                    $data_arr_input['where'] = " pro_admin_id = " . $tmp_admin_id . " AND pro_status  = 1 ";
                    $data_arr_input['key_id'] = 'pro_id';
                    $data_arr_input['key_name'] = 'pro_name';
                    $data_arr_input['current_selection_value'] = 0;
                    $data_arr_input['order_by'] = 'pro_name';
                    $dd_products_option_i = display_dd_options_return($data_arr_input);
                    $dd_products_option .= $dd_products_option_i["data"];
                    $arr_response["data_product"] = $dd_products_option;
                }
                else
                $arr_response["errormsg"] = $r_product["errormsg"];
            }
    echo json_encode($arr_response);
} else if ($action == 'add_product_option_ajax') {
    global $tmp_admin_id;
    $arr_response = array("status" => "failure", "errormsg" => "","data_product_size" => "", "data_product_color" => "");
        
    
    $po_name = get_rdata("po_name");
    $po_type = get_rdata("po_type");
 
    $arr_check_delete = validate_before_delete("sm_product_option", "po_br_id  = ".$tmp_admin_id." AND po_name = '" . $po_name . "'");
            if ($arr_check_delete["error_message"] != '') {
                $arr_response["errormsg"] = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $arr_response["errormsg"] = "Option Name is already exists";
            }
            else
            {
                $product_q = "INSERT INTO sm_product_option (po_name,po_type,po_br_id,po_status) VALUES ('";
                $product_q .= $po_name."','".$po_type."','".$tmp_admin_id."','A')";
                
                $r_product = m_process("insert", $product_q);
                // echo "<pre>";
                //  print_r($r_course_details);
                if ($r_product["status"] == 'success') {
                    $arr_response["status"] = "success";
                    $arr_response["data"] = "Product Option has been added successfully";
                    $dd_products_option_size = '<option value="0">-Please select-</option>';
                    $data_arr_input = array();
                    $data_arr_input['select_field'] = ' po_name ,po_id ';
                    $data_arr_input['table'] = 'sm_product_option';
                    $data_arr_input['where'] = " po_br_id = " . $tmp_admin_id . " AND po_status  = 'A' AND po_type  = 'Size' ";
                    $data_arr_input['key_id'] = 'po_id';
                    $data_arr_input['key_name'] = 'po_name';
                    $data_arr_input['current_selection_value'] = 0;
                    $data_arr_input['order_by'] = 'po_name';
                    $dd_products_option_i = display_dd_options_return($data_arr_input);
                    $dd_products_option_size .= $dd_products_option_i["data"];
                    $arr_response["data_product_size"] = $dd_products_option_size;

                    $dd_products_option_color = '<option value="0">-Please select-</option>';
                    $data_arr_input = array();
                    $data_arr_input['select_field'] = ' po_name ,po_id ';
                    $data_arr_input['table'] = 'sm_product_option';
                    $data_arr_input['where'] = " po_br_id = " . $tmp_admin_id . " AND po_status  = 'A' AND po_type  = 'Color' ";
                    $data_arr_input['key_id'] = 'po_id';
                    $data_arr_input['key_name'] = 'po_name';
                    $data_arr_input['current_selection_value'] = 0;
                    $data_arr_input['order_by'] = 'po_name';
                    $dd_products_option_i = display_dd_options_return($data_arr_input);
                    $dd_products_option_color .= $dd_products_option_i["data"];
                    $arr_response["data_product_color"] = $dd_products_option_color;
                }
                else
                $arr_response["errormsg"] = $r_product["errormsg"];
            }
    echo json_encode($arr_response);
} else if ($action == 'get_gst_amount_ajax') {
    global $tmp_admin_id;
    $arr_response = array("status" => "failure", "errormsg" => "");
    
 
    $po_name = get_rdata("po_name");
} else if ($action == 'event_enroll_unenroll_student_ajax') {
    global $tmp_admin_id;
    $enrolled_student_v = get_rdata("process_id","");
    // if action is checked then following code
    // else removal code to process
        $enrolled_student_v_arr =  explode("_",$enrolled_student_v); 
        $f_stu_id =  $enrolled_student_v_arr[0];
        $f_stu_status =  $enrolled_student_v_arr[1];
        $res_entrolled = enroll_student_event($ev_id, $f_stu_id,$f_stu_status);
// remove_enroll_student_event($none_removal_ids, $ev_id,$stu_or_other,$sigle_only=false,$evs_stu_id=0) 
    $arr_response = array("status" => "failure", "errormsg" => "");
    $po_name = get_rdata("po_name");
}
else if ($action == 'return_reissue_book_ajax') 
{
    global $tmp_admin_id, $cur_date;
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");

    $book_return_re_issue = get_rdata("book_return_re_issue");
    $bi_issue_date = get_rdata("bi_issue_date");
    $bi_issue_date_valid = get_rdata("bi_issue_date_valid");
    $book_id_ajax = get_rdata("book_id_ajax");
    $book_issue_stu_id_ajax = get_rdata("book_issue_stu_id_ajax");
    $check0_q = "UPDATE sm_book_issue_history SET bi_update_date = '".$cur_date."' , bi_update_by_id = '".$tmp_admin_id."' ";
    if ($book_return_re_issue == "Return")
    {
        $check0_q .= ",bi_return_date = '".convert_disp_to_db_date($bi_issue_date)."', bi_status = 'Returned' ";
    }
    else if ($book_return_re_issue == "Re-Issue")
    {
        $check0_q .= ",bi_issue_date_valid = '".convert_disp_to_db_date($bi_issue_date_valid)."', bi_status = 'Re-Issued' ";
    }
    
    $check0_q .= " WHERE bi_br_id = ".$tmp_admin_id." AND bi_stu_id=".$book_issue_stu_id_ajax ." AND bi_book_id=".$book_id_ajax ;
    

    

        $check0_r = m_process("update", $check0_q);
        if ($check0_r["status"] == 'error') 
        {
            $arr_response["errormsg"] = $check0_r['errormsg'];
        } else 
        {
            if ($book_return_re_issue == "Return")
            {
                $book_q = " UPDATE sm_book SET book_issue_date = NULL, book_issue_stu_id = 0 ";
                $book_q .= " WHERE book_id = ".$book_id_ajax." AND book_issue_stu_id = ".$book_issue_stu_id_ajax;
                $check1_r = m_process("update", $book_q);
                if ($check1_r["status"] == 'error') {
                    $arr_response["errormsg"] = $check1_r['errormsg'];
                } else {
                    $arr_response["status"] = "success";
                    $arr_response["data"] = "Book ".$book_return_re_issue." has been successfully";
                }
            }
            else
            {
                $arr_response["status"] = "success";
                $arr_response["data"] = "Book ".$book_return_re_issue." has been successfully";
            }
    }
    echo json_encode($arr_response);
} 
// else if ($action == 'return_product_qty_ajax') 
// {
//     global $tmp_admin_id, $cur_date;
//     $arr_response = array("row_id"=>"","status" => "failure", "errormsg" => "","id" => "", "data" => "");
    
//     $purchase_sale_type = get_rdata("purchase_sale_type");
//     $invpro_id = get_rdata("model_invpro_id");
//     $model_purchase_qty = get_rdata("model_purchase_qty");
//     $model_sold_qty = get_rdata("model_sold_qty");
//     $model_return_qty = get_rdata("model_return_qty");
//     $model_row_id  = get_rdata("model_row_id"); 
//     $arr_response["row_id"]=$model_row_id;
//     $arr_response["id"]= $invpro_id;
//     $table_invoice = "";
//     $table_invoice_products = "";

//     if ($purchase_sale_type == 'Purchase')
//     {
//         $table_invoice = "sm_invoice";
//         $table_invoice_products = "sm_invoice_products";
//     }
//     else
//     {
//         $table_invoice = "sm_invoice_sale";
//         $table_invoice_products = "sm_invoice_products_sale";
//     }

    
//     $check0_q = "SELECT invpro_inv_id, invpro_pro_qty, invpro_pro_qty_sold,invpro_pro_id,  pro_name, invpro_pro_qty_dead, invpro_pro_qty_return , po1.po_name option_1, po2.po_name option_2 FROM ".$table_invoice_products." INNER JOIN $table_invoice  ON (inv_id=invpro_inv_id ) INNER JOIN sm_product_option po1  ON (po1.po_id=invpro_po_id )  INNER JOIN sm_product_option po2  ON (po2.po_id=invpro_po_id_2 )  INNER JOIN sm_products ON (pro_id = invpro_pro_id ) WHERE  invpro_id = '".$invpro_id."'";
//     $check0_r = m_process("get_data", $check0_q);
//     if ($check0_r["status"] == 'error') 
//     {
//         $arr_response["errormsg"] = $check0_r['errormsg'];
//     } 
//     else if ($check0_r["count"] > 0) 
//     {
//         $invpro_pro_qty = $check0_r["res"][0]["invpro_pro_qty"];
//         $invpro_pro_qty_sold = $check0_r["res"][0]["invpro_pro_qty_sold"];
        
//         if ($model_return_qty > ($invpro_pro_qty - $invpro_pro_qty_sold))
//         {
//             $arr_response["errormsg"] = "Invalid Qty To Process";
//         }
//         else
//         {
//             $log_message = " Product: ". $check0_r["res"][0]["pro_name"] .", Option 1: ". $check0_r["res"][0]["option_1"] .", Option 2: ". $check0_r["res"][0]["option_2"] . " has been return Qty:".$model_return_qty;
//             $arr_log = array();
//             $arr_log["log_message"]= $log_message;
//             $arr_log["log_stu_id"]= $check0_r["res"][0]["invpro_inv_id"];
//             $arr_log["log_admin_id"]= $tmp_admin_id;
//             $arr_log["log_course_change_date"]= $cur_date;
//             $arr_log["log_action"]= "return_product_qty_".$purchase_sale_type;
//             add_log($arr_log);
//             $arr_response["status"] = "success";
//         }
//     }
//     echo json_encode($arr_response);
// } 
else if ($action == 'validate-sale-qty-zero') 
{
    // {"action":"validate-sale-qty-zero","sale_inv_id":sale_inv_id,"name_value":name_value,"qty":qty},

    // invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $invpro_inv_id = get_rdata("sale_inv_id");
    $name_value = get_rdata("name_value");
    $name_value_arr = explode("##",$name_value);
    $invpro_id = $name_value_arr[0];
    $qty = get_rdata("qty");
    if ($be_id == 0) {
        $duplicate_q = "SELECT invpro_pro_qty FROM sm_invoice_products_sale WHERE invpro_id = '" . $invpro_id . "' AND invpro_inv_id = $invpro_inv_id ";
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($qty > $duplicate_r["invpro_pro_qty"]) {
            $arr_response["errormsg"] = "Maximum allowed qty(s) are ".$duplicate_r["invpro_pro_qty"];
        } else {
            $arr_response["status"] = "success";
        }
    } else {
        $duplicate_q = "SELECT 1 FROM sm_belt WHERE be_name = '" . $be_name . "' AND be_id !=" . $be_id;
        $duplicate_r = m_process("get_data", $duplicate_q);
        if ($duplicate_r["status"] == 'error') {
            $arr_response["errormsg"] = $duplicate_r['error_message'];
        } else if ($duplicate_r["count"] > 0) {
            $arr_response["errormsg"] = "Duplicate entry for belt name";
        } else {
            $arr_response["status"] = "success";
        }
    }
    echo json_encode($arr_response);
} 
else if ($action == 'product_qty_manager_ajax') 
{   

    global $tmp_admin_id, $cur_date;
    $arr_response = array("row_id"=>"","status" => "failure", "errormsg" => "","id" => "", "data" => "");

    $action_type = get_rdata("action_type"); // dead qty
    $pqm_invpro_pro_id = get_rdata("pqm_invpro_pro_id");
    $pqm_invpro_po_id = get_rdata("pqm_invpro_po_id");
    $pqm_invpro_po_id_2 = get_rdata("pqm_invpro_po_id_2");
    $pqm_invpro_qty = get_rdata("pqm_invpro_qty");

    
    $table_invoice = "sm_invoice";
    $table_invoice_products = "sm_invoice_products";

    
    $check0_q = "SELECT invpro_id, invpro_inv_id, invpro_pro_qty, invpro_pro_qty_sold,invpro_pro_qty_dead,invpro_pro_id,  pro_name, invpro_pro_qty_dead, invpro_pro_qty_return , po1.po_name option_1, po2.po_name option_2 FROM ".$table_invoice_products." INNER JOIN $table_invoice  ON (inv_id=invpro_inv_id ) INNER JOIN sm_product_option po1  ON (po1.po_id=invpro_po_id )  INNER JOIN sm_product_option po2  ON (po2.po_id=invpro_po_id_2 )  INNER JOIN sm_products ON (pro_id = invpro_pro_id ) WHERE ";
    $check0_q .= " invpro_pro_id = '".$pqm_invpro_pro_id."' ";
    $check0_q .= " AND invpro_po_id = '".$pqm_invpro_po_id."' ";
    $check0_q .= " AND invpro_po_id_2 = '".$pqm_invpro_po_id_2."' ";
    $check0_q .= " AND invpro_pro_qty > (invpro_pro_qty_sold+invpro_pro_qty_dead)  ORDER BY invpro_pro_qty";
    $process_done = false; 
   
    $check0_r = m_process("get_data", $check0_q);

    $record_updated = false;
    if ($check0_r["status"] == 'failure') 
    {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } 
    else if ($check0_r["count"] > 0) 
    {
        
        foreach($check0_r["res"] as $check0_db_row)
        {

            if ($process_done == true) 
            {
                continue;
            }
            $current_qty =  $check0_db_row["invpro_pro_qty"]-$check0_db_row["invpro_pro_qty_sold"]-$check0_db_row["invpro_pro_qty_dead"];

            if ($pqm_invpro_qty <= $current_qty )
            {    
                $invoice_product_q = "UPDATE $table_invoice_products SET invpro_pro_qty_sold = (invpro_pro_qty_sold+".$pqm_invpro_qty.") WHERE ";
                $invoice_product_q .= "invpro_id = ".$check0_db_row["invpro_id"];

                $invoice_product_r = m_process("update", $invoice_product_q);
                if ($invoice_product_r["status"] == 'failure') 
                {
                    $arr_response["errormsg"] = $invoice_product_r['errormsg'];
                }
                else 
                {
                    $invoice_q = "UPDATE sm_products SET pro_qty_sold = (pro_qty_sold+".$pqm_invpro_qty.") WHERE pro_id=".$pqm_invpro_pro_id;
                    
                    $invoice_r = m_process("update", $invoice_q);
                    if ($invoice_r["status"] == 'failure') 
                    {
                        $arr_response["errormsg"] = $invoice_r['errormsg'];
                    }
                }
                $process_done = true; 
                $log_message = " Product: ". $check0_db_row["pro_name"] .", Option 1: ". $check0_db_row["option_1"] .", Option 2: ". $check0_db_row["option_2"] . " marked as dead sotock :".$pqm_invpro_qty. "##".$pqm_invpro_pro_id."-".$pqm_invpro_po_id.'-'.$pqm_invpro_po_id_2;
                $arr_log = array();
                $arr_log["log_message"]= $log_message;
                $arr_log["log_stu_id"]= $check0_db_row["invpro_inv_id"];
                $arr_log["log_admin_id"]= $tmp_admin_id;
                $arr_log["log_course_change_date"]= $cur_date;
                $arr_log["log_action"]= "dead_product_qty";
                add_log($arr_log);
                $arr_response["status"]="success";
                m_process("update","UPDATE sm_exam_student_entrolled SET exs_certificate ='Y',exs_belt='Y' WHERE exs_id =".$_REQUEST['exs_id']);

                continue;
            }
            else
            {
                $invoice_product_q = "UPDATE $table_invoice_products SET invpro_pro_qty_sold = (invpro_pro_qty_sold+".$current_qty.") WHERE ";
                $invoice_product_q .= "invpro_id = ".$check0_db_row["invpro_id"];
                $invoice_product_r = m_process("update", $invoice_product_q);
                if ($invoice_product_r["status"] == 'failure') 
                {
                    $arr_response["errormsg"] = $invoice_product_r['errormsg'];
                }
                else 
                {
                    $invoice_q = "UPDATE sm_products SET pro_qty_sold = (pro_qty_sold+".$current_qty.") WHERE pro_id=".$pqm_invpro_pro_id;
                    
                    $invoice_r = m_process("update", $invoice_q);

                    if ($invoice_r["status"] == 'failure') 
                    {
                        $arr_response["errormsg"] = $invoice_r['errormsg'];
                    }
                    else
                    {
                        m_process("update","UPDATE sm_exam_student_entrolled SET exs_certificate ='Y',exs_belt='Y' WHERE exs_id =".$_REQUEST['exs_id']);
                        $log_message = " Product: ". $check0_db_row["pro_name"] .", Option 1: ". $check0_db_row["option_1"] .", Option 2: ". $check0_db_row["option_2"] . " marked as dead sotock :".$current_qty. "##".$pqm_invpro_pro_id."-".$pqm_invpro_po_id.'-'.$pqm_invpro_po_id_2;
                        $arr_log = array();
                        $arr_log["log_message"]= $log_message;
                        $arr_log["log_stu_id"]= $check0_db_row["invpro_inv_id"];
                        $arr_log["log_admin_id"]= $tmp_admin_id;
                        $arr_log["log_course_change_date"]= $cur_date;
                        $arr_log["log_action"]= "dead_product_qty";
                        add_log($arr_log);
                        $pqm_invpro_qty = $pqm_invpro_qty - $current_qty;

                    }
                }
                continue;
            }
        }
        if ($process_done == false)
        {
            $arr_response["errormsg"] = $pqm_invpro_qty ." quantity(s) are pending to process, please check the stock and process only $pqm_invpro_qty quantity, rest are processed successfully.";
        }
    }
    else
    {
        // case where count is zero
        $arr_response["errormsg"] = "No qty found.";
    }
    
    echo json_encode($arr_response);
}
else if ($action == 'check_product_qty_manager_ajax') 
{
    global $tmp_admin_id, $cur_date;
    $arr_response = array("row_id"=>"","status" => "failure", "errormsg" => "","id" => "", "data" => "");

    $action_type = get_rdata("action_type"); // dead qty
    $pqm_invpro_pro_id = get_rdata("pqm_invpro_pro_id");
    $pqm_invpro_po_id = get_rdata("pqm_invpro_po_id");
    $pqm_invpro_po_id_2 = get_rdata("pqm_invpro_po_id_2");
    $pqm_invpro_qty = get_rdata("pqm_invpro_qty");

    
    $table_invoice = "sm_invoice";
    $table_invoice_products = "sm_invoice_products";

    
    $check0_q = "SELECT invpro_id, invpro_inv_id, SUM(invpro_pro_qty) as invpro_pro_qty, SUM(invpro_pro_qty_sold) as invpro_pro_qty_sold,SUM(invpro_pro_qty_dead) as invpro_pro_qty_dead,invpro_pro_id,  pro_name,  invpro_pro_qty_return , po1.po_name option_1, po2.po_name option_2 FROM ".$table_invoice_products." INNER JOIN $table_invoice  ON (inv_id=invpro_inv_id ) INNER JOIN sm_product_option po1  ON (po1.po_id=invpro_po_id )  INNER JOIN sm_product_option po2  ON (po2.po_id=invpro_po_id_2 )  INNER JOIN sm_products ON (pro_id = invpro_pro_id ) WHERE ";
    $check0_q .= " invpro_pro_id = '".$pqm_invpro_pro_id."' ";
    $check0_q .= " AND invpro_po_id = '".$pqm_invpro_po_id."' ";
    $check0_q .= " AND invpro_po_id_2 = '".$pqm_invpro_po_id_2."' ";
    $check0_q .= " AND invpro_pro_qty > (invpro_pro_qty_sold+invpro_pro_qty_dead)  ORDER BY invpro_pro_qty";
    $process_done = false; 
    $check0_r = m_process("get_data", $check0_q);
    $record_updated = false;
    if ($check0_r["status"] == 'failure') 
    {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } 
    else
    {
        $arr_response["data"] = $check0_r["invpro_pro_qty"]-$check0_r["invpro_pro_qty_sold"]-$check0_r["invpro_pro_qty_dead"] ;
    }
    
    echo json_encode($arr_response);
}
else if ($action == 'remove_student_notification') 
{
    global $tmp_admin_id, $cur_date;
    $arr_response = array("row_id"=>"","status" => "failure", "errormsg" => "","id" => "", "data" => "");

    $stu_id = get_rdata("stu_id"); // dead qty
        
    $check0_q = "UPDATE sm_student SET stu_remove_from_list = 'Y' WHERE stu_id = '".$stu_id."' ";
    
    $check0_r = m_process("update", $check0_q);
    if ($check0_r["status"] == 'failure') 
    {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } 
    else
    {
        $arr_response["status"] = 'success';
    }
    
    echo json_encode($arr_response);
}  else if ($action == 'get_student_detail_ajax') {
    $stu_id = get_rdata("d_stu_id");
    $sm_student = new student();
    $sm_student->data["*"] = "";
    $sm_student->action = 'get';
    $sm_student->process_id = $stu_id;
    $result = $sm_student->process();
    
    $response = array();
    if ($result['status'] == 'success') {
        if ($result['count'] > 0) {
            $response = ['status' => 'success', 'data' => $result['res'][0]];
        } else {
            $response = ['status' => 'error', 'data' => null];
        }
    } else {
        $response = ['status' => 'error', 'data' => null];
    }
    echo json_encode($response);
} else if ($action == 'add_size_ajax') {
    global $tmp_admin_id;
    $arr_response = array("status" => "failure", "errormsg" => "");
    
    $po_name = get_rdata("size_name");
    $po_status = get_rdata('po_status','A');
    $po_type = get_rdata('po_type','Size');
    $po_br_id = get_rdata('po_br_id',$tmp_admin_id);
    $po_create_date = $cur_date;
    $po_create_by_id = $tmp_admin_id ;
    $po_update_date =$cur_date;
    $po_update_by_id = $tmp_admin_id;

    $not_value = " AND po_br_id = ".$tmp_admin_id ;
    $arr_duplicate_name = found_duplicate('sm_product_option', 'po_name', $po_name,$not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for size ';
    }
    
    if ($errormsg == '') {
        $size_q = "INSERT INTO sm_product_option (po_name,po_status,po_type,po_br_id,po_create_date,po_create_by_id,po_update_date,po_update_by_id) VALUES (";
        $size_q .= "'".$po_name."','".$po_status."','".$po_type."','".$po_br_id."','".$po_create_date."','".$po_create_by_id."','".$po_update_date."', '".$po_update_by_id."')";
        
        
        $r_size = m_process("insert", $size_q);
        // echo "<pre>";
        //  print_r($r_course_details);
        if ($r_size["status"] == 'success') {
            $arr_response["status"] = "success";
            $arr_response["data"] = "Size has been added successfully";
            $dd_products_option = '<option value="0">-Please select-</option>';
            $data_arr_input = array();
            $data_arr_input['select_field'] = ' po_name ,po_id ';
            $data_arr_input['table'] = 'sm_product_option';
            $data_arr_input['where'] = " po_status = 'A' AND po_type IN('Size','Both')  AND po_br_id = ".$tmp_admin_id;
            $data_arr_input['key_id'] = 'po_id';
            $data_arr_input['key_name'] = 'po_name';
            $data_arr_input['current_selection_value'] = 0;
            $data_arr_input['order_by'] = 'po_name';
            $dd_products_option_i = display_dd_options_return($data_arr_input);
            $dd_products_option .= $dd_products_option_i["data"];
            $arr_response["data_size"] = $dd_products_option;
        }
        else
        $arr_response["errormsg"] = $r_size["errormsg"];
    }
    echo json_encode($arr_response);
} else if ($action == 'add_color_ajax') {
    global $tmp_admin_id;
    $arr_response = array("status" => "failure", "errormsg" => "");
    
    $po_name = get_rdata("color_name");
    $po_status = get_rdata('po_status','A');
    $po_type = get_rdata('po_type','Color');
    $po_br_id = get_rdata('po_br_id',$tmp_admin_id);
    $po_create_date = $cur_date;
    $po_create_by_id = $tmp_admin_id ;
    $po_update_date =$cur_date;
    $po_update_by_id = $tmp_admin_id;

    $not_value = " AND po_br_id = ".$tmp_admin_id ;
    $arr_duplicate_name = found_duplicate('sm_product_option', 'po_name', $po_name,$not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for color ';
    }
    
    if ($errormsg == '') {
        $color_q = "INSERT INTO sm_product_option (po_name,po_status,po_type,po_br_id,po_create_date,po_create_by_id,po_update_date,po_update_by_id) VALUES (";
        $color_q .= "'".$po_name."','".$po_status."','".$po_type."','".$po_br_id."','".$po_create_date."','".$po_create_by_id."','".$po_update_date."', '".$po_update_by_id."')";

        $r_color = m_process("insert", $color_q);
        // echo "<pre>";
        //  print_r($r_course_details);
        if ($r_color["status"] == 'success') {
            $arr_response["status"] = "success";
            $arr_response["data"] = "Color has been added successfully";
            $dd_products_option = '<option value="0">-Please select-</option>';
            $data_arr_input = array();
            $data_arr_input['select_field'] = ' po_name ,po_id ';
            $data_arr_input['table'] = 'sm_product_option';
            $data_arr_input['where'] = " po_status = 'A' AND po_type IN('Color','Both')  AND po_br_id = ".$tmp_admin_id;
            $data_arr_input['key_id'] = 'po_id';
            $data_arr_input['key_name'] = 'po_name';
            $data_arr_input['current_selection_value'] = 0;
            $data_arr_input['order_by'] = 'po_name';
            $dd_products_option_i = display_dd_options_return($data_arr_input);
            $dd_products_option .= $dd_products_option_i["data"];
            $arr_response["data_color"] = $dd_products_option;
        }
        else
        $arr_response["errormsg"] = $r_color["errormsg"];
    }
    echo json_encode($arr_response);
} else if ($action == 'get_return_product_detail_ajax') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $invpro_id = get_rdata("invpro_id");
    $pType = get_rdata("pType");

    if($pType == 'P') {
        $check0_q = "SELECT ip.invpro_id, ip.invpro_pro_qty, ip.invpro_pro_qty_sold, ip.invpro_pro_qty_return, ip.invpro_pro_qty_dead, IFNULL(SUM(r.proret_return_pro_qty), 0) proret_return_pro_qty FROM
        sm_invoice_products ip LEFT JOIN sm_invoice_products_return r ON (r.proret_invpro_id = ip.invpro_id ) 
        WHERE invpro_id = $invpro_id GROUP BY r.proret_invpro_id";
    } else {
        $check0_q = "SELECT ip.invpro_id, ip.invpro_pro_qty, ip.invpro_pro_qty_sold, ip.invpro_pro_qty_return, ip.invpro_pro_qty_dead, IFNULL(SUM(r.proret_return_pro_qty), 0) proret_return_pro_qty FROM
        sm_invoice_products_sale ip LEFT JOIN sm_invoice_products_return r ON (r.proret_invpro_id = ip.invpro_id ) 
        WHERE invpro_id = $invpro_id GROUP BY r.proret_invpro_id";
    }
    $check0_r = m_process("get_data", $check0_q);
    
    if ($check0_r["status"] == 'error') {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } else if ($check0_r["count"] == 0) {
        $arr_response["errormsg"] = 'No Data to Process';
    } else {
        $arr_response["status"] = "success";
        $arr_response["data"] = $check0_r['res'][0];
    }
    echo json_encode($arr_response);
} else if ($action == 'return_product_qty_ajax') 
{
    global $tmp_admin_id, $cur_date;
    $arr_response = array("row_id"=>"","status" => "failure", "errormsg" => "","id" => "", "data" => "");

    $purchase_sale_type = get_rdata("purchase_sale_type");
    $invpro_id = get_rdata("model_invpro_id");
    $model_purchase_qty = get_rdata("model_purchase_qty");
    $model_available_qty = get_rdata("model_available_qty");
    $model_sold_qty = get_rdata("model_sold_qty");
    $model_return_qty = get_rdata("model_return_qty");
    $arr_response["id"]= $invpro_id;
  
    $table_invoice = "";
    $table_invoice_products = "";

    if ($purchase_sale_type == 'Purchase')
    {
        $table_invoice = "sm_invoice";
        $table_invoice_products = "sm_invoice_products ip";
        $proret_pro_type = "P";
    }
    else
    {
        $table_invoice = "sm_invoice_sale";
        $table_invoice_products = "sm_invoice_products_sale ip";
        $proret_pro_type = "S";
    }

    
    $check0_q = "SELECT invpro_id, invpro_inv_id, invpro_pro_qty, invpro_pro_qty_sold,invpro_pro_id, invpro_po_id, invpro_po_id_2,invpro_used,invpro_final_pro_price,  pro_name, invpro_pro_qty_dead, invpro_pro_qty_return , po1.po_name option_1, po2.po_name option_2, SUM(r.proret_return_pro_qty) proret_return_pro_qty FROM ".$table_invoice_products." INNER JOIN $table_invoice  ON (inv_id=invpro_inv_id ) INNER JOIN sm_product_option po1  ON (po1.po_id=invpro_po_id )  INNER JOIN sm_product_option po2  ON (po2.po_id=invpro_po_id_2 )  INNER JOIN sm_products ON (pro_id = invpro_pro_id ) LEFT JOIN sm_invoice_products_return r ON (r.proret_invpro_id = ip.invpro_id ) WHERE  invpro_id = '".$invpro_id."'";
    $check0_r = m_process("get_data", $check0_q);

    if ($check0_r["status"] == 'error') 
    {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } 
    else if ($check0_r["count"] > 0) 
    {
        $invpro_pro_qty = $check0_r["res"][0]["invpro_pro_qty"];
        $invpro_pro_qty_sold = $check0_r["res"][0]["invpro_pro_qty_sold"];
        $proret_return_pro_qty = $check0_r["res"][0]["proret_return_pro_qty"];
        
        if ($model_return_qty > ($invpro_pro_qty - $invpro_pro_qty_sold - $proret_return_pro_qty))
        {
            $arr_response["errormsg"] = "Invalid Qty To Process";
        }
        else
        {
            // insert:: sm_invoice_products_return - for return product log
            $proret_invpro_id = $check0_r["res"][0]["invpro_id"];
            $proret_inv_id = $check0_r["res"][0]["invpro_inv_id"];
            $proret_pro_id = $check0_r["res"][0]["invpro_pro_id"];
            $proret_po_id = $check0_r["res"][0]["invpro_po_id"];
            $proret_po_id_2 = $check0_r["res"][0]["invpro_po_id_2"];
            $proret_used = $check0_r["res"][0]["invpro_used"];
            $proret_final_pro_price = $check0_r["res"][0]["invpro_final_pro_price"];
            $proret_isreturn = "true";
            $proret_create_date = $cur_date;
            $proret_create_by_id = $tmp_admin_id ;
            $proret_update_date =$cur_date;
            $proret_update_by_id = $tmp_admin_id;

            $return_q = "INSERT INTO sm_invoice_products_return (proret_invpro_id,proret_inv_id,proret_pro_id,proret_po_id,proret_po_id_2,proret_used,proret_return_pro_qty,proret_final_pro_price,proret_isreturn,proret_pro_type,proret_create_date,proret_create_by_id,proret_update_date,proret_update_by_id) VALUES (";
            $return_q .= "'".$proret_invpro_id."','".$proret_inv_id."','".$proret_pro_id."','".$proret_po_id."','".$proret_po_id_2."','".$proret_used."','".$model_return_qty."', '".$proret_final_pro_price."', '".$proret_isreturn."', '".$proret_pro_type."', '".$proret_create_date."', '".$proret_create_by_id."', '".$proret_update_date."', '".$proret_update_by_id."')";
            $r_return = m_process("insert", $return_q);

            if ($r_return["status"] == 'success') {
                $log_message = " Product: ". $check0_r["res"][0]["pro_name"] .", Option 1: ". $check0_r["res"][0]["option_1"] .", Option 2: ". $check0_r["res"][0]["option_2"] . " has been return Qty:".$model_return_qty;
                $arr_log = array();
                $arr_log["log_message"]= $log_message;
                $arr_log["log_stu_id"]= $check0_r["res"][0]["invpro_inv_id"];
                $arr_log["log_admin_id"]= $tmp_admin_id;
                $arr_log["log_course_change_date"]= $cur_date;
                $arr_log["log_action"]= "return_product_qty_".$purchase_sale_type;
                add_log($arr_log);
                $arr_response["status"] = "success";
            } else {
                $arr_response["errormsg"] = $r_return["errormsg"];
            }            
        }
    }
    echo json_encode($arr_response);
} else if ($action == 'add_event_attendance') {
    $arr_response = array("status" => "failure", "errormsg" => "", "data" => "");
    $sea_stud_id = get_rdata("attStudId");
    $sea_att_date = date("Y-m-d",get_rdata("attTimestamp"));
    $attStatus = get_rdata("attStatus");
    if($attStatus == 'P') {
        $sea_att_status = 1;
    } else if($attStatus == 'A') {
        $sea_att_status = 2;
    } else {
        $sea_att_status = 0;
    }
    $sea_ev_id = get_rdata("attEvId");

    // get is attendance exist or not
    $duplicate_q = "SELECT * FROM sm_event_attendance WHERE sea_stud_id = '" . $sea_stud_id . "' AND sea_att_date = '" .$sea_att_date . "' AND sea_ev_id = '" . $sea_ev_id . "'";
    $duplicate_r = m_process("get_data", $duplicate_q);
    if(count($duplicate_r['res']) > 0) {
        // update 
        $sea_id = $duplicate_r['res'][0]['sea_id'];
        $q_update_0 = "UPDATE sm_event_attendance SET ";
        $q_update_0 .= " sea_att_status = ".$sea_att_status.", ";
        $q_update_0 .= " sea_update_date = '".$cur_date."', ";
        $q_update_0 .= " sea_update_by_id = '".$tmp_admin_id."'";
        $q_update_0 .= " WHERE sea_id = ". $sea_id ;
        $udpate_result_0 = m_process("update", $q_update_0);
        if ($udpate_result_0["status"] == "failure") {
            $arr_response["errormsg"] = $udpate_result_0["errormsg"];
        } else {
            $arr_response["status"] = "success";
            $arr_response["data"] = "Attendance taken successfully";
        }
    } else {
        // insert
        $event_att_q = "INSERT INTO sm_event_attendance (sea_stud_id,sea_att_date,sea_att_status,sea_ev_id,sea_create_date,sea_create_by_id,sea_update_date,sea_update_by_id) VALUES (";
        $event_att_q .= "$sea_stud_id,'".$sea_att_date."','".$sea_att_status."','".$sea_ev_id."','".$cur_date."','".$tmp_admin_id."','".$cur_date."','".$tmp_admin_id."')";

        $r_event_att = m_process("insert", $event_att_q);
        if ($r_event_att["status"] == 'success') {
            $arr_response["status"] = "success";
            $arr_response["data"] = "Attendance taken successfully";
        } else {
            $arr_response["errormsg"] = $r_event_att["errormsg"];
        }
    }
    echo json_encode($arr_response);
} else if ($action == 'get_product_option_ajax') {
    $status = get_rdata("status");
    $po_type = get_rdata("po_type");
    $po_used_type = get_rdata("po_used_type");

    $q = "SELECT  po_id, po_name  FROM sm_product_option WHERE 1 ";
    if ($status != '') {
        $q .= " AND po_status = '" . $status . "'";
    }
    if ($po_type != '') 
    {
        $q .= " AND po_type  = '" . $po_type."' ";
    } 
    if ($po_used_type != '') 
    {
        $q .= " AND po_used_type  IN (" . $po_used_type.") ";
    } 
    die($q);
    $result = m_process("get_data", $q);
    echo json_encode(convert_db_array_to_php_array($result["res"], "po_id", "po_name"));
}else if ($action == 'get_product_option_ajax_new') {
    $status = get_rdata("status");
    $po_type = get_rdata("po_type");
    $po_used_type = get_rdata("po_used_type");
    $product_name = get_rdata("product_name");
    $pqm_invpro_pro_id = get_rdata("pqm_invpro_pro_id");
    if($po_type=="Size")  {
        $q = "SELECT * FROM sm_product_option WHERE po_id IN(SELECT invpro_po_id  FROM `sm_invoice_products` WHERE `invpro_pro_id` = $pqm_invpro_pro_id)";
    }
    else {
        $q = "SELECT * FROM sm_product_option WHERE po_id IN(SELECT invpro_po_id_2  FROM `sm_invoice_products` WHERE `invpro_pro_id` = $pqm_invpro_pro_id)";
    }
    $result = m_process("get_data", $q);
    echo json_encode(convert_db_array_to_php_array($result["res"] ?? [], "po_id", "po_name"));
} else {
    echo 'E###0###Invalid Request';
}


