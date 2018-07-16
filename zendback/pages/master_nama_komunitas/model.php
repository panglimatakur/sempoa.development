<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition = "";
	if($_SESSION['admin_only'] == 'false'){
		$condition = " AND BY_ID_PURPLE='".$_SESSION['cidkey']."'"; 
	}
	if(!empty($direction) && $direction == "show"){
		if(!empty($_REQUEST['nama']))		{ $condition 	.= " AND NAME 			= '".$nama."'"; 	}
		$class_report = "active";
	}else{
		$class_proses = "active";
	}
	$str_query 			= "SELECT * FROM ".$tpref."communities WHERE ID_COMMUNITY IS NOT NULL ".$condition."";
	$link_str			= $lparam;
	$q_community 		= $db->query($str_query ." ".$limit);
	$num_community		= $db->numRows($q_community);
	
	if(!empty($direction) && $direction == "edit"){
		$q_comm_edit = $db->query("SELECT * FROM ".$tpref."communities WHERE ID_COMMUNITY='".$no."'");
		$dt_comm_edit= $db->fetchNextObject($q_comm_edit);
		$nama 			= $dt_comm_edit->NAME; 
		$ch_ref 		= $dt_comm_edit->STATUS_ACTIVE; 			
	}
?>