<?php
include("includes/application_top.php");
// Set the caption of button
$id = get_rdata("id", 0);

$caption = "Add User";
$btn_caption = "Add User";
if ($id != 0) {
    $caption = "Edit User";
    $btn_caption = "Edit User";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$act = get_rdata("act");
$user_fname = get_rdata('user_fname');
$user_lname = get_rdata('user_lname');
$user_email = get_rdata('user_email');
$user_name = get_rdata('user_name');
$user_password = get_rdata('user_password');
$user_access = get_rdata('user_access');

// Get the data from database
if ($act == '' && $id != 0) {
    $user = new user();
    $user->data["*"] = "";
    $user->action = 'get';
    $user->process_id = $id;
    $result = $user->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $user_fname = $db_row['user_fname'];
                $user_lname = $db_row['user_lname'];
                $user_email = $db_row['user_email'];
                $user_name = $db_row['user_name'];
                $user_password = $db_row['user_password'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
    $email_exist = new user();
    $email_exist->data["user_email"] = '';
    $email_exist->action = 'get';
    $email_exist->where = "user_email = '" . $user_email . "'";
    $result_email_exist = $email_exist->process();
    if ($result_email_exist['status'] == 'failure') {
        $errormsg = $result_email_exist['errormsg'];
    } else {
        if ($result_email_exist['count'] > 0) {
            $errormsg = 'Email already exists.';
        } else {
            $user_exist = new user();
            $user_exist->data["user_name"] = '';
            $user_exist->action = 'get';
            $user_exist->where = "user_name = '" . $user_name . "'";
            $result_exist = $user_exist->process();
            if ($result_exist['status'] == 'failure') {
                $errormsg = $result_exist['errormsg'];
            } else {
                if ($result_exist['count'] > 0) {
                    $errormsg = 'Username already exists.';
                } else {
                    $user = new user();
                    $user->data["user_fname"] = $user_fname;
                    $user->data["user_lname"] = $user_lname;
                    $user->data["user_email"] = $user_email;
                    $user->data["user_name"] = $user_name;
                    $user->data["user_password"] = md5(strtolower(substr($user_fname, 0, 1) . $user_lname));
                    $user->action = 'insert';
                    $result = $user->process();
                    if ($result['status'] == 'failure') {
                        $errormsg = $result['errormsg'];
                    } else {
                        $user_acc_obj = new user_access();
                        $user_acc_obj->data["user_id"] = $result['id'];
                        $user_acc_obj->data["uaccess_id"] = $user_access;
                        $user_acc_obj->action = 'insert';
                        $result_acc = $user_acc_obj->process();
                        if ($result_acc['status'] == 'failure') {
                            $errormsg = $result_acc['errormsg'];
                        } else {
                            // Add log entry
                            if (ENABLE_LOG) {
                                $remarks = str_replace('[USER_NAME]', $user_fname, USER_ADD);
                                add_log('Add new user', $result['id'], '0', $remarks);
                            }
                            $define_param = array();
                            $define_param['to_email'] = $user_email;
                            $define_param['to_name'] = ucwords($user_fname) . ' ' . ucwords($user_lname);
                            $define_param['from_email'] = MAIL_FROM_EMAIL;
                            $define_param['from_name'] = MAIL_FROM_NAME;
                            $define_param['user_id'] = $user_id;
                            $param['USER_PASSWORD'] = md5(strtolower(substr($user_fname, 0, 1) . $user_lname));
                            $param['USER_NAME'] = $user_fname . ' ' . $user_lname;
                            send_mail('add-user', $param, $define_param);
                            // If success then redirect to manage user page
                            header('Location:manage_user.php?msg=2&page=1&per_page=' . $per_page);
                            exit(0);
                        }
                    }
                }
            }
        }
    }
}

// Update user entry
if ($act == 'update') {
    $email_exist = new user();
    $email_exist->data["user_email"] = '';
    $email_exist->action = 'get';
    $email_exist->where = "user_email = '" . $user_email . "' AND user_id <> " . $id;
    $result_email_exist = $email_exist->process();
    if ($result_email_exist['status'] == 'failure') {
        $errormsg = $result_email_exist['errormsg'];
    } else {
        if ($result_email_exist['count'] > 0) {
            $errormsg = 'Email already exists.';
        } else {
            $user_exist = new user();
            $user_exist->data["user_name"] = '';
            $user_exist->action = 'get';
            $user_exist->where = "user_name = '" . $user_name . "' AND user_id <> " . $id;
            $result_exist = $user_exist->process();
            if ($result_exist['status'] == 'failure') {
                $errormsg = $result_exist['errormsg'];
            } else {
                if ($result_exist['count'] > 0) {
                    $errormsg = 'Username already exists.';
                } else {
                    $user = new user();
                    $user->data["user_fname"] = $user_fname;
                    $user->data["user_lname"] = $user_lname;
                    $user->data["user_email"] = $user_email;
                    $user->data["user_name"] = $user_name;
                    $user->action = 'update';
                    $user->process_id = $id;
                    $result = $user->process();
                    if ($result['status'] == 'failure') {
                        $errormsg = $result['errormsg'];
                    } else {
                        // Add log entry
                        if (ENABLE_LOG) {
                            $remarks = str_replace('[USER_NAME]', $user_fname, USER_UPDATE);
                            add_log('Update User', $id, '0', $remarks);
                        }

                        // If success then redirect to manage user page
                        header('Location:manage_user.php?msg=3&page=1&per_page=' . $per_page);
                        exit(0);
                    }
                }
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
                        <div class="col-md-6">
                            <!-- Horizontal Form -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $caption; ?></h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form name="form1" id="form1" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">First Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="user_fname" id="user_fname"  placeholder="First Name" value="<?php echo $user_fname; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Last Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="user_lname" id="user_lname" placeholder="Last Name" value="<?php echo $user_lname; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Email</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="user_email" id="user_email" placeholder="Email" value="<?php echo $user_email; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">User Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="user_name" id="user_name" value="<?php echo $user_name; ?>" placeholder="User Name" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">User Access</label>
                                            <div class="col-sm-9">
                                                <div class="checkbox-inline" >
                                                    <label>
                                                        <input type="checkbox" name="user_access" id="user_access" value="1" <?php if (set_checked($user_access, 1)) { ?> checked="checked" <?php } ?>  />Admin User
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label><input type="checkbox" name="user_access" id="user_access" value="2" <?php if (set_checked($user_access, 2)) { ?> checked="checked" <?php } ?>  />Front User</label>

                                                </div>
                                            </div>



                                        </div><!-- /.box-body -->
                                        <div class="box-footer">
                                     <?php if ($id==0) { ?>        <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                            <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                        </div><!-- /.box-footer -->
                                </form>
                            </div><!-- /.box -->
                            <!-- general form elements disabled -->

                        </div>
                    </div>
                </section>
            </div>

            <!-- end of our page-->
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#frmAddEditUser').bootstrapValidator({
                        message: 'This value is not valid',
                        feedbackIcons: {
                            valid: 'glyphicon glyphicon-ok',
                            invalid: 'glyphicon glyphicon-remove',
                            validating: 'glyphicon glyphicon-refresh'
                        },
                        fields: {
                            user_fname: {
                                message: 'The first name is not valid',
                                validators: {
                                    notEmpty: {
                                        message: 'The first name is required and cannot be empty'
                                    }
                                }
                            },
                            user_lname: {
                                validators: {
                                    notEmpty: {
                                        message: 'The last name is required and cannot be empty'
                                    }
                                }
                            },
                            user_email: {
                                validators: {
                                    notEmpty: {
                                        message: 'The email is required and cannot be empty'
                                    },
                                    emailAddress: {
                                        message: 'The input is not a valid email address'
                                    }
                                }
                            },
                            user_name: {
                                validators: {
                                    notEmpty: {
                                        message: 'The username is required and cannot be empty'
                                    }
                                }
                            }
                        }
                    });
                });

                function resetForm()
                {
                    // reset the form
                    $('.frmAddEditUser input').not(':button,:radio,:submit').val('');
                    $('#id').val(0);
                }
            </script>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
