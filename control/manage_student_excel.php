<?php

require_once('../vendor/autoload.php'); // Include the autoload file for PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "dbkkw4rfsaxdu5");
//$conn = new mysqli("localhost", "martialart", 'MAaoi%SumiT#7878', "dbkkw4rfsaxdu5");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("All Student Data");

$headers = ["Sr.No", "Gr.No", "Name", "Phone", "B.Time", "Belt", "Course"];

// Set header cell width and height
$cellWidth = 20;
$cellHeight = 30;

// Loop through headers and add them to the Excel sheet
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

$rowNumber = 2; // Start from the second row for data

$sql = "SELECT stu_id, stu_gr_no, CONCAT(stu_first_name,' ',stu_middle_name,' ',stu_last_name), CONCAT('S:',stu_whatsappno,' P:',stu_parent_mobile_no), bt_name, be_name, co_name FROM sm_student LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current = 1) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   LEFT JOIN sm_batch_time ON (stu_batchtime = bt_id ) ORDER BY stu_id ASC";

$data = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($data)) {
    $col = 'A';
    foreach ($row as $column) {
        $sheet->setCellValue($col . $rowNumber, $column);
        $col++;
    }
    $rowNumber++;
}

$writer = new Xlsx($spreadsheet);
$writer->save("studentData.xlsx");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="studentData.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
