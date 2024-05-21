<?php
include("includes/application_top.php");
include("../includes/class/events.php");
include("../includes/class/events_images.php");

// Set the caption of button




$id= get_rdata("id",0);
$act = get_rdata("act");
$ev_id= get_rdata('ev_id');
$ev_title= get_rdata('ev_title');
$ev_text= get_rdata('ev_text');
$ev_start_date= get_rdata('ev_start_date');
$ev_end_date= get_rdata('ev_end_date');
$ev_cover_image= get_rdata('ev_cover_image');
$ev_contact_person= get_rdata('ev_contact_person');
$ev_contact_person_phone= get_rdata('ev_contact_person_phone');
$ev_venue_add_1= get_rdata('ev_venue_add_1');
$ev_venue_add_2= get_rdata('ev_venue_add_2');
$ev_venue_city= get_rdata('ev_venue_city');
$ev_venue_st_id= get_rdata('ev_venue_st_id');
$ev_venue_postal_code= get_rdata('ev_venue_postal_code');
$ev_sc_id= get_rdata('ev_sc_id');
$ev_status= get_rdata('ev_status','A');
$ev_cover_image_old= get_rdata('ev_cover_image_old');
$ev_create_date= $cur_date;
$ev_create_by_id= $user_id;
$ev_update_date= $cur_date;
$ev_update_by_id= $user_id;
$file_counter= InputPro('file_counter');


$caption = "Add Events";
$btn_caption = "Add Events";
if ($id != 0) {
    $caption = "Edit Events";
    $btn_caption = "Edit Events";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_events = new events();
    $sm_events->data["*"] = "";
    $sm_events->action = 'get';
    $sm_events->process_id = $id;
    $result = $sm_events->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                  $ev_title = $db_row['ev_title'];
                  $ev_text = $db_row['ev_text'];
                  $ev_start_date = $db_row['ev_start_date'];
                  $ev_end_date = $db_row['ev_end_date'];
                  $ev_cover_image = $db_row['ev_cover_image'];
                  $ev_cover_image_old = $db_row['ev_cover_image'];
                  $ev_contact_person = $db_row['ev_contact_person'];
                  $ev_contact_person_phone = $db_row['ev_contact_person_phone'];
                  $ev_venue_add_1 = $db_row['ev_venue_add_1'];
                  $ev_venue_add_2 = $db_row['ev_venue_add_2'];
                  $ev_venue_city = $db_row['ev_venue_city'];
                  $ev_venue_st_id = $db_row['ev_venue_st_id'];
                  $ev_venue_postal_code = $db_row['ev_venue_postal_code'];
                  $ev_sc_id = $db_row['ev_sc_id'];
                  $ev_status = $db_row['ev_status'];
                  $ev_create_date = $db_row['ev_create_date'];
                  $ev_create_by_id = $db_row['ev_create_by_id'];
                  $ev_update_date = $db_row['ev_update_date'];
                  $ev_update_by_id = $db_row['ev_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {


    $not_value = " AND ev_sc_id = ".$ev_sc_id;
    $arr_duplicate_school_name = found_duplicate('sm_events', 'ev_title', $ev_title,$not_value);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for events name ';
    }

  if ($errormsg == '') {
    if($_FILES['ev_cover_image']['error']==0)  /// Image
    {

            $file_array = explode(".",$_FILES['ev_cover_image']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_file_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_file_type) ;
            }
            else
            {
                $ev_cover_image = "event_".strtolower(randomPrefix(5))."_".clean_name($_FILES['ev_cover_image']['name']);
                $DestPath=EVENTS_IMAGE.$ev_cover_image;
                move_uploaded_file($_FILES['ev_cover_image']['tmp_name'], $DestPath);
            }
    }
    else
    {
            $errormsg = 'Please upload event image';
    }
}
    if ($errormsg == '') {
        $sm_events = new events();
       $sm_events->data["ev_title"]=$ev_title;
            $sm_events->data["ev_text"]=$ev_text;
            $sm_events->data["ev_start_date"]=$ev_start_date;
            $sm_events->data["ev_end_date"]=$ev_end_date;
            $sm_events->data["ev_cover_image"]=SITE_URL."images/events/".$ev_cover_image;
            $sm_events->data["ev_contact_person"]=$ev_contact_person;
            $sm_events->data["ev_contact_person_phone"]=$ev_contact_person_phone;
            $sm_events->data["ev_venue_add_1"]=$ev_venue_add_1;
            $sm_events->data["ev_venue_add_2"]=$ev_venue_add_2;
            $sm_events->data["ev_venue_city"]=$ev_venue_city;
            $sm_events->data["ev_venue_st_id"]=$ev_venue_st_id;
            $sm_events->data["ev_venue_postal_code"]=$ev_venue_postal_code;
            $sm_events->data["ev_sc_id"]=$ev_sc_id;
            $sm_events->data["ev_status"]=$ev_status;
            $sm_events->data["ev_create_date"]=$ev_create_date;
            $sm_events->data["ev_create_by_id"]=$ev_create_by_id;
            $sm_events->data["ev_update_date"]=$ev_update_date;
            $sm_events->data["ev_update_by_id"]=$ev_update_by_id;
        $sm_events->action = 'insert';
        $result = $sm_events->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // adding events images to vision
            $ei_ev_id = $result['id'];
            $iimg =0;
            while($iimg <= $file_counter)
            {
                 if($_FILES['event_details_pic_'.$iimg]['error']==0)  /// Image
                {
                    $file_array = explode(".",$_FILES['event_details_pic_'.$iimg]['name']);
                    $file_ext = $file_array [count($file_array)-1];
                    $file_ext = strtolower($file_ext);

                    if (! (in_array($file_ext,$arr_allow_file_type) == 1))
                    {
                            $errormsg .="Error for file ".$_FILES['event_details_pic_'.$iimg]['name']. "allowed file types are ". implode(",",$arr_allow_file_type) ;
                    }
                    else
                    {
                        $ei_image = "event_".strtolower(randomPrefix(5))."_".clean_name($_FILES['event_details_pic_'.$iimg]['name']);
                        $DestPath=EVENTS_IMAGE.$ei_image;
                        move_uploaded_file($_FILES['event_details_pic_'.$iimg]['tmp_name'], $DestPath);
                        //echo $ei_image; exit;
                        // adding files in db
                        // $sm_events_images->data["ei_id"]=$ei_id;
                        $sm_events_images = new events_images();
                        $sm_events_images->data["ei_title"]=$ev_title;
                        $sm_events_images->data["ei_image"]=SITE_URL."images/events/".$ei_image;
                        $sm_events_images->data["ei_image_alt"]=$ev_title;
                        $sm_events_images->data["ei_ev_id"]=$ei_ev_id;
                        $sm_events_images->data["ei_status"]='A';
                        $sm_events_images->data["ei_create_date"]=$ev_create_date;
                        $sm_events_images->data["ei_create_by_id"]=$ev_create_by_id;
                        $sm_events_images->data["ei_update_date"]=$ev_create_date;
                        $sm_events_images->data["ei_update_by_id"]=$ev_create_by_id;
                        $sm_events_images->action = 'insert';
                        $result = $sm_events_images->process();
                        if ($result['status']=='failure')
                        {
                              $errormsg .= $result['errormsg'];
                        }

                        // end of adding files in db
                    }
                }
                $iimg++;
            }
            if ($errormsg == '')
            {
                if ($ev_status == 'A')
                {
                    $arr_data = array();
                    $arr_data["not_message"] = "event:".$ev_title;
                    $arr_data["not_sc_id"] = $ev_sc_id;
                    $arr_data["not_create_date"] = $cur_date;
                    $arr_data["not_create_by_id"] = $user_id;
                    $arr_data["not_update_date"] = $cur_date;
                    $arr_data["not_update_by_id"] = $user_id;
                    $arr_data["not_goToScreen"]="events";

                    $arr_data["gcmp_message"] =  "event:".$ev_title;
                    $arr_data["gcmp_title"] = "event:".$ev_title;
                    $arr_data["gcmp_subtitle"] =  "event:".$ev_title;
                    $arr_data["gcmp_tickerText"] = "event:".$ev_title;
                    $arr_data["gcmp_create_by"]=$user_id;
                    $arr_data["gcmp_create_date"]=$cur_date;
                    $arr_data["gcmp_gcm_sc_id"]=$ev_sc_id;
                    $arr_data["gcmp_goToScreen"]="events";

                    add_notes($arr_data);

                }

                header('Location:manage_events.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND ev_sc_id = ".$ev_sc_id." AND ev_id != " . $id;
    $arr_duplicate_events_name = found_duplicate('sm_events', 'ev_title', $ev_title,$not_value );
    if ($arr_duplicate_events_name['error_message'] != '') {
        $errormsg = $arr_duplicate_events_name['error_message'];
    } else if ($arr_duplicate_events_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for events name ';
    }

   if ($errormsg == '') {
    if($_FILES['ev_cover_image']['error']==0)  /// Image
    {

            $file_array = explode(".",$_FILES['ev_cover_image']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_file_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_file_type) ;
            }
            else
            {
                $ev_cover_image = "event_".strtolower(randomPrefix(5))."_".clean_name($_FILES['ev_cover_image']['name']);
                $DestPath=EVENTS_IMAGE.$ev_cover_image;
                move_uploaded_file($_FILES['ev_cover_image']['tmp_name'], $DestPath);
                $ev_cover_image = SITE_URL."/images/events/".$ev_cover_image;
            }
    }
    else
    {
           $ev_cover_image = $ev_cover_image_old;
    }
   }

    if ($errormsg == '') {
        $sm_events = new events();
        $sm_events->data["ev_title"]=$ev_title;
            $sm_events->data["ev_text"]=$ev_text;
            $sm_events->data["ev_start_date"]=$ev_start_date;
            $sm_events->data["ev_end_date"]=$ev_end_date;
            $sm_events->data["ev_cover_image"]=$ev_cover_image;
            $sm_events->data["ev_contact_person"]=$ev_contact_person;
            $sm_events->data["ev_contact_person_phone"]=$ev_contact_person_phone;
            $sm_events->data["ev_venue_add_1"]=$ev_venue_add_1;
            $sm_events->data["ev_venue_add_2"]=$ev_venue_add_2;
            $sm_events->data["ev_venue_city"]=$ev_venue_city;
            $sm_events->data["ev_venue_st_id"]=$ev_venue_st_id;
            $sm_events->data["ev_venue_postal_code"]=$ev_venue_postal_code;
            $sm_events->data["ev_sc_id"]=$ev_sc_id;
            $sm_events->data["ev_status"]=$ev_status;
            $sm_events->data["ev_create_date"]=$ev_create_date;
            $sm_events->data["ev_create_by_id"]=$ev_create_by_id;
            $sm_events->data["ev_update_date"]=$ev_update_date;
            $sm_events->data["ev_update_by_id"]=$ev_update_by_id;
        $sm_events->action = 'update';
        $sm_events->process_id = $id;
        $result = $sm_events->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($ev_cover_image !='' && $ev_cover_image_old != '' && ($ev_cover_image != $ev_cover_image_old))
            {
                $ev_cover_image_old = str_replace(SITE_URL, '', $ev_cover_image_old);
                $ev_cover_image_old = "..".$ev_cover_image_old;
               // echo $ev_cover_image_old;
               // exit;
                unlink($ev_cover_image_old);
            }
            $ei_ev_id = $result['id'];
            $iimg =0;
            while($iimg <= $file_counter)
            {
                 if($_FILES['event_details_pic_'.$iimg]['error']==0)  /// Image
                {
                    $file_array = explode(".",$_FILES['event_details_pic_'.$iimg]['name']);
                    $file_ext = $file_array [count($file_array)-1];
                    $file_ext = strtolower($file_ext);

                    if (! (in_array($file_ext,$arr_allow_file_type) == 1))
                    {
                            $errormsg .="Error for file ".$_FILES['event_details_pic_'.$iimg]['name']. "allowed file types are ". implode(",",$arr_allow_file_type) ;
                    }
                    else
                    {
                        $ei_image = "event_".strtolower(randomPrefix(5))."_".clean_name($_FILES['event_details_pic_'.$iimg]['name']);
                        $DestPath=EVENTS_IMAGE.$ei_image;
                        move_uploaded_file($_FILES['event_details_pic_'.$iimg]['tmp_name'], $DestPath);
                        $sm_events_images = new events_images();
                        $sm_events_images->data["ei_title"]=$ev_title;
                        $sm_events_images->data["ei_image"]=SITE_URL."images/events/".$ei_image;
                        $sm_events_images->data["ei_image_alt"]=$ev_title;
                        $sm_events_images->data["ei_ev_id"]=$id;
                        $sm_events_images->data["ei_status"]='A';
                        $sm_events_images->data["ei_create_date"]=$ev_create_date;
                        $sm_events_images->data["ei_create_by_id"]=$ev_create_by_id;
                        $sm_events_images->data["ei_update_date"]=$ev_create_date;
                        $sm_events_images->data["ei_update_by_id"]=$ev_create_by_id;
                        $sm_events_images->action = 'insert';
                        $result = $sm_events_images->process();
                        if ($result['status']=='failure')
                        {
                              $errormsg .= $result['errormsg'];
                        }

                        // end of adding files in db
                    }
                }
                $iimg++;
            }
            if ($errormsg == '')
             {
            // If success then redirect to manage user page
            header('Location:manage_events.php?msg=3&page=1&per_page=' . $per_page);
            exit(0);

            }
        }
    }
}

if ($id !=0)
{
   $q = "SELECT ei.ei_image ,ei.ei_id  FROM  sm_events_images    ei INNER JOIN sm_events ev ON (ei.ei_ev_id=ev.ev_id) INNER JOIN sm_school_master sm ON (ev.ev_sc_id=sm.sc_id)
WHERE ev.ev_id= ".$id;
   if (session_get('admin_login_type') == 'school') {
    $q.=" AND sm.sc_id= " . session_get('admin_sc_id');
}
// echo $q;
        $result = m_process("get_data", $q);

        if ($result['errormsg'] != '') {
            $errormsg = $result['errormsg'];
        }
}
if (session_get('admin_login_type') == 'school') {
$ev_sc_id = session_get('admin_sc_id');
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
                                <form name="form1" id="form1" enctype="multipart/form-data" method="post" class="form-horizontal" onsubmit="return validate_add_edit_events();">
                                    <input type="hidden" id="act" name="act" />
                                    <input type="hidden" id="file_counter" name="file_counter" />

                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />

                                    <input type="hidden" id="ev_cover_image_old" name="ev_cover_image_old" value="<?php echo $ev_cover_image_old; ?>" />
                                    <input type="hidden" id="ev_sc_id" name="ev_sc_id" value="<?php echo $ev_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="ev_title" id="ev_title"  placeholder="Events Name" value="<?php echo $ev_title; ?>" class="form-control" />
                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                                <textarea required name="ev_text" id="ev_text" class="form-control" placeholder="Events Description"  ><?php echo $ev_text; ?></textarea>

                                            </div>
                                        </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Image</label>
                                            <div class="col-sm-9">
                                                <input <?php if ($id ==0 )  {echo "required"; }  ?> type="file" name="ev_cover_image" id="ev_cover_image"  />
                                                    <?php if ($ev_cover_image_old !='' ) { ?>
                                                <a href="<?php echo $ev_cover_image_old; ?>" target="_blank" ><img src="<?php echo $ev_cover_image_old; ?>" height="120px" width="120px" /></a>
                                                <?php }?>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="ev_status" id="ev_status_a" <?php if ($ev_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="ev_status_a">Active</label> <input type="radio" name="ev_status" id="ev_status_i" value="I" <?php if ($ev_status == 'I') echo 'checked="checked"'; ?> /><label for="ev_status_i">InActive</label>
                                            </div>


                                        </div>

                                <div class="form-group">
                                     <label class="col-sm-3 control-label">More Images</label>
                                     <div class="col-sm-9">
                                         <input type="file" name="event_details_pic_0" />
                                         <input type="button" value="Add More" class="btn btn-success" onclick="add_files();" id="add_more" name="add_more" />
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
                            <?php if ($id !=0 && $errormsg == '' && $result['count'] > 0) { ?>
                            <!-- start code -->

                            <div class="row">
                                <div class="col-xs-12">Other Event Images</div>
                            <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center">Image</th>
                                                <th align="center" class="t_align_center"  width="100px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $class = '';   $srNo = 0;

                                                foreach ($result['res'] as $db_row) {
                                                    $srNo++;
                                                    if ($srNo % 2 == 0) {
                                                        $class = 'even';
                                                    } else {
                                                        $class = 'odd';
                                                    }
                                                    ?>
                                                    <tr class="<?php echo $class; ?>" id="tr_<?php echo $db_row['ei_id']; ?>" >
                                                        <td style="padding-left:10px;"><a href="<?php echo $db_row['ei_image']; ?>" target="_blank" > <img src="<?php echo $db_row['ei_image']; ?>" height="120px" width="120px" /> </td>
                                                        <td>
                                                            <div class="col-md-1" id="dv_process_<?php echo $db_row['ei_id']; ?>" style="display:none;">

                <!-- Loading (remove the following to stop the loading)-->
                <div class="overlay mt20">
                  <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- end loading -->
              <!-- /.box -->
                                                            </div>

                                                            <center><a href="javascript:void(0);" id="a_process_<?php echo $db_row['ei_id']; ?>" class="delete glyphicon glyphicon-remove" onclick="delete_image('delete-events-image',<?php echo $db_row['ei_id']; ?>)"></a></center></td>
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
                            <!-- end code -->
                            <?php } ?>
                </section>
            </div>

            <!-- end of our page-->
<?php include("includes/footer.php"); ?>
        </div>
                    <script type="text/javascript" language="javascript">

                        </script>
    </body>
</html>
