<?php $pqm_arr_products_options_key_value_color = get_product_options_as_array('A', '', '', 'Color');  ?>
<?php $pqm_arr_products_options_key_value_size = get_product_options_as_array('A', '', '', 'Size');  ?>
<div class="modal" id="product_qty_manager">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="product_qty_manager_modal_title"></h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <form id="frm_product_qty_manager" name="frm_product_qty_manager" onsubmit="return false;">
                            <input type="hidden" id="action" name="action" value="product_qty_manager_ajax" />
                            <input type="hidden" id="action_type" name="action_type" value="" />
                            <input type="hidden" id="model_row_id" name="model_row_id" value="" />
                            <input type="hidden" id="exs_id" name="exs_id" value="" />
                            <div class=" col-md-12">
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Product</label>
                                    <div class="col-sm-9">
                                        <select name="pqm_invpro_pro_id" id="pqm_invpro_pro_id" class="form-control" style="width:100%">
                                            <option value="">Please select</option>
                                            <?php

                                            if (isset($addcertificate) && $addcertificate == '1') {
                                                $pqm_arr_products_key_value = get_product_as_array(1, '', "Purchase", '5,13');
                                            } else {
                                                $pqm_arr_products_key_value = get_product_as_array(1, '', "Dead Stock");
                                            }

                                            display_dd_options_from_array_old($pqm_arr_products_key_value, '');
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Size</label>
                                    <div class="col-sm-9">
                                        <select name="pqm_invpro_po_id" id="pqm_invpro_po_id" class="form-control" style="width:100%">
                                            <option value="">Please select</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Color</label>
                                    <div class="col-sm-9">
                                        <select name="pqm_invpro_po_id_2" id="pqm_invpro_po_id_2" class="form-control" style="width:100%">
                                            <option value="">Please select</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="model-course-help" id="pqm_invpro_qty_row">
                                    <label class="col-sm-3 control-label">Qty</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="pqm_invpro_qty" id="pqm_invpro_qty" class="form-control" style="width:100%">
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
                <button type="button" id="btn_product_qty_manager" onclick="product_qty_manager_ajax();" class="btn btn-primary confirm-class pull-left ">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="hidden" id="addcertificate_1" value="<?=@$addcertificate?>">
<script type="text/javascript">
    $("#pqm_invpro_pro_id").select2();

    $('#pqm_invpro_pro_id').on('change', async function() {
        $("#pqm_invpro_po_id, #pqm_invpro_po_id_2").html('');
        let pqm_invpro_pro = $('#pqm_invpro_pro_id option:selected').text();
        let po_used_type = ''
        if (pqm_invpro_pro == 'Certificate') {
            po_used_type = '1, 3';
        } else if (pqm_invpro_pro == 'Belt') {
            po_used_type = '2, 3';
        }
        if($("#addcertificate_1").val()=="1") {
            let sizeDropData = await get_product_option_detail_new('Size', po_used_type,$("#pqm_invpro_pro_id").val());
            $("#pqm_invpro_po_id").html(sizeDropData);
            $("#pqm_invpro_po_id").select2();
            let colorDropData = await get_product_option_detail_new('Color', po_used_type,$("#pqm_invpro_pro_id").val());
            $("#pqm_invpro_po_id_2").html(colorDropData);
            $("#pqm_invpro_po_id_2").select2();
        }
        else {
            let sizeDropData = await get_product_option_detail('Size', po_used_type);
            $("#pqm_invpro_po_id").html(sizeDropData);
            $("#pqm_invpro_po_id").select2();
            let colorDropData = await get_product_option_detail('Color', po_used_type);
            $("#pqm_invpro_po_id_2").html(colorDropData);
            $("#pqm_invpro_po_id_2").select2();
        }
    })

    async function get_product_option_detail(po_type, po_used_type) {
        let poData = '<option value="">Please select</option>';
        await $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "get_product_option_ajax",
                status: 'A',
                po_type: po_type,
                po_used_type: po_used_type,
            },
            success: function(result) {
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                for (let key in objResponse) {
                    if (objResponse.hasOwnProperty(key)) {
                        value = objResponse[key];
                        poData += `<option value="${key}">${value}</option>`;
                    }
                }
            }
        });
        return poData
    }

    async function get_product_option_detail_new(po_type, po_used_type,pqm_invpro_pro_id) {
        let poData = '<option value="">Please select</option>';
        await $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "get_product_option_ajax_new",
                status: 'A',
                po_type: po_type,
                po_used_type: po_used_type,
                pqm_invpro_pro_id:pqm_invpro_pro_id
            },
            success: function(result) {
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                for (let key in objResponse) {
                    if (objResponse.hasOwnProperty(key)) {
                        value = objResponse[key];
                        poData += `<option value="${key}">${value}</option>`;
                    }
                }
            }
        });
        return poData
    }

    function product_qty_manager_toggle(action,exs_id=0) {

        $('#pqm_invpro_pro_id').val("").trigger("change");
        $('#pqm_invpro_po_id').val("").trigger("change");
        $('#pqm_invpro_po_id_2').val("").trigger("change");


        $('#action_type').val(action);
        $('#pqm_invpro_qty_row').hide();
        $('#product_qty_manager_modal_title').html(action);

        $('#product_qty_manager').modal('show');
        $('#product_qty_manager #exs_id').val(exs_id);

        if (action == 'Dead Stock') {
            $('#pqm_invpro_qty').val(1);
            $('#pqm_invpro_qty_row').show();
        } else {
            $('#pqm_invpro_qty').val(1);
        }

    }

    function product_qty_manager_ajax() {
        $("#btn_product_qty_manager").attr("disabled", true);
        if ($("#pqm_invpro_pro_id").val() == '') {
            alert("Please select product.");
            $("#pqm_invpro_pro_id").focus();
            $("#btn_product_qty_manager").attr("disabled", false);
            return false;
        } else if ($("#pqm_invpro_po_id").val() == '') {
            alert("Please select size.");
            $("#pqm_invpro_po_id").focus();
            $("#btn_product_qty_manager").attr("disabled", false);
            return false;
        } else if ($("#pqm_invpro_po_id_2").val() == '') {
            alert("Please select color.");
            $("#pqm_invpro_po_id_2").focus();
            $("#btn_product_qty_manager").attr("disabled", false);
            return false;
        }

        if ($.isNumeric($("#pqm_invpro_qty").val()) == false) {
            alert("Quantity must be a numeric value.");
            $("#pqm_invpro_qty").focus();
            $("#btn_product_qty_manager").attr("disabled", false);
            return false;
        } else if ($("#pqm_invpro_qty").val() == 0) {
            alert("Qty must be greater than zero.");
            $("#pqm_invpro_qty").focus();
            $("#btn_product_qty_manager").attr("disabled", false);
            return false;
        }


        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: $("#frm_product_qty_manager").serialize(),
            cache: false,
            async: false,
            success: function(result) {
                r_response = result;
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success') {
                    alert("Your request for " + $('#action_type').val() + " has been processed successfully.");
                    $('#product_qty_manager').modal('hide');
                    $("#btn_product_qty_manager").attr("disabled", false);
                    window.location.reload();
                    if (action == 'Dead Stock') {
                        window.location.href = 'report_stock.php';
                    }
                } else {
                    $("#btn_product_qty_manager").attr("disabled", false);
                    alert(objResponse.errormsg);
                }
            }
        });

        return false;
    }
</script>