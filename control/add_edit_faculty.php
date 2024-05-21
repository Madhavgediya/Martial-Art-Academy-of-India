<?php
include("includes/application_top.php");
include("../includes/class/faculty.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$fac_id = get_rdata('fac_id');
$fac_identity_id = get_rdata('fac_identity_id');
$fac_name = get_rdata('fac_name');
$fac_phone_1 = get_rdata('fac_phone_1');
$fac_phone_2 = get_rdata('fac_phone_2');
$fac_email = get_rdata('fac_email');
$fac_address_1 = get_rdata('fac_address_1');
$fac_address_2 = get_rdata('fac_address_2');
$fac_city = get_rdata('fac_city');
$fac_photo = get_rdata('fac_photo');
$fac_st_id = get_rdata('fac_st_id',0);
$fac_subject = get_rdata('fac_subject');
$fac_designation = get_rdata('fac_designation');
$fac_salary = get_rdata('fac_salary',0);
$fac_experience = get_rdata('fac_experience');
$fac_br_id = get_rdata('fac_br_id',$tmp_admin_id);
$fac_status = get_rdata('fac_status', 'A');
$fac_create_date = $cur_date;
$fac_create_by_id = $tmp_admin_id;
$fac_update_date = $cur_date;
$fac_update_by_id = $tmp_admin_id;
$fac_photo_old = get_rdata('fac_photo_old');

$facs_count = 0;
$caption = "Add Faculty";
$btn_caption = "Add Faculty";
if ($id != 0) {
    $caption = "Edit Faculty";
    $btn_caption = "Edit Faculty";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_faculty = new faculty();
    $sm_faculty->data["*"] = "";
    $sm_faculty->action = 'get';
    $sm_faculty->process_id = $id;
    $result = $sm_faculty->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $fac_identity_id = $db_row['fac_identity_id'];
                $fac_name = $db_row['fac_name'];
                $fac_phone_1 = $db_row['fac_phone_1'];
                $fac_phone_2 = $db_row['fac_phone_2'];
                $fac_email = $db_row['fac_email'];
                $fac_address_1 = $db_row['fac_address_1'];
                $fac_address_2 = $db_row['fac_address_2'];
                $fac_city = $db_row['fac_city'];
                $fac_photo = $db_row['fac_photo'];
                $fac_photo_old = $db_row['fac_photo'];
                $fac_st_id = $db_row['fac_st_id'];
                $fac_subject = $db_row['fac_subject'];
                $fac_experience = $db_row['fac_experience'];
                $fac_br_id = $db_row['fac_br_id'];
                $fac_status = $db_row['fac_status'];
                $fac_designation = $db_row['fac_designation'];
                $fac_salary = $db_row['fac_salary'];
                
                $fac_create_date = $db_row['fac_create_date'];
                $fac_create_by_id = $db_row['fac_create_by_id'];
                $fac_update_date = $db_row['fac_update_date'];
                $fac_update_by_id = $db_row['fac_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') 
 {

    if ($fac_identity_id == '')
    {
        $fac_identity_id  = randomPrefix(10);
    }
    if ($fac_identity_id == '') {
        $errormsg = 'Faculty identity no is required ';
    } else {
        $not_value = " AND fac_br_id = " . $fac_br_id;
        $arr_duplicate_school_name = found_duplicate('sm_faculty', 'fac_identity_id', $fac_identity_id, $not_value);
        if ($arr_duplicate_school_name['error_message'] != '') {
            $errormsg = $arr_duplicate_school_name['error_message'];
        } else if ($arr_duplicate_school_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for faculty identity no ';
        }
    }
    if ($errormsg == '') 
    {
        if ($fac_email != '') 
        {
            $not_value = " AND fac_br_id = " . $fac_br_id;
            $arr_duplicate_faculty_name = found_duplicate('sm_faculty', 'fac_email', $fac_email, $not_value);
            if ($arr_duplicate_faculty_name['error_message'] != '') 
            {
                $errormsg = $arr_duplicate_faculty_name['error_message'];
            } 
            else if ($arr_duplicate_faculty_name['duplicate'] == true) 
            {
                $errormsg = 'Duplicate entry for faculty email address';
            }
        }
        else
        {
            $errormsg = 'Faculty email is required ';
        }
    }

    if ($errormsg == '') {
        if ($_FILES['fac_photo']['error'] == 0) {  /// Image 
            $file_array = explode(".", $_FILES['fac_photo']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else {
                $fac_photo = "faculties_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['fac_photo']['name']);
                $DestPath = FACULTIES_IMAGE . $fac_photo;
                move_uploaded_file($_FILES['fac_photo']['tmp_name'], $DestPath);
            }
        } else {
            $fac_photo = "faculties_" . strtolower(randomPrefix(5)) . "_faculties_main_images.png";
            $DestPath = FACULTIES_IMAGE . "faculties_main_images.png";
        }
    }
    if ($errormsg == '') {
        $sm_faculty = new faculty();

        $sm_faculty->data["fac_identity_id"] = $fac_identity_id;
        $sm_faculty->data["fac_name"] = $fac_name;
        $sm_faculty->data["fac_phone_1"] = $fac_phone_1;
        $sm_faculty->data["fac_phone_2"] = $fac_phone_2;
        $sm_faculty->data["fac_email"] = $fac_email;
        $sm_faculty->data["fac_address_1"] = $fac_address_1;
        $sm_faculty->data["fac_address_2"] = $fac_address_2;
        $sm_faculty->data["fac_city"] = $fac_city;
        $sm_faculty->data["fac_photo"] = SITE_URL . "images/faculties/" . $fac_photo;
        $sm_faculty->data["fac_st_id"] = $fac_st_id;
        $sm_faculty->data["fac_subject"] = $fac_subject;
        $sm_faculty->data["fac_experience"] = $fac_experience;
        $sm_faculty->data["fac_br_id"] = $fac_br_id;
        $sm_faculty->data["fac_status"] = $fac_status;
        $sm_faculty->data["fac_designation"] = $fac_designation;
        $sm_faculty->data["fac_salary"] = $fac_salary;
        
        $sm_faculty->data["fac_create_date"] = $fac_create_date;
        $sm_faculty->data["fac_create_by_id"] = $fac_create_by_id;
        $sm_faculty->data["fac_update_date"] = $fac_update_date;
        $sm_faculty->data["fac_update_by_id"] = $fac_update_by_id;

        $sm_faculty->action = 'insert';
        $result = $sm_faculty->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_faculty.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
    if ($fac_identity_id == '') {
        $errormsg = 'Faculty identity no is required ';
    } else {
        $not_value = " AND fac_br_id = " . $tmp_admin_id . " AND fac_id != " . $id;
        $arr_duplicate_faculty_name = found_duplicate('sm_faculty', 'fac_identity_id', $fac_identity_id, $not_value);
        if ($arr_duplicate_faculty_name['error_message'] != '') {
            $errormsg = $arr_duplicate_faculty_name['error_message'];
        } else if ($arr_duplicate_faculty_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for faculty identity no ';
        }
    }
    if ($errormsg == '') {
        if ($fac_email == '') 
        {
            $errormsg = 'Faculty email address is required ';
        } else 
        {
            $not_value = " AND fac_br_id = " . $tmp_admin_id . " AND fac_id != " . $id;
            $arr_duplicate_faculty_name = found_duplicate('sm_faculty', 'fac_email', $fac_email, $not_value);
            if ($arr_duplicate_faculty_name['error_message'] != '') {
                $errormsg = $arr_duplicate_faculty_name['error_message'];
            } else if ($arr_duplicate_faculty_name['duplicate'] == true) {
                $errormsg = 'Duplicate entry for faculty email address';
            }
        }
    }
    if ($errormsg == '') {
        if ($_FILES['fac_photo']['error'] == 0) {  /// Image 
            $file_array = explode(".", $_FILES['fac_photo']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else {
                $fac_photo = "faculties_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['fac_photo']['name']);
                $DestPath = FACULTIES_IMAGE . $fac_photo;
                move_uploaded_file($_FILES['fac_photo']['tmp_name'], $DestPath);
                $fac_photo = SITE_URL . "/images/faculties/" . $fac_photo;
            }
        } else {
            $fac_photo = $fac_photo_old;
        }
    }

    if ($errormsg == '') {
        $sm_faculty = new faculty();
        $sm_faculty->data["fac_identity_id"] = $fac_identity_id;
        $sm_faculty->data["fac_name"] = $fac_name;
        $sm_faculty->data["fac_phone_1"] = $fac_phone_1;
        $sm_faculty->data["fac_phone_2"] = $fac_phone_2;
        $sm_faculty->data["fac_email"] = $fac_email;
        $sm_faculty->data["fac_address_1"] = $fac_address_1;
        $sm_faculty->data["fac_address_2"] = $fac_address_2;
        $sm_faculty->data["fac_city"] = $fac_city;
        $sm_faculty->data["fac_photo"] = $fac_photo;
        $sm_faculty->data["fac_st_id"] = $fac_st_id;
        $sm_faculty->data["fac_subject"] = $fac_subject;
        $sm_faculty->data["fac_experience"] = $fac_experience;
        $sm_faculty->data["fac_br_id"] = $fac_br_id;
        $sm_faculty->data["fac_status"] = $fac_status;
        $sm_faculty->data["fac_designation"] = $fac_designation;
        $sm_faculty->data["fac_salary"] = $fac_salary;
        $sm_faculty->data["fac_create_date"] = $fac_create_date;
        $sm_faculty->data["fac_create_by_id"] = $fac_create_by_id;
        $sm_faculty->data["fac_update_date"] = $fac_update_date;
        $sm_faculty->data["fac_update_by_id"] = $fac_update_by_id;
        $sm_faculty->action = 'update';
        $sm_faculty->process_id = $id;
        $result = $sm_faculty->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // if ($fac_photo != '' && $fac_photo_old != '' && ($fac_photo != $fac_photo_old)) {
            //     $fac_photo_old = str_replace(SITE_URL, '', $fac_photo_old);
            //     $fac_photo_old = ".." . $fac_photo_old;
            //     unlink($fac_photo_old);
            // }
            // If success then redirect to manage user page
                header('Location:manage_faculty.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}



$q_facs = "SELECT f.* FROM sm_faculty f WHERE f.fac_br_id = ".$tmp_admin_id." AND f.fac_id  = $id ";

$result_facs = m_process("get_data", $q_facs);
if ($result_facs['errormsg'] != '') {
    $errormsg = $result_facs['errormsg'];
} else {
    $facs_count = $result_facs['count'];
}
// echo 'error'.$errormsg.'end of error';
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport" />
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
                            <form name="form1" id="form1" enctype="multipart/form-data" method="post"
                                class="form-horizontal" onsubmit="return validate_user();">
                                <input type="hidden" id="act" name="act" />
                                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

                                <input type="hidden" id="fac_photo_old" name="fac_photo_old"
                                    value="<?php echo $fac_photo_old; ?>" />
                                <input type="hidden" id="fac_br_id" name="fac_br_id"
                                    value="<?php echo $fac_br_id; ?>" />
                                <div class="box-body">
                                    <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="fac_name" id="fac_name"
                                                    placeholder="Faculty Name" value="<?php echo $fac_name; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Identity Id</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="fac_identity_id" id="fac_identity_id"
                                                    placeholder="Faculty Identity Id"
                                                    value="<?php echo $fac_identity_id; ?>" class="form-control" />
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Phone</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="fac_phone_1" id="fac_phone_1"
                                                    placeholder="Faculty Phone" value="<?php echo $fac_phone_1; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Email</label>
                                            <div class="col-sm-9">
                                                <input type="email" name="fac_email" id="fac_email"
                                                    placeholder="Faculty Email" value="<?php echo $fac_email; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Subject</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="fac_subject" id="fac_subject"
                                                    placeholder="Faculty Subject" value="<?php echo $fac_subject; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Designation</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="fac_designation" id="fac_designation"
                                                    placeholder="Faculty Designation"
                                                    value="<?php echo $fac_designation; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Salary</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="fac_salary" id="fac_salary"
                                                    placeholder="Faculty Salary" value="<?php echo $fac_salary; ?>"
                                                    class="form-control" />
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Image</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="fac_photo" id="fac_photo" />
                                                <?php if ($fac_photo_old != '') { ?>
                                                <a href="<?php echo $fac_photo_old; ?>" target="_blank">View Image</a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="fac_status" id="fac_status_a" <?php
                                                    if ($fac_status == 'A') {
                                                        echo 'checked="checked"';
                                                    };
                                                    ?> value="A" /><label for="fac_status_a">Active</label> <input
                                                    type="radio" name="fac_status" id="fac_status_i" value="I"
                                                    <?php if ($fac_status == 'I') echo 'checked="checked"'; ?> /><label
                                                    for="fac_status_i">InActive</label>
                                            </div>


                                        </div>
                                    </div>
                                </div><!-- /.box -->
                                <div class="box-footer">
                                    <?php if ($id == 0) { ?> <input type="reset" value="Reset" class="btn btn-default"
                                        id="btnReset" name="btnReset" /> <?php } ?>
                                    <button type="submit" class="btn btn-info pull-right" id="btnAddUser"
                                        name="btnAddUser">Save</button>
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