<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($direction) && $direction == "edit"){
		$q_satuan_edit = $db->query("SELECT * FROM ".$tpref."products_units WHERE ID_PRODUCT_UNIT='".$no."' ");
		$dt_satuan_edit= $db->fetchNextObject($q_satuan_edit);
		
		$nama 			= $dt_satuan_edit->NAME; 		
	}
	$condition = "";
	if(!empty($direction) && $direction == "show"){
		if(!empty($_REQUEST['nama']))		{ $condition 	.= " AND NAME LIKE '%".$nama."%'"; 	}
		$form_name = "report";
	}else{
		$form_name = "proses";
	}
	$q_satuan 		= $db->query("SELECT * FROM ".$tpref."products_units WHERE ID_PRODUCT_UNIT IS NOT NULL ".$condition."");
	$num_satuan		= $db->numRows($q_satuan);
?>