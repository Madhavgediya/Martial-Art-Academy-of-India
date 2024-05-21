<?php
include("includes/application_top.php");

$id= get_rdata("id",0);
$exs_id= get_rdata("exs_id",0);
$type = get_rdata("type");
$student_type_array = array("Course fee","Exam fee","Event fee[student]","Event fee[other]");

//set Page Title
$page_title = "Print Receipt";
$payment_purpose = $pt_tran_remarks = $payment_received_paid_to_name =  $payment_mode = $amount = $amount_in_words = $student_name= $receipt_no = $payment_date = $enrollment_no =  "";
$sc_full_fee_paid = "Y";
 
$pt_br_id =  $sc_total_fee =  $sc_total_paid = 0;
if (($id !=0 || $exs_id ) && $type !="")
{
     $q_select = "SELECT pt.pt_br_id,  pt.pt_type, pt.pt_tran_u_type, pt.pt_tran_bank, pt.pt_tran_mode_of_payent, pt.pt_tran_no, pt.pt_tran_amount, pt.pt_tran_date, pt.pt_tran_remarks,pt_discount_amount ";
     $q_table = "FROM sm_payment_transaction pt ";
     if ($type == "Course fee")
     {
       $q_select .= " ,stu_gr_no, stu_first_name, stu_last_name , stu_middle_name, pt.pt_receipt_no,be.be_name, co.co_name, sc.sc_total_fee, sc.sc_total_paid, sc.sc_full_fee_paid  ";
        $q_table .=" INNER JOIN sm_student_course sc ON (sc.sc_id=pt.pt_sc_id) INNER JOIN sm_belt be ON (be.be_id= sc.sc_be_id)  INNER JOIN sm_course co ON (co.co_id= sc.sc_co_id)  ";
        $q_table .=" INNER JOIN sm_student stu ON (stu.stu_id=sc.sc_stu_id) ";
        $q_where = " WHERE pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND pt.pt_id=".$id;
     }
     else if ($type == "Exam fee")
     {
        $q_select .= " ,stu_gr_no, stu_first_name, stu_last_name , stu_middle_name, pt.pt_receipt_no,be.be_name_for be_name, co.co_name   ";
        $q_table .=" INNER JOIN sm_exam_student_entrolled exs ON (exs.exs_id=pt.pt_sc_id) INNER JOIN sm_belt be ON (be.be_id= exs.exs_be_id)  INNER JOIN sm_course co ON (co.co_id= exs.exs_co_id)  ";
        $q_table .=" INNER JOIN sm_student stu ON (stu.stu_id=exs.exs_stu_id) ";
        $q_where = " WHERE  pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND exs.exs_id=".$exs_id;
        // $q_table .=" INNER JOIN sm_student_course sc ON (sc.sc_id=pt.pt_sc_id) INNER JOIN sm_exam_student_entrolled exs ON (exs.exs_stu_id=sc.sc_stu_id AND exs.exs_be_id=sc.sc_be_id AND exs.exs_co_id=sc.sc_co_id) INNER JOIN sm_belt be ON (be.be_id= exs.exs_be_id)  INNER JOIN sm_course co ON (co.co_id= exs.exs_co_id)  ";
     }
     else if ($type == "Event fee[student]")
     {
        $q_select .= " ,stu_gr_no, stu_first_name, stu_last_name , stu_middle_name, pt.pt_receipt_no,ev.ev_name  ";
        $q_table .=" INNER JOIN sm_event_student_entrolled evs ON (evs.evs_id=pt.pt_sc_id)  ";
        $q_table .=" INNER JOIN sm_event ev ON (evs.evs_ev_id=ev.ev_id)  ";
        $q_table .=" INNER JOIN sm_student stu ON (stu.stu_id=evs.evs_stu_id) ";
        $q_where = " WHERE  pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND pt.pt_id=".$id;
        // $q_table .=" INNER JOIN sm_student_course sc ON (sc.sc_id=pt.pt_sc_id) INNER JOIN sm_exam_student_entrolled exs ON (exs.exs_stu_id=sc.sc_stu_id AND exs.exs_be_id=sc.sc_be_id AND exs.exs_co_id=sc.sc_co_id) INNER JOIN sm_belt be ON (be.be_id= exs.exs_be_id)  INNER JOIN sm_course co ON (co.co_id= exs.exs_co_id)  ";
     }
     else if ($type == "Event fee[other]")
     {
        $q_select .= " ,stu_gr_no, stu_first_name, stu_last_name , stu_middle_name, pt.pt_receipt_no,ev.ev_name  ";
        $q_table .=" INNER JOIN sm_event_student_entrolled evs ON (evs.evs_id=pt.pt_sc_id)  ";
        $q_table .=" INNER JOIN sm_event ev ON (evs.evs_ev_id=ev.ev_id)  ";
        $q_table .=" INNER JOIN sm_student_other stu ON (stu.stu_id=evs.evs_stu_id) ";
        $q_where = " WHERE  pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND pt.pt_id=".$id;
        // $q_table .=" INNER JOIN sm_student_course sc ON (sc.sc_id=pt.pt_sc_id) INNER JOIN sm_exam_student_entrolled exs ON (exs.exs_stu_id=sc.sc_stu_id AND exs.exs_be_id=sc.sc_be_id AND exs.exs_co_id=sc.sc_co_id) INNER JOIN sm_belt be ON (be.be_id= exs.exs_be_id)  INNER JOIN sm_course co ON (co.co_id= exs.exs_co_id)  ";
     }
     else if ($type == "Income")
     {
      
      // $q_select .= " pt.pt_receipt_no_income as pt_receipt_no, del.del_company_name,   ";
      $q_select .= " ,pt.pt_receipt_no_income as pt_receipt_no, acc.ac_name as payment_received_paid_to_name   ";
      $q_table .=" INNER JOIN sm_account acc ON (acc.ac_id=pt.pt_sc_id)  ";
      $q_where = " WHERE  pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND pt.pt_id=".$id;

     }
     else if ($type == "Expance")
     {
      
      // $q_select .= " pt.pt_receipt_no_income as pt_receipt_no, del.del_company_name,   ";
      $q_select .= " ,pt.pt_receipt_no_expance as pt_receipt_no, acc.ac_name as payment_received_paid_to_name  ";
      $q_table .=" INNER JOIN sm_account acc ON (acc.ac_id=pt.pt_sc_id)  ";
      $q_where = " WHERE  pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND pt.pt_id=".$id;

     }
     else if ($type == "DealerP")
     {
      
      $q_select .= " ,pt.pt_receipt_no_dealer as pt_receipt_no, del.del_company_name  as payment_received_paid_to_name  ";
      $q_table .=" INNER JOIN sm_dealer del ON (del.del_id=pt.pt_sc_id)  ";
      $q_where = " WHERE  pt_br_id = $tmp_admin_id AND pt_tran_u_type='".$type."' AND pt.pt_id=".$id;

     }
     $q = $q_select." ". $q_table. " ". $q_where;

     $result = m_process("get_data",$q);
     //print_r($result);exit(0);
     if ($result["status"]=="failure")
     {
       echo $result["errormsg"];
       exit(0);
     }
     else if ($result["count"]==0) {
       echo "No details found for print receipt";
       exit(0);
     }
     else {
        $pending_fee_message = "";

        $payment_date = DBtoDisp($result["res"][0]["pt_tran_date"]) ;
        $receipt_no = $result["res"][0]["pt_receipt_no"];
        $pt_br_id = $result["res"][0]["pt_br_id"];
        $amount = $result["res"][0]["pt_tran_amount"] ;
        // $amount = ($result["res"][0]["pt_tran_amount"] - $result["res"][0]["pt_discount_amount"]);
        $amount_in_words = ucwords(getIndianCurrency($amount));
        $payment_mode = $result["res"][0]["pt_tran_mode_of_payent"] ;
        if ($result["res"][0]["pt_tran_mode_of_payent"] != 'Cash')
        {
          $payment_mode .=  ' [Bank: '.$result["res"][0]["pt_tran_bank"].',';
          $payment_mode .=  ' Transction No: '.$result["res"][0]["pt_tran_no"].'] ';
        }
      $payment_mode .= ' On '.  $payment_date ;
      
        
        
        if(in_array($type,$student_type_array ) == true ) 
        {
          $student_name= ucwords($result["res"][0]["stu_first_name"]). " ". ucfirst($result["res"][0]["stu_middle_name"]). " ". ucfirst($result["res"][0]["stu_last_name"]);
          $enrollment_no = $result["res"][0]["stu_gr_no"] ;
          $payment_purpose = str_replace("[student]","",$result["res"][0]["pt_tran_u_type"]) ;
          $payment_purpose = str_replace("[other]","",$payment_purpose) ;
          if($type=="Course fee" || $type=="Exam fee"){
            $payment_purpose.=' '.$result["res"][0]["be_name"];}
          else if($type=="Event fee[student]" || $type=="Event fee[other]"){
            $payment_purpose.=' '.$result["res"][0]["ev_name"];
          }
          if ($type == "Course fee" && $result["res"][0]["sc_full_fee_paid"] == "N" )
        {
            $pending_fee_message = " Remaining fees to pay is ".($result["res"][0]["sc_total_fee"]-$result["res"][0]["sc_total_paid"]) . " INR";

        }

        }
        else
        {
          $payment_purpose = $result["res"][0]["pt_tran_u_type"] . " " . $result["res"][0]["be_name"];
          $pt_tran_remarks = $result["res"][0]["pt_tran_remarks"] ;
          $payment_received_paid_to_name = $result["res"][0]["payment_received_paid_to_name"] ;
        }

        
      
      
      
     

      

     }
}

$arr_branch_details =  get_branch_details($pt_br_id);
//print_r($arr_branch_details);exit(0);
// include("includes/header.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <script src="dist/js/jscript.js" type="text/javascript"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <style>
    .receipt_text {
        /* text-decoration:underline; */
        font-weight: bold;
        font-style: italic;
        padding-left: 5px;
    }

    .fee_receipt_bg_class {
        font-weight: bold;
        font-style: italic;
        text-decoration: underline;
        font-size: 26px;
        /* background-color:#000000; color:#ffffff; padding:10px; border-radius:3px; */
    }

    @media print {
        .fee_receipt_bg_class {
            font-weight: bold;
            font-style: italic;
            text-decoration: underline;
            font-size: 26px;
        }
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row" style="border-bottom:1px solid #eeeeee; padding-bottom:10px;">
                <div class="col-xs-6">
                    <div>
                        <img src="dist/img/logo.png" width="130px" height="130px" />
                    </div>
                </div>
                <div class="col-xs-6">
                    <div style="text-align: right;">
                        <!-- <h2 class="page-header text-uppercase" style="border-bottom:none;text-align:center;">
                            Martial Art Academy of India<br> -->
                            <h2 class="page-header" style="border-bottom:none;"><?php echo $arr_branch_details["name"];?> </br>
                            <span class="text-uppercase;"
                                style="font-size:18px;"><?php echo $arr_branch_details["address"];?></span>
                            
                        </h2>
                        
                    </div>
                </div>
                <h3 style="text-align:center;"><strong>Web:</strong> www.martialartacademyofindia.com</h3>
            </div>

            <div class="row">
                <div class="col-md-12 text-center" style="margin-bottom:20px;">
                    <p><span class="fee_receipt_bg_class">Fee Receipt<span></p>
                </div><!-- /.col -->
                <?php if(in_array($type,$student_type_array ) == true ) { ?>
                <div class="col-xs-4 text-left">
                    <p><strong>Enrollment No.:</strong> <?php echo $enrollment_no; ?></p>
                </div><!-- /.col -->
                <?php } ?>
                <div class="col-xs-4 text-center">
                    <p><strong>Date.:</strong> <?php echo $payment_date;?></p>
                </div><!-- /.col -->
                <div class="col-xs-4 text-right">
                    <p><strong>Receipt No:</strong> <?php echo $receipt_no;?></p>
                </div><!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row" style="margin-top:15px;font-size:large;">
                <div class="col-xs-12 " style="">
                  
                    <?php if(in_array($type,$student_type_array ) == true ) { ?>
                    <p>Received with thanks from Shri. <span class="receipt_text"><?php echo $student_name; ?></span>
                    
                    The sum of Rupee <span class="receipt_text"><?php echo $amount_in_words;?>.</span>
                    Payment Through <span class="receipt_text"><?php echo $payment_mode;?></span>
                    Payment Purpose:<span class="receipt_text"><?php echo $payment_purpose; ?></span>
                    <?php  if ($pending_fee_message !="")    { ?>
                    <u>Note:</u><span class="receipt_text"><?php echo $pending_fee_message; ?></span></p>
                    <?php 
  }
  } else { ?>
    <p><?php if($type=='Income') { echo 'Received with thanks from'; } else {  echo 'Paid to '; } ?>  Shri. <span class="receipt_text"><?php echo $payment_received_paid_to_name; ?></span>
    </p>
    <p>The sum of Rupee. <span class="receipt_text"><?php echo $amount_in_words;?></span></p>
    <p>Payment Mode:<span class="receipt_text"><?php echo $payment_mode;?></span></p>
    <p>Payment Remarks:<span class="receipt_text"><?php echo $pt_tran_remarks; ?></span></p>
  <?php }  ?>
                </div>
            </div><!-- /.row -->
            <!-- Table row -->
            <hr style="margin-bottom:2px;">&nbsp;</hr>
            <div class="row">
                <div class="col-xs-6 text-left" style="font-size:22px">Rs. <?php echo $amount;?></div>
                <div class="col-xs-12 text-right" style="">Sign of Cashier</div>
            </div>


        </section><!-- /.content -->
    </div><!-- ./wrapper -->
    <div class="remove_print_button row text-center">
        <div class="col-xs-12">
            <a href="javascript:void(0);" onclick="print_invoice_js();" class="btn btn-default"><i
                    class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <!-- AdminLTE App -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="dist/js/app.min.js" type="text/javascript"></script>
</body>

</html>