<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 	= isset($_POST['direction']) 	? $sanitize->str($_POST['direction']) 	: "";
	$no 		= isset($_POST['no']) 			? $sanitize->number($_POST['no']) 		: "";

	if(!empty($direction) && $direction == "delete"){ 
		$db->delete($tpref."products_categories","WHERE ID_PRODUCT_CATEGORY='".$no."'");
		$db->delete($tpref."products_categories","WHERE ID_PARENT='".$no."'");
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
