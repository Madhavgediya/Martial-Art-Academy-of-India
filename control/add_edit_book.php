<?php
include("includes/application_top.php");
include("../includes/class/book.php");
// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");
$book_id = get_rdata('book_id');
$book_identity_id = get_rdata('book_identity_id');
$book_title = get_rdata('book_title');
$book_author= get_rdata('book_author');
$book_publication = get_rdata('book_publication');
$book_language = get_rdata('book_language');
$book_price = get_rdata('book_price');
$book_issue_stu_id = get_rdata('book_issue_stu_id');
$book_br_id = get_rdata('book_br_id',$tmp_admin_id);
$book_status = get_rdata('book_status', 'A');
$book_create_date = $cur_date;
$book_create_by_id = $tmp_admin_id;
$book_update_date = $cur_date;
$book_update_by_id = $tmp_admin_id;



$caption = "Add book";
$btn_caption = "Add book";
if ($id != 0) {
    $caption = "Edit book";
    $btn_caption = "Edit book";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_book = new book();
    $sm_book->data["*"] = "";
    $sm_book->action = 'get';
    $sm_book->where =  "book_br_id = ".$tmp_admin_id." AND book_id=".$id;
    $result = $sm_book->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $book_title = $db_row['book_title'];
                $book_author= $db_row['book_author'];
                $book_publication = $db_row['book_publication'];
                $book_language= $db_row['book_language'];
                $book_price = $db_row['book_price'];
                $book_issue_stu_id = $db_row['book_issue_stu_id'];
                $book_br_id = $db_row['book_br_id'];
                $book_status = $db_row['book_status'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') 
 {

    if ($book_title == '') {
        $errormsg = 'Book title is required ';
    } else {
        $not_value = " AND book_br_id = " . $book_br_id;
        $arr_duplicate_school_name = found_duplicate('sm_book', 'book_title', escape($book_title), $not_value);
        if ($arr_duplicate_school_name['error_message'] != '') {
            $errormsg = $arr_duplicate_school_name['error_message'];
        } else if ($arr_duplicate_school_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for book title.';
        }
    }

   
    if ($errormsg == '') {
        $sm_book = new book();

        $sm_book->data["book_title"] = escape($book_title);
        $sm_book->data["book_author"] = escape($book_author);
        $sm_book->data["book_publication"] = escape($book_publication);
        $sm_book->data["book_language"] = escape($book_language);
        $sm_book->data["book_price"] = $book_price;
        $sm_book->data["book_issue_stu_id"] = 0;
        $sm_book->data["book_br_id"] = $book_br_id;
        $sm_book->data["book_status"] = $book_status;
        $sm_book->data["book_create_date"] = $book_create_date;
        $sm_book->data["book_create_by_id"] = $book_create_by_id;
        $sm_book->data["book_update_date"] = $book_update_date;
        $sm_book->data["book_update_by_id"] = $book_update_by_id;

        $sm_book->action = 'insert';
        $result = $sm_book->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_book.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
    if ($book_title == '') {
        $errormsg = 'Book title is required ';
    } else {
        $not_value = " AND book_br_id = " . $tmp_admin_id . " AND book_id != " . $id;
        $arr_duplicate_book_name = found_duplicate('sm_book', 'book_title', escape($book_title), $not_value);
        if ($arr_duplicate_book_name['error_message'] != '') {
            $errormsg = $arr_duplicate_book_name['error_message'];
        } else if ($arr_duplicate_book_name['duplicate'] == true) {
            $errormsg = 'Duplicate entry for book title.';
        }
    }
    
    

    if ($errormsg == '') {
        $sm_book = new book();
        $sm_book->data["book_title"] = escape($book_title);
        $sm_book->data["book_author"] = escape($book_author);
        $sm_book->data["book_publication"] = escape($book_publication);
        $sm_book->data["book_language"] = escape($book_language);
        $sm_book->data["book_price"] = $book_price;

        $sm_book->data["book_br_id"] = $book_br_id;
        $sm_book->data["book_status"] = $book_status;
        $sm_book->data["book_update_date"] = $book_update_date;
        $sm_book->data["book_update_by_id"] = $book_update_by_id;
        $sm_book->action = 'update';
        $sm_book->process_id = $id;
        $result = $sm_book->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
                header('Location:manage_book.php?msg=3&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}



$q_books = "SELECT b.*,s.stu_first_name,s.stu_last_name,s.stu_phone FROM sm_book b LEFT JOIN sm_student s ON (b.book_issue_stu_id =  s.stu_id) WHERE b.book_br_id = ".$tmp_admin_id." AND b.book_id  = $id ";

$result_books = m_process("get_data", $q_books);
if ($result_books['errormsg'] != '') {
    $errormsg = $result_books['errormsg'];
} else {
    $books_count = $result_books['count'];
}
// echo 'error'.$errormsg.'end of error';
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
            <!-- our page -->

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
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
                        <div class="col-md-12">
                            <!-- Horizontal Form -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $caption; ?></h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_user();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="book_br_id" name="book_br_id" value="<?php echo $book_br_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Title</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="book_title" id="book_title"  placeholder="Book title" value="<?php echo $book_title; ?>" class="form-control" />
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Author</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="book_author" id="book_author"  placeholder="Book Author" value="<?php echo $book_author; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Publication</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="book_publication" id="book_publication"  placeholder="Book Publication" value="<?php echo $book_publication; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Language</label>
                                                <div class="col-sm-9">
                                                    <input  required type="text" name="book_language" id="book_language"  placeholder="Book Language" value="<?php echo $book_language; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Price</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="book_price" id="book_price"  placeholder="Book Price" value="<?php echo $book_price; ?>" class="form-control" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Status</label>

                                                <div class="col-sm-9">
                                                    <input type="radio" name="book_status" id="book_status_a" <?php
                                                    if ($book_status == 'A') {
                                                        echo 'checked="checked"';
                                                    };
                                                    ?>  value="A" /><label for="book_status_a">Active</label> <input type="radio" name="book_status" id="book_status_i" value="I" <?php if ($book_status == 'I') echo 'checked="checked"'; ?> /><label for="book_status_i">InActive</label>
                                                </div>


                                            </div>
                                        </div>
                                    </div><!-- /.box -->
                                    <div class="box-footer">
<?php if ($id == 0) { ?>  <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                        <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                    </div><!-- /.box-footer -->
                                </form>
                                <!-- general form elements disabled -->
                                <div>
                                </div>
                            </div>

                            <?php 
                            if ($id != 0) {
                                // include "includes/book_issue_history.php";
                            } ?>
                            
                            </section>
                        </div>
                        <!-- end of our page-->
<?php include("includes/footer.php"); ?>
                    </div>
                    </body>
                    </html>