<?php defined('mainload') or die('Restricted Access'); ?>
<?php

	if(empty($id_client_form)){
		$id_client_form	 = $_SESSION['cidkey'];
	}
	
	if($_SESSION['uclevelkey'] != 1){
		$condition_1 = 	"WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$_SESSION['cidkey'].",%'";
		$condition_2 =  "AND (ID_CLIENT='".$id_client_form."' ".parent_condition($id_client_form).") ";
	}else{
		
		$condition_1 = "";
		if(empty($direction)){
			$condition_2 = "";
		}else{
			$condition_2 =  "AND (ID_CLIENT='".$id_client_form."' ".parent_condition($id_client_form).") ";
		}
	}
	
	$query_branch 	= $db->query("SELECT * FROM ".$tpref."clients ".$condition_1." ORDER BY CLIENT_NAME");
	$query_str		= "SELECT ID_CLIENT,CLIENT_NAME,CLIENT_STATEMENT,CLIENT_URL,ID_CLIENT_LEVEL FROM ".$tpref."clients WHERE ID_CLIENT_LEVEL = '2' ORDER BY ID_CLIENT ASC";
	$q_user 		= $db->query($query_str." LIMIT 0,20");
	$num_user		= $db->numRows($q_user);
	
?>