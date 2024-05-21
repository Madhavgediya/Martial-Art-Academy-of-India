<?php
include("includes/application_top.php");
//set Page Title
$page_title = "Print Invoice";
$errormsg = get_rdata('errormsg', '');
$admin_id = session_get("admin_id");
$id = get_rdata("id", 0);
$act = get_rdata("act");
$inv_id = 0;
$total_qty = 0;

if ($id == 0) {
    $errormsg = "Invalid Invoice Details";
} else {

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
        <!--        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
                <meta http-equiv="Pragma" content="no-cache" />
                <meta http-equiv="Expires" content="0" />-->
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="no-cache"/>
        <meta http-equiv="Expires" content="-1"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
        <title><?php echo $page_title; ?></title>
        <?php include("includes/include_files.php"); ?>
        <style>

        </style>
    </head>
    <body>
        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-xs-12">
                        <div style="text-align: center">
                            <h2 class="page-header text-uppercase" >
                                Martial Art Academy Of India </br>
                                <span class="text-uppercase;" style="font-size:18px">310, paltinum complex, kalva chowk, jungadh - 362 001. Mo. 7878273458.</span>
                            </h2>



                        </div>
                    </div><!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-12 text-center" style="margin-bottom:20px;">
                    <p><span style="background-color:#000000; color:#ffffff; padding:10px; border-radius:3px; ">Fee Receipt<span></p>
                    </div><!-- /.col -->
                    <div class="col-xs-4 text-left">
                    <p><strong>Enrollment No.:</strong> MA00001</p>
                    </div><!-- /.col -->
                    <div class="col-xs-4 text-center">
                    <p><strong>Date.:</strong> 23/12/2017</p>
                    </div><!-- /.col -->
                    <div class="col-xs-4 text-right">
                    <p><strong>Receipt No:</strong> 001</p>
                    </div><!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row" style="margin-top:15px;">
                  <div class="col-xs-12 " style="">
  <p>Received with thanks from Shri. <span class="receipt_text">Mayur Dhudasia</span></p>
  <p>The sum of Rupee. <span class="receipt_text">One thousand only</span></p>
  <p>Payment Mode:<span class="receipt_text">Cheque On Date: 08/05/2018</span></p>
  <p>Payment Purpose:<span class="receipt_text">Course Fee</span></p>
  </div>
                  </div><!-- /.row -->
                <!-- Table row -->
              <hr style="margin-bottom:2px;">&nbsp;</hr>
                <div class="row">
                  <div class="col-xs-6 text-left" style="font-size:22px" >Rs. 1000</div>
                  <div class="col-xs-12 text-right" style="">Sign of Cashier</div>
                </div>


            </section><!-- /.content -->

        <div style="text-align: center;" class="remove_print_button row" id=""  >
            <div class="col-xs-12">
                <a href="javascript:void(0);" onclick="print_receipt();"  class="btn btn-default"><i class="fa fa-print"></i> Print </a>
            </div>
        </div>
          </div><!-- ./wrapper -->
        <!-- AdminLTE App -->
        <script src="dist/js/app.min.js" type="text/javascript"></script>
    </body>
</html>
