<?php

require('fpdf/fpdf.php');
$conn = new mysqli("localhost", "root", "", "dbkkw4rfsaxdu5");

$pdf = new FPDF('P', 'mm', array(500, 500));
$pdf->Addpage();
$pdf->SetFont("Arial", "B", 10);
$pdf->SetTitle("All Student Data");

$headers = ["Sr.No","Gr.No", "Name", "Phone","B.Time", "Belt","Course"];

// Set header cell width and height
$cellWidth = 66;
$cellHeight = 12;

// Loop through headers and add them to the PDF
foreach ($headers as $header) {
    $pdf->Cell($cellWidth, $cellHeight, $header, 1, 0, 'C');
}

$pdf->Ln(); // Move to the next line after headers

$sql = "SELECT stu_id,stu_gr_no,CONCAT(stu_first_name,' ',stu_middle_name,' ',stu_last_name),CONCAT('S:',stu_whatsappno,' P:',stu_parent_mobile_no),bt_name,be_name,co_name FROM sm_student LEFT JOIN sm_student_course ON (sc_stu_id = stu_id AND sc_is_current =1) LEFT JOIN sm_belt ON (sc_be_id = be_id ) LEFT JOIN sm_branch_type ON (sc_brt_id = brt_id ) LEFT JOIN sm_course ON (sc_co_id = co_id )   LEFT JOIN sm_batch_time ON (stu_batchtime = bt_id ) order by stu_id ASC";
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

$pdf->Output("studentData.pdf", "D");

?>
