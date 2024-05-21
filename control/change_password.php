<?php
include("includes/application_top.php");

//set Page Title
$page_title = "Change Password";
$admin_id = session_get("admin_id");
if (isset($_POST['btnChangePwd'])) {
    $newPassword = get_rdata('txtNewPassword');
    $txtCurrentPassword = get_rdata('txtCurrentPassword');
    $txtNewPassword = $newPassword;
    $admin = new admin();
    $admin->data["*"] = "";
    $admin->where = " admin_id = " . $admin_id . " AND admin_pass = '" . md5($txtCurrentPassword) . "'";
    $admin->action = 'get';
    $result = $admin->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $admin_uname = $db_row['admin_uname'];
                $admin_email = $db_row['admin_email'];
            }
            $admin_up = new admin();
            $admin_up->data["admin_pass"] = md5($txtNewPassword);
            $admin_up->where = " admin_id = " . $admin_id;
            $admin_up->action = 'update';
            $result_member = $admin_up->process();
            if ($result_member['status'] == 'failure') {
                $errormsg = $result_member['errormsg'];
            } else {
                //log entry for change password
                if (ENABLE_LOG) {
                    $remarks = str_replace('[USER_NAME]', $admin_uname, CHANGE_PASSWORD);
                    add_log('Change Password', $admin_id, 0, $remarks);
                }

                //MAIL FOR User
                $define_param = array();
                $define_param['to_email'] = $admin_email;
                $define_param['to_name'] = ucwords($admin_uname);
                $define_param['from_email'] = MAIL_FROM_EMAIL;
                $define_param['from_name'] = MAIL_FROM_NAME;
                $define_param['user_id'] = $admin_id;
                
                $param['USER_PASSWORD'] = $newPassword;
                $param['USER_NAME'] = $admin_uname;
                send_mail('change-password', $param, $define_param);
                header('Location: index.php?msg_id=1');
            }
        } else {
            $errormsg = 'You have entered wrong current password.';
        }
    }
}

include("includes/header.php");
?>

<!-- Main wrapper -->
<div class="row">
    <?php include('includes/messages.php'); ?>
    <div class="col-sm-6 col-lg-4 col-md-5 col-xs-8 mt20 mauto mb20">
        <form class="form-horizontal all_border" method="POST" action="">
            <div class="form-group text-center ">
                <h3><?php echo $page_title; ?></h3>
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label text_left">Current Password <span class="require">*</span></label>
                <div class="col-sm-12">
                    <input type="password" name="txtCurrentPassword" id="txtCurrentPassword" class="req_mm form-control" autocomplete="off" placeholder="Current Password"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label text_left">New Password <span class="require">*</span></label>
                <div class="col-sm-12">
                    <input type="password" name="txtNewPassword" id="txtNewPassword" class="req_mm form-control" placeholder="New Password"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-12 control-label text_left">Retype New Password <span class="require">*</span></label>
                <div class="col-sm-12">
                    <input type="password" name="txtRePassword" id="txtRePassword" class="validate[equals[txtNewPassword]] req_mm form-control" placeholder="Retype New Password"/>				
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <input type="submit" value="Change Password" class="btn btn-default" id="btnChangePwd" name="btnChangePwd" />
                    <input type="button" onclick="window.location.href = 'index.php';" value="Cancel" class="btn btn-default"/>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Footer -->
<?php include('includes/footer.php'); ?>
