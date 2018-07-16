<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$id 		= isset($_POST['id']) 			? $_POST['id'] : "";
	
	if(!empty($direction) && $direction == "remove_subject"){
		$db->delete($tpref."chat_subject"," WHERE ID_CHAT_SUBJECT='".$id."'");
		$db->delete($tpref."chat"," WHERE ID_CHAT_SUBJECT='".$id."'");
	}
	if(!empty($direction) && $direction == "remove_chat"){
		$db->delete($tpref."chat"," WHERE ID_CHAT='".$id."'");
	}
}
?>