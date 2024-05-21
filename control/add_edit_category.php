<?php
include("includes/application_top.php");
include("../includes/class/category.php");

// Set the caption of button
$id = get_rdata("id", 0);

$caption = "Add Category";
$btn_caption = "Add Category";
if ($id != 0) {
    $caption = "Edit Category";
    $btn_caption = "Edit Category";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');
$admin_id = session_get("admin_id");
// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$act = get_rdata("act");
$cat_name = get_rdata('cat_name', '');
$cat_parent_id = get_rdata('cat_parent_id', '');
$cat_status = get_rdata('cat_status', 1);

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 2) {
    $successmsg = "Category has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT cat_id,cat_name,cat_parent_id,cat_status FROM sm_category WHERE cat_admin_id =  ".$tmp_admin_id." AND  cat_id = " . $id;
    $user = new category();
    $user->cquery = $cquery;
    $user->action = 'get';
    $result = $user->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $cat_name = $db_row['cat_name'];
                $cat_parent_id = $db_row['cat_parent_id'];
                $cat_status = $db_row['cat_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
        if ($errormsg == '') {
            $arr_check_delete = validate_before_delete("sm_category", "cat_admin_id =  ".$tmp_admin_id." AND cat_name = '" . $cat_name . "'");
            if ($arr_check_delete["error_message"] != '') {
                $errormsg = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $errormsg = "Category name is already exists";
            }
        }
        
               
        if ($errormsg == '') {
            $category = new category();
            $category->data["cat_name"] = escape($cat_name);
            $category->data["cat_parent_id"] = $cat_parent_id;
            $category->data["cat_date"] = date('Y-m-d H:i:s');
            $category->data["cat_admin_id"] = $tmp_admin_id;
            $category->data["cat_status"] = $cat_status;
            $category->action = 'insert';
            $result = $category->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
                // Add log entry
               
                if (isset($_POST['btnSaveandAdd'])) {
                    header('Location:add_edit_category.php?msg=2');
                    exit(0);
                } else {
                    // If success then redirect to manage user page
                    header('Location:manage_category.php?msg=2&page=1&per_page=' . $per_page);
                    exit(0);
                }
            }
        }
}

if ($act == 'update') {
    $validate['required'] = array('cat_name' => 'Category Name is required');

    

        if ($errormsg == '') {
            $arr_check_delete = validate_before_delete("sm_category", " cat_admin_id != ".$tmp_admin_id." AND cat_name = '" . $cat_name . "' AND cat_id != '" . $id . "'");
            if ($arr_check_delete["error_message"] != '') {
                $errormsg = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $errormsg = "Category name is already exists";
            }
        }
        if ($errormsg == '') {
            $category = new category();
            $category->data["cat_name"] = escape($cat_name);
            $category->data["cat_parent_id"] = $cat_parent_id;
            $category->data["cat_status"] = $cat_status;
            $category->data["cat_date"] = date('Y-m-d H:i:s');
            $category->data["cat_admin_id"] = $tmp_admin_id;
            $category->action = 'update';
            $category->process_id = $id;
            $result = $category->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
                // If success then redirect to manage user page
                header('Location:manage_category.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
   
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
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content-header">
                    <h1>
                        <?php echo $caption; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $caption; ?></li>
                    </ol>
                </section>
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
                                <form name="frmAddEditCategory" id="frmAddEditCategory" method="post" class="form-horizontal form-employee" onsubmit="return validate_data();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Name<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="cat_name" id="cat_name"  placeholder="Category Name" value="<?php echo $cat_name; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Category Parent</label>
                                                    <div class="col-sm-9">
                                                        
                                                        <select required id="cat_parent_id" name="cat_parent_id" class="form-control">
                                                            <option value="0">--No Parent--</option>
                                                        <?php 
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'cat_name ,cat_id';
                                                        $data_arr_input['table']  = 'sm_category';
                                                        $data_arr_input['key_id'] = 'cat_id';
                                                        $data_arr_input['key_name'] = 'cat_name';
                                                        $data_arr_input['orderby'] = 'cat_name ASC';
                                                        $data_arr_input['current_selection_value'] = $cat_parent_id;
                                                        display_dd_options($data_arr_input) ;
    ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                        
                                            
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Status<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <div class="radio-inline pl0" >
                                                            <input type="radio" class="minimal" name="cat_status" id="cat_status1" value="1" <?php if (set_checked($cat_status, '1')) { ?> checked="checked" <?php } ?>  />
                                                            <label for="cat_status1">Active</label>
                                                        </div>
                                                        <div class="radio-inline pl0">
                                                            <input type="radio" class="minimal" name="cat_status" id="cat_status0" value="0" <?php if (set_checked($cat_status, '0')) { ?> checked="checked" <?php } ?>  />
                                                            <label for="cat_status0">Inactive</label>
                                                        </div>
                                                    </div>  
                                                </div>
                                            
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer"> 
<?php if ($id == 0) { ?>
                                            <input type="button" value="Reset" class="btn btn-default clrbtn leftbtn" id="btnClearCategory" name="btnClearCategory"/>                             
<?php } ?>
                                        <button type="submit" class="ml5 btn btn-info pull-right leftbtn marginl0" id="btnAddCategory" name="btnAddCategory"><?php echo $caption; ?></button>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div><!-- /.box -->
                            <!-- general form elements disabled -->
                        </div>
                    </div>
                </section>
            </div>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>