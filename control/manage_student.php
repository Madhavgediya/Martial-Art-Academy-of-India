<?php
include("includes/application_top.php");
include("../includes/class/student.php");
$page_title = "Manage Student";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$stu_id= get_rdata('stu_id');
$stu_gr_no= get_rdata('stu_gr_no');
$stu_first_name= get_rdata('stu_first_name');
$stu_middle_name= get_rdata('stu_middle_name');
$stu_last_name= get_rdata('stu_last_name');


$stu_status= get_rdata('stu_status','A');
$stu_br_id= get_rdata('stu_br_id');
$stu_current_course= get_rdata('stu_current_course');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Student Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Student Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Student Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Student has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
      $successmsg = "Student has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', ' stu_gr_no ');
$order = get_rdata('order', 'asc');
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

// step 3: make new object of user class
// delete user code
/*
if ($act == 'delete' && $id != 0) {

            $sm_student = new student();
            $sm_student->action = 'delete';

            $del_where = "stu_br_id = ".$tmp_admin_id." AND stu_id = ". $id;

            $sm_student->where = $del_where;

            $result = $sm_student->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                    $successmsg = "Student Has Been Deleted Successfully";
            }
}
*/

//searching and pagination
$condition = '1';
if ($stu_gr_no != '') {
    $condition.=" and 	stu_gr_no LIKE '%" . $stu_gr_no . "%'";
}
if ($stu_first_name != '') {
    $condition.=" and 	stu_first_name LIKE '%" . $stu_first_name . "%'";
}
if ($stu_last_name != '') {
    $condition.=" and 	stu_last_name LIKE '%" . $stu_last_name . "%'";
}
if ($stu_status != 'All') {
    $condition.=" and stu_status = '" . $stu_status . "'";
}

if ($tmp_type != 'admin') {
    $condition.=" and stu_br_id= " . $tmp_admin_id;
}

$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_student LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   LEFT JOIN sm_batch_time ON (stu_batchtime = bt_id )  ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$select_f = "stu_parent_mobile_no, stu_whatsappno, stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name,be_name_for,  	sc_half_course, co_name, bt_name ";
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&stu_last_name=" . $stu_last_name. "&stu_gr_no=" . $stu_gr_no. "&stu_first_name=" . $stu_first_name. "&stu_status=" . $stu_status . "&order by=" . $order_by . "&order=" . $order);
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
                        Manage Student
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Student</li>
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
                                                <label class="col-sm-3 control-label">First Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="stu_first_name" id="stu_first_name"  placeholder="Enter First Name" value="<?php echo $stu_first_name; ?>" class="form-control" />
                                                </div>
                                            </div>
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
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9">
                                                  <select required id="stu_status" name="stu_status" class="form-control">
                                                      <?php
                                                      $data_arr_input = array();
                                                      $data_arr_input['data_array'] =  $arr_status_global;
                                                      $data_arr_input['consider'] =  "key";
                                                      $data_arr_input['current_selection_value'] = $stu_status;
                                                      display_dd_options_from_array($data_arr_input);
                                                      ?>
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_student.php'">Cancel</button>
                                        <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Export <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="manage_student_pdf.php">Export to PDF</a></li>
    <li><a href="manage_student_excel.php">Export to Excel</a></li>
  </ul>
</div>
                                        <button type="button" style="float:right;" class="btn btn-success" onclick="window.location.href='add_edit_student.php'">ADD</button>
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
                                                <th align="center" ><a href="manage_student.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_gr_no&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $stu_gr_no_arrow; ?>"></span></a></th>
                                                <th align="center"><a href="manage_student.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_first_name&order=<?php echo $order; ?>">Name <span class="<?php echo $stu_first_name_arrow; ?>"></span></a></th>
                                                <th align="center" >Phone</th>
                                                <th align="center">B. Type</th>
                                                <th align="center">B. Time</th>
                                                <th align="center" >Belt</th>
                                                <th align="center" >Couse</th>
                                                <th align="center" width="10px">Status</th>
                                                <th align="center" class="t_align_center"  width="120px">Action</th>
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
                                                    $stu_brt_name= $stu_be_name= $stu_co_name = '';
                                                    if ($db_row['brt_name'] == '' && $db_row['be_name'] == '' && $db_row['co_name'] == '')  
                                                    {
                                                        $arr_get_student_course =  get_student_lastet_course($db_row['stu_id']);
                                                        if ($arr_get_student_course["error_message"] == '')
                                                        {
                                                            $stu_brt_name= $arr_get_student_course['brt_name'] ;
                                                            $stu_be_name= $arr_get_student_course['be_name'] ;
                                                            $stu_co_name = $arr_get_student_course['co_name'] ;
                                                        }
                                                        
                                                    } 
                                                    else
                                                    {
                                                        $stu_brt_name= $db_row['brt_name'] ;
                                                         $stu_be_name= $db_row['be_name'] ;
                                                         if ($db_row["sc_half_course"]==1)
                                                            $stu_be_name= $db_row['be_name_for'] ;
                                                         $stu_co_name = $db_row['co_name'] ;
                                                    }
                                                    



                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>

                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>
                                                        <td style="padding-left:10px;"><?php
                                                        if ($db_row['stu_phone'] !='') { echo "S: ".$db_row['stu_phone']."</br>";}
                                                        if ($db_row['stu_parent_mobile_no'] !='') { echo "P: ".$db_row['stu_parent_mobile_no']."</br>";}
                                                        if ($db_row['stu_whatsappno'] !='') { echo "W: ".$db_row['stu_whatsappno']."</br>";}
                                                        ?></td>
                                                        <td style="padding-left:10px;"><?php echo $stu_brt_name; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['bt_name']; ?></td>
                                                      
                                                        <td style="padding-left:10px;"><?php echo $stu_be_name; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $stu_co_name; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['stu_status']=='A'?'Active':'Inactive'); ?></td>
                                                        <td><center><a href="add_edit_student.php?id=<?php echo $db_row['stu_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                        <a href="add_view_student.php?id=<?php echo $db_row['stu_id']; ?>&per_page=<?php echo $per_page; ?>" class="glyphicon glyphicon-eye-open"></a>&nbsp;
                                                        <!--        <a href="javascript:void(0);" class="text-danger glyphicon glyphicon-remove" data-toggle="modal" data-target="#ConfirmDelete" onclick="delete_record(<?php echo $db_row['stu_id']; ?>,'Student')"></a>&nbsp;  -->
                                                                <a href="javascript:void(0);" class="text-info fa fa-bars" onclick="show_add_course(<?php echo $db_row['stu_id']; ?>,'Add/View Course Details')"></a>&nbsp;
<!--
                                                            <a href="add_edit_student_course.php?sc_stu_id=<?php echo $db_row['stu_id']; ?>" class="text-primary fa fa-fw fa-plus" ></a>
                                                            <a href="manage_student_course.php?sc_stu_id=<?php echo $db_row['stu_id']; ?>" class="text-info fa fa-fw fa-tasks" ></a>
-->
<a href="javascript:void(0);" class=" fa fa-fw fa-arrows" onclick="show_batch_type_change(<?php echo $db_row['stu_id']; ?>)"></a>&nbsp;
<a href="student_document.php?doc_stu_id=<?php echo $db_row['stu_id']; ?>" class="text-info fa fa-file" ></a>
<a href="manage_sale.php?inv_purchase_del_id=<?php echo "S-".$db_row['stu_id']; ?>" class="fa fa-shopping-cart" ></a>
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
            <?php include("includes/change_batch_type.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
       <script>
        function exportToPDF()
        {
            var tableData = [];
            $('#example2 tbody tr').each(function () {
            var rowData = [];
            $(this).find('td').each(function () {
            rowData.push($(this).text());
            });
                tableData.push(rowData);
            });
           // console.log("tableData:",tableData);
            $.ajax({
            url: 'manage_student_pdf.php', 
            method: 'POST', 
            data: {tableData:tableData},
            success: function (data) {

                //console.log(data);
                
                var base64PdfData = "base64"

// Create a Blob from the base64 data
                var pdfBlob = new Blob([atob(base64PdfData)], { type: 'application/pdf' });
                //console.log(pdfBlob);
// Create a URL for the Blob
                var pdfUrl = URL.createObjectURL(pdfBlob);
                
// Open the PDF in a new tab
                window.open(pdfUrl, '_blank');
                //window.open('data:application/pdf;base64,' + json_encode(data));
            },
            error: function () {
                alert('Error generating PDF.');
            }
        });
        }
        </script>
     
    </body>
</html>
