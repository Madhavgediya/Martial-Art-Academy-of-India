<div class="modal" id="add_product_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Product</h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <form id="frmaddproduct" name="frmaddproduct" onsubmit="return false;" >
                            <input type="hidden" id="action" name="action" value="add_product_ajax"   />
                            <div class=" col-md-12">
                                <div class="model-course-help" >
                                    <label class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-9">
                                        <select name="pro_cat_id" id="pro_cat_id" class="form-control" >
                                            <?php
                                            $data_arr_input = array();
                                            $data_arr_input['select_field'] = 'cat_name ,cat_id';
                                            $data_arr_input['table'] = 'sm_category';
                                            $data_arr_input['key_id'] = 'cat_id';
                                            $data_arr_input['key_name'] = 'cat_name';
                                            $data_arr_input['orderby'] = 'cat_name ASC';
                                            $data_arr_input['where'] = ' cat_admin_id = ' . $tmp_admin_id . '  AND cat_status = 1 ';
                                            $data_arr_input['current_selection_value'] = 0;
                                            display_dd_options($data_arr_input);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Manufacturer</label>
                                    <div class="col-sm-9">
                                        <select name="pro_manu_id" id="pro_manu_id" class="form-control" >

                                            <?php
                                            $data_arr_input = array();
                                            $data_arr_input['select_field'] = 'manu_name ,	manu_id';
                                            $data_arr_input['table'] = 'sm_manufacturer';
                                            $data_arr_input['key_id'] = 'manu_id';
                                            $data_arr_input['key_name'] = 'manu_name';
                                            $data_arr_input['orderby'] = 'manu_name ASC';
                                            $data_arr_input['where'] = ' manu_admin_id = ' . $tmp_admin_id . ' AND manu_status = 1 ';
                                            $data_arr_input['current_selection_value'] = 0;
                                            display_dd_options($data_arr_input);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="pro_name" id="pro_name"  placeholder="Name" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">HSN No</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="pro_model" id="pro_model"  placeholder="HSN No" value="" class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">GST%</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="pro_gst" id="pro_gst"  placeholder="GST" value="0" class="form-control" />
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
            <button type="button" onclick="add_product_ajax();" class="btn btn-primary confirm-class">Save</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">
    function add_product()
    {
      //  $("#pro_cat_id").val(0);
      //  $("#pro_manu_id").val(0);
        $("#pro_name").val(""); 
        $("#pro_model").val("");
        $('#add_product_model').modal('show');
    }

    function add_product_ajax()
    {
        if ($("#pro_cat_id").val() == 0)
        {
            alert("Please select category");
            $("#pro_cat_id").focus();
            return false;
        } else if ($("#pro_manu_id").val() == 0)
        {
            alert("Please select manufacturer");
            $("#pro_manu_id").focus();
            return false;
        } else if ($("#pro_name").val() == "")
        {
            alert("Please enter product name");
            $("#pro_name").focus();
            return false;
        }else if ($("#pro_gst").val() == "")
        {
            alert("Please enter product gst");
            $("#pro_gst").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: $("#frmaddproduct").serialize(),
            cache: false,
            async: false,
            success: function (result)
            {
                console.log("Adding result to the process");
                r_response = result;
                result = $.trim(result);
                console.log(result);
                var objResponse = jQuery.parseJSON(result);
                console.log(objResponse);
                if (objResponse.status == 'success')
                {
                    $('.inv_product').each(
                            function () {
                                ml(objResponse.data_product);
                                cur_val = $(this).val();
                                ml("val"+$(this).val());
                                $(this).html(objResponse.data_product);
                                // ml($(this).html());
                                $(this).val(cur_val);
                                $(this).trigger('change.select2');
                                ml("called change feor select2")
                            }
                    );
            alert(objResponse.data);
             $('#add_product_model').modal('hide');
                    // each 
                    // 
                    //    $('#course_details').modal('hide');
                    //   $('#form1').submit();
                } else
                {
                    alert(objResponse.errormsg);
                }
            }
        });
        
    return false;
    }
</script>