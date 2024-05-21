<div class="modal" id="change_batch_type_model">
    <input type="hidden" id="change_sc_stu_id" name="change_sc_stu_id" />
    <input type="hidden" id="change_sc_br_id" name="change_sc_br_id" />
    <input type="hidden" id="change_sc_brt_id" name="change_sc_brt_id" />
    <input type="hidden" id="change_sc_co_id" name="change_sc_co_id" />
    <input type="hidden" id="change_sc_be_id" name="change_sc_be_id" />
    <input type="hidden" id="change_sc_id" name="change_sc_id" />
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Student Course [<span id="d_student_name"></span>]</h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <tbody id="student_exam_tbody">
                                    <tr>
                                            <td colspan="2">Current Details</td>
                                        </tr>
                                <tr>
                                    <td>Batch Type</td>
                                    <td id="change_batch_type" ></td>
                                </tr>
                                <tr>
                                    <td>Course</td>
                                    <td  id="change_course"></td>
                                </tr>
                                <tr>
                                    <td>Belt</td>
                                    <td  id="change_belt"></td>
                                </tr>
                                <tr>
                                    <td>Total Fee</td>
                                    <td  id="change_total_fee"></td>
                                </tr>
                                
                                <tr>
                                        <td>Paid Fee</td>
                                        <td  id="change_paid_fee"></td>
                                    </tr>
    <tr>
        <td colspan="2">New Details</td>
    </tr>
    <tr>
        <td>Batch Type</td>
        <td><select required id="new_sc_brt_id" name="new_sc_brt_id" class="form-control">
                <option value="0">--Please select--</option>
                <?php
                $data_arr_input = array();
                $data_arr_input['select_field'] = 'brt_name ,brt_id';
                $data_arr_input['table'] = 'sm_branch_type';
                $data_arr_input['where'] = " brt_br_id = ".$tmp_admin_id." AND brt_status  = 'A' ";
                $data_arr_input['key_id'] = 'brt_id';
                $data_arr_input['key_name'] = 'brt_name';
                $data_arr_input['current_selection_value'] = 0;
                display_dd_options($data_arr_input);
                ?>
            </select></td>
    </tr>
    <tr>
        <td>Total Fee</td>
        <td><input type="text" name="change_total_fee_d" id="change_total_fee_d"  class="form-control" /></td>
    </tr>
    <tr class="hide">
        <td>Paid Fee</td>
        <td><input type="text" name="change_paid_fee_d" id="change_paid_fee_d"  class="form-control" /></td>
    </tr>
    <tr>
                                    <td>Change Date</td>
                                    <td>
                                    <input class="form-control" type="text"  id="change_date" name="change_date" value="<?php echo DBtoDisp($cur_date); ?>" /></td>
                                </tr>

                            </tbody>
                        </table>
                    </div></div>
                <!-- end of showing details -->
                <!-- start of code -->


                <!-- end of code -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" onclick="change_student_batch_type_ajax();" class="btn btn-primary confirm-class">Save</button>
              <!--  <button type="submit" onclick="pay_fee_student_exam_ajax();" class="btn btn-primary confirm-class">Pay Exam Fee</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">

function show_batch_type_change(stu_id)
        {
            get_student_batch_type_details(stu_id);
            $('#change_batch_type_model').modal('show');
        }

      // this function will get exsting result details
        function get_student_batch_type_details(stu_id)
        {

            $("#a_stu_id").val(stu_id);
          $.ajax({
                type: "POST",
                async: "false",
                url: "ajax.php",
                data: {action: "get_student_batch_type_details", stu_id: stu_id},
                success: function (result) {
                    result = $.trim(result);
                    var objResponse = jQuery.parseJSON(result);
                    console.log(objResponse);
                    if (objResponse.status == 'success')
                    {
                        
                        if (objResponse.message !='' && objResponse.message == 'No current course found')
                        {
                             alert(objResponse.message);
                             $(".modal-footer").hide();
                             $(".box-body").hide();
                        }
                        else
                        {
                            $("#change_batch_type").text(objResponse.brt_name);
                            $("#change_belt").text(objResponse.be_name);
                            $("#change_course").text(objResponse.co_name);
                            $("#change_total_fee").text(objResponse.sc_total_fee);
                            $("#change_paid_fee").text(objResponse.sc_total_paid);
                            $("#d_student_name").text(objResponse.stu_name);
                            $("#new_sc_brt_id").val(objResponse.sc_brt_id);
                            $("#change_paid_fee_d").val(objResponse.sc_total_paid);
                            $("#change_sc_stu_id").val(objResponse.sc_stu_id);
                            $("#change_sc_br_id").val(objResponse.sc_br_id);
                            $("#change_sc_brt_id").val(objResponse.sc_brt_id);
                            $("#change_sc_co_id").val(objResponse.sc_co_id);
                            $("#change_sc_be_id").val(objResponse.sc_be_id);
                            $("#change_sc_id").val(objResponse.sc_id);
                            
                            // $("#student_exam_tbody").html(objResponse.data);
                        }
                        // now bidning data to the table.

                        //    $('#course_details').modal('hide');
                        //    $('#form1').submit();
                    }
                    else
                    {
                        alert(objResponse.errormsg);
                    }
                }
            });
        }
    function change_student_batch_type_ajax()
    {

      var data = {};
      var process_id = "";
      data["action"] = "change-student-batch-type-ajax";
      data["sc_id"] = $("#change_sc_id").val();
      data["sc_brt_id"] = $("#new_sc_brt_id").val();
      data["total_fee"] = $("#change_total_fee_d").val();
      data["paid_fee"] = $("#change_paid_fee_d").val();
      data["sc_stu_id"] = $("#change_sc_stu_id").val();
      data["change_date"] = $("#change_date").val();
      
      
// start code to validate data
var dontsubmitform = 0;
if ($("#change_total_fee_d").val()  == '' || isNaN($("#change_total_fee_d").val()) || $("#change_total_fee_d").val() <=0)
{
    alert("Please enter correct total fee.");
    return false;
}
if (data["change_date"] == "")
{
    alert("Please select change date.");
    return false;
}
  if (parseInt($("#change_paid_fee_d").val()) >  parseInt($("#change_total_fee_d").text()))
  {
    console.log(parseInt($("#change_paid_fee_d").val())+"***"+parseInt($("#change_total_fee_d").text()));
    $("#change_paid_fee_d").focus();
    alert("Total Fee can not be more then Total Fee");
    return false;
  }

// end of code to validate data

 var ans_v = confirm("Are you sure you would like to process?");
                    if (ans_v == true)
                    {

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: data,
            success: function (result) {
                result = $.trim(result);

                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success')
                {
                    alert("Batch Type has been updated successfully.");
                    $('#change_batch_type_model').modal('hide');
                    $("#act").val("");
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
    $("#change_date").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true, });
    </script>
