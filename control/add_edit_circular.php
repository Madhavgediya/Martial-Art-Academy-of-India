<?php
include("includes/application_top.php");
include("../includes/class/circular.php");
include("../includes/class/circular_details.php");

// Set the caption of button

$id= get_rdata("id",0);
$act = get_rdata("act");
$ci_id= get_rdata('ci_id');
$ci_title= get_rdata('ci_title');
$ci_text= get_rdata('ci_text');
$ci_cover_image= get_rdata('ci_cover_image');
$ci_sc_id= get_rdata('ci_sc_id');
$ci_status= get_rdata('ci_status','A');
$ci_cover_image_old= get_rdata('ci_cover_image_old');
$ci_create_date= $cur_date;
$ci_create_by_id= $user_id;
$ci_update_date= $cur_date;
$ci_update_by_id= $user_id;
$file_counter= InputPro('file_counter');


$caption = "Add Circular";
$btn_caption = "Add Circular";
if ($id != 0) {
    $caption = "Edit Circular";
    $btn_caption = "Edit Circular";
}
// Set Page Title
$page_title = $caption;
$successmsg = get_rdata('successmsg', '');
$errormsg = get_rdata('errormsg', '');

// Get the data from form.
$per_page = get_rdata('per_page', PER_PAGE);



// Get the data from database
if ($act == '' && $id != 0) {
    $sm_circular = new circular();
    $sm_circular->data["*"] = "";
    $sm_circular->action = 'get';
    $sm_circular->process_id = $id;
    $result = $sm_circular->process();
    if ($result['status'] == 'failure') {
        $errormsg = $result['errormsg'];
    } else {
        if ($result['count'] > 0) {
            foreach ($result['res'] as $db_row) {
                  $ci_title = $db_row['ci_title'];
                  $ci_text = $db_row['ci_text'];
                  $ci_cover_image = $db_row['ci_cover_image'];
                  $ci_cover_image_old = $db_row['ci_cover_image'];
                  $ci_sc_id = $db_row['ci_sc_id'];
                  $ci_status = $db_row['ci_status'];
                  $ci_create_date = $db_row['ci_create_date'];
                  $ci_create_by_id = $db_row['ci_create_by_id'];
                  $ci_update_date = $db_row['ci_update_date'];
                  $ci_update_by_id = $db_row['ci_update_by_id'];
            }
        }
    }
}

// Add user entry
if ($act == 'add') {
    

    $not_value = " AND ci_sc_id = ".$ci_sc_id;
    $arr_duplicate_school_name = found_duplicate('sm_circular', 'ci_title', $ci_title,$not_value);
    if ($arr_duplicate_school_name['error_message'] != '') {
        $errormsg = $arr_duplicate_school_name['error_message'];
    } else if ($arr_duplicate_school_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for circular name ';
    }

    if ($errormsg == '') {  
    if($_FILES['ci_cover_image']['error']==0)  /// Image 
    { 

            $file_array = explode(".",$_FILES['ci_cover_image']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_document_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_document_type) ;
            }
            else
            {
                $ci_cover_image = "circular_".strtolower(randomPrefix(5))."_".clean_name($_FILES['ci_cover_image']['name']);				
                $DestPath=CIRCULAR_IMAGE.$ci_cover_image;
                move_uploaded_file($_FILES['ci_cover_image']['tmp_name'], $DestPath);
            }    
    }
    else
    {
            $errormsg = 'Please upload circular image';			
    }
    }           
    if ($errormsg == '') {
        $sm_circular = new circular();
       $sm_circular->data["ci_title"]=$ci_title;
        $sm_circular->data["ci_text"]=$ci_text;
        $sm_circular->data["ci_cover_image"]=SITE_URL.CIRCULAR_IMAGE_URL.$ci_cover_image;
        $sm_circular->data["ci_sc_id"]=$ci_sc_id;
        $sm_circular->data["ci_status"]=$ci_status;
        $sm_circular->data["ci_create_date"]=$ci_create_date;
        $sm_circular->data["ci_create_by_id"]=$ci_create_by_id;
        $sm_circular->data["ci_update_date"]=$ci_update_date;
        $sm_circular->data["ci_update_by_id"]=$ci_update_by_id;
        $sm_circular->action = 'insert';
        $result = $sm_circular->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            // adding circular images to vision
            $cid_ci_id = $result['id'];
            $iimg =0;
            while($iimg <= $file_counter)
            {
                 if($_FILES['circular_details_pic_'.$iimg]['error']==0)  /// Image 
                { 
                    $file_array = explode(".",$_FILES['circular_details_pic_'.$iimg]['name']);
                    $file_ext = $file_array [count($file_array)-1];
                    $file_ext = strtolower($file_ext);

                    if (! (in_array($file_ext,$arr_allow_document_type) == 1))
                    {
                            $errormsg .="Error for file ".$_FILES['circular_details_pic_'.$iimg]['name']. "allowed file types are ". implode(",",$arr_allow_document_type) ;
                    }
                    else
                    {
                        $cid_image = "circular_".strtolower(randomPrefix(5))."_".clean_name($_FILES['circular_details_pic_'.$iimg]['name']);				
                        $DestPath=CIRCULAR_IMAGE.$cid_image;
                        move_uploaded_file($_FILES['circular_details_pic_'.$iimg]['tmp_name'], $DestPath);
                        //echo $cid_image; exit;
                        // adding files in db
                        // $sm_circular_details->data["cid_id"]=$cid_id;
                        $sm_circular_details = new circular_details();
                        $sm_circular_details->data["cid_title"]=$ci_title;
                        $sm_circular_details->data["cid_image"]=SITE_URL.CIRCULAR_IMAGE_URL.$cid_image;
                        $sm_circular_details->data["cid_image_alt"]=$ci_title;
                        $sm_circular_details->data["cid_ci_id"]=$cid_ci_id;
                        $sm_circular_details->data["cid_status"]='A';
                        $sm_circular_details->data["cid_create_date"]=$ci_create_date;
                        $sm_circular_details->data["cid_create_by_id"]=$ci_create_by_id;
                        $sm_circular_details->data["cid_update_date"]=$ci_create_date;
                        $sm_circular_details->data["cid_update_by_id"]=$ci_create_by_id;
                        $sm_circular_details->action = 'insert';
                        $result = $sm_circular_details->process();
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
                
                if ($ci_status == 'A')
                {
                    $arr_data = array();
                    $arr_data["not_message"] = "circular:".$ci_title;
                    $arr_data["not_sc_id"] = $ci_sc_id;
                    $arr_data["not_create_date"] = $cur_date;
                    $arr_data["not_create_by_id"] = $user_id;
                    $arr_data["not_update_date"] = $cur_date;
                    $arr_data["not_update_by_id"] = $user_id;
                      $arr_data["not_goToScreen"]="circular";
                    
                    $arr_data["gcmp_message"] =  "circular:".$ci_title;
                    $arr_data["gcmp_title"] = "circular:".$ci_title;
                    $arr_data["gcmp_subtitle"] =  "circular:".$ci_title;
                    $arr_data["gcmp_tickerText"] = "circular:".$ci_title;
                    $arr_data["gcmp_create_by"]=$user_id;
                    $arr_data["gcmp_create_date"]=$cur_date;
                    $arr_data["gcmp_gcm_sc_id"]=$ci_sc_id;
                     $arr_data["gcmp_goToScreen"]="circular";
            
                    add_notes($arr_data); 
                   
                }
                
                header('Location:manage_circular.php?msg=2&page=1&per_page=' . $per_page);
                exit(0);
            }
        }
    }
}

// Update user entry
if ($act == 'update') {
  $not_value = " AND ci_sc_id = ".$ci_sc_id." AND ci_id != " . $id;
    $arr_duplicate_circular_name = found_duplicate('sm_circular', 'ci_title', $ci_title,$not_value );
    if ($arr_duplicate_circular_name['error_message'] != '') {
        $errormsg = $arr_duplicate_circular_name['error_message'];
    } else if ($arr_duplicate_circular_name['duplicate'] == true) {
        $errormsg = 'Duplicate entry for circular name ';
    }

   if ($errormsg == '') {  
    if($_FILES['ci_cover_image']['error']==0)  /// Image 
    { 

            $file_array = explode(".",$_FILES['ci_cover_image']['name']);
            $file_ext = $file_array [count($file_array)-1];
            $file_ext = strtolower($file_ext);

            if (! (in_array($file_ext,$arr_allow_document_type) == 1))
            {
                    $errormsg = "allowed file types are ". implode(",",$arr_allow_document_type) ;
            }
            else
            {
                $ci_cover_image = "circular_".strtolower(randomPrefix(5))."_".clean_name($_FILES['ci_cover_image']['name']);							
                $DestPath=CIRCULAR_IMAGE.$ci_cover_image;
                move_uploaded_file($_FILES['ci_cover_image']['tmp_name'], $DestPath);
                $ci_cover_image = SITE_URL.CIRCULAR_IMAGE_URL.$ci_cover_image;
            }    
    }
    else
    {
           $ci_cover_image = $ci_cover_image_old;			
    }
    
   }
    if ($errormsg == '') {
        $sm_circular = new circular();
        $sm_circular->data["ci_title"]=$ci_title;
        $sm_circular->data["ci_text"]=$ci_text;
        $sm_circular->data["ci_cover_image"]=$ci_cover_image;
        $sm_circular->data["ci_sc_id"]=$ci_sc_id;
        $sm_circular->data["ci_status"]=$ci_status;
        $sm_circular->data["ci_create_date"]=$ci_create_date;
        $sm_circular->data["ci_create_by_id"]=$ci_create_by_id;
        $sm_circular->data["ci_update_date"]=$ci_update_date;
        $sm_circular->data["ci_update_by_id"]=$ci_update_by_id;
        $sm_circular->action = 'update';
        $sm_circular->process_id = $id;
        $result = $sm_circular->process();
        if ($result['status'] == 'failure') {
            $errormsg = $result['errormsg'];
        } else {
            if ($ci_cover_image !='' && $ci_cover_image_old != '' && ($ci_cover_image != $ci_cover_image_old))
            {
                $ci_cover_image_old = str_replace(SITE_URL, '', $ci_cover_image_old);
                $ci_cover_image_old = "..".$ci_cover_image_old;
               // echo $ci_cover_image_old;
               // exit;
                unlink($ci_cover_image_old);
            }
            $cid_ci_id = $result['id'];
            $iimg =0;
            while($iimg <= $file_counter)
            {
                 if($_FILES['circular_details_pic_'.$iimg]['error']==0)  /// Image 
                { 
                    $file_array = explode(".",$_FILES['circular_details_pic_'.$iimg]['name']);
                    $file_ext = $file_array [count($file_array)-1];
                    $file_ext = strtolower($file_ext);

                    if (! (in_array($file_ext,$arr_allow_document_type) == 1))
                    {
                            $errormsg .="Error for file ".$_FILES['circular_details_pic_'.$iimg]['name']. "allowed file types are ". implode(",",$arr_allow_document_type) ;
                    }
                    else
                    {
                        $cid_image = "circular_".strtolower(randomPrefix(5))."_".clean_name($_FILES['circular_details_pic_'.$iimg]['name']);				
                        $DestPath=CIRCULAR_IMAGE.$cid_image;
                        move_uploaded_file($_FILES['circular_details_pic_'.$iimg]['tmp_name'], $DestPath);
                        $sm_circular_details = new circular_details();
                        $sm_circular_details->data["cid_title"]=$ci_title;
                        $sm_circular_details->data["cid_image"]=SITE_URL.CIRCULAR_IMAGE_URL.$cid_image;
                        $sm_circular_details->data["cid_image_alt"]=$ci_title;
                        $sm_circular_details->data["cid_ci_id"]=$id;
                        $sm_circular_details->data["cid_status"]='A';
                        $sm_circular_details->data["cid_create_date"]=$ci_create_date;
                        $sm_circular_details->data["cid_create_by_id"]=$ci_create_by_id;
                        $sm_circular_details->data["cid_update_date"]=$ci_create_date;
                        $sm_circular_details->data["cid_update_by_id"]=$ci_create_by_id;
                        $sm_circular_details->action = 'insert';
                        $result = $sm_circular_details->process();
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
            header('Location:manage_circular.php?msg=3&page=1&per_page=' . $per_page);
            exit(0); 
            
            }
        }
    }
}

if ($id !=0)
{
   $q = "SELECT cid.cid_image ,cid.cid_id  FROM  sm_circular_details cid INNER JOIN sm_circular ci ON (cid.cid_ci_id=ci.ci_id) INNER JOIN sm_school_master sm ON (ci.ci_sc_id=sm.sc_id)
WHERE ci.ci_id= ".$id;
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
$ci_sc_id = session_get('admin_sc_id');
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
                                    <input type="hidden" id="file_counter" name="file_counter" />
                                    
                                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
                                    
                                    <input type="hidden" id="ci_cover_image_old" name="ci_cover_image_old" value="<?php echo $ci_cover_image_old; ?>" />
                                    <input type="hidden" id="ci_sc_id" name="ci_sc_id" value="<?php echo $ci_sc_id; ?>" />
                                    <div class="box-body">
                                        <div class=" col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input required type="text" name="ci_title" id="ci_title"  placeholder="Circular Name" value="<?php echo $ci_title; ?>" class="form-control" />
                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                                <textarea required name="ci_text" id="ci_text" class="form-control"  placeholder="Circular Description" ><?php echo $ci_text; ?></textarea>
                                                
                                            </div>
                                        </div>
                                            
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Document</label>
                                            <div class="col-sm-9">
                                                <input <?php if ($id ==0 )  {echo "required"; }  ?> type="file" name="ci_cover_image" id="ci_cover_image"  /> 
                                                    <?php if ($ci_cover_image_old !='' ) { ?>
                                                <a href="<?php echo $ci_cover_image_old; ?>" target="_blank" >View/Download Document</a>
                                                <?php }?>
                                                [Allow File Types are <?php echo implode(", ",$arr_allow_document_type); ?>]
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Status</label>

                                            <div class="col-sm-9">
                                                <input type="radio" name="ci_status" id="ci_status_a" <?php if ($ci_status == 'A') {  echo 'checked="checked"'; }; ?>  value="A" /><label for="ci_status_a">Active</label> <input type="radio" name="ci_status" id="ci_status_i" value="I" <?php if ($ci_status == 'I') echo 'checked="checked"'; ?> /><label for="ci_status_i">InActive</label>
                                            </div>

                                            <input type="file" name="circular_details_pic_0" style="display:none" />
                                        </div>

<!--                                <div class="form-group">       
                                     <label class="col-sm-3 control-label">More Images</label>
                                     <div class="col-sm-9">
                                         <input type="file" name="circular_details_pic_0" />
                                         <input type="button" value="Add More" class="btn btn-success" onclick="add_files();" id="add_more" name="add_more" />
                                           </div>
                                         
                                     </div>-->
                                        <!-- /.box-body -->
                                        
                                </div>
                            </div><!-- /.box -->
                            <div class="box-footer">
                                  <?php if ($id==0) { ?>           <input type="reset" value="Rest" class="btn btn-default" id="btnReset" name="btnReset" /> <?php } ?>
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
                                <div class="col-xs-12">Other Images</div>
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
                                                    <tr class="<?php echo $class; ?>" id="tr_<?php echo $db_row['cid_id']; ?>" >
                                                        <td style="padding-left:10px;"><a href="<?php echo $db_row['cid_image']; ?>" target="_blank" > <img src="<?php echo $db_row['cid_image']; ?>" height="120px" width="120px" /> </td>
                                                        <td>
                                                            <div class="col-md-1" id="dv_process_<?php echo $db_row['cid_id']; ?>" style="display:none;">
              
                <!-- Loading (remove the following to stop the loading)-->
                <div class="overlay mt20">
                  <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- end loading -->
              <!-- /.box -->
                                                            </div>
                                                            
                                                            <center><a href="javascript:void(0);" id="a_process_<?php echo $db_row['cid_id']; ?>" class="delete glyphicon glyphicon-remove" onclick="delete_image('delete-circular-image',<?php echo $db_row['cid_id']; ?>)"></a></center></td>
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