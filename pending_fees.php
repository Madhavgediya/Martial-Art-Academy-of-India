<?php
include("includes/application_top.php");
include("../includes/class/book.php");


//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Pending Fee";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$book_id = get_rdata('book_id');
$book_title = get_rdata('book_title');
$book_author = get_rdata('book_author');
$book_publication = get_rdata('book_publication');
$book_status = get_rdata('book_status', 'A');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Book Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Book Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Book Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', 200000);
$order_by = get_rdata('order_by', 'book_id');
$order = get_rdata('order', 'asc');
$client_arrow = $book_title_arrow = $book_title_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'book_title') {
        $sc_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $client_arrow = 'glyphicon glyphicon-chevron-up';
    }
} else {
    $order = 'asc';
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {

    $sm_book_master = new book();
    $sm_book_master->action = 'delete';
    $del_where = "book_id = " . $id;
    $del_where .= " and book_br_id= " . $tmp_admin_id;

    $sm_book_master->where = $del_where;
    $result = $sm_book_master->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        $successmsg = "Book Has Been Deleted Successfully";
    }
}

//searching and pagination
$condition = ' b.book_br_id=' . $tmp_admin_id;
if ($book_title != '') {
    $condition .= " and 	b.book_title LIKE '%" . escape($book_title) . "%'";
}

$condition .= " order by b." . $order_by . ' ' . $order;
$table = "sm_book b LEFT JOIN sm_student s ON (b.book_issue_stu_id =  s.stu_id) ";

$pageObj = new PS_Pagination($table, 'b.*,s.stu_first_name,s.stu_last_name,s.stu_phone', "$condition", $per_page, 10, "per_page=" . $per_page . "&book_title=" . $book_title . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

$arr_branch_details = get_branch_details(session_get("id"));
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

                    <div class="row" style="margin-right:5px;">
                        <div class="col-xs-6">
                            <div class="box">                                
                                <div class="box-body">
                                    <p class="lead text-center mid_caption">Course Fee <a href="javascript:void(0);" onclick="print_attendance('print_me', '<?php echo $arr_branch_details["name"]; ?>', 'course_pending_fee', '');" class="text-info fa fa-fw fa-print" style="float:right;" ></a></p>
                                    <table class="table table-bordered" id="print_me">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>GR. No.<br>Name</th>
                                            <th>Contact</th>
                                            <th>Details</th>
                                            <th>C. Date</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
<?php
// $bi_issue_date_valid = convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BIRTHDAY_PERIOD)));
$b_sql = "SELECT sc_course_type, sc_remarks, stu_gr_no,stu_first_name,stu_phone,stu_parent_mobile_no,stu_whatsappno,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,sc_half_course, brt_name,be_name,be_name_for,co_name, sc_joined_date, sc_total_fee, sc_total_paid 
               FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   
               WHERE sc_total_fee > (sc_total_paid + sc_discount_amount) AND sc_br_id= " . $tmp_admin_id . " order by stu_gr_no ASC ";
$b_result = m_process("get_data", $b_sql);

if ($b_result['errormsg'] != '') {
    echo $b_result['errormsg'];
} else {
    if ($b_result['count'] > 0) {
        $sr = 0;
        foreach ($b_result['res'] as $b_db_row) {
            $sr++;
            $contact_page_url="add_edit_contact.php?con_name=".$b_db_row['stu_first_name']." ".$b_db_row['stu_last_name']."-Fee&con_email=".$b_db_row['stu_email']."&con_phone=".$b_db_row['stu_phone'].",".$b_db_row['stu_parent_mobile_no']."&con_followup_type=Fee";
            $stu_contact_no = "";
            if ($b_db_row['stu_phone'] != '') {
                $stu_contact_no .= "S: " . $b_db_row['stu_phone'] . "<br>";
            }
            if ($b_db_row['stu_parent_mobile_no'] != '') {
                $stu_contact_no .= "P: " . $b_db_row['stu_parent_mobile_no'] . "<br>";
            }
            if ($b_db_row['stu_whatsappno'] != '') {
                $stu_contact_no .= "W: " . $b_db_row['stu_whatsappno'] . "<br>";
            }

            $stu_others = '<span style="color:blue;">' . $b_db_row['brt_name'] . '</span><br>';
            $stu_others .= '<span style="color:orange;">' . $b_db_row['co_name'] . '</span><br>';
            $stu_others .= '<span style="color:green;">' . ($b_db_row['sc_half_course'] == 1 ? $b_db_row['be_name_for'] : $b_db_row['be_name']) . '</span>';
            ?>
                                                    <tr>
                                                        <td><?php echo $sr; ?></td>
                                                        <td><?php echo $b_db_row["stu_gr_no"] . '<br>' . $b_db_row["stu_first_name"] . " " . $b_db_row["stu_last_name"]; ?></td>
                                                        <td><?php echo $stu_contact_no; ?></td>
                                                        <td><?php echo $stu_others; ?></td>
                                                        <td><?php echo convert_db_to_disp_date($b_db_row["sc_joined_date"]); ?></td>
                                                        <td><?php echo "T: " . ($b_db_row["sc_total_fee"]) . "<br>P: " . $b_db_row["sc_total_paid"] . "<br><span style='color:red;'>T. " . ($b_db_row["sc_total_fee"] - $b_db_row["sc_total_paid"]) . '</span><br><span class=c_"'.str_replace(" ","_",$b_db_row["sc_course_type"]).'">'.$b_db_row["sc_course_type"]; ?></span></td>
                                                        <td><?php echo nl2br($b_db_row['sc_remarks']); ?></td>
                                                        <td><a href="<?php echo $contact_page_url;?>" target="_blank" >Reminder</a></td>
                                                        
        <?php
        }
    } else {
        ?>
                                                    <tr>

                                                        <td colspan="5" class="text-center" >No upcoming birhthdays</td>

                                                    </tr>
    <?php }
}
?>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-6" style="padding-left:0px;">
                            <div class="box">                                
                                <div class="box-body">
                                    <p class="lead text-center mid_caption">Exam Fee <a href="javascript:void(0);" onclick="print_attendance('print_me_exam', '<?php echo $arr_branch_details["name"]; ?>', 'exam_pending_fee', '');" class="text-info fa fa-fw fa-print" style="float:right;" ></a></p>
                                    <table class="table table-bordered" id="print_me_exam">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>GR. No.<br>Name</th>
                                            <th>Contact</th>
                                            <th>Details</th>
                                            <th>E. Date</th>
                                            <th>Amount</th>
                                        </tr>
<?php
// $bi_issue_date_valid = convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BIRTHDAY_PERIOD)));
$b_sql = "SELECT  DISTINCT ex_name, ex_date, exs_belt, exs_already_paid,stu_id, exs_id,exs_ex_id, exs_result_status,exs_result_marks,exs_enroll_next, exs_fee,exs_paid,  
               co_name , be_id, be_name, be_name_for ,be_belt_duration  ,be_belt_exam_fee, sc_joined_date,stu_parent_mobile_no,stu_whatsappno,sc_half_course,
               stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name, stu_br_id, brt_name  FROM   sm_exam_student_entrolled
               INNER JOIN sm_exam ON (ex_id = exs_ex_id)
               INNER JOIN sm_student_course ON (exs_stu_id = sc_stu_id AND  exs_be_id = sc_be_id AND exs_co_id = sc_co_id )
               INNER JOIN sm_course ON (co_id = sc_co_id)
               INNER JOIN sm_belt ON (be_belt_duration != 0  AND be_id = sc_be_id)
               INNER JOIN sm_student ON (stu_id = sc_stu_id )
               INNER JOIN sm_branch_type ON (sc_brt_id = brt_id )  
               WHERE  stu_br_id= " . $tmp_admin_id . " AND exs_already_paid = 'N' AND exs_paid = 0 order by stu_gr_no ASC ";
$b_result = m_process("get_data", $b_sql);

if ($b_result['errormsg'] != '') {
    echo $b_result['errormsg'];
} else {
    if ($b_result['count'] > 0) {
        $sr = 0;
        foreach ($b_result['res'] as $b_db_row) {
            $sr++;
            $stu_contact_no = "";
            if ($b_db_row['stu_phone'] != '') {
                $stu_contact_no .= "S: " . $b_db_row['stu_phone'] . "<br>";
            }
            if ($b_db_row['stu_parent_mobile_no'] != '') {
                $stu_contact_no .= "P: " . $b_db_row['stu_parent_mobile_no'] . "<br>";
            }
            if ($b_db_row['stu_whatsappno'] != '') {
                $stu_contact_no .= "W: " . $b_db_row['stu_whatsappno'] . "<br>";
            }

            $stu_others = '<span style="color:red;">' . $b_db_row['ex_name'] . '</span><br>';
            $stu_others .= '<span style="color:blue;">' . $b_db_row['brt_name'] . '</span><br>';
            $stu_others .= '<span style="color:orange;">' . $b_db_row['co_name'] . '</span><br>';
            $stu_others .= '<span style="color:green;">' . $b_db_row['be_name_for'] . '</span>';
            ?>
                                                    <tr>
                                                        <td><?php echo $sr; ?></td>
                                                        <td><?php echo $b_db_row["stu_gr_no"] . '<br>' . $b_db_row["stu_first_name"] . " " . $b_db_row["stu_last_name"]; ?></td>
                                                        <td><?php echo $stu_contact_no; ?></td>
                                                        <td><?php echo $stu_others; ?></td>
                                                        <td><?php echo convert_db_to_disp_date($b_db_row["ex_date"]); ?></td>
                                                        <td><?php echo $b_db_row["exs_fee"]; ?></td>
        <?php
        }
    } else {
        ?>
                                                    <tr>

                                                        <td colspan="5" class="text-center" >No upcoming birhthdays</td>

                                                    </tr>
                                                <?php }
                                            }
                                            ?>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row" style="margin-right:5px;">
                        <div class="col-xs-6">
                            <div class="box">                                
                                <div class="box-body">
                                    <p class="lead text-center mid_caption">Event Fee <a href="javascript:void(0);" onclick="print_attendance('print_me_event_fee', '<?php echo $arr_branch_details["name"]; ?>', 'event_pending_fee', '');" class="text-info fa fa-fw fa-print" style="float:right;" ></a></p>
                                    <table class="table table-bordered" id="print_me_event_fee">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>GR. No.<br>Name</th>
                                            <th>Contact</th>
                                            <th>Details</th>
                                            <th>C. Date</th>
                                            <th>Amount</th>
                                        </tr>
<?php
// $bi_issue_date_valid = convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BIRTHDAY_PERIOD)));
$b_sql = "SELECT  DISTINCT ev_name, ev_date, stu_parent_mobile_no,stu_whatsappno,stu_email, evs_fee, stu_br_id, stu_id, evs_ev_id,  evs_result_status,evs_result_marks,evs_enroll_next,  evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name, brt_name,be_name_for be_name, be_name_for,co_name, 'student' as stu_or_other   FROM  sm_student
LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1 )
LEFT JOIN sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id) 
LEFT JOIN  sm_event_student_entrolled ON (evs_stu_or_other = 'student'  AND stu_id = evs_stu_id) INNER JOIN sm_event ON (evs_ev_id = ev_id)  WHERE evs_paid = 0 AND evs_fee !=0 AND   stu_br_id= " . $tmp_admin_id . " 
UNION SELECT  DISTINCT  ev_name, ev_date, stu_parent_mobile_no,stu_whatsappno,stu_email, evs_fee, stu_br_id, stu_id, evs_ev_id, evs_result_status,evs_result_marks,evs_enroll_next,evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name , '' brt_name, '' be_name, '' be_name_for,'' co_name, 'other' as stu_or_other  FROM  sm_student_other  LEFT JOIN  sm_event_student_entrolled ON (evs_stu_or_other = 'other' AND stu_id = evs_stu_id ) INNER JOIN sm_event ON (evs_ev_id = ev_id)  WHERE evs_paid = 0 AND evs_fee !=0 AND  stu_br_id= " . $tmp_admin_id . " order by stu_gr_no asc ";

// $b_sql = "SELECT stu_gr_no,stu_first_name,stu_phone,stu_parent_mobile_no,stu_whatsappno,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,sc_half_course, brt_name,be_name,be_name_for,co_name, sc_joined_date, sc_total_fee, sc_total_paid 
//                FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   
//                WHERE sc_total_fee > sc_total_paid AND sc_br_id= " . $tmp_admin_id . " order by stu_gr_no ASC ";
$b_result = m_process("get_data", $b_sql);

if ($b_result['errormsg'] != '') {
    echo $b_result['errormsg'];
} else {
    if ($b_result['count'] > 0) {
        $sr = 0;
        foreach ($b_result['res'] as $b_db_row) {
            $sr++;

            $stu_contact_no = "";
            if ($b_db_row['stu_phone'] != '') {
                $stu_contact_no .= "S: " . $b_db_row['stu_phone'] . "<br>";
            }
            if ($b_db_row['stu_parent_mobile_no'] != '') {
                $stu_contact_no .= "P: " . $b_db_row['stu_parent_mobile_no'] . "<br>";
            }
            if ($b_db_row['stu_whatsappno'] != '') {
                $stu_contact_no .= "W: " . $b_db_row['stu_whatsappno'] . "<br>";
            }

            $stu_others = '<span style="color:blue;">' . $b_db_row['ev_name'] . '</span><br>';
           //  $stu_others .= '<span style="color:orange;">' . $b_db_row['co_name'] . '</span><br>';
            // $stu_others .= '<span style="color:green;">' . ($b_db_row['sc_half_course'] == 1 ? $b_db_row['be_name_for'] : $b_db_row['be_name']) . '</span>';
            ?>
                                                    <tr>
                                                        <td><?php echo $sr; ?></td>
                                                        <td><?php echo $b_db_row["stu_gr_no"] . '<br>' . $b_db_row["stu_first_name"] . " " . $b_db_row["stu_last_name"]; ?></td>
                                                        <td><?php echo $stu_contact_no; ?></td>
                                                        <td><?php echo $stu_others; ?></td>
                                                        <td><?php echo convert_db_to_disp_date($b_db_row["ev_date"]); ?></td>
                                                        <td><?php echo "T: " . ($b_db_row["evs_fee"]) .  '</span>'; ?></td>
        <?php
        }
    } else {
        ?>
                                                    <tr>

                                                        <td colspan="5" class="text-center" >No upcoming birhthdays</td>

                                                    </tr>
    <?php }
}
?>
                                    </table>
                                </div>
                            </div>

                        </div>

                       
                    </div>
                </section>
            </div>
<?php include("includes/return-reissue-book.php"); ?>
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
