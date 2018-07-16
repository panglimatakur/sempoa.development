<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
		
		$direction 	= isset($_REQUEST['direction']) ? $_REQUEST['direction'] : "";
		$id_addon 	   = isset($_REQUEST['id_addon']) 	? $_REQUEST['id_addon'] 	: "";
		$status 	   = isset($_REQUEST['status']) 	? $_REQUEST['status'] 	: "";
		
		if(!empty($direction) && $direction == "set_status")	{
			$num_addon 	 = $db->recount("SELECT ID_CLIENT 
												 FROM ".$tpref."discoin_configs 
												 WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND 
												 	   ID_DISCOIN_ADDON = '".$id_addon."'");
			if($status == "3"){
				$container 	= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_DISCOIN_ADDON",$id_addon));
				$db->insert($tpref."discoin_configs",$container," WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
			}else{
				$db->delete($tpref."discoin_configs"," 
							WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_DISCOIN_ADDON = '".$id_addon."'");
			}
		}

	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
?>


