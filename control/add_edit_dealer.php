<?php
include("includes/application_top.php");
include("../includes/class/dealer.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$del_id = get_rdata('del_id');


$del_first_name = get_rdata('del_first_name');
$del_company_name = get_rdata('del_company_name');
$del_last_name = get_rdata('del_last_name');
$del_phone = get_rdata('del_phone');
$del_office_address = get_rdata('del_office_address');
$del_city = get_rdata('del_city');
$del_state_id = get_rdata('del_state_id',0);
$del_postal_code = get_rdata('del_postal_code');
$del_gstno = get_rdata('del_gstno');
$del_panno = get_rdata('del_panno');
$del_email = get_rdata('del_email');
$del_phone_2 = get_rdata('del_phone_2');
$del_status = get_rdata('del_status','A');
$del_igst = get_rdata('del_igst','N');
$del_create_date = $cur_date;
$del_create_by_id = $tmp_admin_id;
$del_update_date = $cur_date;
$del_update_by_id = $tmp_admin_id;
$lo_lastlogin_date = $cur_date;
$del_br_id = $tmp_admin_id;

$caption = "Add Dealer";
$btn_caption = "Add Dealer";
if ($id != 0) {
    $caption = "Edit Dealer";
    $btn_caption = "Edit Dealer";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);

// Get the data from database
if ($act == '' && $id != 0) {
    $sm_dealer = new dealer();
    $sm_dealer->data["*"] = "";
    $sm_dealer->action = 'get';
    $sm_dealer->process_id = $id;
    $result = $sm_dealer->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $del_first_name = $db_row['del_first_name'];
                $del_company_name = $db_row['del_company_name'];
                $del_last_name = $db_row['del_last_name'];
                $del_phone = $db_row['del_phone'];
                $del_office_address = $db_row['del_office_address'];
                $del_city = $db_row['del_city'];
                $del_state_id = $db_row['del_state_id'];
                $del_postal_code = $db_row['del_postal_code'];
                $del_gstno = $db_row['del_gstno'];
                $del_panno = $db_row['del_panno'];
                $del_email = $db_row['del_email'];
                $del_phone_2 = $db_row['del_phone_2'];
                $del_status = $db_row['del_status'];
                $del_igst = $db_row['del_igst'];
                $del_create_date = $db_row['del_create_date'];
                $del_create_by_id = $db_row['del_create_by_id'];
                $del_update_date = $db_row['del_update_date'];
                $del_update_by_id = $db_row['del_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
    if ($del_company_name !='')
    {
        $not_value = " AND del_br_id = " . $tmp_admin_id;
        $arr_duplicate_name = found_duplicate('sm_dealer', 'del_company_name', escape($del_company_name), $not_value);
        if ($arr_duplicate_name['error_message'] != '') {
            $errormsg = $arr_duplicate_name['error_message'];
        } else if ($arr_duplicate_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for dealer name';
        }
    }


    if ($errormsg == '') {
        $sm_dealer = new dealer();


        $sm_dealer->data["del_first_name"] = $del_first_name;
        $sm_dealer->data["del_company_name"] = escape($del_company_name);
        $sm_dealer->data["del_last_name"] = $del_last_name;
        $sm_dealer->data["del_phone"] = $del_phone;
        $sm_dealer->data["del_office_address"] = escape($del_office_address);
        $sm_dealer->data["del_city"] = $del_city;
        $sm_dealer->data["del_state_id"] = $del_state_id;
        $sm_dealer->data["del_postal_code"] = $del_postal_code;
        $sm_dealer->data["del_gstno"] = $del_gstno;
        $sm_dealer->data["del_panno"] = $del_panno;
        $sm_dealer->data["del_email"] = $del_email;
        $sm_dealer->data["del_phone_2"] = $del_phone_2;
        $sm_dealer->data["del_status"] = $del_status;
        $sm_dealer->data["del_igst"] = $del_igst;
        $sm_dealer->data["del_create_date"] = $del_create_date;
        $sm_dealer->data["del_create_by_id"] = $del_create_by_id;
        $sm_dealer->data["del_update_date"] = $del_update_date;
        $sm_dealer->data["del_update_by_id"] = $del_update_by_id;
        $sm_dealer->data["del_br_id"] = $del_br_id;
        $sm_dealer->action = 'insert';
        $result = $sm_dealer->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

                  $arr_log = array();
                  $arr_log["log_message"]= $del_first_name." ".$del_last_name ." (".$result['id']. ") has been added.";
                  $arr_log["log_del_id"]= $result['id'];
                  $arr_log["log_admin_id"]= $tmp_admin_id;
                  $arr_log["log_stu_id"]= 0;
                  $arr_log["log_action"]= "add_dealer";
                  add_log($arr_log);

                  header('Location:manage_dealer.php?msg=2&page=1&per_page=' . $per_page);
                  exit(0);
         }
    }
}

// Update user entry
if ($act == 'update') {
    if ($del_company_name !='')
    {
        $not_value = " AND del_br_id = " . $tmp_admin_id . " AND del_id != " . $id;
        $arr_duplicate_name = found_duplicate('sm_dealer', 'del_company_name', escape($del_company_name), $not_value);
        if ($arr_duplicate_name['error_message'] != '') {
            $errormsg = $arr_duplicate_name['error_message'];
        } else if ($arr_duplicate_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for dealer name. ';
        }
    }

        $sm_dealer = new dealer();
        $sm_dealer->data["del_first_name"] = $del_first_name;
        $sm_dealer->data["del_company_name"] = escape($del_company_name);
        $sm_dealer->data["del_last_name"] = $del_last_name;
        $sm_dealer->data["del_phone"] = $del_phone;
        $sm_dealer->data["del_office_address"] = escape($del_office_address);
        $sm_dealer->data["del_city"] = $del_city;
        $sm_dealer->data["del_state_id"] = $del_state_id;
        $sm_dealer->data["del_postal_code"] = $del_postal_code;
        $sm_dealer->data["del_gstno"] = $del_gstno;
        $sm_dealer->data["del_panno"] = $del_panno;
        $sm_dealer->data["del_email"] = $del_email;
        $sm_dealer->data["del_phone_2"] = $del_phone_2;
        $sm_dealer->data["del_status"] = $del_status;
        $sm_dealer->data["del_igst"] = $del_igst;
        $sm_dealer->data["del_create_date"] = $del_create_date;
        $sm_dealer->data["del_create_by_id"] = $del_create_by_id;
        $sm_dealer->data["del_update_date"] = $del_update_date;
        $sm_dealer->data["del_update_by_id"] = $del_update_by_id;
        $sm_dealer->data["del_br_id"] = $del_br_id;
        $sm_dealer->action = 'update';
        $sm_dealer->process_id = $id;
        $result = $sm_dealer->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                  header('Location:manage_dealer.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);

        }
    }


//if (session_get('admin_login_type') == 'school') {
//    $del_sc_id = session_get('admin_sc_id');
//}
$del_sc_id = 0;

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
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <!-- START -->
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">First Name</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="del_first_name" id="del_first_name"  placeholder="First Name" value="<?php echo $del_first_name; ?>" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Last Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="del_last_name" id="del_last_name"  placeholder="Last Name" value="<?php echo $del_last_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Company Name</label>
                                                <div class="col-sm-9">

                                                <input type="text" name="del_company_name" id="del_company_name"  placeholder="Company Name" value="<?php echo $del_company_name; ?>" class="form-control" />
</div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Office Address</label>
                                                <div class="col-sm-9">
                                                  <textarea name="del_office_address" id="del_office_address"  placeholder="Office Address" class="form-control"><?php echo $del_office_address; ?></textarea>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input  type="text" name="del_email" id="del_email"  placeholder="Email" value="<?php echo $del_email; ?>" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Contact</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="del_phone" id="del_phone"  placeholder="Contact" value="<?php echo $del_phone; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Contact 2</label>
                                                <div class="col-sm-9">
                                                    <input  type="text" name="del_phone_2" id="del_phone_2"  placeholder="Contact 2" value="<?php echo $del_phone_2; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">GST No</label>
                                                <div class="col-sm-9">
                                                    <input  type="text" name="del_gstno" id="del_gstno"  placeholder="GST No" value="<?php echo $del_gstno; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">PAN No</label>
                                                <div class="col-sm-9">
                                                    <input  type="text" name="del_panno" id="del_panno"  placeholder="PAN No" value="<?php echo $del_panno; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">IGST</label>
                                                <div class="col-sm-9">
                                                <select name="del_igst" id="del_igst" class="form-control" >
                                                <option value="Y" <?php if($del_igst == 'Y') echo "selected"; ?>>Yes</option>
                                                <option value="N"  <?php if($del_igst == 'N') echo "selected"; ?> >No</option>
                                                </select>
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                        </div>
                                    </div><!-- /.box -->
                                    <div class="box-footer">
<?php if ($id == 0) { ?>  <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                        <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                    </div><!-- /.box-footer -->
                                  </br>
                                </form>
                                <!-- general form elements disabled -->
                                <div>
                                </div>
                            </div>
                            </section>
                        </div>
<?php include("includes/footer.php"); ?>
<?php include("includes/models.php"); ?>
                    </div>
                    </body>
                    </html>
