<?php
include("includes/application_top.php");
include("../includes/class/course_belt.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");

$cb_co_id = get_rdata('cb_co_id',0);
$cb_be_id = get_rdata('cb_be_id',0);
$cb_br_id = get_rdata('cb_br_id',$tmp_admin_id);
$cb_create_date = $cur_date;
$cb_create_by_id = $tmp_admin_id ;
$cb_update_date =$cur_date;
$cb_update_by_id = $tmp_admin_id;

$caption = "Add Course Belt";
$btn_caption = "Add Course Belt";
if ($id != 0) {
    $caption = "Edit Course Belt";
    $btn_caption = "Edit Course Belt";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $course_belt_master = new course_belt();
    $course_belt_master->data["*"] = "";
    $course_belt_master->action = 'get';
    $course_belt_master->process_id = $id;
    $result = $course_belt_master ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $cb_co_id = $db_row['cb_co_id'];
              $cb_be_id = $db_row['cb_be_id'];
              $cb_br_id = $db_row['cb_br_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
    $q_check = "SELECT 1 FROM sm_course_belt WHERE cb_co_id = $cb_co_id   AND cb_be_id =  $cb_be_id ";
    $arr_duplicate_name = check_duplicate_record_in_db($q_check);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for Course Belt ';
    }


    if ($errormsg == '') {
        $course_belt_master  = new course_belt();
        $course_belt_master->data["cb_co_id"] = $cb_co_id;
        $course_belt_master->data["cb_br_id"] = $cb_br_id;
        $course_belt_master->data["cb_be_id"] = $cb_be_id;
        $course_belt_master->data["cb_create_date"] = $cb_create_date;
        $course_belt_master->data["cb_create_by_id"] = $cb_create_by_id;
        $course_belt_master->data["cb_update_date"] = $cb_update_date;
        $course_belt_master->data["cb_update_by_id"] = $cb_update_by_id;

        $course_belt_master ->action = 'insert';
        $result = $course_belt_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_course_belt.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $q_check = "SELECT 1 FROM sm_course_belt WHERE cb_co_id = $cb_co_id   AND cb_be_id = ". $cb_be_id ." AND cb_id != ".$id;

  $arr_duplicate_name = check_duplicate_record_in_db($q_check);

  if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
  } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Course Belt';
  }

  if ($errormsg == '') {
        $course_belt_master  = new course_belt();
        $course_belt_master->data["cb_co_id"] = $cb_co_id;
        $course_belt_master->data["cb_br_id"] = $cb_br_id;
        $course_belt_master->data["cb_be_id"] = $cb_be_id;
        $course_belt_master->data["cb_create_date"] = $cb_create_date;
        $course_belt_master->data["cb_create_by_id"] = $cb_create_by_id;
        $course_belt_master->data["cb_update_date"] = $cb_update_date;
        $course_belt_master->data["cb_update_by_id"] = $cb_update_by_id;

        $course_belt_master ->action = 'update';
        $course_belt_master ->process_id = $id;
        $result = $course_belt_master ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

          header('Location:manage_course_belt.php?msg=3&page=1&per_page=' . $per_page);
          exit(0);
      }
    }
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_add_edit_circular();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="cb_br_id" name="cb_br_id" value="<?php echo $cb_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Course</label>
                                            <div class="col-sm-9">
                                                <select required id="cb_co_id" name="cb_co_id" class="form-control">
                                                    <option value="0">--Please select--</option>
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'co_name ,co_id';
                                                    $data_arr_input['table'] = 'sm_course';
                                                    $data_arr_input['where'] = " co_status  = 'A' ";
                                                    $data_arr_input['key_id'] = 'co_id';
                                                    $data_arr_input['key_name'] = 'co_name';
                                                    $data_arr_input['current_selection_value'] = $cb_co_id;
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Belt</label>
                                            <div class="col-sm-9">
                                                <select required id="cb_be_id" name="cb_be_id" class="form-control">
                                                    <option value="0">--Please select--</option>
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'be_name ,be_id';
                                                    $data_arr_input['table'] = 'sm_belt';
                                                    $data_arr_input['where'] = " be_status  = 'A' ";
                                                    $data_arr_input['key_id'] = 'be_id';
                                                    $data_arr_input['key_name'] = 'be_name';
                                                    $data_arr_input['order_by'] = 'be_sort_order';
                                                    $data_arr_input['current_selection_value'] = $cb_be_id;
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?>           <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
                    <script type="text/javascript" language="javascript">

                        </script>
    </body>
</html>
