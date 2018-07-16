<?php
session_start();
//if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../../includes/config.php");
include_once("../../../../../includes/classes.php");
include_once("../../../../../includes/functions.php");
include_once("../../../../../includes/declarations.php");

	$direction 	  	= isset($_REQUEST['direction']) 	 ? $_REQUEST['direction'] 	: "";
	$id_deal 		= isset($_REQUEST['id_deal']) 		 ? $_REQUEST['id_deal'] 	: "";
	$jml_voucher 	= isset($_REQUEST['jml_voucher']) 	 ? $_REQUEST['jml_voucher'] : "";
	$sub_total 		= isset($_REQUEST['sub_total']) 	 ? $_REQUEST['sub_total'] 	: "";
	$callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	
	if(!empty($direction) && $direction == "save_additional"){
		$id_product = isset($_REQUEST['id_product']) ? $_REQUEST['id_product'] : "";
		
		if(!empty($id_product)){
			
			$jumlah_order   = 1;
			$q_product	  	= $db->query("SELECT ID_CLIENT,NAME,SALE_PRICE FROM ".$tpref."products WHERE ID_PRODUCT = '".$id_product."'");
			$dt_product	 	= $db->fetchNextObject($q_product);
			@$id_merchant 	= $dt_product->ID_CLIENT;
			$nm_product	 	= $dt_product->NAME;
			@$real_price 	= $dt_product->SALE_PRICE;
			@$pr_price	   	= $dt_product->SALE_PRICE;
			@$ttl_price  	= $pr_price*$jumlah_order;
			
			$ch_order 	 = $db->recount("SELECT ID_PRODUCT FROM ".$tpref."customers_carts WHERE ID_PRODUCT = '".$id_product."' AND ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0'");	
			if($ch_order == 0){
				$container 		= array(1=>
										array("ID_CLIENT",$id_merchant),
										array("ID_PRODUCT",$id_product),
										array("ID_DISCOUNT",$discount['id_discount']),
										array("ID_CUSTOMER",$_SESSION['sidkey']),
										array("PRICE",@$real_price),
										array("AMOUNT",$jumlah_order),
										array("DISCOUNT",@$discount['discount']),
										array("TOTAL_PRICE",@$ttl_price),
										array("STATUS","0"),
										array("TGLUPDATE",$tglupdate." ".$wktupdate));
				$db->insert($tpref."customers_carts",$container);
			}
			
		}
		$result["content"] = "<b style='#FF0000'>Disimpan...</b>";
		echo $callback.'('.json_encode($result).')';
	}
	if(!empty($direction) && $direction == "cancel_deal"){
		$deal_string   = $db->delete($tpref."customers_carts","WHERE ID_CART = '".$id_deal."' AND ID_CUSTOMER = '".$_SESSION['sidkey']."'"); 
		$ttl_cart 		= $db->sum("TOTAL_PRICE",$tpref."customers_carts","WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS ='0'");

		$result['ttl_cart']  		= money("Rp.",$ttl_cart);
		$result['ttl_cart_data']  	= $ttl_cart;
		$result['del_add_prod']		= "2";
		$str_main 					= "
			SELECT 
				a.ID_CART 
			FROM 
				".$tpref."customers_carts a,
				".$tpref."products b
			WHERE 
				a.ID_CUSTOMER = '".$_SESSION['sidkey']."' 	AND 
				a.STATUS = '0'		 						AND 
				a.ID_PRODUCT = b.ID_PRODUCT					AND
				b.ADDITIONAL_PRODUCT != '1' "; 
		$ch_main_product = $db->recount($str_main);

		if($ch_main_product == 0){ 
			$result['del_add_prod'] = "1"; 
			
			$deal_string   = $db->delete($tpref."customers_carts","WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0'"); 
		}
		echo $callback.'('.json_encode($result).')';
	}
	
	if(!empty($direction) && $direction == "save_jml"){
		$q_product 	= $db->query("SELECT 
									a.ID_PRODUCT,b.ADDITIONAL_PRODUCT
								  FROM 
									".$tpref."customers_carts a,
									".$tpref."products b
								  WHERE 
								  	a.ID_CART = '".$id_deal."' AND
									a.ID_PRODUCT = b.ID_PRODUCT");
	    $dt_product 		= $db->fetchNextObject($q_product);
		$additional_product = $dt_product->ADDITIONAL_PRODUCT;
		$total_price		= $dt_cart->PRICE*$jml_voucher;
		$sql_update = "UPDATE 
							".$tpref."customers_carts 
					   SET 
					   		AMOUNT 		= '".$jml_voucher."',
							TOTAL_PRICE = '".$total_price."' 
					   WHERE 
					   		ID_CUSTOMER = '".$_SESSION['sidkey']."' AND 
							ID_CART = '".$id_deal."'";
		$db->query($sql_update);
		
		$q_cart 		= $db->query("SELECT SUM(TOTAL_PRICE) AS TOTAL_CART,TOTAL_PRICE FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS ='0'"); 
		$dt_cart 	   	= $db->fetchNextObject($q_cart);
		$ttl_price 	 	= $dt_cart->TOTAL_PRICE;
		$ttl_cart  	  	= $dt_cart->TOTAL_CART;
		
		$result['ttl_price'] 		= money("Rp.",$sub_total);
		$result['ttl_cart']  		= money("Rp.",$ttl_cart);
		$result['ttl_cart_data']  	= $ttl_cart;
		echo $callback.'('.json_encode($result).')';
		
	}
	
	if(!empty($direction) && $direction == "purchase"){
		
	  	$saldo  	= $db->last("SALDO",$tpref."savings"," WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND ID_CONFIG = '14' ORDER BY ID_SAVING DESC");
		if($saldo > $total_price){
		
		$deliveries	   	= isset($_REQUEST['deliveries'])  	? $_REQUEST['deliveries']    	: "";
		$delivery_fees	= isset($_REQUEST['delivery_fees']) ? $_REQUEST['delivery_fees'] 	: "";
		$payment_code	= isset($_REQUEST['payment_code'])  ? $_REQUEST['payment_code']  	: "";
		$total_bayar	= isset($_REQUEST['total_bayar']) 	? $_REQUEST['total_bayar'] 		: "";
		$id_merchant	= isset($_REQUEST['id_merchant'])  	? $_REQUEST['id_merchant']  	: "";
		
		$paket_kirim	= isset($_REQUEST['paket_kirim'])   ? $_REQUEST['paket_kirim']    	: "";
		$id_propinsi	= isset($_REQUEST['id_propinsi'])   ? $_REQUEST['id_propinsi']  	: "";
		$id_kota		= isset($_REQUEST['id_kota'])  		? $_REQUEST['id_kota']  		: "";
		$id_kecamatan	= isset($_REQUEST['id_kecamatan'])  ? $_REQUEST['id_kecamatan']  	: "";
		//$id_kelurahan	= isset($_REQUEST['id_kelurahan'])  ? $_REQUEST['id_kelurahan']  	: "";
		$alamat	   	    = isset($_REQUEST['alamat'])    	? $_REQUEST['alamat']    		: "";
		$keterangan		= isset($_REQUEST['keterangan']) 	? $_REQUEST['keterangan'] 		: "";

		//$id_kurir	    = isset($_REQUEST['id_kurir'])      ? $_REQUEST['id_kurir']      	: "";
		$biaya_antar	= isset($_REQUEST['biaya_antar'])   ? $_REQUEST['biaya_antar']   	: "";
		$discount_info 	= ch_diskon($id_merchant,$_SESSION['csidkey']);
		//CODE UNIQUE//	
		$jml_code 		 = $db->recount("SELECT ID_PURCHASE FROM ".$tpref."customers_purchases WHERE PAID_STATUS = '0'");	
		if($jml_code < 999){
			$unique_code     = $db->last("UNIQUE_CODE",$tpref."customers_purchases"," WHERE PAID_STATUS = '0' AND ID_CLIENT = '".$id_merchant."'");
			if(empty($unique_code)){ $unique_code = '100'; }else{ $unique_code = $unique_code+1; }
		}else{
			$unique_code     = $db->last("UNIQUE_CODE",$tpref."customers_purchases"," WHERE PAID_STATUS = '3' AND ID_CLIENT = '".$id_merchant."'");
		}
		$payment_rand	 = $_SESSION['sidkey'].rand(0,2000000);
		$payment_code     = strtoupper(substr(str_shuffle(md5($payment_rand)),0,4)).$unique_code;
		//END OF CODE UNIQUE//
		$list_carts	= "";
		$q_cart_client = $db->query("SELECT ID_CART FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0' AND ID_CLIENT = '".$id_merchant."'");
		while($dt_cat_client = $db->fetchNextObject($q_cart_client)){
			$list_carts .= $dt_cat_client->ID_CART.",";	
		}
		$total_price = $total_bayar+$unique_code;
		$paid_status = '0'; 
		/*if(!empty($discount_info['pre_order_status']))	{ $paid_status = '1'; }*/ 
		$container 		= array(1=>
							array("ID_CLIENT",@$id_merchant),
							array("ID_CUSTOMER",$_SESSION['sidkey']),
							array("ID_CARTS",",".@$list_carts),
							array("PAYMENT_CODE",@$payment_code),
							array("UNIQUE_CODE",@$unique_code),
							array("PAID",@$total_price),
							array("PAID_STATUS","3"),
							array("DELIVERY_FEE",@$biaya_antar),
							array("DELIVERY_TYPE",@$paket_kirim),
							array("TO_ID_PROVINCE",@$id_propinsi),
							array("TO_ID_CITY",@$id_kota),
							array("TO_ID_DISTRICT",@$id_kecamatan),
							array("ADDITIONAL_INFO",@$keterangan),
							array("TO_ADDRESS",@$alamat),
							array("TGLUPDATE",@$tglupdate." ".$wktupdate));
		$db->insert($tpref."customers_purchases",$container);
		$id_purchase	 = mysql_insert_id();
		
		$new_saldo 		= $saldo - $total_price;
		$container 		= array(1=>
		  array("ID_CONFIG","14"),
		  array("ID_CUSTOMER",$_SESSION['sidkey']),
		  array("ID_IN_OUT","2"),
		  array("DEBT",$total_price),
		  array("SALDO",$new_saldo),
		  array("TGLUPDATE",$tglupdate));	
		$db->insert($tpref."savings",$container);

		$container 		= array(1=>
			 array("ID_CLIENT",@$_SESSION['csidkey']),
			 array("ID_CUSTOMER",@$_SESSION['sidkey']),
			 array("ID_IN_OUT","2"),
			 array("PAYMENT",$total_price),
			 array("ID_PAYMENT_SOURCE","3"),
			 array("PAYMENT_DATETIME",$tglupdate." ".$wktupdate),
			 array("PAYMENT_STATUS","3"),
			 array("SALDO",$new_saldo),
			 array("TGLUPDATE",$tglupdate));		
		$db->insert($tpref."customers_payment_history",$container);
		
		
		
		@$nm_propinsi	= $db->fob("NAME","system_master_location"," WHERE ID_LOCATION = '".$id_propinsi."' AND PARENT_ID = '0'");
		@$nm_kota		= $db->fob("NAME","system_master_location"," WHERE PARENT_ID = '".$id_propinsi."' ORDER BY NAME ASC");
		@$nm_kecamatan	= $db->fob("NAME","system_master_location"," WHERE PARENT_ID = '".$id_kota."' ORDER BY NAME ASC");
	//	@$nm_kelurahan	= $db->fob("NAME","system_master_location"," WHERE PARENT_ID = '".$id_kecamatan."' ORDER BY NAME ASC");

		$is_deliver	  		= "";
		@$is_deliver 	  	= in_array($id_merchant,$deliveries);
		if($is_deliver >0){ $deliver_flag = '1'; }
		$container 	   = array(1=>
							//array("DELIVERY_FLAG",@$is_deliver),
							array("STATUS","3"),
							array("ID_PURCHASE",$id_purchase));
		$db->update($tpref."customers_carts",$container," WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0' AND ID_CLIENT = '".$id_merchant."'");				
		//=========================================ARRANGE EMAIL CONTENT NOTIFICATION=============================//
		
		@$q_merchant	  = $db->query("SELECT CLIENT_NAME,CLIENT_ADDRESS,CLIENT_LOGO FROM ".$tpref."clients WHERE ID_CLIENT='".$id_merchant."'");
		$dt_merchant 	  = $db->fetchNextObject($q_merchant);
		@$nm_merchant	  = $dt_merchant->CLIENT_NAME;
		@$alamat_merchant = $dt_merchant->CLIENT_ADDRESS;
		@$logo_merchant	  = $dt_merchant->CLIENT_LOGO;
		
		//INFORMASI CUSTOMER
		$query_email 	= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' ");
		$num_email	  	= $db->numRows($query_email);
		$dt_cust		= $db->fetchNextObject($query_email);
		@$nama_coin	 	= $dt_cust->CUSTOMER_NAME;
		@$email_coin	= $dt_cust->CUSTOMER_EMAIL;
		$email_admin	= "thetakur@gmail.com";
		$subject 		= "[PENTING] Info Pemesanan ".$payment_code;
		$from		   	= "Info Discoin Community <info@sempoa.biz>";
		$type		   	= "html";
		
		$cart_string   	= "SELECT * FROM ".$tpref."customers_carts WHERE STATUS = '3' AND ID_CUSTOMER = '".$_SESSION['sidkey']."' AND ID_PURCHASE = '".$id_purchase."' ORDER BY ID_CART DESC";
		$q_cart 	   	= $db->query($cart_string);
		//CART EMAIL CONTENT//
		$deal 		   	= "
		<style type='text/css'>
			#tbl_data{
				border:1px solid #CCCCCC;	
				width:100%;
				border-radius:4px;
				-moz-border-radius:4px;
				-webkit-border-radius:4px;
			}
			#tbl_data td{ font-size:2vmin; text-align:left; }
			#tbl_data .tcontent td{ padding:3px; }
		</style>
			
	    <table id='tbl_data'>
			<tr>
			  <td valign='top' style='padding:10px; text-align:center' colspan='2'> 
				<div style='width:10%; float:left; height:70%; overflow:hidden; margin:2% 4px 2% 0
				 class='merchant_logo'>";
				if(is_file($basepath."/files/images/logos/".$logo_merchant)){
	  $deal .= '
					<img src="'.$dirhost.'/files/images/logos/'.$logo_merchant.'" width="100%"/>';
				}
	  $deal .= "
				</div>
				<h3>".@$nm_merchant."</h3>&nbsp;<br>
			  </td>
			</tr>";
		while($dt_cart = $db->fetchNextObject($q_cart)){
				$mode		   	= "";
				$new_price  	= "";
				$image_list	 	= "";
				$del_flag	   	= $dt_cart->DELIVERY_FLAG;
				if($del_flag == 1){ $del_flag_cat = "DI ANTAR"; }
				// JIKA ISI CART PEMBELIAN PRODUCT
				if(!empty($dt_cart->ID_PRODUCT)){
					
					$id_product 	= $dt_cart->ID_PRODUCT;
					$id_merchant    = $dt_cart->ID_CLIENT;
					$product        = get_product_info($id_product);
					$unit_product   = $product['unit'];
					$hrg_product	= $dt_cart->PRICE;
					$image_list   	.="
					<div class='img_box'>
						<div class='img_box_inline'>
							".$product['photo']."
						</div>
						".$product['name']."
					</div>";
					$jml_prod	  = 1;
				}
				
		  $deal .= "
    	  <tr>";
    	    if($jml_prod == 1){
          $deal .= "
	   		<td width='30%' style='text-align:center' valign='top' style='padding:4px;'>
          		".$image_list."
            </td>";
		 	}
       	  $deal .= "
    	  	<td align='center' style='vertical-align:top; padding-right:5px'>
              <table width='98%' style='margin-left:5px'>";
              	if($jml_prod > 1){
	   			$deal .= "					
					<tr>
					  <td colspan='2' style='text-align:center' valign='top'>
					  ".$image_list."
					  </td>
					</tr>";
                } 
	   			$deal .= "					
                <tr>
                  <td valign='top'><b>Harga</b></td>
                    <td valign='top'>				
                        <b>".money('Rp.',$dt_cart->PRICE)."</b>
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
                  <td>".$dt_cart->DISCOUNT."%</td>
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
		
		//BANK INFO EMAIL CONTENT//	
		$bank_account 	= "
		<table style='width:98%'>";
		$q_rek 	= $db->query("SELECT * FROM system_master_bank_info ORDER BY BANK_NAME ASC");
		while($dt_rek	= $db->fetchNextObject($q_rek)){
		$bank_account 	.= "
			<tr>
				<td width='40%'>Nama Bank</td>
				<td width='60%'>: <b>".$dt_rek->BANK_NAME."</b></td>
			</tr>		
			<tr>
				<td>Nama Rekening</td>
				<td>: ".$dt_rek->BANK_ACCOUNT_NAME."</td>
			</tr>
			<tr>
				<td>Nomor Rekekning</td>
				<td>: ".$dt_rek->BANK_ACCOUNT_NUMBER."</td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
		}
		$bank_account 	.="
		</table>";
		//END OF BANK INFO EMAIL CONTENT//
		
		if(!empty($discount_info['pre_order_status'])){ 
			$retuning 	 = $dtime->tomorrow('7',date('d'),date('m'),date('Y'));
			if(empty($paket_kirim) || $paket_kirim == 2){ $label_po    = "<b>Pre-Order</b>"; }
			if($paket_kirim == 1)						{ $label_po    = "<b>Ataran Kilat</b>"; }
		}
		
		$total_purchase = $total_price + @$biaya_antar;
		$msg 			= "
		<div style='font-family:Verdana, Geneva, sans-serif;'>
			Dear ".@$nama_coin.",<br>
			Informasi pemesanan ".@$label_po." untuk produk dari ".$nm_merchant." sudah berhasil disimpan, sengan Kode Pembayaran <b class='code'>".$payment_code."</b>;
			<br><br>
				".$deal." 
			<br><br>
			<b>Dengan Alamat Antar :</b><br>
			".@$alamat." ".$nm_kecamatan." ".$nm_kota." ".$nm_propinsi."
			<br><br>
			Pembayaran anda telah lunas, pemesanan anda akan diantarkan ke \"Alamat Antar\" anda, yang tertera diatas. silahkan ditunggu...
			<br>
			<br>
			Atas nama ".@$nm_merchant.", kami ucapkan Terimakasih ".@$nama_coin."  
			<br><br>
			<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
			<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
		</div>";
			
		sendmail(trim($email_coin),$subject,$msg,$from,$type);
		sendmail(trim($email_admin),$subject,$msg,$from,$type);
		//send_cust_msg("user","1",$_SESSION['sidkey'],$subject,$msg);	
		$result['msg'] = "<div class='alert alert-success' style='margin:10px; text-align:justify'>Terimakasih <b>".@$nama_coin."</b>, Informasi pemesanan anda berhasil di proses, silahkan lakukan pembayaran ke rekening yang di kirimkan melalui email <b>".$email_coin."</b>, dan lakukan proses pembayaran seperti yang tertera di email anda tersebut<br><br>";
		
		$result['io'] 			= 2;
		$result['new_saldo'] 	= money("Rp.",$new_saldo);
		}else{
			
			$result['io']  = 1;
			$result['msg'] = "Mohon Maaf ".$_SESSION['cust_name'].", Saldo anda tidak mencukupi untuk pembelian ini, silahkan di isi terlebih dahulu saldo anda untuk melanjutkan pembelian ini, atau anda bisa menghapus beberapa item diatas";
		}
		echo $callback.'('.json_encode($result).')';
		
	}
	
	if(!empty($direction) && $direction == "ch_pay"){
		$code 			 = isset($_REQUEST['code']) ? $_REQUEST['code'] : "";
		$purchase_string  = "SELECT * FROM ".$tpref."customers_purchases WHERE PAYMENT_CODE = '".$code."'";
		$q_purchase 	   = $db->query($purchase_string);
		$num_purchase 	 = $db->numRows($q_purchase);
		if($num_purchase > 0){
			$dt_purchase 	  		= $db->fetchNextObject($q_purchase);
			@$new_price['data']     = $dt_purchase->PAID;
			@$new_price['caption']  = money("Rp.",$dt_purchase->PAID);
		}else{
			@$new_price['data']     = "";
			@$new_price['caption']  = "";
		}
		echo $callback.'('.json_encode($new_price).')';
	}
//}
?>
