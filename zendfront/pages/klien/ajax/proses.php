<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','ADMIN',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 		= isset($_POST['direction']) 		? $sanitize->str($_POST['direction']) 		: "";
	$no 			= isset($_POST['no']) 				? $sanitize->number($_POST['no']) 			: "";
	$client_name 	= isset($_POST['client_name']) 		? $sanitize->str($_POST['client_name']) 	: "";
	$form_direction	= isset($_POST['form_direction']) 	? $sanitize->str($_POST['form_direction']) 	: "";
	
	if(!empty($direction) && $direction == "delete"){ 
		$q_logos		= $db->query("SELECT CLIENT_LOGO,CLIENT_LOGO_LABEL,CLIENT_APP FROM ".$tpref."clients WHERE ID_CLIENT='".$no."'");
		$dt_logos		= $db->fetchNextObject($q_logos);
		@$photori 		= $dt_logos->CLIENT_LOGO;
		@$app_ori		= $dt_logos->CLIENT_APP; 
		if(!is_dir($basepath."/files/images/logos/".$photori)){
			unlink($basepath."/files/images/logos/".$photori);
		}
		if(!is_dir($basepath."/files/images/logos/".$dt_logos->CLIENT_LOGO_LABEL)){
			unlink($basepath."/files/images/logos/".$dt_logos->CLIENT_LOGO_LABEL);
		}
		$db->delete($tpref."clients","WHERE ID_CLIENT='".$no."'");
	}
	if(!empty($direction) && $direction == "check_app"){
		$app_name		= $sanitize->str(strtolower(str_replace(" ","",$client_name)));
		if($form_direction == "insert"){
			$count_app		= $db->recount("SELECT CLIENT_APP FROM ".$tpref."clients WHERE CLIENT_APP='".$app_name."'");
		}else{
			$count_app		= $db->recount("SELECT CLIENT_APP FROM ".$tpref."clients WHERE CLIENT_APP='".$app_name."' AND ID_CLIENT != '".$no."'");
		}
		
		if($count_app > 0){
			$result["nama_app"] = $app_name."".md5(substr($app_name,0,3));;
		}else{
			$result["nama_app"] = $app_name;
		}
		echo json_encode($result);
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
