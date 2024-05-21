<?php
include("includes/application_top.php");
include("../includes/class/book_issue_history.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");

$bi_book_id = get_rdata('bi_book_id');
$bi_stu_id = get_rdata('bi_stu_id');
$bi_issue_date  = get_rdata('bi_issue_date', $cur_date_only);
$bi_issue_date_valid = get_rdata('bi_issue_date_valid', convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date . BOOK_ISSUE_PERIOD))));
$bi_br_id = get_rdata('bi_br_id', $tmp_admin_id);

$bi_create_date = $cur_date;
$bi_create_by_id = $tmp_admin_id;
$bi_update_date = $cur_date;
$bi_update_by_id = $tmp_admin_id;
$bi_status = "Issued";


$caption = "Issue Book";
$btn_caption = "Issue Book";
if ($id != 0) {
    $caption = "Issue Book";
    $btn_caption = "Issue Book";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Book Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Book Has Been issued Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Book Has Been Updated Successfully";
} else {
    $successmsg = '';
}

// Add user entry
if ($act == 'add') {

    $q = "SELECT count(*) as count_s FROM sm_book WHERE book_issue_stu_id !=0 AND book_id  = " . $bi_book_id;
    $arr_duplicate =  found_duplicate_free($q);
    if ($arr_duplicate["errormsg"] != '') {
        $errormsg = $arr_duplicate["errormsg"];
    } else if ($arr_duplicate['duplicate'] == true) {
        $errormsg = "Book is already issued";
    } else {
        $sm_book_issue_history = new book_issue_history();

        $sm_book_issue_history->data["bi_book_id"] = $bi_book_id;
        $sm_book_issue_history->data["bi_stu_id"] = $bi_stu_id;
        $sm_book_issue_history->data["bi_issue_date"] = convert_disp_to_db_date($bi_issue_date);
        $sm_book_issue_history->data["bi_issue_date_valid"] = convert_disp_to_db_date($bi_issue_date_valid);
        $sm_book_issue_history->data["bi_status"] = $bi_status;
        $sm_book_issue_history->data["bi_admin_id"] = $tmp_admin_id;
        $sm_book_issue_history->data["bi_br_id"] = $bi_br_id;
        $sm_book_issue_history->data["bi_create_date"] = $bi_create_date;
        $sm_book_issue_history->data["bi_create_by_id"] = $bi_create_by_id;
        $sm_book_issue_history->data["bi_update_date"] = $bi_update_date;
        $sm_book_issue_history->data["bi_update_by_id"] = $bi_update_by_id;

        $sm_book_issue_history->action = 'insert';
        $result = $sm_book_issue_history->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            $q_book_update = "UPDATE sm_book SET book_issue_stu_id  = " . $bi_stu_id . ", book_issue_date = '" . convert_disp_to_db_date($bi_issue_date) . "' WHERE book_id = " . $bi_book_id . " AND book_br_id =" . $bi_br_id;
            $r_book_update = m_process("update", $q_book_update);
            if ($r_book_update["status"] == "failure") {
                $errormsg = $r_book_update['errormsg'];
            } else {
                header('Location:issue_book.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}

$q_books = "SELECT b.*,s.stu_first_name,s.stu_last_name,s.stu_phone FROM sm_book b LEFT JOIN sm_student s ON (b.book_issue_stu_id =  s.stu_id) WHERE b.book_br_id = " . $tmp_admin_id . " AND b.book_id  = $id ";

$result_books = m_process("get_data", $q_books);
if ($result_books['errormsg'] != '') {
    $errormsg = $result_books['errormsg'];
} else {
    $books_count = $result_books['count'];
}
// echo 'error'.$errormsg.'end of error';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport" />
    <title><?php echo $page_title; ?></title>
    <?php include("includes/include_files.php"); ?>
</head>

<body class="skin-green sidebar-mini">
    <div class="wrapper">

        <?php include("includes/header.php"); ?>
        <?php include("includes/left_menu.php"); ?>
        <!-- our page -->

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo $caption; ?>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"><?php echo $caption; ?></li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php include("includes/messages.php"); ?>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Horizontal Form -->
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $caption; ?></h3>
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                <input type="hidden" id="act" name="act" />
                                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                <div class="box-body">
                                    <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-9">
                                                <select class="form-control select2" id="bi_book_id" name="bi_book_id" style="width: 100%;">
                                                    <option value="0">--Please select--</option>
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = 'book_title ,book_id';
                                                    $data_arr_input['table'] = 'sm_book';
                                                    $data_arr_input['where'] = " book_br_id = " . $tmp_admin_id . " AND book_status  = 'A' ";
                                                    $data_arr_input['key_id'] = 'book_id';
                                                    $data_arr_input['key_name'] = 'book_title';
                                                    $data_arr_input['current_selection_value'] = $bi_book_id;
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>

                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Student</label>
                                            <div class="col-sm-9">
                                                <select class="form-control select2" id="bi_stu_id" name="bi_stu_id" style="width: 100%;">
                                                    <option value="0">--Please select--</option>
                                                    <?php
                                                    $data_arr_input = array();
                                                    $data_arr_input['select_field'] = ' CONCAT(stu_first_name," ",stu_last_name," ",stu_gr_no ) as stu_name ,stu_id';
                                                    $data_arr_input['table'] = 'sm_student';
                                                    $data_arr_input['where'] = " stu_br_id = " . $tmp_admin_id . " AND stu_status  = 'A' ";
                                                    $data_arr_input['key_id'] = 'stu_id';
                                                    $data_arr_input['key_name'] = 'stu_name';
                                                    $data_arr_input['current_selection_value'] = $bi_stu_id;
                                                    display_dd_options($data_arr_input);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>




                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Issue Date</label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" name="bi_issue_date" id="bi_issue_date" placeholder="Issue Date" value="<?php echo $bi_issue_date; ?>" class="form-control ma-input-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Return Date</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly name="bi_issue_date_valid" id="bi_issue_date_valid" placeholder="Return Date" value="<?php echo $bi_issue_date_valid; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box -->
                                <div class="box-footer">
                                    <?php if ($id == 0) { ?> <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                    <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                </div><!-- /.box-footer -->
                            </form>
                            <!-- general form elements disabled -->
                            <div>
                            </div>
                        </div>
            </section>
        </div>
        <!-- end of our page-->
        <script>
            //Initialize Select2 Elements
            $('.select2').select2();

            $("#bi_issue_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            }).on('changeDate', function(e) {
                let newDate = new Date(e.date);
                newDate.setDate(newDate.getDate() + 15);
                $("#bi_issue_date_valid").val(getFormattedDate(newDate))
            });

            $("#bi_issue_date_valid").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                disabled
            });

            function getFormattedDate(date) {
                let year = date.getFullYear();
                let month = (1 + date.getMonth()).toString().padStart(2, '0');
                let day = date.getDate().toString().padStart(2, '0');

                return day + '-' + month + '-' + year;
            }
        </script>
        <?php include("includes/footer.php"); ?>
    </div>
</body>

</html>