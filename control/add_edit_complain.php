<?php
include("includes/application_top.php");
include("../includes/class/complain.php");
// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");
$cm_stu_id= get_rdata('cm_stu_id');
$cm_title= get_rdata('cm_title');
$cm_description= get_rdata('cm_description');
$cm_status= 'A';
$cm_create_date= $cur_date;
$cm_create_by_id= $tmp_admin_id;
$cm_update_date= $cur_date;
$cm_update_by_id= $tmp_admin_id;

$stu_gr_no = $stu_roll_no =  $stu_first_name = $stu_last_name = "";

$caption = "Add Special Notes";
$btn_caption = "Add Special Notes";

// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);


// Add user entry
if ($act == 'add') {

        $sm_complain = new complain();
        $cm_identy_id = randomPrefix(10);

        $sm_complain->data["cm_identy_id"]=$cm_identy_id;
        $sm_complain->data["cm_stu_id"]=$cm_stu_id;
        $sm_complain->data["cm_title"]=$cm_title;
        $sm_complain->data["cm_description"]=$cm_description;
        $sm_complain->data["cm_status"]=$cm_status;
        $sm_complain->data["cm_create_date"]=$cm_create_date;
        $sm_complain->data["cm_create_by_id"]=$cm_create_by_id;
        $sm_complain->data["cm_update_date"]=$cm_update_date;
        $sm_complain->data["cm_update_by_id"]=$cm_update_by_id;
        $sm_complain->action = 'insert';
        $result = $sm_complain->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            if ($cm_status == 'A') {
                $arr_data = array();
                $arr_data["not_message"] = "special notes:" . $cm_title;
                $arr_data["not_sc_id"] = session_get("admin_sc_id");
                $arr_data["not_create_date"] = $cur_date;
                $arr_data["not_create_by_id"] = $user_id;
                $arr_data["not_update_date"] = $cur_date;
                $arr_data["not_update_by_id"] = $user_id;
                   $arr_data["not_goToScreen"]="specialnotes";

                $arr_data["gcmp_message"] = "specialnotes:" . $cm_title;
                $arr_data["gcmp_title"] = "specialnotes:" . $cm_title;
                $arr_data["gcmp_subtitle"] = "specialnotes:" . $cm_title;
                $arr_data["gcmp_tickerText"] = "specialnotes:" . $cm_title;
                $arr_data["gcmp_create_by"] = $user_id;
                $arr_data["gcmp_create_date"] = $cur_date;
                $arr_data["gcmp_gcm_sc_id"] = session_get("admin_sc_id");
                $arr_data["condition"] = "specialnotes";
                $arr_data["medium"] = "";
                $arr_data["std_id"] = 0;
                $arr_data["cl_id"] = 0;
                $arr_data["stu_id"] = $cm_stu_id;
                $arr_data["gcmp_goToScreen"]="specialnotes";

                add_notes($arr_data);
            }
            header('Location:manage_complain.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);
        }

}

if ($cm_stu_id !='' && $cm_stu_id >0 )
{
    $q = "SELECT stu.stu_id , stu.stu_gr_no , stu.stu_roll_no, stu.stu_first_name, stu.stu_last_name  FROM sm_student stu WHERE stu.stu_id =  ".$cm_stu_id;
    $r = m_process("get_data", $q);
    if ($r['errormsg']!='')
    {
        $errormsg = $r['errormsg'];
    }
    else if ($r['count']>0)
    {
        $stu_gr_no = $r["res"][0]["stu_gr_no"];
        $stu_roll_no = $r["res"][0]["stu_roll_no"];
        $stu_first_name = $r["res"][0]["stu_first_name"];
        $stu_last_name = $r["res"][0]["stu_last_name"];
    }
}

if (session_get('admin_login_type') == 'school') {
    $res_sc_id = session_get('admin_sc_id');
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="cm_stu_id" name="cm_stu_id" value="<?php echo $cm_stu_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3"></label>
                                                    <label class="col-sm-9"><?php echo $stu_gr_no ."-". $stu_roll_no ."-". $stu_first_name ." ".$stu_last_name; ?></label>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Title</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="cm_title" id="cm_title"  placeholder="Title" value="<?php echo $cm_title; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Description</label>
                                                <div class="col-sm-9">
                                                    <textarea required name="cm_description" id="cm_description"  placeholder="Description" class="form-control"><?php echo $cm_description; ?></textarea>
                                                </div>
                                            </div>









                                        </div>
                                    </div><!-- /.box -->
                                    <div class="box-footer">
                                        <?php if ($id == 0) { ?>  <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
