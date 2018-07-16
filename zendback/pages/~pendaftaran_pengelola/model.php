<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$level_condition = "";
	if($_SESSION["uclevelkey"] == 1){
	    $query_str 	= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ORDER BY CLIENT_NAME ASC";
		
	}else{
	    $query_str 	= "SELECT * FROM ".$tpref."clients WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ORDER BY CLIENT_NAME ASC";
		$parent_id = $id_client;
		$level_condition .= "AND ID_CLIENT != '1' AND (ID_CLIENT = '0' OR ID_CLIENT = '".$_SESSION['cidkey']."') "; 
	}
	$qlink 			= $db->query($query_str);
	
	$query_level = $db->query("SELECT * FROM system_master_client_users_level WHERE NAME IS NOT NULL ".@$level_condition." ORDER BY NAME ASC");

	if(!empty($direction) && $direction == "edit" ){
		$qcont			=	$db->query("SELECT * FROM system_users_client WHERE ID_USER='".$no."'");
		$dtedit			=	$db->fetchNextObject($qcont);
		$parent_id		= 	$dtedit->ID_CLIENT; 
		$name			=	$dtedit->USER_NAME;
		$user_name 		= 	$dtedit->USER_USERNAME; 	
		$user_pass 		= 	$dtedit->USER_PASS; 	
		$user_email 	= 	$dtedit->USER_EMAIL; 	
		$photo 			= 	$dtedit->USER_PHOTO; 		
		$level 			= 	$dtedit->ID_CLIENT_USER_LEVEL; 
		$add_info 		= 	$dtedit->ADDITIONAL_INFO; 
		$insert_proses 	= 	$dtedit->INSERT_DATA; 
		$edit_proses 	= 	$dtedit->EDIT_DATA; 
		$delete_proses 	= 	$dtedit->DELETE_DATA; 
		
		$q_doc			=	$db->query("SELECT * FROM ".$tpref."documents WHERE ID_USER='".$no."'");
		$num_doc		= 	$db->numRows($q_doc);
	}
	if(!empty($parent_id)){
		$nama_parent		=	$db->fob("CLIENT_NAME","".$tpref."clients","where ID_CLIENT='".$parent_id."'");
	}
	
	
?>
