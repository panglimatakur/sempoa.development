<?php
session_start();
if(!empty($_SESSION['uidkey']) && !empty($_SESSION['cidkey'])){ 
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$id_doc 		= isset($_REQUEST['id_doc']) 	? $sanitize->number($_REQUEST['id_doc']) : "";
	include $call->func("function.download");
	if(!empty($id_doc)){
		$q_doc			= $db->query("SELECT * FROM ".$tpref."documents WHERE ID_DOCUMENT='".$id_doc."'");
		$dt_doc			= $db->fetchNextObject($q_doc);
		$id_user		= $dt_doc->ID_USER;
		$name			= $dt_doc->FILE_DOCUMENT;
		$file			= $basepath."/files/documents/users/".$id_user."/".$dt_doc->FILE_DOCUMENT;
		output_file($file, $name, $mime_type='');
	}
}else{
	defined('mainload') or die('Restricted Access');
}
?>