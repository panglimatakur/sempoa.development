<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','ADMIN',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 	= isset($_POST['direction']) 	? $sanitize->str($_POST['direction']) 	: "";
	$no 		= isset($_POST['no']) 			? $sanitize->number($_POST['no']) 		: "";

	if(!empty($direction) && $direction == "delete"){ 
		$oldhalaman = $db->fob("PAGE","system_pages_client","WHERE ID_PAGE_CLIENT='".$no."'"); 
		if(is_dir($basepath."/zendback/pages/".$oldhalaman)){ rename($basepath."/zendback/pages/".$oldhalaman,$basepath."/zendback/pages/~".$oldhalaman); } 
		$db->delete("system_pages_client_rightaccess","WHERE ID_PAGE_CLIENT='".$no."'");
		$db->delete("system_pages_client","WHERE ID_PAGE_CLIENT='".$no."'");
		$db->delete("system_pages_client","WHERE ID_PARENT='".$no."'");
		echo msg("Data Link Sudah Dihapus!!","true");
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
