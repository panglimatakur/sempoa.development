<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	
	$direction 			= isset($_POST['direction']) 		? $sanitize->str($_POST['direction']) 		: "";
	$id_com 			= isset($_POST['id_com']) 			? $sanitize->number($_POST['id_com']) 		: "";
	$id_merchant 		= isset($_POST['id_merchant']) 		? $sanitize->number($_POST['id_merchant']) 	: "";
	if(!empty($direction) && $direction == "check_community"){
		$num_community   	= $db->recount("SELECT * 
							   FROM ".$tpref."communities_merchants 
							   WHERE ID_CLIENT = '".$id_merchant."' AND ID_COMMUNITY='".$id_com."'");
		$nm_client 		= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$id_merchant."'"); 
		if($num_community > 0){
			$nm_komunitas	= $db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY = '".$id_com."'");
			$result['io']  = "1"; 
			$result['msg'] = "<b style='color:#CF5C60'>".$nm_client."</b> Sudah terdaftar di Komunitas <b style='color:#37A873'>".$nm_komunitas."</b>, silahkan pilih komunitas lain..";	
		}else{
			$num_community_program = $db->recount("SELECT 
													ID_CLIENT 
								   				   FROM 
												   	system_pages_client_rightaccess
												   WHERE 
												   	ID_CLIENT = '".$id_merchant."' AND ID_PAGE_CLIENT='202'");
			if($num_community_program > 0){
				$result['io']  = "2"; 
			}else{
				$result['io']  = "1"; 
				$result['msg'] = "<b style='color:#CF5C60'>".$nm_client."</b> tidak mengikuti program komunitas <b style='color:#CF5C60'>".@$website_name."</b>, silahkan, aktifkan program komunitas <b style='color:#CF5C60'>".$nm_client."</b>, pada halaman hak akses.";	
			}
		}
		echo json_encode($result);
		
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
