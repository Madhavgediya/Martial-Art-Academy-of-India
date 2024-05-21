<?php
include("includes/application_top.php");
include("../includes/class/subject.php");
// Set the caption of button




$id= get_rdata("id",0);
$act = get_rdata("act");
$sub_sc_id= get_rdata('sub_sc_id');
$sub_id= get_rdata('sub_id');
$sub_name= get_rdata('sub_name');
$sub_status= get_rdata('sub_status','A');
$sub_create_date= $cur_date;
$sub_create_by_id= $user_id;
$sub_update_date= $cur_date;
$sub_update_by_id= $user_id;


$caption = "Add Subject";
$btn_caption = "Add Subject";
if ($id != 0) {
    $caption = "Edit Subject";
    $btn_caption = "Edit Subject";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_subject = new subject();
    $sm_subject->data["*"] = "";
    $sm_subject->action = 'get';
    $sm_subject->process_id = $id;
    $result = $sm_subject->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                  $sub_id = $db_row['sub_id'];
                  $sub_sc_id = $db_row['sub_sc_id'];
                  $sub_name = $db_row['sub_name'];
                  $sub_status = $db_row['sub_status'];
                  $sub_create_date = $db_row['sub_create_date'];
                  $sub_create_by_id = $db_row['sub_create_by_id'];
                  $sub_update_date = $db_row['sub_update_date'];
                  $sub_update_by_id = $db_row['sub_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

    $not_value = " AND sub_sc_id = ".$sub_sc_id;
    $arr_duplicate_school_name = found_duplicate('sm_subject', 'sub_name', $sub_name,$not_value);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for subject name ';
    }

    if ($errormsg == '') {
        $sm_subject = new subject();
        $sm_subject->data["sub_sc_id"]=$sub_sc_id;
        $sm_subject->data["sub_name"]=$sub_name;
        $sm_subject->data["sub_status"]=$sub_status;
        $sm_subject->data["sub_create_date"]=$sub_create_date;
        $sm_subject->data["sub_create_by_id"]=$sub_create_by_id;
        $sm_subject->data["sub_update_date"]=$sub_update_date;
        $sm_subject->data["sub_update_by_id"]=$sub_update_by_id;
        $sm_subject->action = 'insert';
        $result = $sm_subject->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            header('Location:manage_subject.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND sub_sc_id = ".$sub_sc_id." AND sub_id != " . $id;
    $arr_duplicate_subject_name = found_duplicate('sm_subject', 'sub_name', $sub_name,$not_value );
    if ($arr_duplicate_subject_name['error_message'] != '') {
        $errormsg = $arr_duplicate_subject_name['error_message'];
    } else if ($arr_duplicate_subject_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for subject name ';
    }



    if ($errormsg == '') {
        $sm_subject = new subject();
        $sm_subject->data["sub_sc_id"]=$sub_sc_id;
        $sm_subject->data["sub_name"]=$sub_name;
        $sm_subject->data["sub_status"]=$sub_status;
        $sm_subject->data["sub_create_date"]=$sub_create_date;
        $sm_subject->data["sub_create_by_id"]=$sub_create_by_id;
        $sm_subject->data["sub_update_date"]=$sub_update_date;
        $sm_subject->data["sub_update_by_id"]=$sub_update_by_id;
        $sm_subject->action = 'update';
        $sm_subject->process_id = $id;
        $result = $sm_subject->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // If success then redirect to manage user page
            header('Location:manage_subject.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}


if (session_get('admin_login_type') == 'school') {
$sub_sc_id = session_get('admin_sc_id');
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
                                    <input type="hidden" id="sub_sc_id" name="sub_sc_id" value="<?php echo $sub_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Subject Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="sub_name" id="sub_name"  placeholder="Subject Name" value="<?php echo $sub_name; ?>" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="sub_status" id="sub_status_a" <?php if ($sub_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="sub_status_a">Active</label> <input type="radio" name="sub_status" id="sub_status_i" value="I" <?php if ($sub_status == 'I') echo 'checked="checked"'; ?> /><label for="sub_status_i">InActive</label>
                                            </div>


                                        </div>

                                        <!-- /.box-body -->

                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                            <?php if ($id==0) { ?>                 <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
