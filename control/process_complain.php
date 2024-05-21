<?php
include("includes/application_top.php");
include("../includes/class/complain.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "View/Process Special Notes";
$errormsg = get_rdata('errormsg', '');


$id = get_rdata("id", 0);
$act = get_rdata("act");
$cm_id = get_rdata('cm_id');
$cm_identy_id = get_rdata('cm_identy_id');
$cm_stu_id = get_rdata('cm_stu_id');
$cm_title = get_rdata('cm_title');
$cmd_text =get_rdata('cmd_text');
$cm_description = get_rdata('cm_description');
$cm_status = get_rdata('cm_status');
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
if ($act != '' && $id != 0) {
    $q = "INSERT INTO sm_complain_details (cmd_id, cmd_cm_id, cmd_text, cmd_status, cmd_create_by_stu_id, cmd_create_by_admin_id, cmd_create_date, cmd_create_by_id, cmd_update_date, cmd_update_by_id) VALUES (NULL, '".$id."', '".$cmd_text ."', 'A', '0', '".$tmp_admin_id."', '".$cur_date."', '".$tmp_admin_id."', '".$cur_date."', '".$tmp_admin_id."')";
                    $result = m_process("insert", $q);

                    if ($result['errormsg'] != '') {
                        $errormsg = $result['errormsg'];
                        
                    } else {
                        header('Location:manage_complain.php?per_page=' . $per_page . '&msg=1');                
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
$condition.=" and c.cm_id = ".$id." order by " . $order_by . ' ' . $order;
$table = "sm_complain c INNER JOIN sm_student st ON (c.cm_stu_id= st.stu_id) INNER JOIN sm_school_master sm ON (sm.sc_id = st.stu_sc_id) ";
// echo "SELECT ".$select . " FROM ". $table . " WHERE " .$condition;
$pageObj = new PS_Pagination($table, $select, $condition, $per_page, 10, "per_page=" . $per_page . "&cm_identy_id=" . $cm_identy_id . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();



 $q_details = "SELECT IF(cd.cmd_create_by_admin_id !=0,ad.admin_uname,CONCAT(st.stu_first_name,' ', st.stu_last_name)) as comment_by_name  ,cd.cmd_create_by_admin_id, cd.cmd_create_by_stu_id,  st.stu_first_name, st.stu_last_name,  DATE_FORMAT(cd.cmd_create_date,'%d-%c-%Y')  as  cmd_create_date, cd.cmd_text FROM sm_complain_details cd INNER JOIN sm_complain c ON (cd.cmd_cm_id = c.cm_id) LEFT JOIN sm_student st ON (st.stu_id = cd.cmd_create_by_stu_id) LEFT JOIN sm_admin ad ON (ad.admin_id = cd.cmd_create_by_admin_id)  WHERE c.cm_id = " . $id;
$result_detail = m_process('get_data', $q_details);
if ($result_detail['errormsg'] != '') {
    $errormsg = $result_detail['errormsg'];
}

//get total records from project table
$sm_complain = new complain();
$sm_complain->cquery = "select * from $table WHERE $condition";
$sm_complain->action = 'get';
$result = $sm_complain->process();
if ($result['status'] == 'failure') {
    $errormsg = $result['errormsg'];
} else {
    if ($result['count'] > 0) {
        $total_rows = $result['count'];
    }
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
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        View/Process Complain
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Process Complain</li>
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
                                <form class="form-horizontal" name="form1" id="form1" method="post" onsubmit="return validate_add_edit_form();" >
                                    <input type="hidden" name="act" id="act">
                                        <input type="hidden"  name="id" id="id" value="<?php echo $id; ?>">
                                            <div class="box-body">
                                                <div class="col-md-6">
                                                    <?php
                                                    if ($objData) {
                                                        $db_row = $objData->fetch();
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 ">Complain No</label>
                                                            <div class="col-sm-9 margin-top:2px; ">
                                                                <?php echo $db_row['cm_identy_id']; ?>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="col-sm-3 ">No</label>
                                                            <div class="col-sm-9 ">
                                                                <?php echo $db_row['stu_gr_no']; ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-3 ">Name</label>
                                                            <div class="col-sm-9 ">
                                                                <?php echo $db_row['stu_first_name'] . " " . $db_row['stu_last_name']; ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-3 ">Title</label>
                                                            <div class="col-sm-9 ">
                                                                <?php echo $db_row['cm_title']; ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-3 ">Description</label>
                                                            <div class="col-sm-9 ">
                                                                <?php echo $db_row['cm_description']; ?>
                                                            </div>
                                                        </div>


                                                        <?php
                                                        if ($result_detail['errormsg'] == '') {
                                                            foreach ($result_detail['res'] as $obj_row) {
                                                                ?>
                                                                <div class="form-group">
                                                                    <label class="col-sm-12 ">Discussion Details</label>
                                                                    <div class="col-sm-12 ">
                                                                        <?php echo $obj_row['cmd_text']; ?> by <?php echo $obj_row['comment_by_name'] ?> as on <?php echo $obj_row['cmd_create_date']; ?> 
                                                                    </div>
                                                                </div>                                             
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label">Complain Reply</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" id="cmd_text" name="cmd_text" ><?php echo $cmd_text; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                                             
                                            </div><!-- /.box-body -->
                                            <div class="box-footer">                                        
                                                <button type="submit" class="btn btn-info">Submit</button>
                                                <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_complain.php'">Cancel</button>
                                            </div><!-- /.box-footer -->
                                            </form>                                
                                            </div>

                                            </div>
                                            </div>


                                            </section>
                                            </div>
<?php include("includes/footer.php"); ?>
                                            </div>
                                            </body>
                                            </html>
