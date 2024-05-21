<?php
include("includes/application_top.php");
include("../includes/class/products.php");
include("../includes/class/products_attributes.php");
// Set the caption of button
$id = get_rdata("id", 0);

$caption = "Add Products";
$btn_caption = "Add Products";
if ($id != 0) {
    $caption = "Edit Products";
    $btn_caption = "Edit Products";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);
$act = get_rdata("act");

$pro_cat_id = get_rdata('pro_cat_id');
$pro_name = get_rdata('pro_name');
$pro_desc = get_rdata('pro_desc');
$pro_model = get_rdata('pro_model');
$pro_warranty_month = get_rdata('pro_warranty_month');
$pro_qty = get_rdata('pro_qty');
$pro_price = get_rdata('pro_price');
$pro_sale_price = get_rdata('pro_sale_price');
$pro_manu_id = get_rdata('pro_manu_id');
$pro_warranty_terms = get_rdata('pro_warranty_terms');
$pro_extended_warranty = get_rdata('pro_extended_warranty');
$pro_condition = get_rdata('pro_condition');
$pro_features = get_rdata('pro_features');
$pro_warranty_provider = get_rdata('pro_warranty_provider');
$pro_custom_field1 = get_rdata('pro_custom_field1');
$pro_custom_field2 = get_rdata('pro_custom_field2');
$pro_custom_field3 = get_rdata('pro_custom_field3');
$pro_admin_id = get_rdata('pro_admin_id');
$pro_sort_order = get_rdata('pro_sort_order');
$pro_status = get_rdata('pro_status', 1);
$proatt_option_name = get_rdata('proatt_option_name');
$proatt_option_value = get_rdata('proatt_option_value');
$pro_admin_id = $tmp_admin_id;

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 2) {
    $successmsg = "Products has been added successfully";
}
// Get the data from database
if ($act == '' && $id != 0) {
    $cquery = "SELECT * FROM sm_products WHERE pro_admin_id = ".$tmp_admin_id." AND  pro_id = " . $id;
    $user = new products();
    $user->cquery = $cquery;
    $user->action = 'get';
    $result = $user->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $pro_cat_id = $db_row['pro_cat_id'];
                $pro_name = $db_row['pro_name'];
                $pro_desc = $db_row['pro_desc'];
                $pro_model = $db_row['pro_model'];
                $pro_warranty_month = $db_row['pro_warranty_month'];
                $pro_qty = $db_row['pro_qty'];
                $pro_price = $db_row['pro_price'];
                $pro_sale_price = $db_row['pro_sale_price'];
                $pro_manu_id = $db_row['pro_manu_id'];
                $pro_warranty_terms = $db_row['pro_warranty_terms'];
                $pro_extended_warranty = $db_row['pro_extended_warranty'];
                $pro_condition = $db_row['pro_condition'];
                $pro_features = $db_row['pro_features'];
                $pro_warranty_provider = $db_row['pro_warranty_provider'];
                $pro_custom_field1 = $db_row['pro_custom_field1'];
                $pro_custom_field2 = $db_row['pro_custom_field2'];
                $pro_custom_field3 = $db_row['pro_custom_field3'];
                $pro_admin_id = $db_row['pro_admin_id'];
                $pro_sort_order = $db_row['pro_sort_order'];
                $pro_status = $db_row['pro_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

        if ($errormsg == '') {
            $arr_check_delete = validate_before_delete("sm_products", "pro_admin_id = ".$tmp_admin_id." AND pro_name = '" . $pro_name . "'");
            if ($arr_check_delete["error_message"] != '') {
                $errormsg = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $errormsg = "Name is already exists";
            }
        }

        if ($errormsg == '') {
            $products = new products();
            $products->data["pro_cat_id"] = $pro_cat_id;
            $products->data["pro_name"] = $pro_name;
            $products->data["pro_desc"] = $pro_desc;
            $products->data["pro_model"] = $pro_model;
            $products->data["pro_warranty_month"] = $pro_warranty_month;
            $products->data["pro_qty"] = $pro_qty;
            $products->data["pro_price"] = $pro_price;
            $products->data["pro_sale_price"] = $pro_sale_price;
            $products->data["pro_manu_id"] = $pro_manu_id;
            $products->data["pro_warranty_terms"] = $pro_warranty_terms;
            $products->data["pro_extended_warranty"] = $pro_extended_warranty;
            $products->data["pro_condition"] = $pro_condition;
            $products->data["pro_features"] = $pro_features;
            $products->data["pro_warranty_provider"] = $pro_warranty_provider;
            $products->data["pro_custom_field1"] = $pro_custom_field1;
            $products->data["pro_custom_field2"] = $pro_custom_field2;
            $products->data["pro_custom_field3"] = $pro_custom_field3;
            $products->data["pro_admin_id"] = $pro_admin_id;
            $products->data["pro_sort_order"] = $pro_sort_order;
            $products->data["pro_status"] = $pro_status;
            $products->data["pro_date"] = $cur_date;

            $products->action = 'insert';
            $result = $products->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
              $proatt_pro_id = $result["id"];
             
                // code for adding product attribute
                       
                if (isset($proatt_option_name) && is_array($proatt_option_name)) {
              
                    foreach ($proatt_option_name as $key => $value) {
                    
                        if (trim($value) == '')
                            continue;

                        $products_attributes = new products_attributes();
                        $products_attributes->data["proatt_option_name"] = trim($value);
                        $products_attributes->data["proatt_option_value"] = trim($proatt_option_value[$key]);
                        $products_attributes->data["proatt_pro_id"] = $proatt_pro_id;
                        $products_attributes->data["proatt_date"] = $cur_date;
                        $products_attributes->data["proatt_admin_id"] = $pro_admin_id;
                        $products_attributes->action = 'insert';
                        $result = $products_attributes->process();
                   //     print_r($result);
                   //     exit(0);
                        if ($result['status'] == 'failure') {
                            $errormsg = $result['errormsg'];
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                
                     
                       
                // end of code for adding product attributes
                // Add log entry
                if (ENABLE_LOG) {
                    $remarks = str_replace('[CON_NAME]', $pro_name, CON_ADD);
                    $remarks = str_replace('[ADMIN_NAME]', session_get('admin_uname'), $remarks);
                    add_log('Add new products', 0, $result['id'], $remarks);
                }
                if ($errormsg == "") {
                    if (isset($_POST['btnSaveandAdd'])) {
                        header('Location:add_edit_products.php?msg=2');
                        exit(0);
                    } else {
                        // If success then redirect to manage user page
                        header('Location:manage_products.php?msg=2&page=1&per_page=' . $per_page);
                        exit(0);
                    }
                }
            }
        }
}

if ($act == 'update') {

        if ($errormsg == '') {
            $arr_check_delete = validate_before_delete("sm_products", "pro_admin_id = ".$tmp_admin_id." AND pro_name = '" . $pro_name . "' AND pro_id != '" . $id . "'");
            if ($arr_check_delete["error_message"] != '') {
                $errormsg = $arr_check_delete["error_message"];
            } else if ($arr_check_delete["found_reference"] == true) {
                $errormsg = "Name is already exists";
            }
        }

//        if ($errormsg == '') {
//            $arr_check_delete = validate_before_delete("products", "pro_model = '" . $pro_model . "' AND pro_id != '" . $id . "'");
//            if ($arr_check_delete["error_message"] != '') {
//                $errormsg = $arr_check_delete["error_message"];
//            } else if ($arr_check_delete["found_reference"] == true) {
//                $errormsg = "Model is already exists";
//            }
//        }

        if ($errormsg == '') {
            $products = new products();

            $products->data["pro_cat_id"] = $pro_cat_id;
            $products->data["pro_name"] = $pro_name;
            $products->data["pro_desc"] = $pro_desc;
            $products->data["pro_model"] = $pro_model;
            $products->data["pro_warranty_month"] = $pro_warranty_month;
            $products->data["pro_qty"] = $pro_qty;
            $products->data["pro_price"] = $pro_price;
            $products->data["pro_sale_price"] = $pro_sale_price;
            $products->data["pro_manu_id"] = $pro_manu_id;
            $products->data["pro_warranty_terms"] = $pro_warranty_terms;
            $products->data["pro_extended_warranty"] = $pro_extended_warranty;
            $products->data["pro_condition"] = $pro_condition;
            $products->data["pro_features"] = $pro_features;
            $products->data["pro_warranty_provider"] = $pro_warranty_provider;
            $products->data["pro_custom_field1"] = $pro_custom_field1;
            $products->data["pro_custom_field2"] = $pro_custom_field2;
            $products->data["pro_custom_field3"] = $pro_custom_field3;
            $products->data["pro_admin_id"] = $pro_admin_id;
            $products->data["pro_sort_order"] = $pro_sort_order;
            $products->data["pro_status"] = $pro_status;
            $products->data["pro_date"] = $cur_date;

            $products->action = 'update';
            $products->process_id = $id;
            $result = $products->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
                // Add log entry
                
                
                if (ENABLE_LOG) {
                    $remarks = str_replace('[CON_NAME]', $pro_name, CON_UPDATE);
                    $remarks = str_replace('[ADMIN_NAME]', session_get('admin_uname'), $remarks);
                    add_log('Update Products', 0, $id, $remarks);
                }
                
                // code for adding product attribute
                       
                    $q_product_att_delete = "DELETE FROM ".TBL_PRODUCTS_ATTRIBUTES." WHERE proatt_pro_id = $id";
                    $res_product_att_delete =  m_process("delete", $q_product_att_delete);
                    if ($res_product_att_delete["errormsg"]!= '')
                    {
                        $errrormsg = $res_product_att_delete["errormsg"];
                    }

                if (isset($proatt_option_name) && is_array($proatt_option_name)) {
              
                    foreach ($proatt_option_name as $key => $value) {
                    
                        if (trim($value) == '')
                            continue;

                        $products_attributes = new products_attributes();
                        $products_attributes->data["proatt_option_name"] = trim($value);
                        $products_attributes->data["proatt_option_value"] = trim($proatt_option_value[$key]);
                        $products_attributes->data["proatt_pro_id"] = $id;
                        $products_attributes->data["proatt_date"] = $cur_date;
                        $products_attributes->data["proatt_admin_id"] = $pro_admin_id;
                        $products_attributes->action = 'insert';
                        $result = $products_attributes->process();
                   //     print_r($result);
                   //     exit(0);
                        if ($result['status'] == 'failure') {
                            $errormsg = $result['errormsg'];
                            break;
                        } else {
                            continue;
                        }
                    }
                }
                
                if ($errormsg == '') 
                { 
                // If success then redirect to manage user page
                header('Location:manage_products.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
                }
            }
        }
}

// product option retrival code
$q_product_att_get = "SELECT * FROM " . TBL_PRODUCTS_ATTRIBUTES . " WHERE proatt_pro_id = $id";
$res_product_att_get = m_process("get_data", $q_product_att_get);
if ($res_product_att_get["errormsg"] != '') {
    $errormsg = $res_product_att_get["errormsg"];
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
                                <form name="frmAddEditProducts" id="frmAddEditProducts" method="post" class="form-horizontal form-employee" onsubmit="return validate_data();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Category<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="pro_cat_id" id="pro_cat_id" class="form-control" >
                                                            <?php
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'cat_name ,cat_id';
                                                            $data_arr_input['table'] = 'category';
                                                            $data_arr_input['key_id'] = 'cat_id';
                                                            $data_arr_input['key_name'] = 'cat_name';
                                                            $data_arr_input['orderby'] = 'cat_name ASC';
                                                            $data_arr_input['where'] = ' cat_admin_id = '.$tmp_admin_id.'  AND cat_status = 1 ';
                                                            $data_arr_input['current_selection_value'] = $pro_cat_id;
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Manufacturer<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="pro_manu_id" id="pro_manu_id" class="form-control" >

                                                            <?php
                                                            $data_arr_input = array();
                                                            $data_arr_input['select_field'] = 'manu_name ,	manu_id';
                                                            $data_arr_input['table'] = 'manufacturer';
                                                            $data_arr_input['key_id'] = 'manu_id';
                                                            $data_arr_input['key_name'] = 'manu_name';
                                                            $data_arr_input['orderby'] = 'manu_name ASC';
                                                            $data_arr_input['where'] = ' manu_admin_id = '.$tmp_admin_id.' AND manu_status = 1 ';
                                                            $data_arr_input['current_selection_value'] = $pro_manu_id;
                                                            display_dd_options($data_arr_input);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>



                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Name<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_name" id="pro_name"  placeholder="Name" value="<?php echo $pro_name; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Model<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_model" id="pro_model"  placeholder="Model" value="<?php echo $pro_model; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Price<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_price" id="pro_price"  placeholder="Price" value="<?php echo $pro_price; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Sale Price</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_sale_price" id="pro_sale_price"  placeholder="Sale Price" value="<?php echo $pro_sale_price; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Warranty Month<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="pro_warranty_month" id="pro_warranty_month"   class="form-control" >
                                                            <?php
                                                            display_dd_options_from_array($arr_warrnty_period, $pro_warranty_month);
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Warranty Terms<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="pro_warranty_terms" id="pro_warranty_terms"  class="form-control" >
                                                            <?php
                                                            display_dd_options_from_array($arr_warrant_terms, $pro_warranty_terms);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Extended Warranty<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="pro_extended_warranty" id="pro_extended_warranty"  class="form-control" >
                                                            <?php
                                                            display_dd_options_from_array($arr_warrnty_period, $pro_extended_warranty);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Condition<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <select name="pro_condition" id="pro_condition"  class="form-control" >
                                                            <?php
                                                            display_dd_options_from_array($arr_product_type, $pro_condition);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>



                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Quantity<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_qty" id="pro_qty" readonly="readonly"  placeholder="Qty" value="<?php echo $pro_qty; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Sort Order<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_sort_order" id="pro_sort_order"  placeholder="Sort_order" value="<?php echo $pro_sort_order; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="row"><div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Custom Field 1<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_custom_field1" id="pro_custom_field1"  placeholder="Custom Field 1" value="<?php echo $pro_custom_field1; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Custom Field 2<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_custom_field2" id="pro_custom_field2"  placeholder="Custom Field 2" value="<?php echo $pro_custom_field2; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row"><div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Custom Field 3<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="pro_custom_field3" id="pro_custom_field3"  placeholder="Custom Field 3" value="<?php echo $pro_custom_field3; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div></div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Description<span class="req">*</span></label>
                                                    <div class="col-sm-10">
                                                        <textarea name="pro_desc" id="pro_desc"  rows="10" cols="80"><?php echo $pro_desc; ?></textarea>      

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Features<span class="req">*</span></label>
                                                    <div class="col-sm-10">

                                                        <textarea name="pro_features" id="pro_features"  rows="10" cols="80"><?php echo $pro_features; ?></textarea>                                                                                                      


                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Status<span class="req">*</span></label>
                                                    <div class="col-sm-9">
                                                        <div class="radio-inline pl0" >
                                                            <input type="radio" class="minimal" name="pro_status" id="pro_status1" value="1" <?php if (set_checked($pro_status, '1')) { ?> checked="checked" <?php } ?>  />
                                                            <label for="pro_status1">Active</label>
                                                        </div>
                                                        <div class="radio-inline pl0">
                                                            <input type="radio" class="minimal" name="pro_status" id="pro_status0" value="0" <?php if (set_checked($pro_status, '0')) { ?> checked="checked" <?php } ?>  />
                                                            <label for="pro_status0">Inactive</label>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">

                                                <label class="control-label">Product Option</label> 

                                            </div>

                                        </div>

                                        <div class="row product-options mb5" id="product_option_0" style="display:none;">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="col-md-3"><input type="text" name="proatt_option_name[]" id="proatt_option_name"  placeholder="Product Option" value="" class="form-control" /></div>
                                                <div class="col-md-5"><input type="text" name="proatt_option_value[]" id="proatt_option_value"  placeholder="Product Option Values" value="" class="form-control" /></div>
                                                <div class="col-md-2"><input type="button" value="Remove" class="btn btn-danger" onclick="product_remove_option(this)" />  </div>
                                            </div>
                                        </div>
                                        <?php if ($id == 0 || ($id != 0 && $errormsg == "" && $res_product_att_get["count"] == 0)) { ?>
                                            <div class="row product-options mb5" id="product_option_1">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-10">
                                                    <div class="col-md-3"><input type="text" name="proatt_option_name[]" id="proatt_option_name"  placeholder="Product Option" value="" class="form-control" /></div>
                                                    <div class="col-md-5"><input type="text" name="proatt_option_value[]" id="proatt_option_value"  placeholder="Product Option Values" value="" class="form-control" /></div>
                                                    <div class="col-md-2"><input type="button" value="Remove" class="btn btn-danger" onclick="product_remove_option(this)" />  </div>
                                                </div>
                                            </div>
                                            <?php
                                        } else if ($id != 0 && $errormsg == "" && $res_product_att_get["count"] > 0) {
                                            foreach ($res_product_att_get['res'] as $db_row) {
                                                ?>                                        
                                                <div class="row product-options mb5" id="product_option_1">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-10">
                                                        <div class="col-md-3"><input type="text" name="proatt_option_name[]" id="proatt_option_name"  placeholder="Product Option" value="<?php echo $db_row["proatt_option_name"]; ?>" class="form-control" /></div>
                                                        <div class="col-md-5"><input type="text" name="proatt_option_value[]" id="proatt_option_value"  placeholder="Product Option Values" value="<?php echo $db_row["proatt_option_value"]; ?>" class="form-control" /></div>
                                                        <div class="col-md-2"><input type="button" value="Remove" class="btn btn-danger" onclick="product_remove_option(this)" />  </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        }
                                        ?>                                        
                                        <div class="row mt5" style="text-align: center;"><div class="col-md-12"><input type="button" value=" Add More" class="btn btn-success" onclick="product_option_append()" />  </div></div>

                                    </div><!-- /.box-body -->
                                    <div class="box-footer"> 
                                        <?php if ($id == 0) { ?>
                                            <input type="button" value="Reset" class="btn btn-default clrbtn leftbtn" id="btnClearProducts" name="btnClearProducts"/>                             
                                        <?php } ?>
                                        <input type="button" value="Cancel" class="btn btn-default leftbtn" id="btnResetProducts" name="btnResetProducts" onclick="window.location.href = 'manage_products.php'"/>                                            
                                        <button type="submit" class="ml5 btn btn-info pull-right leftbtn marginl0" id="btnAddProducts" name="btnAddProducts"><?php echo $caption; ?></button>
                                        <?php if ($id == 0) { ?>
                                            <button type="submit" class="btn btn-info pull-right leftbtn" id="btnSaveandAdd" name="btnSaveandAdd">Save and Add Another Products</button>
                                        <?php } ?>
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