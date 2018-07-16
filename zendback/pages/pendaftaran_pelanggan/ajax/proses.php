<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 			= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$no 				= isset($_POST['no']) 			? $_POST['no'] : "";
	$id_customer 		= isset($_POST['id_customer']) 	? $_POST['id_customer'] : "";
	$id_status 			= isset($_POST['id_status']) 	? $_POST['id_status'] : "";
	$expiration_date 	= isset($_POST['expiration_date']) 	? $_POST['expiration_date'] : "";

	if(!empty($direction) && $direction == "delete"){
		@$photori = $db->fob("CUSTOMER_PHOTO",$tpref."customers","WHERE ID_CUSTOMER='".$no."'");
		if(!empty($photori)){
			if(is_file($basepath."/files/images/members/".$photori)){
					unlink($basepath."/files/images/members/".$photori);
			}
			if(is_file($basepath."/files/images/members/big/".$photori)){
				unlink($basepath."/files/images/members/big/".$photori);
			}
		}
		$db->delete($tpref."customers"," WHERE ID_CUSTOMER='".$no."'");
	}
	
	if(!empty($direction) && $direction == "activate"){
		$active_id		= isset($_POST['active_id']) 		? $_POST['active_id'] 		: "";	
		$keterangan		= isset($_POST['keterangan']) 		? $_POST['keterangan'] 		: "";
		$cidkey			= isset($_POST['cidkey']) 			? $_POST['cidkey'] 			: "";
		
		$db->query("UPDATE ".$tpref."customers SET ADDITIONAL_INFO='".$keterangan."',CUSTOMER_STATUS = '".$active_id."',REQUEST_BY_ID_USER='".$_SESSION['uidkey']."' WHERE ID_CUSTOMER='".$id_customer."'");
		
		@$purple_email	= $db->fob("CLIENT_EMAIL",$tpref."clients"," WHERE ID_CLIENT='".$dt_coin->BY_ID_PURPLE."'");
		$to 			= "thetakur@gmail.com,".$purple_email;
		if(empty($dt_coin->CLIENT_EMAIL)){ $sender = "kiki@s".$website_name; }else{ $sender = $dt_coin->CLIENT_EMAIL; }
		$subject 		= "Aktivasi COIN ".$dt_coin->COIN_NUMBER." MERCHANT ".$merchant;
		$msg 			= $pesan_request;
		mail($to,$subject,$msg,"From:".$sender);	
		echo "sent";
	}
}
?>