<?php
include("includes/application_top.php");
include("../includes/class/exam.php");

// Set the caption of button
$id= get_rdata("id",0);
$act = get_rdata("act");

$ex_name = get_rdata('ex_name');
$eca_exc_id = get_rdata('eca_exc_id');
$eca_total_marks  = get_rdata('eca_total_marks');
$eca_obtain_marks = get_rdata('eca_obtain_marks');
$ex_date = get_rdata('ex_date', DBtoDisp($cur_date));
$ex_description = get_rdata('ex_description');
$ex_br_id = get_rdata('ex_br_id',$tmp_admin_id);
$ex_status = get_rdata('ex_status','A');
$ex_create_date = $cur_date;
$ex_create_by_id = $tmp_admin_id ;
$ex_update_date =$cur_date;
$ex_update_by_id = $tmp_admin_id;

$caption = "Add Exam";
$btn_caption = "Add Exam";
if ($id != 0) {
    $caption = "Edit Exam";
    $btn_caption = "Edit Exam";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$bln_allow_to_edit = true;
// Get the data from database
if ($act == '' && $id != 0) {
    $exam_master = new exam();
    $exam_master->data["*"] = "";
    $exam_master->action = 'get';
    $exam_master->process_id = $id;
    $result = $exam_master ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $ex_name = $db_row['ex_name'];
$ex_date = DBtoDisp($db_row['ex_date']);
$ex_description = $db_row['ex_description'];
$ex_br_id = $db_row['ex_br_id'];
$ex_status = $db_row['ex_status'];
$bln_allow_to_edit = allow_to_edit_exam($id);

            }
        }
    }
}

// Add user entry
if ($act == 'add') {

   $duplicate_q = "SELECT 1 FROM sm_exam WHERE ex_name = '".escape($ex_name)."' AND ex_date = '".disptoDB($ex_date)."' AND ex_br_id = ".$tmp_admin_id;
   $duplicate_r = m_process("get_data",$duplicate_q);
   if ($duplicate_r["status"] == 'error')
  {
      $errormsg = $duplicate_r['error_message'];
   }
   else if ($duplicate_r["count"] > 0)
   {
      $errormsg = "Duplicate entry for exam name and exam date";
   }
//   // validation or exploding data for exam categories.
//   if (!isset($eca_exc_id))
//   {
//       $errormsg = "Please select exam categories I.";
//   }
//   else if (count($eca_exc_id) == 0)
//   {
//       $errormsg = "Please select exam categories II.";
//   }
    if ($errormsg == '') {
        $exam_master  = new exam();
        $exam_master->data["ex_name"] =escape($ex_name);
        $exam_master->data["ex_date"] = disptoDB($ex_date);
        $exam_master->data["ex_description"] = escape($ex_description);
        $exam_master->data["ex_br_id"] = $ex_br_id;
        $exam_master->data["ex_status"] = $ex_status;
        $exam_master->data["ex_create_date"] = $ex_create_date;
        $exam_master->data["ex_create_by_id"] = $ex_create_by_id;
        $exam_master->data["ex_update_date"] = $ex_update_date;
        $exam_master->data["ex_update_by_id"] = $ex_update_by_id;

        $exam_master ->action = 'insert';

        $result = $exam_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                          if ($errormsg == '')
              {
                header('Location:manage_exam.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
                }
        }
    }
}

// Update user entry
if ($act == 'update') {


  $duplicate_q = "SELECT 1 FROM sm_exam WHERE ex_name = '".escape($ex_name)."' AND ex_date = '".disptoDB($ex_date)."' AND ex_br_id = ".$tmp_admin_id ." AND ex_id != ".$id;
  $duplicate_r = m_process("get_data",$duplicate_q);
  if ($duplicate_r["status"] == 'error')
 {
     $errormsg = $duplicate_r['error_message'];
  }
  else if ($duplicate_r["count"] > 0)
  {
     $errormsg = "Duplicate entry for exam name and exam date";
  }

  if ($errormsg == '') {
        $exam_master  = new exam();
        $exam_master->data["ex_name"] =escape($ex_name);
        $exam_master->data["ex_date"] = disptoDB($ex_date);
        $exam_master->data["ex_description"] = escape($ex_description);
        $exam_master->data["ex_br_id"] = $ex_br_id;
        $exam_master->data["ex_status"] = $ex_status;
        $exam_master->data["ex_create_date"] = $ex_create_date;
        $exam_master->data["ex_create_by_id"] = $ex_create_by_id;
        $exam_master->data["ex_update_date"] = $ex_update_date;
        $exam_master->data["ex_update_by_id"] = $ex_update_by_id;

        $exam_master ->action = 'update';
        $exam_master ->process_id = $id;
        $result = $exam_master ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
        /*
        if ($none_removal_ids !='')
        {
            $none_removal_ids_res =  remove_deallocation_exam_categories($none_removal_ids,$exam_id);
            if ($none_removal_ids_res!='')
            {
            $errormsg =   $none_removal_ids_res;
            }
        }
        */
        if ($errormsg == "")
        {  header('Location:manage_exam.php?msg=3&page=1&per_page=' . $per_page);
          exit(0);
        }
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
                                <form name="form1" id="form1" method="post" class="form-horizontal" >
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="ex_br_id" name="ex_br_id" value="<?php echo $ex_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="ex_name" id="ex_name"  placeholder="Name" value="<?php echo $ex_name; ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Date</label>
                                            <div class="col-sm-9">
                                                <input  <?php if ($bln_allow_to_edit == false) { echo "readonly"; }?> required type="text" name="ex_date" id="ex_date"  placeholder="Date" value="<?php echo $ex_date; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                              <textarea name="ex_description" id="ex_description"  placeholder="Description" class="form-control"><?php echo $ex_description; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="ex_status" id="ex_status_a" <?php
                                                if ($ex_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="ex_status_a">Active</label> <input type="radio" name="ex_status" id="ex_status_i" value="I" <?php if ($ex_status == 'I') echo 'checked="checked"'; ?> /><label for="ex_status_i">InActive</label>
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
                                            <button type="button" class="btn btn-info pull-right" onclick="validate_exam();" id="btnAddUser" name="btnAddUser">Save</button>
                                        </div><!-- /.box-footer -->
                            </form>
                            <!-- general form elements disabled -->
                            <div>
                        </div>
                    </div>

                </section>
            </div>
            <script>
            <?php if ($bln_allow_to_edit == true) { ?>
            $("#ex_date").datepicker({
                                            format: 'dd-mm-yyyy',
                                            autoclose: true, });
            <?php }?>
            </script>

            <!-- end of our page-->
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
