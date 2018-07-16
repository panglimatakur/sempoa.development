<?php
error_reporting(0);
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	
	$direction 			= isset($_POST['direction']) 		? $sanitize->str($_POST['direction']) 	: "";
	$no 				= isset($_POST['no']) 				? $sanitize->number($_POST['no']) 		: "";
	$id_com 			= isset($_POST['id_com']) 			? $sanitize->number($_POST['id_com']) 	: "";
	$merchant_lists 	= isset($_POST['merchant_lists']) 	? $_POST['merchant_lists'] 				: "";
	$nm_komunitas 		= $db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY='".$id_com."'");
	$new_merchant		= "";
	if(!empty($direction) && $direction == "enter"){
		
		$msg_content = "<table width='100%'>";
		foreach($merchant_lists as &$merchant_list){
			$check_exist = $db->recount("SELECT ID_CLIENT FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$merchant_list."' AND ID_COMMUNITY = '".$id_com."'");
			if($check_exist == 0){
				$container = array(1=>
					array("ID_COMMUNITY",@$id_com),
					array("ID_CLIENT",@$merchant_list),
					array("TGLUPDATE",@$tglupdate));
				$db->insert($tpref."communities_merchants",$container);
				
				$q_merchant	 	= $db->query("SELECT CLIENT_NAME,CLIENT_EMAIL,CLIENT_DESCRIPTIONS,CLIENT_ADDRESS,CLIENT_LOGO FROM ".$tpref."clients WHERE ID_CLIENT = '".$merchant_list."' AND ID_CLIENT != '1'");
				$dt_merchant 	= $db->fetchNextObject($q_merchant);
				if(empty($dt_merchant->CLIENT_LOGO)){
					$logo_merchant = "<img src='".$dirhost."/files/images/no_image.jpg' style='width:100%'>";	
				}else{
					$logo_merchant = "<img src='".$dirhost."/files/images/logos/".$dt_merchant->CLIENT_LOGO."' style='width:100%'>";	
				}
				$msg_content .= "
				<tr style='border-bottom:1px solid #666666'>
					<td style='padding:8px; width:18%; text-align:center; vertical-align:top'>
						<div>
							".$logo_merchant."
						</div>	
					</td>
					<td style='vertical-align:top; padding:8px'>
						<b>".$dt_merchant->CLIENT_NAME."</b><br>
						".$dt_merchant->CLIENT_DESCRIPTIONS."<br><br>
						<span style='color:#FF0000'>".$dt_merchant->CLIENT_ADDRESS."</span>
						<br>
					</td>
				</tr>";
			$new_merchant 	.= $dt_merchant->CLIENT_NAME.",";
			}
		}
		$msg_content .= "</table>";
		
		$new_merchant = str_replace(",","",$new_merchant);
		if(!empty($new_merchant)){
			
			$email_merchant	= "Info Discoin Community <info@".$website_name.">";
			$admin_email 	= "thetakur@gmail.com";
			
			$subject_coin	= "Merchant baru komunitas ".$nm_komunitas;		
			$type			= "html";
			$msg_coin		= "
			<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
				".$msg_content."
				<br><br>
					Baru saja bergabung di Komunitas Discoin <b style='color:#FF0000'>\"".$nm_komunitas."\"</b>
			</div>";
			//sendmail($admin_email,$subject_coin,$msg_coin,$email_merchant,$type);
			
			$newsletter_cust_list	= array();
			$cust_list				= array();
			$merch_list				= array();	
			$str_communities = "SELECT 
											a.ID_CLIENT,b.CLIENT_EMAIL,b.CLIENT_NAME
										  FROM 
											".$tpref."communities_merchants a, 
											".$tpref."clients b
										  WHERE 
											a.ID_CLIENT = b.ID_CLIENT AND
											a.ID_COMMUNITY = '".$id_com."' AND a.ID_CLIENT != '1'";
			$q_communities 	= $db->query($str_communities);
			while($dt_communities = $db->fetchNextObject($q_communities)){
				$merchant_app = $db->fob("CLIENT_APP",$tpref."clients"," WHERE ID_CLIENT = '".$dt_communities->ID_CLIENT."'");
				//SEND NOTE TO CLIENT ADMIN
				if(!empty($dt_communities->CLIENT_EMAIL)){
					$merch_recipient 		= $dt_communities->CLIENT_EMAIL;
					if(!empty($merch_recipient) && $validate->email($merch_recipient) == "true"){
						if(!in_array($merch_recipient,$merch_list)){
						array_push($merch_list,$merch_recipient);
							$merch_msg		= $msg_coin."
							<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
								<br><br>
								Terimakasih<br>
								NB : Pesan ini disampaikan oleh<br>
								<img src='".$logo_path."'><br>
								
							</div>";
							//sendmail($merch_recipient,$subject_coin,$merch_msg,$email_merchant,$type);
						}
					}
				}
				//END OF SEND NOTE TO CLIENT ADMIN
				
				//SEND NOTE TO CLIENT MEMBERS
				$q_comm_customers 	= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CLIENT = '".$dt_communities->ID_CLIENT."'"); 
				while($dt_comm_customers = $db->fetchNextObject($q_comm_customers)){
					$cust_recipient 	= $dt_comm_customers->CUSTOMER_EMAIL;
					if(!empty($cust_recipient) && $validate->email($cust_recipient) == "true"){
						if(!in_array($cust_recipient,$cust_list)){
						array_push($cust_list,$cust_recipient);
							$cust_msg		= $msg_coin."
							<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
								<br><br>
								Terimakasih<br>
								NB : Pesan ini disampaikan oleh<br>
								<img src='".$logo_path."'><br>
								
							</div>";
							//sendmail($cust_recipient,$subject_coin,$cust_msg,$email_merchant,$type);
						}
					}
				}
				//END OF SEND NOTE TO CLIENT MEMBERS
				
				//SEND NOTE TO CLIENT SUBSCRIBER
				$q_comm_newsletter 	= $db->query("SELECT ID_NEWSLETTER,EMAIL_NEWSLETTER,ENCRYPTED_EMAIL FROM ".$tpref."newsletters WHERE ID_CLIENT = '".$dt_communities->ID_CLIENT."' AND ID_NEWSLETTER_SOURCE = '2'"); 
				while($dt_comm_newsletter = $db->fetchNextObject($q_comm_newsletter)){
					$newsletter_recipient 		= $dt_comm_newsletter->EMAIL_NEWSLETTER;
					if(!empty($newsletter_recipient) && $validate->email($newsletter_recipient) == "true"){
						if(!in_array($newsletter_recipient,$newsletter_cust_list)){
						array_push($newsletter_cust_list,$newsletter_recipient);
							$newslettert_msg		= $msg_coin."
							<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
								<br>
								<br>
								
								Untuk berhenti berlanggan buletin ".$website_name.", klik di 
								<a href='".$dirhost."/".$merchant_app.".coin/activation/".$dt_comm_newsletter->ID_NEWSLETTER."/cancel_subscribe-".$dt_comm_newsletter->ENCRYPTED_EMAIL."'>
									".$dirhost."/".$merchant_app.".coin/activation/".$dt_comm_newsletter->ID_NEWSLETTER."/cancel_subscribe=".$dt_comm_newsletter->ENCRYPTED_EMAIL."
								</a> 
								atau Pastekan Link tersebut di browser anda.
								
								<br><br>
								Terimakasih<br>
								NB : Pesan ini disampaikan oleh<br>
								<img src='".$logo_path."'><br>
								
							</div>";
							//sendmail($newsletter_recipient,$subject_coin,$newslettert_msg,$email_merchant,$type);
						}
					}
				}
				//END OF SEND NOTE TO CLIENT SUBSCRIBER
			}
			
			//SEND NOTE TO SEMPOA SUBSCRIBER
			$q_comm_newsletter_2 	= $db->query("SELECT ID_NEWSLETTER,EMAIL_NEWSLETTER,ENCRYPTED_EMAIL FROM ".$tpref."newsletters WHERE ID_CLIENT = '1' AND ID_NEWSLETTER_SOURCE = '1' AND SUBSCRIBE_STATUS ='2'"); 
			while($dt_comm_newsletter_2 = $db->fetchNextObject($q_comm_newsletter_2)){
				$newsletter_recipient_2 		= $dt_comm_newsletter_2->EMAIL_NEWSLETTER;
				if(!empty($newsletter_recipient_2) && $validate->email($newsletter_recipient_2) == "true"){
					$newslettert_msg2		= $msg_coin."
					<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
						<br>
						<br>
						
						Untuk berhenti berlanggan buletin ".$website_name.", klik di 
						<a href='".$dirhost."/".$merchant_app.".coin/activation/".$dt_comm_newsletter_2->ID_NEWSLETTER."/cancel_subscribe-".$dt_comm_newsletter_2->ENCRYPTED_EMAIL."'>
							".$dirhost."/".$merchant_app.".coin/activation/".$dt_comm_newsletter_2->ID_NEWSLETTER."/cancel_subscribe=".$dt_comm_newsletter_2->ENCRYPTED_EMAIL."
						</a> 
						atau Pastekan Link tersebut di browser anda.
						
						<br><br>
						Terimakasih<br>
						NB : Pesan ini disampaikan oleh<br>
						<img src='".$logo_path."'><br>
						
					</div>";
					//sendmail($newsletter_recipient_2,$subject_coin,$newslettert_msg2,$email_merchant,$type);
				}
			}
			//END OF SEND NOTE TO SEMPOA SUBSCRIBER
		}
		echo "<div class='alert alert-success'>Komunitas Merchant Berhasil Disusun</div>";
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
