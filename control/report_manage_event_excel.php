<?php

require_once('../vendor/autoload.php'); // Include the autoload file for PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "dbkkw4rfsaxdu5");
//$conn = new mysqli("localhost", "martialart", 'MAaoi%SumiT#7878', "dbkkw4rfsaxdu5");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("All Student Data");

$headers =  ["Sr.No", "Event Name", "Start Date","End Date","Exam Fee"];

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

$sql = "SELECT ev_id,ev_name,ev_date,ev_end_date,ev_eu_exam_fee FROM sm_event order by ev_id ASC";

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
$writer->save("eventData.xls");

header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment;filename="eventData.xls"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
