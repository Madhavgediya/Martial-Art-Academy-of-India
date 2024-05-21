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
    $branch = new branch();

    $branch->data["*"] = "";
    $branch->action = 'get';
    $branch->where =  ' br_status ="A" AND br_login = "'.$username.'" AND br_pass = "'.md5($pass).'" ';
    $result = $branch->process();
// print_r($result); exit(0);
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
        //  echo "count > 0 ";
            $db_row = $result['res'] [0];
// print_r($db_row);
                if (($db_row['br_pass'] ==  md5($pass) ) && ($db_row['br_login'] == $username) )
                {
            //      echo "setting sesssion";
                // session_set("admin_uname", $db_row["admin_fname"]. " ". $db_row["admin_lname"] );
                session_set("id", $db_row['br_id']);

                // Add log entry
                /*
                if (ENABLE_LOG) {
                    $remarks = str_replace('[USER_NAME]', $db_row[$lblLoginField], USER_LOGIN);
                    add_log('Admin Login', $db_row['admin_id'], '0', $remarks);
                }
                */
                // If success then redirect to index page
                header("Location: index.php");
             }
             else
                {

                     $errormsg = "You have an insert wrong $lblUserName or password.";
                  }

        } else {
            $errormsg = "You have an insert wrong $lblUserName or password.";
        }
    }
}

// Include Header file

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $page_title ;?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b><?php echo SITE_TITLE_SORT; ?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
          <?php include("includes/messages.php"); ?>
        <p class="login-box-msg">Sign in to start your session</p>
        <form id="frmLogin" name="frmLogin" method="post">
          <div class="form-group has-feedback">
            <input type="email" id="inputUsername" name="inputUsername" class="form-control" value="<?php echo $username; ?>" placeholder="<?php echo $lblUserName; ?>" />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" id="inputPassword" name="inputPassword"  class="form-control" placeholder="Password" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>

          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>



      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>

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
