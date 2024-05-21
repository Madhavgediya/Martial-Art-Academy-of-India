<?php
include("includes/application_top.php");
include("../includes/class/belt.php");

// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$be_name = get_rdata('be_name');
$be_name_for = get_rdata('be_name_for');
$be_image = get_rdata('be_image');
$be_sort_order = get_rdata('be_sort_order', 0);
$be_status = get_rdata('be_status','A');
$be_belt_duration = get_rdata('be_belt_duration',0);
$be_belt_fee = get_rdata('be_belt_fee', 0);
$be_belt_exam_fee = get_rdata('be_belt_exam_fee', 0);
//$be_belt_onemonth_fee = get_rdata('be_belt_onemonth_fee', 0);
$eca_exc_id = get_rdata('eca_exc_id');
$be_br_id = get_rdata('be_br_id', $tmp_admin_id);
$be_create_date = $cur_date;
$be_create_by_id = $tmp_admin_id;
$be_update_date = $cur_date;
$be_update_by_id = $tmp_admin_id;


$caption = "Add Belt";
$btn_caption = "Add Belt";
if ($id != 0) {
    $caption = "Edit Belt";
    $btn_caption = "Edit Belt";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $belt = new belt();
    $belt->data["*"] = "";
    $belt->action = 'get';
    $belt->process_id = $id;
    $result = $belt->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $be_name = $db_row['be_name'];
                $be_name_for = $db_row['be_name_for'];
                $be_image = $db_row['be_image'];
                $be_sort_order = $db_row['be_sort_order'];
                $be_status = $db_row['be_status'];
                $be_belt_duration = $db_row['be_belt_duration'];
                $be_belt_fee = $db_row['be_belt_fee'];
                $be_belt_exam_fee = $db_row['be_belt_exam_fee'];
             //   $be_belt_onemonth_fee = $db_row['be_belt_onemonth_fee'];
                $be_br_id = $db_row['be_br_id'];
            }
        }
    }
}


// Add user entry
if ($act == 'add') {
    $not_value = "";
    $arr_duplicate_name = found_duplicate('sm_belt', 'be_name', $be_name, $not_value);
    if ($arr_duplicate_name['error_message'] != '') {
        $errormsg = $arr_duplicate_name['error_message'];
    } else if ($arr_duplicate_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for belt name. ';
    }


    if ($errormsg == '') {
        $belt = new belt();

        $belt->data["be_name"] = $be_name;
        $belt->data["be_name_for"] = $be_name_for;
        $belt->data["be_image"] = $be_image;
        $belt->data["be_sort_order"] = $be_sort_order;
        $belt->data["be_status"] = $be_status;
        $belt->data["be_belt_duration"] = $be_belt_duration;
        $belt->data["be_belt_fee"] = $be_belt_fee;
        $belt->data["be_belt_exam_fee"] = $be_belt_exam_fee;
      //  $belt->data["be_belt_onemonth_fee"] = $be_belt_onemonth_fee;
        $belt->data["be_br_id"] = $be_br_id;
        $belt->data["be_create_date"] = $be_create_date;
        $belt->data["be_create_by_id"] = $be_create_by_id;
        $belt->data["be_update_date"] = $be_update_date;
        $belt->data["be_update_by_id"] = $be_update_by_id;

        $belt->action = 'insert';
        $result = $belt->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

// START
            $be_id = $result['id'];
            for ($i = 0; $i < count($eca_exc_id); $i++) {
                $data = $datao = array();
                $datao["eca_create_date"] = $be_create_date;
                $datao["eca_create_by_id"] = $be_update_by_id;
                $datao["eca_update_date"] = $be_create_date;
                $datao["eca_update_by_id"] = $be_update_by_id;

                $data["eca_exc_id"] = $eca_exc_id[$i];
                $data["eca_total_marks"] = get_rdata("eca_total_marks_" . $eca_exc_id[$i]);
                $data["eca_obtain_marks"] = 0;

                $res_allocation_deallocation = allocation_deallocation_exam_categories('add', $be_id, $be_br_id, $data, $datao);
                if ($res_allocation_deallocation["errormsg"] != '') {
                    $errormsg = $res_allocation_deallocation["errormsg"];
                    break;
                }
            }
// END
            if ($errormsg == "") {
                header('Location:manage_belt.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
            // end of needs to update login details.
        }
    }
}

// Update user entry
    if ($act == 'update')
    {
        $not_value = " AND be_id != " . $id;
        $arr_duplicate_name = found_duplicate('sm_belt', 'be_name', $be_name, $not_value);
        if ($arr_duplicate_name['error_message'] != '') {
            $errormsg = $arr_duplicate_name['error_message'];
        } else if ($arr_duplicate_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for belt names. ';
        }

        if ($errormsg == '') {
            $belt = new belt();
            $belt->data["be_name"] = $be_name;
            $belt->data["be_name_for"] = $be_name_for;
            $belt->data["be_image"] = $be_image;
            $belt->data["be_sort_order"] = $be_sort_order;
            $belt->data["be_status"] = $be_status;
            $belt->data["be_belt_duration"] = $be_belt_duration;
            $belt->data["be_belt_fee"] = $be_belt_fee;
            $belt->data["be_belt_exam_fee"] = $be_belt_exam_fee;
           // $belt->data["be_belt_onemonth_fee"] = $be_belt_onemonth_fee;
            $belt->data["be_br_id"] = $be_br_id;
            $belt->data["be_create_date"] = $be_create_date;
            $belt->data["be_create_by_id"] = $be_create_by_id;
            $belt->data["be_update_date"] = $be_update_date;
            $belt->data["be_update_by_id"] = $be_update_by_id;

            $belt->action = 'update';
            $belt->process_id = $id;
            $result = $belt->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
                $be_id = $id;
                $none_removal_ids = "";
                for ($i = 0; $i < count($eca_exc_id); $i++) {
                    $data = $datao = array();
                    $datao["eca_create_date"] = $be_create_date;
                    $datao["eca_create_by_id"] = $be_update_by_id;
                    $datao["eca_update_date"] = $be_create_date;
                    $datao["eca_update_by_id"] = $be_update_by_id;

                    $data["eca_exc_id"] = $eca_exc_id[$i];
                    $data["eca_total_marks"] = get_rdata("eca_total_marks_" . $eca_exc_id[$i]);
                    $data["eca_obtain_marks"] = 0;

                    $res_allocation_deallocation = allocation_deallocation_exam_categories('update', $be_id, $be_br_id, $data, $datao);
                    if ($res_allocation_deallocation["errormsg"] != '') {
                        $errormsg = $res_allocation_deallocation["errormsg"];
                        break;
                    } else {
                        $none_removal_ids .= $res_allocation_deallocation["id"] . ",";
                    }
                }
                if ($none_removal_ids != '') {
                    $none_removal_ids_res = remove_deallocation_exam_categories($none_removal_ids, $be_id);
                    if ($none_removal_ids_res != '') {
                        $errormsg = $none_removal_ids_res;
                    }
                }

                if ($errormsg == "") {
                    header('Location:manage_belt.php?msg=3&page=1&per_page=' . $per_page);
                }
            }
        }
    }
// start of code of categories
        if ($id == 0) {
            $categories_q = "SELECT ec.exc_id, ec.exc_marks , ec.exc_name, ec.exc_status, ec.exc_parent_id , ec.exc_status, ec.exc_id, ecp.exc_name exc_name_p, 0 as eca_total_marks, 0 as eca_obtain_marks,  0 as eca_id  FROM sm_exam_categories ec LEFT JOIN sm_exam_categories ecp ON (ec.exc_parent_id =  ecp.exc_id) ORDER BY ec.exc_parent_id , ec.exc_id";
        } else {
            $categories_q = 'SELECT ec.exc_id , ec.exc_marks,  ec.exc_name, ec.exc_status, ec.exc_parent_id , ec.exc_status, ec.exc_id, ecp.exc_name exc_name_p , eca.eca_total_marks, eca.eca_obtain_marks , IF(eca.eca_id IS NULL,0,eca.eca_id) as eca_id
  FROM sm_exam_categories ec
  LEFT JOIN sm_exam_categories ecp ON (ec.exc_parent_id =  ecp.exc_id)
  LEFT JOIN sm_exam_categories_allocation eca ON (eca.eca_exc_id = ec.exc_id AND eca.eca_be_id  = ' . $id . ') WHERE ec.exc_br_id = ' . $tmp_admin_id . ' ORDER BY ec.exc_parent_id , ec.exc_id';
        }
//

        $categories_r = m_process("get_data", $categories_q);
        $show_categories = false;
        if ($categories_r["status"] == "failure") {
            $errormsg = $categories_r["errormsg"];
        } else if ($categories_r["count"] == 0) {
            $errormsg = "no categories found to process";
        } else {
            $show_categories = true;
        }
// end of code of categories
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
                                        <form name="form1"  id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" >
                                            <input type="hidden" id="act" name="act" />
                                            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                            <input type="hidden" id="be_br_id" name="be_br_id" value="<?php echo $be_br_id; ?>" />

                                            <div class="box-body">
                                                <div class=" col-md-6">
                                                    <!-- START -->
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Belt Name</label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="be_name" id="be_name"  placeholder="Belt Name" value="<?php echo $be_name; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Next Belt</label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="be_name_for" id="be_name_for"  placeholder="Next Belt" value="<?php echo $be_name_for; ?>" class="form-control" />
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Exam Duration (Days)</label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="be_belt_duration" id="be_belt_duration"  placeholder="Exam Duration in days" value="<?php echo $be_belt_duration; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Course Duration (Month)</label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="be_belt_fee" id="be_belt_fee"  placeholder="Course Duration (Month)" value="<?php echo $be_belt_fee; ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Exam Fee</label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="be_belt_exam_fee" id=" be_belt_exam_fee"  placeholder="Exam Fee" value="<?php echo $be_belt_exam_fee; ?>"  class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <!-- <label class="col-sm-3 control-label">One Month Fee</label> -->
                                                        <div class="col-sm-9">
                                                            <input  type="hidden" name="be_belt_onemonth_fee" id="be_belt_onemonth_fee"  placeholder="One Month Fee" value="<?php echo $be_belt_onemonth_fee; ?>" class="form-control" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Sort Order</label>
                                                        <div class="col-sm-9">
                                                            <input required type="text" name="be_sort_order" id="be_sort_order"  placeholder="Sort Order" value="<?php echo $be_sort_order; ?>" class="form-control" />
                                                        </div>
                                                    </div>

                                                    <!-- END -->
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Status</label>

                                                        <div class="col-sm-9">
                                                            <input type="radio" name="be_status" id="be_status_a" <?php
        if ($be_status == 'A') {
            echo 'checked="checked"';
        };
        ?>  value="A" /><label for="be_status_a">Active</label> <input type="radio" name="be_status" id="be_status_i" value="I" <?php if ($be_status == 'I') echo 'checked="checked"'; ?> /><label for="be_status_i">InActive</label>
                                                        </div>


                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Categories</label>
                                                        <div class="col-sm-9">

                                                            <?php
                                                            if ($show_categories == true) {

                                                                echo '<table class="table table-bordered" style="width:100%">
                    <tbody><tr>
                    <th  style="width:300px">P. Category</th>
                      <th  style="width:300px">Category</th>
                      <th style="width:100px">Total M</th>
                    </tr>';
                                                                foreach ($categories_r["res"] as $arr_db_c) {
                                                                  $checked = '';
                                                                  $exc_marks = $arr_db_c["exc_marks"];
                                                                  // $exc_marks = $arr_db_c["eca_total_marks"];
                                                                  if ($id ==0)
                                                                  {
                                                                  //  $exc_marks = $arr_db_c["exc_marks"];
                                                                  }
                                                                  else {
                                                                  if ($arr_db_c["eca_id"] !=0)
                                                                    {

                                                                      $checked = 'checked';
                                                                      // $exc_marks = $arr_db_c["eca_total_marks"];
                                                                  }
                                                                  else {
                                                                    //  $exc_marks = $arr_db_c["exc_marks"];
                                                                  }
                                                                  }

                                                                    if (isset($_POST["eca_total_marks_" . $arr_db_c["exc_id"]])) {
                                                                        // $exc_marks = $_POST["eca_total_marks_" . $arr_db_c["exc_id"]];
                                                                    }

                                                                  echo '<tr>
                                                                  <td>' . $arr_db_c["exc_name_p"] . '</td>
                     <td><label style="font-weight:normal;"><input ' . $checked . ' class="exam_categories" type="checkbox" name="eca_exc_id[]" value="' . $arr_db_c["exc_id"] . '" > ' . $arr_db_c["exc_name"] . '</label></td>

                      <td style="text-align:right;">
                      <input type="hidden" name="eca_total_marks_' . $arr_db_c["exc_id"] . '" id="ex_name"  placeholder="Total" value="' . $exc_marks . '" class="form-control" />'.$exc_marks.'
                      </td>
                      </tr> ';
                                                                }
                                                            }
                                                            echo '</tbody></table>';
                                                            ?>
                                                        </div>
                                                    </div>





                                                    <!-- /.box-body -->

                                                </div>
                                            </div><!-- /.box -->
                                            <div class="box-footer">
                                                            <?php if ($id == 0) { ?>  <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                                <button type="button" onclick="validate_belt();" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
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
