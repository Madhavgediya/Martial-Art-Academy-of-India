<?php
include("includes/application_top.php");

// Set the caption of button
if ($tmp_type != 'admin')
{    header("location:index.php"); }
$id = get_rdata("id", 0);
$act = get_rdata("act");
$br_id = get_rdata('br_id');
$br_name = get_rdata('br_name');
$br_pass = get_rdata('br_pass');
$br_contact_p_name1 = get_rdata('br_contact_p_name1');
$br_login = get_rdata('br_login');
$br_contact_p_phone1 = get_rdata('br_contact_p_phone1');
$br_contact_p_email1 = get_rdata('br_contact_p_email1');
$br_contact_p_name2 = get_rdata('br_contact_p_name2');
$br_contact_p_phone2 = get_rdata('br_contact_p_phone2');
$br_contact_p_email2 = get_rdata('br_contact_p_email2');
$br_add_1 = get_rdata('br_add_1');
$br_add_2 = get_rdata('br_add_2');
$br_city = get_rdata('br_city');
$br_district = get_rdata('br_district');
$br_state_id = get_rdata('br_state_id');
$br_country = "India";
$br_postalcode = get_rdata('br_postalcode');
$br_batch_time = get_rdata('br_batch_time');
$br_invoice_prefix = get_rdata('br_invoice_prefix');
$br_logo_old = get_rdata('br_logo_old');

$br_type = 'branch';
$br_status = get_rdata('br_status','A');
$br_create_date = $cur_date;

$br_create_by_id = $tmp_admin_id;
$br_update_date = $cur_date;
$br_update_by_id = $tmp_admin_id;



$caption = "Add Branch";
$btn_caption = "Add Branch";
if ($id != 0) {
    $caption = "Edit Branch";
    $btn_caption = "Edit Branch";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_branch = new branch();
    $sm_branch->data["*"] = "";
    $sm_branch->action = 'get';
    $sm_branch->process_id = $id;
    $result = $sm_branch->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $br_id = $db_row['br_id'];
                $br_name = $db_row['br_name'];
                $br_contact_p_name1 = $db_row['br_contact_p_name1'];
                $br_login = $db_row['br_login'];
                $br_contact_p_phone1 = $db_row['br_contact_p_phone1'];
                $br_contact_p_email1 = $db_row['br_contact_p_email1'];
                $br_contact_p_name2 = $db_row['br_contact_p_name2'];
                $br_contact_p_phone2 = $db_row['br_contact_p_phone2'];
                $br_contact_p_email2 = $db_row['br_contact_p_email2'];
                $br_add_1 = $db_row['br_add_1'];
                $br_add_2 = $db_row['br_add_2'];
                $br_city = $db_row['br_city'];
                $br_district = $db_row['br_district'];
                $br_state_id = $db_row['br_state_id'];
                $br_country = $db_row['br_country'];
                $br_postalcode = $db_row['br_postalcode'];
                $br_invoice_prefix = $db_row['br_invoice_prefix'];
                $br_logo_old = $db_row['br_logo'];
                $br_type = $db_row['br_type'];
                $br_status = $db_row['br_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

    $not_value = " ";
    $arr_duplicate_name = found_duplicate('sm_branch', 'br_login', $br_login, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for branch login name. ';
    }

    if ($errormsg == '') {
        if ($_FILES['br_logo']['error'] == 0) 
        {  /// Image
    
            $file_array = explode(".", $_FILES['br_logo']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);
    
            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) 
            {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else 
            {
                $br_logo = "branch_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['br_logo']['name']);
                $DestPath = BRANCH_IMAGE . $br_logo;
                move_uploaded_file($_FILES['br_logo']['tmp_name'], $DestPath);
            }
        } 
        else 
        {
            $br_logo = 'no_branch_image.png';
        }
    }
    if ($errormsg == '') {
        $sm_branch = new branch();

        $sm_branch->data["br_name"] = $br_name;
        $sm_branch->data["br_pass"] = md5($br_pass);
        $sm_branch->data["br_logo"] = $br_logo;
        $sm_branch->data["br_contact_p_name1"] = $br_contact_p_name1;
        $sm_branch->data["br_login"] = $br_login;
        $sm_branch->data["br_contact_p_phone1"] = $br_contact_p_phone1;
        $sm_branch->data["br_contact_p_email1"] = $br_contact_p_email1;
        $sm_branch->data["br_contact_p_name2"] = $br_contact_p_name2;
        $sm_branch->data["br_contact_p_phone2"] = $br_contact_p_phone2;
        $sm_branch->data["br_contact_p_email2"] = $br_contact_p_email2;
        $sm_branch->data["br_add_1"] = $br_add_1;
        $sm_branch->data["br_add_2"] = $br_add_2;
        $sm_branch->data["br_city"] = $br_city;
        $sm_branch->data["br_district"] = $br_district;
        $sm_branch->data["br_state_id"] = $br_state_id;
        $sm_branch->data["br_country"] = $br_country;
        $sm_branch->data["br_postalcode"] = $br_postalcode;
        $sm_branch->data["br_invoice_prefix"] = $br_invoice_prefix;
        
        $sm_branch->data["br_type"] = $br_type;
        $sm_branch->data["br_status"] = $br_status;
        $sm_branch->data["br_create_date"] = $cur_date;
        $sm_branch->data["br_create_by_id"] = $tmp_admin_id;
        $sm_branch->data["br_update_date"] = $cur_date;
        $sm_branch->data["br_update_by_id"] = $tmp_admin_id;

        $sm_branch->action = 'insert';
        $result = $sm_branch->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            header('Location:manage_branch.php?msg=2&page=1&per_page=' . $per_page);
            exit(0);

            // end of needs to update login details.
        }
    }
}

// Update user entry
if ($act == 'update') {
    $not_value = " AND br_id != " . $id;
    $arr_duplicate_name = found_duplicate('sm_branch', 'br_login', $br_login, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for branch login. ';
    }

    if ($errormsg == '') {
        if ($_FILES['br_logo']['error'] == 0) 
        {  /// Image
    
            $file_array = explode(".", $_FILES['br_logo']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);
    
            if (!(in_array($file_ext, $arr_allow_file_type) == 1)) 
            {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type);
            } else 
            {
                $br_logo = "branch_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['br_logo']['name']);
                $DestPath = BRANCH_IMAGE . $br_logo;
                move_uploaded_file($_FILES['br_logo']['tmp_name'], $DestPath);
            }
        } 
        else 
        {
            $br_logo = $br_logo_old;
        }
    }

    if ($errormsg == '') {


        $sm_branch = new branch();
        $sm_branch->data["br_name"] = $br_name;
        if ($br_pass != '') {
            $sm_branch->data["br_pass"] = md5($br_pass);
        }


    //    $sm_branch->data["br_pass"] = $br_pass;
        $sm_branch->data["br_contact_p_name1"] = $br_contact_p_name1;
        $sm_branch->data["br_login"] = $br_login;
        $sm_branch->data["br_logo"] = $br_logo;
        $sm_branch->data["br_contact_p_phone1"] = $br_contact_p_phone1;
        $sm_branch->data["br_contact_p_email1"] = $br_contact_p_email1;
        $sm_branch->data["br_contact_p_name2"] = $br_contact_p_name2;
        $sm_branch->data["br_contact_p_phone2"] = $br_contact_p_phone2;
        $sm_branch->data["br_contact_p_email2"] = $br_contact_p_email2;
        $sm_branch->data["br_add_1"] = $br_add_1;
        $sm_branch->data["br_add_2"] = $br_add_2;
        $sm_branch->data["br_city"] = $br_city;
        $sm_branch->data["br_district"] = $br_district;
        $sm_branch->data["br_state_id"] = $br_state_id;
        $sm_branch->data["br_country"] = $br_country;
        $sm_branch->data["br_postalcode"] = $br_postalcode;
        $sm_branch->data["br_invoice_prefix"] = $br_invoice_prefix;
        
        $sm_branch->data["br_type"] = $br_type;
        $sm_branch->data["br_status"] = $br_status;
        $sm_branch->data["br_create_date"] = $cur_date;
        $sm_branch->data["br_create_by_id"] = $tmp_admin_id;
        $sm_branch->data["br_update_date"] = $cur_date;
        $sm_branch->data["br_update_by_id"] = $tmp_admin_id;
        $sm_branch->action = 'update';
        $sm_branch->process_id = $id;
        $result = $sm_branch->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            header('Location:manage_branch.php?msg=3&page=1&per_page=' . $per_page);
        }
    }
}


$bt_name_q = "SELECT bt_name, bt_id FROM sm_batch_time WHERE bt_status ='A' ORDER BY bt_id DESC";
$bt_name_r = m_process("get_data", $bt_name_q);

if ($bt_name_r["status"] == 'error') {
    $errormsg = $bt_name_r["errormsg"];
} 

                                                

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
                                    <input type="hidden" id="br_logo_old" name="br_logo_old" value="<?php echo $br_logo_old; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <!-- START -->
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Brach Name</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="br_name" id="br_name"  placeholder="Brach Name" value="<?php echo $br_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Login</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="br_login" id="br_login"  placeholder="Login" value="<?php echo $br_login; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Password</label>
                                                <div class="col-sm-9">
                                                    <input <?php if($id==0) echo "required"; ?> type="password" name="br_pass" id="br_pass"  placeholder="Password" value="<?php echo $br_pass; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Con. P. Name 1</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_contact_p_name1" id="br_contact_p_name1"  placeholder="Contact P. Name" value="<?php echo $br_contact_p_name1; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Con. P. Phone 1</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_contact_p_phone1" id="br_contact_p_phone1"  placeholder="Conact P. Phone" value="<?php echo $br_contact_p_phone1; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Con. P. Email 1</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_contact_p_email1" id="br_contact_p_email1"  placeholder="Conact P. Email" value="<?php echo $br_contact_p_email1; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Con. P. Name 2</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_contact_p_name2" id="br_contact_p_name2"  placeholder="Contact P. Name" value="<?php echo $br_contact_p_name2; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Con. P. Phone 2</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_contact_p_phone2" id="br_contact_p_phone2"  placeholder="Conact P. Phone" value="<?php echo $br_contact_p_phone2; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Con. P. Email 2</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_contact_p_email2" id="br_contact_p_email2"  placeholder="Conact P. Email" value="<?php echo $br_contact_p_email2; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Add. Line 1</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_add_1" id="br_add_1"  placeholder="Add. 1" value="<?php echo $br_add_1; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Add. Line 2</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="br_add_2" id="br_add_2"  placeholder="Add. 2" value="<?php echo $br_add_2; ?>" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">City</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="br_city" id="br_city"  placeholder="City" value="<?php echo $br_city; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">District</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="br_district" id="br_district"  placeholder="District" value="<?php echo $br_district; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">State</label>
                                                <div class="col-sm-9">
                                                    <select required id="br_state_id" name="br_state_id" class="form-control">
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'st_name ,st_id';
                                                        $data_arr_input['table'] = 'sm_state';
                                                        $data_arr_input['where'] = " st_status = 'A'  ";
                                                        $data_arr_input['key_id'] = 'st_id';
                                                        $data_arr_input['key_name'] = 'st_name';
                                                        $data_arr_input['current_selection_value'] = $br_state_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Postal Code</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="br_postalcode" id="br_postalcode"  placeholder="Postal Code" value="<?php echo $br_postalcode; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Invoice Prefix</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="br_invoice_prefix" id="br_invoice_prefix"  placeholder="Invoice Prefix" value="<?php echo $br_invoice_prefix; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Logo</label>
                                                <div class="col-sm-9">
                                                    <input type="file" id="br_logo" name="br_logo" />
                                                    <?php if ($br_logo_old != '') { ?>
                                                        <a href="<?php echo SITE_URL.BRANCH_IMAGE_URL. $br_logo_old; ?>" target="_blank" ><img src="<?php echo BRANCH_IMAGE.$br_logo_old; ?>" height="120px" width="120px" /></a>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <?php /* ?>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Batch Time</label>
                                                <div class="col-sm-9">
                                                <?php
                                                  
                                          
                                                  if ($bt_name_r['count'] > 0) {
                                                      $icount=0;
                                                    foreach ($bt_name_r['res'] as $bt_name_db_row) {
                                                        $checked = '';
                                                        if (in_array($bt_name_db_row["bt_name"],$br_batch_time_array)  )
                                                        {
                                                            $checked = ' checked="checked" ';
                                                        }
                                                        ?>
                                                        <input type="checkbox" <?php echo $checked;?> id="<?php echo $icount;?>" value="<?php echo $bt_name_db_row["bt_name"];?>" name="br_batch_time[]" /><label for="<?php echo $icount;?>"><?php echo $bt_name_db_row["bt_name"];?></label>
                                                        <?php
                                                        $icount++;
                                                    }
                                                }

                                                
                                                ?>
                                                    <input type="checkbox" id="br_batch_time" name="br_batch_time" >
                                                     
                                                    
                                                </div>
                                            </div>
                                             <?php   */ ?>    

                                            <!-- END -->
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                    <input type="radio" name="br_status" id="br_status_a" <?php
                                                    if ($br_status == 'A') {
                                                        echo 'checked="checked"';
                                                    };
                                                    ?>  value="A" /><label for="br_status_a">Active</label> <input type="radio" name="br_status" id="br_status_i" value="I" <?php if ($br_status == 'I') echo 'checked="checked"'; ?> /><label for="br_status_i">InActive</label>
                                                </div>


                                            </div>

                                            <!-- /.box-body -->

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
