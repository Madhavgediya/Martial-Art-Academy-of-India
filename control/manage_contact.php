<?php
include("includes/application_top.php");
include("../includes/class/contact.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Inquiry";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$con_id= get_rdata('con_id');
$con_name= get_rdata('con_name');
$con_email = get_rdata('con_email');
$con_phone= get_rdata('con_phone');
$con_status = get_rdata('con_status','Open');
$con_followup_type = get_rdata('con_followup_type','All');

// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Contact Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Contact Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Contact Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'con_id');
$order = get_rdata('order', 'asc');
$client_arrow = $con_name_arrow = $con_name_arrow = 'glyphicon glyphicon-chevron-down';

if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {
    
            $sm_con_master = new Contact();
            $sm_con_master->action = 'delete';
            $del_where = "con_id = ". $id;
            $del_where.=" and con_br_id= " . $tmp_admin_id;
            
            $sm_con_master->where = $del_where;
            $result = $sm_con_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                    $q_followup = "DELETE FROM sm_contact_followup WHERE con_br_id = $tmp_admin_id AND con_con_id = ".$id;
                    $r_followup =  m_process("delete",$q_followup);
                    if ($r_followup['status']=='failure')
                    {
                          $errormsg = $r_followup['errormsg'];
                    }
                    $successmsg = "Contact Has Been Deleted Successfully";
            }
}

//searching and pagination
$condition = ' b.con_br_id='.$tmp_admin_id;
if ($con_name != '') {
    $condition.=" and 	b.con_name LIKE '%" . $con_name . "%'";
}
if ($con_status != '') {
    $condition.=" and 	b.con_status = '" . $con_status . "'";
}
if ($con_followup_type != 'All') {
    $condition.=" and 	b.con_followup_type = '" . $con_followup_type . "'";
}


$condition.=" order by b." . $order_by . ' ' . $order;
$table = "sm_contact b ";

$pageObj = new PS_Pagination($table, 'b.*', "$condition", $per_page, 10, "per_page=" . $per_page . "&con_status=" . $con_status . "&con_followup_type=" . $con_followup_type. "&con_name=" . $con_name . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'con_name') {
        $sc_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else {
        $client_arrow = 'glyphicon glyphicon-chevron-up';
    }
} else {
    $order = 'asc';
}

//get total records from project table
// $sm_con_master = new Contact();
//$sm_con_master->cquery = "select * from $table WHERE $condition";
//$sm_con_master->action = 'get';
//$result = $sm_con_master->process();
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
                        Manage Inquiry
                        
                    </h1>
                    
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage Inquiry</li>
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
                                                <label class="col-sm-3 control-label">Contact Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="con_name" id="con_name"  placeholder="Enter Contact Name" value="<?php echo $con_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>
                                                <div class="col-sm-9">
                                                <select id="con_status"  name="con_status" class="form-control" >
                                                        <option  <?php if($con_status == '') echo ' selected="selected" ';  ?>   value="">All</option>
                                                        <option  <?php if($con_status == 'Open') echo ' selected="selected" ';  ?> value="Open">Open</option>
                                                        <option  <?php if($con_status == 'Closed') echo ' selected="selected" ';  ?> value="Closed">Closed</option>
                                                        <option  <?php if($con_status == 'Discussion') echo ' selected="selected" ';  ?> value="Discussion">Discussion</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>        -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Type</label>
                                                <div class="col-sm-9">
                                                        <select id="con_followup_type"  name="con_followup_type" class="form-control" >
                                                        <option <?php if($con_followup_type=="All") echo "selected"; ?>  value="All">All</option>
                                                        <option <?php if($con_followup_type=="Contact") echo "selected"; ?>  value="Contact">Contact</option>
                                                        <option <?php if($con_followup_type=="Fee") echo "selected"; ?> value="Fee">Fee</option>
                                                        <option <?php if($con_followup_type=="Document") echo "selected"; ?> value="Document">Document</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">                                        
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_contact.php'">Cancel</button>
                                        <button type="button" style="float:right;" class="btn btn-success" onclick="window.location.href='add_edit_contact.php'">ADD</button>
                                    </div><!-- /.box-footer -->
                                </form>                                
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                           
                                <ul class="nav nav-tabs">
                                    <li><a href="manage_contact.php?con_status=" class="btn btn-default">All</a></li>
                                    <li><a href="manage_contact.php?con_status=Open" class="btn btn-default">Open</a></li>
                                    <li><a href="manage_contact.php?con_status=Closed" class="btn btn-default">Closed</a></li>
                                    <li><a href="manage_contact.php?con_status=Discussion" class="btn btn-default">Discussion</a></li>
                                </ul>
                            
                            <div class="box">                                
                                <div class="box-body">
                                <p class="lead text-center mid_caption"><?php if($con_status==''){echo "All";}else{echo $con_status;};?></p> 
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center"><a href="manage_contact.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=con_name&order=<?php echo $order; ?>">Name <span class="<?php echo $con_name?>"></span></a></th>
                                                <th align="center">Phone</th>
                                                <th align="center">Date</th>
                                                <th align="center">Type</th>
                                               <th align="center">Follow up date</th>
                                              <!--   <th align="center">Follow up(s)</th>  -->
                                                <th align="center" width="80px">Status</th>
                                                <th align="center" class="t_align_center"  width="100px">Action</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['con_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['con_phone']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['con_date']==0?"":convert_db_to_disp_date($db_row['con_date'])); ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['con_followup_type']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['con_followup_date']==0?"":convert_db_to_disp_date($db_row['con_followup_date'])); ?></td>
                                                      <!--  <td style="padding-left:10px;"><?php echo $db_row['con_no_of_followup']; ?></td> -->
                                                        <td style="padding-left:10px;"><?php echo $db_row['con_status']; ?></td>
                                                        <td><center><a href="add_edit_contact.php?id=<?php echo $db_row['con_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['con_id']; ?>,'Contact')"></a>&nbsp;
                                                                
                                                                <a href="add_edit_contact_followup.php?con_con_id=<?php echo $db_row['con_id']; ?>" class="text-success fa fa-fw fa-plus" ></a>&nbsp;
                                                                <a href="manage_contact_followup.php?con_con_id=<?php echo $db_row['con_id']; ?>" class="text-success fa fa-fw fa-info" ></a>&nbsp;
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
