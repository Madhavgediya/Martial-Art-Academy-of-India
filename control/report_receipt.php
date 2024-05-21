<?php
include("includes/application_top.php");
include("../includes/class/exam.php");
$page_title = "Report Receipt";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$pt_receipt_no = get_rdata('pt_receipt_no');
$fee_type = get_rdata('fee_type');
$fina_month = get_rdata('fina_month', 'all');
$fina_year = get_rdata('fina_year', 'all');
$export_data = get_rdata('export_data', '');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Exam Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Exam Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Exam Has Been Updated Successfully";
} else if (isset($msg) && $msg == 4) {
    $successmsg = "Student Exam Enrollment has been done successfully.";
} else {
    $successmsg = '';
}


$total_rows = '';
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'pt_receipt_no');
$order = get_rdata('order', 'desc');
if (isset($_POST['submit'])) {
    $page = 1;
    header('Location:report_receipt.php?page=' . $page . "&per_page=" . $per_page . "&pt_receipt_no=" . $pt_receipt_no . "&fee_type=" . $fee_type . "&fina_month=" . $fina_month  . "&fina_year=" . $fina_year  . "&order by=" . $order_by . "&order=" . $order);
} else {
    $page = get_rdata("page", 1);
}
$ex_name_arrow = $ex_date_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'ex_name') {
        $ex_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ex_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'ex_date') {
        $ex_date_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ex_date_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
//searching and pagination
$table = "";
$select = "";
$condition = "";

$pt_tran_u_type_course = " AND pt_tran_u_type = 'Course Fee' ";
$pt_tran_u_type_event_student = " AND pt_tran_u_type = 'Event fee[student]' ";
$pt_tran_u_type_event_other = " AND pt_tran_u_type = 'Event fee[other]' ";
$pt_tran_u_type_exam = " AND pt_tran_u_type = 'Exam Fee' ";

$additonal_where = "";
if ($fina_year != 'all') {
    $fina_year_arr = explode("-", $fina_year);
    if ($fina_month == 'all') {
        $tran_date_from = $fina_year . "-01-01 00:00:00";
        $tran_date_to = $fina_year . "-12-31 23:59:59";
    } else {
        $tran_date_from = $fina_year . "-" . $fina_month . "-01 00:00:00";
        $tran_date_to = $fina_year . "-" . $fina_month . "-31 23:59:59";
    }
    $additonal_where .= " AND (pt_tran_date BETWEEN '" . $tran_date_from . "' AND '" . $tran_date_to . "') ";    
}
if ($pt_receipt_no != '') {
    $additonal_where .= " AND (pt_receipt_no = '" . $pt_receipt_no . "'";
    $additonal_where .= " OR stu_gr_no = '" . $pt_receipt_no . "'";
    $additonal_where .= " OR CONCAT(stu_first_name,' ', stu_last_name) LIKE '%" . $pt_receipt_no . "%')";
}
if ($fee_type != "") {
    if ($fee_type != 'Event Fee') {
        $pt_tran_u_type_course = $pt_tran_u_type_event_student = $pt_tran_u_type_event_other = $pt_tran_u_type_exam = "";
        $additonal_where .= " AND pt_tran_u_type = '" . $fee_type . "'";
    } else {
        $pt_tran_u_type_event_student = " AND pt_tran_u_type = 'Event fee[student]' ";
        $pt_tran_u_type_event_other = " AND pt_tran_u_type = 'Event fee[other]' ";
        $pt_tran_u_type_exam = $pt_tran_u_type_course = " AND pt_tran_u_type = 'Event Fee' ";
        // $additonal_where .=" AND (pt_tran_u_type = 'Event fee[student]' OR pt_tran_u_type = 'Event fee[other]') ";
    }
}
$mfinalquery = "";

if($fee_type=="Course Fee" || empty($fee_type)) {
    $mfinalquery .= "SELECT pt_tran_amount, pt_id, stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for , sc_half_course,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%m-%Y') as transaction_date, pt_tran_remarks , '' as d_name, 'zanzarda road'
FROM sm_payment_transaction  INNER JOIN sm_student_course ON (pt_sc_id = sc_id) 
INNER JOIN sm_student ON (stu_id = sc_stu_id)
LEFT JOIN  sm_belt ON (sc_be_id = be_id)
LEFT JOIN sm_course ON (sc_co_id = co_id)
WHERE pt_br_id = $tmp_admin_id 
" . $pt_tran_u_type_course . $additonal_where;
}

if($fee_type=="Exam Fee" || empty($fee_type)) {
    if(empty($fee_type)) {
        $mfinalquery .= " UNION ";
    }
    $mfinalquery .= "SELECT pt_tran_amount, pt_id, stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for , 0 sc_half_course,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%m-%Y') as transaction_date, pt_tran_remarks , ex_name as d_name  ,  'Main branch'
FROM sm_payment_transaction  INNER JOIN sm_exam_student_entrolled ON (pt_sc_id = exs_id) 
INNER JOIN sm_exam ON (exs_ex_id = ex_id)
INNER JOIN sm_student ON (stu_id = exs_stu_id)
LEFT JOIN  sm_belt ON (exs_be_id = be_id)
LEFT JOIN sm_course ON (exs_co_id = co_id)
WHERE pt_br_id = $tmp_admin_id 
" . $pt_tran_u_type_exam . $additonal_where;

}

if($fee_type=="Event Fee" || empty($fee_type)) {
    if(empty($fee_type)) {
        $mfinalquery .= " UNION ";
    }
    $mfinalquery .= " SELECT pt_tran_amount, pt_id, stu_first_name, stu_last_name , stu_gr_no,  be_name, co_name , be_name_for , 0 sc_half_course,  pt_receipt_no,   pt_tran_u_type, pt_tran_mode_of_payent, pt_tran_no ,  DATE_FORMAT(pt_tran_date,'%d-%m-%Y') as transaction_date, pt_tran_remarks , ev_name as d_name  ,  'Main branch'
        FROM sm_payment_transaction  INNER JOIN sm_event_student_entrolled ON (pt_sc_id = evs_id) 
        INNER JOIN sm_event ON (evs_ev_id = ev_id)
        INNER JOIN sm_student ON (stu_id = evs_stu_id)
        LEFT JOIN  sm_belt ON (evs_be_id = be_id)
        LEFT JOIN sm_course ON (evs_co_id = co_id)
        WHERE pt_br_id = $tmp_admin_id AND pt_tran_u_type IN('Event fee[student]','Event fee[other]') 
        " . $additonal_where;
}

$mfinalquery .= " ORDER BY pt_receipt_no ASC";
if ($export_data == 'Export') {
    $data_header = array("Sr.", "Receipt No.", "Name", "GR. No.", "Belt", "Course", "Half Belt", "About", "Mode", "Tran No.", "T. Date", "Amount", "Remarks");
    export_data("Report Receipt", "report_receipt_export", $data_header, $mfinalquery);
    exit(0);
}


$pageObj = new PS_Pagination($table, $select, $condition, $per_page, 10, "per_page=" . $per_page . "&pt_receipt_no=" . $pt_receipt_no . "&fee_type=" . $fee_type . "&fina_month=" . $fina_month  . "&fina_year=" . $fina_year  . "&order by=" . $order_by . "&order=" . $order, $mfinalquery);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();
add_log_txt($objData->queryString);
if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}
$arr_branch_details =  get_branch_details(session_get("id"));
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

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    
                    <?php echo $page_title; ?>
                    
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"><?php echo $page_title; ?></li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php include("includes/messages.php"); ?>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-12 col-xs-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Search</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal" name="form1" id="form1" method="post">
                                <input type="hidden" name="act" id="act">
                                <input type="hidden" name="export_data" id="export_data">
                                <input type="hidden" value="0" name="id" id="id">
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Search Term</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="pt_receipt_no" id="pt_receipt_no" placeholder="Receipt no/Name/GR No." value="<?php echo $pt_receipt_no; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Fee Type</label>
                                            <div class="col-sm-9">
                                                <select style="width: 100px; display: inline-block;" id="fee_type" name="fee_type" class="form-control">
                                                    <option <?php if ($fee_type == "") echo 'selected="selected"' ?> value="">All</option>
                                                    <option <?php if ($fee_type == "Exam Fee") echo 'selected="selected"' ?> value="Exam Fee">Exam Fee</option>
                                                    <option <?php if ($fee_type == "Event Fee") echo 'selected="selected"' ?> value="Event Fee">Event Fee</option>
                                                    <option <?php if ($fee_type == "Course Fee") echo 'selected="selected"' ?> value="Course Fee">Course Fee</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Year</label>
                                            <div class="col-sm-9">
                                                <select id="fina_month" name="fina_month" class="form-control" style="width: 120px; display: inline-block;">
                                                    <option value="all" <?php if ($fina_month == 'all') echo 'selected="selected"'; ?>>All Months</option>
                                                    <option value="1" <?php if ($fina_month == 1) echo 'selected="selected"'; ?>>Jan</option>
                                                    <option value="2" <?php if ($fina_month == 2) echo 'selected="selected"'; ?>>Feb</option>
                                                    <option value="3" <?php if ($fina_month == 3) echo 'selected="selected"'; ?>>Mar</option>
                                                    <option value="4" <?php if ($fina_month == 4) echo 'selected="selected"'; ?>>Apr</option>
                                                    <option value="5" <?php if ($fina_month == 5) echo 'selected="selected"'; ?>>May</option>
                                                    <option value="6" <?php if ($fina_month == 6) echo 'selected="selected"'; ?>>Jun</option>
                                                    <option value="7" <?php if ($fina_month == 7) echo 'selected="selected"'; ?>>Jul</option>
                                                    <option value="8" <?php if ($fina_month == 8) echo 'selected="selected"'; ?>>Aug</option>
                                                    <option value="9" <?php if ($fina_month == 9) echo 'selected="selected"'; ?>>Sep</option>
                                                    <option value="10" <?php if ($fina_month == 10) echo 'selected="selected"'; ?>>Oct</option>
                                                    <option value="11" <?php if ($fina_month == 11) echo 'selected="selected"'; ?>>Nov</option>
                                                    <option value="12" <?php if ($fina_month == 12) echo 'selected="selected"'; ?>>Dec </option>
                                                </select>
                                                <select id="fina_year" name="fina_year" class="form-control" style="width: 120px; display: inline-block;">
                                                    <option value="all" <?php if ($fina_month == 'all') echo 'selected="selected"'; ?>>All Years</option>
                                                    <?php
                                                    for ($yi = 2017; $yi <= date('Y'); $yi++) {
                                                    ?>
                                                        <option value="<?php echo $yi; ?>" <?php if ($fina_year == $yi) echo 'selected="selected"'; ?>><?php echo $yi; ?></option>
                                                    <?php } ?>


                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default" onclick="window.location.href = 'report_receipt.php'">Cancel</button>
                                    <!-- <button type="button" onclick="print_attendance('print_me','<?php echo $arr_branch_details['name']; ?>','report_receipt','');" class="btn btn-warning">Print</button>
                                    <button type="button" onclick="export_data_js('form1');" class="btn btn-info">Export</button> -->
                                    <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Export <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="print_attendance('print_me', '<?php echo $arr_branch_details["name"]; ?>', 'report_receipt', '');">Export to PDF</a></li>
                                                <li><a href="#" onclick="exportToExcel()">Export to Excel</a></li>
                                            </ul>
                                        </div>
                                </div><!-- /.box-footer -->
                            </form>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="nav nav-tabs">
                        <li><a href="report_receipt.php?fee_type=" class="btn btn-default">All</a></li>
                            <li><a href="report_receipt.php?fee_type=Course Fee" class="btn btn-default">Course Fee</a></li>
                            <li><a href="report_receipt.php?fee_type=Exam Fee" class="btn btn-default">Exam Fee</a></li>
                            <li><a href="report_receipt.php?fee_type=Event Fee" class="btn btn-default">Event Fee</a></li>
                        </ul>
                        <div class="box">
                            <div class="box-body">
                            <p class="lead text-center mid_caption"><?php if($fee_type==""){echo "All";}else{echo $fee_type;};?></p> 
                                <table class="table table-bordered table-hover" id="print_me">
                                    <thead>
                                        <tr>
                                            <th align="center" width="70px">Sr.No</th>
                                            <th>Receipt No.</th>
                                            <th>Name</th>
                                            <th>GR. No.</th>
                                            <th>Belt</th>
                                            <th>Course</th>
                                            <th>Half Belt</th>
                                            <!-- <th align="center"><a href="#manage_exam.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=ex_name&order=<?php echo $order; ?>">Receipt no <span class="<?php echo $ex_name_arrow; ?>"></span></a></th> -->
                                            <th>About</th>
                                            <th>Mode</th>
                                            <th>Tran No.</th>
                                            <th>T. Date</th>
                                            <th>Amount</th>
                                            <th>Remarks </th>
                                            <!-- <th align="center" class="t_align_center" width="300px">Print Receipt</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $class = '';
                                        if ($objData) {
                                            for ($i = 1; $db_row = $objData->fetch(); $i++) {
                                                $srNo++;
                                                if ($i % 2 == 0) {
                                                    $class = 'even';
                                                } else {
                                                    $class = 'odd';
                                                } // d_name
                                                $d_name = $db_row['pt_tran_u_type'];
                                                if ($db_row['d_name'] != '')
                                                    $d_name .= " - " . $db_row['d_name'];

                                                // if ($db_row['stu_gr_no'] == 'MA00000064')
                                                // {
                                                //     echo "<pre>";
                                                //     print_r($db_row);
                                                //     echo "</pre>";
                                                // }
                                        ?>
                                                <tr class="<?php echo $class; ?>">
                                                    <td>
                                                        <center><?php echo $srNo; ?></center>
                                                    </td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['pt_receipt_no']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'] . ' ' . $db_row['stu_last_name']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['be_name'] . ($db_row['sc_half_course'] == 1 ? "-Half" : "-Full"); ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['co_name']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo ($db_row['sc_half_course'] == 1 ? 'Yes' : 'No'); ?></td>

                                                    <td style="padding-left:10px;"><?php echo $d_name; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['pt_tran_mode_of_payent']; ?></td>
                                                    <td style="padding-left:10px;"><?php if($db_row['pt_tran_mode_of_payent']=="Cash"){echo "-";}else{echo $db_row['pt_tran_no'];} ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['transaction_date']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['pt_tran_amount']; ?></td>

                                                    <td style="padding-left:10px;"><?php echo $db_row['pt_tran_remarks']; ?></td>
                                                    <!-- <td>
                                                          <a href="#" class="text-danger">Receipt</a>
                                                        </td> -->
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="11">No records found or you have not permission to access these records.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php if ($objData) { ?>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mt20 mb20">
                                            <?php
                                            $page = 1;
                                            if (isset($_GET['page'])) {
                                                $page = $_GET['page'];
                                            }
                                            if ($page > 0 && ($page > ceil($total_rows / $per_page))) {
                                                $f_line = 1;
                                                $page = 1;
                                            } else {
                                                $f_line = ($page - 1) * $per_page + 1;
                                            }
                                            ?>
                                            <?php $l_line = $page * $per_page; ?>
                                            Showing
                                            <?php
                                            if ($f_line < $total_rows) {
                                                echo ($page - 1) * $per_page + 1;
                                            } else {
                                                echo $total_rows;
                                            }
                                            ?>
                                            to
                                            <?php
                                            if ($l_line < $total_rows) {
                                                echo $page * $per_page;
                                            } else {
                                                echo $total_rows;
                                            }
                                            ?>
                                            of <?php echo $total_rows ?> entries
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <div class="pull-right">
                                                <ul class="pagination">
                                                    <?php echo $pageObj->renderFullNav(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
        <script>
            $("#ex_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            });
        </script>
        <?php include("includes/footer.php"); ?>
    </div>
    <script>
            function exportToExcel()
            {
                var excelData = '<table border="1">';
                var tableHeaders = document.querySelectorAll('#print_me thead tr th');
                excelData += '<tr>';
                tableHeaders.forEach(function (header) {
                excelData += '<th>' + header.textContent + '</th>';
                });
                excelData += '</tr>';
                var tableRows = document.querySelectorAll('#print_me tbody tr');
                tableRows.forEach(function (row) {
                excelData += '<tr>';
                 var rowCells = row.querySelectorAll('td');
                rowCells.forEach(function (cell) {
                    excelData += '<td>' + cell.textContent + '</td>';
                });
                excelData += '</tr>';
                 });
                excelData += '</table>';
                var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                var receiptStatus=<?php echo json_encode($fee_type);?>;
                if(receiptStatus==''){
                    a.download='All_Fee_report.xls';
                }
                else{
                    a.download = receiptStatus+'.xls';
                }
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            }
        </script>
</body>

</html>