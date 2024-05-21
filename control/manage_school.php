<?php
include("includes/application_top.php");
include("../includes/class/school_master.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage School";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$sc_id= get_rdata('sc_id');
$sc_name= get_rdata('sc_name');
$sc_phone_1= get_rdata('sc_phone_1');
$sc_phone_2= get_rdata('sc_phone_2');
$sc_email= get_rdata('sc_email');
$sc_city= get_rdata('sc_city');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "School Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "School Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "School Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'sc_id');
$order = get_rdata('order', 'asc');
$client_arrow = $sc_name_arrow = $sc_city_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'sc_namee') {
        $sc_name_arrow = 'glyphicon glyphicon-chevron-up';
    } else if ($order_by == 'sc_city') {
        $sc_city_arrow = 'glyphicon glyphicon-chevron-up';
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
    
            $sm_school_master = new school_master();
            $sm_school_master->action = 'delete';
            $sm_school_master->where = "sc_id =". $id;
            $result = $sm_school_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                   $successmsg = "School Has Been Deleted Successfully";
            }
}


//searching and pagination
$condition = '1';
if ($sc_name != '') {
    $condition.=" and 	sc_name LIKE '%" . $sc_name . "%'";
}
if ($sc_city != '') {
    $condition.=" and sc_city LIKE '%" . $sc_city . "%'";
}

$condition.=" order by " . $order_by . ' ' . $order;
$table = "sm_school_master";
$pageObj = new PS_Pagination($table, '*', "$condition", $per_page, 10, "per_page=" . $per_page . "&sc_name=" . $sc_name . "&sc_city=" . $sc_city . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();

//get total records from project table
 $sm_school_master = new school_master();
$sm_school_master->cquery = "select * from $table WHERE $condition";
$sm_school_master->action = 'get';
$result = $sm_school_master->process();
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
                        Manage School
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage School</li>
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
                                <form class="form-horizontal" name="form1" id="form1" method="get" onsubmit="return validate_add_edit_form();">
                                    <input type="hidden" name="act" id="act">
                 		<input type="hidden" value="0" name="id" id="id">
                                    <div class="box-body">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">School Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="sc_name" id="sc_name"  placeholder="Enter School Name" value="<?php echo $sc_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">City</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="sc_city" id="sc_city"  placeholder="Enter City"  title="Enter City" value="<?php echo $sc_city; ?>"  class="form-control"/>
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
                                                <th align="center"><a href="manage_school.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=sc_name&order=<?php echo $order; ?>">School Name <span class="<?php echo $sc_name_arrow; ?>"></span></a></th>
                                                <th align="center">Phone 1</th>
                                                <th align="center">Phone 2</th>
                                                <th align="center">Email</th>
                                                <th align="center"><a href="manage_school.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=sc_city&order=<?php echo $order; ?>">City <i class="<?php echo $sc_city_arrow; ?>"></i></a></th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['sc_name']; ?></td>
                                                        <td><?php echo $db_row['sc_phone_1']; ?></td>
                                                        <td><?php echo $db_row['sc_phone_2']; ?></td>
                                                        <td><?php echo $db_row['sc_email']; ?></td>
                                                        <td><?php echo $db_row['sc_city']; ?></td>
                                                        <td><center><a href="add_edit_school.php?id=<?php echo $db_row['sc_id']; ?>&per_page=<?php echo $per_page; ?>" class="edit glyphicon glyphicon-pencil" ></a>&nbsp;<a href="javascript:void(0);" class="delete glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['sc_id']; ?>)"></a></center></td>
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
