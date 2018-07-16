<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
	$periode	= "harian";
	if(empty($bln)){ $bln = date('m'); }
	if(empty($thn)){ $thn = date('Y'); }
	$r 				= 0;
	$parameter		= $dtime->daysamonth($bln,$thn);
	$condition3		= "AND MONTH(a.TGLUPDATE)='".$bln."' AND YEAR(a.TGLUPDATE) = '".$thn."'";
	$link_print		= "&periode=".$periode."&bln=".$bln."&thn=".$thn; 
}
if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
	$r 				= 0;
	$parameter		= 12;
	$condition3		= "AND YEAR(a.TGLUPDATE) = '".$thn."'";
	$link_print		= "&periode=".$periode."&thn=".$thn; 
}
if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
	$r 				= $thn;
	$parameter		= $thn2;
	$condition3		= "AND YEAR(a.TGLUPDATE) BETWEEN '".$thn."' AND '".$thn2."'";
	$link_print		= "&periode=".$periode."&thn=".$thn."&thn2=".$thn2; 
}

if(empty($alur)){ $alur = 1; }
if(!empty($parent_id)){
	$inout			= 	$db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE = '".$parent_id."'");
}else{	
	$query_str		= "SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC";
	$q_transaksi 	= $db->query($query_str);
}

?>
