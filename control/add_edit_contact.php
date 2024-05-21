<?php
include("includes/application_top.php");
include("../includes/class/contact.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$con_id = get_rdata('con_id');
$con_identity_id = get_rdata('con_identity_id');
$con_name = get_rdata('con_name');
$con_email= get_rdata('con_email');
$con_phone = get_rdata('con_phone');
$con_message = get_rdata('con_message');
$con_date = get_rdata('con_date',$cur_date_only);
$con_followup_date = get_rdata('con_followup_date',$cur_date_only);
$con_br_id = get_rdata('con_br_id',$tmp_admin_id);
$con_status = get_rdata('con_status', 'A');
$con_followup_type = get_rdata('con_followup_type', 'Contact');
$con_create_date = $cur_date;
$con_create_by_id = $tmp_admin_id;
$con_update_date = $cur_date;
$con_update_by_id = $tmp_admin_id;

$caption = "Add Inquiry";
$btn_caption = "Add Inquiry";
if ($id != 0) {
    $caption = "Edit Inquriy";
    $btn_caption = "Edit Inquiry";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_contact = new Contact();
    $sm_contact->data["*"] = "";
    $sm_contact->action = 'get';
    $sm_contact->where =  "con_br_id = ".$tmp_admin_id." AND con_id=".$id;
    $result = $sm_contact->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $con_name = $db_row['con_name'];
                $con_email= $db_row['con_email'];
                $con_phone = $db_row['con_phone'];
                $con_message = $db_row['con_message'];
                $con_followup_type = $db_row['con_followup_type'];
               
                
                if ($db_row['con_date'] =='' || $db_row['con_date'] =='1790-01-01'  )
                    $con_date = '';
                else 
                    $con_date = convert_db_to_disp_date($db_row['con_date']); 

                if ($db_row['con_followup_date'] =='' || $db_row['con_followup_date'] =='1790-01-01'  )
                    $con_followup_date = '';
                else 
                    $con_followup_date = convert_db_to_disp_date($db_row['con_followup_date']); 

                $con_br_id = $db_row['con_br_id'];
                $con_status = $db_row['con_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') 
 {
        $sm_contact = new Contact();

        $sm_contact->data["con_name"] = escape($con_name);
        $sm_contact->data["con_email"] = escape($con_email);
        $sm_contact->data["con_phone"] = escape($con_phone);
        $sm_contact->data["con_message"] = escape($con_message);
        $sm_contact->data["con_followup_type"] = escape($con_followup_type);
        
                
        if ($con_date != '')
            $sm_contact->data["con_date"] = convert_disp_to_db_date($con_date);
        else
           $sm_contact->data["con_date"] = '1790-01-01';

        if ($con_followup_date != '')
            $sm_contact->data["con_followup_date"] = convert_disp_to_db_date($con_followup_date);
        else
           $sm_contact->data["con_followup_date"] = '1790-01-01';

        $sm_contact->data["con_br_id"] = $con_br_id;
        $sm_contact->data["con_status"] = $con_status;
        $sm_contact->data["con_create_date"] = $con_create_date;
        $sm_contact->data["con_create_by_id"] = $con_create_by_id;
        $sm_contact->data["con_update_date"] = $con_update_date;
        $sm_contact->data["con_update_by_id"] = $con_update_by_id;

        $sm_contact->action = 'insert';
        $result = $sm_contact->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_contact.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    
}

// Update user entry
if ($act == 'update') {
    // if ($con_name == '') {
    //     $errormsg = 'Contact title is required ';
    // } else {
    //     $not_value = " AND con_br_id = " . $tmp_admin_id . " AND con_id != " . $id;
    //     $arr_duplicate_con_name = found_duplicate('sm_contact', 'con_name', $con_name, $not_value);
    //     if ($arr_duplicate_con_name['error_message'] != '') {
    //         $errormsg = $arr_duplicate_con_name['error_message'];
    //     } else if ($arr_duplicate_con_name['duplicate'] == true) {
    //         $errormsg = 'Duplicate entry for Contact title.';
    //     }
    // }
    

    if ($errormsg == '') {
        $sm_contact = new Contact();
        $sm_contact->data["con_name"] = escape($con_name);
        $sm_contact->data["con_email"] = escape($con_email);
        $sm_contact->data["con_phone"] = escape($con_phone);
        $sm_contact->data["con_message"] = escape($con_message);
        $sm_contact->data["con_followup_type"] = escape($con_followup_type);

        if ($con_date != '')
            $sm_contact->data["con_date"] = convert_disp_to_db_date($con_date);
        else
           $sm_contact->data["con_date"] = '1790-01-01';

        if ($con_followup_date != '')
            $sm_contact->data["con_followup_date"] = convert_disp_to_db_date($con_followup_date);
        else
           $sm_contact->data["con_followup_date"] = '1790-01-01';

        $sm_contact->data["con_br_id"] = $con_br_id;
        $sm_contact->data["con_status"] = $con_status;
        $sm_contact->data["con_update_date"] = $con_update_date;
        $sm_contact->data["con_update_by_id"] = $con_update_by_id;
        $sm_contact->action = 'update';
        $sm_contact->process_id = $id;
        $result = $sm_contact->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_contact.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
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
                                    <input type="hidden" id="con_br_id" name="con_br_id" value="<?php echo $con_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="con_name" id="con_name"  placeholder="Contact Name" value="<?php echo $con_name; ?>" class="form-control" />
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="con_email" id="con_email"  placeholder="Contact Email" value="<?php echo $con_email; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Phone</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="con_phone" id="con_phone"  placeholder="Contact Phone" value="<?php echo $con_phone; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Message</label>
                                                <div class="col-sm-9">
                                                    <textarea  name="con_message" id="con_message"  placeholder="Contact Message" class="form-control"><?php echo $con_message; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Date</label>
                                                <div class="col-sm-9">
                                                <input type="text" name="con_date" id="con_date"  placeholder="Contact Date" value="<?php echo $con_date; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Followup Date</label>
                                                <div class="col-sm-9">
                                                <input type="text" name="con_followup_date" id="con_followup_date"  placeholder="Contact Followup Date" value="<?php echo $con_followup_date; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                        <select id="con_status"  name="con_status" class="form-control" >
                                                        <option <?php if($con_status=="Open") echo "selected"; ?>  value="Open">Open</option>
                                                        <option <?php if($con_status=="Closed") echo "selected"; ?> value="Closed">Closed</option>
                                                        <option <?php if($con_status=="Discussion") echo "selected"; ?> value="Discussion">Discussion</option>
                                                        </select>
                                                        <input type="hidden" name="con_followup_type" value="<?=$con_followup_type?>" /> 
                                                </div>
                                            </div>
                                           <!--  <div class="form-group">
                                                <label class="col-sm-3 control-label">Type</label>
                                                <div class="col-sm-9">
                                                        <select id="con_followup_type"  name="con_followup_type" class="form-control" >
                                                        <option <?php if($con_followup_type=="Contact") echo "selected"; ?>  value="Contact">Contact</option>
                                                        <option <?php if($con_followup_type=="Fee") echo "selected"; ?> value="Fee">Fee</option>
                                                        <option <?php if($con_followup_type=="Document") echo "selected"; ?> value="Document">Document</option>
                                                        </select>
                                                </div>


                                            </div> -->
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
<script>
    $("#con_date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true, });

            $("#con_followup_date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true, });
</script>
                    </div>
                    </body>
                    </html>