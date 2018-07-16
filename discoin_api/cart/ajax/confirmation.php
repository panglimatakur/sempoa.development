<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
	
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../../includes/config.php");
	include_once("../../../../../includes/classes.php");
	include_once("../../../../../includes/functions.php");
	include_once("../../../../../includes/declarations.php");
	
	$direction 	  		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";	
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	$result['content']  = "";
	
	if(!empty($direction) && $direction == "load"){
		$q_bank = $db->query("SELECT ID_BANK_ACCOUNT,BANK_NAME FROM system_master_bank_info ORDER BY BANK_NAME ASC");
		$result['content'] = '<option value="">-- PILIH REKENING TUJUAN --</option>';
		while($dt_bank = $db->fetchNextObject($q_bank)){
			$result['content']  .= '<option value="'.$dt_bank->ID_BANK_ACCOUNT.'">"'.$dt_bank->BANK_NAME.'"</option>';
		}
		echo $callback.'('.json_encode($result).')';
	}
	
	if(!empty($direction) && $direction == "ch_pay"){
		$code 			 		 = isset($_REQUEST['code']) ? $_REQUEST['code'] : "";
		$purchase_string  		 = "SELECT * FROM ".$tpref."customers_purchases WHERE PAYMENT_CODE = '".$code."' AND ID_CUSTOMER = '".$_SESSION['sidkey']."'";
		$q_purchase 	   		 = $db->query($purchase_string);
		$num_purchase 	 		 = $db->numRows($q_purchase);
		@$result['data']     	 = "";
		@$result['caption'] 	 = "";
		if($num_purchase > 0){
			$dt_purchase 	  	 = $db->fetchNextObject($q_purchase);
			@$result['data']     = $dt_purchase->PAID;
			@$result['caption']  = money("",$dt_purchase->PAID);
		}
		echo $callback.'('.json_encode($result).')';
	}
	
	if(!empty($direction) && $direction == "show_rekening"){
		$id_rek 	= isset($_REQUEST['id_rek']) ? $_REQUEST['id_rek'] 		: "";
		$q_rek 		= $db->query("SELECT * FROM system_master_bank_info WHERE ID_BANK_ACCOUNT = '".$id_rek."'");
		$dt_rek		= $db->fetchNextObject($q_rek);

		$result['content']  .= '
		<style type="text/css">
			.tbl_rek{
				border-radius:7px; 
				-moz-border-radius:7px; 
				-webkit-border-radius:7px;
				width:98%; 
				border:1px solid #CAC477; 
				background:#FEFBD6;  
			}
			.tbl_rek td{
				padding:4px;
			}
		</style>
			<table class="tbl_rek">
				<tr>
					<td width="43%">Nama Rekening</td>
					<td width="57%">'.$dt_rek->BANK_ACCOUNT_NAME.'</td>
				</tr>
				<tr>
					<td>Nomor Rekekning</td>
					<td>'.$dt_rek->BANK_ACCOUNT_NUMBER.'</td>
				</tr>
			 </table>';
			 
		echo $callback.'('.json_encode($result).')';
					
	}
	
	if(!empty($direction) && $direction == "confirmation_pay"){
		$id_bank		= isset($_REQUEST['id_bank']) 	  	? $sanitize->number($_REQUEST['id_bank']):"";
		$kd_purchase	= isset($_REQUEST['kd_purchase'])  	? $_REQUEST['kd_purchase']:"";
		$jumlah_bayar	= isset($_REQUEST['jumlah_bayar'])  ? $_REQUEST['jumlah_bayar']:"";
				
		$nmbank		    = isset($_REQUEST['nmbank']) 		? $sanitize->str($_REQUEST['nmbank']):"";
		$nmrek			= isset($_REQUEST['nmrek']) 		? $sanitize->str($_REQUEST['nmrek']):"";
		$norek			= isset($_REQUEST['norek']) 		? $sanitize->number($_REQUEST['norek']):"";
		
		$container 		= array(1=>
			 array("ID_CLIENT",@$_SESSION['csidkey']),
			 array("ID_CUSTOMER",@$_SESSION['sidkey']),
			 array("ID_IN_OUT","1"),
			 array("FROM_ID_BANK",@$nmbank),
			 array("BANK_ACCOUNT_NAME",strtoupper($nmrek)),
			 array("BANK_ACCOUNT_NUMBER",@$norek),
			 array("TO_ID_BANK",$id_bank),
			 array("PAYMENT",$jumlah_bayar),
			 array("ID_PAYMENT_SOURCE","1"),
			 array("PAYMENT_DATETIME",$tglupdate." ".$wktupdate),
			 array("PAYMENT_STATUS","2"),
			 array("TGLUPDATE",$tglupdate));		
		$db->insert($tpref."customers_payment_history",$container);
		$id_saldo 		= mysql_insert_id();
		  	
		//INFORMASI CUSTOMER
		$q_bank 		= $db->query("SELECT * FROM system_master_bank_info WHERE ID_BANK_ACCOUNT = '".$id_bank."' ORDER BY BANK_NAME ASC");
		$dt_bank 		= $db->fetchNextObject($q_bank);
		@$to_bank_nm 	= $dt_bank->BANK_NAME;
		@$to_bank_no 	= $dt_bank->BANK_ACCOUNT_NUMBER;
		@$to_rek_nm 	= $dt_bank->BANK_ACCOUNT_NAME;

		$client_name 	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$_SESSION['csidkey']."'");
		$query_email 	= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_EMAIL FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' ");
		$dt_cust		= $db->fetchNextObject($query_email);
		@$nama_coin	 	= $dt_cust->CUSTOMER_NAME;
		@$email_coin	= $dt_cust->CUSTOMER_EMAIL;
		$from		   	= "Info Order Discoin <order@sempoa.biz>";
		$type		    = "html";
		$email_admin	= "thetakur@gmail.com";
		$subject 		= "Konfirmasi Kode Pembayaran ".$kd_purchase;
		$msg 			= "
		<div style='font-family:Verdana, Geneva, sans-serif;'>
			Dear ".@$nama_coin.",<br>
			Konfirmasi pembayaran deposit saldo sebesar <span style='color:red'><b>".money("Rp.",$jumlah_bayar)."<b></span> berhasil disimpan, dengan Informasi;
			<br> 
			<br>
			<b>Rekening Asal</b>;
			<br>
			Nama Bank		: ".strtoupper($nmbank)."<br>
			Nama Rekening 	: ".strtoupper($nmrek)."<br>
			Nomor Rekening 	: ".strtoupper($norek)."
			<br>
			<br>
			<b>Rekening Tujuan</b>;
			<br>
			Nama Bank		: ".$to_bank_nm."<br>
			Nama Rekening 	: ".$to_rek_nm."<br>
			Nomor Rekening 	: ".$to_bank_no."<br>
			<br>
			Pembayaran ini akan kami validasi untuk menambah deposit ".$client_name." anda, dan kurang dari 1x24 jam deposit anda akan segera terisi secara otomatis, yang bisa anda lihat pada aplikasi Discoin anda.
			<br>
			<br>
			Terimakasih Banyak ".@$nama_coin."
			<br><br>
			<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
			<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
		</div>	";		
		sendmail(trim($email_coin),$subject,$msg,$from,$type);
		sendmail(trim($email_admin),$subject,$msg,$from,$type);
		//send_cust_msg("user","1",$_SESSION['sidkey'],$subject,$msg);
		$result['io']	    = 2;
		$result['content'] .= " Terimakasih, Konfirmasi, pembayaran ini akan segera di proses.";

		echo $callback.'('.json_encode($result).')';
	}	
}
?>