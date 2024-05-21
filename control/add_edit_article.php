<?php
include("includes/application_top.php");
include("../includes/class/article.php");

// Set the caption of button




$id= get_rdata("id",0);
$act = get_rdata("act");
$art_id= get_rdata('art_id');
$art_title= get_rdata('art_title');
$art_document= get_rdata('art_document');
$art_text= get_rdata('art_text');
$art_sc_id= get_rdata('art_sc_id');
$art_status= get_rdata('art_status','A');
$art_create_date= $cur_date;
$art_create_by_id= $user_id;
$art_update_date= $cur_date;
$art_update_by_id= $user_id;
$art_document_old= get_rdata('art_document_old');



$caption = "Add Article";
$btn_caption = "Add Article";
if ($id != 0) {
    $caption = "Edit Article";
    $btn_caption = "Edit Article";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_article = new article();
    $sm_article->data["*"] = "";
    $sm_article->action = 'get';
    $sm_article->process_id = $id;
    $result = $sm_article->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $art_title = $db_row['art_title'];
                  $art_document = $db_row['art_document'];
                  $art_text = $db_row['art_text'];
                  $art_sc_id = $db_row['art_sc_id'];
                  $art_status = $db_row['art_status'];
                  $art_create_date = $db_row['art_create_date'];
                  $art_create_by_id = $db_row['art_create_by_id'];
                  $art_update_date = $db_row['art_update_date'];
                  $art_update_by_id = $db_row['art_update_by_id'];
                  $art_document_old = $db_row['art_document'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {


    $not_value = " AND art_sc_id = ".$art_sc_id;
    $arr_duplicate_school_name = found_duplicate('sm_article', 'art_title', $art_title,$not_value);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for article name ';
    }

   if ($errormsg == '') {
    if($_FILES['art_document']['error']==0)
    {

            $file_array = explode(".",$_FILES['art_document']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_document_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_document_type) ;
            }
            else
            {
                $art_document = "article_".strtolower(randomPrefix(5))."_".clean_name($_FILES['art_document']['name']);
                $DestPath=ARTICLE_DOC.$art_document;
                move_uploaded_file($_FILES['art_document']['tmp_name'], $DestPath);
            }
    }
    else
    {
            $errormsg = 'Please upload article document';
    }
   }
    if ($errormsg == '') {
        $sm_article = new article();
            $sm_article->data["art_title"]=$art_title;
            $sm_article->data["art_document"]=SITE_URL."document/article/".$art_document;
            $sm_article->data["art_text"]=$art_text;
            $sm_article->data["art_sc_id"]=$art_sc_id;
            $sm_article->data["art_status"]=$art_status;
            $sm_article->data["art_create_date"]=$art_create_date;
            $sm_article->data["art_create_by_id"]=$art_create_by_id;
            $sm_article->data["art_update_date"]=$art_update_date;
            $sm_article->data["art_update_by_id"]=$art_update_by_id;
        $sm_article->action = 'insert';
        $result = $sm_article->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            if ($art_status == 'A')
                {
                    $arr_data = array();
                    $arr_data["not_message"] = "article:".$art_title;
                    $arr_data["not_sc_id"] = $art_sc_id;
                    $arr_data["not_create_date"] = $cur_date;
                    $arr_data["not_create_by_id"] = $user_id;
                    $arr_data["not_update_date"] = $cur_date;
                    $arr_data["not_update_by_id"] = $user_id;
                    $arr_data["not_goToScreen"]="article";

                    $arr_data["gcmp_message"] =  "article:".$art_title;
                    $arr_data["gcmp_title"] = "article:".$art_title;
                    $arr_data["gcmp_subtitle"] = "article:".$art_title;
                    $arr_data["gcmp_tickerText"] = "article:".$art_title;
                    $arr_data["gcmp_create_by"]=$user_id;
                    $arr_data["gcmp_create_date"]=$cur_date;
                    $arr_data["gcmp_gcm_sc_id"]=$art_sc_id;
                    $arr_data["gcmp_goToScreen"]="article";

                    add_notes($arr_data);

                }

                header('Location:manage_article.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND art_sc_id = ".$art_sc_id." AND art_id != " . $id;
    $arr_duplicate_article_name = found_duplicate('sm_article', 'art_title', $art_title,$not_value );
    if ($arr_duplicate_article_name['error_message'] != '') {
        $errormsg = $arr_duplicate_article_name['error_message'];
    } else if ($arr_duplicate_article_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for article name ';
    }

   if ($errormsg == '') {
    if($_FILES['art_document']['error']==0)  /// Image
    {

            $file_array = explode(".",$_FILES['art_document']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_document_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_document_type) ;
            }
            else
            {
                $art_document = "article_".strtolower(randomPrefix(5))."_".clean_name($_FILES['art_document']['name']);
                $DestPath=ARTICLE_DOC.$art_document;
                move_uploaded_file($_FILES['art_document']['tmp_name'], $DestPath);
                $art_document = SITE_URL."/document/article/".$art_document;
            }
    }
    else
    {
           $art_document = $art_document_old;
    }
   }

    if ($errormsg == '') {
          $sm_article = new article();
             $sm_article->data["art_title"]=$art_title;
            $sm_article->data["art_document"]=$art_document;
            $sm_article->data["art_text"]=$art_text;
            $sm_article->data["art_sc_id"]=$art_sc_id;
            $sm_article->data["art_status"]=$art_status;
            $sm_article->data["art_create_date"]=$art_create_date;
            $sm_article->data["art_create_by_id"]=$art_create_by_id;
            $sm_article->data["art_update_date"]=$art_update_date;
            $sm_article->data["art_update_by_id"]=$art_update_by_id;
        $sm_article->action = 'update';
        $sm_article->process_id = $id;
        $result = $sm_article->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($art_document !='' && $art_document_old != '' && ($art_document != $art_document_old))
            {
                $art_document_old = str_replace(SITE_URL, '', $art_document_old);
                $art_document_old = "..".$art_document_old;
               // echo $art_document_old;
               // exit;
                unlink($art_document_old);
            }
            // If success then redirect to manage user page
            header('Location:manage_article.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

if (session_get('admin_login_type') == 'school') {
$art_sc_id = session_get('admin_sc_id');
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_add_edit_form();">
                                    <input type="hidden" id="act" name="act" />


                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

                                    <input type="hidden" id="art_document_old" name="art_document_old" value="<?php echo $art_document_old; ?>" />
                                    <input type="hidden" id="art_sc_id" name="art_sc_id" value="<?php echo $art_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="art_title" id="art_title"  placeholder="Article Title" value="<?php echo $art_title; ?>" class="form-control" />
                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                                <textarea required name="art_text" id="art_text" class="form-control"  placeholder="Article Description"  ><?php echo $art_text; ?></textarea>

                                            </div>
                                        </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Document</label>
                                            <div class="col-sm-9">
                                                <input <?php if ($id ==0 )  {echo "required"; }  ?> type="file" name="art_document" id="art_document"  />
                                                    <?php if ($art_document_old !='' ) { ?>
                                                <a href="<?php echo $art_document_old; ?>" target="_blank" >View/Download Document</a>
                                                <?php }?>
                                                [Allow File Types are <?php echo implode(", ",$arr_allow_document_type); ?>]
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="art_status" id="art_status_a" <?php if ($art_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="art_status_a">Active</label> <input type="radio" name="art_status" id="art_status_i" value="I" <?php if ($art_status == 'I') echo 'checked="checked"'; ?> /><label for="art_status_i">InActive</label>
                                            </div>


                                        </div>
                                        <!-- /.box-body -->

                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?>           <input type="reset" value="Reset" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
                                            <button type="submit" class="btn btn-info pull-right" id="btnAddUser" name="btnAddUser">Save</button>
                                        </div><!-- /.box-footer -->
                            </form>
                            <!-- general form elements disabled -->
                            <div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- end of our page-->
<?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>
