<?php
include("includes/application_top.php");
include("../includes/class/timetable.php");
// Set the caption of button




$id = get_rdata("id", 0);
$act = get_rdata("act");
$tt_id = get_rdata('tt_id');
$tt_title = get_rdata('tt_title');
$tt_medium = get_rdata('tt_medium');
$tt_std_id = get_rdata('tt_std_id');
$tt_sc_id = get_rdata('tt_sc_id');
$tt_cl_id = get_rdata('tt_cl_id');
$tt_image = get_rdata('tt_image');
$tt_image_small = get_rdata('tt_image_small');
$tt_image_old = get_rdata('tt_image_old');
$tt_image_small_old = get_rdata('tt_image_small_old');
$tt_status = get_rdata('tt_status', 'A');
$tt_create_date = $cur_date;
$tt_create_by_id = $user_id;
$tt_update_date = $cur_date;
$tt_update_by_id = $user_id;

$caption = "Add Time Table";
$btn_caption = "Add Time Table";
if ($id != 0) {
    $caption = "Edit Time Table";
    $btn_caption = "Edit Time Table";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_timetable = new timetable();
    $sm_timetable->data["*"] = "";
    $sm_timetable->action = 'get';
    $sm_timetable->process_id = $id;
    $result = $sm_timetable->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $tt_title = $db_row['tt_title'];
                $tt_medium = $db_row['tt_medium'];
                $tt_std_id = $db_row['tt_std_id'];
                $tt_cl_id = $db_row['tt_cl_id'];
                $tt_std_id = get_rdata('tt_std_id');
                $tt_sc_id = $db_row['tt_sc_id'];
                $tt_image = $db_row['tt_image'];
                $tt_image_small = $db_row['tt_image_small'];
                $tt_image_old = $db_row['tt_image'];
                $tt_image_small_old = $db_row['tt_image_small'];
                $tt_status = $db_row['tt_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {


    if ($tt_title == '') {
        $errormsg = 'Time Table Title is required ';
    } else {
        $not_value = " AND tt_sc_id = " . $tt_sc_id;
        $arr_duplicate_name = found_duplicate('sm_timetable', 'tt_title', $tt_title, $not_value);
        if ($arr_duplicate_name['error_message'] != '') {
            $errormsg = $arr_duplicate_name['error_message'];
        } else if ($arr_duplicate_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for Time Table Title';
        }
    }

    if ($errormsg == '') {
        if ($_FILES['tt_image']['error'] == 0) {  /// Image
            $file_array = explode(".", $_FILES['tt_image']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else {
                $tt_image = "timetable_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['tt_image']['name']);
                $DestPath = TIMETABLE_IMAGE . $tt_image;
                move_uploaded_file($_FILES['tt_image']['tmp_name'], $DestPath);
            }
        } else {
            $errormsg = 'Please upload timetable photo';
        }
    }
    if ($errormsg == '') {
        $sm_timetable = new timetable();
        $sm_timetable->data["tt_title"] = $tt_title;
        $sm_timetable->data["tt_medium"] = $tt_medium;
        $sm_timetable->data["tt_sc_id"] = $tt_sc_id;
        $sm_timetable->data["tt_std_id"] = $tt_std_id;
        $sm_timetable->data["tt_cl_id"] = $tt_cl_id;
        $sm_timetable->data["tt_image"] = SITE_URL . "images/timetable/" . $tt_image;
        $sm_timetable->data["tt_image_small"] = $tt_image_small;
        $sm_timetable->data["tt_status"] = $tt_status;
        $sm_timetable->data["tt_create_date"] = $tt_create_date;
        $sm_timetable->data["tt_create_by_id"] = $tt_create_by_id;
        $sm_timetable->data["tt_update_date"] = $tt_update_date;
        $sm_timetable->data["tt_update_by_id"] = $tt_update_by_id;


        $sm_timetable->action = 'insert';
        $result = $sm_timetable->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            if ($tt_status == 'A')
                {
                    $arr_data = array();
                    $arr_data["not_message"] = "timetable:".$tt_title;
                    $arr_data["not_sc_id"] = $tt_sc_id;
                    $arr_data["not_create_date"] = $cur_date;
                    $arr_data["not_create_by_id"] = $user_id;
                    $arr_data["not_update_date"] = $cur_date;
                    $arr_data["not_update_by_id"] = $user_id;
                      $arr_data["not_goToScreen"]="timetable";

                    $arr_data["gcmp_message"] =  "timetable:".$tt_title;
                    $arr_data["gcmp_title"] = "timetable:".$tt_title;
                    $arr_data["gcmp_subtitle"] = "timetable:".$tt_title;
                    $arr_data["gcmp_tickerText"] = "timetable:".$tt_title;
                    $arr_data["gcmp_create_by"]=$user_id;
                    $arr_data["gcmp_create_date"]=$cur_date;
                    $arr_data["gcmp_gcm_sc_id"]=$tt_sc_id;
                    $arr_data["condition"]= "timetable";
                    $arr_data["medium"]= $tt_medium;
                    $arr_data["std_id"]= $tt_std_id;
                    $arr_data["cl_id"]= $tt_cl_id;
                    $arr_data["gcmp_goToScreen"]="timetable";

                    add_notes($arr_data);

                }

            header('Location:manage_timetable.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
    if ($tt_title == '') {
        $errormsg = 'Time Table Title is required ';
    } else {
        $not_value = " AND tt_sc_id = " . $tt_sc_id . " AND tt_id != " . $id;
        $arr_duplicate_timetable_name = found_duplicate('sm_timetable', 'tt_title', $tt_title, $not_value);
        if ($arr_duplicate_timetable_name['error_message'] != '') {
            $errormsg = $arr_duplicate_timetable_name['error_message'];
        } else if ($arr_duplicate_timetable_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for Time Table Title ';
        }
    }

    if ($errormsg == '') {
        if ($_FILES['tt_image']['error'] == 0) {  /// Image
            $file_array = explode(".", $_FILES['tt_image']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else {
                $tt_image = "timetable_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['tt_image']['name']);
                $DestPath = TIMETABLE_IMAGE . $tt_image;
                move_uploaded_file($_FILES['tt_image']['tmp_name'], $DestPath);
                $tt_image = SITE_URL . "/images/timetable/" . $tt_image;
            }
        } else {
            $tt_image = $tt_image_old;
        }
    }

    if ($errormsg == '') {
        $sm_timetable = new timetable();
        $sm_timetable->data["tt_title"] = $tt_title;
        $sm_timetable->data["tt_medium"] = $tt_medium;
        $sm_timetable->data["tt_sc_id"] = $tt_sc_id;
        $sm_timetable->data["tt_std_id"] = $tt_std_id;
        $sm_timetable->data["tt_cl_id"] = $tt_cl_id;
        $sm_timetable->data["tt_image"] = $tt_image;
        $sm_timetable->data["tt_image_small"] = $tt_image_small;
        $sm_timetable->data["tt_status"] = $tt_status;
        $sm_timetable->data["tt_create_date"] = $tt_create_date;
        $sm_timetable->data["tt_create_by_id"] = $tt_create_by_id;
        $sm_timetable->data["tt_update_date"] = $tt_update_date;
        $sm_timetable->data["tt_update_by_id"] = $tt_update_by_id;
        $sm_timetable->action = 'update';
        $sm_timetable->process_id = $id;
        $result = $sm_timetable->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($tt_image != '' && $tt_image_old != '' && ($tt_image != $tt_image_old)) {
                $tt_image_old = str_replace(SITE_URL, '', $tt_image_old);
                $tt_image_old = ".." . $tt_image_old;
                unlink($tt_image_old);
            }
            // If success then redirect to manage user page
            header('Location:manage_timetable.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

if (session_get('admin_login_type') == 'school') {
    $tt_sc_id = session_get('admin_sc_id');
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
                                    <input type="hidden" id="tt_image_old" name="tt_image_old" value="<?php echo $tt_image_old; ?>" />
                                    <input type="hidden" id="tt_sc_id" name="tt_sc_id" value="<?php echo $tt_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Title</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="tt_title" id="tt_name"  placeholder="Time Table Title" value="<?php echo $tt_title; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Medium</label>

                                                <div class="col-sm-9">
                                                    <select required class="form-control" id="tt_medium" name="tt_medium" ><option <?php if ($tt_medium == 'English') echo 'selected="selected"'; ?>  value="English">English</option><option <?php if ($tt_medium == 'Gujarati') echo 'selected="selected"'; ?> value="Gujarati">Gujarati</option></select>
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
                                                                    $data_arr_input['current_selection_value'] = $tt_std_id;
                                                                    ?>
                                                                    <select required class="form-control" id="tt_std_id" name="tt_std_id" ><?php display_dd_options($data_arr_input); ?></select>
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
                                                $data_arr_input['current_selection_value'] = $tt_cl_id;
                                                ?>
                                                <select required class="form-control" id="tt_cl_id" name="tt_cl_id" ><?php display_dd_options($data_arr_input); ?></select>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label  class="col-sm-3 control-label">Image</label>
                                                <div class="col-sm-9">
                                                    <input <?php if ($id ==0 )  {echo "required"; }  ?> type="file" name="tt_image" id="tt_image"  />
                                                    <?php if ($tt_image_old != '') { ?>
                                                        <a href="<?php echo $tt_image_old; ?>" target="_blank" >View Image</a>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                    <input type="radio" name="tt_status" id="tt_status_a" <?php
                                                    if ($tt_status == 'A') {
                                                        echo 'checked="checked"';
                                                    };
                                                    ?>  value="A" /><label for="tt_status_a">Active</label> <input type="radio" name="tt_status" id="tt_status_i" value="I" <?php if ($tt_status == 'I') echo 'checked="checked"'; ?> /><label for="tt_status_i">InActive</label>
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
                    </div>
                    </body>
                    </html>
