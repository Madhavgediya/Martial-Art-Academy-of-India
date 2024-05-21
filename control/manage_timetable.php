<?php
include("includes/application_top.php");
include("../includes/class/timetable.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Time Table";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$tt_id= get_rdata('tt_id');
$tt_title= get_rdata('tt_title');
$tt_medium= get_rdata('tt_medium');
$tt_std_id= get_rdata('tt_std_id');
$tt_cl_id= get_rdata('tt_cl_id');
$tt_status= get_rdata('tt_status');



// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Time Table Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Time Table Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Time Table Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'tt_id');
$order = get_rdata('order', 'asc');
$client_arrow = $tt_title_arrow = $tt_title_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'tt_title') {
        $sc_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $client_arrow = 'glyphicon glyphicon-chevron-up';
    }
} else {
    $order = 'asc';
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {
    
            $sm_timetable_master = new timetable();
            $sm_timetable_master->action = 'delete';
            $del_where = "tt_id = ". $id;
            if (session_get('admin_login_type') == 'school') {
                    $del_where.=" and tt_sc_id= " . session_get('admin_sc_id');
            }
            $sm_timetable_master->where = $del_where;
            $result = $sm_timetable_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                $successmsg = "Time Table Has Been Deleted Successfully";
            }
}


//searching and pagination
$condition = '1';
if ($tt_title != '') {
    $condition.=" and tt_title LIKE '%" . $tt_title . "%'";
}

if (session_get('admin_login_type') == 'school') {
    $condition.=" and sc_id= " . session_get('admin_sc_id');
}

$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_timetable INNER JOIN sm_school_master ON (sc_id=tt_sc_id)";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, '*', "$condition", $per_page, 10, "per_page=" . $per_page . "&tt_title=" . $tt_title . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalabel=no" name="viewport"/>
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
                        Manage Time Table
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Time Table</li>
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
                                                <label class="col-sm-3 control-label">Time Table Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="tt_title" id="tt_title"  placeholder="Enter Time Table Name" value="<?php echo $tt_title; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>                                                                             
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">                                        
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_timetable.php'">Cancel</button>
                                    </div><!-- /.box-footer -->
                                </form>                                
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">                                
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center"><a href="manage_timetable.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=tt_title&order=<?php echo $order; ?>">Time Table Name <span class="<?php echo $tt_title_arrow; ?>"></span></a></th>
                                                <th align="center" class="t_align_center"  width="100px">Action</th>
                                                <th align="center" width="80px">Status</th>
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
                                                        <td><center><?php echo $srNo; ?></center></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['tt_title']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['tt_status']=='A'?'Active':'Inactive'); ?></td>
                                                        <td><center><a href="add_edit_timetable.php?id=<?php echo $db_row['tt_id']; ?>&per_page=<?php echo $per_page; ?>" class="edit glyphicon glyphicon-pencil" ></a>&nbsp;
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="delete glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['tt_id']; ?>,'Time Table')"></a></center></td>
                                                        
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
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
