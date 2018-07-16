<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$no 		= isset($_REQUEST['no']) 			? $_REQUEST['no'] 			: "";
	$id_session = isset($_REQUEST['id_session']) 	? $_REQUEST['id_session'] 	: "";
	$id_picture = isset($_REQUEST['id_picture']) 	? $_REQUEST['id_picture'] 	: "";

	if(!empty($direction) && $direction == "set_status"){
		$id_status 	= isset($_REQUEST['id_status']) ? $_REQUEST['id_status'] 	: "";
		$db->query("UPDATE ".$tpref."products SET ID_STATUS = '".$id_status."' WHERE ID_PRODUCT = '".$no."'");
		
	}
	if(!empty($direction) && $direction == "delete"){
		$q_photos 	= $db->query("SELECT * FROM ".$tpref."products_photos WHERE ID_PRODUCT='".$no."'");
		while($dt_photos = $db->fetchNextObject($q_photos)){
			if(!empty($icon) || is_file($basepath."/files/images/products/".$id_client."/".$dt_photos->PHOTOS)){
				unlink($basepath."/files/images/products/".$id_client."/thumbnails/".$dt_photos->PHOTOS);
				unlink($basepath."/files/images/products/".$id_client."/".$dt_photos->PHOTOS);
			}
		}
		$db->delete($tpref."products_photos"," WHERE ID_PRODUCT='".$no."'");
		$db->delete($tpref."products"," WHERE ID_PRODUCT ='".$no."'");
	}
	if(!empty($direction) && $direction == "check_code"){
		$id_kategori 	= isset($_REQUEST['id_kategori']) ? $_REQUEST['id_kategori'] : "";
		@$last_code 		= $db->fob("CODE",$tpref."products"," WHERE ID_PRODUCT_CATEGORY = '".$id_kategori."' ORDER BY ID_PRODUCT DESC");
		echo $last_code;	
	}
	if(!empty($direction) && $direction == "delete_pic"){
		$q_photos 	= $db->query("SELECT * FROM ".$tpref."products_photos WHERE ID_PRODUCT_PHOTO='".$id_picture."'");
		$dt_photos = $db->fetchNextObject($q_photos);
		if(is_file($basepath."/files/images/products/".$id_client."/".$dt_photos->PHOTOS)){
			unlink($basepath."/files/images/products/".$id_client."/thumbnails/".$dt_photos->PHOTOS);
			unlink($basepath."/files/images/products/".$id_client."/".$dt_photos->PHOTOS);
			$db->delete($tpref."products_photos"," WHERE ID_PRODUCT_PHOTO='".$id_picture."'");
		}
	}
	
	if(!empty($direction) && $direction == "send_notification"){
		
		$q_merchant 		= $db->query("SELECT CLIENT_EMAIL,CLIENT_NAME,CLIENT_APP,CLIENT_LOGO FROM ".$tpref."clients WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
		$dt_merchant 		= $db->fetchNextObject($q_merchant); 
		$merchant_coin		= $dt_merchant->CLIENT_NAME;
		$merchant_app		= $dt_merchant->CLIENT_APP;
		$query_str	= " SELECT 
							a.*,b.PHOTOS 
						FROM 
							".$tpref."products a,".$tpref."products_photos b 
						WHERE 
							a.ID_CLIENT = '".$_SESSION['cidkey']."' AND 
							a.ID_PRODUCT = b.ID_PRODUCT AND
							a.UPLOAD_SESSION = '".$id_session."'
						ORDER BY a.ID_PRODUCT DESC";
		$q_produk 			= $db->query($query_str."  LIMIT 0,10");
		
		//NOTE CONTENT//
		$float				= "";		
		if(is_file($basepath."/files/images/".$dt_merchant->CLIENT_LOGO)){
			$client_logo = "
				<div style='float:left; margin-left:5px;'>
					<img src='".$dirhost."/files/images/".$dt_merchant->CLIENT_LOGO."' style='width:30%'>
				</div>";	
				$float = " style='float:left' ";	
		}
		
		$introduction		= "<div ".$float.">
									".$merchant_coin.", baru saja, memperbaharui katalog produknya, dan banyak yang baru terjadi di aplikasi Discoin.
							   </div>";
		
		$new_product_list = "
			<table width='100%' style='border:1px solid #EFD1E4'>";
		while($dt_produk 	= $db->fetchNextObject($q_produk)){
			$new_product_list .= "
				<tr>
					<td colspan='2' style='padding:9px 5px 9px 3px; background:#7B1A4D; color:#FFF'>
						".$dt_produk->NAME."
					</td>
				</tr>
				<tr>
					<td width='10%' style='padding:6px;'>"; 
					
						if(is_file($basepath."/files/images/products/".$_SESSION['cidkey']."/".$dt_produk->PHOTOS)){
			$new_product_list .= "<img src='".$dirhost."/files/images/products/".$_SESSION['cidkey']."/thumbnails/".$dt_produk->PHOTOS."'>";				
						}else{
			$new_product_list .= "<img src='".$dirhost."/files/images/no_image.jpg'>";				
						}
						
			$new_product_list .= "					
					</td>
					<td  width='90%' style='vertical-align:top;'>
						<b>Harga</b><br>
						<small>".money("Rp.",$dt_produk->SALE_PRICE)."</small><br>
						<b>Deskripsi</b><br>
						<small>".$dt_produk->DESCRIPTION."</small><br>
					</td>
				</tr>";
		}
					
		$new_product_list .= "
			</table>";
		//END OF NOTE CONTENT//
		
		
		
		// SENDING NOTE //
		$from				= "Info Discoin Community <info@".$website_name.">";
		$subject 			= "Update Produk Terbaru ".$merchant_coin;
		$to 				= "thetakur@gmail.com";
		$type				= "html";
		
		//READ COMMUNITIES
		$recipient 			= "";		
		$q_comm 		= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
		while($dt_comm  = $db->fetchNextObject($q_comm)){
			
			//READ COMMUNITIES MERCHANTS
			$q_comm_merch 		= $db->query("
											SELECT 
												a.ID_CLIENT,b.CLIENT_EMAIL 
											FROM 
												".$tpref."communities_merchants a, 
												".$tpref."clients b
											WHERE 
												a.ID_CLIENT = b.ID_CLIENT AND
												a.ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."'");
			while($dt_comm_merch = $db->fetchNextObject($q_comm_merch)){
				
				//READ COMMUNITIES MERCHANTS CUSTOMERS
				$q_comm_merch_cust 	= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CLIENT = '".$dt_comm_merch->ID_CLIENT."'"); 
				while($dt_comm_merch_cust = $db->fetchNextObject($q_comm_merch_cust)){
					$cust_recipient 		= $dt_comm_merch_cust->CUSTOMER_EMAIL;
					if(!empty($cust_recipient) && $validate->email($cust_recipient) == "true"){
						$msg_cust 				= "
						<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
							Hai ".$dt_comm_merch_cust->CUSTOMER_NAME.",<br><br>
							".@$introduction."
							<br clear='all'>
							<br>
							".$new_product_list."
							<br>
							<br>
							".$dt_comm_merch_cust->CUSTOMER_NAME.", jangan malu atau sungkan untuk membuka aplikasi Discoin ".$merchant_coin." anda, atau kunjungi halaman discoinnya di <a href='".$dirhost."/".$merchant_app.".coin' target='_blank'>".$dirhost."/".$merchant_app.".coin</a>
							<br>
							<br>
							Terimakasih<br>
							- info@".$website_name." - <br><br>
							<img src='".$logo_path."'><br>
							
						</div>	";	
						//echo "CUTOMER - ".$cust_recipient."<br>";
						sendmail($cust_recipient,$subject,$msg_cust,$from,$type);
					}
				}
				//echo $msg_cust."<br><br>";
				$q_comm_newsletter 	= $db->query("SELECT ID_NEWSLETTER,EMAIL_NEWSLETTER,ENCRYPTED_EMAIL FROM ".$tpref."newsletters WHERE ID_CLIENT = '".$dt_comm_merch->ID_CLIENT."' AND ID_NEWSLETTER_SOURCE = '2' AND SUBSCRIBE_STATUS ='2'"); 
				while($dt_comm_newsletter = $db->fetchNextObject($q_comm_newsletter)){
					$newsletter_recipient 		= $dt_comm_newsletter->EMAIL_NEWSLETTER;
					if(!empty($newsletter_recipient) && $validate->email($newsletter_recipient) == "true"){
						$msg_newsletter 				= "
						<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
							Hai,<br><br>
							".@$introduction."
							<br clear='all'>
							<br>
							".$new_product_list."
							<br>
							<br>
							Jangan malu atau sungkan untuk membuka aplikasi Discoin ".$merchant_coin." anda, atau kunjungi halaman discoinnya di <a href='".$dirhost."/".$merchant_app.".coin' target='_blank'>".$dirhost."/".$merchant_app.".coin</a>
							<br>
							<br>
							
							Untuk berhenti berlanggan buletin ".$website_name.", klik di 
							<a href='".$dirhost."/".$merchant_app.".coin/activation/".$dt_comm_newsletter->ID_NEWSLETTER."/cancel_subscribe-".$dt_comm_newsletter->ENCRYPTED_EMAIL."'>
								".$dirhost."/".$merchant_app.".coin/activation/".$dt_comm_newsletter->ID_NEWSLETTER."/cancel_subscribe=".$dt_comm_newsletter->ENCRYPTED_EMAIL."
							</a> 
							atau Pastekan Link tersebut di browser anda.
							
							<br><br>
							Terimakasih
							<br>
							- info@".$website_name." - <br><br>
							<img src='".$logo_path."'><br>
							
						</div>	";	
						//echo "NEWS - ".$newsletter_recipient."<br>";
						sendmail($newsletter_recipient,$subject,$msg_newsletter,$from,$type);
					}
				}
				//echo @$msg_newsletter;
			}
			
		}
		// END OF SENDING NOTE //
		
	}

	if(!empty($direction) && $direction == "add_new_category"){
		$parent_id 		= isset($_REQUEST['parent_id']) 	? $_REQUEST['parent_id'] 	: "";
		$id_type 		= isset($_REQUEST['id_type']) 		? $_REQUEST['id_type'] 	: "";
		$nama_kategori 	= isset($_REQUEST['nama_kategori'])? $_REQUEST['nama_kategori'] 	: "";
		$halaman = permalink(strtolower($nama_kategori),"_");
		$tblnya 	= $tpref."products_categories";
		if(!empty($parent_id)){  $depth = $db->fob("SERI",$tblnya,"WHERE ID_PRODUCT_CATEGORY='".$parent_id."'")+1; }else{ $depth='1'; }
		
		$enc	= substr(md5(rand(0,100)),0,8);
		$seri 	= $db->last("SERI",$tblnya,"WHERE SERI='".$depth."'")+1;
		$content = array(1=>
					array("ID_PRODUCT_TYPE",$id_type),
					array("ID_CLIENT",$_SESSION['cidkey']),
					array("SERI",$seri),
					array("TITLE",ucwords($nama_kategori)),
					array("NAME",ucwords($nama_kategori)),
					array("PAGE",$halaman),
					array("ID_PARENT",@$parent_id),
					array("STATUS",@$status),
					array("BY_ID_USER",$_SESSION['uidkey']),
					array("TGLUPDATE",date("Y-m-d G:i:s")));
		$db->insert($tblnya,$content);
		$new_id_type 	= mysql_insert_id();
		$result['msg'] 	= "berhasil";
		$result['value']= $new_id_type;
		echo json_encode($result);
	}
}
?>