<?php
include("includes/application_top.php");
include("../includes/class/book.php");

//echo '<pre>';
//print_r($_REQUEST);
//set Page Title
$page_title = "Manage book";
$errormsg = get_rdata('errormsg', '');

$id= get_rdata("id",0);
$act = get_rdata("act");
$book_id= get_rdata('book_id');
$book_title= get_rdata('book_title');
$book_author= get_rdata('book_author');
$book_publication= get_rdata('book_publication');
$book_status= get_rdata('book_status','A');


// Set success message based on msg ID
$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Book Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Book Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Book Has Been Updated Successfully";
} else {
    $successmsg = '';
}


$total_rows = '';
$page = get_rdata("page", 1);
$per_page = get_rdata('per_page', PER_PAGE);
$order_by = get_rdata('order_by', 'book_id');
$order = get_rdata('order', 'asc');
$client_arrow = $book_title_arrow = $book_title_arrow = 'glyphicon glyphicon-chevron-down';
if ($order == 'asc') {
    $order = 'desc';
    if ($order_by == 'book_title') {
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
    
            $sm_book_master = new book();
            $sm_book_master->action = 'delete';
            $del_where = "book_id = ". $id;
            $del_where.=" and book_br_id= " . $tmp_admin_id;
            
            $sm_book_master->where = $del_where;
            $result = $sm_book_master->process();
            if ($result['status']=='failure')
            {
                  $errormsg = $result['errormsg'];
            }
            else
            {
                    $successmsg = "Book Has Been Deleted Successfully";
            }
}

//searching and pagination
$condition = ' b.book_br_id='.$tmp_admin_id;
if ($book_title != '') {
    $condition.=" and 	b.book_title LIKE '%" . escape($book_title) . "%'";
}

$condition.=" order by b." . $order_by . ' ' . $order;
$table = "sm_book b LEFT JOIN sm_student s ON (b.book_issue_stu_id =  s.stu_id) ";

$pageObj = new PS_Pagination($table, 'b.*,s.stu_first_name,s.stu_last_name,s.stu_phone', "$condition", $per_page, 10, "per_page=" . $per_page . "&book_title=" . $book_title . "&order by=" . $order_by . "&order=" . $order);
$objData = $pageObj->paginate();
$total_rows = $pageObj->totRows();

//get total records from project table
// $sm_book_master = new book();
//$sm_book_master->cquery = "select * from $table WHERE $condition";
//$sm_book_master->action = 'get';
//$result = $sm_book_master->process();
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
                        Manage book
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Manage book</li>
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
                                                <label class="col-sm-3 control-label">Book Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="book_title" id="book_title"  placeholder="Enter book Name" value="<?php echo $book_title; ?>" class="form-control" />
                                                </div>
                                            </div>
                                        </div>                                                                             
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">                                        
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <button type="button" class="btn btn-default" onclick="window.location.href = 'manage_book.php'">Cancel</button>
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
                                                <th align="center"><a href="manage_book.php?page=<?php echo $page; ?>&per_page=<?php echo $per_page; ?>&order_by=book_title&order=<?php echo $order; ?>">book Name <span class="<?php echo $book_title_arrow; ?>"></span></a></th>
                                                <th align="center">Issued To</th>
                                                <th align="center">Issued Date</th>
                                                <th align="center">Issuer Contact</th>
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
                                                        <td style="padding-left:10px;"><?php echo $db_row['book_title']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_first_name']." ".$db_row['stu_last_name']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['book_issue_stu_id']==0?"":convert_db_to_disp_date($db_row['book_issue_date'])); ?></td>
                                                        <td style="padding-left:10px;"><?php echo $db_row['stu_phone']; ?></td>
                                                        <td style="padding-left:10px;"><?php echo ($db_row['book_status']=='A'?'Active':'Inactive'); ?></td>
                                                        <td><center><a href="add_edit_book.php?id=<?php echo $db_row['book_id']; ?>&per_page=<?php echo $per_page; ?>" class="text-success glyphicon glyphicon-pencil" ></a>&nbsp;&nbsp;
		<a href="info_book.php?id=<?php echo $db_row['book_id']; ?>&per_page=<?php echo $per_page; ?>" class="fa fa-info-circle"></a>&nbsp;
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $db_row['book_id']; ?>,'book')"></a>&nbsp;
                                                                <?php if ($db_row['book_issue_stu_id']!=0) { ?>
                                                                <a href="javascript:void(0);" onclick="return_re_check_model(<?php echo $db_row['book_id']; ?>,<?php echo $db_row['book_issue_stu_id'] ?>,'<?php echo escape($db_row['book_title']); ?>','<?php echo escape($db_row['stu_first_name'].' '.$db_row['stu_last_name']); ?>');" class="fa fa-fw fa-book" ></a>
                                                                <?php }  ?>
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
            <?php include("includes/return-reissue-book.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
