<div class="modal" id="add_product_option_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Option</h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <form id="frmaddproductoption" name="frmaddproductoption" onsubmit="return false;" >
                            <input type="hidden" id="action" name="action" value="add_product_option_ajax"   />
                            <div class=" col-md-12">
                                
                                
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Option Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="po_name" id="po_name"  placeholder="Option Name" value="" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-12">
                                
                                
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Type</label>
                                    <div class="col-sm-9">
                                    <input type="radio" name="po_type" id="po_type_a" value="Color" checked="checked" /><label for="po_type_a">Color</label> <input type="radio" name="po_type" id="po_type_i" value="Size" /><label for="po_type_i">Size</label> <input type="radio" name="po_type" id="po_type_b" value="Both" /><label for="po_type_b">Both</label>
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
            <button type="button" onclick="add_product_option_ajax();" class="btn btn-primary confirm-class">Save</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">
    function add_product_option()
    {
        $("#po_name").val(""); 
        $('#add_product_option_model').modal('show');
    }

    function add_product_option_ajax()
    {
        if ($("#po_name").val() == "")
        {
            alert("Please enter option name");
            $("#pro_model").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: $("#frmaddproductoption").serialize(),
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
                    $('.inv_product_option').each(
                            function () {
                                ml(objResponse.data_product_size);
                                cur_val = $(this).val();
                                ml("val"+$(this).val());
                                $(this).html(objResponse.data_product_size);
                                ml($(this).html());
                                $(this).val(cur_val);
                                $(this).trigger('change.select2');
                            }
                    );

                    $('.inv_product_option_2').each(
                            function () {
                                ml(objResponse.data_product_color);
                                cur_val = $(this).val();
                                ml("val"+$(this).val());
                                $(this).html(objResponse.data_product_color);
                                ml($(this).html());
                                $(this).val(cur_val);
                                $(this).trigger('change.select2');
                            }
                    );
                    alert(objResponse.data);
                    $('#add_product_option_model').modal('hide');
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