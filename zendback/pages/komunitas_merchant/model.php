<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition 		= "";
	$condition_com	= "";
	if($_SESSION['admin_only'] == 'false'){
		$condition 		= " AND BY_ID_PURPLE='".$_SESSION['cidkey']."'";
		$condition_com  = " AND BY_ID_PURPLE='".$_SESSION['cidkey']."'"; 
	}
	$str_merchant 	= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ".$condition." ORDER BY CLIENT_NAME ASC"; 
	$q_merchant 	= $db->query($str_merchant);
	
	$query_com = $db->query("SELECT * FROM ".$tpref."communities WHERE NAME IS NOT NULL ".$condition_com." AND STATUS_ACTIVE = '3' ORDER BY NAME ASC");


?>