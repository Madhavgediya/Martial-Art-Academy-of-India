<?php
include("includes/application_top.php");
$invpro_id_purchase_string = update_invoice_product_stock("0,",6);
update_purchase_order($invpro_id_purchase_string.",");
exit(0);
echo "</br>".getIndianCurrency(1);
echo "</br>".getIndianCurrency(10);
echo "</br>".getIndianCurrency(100);
echo "</br>".getIndianCurrency(1000);
echo "</br>".getIndianCurrency(1001);
exit(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
        <title><?php echo $page_title; ?></title>
<?php include("includes/include_files.php"); ?>

<script type="text/javascript" lang="javascript">
function show_add_course(stu_id)
{
    $("#a_stu_id").val(stu_id);
    $("#a_sc_be_id").val(0);
    $("#a_sc_co_id").val(0);
    $("#a_sc_brt_id").val(0);
    $('#course_details').modal('show');
    // $("#course_details").find(".modal-title").html("Delete " + option);
    // $("#course_details").find(".modal-body").html("<p>Please wait</p>");
    // $(".confirm-class").attr("onclick", "delete_data(" + id + ")");
}


$( document ).ready(function() {
    console.log( "ready!" );
    alert("read");
  //  get_student_data();
});

</script>

    </head>
    <body class="skin-green sidebar-mini">

        </body>
</html>
