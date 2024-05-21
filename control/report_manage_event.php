<?php
include("includes/application_top.php");
include("../includes/class/event.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Event Attendance";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$ev_name = get_rdata('ev_name');
$ev_date = get_rdata('ev_date', '');

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'ev_date');
$order = get_rdata('order', 'desc');
$ev_name_arrow = $ev_date_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'ev_name') {
        $ev_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ev_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
    if ($order_by == 'ev_date') {
        $ev_date_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $ev_date_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

//searching and pagination
$condition = ' ev.ev_br_id = ' . $tmp_admin_id;
if ($ev_name != '') {
    $condition .= " and ev.ev_name LIKE '%" . $ev_name . "%'";
}
if ($ev_date != '') {
    $condition .= " and ev.ev_date = '" .  convert_disp_to_db_date($ev_date) . "'";
}


$select = "IF(M.count_event IS NULL,0,M.count_event) count_event , ev.*";
$condition .= " order by ev." . $order_by . ' ' . $order;
$table = "  sm_event  ev LEFT JOIN (select count(1) as count_event , evs_ev_id FROM sm_event_student_entrolled
GROUP BY evs_ev_id ) as M ON (ev.ev_id = M.evs_ev_id) ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 10, "per_page=" . $per_page . "&ev_name=" . $ev_name . "&ev_date=" . $ev_date . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
} else {
    $order = 'asc';
}
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
                    <a href="report_student_attendance.php" class="btn btn btn-default">Student Attendance</a>
                    <a href="report_factulty_attendance.php" class="btn btn btn-default">Faculty Attendance</a>
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
                                            <label class="col-sm-3 control-label">Event Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ev_name" id="ev_name" placeholder="Enter event Name" value="<?php echo $ev_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Event Date</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ev_date" id="ev_date" placeholder="Enter event Date" value="<?php echo $ev_date; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default" onclick="window.location.href = 'report_manage_event.php'">Cancel</button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Export <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="report_manage_event_pdf.php">Export to PDF</a></li>
                                            <li><a href="report_manage_event_excel.php">Export to Excel</a></li>
                                        </ul>
                                    </div>
                                </div><!-- /.box-footer -->
                            </form>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body">
                                <table id="eventple2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th align="center" width="70px">Sr.No</th>
                                            <th align="center"><a href="report_manage_event.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=ev_name&order=<?php echo $order; ?>">Event Name <span class="<?php echo $ev_name_arrow; ?>"></span></a></th>
                                            <th align="center"><a href="report_manage_event.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=ev_date&order=<?php echo $order; ?>">Start Date <span class="<?php echo $ev_date_arrow; ?>"></span></a></th>
                                            <th align="center">End date</th>
                                            <th align="center" width="180px">Event Fee</th>
                                            <th align="center" width="80px">Status</th>
                                            <th align="center" class="t_align_center" width="250px">Action</th>
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
                                                    <td style="padding-left:10px;"><?php echo $db_row['ev_name']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo  DBtoDisp($db_row['ev_date']); ?></td>
                                                    <td style="padding-left:10px;"><?php echo ($db_row['ev_end_date'] != "" ? DBtoDisp($db_row['ev_end_date']) : ""); ?></td>
                                                    <td style="padding-left:10px;"><?php echo  $db_row['ev_eu_exam_fee']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo ($db_row['ev_status'] == 'A' ? 'Active' : 'Inactive'); ?></td>
                                                    <td>
                                                        <a href="event_attendance.php?ev_id=<?php echo $db_row['ev_id']; ?>&type=view" class="text-info glyphicon glyphicon-eye-open"></a>
                                                    </td>
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
            $("#ev_date").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
            });
        </script>
        <?php include("includes/footer.php"); ?>
    </div>
</body>

</html>