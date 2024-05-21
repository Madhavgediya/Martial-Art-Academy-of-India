<div class="modal" id="add_color_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Color</h4>
            </div>
            <div class="modal-body">
                <!-- start of showing details -->
                <div class="box">
                    <div class="box-body">
                        <form id="frmaddcolor" name="frmaddcolor" onsubmit="return false;">
                            <input type="hidden" id="action" name="action" value="add_color_ajax" />
                            <div class=" col-md-12">
                                <div class="model-course-help">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="color_name" id="color_name" placeholder="Name" value="" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end of showing details -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" onclick="add_color_ajax();" class="btn btn-primary confirm-class">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" lang="javascript">
    function add_color() {
        $("#color_name").val("");
        $('#add_color_model').modal('show');
    }

    function add_color_ajax() {
        if ($("#color_name").val() == "") {
            alert("Please enter color name");
            $("#color_name").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: $("#frmaddcolor").serialize(),
            cache: false,
            async: false,
            success: function(result) {
                r_response = result;
                result = $.trim(result);
                var objResponse = jQuery.parseJSON(result);
                if (objResponse.status == 'success') {
                    $('.inv_product_option_2').each(
                        function() {
                            cur_val = $(this).val();
                            $(this).html(objResponse.data_color);
                            $(this).val(cur_val);
                            $(this).trigger('change.select2');
                        }
                    );
                    alert(objResponse.data);
                    $('#add_color_model').modal('hide');

                } else {
                    alert(objResponse.errormsg);
                }
            }
        });

        return false;
    }
</script>