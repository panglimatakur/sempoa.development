<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		
		
		$direction			= isset($_REQUEST['direction']) 	? $sanitize->str($_REQUEST['direction']) : "";
		$id_merchant		= isset($_REQUEST['id_merchant']) 	? $sanitize->number($_REQUEST['id_merchant']) : "";
		$code_numbers		= $_REQUEST['code_numbers'];
		
		if(!empty($direction) && $direction == "activate"){
			$nm_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$id_merchant."'");
			foreach($code_numbers as &$code_number){
				$db->query("UPDATE ".$tpref."discoin_activation_codes 
							SET 
								ACTIVATE_BY_ID_CLIENT = '".$id_merchant."'
							WHERE ID_DISCOIN_ACTIVATION_CODE = '".$code_number."'");
				
			}
			echo $nm_merchant;
		}
		
		
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
?>