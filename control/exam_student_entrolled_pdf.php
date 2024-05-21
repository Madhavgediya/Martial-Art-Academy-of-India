<?php
$html = '<div><table border="1" cellpadding="5" cellspacing="0" style="width:100%;font-size:14px;">';
$html .= '<tr>';
$html .= '<th>Gr No.</th>
        <th>Name</th>
        <th>B. Type</th>
        <th>Belt</th>
        <th>Course</th>';
if ($enroll == 1) {
    $html .= '<th>Join D.</th>';
}
if ($addresult == 1) {
    $html .= '<th>T. Marks.</th>';
}
if ($pay_fee == 1) {
    $html .= '<th>Fee</th>';
}
if ($pay_fee == 1 || $enroll == 1) {
    $html .= '<th>Paid?</th>';
}
if ($addresult == 1) {
    $html .= '<th>R. Marks</th>
    <th>R. Status</th>
    <th>Finalized</th>';
}
if ($addcertificate == 1) {
    $html .= '<th>Result</th>';
}
if($pay_fee != 1) {
    $html .= '<th>Certificate</th>
    <th>Belt</th>';
}
if($pay_fee == 1) {
    $html .= '<th>Date</th>';
}

$html .=  '</tr>';
if(!empty($objData)) {
    for ($i = 1; $db_row = $objData->fetch(); $i++) {
        $exs_result_status = "";
        if ($db_row['exs_result_status'] == "F") {
            $exs_result_status = "Fail";
        } else if ($db_row['exs_result_status'] == "P") {
            $exs_result_status = "Pass";
        } else if ($db_row['exs_result_status'] == "A") {
            $exs_result_status = "AB";
        }

        $exs_paid = ($db_row['exs_paid'] == 0) ? "No" : "Yes";
        if ($enroll == 1 && isset($db_row['exs_already_paid']) && $db_row['exs_already_paid'] == 'Y') {
            $exs_paid = 'NA';
        }

        $html .=  '<tr>';
        $html .=  '<td>' . $db_row['stu_gr_no'] . '</td>';
        $html .=  '<td>' . $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name'] . '</td>';
        $html .=  '<td>' . $db_row['brt_name'] . '</td>';
        $html .=  '<td>' . $db_row['be_name_for'] . '</td>';
        $html .=  '<td>' . $db_row['co_name'] . '</td>';
        if ($enroll == 1) {
            $html .=  '<td>' . DBtoDisp($db_row['sc_joined_date']) . '</td>';
        }
        if ($addresult == 1) {
            $html .=  '<td>' . $db_row['eca_total_marks'] . '</td>';
        }
        if ($pay_fee == 1) {
            $html .=  '<td>' . $db_row['exs_fee'] . '</td>';
        }
        if (isset($addresult) && $addresult == 1) {
            $html .=  '<td>' . $db_row['exs_result_marks'] . '</td>';
            $html .=  '<td>' . $exs_result_status . '</td>';
            $html .=  '<td>' . $db_row['exs_finalized'] . '</td>';
        }
        if ($pay_fee == 1 || $enroll == 1) {
            $html .=  '<td>' . $exs_paid . '</td>';
        }
        if ($addcertificate == 1) {
            $html .=  '<td>' . $exs_result_status . '</td>';
        }
        if ($pay_fee != 1) {
            $html .=  '<td></td>';
            $html .=  '<td></td>';
        }

        if($pay_fee == 1) {
            $html .= '<td>'.($db_row['exs_paid'] == 0 ? '' : $db_row['fee_date']).'</td>';
        }

        $html .=  '</tr>';
    }
}
$html .=  '</table>';
