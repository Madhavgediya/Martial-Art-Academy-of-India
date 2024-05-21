<?php
include("includes/application_top.php");
include("../includes/class/account.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");

$ac_name = get_rdata('ac_name');
$ac_br_id = get_rdata('ac_br_id',$tmp_admin_id);
$ac_status = get_rdata('ac_status','A');
$ac_type = get_rdata('ac_type','Cash');
$ac_create_date = $cur_date;
$ac_create_by_id = $tmp_admin_id ;
$ac_update_date =$cur_date;
$ac_update_by_id = $tmp_admin_id;

$caption = "Add Account";
$btn_caption = "Add Account";
if ($id != 0) {
    $caption = "Edit Account";
    $btn_caption = "Edit Account";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $account_master = new account();
    $account_master->data["*"] = "";
    $account_master->action = 'get';
    $account_master->process_id = $id;
    $result = $account_master ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $ac_name = $db_row['ac_name'];
              $ac_br_id = $db_row['ac_br_id'];
              $ac_status = $db_row['ac_status'];
              $ac_type = $db_row['ac_type'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

   // $not_value = " AND ac_br_id = ".$tmp_admin_id ;
   $not_value = "";
    $arr_duplicate_name = found_duplicate('sm_account', 'ac_name',escape($ac_name),$not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for Account name ';
    }


    if ($errormsg == '') {
        $account_master  = new account();
        $account_master->data["ac_name"] =  escape($ac_name);
        $account_master->data["ac_br_id"] = $ac_br_id;
        $account_master->data["ac_status"] = $ac_status;
        $account_master->data["ac_type"] = $ac_type;
        $account_master->data["ac_create_date"] = $ac_create_date;
        $account_master->data["ac_create_by_id"] = $ac_create_by_id;
        $account_master->data["ac_update_date"] = $ac_update_date;
        $account_master->data["ac_update_by_id"] = $ac_update_by_id;

        $account_master ->action = 'insert';
        $result = $account_master->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_account.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND ac_id != ".$id;

  $arr_duplicate_name = found_duplicate('sm_account', 'ac_name', escape($ac_name),$not_value);
  if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
  } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Account name ';
  }

  if ($errormsg == '') {
        $account_master  = new account();
        $account_master->data["ac_name"] = escape($ac_name);
  $account_master->data["ac_br_id"] = $ac_br_id;
  $account_master->data["ac_status"] = $ac_status;
  $account_master->data["ac_type"] = $ac_type;
  $account_master->data["ac_create_date"] = $ac_create_date;
  $account_master->data["ac_create_by_id"] = $ac_create_by_id;
  $account_master->data["ac_update_date"] = $ac_update_date;
  $account_master->data["ac_update_by_id"] = $ac_update_by_id;

        $account_master ->action = 'update';
        $account_master ->process_id = $id;
        $result = $account_master ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

          header('Location:manage_account.php?msg=3&page=1&per_page=' . $per_page);
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
                                    <input type="hidden" id="ac_br_id" name="ac_br_id" value="<?php echo $ac_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="ac_name" id="ac_name"  placeholder="Name" value="<?php echo $ac_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label class="col-sm-3 control-label">Type</label>
            <div class="col-sm-9">
            <select name="ac_type" class="form-control" >
            <option <?php if ($ac_type == 'Cash') echo 'selected="selected"'; ?> value="Cash">Cash</option>
            <option <?php if ($ac_type == 'Bank') echo 'selected="selected"'; ?> value="Bank">Bank</option>
            <option <?php if ($ac_type == 'Other') echo 'selected="selected"'; ?> value="Other">Other</option>
            </select>
            </div>
                                        </div> -->
                                                                                <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="ac_status" id="ac_status_a" <?php
                                                if ($ac_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="ac_status_a">Active</label> <input type="radio" name="ac_status" id="ac_status_i" value="I" <?php if ($ac_status == 'I') echo 'checked="checked"'; ?> /><label for="ac_status_i">InActive</label>
                                            </div>
                                        </div>
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
