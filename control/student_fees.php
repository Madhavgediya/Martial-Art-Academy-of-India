<?php
include("includes/application_top.php");
include("../includes/class/batchtype.php");


$page_title = "Student Fee";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$brt_name = get_rdata('brt_name');

$sc_co_id = get_rdata('sc_co_id');
$sc_be_id = get_rdata('sc_be_id');
$discount = get_rdata('discount');
$sc_brt_id = get_rdata('sc_brt_id');
$payby = get_rdata('payby','all');

$stu_gr_no = get_rdata('stu_gr_no');
$stu_first_name = get_rdata('stu_first_name');
$stu_last_name = get_rdata('stu_last_name');
$stu_status = get_rdata('stu_status','All');
$sc_full_fee_paid = get_rdata('sc_full_fee_paid','N');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Branch Type Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Branch Type Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Branch Type Has Been Updated Successfully";
} else {
    $successmsg = '';
}

$total_rows = '';
$page = get_rdata("page", 1);
$order = get_rdata('order', 'asc');
$per_page = get_rdata('per_page',PER_PAGE);
$order_by = get_rdata('order_by',' stu_gr_no ');
$stu_first_name_arrow = $stu_gr_no_arrow=  'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'stu_first_name') {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'stu_gr_no') {
        $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

//searching and pagination

  $condition = ' sc_br_id='.$tmp_admin_id;
if ($payby == 'batch')
{

  if ($stu_gr_no != '') {
      $condition.=" and stu_gr_no LIKE '%" . $stu_gr_no . "%'";
  }
  if ($stu_first_name != '') {
      $condition.=" and stu_first_name LIKE '%" . $stu_first_name . "%'";
  }
  if ($stu_last_name != '') {
      $condition.=" and 	stu_last_name LIKE '%" . $stu_last_name . "%'";
  }
  if ($stu_status != 'All') {
      $condition.=" and stu_status = '" . $stu_status . "'";
  }

  if ($sc_be_id != 0) {
      $condition.=" and 	sc_be_id =" . $sc_be_id ;
  }
  if ($sc_brt_id != 0) {
      $condition.=" and 	sc_brt_id  =" . $sc_brt_id;
  }
  if ($sc_co_id != 0) {
      $condition.=" and 	sc_co_id =" . $sc_co_id;
  }
  if ($stu_status != 'All') {
      $condition.=" and stu_status = '" . $stu_status . "'";
  }
}

if ($sc_full_fee_paid != 'All') {
    $condition.=" and sc_full_fee_paid = '" . $sc_full_fee_paid . "'";
}
if ($discount == 'Y') {
    $condition.=" and sc_discount_amount > 0 ";
}
if ($discount == 'N') {
    $condition.=" and sc_discount_amount = 0 ";
}


$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )  ";

$select_f = "stu_gr_no,stu_first_name,stu_phone,stu_parent_mobile_no,stu_whatsappno,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name,co_name, sc_joined_date, sc_total_fee, sc_total_paid, sc_full_fee_paid, sc_br_id, sc_brt_id, sc_co_id, sc_be_id, sc_id, sc_cd_id, sc_discount_amount ";
// echo "SELECT $select_f FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . 
"&brt_name=" . $brt_name .
"&sc_co_id=" . $sc_co_id .
"&sc_be_id=" . $sc_be_id .
"&discount=" . $discount . 
"&sc_brt_id=" . $sc_brt_id . 
"&payby=" . $payby . 
"&stu_gr_no=" . $stu_gr_no . 
"&stu_first_name=" . $stu_first_name . 
"&stu_last_name=" . $stu_last_name . 
"&stu_status=" . $stu_status . 
"&sc_full_fee_paid=" . $sc_full_fee_paid . 
"&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
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
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?php echo $page_title; ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $page_title; ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                     <?php include("includes/messages.php"); ?>
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form class="form-horizontal" name="form1" id="form1" method="post" onsubmit="return validate_add_edit_form();">
                                    <input type="hidden" name="act" id="act">
                 		                <input type="hidden" value="0" name="id" id="id">
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">By</label>
                                                <div class="col-sm-9">
                                                  <div class="col-sm-9">
                                                      <input type="radio" name="payby" onclick="show_hide_fees('hide');"  <?php if($payby == 'all') echo 'checked="checked"'; ?>  id="payby_b" value="all" /><label for="payby_b"> All</label><input onclick="show_hide_fees('show');" type="radio" name="payby" id="payby_s" style="margin-left:10px;"  value="batch" <?php if($payby == 'batch') echo 'checked="checked"'; ?>  /><label for="payby_s"> Batch/Student</label>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear:both"></div>
                                        <div id="by_batch" <?php if($payby != 'all') echo ''; else echo 'style="display:none;"' ?>   >
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Batch Type</label>
                                                <div class="col-sm-9">
                                                    <select required id="sc_brt_id" name="sc_brt_id" class="form-control">
                                                        <option value="0">--Please select--</option>
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'brt_name ,brt_id';
                                                        $data_arr_input['table'] = 'sm_branch_type';
                                                        $data_arr_input['where'] = " brt_br_id = ".$tmp_admin_id." AND brt_status  = 'A' ";
                                                        $data_arr_input['key_id'] = 'brt_id';
                                                        $data_arr_input['key_name'] = 'brt_name';
                                                        $data_arr_input['current_selection_value'] = $sc_brt_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Course</label>
                                                <div class="col-sm-9">
                                                    <select required id="sc_co_id" name="sc_co_id" class="form-control">
                                                        <option value="0">--Please select--</option>
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'co_name ,co_id';
                                                        $data_arr_input['table'] = 'sm_course';
                                                        $data_arr_input['where'] = " co_status  = 'A' ";
                                                        $data_arr_input['key_id'] = 'co_id';
                                                        $data_arr_input['key_name'] = 'co_name';
                                                        $data_arr_input['current_selection_value'] = $sc_co_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Belt</label>
                                                <div class="col-sm-9">
                                                    <select required id="sc_be_id" name="sc_be_id" class="form-control">
                                                        <option value="0">--Please select--</option>
                                                        <?php
                                                        $data_arr_input = array();
                                                        $data_arr_input['select_field'] = 'be_name ,be_id';
                                                        $data_arr_input['table'] = 'sm_belt';
                                                        $data_arr_input['where'] = " be_status  = 'A' ";
                                                        $data_arr_input['key_id'] = 'be_id';
                                                        $data_arr_input['key_name'] = 'be_name';
                                                        $data_arr_input['order_by'] = 'be_sort_order';
                                                        $data_arr_input['current_selection_value'] = $sc_be_id;
                                                        display_dd_options($data_arr_input);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                          </div>
		                                  <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Discount</label>
                                                <div class="col-sm-9">
                                                    <select id="discount" name="discount"  class="form-control" >
                                                    <option <?php if($discount == 'All') { echo 'selected'; } ?>  value="All">All</option>
                                                    <option <?php if($discount == 'Y') { echo 'selected'; } ?> value="Y">Yes</option>
                                                    <option <?php if($discount == 'N') { echo 'selected'; } ?> value="N">No</option>

                                                  </select>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6"><div class="form-group">
                                              <label class="col-sm-3 control-label"></label>
                                              <div class="col-sm-9">
                                              </div>
                                          </div></div>
                                          <div style="clear:both;"></div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label class="col-sm-3 control-label">First Name</label>
                                                  <div class="col-sm-9">
                                                      <input type="text" name="stu_first_name" id="stu_first_name"  placeholder="Enter First Name" value="<?php echo $stu_first_name; ?>" class="form-control" />
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label class="col-sm-3 control-label">Last Name</label>
                                                  <div class="col-sm-9">
                                                      <input type="text" name="stu_last_name" id="stu_last_name"  placeholder="Enter Last Name" value="<?php echo $stu_last_name; ?>" class="form-control" />
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label class="col-sm-3 control-label">GR No</label>
                                                  <div class="col-sm-9">
                                                      <input type="text" name="stu_gr_no" id="stu_gr_no"  placeholder="Enter GR No" value="<?php echo $stu_gr_no; ?>" class="form-control" />
                                                  </div>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Paid?</label>
                                                <div class="col-sm-9">
                                                  <select id="sc_full_fee_paid" name="sc_full_fee_paid"  class="form-control" >
                                                    <option <?php if($sc_full_fee_paid == 'All') { echo 'selected'; } ?>  value="All">All</option>
                                                    <option <?php if($sc_full_fee_paid == 'Y') { echo 'selected'; } ?> value="Y">Yes</option>
                                                    <option <?php if($sc_full_fee_paid == 'N') { echo 'selected'; } ?> value="N">No</option>
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'student_fees.php'">Cancel</button>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center" ><a href="student_fees.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_gr_no&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $stu_gr_no_arrow; ?>"></span></a></th>
                                                <th align="center"><a href="student_fees.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_first_name&order=<?php echo $order; ?>">Name <span class="<?php echo $stu_first_name_arrow; ?>"></span></a></th>
                                                <th align="center" >Phone</th>

                                                <th align="center">B. Type</th>
                                                <th align="center" >Belt</th>
                                                <th align="center" >Couse</th>
                                                <th align="center" >C. Date</th>
                                                <th align="center" >Fee</th>
                                                <th align="center" >Paid</th>
                                                <th align="center" >Discount</th>
                                                <th align="center" >Full Paid</th>
                                                <th align="center" class="t_align_center"  width="75px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $class = '';
                                            if ($objData) {
                                                for ($i = 1; $db_row = $objData->fetch(); $i++) {
                                                    $srNo++;
                                                    if ($i % 2 == 0) {
                                                        $class = 'even';
                                                    } else {
                                                        $class = 'odd';
                                                    }





                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>

                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>
                                                        <td style="padding-left:10px;">
                                                        <?php
                                                        if ($db_row['stu_phone'] !='') { echo "S: ".$db_row['stu_phone']."</br>";}
                                                        if ($db_row['stu_parent_mobile_no'] !='') { echo "P: ".$db_row['stu_parent_mobile_no']."</br>";}
                                                        if ($db_row['stu_whatsappno'] !='') { echo "W: ".$db_row['stu_whatsappno']."</br>";}
                                                        ?>
                                                        </td>

                                                        <td style="padding-left:10px;"><?php echo $db_row['brt_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['be_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['co_name']; ?></td>


                                                        <td style="padding-left:10px;"><?php echo DBtoDisp($db_row['sc_joined_date']); ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['sc_total_fee']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['sc_total_paid']); ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['sc_discount_amount']; ?></td>
                                                        
                                                        <td style="padding-left:10px;"><?php if ($db_row['sc_full_fee_paid']=='Y') echo '<span style="color:green">Yes</span>'; else echo '<span style="color:red">No</span>' ; ?></td>

                                                        <td><center>
                                                                <a id="pay_button_<?php echo $db_row['sc_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="pay_fee_student(<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['sc_id']; ?>,<?php echo $db_row['sc_br_id']; ?>,<?php echo $db_row['sc_brt_id']; ?>,<?php echo $db_row['sc_co_id']; ?>,<?php echo $db_row['sc_be_id']; ?>,'Pay Student Fee',<?php echo $db_row['sc_total_fee']; ?>,<?php echo $db_row['sc_total_paid']; ?>,'<?php echo $db_row['stu_first_name'].'  '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name']; ?>',<?php echo $db_row['sc_discount_amount']; ?>);">Pay Fee</a>
                                                            </center></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="11">No records found or you have not permission to access these records.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php if ($objData) { ?>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mt20 mb20">
                                            <?php
                                                $page = 1;
                                                if (isset($_GET['page'])) {
                                                    $page = $_GET['page'];
                                                }
                                                if ($page > 0 && ($page > ceil($total_rows / $per_page))) {
                                                    $f_line = 1;
                                                    $page = 1;
                                                } else {
                                                    $f_line = ($page - 1) * $per_page + 1;
                                                }
                                                ?>
                                                <?php $l_line = $page * $per_page; ?>
                                                Showing
                                                <?php
                                                if ($f_line < $total_rows) {
                                                    echo ($page - 1) * $per_page + 1;
                                                } else {
                                                    echo $total_rows;
                                                }
                                                ?>
                                                to
                                                <?php
                                                if ($l_line < $total_rows) {
                                                    echo $page * $per_page;
                                                } else {
                                                    echo $total_rows;
                                                }
                                                ?>
                                                of <?php echo $total_rows ?> entries
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <div class="pull-right">
                                            <ul class="pagination">
                                                <?php echo $pageObj->renderFullNav(); ?>
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
            <?php include("includes/models.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
