<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 			= isset($_POST['direction']) 		? $sanitize->str($_POST['direction']) 	: "";
	$no 				= isset($_POST['no']) 				? $sanitize->number($_POST['no']) 		: "";
	$id_com 			= isset($_POST['id_com']) 			? $sanitize->number($_POST['id_com']) 	: "";
	$merchant_lists 	= isset($_POST['merchant_lists']) 	? $_POST['merchant_lists'] 				: "";
	$seri 				= isset($_POST['seri']) 			? $sanitize->number($_POST['seri'] )	: "";
	
	if(!empty($direction) && $direction == "delete"){ 
		$db->delete($tpref."communities_merchants","WHERE ID_COMMUNITY_MERCHANT='".$no."' AND ID_COMMUNITY='".$id_com."'");
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
