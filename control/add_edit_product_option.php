<?php
include("includes/application_top.php");
include("../includes/class/product_option.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");

$po_name = get_rdata('po_name');
$po_br_id = get_rdata('po_br_id',$tmp_admin_id);
$po_status = get_rdata('po_status','A');
$po_type = get_rdata('po_type','Color');
$po_used_type = get_rdata('po_used_type',0);
$po_create_date = $cur_date;
$po_create_by_id = $tmp_admin_id ;
$po_update_date =$cur_date;
$po_update_by_id = $tmp_admin_id;

$caption = "Add Product Option";
$btn_caption = "Add Product Option";
if ($id != 0) {
    $caption = "Edit Product Option";
    $btn_caption = "Edit Product Option";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);

// Get the data from database
if ($act == '' && $id != 0) {
    $product_option_master = new product_option();
    $product_option_master->data["*"] = "";
    $product_option_master->action = 'get';
    $product_option_master->process_id = $id;
    $result = $product_option_master ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $po_name = $db_row['po_name'];
              $po_br_id = $db_row['po_br_id'];
              $po_status = $db_row['po_status'];
              $po_type = $db_row['po_type'];
              $po_used_type = $db_row['po_used_type'];
            }
        }
    }
}
// Add user entry
if ($act == 'add') {

    $not_value = " AND po_br_id = ".$tmp_admin_id ;
    $arr_duplicate_name = found_duplicate('sm_product_option', 'po_name', $po_name,$not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for Product Option name ';
    }


    if ($errormsg == '') {
        $product_option_master  = new product_option();
        $product_option_master->data["po_name"] = $po_name;
        $product_option_master->data["po_br_id"] = $po_br_id;
        $product_option_master->data["po_status"] = $po_status;
        $product_option_master->data["po_type"] = $po_type;
        $product_option_master->data["po_used_type"] = $po_used_type;
        $product_option_master->data["po_create_date"] = $po_create_date;
        $product_option_master->data["po_create_by_id"] = $po_create_by_id;
        $product_option_master->data["po_update_date"] = $po_update_date;
        $product_option_master->data["po_update_by_id"] = $po_update_by_id;

        $product_option_master ->action = 'insert';
        $result = $product_option_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_product_option.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND po_br_id = ".$tmp_admin_id." AND po_id != ".$id;

  $arr_duplicate_name = found_duplicate('sm_product_option', 'po_name', $po_name,$not_value);
  if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
  } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Product Option name ';
  }

  if ($errormsg == '') {
  $product_option_master  = new product_option();
  $product_option_master->data["po_name"] = $po_name;
  $product_option_master->data["po_br_id"] = $po_br_id;
  $product_option_master->data["po_status"] = $po_status;
  $product_option_master->data["po_type"] = $po_type;
  $product_option_master->data["po_used_type"] = $po_used_type;
  $product_option_master->data["po_create_date"] = $po_create_date;
  $product_option_master->data["po_create_by_id"] = $po_create_by_id;
  $product_option_master->data["po_update_date"] = $po_update_date;
  $product_option_master->data["po_update_by_id"] = $po_update_by_id;

        $product_option_master ->action = 'update';
        $product_option_master ->process_id = $id;
        $result = $product_option_master ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

          header('Location:manage_product_option.php?msg=3&page=1&per_page=' . $per_page);
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_add_edit_circular();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="po_br_id" name="po_br_id" value="<?php echo $po_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="po_name" id="po_name"  placeholder="Name" value="<?php echo $po_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                                                                <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="po_status" id="po_status_a" <?php
                                                if ($po_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="po_status_a">Active</label> <input type="radio" name="po_status" id="po_status_i" value="I" <?php if ($po_status == 'I') echo 'checked="checked"'; ?> /><label for="po_status_i">InActive</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Type</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="po_type" id="po_type_a" <?php
                                                if ($po_type == 'Color') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="Color" /><label for="po_type_a">Color</label> <input type="radio" name="po_type" id="po_type_i" value="Size" <?php if ($po_type == 'Size') echo 'checked="checked"'; ?> /><label for="po_type_i">Size</label>
                                                <input type="radio" name="po_type" id="po_type_b" <?php
                                                if ($po_type == 'Both') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="Both" /><label for="po_type_b">Both</label>
                                            </div>
                                        </div>
                                       <!--  <div class="form-group">
                                            <label class="col-sm-3 control-label">Used Type</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="po_used_type" id="po_used_type_a" <?php
                                                if ($po_used_type == 1) {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="1" /><label for="po_used_type_a">Certificate</label> 
                                                <input type="radio" name="po_used_type" id="po_used_type_b"  <?php 
                                                if ($po_used_type == 2) echo 'checked="checked"'; 
                                                ?> value="2" /><label for="po_used_type_b">Belt</label>
                                                <input type="radio" name="po_used_type" id="po_used_type_c" <?php
                                                if ($po_used_type == 3) {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="3" /><label for="po_used_type_c">Both</label>
                                            </div>
                                        </div> -->
                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?>           <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
                    <script type="text/javascript" language="javascript">

                        </script>
    </body>
</html>
