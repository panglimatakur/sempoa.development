<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 	= isset($_REQUEST['direction']) 	? $sanitize->str($_REQUEST['direction']) 	: "";
	$no 		= isset($_REQUEST['no']) 			? $sanitize->number($_REQUEST['no']) 		: "";

	if(!empty($direction) && $direction == "delete"){ 
		$db->delete("system_master_location","where ID_LOCATION='".$no."'");
		$db->delete("system_master_location","where PARENT_ID='".$no."'");
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
