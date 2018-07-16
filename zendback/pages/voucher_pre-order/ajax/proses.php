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
	$id_product_deal 	= isset($_POST['id_product_deal']) 	? $_POST['id_product_deal'] 				: "";
	$id_customer 		= isset($_POST['id_customer']) 		? $_POST['id_customer'] 					: "";
	if(!empty($direction) && $direction == "refuse_deal"){
		$db->delete($tpref."client_discounts"," WHERE ID_DISCOUNT = '".$id_product_deal."'");
		
		$deal_string   	= "SELECT * FROM ".$tpref."client_discounts WHERE ID_DISCOUNT = '".$id_product_deal."'";
		$q_deal 	   	= $db->query($deal_string);
		$dt_deal 		= $db->fetchNextObject($q_deal);
		$id_deal		= transletNum($dt_deal->ID_DISCOUNT);
		$id_customer 	= $dt_deal->REQUEST_BY_ID_CUSTOMER;
		//INFORMASI CUSTOMER
		$query_email = $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."' ");
		$num_email	= $db->numRows($query_email);
		$dt_cust		= $db->fetchNextObject($query_email);
		@$nama_coin		= $dt_cust->CUSTOMER_NAME;
		@$email_coin	= "thetakur@gmail.com"; //$dt_cust->CUSTOMER_EMAIL;
		//==================================================================
		//INFORMASI MERCHANT
		$merchant_coin	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_client."'");
		//==================================================================
		//INFORMASI PRODUCT
		@$nm_product    = $db->fob("NAME",$tpref."products"," WHERE ID_PRODUCT='".$id_product."'");
		@$photo		    = $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
		//==================================================================		
		 
		$subject 		= "DEAL #".$id_deal." Ditolak";
		if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_client."/thumbnails/".$photo)){
				$img = "<img src='".$dirhost."/".$img_dir."/products/".$id_client."/thumbnails/".$photo."'  style='width:80px;'/>";
			 }else{
				$img = "<img src='".$dirhost."/files/images/no_image.jpg' style='width:80px;'/>";
	    }         
		$msg 			= "
		<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
			Dear ".@$nama_coin.",<br><br>
			Mohon maaf, Deal Diskon dengan informasi deal dibawah ini;
				<table style='100%'>
					<tr>
					  <td width='15%' rowspan='5' align='center' valign='top' style='padding:4px;'>
					  ".$img."
					  </td>
					  <td colspan='2'>ID DEAL : #".$id_deal."</td>
				  </tr>
					<tr>
					  <td width='25%' valign='top'>Nama Product</td>
						<td width='60%' valign='top'>".$nm_product."</td>
					</tr>
					<tr>
					  <td >Diskon (%)</td>
					  <td >".$dt_deal->VALUE."%</td>
					</tr>
					<tr>
					  <td valign='top'>Dealer</td>
					  <td valign='top'>".@$nama_coin."</td>
					</tr>
				</table>				
			Sementara ini belum diberlakukan oleh ".$merchant_coin.", karena beberapa faktor yang menyebabkan deal ini belum diberlakukan, jangan menyerah, silahkan lakukan deal ini dilain waktu, Terimakasih ".@$nama_coin."
			<br>
			<br>
			Terimakasih<br>
			<img src='".$logo_path."'><br>
			
		</div>	";		
		#sendmail(trim($email_coin),$subject,$msg,$from,$type);
		send_cust_msg("user","1",$id_customer,$subject,$msg);
		echo "Terimakasih, Informasi deal ini telah di kirim ke kotak masu COIN <span class='code'><b>".$nama_coin."</b></span>";
	}
	
	if(!empty($direction) && $direction == "create_deal"){
			$container 		= array(1=>
								array("ID_CLIENT",$id_merchant),
								array("ID_PRODUCT",$id_product),
								array("VALUE",$discount),
								array("VOUCHER_NUM",$voucher),
								array("TGLUPDATE",$tglupdate),
								array("WKTUPDATE",$wktupdate));
			$db->insert($tpref."client_discounts",$container);
	}
	
	if(!empty($direction) && $direction == "save_deal"){
		$discount 			= isset($_POST['discount']) 		? $_POST['discount'] 						: "";
		$voucher 			= isset($_POST['voucher']) 			? $_POST['voucher'] 						: "";
		$expired 			= isset($_POST['expired']) 			? $dtime->indodate2date($_POST['expired']) 	: "0000-00-00";
		
		$deal_string   	= "SELECT * FROM ".$tpref."client_discounts WHERE ID_DISCOUNT = '".$id_product_deal."'";
		$q_deal 	   	= $db->query($deal_string);
		$dt_deal 		= $db->fetchNextObject($q_deal);
		
		$nama_dealer 	= $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER = '".$dt_deal->ID_CUSTOMER."'");
		$id_deal		= transletNum($dt_deal->ID_DISCOUNT);
		$array		   = array(";",",");
		$id_product    = str_replace($array,"",$dt_deal->ID_PRODUCTS);
		
		
		//INFORMASI PRODUCT
		$q_product 	   = $db->query("SELECT NAME,SALE_PRICE FROM ".$tpref."products WHERE ID_PRODUCT='".$id_product."'");
		$new_price	   = "0";
		$dt_product	   = $db->fetchNextObject($q_product);
		@$nm_product   = $dt_product->NAME; 
		@$hrg_product  = $dt_product->SALE_PRICE;
		if(!empty($hrg_product)){
			$new_disc	   = ($hrg_product/100)*$dt_deal->DISCOUNT;
			$new_price	   = $hrg_product-$new_disc;
		}
		@$photo		    = $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
		//==================================================================		
		
		$container 		= array(1=>
							array("VALUE",$discount),
							array("VOUCHER_NUM",$voucher),
							array("VOUCHER_PRICE",$new_price),
							array("DISCOUNT_STATUS","3"),
							array("EXPIRATION_DATE",$expired),
							array("TGLUPDATE",$tglupdate." ".$wktupdate));
		$db->update($tpref."client_discounts",$container," WHERE ID_DISCOUNT = '".$id_product_deal."' AND ID_CLIENT='".$id_client."'");		
		
		//INFORMASI CUSTOMER
		$query_email = $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."' ");
		$num_email	= $db->numRows($query_email);
		$dt_cust		= $db->fetchNextObject($query_email);
		@$nama_coin		= $dt_cust->CUSTOMER_NAME;
		@$email_coin	= "thetakur@gmail.com"; //$dt_cust->CUSTOMER_EMAIL;
		//==================================================================
		
		//INFORMASI MERCHANT
		$q_merchant		= $db->query("SELECT CLIENT_EMAIL,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT='".$id_client."'");
		$dt_merchant	= $db->fetchNextObject($q_merchant);
		$merchant_coin	= $dt_merchant->CLIENT_NAME;
		$merchant_email	= $dt_merchant->CLIENT_EMAIL;
		//==================================================================
		
		$subject 		= "DEAL #".$id_deal." Diterima";
		$from			= "deal@".$website_name."";
		$type			= "html";
		if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_client."/thumbnails/".$photo)){
				$img = "<img src='".$dirhost."/".$img_dir."/products/".$id_client."/thumbnails/".$photo."'  style='width:100%'/>";
			 }else{
				$img = "<img src='".$dirhost."/files/images/no_image.jpg' style='width:100%'/>";
	    }         
		$msg 			= "
		<style type='text/css'>
			#tbl_data td{
				font-size:2vmin;
			}
		</style>
			
		<div style='font-family:Verdana, Geneva, sans-serif;'>
			Dear ".@$nama_coin.",<br>
			Deal Diskon kamu diterima oleh <b style='color:#CC0000'>".$merchant_coin."</b>, dan telah ditampilkan untuk seluruh pelanggan komunitas yang ingin ikut bergabung untuk deal diskonmu, dengan informasi deal;
				<table style='100%' id='tbl_data'>
					<tr>
					  <td width='15%' rowspan='7' align='center' valign='top' style='padding:4px;'>
					  ".$img."
					  </td>
					  <td colspan='2'><b>ID DEAL : #".$id_deal."</b></td>
				  </tr>
					<tr>
					  <td width='25%' valign='top'><b>Nama Produk</b></td>
					  <td width='60%' valign='top'>".$nm_product."</td>
					</tr>
					<tr>
					  <td ><b>Diskon (%)</td>
					  <td >".$dt_deal->VALUE."%</td>
					</tr>
                    <tr>
                      <td valign='top'><b>Harga Produk</b></td>
                        <td valign='top'>
                            <span style='text-decoration:line-through'>".money('Rp.',$hrg_product)."</span> 
                            <br> 
                            <b>".money('Rp.',$new_price)."</b>
                        </td>
                    </tr>
                    <tr>
                      <td><b>Voucher</b></td>
                      <td><b style='color:#CC0000'>".$voucher." Voucher</b></td>
                    </tr>
                    <tr>
                      <td valign='top'><b>Masa Berlaku</b></td>
                      <td valign='top'>
                        <b>
                            ".$dtime->now2indodate2($expired)."
                        </b>
                    </td>
                    </tr>
					<tr>
					  <td valign='top'><b>Dealer</b></td>
					  <td valign='top'>".@$nama_dealer."</td>
					</tr>
				</table>				
			<b style='color:#CC0000'>".$merchant_coin."</b> menjual <b style='color:#CC0000'>".$voucher." Voucher</b> untuk deal diskon ini kepada pelanggan komunitas yang ingin mengikuti deal mu ini hingga batas waktu <b>".$dtime->now2indodate2($expired)."</b>.
			<br>
			<span style='color:#CC0000'>
				Jika sampe batas waktu itu, tidak semua voucher yang terjual, maka biaya pembelian voucher akan dikembalikan (refund) kepada masing pelanggan yang mengikuti deal ini.
			</span>
			<br>
			Silahkan lakukan pemesanan voucher diskon untuk produk dari <b style='color:#CC0000'>".$merchant_coin."</b>, 
			<br>
			<a href='javascript:void()' class='sempoa-ajax' data-direction='".@$dirhost."/products/community/pages/index.php?spage=deals&id_deal=".$id_deal."'>
				<button type='button' class='btn btn-sempoa-1'>Disini</button>
			</a>
			<br>
			Terimakasih<br><br>
			<img src='".$logo_path."'><br>
			
		</div>	";		
		#sendmail(trim($email_coin),$subject,$msg,$from,$type);
		send_cust_msg("user","1",$id_customer,$subject,$msg);

		echo "Terimakasih, Informasi deal ini telah di kirim ke kotak masuk COIN <span class='code'><b>".$nama_coin."</b></span>";
		
		
	}
	
	if(!empty($direction) && $direction == "set_status"){
		$id_status 		= isset($_POST['id_status']) 		? $_POST['id_status'] 					: "";
		$cust_name 		= $db->fob("CUSTOMER_USERNAME",$tpref."customers"," WHERE ID_CUSTOMER='".$id_customer."'");
		$no_voucher		= "";
		if($id_status == 3){ $no_voucher		= strtoupper(substr(md5($cust_name),0,8)); }
		$container 		= array(1=>
							array("VOUCHER_NUMBER",strtoupper($no_voucher)),
							array("PAID_STATUS",$id_status));
		$db->update($tpref."customers_dealers",$container," WHERE ID_DISCOUNT = '".$id_product_deal."' AND ID_CUSTOMER='".$id_customer."'");	
		
		//INFORMASI CUSTOMER
		$query_email = $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."' ");
		$num_email	= $db->numRows($query_email);
		$dt_cust		= $db->fetchNextObject($query_email);
		@$nama_coin		= $dt_cust->CUSTOMER_NAME;
		@$email_coin	= "thetakur@gmail.com"; //$dt_cust->CUSTOMER_EMAIL;
		//==================================================================
		
		//INFORMASI MERCHANT
		$q_merchant		= $db->query("SELECT CLIENT_EMAIL,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT='".$id_client."'");
		$dt_merchant	= $db->fetchNextObject($q_merchant);
		$merchant_coin	= $dt_merchant->CLIENT_NAME;
		$merchant_email	= $dt_merchant->CLIENT_EMAIL;
		//==================================================================

		//INFORMASI DEAL
		$deal_string   	= "SELECT * FROM ".$tpref."client_discounts WHERE ID_DISCOUNT = '".$id_product_deal."'";
		$q_deal 	   	= $db->query($deal_string);
		$dt_deal 		= $db->fetchNextObject($q_deal);
		$id_deal		= transletNum($id_product_deal);
		$array		    = array(";",",");
		$id_product     = str_replace($array,"",$dt_deal->ID_PRODUCTS);
		$nama_dealer 	= $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER = '".$dt_deal->REQUEST_BY_ID_CUSTOMER."'");
		@$current_deal  = $db->recount("SELECT ID_DISCOUNT FROM ".$tpref."customers_dealers WHERE ID_PRODUCT_DEAL = '".$id_product_deal."'");
		//==================================================================		

		//INFORMASI PRODUCT
		$q_product 	   = $db->query("SELECT NAME,SALE_PRICE FROM ".$tpref."products WHERE ID_PRODUCT='".$id_product."'");
		$new_price	   = "0";
		$dt_product	   = $db->fetchNextObject($q_product);
		@$nm_product   = $dt_product->NAME; 
		@$hrg_product  = $dt_product->SALE_PRICE;
		if(!empty($hrg_product)){
			$new_disc	   = ($hrg_product/100)*$dt_deal->VALUE;
			$new_price	   = $hrg_product-$new_disc;
		}
		@$photo		    = $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
		//==================================================================		

		$subject 		= "Voucher DEAL #".$id_deal."";
		$from			= "deal@".$website_name."";
		$type			= "html";
		if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_client."/thumbnails/".$photo)){
				$img = "<img src='".$dirhost."/".$img_dir."/products/".$id_client."/thumbnails/".$photo."'  style='width:100%'/>";
			 }else{
				$img = "<img src='".$dirhost."/files/images/no_image.jpg' style='width:100%'/>";
	    }  
		$deal			= "
			<table style='100%' id='tbl_data'>
				<tr>
				  <td width='15%' rowspan='7' align='center' valign='top' style='padding:4px;'>
				  ".$img."
				  </td>
				  <td colspan='2'><b>ID DEAL : #".$id_deal."</b></td>
			  </tr>
				<tr>
				  <td width='25%' valign='top'><b>Nama Produk</b></td>
				  <td width='60%' valign='top'>".$nm_product."</td>
				</tr>
				<tr>
				  <td ><b>Diskon (%)</td>
				  <td >".$dt_deal->VALUE."%</td>
				</tr>
				<tr>
				  <td valign='top'><b>Harga Produk</b></td>
					<td valign='top'>
						<span style='text-decoration:line-through'>".money('Rp.',$hrg_product)."</span> 
						<br> 
						<b>".money('Rp.',$new_price)."</b>
					</td>
				</tr>
				<tr>
				  <td><b>Voucher</b></td>
				  <td>".@$current_deal."/".$dt_deal->VOUCHER_NUM." Voucher</td>
				</tr>
				<tr>
				  <td valign='top'><b>Masa Berlaku</b></td>
				  <td valign='top'>
					<b>
						".$dtime->now2indodate2($dt_deal->EXPIRATION_DATE)."
					</b>
				</td>
				</tr>
				<tr>
				  <td valign='top'><b>Dealer</b></td>
				  <td valign='top'>".@$nama_dealer."</td>
				</tr>
			</table>";
			      
		$css 			= "
		<style type='text/css'>
			#tbl_data td{
				font-size:2vmin;
			}
			.vclass_main{
				text-align:center;
			}
			.vclass{
				border:1px solid #E8E8E8;
				text-align:center;
				border-radius:2px;
				-moz-border-radius:2px;
				-webkit-border-radius:2px;
				padding:20px;
				width:90%;
				font-size:30px;
				box-shadow			:black 1px 2px 6px;
				-moz-box-shadow		:black 1px 2px 6px;
				-webkit-box-shadow	:black 1px 2px 6px;
				background:#FFF;
				text-shadow:#666 2px 2px 6px;
				color:#8D0345;
			}
		</style>";
		
		$msg = $css."	
		<div style='font-family:Verdana, Geneva, sans-serif;'>
			Dear ".@$nama_coin.",<br>
			Dibawah ini adalah informasi Nomor Voucher Diskon <b style='color:#CC0000'>".$merchant_coin."</b> kamu, untuk deal dengan informasi dibawah ini.
			".$deal."
			Dengan Nomor Voucher<br><br>
			<div class='vclass_main'>
				<div class='vclass'>".$no_voucher."</div>
			</div>	
			<br>		
			Silahkan belanjakan Voucher deal diskonmu di <b style='color:#CC0000'>".$merchant_coin."</b> sebelum batas waktu <b>".$dtime->now2indodate2($dt_deal->EXPIRATION_DATE)."</b>.
			<br>
			Terimakasih ".$nama_coin.", telah menggunakan COINmu sebagai kartu digital belanjamu, selamat menikmati deal-deal menarik lainya di Discoin Community<br><br>
			<img src='".$logo_path."'><br>
			
		</div>	";		
		#sendmail(trim($email_coin),$subject,$msg,$from,$type);
		send_cust_msg("user","1",$id_customer,$subject,$msg);
		
		
		$msg = $css."
		<div style='font-family:Verdana, Geneva, sans-serif;'>
			Dear ".@$merchant_coin.",<br>
			".$nama_coin." Telah melakukan pembayaran Voucher deal <b style='color:#CC0000'>#".$id_deal."</b> sebsar <b>".money('Rp.',$new_price)."</b>, dengan informasi dibawah ini;
			".$deal."
			Dengan Nomor Voucher<br><br>
			<div class='vclass_main'>
				<div class='vclass'>".$no_voucher."</div>
			</div>	
			<br>		
			Nomor Voucher deal diatas berakhir hingga batas waktu <b>".$dtime->now2indodate2($dt_deal->EXPIRATION_DATE)."</b>.
			<br>
			<span style='color:#CC0000'>
				Jika sampe batas waktu itu, tidak semua voucher yang terjual, maka biaya pembelian voucher akan dikembalikan (refund) kepada masing pelanggan yang mengikuti deal ini, sebaliknya, jika seluruh voucher terjual, maka total penjualan Voucher akan dikirim ke Rekening <b class='code'>".@$merchant_coin."</b>
			</span>
			<br>
			<br>
			Terimakasih ".$merchant_coin.", telah atas kepercayaanya kepada Discoin Community<br><br>
			<img src='".$logo_path."'><br>
			
		</div>";
				
		send_user_msg("user","1",$id_customer,$subject,$msg);
		echo "Disimpan";
	}
?>