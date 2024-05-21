<?php
include("includes/application_top.php");

//set Page Title
$page_title = "Forgot Password";

if (isset($_POST['sbtForgotPass'])) {
    $admin_email = $_POST['admin_email'];
    $admin = new admin();
    $admin->data["*"] = "";
    $admin->where = " admin_email = '" . $admin_email . "'";
    $admin->action = 'get';
    $result = $admin->process();

    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $admin_id = $db_row['admin_id'];
                $admin_uname = $db_row['admin_uname'];
                $admin_email = $db_row['admin_email'];
                $new_user_password = '';
                $possible_letters = '23456789bcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $i = 0;
                while ($i < 8) {
                    $new_user_password .= substr($possible_letters, mt_rand(0, strlen($possible_letters) - 1), 1);
                    $i++;
                }
                $admin1 = new admin();
                $admin1->data["admin_pass"] = md5($new_user_password);
                $admin1->action = 'update';
                $admin1->where = " admin_email ='" . $admin_email . "'";
                $result_admin = $admin1->process();
                if ($result_admin['status'] == 'failure') {
                    $errormsg = $result_admin['errormsg'];
                } else {
                    //coding for log master
                    if (ENABLE_LOG) {
                        $remarks = str_replace('[USER_NAME]', $admin_uname, FORGOT_PASSWORD);
                        add_log('Forgot Password Admin', $admin_id, 0, $remarks);
                    }
                    //end code for log master
                    // mail
                    //$to =  $user_user;
                    $define_param = array();
                    $define_param['to_email'] = $admin_email;
                    $define_param['to_name'] = $admin_uname;
                    $define_param['from_email'] = MAIL_FROM_EMAIL;
                    $define_param['from_name'] = MAIL_FROM_NAME;

                    //change to regular from config file - dax 3/22/14 6:09pm
                    $define_param['reply_to_email'] = MAIL_TO_EMAIL;
                    $define_param['reply_to_name'] = MAIL_TO_NAME;
                    $define_param['user_id'] = $admin_id;

                    $param['USER_NAME'] = $admin_uname;
                    $param['USER_EMAIL'] = $admin_email;
                    $param['USER_PASSWORD'] = $new_user_password;
                    send_mail('forgot-password', $param, $define_param);

                    $redirect_page = 'login.php';
                    header('Location:' . $redirect_page . '?msg_id=2');
                }
            }
        } else {
            $errormsg = 'You have entered wrong email address.';
        }
    }
}

include("includes/header.php");
?>


<div class="row">
    <?php include('includes/messages.php'); ?>
    <div class="col-sm-6 col-lg-4 col-md-5 col-xs-8 mt20 mauto mb20">
        <form class="form-horizontal all_border" method="POST" action="">
            <div class="form-group text-center ">
                <h3><?php echo $page_title; ?></h3>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Email <span class="required">*</span></label>
                <div class="col-sm-9">
                    <input required type="text" name="admin_email" id="admin_email" class="req_mm form-control" placeholder="Email"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3 text-right">
                    <a href="login.php">Back to login</a>&nbsp;&nbsp;
                    <input type="submit" value="Forgot Password" class="btn btn-default" id="sbtForgotPass" name="sbtForgotPass" />				
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Footer -->
<?php include('includes/footer.php'); ?>
