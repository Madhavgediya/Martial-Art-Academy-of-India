<?php
include("includes/application_top.php");
include("../includes/class/dailydarshan.php");
include("../includes/class/dailydarshan_photos.php");

// Set the caption of button




$id = get_rdata("id", 0);
$act = get_rdata("act");
$ga_id = get_rdata('ga_id');
$ga_title = get_rdata('ga_title');
$ga_cover_image = get_rdata('ga_cover_image');
$ga_cover_image_old = get_rdata('ga_cover_image_old');
$ga_sc_id = get_rdata('ga_sc_id');
$ga_status = get_rdata('ga_status','A');
$ga_create_date = $cur_date;
$ga_create_by_id = $user_id;
$ga_update_date = $cur_date;
$ga_update_by_id = $user_id;
$file_counter = InputPro('file_counter');


$caption = "Add Daily Darshan";
$btn_caption = "Add Daily Darshan";
if ($id != 0) {
    $caption = "Edit Daily Darshan";
    $btn_caption = "Edit Daily Darshan";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_gallery = new dailydarshan();
    $sm_gallery->data["*"] = "";
    $sm_gallery->action = 'get';
    $sm_gallery->process_id = $id;
    $result = $sm_gallery->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $ga_title = $db_row['ga_title'];
                $ga_cover_image = $db_row['ga_cover_image'];
                $ga_sc_id = $db_row['ga_sc_id'];
                $ga_status = $db_row['ga_status'];
                $ga_create_date = $db_row['ga_create_date'];
                $ga_create_by_id = $db_row['ga_create_by_id'];
                $ga_update_date = $db_row['ga_update_date'];
                $ga_update_by_id = $db_row['ga_update_by_id'];
                $ga_cover_image_old = $db_row['ga_cover_image'];

            }
        }
    }
}

// Add user entry
if ($act == 'add') {


    $not_value = " AND ga_sc_id = " . $ga_sc_id;
    $arr_duplicate_school_name = found_duplicate('sm_dailydarshan', 'ga_title', $ga_title, $not_value);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for daily darshan name ';
    }

if ($errormsg == '') {
    if ($_FILES['ga_cover_image']['error'] == 0) {  /// Image

        $file_array = explode(".", $_FILES['ga_cover_image']['name']);
        $file_ext = $file_array [count($file_array) - 1];
        $file_ext = strtolower($file_ext);

        if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
            $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
        } else {
            $ga_cover_image = "dailydarshan_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['ga_cover_image']['name']);
            $DestPath = DAILYDARSHAN_IMAGE . $ga_cover_image;
            move_uploaded_file($_FILES['ga_cover_image']['tmp_name'], $DestPath);
        }
    } else {
        $errormsg = 'Please upload gallery image';
    }
}
    if ($errormsg == '') {
        $sm_gallery = new dailydarshan();

        $sm_gallery->data["ga_title"]=$ga_title;
            $sm_gallery->data["ga_cover_image"]=SITE_URL . "images/dailydarshan/" . $ga_cover_image;
            $sm_gallery->data["ga_sc_id"]=$ga_sc_id;
            $sm_gallery->data["ga_status"]=$ga_status;
            $sm_gallery->data["ga_create_date"]=$ga_create_date;
            $sm_gallery->data["ga_create_by_id"]=$ga_create_by_id;
            $sm_gallery->data["ga_update_date"]=$ga_update_date;
            $sm_gallery->data["ga_update_by_id"]=$ga_update_by_id;
            $sm_gallery->action = 'insert';

        $result = $sm_gallery->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // adding gallery photos images to vision
            $gp_ga_id = $result['id'];
            $iimg = 0;
            while ($iimg <= $file_counter) {
                if ($_FILES['gallery_details_pic_' . $iimg]['error'] == 0) {  /// Image
                    $file_array = explode(".", $_FILES['gallery_details_pic_' . $iimg]['name']);
                    $file_ext = $file_array [count($file_array) - 1];
                    $file_ext = strtolower($file_ext);

                    if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                        $errormsg .="Error for file " . $_FILES['gallery_details_pic_' . $iimg]['name'] . "allowed file types are " . implode(",", $arr_allow_file_type);
                    } else {
                        $gp_image = "dailydarshan_" . strtolower(randomPrefix(5)) . "_" . clean_name(['gallery_details_pic_' . $iimg]['name']);
                        $DestPath = DAILYDARSHAN_IMAGE . $gp_image;
                        move_uploaded_file($_FILES['gallery_details_pic_' . $iimg]['tmp_name'], $DestPath);
                        //echo $gp_image; exit;
                        // adding files in db
                        // $sm_gallery_photos->data["gp_id"]=$gp_id;
                        $sm_gallery_photos = new dailydarshan_photos();

                        $sm_gallery_photos->data["gp_image"] = SITE_URL . "images/dailydarshan/" . $gp_image;
                        $sm_gallery_photos->data["gp_title"] = $ga_title;
                        $sm_gallery_photos->data["gp_image_alt"] = $ga_title;
                        $sm_gallery_photos->data["gp_ga_id"] = $gp_ga_id;
                        $sm_gallery_photos->data["gp_status"] = 'A';
                        $sm_gallery_photos->data["gp_create_date"] = $ga_create_date;
                        $sm_gallery_photos->data["gp_create_by_id"] = $ga_create_by_id;
                        $sm_gallery_photos->data["gp_update_date"] = $ga_update_date;
                        $sm_gallery_photos->data["gp_update_by_id"] = $ga_update_by_id;
                        $sm_gallery_photos->action = 'insert';
                        $result = $sm_gallery_photos->process();

                        if ($result['status'] == 'failure') {
                            $errormsg .= $result['errormsg'];
                        }

                        // end of adding files in db
                    }
                }
                $iimg++;
            }
            if ($errormsg == '') {

                if ($ga_status == 'A')
                {
                    $arr_data = array();
                    $arr_data["not_message"] = "gallery:".$ga_title;
                    $arr_data["not_sc_id"] = $ga_sc_id;
                    $arr_data["not_create_date"] = $cur_date;
                    $arr_data["not_create_by_id"] = $user_id;
                    $arr_data["not_update_date"] = $cur_date;
                    $arr_data["not_update_by_id"] = $user_id;
                       $arr_data["not_goToScreen"]="dailydarshan";

                    $arr_data["gcmp_message"] =  "gallery:".$ga_title;
                    $arr_data["gcmp_title"] = "gallery:".$ga_title;
                    $arr_data["gcmp_subtitle"] = "gallery:".$ga_title;
                    $arr_data["gcmp_tickerText"] = "gallery:".$ga_title;
                    $arr_data["gcmp_create_by"]=$user_id;
                    $arr_data["gcmp_create_date"]=$cur_date;
                    $arr_data["gcmp_gcm_sc_id"]=$ga_sc_id;
                     $arr_data["gcmp_goToScreen"]="dailydarshan";

                    add_notes($arr_data);

                }

                header('Location:manage_dailydarshan.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}

// Update user entry
if ($act == 'update') {
    $not_value = " AND ga_sc_id = " . $ga_sc_id . " AND ga_id != " . $id;
    $arr_duplicate_gallery_name = found_duplicate('sm_dailydarshan', 'ga_title', $ga_title, $not_value);
    if ($arr_duplicate_gallery_name['error_message'] != '') {
        $errormsg = $arr_duplicate_gallery_name['error_message'];
    } else if ($arr_duplicate_gallery_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for gallery name ';
    }


if ($errormsg == '') {
    if ($_FILES['ga_cover_image']['error'] == 0) {  /// Image

        $file_array = explode(".", $_FILES['ga_cover_image']['name']);
        $file_ext = $file_array [count($file_array) - 1];
        $file_ext = strtolower($file_ext);

        if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
            $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
        } else {
            $ga_cover_image = "dailydarshan_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['ga_cover_image']['name']);
            $DestPath = DAILYDARSHAN_IMAGE . $ga_cover_image;
            move_uploaded_file($_FILES['ga_cover_image']['tmp_name'], $DestPath);
            $ga_cover_image = SITE_URL . "/images/dailydarshan/" . $ga_cover_image;
        }
    } else {
        $ga_cover_image = $ga_cover_image_old;
    }
}

    if ($errormsg == '') {
        $sm_gallery = new dailydarshan();

        $sm_gallery->data["ga_cover_image"]=$ga_cover_image;
            $sm_gallery->data["ga_sc_id"]=$ga_sc_id;
            $sm_gallery->data["ga_status"]=$ga_status;
            $sm_gallery->data["ga_create_date"]=$ga_create_date;
            $sm_gallery->data["ga_create_by_id"]=$ga_create_by_id;
            $sm_gallery->data["ga_update_date"]=$ga_update_date;
            $sm_gallery->data["ga_update_by_id"]=$ga_update_by_id;

        $sm_gallery->action = 'update';
        $sm_gallery->process_id = $id;
        $result = $sm_gallery->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($ga_cover_image != '' && $ga_cover_image_old != '' && ($ga_cover_image != $ga_cover_image_old)) {
                $ga_cover_image_old = str_replace(SITE_URL, '', $ga_cover_image_old);
                $ga_cover_image_old = ".." . $ga_cover_image_old;
                // echo $ga_cover_image_old;
                // exit;
                unlink($ga_cover_image_old);
            }

            $iimg = 0;
            while ($iimg <= $file_counter) {
                if ($_FILES['gallery_details_pic_' . $iimg]['error'] == 0) {  /// Image
                    $file_array = explode(".", $_FILES['gallery_details_pic_' . $iimg]['name']);
                    $file_ext = $file_array [count($file_array) - 1];
                    $file_ext = strtolower($file_ext);

                    if (!(in_array($file_ext, $arr_allow_file_type) == 1)) {
                        $errormsg .="Error for file " . $_FILES['gallery_details_pic_' . $iimg]['name'] . "allowed file types are " . implode(",", $arr_allow_file_type);
                    } else {
                        $gp_image = "dailydarshan_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['gallery_details_pic_' . $iimg]['name']);
                        $DestPath = DAILYDARSHAN_IMAGE . $gp_image;
                        move_uploaded_file($_FILES['gallery_details_pic_' . $iimg]['tmp_name'], $DestPath);
                        //echo $gp_image; exit;
                        // adding files in db
                        // $sm_gallery_photos->data["gp_id"]=$gp_id;
                        $sm_gallery_photos = new dailydarshan_photos();

                        $sm_gallery_photos->data["gp_image"] = SITE_URL . "images/dailydarshan/" . $gp_image;
                        $sm_gallery_photos->data["gp_title"] = $ga_title;
                        $sm_gallery_photos->data["gp_image_alt"] = $ga_title;
                        $sm_gallery_photos->data["gp_ga_id"] = $id;
                        $sm_gallery_photos->data["gp_status"] = 'A';
                        $sm_gallery_photos->data["gp_create_date"] = $ga_create_date;
                        $sm_gallery_photos->data["gp_create_by_id"] = $ga_create_by_id;
                        $sm_gallery_photos->data["gp_update_date"] = $ga_update_date;
                        $sm_gallery_photos->data["gp_update_by_id"] = $ga_update_by_id;
                        $sm_gallery_photos->action = 'insert';
                        $result = $sm_gallery_photos->process();

                        if ($result['status'] == 'failure') {
                            $errormsg .= $result['errormsg'];
                        }

                        // end of adding files in db
                    }
                }
                $iimg++;
            }
            if ($errormsg == '') {
                // If success then redirect to manage user page
                header('Location:manage_dailydarshan.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}

if ($id != 0) {
    $q = "SELECT gp.gp_image ,gp.gp_id  FROM  sm_dailydarshan_photos gp INNER JOIN sm_dailydarshan g ON (gp.gp_ga_id=g.ga_id) INNER JOIN sm_school_master sm ON (g.ga_sc_id=sm.sc_id) WHERE g.ga_id= " . $id;
    if (session_get('admin_login_type') == 'school') {
        $q.=" AND sm.sc_id= " . session_get('admin_sc_id');
    }
// echo $q;
    $result = m_process("get_data", $q);

    if ($result['errormsg'] != '') {
        $errormsg = $result['errormsg'];
    }
}
if (session_get('admin_login_type') == 'school') {
    $ga_sc_id = session_get('admin_sc_id');
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_add_edit_gallery();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="file_counter" name="file_counter" />

                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

                                    <input type="hidden" id="ga_cover_image_old" name="ga_cover_image_old" value="<?php echo $ga_cover_image_old; ?>" />
                                    <input type="hidden" id="ga_sc_id" name="ga_sc_id" value="<?php echo $ga_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="ga_title" id="ga_title"  placeholder="Daily Darshan Name" value="<?php echo $ga_title; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Image</label>
                                                <div class="col-sm-9">
                                                    <input <?php if ($id ==0 )  {echo "required"; }  ?> type="file" name="ga_cover_image" id="ga_cover_image"  />
<?php if ($ga_cover_image_old != '') { ?>
                                                        <a href="<?php echo $ga_cover_image_old; ?>" target="_blank" ><img src="<?php echo $ga_cover_image_old; ?>" height="120px" width="120px" /></a>
<?php } ?>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                    <input type="radio" name="ga_status" id="ga_status_a" <?php if ($ga_status == 'A') {
    echo 'checked="checked"';
}; ?>  value="A" /><label for="ga_status_a">Active</label> <input type="radio" name="ga_status" id="ga_status_i" value="I" <?php if ($ga_status == 'I') echo 'checked="checked"'; ?> /><label for="ga_status_i">InActive</label>
                                                </div>


                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">More Images</label>
                                                <div class="col-sm-9">
                                                    <input type="file" name="gallery_details_pic_0" />
                                                    <input type="button" value="Add More" class="btn btn-success" onclick="add_files();" id="add_more" name="add_more" />
                                                </div>

                                            </div>
                                            <!-- /.box-body -->

                                        </div>
                                    </div><!-- /.box -->
                                    <div class="box-footer">
                                                    <?php if ($id == 0) { ?>           <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                        <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                    </div><!-- /.box-footer -->
                                </form>
                                <!-- general form elements disabled -->
                                <div>
                                </div>
                            </div>
<?php if ($id != 0 && $errormsg == '' && $result['count'] > 0) { ?>
                                <!-- start code -->

                                <div class="row">
                                    <div class="col-xs-12">Other Event Images</div>
                                    <div class="col-xs-12">
                                        <div class="box">
                                            <div class="box-body">
                                                <table id="example2" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th align="center">Image</th>
                                                            <th align="center" class="t_align_center"  width="100px">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
    <?php
    $class = '';
    $srNo = 0;

    foreach ($result['res'] as $db_row) {
        $srNo++;
        if ($srNo % 2 == 0) {
            $class = 'even';
        } else {
            $class = 'odd';
        }
        ?>
                                                            <tr class="<?php echo $class; ?>" id="tr_<?php echo $db_row['gp_id']; ?>" >
                                                                <td style="padding-left:10px;"><a href="<?php echo $db_row['gp_image']; ?>" target="_blank" > <img src="<?php echo $db_row['gp_image']; ?>" height="120px" width="120px" /> </td>
                                                                <td>
                                                                    <div class="col-md-1" id="dv_process_<?php echo $db_row['gp_id']; ?>" style="display:none;">

                                                                        <!-- Loading (remove the following to stop the loading)-->
                                                                        <div class="overlay mt20">
                                                                            <i class="fa fa-refresh fa-spin"></i>
                                                                        </div>
                                                                        <!-- end loading -->
                                                                        <!-- /.box -->
                                                                    </div>

                                                                    <center><a href="javascript:void(0);" id="a_process_<?php echo $db_row['gp_id']; ?>" class="delete glyphicon glyphicon-remove" onclick="delete_image('delete-dailydarshan-image',<?php echo $db_row['gp_id']; ?>)"></a></center></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end code -->
<?php } ?>
                            </section>
                        </div>

                        <!-- end of our page-->
<?php include("includes/footer.php"); ?>
                    </div>
                    <script type="text/javascript" language="javascript">

                    </script>
                    </body>
                    </html>
