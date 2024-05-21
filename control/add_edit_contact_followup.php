<?php
include("includes/application_top.php");
include("../includes/class/contact_followup.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");

$con_con_id = get_rdata('con_con_id');
$con_message = get_rdata('con_message');
$con_status = get_rdata('con_status');

$con_followup_date = get_rdata('con_followup_date',$cur_date_only);
$con_br_id = get_rdata('con_br_id',$tmp_admin_id);
$con_create_date = $cur_date;
$con_create_by_id = $tmp_admin_id;
$con_update_date = $cur_date;
$con_update_by_id = $tmp_admin_id;

$caption = "Add Contact Followup";
$btn_caption = "Add Contact Followup";
if ($id != 0) {
    $caption = "Edit Contact Followup";
    $btn_caption = "Edit Contact Followup";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $query = "SELECT cfw.con_con_id, cfw.con_message, cfw.con_followup_date,cfw.con_br_id,c.con_status FROM sm_contact_followup cfw INNER JOIN sm_contact c ON (cfw.con_con_id=c.con_id) WHERE  cfw.con_id= $id";
    $result = m_process('get_data',$query);
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $con_con_id = $db_row['con_con_id'];
                $con_message = $db_row['con_message'];
                $con_status = $db_row['con_status'];
                $con_followup_date = convert_db_to_disp_date($db_row['con_followup_date']);
            }
        }
    }
}

// Add user entry
if ($act == 'add') 
 {
        $sm_contact = new contact_followup();
        $sm_contact->data["con_message"] = escape($con_message);
        $sm_contact->data["con_followup_date"] = convert_disp_to_db_date($con_followup_date);
        $sm_contact->data["con_br_id"] = $con_br_id;
        $sm_contact->data["con_con_id"] = $con_con_id;
        $sm_contact->data["con_create_date"] = $con_create_date;
        $sm_contact->data["con_create_by_id"] = $con_create_by_id;
        $sm_contact->data["con_update_date"] = $con_update_date;
        $sm_contact->data["con_update_by_id"] = $con_update_by_id;

        $sm_contact->action = 'insert';
        $result = $sm_contact->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // update followup date and count follow up.
            $q = "UPDATE sm_contact SET con_status ='".$con_status."', con_followup_date = '".convert_disp_to_db_date($con_followup_date)."',con_no_of_followup =  con_no_of_followup +1 WHERE con_id = $con_con_id ";
            $r = m_process("update",$q);
            if ($r["status"] == 'error') {
                $errormsg = $r["errormsg"];
            } else {
                header('Location:manage_contact_followup.php?msg=2&con_con_id='.$con_con_id.'&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    
}

// Update user entry
if ($act == 'update') {
    if ($errormsg == '') {
        $sm_contact = new contact_followup();
        $sm_contact->data["con_message"] = escape($con_message);
        $sm_contact->data["con_followup_date"] = convert_disp_to_db_date($con_followup_date);
        $sm_contact->data["con_br_id"] = $con_br_id;
        $sm_contact->data["con_con_id"] = $con_con_id;
        $sm_contact->data["con_create_date"] = $con_create_date;
        $sm_contact->data["con_create_by_id"] = $con_create_by_id;
        $sm_contact->data["con_update_date"] = $con_update_date;
        $sm_contact->data["con_update_by_id"] = $con_update_by_id;
        $sm_contact->action = 'update';
        $sm_contact->process_id = $id;
        $result = $sm_contact->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // update followup date and count follow up.
            $q = "UPDATE sm_contact SET con_status ='".$con_status."', con_followup_date = '".convert_disp_to_db_date($con_followup_date)."',con_no_of_followup =  con_no_of_followup +1 WHERE con_id = $con_con_id ";
            $r = m_process("update",$q);
            if ($r["status"] == 'error') {
                $errormsg = $r["errormsg"];
            } else {
                header('Location:manage_contact_followup.php?msg=2&con_con_id='.$con_con_id.'&page=1&per_page=' . $per_page);
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
                    <?php
                                $contact_q = "SELECT c.con_name, c.con_email, c.con_phone, c.con_message, c.con_status, c.con_date, c.con_no_of_followup, c.con_followup_date, c.con_create_date, c.con_followup_type  FROM  sm_contact c  WHERE  c.con_id = $con_con_id";  
                                $contact_r = m_process("get_data",$contact_q);
                                if ($contact_r["status"] == 'success' && $contact_r["count"]>0) 
                                        {
                                ?>
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">                                
                                <div class="box-header with-border">
                                    <h3 class="box-title">Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                
                                
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="col-sm-6">Name</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_name"]; ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-6">Email</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_email"]; ?>
                                                </div>
                                            </div>
                                        </div>      
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="col-sm-6">Contact</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_phone"]; ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-6">Status</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_status"]; ?>
                                                </div>
                                            </div>
                                        </div>      
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="col-sm-6">Type</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_followup_type"]; ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-6">Followup Date</label>
                                                <div class="col-sm-6">
                                                    <?php  
                                                    if ($contact_r["res"][0]["con_followup_date"] !='')
                                                    {
                                                        echo convert_db_to_disp_date($contact_r["res"][0]["con_followup_date"]);
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3">Message</label>
                                                <div class="col-sm-9 text-left" >
                                                    <?php echo nl2br($contact_r["res"][0]["con_message"]); ?>
                                                </div>
                                            </div>
                                        </div>   
                                                                                                                 
                                    </div><!-- /.box-body -->
                                                                 
                            </div>

                        </div>
                    </div>
                    <?php } ?> 
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
                                    <input type="hidden" id="con_con_id" name="con_con_id" value="<?php echo $con_con_id; ?>" />
                                    <input type="hidden" id="con_br_id" name="con_br_id" value="<?php echo $con_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Message</label>
                                                <div class="col-sm-9">
                                                    <textarea  name="con_message" id="con_message"  placeholder="Contact Message" class="form-control"><?php echo $con_message; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Date</label>
                                                <div class="col-sm-9">
                                                <input type="text" name="con_followup_date" id="con_followup_date"  placeholder="Followup Date" value="<?php echo $con_followup_date; ?>" class="form-control" />
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
<script>
    $("#con_followup_date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true, });
</script>
                    </div>
                    </body>
                    </html>