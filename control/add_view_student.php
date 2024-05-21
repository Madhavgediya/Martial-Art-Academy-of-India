<?php
include("includes/application_top.php");
include("../includes/class/student.php");
// Set the caption of button
$HTTP_REFERER = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
if(strpos($HTTP_REFERER,"student_missing_details.php") !== false) {
  $_SESSION["is_student_missing_details"] = 1;  
}
$id = get_rdata("id", 0);
$act = get_rdata("act");
$stu_id = get_rdata('stu_id');
$stu_gr_no = get_rdata('stu_gr_no');

$stu_first_name = get_rdata('stu_first_name');
$stu_middle_name = get_rdata('stu_middle_name');
$stu_last_name = get_rdata('stu_last_name');
$stu_phone = get_rdata('stu_phone');
$stu_birth_date = get_rdata('stu_birth_date');
$sc_course_type = get_rdata('sc_course_type', 'Quarterly');
$stu_admission_date = get_rdata('stu_admission_date');
$stu_deactivation_date = get_rdata('stu_deactivation_date');
if ($stu_admission_date == '') {
    $stu_admission_date = DBtoDisp($cur_date);
}
$stu_home_address = get_rdata('stu_home_address');
$stu_office_address = get_rdata('stu_office_address');
$stu_city = get_rdata('stu_city');
$stu_state_id = get_rdata('stu_state_id', 0);
$stu_postal_code = get_rdata('stu_postal_code');
$stu_photo_old = get_rdata('stu_photo_old');
$stu_mother_name = get_rdata('stu_mother_name');
$stu_parent_mobile_no = get_rdata('stu_parent_mobile_no');
$stu_email = get_rdata('stu_email');
$stu_aadharno = get_rdata('stu_aadharno');
$stu_whatsappno = get_rdata('stu_whatsappno');
$stu_batchtime = get_rdata('stu_batchtime', $tmp_default_batch_time);
$stu_parent3_phone = get_rdata('stu_parent3_phone');
$stu_status = get_rdata('stu_status', 'A');
$stu_status_old = get_rdata('stu_status_old', 'A');
$stu_medium = get_rdata('stu_medium', 'English');
$stu_sc_id = get_rdata('stu_sc_id', 0);
$sc_half_course = get_rdata('sc_half_course', 0);
$stu_current_course = get_rdata('stu_current_course');
$stu_std_id = get_rdata('stu_std_id', 0);
$stu_user_type = get_rdata('stu_user_type', 'N');
$stu_create_date = $cur_date;
$stu_create_by_id = $tmp_admin_id;
$stu_update_date = $cur_date;
$stu_update_by_id = $tmp_admin_id;
$lo_lastlogin_date = $cur_date;
$stu_br_id = $tmp_admin_id;

$sc_brt_id = get_rdata('sc_brt_id', 0);
$sc_co_id = get_rdata('sc_co_id', 0);
$sc_be_id = get_rdata('sc_be_id', 0);
$sc_cd_id = get_rdata('sc_cd_id', 0);
$sc_joined_date = get_rdata('sc_joined_date');

if ($sc_joined_date == '') {
    $sc_joined_date = DBtoDisp($cur_date);
}
$sc_is_current = get_rdata('sc_is_current', 1);

$caption = "Add Student";
$btn_caption = "Add Student";
if ($id != 0) {
    $caption = "View Student";
    $btn_caption = "View Student";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_student = new student();
    $sm_student->data["*"] = "";
    $sm_student->action = 'get';
    $sm_student->process_id = $id;
    $result = $sm_student->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $stu_gr_no = $db_row['stu_gr_no'];
                $stu_first_name = $db_row['stu_first_name'];
                $stu_middle_name = $db_row['stu_middle_name'];
                $stu_last_name = $db_row['stu_last_name'];
                $stu_phone = $db_row['stu_phone'];
                $stu_birth_date = DBtoDisp($db_row['stu_birth_date']);
                $stu_admission_date = DBtoDisp($db_row['stu_admission_date']);
                if ($db_row['stu_deactivation_date'] != '') {
                    $stu_deactivation_date = DBtoDisp($db_row['stu_deactivation_date']);
                }

                $stu_photo_old = $db_row['stu_photo'];
                $stu_home_address = $db_row['stu_home_address'];
                $stu_office_address = $db_row['stu_office_address'];
                $stu_city = $db_row['stu_city'];
                $stu_state_id = $db_row['stu_state_id'];
                $stu_postal_code = $db_row['stu_postal_code'];
                $stu_mother_name = $db_row['stu_mother_name'];
                $stu_parent_mobile_no = $db_row['stu_parent_mobile_no'];
                $stu_email = $db_row['stu_email'];
                $stu_aadharno = $db_row['stu_aadharno'];
                $stu_whatsappno = $db_row['stu_whatsappno'];
                $stu_batchtime = $db_row['stu_batchtime'];
                $stu_parent3_phone = $db_row['stu_parent3_phone'];
                $stu_status = $db_row['stu_status'];
                $stu_status_old = $db_row['stu_status'];
                $stu_medium = $db_row['stu_medium'];
                $stu_current_course = $db_row['stu_current_course'];
                $stu_std_id = $db_row['stu_std_id'];
                $stu_user_type = $db_row['stu_user_type'];
                $stu_create_date = $db_row['stu_create_date'];
                $stu_create_by_id = $db_row['stu_create_by_id'];
                $stu_update_date = $db_row['stu_update_date'];
                $stu_update_by_id = $db_row['stu_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
    /*
    $not_value = " AND stu_br_id = " . $tmp_admin_id;
    $arr_duplicate_name = found_duplicate('sm_student', 'stu_gr_no', $stu_gr_no, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for student GR No. ';
    }

 */
    // end of roll no with in class
    if ($errormsg == '') {
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
            }
        } else {
            $stu_photo = 'student-default-no-image.jpg';
        }
    }
    if ($errormsg == '') {
        $sm_student = new student();
        // $sm_student->data["stu_gr_no"] = randomPrefix(14);
        $sm_student->data["stu_gr_no"] = "";
        $sm_student->data["stu_first_name"] = $stu_first_name;
        $sm_student->data["stu_middle_name"] = $stu_middle_name;
        $sm_student->data["stu_last_name"] = $stu_last_name;
        $sm_student->data["stu_phone"] = $stu_phone;
        $sm_student->data["stu_birth_date"] = disptoDB($stu_birth_date);
        // $sm_student->data["stu_admission_date"] = disptoDB($stu_admission_date);
        $sm_student->data["stu_admission_date"] = disptoDB($sc_joined_date);
        if ($stu_deactivation_date != '') {
            $sm_student->data["stu_deactivation_date"] = disptoDB($stu_deactivation_date);
        }
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
            $errormsg = $result['errormsg'];
        } else {
            $arr_update_gr =  update_gr_no($result['id'], $tmp_admin_id, "sm_student");
            if ($arr_update_gr['status'] == 'failure') {
                $errormsg = $arr_update_gr['errormsg'];
            } else {
                $arr_log = array();
                $arr_log["log_message"] = $stu_first_name . " " . $stu_last_name . " (" . $result['id'] . ") has been added.";
                $arr_log["log_stu_id"] = $result['id'];
                $arr_log["log_admin_id"] = $tmp_admin_id;
                $arr_log["log_action"] = "add_student";
                add_log($arr_log);
                // code to add course to student
                if (($sc_be_id != '' && $sc_be_id > 0) && ($sc_brt_id != '' && $sc_brt_id > 0) && ($sc_co_id != '' && $sc_co_id > 0)) {
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
                    $add_course_to_student =  add_course_to_student($arr_course_data);
                    if ($add_course_to_student != '') {
                        $errormsg = $add_course_to_student;
                    } else {
                        header('Location:manage_student.php?msg=5&page=1&per_page=' . $per_page);
                        exit(0);
                    }
                }
                // end of code to add course to student
                header('Location:manage_student.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}
// Update user entry
if ($act == 'update') {
    /*
    $not_value = " AND stu_sc_id = " . $stu_sc_id . " AND stu_id != " . $id;
    $arr_duplicate_name = found_duplicate('sm_student', 'stu_gr_no', $stu_gr_no, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for student GR No. ';
    }
 */
    if ($errormsg == '') {
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
                $stu_photo = SITE_URL . "/images/student/" . $stu_photo;
            }
        } else {
            $stu_photo = $stu_photo_old;
        }
        $sm_student = new student();
        $sm_student->data["stu_first_name"] = $stu_first_name;
        $sm_student->data["stu_middle_name"] = $stu_middle_name;
        $sm_student->data["stu_last_name"] = $stu_last_name;
        $sm_student->data["stu_photo"] = $stu_photo;
        $sm_student->data["stu_phone"] = $stu_phone;
        $sm_student->data["stu_birth_date"] = disptoDB($stu_birth_date);
        $sm_student->data["stu_admission_date"] = disptoDB($stu_admission_date);
        if ($stu_deactivation_date != '') {
            $sm_student->data["stu_deactivation_date"] = disptoDB($stu_deactivation_date);
        }
        $sm_student->data["stu_home_address"] = escape($stu_home_address);
        $sm_student->data["stu_office_address"] = escape($stu_office_address);
        $sm_student->data["stu_city"] = $stu_city;
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
        $sm_student->action = 'update';
        $sm_student->process_id = $id;
        $result = $sm_student->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($stu_status != $stu_status_old) {
                // $a_log = 'INSERT INTO sm_activeinactive (ac_stu_id, ac_date, ac_status, ac_br_id, ac_create_date, ac_create_by_id, ac_update_date, ac_update_by_id) VALUES ('..')';
                $q_log = 'INSERT INTO sm_activeinactive (ac_stu_id, ac_date, ac_status, ac_br_id, ac_create_date, ac_create_by_id, ac_update_date, ac_update_by_id) VALUES (' . $id . '.,"' . disptoDB($stu_deactivation_date) . '","' . $stu_status . '",' . $stu_br_id . ',"' . $stu_create_date . '",' . $stu_create_by_id . ',"' . $stu_update_date . '",' . $stu_update_by_id . ')';
                $r_log = m_process("insert", $q_log);
                if ($r_log["status"] == "failure") {
                    $errormsg = $r_log["errormsg"];
                } else {
                    if(!empty($_SESSION["is_student_missing_details"])) {
                        header('Location:student_missing_details.php');
                    }else {
                        header('Location:manage_student.php?msg=3&page=1&per_page=' . $per_page);
                    }
                    exit(0);
                }
            } else {
                if(!empty($_SESSION["is_student_missing_details"])) {
                    header('Location:student_missing_details.php');
                }else {
                    header('Location:manage_student.php?msg=3&page=1&per_page=' . $per_page);
                }
                //header('Location:manage_student.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}
//if (session_get('admin_login_type') == 'school') {
//    $stu_sc_id = session_get('admin_sc_id');
//}
$stu_sc_id = 0;

// echo 'error'.$errormsg.'end of error';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport" />
    <title><?php echo $page_title; ?></title>
    <?php include("includes/include_files.php"); ?>
</head>

<body class="skin-green sidebar-mini">
    <div class="wrapper">

        <?php include("includes/header.php"); ?>
        <?php include("includes/left_menu.php"); ?>
        <!-- our page -->

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo $caption; ?>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"><?php echo $caption; ?></li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php include("includes/messages.php"); ?>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $caption; ?></h3>
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                <input type="hidden" id="act" name="act" />
                                <input type="hidden" id="stu_status_old" name="stu_status_old" value="<?php echo $stu_status_old; ?>" />
                                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                <input type="hidden" id="stu_photo_old" name="stu_photo_old" value="<?php echo $stu_photo_old; ?>" />
                                <div class="box-body">
                                    <div class=" col-md-6">
                                        <!-- START -->
                                        <?php if ($id > 0) { ?>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">GR No</label>
                                                <div class="col-sm-9">
                                                    <input readonly type="text" name="stu_gr_no" id="stu_gr_no" placeholder="GR No" value="<?php echo $stu_gr_no; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">First Name</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_first_name" id="stu_first_name" placeholder="First Name" value="<?php echo $stu_first_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Middle Name</label>
                                            <div class="col-sm-9">

                                                <input readonly type="text" name="stu_middle_name" id="stu_middle_name" placeholder="Middle Name" value="<?php echo $stu_middle_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Last Name</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_last_name" id="stu_last_name" placeholder="Last Name" value="<?php echo $stu_last_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Mother Name</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_mother_name" id="stu_mother_name" placeholder="Mother Name" value="<?php echo $stu_mother_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Birth Date</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_birth_date" id="stu_birth_date" placeholder="Birth Date" value="<?php echo $stu_birth_date; ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Home Address</label>
                                            <div class="col-sm-9">
                                                <textarea readonly name="stu_home_address" id="stu_home_address" placeholder="Home Address" class="form-control"><?php echo $stu_home_address; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Office Address</label>
                                            <div class="col-sm-9">
                                                <textarea readonly name="stu_office_address" id="stu_office_address" placeholder="Office Address" class="form-control"><?php echo $stu_office_address; ?></textarea>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Email</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_email" id="stu_email" placeholder="Email" value="<?php echo $stu_email; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Parent Mobile</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_parent_mobile_no" id="stu_parent_mobile_no" placeholder="Parent Mobile" value="<?php echo $stu_parent_mobile_no; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">App. Mobile</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_phone" id="stu_phone" placeholder="App. Mobile" value="<?php echo $stu_phone; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Whatsapp No.</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_whatsappno" id="stu_whatsappno" placeholder="Whatsapp No." value="<?php echo $stu_whatsappno; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Aadhar No.</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_aadharno" id="stu_aadharno" placeholder="Aadhar No." value="<?php echo $stu_aadharno; ?>" class="form-control" />
                                            </div>
                                        </div>


                                        <div class="form-group hide">
                                            <label class="col-sm-3 control-label">Admission Date</label>
                                            <div class="col-sm-9">
                                                <?php
                                                $adminssion_date_readonly = "readonly";
                                                if ($tmp_admin_id == 1 || $tmp_admin_id == 3) {
                                                    $adminssion_date_readonly = "";
                                                }
                                                ?>
                                                <input readonly type="text" <?php echo $adminssion_date_readonly; ?> name="stu_admission_date" id="stu_admission_date" placeholder="Admission Date" value="<?php echo $stu_admission_date; ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Batch Time</label>
                                            <div class="col-sm-9">
                                                <select disabled id="stu_batchtime" name="stu_batchtime" class="form-control">
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'bt_name ,bt_id';
                                                    $data_arr_input['table'] = 'sm_batch_time';
                                                    $data_arr_input['where'] = " bt_br_id = " . $tmp_admin_id . " AND bt_status  = 'A' ";
                                                    $data_arr_input['key_id'] = 'bt_id';
                                                    $data_arr_input['key_name'] = 'bt_name';
                                                    $data_arr_input['current_selection_value'] = $stu_batchtime;
                                                    $data_arr_input['order_by'] = 'bt_id';

                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group " style="display:none;">
                                            <label class="col-sm-3 control-label">User I.</label>
                                            <div class="col-sm-9">
                                                <select readonly id="stu_user_type" name="stu_user_type" class="form-control">
                                                    <option value="E" <?php if ($stu_user_type == 'E') echo "selected";  ?>>Existing</option>
                                                    <option value="N" <?php if ($stu_user_type == 'N') echo "selected";  ?>>New</option>
                                                </select>
                                            </div>
                                        </div>



                                        <!-- END -->
                                        <div class="form-group" <?php if ($id == 0) echo ' style="display:none;" ' ?>>
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input disabled type="radio" name="stu_status" id="stu_status_a" <?php if ($stu_status == 'A') {
                                                                                                            echo 'checked="checked"';
                                                                                                        }; ?> value="A" onclick="show_hide_deactivation_date('hide');" /><label for="stu_status_a">Active</label> <input disabled type="radio" name="stu_status" onclick="show_hide_deactivation_date('show');" id="stu_status_i" value="I" <?php if ($stu_status == 'I') echo 'checked="checked"'; ?> /><label for="stu_status_i">InActive</label>
                                            </div>


                                        </div>

                                        <div class="form-group" id="employee_inactive_date" <?php if ($id == 0) echo ' style="display:none;" ' ?>>
                                            <label id="lbl_active_inactive" class="col-sm-3 control-label"><?php echo ($stu_status == 'A') ? 'Activation Date' : 'Deactivation Date'; ?></label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="stu_deactivation_date" id="stu_deactivation_date" placeholder="Date" value="<?php echo $stu_deactivation_date; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                        <!-- start adding course-->



                                        <?php if ($id == 0) {  ?>
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Add Course to Student</h3> <i style="margin-left:10px;">[select student current course]</i>
                                            </div>
                                            </br>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Batch Type</label>
                                                <div class="col-sm-9">
                                                    <select readonly id="sc_brt_id" name="sc_brt_id" class="form-control">
                                                        <option value="0">--Please select--</option>
                                                        <?php
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'brt_name ,brt_id';
                                                            $data_arr_input['table'] = 'sm_branch_type';
                                                            $data_arr_input['where'] = " brt_br_id = " . $tmp_admin_id . " AND brt_status  = 'A' ";
                                                            $data_arr_input['key_id'] = 'brt_id';
                                                            $data_arr_input['key_name'] = 'brt_name';
                                                            $data_arr_input['current_selection_value'] = $sc_brt_id;
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Course</label>
                                                <div class="col-sm-9">
                                                    <select readonly onchange="get_belt_details_from_course('sc_co_id','sc_be_id');" required id="sc_co_id" name="sc_co_id" class="form-control">
                                                        <option value="0">--Please select--</option>
                                                        <?php
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'co_name ,co_id';
                                                            $data_arr_input['table'] = 'sm_course';
                                                            $data_arr_input['where'] = " co_status  = 'A' ";
                                                            $data_arr_input['key_id'] = 'co_id';
                                                            $data_arr_input['key_name'] = 'co_name';
                                                            $data_arr_input['current_selection_value'] = $sc_co_id;
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Belt</label>
                                                <div class="col-sm-9">
                                                    <select readonly id="sc_be_id" name="sc_be_id" class="form-control">
                                                        <option value="0">--Please select--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Joined Date</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly name="sc_joined_date" id="sc_joined_date" placeholder="Joining Date" value="<?php echo $sc_joined_date; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Half Course</label>
                                                <div class="col-sm-9">
                                                    <input readonly type="checkbox" name="sc_half_course" id="sc_half_course" value="1" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Payment Terms</label>
                                                <div class="col-sm-9">
                                                    <select disabled name="sc_course_type" id="sc_course_type" class="form-control">
                                                    <option <?php if ($sc_course_type == 'Monthly') echo 'selected="selected"'; ?> value="Monthly">Monthly</option>
<option <?php if ($sc_course_type == 'Quarterly') echo 'selected="selected"'; ?> value="Quarterly">Quarterly</option>
<option <?php if ($sc_course_type == 'Half Yearly') echo 'selected="selected"'; ?> value="Half Yearly">Half Yearly</option>
<option <?php if ($sc_course_type == 'Yearly') echo 'selected="selected"'; ?> value="Yearly">Yearly</option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Photo</label>
                                            <div class="col-sm-9">
                                                <input disabled type="file" id="stu_photo" name="stu_photo" />
                                                <?php if ($stu_photo_old != '') { ?>
                                                    <a href="<?php echo $stu_photo_old; ?>" target="_blank"><img src="<?php echo $stu_photo_old; ?>" height="120px" width="120px" /></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- end adding course-->

                                    </div>
                                </div><!-- /.box -->
                                <div class="box-footer">
                                <button type="button" style="float:right;" class="btn btn-primary" onclick="window.location.href = 'manage_student.php'">Back</button>
                                    <?php if ($id == 0) { ?> <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                    <!-- <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button> -->
                                </div><!-- /.box-footer -->
                                <div class="form-group " style="margin-bottom:20px;">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-11">
                                        <?php
                                        if ($id != '' && $id != 0) {
                                            $q_activation_log = "SELECT * FROM sm_activeinactive WHERE ac_stu_id = $id";
                                            $r_activation_log = m_process("get_data", $q_activation_log);
                                            if ($r_activation_log["status"] == "success" && $r_activation_log["count"] > 0) {
                                                echo '<i>Active/Inactive Log:</i><br/>';
                                                foreach ($r_activation_log['res'] as $arr_db_log) {
                                                    $ac_status = ($arr_db_log["ac_status"] == 'I') ? "Inactivated" : "Activated";
                                                    echo $ac_status . " date " . DBtoDisp($arr_db_log["ac_date"]) . " Activity done as on " . DBtoDisp($arr_db_log["ac_create_date"]) . "</br>";
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group " style="margin-bottom:20px;">
                                    <label class="col-sm-1 control-label"></label>
                                    <div class="col-sm-11">
                                        <?php
                                        if ($id != '' && $id != 0) {
                                            get_student_branchtype_change($id);
                                        }
                                        ?>
                                    </div>
                                </div>
                                <br />

                            </form>
                            <!-- general form elements disabled -->
                            <div>
                            </div>
                        </div>
            </section>
        </div>



        <!-- end of our page-->
        <script>
            $("#stu_birth_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            });
        </script>
        <?php

        if ($tmp_admin_id == 1 || $tmp_admin_id == 3) {
            ?>
            <script>
                $("#stu_admission_date").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                });
            </script>
        <?php  }
        ?>

        <script>
            $("#stu_deactivation_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            });
        </script>

        <?php include("includes/footer.php"); ?>
        <?php include("includes/models.php"); ?>
    </div>
</body>

</html>