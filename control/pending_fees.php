<?php
include("includes/application_top.php");
include("../includes/class/book.php");
include("../includes/class/contact.php");



//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Pending Fee";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$stu_first_name = get_rdata('stu_first_name');
$stu_last_name = get_rdata('stu_last_name');
$stu_gr_no = get_rdata('stu_gr_no');

// Set success message based on msg ID
$msg = get_rdata('msg', '');

$act = get_rdata("act");
if($act=="inquiry_form") {
     $sm_contact = new Contact();

        $con_name = get_rdata('con_name');
        $con_email= get_rdata('con_email');
        $con_status = get_rdata('con_status','Open');
        $con_phone = get_rdata('con_phone');
        $con_message = get_rdata('con_message');
        $con_followup_type = get_rdata('con_followup_type', 'Contact');
        $con_date = get_rdata('con_date',$cur_date_only);
        $con_followup_date = get_rdata('con_followup_date',$cur_date_only);

        $sm_contact->data["con_name"] = escape($con_name);
        $sm_contact->data["con_email"] = escape($con_email);
        $sm_contact->data["con_phone"] = escape($con_phone);
        $sm_contact->data["con_message"] = escape($con_message);
        $sm_contact->data["con_followup_type"] = escape($con_followup_type);
        
                
        if ($con_date != '')
            $sm_contact->data["con_date"] = convert_disp_to_db_date($con_date);
        else
           $sm_contact->data["con_date"] = '1790-01-01';

        if ($con_followup_date != '')
            $sm_contact->data["con_followup_date"] = convert_disp_to_db_date($con_followup_date);
        else
           $sm_contact->data["con_followup_date"] = '1790-01-01';

       
        $sm_contact->data["con_br_id"] = $tmp_admin_id;
        $sm_contact->data["con_status"] = $con_status;
        $sm_contact->data["con_create_date"] = $cur_date;
        $sm_contact->data["con_create_by_id"] = $tmp_admin_id;
        $sm_contact->data["con_update_date"] = $cur_date;
        $sm_contact->data["con_update_by_id"] = $tmp_admin_id;

        $sm_contact->action = 'insert';
        $result = $sm_contact->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:pending_fees.php');
                exit(0);
        }
}

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
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form class="form-horizontal" name="form1" id="form1" method="post" >
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
                                                <label class="col-sm-3 control-label">GR No.</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="stu_gr_no" id="stu_gr_no"  placeholder="Enter GR No" value="<?php echo $stu_gr_no; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'pending_fees.php'">Cancel</button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Export <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="exportToPdf()">Export to PDF</a></li>
                                                <li><a href="#" onclick="exportToExcel()">Export to Excel</a></li>
                                            </ul>
                                        </div>
                                    </div><!-- /.box-footer -->
                                </form>
                            </div>

                        </div>
                    </div>
                     <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a id="tab_1_link" href="#tab_1" data-toggle="tab">Course Fee</a></li>
                <li><a id="tab_2_link" href="#tab_2" data-toggle="tab">Exam Fee</a></li>
                <li><a id="tab_3_link" href="#tab_3" data-toggle="tab">Event Fee</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
               
                            <div class="box">                                
                                <div class="box-body">
                                    <p class="lead text-center mid_caption">Course Fee</p>
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
                    $b_sql = "SELECT sc_course_type, sc_remarks, stu_gr_no,stu_first_name,stu_phone,stu_parent_mobile_no,stu_whatsappno,stu_email,stu_status,stu_id,stu_middle_name,stu_last_name,sc_half_course, brt_name,be_name,be_name_for,co_name, sc_joined_date, sc_total_fee, sc_total_paid, sc_discount_amount,sc_id,sc_br_id,sc_brt_id ,sc_co_id,sc_be_id  
                                   FROM sm_student INNER JOIN sm_student_course ON (sc_stu_id = stu_id) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   
                                   WHERE sc_total_fee > (sc_total_paid + sc_discount_amount) AND sc_br_id= " . $tmp_admin_id ;

                    if ($stu_first_name !='')
                    {    $b_sql .= " AND stu_first_name like '%".$stu_first_name."%'"; }
                    if ($stu_last_name !='')
                    {    $b_sql .= " AND stu_last_name like '%".$stu_last_name."%'"; }
                    if ($stu_gr_no !='')
                    {    $b_sql .= " AND stu_gr_no like '%".$stu_gr_no."%'"; }


                    $b_sql .=  " order by stu_gr_no ASC ";

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
                                                        <td><?php echo "T: " . ($b_db_row["sc_total_fee"]) . "<br>P: " . $b_db_row["sc_total_paid"] . "<br><span style='color:red;'>R. " . ($b_db_row["sc_total_fee"] - $b_db_row["sc_total_paid"]- $b_db_row["sc_discount_amount"]) . '</span><br><span class=c_"'.str_replace(" ","_",$b_db_row["sc_course_type"]).'">'.$b_db_row["sc_course_type"]; ?></span></td>
                                                        <td><?php echo nl2br($b_db_row['sc_remarks']); ?></td>
                                                        <td>
                                                            <a href="javascript:void(0)"  
                                                        data-name="<?=$b_db_row["stu_first_name"].' '.$b_db_row["stu_last_name"]?>"
                                                        data-email="<?=$b_db_row["stu_email"]?>"
                                                        data-mobile="<?=$b_db_row["stu_phone"].','.$b_db_row['stu_parent_mobile_no']?>"
                                                         onclick="open_inquiry_modal(this)">Reminder</a>

                                                            <!-- <a href="<?php echo $contact_page_url;?>" target="_blank" >Reminder</a> --><br>

                                                            <a id="pay_button_<?php echo $b_db_row['sc_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="pay_fee_student(<?php echo $b_db_row['stu_id']; ?>,<?php echo $b_db_row['sc_id']; ?>,<?php echo $b_db_row['sc_br_id']; ?>,<?php echo $b_db_row['sc_brt_id']; ?>,<?php echo $b_db_row['sc_co_id']; ?>,<?php echo $b_db_row['sc_be_id']; ?>,'Pay Student Fee',<?php echo $b_db_row['sc_total_fee']; ?>,<?php echo $b_db_row['sc_total_paid']; ?>,'<?php echo $b_db_row['stu_first_name'].'  '.$b_db_row['stu_middle_name'].' '.$b_db_row['stu_last_name']; ?>',<?php echo $b_db_row['sc_discount_amount']; ?>);">Pay Fee</a></td>
                                                        
        <?php
        }
    } else {
        ?>
                                                    <tr>

                                                        <td colspan="8" class="text-center" >No records found</td>

                                                    </tr>
    <?php }
}
?>
                                    </table>
                                </div>
                            
                        </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
             
                            <div class="box">                                
                                <div class="box-body">
                                    <p class="lead text-center mid_caption">Exam Fee</p>
                                    <table class="table table-bordered" id="print_me_exam">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>GR. No.<br>Name</th>
                                            <th>Contact</th>
                                            <th>Details</th>
                                            <th>E. Date</th>
                                            <th>Amount</th>
                  <th>Action</th>
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
               WHERE  stu_br_id= " . $tmp_admin_id . " AND exs_already_paid = 'N' AND exs_paid = 0 ";

if ($stu_first_name !='')
{    $b_sql .= " AND stu_first_name like '%".$stu_first_name."%'"; }
if ($stu_last_name !='')
{    $b_sql .= " AND stu_last_name like '%".$stu_last_name."%'"; }
if ($stu_gr_no !='')
{    $b_sql .= " AND stu_gr_no like '%".$stu_gr_no."%'"; }


$b_sql .=  " order by stu_gr_no ASC ";



$b_result = m_process("get_data", $b_sql);

if ($b_result['errormsg'] != '') {
    echo $b_result['errormsg'];
} else {
    if ($b_result['count'] > 0) {
        $sr = 0;
        foreach ($b_result['res'] as $b_db_row) {
               // echo "<pre>";
              //print_r($b_db_row);
              ///exit;
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
            <td>
                <a href="javascript:void(0)"  
                        data-name="<?=$b_db_row["stu_first_name"].' '.$b_db_row["stu_last_name"]?>"
                        data-email="<?=$b_db_row["stu_email"]?>"
                        data-mobile="<?=$b_db_row["stu_phone"].','.$b_db_row['stu_parent_mobile_no']?>"
                         onclick="open_inquiry_modal(this)">Reminder</a>
                <!-- <a href="<?php echo $contact_page_url;?>" target="_blank">Reminder</a> -->
                <br>


                   <a id="pay_button_<?php echo $b_db_row['exs_id']; ?>"  href="javascript:void(0);" class="text-info" onclick="pay_fee_student_exam(<?php echo $b_db_row['stu_id']; ?>,<?php echo $b_db_row['exs_id']; ?>,<?php echo $b_db_row['stu_br_id']; ?>,<?php echo $b_db_row['exs_fee']; ?>,<?php echo $b_db_row['exs_paid']; ?>, 'Pay Student Exam Fee', '<?php echo $b_db_row['stu_first_name'] . '  ' . $b_db_row['stu_middle_name'] . ' ' . $b_db_row['stu_last_name']; ?>');">Pay Fee</a>




            </td>
        <?php
        }
    } else {
        ?>
                                                    <tr>

                                                        <td colspan="5" class="text-center" >No records found</td>

                                                    </tr>
                                                <?php }
                                            }
                                            ?>
                                    </table>

                                
                            </div>

                        </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
           
                            <div class="box">                                
                                <div class="box-body">
                                    <p class="lead text-center mid_caption">Event Fee</p>
                                    <table class="table table-bordered" id="print_me_event_fee">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>GR. No.<br>Name</th>
                                            <th>Contact</th>
                                            <th>Details</th>
                                            <th>C. Date</th>
                                            <th>Amount</th>
                  <th>Action</th>
                                        </tr>
<?php
// $bi_issue_date_valid = convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BIRTHDAY_PERIOD)));
$b_sql = "SELECT  DISTINCT ev_name, ev_date, stu_parent_mobile_no,stu_whatsappno,stu_email, evs_fee, stu_br_id, stu_id, evs_ev_id,  evs_result_status,evs_result_marks,evs_enroll_next,  evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name, brt_name,be_name_for be_name, be_name_for,co_name, 'student' as stu_or_other,evs_stu_or_other,evs_total_paid,evs_fee,evs_discount_amount   FROM  sm_student
LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1 )
LEFT JOIN sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_branch_type
ON (sc_brt_id = brt_id )
LEFT JOIN sm_course ON ( co_id = sc_co_id) 
LEFT JOIN  sm_event_student_entrolled ON (evs_stu_or_other = 'student'  AND stu_id = evs_stu_id) INNER JOIN sm_event ON (evs_ev_id = ev_id)  WHERE evs_paid = 0 AND evs_fee !=0 AND   stu_br_id= " . $tmp_admin_id ; 

if ($stu_first_name !='')
{    $b_sql .= " AND stu_first_name like '%".$stu_first_name."%'"; }
if ($stu_last_name !='')
{    $b_sql .= " AND stu_last_name like '%".$stu_last_name."%'"; }
if ($stu_gr_no !='')
{    $b_sql .= " AND stu_gr_no like '%".$stu_gr_no."%'"; }


$b_sql .=" UNION SELECT  DISTINCT  ev_name, ev_date, stu_parent_mobile_no,stu_whatsappno,stu_email, evs_fee, stu_br_id, stu_id, evs_ev_id, evs_result_status,evs_result_marks,evs_enroll_next,evs_fee,evs_paid,  IF(evs_id IS NULL,0,evs_id) as evs_id,  stu_gr_no,stu_first_name,stu_phone,stu_email,stu_status, stu_id,stu_middle_name,stu_last_name , '' brt_name, '' be_name, '' be_name_for,'' co_name, 'other' as stu_or_other,evs_stu_or_other,evs_total_paid,evs_fee,evs_discount_amount  FROM  sm_student_other  LEFT JOIN  sm_event_student_entrolled ON (evs_stu_or_other = 'other' AND stu_id = evs_stu_id ) INNER JOIN sm_event ON (evs_ev_id = ev_id)  WHERE evs_paid = 0 AND evs_fee !=0 AND  stu_br_id= " . $tmp_admin_id  ;
if ($stu_first_name !='')
{    $b_sql .= " AND stu_first_name like '%".$stu_first_name."%'"; }
if ($stu_last_name !='')
{    $b_sql .= " AND stu_last_name like '%".$stu_last_name."%'"; }
if ($stu_gr_no !='')
{    $b_sql .= " AND stu_gr_no like '%".$stu_gr_no."%'"; }



$b_sql .=  " order by stu_gr_no ASC ";
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
                                                        <td>
                                                            <a href="javascript:void(0)"  
                                                                    data-name="<?=$b_db_row["stu_first_name"].' '.$b_db_row["stu_last_name"]?>"
                                                                    data-email="<?=$b_db_row["stu_email"]?>"
                                                                    data-mobile="<?=$b_db_row["stu_phone"].','.$b_db_row['stu_parent_mobile_no']?>"
                                                                     onclick="open_inquiry_modal(this)">Reminder</a>
                                                            <!-- <a href="<?php echo $contact_page_url;?>" target="_blank">Reminder</a> -->
                                                            <br>


                                                        

                                                              <a id="pay_button_<?php echo $b_db_row['evs_id']; ?>"
                                                            href="javascript:void(0);" class="text-info"
                                                            onclick="pay_fee_student_event('<?php echo $b_db_row['evs_stu_or_other']?>',<?php echo $b_db_row['stu_id']; ?>,<?php echo $b_db_row['evs_id']; ?>,<?php echo $b_db_row['stu_br_id']; ?>,<?php echo $b_db_row['evs_fee']; ?>,<?php echo $b_db_row['evs_total_paid']; ?>, 'Pay Student Event Fee', '<?php echo $b_db_row['stu_first_name'] . '  ' . $b_db_row['stu_middle_name'] . ' ' . $b_db_row['stu_last_name']; ?>','<?php echo $b_db_row['evs_discount_amount']; ?>');">Pay
                                                            Event Fee</a></td>
        <?php
        }
    } else {
        ?>
                                                    <tr>

                                                        <td colspan="7" class="text-center" >No records found</td>

                                                    </tr>
    <?php }
}
?>
                                    </table>
                                </div>
                            </div>

              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        </div>
        <!-- /.col -->
                    
                </section>
            </div>
<?php include("includes/return-reissue-book.php"); ?>
<?php include("includes/footer.php"); ?>
<style>
    #pay_fee_student .modal-dialog {
        width: 700px;
    }
    </style>
    <div class="modal" id="pay_fee_student">
      <input type="hidden" id="d_stu_id" name="d_stu_id" />
     <input type="hidden" id="d_exs_id" name="d_exs_id" />
     <input type="hidden" id="d_stu_br_id" name="d_stu_br_id" />
     <input type="hidden" id="d_exs_fee" name="d_exs_fee" />
     <input type="hidden" id="d_exs_paid" name="d_exs_paid" />
     <input type="hidden" id="d_evs_id" name="d_evs_id" />
    <input type="hidden" id="d_sc_id" name="d_sc_id" />
    <input type="hidden" id="d_sc_br_id" name="d_sc_br_id" />
    <input type="hidden" id="d_sc_brt_id" name="d_sc_brt_id" />
    <input type="hidden" id="d_sc_co_id" name="d_sc_co_id" />
    <input type="hidden" id="d_sc_be_id" name="d_sc_be_id" />
    <input type="hidden" id="d_evs_stu_or_other" name="d_evs_stu_or_other" />
    <input type="hidden" id="d_max_pending_pay" name="d_max_pending_pay" />
    <input type="hidden" id="d_max_pending_pay" name="d_max_pending_pay" />
    <input type="hidden" id="d_max_pending_pay" name="d_max_pending_pay" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pay Fees [<span id="d_student_name"></span>] <a href="javascript:void(0);"
                        onclick="fresh_receipt();">New</a></h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th align="center" width="70px">Receipt</th>
                                    <th align="center">Mode</th>
                                    <th align="center">Bank</th>
                                    <th align="center">T. No.</th>
                                    <th align="center">Amount</th>
                                    <th align="center">Discount</th>
                                    <th align="center">Date</th>
                                    <th align="center">Remarks</th>
                                    <th align="center" class="t_align_center" width="120px">Action</th>
                                </tr>
                            </thead>
                            <tbody id="course_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end of showing details -->
                <!-- start of code -->
                <div class="box box-info">
                    <div class="box-body">
                        <div class=" col-md-12">
                            <div class="model-course-help">

                                <label class="col-sm-3 control-label">Total Fee</label>
                                <div class="col-sm-9">
                                    <span id="v_amt_topay"></span>
                                </div>
                            </div>
                            <div class="model-course-help">

                                <label class="col-sm-3 control-label">Paid Fee</label>
                                <div class="col-sm-9">
                                    <span id="v_amt_paid"></span>
                                </div>
                            </div>
                            <div class="model-course-help discount_section">

                                <label class="col-sm-3 control-label">Discount </label>
                                <div class="col-sm-9">
                                    <span id="v_amt_discount"></span>
                                </div>
                            </div>
                            <div class="model-course-help">

                                <label class="col-sm-3 control-label">Pending Fee </label>
                                <div class="col-sm-9">
                                    <span id="v_amt_topending"></span>
                                </div>
                            </div>
                            <div class="model-course-help hide">
                                <label class="col-sm-3 control-label">Receipt No.</label>
                                <div class="col-sm-9">
                                    <input type="text" name="pt_receipt_no" id="pt_receipt_no" placeholder="Receipt No."
                                        value="" class="form-control" />
                                </div>
                            </div>
                            <div class="model-course-help">

                                <label class="col-sm-3 control-label">Account</label>
                                <div class="col-sm-9">
                                    <select required id="pt_ac_id" name="pt_ac_id" class="form-control" onchange="getpaymentmodevalue();">
                                        <option value="0">--Please select--</option>
                                        <option value="1">Martial art academy - Cash</option> 
                                        <option value="2">Indian Bank - 6490380758</option>                                   </select>
                                </div>
                                </div>
                                <div class="model-course-help">

                                    <label class="col-sm-3 control-label">Payment Mode</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" id="pt_id" name="pt_id" />
                                        <select required id="pt_tran_mode_of_payent" onchange="change_payment_mode();"
                                            name="pt_tran_mode_of_payent" class="form-control">
                                            <option value="">--Please select--</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Net Banking">Net Banking</option>
                                            <option value="DD">DD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Tran. Date</label>
                                    <div class="col-sm-9">
                                        <input type="text"  name="pt_tran_date" id="pt_tran_date"
                                            placeholder="Payment Date" value="<?=date('d-m-Y')?>"
                                            class="form-control" />
                                    </div>
                                </div>
                                <div id="transaction_no" style="display:none;">
                                    <div class="model-course-help">
                                        <label class="col-sm-3 control-label">Txn. No</label>
                                        <div class="col-sm-9">
                                            <input type="text" required id="pt_tran_no" name="pt_tran_no"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div id="tran_bank" style="display:none;">
                                    <div class="model-course-help">
                                        <label class="col-sm-3 control-label">Bank</label>
                                        <div class="col-sm-9">
                                            <input type="text" required id="pt_tran_bank" name="pt_tran_bank"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="model-course-help amount_section" >
                                    <label class="col-sm-3 control-label">Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" required id="pt_tran_amount" name="pt_tran_amount"
                                            class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help discount_section">
                                    <label class="col-sm-3 control-label">Discount</label>
                                    <div class="col-sm-9">
                                        <input type="text" required id="pt_discount_amount" name="pt_discount_amount"
                                            class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Remarks</label>
                                    <div class="col-sm-9">
                                        <textarea id="pt_tran_remarks" name="pt_tran_remarks"
                                            class="form-control"></textarea>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- end of code -->
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="pay_fee_student_exam_ajax" id="action_popup" />
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" onclick="submit();" class="btn btn-primary confirm-class">Pay
                        Fee</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal inquiry-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Inquiry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_user();">
                <input type="hidden" id="act" name="act" value="inquiry_form">
                <input type="hidden" id="id" name="id" value="0">
                <input type="hidden" id="con_br_id" name="con_br_id" value="1">
            <div class="box-body">
                <div class=" col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input required="" type="text" name="con_name" id="con_name" placeholder="Contact Name" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="text" name="con_email" id="con_email" placeholder="Contact Email" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Phone</label>
                        <div class="col-sm-9">
                            <input required="" type="text" name="con_phone" id="con_phone" placeholder="Contact Phone" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Message</label>
                        <div class="col-sm-9">
                            <textarea name="con_message" id="con_message" placeholder="Contact Message" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Date</label>
                        <div class="col-sm-9">
                        <input type="text" name="con_date" id="con_date" placeholder="Contact Date" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Followup Date</label>
                        <div class="col-sm-9">
                        <input type="text" name="con_followup_date" id="con_followup_date" placeholder="Contact Followup Date" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <select id="con_status" name="con_status" class="form-control">
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                            <option value="Discussion">Discussion</option>
                            </select>
                            <input type="hidden" name="con_followup_type" value="Document"> 
                        </div>
                    </div>
                </div>
            </div><!-- /.box -->
            <div class="box-footer">
              <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset">                                         
              <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
            </div><!-- /.box-footer -->
        </form>
      </div>
    </div>
  </div>
</div> 

<script type="text/javascript">
    function loadDatePicker(){
        $("#pt_tran_date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true, 
        });
    }
    
   
    function exportToPdf()
    {
        var activeTabLink=getActiveTabId();
       // alert(activeTabLink);
     switch(activeTabLink)
     {
        case 'tab_1_link':
            print_attendance('print_me', '<?php echo $arr_branch_details["name"]; ?>','course_pending_fee' , '');
        break;
        case 'tab_2_link':
            print_attendance('print_me_exam', '<?php echo $arr_branch_details["name"]; ?>','exam_pending_fee' , '');
        break;
        case 'tab_3_link':
            print_attendance('print_me_event_fee', '<?php echo $arr_branch_details["name"]; ?>','event_pending_fee' , '');
        break;
     }
     
     
}
function getActiveTabId() {
     //var activeTabLink = document.querySelector('.nav-tabs li.active a');
     return $('.nav-tabs li.active a').attr('id');
     //alert(activeTabLink);
    }
function exportToExcel() {
            
    
            var activeTabLink=getActiveTabId();
            
            switch(activeTabLink)
            {
                case 'tab_1_link':
                    var excelData = '<table border="1">';
                    var tableHeaders = document.querySelectorAll('#print_me tr th');
                    excelData += '<tr>';
                    tableHeaders.forEach(function (header) {
                        if (header.textContent !== "Action") { 
                            excelData += '<th>' + header.textContent + '</th>';
                        }
                    });
                    excelData += '</tr>';
    
                    var tableRows = document.querySelectorAll('#print_me tr');
                    tableRows.forEach(function (row) {
                        excelData += '<tr>';
                        var rowCells = row.querySelectorAll('td');
                        rowCells.forEach(function (cell, index) {
                            if (index !== (rowCells.length - 1)) {
                                excelData += '<td>' + cell.textContent + '</td>';
                            }       
                        });
                        excelData += '</tr>';
                    });
    
                    excelData += '</table>';
                    var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'Pending Course Fee.xls';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                break;

                case 'tab_2_link':
                    var excelData = '<table border="1">';
                    var tableHeaders = document.querySelectorAll('#print_me_exam tr th');
                    excelData += '<tr>';
                    tableHeaders.forEach(function (header) {
                        if (header.textContent !== "Action") { 
                            excelData += '<th>' + header.textContent + '</th>';
                        }
                    });
                    excelData += '</tr>';
    
                    var tableRows = document.querySelectorAll('#print_me_exam tr');
                    tableRows.forEach(function (row) {
                        excelData += '<tr>';
                        var rowCells = row.querySelectorAll('td');
                        rowCells.forEach(function (cell, index) {
                            if (index !== (rowCells.length - 1)) {
                                excelData += '<td>' + cell.textContent + '</td>';
                            }       
                        });
                        excelData += '</tr>';
                    });
    
                    excelData += '</table>';
                    var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'Pending Exam Fee.xls';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                break;

                case 'tab_3_link':
                    var excelData = '<table border="1">';
                    var tableHeaders = document.querySelectorAll('#print_me_event_fee tr th');
                    excelData += '<tr>';
                    tableHeaders.forEach(function (header) {
                        if (header.textContent !== "Action") { 
                            excelData += '<th>' + header.textContent + '</th>';
                        }
                    });
                    excelData += '</tr>';
    
                    var tableRows = document.querySelectorAll('#print_me_event_fee tr');
                    tableRows.forEach(function (row) {
                        excelData += '<tr>';
                        var rowCells = row.querySelectorAll('td');
                        rowCells.forEach(function (cell, index) {
                            if (index !== (rowCells.length - 1)) {
                                excelData += '<td>' + cell.textContent + '</td>';
                            }       
                        });
                        excelData += '</tr>';
                    });
    
                    excelData += '</table>';
                    var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'Pending Event Fee.xls';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                break;
            }
        }
    
    $(document).ready(function(){
        loadDatePicker();
    });

    

    function open_inquiry_modal(element) {
        $("#form1 #con_name").val($(element).attr("data-name"));
        $("#form1 #con_email").val($(element).attr("data-email"));
        $("#form1 #con_phone").val($(element).attr("data-mobile"));                
        $(".inquiry-modal").modal("show");
        $("#con_date,#con_followup_date").datepicker({format: 'dd-mm-yyyy',autoclose: true});
    }

    function submit(){
        if($("#action_popup").val()=="pay_fee_student_exam_ajax"){
            return pay_fee_student_exam_ajax();
        }
        else if($("#action_popup").val()=="pay_fee_student_event_ajax"){
            return pay_fee_student_event_ajax();
        }        
        else {
            return pay_fee_student_ajax();
        }
    }
    function getpaymentmodevalue() {
    
    
    var accountDropdown = document.getElementById("pt_ac_id");
    
           var paymentModeDropdown = document.getElementById("pt_tran_mode_of_payent");
           
           // Define the options for each account
           var accountOptions = {
               0: [],          // Default option when no account is selected
               1: ["--Please Select--","Cash"],    // Options when Account 1 is selected
               2: ["--Please Select--","Cheque","Net Banking","DD"],  // Options when Account 2 is selected
               // Add more options for other account values as needed
           };
   
           // Function to update Payment Mode dropdown based on selected Account
           function updatePaymentModeOptions() {
               var selectedAccount = accountDropdown.value;
               var options = accountOptions[selectedAccount] || [];
   
               // Clear existing options
               paymentModeDropdown.innerHTML = "";
   
               // Add new options
               options.forEach(function(option) {
                   var optionElement = document.createElement("option");
                   optionElement.value = option;
                   optionElement.text = option;
                   paymentModeDropdown.appendChild(optionElement);
               });
           }
   
           // Add an event listener to the Account dropdown
           accountDropdown.addEventListener("change", updatePaymentModeOptions);
   
           // Initially, populate Payment Mode options based on the default selected Account
           updatePaymentModeOptions();
       };
 function change_payment_mode() {
        if ($("#pt_tran_mode_of_payent").val() != 'Cash' && $("#pt_tran_mode_of_payent").val() != '--Please Select--' ) {
            $("#transaction_no").show();
            $("#pt_tran_no").val('');
            $("#tran_bank").show();
            $("#pt_tran_bank").val('');
        } else {
            $("#transaction_no").hide();
            $("#tran_bank").hide();
        }
    }
    function pay_fee_student_ajax() {

        var d_max_pending_pay = parseInt($("#d_max_pending_pay").val());
        console.log("--" + $("#pt_tran_amount").val() + "--" + d_max_pending_pay + '--');
        //             else if ( ($("#pt_id").val() != '' || $("#pt_id").val()>=0) && ( parseInt($("#pt_tran_amount").val()) > (d_max_pending_pay-parseInt($("#pt_tran_amount").val())-parseInt($("#pt_discount_amount").val()) ) )

        if (($("#pt_id").val() == '' || $("#pt_id").val() == 0) && parseInt($("#pt_tran_amount").val()) >
            d_max_pending_pay) {
            console.log('payable amount 0');
            alert("maximum payable amoun is " + d_max_pending_pay + " INR");
            return false;
        }

        if (($("#pt_id").val() == '' || $("#pt_id").val() == 0) && d_max_pending_pay == 0) {
            alert("payment amount should be more than zero");
            return false;
        }
        if ((parseInt($("#pt_tran_amount").val()) + parseInt($("#pt_discount_amount").val())) <= 0) {
            alert("Invalid Transaction amount.");
            return false;
        }

        if ($("#pt_tran_date").val() == '') {
            alert("Please select transaction date");
            return false;
        }


        if ($("#pt_tran_mode_of_payent").val() == '') {
            alert("Please select payment option");
            return false;
        }
        if ($("#pt_tran_mode_of_payent").val() != 'Cash' && ($("#pt_tran_no").val() == '' || $("#pt_tran_bank").val() ==
                '')) {
            alert("Please fill missing details like as bank, tranction no");
            return false;
        }

        var ans_v = confirm("Are you sure you would like to process?");
        if (ans_v == true) {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    action: "pay_fee_student_ajax",
                    pt_id: $("#pt_id").val(),
                    pt_receipt_no: $("#pt_receipt_no").val(),
                    pt_tran_bank: $("#pt_tran_bank").val(),
                    pt_tran_no: $("#pt_tran_no").val(),
                    pt_tran_mode_of_payent: $("#pt_tran_mode_of_payent").val(),
                    pt_tran_bank: $("#pt_tran_bank").val(),
                    pt_tran_amount: $("#pt_tran_amount").val(),
                    pt_tran_date: $("#pt_tran_date").val(),
                    pt_tran_remarks: $("#pt_tran_remarks").val(),
                    d_stu_id: $("#d_stu_id").val(),
                    d_sc_id: $("#d_sc_id").val(),
                    d_sc_br_id: $("#d_sc_br_id").val(),
                    d_sc_brt_id: $("#d_sc_brt_id").val(),
                    d_sc_co_id: $("#d_sc_co_id").val(),
                    d_sc_be_id: $("#d_sc_be_id").val(),
                    pt_ac_id: $("#pt_ac_id").val(),
                    pt_discount_amount: $("#pt_discount_amount").val()
                },
                success: function(result) {
                    result = $.trim(result);

                    var objResponse = jQuery.parseJSON(result);
                    if (objResponse.status == 'success') {
                        alert("Fees has been paid successfully.");
                        $('#pay_fee_student').modal('hide');

                        $('#pay_button_' + $("#d_sc_id").val()).remove();
                        $('#form1').submit();
                    } else {
                        alert(objResponse.errormsg);
                    }
                }
            });
        }
    }


     function delete_student_fee(in_pt_id, in_pt_sc_id)
        {
            var ans = confirm("Are you sure, you would like to delete the fee?");
            if (ans == true)
            {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {action: "remove-student-existing-fees-details", pt_id: in_pt_id, pt_sc_id: in_pt_sc_id},
                    success: function (result) {
                        result = $.trim(result);

                        var objResponse = jQuery.parseJSON(result);
                        console.log(objResponse);
                        if (objResponse.status == 'success')
                        {
                            alert(objResponse.data);
                            //    $('#course_details').modal('hide');
                            $('#form1').submit();
                        }
                        else
                        {
                            alert(objResponse.errormsg);
                        }
                    }
                });
            }
        }
    function get_student_fees_payment_details(in_sc_id) {

        // $('#a_process_'+ei_id).remove();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "get-student-fees-payment-details",
                sc_id: in_sc_id
            },
            success: function(result) {
                result = $.trim(result);

                var objResponse = jQuery.parseJSON(result);
                console.log(objResponse);
                if (objResponse.status == 'success') {
                    // now bidning data to the table.
                    $("#course_tbody").html(objResponse.data);
                    //    $('#course_details').modal('hide');
                    //    $('#form1').submit();
                } else {
                    alert(objResponse.errormsg);
                }
            }
        });
    }
    function pay_fee_student(d_stu_id, d_sc_id, d_sc_br_id, d_sc_brt_id, d_sc_co_id, d_sc_be_id, d_title, amt_topay,
        amt_paid, d_student_name, d_amt_discount) {
        $(`.amount_section,.discount_section`).removeClass("hide");
        get_student_fees_payment_details(d_sc_id);
        $('#d_student_name').text(d_student_name);
        $('#v_amt_topay').text(amt_topay + ' INR');
        $('#v_amt_paid').text(amt_paid + ' INR');
        $('#v_amt_discount').text(d_amt_discount + ' INR');
        $('#v_amt_topending').text((amt_topay - amt_paid - d_amt_discount) + ' INR');
        $("#d_max_pending_pay").val(amt_topay - amt_paid - d_amt_discount);
        $('#pt_tran_amount').val(amt_topay - amt_paid - d_amt_discount);
        $('#pay_fee_student').modal('show');
        $('#action_popup').val('pay_fee_student_ajax');

        $("#transaction_no").hide();
        $("#tran_bank").hide();
        $("#pt_tran_no").val('');
        $("#pt_tran_bank").val('');

        $('#d_stu_id').val(d_stu_id);
        $('#d_sc_id').val(d_sc_id);
        $('#d_sc_br_id').val(d_sc_br_id);
        $('#d_sc_brt_id').val(d_sc_brt_id);
        $('#d_sc_co_id').val(d_sc_co_id);
        $('#d_sc_be_id').val(d_sc_be_id);

          $("#pt_tran_date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true, 
        });

    }
    function pay_fee_student_ajax() {

        var d_max_pending_pay = parseInt($("#d_max_pending_pay").val());
        if (($("#pt_id").val() == '' || $("#pt_id").val() == 0) && parseInt($("#pt_tran_amount").val()) >
            d_max_pending_pay) {
            console.log('payable amount 0');
            alert("maximum payable amoun is " + d_max_pending_pay + " INR");
            return false;
        }

        if (($("#pt_id").val() == '' || $("#pt_id").val() == 0) && d_max_pending_pay == 0) {
            alert("payment amount should be more than zero");
            return false;
        }
        if ((parseInt($("#pt_tran_amount").val()) + parseInt($("#pt_discount_amount").val())) <= 0) {
            alert("Invalid Transaction amount.");
            return false;
        }

        if ($("#pt_tran_date").val() == '') {
            alert("Please select transaction date");
            return false;
        }


        if ($("#pt_tran_mode_of_payent").val() == '') {
            alert("Please select payment option");
            return false;
        }
        if ($("#pt_tran_mode_of_payent").val() != 'Cash' && ($("#pt_tran_no").val() == '' || $("#pt_tran_bank").val() ==
                '')) {
            alert("Please fill missing details like as bank, tranction no");
            return false;
        }

        var ans_v = confirm("Are you sure you would like to process?");
        if (ans_v == true) {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    action: "pay_fee_student_ajax",
                    pt_id: $("#pt_id").val(),
                    pt_receipt_no: $("#pt_receipt_no").val(),
                    pt_tran_bank: $("#pt_tran_bank").val(),
                    pt_tran_no: $("#pt_tran_no").val(),
                    pt_tran_mode_of_payent: $("#pt_tran_mode_of_payent").val(),
                    pt_tran_bank: $("#pt_tran_bank").val(),
                    pt_tran_amount: $("#pt_tran_amount").val(),
                    pt_tran_date: $("#pt_tran_date").val(),
                    pt_tran_remarks: $("#pt_tran_remarks").val(),
                    d_stu_id: $("#d_stu_id").val(),
                    d_sc_id: $("#d_sc_id").val(),
                    d_sc_br_id: $("#d_sc_br_id").val(),
                    d_sc_brt_id: $("#d_sc_brt_id").val(),
                    d_sc_co_id: $("#d_sc_co_id").val(),
                    d_sc_be_id: $("#d_sc_be_id").val(),
                    pt_ac_id: $("#pt_ac_id").val(),
                    pt_discount_amount: $("#pt_discount_amount").val()
                },
                success: function(result) {
                    result = $.trim(result);

                    var objResponse = jQuery.parseJSON(result);
                    if (objResponse.status == 'success') {
                        alert("Fees has been paid successfully.");
                        $('#pay_fee_student').modal('hide');

                        $('#pay_button_' + $("#d_sc_id").val()).remove();
                        $('#form1').submit();
                    } else {
                        alert(objResponse.errormsg);
                    }
                }
            });
        }
    }

        function pay_fee_student_exam(d_stu_id, d_exs_id, d_stu_br_id, d_exs_fee, d_exs_paid, d_title, d_student_name) {
            loadDatePicker();   
            $(`.amount_section,.discount_section`).addClass("hide");
            $('#d_student_name').text(d_student_name);
            $('#v_amt_topay').text(d_exs_fee + ' INR');
            var amt_paid = 0;
            if (d_exs_paid == 1) {
                amt_paid = d_exs_fee;
            }
            $("#course_tbody").html("");

            $('#v_amt_paid').text(amt_paid + ' INR');
            $('#v_amt_topending').text((d_exs_fee - amt_paid) + ' INR');
            $("#d_max_pending_pay").val(d_exs_fee - amt_paid);
            $('#pt_tran_amount').val(d_exs_fee - amt_paid);
            $('#pay_fee_student').modal('show');

            $("#transaction_no").hide();
            $("#tran_bank").hide();
            $("#pt_tran_no").val('');
            $("#pt_tran_bank").val('');

            $('#d_stu_id').val(d_stu_id);
            $('#d_exs_id').val(d_exs_id);
            $('#d_stu_br_id').val(d_stu_br_id);
            $('#d_exs_fee').val(d_exs_fee);
            $('#d_exs_paid').val(d_exs_paid);
             $('#action_popup').val('pay_fee_student_exam_ajax');
        }


function pay_fee_student_exam_ajax()
{

var d_max_pending_pay = parseInt($("#d_max_pending_pay").val());
if ( parseInt($("#pt_tran_amount").val()) > d_max_pending_pay)
{
   alert("maximum payable amoun is "+d_max_pending_pay+" INR");
   return false;
}
if (d_max_pending_pay ==0)
{
  alert("payment amount should be more than zero");
  return false;
}
if (parseInt($("#pt_tran_amount").val())<=0)
{
   alert("Invalid Transaction amount.");
   return false;
}
if ($("#pt_tran_date").val()  == '' )
{
   alert("Please select transaction date");
   return false;
}


if ($("#pt_tran_mode_of_payent").val() =='' )
{
  alert("Please select paymente option");
  return false;
}
if ($("#pt_tran_mode_of_payent").val() !='Cash' && ( $("#pt_tran_no").val()=='' || $("#pt_tran_bank").val() == '') )
{
  alert("Please fill missing details like as bank, tranction no");
  return false;
}
var ans_v = confirm("Are you sure you would like to process?");
          if (ans_v == true)
          {

  $.ajax({
      type: "POST",
      url: "ajax.php",
     data: { action: "pay_fee_student_exam_ajax", pt_receipt_no : $("#pt_receipt_no").val(), pt_tran_bank : $("#pt_tran_bank").val(), pt_tran_no : $("#pt_tran_no").val(), pt_tran_mode_of_payent : $("#pt_tran_mode_of_payent").val(), pt_tran_bank : $("#pt_tran_bank").val(), pt_tran_amount : $("#pt_tran_amount").val(), pt_tran_date : $("#pt_tran_date").val(), pt_tran_remarks : $("#pt_tran_remarks").val()  , d_stu_id : $("#d_stu_id").val(), d_exs_id : $("#d_exs_id").val() , d_stu_br_id : $("#d_stu_br_id").val() } ,
     success: function (result) {
          result = $.trim(result);

          var objResponse = jQuery.parseJSON(result);
          if (objResponse.status =='success')
          {
              alert("Fees has been paid successfully.");
              $('#form_enrollment').submit();
              $('#pay_fee_student').modal('hide');
              $("#act").val("act");
              $("#pay_fee").val(1);
              window.location.reload();
              
          }
          else
          {
            alert(objResponse.errormsg);
          }
      }
  });
  }
}


function get_event_fees_payment_details(in_evs_stu_or_other, in_evs_id) {

    // $('#a_process_'+ei_id).remove();
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: "get-event-fees-payment-details",
            evs_id: in_evs_id,
            evs_stu_or_other: in_evs_stu_or_other
        },
        success: function(result) {
            result = $.trim(result);

            var objResponse = jQuery.parseJSON(result);
            console.log(objResponse);
            if (objResponse.status == 'success') {
                // now bidning data to the table.
                $("#course_tbody").html(objResponse.data);
                //    $('#course_details').modal('hide');
                //    $('#form1').submit();
            } else {
                alert(objResponse.errormsg);
            }
        }
    });
}

function pay_fee_student_event(d_evs_stu_or_other, d_stu_id, d_evs_id, d_stu_br_id, d_exs_fee, d_exs_paid, d_title,
    d_student_name, d_amt_discount) {
    // console.log(d_evs_stu_or_other,"d_evs_stu_or_other");
    // console.log(d_stu_id,"d_stu_id");
    // console.log(d_evs_id,"d_evs_id");
    // console.log(d_stu_br_id,"d_stu_br_id");
    // console.log(d_exs_fee,"d_exs_fee");
    // console.log(d_exs_paid,"d_exs_paid");
    // console.log(d_title,"d_title");
    // console.log(d_student_name,"d_student_name");
    // $(`.amount_section,.discount_section`).removeClass("hide");
    // $("#course_tbody").html("");
    // get_event_fees_payment_details(d_evs_stu_or_other, d_evs_id);
    // $('#action_popup').val('pay_fee_student_event_ajax');
    // $('#d_student_name').text(d_student_name);
    // $('#v_amt_topay').text(d_exs_fee + ' INR');
    // var amt_paid = 0;
    // if (d_exs_paid == 1) {
    //     amt_paid = d_exs_fee;
    // }

    // $('#v_amt_paid').text(amt_paid + ' INR');
    // $('#v_amt_topending').text((d_exs_fee - amt_paid) + ' INR');
    // $("#d_max_pending_pay").val(d_exs_fee - amt_paid);
    // $('#pt_tran_amount').val(d_exs_fee - amt_paid);
    // $('#d_evs_stu_or_other').val(d_evs_stu_or_other);

    // $('#pay_fee_student').modal('show');

    // $("#transaction_no").hide();
    // $("#tran_bank").hide();
    // $("#pt_tran_no").val('');
    // $("#pt_tran_bank").val('');

    // $('#d_stu_id').val(d_stu_id);
    // $('#d_evs_id').val(d_evs_id);
    // $('#d_stu_br_id').val(d_stu_br_id);
    // $('#d_exs_fee').val(d_exs_fee);
    // $('#d_exs_paid').val(d_exs_paid);
    // debugger;
 $('#action_popup').val('pay_fee_student_event_ajax');
    $(`.amount_section,.discount_section`).removeClass("hide");
    get_event_fees_payment_details(d_evs_stu_or_other, d_evs_id);
    $('#d_student_name').text(d_student_name);
    $('#v_amt_topay').text(d_exs_fee + ' INR');
    $('#v_amt_discount').text(d_amt_discount + ' INR');
    $('#v_amt_paid').text(d_exs_paid + ' INR');
    $('#v_amt_topending').text((d_exs_fee - d_exs_paid - d_amt_discount) + ' INR');
    $("#d_max_pending_pay").val(d_exs_fee - d_exs_paid - d_amt_discount);
    $('#pt_tran_amount').val(d_exs_fee - d_exs_paid - d_amt_discount);
    $('#d_evs_stu_or_other').val(d_evs_stu_or_other);

    $('#pay_fee_student').modal('show');

    $("#transaction_no").hide();
    $("#tran_bank").hide();
    $("#pt_tran_no").val('');
    $("#pt_tran_bank").val('');

    $('#d_stu_id').val(d_stu_id);
    $('#d_evs_id').val(d_evs_id);
    $('#d_stu_br_id').val(d_stu_br_id);
    $('#d_exs_fee').val(d_exs_fee);
    $('#d_exs_paid').val(d_exs_paid);
}

function pay_fee_student_event_ajax() {
    var d_max_pending_pay = parseInt($("#d_max_pending_pay").val());
    console.log("--" + $("#pt_tran_amount").val() + "--" + d_max_pending_pay + '--');
    // else if ( ($("#pt_id").val() != '' || $("#pt_id").val()>=0) && (parseInt($("#pt_tran_amount").val()) > (d_max_pending_pay-parseInt($("#pt_tran_amount").val())-parseInt($("#pt_discount_amount").val()))))
    if (($("#pt_id").val() == '' || $("#pt_id").val() == 0) && (parseInt($("#pt_tran_amount").val()) >
            d_max_pending_pay)) {
        console.log('payable amount e0');
        alert("maximum payable amoun is " + d_max_pending_pay + " INR");
        return false;
    } else if ($("#pt_id").val() != '' || $("#pt_id").val() >= 0) {
        // console.log('total valuation'+ (d_max_pending_pay-parseInt($("#pt_tran_amount").val())-parseInt($("#pt_discount_amount").val())));
        // console.log('payable amount e1'+d_max_pending_pay +' 1 ' +parseInt($("#pt_tran_amount").val())+' 2 '+parseInt($("#pt_discount_amount").val()));

        // alert("maximum payable amoun is " + d_max_pending_pay + " INR");
        // return false;
    }
    if (($("#pt_id").val() == '' || $("#pt_id").val() == 0) && d_max_pending_pay == 0) {
        alert("payment amount should be more than zero");
        return false;
    }
    if ((parseInt($("#pt_tran_amount").val()) + parseInt($("#pt_discount_amount").val())) <= 0) {
        alert("Invalid Transaction amount.");
        return false;
    }
    if ($("#pt_tran_date").val() == '') {
        alert("Please select transaction date");
        return false;
    }



    if ($("#pt_tran_mode_of_payent").val() == '') {
        alert("Please select payment option");
        return false;
    }
    if ($("#pt_tran_mode_of_payent").val() != 'Cash' && ($("#pt_tran_no").val() == '' || $("#pt_tran_bank").val() ==
        '')) {
        alert("Please fill missing details like as bank, tranction no");
        return false;
    }
    var ans_v = confirm("Are you sure you would like to process?");
    if (ans_v == true) {

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "pay_fee_student_event_ajax",
                pt_id: $("#pt_id").val(),
                d_evs_stu_or_other: $("#d_evs_stu_or_other").val(),
                pt_receipt_no: $("#pt_receipt_no").val(),
                pt_tran_bank: $("#pt_tran_bank").val(),
                pt_tran_no: $("#pt_tran_no").val(),
                pt_tran_mode_of_payent: $("#pt_tran_mode_of_payent").val(),
                pt_ac_id: $("#pt_ac_id").val(),
                pt_tran_bank: $("#pt_tran_bank").val(),
                pt_tran_amount: $("#pt_tran_amount").val(),
                pt_tran_date: $("#pt_tran_date").val(),
                pt_tran_remarks: $("#pt_tran_remarks").val(),
                d_stu_id: $("#d_stu_id").val(),
                d_evs_id: $("#d_evs_id").val(),
                d_stu_br_id: $("#d_stu_br_id").val(),
                pt_discount_amount: $("#pt_discount_amount").val()
            },
            success: function(result) {
                result = $.trim(result);    
                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success') {
                    alert("Fees has been paid successfully.");
                    window.location.reload();
                    $('#pay_fee_student').modal('hide');
                    $("#act").val("act");
                    $("#pay_fee").val(1);
                    $('#form_enrollment').submit();
                } else {
                    alert(objResponse.errormsg);
                }
            }
        });
    }
}


function delete_event_fee(in_pt_id, in_pt_sc_id, in_pt_tran_u_type) {
    var ans = confirm("Are you sure, you would like to delete the fee?");
    if (ans == true) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "remove-event-existing-fees-details",
                pt_id: in_pt_id,
                pt_sc_id: in_pt_sc_id,
                pt_tran_u_type: in_pt_tran_u_type
            },
            success: function(result) {
                result = $.trim(result);

                var objResponse = jQuery.parseJSON(result);
                console.log(objResponse);
                if (objResponse.status == 'success') {
                    alert(objResponse.data);
                    //    $('#course_details').modal('hide');
                    $('#form1').submit();
                } else {
                    alert(objResponse.errormsg);
                }
            }
        });
    }
}


function get_event_fees_payment_details_for_edit(in_type, in_pt_id, in_pt_sc_id) {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: "get-event-fees-payment-details-for-edit",
            pt_id: in_pt_id,
            pt_sc_id: in_pt_sc_id,
            type: in_type
        },
        success: function(result) {
            result = $.trim(result);

            var objResponse = jQuery.parseJSON(result);
            console.log(objResponse);
            if (objResponse.status == 'success') {
                $("#pt_tran_mode_of_payent").val(objResponse.data.pt_tran_mode_of_payent);
                $("#pt_tran_amount").val(objResponse.data.pt_tran_amount);
                $("#pt_tran_date").val(objResponse.data.pt_tran_date);
                $("#pt_ac_id").val(objResponse.data.pt_ac_id);
                $("#pt_tran_remarks").val(objResponse.data.pt_tran_remarks);
                $("#pt_tran_no").val(objResponse.data.pt_tran_no);
                $("#pt_tran_bank").val(objResponse.data.pt_tran_bank);
                $("#pt_id").val(objResponse.data.pt_id);
                $("#pt_discount_amount").val(objResponse.data.pt_discount_amount);
                if (objResponse.data.pt_tran_mode_of_payent != 'Cash') {
                    $("#transaction_no").show();
                    $("#tran_bank").show();
                } else {
                    $("#transaction_no").hide();
                    $("#tran_bank").hide();
                }

                // if found then we need to show and hide respective section.
            } else {
                alert(objResponse.errormsg);
            }
        }
    });
}

</script>


        </div>
    </body>
</html>
