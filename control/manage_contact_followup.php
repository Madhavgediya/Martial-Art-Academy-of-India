<?php
include("includes/application_top.php");
include("../includes/class/contact_followup.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage Contact Followup";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$con_con_id= get_rdata('con_con_id');



// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Contact Followup Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Contact Followup Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Contact Followup Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'con_id');
$order = get_rdata('order', 'asc');
$client_arrow = $con_name_arrow = $con_name_arrow = 'glyphicon glyphicon-chevron-down';
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
if (isset($_GET['page'])) {
    $srNo = $per_page * ($_GET['page'] - 1);
} else {
    $srNo = 0;
}

// step 3: make new object of user class
// delete user code
if ($act == 'delete' && $id != 0) {
    
            $sm_con_master = new contact_followup();
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
                    $successmsg = "Contact Has Been Deleted Successfully";
            }
}

//searching and pagination
$condition = ' b.con_con_id = '.$con_con_id.' AND b.con_br_id='.$tmp_admin_id;

$condition.=" order by b." . $order_by . ' ' . $order;
$table = "sm_contact_followup b ";

$pageObj = new PS_Pagination($table, 'b.*', "$condition", $per_page, 10, "per_page=" . $per_page . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

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
                    <!-- start of main section -->
                    <?php
                                $contact_q = "SELECT c.con_id, c.con_name, c.con_email, c.con_phone, c.con_message, c.con_status, c.con_date, c.con_no_of_followup, c.con_followup_date, c.con_create_date, c.con_followup_type  FROM  sm_contact c  WHERE  c.con_id = $con_con_id";  
                                $contact_r = m_process("get_data",$contact_q);
                                if ($contact_r["status"] == 'success' && $contact_r["count"]>0) 
                                        {
                                ?>
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="box box-info">                                
                                <div class="box-header with-border">
                                    <h3 class="box-title">Details</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                
                                
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="col-sm-6">Name</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_name"]; ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-6">Email</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_email"]; ?>
                                                </div>
                                            </div>
                                        </div>      
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="col-sm-6">Contact</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_phone"]; ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-6">Status</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_status"]; ?>
                                                </div>
                                            </div>
                                        </div>      
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="col-sm-6">Type</label>
                                                <div class="col-sm-6">
                                                    <?php echo $contact_r["res"][0]["con_followup_type"]; ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-6">Followup Date</label>
                                                <div class="col-sm-6">
                                                    <?php  
                                                    if ($contact_r["res"][0]["con_followup_date"] !='')
                                                    {
                                                        echo convert_db_to_disp_date($contact_r["res"][0]["con_followup_date"]);
                                                    }
                                                     ?>
                                                </div>
                                            </div>
                                        </div>                                                                             
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3">Message</label>
                                                <div class="col-sm-9 text-left" >
                                                    <?php echo nl2br($contact_r["res"][0]["con_message"]); ?>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="col-md-12">
                                            <div class="form-group">
                                            <a href="add_edit_contact_followup.php?con_con_id=<?php echo $contact_r['res'][0]['con_id'];?>">
                                            <button type="button" class="btn btn-info pull-right" id="" name="">Add followup</button>
                                            </a>
                                                
                                            </div>
                                        </div>   
                                                                                                                 
                                    </div><!-- /.box-body -->
                                                                 
                            </div>

                        </div>
                    </div>
                    <?php } ?> 
                    <!-- end of main section -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">                                
                                <div class="box-body">
                                <form class="form-horizontal" name="form1" id="form1" method="post">
                                    <input type="hidden" name="act" id="act">
                 		<input type="hidden" value="0" name="id" id="id">
                         </form>

                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center">Followup Date</th>
                                                <th align="center">Message</th>
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
                                                        <td style="padding-left:10px;"><?php echo convert_db_to_disp_date($db_row['con_followup_date']); ?></td>
                                                        <td style="padding-left:10px;"><?php echo nl2br($db_row['con_message']); ?></td>
                                                        
                                                        <td><center><a href="add_edit_contact_followup.php?id=<?php echo $db_row['con_id']; ?>&con_con_id=<?php echo $db_row['con_con_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['con_id']; ?>,'Contact Followup')"></a>
                                                                
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
