<?php
include("includes/application_top.php");
include("../includes/class/manufacturer.php");
//set Page Title
$caption = $page_title = "Manage Manufacturer";
$errormsg = get_rdata('errormsg', '');
$id = get_rdata("id", 0);
$act = get_rdata("act");

// Set success message based on msg ID
$msg = get_rdata('msg', '');

if (isset($msg) && $msg == 1) {
    $successmsg = "Manufacturer has been deleted successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Manufacturer has been added successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Manufacturer has been updated successfully";
}

$total_rows = 0;
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);

if (isset($_GET['per_page'])) {
    $per_page = $_GET['per_page'];
}
if (isset($_GET['per_page']) && $per_page <= 0) {
    $per_page = PER_PAGE;
}
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

$order_by = get_rdata('order_by', 'manu_name');
$order = get_rdata('order', 'asc');
$link_order = 'desc';
$manu_name_arrow = $manu_status_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'desc') {
    $link_order = 'asc';
    if ($order_by == 'manu_status') {
        $manu_status_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $manu_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if ($act == 'delete' && $id != 0) {

    if ($errormsg == '') {
        $arr_check_delete = validate_before_delete("sm_products", "pro_manu_id = " . $id);
        if ($arr_check_delete["error_message"] != '') {
            $errormsg = $arr_check_delete["error_message"];
        } else if ($arr_check_delete["found_reference"] == true) {
            $errormsg = "Delete process can not be completed because of existing manufacture is related to other products";
        }
    }
    if ($errormsg == '') {
        $cquery = "DELETE FROM sm_manufacturer WHERE manu_admin_id = " . $tmp_admin_id . " AND manu_id = " . $id;
        $manu_result = m_process('delete', $cquery);
        if ($manu_result['status'] == 'failure') {
            echo $errormsg = $manu_result['errormsg'];
        } else {
            $successmsg = 'Manufacturer has been deleted successfully.';
        }
    }
}

$manu_name = get_rdata('manu_name', '');
$manu_status = get_rdata('manu_status', 'All');

//searching and pagination
$condition = ' manu_admin_id='.$tmp_admin_id;
if ($manu_name != '') {
    $condition .= " AND manu_name LIKE '" . $manu_name . "%'";
}
if ($manu_status != '' && $manu_status != 'All') {
    $condition .= " AND manu_status ='" . $manu_status . "'";
}
$select = " * ";
$condition .= " order by " . $order_by . ' ' . $order;
$table = "sm_manufacturer";
$searchField = "&manu_name=" . $manu_name . "&manu_status=" . $manu_status;
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 5, "per_page=" . $per_page . $searchField . "&order by=" . $order_by . "&order=" . $order);
$manufacturerData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if (isset($_GET['page']) && $_GET['page'] > 0 && ($_GET['page'] > ceil($total_rows / $per_page))) {
    $srNo = 0;
}
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
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">                                
                                <div class="box-header with-border">
                                    <h3 class="box-title">Search</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form class="form-horizontal" name="form1" id="form1" method="post">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="manu_name" id="manu_name"  placeholder="Manufacturer Name" title="Manufacturer Name" value="<?php echo $manu_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9">
                                                    <div class="radio-inline pl0" >
                                                        <input type="radio" class="minimal" name="manu_status" id="manu_statusall" value="All" <?php if (set_checked($manu_status, 'All')) { ?> checked="checked" <?php } ?>  />
                                                        <label for="manu_statusall">All</label>
                                                    </div>
                                                    <div class="radio-inline pl0" >
                                                        <input type="radio" class="minimal" name="manu_status" id="manu_status1" value="1" <?php if (set_checked($manu_status, '1')) { ?> checked="checked" <?php } ?>  />
                                                        <label for="manu_status1">Active</label>
                                                    </div>
                                                    <div class="radio-inline pl0">
                                                        <input type="radio" class="minimal" name="manu_status" id="manu_status0" value="0" <?php if (set_checked($manu_status, '0')) { ?> checked="checked" <?php } ?>  />
                                                        <label for="manu_status0">Inactive</label>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">                                                                                
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_manufacturer.php'">Cancel</button>
                                        <button type="submit" class="btn btn-info pull-right">Search</button>
                                    </div><!-- /.box-footer -->
                                </form>                                
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">                                
                                <div class="box-body">
                                   
                                        <table id="example2" class="table table-bordered table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th width="5%"><center>Sr.No.</center></th>

                                                    <th width="55%"><center><a href="manage_manufacturer.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&<?php echo $searchField; ?>&order_by=manu_name&order=<?php echo $link_order; ?>">Manufacturer Name <span class="<?php echo $manu_name_arrow; ?>"></span></a></center></th>
                                                    <th width="10%"><center><a href="manage_manufacturer.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&<?php echo $searchField; ?>&order_by=manu_status&order=<?php echo $link_order; ?>">Status <span class="<?php echo $manu_status_arrow; ?>"></span></a></center></th>
                                                    <th width="10%"><center>Action</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $class = '';
                                                if ($manufacturerData) {
                                                    for ($i = 1; $manufacturerrow = $manufacturerData->fetch(); $i++) {
                                                        $srNo++;
                                                        if ($i % 2 == 0) {
                                                            $class = 'even';
                                                        } else {
                                                            $class = 'odd';
                                                        }

                                                        $manu_id = $manufacturerrow['manu_id'];
                                                        $manu_name = $manufacturerrow['manu_name'];
                                                        $manu_status = $manufacturerrow['manu_status'];


                                                        if ($manu_status == 1) {
                                                            $status = 'Active';
                                                        } else if ($manu_status == 0) {
                                                            $status = 'Inactive';
                                                        }
                                                        ?>
                                                        <tr class="<?php echo $class; ?>">
                                                            <td><center><?php echo $srNo; ?></center></td>

                                                            <td style="padding-left:10px;"><?php echo ucfirst($manu_name); ?></td>                                                        
                                                            <td><?php echo $status; ?></td>
                                                            <td><center>
                                                                    <a href="add_edit_manufacturer.php?id=<?php echo $manu_id; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" title="Edit"></a>
                                                                    &nbsp;
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $manu_id; ?>, 'manufacturer');" title="Delete"></a>
                                                                </center></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="5">No records found or you have not permission to access these records.</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    
                                    <?php if ($pageObj) { ?>
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
                </section>               
            </div>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>