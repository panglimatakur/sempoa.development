<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition		= "";
	$condition_com	= "";
	if($_SESSION['uclevelkey'] != '1'){
		$condition_com 	= " AND BY_ID_PURPLE	= '".$_SESSION['cidkey']."'"; 
		$condition 		= " AND BY_ID_PURPLE	= '".$_SESSION['cidkey']."'"; 
	}
	$query_com 		= $db->query("SELECT * FROM ".$tpref."communities WHERE ID_COMMUNITY IS NOT NULL ".$condition_com." ORDER BY ID_COMMUNITY ASC");
	if(!empty($id_com)){ $condition .= " AND ID_COMMUNITY = '".$id_com."'";	}
	
	$str_list_comm	= "SELECT * FROM ".$tpref."communities WHERE ID_COMMUNITY IS NOT NULL ".$condition." ORDER BY ID_COMMUNITY ASC";
	//echo $str_list_comm;
	$num_community	= $db->recount($str_list_comm);
$q_list_comm	= $db->query($str_list_comm." LIMIT 0,3");
?>