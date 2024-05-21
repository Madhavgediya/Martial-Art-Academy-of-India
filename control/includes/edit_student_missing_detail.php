<div class="modal" id="edit_student_detail">
    <input type="hidden" id="d_stu_id" name="d_stu_id" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Student[<span id="d_student_name"></span>] Detail</h4>
            </div>
            <div class="modal-body">
                <form name="editForm" id="editForm" enctype="multipart/form-data" method="post" class="form-horizontal">
                    <div class="box box-info">
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">GR No</label>
                                    <div class="col-sm-9">
                                        <input readonly type="text" name="stu_gr_no" id="stu_gr_no" placeholder="GR No" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">First Name</label>
                                    <div class="col-sm-9">
                                        <input required type="text" name="stu_first_name" id="stu_first_name" placeholder="First Name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Middle Name</label>
                                    <div class="col-sm-9">

                                        <input type="text" name="stu_middle_name" id="stu_middle_name" placeholder="Middle Name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Last Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_last_name" id="stu_last_name" placeholder="Last Name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Mother Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_mother_name" id="stu_mother_name" placeholder="Mother Name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Birth Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_birth_date" id="stu_birth_date" placeholder="Birth Date" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Home Address</label>
                                    <div class="col-sm-9">
                                        <textarea name="stu_home_address" id="stu_home_address" placeholder="Home Address" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Office Address</label>
                                    <div class="col-sm-9">
                                        <textarea name="stu_office_address" id="stu_office_address" placeholder="Office Address" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_email" id="stu_email" placeholder="Email" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Parent Mobile</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_parent_mobile_no" id="stu_parent_mobile_no" placeholder="Parent Mobile" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">App. Mobile</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_phone" id="stu_phone" placeholder="App. Mobile"  class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Whatsapp No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_whatsappno" id="stu_whatsappno" placeholder="Whatsapp No."  class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Aadhar No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_aadharno" id="stu_aadharno" placeholder="Aadhar No."  class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Batch Time</label>
                                    <div class="col-sm-9">
                                        <select required id="stu_batchtime" name="stu_batchtime" class="form-control">
                                            <?php
                                            $data_arr_input = array();
                                            $data_arr_input['select_field'] = 'bt_name ,bt_id';
                                            $data_arr_input['table'] = 'sm_batch_time';
                                            $data_arr_input['where'] = " bt_br_id = " . $tmp_admin_id . " AND bt_status  = 'A' ";
                                            $data_arr_input['key_id'] = 'bt_id';
                                            $data_arr_input['key_name'] = 'bt_name';
                                            $data_arr_input['current_selection_value'] = $stu_batchtime;
                                            $data_arr_input['order_by'] = 'bt_id';

                                            display_dd_options($data_arr_input);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="stu_status_old" name="stu_status_old" />
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9" id="stu_status">
                                        <input type="radio" name="stu_status" id="stu_status_a" value="A" onclick="show_hide_deactivation_date('hide');" />
                                        <label for="stu_status_a">Active</label>
                                        <input type="radio" name="stu_status" onclick="show_hide_deactivation_date('show');" id="stu_status_i" value="I" />
                                        <label for="stu_status_i">InActive</label>
                                    </div>
                                </div>
                                <div class="form-group" id="employee_inactive_date">
                                    <label id="lbl_active_inactive" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="stu_deactivation_date" id="stu_deactivation_date" placeholder="Date" class="form-control" />
                                    </div>
                                </div>
                                <input type="hidden" id="stu_photo_old" name="stu_photo_old" />
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Photo</label>
                                    <div class="col-sm-9">
                                        <input type="file" id="stu_photo" name="stu_photo" />
                                        <div id="stu_profile"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right" id="btnEditUser" name="btnEditUser">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" lang="javascript">
    async function get_student_detail(stu_id) {
        let studData;
        await $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "get_student_detail_ajax",
                d_stu_id: $("#d_stu_id").val(),
            },
            success: function(result) {
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success') {
                    studData = objResponse.data
                }
            }
        });
        return studData
    }

    async function edit_student_event(stu_id) {
        $('#edit_student_detail').modal('show');
        $('#d_stu_id').val(stu_id);
        let data = await get_student_detail(stu_id)

        $('#d_student_name').text(data.stu_first_name + ' ' + data.stu_middle_name + ' ' + data.stu_last_name);
        $('#stu_gr_no').val(data.stu_gr_no)
        $('#stu_first_name').val(data.stu_first_name)
        $('#stu_middle_name').val(data.stu_middle_name)
        $('#stu_last_name').val(data.stu_last_name)
        $('#stu_mother_name').val(data.stu_mother_name)
        $('#stu_birth_date').val(dateFormat(data.stu_birth_date, 'dd-MM-yyyy'))
        $('#stu_home_address').val(data.stu_home_address)
        $('#stu_office_address').val(data.stu_office_address)
        $('#stu_email').val(data.stu_email)
        $('#stu_parent_mobile_no').val(data.stu_parent_mobile_no)
        $('#stu_phone').val(data.stu_phone)
        $('#stu_whatsappno').val(data.stu_whatsappno)
        $('#stu_aadharno').val(data.stu_aadharno)
        $('#stu_batchtime').val(data.stu_batchtime)
        $('#stu_status_old').val(data.stu_status)
        $("#stu_status input:radio[value=" + data.stu_status + "]").prop('checked', true);
        $('#lbl_active_inactive').html(data.stu_status == 'A' ? 'Activation Date' : 'Deactivation Date')
        $('#stu_deactivation_date').val(dateFormat(data.stu_deactivation_date, 'dd-MM-yyyy'))
        $('#stu_photo_old').val(data.stu_photo)
        if (data.stu_photo != '') {
            $('#stu_profile').html(`
            <a href="${data.stu_photo}" target="_blank"><img src="${data.stu_photo}" height="120px" width="120px" /></a>
            `)
        }
    }

    function dateFormat(inputDate, format) {
        const date = new Date(inputDate);
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        format = format.replace("MM", month.toString().padStart(2, "0"));
        if (format.indexOf("yyyy") > -1) {
            format = format.replace("yyyy", year.toString());
        } else if (format.indexOf("yy") > -1) {
            format = format.replace("yy", year.toString().substr(2, 2));
        }
        format = format.replace("dd", day.toString().padStart(2, "0"));
        return format;
    }

    $('#editForm').on('submit', async function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('stu_id', $("#d_stu_id").val());

        await $.ajax({
            url: "edit_student_missing_detail.php",
            data: formData,
            type: "POST",
            processData: false,
            contentType: false,
            success: function(result) {
                result = $.trim(result);
                $('#edit_student_detail').modal('hide');
                if (result == 'success') {
                    window.location.href = 'student_missing_details.php?msg=1'
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: result
                    })
                }
            },
            error: function(error) {
                Toast.fire({
                    icon: 'error',
                    title: 'Internal server error'
                })
            }
        });
        return false;
    });

    $("#stu_birth_date, #stu_deactivation_date").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });
</script>