<?php
include("includes/application_top.php");
include("../includes/class/event.php");

$page_title = "Event Attendance";
$errormsg = get_rdata('errormsg', '');

$type = get_rdata("type", "addEdit");
$ev_id = get_rdata("ev_id", 0);
$act = get_rdata("act");
$stu_id = get_rdata('stu_id');
$search_term = get_rdata('search_term');
$export_data = get_rdata('export_data', '');
$generate_pdf = get_rdata('generate_pdf', '');

// get event detail
$event = new event();
$event->cquery = "select * from sm_event WHERE ev_id = $ev_id";
$event->action = 'get';
$eventData = $event->process();
$ev_startdate = $eventData['res'][0]['ev_date'];
$ev_enddate = $eventData['res'][0]['ev_end_date'];
$eventAllDates = getPeriodToDates($ev_startdate, $ev_enddate, 'Y-m-d');

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 2) {
    $successmsg = "Student Has Been Added Successfully";
} else {
    $successmsg = '';
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'stu_gr_no ');
$order = get_rdata('order', 'asc');
$stu_first_name_arrow = $stu_gr_no_arrow =  'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'stu_first_name') {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_first_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'stu_gr_no') {
        $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $stu_gr_no_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

//searching and pagination
$condition = 'ES.evs_ev_id=' . $ev_id;
if ($search_term != '') {
    $condition .= " AND (S.stu_gr_no LIKE '%" . $search_term . "%'";
    $condition .= " OR CONCAT(S.stu_first_name,' ',S.stu_middle_name, ' ', S.stu_last_name) LIKE '%" . $search_term . "%')";
}

$condition .= " order by " . $order_by . ' ' . $order;
$table = "sm_event_student_entrolled ES LEFT JOIN sm_student S ON (ES.evs_stu_id = S.stu_id)";
$select_f = "stu_gr_no,stu_first_name,stu_id,stu_middle_name,stu_last_name ";
$pageObj = new PS_Pagination($table, $select_f, "$condition", $per_page, 10, "per_page=" . $per_page . "&search_term=" . $search_term . "&order by=" . $order_by . "&order=" . $order);
// echo "<pre>";
// print_r($pageObj);

$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}

// attendance data
$event_attendance =  get_event_attendance_data($ev_id);
$event_attendance_array = array();
if ($event_attendance["status"] == "success" && $event_attendance["count"] > 0) {
    foreach ($event_attendance["res"] as $key => $val) {
        $event_attendance_array[$val["sea_att_date"]."-".$val["sea_stud_id"]] = $val["sea_att_status"];
    }
}
if ($export_data == 'Export') {
    $excelHeading = ['Sr.No', 'Gr No.', 'Name'];
    foreach ($eventAllDates as $date) {
        array_push($excelHeading, DBtoDisp($date, 'd/m/y'));
    }

    $excelData = [];
    for ($i = 1; $db_row = $objData->fetch(); $i++) {
        $srNo++;
        $new = [
            $srNo,
            $db_row['stu_gr_no'],
            $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']
        ];

        foreach ($eventAllDates as $date) {
            $timestamp = strtotime($date);
            $find_key = $date . "-" . $db_row['stu_id'];

            $att_val = 'N/A';
            if (isset($event_attendance_array[$find_key])) {
                if ($event_attendance_array[$find_key] == 1) {
                    $att_val = 'P';
                } else if ($event_attendance_array[$find_key] == 2) {
                    $att_val = 'A';
                } else {
                    $att_val = 'N/A';
                }
            }
            array_push($new, $att_val);
        }
        array_push($excelData, $new);
    }

    // export excel
    $filename = "event_attendance_" . date('d-m-Y') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo implode("\t", $excelHeading) . "\n";
    foreach ($excelData as $row) {
        echo implode("\t", array_values($row)) . "\n";
    }
    exit(0);
}

if ($generate_pdf == 'PDF') {
    require_once __DIR__ . '../../vendor/autoload.php';
    include("event_attendance_pdf.php");
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 5,
        'margin_right' => 5,
        'margin_top' => 5,
        'margin_bottom' => 5,
    ]);
    $mpdf->WriteHTML($html);
    $filename = "event_attendance_" . date('d-m-Y') . ".pdf";
    $mpdf->Output($filename, 'D');
    exit(0);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport" />
    <title><?php echo $page_title; ?></title>
    <?php include("includes/include_files.php"); ?>
    <style>
        #tbl_student_attendance tr td {
            white-space: nowrap;
        }

        .att_A {
            color: red;
        }

        .att_P {
            color: green;
        }
    </style>
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
                            <form class="form-horizontal" name="form1" id="form1" method="post" onsubmit="return validate_add_edit_form();">
                                <input type="hidden" name="act" id="act">
                                <input type="hidden" value="0" name="id" id="id">
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Search Term</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="search_term" id="search_term" placeholder="Enter search term" value="<?php echo $search_term; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default" onclick="window.location.href = 'event_attendance.php?ev_id=<?php echo $ev_id; ?>&type=<?= $type ?>'">Cancel</button>
                                    <?php
                                    if ($type == 'view') {
                                    ?>
                                        <!-- <button type="button" onclick="export_data_js('form_event_attendance');" class="btn btn-info">Export</button> -->
                                        <!-- <button type="button" onclick="generate_pdf_js('form_event_attendance');" class="btn btn-warning">Generate PDF</button> -->
                                        <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Export <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onclick="generate_pdf_js('form_event_attendance');">Export to PDF</a></li>
                                            <li><a href="#" onclick="export_data_js('form_event_attendance');">Export to Excel</a></li>
                                        </ul>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div><!-- /.box-footer -->
                            </form>
                        </div>
                    </div>
                </div>
                <form id="form_event_attendance" name="form_event_attendance" method="post">
                    <input type="hidden" name="export_data" id="export_data">
                    <input type="hidden" name="generate_pdf" id="generate_pdf">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body" style="overflow-x:auto; padding-right:5px; ">
                                    <table id="tbl_student_attendance" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center" width="20px"><a href="event_attendance.php?ev_id=<?php echo $ev_id; ?>&page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_gr_no&order=<?php echo $order; ?>">Gr No. <span class="<?php echo $stu_gr_no_arrow; ?>"></span></a></th>
                                                <th align="center" width="20px"><a href="event_attendance.php?ev_id=<?php echo $ev_id; ?>&page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=stu_first_name&order=<?php echo $order; ?>">Name <span class="<?php echo $stu_first_name_arrow; ?>"></span></a></th>
                                                <?php
                                                foreach ($eventAllDates as $date) {
                                                    $disable = '';
                                                    if ($type != 'view' && $date > date('Y-m-d')) {
                                                        $disable = 'text-muted';
                                                    }
                                                    $timestamp = strtotime($date);
                                                    $day = strtoupper(date('D', $timestamp));
                                                    $weekDay = strtoupper(substr($day, 0, 1));

                                                    echo '<th align="center"><span class="' . $disable . '">' . $day . '<br>' . DBtoDisp($date, 'd/m/y') . '</span></th>';
                                                }

                                                ?>
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
                                                    }
                                            ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td>
                                                            <center><?php echo $srNo; ?></center>
                                                        </td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_gr_no']; ?></td>

                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name']; ?></td>
                                                        <?php
                                                        foreach ($eventAllDates as $date) {
                                                            $timestamp = strtotime($date);
                                                            $find_key = $date . "-" . $db_row['stu_id'];
                                                            $disable = '';
                                                            if ($date > date('Y-m-d') || $type == 'view') {
                                                                $disable = 'disabled';
                                                            }

                                                            $attVal = 'N/A';
                                                            $btnClass = 'btn-secondary';
                                                            if (isset($event_attendance_array[$find_key])) {
                                                                if ($event_attendance_array[$find_key] == 1) {
                                                                    $attVal = 'P';
                                                                    $btnClass = 'btn-success';
                                                                } else if ($event_attendance_array[$find_key] == 2) {
                                                                    $attVal = 'A';
                                                                    $btnClass = 'btn-danger';
                                                                } else {
                                                                    $attVal = 'N/A';
                                                                    $btnClass = 'btn-secondary';
                                                                }
                                                            }

                                                            echo '<td style="padding-left:10px;"><button id="' . $find_key . '" class="btn ' . $btnClass . ' ' . $disable . '" onclick=toggleAttBtnStatus("' . $attVal . '_' . $timestamp . '_' . $db_row['stu_id'] . '")>' . $attVal . '</button></td>';
                                                        }

                                                        ?>
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
                    </div>
                </form>
            </section>
        </div>
        <?php include("includes/models.php"); ?>
        <script type="text/javascript" lang="javascript">
            function toggleAttBtnStatus(attString) {
                let result = attString.split('_');
                let attStatus = result[0];
                let attTimestamp = result[1];
                let attStudId = result[2];
                let attEvId = <?= $ev_id ?>;
                let currentEle = attTimestamp + '-' + attStudId;

                let attNewVal, attNewClass;
                if (attStatus == 'N/A') {
                    attNewVal = 'P';
                    attNewClass = 'btn-success';
                } else if (attStatus == 'P') {
                    attNewVal = 'A';
                    attNewClass = 'btn-danger';
                } else if (attStatus == 'A') {
                    attNewVal = 'N/A';
                    attNewClass = 'btn-secondary';
                }

                $('#' + currentEle).replaceWith(`
                    <button id="${currentEle}" class="btn ${attNewClass}" onclick="toggleAttBtnStatus('${attNewVal}_${attTimestamp}_${attStudId}')">${attNewVal}</button>
                `)

                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {
                        action: "add_event_attendance",
                        attStudId: attStudId,
                        attTimestamp: attTimestamp,
                        attStatus: attNewVal,
                        attEvId: attEvId,
                    },
                    success: function(result) {
                        result = $.trim(result);

                        var objResponse = jQuery.parseJSON(result);
                       /* if (objResponse.status == 'success') {
                            alert(objResponse.data);
                        } else {
                            alert(objResponse.errormsg);
                        }*/
                    }
                });
            }
        </script>
        <?php include("includes/footer.php"); ?>
    </div>
</body>

</html>