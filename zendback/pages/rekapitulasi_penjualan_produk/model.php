<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
	$periode	= "harian";
	if(empty($bln)){ $bln = date('m'); }
	if(empty($thn)){ $thn = date('Y'); }
	$r 				= 0;
	$parameter		= $dtime->daysamonth($bln,$thn);
	$condition3		= "AND MONTH(a.TRANSACTION_DATE)='".$bln."' AND YEAR(a.TRANSACTION_DATE) = '".$thn."'";
	$lcondition		= "&periode=".$periode."&bln=".$bln."&thn=".$thn; 
}
if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
	$r 				= 0;
	$parameter		= 12;
	$condition3		= "AND YEAR(a.TRANSACTION_DATE) = '".$thn."'";
	$lcondition		= "&periode=".$periode."&thn=".$thn; 
}
if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
	$r 				= $thn-1;
	$parameter		= $thn2;
	$condition3		= "AND YEAR(a.TRANSACTION_DATE) BETWEEN '".$thn."' AND '".$thn2."'";
	$lcondition		= "&periode=".$periode."&thn=".$thn."&thn2=".$thn2; 
}
?>