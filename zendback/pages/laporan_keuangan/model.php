<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$condition	= "";
if(!empty($tgl_1) && 
	!empty($tgl_2))					{ 
	$tgl_1_new		= $dtime->date2sysdate($tgl_1);
	$tgl_2_new		= $dtime->date2sysdate($tgl_2);
	$condition 		.= " AND TGLUPDATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 	
}

$lcondition		=  "?tgl_1=".$tgl_1_new."&tgl_2=".$tgl_2_new;					

if(!empty($parent_id)){
	$query_str		= "SELECT * FROM ".$tpref."cash_flow WHERE ID_CASH_TYPE='".$parent_id."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." ORDER BY ID_CASH_FLOW DESC";
	
	$q_transaksi	= $db->query($query_str." ".$limit);
	$num_transaksi	= $db->numRows($q_transaksi);
	$link_str		= "";
	
	$q_cash_type	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE = '".$parent_id."'");
	$dt_cash_type	= $db->fetchNextObject($q_cash_type);
	$inout			= $dt_cash_type->NAME;
	$id_writer		= $dt_cash_type->ID_CLIENT;
	if($id_writer != 0){ $open_process = "open"; }
		
}else{
	$qlink 		= $db->query("SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC");
	$total_all	= $db->sum("PAID",$tpref."cash_flow"," WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition."");
}

?>