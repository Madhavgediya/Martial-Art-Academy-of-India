<?php
include("includes/application_top.php");
include("../includes/class/results.php");
// Set the caption of button




$id = get_rdata("id", 0);
$act = get_rdata("act");
$res_id = get_rdata('res_id');
$res_title = get_rdata('res_title');
$res_description = get_rdata('res_description');
$res_stu_id = get_rdata('res_stu_id');
$res_sc_id = get_rdata('res_sc_id');
$res_medium = get_rdata('res_medium');
$res_std_id = get_rdata('res_std_id');
$res_cl_id = get_rdata('res_cl_id');
$res_total_marks = get_rdata('res_total_marks');
$res_obtain_marks = get_rdata('res_obtain_marks');
$res_examdate = get_rdata('res_examdate');
$res_image = get_rdata('res_image', 0);
$res_image_old = get_rdata('res_image_old', 0);
$res_image_small = get_rdata('res_image_small');
$res_status = get_rdata('res_status', 'A');
$res_create_date = $cur_date;
$res_create_by_id = $user_id;
$res_update_date = $cur_date;
$res_update_by_id = $user_id;


$caption = "Add Results";
$btn_caption = "Add Results";
if ($id != 0) {
    $caption = "Edit Results";
    $btn_caption = "Edit Results";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_results = new results();
    $sm_results->data["*"] = "";
    $sm_results->action = 'get';
    $sm_results->process_id = $id;
    $result = $sm_results->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $res_title = $db_row['res_title'];
                $res_description = $db_row['res_description'];
                $res_stu_id = $db_row['res_stu_id'];
                $res_sc_id = $db_row['res_sc_id'];
                $res_medium = $db_row['res_medium'];
                $res_total_marks = $db_row['res_total_marks'];
                $res_obtain_marks = $db_row['res_obtain_marks'];
                $res_std_id = $db_row['res_std_id'];
                $res_cl_id = $db_row['res_cl_id'];
                $res_image = $db_row['res_image'];
                $res_image_old = $db_row['res_image'];
                $res_examdate = convert_db_to_disp_date($db_row['res_examdate']) ;
                $res_image_small = $db_row['res_image_small'];
                $res_status = $db_row['res_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

    /*
      if ($res_title == '') {
      $errormsg = 'Results Title is required ';
      } else {
      $not_value = " AND res_sc_id = " . $res_sc_id;
      $arr_duplicate_name = found_duplicate('sm_results', 'res_title', $res_title, $not_value);
      if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
      } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Results Title';
      }
      }
     */

    if ($errormsg == '') {
        if ($_FILES['res_image']['error'] == 0) {  /// Image
            $file_array = explode(".", $_FILES['res_image']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else {
                $res_image = "results_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['res_image']['name']);
                $DestPath = RESULTS_IMAGE . $res_image;
                move_uploaded_file($_FILES['res_image']['tmp_name'], $DestPath);
            }
        } else if ($res_description == '') {
            $errormsg = 'Please enter result description and/or result image';
        }
    }
    if ($errormsg == '') {
        $sm_results = new results();
        $sm_results->data["res_title"] = $res_title;
        $sm_results->data["res_description"] = $res_description;
        $sm_results->data["res_stu_id"] = $res_stu_id;
        $sm_results->data["res_sc_id"] = $res_sc_id;
        $sm_results->data["res_medium"] = $res_medium;
        $sm_results->data["res_std_id"] = $res_std_id;
        $sm_results->data["res_cl_id"] = $res_cl_id;
        $sm_results->data["res_image"] = SITE_URL . "images/results/" . $res_image;
        $sm_results->data["res_image_small"] = $res_image_small;
        $sm_results->data["res_status"] = $res_status;
        $sm_results->data["res_total_marks"] = $res_total_marks;
        $sm_results->data["res_obtain_marks"] = $res_obtain_marks;
        $sm_results->data["res_examdate"] = convert_disp_to_db_date($res_examdate);
        $sm_results->data["res_create_date"] = $res_create_date;
        $sm_results->data["res_create_by_id"] = $res_create_by_id;
        $sm_results->data["res_update_date"] = $res_update_date;
        $sm_results->data["res_update_by_id"] = $res_update_by_id;
        $sm_results->action = 'insert';
        $result = $sm_results->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            if ($res_status == 'A') {
                $arr_data = array();
                $arr_data["not_message"] = "result:" . $res_title;
                $arr_data["not_sc_id"] = $res_sc_id;
                $arr_data["not_create_date"] = $cur_date;
                $arr_data["not_create_by_id"] = $user_id;
                $arr_data["not_update_date"] = $cur_date;
                $arr_data["not_update_by_id"] = $user_id;
                   $arr_data["not_goToScreen"]="result";

                $arr_data["gcmp_message"] = "result:" . $res_title;
                $arr_data["gcmp_title"] = "result:" . $res_title;
                $arr_data["gcmp_subtitle"] = "result:" . $res_title;
                $arr_data["gcmp_tickerText"] = "result:" . $res_title;
                $arr_data["gcmp_create_by"] = $user_id;
                $arr_data["gcmp_create_date"] = $cur_date;
                $arr_data["gcmp_gcm_sc_id"] = $res_sc_id;
                $arr_data["condition"] = "result";
                $arr_data["medium"] = $res_medium;
                $arr_data["std_id"] = $res_std_id;
                $arr_data["cl_id"] = $res_cl_id;
                $arr_data["stu_id"] = $res_stu_id;
                $arr_data["gcmp_goToScreen"]="result";

                add_notes($arr_data);
            }

            header('Location:manage_results.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
    /*
      if ($res_title == '') {
      $errormsg = 'Results Title is required ';
      } else {
      $not_value = " AND res_sc_id = " . $res_sc_id . " AND res_id != " . $id;
      $arr_duplicate_results_name = found_duplicate('sm_results', 'res_title', $res_title, $not_value);
      if ($arr_duplicate_results_name['error_message'] != '') {
      $errormsg = $arr_duplicate_results_name['error_message'];
      } else if ($arr_duplicate_results_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Results Title ';
      }
      }
     */
    if ($errormsg == '') {
        if ($_FILES['res_image']['error'] == 0) {  /// Image
            $file_array = explode(".", $_FILES['res_image']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else {
                $res_image = "results_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['res_image']['name']);
                $DestPath = RESULTS_IMAGE . $res_image;
                move_uploaded_file($_FILES['res_image']['tmp_name'], $DestPath);
                $res_image = SITE_URL . "/images/results/" . $res_image;
            }
        } else {
            $res_image = $res_image_old;
        }
    }

    if ($errormsg == '') {
        $sm_results = new results();

        $sm_results->data["res_title"] = $res_title;
        $sm_results->data["res_description"] = $res_description;
        $sm_results->data["res_stu_id"] = $res_stu_id;
        $sm_results->data["res_sc_id"] = $res_sc_id;
        $sm_results->data["res_medium"] = $res_medium;
        $sm_results->data["res_std_id"] = $res_std_id;
        $sm_results->data["res_cl_id"] = $res_cl_id;
        $sm_results->data["res_image"] = $res_image;
        $sm_results->data["res_image_small"] = $res_image_small;
        $sm_results->data["res_total_marks"] = $res_total_marks;
        $sm_results->data["res_obtain_marks"] = $res_obtain_marks;
        $sm_results->data["res_examdate"] = convert_disp_to_db_date($res_examdate);
        $sm_results->data["res_status"] = $res_status;
        $sm_results->data["res_create_date"] = $res_create_date;
        $sm_results->data["res_create_by_id"] = $res_create_by_id;
        $sm_results->data["res_update_date"] = $res_update_date;
        $sm_results->data["res_update_by_id"] = $res_update_by_id;


        $sm_results->action = 'update';
        $sm_results->process_id = $id;
        $result = $sm_results->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($res_image != '' && $res_image_old != '' && ($res_image != $res_image_old)) {
                $res_image_old = str_replace(SITE_URL, '', $res_image_old);
                $res_image_old = ".." . $res_image_old;
                unlink($res_image_old);
            }
            // If success then redirect to manage user page
            header('Location:manage_results.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

if (session_get('admin_login_type') == 'school') {
    $res_sc_id = session_get('admin_sc_id');
}
// echo 'error'.$errormsg.'end of error';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
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
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="res_image_old" name="res_image_old" value="<?php echo $res_image_old; ?>" />
                                    <input type="hidden" id="res_sc_id" name="res_sc_id" value="<?php echo $res_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Title</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="res_title" id="res_name"  placeholder="Results Title" value="<?php echo $res_title; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Description</label>
                                                <div class="col-sm-9">
                                                    <textarea name="res_description" id="res_description"  placeholder="Results Description" class="form-control" ><?php echo $res_description; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Total Marks</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="res_total_marks" id="res_total_marks"  placeholder="Total Marks" value="<?php echo $res_total_marks; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Obtain Marks</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="res_obtain_marks" id="res_obtain_marks"  placeholder="Obtain Marks" value="<?php echo $res_obtain_marks; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Exam Date</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="res_examdate" id="res_examdate" value="<?php echo $res_examdate;?>"  class="form-control datepicker">
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Medium</label>

                                                <div class="col-sm-9">
                                                    <select required class="form-control" id="res_medium" name="res_medium" onchange="fill_studnet();" >
                                                        <option value="" >--Please select--</option>
                                                        <option <?php if ($res_medium == 'English') echo 'selected="selected"'; ?>  value="English">English</option><option <?php if ($res_medium == 'Gujarati') echo 'selected="selected"'; ?> value="Gujarati">Gujarati</option></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Standard</label>
                                                <div class="col-sm-9">
<?php
$data_arr_input = array();
$data_arr_input['select_field'] = "std_id, std_name";
$data_arr_input['table'] = "sm_standard";
$data_arr_input['where'] = " std_status = 'A' AND std_sc_id = " . $tmp_admin_sc_id;
$data_arr_input['key_id'] = "std_id";
$data_arr_input['key_name'] = "std_name";
$data_arr_input['current_selection_value'] = $res_std_id;
?>
                                                    <select required class="form-control" id="res_std_id" name="res_std_id" onchange="fill_studnet();" >
                                                        <option value="" >--Please select--</option>
                                                    <?php display_dd_options($data_arr_input); ?></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Class</label>
                                                <div class="col-sm-9">
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = "cl_id, cl_name";
                                                        $data_arr_input['table'] = "sm_class";
                                                        $data_arr_input['where'] = " cl_status = 'A' AND cl_sc_id = " . $tmp_admin_sc_id;
                                                        $data_arr_input['key_id'] = "cl_id";
                                                        $data_arr_input['key_name'] = "cl_name";
                                                        $data_arr_input['current_selection_value'] = $res_cl_id;
                                                        ?>
                                                    <select required class="form-control"  id="res_cl_id" name="res_cl_id" onchange="fill_studnet();" >
                                                        <option value="" >--Please select--</option>
                                                    <?php display_dd_options($data_arr_input); ?></select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Student</label>
                                                <div class="col-sm-9">
                                                    <select required class="form-control" id="res_stu_id" name="res_stu_id" >
                                                        <option value="" >--Please select--</option>
<?php
if ($res_stu_id != 0 && $res_stu_id != "") {
    $q_stu = "SELECT stu.stu_id , stu.stu_gr_no , stu.stu_roll_no, stu.stu_first_name, stu.stu_last_name ,stu.stu_id FROM sm_student stu INNER JOIN  sm_standard std ON (std.std_id = stu.stu_std_id) INNER JOIN sm_class cl ON (stu.stu_cl_id = cl.cl_id) WHERE stu_sc_id = " . $tmp_admin_sc_id . " AND cl_sc_id = " . $tmp_admin_sc_id . " AND std_sc_id = " . $tmp_admin_sc_id . " AND stu_medium = '" . $res_medium . "' AND  cl.cl_id = " . $res_cl_id . " AND  std.std_id =" . $res_std_id;
    $result_stu = m_process("get_data", $q_stu);
    if ($result_stu['errormsg'] == '' && $result_stu['count'] > 0) {
        foreach ($result_stu['res'] as $db_row) {
            if ($db_row["stu_id"] == $res_stu_id) {
                echo '<option selected="selected" value="' . $db_row["stu_id"] . '">' . $db_row["stu_gr_no"] . '-' . $db_row["stu_first_name"] . ' ' . $db_row["stu_last_name"] . '</option>';
            } else {
                echo '<option value="' . $db_row["stu_id"] . '">' . $db_row["stu_gr_no"] . '-' . $db_row["stu_first_name"] . ' ' . $db_row["stu_last_name"] . '</option>';
            }
        }
    }
}
?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Image</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="res_image" id="res_image"  />
                                                        <?php if ($res_image_old != '') { ?>
                                                        <a href="<?php echo $res_image_old; ?>" target="_blank" >View Image</a>
<?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                    <input type="radio" name="res_status" id="res_status_a" <?php
                                                    if ($res_status == 'A') {
                                                        echo 'checked="checked"';
                                                    };
                                                    ?>  value="A" /><label for="res_status_a">Active</label> <input type="radio" name="res_status" id="res_status_i" value="I" <?php if ($res_status == 'I') echo 'checked="checked"'; ?> /><label for="res_status_i">InActive</label>
                                                </div>


                                            </div>



                                        </div>
                                    </div><!-- /.box -->
                                    <div class="box-footer">
<?php if ($id == 0) { ?>  <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                        <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                    </div><!-- /.box-footer -->
                                </form>
                                <!-- general form elements disabled -->
                                <div>
                                </div>
                            </div>
                            </section>
                        </div>
                        <!-- end of our page-->
<?php include("includes/footer.php"); ?>
                        <script type="text/javascript" language="javascript">
                            $('.datepicker').datepicker({
                                format: 'dd/mm/yyyy',
                                autoclose: true,
                            });
                        </script>
                    </div>
                    </body>
                    </html>
