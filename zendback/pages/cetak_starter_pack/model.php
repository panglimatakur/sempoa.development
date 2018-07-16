<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$query_branch 	= $db->query("SELECT * FROM ".$tpref."clients ".$condition_1." ORDER BY CLIENT_NAME");
	
	if(!empty($id_client_form)){
		$query_client 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$id_client_form."' ORDER BY CLIENT_NAME");
		$dt_client		= $db->fetchNextObject($query_client);
		
		$q_customer 	= $db->query("SELECT COIN_NUMBER FROM ".$tpref."customers WHERE ID_CLIENT='".$id_client_form."' AND CUSTOMER_STATUS = '3' ORDER BY ID_CUSTOMER ASC LIMIT 0,5");
		
		$q_admin		= $db->query("SELECT USER_USERNAME,USER_PASS FROM system_users_client WHERE ID_CLIENT='".$id_client_form."' AND ID_CLIENT_USER_LEVEL = '1' ORDER BY ID_USER ASC");
		$dt_admin		= $db->fetchNextObject($q_admin);
		
		$q_titanium		= $db->query("SELECT COIN_NUMBER FROM ".$tpref."customers WHERE ID_CLIENT='1' AND ID_CLIENT_TITANIUM = '".$id_client_form."' ORDER BY ID_CUSTOMER ASC LIMIT 0,3");
	}

?>