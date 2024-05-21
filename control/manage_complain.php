<?php
include("includes/application_top.php");
include("../includes/class/complain.php");


//set Page Title
$page_title = "Manage Special Notes";
$errormsg = get_rdata('errormsg', '');


$id = get_rdata("id", 0);
$act = get_rdata("act");
$cm_id = get_rdata('cm_id');
$cm_identy_id = get_rdata('cm_identy_id');
$cm_stu_id = get_rdata('cm_stu_id');
$cm_title = get_rdata('cm_title');
$cm_description = get_rdata('cm_description');
$cm_status = get_rdata('cm_status','A');
$cm_create_date = get_rdata('cm_create_date');
$cm_create_by_id = get_rdata('cm_create_by_id');
$cm_update_date = get_rdata('cm_update_date');
$cm_update_by_id = get_rdata('cm_update_by_id');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Special Notes Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Special Notes Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Special Notes Has Been Updated Successfully";
} else {
    $successmsg = '';
}





$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'cm_id');
$order = get_rdata('order', 'asc');
$client_arrow = $cm_identy_id_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'cm_identy_id') {
        $cm_identy_id_arrow = 'glyphicon glyphicon-chevron-up';
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

    $q_complain = "DELETE c FROM sm_complain c  INNER JOIN sm_student ON  (cm_stu_id = stu_id)  WHERE  cm_id = ".$id;
              if (session_get('admin_login_type') == 'school') {
            $q_complain .=" and stu_sc_id= " . session_get('admin_sc_id');
        }
      //  echo $q_complain;
    $res_complain = m_process("delete", $q_complain);
  //  print_r($res_complain);
    if ($res_complain['errormsg'] != '') {
        $errormsg = $res_complain['errormsg'];
    } else {
        // delete from complain details
        $q_delete = "DELETE FROM sm_complain_details WHERE cmd_cm_id = ".$id ;
        $res_delete = m_process("delete", $q_delete);
        if ($res_delete["errormsg"] != '')
        {
             $errormsg = $res_delete['errormsg'];
        }
        else
        {
            $successmsg = "Complain Has Been Deleted Successfully";
        }
        // end of delete complain details

    }
}


//searching and pagination
$condition = '1';
if (session_get('admin_login_type') == 'school') {
    $condition.=" and  sm.sc_id= " . session_get('admin_sc_id');
}
if ($cm_identy_id != '') {
    $condition.=" and 	cm_identy_id LIKE '%" . $cm_identy_id . "%'";
}
/*
if ($sc_city != '') {
    $condition.=" and sc_city LIKE '%" . $sc_city . "%'";
}
*/


$select = " c.cm_id, c.cm_identy_id ,c.cm_title, c.cm_description , st.stu_gr_no,st.stu_first_name, st.stu_middle_name, st.stu_last_name   ";
$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_complain c INNER JOIN sm_student st ON (c.cm_stu_id= st.stu_id) INNER JOIN sm_school_master sm ON (sm.sc_id = st.stu_sc_id) ";
// echo "SELECT ".$select . " FROM ". $table . " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select, "$condition", $per_page, 10, "per_page=" . $per_page . "&cm_identy_id=" . $cm_identy_id . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();



//get total records from project table
//$sm_complain = new complain();
//$sm_complain->cquery = "select * from $table WHERE $condition";
//$sm_complain->action = 'get';
//$result = $sm_complain->process();
//if ($result['status'] == 'failure') {
//    $errormsg = $result['errormsg'];
//} else {
//    if ($result['count'] > 0) {
//        $total_rows = $result['count'];
//    }
//}
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
                        Manage Special Notes
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Special Notes</li>
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
                                <form class="form-horizontal" name="form1" id="form1" method="post" >
                                    <input type="hidden" name="act" id="act">
                                        <input type="hidden" value="0" name="id" id="id">
                                            <div class="box-body">
                                                <div class="col-md-6">

                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Ref. No</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="cm_identy_id" id="cm_identy_id"  placeholder="Enter Complain no"  title="Enter Complain no" value="<?php echo $cm_identy_id; ?>"  class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.box-body -->
                                            <div class="box-footer">
                                                <button type="submit" class="btn btn-info">Search</button>
                                                <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_school.php'">Cancel</button>
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
                                                                        <th align="center">Sr.No</th>
                                                                        <th align="center"><a href="manage_complain.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=cm_identy_id&order=<?php echo $order; ?>">Complain No<span class="<?php echo $cm_identy_id_arrow; ?>"></span></a></th>
                                                                        <th align="center">Title</th>
                                                                        <th align="center">Description</th>
                                                                        <th align="center">Student Id</th>
                                                                        <th align="center">Student Name </th>
                                                                        <th align="center">Action</th>
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
                                                                                <td style="padding-left:10px;"><?php echo $db_row['cm_identy_id']; ?></td>
                                                                                <td><?php echo $db_row['cm_title']; ?></td>
                                                                                <td><?php echo $db_row['cm_description']; ?></td>
                                                                                <td><?php echo $db_row['stu_gr_no']; ?></td>
                                                                                <td><?php echo $db_row['stu_first_name'] . " " . $db_row['stu_last_name']; ?></td>
                                                                                <td><center>
<!--                                                                                        <a href="add_edit_complain.php?id=<?php echo $db_row['cm_id']; ?>&per_page=<?php echo $per_page; ?>" class="edit glyphicon glyphicon-pencil" ></a>&nbsp;-->
                                                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="delete glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['cm_id']; ?>,'Complain')"></a>&nbsp;
<!--                                                                                    <a class="fa fa-fw fa-comment-o chat-discussion" href="process_complain.php?id=<?php echo $db_row['cm_id']; ?>&per_page=<?php echo $per_page; ?>" ></a>-->
                                                                                    </center></td>
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
                                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
