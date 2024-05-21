// JavaScript Document
function Go_toPage(page_no)
{
    $("#page_no").val(page_no);
    $("#formGeneral").submit();
}

function Go_Pagesize()
{
    $("#formGeneral").submit();
}

function validate_project()
{
    if ($("#id").val() == 0)
        $("#act").val('add');
    else
    {
        $("#act").val('update');
    }
}

function delete_user(id)
{
    var r = confirm("Are you sure to delete this user?");
    if (r == true)
    {
        $("#act").val('delete');
        $("#id").val(id);
        $("#frmUser").submit();
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


