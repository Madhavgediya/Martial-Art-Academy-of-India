<?php
include("includes/application_top.php");
include("../includes/class/income_expance_type.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");

$iet_name = get_rdata('iet_name');
$iet_br_id = get_rdata('iet_br_id',$tmp_admin_id);
$iet_status = get_rdata('iet_status','A');
$iet_type = get_rdata('iet_type','Both');
$iet_create_date = $cur_date;
$iet_create_by_id = $tmp_admin_id ;
$iet_update_date =$cur_date;
$iet_update_by_id = $tmp_admin_id;

$caption = "Add Income Expance Type";
$btn_caption = "Add Income Expance Type";
if ($id != 0) {
    $caption = "Edit Income Expance Type";
    $btn_caption = "Edit Income Expance Type";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $income_expance_typemaster = new income_expance_type();
    $income_expance_typemaster->data["*"] = "";
    $income_expance_typemaster->action = 'get';
    // $income_expance_typemaster->process_id = $id;
    $income_expance_typemaster->where = "iet_id = $id AND iet_br_id = ".$tmp_admin_id;
    $result = $income_expance_typemaster ->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
              $iet_name = $db_row['iet_name'];
              $iet_br_id = $db_row['iet_br_id'];
              $iet_status = $db_row['iet_status'];
              $iet_type = $db_row['iet_type'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {

   $not_value = " AND iet_br_id = ".$tmp_admin_id;
    $arr_duplicate_name = found_duplicate('sm_income_expance_type', 'iet_name',escape($iet_name),$not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for Income Expance Type name ';
    }


    if ($errormsg == '') {
        $income_expance_typemaster  = new income_expance_type();
        $income_expance_typemaster->data["iet_name"] =  escape($iet_name);
        $income_expance_typemaster->data["iet_br_id"] = $iet_br_id;
        $income_expance_typemaster->data["iet_status"] = $iet_status;
        $income_expance_typemaster->data["iet_type"] = $iet_type;
        $income_expance_typemaster->data["iet_create_date"] = $iet_create_date;
        $income_expance_typemaster->data["iet_create_by_id"] = $iet_create_by_id;
        $income_expance_typemaster->data["iet_update_date"] = $iet_update_date;
        $income_expance_typemaster->data["iet_update_by_id"] = $iet_update_by_id;

        $income_expance_typemaster ->action = 'insert';
        $result = $income_expance_typemaster->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_income_expance_type.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND iet_br_id = ".$tmp_admin_id. " AND iet_id != ".$id;

  $arr_duplicate_name = found_duplicate('sm_income_expance_type', 'iet_name', escape($iet_name),$not_value);
  if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
  } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Income Expance Type name ';
  }

  if ($errormsg == '') {
        $income_expance_typemaster  = new income_expance_type();
        $income_expance_typemaster->data["iet_name"] = escape($iet_name);
        $income_expance_typemaster->data["iet_br_id"] = $iet_br_id;
        $income_expance_typemaster->data["iet_status"] = $iet_status;
        $income_expance_typemaster->data["iet_type"] = $iet_type;
        
        $income_expance_typemaster->data["iet_create_date"] = $iet_create_date;
        $income_expance_typemaster->data["iet_create_by_id"] = $iet_create_by_id;
        $income_expance_typemaster->data["iet_update_date"] = $iet_update_date;
        $income_expance_typemaster->data["iet_update_by_id"] = $iet_update_by_id;

        $income_expance_typemaster ->action = 'update';
        $income_expance_typemaster ->process_id = $id;
        $result = $income_expance_typemaster ->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

          header('Location:manage_income_expance_type.php?msg=3&page=1&per_page=' . $per_page);
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
                                    <input type="hidden" id="iet_br_id" name="iet_br_id" value="<?php echo $iet_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="iet_name" id="iet_name"  placeholder="Name" value="<?php echo $iet_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="iet_status" id="iet_status_a" <?php
                                                if ($iet_status == 'A') {
                                                    echo 'checked="checked"';
                                                };
                                                ?>  value="A" /><label for="iet_status_a">Active</label> <input type="radio" name="iet_status" id="iet_status_i" value="I" <?php if ($iet_status == 'I') echo 'checked="checked"'; ?> /><label for="iet_status_i">InActive</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Type</label>
                                            <div class="col-sm-9">
                                                <select id="iet_type" name="iet_type" class="form-control" >
                                                <option <?php if($iet_type=='Both') { echo 'selected="selected"'; } ?>  value="Both">Both</option>
                                                  <option <?php if($iet_type=='Income') { echo 'selected="selected"'; } ?> value="Income">Income</option>
</option>                                                <option <?php if($iet_type=='Expance') { echo 'selected="selected"'; } ?> value="Expance">Expance</option>
                                                </select>
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
