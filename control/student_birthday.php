<?php
include("includes/application_top.php");
include("../includes/class/student.php");
$page_title = "Student Birthday";
$errormsg = get_rdata('errormsg', '');
$is_current_date = get_rdata('is_current_date',0);

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
                <section class="content-header">
                    <h1>
                        Student Birthday
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Student Birthday</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php include("includes/messages.php"); ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th align="center" width="70px">Sr.No</th>
                                                <th align="center" >Gr No.</th>
                                                <th align="center">Name</th>
                                                <th align="center" >Phone</th>
                                                <th align="center" >D.O.B</th>
                                                <th align="center">B. Type</th>
                                                <th align="center">B. Time</th>
                                                <th align="center">Belt</th>
                                                <th align="center">Couse</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if(!empty($is_current_date)) {
                                                    $q ="SELECT * FROM  sm_student   LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   LEFT JOIN sm_batch_time ON (stu_batchtime = bt_id ) WHERE  DATE_ADD(stu_birth_date,INTERVAL YEAR(CURDATE())-YEAR(stu_birth_date) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(stu_birth_date),1,0)YEAR)  = CURDATE();";
                                                }else {
                                             $q = "SELECT *, DATE_ADD(stu_birth_date,INTERVAL YEAR(CURDATE())-YEAR(stu_birth_date) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(stu_birth_date),1,0)
                YEAR)  BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) as date1 FROM  sm_student LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   LEFT JOIN sm_batch_time ON (stu_batchtime = bt_id ) WHERE  DATE_ADD(stu_birth_date,INTERVAL YEAR(CURDATE())-YEAR(stu_birth_date) + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(stu_birth_date),1,0)
                YEAR)  BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY  DATE_FORMAT(stu_birth_date,'%M-%d') ASC;";
                                        }
                                            $r = m_process("get_data", $q);
                                            $class = '';
                                            if (!empty($r["res"])) {
                                                $srNo=0;
                                                $i=0;
                                                foreach ($r['res'] as $db_row) {
                                                    $srNo++;
                                                    if ($i % 2 == 0) {
                                                        $class = 'even';
                                                    } else {
                                                        $class = 'odd';
                                                    }
                                                    $stu_brt_name= $stu_be_name= $stu_co_name = '';
                                                    if ($db_row['brt_name'] == '' && $db_row['be_name'] == '' && $db_row['co_name'] == '')  
                                                    {
                                                        $arr_get_student_course =  get_student_lastet_course($db_row['stu_id']);
                                                        if ($arr_get_student_course["error_message"] == '')
                                                        {
                                                            $stu_brt_name= $arr_get_student_course['brt_name'] ;
                                                            $stu_be_name= $arr_get_student_course['be_name'] ;
                                                            $stu_co_name = $arr_get_student_course['co_name'] ;
                                                        }
                                                    } 
                                                    else
                                                    {
                                                        $stu_brt_name= $db_row['brt_name'] ;
                                                         $stu_be_name= $db_row['be_name'] ;
                                                         if ($db_row["sc_half_course"]==1)
                                                            $stu_be_name= $db_row['be_name_for'] ;
                                                         $stu_co_name = $db_row['co_name'] ;
                                                    }
                                                    



                                                    ?>
                                                    <tr class="<?=$class?>">
                                                        <td><?=$srNo?></td>
                                                        <td><?=$db_row['stu_gr_no']?></td>
                                                        <td>
                                                            <?php echo $db_row['stu_first_name'].' '.$db_row['stu_middle_name'].' '.$db_row['stu_last_name'] ; ?></td>
                                                        <td>
                                                            <?php
                                                                if ($db_row['stu_phone'] !='') { 
                                                                    echo "S: ".$db_row['stu_phone']."</br>";
                                                                }
                                                                if ($db_row['stu_parent_mobile_no'] !='') { 
                                                                    echo "P: ".$db_row['stu_parent_mobile_no']."</br>";
                                                                }
                                                                if ($db_row['stu_whatsappno'] !='') { 
                                                                    echo "W: ".$db_row['stu_whatsappno']."</br>";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td style="padding-left:10px;"><?=DBtoDisp($db_row["stu_birth_date"])?></td>
                                                        <td style="padding-left:10px;"><?=$stu_brt_name?></td>
                                                        <td style="padding-left:10px;"><?=$db_row['bt_name']?></td>
                                                        <td style="padding-left:10px;"><?=$stu_be_name?></td>
                                                        <td style="padding-left:10px;"><?=$stu_co_name?></td>
                                                     
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr class="gradeA"><td class="center" style="text-align:center;" colspan="11">No records found or you have not permission to access these records.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
            <?php include("includes/models.php"); ?>
            <?php include("includes/change_batch_type.php"); ?>
            <?php include("includes/footer.php"); ?>
        </div>
    </body>
</html>