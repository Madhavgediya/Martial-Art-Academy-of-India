<?php
include("includes/application_top.php");
include("../includes/class/student_course.php");

// Set the caption of button



$id = get_rdata("id", 0);
$act = get_rdata("act");
$sc_stu_id = get_rdata('sc_stu_id');
$sc_cd_id = get_rdata('sc_cd_id');
$sc_joined_date = get_rdata('sc_joined_date');
if ($sc_joined_date == '') {
    $sc_joined_date = DBtoDisp($cur_date);
}
$sc_total_fee = get_rdata('sc_total_fee',0);
$sc_total_paid = get_rdata('sc_total_paid',0);
$sc_full_fee_paid = get_rdata('sc_full_fee_paid',0);
$sc_is_current = get_rdata('sc_is_current');
$sc_create_date = $cur_date;
$sc_create_by_id = $tmp_admin_id;
$sc_update_date = $cur_date;
$sc_update_by_id = $tmp_admin_id;

$caption = "Add Student Course Details";
$btn_caption = "Add Student Course Details";
if ($id != 0) {
    $caption = "Edit Student Course Details";
    $btn_caption = "Edit Student Course Details";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_student_course = new studentcourse();
    $sm_student_course->data["*"] = "";
    $sm_student_course->action = 'get';
    $sm_student_course->process_id = $id;
    $result = $sm_student_course->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $sc_stu_id = $db_row['sc_stu_id'];
                $sc_cd_id = $db_row['sc_cd_id'];
                $sc_joined_date = DBtoDisp($db_row['sc_joined_date']);
                $sc_total_fee = $db_row['sc_total_fee'];
                $sc_total_paid = $db_row['sc_total_paid'];
                $sc_full_fee_paid = $db_row['sc_full_fee_paid'];
                $sc_is_current = $db_row['sc_is_current'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

//    $not_value = " AND cd_br_id = $tmp_admin_id";
//    $arr_duplicate_name = found_duplicate('sm_course_details', 'cd_name', $cd_name, $not_value);
//    if ($arr_duplicate_name['error_message'] != '') {
//        $errormsg = $arr_duplicate_name['error_message'];
//    } else if ($arr_duplicate_name['duplicate'] == true) {
//        $errormsg = 'Duplicate entry for course details name. ';
//    }


    if ($errormsg == '') {
        $sm_student_course = new studentcourse();
        $arr_get_course_details =  get_course_pricing($sc_cd_id);
        if ($arr_get_course_details["errormsg"]!="")
        {
            $errormsg = $arr_get_course_details["errormsg"];
        }
        else {
          $sm_student_course->data["sc_stu_id"] = $sc_stu_id;
          $sm_student_course->data["sc_cd_id"] = $sc_cd_id;
          $sm_student_course->data["sc_joined_date"] = disptoDB($sc_joined_date);
          $sm_student_course->data["sc_total_fee"] = $arr_get_course_details["cd_eu_fee"];
          $sm_student_course->data["sc_total_paid"] = 0;
          $sm_student_course->data["sc_full_fee_paid"] = 'N';
          $sm_student_course->data["sc_is_current"] = $sc_is_current;
          $sm_student_course->data["sc_create_date"] = $sc_create_date;
          $sm_student_course->data["sc_create_by_id"] = $sc_create_by_id;
          $sm_student_course->data["sc_update_date"] = $sc_update_date;
          $sm_student_course->data["sc_update_by_id"] = $sc_update_by_id;
          $sm_student_course->action = 'insert';
          $result = $sm_student_course->process();
          if ($result['status'] == 'failure') {
              $errormsg = $result['errormsg'];
          } else {

              header('Location:manage_student_course.php?sc_stu_id='.$sc_stu_id.'&msg=2&page=1&per_page=' . $per_page);
              exit(0);

              // end of needs to update login details.
          }
        }

    }
}

// Update user entry
if ($act == 'update') {
//    $not_value = " AND cd_br_id = $tmp_admin_id  AND cd_id != " . $id;
//    $arr_duplicate_name = found_duplicate('sm_course_details', 'cd_name', $cd_name, $not_value);
//    if ($arr_duplicate_name['error_message'] != '') {
//        $errormsg = $arr_duplicate_name['error_message'];
//    } else if ($arr_duplicate_name['duplicate'] == true) {
//        $errormsg = 'Duplicate entry for course details names. ';
//    }

    if ($errormsg == '') {


        $sm_student_course = new studentcourse();
        $sm_student_course->data["sc_stu_id"] = $sc_stu_id;
        $sm_student_course->data["sc_cd_id"] = $sc_cd_id;
        $sm_student_course->data["sc_joined_date"] = disptoDB($sc_joined_date);
        $sm_student_course->data["sc_total_fee"] = $sc_total_fee;
        $sm_student_course->data["sc_total_paid"] = $sc_total_paid;
        $sm_student_course->data["sc_full_fee_paid"] = $sc_full_fee_paid;
        $sm_student_course->data["sc_is_current"] = $sc_is_current;
        $sm_student_course->data["sc_create_date"] = $sc_create_date;
        $sm_student_course->data["sc_create_by_id"] = $sc_create_by_id;
        $sm_student_course->data["sc_update_date"] = $sc_update_date;
        $sm_student_course->data["sc_update_by_id"] = $sc_update_by_id;



        $sm_student_course->action = 'update';
        $sm_student_course->process_id = $id;
        $result = $sm_student_course->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            header('Location:manage_student_course.php?sc_stu_id='.$sc_stu_id.'&msg=3&page=1&per_page=' . $per_page);
        }
    }
}
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
                                    <input type="hidden" id="sc_stu_id" name="sc_stu_id" value="<?php echo $sc_stu_id; ?>" />


                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <!-- START -->

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Course</label>
                                                <div class="col-sm-9">
                                                    <select required id="sc_cd_id" name="sc_cd_id" class="form-control">
                                                        <option value="">--Please select--</option>
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'cd_name ,cd_id';
                                                        $data_arr_input['table'] = 'sm_course_details';
                                                        $data_arr_input['where'] = " cd_status  = 'A' ";
                                                        $data_arr_input['key_id'] = 'cd_id';
                                                        $data_arr_input['key_name'] = 'cd_name';
                                                        $data_arr_input['current_selection_value'] = $sc_cd_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Joined Date</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="sc_joined_date" id="sc_joined_date"  placeholder="Joining Date" value="<?php echo $sc_joined_date; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Current Course?</label>
                                                <div class="col-sm-9">
                                                    <select required id="sc_cd_id" name="sc_is_current" class="form-control">
                                                        <option value="">--Please select--</option>
                                                        <option value="1" <?php if ($sc_cd_id == 1) echo 'selected'; ?>>Yes</option>
                                                        <option value="0" <?php if ($sc_cd_id == 0) echo 'selected'; ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
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
                        <script>
                            $("#sc_joined_date").datepicker({
                                format: 'dd-mm-yyyy',
                                startDate: new Date(),
                                autoclose: true, });

                        </script>
                        <!-- end of our page-->
                        <?php include("includes/footer.php"); ?>
                    </div>
                    </body>
                    </html>
