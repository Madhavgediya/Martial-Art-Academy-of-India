<?php
include("includes/application_top.php");
include("../includes/class/event.php");

// Set the caption of button
$id= get_rdata("id",0);
$act = get_rdata("act");

$ev_name = get_rdata('ev_name',date("Ymdhmis"));
$eca_exc_id = get_rdata('eca_exc_id');
$eca_total_marks  = get_rdata('eca_total_marks');
$eca_obtain_marks = get_rdata('eca_obtain_marks');
$ev_date = get_rdata('ev_date', DBtoDisp($cur_date));
$ev_end_date = get_rdata('ev_end_date', DBtoDisp($cur_date));
$ev_description = get_rdata('ev_description');
$ev_eu_exam_fee = get_rdata('ev_eu_exam_fee',0);
$ev_nu_exam_fee  = get_rdata('ev_nu_exam_fee',0);
$ev_br_id = get_rdata('ev_br_id',$tmp_admin_id);
$ev_status = get_rdata('ev_status','A');
$ev_create_date = $cur_date;
$ev_create_by_id = $tmp_admin_id ;
$ev_update_date =$cur_date;
$ev_update_by_id = $tmp_admin_id;

$caption = "Add Event";
$btn_caption = "Add Event";
if ($id != 0) {
    $caption = "Edit Event";
    $btn_caption = "Edit Event";
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
    $event_master = new event();
    $event_master->data["*"] = "";
    $event_master->action = 'get';
    $event_master->process_id = $id;
    $result = $event_master ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $ev_name = $db_row['ev_name'];
$ev_date = DBtoDisp($db_row['ev_date']);
$ev_end_date = DBtoDisp($db_row['ev_end_date']);
$ev_description = $db_row['ev_description'];
$ev_eu_exam_fee = $db_row['ev_eu_exam_fee'];
$ev_nu_exam_fee = $db_row['ev_nu_exam_fee'];
$ev_br_id = $db_row['ev_br_id'];

$ev_status = $db_row['ev_status'];
$bln_allow_to_edit = allow_to_edit_event($id);

            }
        }
    }
}

// Add user entry
if ($act == 'add') {

   $duplicate_q = "SELECT 1 FROM sm_event WHERE ev_name = '".escape($ev_name)."' AND ev_date = '".disptoDB($ev_date)."' AND ev_br_id = ".$tmp_admin_id;
   $duplicate_r = m_process("get_data",$duplicate_q);
   if ($duplicate_r["status"] == 'error')
  {
      $errormsg = $duplicate_r['error_message'];
   }
   else if ($duplicate_r["count"] > 0)
   {
      $errormsg = "Duplicate entry for event name and event date";
   }
  // validation or exploding data for event categories.
    if ($errormsg == '') {
        $event_master  = new event();
        $event_master->data["ev_name"] = escape($ev_name);
        $event_master->data["ev_date"] = disptoDB($ev_date);
        $event_master->data["ev_end_date"] = disptoDB($ev_end_date);
        $event_master->data["ev_description"] = escape($ev_description);
        $event_master->data["ev_eu_exam_fee"] = $ev_eu_exam_fee;
        $event_master->data["ev_nu_exam_fee"] = $ev_nu_exam_fee;
        $event_master->data["ev_br_id"] = $ev_br_id;
        $event_master->data["ev_status"] = $ev_status;
        $event_master->data["ev_create_date"] = $ev_create_date;
        $event_master->data["ev_create_by_id"] = $ev_create_by_id;
        $event_master->data["ev_update_date"] = $ev_update_date;
        $event_master->data["ev_update_by_id"] = $ev_update_by_id;

        $event_master ->action = 'insert';

        $result = $event_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_event.php?msg=2&page=1&per_page=' . $per_page);
               exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') 

{


  $duplicate_q = "SELECT 1 FROM sm_event WHERE ev_name = '".escape($ev_name)."' AND ev_date = '".disptoDB($ev_date)."' AND ev_br_id = ".$tmp_admin_id ." AND ev_id != ".$id;
  $duplicate_r = m_process("get_data",$duplicate_q);
  if ($duplicate_r["status"] == 'error')
 {
     $errormsg = $duplicate_r['error_message'];
  }
  else if ($duplicate_r["count"] > 0)
  {
     $errormsg = "Duplicate entry for event name and event date";
  }

  if ($errormsg == '') {
        $event_master  = new event();
        $event_master->data["ev_name"] = escape($ev_name);
        $event_master->data["ev_date"] = disptoDB($ev_date);
        $event_master->data["ev_end_date"] = disptoDB($ev_end_date);
        $event_master->data["ev_description"] = escape($ev_description);
        $event_master->data["ev_eu_exam_fee"] = $ev_eu_exam_fee;
        $event_master->data["ev_nu_exam_fee"] = $ev_nu_exam_fee;
  $event_master->data["ev_br_id"] = $ev_br_id;
  $event_master->data["ev_status"] = $ev_status;
  $event_master->data["ev_create_date"] = $ev_create_date;
  $event_master->data["ev_create_by_id"] = $ev_create_by_id;
  $event_master->data["ev_update_date"] = $ev_update_date;
  $event_master->data["ev_update_by_id"] = $ev_update_by_id;

        $event_master ->action = 'update';
        $event_master ->process_id = $id;
        $result = $event_master ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
          
          header('Location:manage_event.php?msg=3&page=1&per_page=' . $per_page);
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
                                <form name="form1" id="form1" method="post" class="form-horizontal" >
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="ev_br_id" name="ev_br_id" value="<?php echo $ev_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required  type="text" name="ev_name" id="ev_name"  placeholder="Name" value="<?php echo $ev_name; ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Start Date</label>
                                            <div class="col-sm-9">
                                                <input required <?php if ($bln_allow_to_edit == false) { echo "readonly"; }?> type="text" name="ev_date" id="ev_date"  placeholder="Date" value="<?php echo $ev_date; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">End Date</label>
                                            <div class="col-sm-9">
                                            <?php /* ?>  <input required <?php if ($bln_allow_to_edit == false) { echo "readonly"; }?> type="text" name="ev_end_date" id="ev_end_date"  placeholder="End Date" value="<?php echo $ev_end_date; ?>" class="form-control" /> <?php */ ?>
                                                <input required type="text" name="ev_end_date" id="ev_end_date"  placeholder="End Date" value="<?php echo $ev_end_date; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                              <textarea name="ev_description" id="ev_description"  placeholder="Description" class="form-control"><?php echo $ev_name; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Event Fee</label>
                                            <div class="col-sm-9">
                                                <input required <?php if ($bln_allow_to_edit == false) { echo "readonly"; }?>    type="text" name="ev_eu_exam_fee" id="ev_eu_exam_fee"  placeholder="Event Fee" value="<?php echo $ev_eu_exam_fee; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Event Fee Other</label>
                                            <div class="col-sm-9">
                                                <input required <?php if ($bln_allow_to_edit == false) { echo "readonly"; }?> type="text" name="ev_nu_exam_fee" id="ev_nu_exam_fee"  placeholder="Event Fee Other" value="<?php echo $ev_nu_exam_fee; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="ev_status" id="ev_status_a" <?php
                                                if ($ev_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="ev_status_a">Active</label> <input type="radio" name="ev_status" id="ev_status_i" value="I" <?php if ($ev_status == 'I') echo 'checked="checked"'; ?> /><label for="ev_status_i">InActive</label>
                                            </div>
                                        </div>
                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?>           <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                            <button type="button" class="btn btn-info pull-right" onclick="validate_event();" id="btnAddUser" name="btnAddUser">Save</button>
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
            $("#ev_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true, });

                

                // $("#ev_end_date").datepicker({
                // format: 'dd-mm-yyyy',
                // autoclose: true, });
                <?php }?>
                $("#ev_end_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true, });
            </script>

            <!-- end of our page-->
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
