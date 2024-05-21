<?php
include("includes/application_top.php");
include("../includes/class/news.php");

// Set the caption of button




$id= get_rdata("id",0);
$act = get_rdata("act");
$ne_id= get_rdata('ne_id');
$ne_title= get_rdata('ne_title');
$ne_cover_image= get_rdata('ne_cover_image');
$ne_text= get_rdata('ne_text');
$ne_sc_id= get_rdata('ne_sc_id');
$ne_status= get_rdata('ne_status','A');
$ne_create_date= $cur_date;
$ne_create_by_id= $user_id;
$ne_update_date= $cur_date;
$ne_update_by_id= $user_id;
$ne_cover_image_old= get_rdata('ne_cover_image_old');



$caption = "Add News";
$btn_caption = "Add News";
if ($id != 0) {
    $caption = "Edit News";
    $btn_caption = "Edit News";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_news = new news();
    $sm_news->data["*"] = "";
    $sm_news->action = 'get';
    $sm_news->process_id = $id;
    $result = $sm_news->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                $ne_title = $db_row['ne_title'];
                  $ne_cover_image = $db_row['ne_cover_image'];
                  $ne_text = $db_row['ne_text'];
                  $ne_sc_id = $db_row['ne_sc_id'];
                  $ne_status = $db_row['ne_status'];
                  $ne_create_date = $db_row['ne_create_date'];
                  $ne_create_by_id = $db_row['ne_create_by_id'];
                  $ne_update_date = $db_row['ne_update_date'];
                  $ne_update_by_id = $db_row['ne_update_by_id'];
                  $ne_cover_image_old = $db_row['ne_cover_image'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {


    $not_value = " AND ne_sc_id = ".$ne_sc_id;
    $arr_duplicate_school_name = found_duplicate('sm_news', 'ne_title', $ne_title,$not_value);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for news name ';
    }

   if ($errormsg == '') {
    if($_FILES['ne_cover_image']['error']==0)  /// Image
    {

            $file_array = explode(".",$_FILES['ne_cover_image']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_file_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_file_type) ;
            }
            else
            {
                $ne_cover_image = "news_".strtolower(randomPrefix(5))."_".clean_name($_FILES['ne_cover_image']['name']);
                $DestPath=NEWS_IMAGE.$ne_cover_image;
                move_uploaded_file($_FILES['ne_cover_image']['tmp_name'], $DestPath);
            }
    }
    else
    {
            $errormsg = 'Please upload news image';
    }
   }
    if ($errormsg == '') {
        $sm_news = new news();
            $sm_news->data["ne_title"]=$ne_title;
            $sm_news->data["ne_cover_image"]=SITE_URL."images/news/".$ne_cover_image;
            $sm_news->data["ne_text"]=$ne_text;
            $sm_news->data["ne_sc_id"]=$ne_sc_id;
            $sm_news->data["ne_status"]=$ne_status;
            $sm_news->data["ne_create_date"]=$ne_create_date;
            $sm_news->data["ne_create_by_id"]=$ne_create_by_id;
            $sm_news->data["ne_update_date"]=$ne_update_date;
            $sm_news->data["ne_update_by_id"]=$ne_update_by_id;
        $sm_news->action = 'insert';
        $result = $sm_news->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {

            if ($ne_status == 'A')
                {
                    $arr_data = array();
                    $arr_data["not_message"] = "news:".$ne_title;
                    $arr_data["not_sc_id"] = $ne_sc_id;
                    $arr_data["not_create_date"] = $cur_date;
                    $arr_data["not_create_by_id"] = $user_id;
                    $arr_data["not_update_date"] = $cur_date;
                    $arr_data["not_update_by_id"] = $user_id;
                     $arr_data["not_goToScreen"]="news";

                    $arr_data["gcmp_message"] =  "news:".$ne_title;
                    $arr_data["gcmp_title"] = "news:".$ne_title;
                    $arr_data["gcmp_subtitle"] = "news:".$ne_title;
                    $arr_data["gcmp_tickerText"] = "news:".$ne_title;
                    $arr_data["gcmp_create_by"]=$user_id;
                    $arr_data["gcmp_create_date"]=$cur_date;
                    $arr_data["gcmp_gcm_sc_id"]=$ne_sc_id;
                    $arr_data["gcmp_goToScreen"]="news";

                    add_notes($arr_data);

                }

            // adding news images to vision
                header('Location:manage_news.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND ne_sc_id = ".$ne_sc_id." AND ne_id != " . $id;
    $arr_duplicate_news_name = found_duplicate('sm_news', 'ne_title', $ne_title,$not_value );
    if ($arr_duplicate_news_name['error_message'] != '') {
        $errormsg = $arr_duplicate_news_name['error_message'];
    } else if ($arr_duplicate_news_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for news name ';
    }
if ($errormsg == '') {

    if($_FILES['ne_cover_image']['error']==0)  /// Image
    {

            $file_array = explode(".",$_FILES['ne_cover_image']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_file_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_file_type) ;
            }
            else
            {
                $ne_cover_image = "news_".strtolower(randomPrefix(5))."_".clean_name($_FILES['ne_cover_image']['name']);
                $DestPath=NEWS_IMAGE.$ne_cover_image;
                move_uploaded_file($_FILES['ne_cover_image']['tmp_name'], $DestPath);
                $ne_cover_image = SITE_URL."/images/news/".$ne_cover_image;
            }
    }
    else
    {
           $ne_cover_image = $ne_cover_image_old;
    }

}
    if ($errormsg == '') {
          $sm_news = new news();
             $sm_news->data["ne_title"]=$ne_title;
            $sm_news->data["ne_cover_image"]=$ne_cover_image;
            $sm_news->data["ne_text"]=$ne_text;
            $sm_news->data["ne_sc_id"]=$ne_sc_id;
            $sm_news->data["ne_status"]=$ne_status;
            $sm_news->data["ne_create_date"]=$ne_create_date;
            $sm_news->data["ne_create_by_id"]=$ne_create_by_id;
            $sm_news->data["ne_update_date"]=$ne_update_date;
            $sm_news->data["ne_update_by_id"]=$ne_update_by_id;
        $sm_news->action = 'update';
        $sm_news->process_id = $id;
        $result = $sm_news->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($ne_cover_image !='' && $ne_cover_image_old != '' && ($ne_cover_image != $ne_cover_image_old))
            {
                $ne_cover_image_old = str_replace(SITE_URL, '', $ne_cover_image_old);
                $ne_cover_image_old = "..".$ne_cover_image_old;
               // echo $ne_cover_image_old;
               // exit;
                unlink($ne_cover_image_old);
            }
            // If success then redirect to manage user page
            header('Location:manage_news.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);
        }
    }
}

if (session_get('admin_login_type') == 'school') {
$ne_sc_id = session_get('admin_sc_id');
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

                                    <input type="hidden" id="ne_cover_image_old" name="ne_cover_image_old" value="<?php echo $ne_cover_image_old; ?>" />
                                    <input type="hidden" id="ne_sc_id" name="ne_sc_id" value="<?php echo $ne_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="ne_title" id="ne_title"  placeholder="News Title" value="<?php echo $ne_title; ?>" class="form-control" />
                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                                <textarea required name="ne_text" id="ne_text" class="form-control"   placeholder="News Description" ><?php echo $ne_text; ?></textarea>

                                            </div>
                                        </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Image</label>
                                            <div class="col-sm-9">
                                                <input <?php if ($id ==0 )  {echo "required"; }  ?> type="file" name="ne_cover_image" id="ne_cover_image"  />
                                                    <?php if ($ne_cover_image_old !='' ) { ?>
                                                <a href="<?php echo $ne_cover_image_old; ?>" target="_blank" ><img src="<?php echo $ne_cover_image_old; ?>" height="120px" width="120px" /></a>
                                                <?php }?>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="ne_status" id="ne_status_a" <?php if ($ne_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="ne_status_a">Active</label> <input type="radio" name="ne_status" id="ne_status_i" value="I" <?php if ($ne_status == 'I') echo 'checked="checked"'; ?> /><label for="ne_status_i">InActive</label>
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
