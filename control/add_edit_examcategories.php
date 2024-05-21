<?php
include("includes/application_top.php");
include("../includes/class/examcategories.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");

$exc_parent_id = get_rdata('exc_parent_id');
$exc_group_name = get_rdata('exc_group_name');
$exc_name = get_rdata('exc_name');
$exc_marks = get_rdata('exc_marks',0);
$exc_status = get_rdata('exc_status','A');
$exc_br_id = $tmp_admin_id;
$exc_create_date = $cur_date;
$exc_create_by_id = $tmp_admin_id;
$exc_update_date = $cur_date;
$exc_update_by_id = $tmp_admin_id;

$caption = "Add Exam Categories";
$btn_caption = "Add Exam Categories";
if ($id != 0) {
    $caption = "Edit Exam Categories";
    $btn_caption = "Edit Exam Categories";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_student_course = new examcategories();
    $sm_student_course->data["*"] = "";
    $sm_student_course->action = 'get';
    $sm_student_course->process_id = $id;
    $result = $sm_student_course ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $exc_parent_id = $db_row['exc_parent_id'];
              $exc_group_name = $db_row['exc_group_name'];
              $exc_name = $db_row['exc_name'];
              $exc_marks = $db_row['exc_marks'];
              $exc_status = $db_row['exc_status'];
              $exc_br_id = $db_row['exc_br_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

   $duplicate_q = "SELECT 1 FROM sm_exam_categories WHERE exc_name   = '".$exc_name."' AND exc_br_id = ".$tmp_admin_id;
   $duplicate_r = m_process("get_data",$duplicate_q);
   if ($duplicate_r["status"] == 'error')
  {
      $errormsg = $duplicate_r['error_message'];
   }
   else if ($duplicate_r["count"] > 0)
   {
      $errormsg = "Duplicate entry for exam categories";
   }

    if ($errormsg == '') {
        $sm_student_course  = new examcategories();
        $sm_student_course->data["exc_parent_id"] = $exc_parent_id;
  $sm_student_course->data["exc_group_name"] = $exc_group_name;
  $sm_student_course->data["exc_name"] = $exc_name;
  $sm_student_course->data["exc_marks"] = $exc_marks;
  $sm_student_course->data["exc_status"] = $exc_status;
  $sm_student_course->data["exc_br_id"] = $exc_br_id;
  $sm_student_course->data["exc_create_date"] = $exc_create_date;
  $sm_student_course->data["exc_create_by_id"] = $exc_create_by_id;
  $sm_student_course->data["exc_update_date"] = $exc_update_date;
  $sm_student_course->data["exc_update_by_id"] = $exc_update_by_id;


        $sm_student_course ->action = 'insert';
        $result = $sm_student_course->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_examcategories.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $duplicate_q = "SELECT 1 FROM sm_exam_categories WHERE exc_name   = '".$exc_name."' AND exc_br_id = ".$tmp_admin_id." AND exc_id != ".$id;
  $duplicate_r = m_process("get_data",$duplicate_q);
  if ($duplicate_r["status"] == 'error')
 {
     $errormsg = $duplicate_r['error_message'];
  }
  else if ($duplicate_r["count"] > 0)
  {
     $errormsg = "Duplicate entry for exam categories";
  }

  if ($errormsg == '') {
        $sm_student_course  = new examcategories();
        $sm_student_course->data["exc_parent_id"] = $exc_parent_id;
$sm_student_course->data["exc_group_name"] = $exc_group_name;
$sm_student_course->data["exc_name"] = $exc_name;
$sm_student_course->data["exc_marks"] = $exc_marks;
$sm_student_course->data["exc_status"] = $exc_status;
$sm_student_course->data["exc_br_id"] = $exc_br_id;
$sm_student_course->data["exc_create_date"] = $exc_create_date;
$sm_student_course->data["exc_create_by_id"] = $exc_create_by_id;
$sm_student_course->data["exc_update_date"] = $exc_update_date;
$sm_student_course->data["exc_update_by_id"] = $exc_update_by_id;


        $sm_student_course ->action = 'update';
        $sm_student_course ->process_id = $id;
        $result = $sm_student_course ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

          header('Location:manage_examcategories.php?msg=3&page=1&per_page=' . $per_page);
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
                                    <input type="hidden" id="exc_br_id" name="exc_br_id" value="<?php echo $exc_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">P. Categories</label>
                                            <div class="col-sm-9">
                                              <select required id="exc_parent_id" name="exc_parent_id" class="form-control">
                                              <option value="0">--Please Select--</option>
                                              <?php
                                              $data_arr_input = array();
                                              $data_arr_input['select_field'] = 'exc_name, exc_id';
                                              $data_arr_input['table'] = 'sm_exam_categories';
                                              $data_arr_input['where'] = " exc_br_id = ".$tmp_admin_id." AND exc_status  = 'A' ";
                                              $data_arr_input['key_id'] = 'exc_id';
                                              $data_arr_input['key_name'] = 'exc_name';
                                              $data_arr_input['current_selection_value'] = $exc_parent_id;
                                              $data_arr_input['order_by'] = 'exc_id';
                                              display_dd_options($data_arr_input);
                                              ?>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="exc_name" id="exc_name"  placeholder="Name" value="<?php echo $exc_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Marks</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="exc_marks" id="exc_marks"  placeholder="Marks" value="<?php echo $exc_marks; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="exc_status" id="exc_status_a" <?php
                                                if ($exc_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="exc_status_a">Active</label> <input type="radio" name="exc_status" id="exc_status_i" value="I" <?php if ($exc_status == 'I') echo 'checked="checked"'; ?> /><label for="exc_status_i">InActive</label>
                                            </div>
                                        </div>
<!--                                <div class="form-group">
                                     <label class="col-sm-3 control-label">More Images</label>
                                     <div class="col-sm-9">
                                         <input type="file" name="circular_details_pic_0" />
                                         <input type="button" value="Add More" class="btn btn-success" onclick="add_files();" id="add_more" name="add_more" />
                                           </div>

                                     </div>-->
                                        <!-- /.box-body -->

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
            <script>
            $("#exc_date").datepicker({
                                            format: 'dd-mm-yyyy',
                                            autoclose: true, });
            </script>

            <!-- end of our page-->
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
