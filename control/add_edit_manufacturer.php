<?php
include("includes/application_top.php");
include("../includes/class/manufacturer.php");
// Set the caption of button
$id = get_rdata("id", 0);

$caption = "Add Manufacturer";
$btn_caption = "Add Manufacturer";
if ($id != 0) {
    $caption = "Edit Manufacturer";
    $btn_caption = "Edit Manufacturer";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$act = get_rdata("act");
$manu_name = get_rdata('manu_name', '');
$manu_status = get_rdata('manu_status', 1);

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 2) {
    $successmsg = "Manufacturer has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT manu_id,manu_name,manu_status FROM sm_manufacturer WHERE manu_admin_id = ".$tmp_admin_id." AND manu_id = " . $id;
    $user = new manufacturer();
    $user->cquery = $cquery;
    $user->action = 'get';
    $result = $user->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $manu_name = $db_row['manu_name'];
                $manu_status = $db_row['manu_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
        if ($errormsg == '') {
            $arr_check_delete = validate_before_delete("sm_manufacturer", "manu_admin_id= ".$tmp_admin_id." AND manu_name = '" . $manu_name . "'", "");
            if ($arr_check_delete["error_message"] != '') {
                $errormsg = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $errormsg = "Manufacturer name is already exists";
            }
        }
        if ($errormsg == '') {
            $manufacturer = new manufacturer();
            $manufacturer->data["manu_name"] = $manu_name;
            $manufacturer->data["manu_date"] = date('Y-m-d H:i:s');
            $manufacturer->data["manu_admin_id"] = $tmp_admin_id;
            $manufacturer->data["manu_status"] = $manu_status;
            $manufacturer->action = 'insert';
            $result = $manufacturer->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
                // Add log entry
                
                if (isset($_POST['btnSaveandAdd'])) {
                    header('Location:add_edit_manufacturer.php?msg=2');
                    exit(0);
                } else {
                    // If success then redirect to manage user page
                    header('Location:manage_manufacturer.php?msg=2&page=1&per_page=' . $per_page);
                    exit(0);
                }
            }
        }
}

if ($act == 'update') {
        if ($errormsg == '') {
            $arr_check_delete = validate_before_delete("sm_manufacturer", "manu_admin_id = ".$tmp_admin_id." AND manu_name = '" . $manu_name . "' AND manu_id != '" . $id . "'");
            if ($arr_check_delete["error_message"] != '') {
                $errormsg = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $errormsg = "Manufacturer name is already exists";
            }
        }
        if ($errormsg == '') {
            $manufacturer = new manufacturer();
            $manufacturer->data["manu_name"] = $manu_name;
            $manufacturer->data["manu_status"] = $manu_status;
            $manufacturer->data["manu_date"] = date('Y-m-d H:i:s');
            $manufacturer->data["manu_admin_id"] = $tmp_admin_id;
            $manufacturer->action = 'update';
            $manufacturer->process_id = $id;
            $result = $manufacturer->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
               
                header('Location:manage_manufacturer.php?msg=3&page=1&per_page=' . $per_page);
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
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content-header">
                    <h1>
                        <?php echo $caption; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $caption; ?></li>
                    </ol>
                </section>
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
                                <form name="frmAddEditManufacturer" id="frmAddEditManufacturer" method="post" class="form-horizontal form-employee" onsubmit="return validate_data();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Name<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="manu_name" id="manu_name"  placeholder="Manufacturer Name" value="<?php echo $manu_name; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            
                                            
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Status<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <div class="radio-inline pl0" >
                                                            <input type="radio" class="minimal" name="manu_status" id="manu_status1" value="1" <?php if (set_checked($manu_status, '1')) { ?> checked="checked" <?php } ?>  />
                                                            <label for="manu_status1">Active</label>
                                                        </div>
                                                        <div class="radio-inline pl0">
                                                            <input type="radio" class="minimal" name="manu_status" id="manu_status0" value="0" <?php if (set_checked($manu_status, '0')) { ?> checked="checked" <?php } ?>  />
                                                            <label for="manu_status0">Inactive</label>
                                                        </div>
                                                    </div>  
                                                </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer"> 
                                        <?php if ($id == 0) { ?>
                                            <input type="button" value="Reset" class="btn btn-default clrbtn leftbtn" id="btnClearManufacturer" name="btnClearManufacturer"/>                             
                                        <?php } ?>
                                        <button type="submit" class="ml5 btn btn-info pull-right leftbtn marginl0" id="btnAddManufacturer" name="btnAddManufacturer"><?php echo $caption; ?></button>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div><!-- /.box -->
                            <!-- general form elements disabled -->
                        </div>
                    </div>
                </section>
            </div>
 <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>            
