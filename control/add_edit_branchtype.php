<?php
include("includes/application_top.php");
include("../includes/class/branchtype.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");

$brt_name = get_rdata('brt_name');
$brt_amount = get_rdata('brt_amount');
$brt_br_id = get_rdata('brt_br_id',$tmp_admin_id);
$brt_status = get_rdata('brt_status');
$brt_create_date = $cur_date;
$brt_create_by_id = $tmp_admin_id ;
$brt_update_date =$cur_date;
$brt_update_by_id = $tmp_admin_id;

$caption = "Add Branch Type";
$btn_caption = "Add Branch Type";
if ($id != 0) {
    $caption = "Edit Branch Type";
    $btn_caption = "Edit Branch Type";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $branchtype_master = new branchtype();
    $branchtype_master->data["*"] = "";
    $branchtype_master->action = 'get';
    $branchtype_master->process_id = $id;
    $result = $branchtype_master ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $brt_name = $db_row['brt_name'];
$brt_amount = $db_row['brt_amount'];
$brt_br_id = $db_row['brt_br_id'];
$brt_status = $db_row['brt_status'];


            }
        }
    }
}

// Add user entry
if ($act == 'add') {

    $not_value = " AND brt_br_id = ".$tmp_admin_id ;
    $arr_duplicate_name = found_duplicate('sm_branch_type', 'brt_name', $brt_name,$not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for branch type name ';
    }


    if ($errormsg == '') {
        $branchtype_master  = new branchtype();
        $branchtype_master->data["brt_name"] = $brt_name;
        $branchtype_master->data["brt_amount"] = $brt_amount;
        $branchtype_master->data["brt_br_id"] = $brt_br_id;
        $branchtype_master->data["brt_status"] = $brt_status;
        $branchtype_master->data["brt_create_date"] = $brt_create_date;
        $branchtype_master->data["brt_create_by_id"] = $brt_create_by_id;
        $branchtype_master->data["brt_update_date"] = $brt_update_date;
        $branchtype_master->data["brt_update_by_id"] = $brt_update_by_id;

        $branchtype_master ->action = 'insert';
        $result = $branchtype_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_branchtype.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND brt_br_id = ".$tmp_admin_id." AND brt_id != ".$id;

  $arr_duplicate_name = found_duplicate('sm_branch_type', 'brt_name', $brt_name,$not_value);
  if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
  } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for branch type name ';
  }

  if ($errormsg == '') {
        $branchtype_master  = new branchtype();
        $branchtype_master->data["brt_name"] = $brt_name;
  $branchtype_master->data["brt_amount"] = $brt_amount;
  $branchtype_master->data["brt_br_id"] = $brt_br_id;
  $branchtype_master->data["brt_status"] = $brt_status;
  $branchtype_master->data["brt_create_date"] = $brt_create_date;
  $branchtype_master->data["brt_create_by_id"] = $brt_create_by_id;
  $branchtype_master->data["brt_update_date"] = $brt_update_date;
  $branchtype_master->data["brt_update_by_id"] = $brt_update_by_id;

        $branchtype_master ->action = 'update';
        $branchtype_master ->process_id = $id;
        $result = $branchtype_master ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

          header('Location:manage_branchtype.php?msg=3&page=1&per_page=' . $per_page);
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
                                    <input type="hidden" id="brt_br_id" name="brt_br_id" value="<?php echo $brt_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="brt_name" id="brt_name"  placeholder="Name" value="<?php echo $brt_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Amount</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="brt_amount" id="brt_amount"  placeholder="Amount" value="<?php echo $brt_amount; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="brt_status" id="brt_status_a" <?php
                                                if ($brt_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="brt_status_a">Active</label> <input type="radio" name="brt_status" id="brt_status_i" value="I" <?php if ($brt_status == 'I') echo 'checked="checked"'; ?> /><label for="brt_status_i">InActive</label>
                                            </div>
                                        </div>




<!--                                <div class="form-group">
                                     <label class="col-sm-3 control-label">More Images</label>
                                     <div class="col-sm-9">
                                         <input type="file" name="circular_details_pic_0" />
                                         <input type="button" value="Add More" class="btn btn-success" onclick="add_files();" id="add_more" name="add_more" />
                                           </div>

                                     </div>-->
                                        <!-- /.box-body -->

                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?>           <input type="reset" value="Rest" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
