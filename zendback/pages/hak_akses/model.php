<?php defined('mainload') or die('Restricted Access'); ?>
<?php
 if(!empty($single) && (!empty($direction) && $direction == "edit")){
	$id_client_level = $db->fob("ID_CLIENT_LEVEL",$tpref."clients"," WHERE ID_CLIENT = '".$client_id."'");	
 }
 $q_client_level 		= $db->query("SELECT ID_CLIENT_LEVEL,NAME FROM system_master_client_level ORDER BY ID_CLIENT_LEVEL ASC");
?>