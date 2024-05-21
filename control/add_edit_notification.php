<?php
include("includes/application_top.php");
include("../includes/class/notification.php");
// Set the caption of button

$user_id = session_get("admin_id");

$id= get_rdata("id",0);
$act = get_rdata("act");
$not_id= get_rdata('not_id');
$not_message= get_rdata('not_message');
$not_sc_id= get_rdata('not_sc_id');
$not_status= get_rdata('not_status','A');
$not_create_date= $cur_date;
$not_create_by_id= $user_id;
$not_update_date= $cur_date;
$not_update_by_id= $user_id;

$caption = "Add Notification";
$btn_caption = "Add Notification";
if ($id != 0) {
    $caption = "Edit Notification";
    $btn_caption = "Edit Notification";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_notification = new notification();
    $sm_notification->data["*"] = "";
    $sm_notification->action = 'get';
    $sm_notification->process_id = $id;
    $result = $sm_notification->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                  $not_id = $db_row['not_id'];
                  $not_message = $db_row['not_message'];
                  $not_sc_id = $db_row['not_sc_id'];
                  $not_status = $db_row['not_status'];
                  $not_create_date = $db_row['not_create_date'];
                  $not_create_by_id = $db_row['not_create_by_id'];
                  $not_update_date = $db_row['not_update_date'];
                  $not_update_by_id = $db_row['not_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
        $sm_notification = new notification();
        $sm_notification->data["not_message"]=$not_message;
        $sm_notification->data["not_sc_id"]=$not_sc_id;
        $sm_notification->data["not_status"]=$not_status;
        $sm_notification->data["not_create_date"]=$not_create_date;
        $sm_notification->data["not_create_by_id"]=$not_create_by_id;
        $sm_notification->data["not_update_date"]=$not_update_date;
        $sm_notification->data["not_update_by_id"]=$not_update_by_id;
        $sm_notification->action = 'insert';
        $result = $sm_notification->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            $arr_data = array();
            $arr_data["gcmp_message"] = $not_message;
            $arr_data["gcmp_title"] =$not_message;
            $arr_data["gcmp_subtitle"] = $not_message;
            $arr_data["gcmp_tickerText"] =$not_message;
            $arr_data["gcmp_create_by"]=$not_create_by_id;
            $arr_data["gcmp_create_date"]=$not_create_date;
            $arr_data["gcmp_not_id"]=$result["id"] ;
            $arr_data["gcmp_gcm_sc_id"]=$tmp_admin_sc_id;
            $errormsg =  add_gcm_notification($arr_data);
            if ($errormsg== "")
            {
                header('Location:manage_notification.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }

}

// Update user entry
if ($act == 'update') {
        $sm_notification = new notification();
        $sm_notification->data["not_message"]=$not_message;
        $sm_notification->data["not_sc_id"]=$not_sc_id;
        $sm_notification->data["not_status"]=$not_status;
        $sm_notification->data["not_create_date"]=$not_create_date;
        $sm_notification->data["not_create_by_id"]=$not_create_by_id;
        $sm_notification->data["not_update_date"]=$not_update_date;
        $sm_notification->data["not_update_by_id"]=$not_update_by_id;
        $sm_notification->action = 'update';
        $sm_notification->process_id = $id;
        $result = $sm_notification->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // If success then redirect to manage user page
            header('Location:manage_notification.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
        }

}


if (session_get('admin_login_type') == 'school') {
$not_sc_id = session_get('admin_sc_id');
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
                                <form name="form1" id="form1" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="not_sc_id" name="not_sc_id" value="<?php echo $not_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Notification Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="not_message" id="not_message"  placeholder="Notification Name" value="<?php echo $not_message; ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="not_status" id="not_status_a" <?php if ($not_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="not_status_a">Active</label> <input type="radio" name="not_status" id="not_status_i" value="I" <?php if ($not_status == 'I') echo 'checked="checked"'; ?> /><label for="not_status_i">InActive</label>
                                            </div>


                                        </div>

                                        <!-- /.box-body -->

                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                    <?php if ($id==0) { ?>         <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
