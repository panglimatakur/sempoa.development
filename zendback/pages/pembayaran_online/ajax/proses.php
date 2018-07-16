<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 			= isset($_POST['direction'])  		? $_POST['direction']  		: "";
	$id_deal 	  		= isset($_POST['id_deal']) 			? $_POST['id_deal'] 		: "";
	$st 		   		= isset($_POST['st']) 		 		? $_POST['st'] 		 		: "";
	$send_st_cust 		= isset($_POST['send_st_cust'])  	? $_POST['send_st_cust'] 	: "";
	$send_st_user 		= isset($_POST['send_st_user']) 	? $_POST['send_st_user'] 	: "";	
	if($direction == "set_status"){
		switch($st){
			case "0":
				$status_cart 	= '0';
				$status_purch 	= '0';
			break;
			case "2":
				$status_cart 	= '2';
				$status_purch 	= '0';
			break;
			case "3":
				$status_cart 	= '3';
				$status_purch 	= '3';
			break;
			case "4":
				$status_cart 	= '3';
				$status_purch 	= '4';
			break;
			case "6":
				$status_cart 	= '3';
				$status_purch 	= '5';
			break;
		}
		
		//$db->query("UPDATE ".$tpref."customers_purchases SET PAID_STATUS = '".$status_purch."',PAID_DATETIME = '".$tglupdate." ".$wktupdate."' WHERE ID_PURCHASE='".$id_deal."'");
		//$db->query("UPDATE ".$tpref."customers_carts SET STATUS = '".$status_cart."' WHERE ID_PURCHASE = '".$id_deal."' ");
		
		
		if($st == "3"){
		
		//INFORMASI PURCHASING
		$pur_string   	= "SELECT * FROM ".$tpref."customers_purchases WHERE ID_PURCHASE 	= '".$id_deal."' ORDER BY ID_PURCHASE ASC";
		$q_purchase 	= $db->query($pur_string);
		$dt_purchase   	= $db->fetchNextObject($q_purchase);
		$id_merchant   	= $dt_purchase->ID_CLIENT;
		$id_customer   	= $dt_purchase->ID_CUSTOMER;
		$total_purchase	= $dt_purchase->PAID;
		$payment_code	= $dt_purchase->PAYMENT_CODE; 
		$unique_code	= $dt_purchase->UNIQUE_CODE; 
		$alamat_pembeli	= $dt_purchase->TO_ADDRESS;
		$cart_list	 	= $dt_purchase->ID_CARTS;
		$id_carts	  	= explode(",",$cart_list);
		//END OF INFORMASI PURCHASING
		
		//INFORMASI KONSUMEN
		$q_cust			= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL,CUSTOMER_PHONE FROM ".$tpref."customers  WHERE ID_CUSTOMER = '".$id_customer."'");
		$dt_cust   	   	= $db->fetchNextObject($q_cust);
		@$email_cust  	= $dt_cust->CUSTOMER_EMAIL;
		@$nm_customer  	= $dt_cust->CUSTOMER_NAME;
		@$phone_customer= $dt_cust->CUSTOMER_PHONE;
		$cust_add		= customer_address($id_customer);
		$alamat_customer= $cust_add['alamat']." ".$cust_add['kelurahan']." ".$cust_add['kecamatan']." ".$cust_add['kota']." ".$cust_add['propinsi'];
		if(empty($nm_customer) || $nm_customer == "unknown"){ $nm_customer = "Seseorang"; }
		//END OF INFORMASI KONSUMEN

		//INFORMASI PENJUAL / MERCHANT
		@$q_merchant	  = $db->query("SELECT CLIENT_NAME,CLIENT_ADDRESS,CLIENT_LOGO,CLIENT_EMAIL FROM ".$tpref."clients WHERE ID_CLIENT='".$id_merchant."'");
		$dt_merchant 	  = $db->fetchNextObject($q_merchant);
		@$nm_merchant	  = $dt_merchant->CLIENT_NAME;
		@$alamat_merchant = $dt_merchant->CLIENT_ADDRESS;
		$email_merchant	  = $dt_merchant->CLIENT_EMAIL;
		@$logo_merchant	  = $dt_merchant->CLIENT_LOGO;
		//END OF INFORMASI PENJUAL / MERCHANT

		//=====================ARRANGE EMAIL CONTENT NOTIFICATION=============================//
		$email_admin	= "thetakur@gmail.com";
		$subject 		= "[PENTING] Info Pemesanan ".$payment_code;
		$from		   	= "Info Discoin Community <info@".$website_name.">";
		$type		   	= "html";
								
		$cart_string   	= "SELECT * FROM ".$tpref."customers_carts WHERE STATUS = '3' AND ID_CUSTOMER = '".$id_customer."' AND ID_PURCHASE = '".$id_deal."' ORDER BY ID_CART DESC";
		
		$q_cart 	   	= $db->query($cart_string);
		//CART EMAIL CONTENT//
		$image_list		= "";	
		$deal 		   	= "
		<style type='text/css'>
			#tbl_data{
				border:1px solid #CCCCCC;	
				width:100%;
				border-radius:4px;
				-moz-border-radius:4px;
				-webkit-border-radius:4px;
			}
			#tbl_data td{ font-size:12px; text-align:left; }
			#tbl_data .tcontent td{ padding:3px; }
		</style>
			
		<table id='tbl_data'>
			<tr>
			  <td valign='top' style='padding:10px; text-align:center' colspan='3'> 
				<div style='width:10%; float:left; height:70%; overflow:hidden; margin:2% 4px 2% 0' class='merchant_logo'>";
				if(is_file($basepath."/files/images/logos/".$logo_merchant)){
	  $deal .= '
					<img src="'.$dirhost.'/files/images/logos/'.$logo_merchant.'" width="100%"/>';
				}
	  $deal .= "
				</div>
				<h1 style='float:left;'>".@$nm_merchant."</h1>&nbsp;
				<br clear='all'>
			  </td>
			</tr>";
		while($dt_cart = $db->fetchNextObject($q_cart)){
				$del_flag	   = $dt_cart->DELIVERY_FLAG;
				if($del_flag == 1){ $del_flag_cat = "DI ANTAR"; }
				// JIKA ISI CART PEMBELIAN PRODUCT
				if(!empty($dt_cart->ID_PRODUCT)){
					$id_product 	= $dt_cart->ID_PRODUCT;
					$id_merchant    = $dt_cart->ID_CLIENT;
					$hrg_product	= $dt_cart->PRICE;
					$discount 		= ch_diskon($id_merchant,$id_merchant);
					$product        = get_product_info($id_product);
					@$unit_product   = $product['unit'];
					$image_list   .="
					<div class='img_box'>
						".$dt_cart->ID_PRODUCT." <br> ".@$product['name']."
						<div class='img_box_inline'>
							".@$product['photo']."
						</div>
					</div>";
					$jml_prod	  = 1;
				}
				
		  $deal .= "
		  <tr>";
			if($jml_prod == 1){
		  $deal .= "
			<td width='15%'  style='text-align:center' valign='top' style='padding:4px;'>
				".@$image_list."
			</td>";
			}
		  $deal .= "
			<td align='center' style='vertical-align:top; padding-right:5px'>
			  <table width='98%' style='margin-left:5px'>";
				if($jml_prod > 1){
				$deal .= "					
					<tr>
					  <td colspan='2' valign='top' style='text-align:center' >
					  ".@$image_list."
					  </td>
					</tr>";
				} 
				$deal .= "					
				<tr>
				  <td valign='top'><b>Harga</b></td>
					<td valign='top'>				
						<b>".money('Rp.',$dt_cart->PRICE)." / ".$unit_product."</b>
					</td>
				</tr>
				<tr>
				  <td><b>Jumlah</b></td>
				  <td>
					<span id='current_deal'>".@$dt_cart->AMOUNT."</span> ".@$unit_product." 
				  </td>
				</tr>
				<tr>
				  <td><b>Diskon (%)</b></td>
				  <td>".$discount['discount']."%</td>
				</tr>
				<tr>
				  <td valign='top' ><b>Total</b></td>
				  <td valign='top' class='code'>".money('Rp.',@$dt_cart->TOTAL_PRICE)."</td>
				</tr>
			  </table>
			</td>
		  </tr>";
		} 
		$deal .= "
		</table>";
		//END OF CART EMAIL CONTENT//
		
			$msg 			= "
			<div style='font-family:Verdana, Geneva, sans-serif;'>
				Dear Admin ".@$nm_merchant.",<br>
				".$nm_customer." sudah melakukan perlunasan pembayaran sebesar <b class='code'>".money("Rp.",$total_purchase)."</b> ke salah satu informasi rekening bersama Sempoa, untuk pembelian produk-produk ".$nm_merchant." dibawah ini;
				<br><br>
					".$deal." 
				<br><br>
				Mohon segera dilakukan proses pengiriman ke alamat dibawah ini : <br>
				".$alamat_pembeli." <br>
				Sebagai Bahan Konfirmasi, anda bisa menghubungi Informasi Konsumen dibawah ini :<br>
				Nama 	: ".$nm_customer."<br>
				Alamat 	: ".$alamat_customer."<br>
				No Tlp	: ".$phone_customer."
				<br>
					
				<span style='color:#FF0000'>Catatan : Dari total pembelanjaan diatas, akan dipotong ".money("Rp.",$unique_code)." sebagai biaya donasi untuk Rekening Bersama Sempoa</span>
				<br> 
				<br>
				Setelah anda melakukan pengiriman, mohon konfirmasikan ke akun sempoa anda, di alamat <a href='".$dirhost."/?page=pemesanan_online'>Pemesanan Online</a>, yang terdapat pada menu \"Transaksi -> Pemesanan Online\", yang langkah-langkahnya akan di tampilkan dihalaman tersebut<br><br>
				
				Terimakasih, atas kerjasamanya.
				<br><br>
				<img src='".$logo_path."'><br>
				
			</div>";
			//echo $msg;
			if($send_st_user == '1'){	
				sendmail(trim($email_merchant),$subject,$msg,$from,$type);
			}
			sendmail(trim($email_admin),$subject,$msg,$from,$type);
			//echo trim($email_coin)."<br>".$subject."<br>".$msg."<br>".$from."<br>".$type."<br>";
		
			if($send_st_cust == '1'){	
				$msg_2 		  = "
				<div style='font-family:Verdana, Geneva, sans-serif;'>
					Dear ".@$nm_customer.",<br>
					Pembayaran anda sudah diterima, silahkan ditunggu untuk pemesanan anda kami antarkan dalam selambat-lambatnya 1x24 jam;
					<br> 
					<br>
					".$deal."
					<br>
					<br>
					Terimakasih atas kepercayaannya kepada kami.<br> 
					<br>
					<img src='".$logo_path."'><br>
					
				</div>	";
				//echo $msg_2;		
				sendmail(trim($email_cust),$subject,$msg_2,$from,$type);
			}

		}
	}
	
	
}
?>