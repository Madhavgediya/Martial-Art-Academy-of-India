<?php

// convert_db_to_disp_date
// convert_disp_to_db_date
function escape($str) {
    $search = array("'");
    $replace = array("\'");
    return str_replace($search, $replace, $str);
}

include_once("send-mail.php");
/**
 * Get random generated string
 *
 * Returns random string with given length
 *
 * @access	public
 * @return	string
 * @param	int
 */
if (!function_exists("randomPrefix")) {

    function randomPrefix($length) {
        $random = "";

        srand((double) microtime() * 1000000);

        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        $random = strtoupper($random);
        return $random;
    }

}

/**
 * Get the requested data
 *
 *
 * @access	public
 * @return	string
 * @param	string
 */
if (!function_exists("get_rdata")) {

    function get_rdata($VarName, $Default = "") { /// proocess input variables;
        $InputPro = '';
        if (isset($_REQUEST[$VarName]) && is_array($_REQUEST[$VarName])) {
            $InputPro = $_REQUEST[$VarName];
            foreach ($InputPro as $key => $val) {
                $InputPro[$key] = trim($val);
            }
        } else if (isset($_REQUEST[$VarName]) && !is_array($_REQUEST[$VarName])) {
            $InputPro = trim($_REQUEST[$VarName]);
        } else {
            $InputPro = trim($Default);
        }
        return $InputPro;
    }

}

function InputPro($VarName, $Default = "") { /// proocess input variables;
    if (isset($_REQUEST[$VarName]) && is_array($_REQUEST[$VarName])) {
        $InputPro = $_REQUEST[$VarName];
        foreach ($InputPro as $key => $val) {
            $InputPro[$key] = addslashes(trim($val));
        }
    } else if (isset($_REQUEST[$VarName]) && !is_array($_REQUEST[$VarName])) {
        $InputPro = addslashes(trim($_REQUEST[$VarName]));
    } else {
        $InputPro = addslashes(trim($Default));
    }
    return $InputPro;
}

/**
 * For check that the checkbox is selected or not.
 *
 * @access	public
 * @return	the checkbox is checked or not.
 * @param	string/int and string/int
 */
if (!function_exists("set_checked")) {

    function set_checked($value1, $value2) {
        if ($value1 == $value2) {
            return 'checked = "checked"';
        }
    }

}

/**
 * For check that the combobox is selected or not.
 *
 * @access	public
 * @return	the combobox is selected or not.
 * @param	string/int and string/int
 */
if (!function_exists("set_selected")) {

    function set_selected($value1, $value2) {
        if ($value1 == $value2) {
            return 'selected = "selected"';
        }
    }

}

/**
 * For insert the log data.
 *
 * @access	public
 * @return	success if inserted successfully.
 * @param	needed data which are you insert in log.
 */
/*
if (!function_exists("add_log")) {

    //function add_log($log_action, $log_user_id, $log_user_type, $log_admin_id, $log_remarks) {
    function add_log($log_action, $log_user_id, $log_admin_id, $log_remarks) {
        $log_master = new log_master();
        $log_master->data['log_action'] = $log_action;
        $log_master->data['log_user_id'] = $log_user_id;
        //$log_master->data['log_user_type'] = $log_user_type;
        $log_master->data['log_admin_id'] = $log_admin_id;
        $log_master->data['log_remarks'] = $log_remarks;
        $log_master->data['log_date'] = date('Y-m-d');
        $log_master->action = 'insert';
        $log_master->process();
    }

}
*/
/**
 * This function takes a path to a file to output ($file),
 * the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
 * @access	public
 * @return
 * @param
 */
if (!function_exists("output_file")) {

    function output_file($file, $name, $mime_type = '', $file_actual_name) {
        //Check the file premission
        if (!is_readable($file))
            die('File not found or inaccessible!');

        $size = filesize($file);
        $name = rawurldecode($name);

        /* Figure out the MIME type | Check in array */
        $known_mime_types = array(
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "htm" => "text/html",
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "gif" => "image/gif",
            "png" => "image/png",
            "jpeg" => "image/jpg",
            "jpg" => "image/jpg",
            "php" => "text/plain"
        );

        if ($mime_type == '') {
            $file_extension = strtolower(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else {
                $mime_type = "application/force-download";
            };
        };

        //turn off output buffering to decrease cpu usage
        @ob_end_clean();

        // required for IE, otherwise Content-Disposition may be ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type:application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_actual_name . '"'); //
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the
          download non-cacheable */
        header("Cache-control: Public");
        header('Pragma: no-cache');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }
            /*
              ------------------------------------------------------------------------------------------------------
              //This application is developed by www.webinfopedia.com
              //visit www.webinfopedia.com for PHP,Mysql,html5 and Designing tutorials for FREE!!!
              ------------------------------------------------------------------------------------------------------
             */
            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }

        /* Will output the file itself */
        $chunksize = 1 * (1024 * 1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length)) {
                $buffer = fread($file, $chunksize);
                print($buffer); //echo($buffer); // can also possible
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        } else
        //If no permissiion
            die('Error - can not open file.');
        //die
        die();
    }

}

function m_process($action, $query_a) {
    global $dbh, $c_file;
    // fetchAll()
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$dbh->beginTransaction();
    if ($action == 'get_data') {
        try {
            $sth1 = $dbh->prepare($query_a);
            $sth1->execute();
            $dbh->commit();
            $result = array("errormsg" => "", "id" => 0, "status" => "success", "res" => $sth1->fetchAll(PDO::FETCH_ASSOC), "count" => $sth1->rowCount());
        } catch (Exception $e) {
            $dbh->rollBack();
            $result = array("errormsg" => $e->getMessage(), "status" => "failure");
        }
    } else if ($action == 'insert') {
        try {
            $dbh->exec($query_a);
            $result = array("errormsg" => "", "id" => $dbh->lastInsertId(), "status" => "success");
            $dbh->commit();
        } catch (Exception $e) {
            $dbh->rollBack();
            $result = array("errormsg" => $e->getMessage(), "status" => "failure");
        }
    } else if ($action == 'update') {
        try {
            $dbh->exec($query_a);
            $result = array("errormsg" => "", "id" => 0, "status" => "success");
            $dbh->commit();
        } catch (Exception $e) {
            $dbh->rollBack();
            $result = array("errormsg" => $e->getMessage(), "status" => "failure");
        }
    } else if ($action == 'delete') {
        try {
            $dbh->exec($query_a);
            $result = array("errormsg" => "", "id" => 0, "status" => "success");
            $dbh->commit();
        } catch (Exception $e) {
            $dbh->rollBack();
            $result = array("errormsg" => $e->getMessage(), "status" => "failure");
        }
    }
    if (IS_LOCAL == TRUE) {
        add_log_txt($c_file . '--' . $query_a);
    }
    //  add_log_txt($query_a);
    return $result;
}

// SELECT count(*) as count_s FROM
function found_duplicate_free($q) {
    $arr_retun = array();
    $arr_retun['errormsg'] = '';
    $arr_retun['duplicate'] = false;
    // $q = "SELECT count(*) as count_s FROM " . $table . " WHERE " . $filed . " = '" . $filed_value . "' " . $not_value;
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_retun['errormsg'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            if ($result['res'][0]['count_s'] > 0) {
                $arr_retun['duplicate'] = true;
            } else {
                $arr_retun['duplicate'] = false;
            }
        } else {
            $arr_retun['errormsg'] = 'no records found';
        }
    }
    return $arr_retun;
}

function found_duplicate($table, $filed, $filed_value, $not_value = "") {
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['duplicate'] = false;
    $q = "SELECT count(*) as count_s FROM " . $table . " WHERE " . $filed . " = '" . $filed_value . "' " . $not_value;
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            if ($result['res'][0]['count_s'] > 0) {
                $arr_retun['duplicate'] = true;
            } else {
                $arr_retun['duplicate'] = false;
            }
        } else {
            $arr_retun['error_message'] = 'no records found';
        }
    }
    return $arr_retun;
}

function get_student_id_from_login_id($req_session_id) {
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['id'] = 0;
    $q = "SELECT lo_access_id FROM sm_login WHERE lo_id= " . $req_session_id;
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            $arr_retun['id'] = $result['res'][0]['lo_access_id'];
        } else {
            $arr_retun['error_message'] = 'no records found';
        }
    }
    return $arr_retun;
}

function get_standard($sc_id, $st_status, $select_value) {
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['id'] = 0;
    $q = "SELECT std_name ,std_id  FROM sm_standard  WHERE std_sc_id= " . $sc_id . " AND (std_status = '" . $st_status . "' OR '" . $st_status . "'='' )";
    // echo $q;
    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                if ($select_value == $db_row["std_id"]) {
                    echo '<option value="' . $db_row["std_id"] . '" selected="selected">' . $db_row["std_name"] . '</option>';
                } else {
                    echo '<option value="' . $db_row["std_id"] . '">' . $db_row["std_name"] . '</option>';
                }
            }
        }
    }
}

/*

 */

// $data_arr_input['select_field']
// $data_arr_input['table']
// $data_arr_input['where']
// $data_arr_input['key_id']
// $data_arr_input['key_name']
// $data_arr_input['order_by']
function display_dd_options($data_arr_input) {
    $order_by = "";
    if (isset($data_arr_input["order_by"]) && $data_arr_input["order_by"] != '') {
        $order_by = $data_arr_input["order_by"];
    } else {
        $order_by = $data_arr_input['key_name'];
    }
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['id'] = 0;
    $q = "SELECT " . $data_arr_input['select_field'] . "  FROM " . $data_arr_input['table'];
    if ($data_arr_input['where'] != '') {
        $q .= " WHERE " . $data_arr_input['where'];
    }
    $q .= " ORDER BY " . $order_by;
    //exit(0);
    // echo $q;
    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                if (array_key_exists('current_selection_value', $data_arr_input) && ($data_arr_input['current_selection_value'] == $db_row[$data_arr_input['key_id']])) {
                    echo '<option value="' . $db_row[$data_arr_input['key_id']] . '" selected="selected">' . $db_row[$data_arr_input['key_name']] . '</option>';
                } else {
                    echo '<option value="' . $db_row[$data_arr_input['key_id']] . '">' . $db_row[$data_arr_input['key_name']] . '</option>';
                }
            }
        }
    }
}

function display_dd_options_return($data_arr_input) {
    $order_by = "";
    $response = array();
    $response["errormsg"] = "";
    $response["status"] = "success";
    $response["data"] = "";

    if (isset($data_arr_input["order_by"]) && $data_arr_input["order_by"] != '') {
        $order_by = $data_arr_input["order_by"];
    } else {
        $order_by = $data_arr_input['key_name'];
    }

    $q = "SELECT " . $data_arr_input['select_field'] . "  FROM " . $data_arr_input['table'];
    if ($data_arr_input['where'] != '') {
        $q .= " WHERE " . $data_arr_input['where'];
    }
    $q .= " ORDER BY " . $order_by;
// echo $q;
    $result = m_process("get_data", $q);
    //  echo $q;
    //  print_r($result);
    if ($result['errormsg'] != '') {
        $response['errormsg'] = $result['errormsg'];
        $response["status"] = "failure";
    } else {
        if (isset($data_arr_input["first_root"]))
            $response["data"] .= $data_arr_input["first_root"];

        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {                
                if (array_key_exists('current_selection_value', $data_arr_input) && ($data_arr_input['current_selection_value'] == $db_row[$data_arr_input['key_id']])) {
                    $response["data"] .= '<option value="' . $db_row[$data_arr_input['key_id']] . '" selected="selected">' . $db_row[$data_arr_input['key_name']] . '</option>';
                } else {
                    $response["data"] .= '<option value="' . $db_row[$data_arr_input['key_id']] . '">' . $db_row[$data_arr_input['key_name']] . '</option>';
                }
            }
        }
    }
    //   print_r($response);
    return $response;
}

function display_dd_options_from_array_old($arr_dd, $select_value) {
    foreach ($arr_dd as $key => $value) {
        if ($key == $select_value) {
            echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
        } else {
            echo '<option value="' . $key . '">' . $value . '</option>';
        }
    }
}

function selected_value_return($arr_dd, $select_value,$return_type="value") 
{
    foreach ($arr_dd as $key => $value) 
    {
        if ($key == $select_value) 
        {
            if ($return_type == 'key') return $key;
            else if ($return_type == 'value') return $value;
        } 
    }
}

function display_dd_options_from_array_old_return($arr_dd, $select_value) {
    $output = "";
    foreach ($arr_dd as $key => $value) {
        if ($key == $select_value) {
            $output .= '<option value="' . $key . '" selected="selected">' . $value . '</option>';
        } else {
            $output .= '<option value="' . $key . '">' . $value . '</option>';
        }
    }
    return $output;
}

// $data_arr_input['select_field']
// $data_arr_input['consider'] = value or key
// data_array
// current_selection_value
function display_dd_options_from_array($data_arr_input) {
    if (count($data_arr_input['data_array']) > 0) {
        foreach ($data_arr_input['data_array'] as $array_row_key => $array_row_value) {
            if ($data_arr_input['consider'] == 'key') {
                if (array_key_exists('current_selection_value', $data_arr_input) && $data_arr_input['current_selection_value'] == $array_row_key) {
                    echo '<option value="' . $array_row_key . '" selected="selected">' . $array_row_value . '</option>';
                } else {
                    echo '<option value="' . $array_row_key . '">' . $array_row_value . '</option>';
                }
            } else {
                if (array_key_exists('current_selection_value', $data_arr_input) && $data_arr_input['current_selection_value'] == $array_row_value) {
                    echo '<option value="' . $array_row_value . '" selected="selected">' . $array_row_value . '</option>';
                } else {
                    echo '<option value="' . $array_row_value . '">' . $array_row_value . '</option>';
                }
            }
        }
    }
}

function convert_disp_to_db_date($input_date) {
    // $input_date = 12/08/2015
    $arr_date = explode("-", $input_date);
    return $arr_date[2] . "-" . $arr_date[1] . "-" . $arr_date[0];
}

function convert_db_to_disp_date($input_date) {
    // $input_date = 2015/08/29
    // echo $input_date;
    $arr_date = explode("-", $input_date);
    return $arr_date[2] . "-" . $arr_date[1] . "-" . $arr_date[0];
}

function add_log_txt($somecontent) {
    $filename = 'qlog.sql';
    $somecontent = date("d-m-y-h:m:s") . ' ' . $somecontent . "\n\n";
    if (is_writable($filename)) {
        if (!$handle = fopen($filename, 'a')) {
            exit;
        }
        if (fwrite($handle, $somecontent) === FALSE) {
            exit;
        }
        fclose($handle);
    }
}

function validate_before_delete($table, $where = "") {
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['found_reference'] = false;
    $q = "SELECT 1 FROM " . $table . " WHERE  " . $where;
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            $arr_retun['found_reference'] = true;
        } else {
            $arr_retun['found_reference'] = false;
        }
    }
    return $arr_retun;
}

function add_gcm_notification($arr_data) {
    if ((!isset($arr_data["condition"])) || (isset($arr_data["condition"]) && $arr_data["condition"] == "" )) {
        $q = "INSERT INTO sm_gcm_process(gcmp_gcm_gcm_id,	gcmp_not_id, gcmp_gcm_sc_id, gcmp_gcm_stu_id, gcmp_message, gcmp_title, gcmp_subtitle, gcmp_tickerText, gcmp_status, gcmp_create_by, gcmp_create_date,gcmp_goToScreen,gcmp_school) ";
        $q .= " SELECT g.gcm_gcm_id , sm.sc_id,s.stu_id ,  " . $arr_data["gcmp_not_id"] . ",' " . $arr_data["gcmp_message"] . "', '" . $arr_data["gcmp_title"] . "', '" . $arr_data["gcmp_subtitle"] . "', '" . $arr_data["gcmp_tickerText"] . "',  'Y', '" . $arr_data["gcmp_create_by"] . "', '" . $arr_data["gcmp_create_date"] . "', '" . $arr_data["gcmp_goToScreen"] . "' , sm.sc_name  FROM sm_gcm g ";
        $q .= " INNER JOIN sm_school_master sm ON (g.gcm_sc_id=sm.sc_id) ";
        $q .= " INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id) ";
        $q .= " INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)  ";
        $q .= " WHERE g.gcm_sc_id = " . $arr_data["gcmp_gcm_sc_id"] . " AND s.stu_status = 'A' ";
    } else if (isset($arr_data["condition"]) && $arr_data["condition"] == "timetable") {
        $q = "INSERT INTO sm_gcm_process(gcmp_gcm_gcm_id, gcmp_not_id, gcmp_gcm_sc_id, gcmp_gcm_stu_id, gcmp_message, gcmp_title, gcmp_subtitle, gcmp_tickerText, gcmp_status, gcmp_create_by,  gcmp_create_date,gcmp_goToScreen,gcmp_school) ";
        $q .= " SELECT g.gcm_gcm_id , sm.sc_id,s.stu_id ,  " . $arr_data["gcmp_not_id"] . ",' " . $arr_data["gcmp_message"] . "', '" . $arr_data["gcmp_title"] . "', '" . $arr_data["gcmp_subtitle"] . "', '" . $arr_data["gcmp_tickerText"] . "',  'Y', '" . $arr_data["gcmp_create_by"] . "', '" . $arr_data["gcmp_create_date"] . "', '" . $arr_data["gcmp_goToScreen"] . "' , sm.sc_name  FROM sm_gcm g ";
        $q .= " INNER JOIN sm_school_master sm ON (g.gcm_sc_id=sm.sc_id) ";
        $q .= " INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id) ";
        $q .= " INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)  ";
        $q .= " INNER JOIN sm_standard st  ON (	st.std_id = s.stu_std_id)  ";
        $q .= " INNER JOIN sm_class c  ON (c.cl_id = s.stu_cl_id)  ";
        $q .= " WHERE g.gcm_sc_id = " . $arr_data["gcmp_gcm_sc_id"] . " AND s.stu_status = 'A' AND st.std_id = " . $arr_data["std_id"] . " AND s.stu_medium = '" . $arr_data["medium"] . "' AND c.cl_id = " . $arr_data["cl_id"];
    } else if (isset($arr_data["condition"]) && ($arr_data["condition"] == "result" || $arr_data["condition"] == "specialnotes")) {
        $q = "INSERT INTO sm_gcm_process(gcmp_gcm_gcm_id,	gcmp_not_id, gcmp_gcm_sc_id, gcmp_gcm_stu_id, gcmp_message, gcmp_title, gcmp_subtitle, gcmp_tickerText, gcmp_status, gcmp_create_by, gcmp_create_date,gcmp_goToScreen,gcmp_school) ";
        $q .= " SELECT g.gcm_gcm_id , sm.sc_id,s.stu_id ,  " . $arr_data["gcmp_not_id"] . ",' " . $arr_data["gcmp_message"] . "', '" . $arr_data["gcmp_title"] . "', '" . $arr_data["gcmp_subtitle"] . "', '" . $arr_data["gcmp_tickerText"] . "',  'Y', '" . $arr_data["gcmp_create_by"] . "', '" . $arr_data["gcmp_create_date"] . "', '" . $arr_data["gcmp_goToScreen"] . "' , sm.sc_name FROM sm_gcm g ";
        $q .= " INNER JOIN sm_school_master sm ON (g.gcm_sc_id=sm.sc_id) ";
        $q .= " INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id) ";
        $q .= " INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)  ";
        $q .= " WHERE g.gcm_sc_id = " . $arr_data["gcmp_gcm_sc_id"] . " AND s.stu_status = 'A' AND s.stu_id = " . $arr_data["stu_id"];
    }

    $result = m_process("insert", $q);

    if ((!isset($arr_data["condition"])) || (isset($arr_data["condition"]) && $arr_data["condition"] == "" )) {
        $process_school = 0;

        if ($arr_data["gcmp_gcm_sc_id"] == 1) {
            $process_school = 2;
        } else if ($arr_data["gcmp_gcm_sc_id"] == 2) {
            $process_school = 1;
        }

        if ($process_school != 0) {
            $q = "INSERT INTO sm_gcm_process(gcmp_gcm_gcm_id,	gcmp_not_id, gcmp_gcm_sc_id, gcmp_gcm_stu_id, gcmp_message, gcmp_title, gcmp_subtitle, gcmp_tickerText, gcmp_status, gcmp_create_by, gcmp_create_date,gcmp_goToScreen,gcmp_school) ";
            $q .= " SELECT g.gcm_gcm_id , sm.sc_id,s.stu_id ,  " . $arr_data["gcmp_not_id"] . ",' " . $arr_data["gcmp_message"] . "', '" . $arr_data["gcmp_title"] . "', '" . $arr_data["gcmp_subtitle"] . "', '" . $arr_data["gcmp_tickerText"] . "',  'Y', '" . $arr_data["gcmp_create_by"] . "', '" . $arr_data["gcmp_create_date"] . "', '" . $arr_data["gcmp_goToScreen"] . "' , sm.sc_name  FROM sm_gcm g LEFT JOIN sm_gcm_process gcp ON (gcp.gcmp_gcm_gcm_id = g.gcm_gcm_id ) ";
            $q .= " INNER JOIN sm_school_master sm ON (g.gcm_sc_id=sm.sc_id) ";
            $q .= " INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id) ";
            $q .= " INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)  ";
            $q .= " WHERE g.gcm_sc_id = " . $process_school . " AND s.stu_status = 'A' AND gcp.gcmp_id IS NULL ";
            $result = m_process("insert", $q);
        }
        // process for school 2 and school 1
    }



    if ($result['errormsg'] != '') {
        print_r($result['errormsg']);
        // return $result['errormsg'];
        exit(0);
    }
    add_gcm_notification_not_school($arr_data);
    send_gcm();
}

function send_gcm() {
    $update_ids = 0;
    $gcmp_not_id = 0;
    $q = "SELECT count(*),gcmp_not_id  FROM sm_gcm_process WHERE gcmp_status = 'Y'  GROUP BY  gcmp_not_id LIMIT 0,1";
    $result = m_process("get_data", $q);
    //echo $q;
    $gcm_message = "";
    $registrationId = array();
    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    } else {
        if ($result["count"] > 0) {
            $gcmp_not_id = $result["res"][0]["gcmp_not_id"];
        } else {
            return false;
        }
    }


    $q = "SELECT gcmp.* FROM sm_gcm_process gcmp WHERE gcmp.gcmp_status = 'Y' AND  gcmp.gcmp_not_id = $gcmp_not_id LIMIT 0,50 ";
    $result = m_process("get_data", $q);
    //echo $q;
    $gcm_message = "";
    $registrationId = array();
    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    } else {
        foreach ($result["res"] as $arr_db) {
            $registrationId[] = $arr_db["gcmp_gcm_gcm_id"];
            $gcm_message = $arr_db["gcmp_message"];
            $goToScreen = $arr_db["gcmp_goToScreen"];
            $school = $arr_db["gcmp_school"];
            $update_ids .= "," . $arr_db["gcmp_id"];
        }
    }

    $msg = array('message' => $gcm_message, 'goToScreen' => $goToScreen, 'school' => $school);
    /*
      'goToScreen'		=> $goToScreen,
      'school'			=> $school,
     *  */

    $fields = array
        (
        'registration_ids' => $registrationId,
        'data' => $msg,
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json',
        'delay_while_idle: true',
    );

    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        $q = "UPDATE sm_gcm_process SET gcmp_status = 'N' WHERE  gcmp_id IN ($update_ids)";
        $result = m_process("update", $q);
        //   echo $q;
        $gcm_message = "";
        $registrationId = array();
        if ($result['errormsg'] != '') {
            return $result['errormsg'];
        }
    } catch (Exception $e) {
        echo $e;
        echo "inside catch";
    }

// prep the bundle
    /*
      $msg = array
      (
      'message' 	=> 'here is a message. message',
      'title'		=> 'This is a title. title',
      'subtitle'	=> 'This is a subtitle. subtitle',
      'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
      'vibrate'	=> 1,
      'sound'		=> 1,
      'largeIcon'	=> 'large_icon',
      'smallIcon'	=> 'small_icon'
      );
     */
//$msg = "please note this..";
}

function add_notes($arr_data) {
    $not_id = 0;
    $q = "INSERT INTO sm_notification(not_message, not_sc_id,not_goToScreen, not_status, not_create_date, not_create_by_id, not_update_date, not_update_by_id) VALUES ('" . $arr_data["not_message"] . "'," . $arr_data["not_sc_id"] . ",'" . $arr_data["not_goToScreen"] . "', 'A', ' " . $arr_data["not_create_date"] . "'," . $arr_data["not_create_by_id"] . " , ' " . $arr_data["not_update_date"] . " ', " . $arr_data["not_update_by_id"] . ") ";


    //, '$gcmp_gcm_gcm_id', $gcmp_gcm_sc_id, $gcmp_gcm_stu_id, )";
    //$q = "INSERT INTO sm_gcm_process(gcmp_gcm_gcm_id, gcmp_gcm_sc_id, gcmp_gcm_stu_id, gcmp_message, gcmp_title, gcmp_subtitle, gcmp_tickerText, gcmp_status, gcmp_create_by, gcmp_create_date) SELECT  ($gcmp_id, '$gcmp_gcm_gcm_id', $gcmp_gcm_sc_id, $gcmp_gcm_stu_id, '$gcmp_message', '$gcmp_title', '$gcmp_subtitle', '$gcmp_tickerText',  'Y', '$gcmp_create_by', '$gcmp_create_date')";
    $result = m_process("insert", $q);
    //echo $q;
    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    } else {
        $not_id = $result["id"];
    }
    $arr_data["gcmp_not_id"] = $not_id;
    add_gcm_notification($arr_data);
}

function clean_name($input_name) {
    $input_name = strtolower($input_name);
    $input_name = str_replace("=", "", $input_name);
    $input_name = str_replace("?", "", $input_name);
    $input_name = str_replace("&", "and", $input_name);
    $input_name = str_replace("%", "", $input_name);
    $input_name = str_replace("'", "", $input_name);
    $input_name = str_replace("\"", "", $input_name);
    $input_name = str_replace("/", "", $input_name);
    $input_name = str_replace("\\", "", $input_name);
    $input_name = str_replace(" ", "-", $input_name);
    $input_name = str_replace(",", "", $input_name);
    $input_name = str_replace("--", "-", $input_name);
    return $input_name;
}

function get_attendance_details($sc_id, $std_id, $cl_id, $att_stu_medium, $att_date) {
    $response = array();
    $response['errormsg'] = '';
    $response["att_attended"] = '';
    $response["att_absent"] = '';
    $att_date = convert_disp_to_db_date($att_date);
    $q = " SELECT att_attended , att_absent FROM ";
    $q .= " sm_attendance_b INNER JOIN sm_school_master ON (sc_id=att_sc_id) INNER JOIN sm_standard ON (std_id = att_std_id) INNER JOIN sm_class  ON (cl_id = att_cl_id ) ";
    $q .= " WHERE sc_id=$sc_id AND std_id = $std_id AND cl_id = $cl_id AND att_stu_medium = '$att_stu_medium' AND att_date ='$att_date'  ";

    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $response['errormsg'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            print_r($result["res"]);
            $response["att_attended"] = $result["res"][0]["att_attended"];
            $response["att_absent"] = $result["res"][0]["att_absent"];
        }
    }

    return $response;
}

function get_student_id_school_id($req_school, $req_session_id) {
    $arr_return = array();
    $arr_return["stu_id"] = 0;
    $arr_return["sc_id"] = 0;

    $q_student_school = "SELECT DISTINCT s.stu_id , sm.sc_id FROM sm_school_master sm
                        INNER JOIN sm_student s ON (s.stu_sc_id=sm.sc_id)
                        INNER JOIN sm_login lo  ON (lo.lo_access_id = s.stu_id)
                        WHERE s.stu_status = 'A' AND sm.sc_name='" . $req_school . "' AND lo.lo_status = 'A'  AND lo.lo_id =  " . $req_session_id;
    $result_student_school = m_process("get_data", $q_student_school);

    if ($result_student_school['errormsg'] != '') {
        $response_array_student_school['error_code'] = '002';
        $response_array_student_school['error_message'] = $result_student_school['errormsg'];
    } else {
        if ($result_student_school['count'] > 0) {
            $arr_return["stu_id"] = $result_student_school["res"][0]["stu_id"];
            $arr_return["sc_id"] = $result_student_school["res"][0]["sc_id"];
        }
    }
    return $arr_return;
}

function add_gcm_notification_not_school($arr_data) {
    if ((!isset($arr_data["condition"])) || (isset($arr_data["condition"]) && $arr_data["condition"] == "" )) {
        $q = "INSERT INTO sm_gcm_process(gcmp_gcm_gcm_id,	gcmp_not_id, gcmp_gcm_sc_id, gcmp_gcm_stu_id, gcmp_message, gcmp_title, gcmp_subtitle, gcmp_tickerText, gcmp_status, gcmp_create_by, gcmp_create_date,gcmp_goToScreen,gcmp_school) ";
        $q .= " SELECT g.gcm_gcm_id ,  " . $arr_data["gcmp_not_id"] . ",1,1,' " . $arr_data["gcmp_message"] . "', '" . $arr_data["gcmp_title"] . "', '" . $arr_data["gcmp_subtitle"] . "', '" . $arr_data["gcmp_tickerText"] . "',  'Y', '" . $arr_data["gcmp_create_by"] . "', '" . $arr_data["gcmp_create_date"] . "', '" . $arr_data["gcmp_goToScreen"] . "' , 'eklavya'  FROM sm_gcm g ";
        $q .= " WHERE g.gcm_sc_id = 0  ";

        $result = m_process("insert", $q);

        if ($result['errormsg'] != '') {
            print_r($result['errormsg']);
            // return $result['errormsg'];
            exit(0);
        }
    }
}

function get_user_details() {

    global $tmp_invoice_prefix, $tmp_admin_id, $tmp_name, $tmp_pass, $tmp_type, $tmp_status, $tmp_batch_time;
//  $arr_respone = array("errormsg");
    $tmp_id = session_get("id");

    $q = "SELECT br_invoice_prefix,  br_id, br_name, br_pass , br_batch_time, br_type, br_status FROM sm_branch WHERE br_id = " . $tmp_id;
    $result = m_process("get_data", $q);
    //echo $q;



    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    } else {

        if ($result["count"] == 0) {

            return "Invalid Admin:001";
        } else {


            $tmp_name = $result["res"][0]["br_name"];
            $tmp_pass = $result["res"][0]["br_pass"];
            $tmp_type = $result["res"][0]["br_type"];
            $tmp_status = $result["res"][0]["br_status"];
            $tmp_id = $result["res"][0]["br_id"];
            $tmp_batch_time = $result["res"][0]["br_batch_time"];
            $tmp_invoice_prefix = $result["res"][0]["br_invoice_prefix"];
            return "";
            /*
              $arr_respone["admin_fname"] = $arr_db[0]["admin_fname"];
              $arr_respone["admin_mname"] = $arr_db[0]["admin_mname"];
              $arr_respone["admin_lname"] = $arr_db[0]["admin_lname"];
              $arr_respone["admin_uname"] = $arr_db[0]["admin_uname"];
              $arr_respone["admin_pass"] = $arr_db[0]["admin_pass"];
              $arr_respone["admin_email"] = $arr_db[0]["admin_email"];
              $arr_respone["admin_status"] = $arr_db[0]["admin_status"];
              $arr_respone["admin_login_type"] = $arr_db[0]["admin_login_type"];
              $arr_respone["admin_br_id"] = $arr_db[0]["admin_br_id"];
             */
        }
    }
}

function get_branch_batch_time() {
    global $tmp_batch_time, $tmp_default_batch_time;
    $var_decode = json_decode($tmp_batch_time);
    $tmp_default_batch_time = $var_decode[0];
    return $var_decode;
}

function update_gr_no($stu_id, $stu_br_id, $table_name) 
{
    $student_id = 1;
    $stu_gr_no = 0;
    if ($table_name == "sm_student") {
    //    $check_q = "SELECT stu_gr_no FROM sm_student WHERE stu_gr_no !='' ORDER BY stu_id DESC LIMIT 0,1";
    $check_q = " SELECT CAST(REPLACE(stu_gr_no,'MA',0) as SIGNED) as stu_inc_id FROM sm_student WHERE stu_gr_no !='' ORDER BY stu_inc_id DESC LIMIT 0,1";
        $check_check_r = m_process("get_data", $check_q);

        if ($check_check_r["status"] == 'error') {
            $arr_response["errormsg"] = $check_check_r["errormsg"];
        } else {
            if ($check_check_r["res"][0]["stu_inc_id"] != null) {
                $stu_gr_no = $check_check_r["res"][0]["stu_inc_id"];
                // $stu_gr_no = str_replace('MA', '', $stu_gr_no);
                $student_id = (int)$stu_gr_no + $student_id;
                $stu_gr_no = 'MA' . substr('00000000' . $student_id, -8);
            }
            else
            {
                $stu_gr_no = 'MA' . substr('00000000' . $student_id, -8);
            }
        }
    }
    else    
    {
        $check_q = "SELECT stu_gr_no FROM sm_student_other WHERE stu_gr_no !=''  ORDER BY stu_id DESC LIMIT 0,1";
        $check_check_r = m_process("get_data", $check_q);

        if ($check_check_r["status"] == 'error') {
            $arr_response["errormsg"] = $check_check_r["errormsg"];
        } else {
            if ($check_check_r["res"][0]["stu_gr_no"] != null) {
                $stu_gr_no = $check_check_r["res"][0]["stu_gr_no"];
                $stu_gr_no = str_replace('OT', '', $stu_gr_no);
                $student_id = (int)$stu_gr_no + $student_id;
                $stu_gr_no = 'OT' . substr('00000000' . $student_id, -8);
            }
            else
            {
                $stu_gr_no = 'OT' . substr('00000000' . $student_id, -8);
            }
        }
    }
  /*  
    if ($table_name == "sm_student")
        $stu_gr_no = 'MA' . substr('00000000' . $stu_id, -8);
    else
        
*/
    $q = "UPDATE  " . $table_name . " SET stu_gr_no = '" . $stu_gr_no . "' ";
    $q .= " WHERE stu_id = " . $stu_id . " AND stu_br_id  =  " . $stu_br_id;
    return m_process("update", $q);
}

function get_course_pricing($cd_id) {
    $arr_response = array();
    $arr_response["errormsg"] = "";
    $q = "SELECT  cd_eu_fee, cd_nu_fee ,cd_eu_exam_fee, 	cd_nu_exam_fee ,cd_eu_fee_onemonth, cd_nu_fee_onemonth FROM sm_course_details WHERE  cd_id  = " . $cd_id;
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    } else {

        if ($result["count"] > 0) {
            $arr_response["cd_eu_fee"] = $result[0]["cd_eu_fee"];
            $arr_response["cd_nu_fee"] = $result[0]["cd_nu_fee"];
            $arr_response["cd_eu_exam_fee"] = $result[0]["cd_eu_exam_fee"];
            $arr_response["cd_nu_exam_fee"] = $result[0]["cd_nu_exam_fee"];
            $arr_response["cd_eu_fee_onemonth"] = $result[0]["cd_eu_fee_onemonth"];
            $arr_response["cd_nu_fee_onemonth"] = $result[0]["cd_nu_fee_onemonth"];
        }
        return $arr_response;
    }
}

function mark_other_course_as_inactive($stu_id, $sc_id, $sc_co_id) {
    $arr_response = array();
    $arr_response["errormsg"] = "";
    $q = "UPDATE sm_student_course SET sc_is_current = 0 WHERE sc_co_id = " .
            $sc_co_id . " AND sc_stu_id = $stu_id AND sc_id != $sc_id ";
    $result = m_process("update", $q);

    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    }
    return "";
}

function get_fee_details_for_student_course($br_id, $brt_id, $be_id, $sc_half_course = 0) {
    $arr_response = array();
    $arr_response["errormsg"] = "";
    $arr_response["totalamount"] = 0;
    // start of getting amount from branch type
    $brt_amount = 0;
    $q = "SELECT brt_amount, brt_amount_month  FROM sm_branch_type WHERE brt_br_id = $br_id AND brt_id = $brt_id";
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_response['errormsg'] = $result['errormsg'];
        return $arr_response;
    } else if ($result['count'] == 0) {
        $arr_response['errormsg'] = "no branch type found";
        return $arr_response;
    } else {

        if ($sc_half_course == 1) {
            $brt_amount = $result["res"][0]['brt_amount_month'];
        } else {
            $brt_amount = $result["res"][0]['brt_amount'];
        }
    }
    // end of getting amount from branch type
    // start of getting amount from belt type
    $be_belt_fee = 0;
    $q = "SELECT be_belt_fee  FROM sm_belt WHERE be_id = $be_id";
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_response['errormsg'] = $result['errormsg'];
        return $arr_response;
    } else if ($result['count'] == 0) {
        $arr_response['errormsg'] = "no belt found";
        return $arr_response;
    } else {
        $be_belt_fee = $result["res"][0]['be_belt_fee'];
    }
    // end of getting amount from belt type
    $arr_response["totalamount"] = $brt_amount * $be_belt_fee;
    return $arr_response;
}

// function wil validate student is able to entroll next course or not.
function validate_course($arr_course_data) {
    // print_r($arr_course_data);
    // check exam is passed for this or previous corse or not.
    $q = "SELECT DISTINCT CONCAT(sc_stu_id,'-',sc_be_id) as passed_id FROM sm_exam_student_entrolled INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id ) WHERE sc_co_id = " . $arr_course_data["sc_co_id"] . " AND sc_stu_id = " . $arr_course_data["sc_stu_id"] . " AND (exs_result_status = 'F' OR exs_result_status = ''  OR exs_result_status = 'A') AND CONCAT(sc_stu_id,'-',sc_be_id) NOT IN ( SELECT DISTINCT CONCAT(sc_stu_id,'-',sc_be_id) as passed_id FROM sm_exam_student_entrolled INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND exs_be_id = sc_be_id AND exs_co_id = sc_co_id ) WHERE  sc_co_id = " . $arr_course_data["sc_co_id"] . " AND sc_stu_id = " . $arr_course_data["sc_stu_id"] . " AND (exs_result_status = 'P') )";
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        return $result['errormsg'];
    } else if ($result['count'] > 0) {
        return "all course are not passed";
    } else {
        // select course those are not given exam
        // $q1 = "SELECT DISTINCT CONCAT(sc_stu_id,'-',sc_be_id) as passed_id FROM sm_student_course WHERE  sc_co_id = ".$arr_course_data["sc_co_id"]." AND  CONCAT(sc_stu_id,'-',sc_be_id) NOT IN (SELECT DISTINCT CONCAT(exs_stu_id,'-',exs_be_id) FROM sm_exam_student_entrolled WHERE exs_co_id = $arr_course_data["sc_co_id"])";

        $q1 = "SELECT DISTINCT CONCAT(sc_stu_id,'-',sc_be_id) as passed_id FROM sm_student_course WHERE  sc_stu_id =" . $arr_course_data["sc_stu_id"] . " AND  sc_co_id = " . $arr_course_data["sc_co_id"] . " AND  CONCAT(sc_stu_id,'-',sc_be_id) NOT IN (SELECT DISTINCT CONCAT(exs_stu_id,'-',exs_be_id) FROM sm_exam_student_entrolled WHERE exs_stu_id =" . $arr_course_data["sc_stu_id"] . " AND exs_co_id =" . $arr_course_data["sc_co_id"] . ")";

        $result = m_process("get_data", $q1);

        if ($result['errormsg'] != '') {
            return $result['errormsg'];
        } else if ($result['count'] > 0) {
            return "all course exam has not taken";
        } else {
            // select course those are not given exam
            return "";
        }
    }
    return "";
}

// if function has any error then this function will return error else return blnak value.
function add_course_to_student($arr_course_data) {
    global $cur_date, $tmp_admin_id;
    $arr_validate_course = validate_course($arr_course_data);
    if ($arr_validate_course != '') {
        return $arr_validate_course;
    }
    $arr_get_course_details = get_fee_details_for_student_course($arr_course_data['sc_br_id'], $arr_course_data['sc_brt_id'], $arr_course_data['sc_be_id'], $arr_course_data['sc_half_course']);
    // $arr_get_course_details = get_course_pricing($sc_cd_id);
    if ($arr_get_course_details["errormsg"] != "") {
        return $arr_get_course_details["errormsg"];
    } else {

        $arr_course_data['sc_create_date'] = $cur_date;
        $arr_course_data['sc_update_date'] = $cur_date;
        $arr_course_data['sc_create_by_id'] = $tmp_admin_id;
        $arr_course_data['sc_update_by_id'] = $tmp_admin_id;
        $arr_course_data['sc_total_fee'] = $arr_get_course_details["totalamount"];
        $arr_course_data['sc_total_paid'] = 0;
        $arr_course_data['sc_full_fee_paid'] = 'N';
        $arr_course_data['sc_is_current'] = 1;

//  $arr_course_data['sc_cd_id'] comes from top
//  $arr_course_data['sc_stu_id'] comes from top
//  $arr_course_data['sc_joined_date'] comes from top
// start of checking code of duplicate entry of student course
        $check_student_course_query = 'SELECT 1 FROM sm_student_course WHERE sc_br_id = ' . $arr_course_data['sc_br_id'] . ' AND sc_brt_id = ' . $arr_course_data['sc_brt_id'] . ' AND	sc_co_id = ' . $arr_course_data['sc_co_id'] . ' AND	sc_be_id = ' . $arr_course_data['sc_be_id'] . ' AND sc_stu_id =' . $arr_course_data['sc_stu_id'];
        $data = array("sc_id" => 0);
        $check_student_course_result = db_perform("sm_student_course", $data, 'get', '', '', $check_student_course_query);
        if ($check_student_course_result["errormsg"] != '') {
            return $check_student_course_result["errormsg"];
        } else if ($check_student_course_result["count"] > 0) {
            return "student is alread enrolled for the same course";
        }
// end of checking code of duplicate entry of student course
// start to code to add student in enrollment
        $add_course_q = "INSERT INTO sm_student_course(sc_half_course,sc_br_id,sc_be_id,sc_co_id,sc_brt_id,sc_stu_id, sc_cd_id, sc_joined_date, sc_total_fee, sc_total_paid, sc_full_fee_paid, sc_is_current, sc_create_date, sc_create_by_id, sc_update_date, sc_update_by_id,sc_course_type) VALUES ";
        $add_course_q .= "(" . $arr_course_data['sc_half_course'] . ", " . $arr_course_data['sc_br_id'] . ", " . $arr_course_data['sc_be_id'] . ", " . $arr_course_data['sc_co_id'] . ", " . $arr_course_data['sc_brt_id'] . ", " . $arr_course_data['sc_stu_id'] . ", " . $arr_course_data['sc_cd_id'] . ", '" . $arr_course_data['sc_joined_date'] . "', " . $arr_course_data['sc_total_fee'] . ", " . $arr_course_data['sc_total_paid'] . ", '" . $arr_course_data['sc_full_fee_paid'] . "', " . $arr_course_data['sc_is_current'] . ", '" . $arr_course_data['sc_create_date'] . "', " . $arr_course_data['sc_create_by_id'] . ", '" . $arr_course_data['sc_update_date'] . "', " . $arr_course_data['sc_update_by_id']. ", '" . $arr_course_data['sc_course_type'] . "') ";

        $result = m_process("insert", $add_course_q);
        if ($result['status'] == 'failure') {
            return $result['errormsg'];
        } else {
            $arr_log = array();
            $arr_log["log_message"] = "Added new course to student.";
            $arr_log["log_stu_id"] = $arr_course_data['sc_stu_id'];
            $arr_log["log_admin_id"] = $tmp_admin_id;
            $arr_log["log_action"] = "add_course_to_student";
            add_log($arr_log);
            $sc_id = $result['id'];
            $result_1 = mark_other_course_as_inactive($arr_course_data['sc_stu_id'], $sc_id, $arr_course_data['sc_co_id']);
            return $result_1;
        }
// end to code to add student in enrollment
    }
}

//  add  couser to student older one end
// $data_arr_input['select_field']
// $data_arr_input['table']
// $data_arr_input['where']
// $data_arr_input['key_id']
// $data_arr_input['key_name']
// $data_arr_input['order_by']
function m_process_arrray($data_arr_input) {
    /*
      foreach($data_arr_input["field"] as $key=>$value)
      {

      }
      $order_by = "";
      if (isset($data_arr_input["order_by"]) && $data_arr_input["order_by"] != '') {
      $order_by = $data_arr_input["order_by"];
      } else {
      $order_by = $data_arr_input['key_name'];
      }
      $arr_retun = array();
      $arr_retun['error_message'] = '';
      $arr_retun['id'] = 0;
      $q = "SELECT " . $data_arr_input['select_field'] . "  FROM " . $data_arr_input['table'];
      if ($data_arr_input['where'] != '') {
      $q .= " WHERE " . $data_arr_input['where'];
      }
      $q .= " ORDER BY " . $order_by;
      //exit(0);
      // echo $q;
      $result = m_process("get_data", $q);
      if ($result['errormsg'] != '') {
      $arr_retun['error_message'] = $result['errormsg'];
      } else {
      if ($result['count'] > 0) {
      foreach ($result['res'] as $db_row) {
      if ($data_arr_input['current_selection_value'] == $db_row[$data_arr_input['key_id']]) {
      echo '<option value="' . $db_row[$data_arr_input['key_id']] . '" selected="selected">' . $db_row[$data_arr_input['key_name']] . '</option>';
      } else {
      echo '<option value="' . $db_row[$data_arr_input['key_id']] . '">' . $db_row[$data_arr_input['key_name']] . '</option>';
      }
      }
      }
      }
     */
}

// if function has any error then this function will return error else return blnak value.
function pay_fee_student($arr_fees_data) {
    global $cur_date, $tmp_admin_id;

    // start to add entry in payment

    $data = array();
    if ($arr_fees_data["pt_tran_amount"] == '') $arr_fees_data["pt_tran_amount"] = 0;
    if ($arr_fees_data["pt_discount_amount"] == '') $arr_fees_data["pt_discount_amount"] = 0;
    $arr_fees_data["pt_receipt_no"] = get_receipt_no($tmp_admin_id,'');
    $data["pt_type"] = $arr_fees_data["pt_type"];
    $data["pt_ac_id"] = isset($arr_fees_data["pt_ac_id"])?$arr_fees_data["pt_ac_id"]:0;
    $data["pt_tran_u_type"] = $arr_fees_data["pt_tran_u_type"] ?? NULL;
    $data["pt_tran_bank"] = $arr_fees_data["pt_tran_bank"] ?? NULL;
    $data["pt_tran_mode_of_payent"] = $arr_fees_data["pt_tran_mode_of_payent"] ?? NULL;
    $data["pt_tran_no"] = $arr_fees_data["pt_tran_no"] ?? NULL;
    $data["pt_tran_amount"] = $arr_fees_data["pt_tran_amount"] ?? NULL;
    $data["pt_receipt_no"] = $arr_fees_data["pt_receipt_no"] ?? NULL;
    $data["pt_stu_id"] = $arr_fees_data["pt_stu_id"] ?? NULL;
    $data["pt_discount_amount"] = $arr_fees_data["pt_discount_amount"] ?? NULL;

    $data["pt_tran_date"] = $arr_fees_data["pt_tran_date"] ?? NULL;
    $data["pt_tran_remarks"] = $arr_fees_data["pt_tran_remarks"] ?? NULL;
    $data["pt_sc_id"] = $arr_fees_data["sc_id"] ?? NULL;
    $data["pt_br_id"] = $arr_fees_data["pt_br_id"] ?? NULL;
    $data["pt_create_date"] = $cur_date;
    $data["pt_create_by_id"] = $tmp_admin_id;

    $data["pt_update_date"] = $cur_date;
    $data["pt_update_by_id"] = $tmp_admin_id;

    $add_fees_result = db_perform("sm_payment_transaction", $data, 'insert', '', '', '');

    if ($add_fees_result["status"] == 'failure') {
        return $add_fees_result["errormsg"];
    } else {
    
        $q_update = "UPDATE sm_student_course SET sc_discount_amount = sc_discount_amount + " . $arr_fees_data['pt_discount_amount'].", sc_total_paid = sc_total_paid + " . $arr_fees_data['pt_tran_amount'] . " WHERE sc_id = " . $arr_fees_data['sc_id'];
        $udpate_result = m_process("update", $q_update);
        if ($udpate_result["status"] == "failure") {
            return $udpate_result["errormsg"];
        } else {
            $q_update1 = "UPDATE sm_student_course SET sc_full_fee_paid = 'Y' WHERE sc_total_fee = (sc_total_paid+sc_discount_amount) AND sc_id = " . $arr_fees_data['sc_id'];
            $udpate_result1 = m_process("update", $q_update1);
            if ($udpate_result1["status"] == "failure") {
                return $udpate_result1["errormsg"];
            } else {
                return "";
            }
        }
    }
    return '';
}

// if function has any error then this function will return error else return blnak value.
function pay_fee_student_exam($arr_fees_data) {
    global $cur_date, $tmp_admin_id;

// start to add entry in payment
  
    $data = array();
    if ($arr_fees_data["pt_tran_amount"] == '') $arr_fees_data["pt_tran_amount"] = 0;
    $arr_fees_data["pt_receipt_no"] = get_receipt_no($tmp_admin_id,'');
    $data["pt_type"] = $arr_fees_data["pt_type"];
    $data["pt_ac_id"] = isset($arr_fees_data["pt_ac_id"])?$arr_fees_data["pt_ac_id"]:0;
    $data["pt_tran_u_type"] = $arr_fees_data["pt_tran_u_type"];
    $data["pt_tran_bank"] = $arr_fees_data["pt_tran_bank"];
    $data["pt_tran_mode_of_payent"] = $arr_fees_data["pt_tran_mode_of_payent"];
    $data["pt_tran_no"] = $arr_fees_data["pt_tran_no"];
    $data["pt_tran_amount"] = $arr_fees_data["pt_tran_amount"];
    $data["pt_receipt_no"] = $arr_fees_data["pt_receipt_no"];
    $data["pt_br_id"] = $arr_fees_data["pt_br_id"];
    $data["pt_stu_id"] = $arr_fees_data["pt_stu_id"];

    $data["pt_tran_date"] = $arr_fees_data["pt_tran_date"];
    $data["pt_tran_remarks"] = $arr_fees_data["pt_tran_remarks"];
    $data["pt_sc_id"] = $arr_fees_data["sc_id"];

    $data["pt_create_date"] = $cur_date;
    $data["pt_create_by_id"] = $tmp_admin_id;

    $data["pt_update_date"] = $cur_date;
    $data["pt_update_by_id"] = $tmp_admin_id;

    $add_fees_result = db_perform("sm_payment_transaction", $data, 'insert', '', '', '');

    if ($add_fees_result["status"] == 'failure') {
        return $add_fees_result["errormsg"];
    } else {
        $q_update = "UPDATE sm_exam_student_entrolled SET exs_update_date = '" . $cur_date . "',exs_update_by_id = '" . $tmp_admin_id . "', exs_paid = 1 , exs_transction_no = '" . $arr_fees_data["pt_tran_no"] . "', exs_transtion_type = '" . $data["pt_tran_u_type"] . "' WHERE exs_id = " . $arr_fees_data['sc_id'];
        $udpate_result = m_process("update", $q_update);
        if ($udpate_result["status"] == "failure") {
            return $udpate_result["errormsg"];
        } else {
            return "";
        }
    }
    return '';
}

// if function has any error then this function will return error else return blnak value.
function update_fee_student_to_course($sc_id) {
// start to add entry in payment
    if ($sc_id > 0) {
        $q_update_amount = "UPDATE sm_student_course LEFT JOIN (SELECT SUM(pt_tran_amount) sum_amt,SUM(pt_discount_amount) sum_discount_amt ,pt_sc_id FROM sm_payment_transaction GROUP BY pt_sc_id) spt ON (spt.pt_sc_id = sm_student_course.sc_id) SET sc_total_paid = (CASE WHEN spt.sum_amt IS NULL THEN 0 ELSE spt.sum_amt END),sc_discount_amount = (CASE WHEN spt.sum_discount_amt IS NULL THEN 0 ELSE spt.sum_discount_amt END) WHERE sc_id = $sc_id ";
        $r_update_amount = m_process("update", $q_update_amount);
        if ($r_update_amount["status"] == "failure") {
            return $r_update_amount["erromsg"];
        } else {
            $q_update1 = "UPDATE sm_student_course SET sc_full_fee_paid = 'N' WHERE sc_total_fee != (sc_total_paid+sc_discount_amount) AND sc_id = " . $sc_id;
            $udpate_result1 = m_process("update", $q_update1);
            if ($udpate_result1["status"] == "failure") {
                return $udpate_result1["errormsg"];
            } else {
                return "";
            }
        }
        return "";
    } else
        return "";
}

// action = add for adding new and edit = editing for existing
function allocation_deallocation_exam_categories($action, $be_id, $exam_br_id, $data, $datao) {
    $arr_response = array("errormsg" => "", "id" => 0);
    if ($action == 'add') {
        $cateallo_q = 'INSERT INTO sm_exam_categories_allocation(eca_be_id, eca_exc_id, eca_total_marks, eca_obtain_marks, eca_br_id, eca_create_date, eca_create_by_id, eca_update_date, eca_update_by_id) VALUES (' . $be_id . ',' . $data["eca_exc_id"] . ',' . $data["eca_total_marks"] . ',' . $data["eca_obtain_marks"] . ',' . $exam_br_id . ',"' . $datao["eca_create_date"] . '",' . $datao["eca_create_by_id"] . ',"' . $datao["eca_update_date"] . '",' . $datao["eca_update_by_id"] . ')';
        $cateallo_r = m_process("insert", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            $arr_response["errormsg"] = $cateallo_r["errormsg"];
            return $arr_response;
        }
    }
    if ($action == 'update') {

        $cateallocheck_q = 'SELECT eca_id FROM sm_exam_categories_allocation WHERE eca_be_id =  ' . $be_id . ' AND eca_exc_id = ' . $data["eca_exc_id"];
        $cateallocheck_r = m_process("get_data", $cateallocheck_q);
        if ($cateallocheck_r["status"] == 'error') {
            $arr_response["errormsg"] = $cateallocheck_r["errormsg"];
            return $arr_response;
        } else if ($cateallocheck_r["count"] == 0) {
            $cateallo_q = 'INSERT INTO sm_exam_categories_allocation(eca_be_id, eca_exc_id, eca_total_marks, eca_obtain_marks, eca_br_id, eca_create_date, eca_create_by_id, eca_update_date, eca_update_by_id) VALUES (' . $be_id . ',' . $data["eca_exc_id"] . ',' . $data["eca_total_marks"] . ',' . $data["eca_obtain_marks"] . ',' . $exam_br_id . ',"' . $datao["eca_create_date"] . '",' . $datao["eca_create_by_id"] . ',"' . $datao["eca_update_date"] . '",' . $datao["eca_update_by_id"] . ')';
            $cateallo_r = m_process("insert", $cateallo_q);
            if ($cateallo_r["status"] == 'error') {
                $arr_response["errormsg"] = $cateallo_r["errormsg"];
                return $arr_response;
            } else {
                $arr_response["id"] = $cateallo_r["id"];
                return $arr_response;
            }
        } else {
            $eca_id = $cateallocheck_r["res"][0]["eca_id"];
            $cateallou_q = 'UPDATE sm_exam_categories_allocation SET eca_be_id=' . $be_id . ' , eca_exc_id=' . $data["eca_exc_id"] . ' , eca_total_marks = ' . $data["eca_total_marks"] . ' , eca_obtain_marks = ' . $data["eca_obtain_marks"] . ', eca_br_id = ' . $exam_br_id . ' , eca_create_date = "' . $datao["eca_create_date"] . '" , eca_create_by_id = ' . $datao["eca_create_by_id"] . ' , eca_update_date = "' . $datao["eca_update_date"] . '" , eca_update_by_id= ' . $datao["eca_update_by_id"] . ' WHERE eca_id =   ' . $eca_id;
            $cateallou_r = m_process("update", $cateallou_q);
            if ($cateallou_r["status"] == 'error') {
                $arr_response["errormsg"] = $cateallou_r["errormsg"];
                return $arr_response;
            } else {
                $arr_response["id"] = $eca_id;
                return $arr_response;
            }
        }
    }

    return $arr_response;
}

function remove_deallocation_exam_categories($none_removal_ids, $eca_be_id) {

    $none_removal_ids = trim($none_removal_ids);
    $none_removal_ids = str_replace(",,", ",", $none_removal_ids);

    // echo "**".$none_removal_ids."**".$eca_ex_id; exit(0);
    if ($none_removal_ids != '') {
        $cateallo_q = 'DELETE FROM sm_exam_categories_allocation WHERE eca_be_id = ' . $eca_be_id . ' AND eca_id NOT IN (' . $none_removal_ids . '0) ';

        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }
    }
    return "";
}

function remove_enroll_student($none_removal_ids, $ex_id) {

    $none_removal_ids = trim($none_removal_ids);
    $none_removal_ids = str_replace(",,", ",", $none_removal_ids);

    if ($none_removal_ids != '') {
        $cateallo_q = 'DELETE FROM sm_exam_student_entrolled WHERE exs_ex_id = ' . $ex_id . ' AND exs_id NOT IN (' . $none_removal_ids . '0) ';

        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }

        // removing from result categories table too
        $cateallo_q = 'DELETE FROM sm_exam_result WHERE exre_ex_id = ' . $ex_id . ' AND exre_exs_id NOT IN (' . $none_removal_ids . '0) ';

        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }
    }
    else
    {
        $cateallo_q = 'DELETE FROM sm_exam_student_entrolled WHERE exs_ex_id = ' . $ex_id ;

        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }

        $cateallo_q = 'DELETE FROM sm_exam_result WHERE exre_ex_id = ' . $ex_id;

        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }
    }
    return "";
}
/*
function exam_student_entrolled($exs_ex_id, $exs_stu_id, $arr_data) {

    if ($none_removal_ids != '') {
        $cateallo_q = 'INSERT INTO sm_exam_student_entrolled( exs_ex_id, exs_stu_id, exs_fee, exs_paid, exs_attended, exs_transtion_type, exs_transction_no, exs_create_date, exs_create_by_id, exs_update_date, exs_update_by_id) VALUES (exs_ex_id, exs_stu_id, exs_fee, exs_paid, exs_attended, exs_transtion_type, exs_transction_no, exs_create_date, exs_create_by_id, exs_update_date, exs_update_by_id)';

        $cateallo_r = m_process("insert", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }
    }
    return "";
}
*/
// start function to entroll in result
function insert_exam_result_categories_to_student($ex_id, $stu_id, $exs_ids) {

    $arr_response = array("errormsg" => "", "id" => 0);

    global $cur_date, $tmp_admin_id;

    $cateallo_q = 'INSERT INTO sm_exam_result (exre_ex_id, exre_exs_id, exre_eca_exc_id, exre_total_marks, exre_br_id,exre_stu_id, exre_total_marks_obtain, exre_pass,  exre_create_date, exre_create_by_id, exre_update_date, exre_update_by_id)
 SELECT DISTINCT ex_id,exs_id,eca_exc_id, eca_total_marks, stu_br_id, stu_id, 0, "", "' . $cur_date . '",' . $tmp_admin_id . ', "' . $cur_date . '",' . $tmp_admin_id . '
FROM  sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id AND sc_is_current =1)
INNER JOIN sm_belt ON (sc_be_id = be_id )
INNER JOIN sm_exam ON (ex_br_id  = stu_br_id)
INNER JOIN sm_exam_student_entrolled ON (stu_id = exs_stu_id AND exs_ex_id  = ' . $ex_id . ')
INNER JOIN sm_exam_categories_allocation ON (be_id =  eca_be_id)
WHERE stu_br_id= ' . $tmp_admin_id . ' AND ex_id = ' . $ex_id;
 if(!empty($exs_ids)) {
    $cateallo_q = ' AND exs_id IN (' . $exs_ids . ')';
}
// stu_br_id= '.$tmp_admin_id.' AND stu_id = '.$stu_id.'  And ex_id = '.$ex_id;
     add_log_txt($cateallo_q);
    $cateallo_r = m_process("insert", $cateallo_q);
    if ($cateallo_r["status"] == 'error') {
        $arr_response["errormsg"] = $cateallo_r["errormsg"];
        return $arr_response;
    }
}

// end function to entroll in result

function validate_fees_paid_or_not($exa_id, $stu_id, $exs_co_id, $exs_be_id) {

    if ($exs_co_id =='' || $exs_be_id == '' || $exs_co_id ==0 || $exs_be_id == 0 )
        return false;

    /*
    -- Old queries
    $q_check = "SELECT DISTINCT exs_id
        FROM  sm_exam_student_entrolled
        INNER JOIN sm_student_course ON (sc_stu_id = exs_stu_id AND sc_be_id =exs_be_id AND sc_co_id = exs_co_id )
        WHERE exs_paid = 1   AND exs_co_id  = " . $exs_co_id . "   AND exs_be_id  = " . $exs_be_id . "  AND exs_stu_id  = " . $stu_id . " AND exs_result_status IN ('A','F') ";
    */
    $q_check = "SELECT DISTINCT exs_id
        FROM  sm_exam_student_entrolled
        INNER JOIN sm_student_course ON (sc_stu_id = exs_stu_id AND sc_be_id =exs_be_id AND sc_co_id = exs_co_id )
        WHERE exs_co_id  = " . $exs_co_id . "   AND exs_be_id  = " . $exs_be_id . "  AND exs_stu_id  = " . $stu_id . " AND exs_result_status IN ('A','F') ";
    $r_check = m_process("get_data", $q_check);
    if ($r_check["status"] == 'error') {
        return false;
    } else if ($r_check["count"] == 0) {
        return false;
    }
    return true;
}

function get_student_current_course_details($stu_id) {
    $arr_response = array("errormsg" => "", "co_id" => 0, "be_id" => 0);
    $q_check = "SELECT sc_be_id, sc_co_id  FROM  sm_student_course WHERE sc_is_current  = 1   AND  sc_stu_id  = " . $stu_id;
    $r_check = m_process("get_data", $q_check);
    if ($r_check["status"] == 'error') {
        $arr_response["errormsg"] = $r_check["errormsg"];
    } else if ($r_check["count"] > 0) {
        $arr_response["be_id"] = $r_check["res"][0]["sc_be_id"];
        $arr_response["co_id"] = $r_check["res"][0]["sc_co_id"];
    }
    return $arr_response;
}

function enroll_student($exa_id, $stu_id) {
    global $exs_ids;
    $arr_response = array("errormsg" => "", "id" => 0);

    $cateallocheck_q = 'SELECT exs_id FROM sm_exam_student_entrolled WHERE exs_ex_id =  ' . $exa_id . ' AND exs_stu_id = ' . $stu_id;
    $cateallocheck_r = m_process("get_data", $cateallocheck_q);
   
    if ($cateallocheck_r["status"] == 'error') {
        $arr_response["errormsg"] = $cateallocheck_r["errormsg"];
        return $arr_response;
    } else if ($cateallocheck_r["count"] == 0) {

        global $cur_date, $tmp_admin_id;
        $exs_paid = 0;
        $exs_already_paid = 'N';
        // need to find the be id and co id for current course for this 
        $check_get_course_belt = get_student_current_course_details($stu_id);
         add_log_txt(json_encode($check_get_course_belt));
        if ($check_get_course_belt["errormsg"] != "") {
            $arr_response["errormsg"] = $check_get_course_belt["errormsg"];
        } else {

            if (validate_fees_paid_or_not($exa_id, $stu_id, $check_get_course_belt["co_id"], $check_get_course_belt["be_id"]) == true) {
                $exs_paid = 1;
                $exs_already_paid = 'Y';
            }
            $cateallo_q = 'INSERT INTO sm_exam_student_entrolled
(exs_already_paid, exs_paid , exs_ex_id, exs_stu_id, 	exs_be_id,  	exs_co_id, exs_total_marks, exs_eca_exc_ids, exs_fee, exs_create_date, exs_create_by_id, exs_update_date, exs_update_by_id)
SELECT "' . $exs_already_paid . '",' . $exs_paid . ', ex_id, stu_id, sc_be_id, sc_co_id, eca_total_marks as exs_total_marks, M.exs_eca_exc_ids, be.be_belt_exam_fee, "' . $cur_date . '",' . $tmp_admin_id . ', "' . $cur_date . '",' . $tmp_admin_id . '
FROM  sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id AND sc_is_current =1)
INNER JOIN sm_belt be ON (sc_be_id = be.be_id )
LEFT JOIN sm_exam ON (ex_br_id  = stu_br_id)
LEFT JOIN sm_exam_student_entrolled ON (sc_stu_id = exs_stu_id AND exs_ex_id  = ' . $exa_id . ')
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id , GROUP_CONCAT(eca_id) AS exs_eca_exc_ids
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_id =  eca_be_id)
GROUP BY be_id ) as M  ON (sc_be_id = M.be_id)
WHERE
stu_br_id= ' . $tmp_admin_id . '
AND stu_id = ' . $stu_id . '  And ex_id =
' . $exa_id;
            add_log_txt($cateallo_q);

            //$cateallo_q = 'INSERT INTO sm_exam_categories_allocation(eca_be_id, eca_exc_id, eca_total_marks, eca_obtain_marks, eca_br_id, eca_create_date, eca_create_by_id, eca_update_date, eca_update_by_id) VALUES ('.$be_id.','.$data["eca_exc_id"].','.$data["eca_total_marks"].','.$data["eca_obtain_marks"].','.$exam_br_id.',"'.$datao["eca_create_date"].'",'.$datao["eca_create_by_id"].',"'.$datao["eca_update_date"].'",'.$datao["eca_update_by_id"].')';
            $cateallo_r = m_process("insert", $cateallo_q);
            if ($cateallo_r["status"] == 'error') {
                $arr_response["errormsg"] = $cateallo_r["errormsg"];
                return $arr_response;
            } else {
                $exs_ids .= $cateallo_r["id"] . ",";
                $arr_response["id"] = $cateallo_r["id"];
                // $arr_exam_student_result_data = array("exre_ex_id"=>$exa_id, "exre_exs_id"=>$arr_response["id"]);
                //  $arr_exam_student_result = insert_exam_result_categories_to_student($exa_id,$stu_id);
                return $arr_response;
            }
        }
    } else {
        $arr_response["id"] = $cateallocheck_r["res"][0]["exs_id"];
        return $arr_response;
        // no updation code for now so commenting following code
        /*
          $eca_id = $cateallocheck_r["res"][0]["eca_id"];
          $cateallou_q = 'UPDATE sm_exam_student_entrolled SET eca_be_id='.$be_id.' , eca_exc_id='.$data["eca_exc_id"].' , eca_total_marks = '.$data["eca_total_marks"].' , eca_obtain_marks = '.$data["eca_obtain_marks"].', eca_br_id = '.$exam_br_id.' , eca_create_date = "'.$datao["eca_create_date"].'" , eca_create_by_id = '.$datao["eca_create_by_id"].' , eca_update_date = "'.$datao["eca_update_date"].'" , eca_update_by_id= '.$datao["eca_update_by_id"].' WHERE eca_id =   '.$eca_id;
          $cateallou_r =  m_process("update",$cateallou_q);
          if ($cateallou_r["status"] == 'error')
          {
          $arr_response["errormsg"] = $cateallou_r["errormsg"];
          return $arr_response ;
          }
          else {
          $arr_response["id"] = $eca_id;
          return $arr_response ;
          }
         */
        // end of commenting code
    }
}

function get_exam_details($ex_id) {
    $arr_response = array("errormsg" => "", "data" => "");
    $cateallocheck_q = 'SELECT * FROM sm_exam WHERE ex_id =  ' . $ex_id;
    $cateallocheck_r = m_process("get_data", $cateallocheck_q);
    if ($cateallocheck_r["status"] == 'error') {
        $arr_response["errormsg"] = $cateallocheck_r["errormsg"];
    } else if ($cateallocheck_r["count"] == 0) {
        $arr_response["errormsg"] = "no details found";
    } else {
        $arr_response["data"] = $cateallocheck_r["res"][0];
    }
    return $arr_response;
}

function add_one_month_fee_to_student($stu_id, $sc_remarks) {
    // here we had to code that half_belt = 1 remove it may the condition was added because of need to consider 30 days for the next
    $cateallocheck_q = 'UPDATE sm_student_course INNER JOIN sm_branch_type ON (sc_brt_id = brt_id) SET sc_additional_days = (sc_additional_days+' . ADD_ABSENT_OR_FAIL_DAYS . ') , sc_total_fee = (sc_total_fee+brt_amount_month), sc_remarks  = CONCAT(sc_remarks,"' . $sc_remarks . '") ,  sc_full_fee_paid = "N" WHERE sc_is_current = 1 AND sc_stu_id = ' . $stu_id;
    $cateallocheck_r = m_process("update", $cateallocheck_q);
    if ($cateallocheck_r["status"] == 'error') {
        return $cateallocheck_r["errormsg"];
    }
    return "";
}

/*
  $arr_log = array();
  $arr_log["log_message"]= "";
  $arr_log["log_stu_id"]= "";
  $arr_log["log_admin_id"]= "";
  $arr_log["log_action"]= "";

 */

function add_log($arr_log) {
    global $cur_date_only_db,$cur_date;
    $arr_log["log_other"] = "";
    if (!(isset($arr_log["log_course_change_date"]) && $arr_log["log_course_change_date"] != ''))
    {
        $arr_log["log_course_change_date"] =$cur_date_only_db;
    }
    $arr_log["log_course_change_date"] = isset($arr_log["log_course_change_date"])?$arr_log["log_course_change_date"]:null;
    $log_q = "INSERT INTO sm_log(log_message, log_stu_id, log_admin_id, log_action, log_other, log_date,log_course_change_date) VALUES ('" . $arr_log["log_message"] . "'," . $arr_log["log_stu_id"] . "," . $arr_log["log_admin_id"] . ",'" . $arr_log["log_action"] . "','','" . $cur_date_only_db . "','" . $arr_log["log_course_change_date"] . "')";
    $log_r = m_process("insert", $log_q);
    if ($log_r["status"] == 'error') {
        return $log_r["errormsg"];
    }
}

// $arr_data["sta_id"] = $process_id_arr_ids[0];
// $arr_data["sta_att_date"] = disptoDB($sta_att_date);
// $arr_data["sc_brt_id"] = $process_id_arr_ids[1];
// $arr_data["sc_co_id"] = $process_id_arr_ids[2];
// $arr_data["sc_be_id"] = $process_id_arr_ids[3];
// $arr_data["sc_id"] = $process_id_arr_ids[4];
// $arr_data["sc_stu_id"] = $process_id_arr_ids[5];
// $arr_data["stu_br_id"] = $process_id_arr_ids[6];
// $arr_data["sta_att_status"] = $process_id_arr_ids[6];
function add_update_attendance($arr_data) {
    global $tmp_admin_id;
    if ($arr_data["sta_id"] != 0) {
        // update code will be here
        $cateallocheck_q = 'UPDATE sm_student_attendance
      SET sta_att_status = "' . $arr_data["sta_att_status"] . '", sta_update_date= now(), sta_update_by_id = ' . $tmp_admin_id . ' WHERE sta_id = ' . $arr_data["sta_id"];
        $cateallocheck_r = m_process("update", $cateallocheck_q);
        if ($cateallocheck_r["status"] == 'error') {
            return $cateallocheck_r["errormsg"];
        }
    } else {
        $cateallocheck_q = 'INSERT INTO sm_student_attendance(sta_stu_id, sta_att_date, sta_att_status, sta_br_id, sta_co_id, sta_brt_id, sta_be_id, sta_create_date, sta_create_by_id, sta_update_date, sta_update_by_id)
      VALUES (' . $arr_data["sta_stu_id"] . ',"' . $arr_data["sta_att_date"] . '", "' . $arr_data["sta_att_status"] . '",' . $arr_data["sta_br_id"] . ',' . $arr_data["sta_co_id"] . ',' . $arr_data["sta_brt_id"] . ',' . $arr_data["sta_be_id"] . ',now(), ' . $tmp_admin_id . ',now(), ' . $tmp_admin_id . ') ';
        $cateallocheck_r = m_process("insert", $cateallocheck_q);
        if ($cateallocheck_r["status"] == 'error') {
            return $cateallocheck_r["errormsg"];
        }
    }
}

// $arr_data["fta_id"] = $process_id_arr_ids[0];
// $arr_data["fta_fa_id"] = $process_id_arr_ids[5];
// $arr_data["stu_br_id"] = $process_id_arr_ids[6];
// $arr_data["sta_att_status"] = $process_id_arr_ids[6];
function add_update_attendance_faculty($arr_data) {
    global $tmp_admin_id;
    if ($arr_data["fta_id"] != 0) {
        // update code will be here
        $cateallocheck_q = 'UPDATE sm_faculty_attendance
      SET fta_att_status = "' . $arr_data["fta_att_status"] . '", fta_update_date= now(), fta_update_by_id = ' . $tmp_admin_id . ' WHERE fta_id = ' . $arr_data["fta_id"];
        $cateallocheck_r = m_process("update", $cateallocheck_q);
        if ($cateallocheck_r["status"] == 'error') {
            return $cateallocheck_r["errormsg"];
        }
    } else {
        $cateallocheck_q = 'INSERT INTO sm_faculty_attendance(fta_fac_id, fta_att_date, fta_att_status, fta_br_id, fta_co_id, fta_brt_id, fta_be_id, fta_create_date, fta_create_by_id, fta_update_date, fta_update_by_id)
      VALUES (' . $arr_data["fta_fac_id"] . ',"' . $arr_data["fta_att_date"] . '", "' . $arr_data["fta_att_status"] . '",' . $arr_data["fta_br_id"] . ',' . $arr_data["fta_co_id"] . ',' . $arr_data["fta_brt_id"] . ',' . $arr_data["fta_be_id"] . ',now(), ' . $tmp_admin_id . ',now(), ' . $tmp_admin_id . ') ';
        $cateallocheck_r = m_process("insert", $cateallocheck_q);
        if ($cateallocheck_r["status"] == 'error') {
            return $cateallocheck_r["errormsg"];
        }
    }
}

function getIndianCurrency($number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else
            $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

function check_duplicate_record_in_db($query) {
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['duplicate'] = false;
    $result = m_process("get_data", $query);

    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            $arr_retun['duplicate'] = true;
        } else {
            $arr_retun['duplicate'] = false;
        }
    }
    return $arr_retun;
}

function get_student_lastet_course($stu_id) {
    $arr_return = array("error_message" => "", "co_name" => "", "be_name" => "", "brt_name" => "");
    $q = "SELECT co_name, be_name,be_name_for, brt_name,  sc_co_id, 	sc_half_course, sc_be_id, sc_brt_id FROM sm_student_course INNER JOIN sm_belt ON (sc_be_id = be_id) INNER JOIN sm_course ON (sc_co_id = co_id) INNER JOIN  	sm_branch_type ON (sc_brt_id = brt_id) WHERE sc_stu_id = $stu_id ORDER BY be_sort_order DESC LIMIT 0,1";
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $arr_return['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            $arr_return['co_name'] = $result["res"][0]["co_name"];
            $arr_return['be_name'] = $result["res"][0]["be_name"];
            if ($result["res"][0]["sc_half_course"] == 1)
                $arr_return['be_name'] = $result["res"][0]["be_name_for"];
            $arr_return['brt_name'] = $result["res"][0]["brt_name"];
        }
    }
    return $arr_return;
}

function check_rights_main_branch() {
    global $c_file, $tmp_admin_id, $arr_only_main_branch_files;

    if ($tmp_admin_id != 1 && in_array($c_file, $arr_only_main_branch_files)) {
        header("location:index.php");
        exit(0);
    }
}

function enroll_student_event($eva_id, $stu_id, $evs_stu_or_other) {
    global $evs_ids;
    $arr_response = array("errormsg" => "", "id" => 0);
// $stu_or_other
    $cateallocheck_q = 'SELECT evs_id, evs_paid  FROM sm_event_student_entrolled WHERE evs_ev_id =  ' . $eva_id . ' AND evs_stu_id = ' . $stu_id . ' AND evs_stu_or_other = "' . $evs_stu_or_other . '"';
    $cateallocheck_r = m_process("get_data", $cateallocheck_q);
    if ($cateallocheck_r["status"] == 'error') {
        $arr_response["errormsg"] = $cateallocheck_r["errormsg"];
        return $arr_response;
    } else if ($cateallocheck_r["count"] == 0) {
        global $cur_date, $tmp_admin_id;
        $evs_already_paid = 'N';
        $evs_paid = 0;
        if ($evs_stu_or_other == 'other') {
            $cateallo_q = 'INSERT INTO sm_event_student_entrolled
        (evs_stu_or_other,evs_already_paid,evs_paid , evs_ev_id, evs_stu_id, evs_be_id, evs_co_id, evs_total_marks, evs_eca_exc_ids, evs_fee, evs_create_date, evs_create_by_id, evs_update_date, evs_update_by_id )
        SELECT "' . $evs_stu_or_other . '","' . $evs_already_paid . '",' . $evs_paid . ', ev_id, stu_id, 0, 0, 0, 0, ev_nu_exam_fee, "' . $cur_date . '",' . $tmp_admin_id . ', "' . $cur_date . '",' . $tmp_admin_id . '
        FROM  sm_student_other  LEFT JOIN sm_event ON (ev_br_id  = stu_br_id) WHERE
        stu_br_id= ' . $tmp_admin_id . '
        AND stu_id = ' . $stu_id . '  AND ev_id =' . $eva_id . ' LIMIT 0,1';

            $cateallo_r = m_process("insert", $cateallo_q);
            if ($cateallo_r["status"] == 'error') {
                $arr_response["errormsg"] = $cateallo_r["errormsg"];
                return $arr_response;
            } else {
                $evs_ids .= $cateallo_r["id"] . ",";
                $arr_response["id"] = $cateallo_r["id"];
                return $arr_response;
            }
        } else if ($evs_stu_or_other == 'student') {

            $cateallo_q = 'INSERT INTO sm_event_student_entrolled
            (evs_stu_or_other,evs_already_paid,evs_paid , evs_ev_id, evs_stu_id, evs_be_id, evs_co_id, evs_total_marks, evs_eca_exc_ids, evs_fee, evs_create_date, evs_create_by_id, evs_update_date, evs_update_by_id )
            SELECT "' . $evs_stu_or_other . '","' . $evs_already_paid . '",' . $evs_paid . ', ev_id, stu_id, sc_be_id, sc_co_id, 0, 0, ev_eu_exam_fee, "' . $cur_date . '",' . $tmp_admin_id . ', "' . $cur_date . '",' . $tmp_admin_id . '
            FROM  sm_student
            INNER JOIN sm_student_course
            ON (sc_stu_id = stu_id)
            INNER JOIN sm_belt be ON (sc_be_id = be.be_id )
            LEFT JOIN sm_event ON (ev_br_id  = stu_br_id)
            WHERE
            stu_br_id= ' . $tmp_admin_id . '
            AND stu_id = ' . $stu_id . '  AND ev_id =' . $eva_id . ' LIMIT 0,1';
           
            add_log_txt($cateallo_q);
            $cateallo_r = m_process("insert", $cateallo_q);
            if ($cateallo_r["status"] == 'error') {
                $arr_response["errormsg"] = $cateallo_r["errormsg"];
                return $arr_response;
            } else {
                $evs_ids .= $cateallo_r["id"] . ",";
                $arr_response["id"] = $cateallo_r["id"];
                return $arr_response;
            }
        }
    } else {
        $arr_response["id"] = $cateallocheck_r["res"][0]["evs_id"];
        return $arr_response;
    }
}

function get_event_details($ev_id) {
    $arr_response = array("errormsg" => "", "data" => "");
    $cateallocheck_q = 'SELECT * FROM sm_event WHERE ev_id =  ' . $ev_id;
    $cateallocheck_r = m_process("get_data", $cateallocheck_q);
    if ($cateallocheck_r["status"] == 'error') {
        $arr_response["errormsg"] = $cateallocheck_r["errormsg"];
    } else if ($cateallocheck_r["count"] == 0) {
        $arr_response["errormsg"] = "no details found";
    } else {
        $arr_response["data"] = $cateallocheck_r["res"][0];
    }
    return $arr_response;
}

function remove_enroll_student_event($none_removal_ids, $ev_id, $stu_or_other, $sigle_only = false, $evs_stu_id = 0,$list_to_process="") {
    $list_to_process = trim($list_to_process,",");

    $none_removal_ids = trim($none_removal_ids);
    $none_removal_ids = str_replace(",,", ",", $none_removal_ids);

    if ($none_removal_ids != '') {
        if ($sigle_only == false)
            $cateallo_q = 'DELETE FROM sm_event_student_entrolled WHERE evs_stu_or_other = "' . $stu_or_other . '" AND evs_ev_id = ' . $ev_id . ' AND evs_id NOT IN (' . $none_removal_ids . '0) AND evs_id IN ('.$list_to_process.')';
        else
            $cateallo_q = 'DELETE FROM sm_event_student_entrolled WHERE evs_stu_or_other = "' . $stu_or_other . '" AND evs_ev_id = ' . $ev_id . ' AND evs_stu_id =' . $evs_stu_id.' AND evs_id IN ('.$list_to_process.')';

        add_log_txt($cateallo_q);
        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }
        else
        {
            // need to remove from exam result tables too.
        }
    }
    return "";
}

// if function has any error then this function will return error else return blnak value.
function pay_fee_student_event($arr_fees_data) {
    global $cur_date, $tmp_admin_id;

// start to add entry in payment
    
    $data = array();
    if($arr_fees_data["pt_tran_amount"] == '') $arr_fees_data["pt_tran_amount"] = 0;
    if ($arr_fees_data["pt_discount_amount"] == '') $arr_fees_data["pt_discount_amount"] = 0;

    $arr_fees_data["pt_receipt_no"] = get_receipt_no($tmp_admin_id,'');
    $data["pt_type"] = $arr_fees_data["pt_type"];
    $data["pt_ac_id"] = isset($arr_fees_data["pt_ac_id"])?$arr_fees_data["pt_ac_id"]:0;
    $data["pt_tran_u_type"] = $arr_fees_data["pt_tran_u_type"];
    $data["pt_tran_bank"] = $arr_fees_data["pt_tran_bank"];
    $data["pt_tran_mode_of_payent"] = $arr_fees_data["pt_tran_mode_of_payent"];
    $data["pt_tran_no"] = $arr_fees_data["pt_tran_no"];
    $data["pt_tran_amount"] = $arr_fees_data["pt_tran_amount"];
    $data["pt_receipt_no"] = $arr_fees_data["pt_receipt_no"];
    $data["pt_stu_id"] = $arr_fees_data["pt_stu_id"];
    $data["pt_discount_amount"] = $arr_fees_data["pt_discount_amount"];
    $data["pt_tran_date"] = $arr_fees_data["pt_tran_date"];
    $data["pt_tran_remarks"] = $arr_fees_data["pt_tran_remarks"];
    $data["pt_sc_id"] = $arr_fees_data["sc_id"];
    $data["pt_br_id"] = $arr_fees_data["pt_br_id"];
    $data["pt_create_date"] = $cur_date;
    $data["pt_create_by_id"] = $tmp_admin_id;

    $data["pt_update_date"] = $cur_date;
    $data["pt_update_by_id"] = $tmp_admin_id;

    $add_fees_result = db_perform("sm_payment_transaction", $data, 'insert', '', '', '');

    if ($add_fees_result["status"] == 'failure') {
        return $add_fees_result["errormsg"];
    } else {
        $d_evs_stu_or_other = $arr_fees_data['d_evs_stu_or_other'];
        $q_update = "UPDATE sm_event_student_entrolled ese
        INNER JOIN (SELECT evs_id, SUM(pt_discount_amount) as evs_discount_amount , SUM(pt_tran_amount) as evs_total_paid FROM sm_event_student_entrolled INNER JOIN sm_payment_transaction ON (evs_id=pt_sc_id) WHERE evs_id = " . $arr_fees_data['evs_id'] ." AND pt_tran_u_type='Event fee[".$d_evs_stu_or_other."]' GROUP BY pt_sc_id, pt_tran_u_type) as M 
        ON (ese.evs_id = M.evs_id)
        SET ese.evs_discount_amount = M.evs_discount_amount , ese.evs_total_paid = M.evs_total_paid 
        WHERE ese.evs_id  = " . $arr_fees_data['evs_id'] ." AND  M.evs_id =  " . $arr_fees_data['evs_id'] ;
        
        //echo $q_update;
        $udpate_result = m_process("update", $q_update);
        if ($udpate_result["status"] == "failure") {
            return $udpate_result["errormsg"];
        } else {
            $q_update1 = "UPDATE sm_event_student_entrolled SET evs_paid = 1 WHERE evs_fee = (evs_total_paid+evs_discount_amount) AND evs_id = " . $arr_fees_data['sc_id'];
            $udpate_result1 = m_process("update", $q_update1);
            if ($udpate_result1["status"] == "failure") {
                return $udpate_result1["errormsg"];
            } else {
                return "";
            }
        }

    }
    return '';
}

function get_products_invoice() {
    global $tmp_admin_id;
    $q = "SELECT pro_id, pro_name, pro_model,  	pro_gst, pro_qty, pro_price, pro_sale_price,pro_warranty_terms , pro_condition,pro_warranty_provider FROM sm_products  WHERE pro_status = 1 AND pro_admin_id = " . $tmp_admin_id . " ORDER BY pro_name ";

    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['id'] = 0;
    $arr_retun['return_value'] = '';
    $return_value = '';
    echo '<script type="text/javascript">';
    echo 'var arr_product = new Array();';

    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $return_value .= '<option value="' . $db_row["pro_id"] . '">' . $db_row["pro_name"] . '</option>';

                echo 'arr_product[' . $db_row["pro_id"] . ']= new Array();';
                echo 'arr_product[' . $db_row["pro_id"] . '][0] = ' . $db_row["pro_qty"] . ';';
                echo 'arr_product[' . $db_row["pro_id"] . '][1] = ' . $db_row["pro_price"] . ';';
                echo 'arr_product[' . $db_row["pro_id"] . '][2] = ' . $db_row["pro_gst"] . ';';
                //     echo 'arr_product[' . $db_row["pro_id"] . '][2] = "' . $db_row["pro_model"] . '";';
            }
        }
    }
    echo '</script>';
  
    $arr_retun['return_value'] = $return_value;

    return $arr_retun;
}

function get_products_options_invoice($data_arr_input) {
    global $tmp_admin_id;
    if (isset($data_arr_input["where"]) && $data_arr_input["where"] !='')
    { 
        $q = "SELECT po_id, po_name FROM sm_product_option  WHERE " . $data_arr_input["where"] . " ORDER BY po_name ";
    }
    else   
    $q = "SELECT po_id, po_name FROM sm_product_option WHERE  po_status = 'A' AND po_br_id = '.$tmp_admin_id ORDER BY po_name ";
    $arr_retun = array();
    $arr_retun['error_message'] = '';
    $arr_retun['id'] = 0;
    $arr_retun['return_value'] = '';
    $return_value = '';
    echo '<script type="text/javascript">';
    echo 'var arr_product_option = new Array();';

    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') {
        $arr_retun['error_message'] = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $return_value .= '<option value="' . $db_row["po_id"] . '">' . $db_row["po_name"] . '</option>';

                echo 'arr_product_option[' . $db_row["po_id"] . ']= new Array();';
                echo 'arr_product_option[' . $db_row["po_id"] . '][0] = ' . $db_row["po_id"] . ';';
                echo 'arr_product_option[' . $db_row["po_id"] . '][1] = "' . $db_row["po_name"] . '";';
                //     echo 'arr_product[' . $db_row["pro_id"] . '][2] = "' . $db_row["pro_model"] . '";';
            }
        }
    }
    echo '</script>';
    $arr_retun['return_value'] = $return_value;

    return $arr_retun;
}

function add_invoice_product($arr_data) {
    global $table_invoice_products;
    $arr_response = array("errormsg" => "", "id" => 0);
    $error_msg = "";

    //  invpro_id
    if ($arr_data["action"] == "add") {
        $q = "INSERT INTO $table_invoice_products (invpro_id_purchase,invpro_igst_amount,invpro_cgst_amount,invpro_sgst_amount, invpro_inv_id, invpro_pro_id, invpro_po_id,invpro_po_id_2, invpro_pro_qty, invpro_final_pro_price,invpro_final_price_tot,invpro_gst,invpro_used) VALUES (" . $arr_data["invpro_id_purchase"] . "," . $arr_data["invpro_igst_amount"] . "," . $arr_data["invpro_cgst_amount"] . "," . $arr_data["invpro_sgst_amount"] . "," . $arr_data["invpro_inv_id"] . "," . $arr_data["invpro_pro_id"] . "," . $arr_data["invpro_po_id"] . "," . $arr_data["invpro_po_id_2"] . "," . $arr_data["invpro_pro_qty"] . "," . $arr_data["invpro_final_pro_price"] . "," . $arr_data["invpro_final_price_tot"] . "," . $arr_data["invpro_gst"] . ",'" . $arr_data["invpro_used"]  . "')";
        $result = m_process("insert", $q);
    } else {
        $q = "UPDATE $table_invoice_products SET ";
        $q .= " invpro_id_purchase = " . $arr_data["invpro_id_purchase"];
        $q .= ", invpro_inv_id = " . $arr_data["invpro_inv_id"];
        $q .= ", invpro_pro_id = " . $arr_data["invpro_pro_id"];
        $q .= ", invpro_po_id = " . $arr_data["invpro_po_id"];
        $q .= ", invpro_po_id_2 = " . $arr_data["invpro_po_id_2"];
        $q .= ", invpro_pro_qty = " . $arr_data["invpro_pro_qty"];
        $q .= ", invpro_final_pro_price = " . $arr_data["invpro_final_pro_price"];
        $q .= ", invpro_final_price_tot = " . $arr_data["invpro_final_price_tot"];
        $q .= ", invpro_gst = " . $arr_data["invpro_gst"];
        $q .= ", invpro_cgst_amount = " . $arr_data["invpro_cgst_amount"];
        $q .= ", invpro_sgst_amount = " . $arr_data["invpro_sgst_amount"];
        $q .= ", invpro_igst_amount = " . $arr_data["invpro_igst_amount"];
        $q .= ", invpro_used = '" . $arr_data["invpro_used"]."'";
        $q .= " WHERE invpro_id = " . $arr_data["invpro_id"];

        $result = m_process("update", $q);
    }

    if ($result['errormsg'] != '')
        $arr_response["errormsg"] = $result['errormsg'];
    else {
        if ($arr_data["action"] == "edit") {
            $arr_response['id'] = $arr_data["invpro_id"];
        } else
            $arr_response['id'] = $result["id"];
    }
    return $arr_response;
}
function remove_invoice_product_details_all_clean()
{
    $u_q = 'UPDATE sm_invoice_products IP LEFT JOIN (SELECT SUM(IF(invpro_status = "Y", invpro_pro_qty, 0)) as invpro_pro_qty, invpro_id_purchase FROM sm_invoice_products_sale WHERE invpro_status = "Y" GROUP BY invpro_id_purchase) as M ON (IP.invpro_id = M.invpro_id_purchase) 
    SET IP.invpro_pro_qty_sold = 0
    WHERE M.invpro_id_purchase IS NULL';

    $u_r = m_process("delete", $u_q);
    if ($u_r["status"] == 'error') {
        return $u_r["errormsg"];
    }
}
function remove_product_add_comments($invpro_inv_id,$none_removal_ids)
{
    global $page_type,$table_invoice_products,$table_invoice,$tmp_admin_id,$cur_date;
                                                                                                                                                                                                                                                   
    $check0_q = "SELECT invpro_pro_qty, invpro_id, invpro_pro_qty_sold,invpro_pro_id,  pro_name, invpro_pro_qty_dead, invpro_pro_qty_return, po1.po_name option_1, po2.po_name option_2  FROM ".$table_invoice_products." INNER JOIN $table_invoice  ON (inv_id=invpro_inv_id ) INNER JOIN sm_product_option po1  ON (po1.po_id=invpro_po_id )  INNER JOIN sm_product_option po2  ON (po2.po_id=invpro_po_id_2 )  INNER JOIN sm_products ON (pro_id = invpro_pro_id ) WHERE invpro_inv_id = " . $invpro_inv_id . " AND invpro_id NOT IN (" . $none_removal_ids . ")";
    $check0_r = m_process("get_data", $check0_q);
    if ($check0_r["status"] == 'error') 
    {
        $arr_response["errormsg"] = $check0_r['errormsg'];
    } 
    else if ($check0_r["count"] > 0) 
    {
        foreach($check0_r["res"] as $check_db_row )
        {
            $invpro_pro_qty_sold = $check_db_row["invpro_pro_qty_sold"];
        
        // if ($model_return_qty > ($invpro_pro_qty - $invpro_pro_qty_sold))
        // {
        //     $arr_response["errormsg"] = "Invalid Qty To Process";
        // }
        // else
        // {
            $log_message = " Product: ". $check_db_row["pro_name"] .", Option 1: ". $check_db_row["option_1"] .", Option 2: ". $check_db_row["option_2"] . " has been deleted Qty: ".$check_db_row["invpro_pro_qty"];
            $arr_log = array();
            $arr_log["log_message"]= $log_message;
            $arr_log["log_stu_id"]= $invpro_inv_id;
            $arr_log["log_admin_id"]= $tmp_admin_id;
            $arr_log["log_course_change_date"]= $cur_date;
            $arr_log["log_action"]= "delete_product_qty_".$page_type;
            add_log($arr_log);
    //    }
    }
    }

}
function remove_invoice_product($none_removal_ids, $invpro_inv_id) {
    global $table_invoice_products,$tmp_admin_id;
    $none_removal_ids = trim($none_removal_ids);
    $none_removal_ids = str_replace(",,", ",", $none_removal_ids);

    // echo "**".$none_removal_ids."**".$eca_ex_id; exit(0);
    if ($none_removal_ids != '') {
        remove_product_add_comments($invpro_inv_id,$none_removal_ids.'0');
        $cateallo_q = 'DELETE FROM '.$table_invoice_products.' WHERE invpro_inv_id = ' . $invpro_inv_id . ' AND invpro_id NOT IN (' . $none_removal_ids . '0) ';
        
        $cateallo_r = m_process("delete", $cateallo_q);
        if ($cateallo_r["status"] == 'error') {
            return $cateallo_r["errormsg"];
        }
        else
        {
            remove_invoice_product_details_all_clean();
        }
    }
    return "";
}

function update_invoice_amount($arr_data) {
    global $table_invoice;
    
    $inv_total_amount = $arr_data["inv_total_amount"] + $arr_data["inv_additional_amount"] - $arr_data["inv_discount_amount"];
    $q = "UPDATE  $table_invoice SET inv_sgst_amount = " . $arr_data["inv_sgst_amount"] .",inv_cgst_amount = " . $arr_data["inv_cgst_amount"] ." , inv_total_amount = " . $arr_data["inv_total_amount"] . " , inv_additional_amount =  " . $arr_data["inv_additional_amount"] . " , inv_net_amount =  " . $inv_total_amount . " , inv_discount_amount =  " . $arr_data["inv_discount_amount"] . "   WHERE inv_id = " . $arr_data["inv_id"];
    $r = m_process("update", $q);
}

function delete_product_order($inv_id,$order_type) {
    return true;
    global $table_invoice_products;
    $q = "SELECT invpro_pro_id, invpro_pro_qty FROM $table_invoice_products WHERE invpro_inv_id = " . $inv_id;
    $r = m_process("get_data", $q);
    $arr_retun = array();
    if ($r['errormsg'] != '') {
        $arr_retun['error_message'] = $r['errormsg'];
    } else {
        if ($order_type == "Purchase")
        {
            if ($r['count'] > 0) {
                foreach ($r['res'] as $db_row) {
                    $q1 = "UPDATE  products SET pro_qty = pro_qty + " . $db_row["invpro_pro_qty"] . ", pro_qty_sold = pro_qty_sold - " . $db_row["invpro_pro_qty"] . " WHERE pro_id = " . $db_row["invpro_pro_id"];
                    $r1 = m_process("update", $q1);
                }
            }
        }
        else
        {

        }
        
    }
}

function convert_db_array_to_php_array($array, $key_name, $value_name) {
    $arrayTemp = array();
    foreach ($array as $key => $val) {
        foreach ($val as $key_v => $val_v) {
            $arrayTemp[$val[$key_name]] = $val[$value_name];
        }
    }
    return $arrayTemp;
}

function get_product_as_array($stauts = '', $cat_id = '',$type='Purchase', $cat_ids = '') {
    global $tmp_admin_id;
    $return_value = "";
    if ($type == 'Purchase') 
    {
        //$q = "SELECT  pro_id, pro_name  FROM sm_products WHERE 1 ";
        $q = "SELECT  pro_id, pro_name  FROM sm_products WHERE pro_id IN(SELECT invpro_pro_id FROM `sm_invoice_products` WHERE (invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead) > 0)  ";
        if ($stauts != '') {
            $q .= " AND pro_status = " . $stauts;
        } 
        if ($cat_id != '') {
            $q .= " AND pro_cat_id  = " . $cat_id;
        }
        if ($cat_ids != '') {
            $q .= " AND pro_cat_id  IN (" . $cat_ids.")";
        }
        $q .= " ORDER BY pro_name" ;
    }
    else if ($type == 'Dead Stock') {
        $q =  " SELECT pro_id , pro_name ";
        $q .= " FROM sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) INNER JOIN sm_product_option po1 ON (po1.po_id = invpro_po_id) INNER JOIN sm_product_option po2 ON (po2.po_id = invpro_po_id_2) ";
        $q .= " WHERE (invpro_pro_qty - invpro_pro_qty_dead - invpro_pro_qty_sold) > 0 AND inv_admin_id = ".$tmp_admin_id . " ORDER BY pro_name" ;
    }
    else {
        $q =  " SELECT CONCAT(pro_name,'-',po1.po_name,'-',po2.po_name,'-[',invpro_final_pro_price,'] (',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead),')') as pro_name , CONCAT(invpro_id,'##',pro_id,'##',invpro_po_id,'##',invpro_po_id_2) as pro_id  ";
        $q .= " FROM sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) INNER JOIN sm_product_option po1 ON (po1.po_id = invpro_po_id) INNER JOIN sm_product_option po2 ON (po2.po_id = invpro_po_id_2) ";
        $q .= " WHERE (invpro_pro_qty - invpro_pro_qty_dead) > 0 AND inv_admin_id = ".$tmp_admin_id . " ORDER BY pro_name"  ;
    }
    add_log_txt($q);
    $result = m_process("get_data", $q);
    return convert_db_array_to_php_array($result["res"], "pro_id", "pro_name");
}

function get_product_options_as_array($stauts = '', $cat_id = '', $cat_ids = '', $po_type='') {
    $return_value = "";
    $q = "SELECT  po_id, po_name  FROM sm_product_option WHERE 1 ";
    if ($stauts != '') {
        $q .= " AND po_status = '" . $stauts . "'";
    }
    if ($cat_id != '') 
    {
        $q .= " AND po_cat_id  = " . $cat_id;
    } 
    if ($cat_ids != '')
    {
        $q .= " AND po_cat_id  IN (" . $cat_ids.")";
    }
    if ($po_type != '') 
    {
        $q .= " AND po_type  = '" . $po_type."' ";
    } 
    $result = m_process("get_data", $q);
    return convert_db_array_to_php_array($result["res"], "po_id", "po_name");
}



function get_course_wise_belt_details($co_id) {
    $return_value = "";
    $q = "SELECT GROUP_CONCAT(be_name SEPARATOR '</br>') as be_name FROM sm_belt be INNER JOIN sm_course_belt cb ON (cb.cb_be_id = be.be_id) WHERE be.be_status = 'A' AND cb.cb_co_id = $co_id";
    $r = m_process("get_data", $q);
    if ($r['status'] == 'success' && $r['count'] > 0) {
        $return_value = $r['res'][0]["be_name"];
    }
    return $return_value;
}

function update_student_batch_time($stu_id, $stu_batchtime) {
    // updating student batch time
    $cateallocheck_q = 'UPDATE sm_student SET stu_batchtime  = ' . $stu_batchtime . ' WHERE stu_id = ' . $stu_id;
    $cateallocheck_r = m_process("update", $cateallocheck_q);
    if ($cateallocheck_r["status"] == 'error') {
        return $cateallocheck_r["errormsg"];
    }
    return "";
}
function allocate_certificate_belt($ex_id, $exs_ids,$certificate_or_belt) 
{
    $field_name = "";
    $arr_response = array("errormsg" => "", "id" => 0);
    // adding/updating certificate
    if ($certificate_or_belt == 'certificate')
        $field_name = "exs_certificate";
    else
        $field_name = "exs_belt";

    if ($exs_ids !='')
    {
        $certificatecheck_q = "UPDATE sm_exam_student_entrolled SET ".$field_name." = 'Y' WHERE exs_ex_id = $ex_id  AND exs_id IN (".$exs_ids.")" ;

        $certificatecheck_r = m_process("update", $certificatecheck_q);

        if ($certificatecheck_r["status"] == 'error') 
        {
            $arr_response["errormsg"] = $certificatecheck_r["errormsg"];
        }
    }
    // removing others
    if ($arr_response["errormsg"] == "")
    {
        if ($exs_ids !='')
        {
            $certificatecheck1_q = "UPDATE sm_exam_student_entrolled SET ".$field_name." = 'N' WHERE exs_ex_id = $ex_id  AND exs_id NOT IN (".$exs_ids.")" ;
        }
        else
        {
            $certificatecheck1_q = "UPDATE sm_exam_student_entrolled SET ".$field_name." = 'N' WHERE exs_ex_id = $ex_id " ;
        }   
        $certificatecheck1_r = m_process("update", $certificatecheck1_q);

        if ($certificatecheck1_r["status"] == 'error') 
        {
            $arr_response["errormsg"] = $certificatecheck1_r["errormsg"];
        }
    }
    return $arr_response;
}
function get_not_removal_ids($ex_id)
{
    $arr_response = array("errormsg" => "", "ids" => 0);
    $exam_enrolled_id_check_q = "SELECT GROUP_CONCAT(exs_id) as exs_ids FROM sm_exam_student_entrolled WHERE exs_ex_id = $ex_id  AND (exs_result_status != '' OR exs_paid =1 )" ;
    $exam_enrolled_id_check_r = m_process("get_data", $exam_enrolled_id_check_q);

        if ($exam_enrolled_id_check_r["status"] == 'error') 
        {
            $arr_response["errormsg"] = $exam_enrolled_id_check_r["errormsg"];
        }
        else
        {
            if ($exam_enrolled_id_check_r["res"][0]["exs_ids"] == null)
            $exam_enrolled_id_check_r["res"][0]["exs_ids"] = "";
            $arr_response["ids"] = $exam_enrolled_id_check_r["res"][0]["exs_ids"];
        }
        return $arr_response;
}

// br_id = branch id
// for = regular value = blank, Income, Expance, DealerP 
function get_receipt_no($br_id,$for='')
{
    $receipt_no = 0;
    if ($for == 'Expance') {
    $check_q = "SELECT max(pt_receipt_no_expance) as receipt_no FROM sm_payment_transaction WHERE pt_br_id = ".$br_id ;
    }
    else if ($for == 'Income')
    {
        $check_q = "SELECT max(pt_receipt_no_income) as receipt_no FROM sm_payment_transaction WHERE pt_br_id = ".$br_id ;
    }
    else if ($for == 'DealerP')
    {
        $check_q = "SELECT max(pt_receipt_no_dealer) as receipt_no FROM sm_payment_transaction WHERE pt_br_id = ".$br_id ;
    }
    else
    {
        $check_q = "SELECT max(pt_receipt_no) as receipt_no FROM sm_payment_transaction WHERE pt_br_id = ".$br_id ;
    }
    $check_check_r = m_process("get_data", $check_q);

        if ($check_check_r["status"] == 'error') 
        {
            $arr_response["errormsg"] = $check_check_r["errormsg"];
        }
        else
        {
            if ($check_check_r["res"][0]["receipt_no"] != null)
                $receipt_no = $check_check_r["res"][0]["receipt_no"];
        }
        return ($receipt_no+1);
}
function get_branch_details($br_id)
{
    $arr_branch = array();
    $arr_branch["address"]="";
    $arr_branch["name"]="";
    $arr_branch["logo_path"]="";
    $arr_branch["errormsg"]="";
    $address_to_print = "";
    $address_q = "SELECT br_logo , br_name, br_add_1 , br_add_2 , br_city,  br_district ,  br_contact_p_phone1,br_contact_p_email1,	st_name,br_postalcode , br_country FROM sm_branch LEFT JOIN sm_state ON (br_state_id= st_id ) WHERE br_id = $br_id" ;
    $address_r = m_process("get_data", $address_q);

        if ($address_r["status"] == 'error') 
        {
            $arr_branch["errormsg"] = $address_r["errormsg"];
        }
        else if ($address_r["count"]>0)
        {
            $address_to_print .= $address_r["res"][0]["br_add_1"];
            
            if ($address_r["res"][0]["br_add_2"] !='')
                $address_to_print .= ", ".$address_r["res"][0]["br_add_2"];

            $address_to_print .= "</br>";
            $address_to_print .= $address_r["res"][0]["br_city"];

            if ($address_r["res"][0]["br_postalcode"] !='')
            $address_to_print .= "-".$address_r["res"][0]["br_postalcode"];
            $address_to_print .= ".</br>";
            $address_to_print .= "<strong>Mo.</strong>-".$address_r["res"][0]["br_contact_p_phone1"];
            $address_to_print.=".</br>";
            $address_to_print.="<strong>Email</strong> - ".$address_r["res"][0]["br_contact_p_email1"];
            $arr_branch["address"] = $address_to_print;
            $arr_branch["name"] = $address_r["res"][0]["br_name"];
            $arr_branch["logo_path"] = BRANCH_IMAGE_URL.$address_r["res"][0]["br_logo"];
            
        }
        return $arr_branch;
}

function get_distributor_details($br_id,$del_id)
{
    $arr_dealer = array();
    $arr_dealer["address"]="";
    $arr_dealer["name"]="";
    $arr_dealer["errormsg"]="";
    $address_to_print = "";
    $address_q = "SELECT del_first_name, del_last_name, del_company_name, del_phone, del_phone_2, del_email, del_office_address, del_city, del_state_id, del_postal_code, del_gstno, del_panno, del_whatsappno FROM sm_dealer WHERE del_id = $del_id" ;
    $address_r = m_process("get_data", $address_q);

        if ($address_r["status"] == 'error') 
        {
            $arr_dealer["errormsg"] = $address_r["errormsg"];
        }
        else if ($address_r["count"]>0)
        {
            
            $address_to_print .= nl2br($address_r["res"][0]["del_office_address"]);

            if ($address_r["res"][0]["del_postal_code"] !='')
            $address_to_print .= "-".$address_r["res"][0]["del_postal_code"];
            $address_to_print .= "</br>";
            $address_to_print .= "No.-".$address_r["res"][0]["del_phone"]."<br>";
            if ($address_r["res"][0]["del_gstno"] !='')
            $address_to_print .= "<br> GST: ".$address_r["res"][0]["del_gstno"];

            if ($address_r["res"][0]["del_panno"] !='')
            $address_to_print .= "<br> GST: ".$address_r["res"][0]["del_panno"];

            $arr_dealer["address"] = $address_to_print;

            $arr_dealer["name"] = $address_r["res"][0]["del_first_name"]." ".$address_r["res"][0]["del_last_name"];
            if ($address_r["res"][0]["del_company_name"] !='')
            $arr_dealer["name"] .= "<br>".$address_r["res"][0]["del_company_name"];
        }
        return $arr_dealer;
}

function get_student_details($br_id,$stu_id)
{
    $arr_student = array();
    $arr_student["address"]="";
    $arr_student["name"]="";
    $arr_student["errormsg"]="";
    $address_to_print = "";
    $address_q = "SELECT stu_gr_no, stu_profile, stu_first_name, stu_middle_name, stu_last_name, stu_birth_date, stu_phone, stu_email, stu_home_address, stu_office_address, stu_city, stu_state_id, stu_postal_code, stu_mother_name, stu_parent_mobile_no, stu_aadharno, stu_whatsappno, stu_batchtime, stu_parent3_phone, stu_status, stu_medium, stu_sc_id, stu_current_course FROM sm_student WHERE stu_id  = $stu_id" ;
    $address_r = m_process("get_data", $address_q);

    if ($address_r["status"] == 'error') 
    {
        $arr_student["errormsg"] = $address_r["errormsg"];
    }
    else if ($address_r["count"]>0 )
    {
        $address_to_print .= nl2br($address_r["res"][0]["stu_home_address"]);
        $arr_student["address"] = $address_to_print;
        $arr_student["name"] = $address_r["res"][0]["stu_first_name"]." ".$address_r["res"][0]["stu_last_name"];
    }
    return $arr_student;
}

function get_batch_type($brt_id) 
{
    $arr_return = array("errormsg" => "", "brt_name" => "");
    $q = "SELECT brt_name FROM sm_branch_type WHERE brt_id = $brt_id";
    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') 
    {
        $arr_return['errormsg'] = $result['errormsg'];
    } 
    else 
    {
        if ($result['count'] > 0) 
        {
            $arr_return['brt_name'] = $result["res"][0]["brt_name"];
        }
    }
    return $arr_return;
}

function get_student_branchtype_change($stu_id)
{
    $arr_return = array("errormsg" => "", "res" => "", "status" => "failure");
    $q = "SELECT log_message FROM sm_log WHERE log_action = 'batch_type_change' AND log_stu_id = $stu_id";
    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') 
    {
        $arr_return['errormsg'] = $result['errormsg'];
    } 
    else 
    {
        if ($result["count"] > 0 )
        {
            echo "<br /><i>Change Batch Type Log:</i>";
            foreach($result["res"] as $arr_db)
            {
                echo "<br />".$arr_db["log_message"];
            }
        }
    }
    return $arr_return;
}

function allow_to_edit_event($evs_ev_id)
{
    $bln_return = true;
    $q = "select 1 FROM sm_event_student_entrolled WHERE evs_ev_id = ".$evs_ev_id;
    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') 
    {
        $bln_return = false; 
    } 
    else if ($result["count"] > 0)
    {
            $bln_return = false; 
    }
    return $bln_return;
}

function allow_to_edit_exam($ex_id)
{
    $bln_return = true;
    $q = "select 1 FROM sm_exam_student_entrolled  WHERE exs_ex_id = ".$ex_id;
    $result = m_process("get_data", $q);
    if ($result['errormsg'] != '') 
    {
        $bln_return = false; 
    } 
    else if ($result["count"] > 0)
    {
            $bln_return = false; 
    }
    return $bln_return;
}
function get_dealer_gst_type($del_id)
{
    $return = "";
    $q = "select del_igst FROM sm_dealer  WHERE del_id = ".$del_id;
    $result = m_process("get_data", $q);
    if ($result['errormsg'] == '' && $result["count"] > 0)  
    {
        $return =$result["res"][0]["del_igst"];
    } 
    return $return;
}
function get_customer_gst_type($del_id)
{
    $return = "";
    $q = "select del_igst FROM sm_customer  WHERE del_id = ".$del_id;
    $result = m_process("get_data", $q);
    if ($result['errormsg'] == '' && $result["count"] > 0)  
    {
        $return =$result["res"][0]["del_igst"];
    } 
    return $return;
}
function get_product_for_sale()
{
    global $tmp_admin_id;    
    $q = "SELECT DISTINCT pro_name, invpro_id FROM sm_invoice_products INNER JOIN sm_invoice_products ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) WHERE inv_br_id = ".$tmp_admin_id  ;
    // $q = "SELECT pro_name, invpro_id,'-',invpro_pro_id,'-',(invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead)), invpro_pro_id, invpro_pro_qty FROM sm_invoice_products INNER JOIN sm_invoice_products ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) WHERE inv_br_id = ".$tmp_admin_id  ;
    echo $q .= " AND (invpro_pro_qty - invpro_pro_qty_sold - invpro_pro_qty_dead) > 0 ";
    $r = m_process("get_data", $q);
    $arr_retun = array();
    if ($r['errormsg'] != '') {
        $arr_retun['error_message'] = $r['errormsg'];
    } else {
        if ($r['count'] > 0) {
            echo "<pre>";
            print_r($r['res']);
            echo "</pre>";
            /*
            foreach ($r['res'] as $db_row) {
                
            }
            */
        }
    }  
}

function update_purchase_order($update_ids) 
{
    $update_ids = trim($update_ids);
    $update_ids = str_replace(",,", ",", $update_ids);
    if ($update_ids != '') {
        $update_q = 'UPDATE sm_invoice_products IP INNER JOIN (SELECT SUM(IF(invpro_status = "Y", invpro_pro_qty, 0)) as invpro_pro_qty, invpro_id_purchase FROM sm_invoice_products_sale WHERE invpro_status = "Y" GROUP BY invpro_id_purchase) as M ON (IP.invpro_id = M.invpro_id_purchase) SET IP.invpro_pro_qty_sold = M.invpro_pro_qty WHERE IP.invpro_id = M.invpro_id_purchase AND M.invpro_id_purchase IN ('.$update_ids.'0)' ;        
        $update_r = m_process("update", $update_q);
        if ($update_r["status"] == 'error') {
            return $update_r["errormsg"];
        }
    }
    return "";
}

function get_attendance_result_set($month,$year,$br_id)
{
     // $q = 'SELECT CONCAT(DATE_FORMAT(sta_att_date,"%d"),"-",sta_stu_id) as check_key,sta_att_status FROM sm_student_attendance  WHERE DATE_FORMAT(sta_att_date,"%Y") = '.$year.' AND DATE_FORMAT(sta_att_date,"%m") = '.$month.'  AND sta_brt_id = '.$br_id.' ORDER BY DATE_FORMAT(sta_att_date,"%d") ';
     $q = 'SELECT CONCAT(DATE_FORMAT(sta_att_date,"%d"),"-",sta_stu_id) as check_key,sta_att_status FROM sm_student_attendance  WHERE DATE_FORMAT(sta_att_date,"%Y") = '.$year.' AND DATE_FORMAT(sta_att_date,"%m") = '.$month.'  AND sta_br_id = '.$br_id.' ORDER BY DATE_FORMAT(sta_att_date,"%d") ';
    $r = m_process("get_data", $q);
    return $r;
}
function get_faculty_attendance($month,$year,$br_id)
{
    //print_r($br_id);exit();
    
    $q='SELECT DISTINCT CONCAT(DATE_FORMAT(fta_att_date,"%d"),"-",fta_fac_id) as check_key,fta_att_status FROM sm_faculty_attendance WHERE DATE_FORMAT(fta_att_date,"%Y") = '.$year.' AND DATE_FORMAT(fta_att_date,"%m") = '.$month.' AND fta_br_id = '.$br_id.' ORDER BY DATE_FORMAT(fta_att_date,"%d") ';
    $r=m_process("get_data",$q);
    return $r;
}

function get_event_attendance($ev_id)
{
    $q = 'SELECT CONCAT(UNIX_TIMESTAMP(sea_att_date),"-",sea_stud_id) as check_key,sea_att_status FROM sm_event_attendance  WHERE sea_ev_id = '.$ev_id.' ORDER BY DATE_FORMAT(sea_att_date,"%d") ';
    $r = m_process("get_data", $q);
    return $r;
}

function get_event_attendance_data($ev_id)
{
    $q = 'SELECT * FROM sm_event_attendance  WHERE sea_ev_id = '.$ev_id.' ORDER BY DATE_FORMAT(sea_att_date,"%d") ';
    $r = m_process("get_data", $q);
    return $r;
}

function update_invoice_product_stock($none_removal_ids, $invpro_inv_id)
 {
    $none_removal_ids = trim($none_removal_ids);
    $none_removal_ids = str_replace(",,", ",", $none_removal_ids);

    // echo "**".$none_removal_ids."**".$eca_ex_id; exit(0);
    if ($none_removal_ids != '') {
        $cateallo_q = 'SELECT GROUP_CONCAT(invpro_id_purchase) as invpro_inv_id FROM sm_invoice_products_sale WHERE invpro_inv_id = ' . $invpro_inv_id . ' AND invpro_id NOT IN (' . $none_removal_ids . '0) ';

        $cateallo_r = m_process("get_data", $cateallo_q);
        if ($cateallo_r["status"] == 'success' && $cateallo_r["count"] > 0) 
        {
            if ($cateallo_r["res"][0]["invpro_inv_id"] != null && $cateallo_r["res"][0]["invpro_inv_id"] !="")
            {
                return $cateallo_r["res"][0]["invpro_inv_id"];
            }
            // return $cateallo_r["res"][0]["invpro_inv_id"];
        }
    }
    return "";
}

function order_allow_to_edit($inv_id)
{
    $bln_return = true;
    $q  = "SELECT count(1) as count_m FROM sm_invoice_products WHERE invpro_inv_id = ".$inv_id . " AND invpro_pro_qty_sold !=0 ";
    $r = m_process("get_data", $q);
    if ($r["status"] == 'success') 
    {
        if ($r["count"] > 0 && $r["res"][0]["count_m"] > 0)
        {
            $bln_return = false;
        }
        // return $cateallo_r["res"][0]["invpro_inv_id"];
    }
    else
    {
        $bln_return = false;
    }
    return $bln_return;
}

function mark_sales_as_inactive($inv_id)
{
    $q = 'UPDATE sm_invoice_products_sale SET invpro_status = "N" WHERE invpro_inv_id = '.$inv_id;
    $r = m_process("update", $q);
    return $r;
}

function check_dealer_invoice($inv_id)
{
    $bln_return = true;
    $q  = "SELECT count(1) as count_m FROM sm_invoice WHERE inv_purchase_del_id = ".$inv_id . " AND inv_active  = 'Y' ";
    $r = m_process("get_data", $q);
    if ($r["status"] == 'success') 
    {
        if ($r["count"] > 0 && $r["res"][0]["count_m"] > 0)
        {
            $bln_return = false;
        }
        // return $cateallo_r["res"][0]["invpro_inv_id"];
    }
    else
    {
        $bln_return = false;
    }
    return $bln_return;
}

function check_customer_invoice($inv_id)
{
    $bln_return = true;
    $q  = "SELECT count(1) as count_m FROM sm_invoice_sale WHERE inv_sale_type = 'C' AND inv_purchase_del_id = ".$inv_id . " AND inv_active  = 'Y' ";
    $r = m_process("get_data", $q);
    if ($r["status"] == 'success') 
    {
        if ($r["count"] > 0 && $r["res"][0]["count_m"] > 0)
        {
            $bln_return = false;
        }
        // return $cateallo_r["res"][0]["invpro_inv_id"];
    }
    else
    {
        $bln_return = false;
    }
    return $bln_return;
}

function get_invoice_invd_id($table_no,$br_id)
{
    $bln_return = true;
    $inv_invd_id = 1;
    $q  = "SELECT MAX(inv_invd_id) as inv_invd_id FROM $table_no WHERE inv_status = 'G' AND inv_admin_id = ".$br_id;
    $r = m_process("get_data", $q);
    if ($r["status"] == 'success') 
    {
        if ($r["count"] > 0 && $r["res"][0]["inv_invd_id"] > 0)
        {
            $inv_invd_id = $r["res"][0]["inv_invd_id"] +1;
        }
        // return $cateallo_r["res"][0]["invpro_inv_id"];
    }
    else
    {
        $inv_invd_id;
    }
    return $inv_invd_id;
}
function get_invoice_inv_ref_no($table_no,$br_id,$inv_generate_date)
{
    global $tmp_invoice_prefix ;
    $bln_return = true;
    $inv_ref_no = 1;
    $inv_generate_date_arr = explode("-",$inv_generate_date);
    $year= ""; 
    if ($inv_generate_date_arr[1] > 3) {
        $year = substr($inv_generate_date_arr[2],-2)."-".substr(($inv_generate_date_arr[2] +1),-2);
    }
    else {
        $year = substr(($inv_generate_date_arr[2]-1),-2)."-".substr($inv_generate_date_arr[2],-2);
    }
    

    $q  = "SELECT COUNT(inv_invd_id) as inv_invd_id FROM $table_no WHERE inv_status = 'G' AND inv_admin_id = ".$br_id;
    $r = m_process("get_data", $q);
    if ($r["status"] == 'success') 
    {
        if ($r["count"] > 0 && $r["res"][0]["inv_invd_id"] > 0)
            $inv_invd_id = $r["res"][0]["inv_invd_id"] +1;
        else
            $inv_invd_id= 1;    
        
            $inv_ref_no = $tmp_invoice_prefix."/".$year."/".substr('000'.$inv_invd_id,-4);
        // return $cateallo_r["res"][0]["invpro_inv_id"];
    }
    else
    {
        $inv_ref_no = $tmp_invoice_prefix."/".$year."/".substr('0001',-4);
    }

    return $inv_ref_no;
}
function followup_reminder_boxs()
{
    global $cur_date, $tmp_admin_id ;
    $html_to_append = "";
    $q = "SELECT  c.con_id, c.con_name, c.con_email, c.con_phone, c.con_message, c.con_status, c.con_date, c.con_no_of_followup, c.con_followup_date, c.con_create_date, c.con_followup_type  FROM  sm_contact c  WHERE c.con_status !='Closed' AND c.con_br_id = $tmp_admin_id AND date(c.con_followup_date) = '".date("Y-m-d")."'";
    $r = m_process("get_data", $q);
     
        $html_to_append .= '<li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Followup Reminder">
          <i class="fa fa-bell-o"></i>
          <span class="label label-warning">'.$r["count"] .'</span>
        </a>
        <ul class="dropdown-menu">
          
          <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu"><li><a href="#" class="font-weight-bold text-center"><b>Followup Reminder</b></a></li>';
              
          
         if (!empty($r["res"])) 
    { 
        foreach ($r['res'] as $db_row) {
            $con_followup_date = '';
            if ($db_row["con_followup_date"] !='')
            {
                $con_followup_date = convert_db_to_disp_date($db_row["con_followup_date"]);
            }
            $html_to_append .= ' <li>
                    <a href="manage_contact_followup.php?con_con_id='.$db_row["con_id"].'">
                      <i class="fa fa-warning text-yellow"></i> '.$db_row["con_followup_type"].' - '.$db_row["con_name"].' - '.$con_followup_date.'
                    </a>
                  </li>';
        }  }
        $html_to_append .= '</ul>
        </li>
        
      </ul>
    </li>';    
    
    return $html_to_append;
}

function bday_reminder_boxs()
{
    global $cur_date, $tmp_admin_id ;
    $html_to_append = "";
    $q = "SELECT * FROM  sm_student WHERE  DATE_ADD(stu_birth_date,INTERVAL YEAR(CURDATE())-YEAR(stu_birth_date) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(stu_birth_date),1,0)
                YEAR)  = CURDATE();";
    $r = m_process("get_data", $q);
     
        $html_to_append .= '<li class="dropdown notifications-menu">
        <a href="student_birthday.php" class="dropdown-toggle" data-toggle="dropdown" title="Student Birthday List">
          <i class="fa fa-birthday-cake"></i>
          <span class="label label-warning">'.$r["count"] .'</span>
        </a>
        <ul class="dropdown-menu">
          
          <li>
           
            <ul class="menu"><li><a href="#" class="font-weight-bold text-center"><b>Student Birthday</b></a></li>';
         if (!empty($r["res"])) 
    { 
        foreach ($r['res'] as $db_row) {
            $html_to_append .= ' <li>
                    <a href="student_birthday.php?is_current_date=1">
                      <i class="fa fa-warning text-yellow"></i>'.$db_row["stu_first_name"].' '.$db_row["stu_middle_name"].' '.$db_row["stu_last_name"].' ['.$db_row["stu_gr_no"].']'.'
                    </a>
                  </li>';
        }  }
        $html_to_append .= '</ul>
        </li>
        
      </ul>
    </li>';    
    
    return $html_to_append;
}

function absent_student_boxs()
{
    global $cur_date, $tmp_admin_id ;
    $html_to_append = "";
    $q = "SELECT * FROM  sm_student WHERE stu_id  IN(SELECT sta_stu_id FROM `sm_student_attendance` WHERE sta_att_date BETWEEN DATE_ADD(CURDATE(), INTERVAL -4 DAY) AND CURDATE() GROUP BY sta_stu_id HAVING count(sta_att_status='A') > 3);";
    //echo $q;exit();
   // $q = "SELECT * FROM  sm_student WHERE stu_id  IN(SELECT sta_stu_id FROM `sm_student_attendance` WHERE sta_att_date BETWEEN DATE_ADD('2019-01-01', INTERVAL -4 DAY) AND '2019-01-01' GROUP BY sta_stu_id HAVING count(sta_att_status='A') > 3);";
  
    $r = m_process("get_data", $q);
          // echo $r;exit();
     
        $html_to_append .= '<li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Student Absent List">
          <i class="fa fa-clock-o"></i>
          <span class="label label-warning">'.$r["count"] .'</span>
        </a>
        <ul class="dropdown-menu">
          
          <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu"><li><a href="#" class="font-weight-bold text-center"><b>Student Absent</b></a></li>';
            if (!empty($r["res"])) 
    {      
        foreach ($r['res'] as $db_row) {
            $html_to_append .= ' <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i>'.$db_row["stu_first_name"].' '.$db_row["stu_middle_name"].' '.$db_row["stu_last_name"].' ['.$db_row["stu_gr_no"].']'.'
                    </a>
                  </li>';
        }  
        }  
        $html_to_append .= '</ul>
        </li>
        
      </ul>
    </li>';    
    
    return $html_to_append;
}
function quick_access()
{
    $html_to_append="";
    $html_to_append .= '<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Quick Access">
      <i class="fa fa-plus"></i>
      <span class="label label-warning"></span>
    </a>
    <ul class="dropdown-menu">
      
      <li>
        <!-- inner menu: contains the actual data -->
        <ul class="menu"><li><a href="#" class="font-weight-bold text-center"><b>Quick Access</b></a></li>';
        if (empty($r["res"])) 
{      
    
        $html_to_append .= ' <li>
                <a href="manage_student.php">
                <i class="fa fa-user text-yellow">
                </i>Student Management
                </a>
                
              </li>';
        $html_to_append .= ' <li>
                <a href="manage_exam.php">
                <i class="fa fa-bell text-yellow">
                </i>Exam Management
              </a>
              
            </li>';
        $html_to_append .= ' <li>
            <a href="manage_sale.php">
            <i class="fa fa-cart-plus text-yellow"></i>
            Sale Management
            </a>
          
            </li>';
        $html_to_append .= ' <li>
            <a href="manage_event.php">
            <i class="fa fa-calendar text-yellow"></i>
            Event Management
            </a>
      
            </li>';

        $html_to_append .= ' <li>
            <a href="pending_fees.php">
            <i class="fa fa-cogs text-yellow"></i>
            Pending Fees
            </a>
      
            </li>';    
        
    }  
    $html_to_append .= '</ul>
    </li>
    
  </ul>
</li>';    

return $html_to_append;
}
function get_exs_finalized($exre_ex_id,$exre_stu_id)
{
    $exs_finalized = 'N';
    $q  = "SELECT exs_finalized FROM sm_exam_student_entrolled WHERE exs_ex_id = ".$exre_ex_id." AND exs_stu_id = ".$exre_stu_id;
    $r = m_process("get_data", $q);
    if ($r["status"] == 'success') 
    {
        if ($r["count"] > 0)
        {
            $exs_finalized = $r["res"][0]["exs_finalized"];
        }
        // return $cateallo_r["res"][0]["invpro_inv_id"];
    }
    
    return $exs_finalized;
}

function get_product_options_as_array_from_stock_only($stauts = '', $cat_id = '', $cat_ids = '', $po_type='') 
{
        global $tmp_admin_id;
        $return_value = "";
        
        if ($po_type == 'Size') 
        {
            $q =  " SELECT DISTINCT po1.po_id , po1.po_name ";
            $q .= " FROM sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) INNER JOIN sm_product_option po1 ON (po1.po_id = invpro_po_id) ";
            $q .= " WHERE (invpro_pro_qty - invpro_pro_qty_dead - invpro_pro_qty_sold) > 0 " ;            
            $q .= " AND po1.po_type  = 'Size' ";
            $q .= " AND inv_admin_id = ".$tmp_admin_id . " ORDER BY pro_name" ;
        } 
        else if ($po_type == 'Color')
        {
            $q =  " SELECT DISTINCT po1.po_id , po1.po_name ";
            $q .= " FROM sm_invoice_products INNER JOIN sm_invoice ON (inv_id = invpro_inv_id) INNER JOIN sm_products ON (invpro_pro_id = pro_id) INNER JOIN sm_product_option po1 ON (po1.po_id = invpro_po_id_2) ";
            $q .= " WHERE (invpro_pro_qty - invpro_pro_qty_dead - invpro_pro_qty_sold) > 0 " ;            
            $q .= " AND po1.po_type  = 'Color' ";
            $q .= " AND inv_admin_id = ".$tmp_admin_id . " ORDER BY pro_name" ;
        }
        
    
        $result = m_process("get_data", $q);
        return convert_db_array_to_php_array($result["res"], "po_id", "po_name");
}

function export_data($report_name, $export_file_name,$data_header,$data_query)
{
    $filename = $export_file_name.'_'.date('d-m-Y').'.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    //header("Content-Description: File Transfer");
   // header("Content-Disposition: attachment; filename=$filename.");
   // header("Content-Type: application/xls; ");

    $file_e = fopen('php://output', 'w');
    if ($report_name == 'Report Student Attendance')
    {
        $today_year = date('Y');
        $today_month = date('m');
        global $att_month,$att_year,$tmp_admin_id;
        // start of creating header
      //  $data_header= array("Sr.","Gr No.","Name","B. Type","Belt","B. Time");
        $student_attendance =  get_attendance_result_set($att_month,$att_year,$tmp_admin_id);
        $student_attendance_array = array();    
        if ($student_attendance["status"]=="success" && $student_attendance["count"] > 0)
        {
            foreach($student_attendance["res"] as $key=>$val) 
            { 
                $student_attendance_array[$val["check_key"]] = $val["sta_att_status"];
            }
        }
        
        $total_days =   cal_days_in_month(CAL_GREGORIAN, $att_month, $att_year);
        // echo "*-".$att_month."-*-".$att_year-"--*--".$total_days."*"; exit(0);
        for ($cal_i= 1; $cal_i <=$total_days; $cal_i++)
        {
            $timestamp = strtotime($att_year.'-'.$att_month.'-'.$cal_i);
            $day = strtolower(date('D', $timestamp));
            $timestamp = strtoupper( substr($day,0,1));
            $data_header[] = $timestamp."\n".$cal_i;
            
        }
       // echo implode("\t", $data_header) . "\n";
       // print_r($data_header); exit(0);
        // end of creating header
        
     
    }
    else if ($report_name == 'Report Faculty Attendance')
    {
        $today_year = date('Y');
        $today_month = date('m');
        global $att_month,$att_year,$tmp_admin_id;
        $total_days =   cal_days_in_month(CAL_GREGORIAN, $att_month, $att_year);
        // echo "*-".$att_month."-*-".$att_year-"--*--".$total_days."*"; exit(0);
        for ($cal_i= 1; $cal_i <=$total_days; $cal_i++)
        {
            $timestamp = strtotime($att_year.'-'.$att_month.'-'.$cal_i);
            $day = strtolower(date('D', $timestamp));
            $timestamp = strtoupper( substr($day,0,1));
            $data_header[] =$timestamp."\n".$cal_i;
        }
        
        
     //   print_r($data_header); exit(0);
        // end of creating header
        
     
    }
    fputcsv($file_e, $data_header);
    
    // if ($report_name != 'Report Student Attendance')
    // { fputcsv($file_e, $data_header); }
    $result_export_data = m_process("get_data", $data_query);
    if ($result_export_data["status"] == 'success' && $result_export_data["count"] > 0)
    {
    $i=1;
    foreach($result_export_data["res"] as $result_export_data_row )
    {
        if ($report_name == 'Report Income Export')
        {
           
            $data_row = array($i,$result_export_data_row["ac_name"],$result_export_data_row["pt_tran_u_type"],$result_export_data_row["iet_name"],DBtoDisp($result_export_data_row["pt_tran_date"]),$result_export_data_row["pt_tran_mode_of_payent"],$result_export_data_row["pt_tran_bank"],$result_export_data_row["pt_tran_no"],$result_export_data_row["pt_tran_amount"], $result_export_data_row["pt_tran_remarks"]);
        }
        else if ($report_name == 'Report Receipt')
        {
            $sc_half_course = $result_export_data_row['sc_half_course']==1?'Yes':'No';
            $d_name = $result_export_data_row['pt_tran_u_type'];
            if ($result_export_data_row['d_name'] !='')
                $d_name .= " - ".$result_export_data_row['d_name'];

            $data_row = array($i,$result_export_data_row["pt_receipt_no"],$result_export_data_row["stu_first_name"].' '.$result_export_data_row["stu_last_name"],$result_export_data_row["stu_gr_no"],$result_export_data_row["be_name"].($result_export_data_row['sc_half_course']==1?"-Half":"-Full"),$result_export_data_row["co_name"],$sc_half_course,$d_name,$result_export_data_row["pt_tran_mode_of_payent"],$result_export_data_row["pt_tran_no"],$result_export_data_row["transaction_date"],$result_export_data_row["pt_tran_amount"],$result_export_data_row["pt_tran_remarks"]);
        }
        else if ($report_name == 'Report Stock')
        {
            $invpro_net_qty =  $result_export_data_row['invpro_pro_qty']-$result_export_data_row['invpro_pro_qty_sold']-$result_export_data_row['invpro_pro_qty_dead'];
            $data_row = array($i,$result_export_data_row["cat_name"],$result_export_data_row["pro_name"],$result_export_data_row["option1"],$result_export_data_row["option2"],$result_export_data_row["invpro_final_pro_price"],$result_export_data_row["invpro_pro_qty"],$result_export_data_row["invpro_pro_qty_sold"],$result_export_data_row["invpro_pro_qty_dead"],$invpro_net_qty);
        }
        
        else if ($report_name == 'Report Student Attendance')
        {
            $stu_brt_name= $stu_be_name= $stu_co_name = '';
            if ($result_export_data_row['brt_name'] == '' && $result_export_data_row['be_name'] == '' && $result_export_data_row['co_name'] == '')  
            {
                $arr_get_student_course =  get_student_lastet_course($result_export_data_row['stu_id']);
                if ($arr_get_student_course["error_message"] == '')
                {
                    $stu_brt_name= $arr_get_student_course['brt_name'] ;
                    $stu_be_name= $arr_get_student_course['be_name'] ;
                    $stu_co_name = $arr_get_student_course['co_name'] ;
                }
                
            } 
            else
            {
                $stu_brt_name= $result_export_data_row['brt_name'] ;
                    $stu_be_name= $result_export_data_row['be_name'] ;
                    if ($result_export_data_row["sc_half_course"]==1)
                    $stu_be_name= $result_export_data_row['be_name_for'] ;
                    $stu_co_name = $result_export_data_row['co_name'] ;
            }
            
            $data_row = array($i,$result_export_data_row["stu_gr_no"],$result_export_data_row["stu_first_name"].' '.$result_export_data_row['stu_middle_name'].' '.$result_export_data_row["stu_last_name"],$stu_brt_name,$result_export_data_row["bt_name"]);
            for ($cal_i= 1; $cal_i <=$total_days; $cal_i++)
            {
                $find_key = substr("0".$cal_i,-2)."-".$result_export_data_row['stu_id'];
                $brt_working_days = strtolower($result_export_data_row['brt_working_days']);
                $brt_working_days_array = explode(",",$brt_working_days);
                $timestamp = strtotime($att_year.'-'.$att_month.'-'.$cal_i);
                $day = strtolower(date('D', $timestamp));
                $default_value = '';
                if (!(in_array ( $day, $brt_working_days_array)))
                {
                    $default_value = '-';
                }
                $data_row[]=(isset($student_attendance_array[$find_key])?$student_attendance_array[$find_key]:$default_value);
            }

        }
        else if ($report_name == 'Report Faculty Attendance')
        {
            $stu_brt_name= $stu_be_name= $stu_co_name = '';
            if ($result_export_data_row['brt_name'] == '' && $result_export_data_row['be_name'] == '' && $result_export_data_row['co_name'] == '')  
            {
                $arr_get_student_course =  get_student_lastet_course($result_export_data_row['stu_id']);
                if ($arr_get_student_course["error_message"] == '')
                {
                    $stu_brt_name= $arr_get_student_course['brt_name'] ;
                    $stu_be_name= $arr_get_student_course['be_name'] ;
                    $stu_co_name = $arr_get_student_course['co_name'] ;
                }
                
            } 
            else
            {
                $stu_brt_name= $result_export_data_row['brt_name'] ;
                    $stu_be_name= $result_export_data_row['be_name'] ;
                    if ($result_export_data_row["sc_half_course"]==1)
                    $stu_be_name= $result_export_data_row['be_name_for'] ;
                    $stu_co_name = $result_export_data_row['co_name'] ;
            }
    
            $data_row = array($i,$result_export_data_row["stu_gr_no"],$result_export_data_row["stu_first_name"].' '.$result_export_data_row['stu_middle_name'].' '.$result_export_data_row["stu_last_name"],$stu_brt_name,$result_export_data_row["bt_name"]);
            
          
     //print_r($student_attendance_array);exit(0);               
    for ($cal_i= 1; $cal_i <=$total_days; $cal_i++)
    {
        $find_key = substr("0".$cal_i,-2)."-".$db_row['stu_id'];
        $brt_working_days = strtolower($db_row['brt_working_days']);
        $brt_working_days_array = explode(",",$brt_working_days);
        $timestamp = strtotime($att_year.'-'.$att_month.'-'.$cal_i);
        $day = strtolower(date('D', $timestamp));
        $default_value = '';
        if (!(in_array ( $day, $brt_working_days_array)))
        {
            $default_value = '-';
        }
        $data_row[]= (isset($student_attendance_array[$find_key])?$student_attendance_array[$find_key]:$default_value);
    }
    // end
        }
        fputcsv($file_e,$data_row);   
    
        $i++;
        // $invpro_pro_qty_sold = $check_db_row["invpro_pro_qty_sold"];
    }
    }
}

function getPeriodToDates($start, $end, $format = 'Y-m-d')
{
    $dateArr = array();
    $interval = new DateInterval('P1D');
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    foreach ($period as $date) {
        $dateArr[] = $date->format($format);
    }
    return $dateArr;
}