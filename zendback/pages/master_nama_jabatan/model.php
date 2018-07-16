<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition = "";
	if($_SESSION['uclevelkey'] != '1'){
		$condition = " AND (ID_CLIENT ='".$id_client."' OR ID_CLIENT ='0')"; 
	}
	$str_query 			= "SELECT * FROM system_master_client_users_level WHERE ID_CLIENT_USER_LEVEL IS NOT NULL ".$condition."";
	$link_str			= $lparam;
	$q_level 			= $db->query($str_query ." ".$limit);
	$num_level			= $db->numRows($q_level);
	
	if(!empty($direction) && $direction == "edit"){
		$q_level_edit 	= $db->query("SELECT * FROM system_master_client_users_level WHERE ID_CLIENT_USER_LEVEL ='".$no."'");
		$dt_level_edit	= $db->fetchNextObject($q_level_edit);
		$nama 			= $dt_level_edit->NAME; 		
	}
?>