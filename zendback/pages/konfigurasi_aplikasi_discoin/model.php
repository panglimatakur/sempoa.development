<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$q_merchant    	= $db->query("SELECT CLIENT_NAME, CLIENT_APP FROM ".$tpref."clients WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
$dt_merchant	= $db->fetchNextObject($q_merchant);
$nm_merchant   	= $dt_merchant->CLIENT_NAME;
$app_name   	= $nm_merchant;

$q_addons 	   	= $db->query("SELECT * 
							  FROM ".$tpref."discoin_addons 
							  WHERE 
							 	ADDON_ACTIVATE_STATUS = '3' 
							  ORDER BY ID_DISCOIN_ADDON ASC");
?>