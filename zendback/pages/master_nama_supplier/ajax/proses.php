<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$no 		= isset($_POST['no']) 			? $_POST['no'] : "";
	
	if($direction == "delete"){
		$db->delete($tpref."partners"," WHERE ID_PARTNER='".$no."'");
	}
}
?>