<?php

require('fpdf/fpdf.php');
$conn = new mysqli("localhost", "root", "", "dbkkw4rfsaxdu5");
//$conn = new mysqli("localhost", "martialart", 'MAaoi%SumiT#7878', "dbkkw4rfsaxdu5");
$pdf = new FPDF('P', 'mm', array(550, 550));
$pdf->Addpage();
$pdf->SetFont("Arial", "B", 10);
$pdf->SetTitle("All Event Data");

$headers = ["Sr.No", "Event Name", "Start Date","End Date","Exam Fee"];

// Set header cell width and height
$cellWidth = 110;
$cellHeight = 8;

// Loop through headers and add them to the PDF
foreach ($headers as $header) {
    $pdf->Cell($cellWidth, $cellHeight, $header, 1, 0, 'C');
}

$pdf->Ln(); // Move to the next line after headers

$sql = "SELECT ev_id,ev_name,ev_date,ev_end_date,ev_eu_exam_fee FROM sm_event order by ev_id ASC";
$data = mysqli_query($conn, $sql);
//$serialNumber=1;
while ($row = mysqli_fetch_assoc($data)) {
    $pdf->Ln();
   // $pdf->Cell($cellWidth, $cellHeight, $serialNumber, 1, 0, 'C');
    foreach ($row as $column) {
        $pdf->Cell($cellWidth, $cellHeight, $column, 1, 0, 'C');
       // $serialNumber++;
    }
}

$pdf->Output("eventData.pdf", "D");

?>
