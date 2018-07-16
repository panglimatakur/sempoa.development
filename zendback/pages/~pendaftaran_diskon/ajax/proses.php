<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
	$direction 	= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$id 		= isset($_REQUEST['id']) 		? $_REQUEST['id'] 			: "";
	if(!empty($direction) && $direction == "insert_diskon")	{
		$pattern 		= isset($_REQUEST['pattern']) 	   	? $_REQUEST['pattern'] 		: 0;
		$jml_beli 		= isset($_REQUEST['jml_beli'])		? $_REQUEST['jml_beli'] 	: 0;
		$pre_order 		= isset($_REQUEST['pre_order'])		? $_REQUEST['pre_order'] 	: 0;
		$nilai 			= isset($_REQUEST['nilai']) 	   	? $_REQUEST['nilai'] 		: 0;
		$besar 			= isset($_REQUEST['besar']) 	   	? $_REQUEST['besar'] 		: 0;
		$satuan 		= isset($_REQUEST['satuan']) 		? $_REQUEST['satuan'] 		: 0;
		$id_products 	= isset($_REQUEST['id_products'])   ? $_REQUEST['id_products'] 	: 0;
		$keterangan 	= isset($_REQUEST['keterangan'])	? $_REQUEST['keterangan'] 	: "";
		$expiration 	= isset($_REQUEST['expiration'])	? $_REQUEST['expiration'] 	: "";
		$target 		= isset($_REQUEST['target'])		? $_REQUEST['target']		: "";
		$formember 		= isset($_REQUEST['formember'])		? $_REQUEST['formember']	: "";
		
		$quota 			= isset($_REQUEST['quota'])			? $_REQUEST['quota']		: "0";
		$quota_unit 	= isset($_REQUEST['quota_unit'])	? $_REQUEST['quota_unit']	: "0";
		
		switch($target){
			case "community":
				$field  = "COMMUNITY_FLAG"; 
			break;	
			case "customer":
				$field  = "CUSTOMER_FLAG";  
			break;	
		}
		if($satuan == "persen"){ $piece = "%"; }else{ $piece = "Rp"; }		
		// && !empty($satuan)
		if(!empty($besar)){
			$list_id_product = "";
			if(!empty($id_products)){
				foreach($id_products as &$id_product){
					$list_id_product .= ";".$id_product.",";
					if($pattern == "1"){
						if($jml_beli == "few"){
							$db->query("UPDATE ".$tpref."products SET DISCOUNT = '".$besar."' WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_PRODUCT = '".$id_product."'");	 
						}
					}
				}
			}
			$jml_products 	= count($id_products);
			if($pattern == "1" && $jml_products == 0)	{ 
				$jml_beli = "all"; 
			}
			if($pattern == "2" && empty($jml_beli))		{ 
				$pattern = "1"; 
				$jml_beli = "all"; 
			}
			
			$container 	= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_DISCOUNT_PATTERN",@$pattern),
								array("ID_PRODUCTS",@$list_id_product),
								array("BUY_SUMMARY",@$jml_beli),
								array("VALUE",@$besar),
								array("PIECE",@$satuan),
								array("STATEMENT",@$keterangan),
								array("PRE_ORDER_STATUS",@$pre_order),
								array("PO_QUOTA","0"),
								array("PO_ID_UNIT",@$quota_unit),
								array($field,"1"),
								array("EXPIRATION_DATE",@$expiration),
								array("DISCOUNT_STATUS","3"),
								array("TGLUPDATE",$tglupdate." ".$wktupdate));
			$db->insert($tpref."client_discounts",$container);
			$id_diskon			= mysql_insert_id();
			$result['msg'] 		= 1;
			$result['id_diskon']= $id_diskon;
		}else{
			$result['msg'] 		= 2;
		}
			$result['nilai'] 	= $nilai;
			
		echo json_encode($result);
	}
	if(!empty($direction) && $direction == "save_diskon")	{
		$id_diskon 		= isset($_REQUEST['id_diskon']) 	? $_REQUEST['id_diskon'] 	: "";
		$pattern 		= isset($_REQUEST['pattern']) 	   ? $_REQUEST['pattern'] 		: "";
		$jml_beli 		= isset($_REQUEST['jml_beli'])		? $_REQUEST['jml_beli'] 	: "";
		$pre_order 		= isset($_REQUEST['pre_order'])		? $_REQUEST['pre_order'] 	: "";
		$nilai 			= isset($_REQUEST['nilai']) 		? $_REQUEST['nilai'] 		: "";
		$besar 			= isset($_REQUEST['besar']) 		? $_REQUEST['besar'] 		: "";
		$satuan 		= isset($_REQUEST['satuan']) 		? $_REQUEST['satuan'] 		: "";
		$id_products 	= isset($_REQUEST['id_products'])	? $_REQUEST['id_products'] 	: "";
		$keterangan 	= isset($_REQUEST['keterangan'])	? $_REQUEST['keterangan'] 	: "";
		$expiration 	= isset($_REQUEST['expiration'])	? $_REQUEST['expiration'] 	: "";
		
		$target 		= isset($_REQUEST['target'])	? $_REQUEST['target']: "";
		$formember 		= isset($_REQUEST['formember'])	? $_REQUEST['formember']: "";
		
		$quota 			= isset($_REQUEST['quota'])			? $_REQUEST['quota']		: "";
		$quota_unit 	= isset($_REQUEST['quota_unit'])	? $_REQUEST['quota_unit']	: "";

		switch($target){
			case "community":
				$field  		= "COMMUNITY_FLAG"; 
				$field_reverse	= "CUSTOMER_FLAG"; 
				$formember 		= "1";
			break;	
			case "customer":
				$field  		= "CUSTOMER_FLAG"; 
				$field_reverse	= "COMMUNITY_FLAG"; 
				$formember 		= "1";
			break;	
		}
	 	$db->query("UPDATE ".$tpref."client_discounts SET ".$field_reverse."='0' WHERE ID_DISCOUNT = '".$id_diskon."'");
		if(!empty($besar) && !empty($satuan)){
			$list_id_product= "";
			if(!empty($id_products)){
				foreach($id_products as &$id_product){
					$list_id_product .= ";".$id_product.",";
				}
			}
			
			$jml_products 	= count($id_products);
			if($pattern == "1" && $jml_products == 0)	{ 
				$jml_beli = "all"; 
			}
			if($pattern == "2" && empty($jml_beli))		{ 
				$pattern = "1"; 
				$jml_beli = "all"; 
			}
			$container 	= array(1=>
								array("ID_PRODUCTS",$list_id_product),
								array("ID_DISCOUNT_PATTERN",$pattern),
								array("BUY_SUMMARY",@$jml_beli),
								array("VALUE",$besar),
								array("PIECE",$satuan),
								array("STATEMENT",$keterangan),
								array("PRE_ORDER_STATUS",$pre_order),
								array("PO_QUOTA",$quota),
								array("PO_ID_UNIT",$quota_unit),
								array($field,@$formember),
								array("EXPIRATION_DATE",$expiration),
								array("DISCOUNT_STATUS","3"),
								array("TGLUPDATE",$tglupdate." ".$wktupdate));
			$db->update($tpref."client_discounts",$container," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_DISCOUNT='".$id_diskon."'");
			$result['msg'] 		= 1;
		}else{
			$result['msg'] 		= 2;
		}
		$result['nilai'] 	= $nilai;
		
		if($satuan == "persen"){ $piece = "%"; }else{ $piece = "Rp"; }				
		echo json_encode($result);
	}
	if(!empty($direction) && $direction == "delete_diskon")	{
		$id 			= isset($_REQUEST['id']) 		? $_REQUEST['id'] : "";
		$db->delete($tpref."client_discounts"," WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_DISCOUNT='".$id."'");
	}
	
	if(!empty($direction) && $direction == "del_pic"){
		$id_diskon 			= isset($_POST['id_diskon']) 		? $_POST['id_diskon'] : "";
		$id_product_disc  	= isset($_POST['id_product_disc']) 	? $_POST['id_product_disc'] : "";
		$db->query("UPDATE ".$tpref."client_discounts SET `ID_PRODUCTS` = REPLACE (`ID_PRODUCTS`,';".$id_product_disc.",','') WHERE ID_DISCOUNT = '".$id_diskon."'");
	}
	
	if(!empty($direction) && $direction == "send_note"){
		$id_diskon 		= isset($_REQUEST['id_diskon']) 	? $_REQUEST['id_diskon'] 	: "";
		$src_direction	= isset($_REQUEST['src_direction']) ? $_REQUEST['src_direction']: ""; 
		
		$q_diskon 	= $db->query("SELECT * FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_DISCOUNT = '".$id_diskon."'");
		$dt_diskon 	= $db->fetchNextObject($q_diskon);
		$pattern	= $dt_diskon->ID_DISCOUNT_PATTERN;
		$keterangan	= $dt_diskon->STATEMENT;
		$expiration	= $dt_diskon->EXPIRATION_DATE;
		$besar		= $dt_diskon->VALUE; 
		$piece		= $dt_diskon->PIECE;
		if($piece == "persen"){
			$piece = "%";	
		}
		if($pattern == "2"){ 
			$jml_beli 	= $dt_diskon->BUY_SUMMARY;
			$pattern_caption = " Untuk Minimal Pembelian ".money("Rp.",$jml_beli)." "; }
		if($pattern == "1"){ 
			if(empty($dt_diskon->ID_PRODUCTS)){
				$pattern_caption = " Untuk Semua Item ";
			}else{
				$pattern_caption = " Untuk Beberapa Item ";
			}
		}
		
		$q_merchant		= $db->query("SELECT CLIENT_NAME,CLIENT_EMAIL,CLIENT_LOGO,CLIENT_APP FROM ".$tpref."clients WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
		$dt_merchant 	= $db->fetchNextObject($q_merchant);
		$merchant_coin	= $dt_merchant->CLIENT_NAME;
		$merchant_email	= $dt_merchant->CLIENT_EMAIL;
		$merchant_app	= $dt_merchant->CLIENT_APP; 
		if(empty($dt_merchant->CLIENT_LOGO)){
			$logo_merchant = "<img src='".$dirhost."/files/images/no_image.jpg' style='width:100%'>";	
		}else{
			$logo_merchant = "<img src='".$dirhost."/files/images/logos/".$dt_merchant->CLIENT_LOGO."' style='width:100%'>";	
		}
		
		if(!empty($keterangan)){
			$cap_keterangan = "
			<br> <b>Keterangan : </b><br>
			".@$keterangan;
		}
		
		if(!empty($expiration) && $expiration != "0000-00-00"){ 
			$tgl_berakhir = "<b style='color:#FF0000'>Berlaku Hingga : ".$dtime->now2indodate2($expiration)."</b><br><br>";
		}
		$sempoa_email	= "Info Discoin Community <info@".$website_name.">";
		if($src_direction == "insert"){
			$subject_coin	= $merchant_coin." Menambahkan Promo Diskonnya ";
		}else{
			$subject_coin	= $merchant_coin." Memperbaharui Promo Diskonnya ";
		}
		$type			= "html";
		$msg_coin		= "
		<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
			<table width='100%' border='0'>
			  <tr>
				<td width='5%' style='vertical-align:top'>
					".$logo_merchant."
				</td>
				<td style='vertical-align:top'>
					".$subject_coin."
					<br>
					 Diskon ".$besar." ".@$piece." ".$pattern_caption." 
					 ".@$cap_keterangan."
					 ".@$tgl_berakhir."
					 <br><br>
					 Atau Silahkan mengunjungi, <a href='".$dirhost."/".$merchant_app.".coin' target='_blank'>".$dirhost."/".$merchant_app.".coin </a> ,untuk lebih lengkapnya<br>
				</td>
			  </tr>
			</table>
		</div>";
		
		// ==== SEND NOTE TO CUSTOMER COOMUNITIES ==== //		
		$recipient 				= "";
		$newsletter_cust_list	= array();
		$cust_list				= array();
		$merch_list				= array();	
		$q_comm 		= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
		while($dt_comm  = $db->fetchNextObject($q_comm)){
			
			$q_comm_merch 		= $db->query("
											SELECT 
												a.ID_CLIENT,b.CLIENT_EMAIL 
											FROM 
												".$tpref."communities_merchants a, 
												".$tpref."clients b
											WHERE 
												a.ID_CLIENT = b.ID_CLIENT AND
												a.ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND
												a.ID_CLIENT != '1'");
			while($dt_comm_merch = $db->fetchNextObject($q_comm_merch)){
				
				//SEND NOTE TO COMMUNITIES MERCHANTS
				$merch_recipient = $dt_comm_merch->CLIENT_EMAIL;
				if(!empty($merch_recipient) && $validate->email($merch_recipient) == "true"){
					if(!in_array($merch_recipient,$merch_list)){
					array_push($merch_list,$merch_recipient);
						$merch_msg		= $msg_coin."
						<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
							<br><br>
							Terimakasih<br>
							<img src='".$logo_path."'><br>
							
						</div>";
						//echo "MERCHANT - ".$merch_recipient."<br>";
						sendmail($merch_recipient,$subject_coin,$merch_msg,$sempoa_email,$type);
					}
				}
				//END OF SEND NOTE TO COMMUNITIES MERCHANTS
				
				//SEND NOTE TO COMMUNITIES MERCHANTS CUSTOMERS
				$q_comm_merch_cust 	= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CLIENT = '".$dt_comm_merch->ID_CLIENT."'"); 
				while($dt_comm_merch_cust = $db->fetchNextObject($q_comm_merch_cust)){
					$cust_recipient 	= $dt_comm_merch_cust->CUSTOMER_EMAIL;
					if(!empty($cust_recipient) && $validate->email($cust_recipient) == "true"){
						if(!in_array($cust_recipient,$cust_list)){
						array_push($cust_list,$cust_recipient);
							$cust_msg		= $msg_coin."
							<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
								<br><br>
								Terimakasih<br>
								<img src='".$logo_path."'><br>
								
							</div>";
							//echo "CUSTOMER - ".$cust_recipient."<br>";
							sendmail($cust_recipient,$subject_coin,$cust_msg,$sempoa_email,$type);
						}
					}
				}
				//END OF SEND NOTE TO COMMUNITIES MERCHANTS CUSTOMERS

				//SEND NOTE TO CUTOMER SUBSCRIBER
				$q_comm_newsletter 	= $db->query("SELECT ID_NEWSLETTER,EMAIL_NEWSLETTER,ENCRYPTED_EMAIL FROM ".$tpref."newsletters WHERE ID_CLIENT = '".$dt_comm_merch->ID_CLIENT."' AND ID_NEWSLETTER_SOURCE = '2' AND SUBSCRIBE_STATUS ='2'"); 
				while($dt_comm_newsletter = $db->fetchNextObject($q_comm_newsletter)){
					$newsletter_recipient 		= $dt_comm_newsletter->EMAIL_NEWSLETTER;
					if(!empty($newsletter_recipient) && $validate->email($newsletter_recipient) == "true"){
						if(!in_array($newsletter_recipient,$newsletter_cust_list)){
						array_push($newsletter_cust_list,$newsletter_recipient);
							$newsletter_msg		= $msg_coin."
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
								<img src='".$logo_path."'><br>
								
							</div>";
							//echo "NEWS  - ".$newsletter_recipient."<br>";
							sendmail($newsletter_recipient,$subject_coin,$newsletter_msg,$sempoa_email,$type);
						}
					}
				}
				//END OF SEND NOTE TO CUTOMER SUBSCRIBER
			}
			
		}
		/*print_r($newsletter_cust_list);
		echo @$merch_msg."<br><br>";
		echo @$cust_msg."<br><br>";
		echo @$newsletter_msg."<br><br>";*/
		
		
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
					<img src='".$logo_path."'><br>
					
				</div>";
				//echo "NEWS MERCHANT - ".$newsletter_recipient_2."<br>";
				sendmail($newsletter_recipient_2,$subject_coin,$newslettert_msg2,$sempoa_email,$type);
			}
		}
		//echo @$newslettert_msg2."<br><br>";
		//END OF SEND NOTE TO SEMPOA SUBSCRIBER
		
	}

?>