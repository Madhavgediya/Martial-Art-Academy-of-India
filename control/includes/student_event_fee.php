<!-- START - called in list of student page for enrollment of course details to student -->
<?php if ($c_file == 'event_student_entrolled.php' && isset($pay_fee) && $pay_fee ==1) { ?>
<style>
.modal-dialog {
    width: 750px;
}
</style>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pay Fees [<span id="d_student_name"></span>] <a href="javascript:void(0);"
                        onclick="fresh_receipt();">New</a></h4>
            </div>
            <div class="modal-body">
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
                <!-- start of code -->
                <div class="box box-info">
                    <div class="box-body">
                        <div class=" col-md-12">
                            <div class="model-course-help">
                                <input type="hidden" id="pt_id" name="pt_id" />
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
                            <div class="model-course-help">
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
                                    <select id="pt_ac_id" name="pt_ac_id" class="form-control" onchange="getpaymentmodevalue();">
                                        <option value="0">--Please select--</option>
                                        <?php
                                          $data_arr_input = array();
                                          $data_arr_input['select_field'] = 'ac_name ,ac_id';
                                          $data_arr_input['table'] = 'sm_account';
                                          $data_arr_input['where'] = " ac_status  = 'A' and ac_br_id= $tmp_admin_id";
                                          $data_arr_input['key_id'] = 'ac_id';
                                          $data_arr_input['key_name'] = 'ac_name';
                                          $data_arr_input['current_selection_value'] = 0;
                                          display_dd_options($data_arr_input);
                                          ?>
                                    </select>
                                </div>
                            </div>
                            <div class="model-course-help">

                                <label class="col-sm-3 control-label">Payment Mode</label>
                                <div class="col-sm-9">
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
                                    <input type="text" readonly name="pt_tran_date" id="pt_tran_date"
                                        placeholder="Payment Date" value="<?php echo DBtoDisp($cur_date); ?>"
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
                            <div class="model-course-help">
                                <label class="col-sm-3 control-label">Amount</label>
                                <div class="col-sm-9">
                                    <input type="text" id="pt_tran_amount" name="pt_tran_amount" class="form-control" />
                                </div>
                            </div>
                            <div class="model-course-help">
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
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" onclick="pay_fee_student_event_ajax();" class="btn btn-primary confirm-class">Pay
                    Event Fee</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">
// new funcation to process records
function fresh_receipt() {
    $("#pt_id").val(0);
    $("#pt_tran_mode_of_payent").val("");
    $("#pt_tran_amount").val("");
    $("#pt_tran_remarks").val("");
    $("#pt_tran_bank").val("");
    $("#pt_tran_no").val("");
    $("#pt_discount_amount").val("");
    $("#pt_tran_date").val("<?php echo $cur_date_only; ?>");
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
                    location.reload();
                } else {
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

// end of new funcation

$("#pt_tran_date").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
});

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
    if ($("#pt_tran_mode_of_payent").val() != 'Cash' && $("#pt_tran_mode_of_payent").val() != '--Please Select--') {
        $("#transaction_no").show();
        $("#pt_tran_no").val('');
        $("#tran_bank").show();
        $("#pt_tran_bank").val('');
    } else {
        $("#transaction_no").hide();
        $("#tran_bank").hide();
    }
}

function pay_fee_student_event(d_evs_stu_or_other, d_stu_id, d_evs_id, d_stu_br_id, d_exs_fee, d_exs_paid, d_title,
    d_student_name, d_amt_discount) {
    get_event_fees_payment_details(d_evs_stu_or_other, d_evs_id);
    $('#d_student_name').text(d_student_name);
    $('#v_amt_topay').text(d_exs_fee + ' INR');
    //    var amt_paid = 0;
    //    if (d_exs_paid == 1)
    //    {
    // 	   amt_paid=d_exs_fee;
    //    }
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
</script>
<?php }  ?>