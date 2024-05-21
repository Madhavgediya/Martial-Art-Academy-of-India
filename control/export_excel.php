<?php
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=exportfile.xlsx");
header("Pragma: no-cache");
header("Expires: 0");
$header = "name\temail\tbirthdate\n";
$data = "mayur\tit.mayur@yahoo.com\t04-12-1985\n";
$data .= "jayesh\tit.mayur+1@yahoo.com\t04-12-1985\n";
// output data
echo $header."\n".$data;
?>