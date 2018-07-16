<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$q_jabatan = $db->query("SELECT * FROM system_master_client_users_level 
							 WHERE ID_CLIENT_USER_LEVEL = '1' AND ID_CLIENT != '1' AND ACTIVE_STATUS ='3' ORDER BY NAME ASC");
	
	$q_merchant = $db->query("SELECT * FROM ".$tpref."clients ORDER BY CLIENT_NAME ASC");
?>