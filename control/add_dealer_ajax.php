<?php
include("includes/application_top.php");
include("../includes/class/dealer.php");
$id = get_rdata("id", 0);
$act = get_rdata("act");
$del_id = get_rdata('del_id');
$del_first_name = get_rdata('del_first_name');
$del_company_name = get_rdata('del_company_name');
$del_last_name = get_rdata('del_last_name');
$del_phone = get_rdata('del_phone');
$del_office_address = get_rdata('del_office_address');
$del_city = get_rdata('del_city');
$del_state_id = get_rdata('del_state_id', 0);
$del_postal_code = get_rdata('del_postal_code');
$del_gstno = get_rdata('del_gstno');
$del_panno = get_rdata('del_panno');
$del_email = get_rdata('del_email');
$del_phone_2 = get_rdata('del_phone_2');
$del_status = get_rdata('del_status', 'A');
$del_igst = get_rdata('del_igst', 'N');
$del_create_date = $cur_date;
$del_create_by_id = $tmp_admin_id;
$del_update_date = $cur_date;
$del_update_by_id = $tmp_admin_id;
$lo_lastlogin_date = $cur_date;
$del_br_id = $tmp_admin_id;

if ($del_company_name != '') {
    $not_value = " AND del_br_id = " . $tmp_admin_id;
    $arr_duplicate_name = found_duplicate('sm_dealer', 'del_company_name', escape($del_company_name), $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for dealer name';
    }
}

if ($errormsg == '') {
    $sm_dealer = new dealer();
    $sm_dealer->data["del_first_name"] = $del_first_name;
    $sm_dealer->data["del_company_name"] = escape($del_company_name);
    $sm_dealer->data["del_last_name"] = $del_last_name;
    $sm_dealer->data["del_phone"] = $del_phone;
    $sm_dealer->data["del_office_address"] = escape($del_office_address);
    $sm_dealer->data["del_city"] = $del_city;
    $sm_dealer->data["del_state_id"] = $del_state_id;
    $sm_dealer->data["del_postal_code"] = $del_postal_code;
    $sm_dealer->data["del_gstno"] = $del_gstno;
    $sm_dealer->data["del_panno"] = $del_panno;
    $sm_dealer->data["del_email"] = $del_email;
    $sm_dealer->data["del_phone_2"] = $del_phone_2;
    $sm_dealer->data["del_status"] = $del_status;
    $sm_dealer->data["del_igst"] = $del_igst;
    $sm_dealer->data["del_create_date"] = $del_create_date;
    $sm_dealer->data["del_create_by_id"] = $del_create_by_id;
    $sm_dealer->data["del_update_date"] = $del_update_date;
    $sm_dealer->data["del_update_by_id"] = $del_update_by_id;
    $sm_dealer->data["del_br_id"] = $del_br_id;
    $sm_dealer->action = 'insert';
    $result = $sm_dealer->process();
    if ($result['status'] == 'failure') {
        die(json_encode(["success" => false, "successmsg" => $result["errormsg"]]));
    } else {
        $arr_log = array();
        $arr_log["log_message"] = $del_first_name . " " . $del_last_name . " (" . $result['id'] . ") has been added.";
        $arr_log["log_del_id"] = $result['id'];
        $arr_log["log_admin_id"] = $tmp_admin_id;
        $arr_log["log_stu_id"] = 0;
        $arr_log["log_action"] = "add_dealer";
        add_log($arr_log);

        $data_arr_input = array();
        $data_arr_input['select_field'] = 'del_company_name ,del_id';
        $data_arr_input['table'] = 'sm_dealer';
        $data_arr_input['where'] = " del_br_id = " . $tmp_admin_id . " AND del_status  = 'A' ";
        $data_arr_input['key_id'] = 'del_id';
        $data_arr_input['key_name'] = 'del_company_name';
        $data_arr_input['current_selection_value'] = $result['id'];
        $data_arr_input['order_by'] = 'del_company_name';

        die(json_encode(["success" => true, "successmsg" => $del_first_name . " " . $del_last_name . " (" . $result['id'] . ") has been added.", "data" => display_dd_options_return($data_arr_input)]));
    }
}
