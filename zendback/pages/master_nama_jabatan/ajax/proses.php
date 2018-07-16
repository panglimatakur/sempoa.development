<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	$no 		= isset($_REQUEST['no']) 			? $_REQUEST['no'] : "";
	
	if(!empty($direction) && $direction == "delete"){
		$db->delete("system_master_client_users_level"," WHERE ID_CLIENT_USER_LEVEL='".$no."'");
	}
}
?>