<?php
include("includes/application_top.php");
include("../includes/class/coursedetails.php");

// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$cd_name = get_rdata('cd_name');
$cd_br_id = get_rdata('cd_br_id');
$cd_brt_id = get_rdata('cd_brt_id');
$cd_co_id = get_rdata('cd_co_id');
$cd_be_id = get_rdata('cd_be_id');
$cd_status = get_rdata('cd_status', 'A');
$cd_eu_duration = get_rdata('cd_eu_duration', 0);
$cd_nu_duration = get_rdata('cd_nu_duration', 0);
$cd_eu_fee = get_rdata('cd_eu_fee', 0);
$cd_nu_fee = get_rdata('cd_nu_fee', 0);
$cd_eu_exam_fee = get_rdata('cd_eu_exam_fee', 0);
$cd_nu_exam_fee = get_rdata('cd_nu_exam_fee', 0);
$cd_eu_fee_onemonth = get_rdata('cd_eu_fee_onemonth', 0);
$cd_nu_fee_onemonth = get_rdata('cd_nu_fee_onemonth', 0);
$cd_create_date = $cur_date;
$cd_create_by_id = $tmp_admin_id;
$cd_update_date = $cur_date;
$cd_update_by_id = $tmp_admin_id;

$caption = "Add Course Details";
$btn_caption = "Add Course Details";
if ($id != 0) {
    $caption = "Edit Course Details";
    $btn_caption = "Edit Course Details";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_coursedetails = new coursedetails();
    $sm_coursedetails->data["*"] = "";
    $sm_coursedetails->action = 'get';
    $sm_coursedetails->process_id = $id;
    $result = $sm_coursedetails->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $cd_name = $db_row['cd_name'];
                $cd_br_id = $db_row['cd_br_id'];
                $cd_brt_id = $db_row['cd_brt_id'];
                $cd_co_id = $db_row['cd_co_id'];
                $cd_be_id = $db_row['cd_be_id'];
                $cd_status = $db_row['cd_status'];
                $cd_eu_duration = $db_row['cd_eu_duration'];
                $cd_nu_duration = $db_row['cd_nu_duration'];
                $cd_eu_fee = $db_row['cd_eu_fee'];
                $cd_nu_fee = $db_row['cd_nu_fee'];
                $cd_eu_exam_fee = $db_row['cd_eu_exam_fee'];
                $cd_nu_exam_fee = $db_row['cd_nu_exam_fee'];
                $cd_eu_fee_onemonth = $db_row['cd_eu_fee_onemonth'];
                $cd_nu_fee_onemonth = $db_row['cd_nu_fee_onemonth'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

    $not_value = " AND cd_br_id = $tmp_admin_id";
    $arr_duplicate_name = found_duplicate('sm_course_details', 'cd_name', $cd_name, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for course details name. ';
    }


    if ($errormsg == '') {
        $sm_coursedetails = new coursedetails();

$sm_coursedetails->data["cd_name"] = $cd_name;
$sm_coursedetails->data["cd_br_id"] = $tmp_admin_id;
$sm_coursedetails->data["cd_brt_id"] = $cd_brt_id;
$sm_coursedetails->data["cd_co_id"] = $cd_co_id;
$sm_coursedetails->data["cd_be_id"] = $cd_be_id;
$sm_coursedetails->data["cd_status"] = $cd_status;
$sm_coursedetails->data["cd_eu_duration"] = $cd_eu_duration;
$sm_coursedetails->data["cd_nu_duration"] = $cd_nu_duration;
$sm_coursedetails->data["cd_eu_fee"] = $cd_eu_fee;
$sm_coursedetails->data["cd_nu_fee"] = $cd_nu_fee;
$sm_coursedetails->data["cd_eu_exam_fee"] = $cd_eu_exam_fee;
$sm_coursedetails->data["cd_nu_exam_fee"] = $cd_nu_exam_fee;
$sm_coursedetails->data["cd_eu_fee_onemonth"] = $cd_eu_fee_onemonth;
$sm_coursedetails->data["cd_nu_fee_onemonth"] = $cd_nu_fee_onemonth;
$sm_coursedetails->data["cd_create_date"] = $cd_create_date;
$sm_coursedetails->data["cd_create_by_id"] = $cd_create_by_id;
$sm_coursedetails->data["cd_update_date"] = $cd_update_date;
$sm_coursedetails->data["cd_update_by_id"] = $cd_update_by_id;


        $sm_coursedetails->action = 'insert';
        $result = $sm_coursedetails->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            header('Location:manage_coursedetails.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);

            // end of needs to update login details.
        }
    }
}

// Update user entry
if ($act == 'update') {
    $not_value = " AND cd_br_id = $tmp_admin_id  AND cd_id != " . $id;
    $arr_duplicate_name = found_duplicate('sm_course_details', 'cd_name', $cd_name, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for course details names. ';
    }

    if ($errormsg == '') {


        $sm_coursedetails = new coursedetails();
        $sm_coursedetails->data["cd_name"] = $cd_name;
        $sm_coursedetails->data["cd_br_id"] = $tmp_admin_id;
        $sm_coursedetails->data["cd_brt_id"] = $cd_brt_id;
        $sm_coursedetails->data["cd_co_id"] = $cd_co_id;
        $sm_coursedetails->data["cd_be_id"] = $cd_be_id;
        $sm_coursedetails->data["cd_status"] = $cd_status;
        $sm_coursedetails->data["cd_eu_duration"] = $cd_eu_duration;
        $sm_coursedetails->data["cd_nu_duration"] = $cd_nu_duration;
        $sm_coursedetails->data["cd_eu_fee"] = $cd_eu_fee;
        $sm_coursedetails->data["cd_nu_fee"] = $cd_nu_fee;
        $sm_coursedetails->data["cd_eu_exam_fee"] = $cd_eu_exam_fee;
        $sm_coursedetails->data["cd_nu_exam_fee"] = $cd_nu_exam_fee;
        $sm_coursedetails->data["cd_eu_fee_onemonth"] = $cd_eu_fee_onemonth;
        $sm_coursedetails->data["cd_nu_fee_onemonth"] = $cd_nu_fee_onemonth;
        $sm_coursedetails->data["cd_update_date"] = $cd_update_date;
        $sm_coursedetails->data["cd_update_by_id"] = $cd_update_by_id;


        $sm_coursedetails->action = 'update';
        $sm_coursedetails->process_id = $id;
        $result = $sm_coursedetails->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            header('Location:manage_coursedetails.php?msg=3&page=1&per_page=' . $per_page);
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
                                <form name="form1"  id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />


                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <!-- START -->
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Course D. Name</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_name" id="cd_name"  placeholder="Course D. Name" value="<?php echo $cd_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Batch Type</label>
                                                <div class="col-sm-9">
                                                    <select required id="cd_brt_id" name="cd_brt_id" class="form-control">
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'brt_name ,brt_id';
                                                        $data_arr_input['table'] = 'sm_branch_type';
                                                        $data_arr_input['where'] = "";
                                                        // $data_arr_input['where'] = " brt_status = 'A'  ";
                                                        $data_arr_input['key_id'] = 'brt_id';
                                                        $data_arr_input['key_name'] = 'brt_name';
                                                        $data_arr_input['current_selection_value'] = $cd_brt_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Course</label>
                                                <div class="col-sm-9">
                                                    <select required id="cd_co_id" name="cd_co_id" class="form-control">
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'co_name ,co_id';
                                                        $data_arr_input['table'] = 'sm_course';
                                                        $data_arr_input['where'] = " co_status = 'A'  ";
                                                        $data_arr_input['key_id'] = 'co_id';
                                                        $data_arr_input['key_name'] = 'co_name';
                                                        $data_arr_input['current_selection_value'] = $cd_co_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Belt</label>
                                                <div class="col-sm-9">
                                                    <select required id="cd_be_id" name="cd_be_id" class="form-control">
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'be_name ,be_id';
                                                        $data_arr_input['table'] = 'sm_belt';
                                                        $data_arr_input['where'] = " be_status = 'A'  ";
                                                        $data_arr_input['key_id'] = 'be_id';
                                                        $data_arr_input['key_name'] = 'be_name';
                                                        $data_arr_input['order_by'] = 'be_id';
                                                        $data_arr_input['current_selection_value'] = $cd_be_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Ex. U. Duration</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_eu_duration" id="cd_eu_duration"  placeholder="Ex. U. Duration" value="<?php echo $cd_eu_duration; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">N. U. Duration</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_nu_duration" id="cd_nu_duration"  placeholder="Ex. U. Duration" value="<?php echo $cd_nu_duration; ?>" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">E. U. Fee</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_eu_fee" id="cd_eu_fee"  placeholder="N. U. Duration" value="<?php echo $cd_eu_fee; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">N. U. Fee</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_nu_fee" id="cd_nu_fee"  placeholder="Ex. U. Fee" value="<?php echo $cd_nu_fee; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">E. U. Ex.Fee</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_eu_exam_fee" id="cd_eu_exam_fee"  placeholder="Ex. U. Fee" value="<?php echo $cd_eu_exam_fee; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">N. U. Ex.Fee</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_nu_exam_fee" id="cd_nu_exam_fee"  placeholder="cd_nu_exam_fee" value="<?php echo $cd_nu_exam_fee; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Ex. U. 1M. Fee</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_eu_fee_onemonth" id="cd_eu_fee_onemonth"  placeholder="cd_eu_fee_onemonth" value="<?php echo $cd_eu_fee_onemonth; ?>"  class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Ex. Nu. 1M. Fee</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cd_nu_fee_onemonth" id="cd_nu_fee_onemonth"  placeholder="cd_nu_fee_onemonth" value="<?php echo $cd_nu_fee_onemonth; ?>" class="form-control" />
                                                </div>
                                            </div>

                                            <!-- END -->
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                    <input type="radio" name="cd_status" id="cd_status_a" <?php
                                                    if ($cd_status == 'A') {
                                                        echo 'checked="checked"';
                                                    };
                                                    ?>  value="A" /><label for="cd_status_a">Active</label> <input type="radio" name="cd_status" id="cd_status_i" value="I" <?php if ($cd_status == 'I') echo 'checked="checked"'; ?> /><label for="cd_status_i">InActive</label>
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
                        <!-- end of our page-->
                        <?php include("includes/footer.php"); ?>
                    </div>
                    </body>
                    </html>
