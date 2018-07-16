<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$nm_merchant	 	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
$q_discount_pattern = $db->query("SELECT ID_DISCOUNT_PATTERN,DESCRIPTION FROM ".$tpref."discount_patterns ORDER BY ID_DISCOUNT_PATTERN ASC");



?>