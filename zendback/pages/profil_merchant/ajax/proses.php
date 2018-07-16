<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','ADMIN',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 	= isset($_REQUEST['direction']) 	? $sanitize->str($_REQUEST['direction']) 	: "";
	$no 		= isset($_REQUEST['no']) 			? $sanitize->number($_REQUEST['no']) 		: "";
	
	$marker_id 			= isset($_REQUEST['marker_id'])  ? $sanitize->str($_REQUEST['marker_id']) 			: "";
	$coordinate 		= isset($_REQUEST['coordinate']) ? $sanitize->str($_REQUEST['coordinate']) 			: "";
	$keterangan 		= isset($_REQUEST['keterangan']) ? $sanitize->str($_REQUEST['keterangan']) 			: "";
	
	if(!empty($direction) && $direction == "delete"){ 
		$db->delete($tpref."clients","WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
	}
	if(!empty($direction) && $direction == "delete_coordinate"){ 
		$db->delete($tpref."clients_maps"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND MARKER_LABEL = '".$marker_id."'");
		echo "Berhasil";
	}
	if(!empty($direction) && $direction == "save_coordinate"){
		$db->delete($tpref."clients_maps"," WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND MARKER_LABEL = '".$marker_id."'");
		$coordinate = array(1=>
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("MARKER_LABEL",$marker_id),
						array("COORDINATES",$coordinate),
						array("COORDINATE_DESCRIPTIONS",$keterangan),
						array("UPDATEDATE",$tglupdate));
		$db->insert($tpref."clients_maps",$coordinate);	
		echo "Peta Berhasil Disimpan";
	}
	
}
else{  defined('mainload') or die('Restricted Access'); }
?>
