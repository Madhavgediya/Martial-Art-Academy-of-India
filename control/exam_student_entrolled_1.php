<?php
include("includes/application_top.php");
include("../includes/class/student.php");

$page_title = "Student Exam Enrollment";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$ex_id= get_rdata('ex_id');
$stu_gr_no= get_rdata('stu_gr_no');
$stu_first_name = get_rdata('stu_first_name');
$stu_last_name= get_rdata('stu_last_name');
$chk_process= get_rdata('chk_process');
$pay_fee= get_rdata('pay_fee',0);
$enroll= get_rdata('enroll',0);
$addresult= get_rdata('addresult',0);

if ($enroll ==1)
{
  $page_title = "Student Exam Enrollment";
}
else if ($pay_fee ==1)
{
  $page_title = "Student Exam Enrollment Fees";
}
else if ($addresult ==1)
{
  $page_title = "Student Exam Result";
}

if ($ex_id == '' OR $ex_id == 0 )
{
  echo "invalid request"; exit(0);
}
// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Exam Student Allocation Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Exam Student Allocation Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Exam Student Allocation Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Exam Student Allocation has been added successfully but course has not assigned to him/her";
} else if (isset($msg) && $msg == 5) {
      $successmsg = "Exam Student Allocation has been added successfully and course has assigned to him/her";
} else {
    $successmsg = '';
}

if ($act == "enrollstudent")
{
  $exs_ids = "";
  	add_log_txt($c_file. '--'. json_encode($chk_process) );
    $not_removed_ids = "";
  foreach ($chk_process as $enrolled_student_k =>$enrolled_student_v )
  {
     $res_entrolled = enroll_student($ex_id,$enrolled_student_v);
     if ($res_entrolled["errormsg"] != '')
     {
       $errormsg = $res_entrolled["errormsg"];
       break;
     }
     else {
        $not_removed_ids .= $res_entrolled["id"] . ",";
     }
  }

  if ($not_removed_ids != '') {
      $none_removal_ids_res = remove_enroll_student($not_removed_ids, $ex_id);
      if ($none_removal_ids_res != '') {
          $errormsg = $none_removal_ids_res;
      }
      else {
        $arr_student_categories =  insert_exam_result_categories_to_student($ex_id,0,$exs_ids."0");
        if ($arr_student_categories["errormsg"] != "")
        {
             $errrmsg = $arr_student_categories["errormsg"];
        }
        else
        { $successmsg = "Student Exam Enrollment has been done successfully."; }


      }
  }
  else {
$successmsg = "Student Exam Enrollment has been done successfully.";

  }
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', 1000);
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
if ($act == 'delete' && $id != 0) {

            $sm_student = new student();
            $sm_student->action = 'delete';

            $del_where = "stu_id = ". $id;
            if (session_get('admin_login_type') == 'school') {
                    $del_where.=" and stu_sc_id= " . session_get('admin_sc_id');
            }
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

$arr_exam_details =  get_exam_details($ex_id);
$ex_date = $cur_date;
if ($arr_exam_details["errormsg"] == "")
{
  $ex_date = $arr_exam_details["data"]["ex_date"];
}

//searching and pagination
$condition = ' DATEDIFF("'.$ex_date.'",sc_joined_date) >= be_belt_duration  ';
if ($stu_gr_no != '') {
    $condition.=" and 	stu_gr_no LIKE '%" . $stu_gr_no . "%'";
}
if ($stu_first_name != '') {
    $condition.=" and 	stu_first_name LIKE '%" . $stu_first_name . "%'";
}
if ($stu_last_name != '') {
    $condition.=" and 	stu_last_name LIKE '%" . $stu_last_name . "%'";
}


if ($tmp_type != 'admin') {
    $condition.=" and stu_br_id= " . $tmp_admin_id;
}



$select_f = " exs_ex_id, be_name_for, exs_result_status,exs_result_marks,exs_enroll_next,	sc_joined_date , stu_br_id, M.be_id, exs_total_marks, exs_fee, exs_paid,  co_name, M.eca_total_marks , be_belt_exam_fee, exs_fee,exs_paid, IF(exs_id IS NULL,0,exs_id) as exs_id,  co_name, DATEDIFF(now(),sc_joined_date) n_j_diff , be_belt_duration , stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,brt_name,be_name_for be_name";
$j_type = "";
$j_is_current = "";
if (  isset($pay_fee) && $pay_fee == 1  )
{
  $j_type = " INNER ";
}
else if (isset($enroll) && $enroll == 1  )
{
  $j_is_current = " AND sc_is_current =1 ";
  $j_type = " LEFT ";
}
if (  isset($addresult) && $addresult == 1  )
{
  $j_type = " INNER ";
  $condition.=" AND exs_paid=1 ";
}
$table = " sm_student
INNER JOIN sm_student_course
ON (sc_stu_id = stu_id )
INNER JOIN sm_belt ON (sc_be_id = be_id $j_is_current )
INNER JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id)
".$j_type. " JOIN sm_exam_student_entrolled ON (stu_id = exs_stu_id AND exs_ex_id  = ".$ex_id.")
LEFT JOIN
(SELECT SUM(IF(eca_total_marks IS NULL,0,eca_total_marks)) as  eca_total_marks , be_id
FROM sm_belt LEFT JOIN sm_exam_categories_allocation ON (be_id =  eca_be_id)
GROUP BY be_id ) as M ON (sc_be_id = M.be_id)  ";
$condition.=" AND (stu_status = 'A' OR (stu_status = 'I' AND (DATEDIFF(now(),stu_deactivation_date) < 30) )) ";
$condition.=" order by " . $order_by . ' ' . $order;

$query_final = "SELECT ". $select_f;
$query_final .= " FROM ".$table;
$query_final .= " WHERE ".$condition;
$query_final .= " UNION ";
$query_final = " SELECT ". $select_f;
$query_final .= " FROM ".$table;
$query_final .= " WHERE ".$condition;
$query_final .= " ORDER BY " . $order_by . ' ' . $order;

// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&stu_first_name=" . $stu_first_name . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

//get total records from project table
// $sm_student = new student();
//$sm_student->cquery = "select * from $table WHERE $condition";
//$sm_student->action = 'get';
//$result = $sm_student->process();
//if ($result['status'] == 'failure') {
//    $errormsg = $result['errormsg'];
//} else {
//    if ($result['count'] > 0) {
//        $total_rows = $result['count'];
//    }
//}

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
                        <li class="active">Manage Student</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php include("includes/messages.php"); ?>
                    <!-- Small boxes (Stat box) -->

                    <form id="form_enrollment" name="form_enrollment" method="post" >
                      <input type="hidden" id="act" name="act" />
                      <input type="hidden" id="pay_fee" name="pay_fee" />

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
                                                <th align="center">B. Type</th>
                                                <th align="center" >Belt</th>
                                                <th align="center" >Couse</th>
                                                <?php if($enroll ==1) { ?>
                                                <th align="center" >Join D.</th>
                                              <?php } ?>

                                                <th align="center">T. Marks</th>

                                                <?php if($pay_fee==1) { ?>
                                                <th align="center">Fee</th>
                                                <?php } ?>
                                                <?php if($pay_fee==1 || $enroll ==1) { ?>
                                                <th align="center">Paid?</th>
                                                <?php } ?>
<?php if($addresult ==1) { ?>
                                                <th align="center">R. Marks</th>
                                                <th align="center">R. Status</th>
<?php } ?>
                                                <th align="center" class="t_align_center"  width="120px">Action
<?php   if ($enroll == 1 ) { ?> <input type="checkbox" id="enrollment_check_all" name="" onchange="check_uncheck_enrollment_check();" > <?php } ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $class = '';
                                            if ($objData) {
                                                for ($i = 1; $db_row = $objData->fetch(); $i++) {
                                                    $srNo++;
                                                    if ($i % 2 == 0) {  $class = 'even'; } else { $class = 'odd'; }
                                                    $fee =$paid = 0;
                                                    $exs_paid = ($db_row['exs_paid']==0)?"No":"Yes";
                                                    $read_only = ($db_row['exs_paid']==0)?'':' onclick="return false;"  ';

                                                    $checked =  ($db_row['exs_id']==0)?'':' checked="checked"';
                                                    $exs_result_status = "";
                                                    if ($db_row['exs_result_status'] == "F")
                                                    {
                                                      $exs_result_status = "Fail";
                                                    }
                                                    else if ($db_row['exs_result_status'] == "P")
                                                    {
                                                      $exs_result_status = "Pass";
                                                    }
                                                    else if ($db_row['exs_result_status'] == "A")
                                                    {
                                                      $exs_result_status = "AB";
                                                    }
                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>

                                                        <td style="padding-left:10px;"><?php echo $db_row['brt_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['be_name_for']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['co_name']; ?></td>
<?php if($enroll ==1) { ?>
                                                        <td style="padding-left:10px;"><?php echo DBtoDisp($db_row['sc_joined_date']); ?></td>
<?php } ?>

                                                        <td style="padding-left:10px;"><?php echo $db_row['eca_total_marks']; ?></td>

<?php if($pay_fee==1) { ?>
                                                        <td style="padding-left:10px;"><?php echo $db_row['be_belt_exam_fee']; ?></td>

                                                        <?php } ?>
                                                                                                                <?php if (isset($addresult) && $addresult == 1) { ?>
                                                                                                                  <td style="padding-left:10px;"><?php echo $db_row['exs_result_marks']; ?></td>
<td style="padding-left:10px;" class="process_<?php echo $exs_result_status;?>"><?php echo $exs_result_status; ?></td>
<?php } ?>
                                                        <?php if($pay_fee==1 || $enroll ==1) { ?>
                                                        <td style="padding-left:10px;" class="process_<?php echo $exs_paid;?>"  ><?php echo $exs_paid; ?> </td>
<?php } ?>
                                                        <td><center>
                                                              <?php if (isset($enroll) && $enroll == 1)  { ?>
<input type="checkbox" class="enrollment_check" <?php echo $checked. ' '. $read_only; ?> id="chk_process" name="chk_process[]" value="<?php echo $db_row['stu_id']; ?>"  />
<?php }  else if (isset($pay_fee) && $pay_fee == 1 && $db_row['exs_paid'] ==0 ) { ?>

                                                    <a id="pay_button_<?php echo $db_row['exs_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="pay_fee_student_exam(<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['exs_id']; ?>,<?php echo $db_row['stu_br_id']; ?>,<?php echo $db_row['exs_fee']; ?>,<?php echo $db_row['exs_paid']; ?>,'Pay Student Exam Fee','<?php echo $db_row['stu_first_name'].'  '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name']; ?>');">Pay Exam Fee</a>
<?php }  else if (isset($addresult) && $addresult == 1) { ?>

                                                    <a id="result_button_<?php echo $db_row['exs_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="add_student_exam_result(<?php echo $db_row['exs_ex_id']; ?>,<?php echo $db_row['stu_id']; ?>,<?php echo $db_row['exs_id']; ?>,<?php echo $db_row['stu_br_id']; ?>,'Add Student Result','<?php echo $db_row['stu_first_name'].'  '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name']; ?>');">Add Exam Result</a>
<?php } ?>
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
                                                <?php // echo $pageObj->renderFullNav(); ?>
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if ($enroll == 1 ) {
                                             ?>
                                              <div style="text-align:right;"><button type="button" onclick="student_exam_enrollment_process();" class="btn btn-info">Process</button></div>
                                          <?php } ?>
                                </div>
                                <!-- start of greed 2 -->
                                <!-- end of greed 2 -->

                            </div>
                        </div>
                </section>
            </div>
          </form>
            <?php include("includes/models.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
