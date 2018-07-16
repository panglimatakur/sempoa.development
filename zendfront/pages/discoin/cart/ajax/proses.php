<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../../includes/config.php");
	include_once("../../../../../includes/classes.php");
	include_once("../../../../../includes/functions.php");
	include_once("../../../../../includes/declarations.php");
	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$id_product 	= isset($_REQUEST['id_product']) 	? $_REQUEST['id_product'] 	: "";
	$id_customer 	= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 	: "";
	$jumlah_order 	= isset($_REQUEST['jumlah_order']) 	? $_REQUEST['jumlah_order'] : "";
	$total_price 	= isset($_REQUEST['total_price']) 	? $_REQUEST['total_price'] 	: "";
	
	if((!empty($direction) && $direction == "save_cart")){
		$cart_content 		= array(1=>
								array("AMOUNT",$jumlah_order),
								array("TOTAL_PRICE",@$total_price));
		$db->update($tpref."customers_carts",$cart_content," WHERE ID_PRODUCT = '".$id_product."' AND ID_CUSTOMER = '".$_SESSION['customer_id']."'");
	}
	if((!empty($direction) && $direction == "cancel_cart")){ 
		$id_cart 	= isset($_REQUEST['id_cart']) 	? $_REQUEST['id_cart'] 	: "";
		$db->delete($tpref."customers_carts"," WHERE ID_CART = '".$id_cart."'");
	}
	if((!empty($direction) && $direction == "purchase_cart")){ 
		$id_cart 		  = isset($_REQUEST['id_cart']) 		? $_REQUEST['id_cart'] 			: "";
		$id_merchant 	  = isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] 	: "";
		$list_id_cart 	  = isset($_REQUEST['list_id_cart']) 	? $_REQUEST['list_id_cart'] 	: "";
		
		//INFORMASI BRAND MERCHANT//
		@$q_merchant	  = $db->fob("SELECT CLIENT_NAME,CLIENT_ADDRESS,CLIENT_LOGO FROM ".$tpref."clients WHERE ID_CLIENT='".$id_merchant."'");
		$dt_merchant 	  = $db->fetchNextObject($q_merchant);
		@$nm_merchant	  = $dt_merchant->CLIENT_NAME;
		@$alamat_merchant = $dt_merchant->CLIENT_ADDRESS;
		@$logo_merchant	  = $dt_merchant->CLIENT_LOGO;
		//END OF INFORMASI BRAND MERCHANT//

		//CODE UNIQUE//	
		$jml_code 		 = $db->recount("SELECT ID_PURCHASE FROM ".$tpref."customers_purchases WHERE PAID_STATUS = '0'");	
		if($jml_code < 999){
			$unique_code     = $db->last("UNIQUE_CODE",$tpref."customers_purchases"," WHERE PAID_STATUS = '0' AND ID_CLIENT = '".$id_merchant."'");
			if(empty($unique_code)){ $unique_code = '100'; }else{ $unique_code = $unique_code+1; }
		}else{
			$unique_code     = $db->last("UNIQUE_CODE",$tpref."customers_purchases"," WHERE PAID_STATUS = '3' AND ID_CLIENT = '".$id_merchant."'");
		}
		$payment_rand	 = $_SESSION['customer_id'].rand(0,2000000);
		$payment_code     = strtoupper(substr(str_shuffle(md5($payment_rand)),0,4)).$unique_code;
		//END OF CODE UNIQUE//
		
		//INFORMASI CUSTOMER//
		$q_customer 	= $db->query("SELECT CUSTOMER_EMAIL,CUSTOMER_NAME FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$_SESSION['customer_id']."'");
		$total_price	= $total_price+$unique_code;
		$dt_customer 	= $db->fetchNextObject($q_customer);
		$email_customer = $dt_customer->CUSTOMER_EMAIL;
		$name_customer 	= $dt_customer->CUSTOMER_NAME;
		//END OF INFORMASI CUSTOMER//
		
		//SAVE PURCHASING//
		$container 		= array(1=>
							array("ID_CLIENT",$id_merchant),
							array("ID_CUSTOMER",$_SESSION['customer_id']),
							array("ID_CARTS",",".$list_id_cart),
							array("PAYMENT_CODE",$payment_code),
							array("UNIQUE_CODE",$unique_code),
							array("PAID",$total_price),
							array("PAID_STATUS","0"),
							array("DELIVERY_FEE",@$biaya_antar),
							array("TGLUPDATE",@$tglupdate." ".$wktupdate));
		$db->insert($tpref."customers_purchases",$container);
		$id_purchase	 = mysql_insert_id();
		//END OF SAVE PURCHASING//
		
		//UPDATE CART//
		$id_carts 		= explode(",",$list_id_cart);
		foreach($id_carts as &$id_cart){
			if(!empty($id_cart)){
				$container 	= array(1=>array("STATUS","3"),
									   array("ID_PURCHASE",$id_purchase));
				$db->update($tpref."customers_carts",$container," WHERE ID_CUSTOMER = '".$_SESSION['customer_id']."' AND ID_CART = '".$id_cart."'");
			}
		}
		//END OF UPDATE CART//

		echo "<div class='alert alert-success' style='margin:10px'>Terimakasih <b>".@$name_customer."</b>, Informasi pemesanan anda berhasil di proses, silahkan lakukan pembayaran ke rekening yang di kirimkan melalui email <b>".$email_customer."</b>, dan lakukan proses pembayaran seperti yang tertera di email anda tersebut<br><br>
		
		Terimakasih atas kepercayaannya.
		</div>";
		
		//SEND EMAIL NOTIFIKASI//
		$email_admin	= "thetakur@gmail.com";
		$subject 		= "PENTING!! Info Pemesanan ".$payment_code;
		$from		   	= "Info Discoin Community <info@sempoa.biz>";
		$type		   	= "html";
		$deal 		   = "
		<style type='text/css'>
			#tbl_data{
				border:1px solid #CCCCCC;	
				width:97%;
				margin:10px
			}
			#tbl_data .tcontent{
				border:1px solid #CCCCCC;	
				border-radius:4px;
				-moz-border-radius:4px;
				-webkit-border-radius:4px;
				background:#F4F4F4;
			}
			#tbl_data td{ font-size:2vmin; }
			#tbl_data .tcontent td{ padding:3px; }
		</style>
			
	    <table id='tbl_data'>
			<tr>
			  <td valign='top'>
				<div style='width:10%; float:left; height:70%; overflow:hidden; margin:2% 4px 2% 0
				 class='merchant_logo'>";
				if(is_file($basepath."/files/images/logos/".$logo_merchant)){
	  $deal .= '
					<img src="'.$dirhost.'/files/images/logos/'.$logo_merchant.'" width="100%"/>';
				}
	  $deal .= "
				</div>
				".@$nm_merchant."&nbsp;<br>
			  </td>
			</tr>
		";
	    $list_cart = "";
		$cart_string   	= "SELECT * FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$_SESSION['customer_id']."' AND ID_PURCHASE = '".$id_purchase."' ORDER BY ID_CART DESC";
		//echo $deal_string;
		$q_cart 	   	= $db->query($cart_string);
		while($dt_cart = $db->fetchNextObject($q_cart)){
				$list_cart	  	.= ";".$dt_cart->ID_CART.",";
				$mode		   	= "";
				$hrg_product 	= "";
				$new_price  	= "";
				$new_disc	   	= "";
				$image_list	 	= "";
				$image_array	= "";
				$del_flag	   	= $dt_cart->DELIVERY_FLAG;
				if($del_flag == 1){ $del_flag_cat = "DI ANTAR"; }
				// JIKA ISI CART PEMBELIAN PRODUCT
				if(!empty($dt_cart->ID_PRODUCT)){
					$id_product 	 = $dt_cart->ID_PRODUCT;
					$id_merchant    = $dt_cart->ID_CLIENT;
					$product        = get_product_info($id_product);
					$unit_product   = $product['unit'];
					$hrg_product	= $dt_cart->PRICE;
					$image_list   .="
					<div class='img_box'>
						".$product['name']."
						<div class='img_box_inline'>
							".$product['photo']."
						</div>
					</div>";
					$jml_prod	  = 1;
				}
				
				@$q_merchant	  = $db->query("SELECT CLIENT_NAME,CLIENT_ADDRESS FROM ".$tpref."clients WHERE ID_CLIENT='".$id_merchant."'");
				$dt_merchant 	  = $db->fetchNextObject($q_merchant);
				@$nm_merchant	  = $dt_merchant->CLIENT_NAME;
				@$alamat_merchant = $dt_merchant->CLIENT_ADDRESS;
				//INFORMASI PRODUCT	
		  $deal .= "
    	  <tr>";
    	    if($jml_prod == 1){
          $deal .= "
	   		<td width='15%'  align='center' valign='top' style='padding:4px;'>
          		".$image_list."
            </td>";
		 	}
       	  $deal .= "
    	  	<td align='center' style='vertical-align:top; padding-right:5px'>
              <table width='98%' style='margin-left:5px'>";
              	if($jml_prod > 1){
	   			$deal .= "					
					<tr>
					  <td colspan='2' valign='top'>
					  ".$image_list."
					  </td>
					</tr>";
                } 
	   			$deal .= "					
                <tr>
                  <td valign='top'>Harga</td>
                    <td valign='top'>					
                        <b>".money('Rp.',$dt_cart->PRICE)."</b>
                    </td>
                </tr>
                <tr>
                  <td >Jumlah Dibeli</td>
                  <td >
                  	<span id='current_deal'>".@$dt_cart->AMOUNT."</span> ".@$unit_product." 
                  </td>
                </tr>
				<tr>
                  <td valign='top' >Total Bayar</td>
                  <td valign='top' class='code'>".money('Rp.',@$dt_cart->TOTAL_PRICE)."</td>
                </tr>
              </table>
          	</td>
   	  	  </tr>";
        } 
		$deal .= "
		</table>";
			
		$bank_account 	= "
		<table style='width:98%; margin:6px'>";
		$q_rek 	= $db->query("SELECT * FROM system_master_bank_info ORDER BY BANK_NAME ASC");
		while($dt_rek	= $db->fetchNextObject($q_rek)){
			$bank_account 	.= "
			<tr>
				<td width='30%'>Nama Bank</td>
				<td width='70%'>: <b>".$dt_rek->BANK_NAME."</b></td>
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

		$msg 			= "
		<div style='font-family:Verdana, Geneva, sans-serif;'>
			Dear ".@$name_customer.",<br>
			Informasi pemesanan item untuk ".$nm_merchant." sudah berhasil disimpan, dan kurang dari 2x24 Jam, akan segera diproses dengan Kode Pembayaran <b>".$payment_code."</b>;
			<br>
				".$deal." 
			<br><br>
			Mohon lakukan pembayaran sebesar <b class='code'>".money("Rp.",$total_price)."</b> ke salah satu informasi rekening dibawah ini;
			<br>
				".$bank_account."
			
			<span style='color:#FF0000'>Catatan : ".money("Rp.",$unique_code)." adalah kode Unik pembayaran anda, untuk mempermudah proses konfirmasi, tanpa harus mengkonfirmasikan pembayaran anda</span>
			<br>
			<br>
			Terimakasih<br><br>
			<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
			<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
		</div>	";
		//echo $msg;
		sendmail(trim($email_customer),$subject,$msg,$from,$type);
		sendmail(trim($email_admin),$subject,$msg,$from,$type);
		
	}
	if((!empty($direction) && $direction == "regist_new_customer")){
		$new_email 	= isset($_REQUEST['new_email']) ? $_REQUEST['new_email'] : "";
		$new_nama 	= isset($_REQUEST['new_nama']) ? $_REQUEST['new_nama'] : "";
		$new_pass 	= isset($_REQUEST['new_pass']) ? $_REQUEST['new_pass'] : "";
		if($validate->email($new_email) == "1"){
			$id_customer 	 = $db->fob("ID_CUSTOMER",$tpref."customers"," WHERE CUSTOMER_EMAIL = '".$new_email."' AND ID_CLIENT IS NULL");
			if(empty($ch_customer)){
				$cart_content 		= array(1=>
										array("CUSTOMER_EMAIL",$new_email),
										array("CUSTOMER_PASS",$new_pass),
										array("CUSTOMER_NAME",$new_nama),
										array("TGLUPDATE",$tglupdate));
				$db->insert($tpref."customers",$cart_content);
				$_SESSION['customer_id'] = mysql_insert_id();
			}else{
				$_SESSION['customer_id'] = $id_customer;
			}
		}else{
			echo "null";	
		}
	}
}
?>