            <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Book Issue Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered"><table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Book</th>
                  <th>Name</th>
                  <th>Issue Date</th>
                  <th>Valid Date</th>
                  <th>Return Date</th>
                  <th>Status</th>
                </tr>
                <?php 

                // $bi_issue_date_valid = convert_db_to_disp_date(date('Y-m-d', strtotime($cur_date .BIRTHDAY_PERIOD)));
               $b_sql =  'SELECT CONCAT(stu_first_name," ",stu_last_name) as sname , book_title, bi_issue_date, bi_issue_date_valid, bi_return_date, bi_status FROM sm_book_issue_history INNER JOIN sm_book ON (book_id = bi_book_id) INNER JOIN sm_student ON (stu_id = bi_stu_id) WHERE book_id ='.$id;
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
              $bi_issue_date = $bi_issue_date_valid = $bi_return_date = "";
              if (!($b_db_row["bi_issue_date"] == null OR  $b_db_row["bi_issue_date"] == "" ))
                   $bi_issue_date =  convert_db_to_disp_date($b_db_row["bi_issue_date"]);
                   
              if (!($b_db_row["bi_issue_date_valid"] == null OR  $b_db_row["bi_issue_date_valid"] == "" ))
                   $bi_issue_date_valid =  convert_db_to_disp_date($b_db_row["bi_issue_date_valid"]);
                   
              if (!($b_db_row["bi_return_date"] == null OR  $b_db_row["bi_return_date"] == "" ))
                   $bi_return_date =  convert_db_to_disp_date($b_db_row["bi_return_date"]);

                ?>
                <tr>
                  <td><?php echo $sr; ?></td>
                  <td><?php echo $b_db_row["book_title"]; ?></td>
                  <td><?php echo $b_db_row["sname"]; ?></td>
                  <td><?php echo $bi_issue_date; ?></td>
                  <td><?php echo $bi_issue_date_valid; ?></td>
                  <td><?php echo $bi_return_date; ?></td>
                  <td><?php echo $b_db_row["bi_status"]; ?></td>
                <?php  }
        }
        else
        { ?>
          <tr>
                  
                  <td colspan="7" class="text-center" >No Book issue history.</td>
                  
                </tr>
        <?php }
  } ?>
              </table>
            </div>
            <!-- /.box-body -->
            </div>          