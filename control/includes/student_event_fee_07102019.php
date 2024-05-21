<!-- START - called in list of student page for enrollment of course details to student -->
<?php if ($c_file == 'event_student_entrolled.php' && isset($pay_fee) && $pay_fee ==1) { ?>
    <div class="modal" id="pay_fee_student">
  <input type="hidden" id="d_stu_id" name="d_stu_id" />
  <input type="hidden" id="d_evs_id" name="d_evs_id" />
  <input type="hidden" id="d_stu_br_id" name="d_stu_br_id" />
  <input type="hidden" id="d_exs_fee" name="d_exs_fee" />
  <input type="hidden" id="d_exs_paid" name="d_exs_paid" />
  <input type="hidden" id="d_max_pending_pay" name="d_max_pending_pay" />
  <input type="hidden" id="d_evs_stu_or_other" name="d_evs_stu_or_other" />
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pay Fees  [<span id="d_student_name"></span>]</h4>
            </div>
            <div class="modal-body">
<!-- start of code -->
  <div class="box box-info">
<div class="box-body">
    <div class=" col-md-12">
<div class="model-course-help" >

    <label class="col-sm-3 control-label">Total Fee</label>
    <div class="col-sm-9">
      <span id="v_amt_topay" ></span>
    </div>
</div>
<div class="model-course-help" >

    <label class="col-sm-3 control-label">Paid Fee</label>
    <div class="col-sm-9">
      <span id="v_amt_paid" ></span>
    </div>
</div>
<div class="model-course-help" >

    <label class="col-sm-3 control-label">Pending Fee </label>
    <div class="col-sm-9">
      <span id="v_amt_topending" ></span>
    </div>
</div>
<div class="model-course-help hide">
    <label class="col-sm-3 control-label">Receipt No.</label>
    <div class="col-sm-9">
        <input type="text" name="pt_receipt_no" id="pt_receipt_no"  placeholder="Receipt No." value="" class="form-control" />
    </div>
</div>
<div class="model-course-help" >

    <label class="col-sm-3 control-label">Payment Mode</label>
    <div class="col-sm-9">
        <select required id="pt_tran_mode_of_payent" onchange="change_payment_mode();" name="pt_tran_mode_of_payent" class="form-control">
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
        <input type="text" readonly name="pt_tran_date" id="pt_tran_date"  placeholder="Payment Date" value="<?php echo DBtoDisp($cur_date); ?>" class="form-control" />
    </div>
</div>
<div id="transaction_no" style="display:none;" >
  <div class="model-course-help">
  <label class="col-sm-3 control-label">Txn. No</label>
  <div class="col-sm-9">
    <input type="text" required id="pt_tran_no" name="pt_tran_no" class="form-control" />
  </div>
  </div>
</div>
<div id="tran_bank" style="display:none;" >
  <div class="model-course-help">
  <label class="col-sm-3 control-label">Bank</label>
  <div class="col-sm-9">
    <input type="text" required id="pt_tran_bank" name="pt_tran_bank" class="form-control" />
  </div>
  </div>
</div>
        <div class="model-course-help" style="display: none;">
    <label class="col-sm-3 control-label">Amount</label>
    <div class="col-sm-9">
      <input type="text"  readonly id="pt_tran_amount" name="pt_tran_amount" class="form-control" />
    </div>
</div>
<div class="model-course-help">
    <label class="col-sm-3 control-label">Remarks</label>
    <div class="col-sm-9">
      <textarea id="pt_tran_remarks" name="pt_tran_remarks" class="form-control" ></textarea>
    </div>
</div>


    </div>
</div>
</div>

<!-- end of code -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" onclick="pay_fee_student_event_ajax();" class="btn btn-primary confirm-class">Pay Event Fee</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">
$("#pt_tran_date").datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true, });
function change_payment_mode()
{
  if ($("#pt_tran_mode_of_payent").val() != 'Cash')
  {
    $("#transaction_no").show();
    $("#pt_tran_no").val('');
    $("#tran_bank").show();
    $("#pt_tran_bank").val('');
  }
  else
  {
    $("#transaction_no").hide();
    $("#tran_bank").hide();
  }
}
function pay_fee_student_event(d_evs_stu_or_other,d_stu_id,d_evs_id,d_stu_br_id,d_exs_fee,d_exs_paid,d_title,d_student_name)
{
   $('#d_student_name').text(d_student_name);
   $('#v_amt_topay').text(d_exs_fee+ ' INR');
   var amt_paid = 0;
   if (d_exs_paid == 1)
   {
	   amt_paid=d_exs_fee;
   }

   $('#v_amt_paid').text(amt_paid+ ' INR');
   $('#v_amt_topending').text((d_exs_fee-amt_paid)+ ' INR');
   $("#d_max_pending_pay").val(d_exs_fee-amt_paid);
   $('#pt_tran_amount').val(d_exs_fee-amt_paid);
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

function pay_fee_student_event_ajax()
{
var d_max_pending_pay = parseInt($("#d_max_pending_pay").val());
console.log("--"+$("#pt_tran_amount").val() +"--"+ d_max_pending_pay+'--');
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
     data: { action: "pay_fee_student_event_ajax", d_evs_stu_or_other:$("#d_evs_stu_or_other").val() , pt_receipt_no : $("#pt_receipt_no").val(), pt_tran_bank : $("#pt_tran_bank").val(), pt_tran_no : $("#pt_tran_no").val(), pt_tran_mode_of_payent : $("#pt_tran_mode_of_payent").val(), pt_tran_bank : $("#pt_tran_bank").val(), pt_tran_amount : $("#pt_tran_amount").val(), pt_tran_date : $("#pt_tran_date").val(), pt_tran_remarks : $("#pt_tran_remarks").val()  , d_stu_id : $("#d_stu_id").val(), d_evs_id : $("#d_evs_id").val() , d_stu_br_id : $("#d_stu_br_id").val() } ,
     success: function (result) {
          result = $.trim(result);

          var objResponse = jQuery.parseJSON(result);
          if (objResponse.status =='success')
          {
              alert("Fees has been paid successfully.");
              $('#pay_fee_student').modal('hide');
              $("#act").val("act");
              $("#pay_fee").val(1);
              $('#form_enrollment').submit();
          }
          else
          {
            alert(objResponse.errormsg);
          }
      }
  });
  }
}
</script>
<?php }  ?>
