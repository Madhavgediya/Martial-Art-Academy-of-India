<?php
$inv_sc_id = '';
if ($page_type == 'Sale') {
    $inv_sc_id = get_rdata('inv_purchase_del_id');
}
?>
<div class="modal" id="return_product_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Return Product</h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <form id="frmreturnproduct" name="frmreturnproduct" onsubmit="return false;">
                            <input type="hidden" id="action" name="action" value="return_product_qty_ajax" />
                            <input type="hidden" id="model_invpro_id" name="model_invpro_id" value="" />
                            <input type="hidden" id="purchase_sale_type" name="purchase_sale_type" value="<?php echo $page_type; ?>" />
                            <div class=" col-md-12">
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Purchase Qty</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="model_purchase_qty" id="model_purchase_qty" readonly placeholder="Purchase Qty" value="0" class="form-control" />
                                    </div>
                                </div>
                                <?php if ($page_type == 'Purchase') { ?>
                                    <div class="model-course-help">
                                        <label class="col-sm-3 control-label">Sold Qty</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="model_sold_qty" id="model_sold_qty" readonly placeholder="Sold Qty" value="0" class="form-control" />
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Total Return</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="model_tot_return_qty" id="model_tot_return_qty" readonly placeholder="Available Qty" value="0" class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Available Qty</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="model_available_qty" id="model_available_qty" readonly placeholder="Available Qty" value="0" class="form-control" />
                                    </div>
                                </div>
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Return Qty</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="model_return_qty" id="model_return_qty" placeholder="Return Qty" value="0" class="form-control" />
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
                <button type="button" onclick="return_product_ajax();" class="btn btn-primary confirm-class">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    async function get_inv_product_detail(invpro_id, pType) {
        let invProData;
        await $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: "get_return_product_detail_ajax",
                invpro_id: invpro_id,
                pType: pType,
            },
            success: function(result) {
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success') {
                    invProData = objResponse.data
                }
            }
        });
        return invProData
    }

    window.return_process = async function(invpro_id, pType) {
        $("#model_invpro_id").val(invpro_id);
        let data = await get_inv_product_detail(invpro_id, pType)

        $("#model_purchase_qty").val(data.invpro_pro_qty);
        $("#model_tot_return_qty").val(data.proret_return_pro_qty);
        $("#model_sold_qty").val(data.invpro_pro_qty_sold);
        if (pType == 'P') {
            let available_qty = data.invpro_pro_qty - data.proret_return_pro_qty - data.invpro_pro_qty_sold
            $("#model_available_qty").val(available_qty);
        } else {
            let available_qty = data.invpro_pro_qty - data.proret_return_pro_qty
            $("#model_available_qty").val(available_qty);
        }
        $('#return_product_model').modal('show');
    }

    function return_product_ajax() {

        if ($.isNumeric($("#model_return_qty").val()) == false) {
            alert("Return qty must be a numeric value");
            $("#model_return_qty").focus();
            return false;
        } else if ($("#model_return_qty").val() <= 0) {
            alert("Please enter return qty.");
            $("#model_return_qty").focus();
            return false;
        } else if ($("#model_return_qty").val() > ($("#model_available_qty").val())) {
            alert("Return qty should not be more than available qty.");
            $("#model_return_qty").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: $("#frmreturnproduct").serialize(),
            cache: false,
            async: false,
            success: function(result) {
                r_response = result;
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success') {
                    let url = $("#purchase_sale_type").val() == 'Purchase' ? "manage_return_item_purchase.php" : "manage_return_item_sale.php";
                    url += '<?php echo "?page=$page&inv_purchase_del_id=$inv_purchase_del_id&per_page=$per_page&order%20by=$order_by&order=$order" ?>';
                    window.location.href = url;
                } else {
                    alert(objResponse.errormsg);
                }
            }
        });

        return false;
    }
</script>