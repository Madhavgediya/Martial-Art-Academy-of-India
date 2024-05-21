<?php
include("includes/application_top.php");
include("../includes/class/school_master.php");
// Set the caption of button




$id = get_rdata("id", 0);
$act = get_rdata("act");
$sc_name = get_rdata('sc_name');
$sc_phone_1 = get_rdata('sc_phone_1');
$sc_phone_2 = get_rdata('sc_phone_2');
$sc_website = get_rdata('sc_website');
$sc_email = get_rdata('sc_email');
$sc_address_1 = get_rdata('sc_address_1');
$sc_address_2 = get_rdata('sc_address_2');
$sc_city = get_rdata('sc_city');
$sc_st_id = get_rdata('sc_st_id');
$sc_status = get_rdata('sc_status','A');


$sc_latitude = get_rdata('sc_latitude');
$sc_longitude = get_rdata('sc_longitude');
$sc_create_date = $cur_date;
$sc_create_by_id = $user_id;
$sc_update_date = $cur_date;
$sc_update_by_id = $user_id;


$caption = "Add School";
$btn_caption = "Add School";
if ($id != 0) {
    $caption = "Edit School";
    $btn_caption = "Edit School";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_school_master = new school_master();
    $sm_school_master->data["*"] = "";
    $sm_school_master->action = 'get';
    $sm_school_master->process_id = $id;
    $result = $sm_school_master->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $sc_name = $db_row['sc_name'];
                $sc_phone_1 = $db_row['sc_phone_1'];
                $sc_phone_2 = $db_row['sc_phone_2'];
                $sc_website = $db_row['sc_website'];
                $sc_email = $db_row['sc_email'];
                $sc_address_1 = $db_row['sc_address_1'];
                $sc_address_2 = $db_row['sc_address_2'];
                $sc_city = $db_row['sc_city'];
                $sc_st_id = $db_row['sc_st_id'];
                $sc_status = $db_row['sc_status'];
                $sc_latitude = $db_row['sc_latitude'];
                $sc_longitude = $db_row['sc_longitude'];
                $sc_create_date = $db_row['sc_create_date'];
                $sc_create_by_id = $db_row['sc_create_by_id'];
                $sc_update_date = $db_row['sc_update_date'];
                $sc_update_by_id = $db_row['sc_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

    $arr_duplicate_school_name = found_duplicate('sm_school_master', 'sc_name', $sc_name);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for school name ';
    }

    $arr_duplicate_school_name = found_duplicate('sm_school_master', 'sc_email', $sc_email);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for school email ';
    }

    if ($errormsg == '') {
        $sm_school_master = new school_master();
        $sm_school_master->data["sc_id"] = $sc_id;
        $sm_school_master->data["sc_name"] = $sc_name;
        $sm_school_master->data["sc_phone_1"] = $sc_phone_1;
        $sm_school_master->data["sc_phone_2"] = $sc_phone_2;
        $sm_school_master->data["sc_website"] = $sc_website;
        $sm_school_master->data["sc_email"] = $sc_email;
        $sm_school_master->data["sc_address_1"] = $sc_address_1;
        $sm_school_master->data["sc_address_2"] = $sc_address_2;
        $sm_school_master->data["sc_city"] = $sc_city;
        $sm_school_master->data["sc_st_id"] = $sc_st_id;
        $sm_school_master->data["sc_status"] = $sc_status;
        $sm_school_master->data["sc_latitude"] = $sc_latitude;
        $sm_school_master->data["sc_longitude"] = $sc_longitude;
        $sm_school_master->data["sc_create_date"] = $sc_create_date;
        $sm_school_master->data["sc_create_by_id"] = $sc_create_by_id;
        $sm_school_master->data["sc_update_date"] = $sc_update_date;
        $sm_school_master->data["sc_update_by_id"] = $sc_update_by_id;
        $sm_school_master->action = 'insert';
        $result = $sm_school_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            header('Location:manage_school.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {

    $arr_duplicate_school_name = found_duplicate('sm_school_master', 'sc_name', $sc_name, " AND sc_id != " . $id);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for school name ';
    }

    $arr_duplicate_school_name = found_duplicate('sm_school_master', 'sc_email', $sc_email, " AND sc_id != " . $id);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for school email ';
    }

    if ($errormsg == '') {
        $sm_school_master = new school_master();
        $sm_school_master->data["sc_name"] = $sc_name;
        $sm_school_master->data["sc_phone_1"] = $sc_phone_1;
        $sm_school_master->data["sc_phone_2"] = $sc_phone_2;
        $sm_school_master->data["sc_website"] = $sc_website;
        $sm_school_master->data["sc_email"] = $sc_email;
        $sm_school_master->data["sc_address_1"] = $sc_address_1;
        $sm_school_master->data["sc_address_2"] = $sc_address_2;
        $sm_school_master->data["sc_city"] = $sc_city;
        $sm_school_master->data["sc_st_id"] = $sc_st_id;
        $sm_school_master->data["sc_status"] = $sc_status;
        $sm_school_master->data["sc_latitude"] = $sc_latitude;
        $sm_school_master->data["sc_longitude"] = $sc_longitude;
        $sm_school_master->data["sc_create_date"] = $sc_create_date;
        $sm_school_master->data["sc_create_by_id"] = $sc_create_by_id;
        $sm_school_master->data["sc_update_date"] = $sc_update_date;
        $sm_school_master->data["sc_update_by_id"] = $sc_update_by_id;
        $sm_school_master->action = 'update';
        $sm_school_master->process_id = $id;
        $result = $sm_school_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // If success then redirect to manage user page
            header('Location:manage_school.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
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
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">School Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="sc_name" id="sc_name"  placeholder="School Name" value="<?php echo $sc_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">School Phone1</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="sc_phone_1" id="sc_phone_1" placeholder="Phone 1" value="<?php echo $sc_phone_1; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">School Phone2</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="sc_phone_2" id="sc_phone_2" placeholder="Phone 2" value="<?php echo $sc_phone_2; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Web Site</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="sc_website" id="sc_website" value="<?php echo $sc_website; ?>" placeholder="Website" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Email</label>

                                            <div class="col-sm-9">
                                                <input required type="text" name="sc_email" id="sc_email" value="<?php echo $sc_email; ?>" placeholder="Email" class="form-control" />
                                            </div>


                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="sc_status" id="sc_status_a" <?php if ($sc_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="sc_status_a">Active</label> <input type="radio" name="sc_status" id="sc_status_i" value="I" <?php if ($sc_status == 'I') echo 'checked="checked"'; ?> /><label for="sc_status_i">In-Active</label>
                                            </div>


                                        </div>

                                        <!-- /.box-body -->

                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?> <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
