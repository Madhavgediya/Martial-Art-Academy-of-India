<?php
include("includes/application_top.php");
include("../includes/class/student_document.php");

// Set the caption of button

$id = get_rdata("id", 0);
$act = get_rdata("act");

$doc_name = get_rdata('doc_name');
$doc_stu_id = get_rdata('doc_stu_id');



$doc_create_date = $cur_date;
$doc_create_by_id = $tmp_admin_id;
$doc_update_date = $cur_date;
$doc_update_by_id = $tmp_admin_id;

$caption = "Student Document";
$btn_caption = "Student Document";

//if ($id != 0) 
//{
//    $caption = "Edit Student Document";
//    $btn_caption = "Edit Student Document";
//}

// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);

$msg = get_rdata('msg', '');
if (isset($msg) && $msg == 1) {
    $successmsg = "Student Document Has Been Deleted Successfully";
} else if (isset($msg) && $msg == 2) {
    $successmsg = "Student Document Has Been Added Successfully";
} else if (isset($msg) && $msg == 3) {
    $successmsg = "Student Document Has Been Updated Successfully";
} else {
    $successmsg = '';
}



// Get the data from database
//if ($act == '' && $id != 0) {
//    $student_documentmaster = new student_document();
//    $student_documentmaster->data["*"] = "";
//    $student_documentmaster->action = 'get';
//    $student_documentmaster->process_id = $id;
//    $result = $student_documentmaster->process();
//    if ($result['status'] == 'failure') {
//        $errormsg = $result['errormsg'];
//    } else {
//        if ($result['count'] > 0) {
//            foreach ($result['res'] as $db_row) {
//                $doc_name = $db_row['doc_name'];
//                $doc_stu_id = $db_row['doc_stu_id'];
//                $doc_file_name_original = $db_row['doc_file_name_original'];
//                $doc_file_name_save = $db_row['doc_file_name_save'];
//            }
//        }
//    }
//}


if (($act == 'update' || $act == 'delete') && $doc_stu_id != 0 && $id != 0) {
   
    $cquery = "DELETE FROM sm_student_document WHERE doc_id = " . $id . " AND doc_stu_id=" . $doc_stu_id;
    $cat_result = m_process('delete', $cquery);
    if ($cat_result['status'] == 'failure') {
        echo $errormsg = $cat_result['errormsg'];
    } else {
        $successmsg = 'Document has been deleted successfully.';
    }
    
}
// Add user entry
if ($act == 'add') {
    $doc_file_name_original = $doc_file_name_save = "";
    // file upload code will be here
    if ($errormsg == '') {

        if ($_FILES['doc_file_name_original']['error'] == 0) {  /// Image
            $file_array = explode(".", $_FILES['doc_file_name_original']['name']);
            $file_ext = $file_array [count($file_array) - 1];
            $file_ext = strtolower($file_ext);

            if (!(in_array($file_ext, $arr_allow_file_type_document) == 1)) {
                $errormsg = "allowed file types are " . implode(",", $arr_allow_file_type_document);
            } else {
                $doc_file_name_original = $_FILES['doc_file_name_original']['name'];
                $doc_file_name_save = "document_" . strtolower(randomPrefix(5)) . "_" . clean_name($_FILES['doc_file_name_original']['name']);
                $DestPath = STUDENT_DOCUMENT_IMAGE . $doc_file_name_save;
                move_uploaded_file($_FILES['doc_file_name_original']['tmp_name'], $DestPath);
            }
        } else {
            $errormsg = "Please upload document";
        }

        if ($errormsg == '') {
            $student_documentmaster = new student_document();
            $student_documentmaster->data["doc_name"] = $doc_name;
            $student_documentmaster->data["doc_stu_id"] = $doc_stu_id;
            $student_documentmaster->data["doc_file_name_original"] = $doc_file_name_original;
            $student_documentmaster->data["doc_file_name_save"] = $doc_file_name_save;
            $student_documentmaster->data["doc_create_date"] = $doc_create_date;
            $student_documentmaster->data["doc_create_by_id"] = $doc_create_by_id;
            $student_documentmaster->data["doc_update_date"] = $doc_update_date;
            $student_documentmaster->data["doc_update_by_id"] = $doc_update_by_id;

            $student_documentmaster->action = 'insert';
            $result = $student_documentmaster->process();
            if ($result['status'] == 'failure') {
                $errormsg = $result['errormsg'];
            } else {
                header('Location:student_document.php?doc_stu_id='.$doc_stu_id.'&msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}
// Update user entry
if ($act == 'update') {
    /*
      $not_value = " AND doc_br_id = ".$tmp_admin_id." AND doc_id != ".$id;

      $arr_duplicate_name = found_duplicate('sm_batch_time', 'doc_name', $doc_name,$not_value);
      if ($arr_duplicate_name['error_message'] != '') {
      $errormsg = $arr_duplicate_name['error_message'];
      } else if ($arr_duplicate_name['duplicate'] == true) {
      $errormsg = 'Duplicate entry for Student Document name ';
      }

      if ($errormsg == '') {
      $student_documentmaster  = new student_document();
      $student_documentmaster->data["doc_name"] = $doc_name;
      $student_documentmaster->data["doc_br_id"] = $doc_br_id;
      $student_documentmaster->data["doc_status"] = $doc_status;
      $student_documentmaster->data["doc_create_date"] = $doc_create_date;
      $student_documentmaster->data["doc_create_by_id"] = $doc_create_by_id;
      $student_documentmaster->data["doc_update_date"] = $doc_update_date;
      $student_documentmaster->data["doc_update_by_id"] = $doc_update_by_id;

      $student_documentmaster ->action = 'update';
      $student_documentmaster ->process_id = $id;
      $result = $student_documentmaster ->process();
      if ($result['status'] == 'failure') {
      $errormsg = $result['errormsg'];
      } else {

      header('Location:manage_batchtime.php?msg=3&page=1&per_page=' . $per_page);
      exit(0);
      }
      }
     */
}
$q = "SELECT * FROM sm_student_document WHERE doc_stu_id = " . $doc_stu_id;
$result = m_process("get_data", $q);
if ($result["status"] == "failure") {
    $errormsg = $result["errormsg"];
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_add_edit_circular();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" id="doc_stu_id" name="doc_stu_id" value="<?php echo $doc_stu_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input required type="text" name="doc_name" id="doc_name"  placeholder="Name" value="<?php echo $doc_name; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Document</label>
                                                <div class="col-sm-9">
                                                    <input required type="file" name="doc_file_name_original" id="doc_file_name_original"  placeholder="Upload File"  class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.box -->
                                    <div class="box-footer">
                                        <?php if ($id == 0) { ?>           <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                        <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                    </div><!-- /.box-footer -->
                                </form>
                                <!-- general form elements disabled -->
                                <div>
                                </div>
                            </div>


                        </div>

                        <!-- end of our page-->

                    </div>
                    <?php if ($errormsg == "" && $result["count"] > 0) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">                                
                                    <div class="box-body">

                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>



                                                    <th ><center>Document</center></th>

                                                    <th width="10%"><center>Action</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $srNo = 0;
                                                $class = '';
                                                $i =1;
                                                foreach ($result["res"] as $arr_db) {

                                                    $srNo++;
                                                    if ($i % 2 == 0) {
                                                        $class = 'even';
                                                    } else {
                                                        $class = 'odd';
                                                    }
                                                    ?>
                                                    <tr class="<?php echo $class; ?>">


                                                        <td style="padding-left:10px;">
                                                            <a target="_blank" href="<?php echo STUDENT_DOCUMENT_IMAGE . $arr_db["doc_file_name_save"]; ?>" class="text-info" ><?php echo ($arr_db["doc_name"] != "") ? $arr_db["doc_name"] : $arr_db["doc_file_name_original"]; ?></a></td>                                                        

                                                        <td><center>
                                                                &nbsp;
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#ConfirmDelete" class="text-danger glyphicon glyphicon-remove" onclick="delete_record(<?php echo $arr_db["doc_id"]; ?>, 'Document');" title="Delete"></a>
                                                            </center></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </section>
            </div>
            <?php include("includes/footer.php"); ?>
        </div>
        <script type="text/javascript" language="javascript">

        </script>
    </body>
</html>
