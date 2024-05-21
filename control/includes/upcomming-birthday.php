<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Today's Birhday</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 10px">#</th>
                <th>GR. No.</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Birthdate</th>
                <th>Note</th>
            </tr>
            <?php 
                $time=strtotime($cur_date);
                $month=date("m",$time);
                $day=date("d",$time);
                $month_next = ($month+1);

                // $bi_issue_date_valid = convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BIRTHDAY_PERIOD)));
               $b_sql = "SELECT stu_birth_date, stu_status, stu_gr_no,stu_first_name,stu_last_name,stu_phone,stu_parent_mobile_no,stu_whatsappno FROM sm_student WHERE  MONTH(stu_birth_date) = ".$month." AND DAY(stu_birth_date) = ".$day."  AND stu_br_id = ".$tmp_admin_id." ORDER BY DAY(stu_birth_date) ASC";
              //  echo $b_sql;
              //  exit(0);
                $b_result = m_process("get_data", $b_sql);

    if ($b_result['errormsg'] != '') 
    {
        echo $b_result['errormsg'];
    } else 
    {
        if ($b_result['count'] > 0) 
        {
          $sr=0;
            foreach($b_result['res'] as $b_db_row )
            {
              $sr++;
              $stu_contact_no = "";
              if ($b_db_row['stu_phone'] !='') { $stu_contact_no .="S: ".$b_db_row['stu_phone']."</br>";}
              if ($b_db_row['stu_parent_mobile_no'] !='') { $stu_contact_no .="P: ".$b_db_row['stu_parent_mobile_no']."</br>";}
              if ($b_db_row['stu_whatsappno'] !='') { $stu_contact_no .="W: ".$b_db_row['stu_whatsappno']."</br>";}

                ?>
            <tr>
                <td><?php echo $sr; ?></td>
                <td><?php echo $b_db_row["stu_gr_no"]; ?></td>
                <td><?php echo $b_db_row["stu_first_name"]." ".$b_db_row["stu_last_name"]; ?></td>
                <td><?php echo $stu_contact_no; ?></td>
                <td><?php echo convert_db_to_disp_date($b_db_row["stu_birth_date"]); ?></td>
                <td style="color:red"><?php if ($b_db_row["stu_status"] == 'I' ) echo "Student is not currently active."; ?></td>
                
                <?php  }
        }
        else
        { ?>
            <tr>

                <td colspan="6" class="text-center">No records found</td>

            </tr>
            <?php }
  } ?>
        </table>
    </div>
    <!-- /.box-body -->
</div>

</div>