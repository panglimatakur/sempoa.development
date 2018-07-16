<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 			= isset($_POST['direction']) 			? $sanitize->str($_POST['direction']) 	: "";
	$no 				= isset($_POST['no']) 					? $sanitize->number($_POST['no']) 		: "";
	$id_customer 		= isset($_REQUEST['id_customer']) 		? $_REQUEST['id_customer'] 				: "";
	$id_status 			= isset($_REQUEST['id_status']) 		? $_REQUEST['id_status'] 				: "";
	$id_titanium 		= isset($_REQUEST['id_titanium']) 		? $_REQUEST['id_titanium'] 				: "";
	$expiration_date 	= isset($_REQUEST['expiration_date']) 	? $_REQUEST['expiration_date'] 			: "";
	
	if(!empty($direction) && $direction == "delete"){ 
		$db->delete($tpref."clients","WHERE ID_CLIENT='".$no."'");
	}
	if(!empty($direction) && $direction == "delete_file"){ 
		$q_doc		= $db->query("SELECT * FROM ".$tpref."documents WHERE ID_DOCUMENT='".$no."' AND ID_CLIENT='".$id_client."'");
		$dt_doc		= $db->fetchNextObject($q_doc);
		$id_user	= $dt_doc->ID_USER;
		$name		= $dt_doc->FILE_DOCUMENT;
		$file		= $basepath."/files/documents/users/".$id_user."/".$dt_doc->FILE_DOCUMENT;
		if(is_file($file)){
			unlink($file);
			$db->delete($tpref."documents"," WHERE ID_DOCUMENT='".$no."'");
		}
	}
	if(!empty($direction) && $direction == "set_status"){
		$cust_name 	= isset($_REQUEST['cust_name']) 	? $_REQUEST['cust_name']:"";
		$cust_email = isset($_REQUEST['cust_email']) 	? $_REQUEST['cust_email']:"";
		$cust_phone = isset($_REQUEST['cust_phone']) 	? $_REQUEST['cust_phone']:"";
		function config($id){
			global $db;
			global $tpref;
			$result = $db->fob("CONFIG_VALUE",$tpref."config"," WHERE ID_CONFIG = '".$id."'");
			return $result;
		}
		$db->query("UPDATE ".$tpref."customers SET EXPIRATION_DATE='".$expiration_date."',CUSTOMER_NAME='".$cust_name."',CUSTOMER_EMAIL='".$cust_email."',CUSTOMER_PHONE='".$cust_phone."',ID_CLIENT_TITANIUM='".@$id_titanium."',CUSTOMER_STATUS = '".$id_status."',REQUEST_BY_ID_USER='".$_SESSION['uidkey']."' WHERE ID_CUSTOMER = '".$id_customer."'");
		
		if($id_status == 3){
			$q_coin			= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."'");
			$dt_coin		= $db->fetchNextObject($q_coin);
			$nama_coin		= $dt_coin->CUSTOMER_NAME;
			$uname_coin		= $dt_coin->CUSTOMER_USERNAME;
			$pass_coin		= $dt_coin->CUSTOMER_PASS;
			$to 			= $cust_email;
			
			$q_merchant		= $db->query("SELECT CLIENT_NAME,CLIENT_EMAIL FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_coin->ID_CLIENT."'");
			$dt_merchant 	= $db->fetchNextObject($q_merchant);
			$merchant_coin	= $dt_merchant->CLIENT_NAME;
			$merchant_email	= $dt_merchant->CLIENT_EMAIL;

			rank_formula($dt_coin->ID_CLIENT);
			
			if(!empty($dt_coin->ID_CUSTOMER_REF)){
				$ch_ref		= $db->recount("SELECT ID_CUSTOMER_REF FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."' AND ID_CUSTOMER_REF='".$dt_coin->ID_CUSTOMER_REF."'");
				if($ch_ref == 0){ 
					$debt 		= (config(13)/100)*config(3);
					$saldo		= $db->fob("SALDO",$tpref."savings"," WHERE ID_CUSTOMER='".$dt_coin->ID_CUSTOMER_REF."' ORDER BY ID_SAVING DESC");
					$new_saldo	= $saldo+$debt;
					$saving	= array(1=>
										array("ID_CUSTOMER",$dt_coin->ID_CUSTOMER_REF),
										array("ID_CONFIG",3),
										array("DEBT",$debt),
										array("SALDO",$new_saldo),
										array("TGLUPDATE",$tglupdate) 
									);
					$db->insert($tpref."savings",$saving);
				}
			}
			if(!empty($to) && $dt_coin->ID_CLIENT !='1'){
				$subject 		= "Aktifasi COIN ".$merchant_coin;
				$from			= "Info Discoin Community <info@".$website_name.">";
				$type			= "html";
				$msg 			= "
				<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
					Dear ".$nama_coin.",<br><br>
					Terimakasih sudah bergabung di Komunitas ".$merchant_coin.", yang di support oleh Sempoa Discoin Community.
					<br><br>
					COIN anda telah berhasil di aktifkan, dengan account<br><br>
					Username : ".$uname_coin."<br>
					Password : ".$pass_coin."<br>
					<br>
					<br>
					Mohon di jaga dengan baik, atau di simpan dengan baik dimana pun anda bisa meyimpan nomor COIN anda.
					<br>
					<br>
					Terimakasih<br><br>
					- info@".$website_name." - <br><br>
					<img src='".$logo_path."'><br>
					
				</div>	";		
				sendmail($to,$subject,$msg,$from,$type);
				
				$sempoa_email	= "Info Discoin Community <info@".$website_name."";
				$subject_coin	= $nama_coin." baru saja menjadi member Discoin ".$merchant_coin." ";
				$msg_coin		= "
				<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
					".$nama_coin." baru saja bergabung menjadi pelanggan ".$merchant_coin.", yang di support oleh Sempoa Discoin Community.
					<br>
					<br>
					Anda juga bisa membantu promosi ".$merchant_coin." dengan menambah member anda.
					<br>
					<br>
					Terimakasih<br><br>
					- info@".$website_name." - <br><br>
					<img src='".$logo_path."'><br>
					
				</div>		
				";
				
				$recipients 	= "thetakur@gmail.com,indwic@gmail.com,junjungan70@gmail.com";
				sendmail($recipients,$subject_coin,$msg_coin,$sempoa_email,$type);
				$recipient 	= "";
				$q_communities 	= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$dt_coin->ID_CLIENT."'");
				while($dt_communities = $db->fetchNextObject($q_communities)){
					$q_communities_2 	= $db->query("
													SELECT 
														a.ID_CLIENT,b.CLIENT_EMAIL 
													FROM 
														".$tpref."communities_merchants a, 
														".$tpref."clients b
													WHERE 
														a.ID_CLIENT = b.ID_CLIENT AND
														a.ID_COMMUNITY = '".$dt_communities->ID_COMMUNITY."'");
					while($dt_communities_2 = $db->fetchNextObject($q_communities_2)){
						if(!empty($dt_communities_2->CLIENT_EMAIL)){
							$recipient = $dt_communities_2->CLIENT_EMAIL;
							sendmail($recipient,$subject_coin,$msg_coin,$sempoa_email,$type);
						}
					}
					
				}
			}
		}
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
