<?php defined("mainload") or die("Restricted Access"); ?>
<?php
function excel($namafile){
	header("Content-type: application/vnd.ms-exel");
	header("Content-Disposition: attachment; filename=$namafile" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}

function csv($filename){
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header("Content-disposition: filename=".$filename.".csv");
}

function pdf($converterpath,$html){
	include($converterpath."/mpdf.php");
	$mpdf=new mPDF(); 
	$mpdf->WriteHTML($html);
	$mpdf->Output();
	exit;
}

?>