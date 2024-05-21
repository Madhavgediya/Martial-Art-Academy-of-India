<?php
include "includes/application_top.php";
include "../includes/class/product_option.php";

$page_title = "Manage Product Option";
$errormsg = get_rdata('errormsg', '');

$id = get_rdata("id", 0);
$act = get_rdata("act");
$po_name = get_rdata('po_name');
$po_type = get_rdata('po_type', '');
$po_used_type = get_rdata('po_used_type', '');

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Product Option Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Product Option Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Product Option Has Been Updated Successfully";
} else {
    $successmsg = '';
}

$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'po_name');
$order = get_rdata('order', 'asc');
$po_name_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    if ($order_by == 'po_name') {
        $po_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $po_name_arrow = 'glyphicon glyphicon-chevron-up';
    }
}
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {
    $q_d_check = "SELECT 1 FROM sm_invoice_products WHERE (invpro_po_id_2 = " . $id . " OR invpro_po_id= " . $id . ") LIMIT 0,1";
    $r_d_check = m_process("get_data", $q_d_check);
    if ($r_d_check["status"] == "failure") {
        $errormsg = $r_d_check["errormsg"];
    } else {
        if ($r_d_check["count"] > 0) {
            $errormsg = "Product option is already used in Purchase so you can't delete it";
        } else {

            $q_d_check = "SELECT 1 FROM sm_invoice_products_sale WHERE (invpro_po_id_2 = " . $id . " OR invpro_po_id= " . $id . ") LIMIT 0,1";
            $r_d_check = m_process("get_data", $q_d_check);
            if ($r_d_check["status"] == "failure") {
                $errormsg = $r_d_check["errormsg"];
            } else {
                if ($r_d_check["count"] > 0) {
                    $errormsg = "Product option is already used in Sale so you can't delete it";
                } else {
                    $sm_product_option_master = new product_option();
                    $sm_product_option_master->action = 'delete';
                    $del_where = "po_id = " . $id;
                    $sm_product_option_master->where = $del_where;
                    $result = $sm_product_option_master->process();
                    if ($result['status'] == 'failure') {
                        $errormsg = $result['errormsg'];
                    } else {
                        $successmsg = "Product Option Has Been Deleted Successfully";
                    }
                }
            }
        }
    }
}

//searching and pagination
$condition = ' po_br_id = ' . $tmp_admin_id;
if ($po_name != '') {
    $condition .= " and po_name LIKE '%" . $po_name . "%'";
}
if ($po_type != '') {
    $condition .= " and po_type = '" . $po_type . "'";
}
if ($po_used_type != '') {
    $condition .= " and po_used_type = '" . $po_used_type . "'";
}

$condition .= " order by " . $order_by . ' ' . $order;
$table = "  sm_product_option ";
// echo "SELECT * FROM ".$table. " WHERE " .$condition;
$pageObj = new PS_Pagination($table, '*', "$condition", $per_page, 10, "per_page=" . $per_page . "&po_name=" . $po_name . "&po_type=" . $po_type . "&po_used_type=" . $po_used_type . "&order by=" . $order_by . "&order=" . $order);
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
    <?php include "includes/include_files.php"; ?>
</head>

<body class="skin-green sidebar-mini">
    <div class="wrapper">
        <?php include "includes/header.php"; ?>
        <?php include "includes/left_menu.php"; ?>

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Manage Product Option
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Manage Product Option</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php include "includes/messages.php"; ?>
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
                                            <label class="col-sm-3 control-label">Product Option</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="po_name" id="po_name" placeholder="Enter Product Option Name" value="<?php echo $po_name; ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Type</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="po_type" id="po_type_all" <?php if ($po_type == '') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="" />
                                                <label for="po_type_all">All</label>
                                                <input type="radio" name="po_type" id="po_type_a" <?php if ($po_type == 'Color') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="Color" />
                                                <label for="po_type_a">Color</label>
                                                <input type="radio" name="po_type" id="po_type_i" <?php if ($po_type == 'Size') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="Size" />
                                                <label for="po_type_i">Size</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Used Type</label>
                                            <div class="col-sm-9">
                                                <input type="radio" name="po_used_type" id="po_used_type_all" <?php if ($po_used_type == '') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="" />
                                                <label for="po_used_type_all">All</label>
                                                <input type="radio" name="po_used_type" id="po_used_type_a" <?php if ($po_used_type == 1) {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="1" />
                                                <label for="po_used_type_a">Certificate</label>
                                                <input type="radio" name="po_used_type" id="po_used_type_b" <?php if ($po_used_type == 2) {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="2" />
                                                <label for="po_used_type_b">Belt</label>
                                                <input type="radio" name="po_used_type" id="po_used_type_c" <?php if ($po_used_type == 3) {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?> value="3" />
                                                <label for="po_used_type_c">Both</label>
                                            </div>
                                        </div>
                                    </div> -->
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info">Search</button>
                                    <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_product_option.php'">Cancel</button>
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
                                            <th align="center"><a href="manage_product_option.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=po_name&order=<?php echo $order; ?>">Product
                                                    Option Name <span class="<?php echo $po_name_arrow; ?>"></span></a>
                                            </th>
                                            <th align="center" width="80px">Type</th>
                                            <!-- <th align="center" width="80px">Used Type</th> -->
                                            <th align="center" width="80px">Status</th>
                                            <th align="center" class="t_align_center" width="100px">Action</th>
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
                                                    <td style="padding-left:10px;"><?php echo $db_row['po_name']; ?></td>
                                                    <td style="padding-left:10px;"><?php echo $db_row['po_type']; ?></td>
                                                    <!-- <td style="padding-left:10px;"> -->
                                                        <?php
                                                        /*if($db_row['po_used_type'] == 1) {
                                                            $po_used_type = 'Certificate';
                                                        } else if($db_row['po_used_type'] == 2) {
                                                            $po_used_type = 'Belt';
                                                        } else if($db_row['po_used_type'] == 3) {
                                                            $po_used_type = 'Both';
                                                        } else {
                                                            $po_used_type = 'Default';
                                                        }
                                                        echo $po_used_type;*/
                                                        ?>
                                                    <!-- </td> -->
                                                    <td style="padding-left:10px;">
                                                        <?php echo ($db_row['po_status'] == 'A' ? 'Active' : 'Inactive'); ?>
                                                    </td>
                                                    <td>
                                                        <center><a href="add_edit_product_option.php?id=<?php echo $db_row['po_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil"></a>&nbsp;
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['po_id']; ?>,'Product Option')"></a>
                                                        </center>
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
        <?php include "includes/footer.php"; ?>
    </div>
</body>

</html>