// JavaScript Document
/*
finding object all details
   $("#"+row_id).find(".inv_product").each(function() {
  $.each(this.attributes, function() {
    // this.attributes is not a plain object, but an array
    // of attribute nodes, which contain both the name and value
    if(this.specified) {
      console.log(this.name, this.value);
    }
  });
});
*/
function return_vall_atttributes(object_id)
{
    $("#"+object_id).find(".inv_product").each(function() {
        $.each(this.attributes, function() {
          // this.attributes is not a plain object, but an array
          // of attribute nodes, which contain both the name and value
          if(this.specified) {
            console.log(this.name, this.value);
          }
        });
      });
}
function ml(m)
{
    console.log(m);
}
function js_m_process_show()
{
    $(".preloader-overlay").show();
    $("#m_process").show();
}

function js_m_process_hide()
{
  $(".preloader-overlay").hide();
    $("#m_process").hide();
}
function validate_data_ajax()
{
    if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }
}
function validate_data()
{
    if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }
}
function custom_alert(title,value){
    if(title == '')
    {
        title = "Warning";
    }
    $("#ConfirmAlert").find(".modal-title").addClass('alert_'+title.toLowerCase());
    $("#ConfirmAlert").find(".modal-header").addClass('alert_border_'+title.toLowerCase());
    $("#ConfirmAlert").find(".modal-title").html(title);
    $("#ConfirmAlert").find(".modal-body").html(value);
    $('#ConfirmAlert').modal('show');

}
function Go_toPage(page_no)
{
    $("#page_no").val(page_no);
    $("#formGeneral").submit();
}

function Go_Pagesize()
{
    $("#formGeneral").submit();
}

function validate_user()
{
    if ($("#id").val() == 0)
    {
      if ($("#sc_brt_id").val()==0 )
      {
            alert("Please select Batch Type");
            $("#sc_brt_id").focus();
            return false;
      }
      else if ($("#sc_co_id").val()==0 )
      {
            alert("Please select Course");
            $("#sc_co_id").focus();
            return false;
      }
      else if ($("#sc_be_id").val()==0)
      {
            alert("Please select Belt");
            $("#sc_be_id").focus();
            return false;
      }
        $("#act").val('add');
  }
    else
    {
        $("#act").val('update');
    }

}

function validate_add_edit_events()
{
    // code will change the sequence of files
     var c=1;
    $('.extra_files').each(
                function () {
                $(this).attr("name",'event_details_pic_'+c);
                c++;
                }
        );
        c--;
        $("#file_counter").val(c);


    if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }

}

function validate_add_edit_form()
{
    if ($("#act").val()!='delete')
    {
        if ($("#id").val() == 0)
            $("#act").val('add');
        else
        {
            $("#act").val('update');
        }
    }
}

function delete_record(id, option)
{
    del_msg = option;
    option = option.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
    $("#ConfirmDelete").find(".modal-title").html("Delete " + option);
    $("#ConfirmDelete").find(".modal-body").html("<p>Are you sure you want to delete this " + del_msg + "?</p>");
    $(".confirm-class").attr("onclick", "delete_data(" + id + ")");
}
function delete_data(id) {
    $("#act").val('delete');
    $("#id").val(id);
    $("#form1").submit();
    $('#ConfirmDelete').modal('hide');
}
//function delete_record(id)
//{
//    //alert('calling me');
//    var r = confirm("Are you sure to delete this record?");
//    if (r == true)
//    {
//        //alert('press submit');
//        $("#act").val('delete');
//        $("#id").val(id);
//        $("#form1").submit();
//
//    }
//    else
//    {
//        return false;
//    }
//
//}

function delete_user(id)
{
    //alert('calling me');
    var r = confirm("Are you sure to delete this user?");
    if (r == true)
    {
        //alert('press submit');
        $("#act").val('delete');
        $("#id").val(id);
        $("#form1").submit();

    }
    else
    {
        return false;
    }

}

function jGrowlMsg(msg, theme) {
    $.jGrowl("<strong>" + msg + "<strong>", {
        theme: theme
    });
}

/* procssing more than one files at one place*/
 var counter = 1;
function add_files()
{
    $('#add_more').before('<div><input type="file" class="extra_files" name="f_'+counter+'" id="f_'+counter+'" /><a href="javascript:void(0);" class="remove" id="f_'+counter+'_r" onclick="remove_f_me(\'f_'+counter+'\')">Remove</a></div>');
    counter++;
}
function remove_f_me(arg)
{
    $('#'+arg+'').remove();
    $('#'+arg+'_r').remove();
}

function delete_image(action,p_id)
{
    $('#dv_process_'+p_id).show();
    // $('#a_process_'+ei_id).remove();

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: "action="+action+"&p_id=" + p_id,
        success: function (result) {
            result = $.trim(result);
            result_arr = result.split("###");
            alert(result_arr);

            // if ($.trim(result_arr[0])=='S')
            // {
            //     $('#tr_'+$.trim(result_arr[1])).remove();
            // }
            // else
            // {
            //      $('#dv_process_'+$.trim(result_arr[1])).hide();
            //      alert(result_arr[2]);
            // }
        }
    });

}
/* end of procssing more the one file*/

function validate_add_edit_gallery()
{
    // code will change the sequence of files
     var c=1;
    $('.extra_files').each(
                function () {
                $(this).attr("name",'gallery_details_pic_'+c);
                c++;
                }
        );
        c--;
        $("#file_counter").val(c);


    if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }

}
function set_var_value(id,value,submitform)
{
    $("#"+id).val(value);
    if (submitform==1)
    {
        $("#form1").submit();
    }
}

function set_standard_class(std_id,std_name,cl_id,cl_name)
{
    $("#std_id").val(std_id);
    $("#std_name").val(std_name);
    $("#cl_id").val(cl_id);
    $("#cl_name").val(cl_name);
    $("#act").val('allow-attendance');
    $("#form1").submit();
}
function process_batch_processing()
{
    if ($("#act").val()=='process-attendance')
    {
        if ( ($("#std_id").val() == '') || ($("#cl_id").val() == '') || ($("#cl_name").val() == '') || ($("#std_name").val() == '')  )
        {
            alert('Please select Medium and Standard and Class ');
            return false;
        }
        else if ($("#attend_date").val() == '')
        {
            alert('Please select date ');
            return false;
        }
        else if ($("#abs_ids").val() == '')
        {
            alert('Please enter absent Roll nos ');
            return false;
        }
        // $("#form1").submit();
    }

   // return false;
}

function fill_studnet()
{

    if ($("#res_cl_id").val() != '' && $("#res_std_id").val() != '' && $("#res_medium").val() != ''  )
    {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "action=fill_student&res_medium=" + $("#res_medium").val()+"&res_std_id="+$("#res_std_id").val()+"&res_cl_id="+$("#res_cl_id").val(),
            success: function (result) {
                result = $.trim(result);
                result_arr = result.split("###");
                if ($.trim(result_arr[0])=='S')
                {
                       $("#res_stu_id").html(result_arr[1]);
                }
                else
                {
                    custom_alert("Warning",result_arr[1]);
                }
            }
        });
    }
}

function validate_add_edit_circular()
{
    // code will change the sequence of files
     var c=1;
    $('.extra_files').each(
                function () {
                $(this).attr("name",'circular_details_pic_'+c);
                c++;
                }
        );
        c--;
        $("#file_counter").val(c);


    if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }

}

function get_attendance_details()
{

    if ($("#stu_medium").val() != '' && $("#std_id").val() != '' && $("#cl_id").val() != '' && $("#sc_id").val() != '' && $("#attend_date").val() != ''  )
    {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "action=get_attendance_details&stu_medium=" + $("#stu_medium").val()+"&std_id="+parseInt($("#std_id").val())+"&cl_id="+parseInt($("#cl_id").val())+"&sc_id="+parseInt($("#sc_id").val())+"&attend_date="+$("#attend_date").val(),
            success: function (result) {
                result = $.trim(result);
                result_arr = result.split("###");

                if ($.trim(result_arr[0])=='E')
                {
                       custom_alert("Warning",result_arr[1]);
                }
                else
                {
                    arr_roll = result_arr[1].split("##");
                    $("#attendance_data").html("<b>Absent Role Nos. :</b> "+arr_roll[1]+"   <b>Present Role Nos. :</b> "+arr_roll[0]);
                }
            }
        });
    }
    else
    {
                    custom_alert("Warning",'Please select medium/Standard/Class/Date');
    }
}

function validate_exam()
{
  $.ajax({
      type: "POST",
      url: "ajax.php",
     data: { action: "validate-exam-details", ex_name : $("#ex_name").val(),ex_name : $("#ex_name").val(),ex_date : $("#ex_date").val(),ex_id : $("#id").val(),ex_br_id : $("#ex_br_id").val()} ,
     success: function (result) {
          result = $.trim(result);
          var objResponse = jQuery.parseJSON(result);
           if (objResponse.status !='success')
          {
            alert(objResponse.errormsg);
            return false;
          }
          else
          {
            if ($("#id").val() == 0)
                $("#act").val('add');
            else
            {
                $("#act").val('update');
            }
            $("#form1").submit();
        }
      }
  });
}


function validate_event()
{
  $.ajax({
      type: "POST",
      url: "ajax.php",
     data: { action: "validate-event-details", ev_name : $("#ev_name").val(),ev_date : $("#ev_date").val(),ev_id : $("#id").val(),ev_br_id : $("#ev_br_id").val()} ,
     success: function (result) {
          result = $.trim(result);
          var objResponse = jQuery.parseJSON(result);
          if (objResponse.status !='success')
          {
            alert(objResponse.errormsg);
            return false;
          }
          else
          {
            if ($("#id").val() == 0)
                $("#act").val('add');
            else
            {
                $("#act").val('update');
            }
            $("#form1").submit();
        }
      }
  });
}
function validate_belt()
{
  var categories_count = $('.exam_categories').length;
  if (categories_count == 0)
  {
    alert("no exam categories to process");
    return false;
  }
  $.ajax({
      type: "POST",
      url: "ajax.php",
     data: { action: "validate-belt-details", be_name : $("#be_name").val(),be_id : $("#id").val(),be_br_id : $("#be_br_id").val()} ,
     success: function (result) {
          result = $.trim(result);
          var objResponse = jQuery.parseJSON(result);
          if (objResponse.status !='success')
          {
            alert(objResponse.errormsg);
            return false;
          }
          else
          {
            if ($("#id").val() == 0)
                $("#act").val('add');
            else
            {
                $("#act").val('update');
            }
            $("#form1").submit();
        }
      }
  });
}
function student_exam_enrollment_process()
{
    console.log("asd");
    debugger;
    if (confirm("Are you sure you would like to process?")) {
        $("#act").val('enrollstudent');
        $("#all_ids").val($(".enrollment_check").map(function(){return this.value.split("_")[0];}).get().join(","));
        $("#form_enrollment").submit();
    }
}
function check_uncheck_enrollment_check()
{
if ($('#enrollment_check_all').prop('checked') == true)
{
  $( ".enrollment_check" ).each(function( index ) {
    if ($(this).attr('readonly') != 'readonly')
    { $(this).prop('checked', true);  }
});
}
else {
  $( ".enrollment_check" ).each(function( index ) {
      if ($(this).attr('readonly') != 'readonly')
    { $( this ).prop('checked', false); }
});
}
}
function show_hide_deactivation_date(arg)
{

  if (arg == 'show')
  {
    $("#lbl_active_inactive").text("Deactivation Date");
    // $("#employee_inactive_date").show();

}
  else {
    $("#lbl_active_inactive").text("Activation Date");
    // $("#employee_inactive_date").hide();
}

}
function print_receipt()
{
  $(".remove_print_button").remove();
  window.print();
}
function do_exam_attendance()
{
  $('#process_btn').addClass('ma-btn-disable');
  $("#act2").val("process_attendance");
}
function mark_all_attendance_present()
{
  $( ".cl_student_attendance" ).each(function( index ) {
      $(this).val('P');
  });
}
function print_fee_receipt(arg,pt_id,exs_id)
{
   window.open("print_receipt.php?exs_id="+exs_id+"&id="+pt_id+"&type="+arg,"Receipt","status=1,width=800");
}

function print_invoice_js()
{
    $(".remove_print_button").remove();
    window.print();
}

function get_belt_details_from_course(co_id,be_id)
{
    if($("#"+co_id).val() == 0)
    {
        alert("Please select course");
        return false;
    }
    else
    {
    $.ajax({
        type: "POST",
        url: "ajax.php",
       data: { action: "get_course_wise_belts_ajax", a_sc_co_id : $("#"+co_id).val()} ,
       success: function (result) {
            result = $.trim(result);
            var objResponse = jQuery.parseJSON(result);
            if (objResponse.status !='success')
            {
              alert(objResponse.errormsg);
              return false;
            }
            else
            {
                $("#"+be_id).html(objResponse.data);
            }
        }
    });
    }
}

// stat purchase page jquery
function update_invoice_total()
{
    var grand_total = 0;
   // debugger;
    var inv_discount_amount = inv_additional_amount  =  0;

    if ($("#inv_discount_amount").val() !='' && !Number.isNaN($("#inv_discount_amount").val()))
    {
      inv_discount_amount =  parseFloat($("#inv_discount_amount").val()).toFixed(2);
    }

    if ($("#inv_additional_amount ").val() !='' && !Number.isNaN($("#inv_additional_amount ").val()))
    {
      inv_additional_amount  =  parseFloat($("#inv_additional_amount ").val()).toFixed(2);
    }

    grand_gst =grand_sgst = grand_cgst =  0;

    var m= 1;
    
    $('.product-options').each(function () 
    {
//        if ($(this).find(".inv_gst").val() !='' && !Number.isNaN($(this).find(".inv_gst").val()) && $(this).find(".inv_gst").val() >0 )
        if ($(this).find(".inv_gst").val() !='' && !Number.isNaN($(this).find(".inv_gst").val()) )
        {
            if ( ($(this).find("#product_qty").val() !='' && !Number.isNaN($(this).find("#product_qty").val()) && $(this).find("#product_qty").val() > 0 ) && ($(this).find("#product_price").val() !='' && !Number.isNaN($(this).find("#product_price").val()) && $(this).find("#product_price").val() > 0 ) )
            {
              product_qty_local = parseFloat($(this).find("#product_qty").val()).toFixed(2);
              product_price_local = parseFloat($(this).find("#product_price").val()).toFixed(2);
              product_total_price_local = product_qty_local*product_price_local;
              grand_total = grand_total + product_total_price_local;
              ml("inside console log"+"product price"+product_price_local+"product qty"+product_qty_local+"product total price"+product_total_price_local+" grand total "+grand_total);  
                grand_gst_local = parseFloat(product_total_price_local *100);
                grand_gst_local_division = parseFloat(100+parseFloat($(this).find(".inv_gst").val())).toFixed(2);
                grand_gst_local = (grand_gst_local/grand_gst_local_division).toFixed(2);
                grand_gst_local = parseFloat(product_total_price_local)-grand_gst_local;
                grand_gst = parseFloat(grand_gst) + parseFloat(grand_gst_local.toFixed(2));
            }       
            else 
            {
                ml("else condition");  
            }
        }
        else
        {
         //   console.log("M-4");
        }
        m++;
    });


    grand_gst = parseFloat(grand_gst).toFixed(2);
    grand_total  = parseFloat(parseFloat(grand_total).toFixed(2));
    grand_sgst = parseFloat(parseFloat(grand_gst/2).toFixed(2));
    grand_cgst = parseFloat(parseFloat(grand_gst/2).toFixed(2));
    
    grand_total = grand_total - grand_cgst  - grand_sgst ;
    grand_total = parseFloat(grand_total).toFixed(2);

    $("#grand_total").text(grand_total);
    
    /*if (inv_discount_amount> (grand_total + inv_additional_amount) )
    {
        console.log("AAAAAAAAA");
        console.log("AAAAAAAAA");
        console.log("AAAAAAAAA");
        alert("Discount is more then invoice amount");
        return false;
    }*/
    clone_final_price = parseFloat(grand_total) + parseFloat(inv_additional_amount) - parseFloat(inv_discount_amount)  + parseFloat(grand_cgst)  + parseFloat(grand_sgst) ;
    clone_final_price = parseFloat(clone_final_price).toFixed(2);
    $("#clone_final_price").text(clone_final_price);
    $("#clone_final_cgst").text(grand_cgst);
    $("#clone_final_sgst").text(grand_sgst);
    $("#clone_final_igst").text(grand_gst);
    if ($("#inv_additional_amount").val() !='' && !Number.isNaN($("#inv_additional_amount").val()) && $("#inv_additional_amount").val() >0 )
    {
        $("#clone_additional_amount").text($("#inv_additional_amount").val());
    }
    if ($("#inv_discount_amount").val() !='' && !Number.isNaN($("#inv_discount_amount").val()) && $("#inv_discount_amount").val() >0 )
    {
        $("#clone_discount_amount").text($("#inv_discount_amount").val());
    }
}

// stat purchase page jquery
function update_gst(arg)
{
    var page_type = $.trim($("#page_type").val()); 
    var pro_id_m = grand_total = inv_discount_amount = inv_additional_amount  =  0;
    var pro_id_m  = $(arg).closest(".parent_class").find("#product_name").val();
   var qty = 1;
    if ($(arg).closest(".parent_class").find("#product_qty").val() !='' && !Number.isNaN($(arg).closest(".parent_class").find("#product_qty").val()))
    {
        qty = parseInt($(arg).closest(".parent_class").find("#product_qty").val());
    }
    
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "action=get-product-gst&page_type="+page_type+"&pro_id=" + $(arg).closest(".parent_class").find("#product_name").val(),
            success: function (result) {
                result = $.trim(result);
                result = parseFloat(result,2) ;
                var gst_price = result;
                $(arg).closest(".parent_class").find("#product_gst").val(gst_price);       
            }
        });
}
function update_price(arg)
{

    if (arg.val() != 0)
    {
         // var p_price =  0 arr_product[arg.val()][1];
        var p_price = p_qty =  p_net_price = 0 ;
//        var p_max_qty_available = arr_product[arg.val()][0];
        var p_max_qty_available = 100000;

       
        if (arg.closest(".parent_class").find("#product_price").val() !='' && !Number.isNaN(arg.closest(".parent_class").find("#product_price").val()))
        {
       
          p_price = parseFloat(arg.closest(".parent_class").find("#product_price").val()).toFixed(2);
        }

        if (arg.closest(".parent_class").find("#product_qty").val() && !Number.isNaN(arg.closest(".parent_class").find("#product_qty").val()))
        {
       
          p_qty = parseInt(arg.closest(".parent_class").find("#product_qty").val());
        }

        p_net_price = (p_qty * p_price).toFixed(2);

        arg.closest(".parent_class").find("#product_price").val(p_price);
        arg.closest(".parent_class").find("#product_net_price").val(p_net_price);
        update_invoice_total();
    }
}

function product_option_append()
{
    $("#div_product_option_0").clone().insertAfter("div.product-options:last");
    $('.product-options:last').show();
    $('.product-options:last input[type="text"]').val('');
}

function product_remove_option(arg_control)
{
    arg_control.closest(".product-options").remove();
}

// called on add invoice page
function product_invoice_append()
{
    $("#div_product_option_0").clone().insertAfter("div.product-options:last");
    $('.product-options:last').show();
    
    
    $('.product-options:last').attr("id","div_product_option_"+next_id);
    
    $('.product-options:last input[type="text"]').val('');
    $(".inv_product").change(function () {
        update_gst(this);
         update_price($(this).closest(".parent_class").find(".inv_product"));
        update_gst($(this).closest(".parent_class").find(".inv_product"));
        update_invoice_total();
    });

    $(".inv_pro_price").change(function () {
        update_price($(this).closest(".parent_class").find(".inv_product"));
        update_gst($(this).closest(".parent_class").find(".inv_product"));
        // Check input( $( this ).val() ) for validity here
        update_invoice_total();
    });

    $(".inv_qty").change(function () {
        update_price($(this).closest(".parent_class").find(".inv_product"));
        update_gst($(this).closest(".parent_class").find(".inv_product"));
        update_invoice_total();
    });

    // danger code end
    
    $(".pro_net_price").change(function () {
        update_invoice_total();
    });

    $("#div_product_option_"+next_id + " #product_name").select2({
        "language": {
            "noResults": function(){
                var noFound = $.parseHTML(`
                    <div>
                        <div>No results found</div>
                        <input type="button" value="Add Product" class="btn btn-info ma-select2-addbtn" onclick="add_product()" />
                    </div>
                `)
                return noFound;
            }
        },
        dropdownCssClass: "ma-select2-dropdown"
    });
    $("#div_product_option_"+next_id + " #product_option").select2({
        "language": {
            "noResults": function(){
                var noFound = $.parseHTML(`
                    <div>
                        <div>No results found</div>
                        <input type="button" value="Add Size" class="btn btn-info ma-select2-addbtn" onclick="add_size()" />
                    </div>
                `)
                return noFound;
            }
        },
        dropdownCssClass: "ma-select2-dropdown"
    });
    $("#div_product_option_"+next_id + " #product_option_2").select2({
        "language": {
            "noResults": function(){
                var noFound = $.parseHTML(`
                    <div>
                        <div>No results found</div>
                        <input type="button" value="Add Color" class="btn btn-info ma-select2-addbtn" onclick="add_color()" />
                    </div>
                `)
                return noFound;
            }
        },
        dropdownCssClass: "ma-select2-dropdown"
    });
    $("#div_product_option_"+next_id + " #invpro_used").select2();

   next_id = next_id +1;
}

// called on add invoice page
function product_remove_invoice(arg_control)
{
    //alert(arg_control);
    arg_control.closest(".product-options").remove();
    update_invoice_total();
}


// end purchase page jquery
function validate_data_invoice()
{

        var grand_total = 0;
    var inv_discount_amount = inv_additional_amount  =  0;
    if ($("#inv_discount_amount").val() !='' && !Number.isNaN($("#inv_discount_amount").val())) {
        inv_discount_amount =  parseFloat($("#inv_discount_amount").val()).toFixed(2);
    }
    if ($("#inv_additional_amount ").val() !='' && !Number.isNaN($("#inv_additional_amount ").val())) {
        inv_additional_amount  =  parseFloat($("#inv_additional_amount ").val()).toFixed(2);
    }
    grand_gst =grand_sgst = grand_cgst =  0;
    var m= 1;    
    $('.product-options').each(function () {
        if ($(this).find(".inv_gst").val() !='' && !Number.isNaN($(this).find(".inv_gst").val()) ) {
            if ( ($(this).find("#product_qty").val() !='' && !Number.isNaN($(this).find("#product_qty").val()) && $(this).find("#product_qty").val() > 0 ) && ($(this).find("#product_price").val() !='' && !Number.isNaN($(this).find("#product_price").val()) && $(this).find("#product_price").val() > 0 ) ) {
                product_qty_local = parseFloat($(this).find("#product_qty").val()).toFixed(2);
                product_price_local = parseFloat($(this).find("#product_price").val()).toFixed(2);
                product_total_price_local = product_qty_local*product_price_local;
                grand_total = grand_total + product_total_price_local;

                grand_gst_local = parseFloat(product_total_price_local *100);
                grand_gst_local_division = parseFloat(100+parseFloat($(this).find(".inv_gst").val())).toFixed(2);
                grand_gst_local = (grand_gst_local/grand_gst_local_division).toFixed(2);
                grand_gst_local = parseFloat(product_total_price_local)-grand_gst_local;
                grand_gst = parseFloat(grand_gst) + parseFloat(grand_gst_local.toFixed(2));
            }    
        }
        m++;
    });
    grand_gst = parseFloat(grand_gst).toFixed(2);
    grand_total  = parseFloat(parseFloat(grand_total).toFixed(2));
    grand_sgst = parseFloat(parseFloat(grand_gst/2).toFixed(2));
    grand_cgst = parseFloat(parseFloat(grand_gst/2).toFixed(2));
    
    grand_total = grand_total - grand_cgst  - grand_sgst ;
    grand_total = parseFloat(grand_total).toFixed(2);
    if (inv_discount_amount> (grand_total + inv_additional_amount) ) {
        alert("Discount is more then invoice amount");
        return false;
    }
    
    var mid = $("#id").val();  // Purchase
    var page_type = $("#page_type").val();  // Purchase
    var inv_sale_type = $("#inv_sale_type").val();  // C = Customer and S= Student
    var inv_purchase_del_id = $("#inv_purchase_del_id").val();
    if (page_type == 'Sale' && inv_sale_type == 'C') 
    {
        inv_purchase_del_id = $("#inv_purchase_del_id_other").val();
    }
    var errormessage_dealer = 'dealer';
    if (page_type == 'Sale' && inv_sale_type == 'S')
    {
        errormessage_dealer = 'student';
    }
    else if (page_type == 'Sale' && inv_sale_type == 'C') 
    {
        errormessage_dealer = 'student';
    }
   if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }
    if (page_type == 'Purchase' || (page_type == 'Sale' && inv_sale_type == 'S'))
    {
        if (inv_purchase_del_id=="" || inv_purchase_del_id==0)
        {
            alert("Please select "+errormessage_dealer);
            return false;
        }
    }
    else if (page_type == 'Sale' && inv_sale_type == 'C')
    {
        if (inv_purchase_del_id=="" || inv_purchase_del_id==0)
        {
            alert("Please select "+errormessage_dealer);
            return false;
        }
    }
    var c1=0;
    var is_error = 0;
    $('.product-options').each( 
        function () {
            if (c1>0)
            {
                if ($(this).find("#product_name").val() == "" || $(this).find("#product_name").val() == 0 )
                {
                    alert("Please select product. (item:"+c1+")");
                    $(this).find("#product_name").focus();
                    is_error = 1;
                    return false;
                }
                if (page_type == 'Purchase' && ($(this).find("#product_option").val() == "" || $(this).find("#product_option").val() == 0 ))
                {
                    alert("Please select product option. (item:"+c1+")");
                    $(this).find("#product_option").focus();
                    is_error = 1;
                    return false;
                }
                if (page_type == 'Purchase' &&  ($(this).find("#product_option_2").val() == "" || $(this).find("#product_option_2").val() == 0 ))
                {
                    alert("Please select product option 2 (item:"+c1+")");
                    $(this).find("#product_option_2").focus();
                    is_error = 1;
                    return false;
                }
                if (Number.isNaN($(this).find("#product_qty").val()) || $(this).find("#product_qty").val() == "" || $(this).find("#product_qty").val() == 0 )
                {
                    alert("Please enter product quantity. (item:"+c1+")");
                    $(this).find("#product_qty").focus();
                    is_error = 1;
                    return false;
                }

                if (Number.isNaN($(this).find("#product_price").val()) || $(this).find("#product_price").val() == ""  )
                {
                    alert("Please enter product price. (item:"+c1+")");
                    $(this).find("#product_price").focus();
                    is_error = 1;
                    return false;
                }

                if (page_type == 'Sale' )
                {
                    var selected_product =$(this).find("#product_name option:selected").html();
                    var selected_product_arr =  selected_product.split("(");
                    var selected_product_arr_1 =  selected_product_arr[1].split(")");

                   
                    if (!Number.isNaN(selected_product_arr_1[0]))
                    {
                        if (!(mid !="" && mid !="0" && mid >0 ))
                        {
                            if (selected_product_arr_1[0] < $(this).find("#product_qty").val())
                            {
                            alert("Please enter the qty less than or equal to "+selected_product_arr_1[0]);
                            $(this).find("#product_qty").focus();
                            is_error = 1;
                            return false;
                            }
                        }
                    }
                    else
                    {
                        alert("Wrong product qty");
                        $(this).find("#product_qty").focus();
                        is_error = 1;
                        return false;
                    }
                
                    // validate for  sales qty                  
                }
            }    
            
        //    
            
        c1++;
        }
);
    if (is_error == 1)
    {  return false;  }
    js_m_process_show();
    var action_url = $("#ajax_page_url").val(); 
    var r_response = "";
    var frm_id = "form1";
    console.log($("#" + frm_id).serialize());
    var formData = $("#" + frm_id).serializeArray();
    formData.push({ name: "inv_purchase_del_id", value: $("#inv_purchase_del_id").val() });
    console.log(formData);
    $.ajax({
        type: "POST",
        url: action_url,
        data: formData,
        cache: false,
        async: false,
        success: function (result)
        {
            r_response = result;
        }
    });

    var result_arr = $.trim(r_response).split("##");
                    
    if ($.trim(result_arr[0]) == 'E')
    {
     //   console.log(result_a+ "###"+result_arr[1]);
     //   js_message("error",result_arr[1]);
    }
    else if ($.trim(result_arr[0]) == 'S')
    {
            window.location.href = $("#success_page_url").val()+"?msg=2";
    }
}

function validate_event_other()
{
    if ($("#id").val() == 0)
    {
        $("#act").val('add');
    }
    else
    {
        $("#act").val('update');
    }
}

function show_hide_fees(action)
{
  if (action=='hide' )
  {
    $("#by_batch").hide();
    $("#sc_brt_id").val(0);
    $("#sc_co_id").val(0);
    $("#sc_be_id").val(0);
    $("#stu_first_name").val('');
    $("#stu_last_name").val('');
    $("#stu_gr_no").val('');
   }
  if (action=='show' )
  {
     $("#by_batch").show();

   }
}

function validate_add_edit_form_act1()
{
    $("#act").val('');
    $("#act1").val('search');
    $("#form1").submit();
}

function student_certification_belt_process()
{
  var ans_v = confirm("Are you sure you would like to process?");
          if (ans_v == true)
          {
            $("#act").val('apply_belt_certificate');
        $("#form_enrollment").submit();
        }
}

function check_dealer_igst()
{
    if ($("#page_type").val() == "Purchase")
    {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "action=get-dealer-gst-type&inv_purchase_del_id=" + $("#inv_purchase_del_id").val(),
            success: function (result) {
                result = $.trim(result);
                if (result =='Y' )
                {
                        $("#main_final_igst").show();
                        $("#main_final_cgst").hide();
                        $("#main_final_sgst").hide();
                }
                else
                {
                    $("#main_final_cgst").show();
                    $("#main_final_sgst").show();
                    $("#main_final_igst").hide();
                }
            }
        });
    }
}
function check_customer_igst()
{
    if ($("#page_type").val() == "Sale")
    {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "action=get-customer-gst-type&inv_purchase_del_id_other=" + $("#inv_purchase_del_id_other").val(),
            success: function (result) {
                result = $.trim(result);
                if (result =='Y' )
                {
                        $("#main_final_igst").show();
                        $("#main_final_cgst").hide();
                        $("#main_final_sgst").hide();
                }
                else
                {
                    $("#main_final_cgst").show();
                    $("#main_final_sgst").show();
                    $("#main_final_igst").hide();
                }
            }
        });
    }
}
function print_attendance(arg_id,branch_name,action_reprt_name,branch_other)
{
    
    var prtContent = document.getElementById(arg_id);
    var headerHTML = '<div id="header_branch">'+branch_name+'</div>';
    var additional_script = '<script>';    
    var additional_style = '<style> table {  border-collapse: collapse;  } table, th, td {  border: 1px solid black; padding:2px 2px; }';
    
    if (action_reprt_name =='exam_student_enrolled_print')
    {
        var headerHTML = headerHTML + '<div id="header_main">Eligible For Exam Student List<span>['+branch_other+']</span></div>';
    }
    else if (action_reprt_name =='report_stock')
    {
        var headerHTML = headerHTML + '<div id="header_main">Stock</div>';
    }
    else if (action_reprt_name =='student_missing_details')
    {
        $("#print_me th:last-child, #print_me td:last-child").hide();
        var headerHTML = headerHTML + '<div id="header_main">Student Missing Details</div>';
        additional_script = additional_script + ' $("#print_me th:last-child, #print_me td:last-child").remove(); ';
    }
    else if (action_reprt_name =='course_pending_fee')
    {
        var headerHTML = headerHTML + '<div id="header_main">Pending Course Fee</div>';
    }
    else if (action_reprt_name =='exam_pending_fee')
    {
        var headerHTML = headerHTML + '<div id="header_main">Exam Pending Fee</div>';
    }
    else if (action_reprt_name =='event_pending_fee')
    {
        var headerHTML = headerHTML + '<div id="header_main">Event Pending Fee</div>';
    }
    else if (action_reprt_name =='report_student_attendance')
    {
        branch_other = $("#att_month option:selected").html()+ ' ';
        branch_other += $("#att_year option:selected").html();
        var headerHTML = headerHTML + '<div id="header_main">Student Attendance<span>['+branch_other+']</span></div>';
    }
    else if (action_reprt_name =='report_receipt')
    {
        var headerHTML = headerHTML + '<div id="header_main">Receipt</div>';
    }

    var WinPrint = window.open('', '', 'left=0,top=0,toolbar=0,scrollbars=0,status=0');
    
    additional_style = additional_style + ' #header_branch {  font-size:27px; text-transform: uppercase; margin-bottom:10px; } ';
    additional_style = additional_style + ' #header_main {  font-size:23px; margin-bottom:10px;  } #header_main span{  font-size: 14px; margin-left: 20px; display: inline-block; }';
    
    additional_style = additional_style + '</style>';
    
    
    additional_script = additional_script+'</script>';    
    WinPrint.document.write(additional_style);
    WinPrint.document.write(headerHTML);
    WinPrint.document.write(additional_script)
    WinPrint.document.write(prtContent.outerHTML);
    ;
   // WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();

    if (action_reprt_name =='student_missing_details' || action_reprt_name =='course_pending_fee')
    {
        $("#print_me th:last-child, #print_me td:last-child").show();
    }
//    WinPrint.close();
}

$(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
    //this.value = this.value.replace(/[^0-9\.]/g,'');
  $(this).val($(this).val().replace(/[^0-9\.]/g,''));
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
  });

  function export_data_js(form_id) {
    $(".export-modal").modal("hide");   
    $('#export_data').val("Export");  
    $('#'+form_id).attr("target","_blank");
    $('#'+form_id).submit();
    $('#export_data').val("");  
    $('#'+form_id).attr("target","");
  }

  function generate_pdf_js(form_id) {
    $(".export-modal").modal("hide");   
    $('#generate_pdf').val("PDF");  
    $('#'+form_id).attr("target","_blank");
    $('#'+form_id).submit();
    $('#generate_pdf').val("");  
    $('#'+form_id).attr("target","");
  }

  function export_data_js_2(form_id,type) {
    $(".export-modal").modal("hide");   
    $('#export_data').val(type);  
    $('#'+form_id).attr("target","_blank").submit();
    $('#export_data').val("");  
    $('#'+form_id).attr("target","");
     $(".export-modal-2").modal("hide");
  }

  function generate_pdf_js_2(form_id) {
    $(".export-modal").modal("hide");   
    $('#generate_pdf').val("PDF-2");  
    $('#'+form_id).attr("target","_blank").submit();
    $('#generate_pdf').val("");  
    $('#'+form_id).attr("target","");
  }

    function selectExportType(form_id) {
        $(".export-modal").modal("show");   
        $(`.export-content`).html(`<h3>Please Select Export Type</h3><br/><button type="button" onclick="export_data_js('form_enrollment');" class="btn btn-info">Excel</button>&ensp;&ensp;&ensp;<button type="button" onclick="generate_pdf_js('form_enrollment');" class="btn btn-warning">PDF</button>`);
    }
    
    function selectExportType2() {
        $(".export-modal-2").modal("show");   
        $(`.export-content-2`).html(`<h3>Please Select Result</h3><br/><button type="button" onclick="export_data_js_2('form_enrollment','detail');" class="btn btn-info">Detail</button>&ensp;&ensp;&ensp;<button type="button" onclick="export_data_js_2('form_enrollment','head');" class="btn btn-warning">Head</button>`);     
    }