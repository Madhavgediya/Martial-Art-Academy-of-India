<?php
$bi_issue_date  = get_rdata('bi_issue_date',$cur_date_only);
$bi_issue_date_valid = get_rdata('bi_issue_date_valid',convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BOOK_ISSUE_PERIOD))));
?>
<div class="modal" id="return_reissue_book_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Return/Re-Issue [<span id="book_name_disp"></span>-<span id="student_name_disp"></span>]</h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <form id="frmreturnreissue" name="frmreturnreissue" onsubmit="return false;" >
                            <input type="hidden" id="action" name="action" value="return_reissue_book_ajax"   />
                            <input type="hidden" id="book_id_ajax" name="book_id_ajax" value=""   />
                            <input type="hidden" id="book_issue_stu_id_ajax" name="book_issue_stu_id_ajax" value=""   />
                            <div class=" col-md-12">
                            <div class="model-course-help" >
                                    <label class="col-sm-3 control-label">Return/Reissue</label>
                                    <div class="col-sm-9">
                                        <select name="book_return_re_issue" id="book_return_re_issue" class="form-control" onchange="check_return_re_issue();" >
                                            <option value="Return" selected >Return</option>
                                            <option value="Re-Issue">Re-Issue</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="model-course-help" id="date_return_date" >
                                    <label class="col-sm-3 control-label">Return Date</label>
                                    <div class="col-sm-9">
                                    <input type="text" readonly name="bi_issue_date" id="bi_issue_date"  placeholder="Issue Date" value="<?php echo $bi_issue_date; ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help" id="date_re_issue_date" style="display:none;" >
                                    <label class="col-sm-3 control-label">Re-Issue Date Valid</label>
                                    <div class="col-sm-9">
                                    <input type="text" readonly name="bi_issue_date_valid" id="bi_issue_date_valid"  placeholder="Return Date" value="<?php echo $bi_issue_date_valid; ?>" class="form-control" />
                                    </div>
                                </div>
                                
                            </div>
                    </form>
                        </div>
                </div>
            </div>
            <!-- end of showing details -->
            <!-- start of code -->


            <!-- end of code -->

        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="button" onclick="return_re_issue_book_ajax();" class="btn btn-primary confirm-class">Save</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">
function return_re_check_model(book_id, book_issue_stu_id,book_title,student_name)
{
    $('#book_id_ajax').val(book_id);
    $('#book_issue_stu_id_ajax').val(book_issue_stu_id);
    $('#book_name_disp').text(book_title);
    $('#student_name_disp').text(student_name);
    $('#return_reissue_book_model').modal('show');
}
function check_return_re_issue()
{
    if ($("#book_return_re_issue").val()=="Return")
    {
        $('#date_return_date').show();
        $('#date_re_issue_date').hide();
    }
    else if ($("#book_return_re_issue").val()=="Re-Issue")
    {
        $('#date_re_issue_date').show();
        $('#date_return_date').hide();
    }
}
    

    function return_re_issue_book_ajax()
    {
        if ($("#book_id_ajax").val() == 0)
        {
            alert("Please server communication error for book");
            $("#book_id_ajax").focus();
            return false;
        } else if ($("#book_issue_stu_id_ajax").val() == 0)
        {
            alert("Please server communication error for student");
            $("#book_issue_stu_id_ajax").focus();
            return false;
        } 

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: $("#frmreturnreissue").serialize(),
            cache: false,
            async: false,
            success: function (result)
            {
                r_response = result;
                result = $.trim(result);

                var objResponse = jQuery.parseJSON(result);
                console.log(objResponse);
                if (objResponse.status == 'success')
                {
                    alert(objResponse.data);
                    $('#return_reissue_book_model').modal('hide');
                    $('#form1').submit();
                } 
                else
                {
                    alert(objResponse.errormsg);
                }
            }
        });
    return false;
    }

    $("#bi_issue_date").datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true, });

  $("#bi_issue_date_valid").datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true, });                                
</script>  