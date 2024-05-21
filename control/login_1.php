<?php
include("includes/application_top.php");

//set Page Title
$page_title = "Login";

// Check is session is exist or not. IF yes then redirect to index.php page
if (session_get("admin_uname")) {
    header("Location: index.php");
}

$lblUserName = '';
if (EMAIL_LOGIN) {
    $lblUserName = 'Email id';
} else {
    $lblUserName = 'User Name';
}

// Set all the required variables
$successmsg = get_rdata('successmsg','');
$errormsg = get_rdata('errormsg','');
$username = get_rdata("inputUsername");
$pass = get_rdata("inputPassword");
$remember = get_rdata("remember");

// Set success/error message
if (isset($_GET['msg_id']) && $_GET['msg_id'] == '2') {
    $successmsg = "Your new password has Been send to your email address.";
}
if (isset($_GET['msg_id']) && $_GET['msg_id'] == '3') {
    $successmsg = "You have logged out successfully.";
}

// If submit for login  then check for validation and loggedin user
if (isset($_POST) && count($_POST) > 0) {

    // Set login fields name based config varable
    $lblLoginField='';
    if (EMAIL_LOGIN) {
        $lblLoginField = 'admin_email';
    }else{
        $lblLoginField = 'admin_uname';
    }

    // Check user with database and validate
    $admin = new admin();
    $admin->data["*"] = "";
    $admin->action = 'get';
    $admin->where = $lblLoginField.'="' . $username . '" AND admin_pass="' . md5($pass) . '" ';
    $result = $admin->process();

    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                session_set("admin_uname", $db_row[$lblLoginField]);
                session_set("admin_id", $db_row['admin_id']);

                if (REMEMBER_ME_ENABLE) {
                    if ($remember) {
                        setcookie('admin_uname', $db_row[$lblLoginField], time() + REMEMBER_ME_TIME);
                        setcookie('admin_pass', $db_row['admin_pass'], time() + REMEMBER_ME_TIME);
                    } else if (!$remember) {
                        if (isset($_COOKIE['admin_uname'])) {
                            setcookie('admin_uname', $db_row[$lblLoginField], time() - REMEMBER_ME_TIME);
                        } else if (isset($_COOKIE['admin_pass'])) {
                            setcookie('admin_pass', $db_row['admin_pass'], time() - REMEMBER_ME_TIME);
                        }
                    }
                }

                // Add log entry
                if (ENABLE_LOG) {
                    $remarks = str_replace('[USER_NAME]', $db_row[$lblLoginField], USER_LOGIN);
                    add_log('Admin Login', $db_row['admin_id'], '0', $remarks);
                }

                // If success then redirect to index page
                header("Location: index.php");
            }
        } else {
            $errormsg = "You have an insert wrong $lblUserName or password.";
        }
    }
}

// Include Header file
include("includes/header.php");
?>
<div class="col-sm-6 col-lg-4 col-md-5 col-xs-8 mt20 mauto mb20">
    <?php include('includes/messages.php'); ?>
    <form id="frmLogin" class="form-horizontal all_border" method="POST" action="">
        <div class="form-group text-center ">
            <h3><?php echo $page_title; ?></h3>
        </div>
        <div class="form-group">
            <label for="inputUsername" class="col-sm-4 control-label"><?php echo $lblUserName; ?></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="inputUsername" name="inputUsername" autocomplete="off" placeholder="<?php echo $lblUserName; ?>" value="<?php if (isset($_COOKIE['admin_uname'])) echo $_COOKIE['admin_uname']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="col-sm-4 control-label">Password</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="inputPassword" name="inputPassword" autocomplete="off" placeholder="Password" value="<?php if (isset($_COOKIE['admin_pass'])) echo $_COOKIE['admin_pass']; ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" value="1" <?php
                        if (isset($_COOKIE['admin_uname']) && isset($_COOKIE['admin_pass'])) {
                            echo set_checked(1, 1);
                        }
                        ?>> Remember me
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
                <button type="submit" class="btn btn-success" name="sbtLogin" id="sbtLogin">Log in</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#frmLogin').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                inputUsername: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The username is required and cannot be empty'
                        }
                    }
                },
                inputPassword: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required and cannot be empty'
                        }
                    }
                }
            }
        });
    });
</script>
<?php
//Include Footer
include("includes/footer.php");
?>