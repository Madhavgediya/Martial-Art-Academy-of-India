<?php
$html = '<div><table border="1" cellpadding="5" cellspacing="0" style="width:100%;font-size:14px;">';
$html .= '<tr>';
$html .= '<th>Sr.No</th>
        <th>Gr No.</th>
        <th>Name</th>';

foreach ($eventAllDates as $date) {
    $html .= '<th>' . DBtoDisp($date, 'd/m/y') . '</th>';
}
$html .=  '</tr>';

for ($i = 1; $db_row = $objData->fetch(); $i++) {
    $srNo++;
    $html .=  '<tr>';
    $html .=  '<td>' . $srNo . '</td>';
    $html .=  '<td>' . $db_row['stu_gr_no'] . '</td>';
    $html .=  '<td>' . $db_row['stu_first_name'] . ' ' . $db_row['stu_middle_name'] . ' ' . $db_row['stu_last_name'] . '</td>';

    foreach ($eventAllDates as $date) {
        $timestamp = strtotime($date);
        $find_key = $timestamp . "-" . $db_row['stu_id'];

        $att_val = 'N/A';
        if (isset($event_attendance_array[$find_key])) {
            if ($event_attendance_array[$find_key] == 1) {
                $att_val = 'P';
            } else if ($event_attendance_array[$find_key] == 2) {
                $att_val = 'A';
            } else {
                $att_val = 'N/A';
            }
        }
        $html .=  '<td>' . $att_val . '</td>';
    }
    $html .=  '</tr>';
}

$html .=  '</table>';
