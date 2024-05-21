<?php
    function generate_pager($arr_param)
	{
		$arr_result = array();
		$page_size = $arr_param["page_size"];
		$page_no = $arr_param["page_no"];

        global $dbh;

        // BUild total record query and Query with LIMIT
        $totalRecordQry = "SELECT 1 FROM ".$arr_param["table"]." WHERE ".$arr_param["cond"];
        $limitRecordQry = "SELECT ".$arr_param["lsQry"]." FROM ".$arr_param["table"]." WHERE ".$arr_param["cond"];

        $objPage = $dbh->prepare($totalRecordQry);
		$objPage->execute();
        if (! $objPage) {
			if ($objPage->debug)
				echo "SQL query failed. Check your query.<br /><br />Error Returned: " . mysql_error();
			return false;
		}
		$total_record_count = $objPage->rowCount();

        $custom_query_where =' LIMIT '. (($arr_param["page_no"]-1)* $arr_param["page_size"]) .' , ' . $arr_param["page_size"] ;
		$custom_query = $limitRecordQry .  $custom_query_where;
        $objPageLimit = $dbh->prepare($custom_query);
		$objPageLimit->execute();
        if (! $objPageLimit) {
			if ($objPageLimit->debug)
				echo "SQL query failed. Check your query.<br /><br />Error Returned: " . mysql_error();
			return false;
		}
        $no_of_count_page = $objPageLimit->rowCount();
		$noOfPages=0;

        if (($total_record_count  %  $page_size) ==0)
		$noOfPages = (int)($total_record_count  /  $page_size);

		if (($total_record_count  %  $page_size) !=0)
			$noOfPages = (int)($total_record_count  /  $page_size) +1;

		$end_record_no = 0;

		if (( ($page_no-1)*$page_size+1 )+($page_size-1) > $total_record_count)
		{
			if ($page_no == 1 && ($total_record_count >=$no_of_count_page ))
				$end_record_no =  $no_of_count_page;
			else
			{
				if ($noOfPages == $page_no)
					$end_record_no = (($page_no-1)*$page_size+$no_of_count_page);
				else
					$end_record_no = (($page_no-1)*$page_size+1);
			}
        }
		else{
            $end_record_no = (($page_no-1)*$page_size+1)+($page_size-1);
		}

        $displaying = '<span class="pager_displaying">Displaying ';
		$displaying .= ($page_no-1)*$page_size+1;
		$displaying .= ' - ';
		$displaying .= $end_record_no;
		$displaying .= ' of ';
		$displaying .= $total_record_count.'</span>';
		$next = $page_no != $noOfPages?($page_no+1):$noOfPages;
		$previous = $page_no != 1?($page_no-1):1;

		$arr_result['displaying_text'] = $displaying;

		$arr_result['first_text'] = "";
		$arr_result['last_text'] = "";
		$arr_result['previous_text'] = $previous;
		$arr_result['next_text'] =$next;

		$StartNo = $page_no - 2;
		$EndNo = $page_no + 2;

		if($StartNo < 1) { $StartNo = 1; $EndNo = $EndNo + 2; }
		if($EndNo > $noOfPages) { $EndNo = $noOfPages; $StartNo = $StartNo - 2; if($StartNo < 1) { $StartNo = 1; }  }

        echo '<input type="hidden" name="order_by" id="order_by" value="'.$arr_param["order_by"].'">';
        echo '<input type="hidden" name="order" id="order" value="'.$arr_param["order"].'">';
        echo '<input type="hidden" name="page_no" id="page_no" value="'.$arr_param["page_no"].'">';
        echo '<div class="pager">';
        echo	$displaying;
        echo '<a onclick="Go_toPage(1);" href="javascript:void(0);" class="pager_first">First</a>';
		echo '<a title="Previous Page" onclick="Go_toPage('.$previous.');"  href="javascript:void(0);" class="pager_previous">Previous</a>';
		for($i = $StartNo; $i <= $EndNo; $i++)
		{
			if($i == $page_no) {
				echo '<span class="pager_active">'.$i.'</span>';
			} else {
				echo '<a href="#" class="pager_go" onclick="Go_toPage('.$i.')">'.$i.'</a>';
			}
		}
		echo '<a class="pager_next" title="Next Page" onclick="Go_toPage('.$next.');" href="javascript:void(0);">Next</a> ';
		echo '<a class="pager_last" title="Last Page" href="javascript:void(0);" onclick="Go_toPage('.$noOfPages.');">Last</a>';

		echo ' Page Size: <select class="pagesize" id="page_size" name="page_size" onchange="Go_Pagesize();" >
              <option value="5" '.(($page_size == 5)?'selected="selected"':'').'>5</option>
              <option value="10" '.(($page_size == 10)?'selected="selected"':'').'>10</option>
              <option value="20" '.(($page_size == 20)?'selected="selected"':'').'>20</option>
              <option value="50" '.(($page_size == 50)?'selected="selected"':'').'>50</option>
              <option value="100" '.(($page_size == 100)?'selected="selected"':'').'>100</option>
            </select>';
        echo '</div>';
    }
?>