<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_POST['direction']) 	? $sanitize->str($_POST['direction']) 	: "";
	$no 		= isset($_POST['no']) 			? $sanitize->number($_POST['no']) 		: "";
	if(!empty($direction) && $direction == "delete_file"){ 
		$q_doc		= $db->query("SELECT * FROM ".$tpref."documents WHERE ID_DOCUMENT='".$no."' AND ID_USER='".$_SESSION['uidkey']."'");
		$dt_doc		= $db->fetchNextObject($q_doc);
		$id_user	= $dt_doc->ID_USER;
		$name		= $dt_doc->FILE_DOCUMENT;
		$file		= $basepath."/files/documents/users/".$id_user."/".$dt_doc->FILE_DOCUMENT;
		if(is_file($file)){
			unlink($file);
		}
		$db->delete($tpref."documents"," WHERE ID_DOCUMENT='".$no."' AND ID_USER='".$_SESSION['uidkey']."'");
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
