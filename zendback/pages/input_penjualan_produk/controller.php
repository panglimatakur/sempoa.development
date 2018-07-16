<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(!empty($direction) && $direction == "delete_draft"){
	$db->delete($tpref."draft"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_USER = '".$_SESSION['uidkey']."' AND PAGE='".$page."'");
}

@$draft 	= $db->fob("DRAFT",$tpref."draft","WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_USER = '".$_SESSION['uidkey']."' AND PAGE='".$page."'");
if(!empty($draft)){
	parse_str($draft, $args);
	foreach ($args as $key => $val) {
		$$key = @$val;
	}
}

$id_product_multi	= isset($_REQUEST['id_product']) 		? $_REQUEST['id_product']		:"";
$harga_multi 		= isset($_REQUEST['new_harga_multi']) 	? $_REQUEST['new_harga_multi']							:@$new_harga_multi; 
$jumlah_multi 		= isset($_REQUEST['jumlah_multi']) 		? $_REQUEST['jumlah_multi']								:@$jumlah_multi; 
$tgl_jual_multi 	= isset($_REQUEST['tgl_jual_multi']) 	? $_REQUEST['tgl_jual_multi']							:@$tgl_jual_multi; 
if(!empty($tgl_jual_multi)){
	$tgl_jual_multi		= $dtime->indodate2date(@$tgl_jual_multi);
}

$termin_multi 		= isset($_REQUEST['termin_multi']) 		? $_REQUEST['termin_multi']								:@$termin_multi; 
$tgl_tempo_multi 	= isset($_REQUEST['tgl_tempo_multi']) 	? $_REQUEST['tgl_tempo_multi']							:@$tgl_tempo_multi; 

$id_sales_multi 	= isset($_REQUEST['id_sales_multi']) 	? $sanitize->number($_REQUEST['id_sales_multi'])		:@$id_sales_multi; 
$id_customer_multi 	= isset($_REQUEST['id_customer_multi']) ? $sanitize->number($_REQUEST['id_customer_multi'])		:@$id_customer_multi; 
$faktur_multi 		= isset($_REQUEST['faktur_multi']) 		? $sanitize->str(strtoupper($_REQUEST['faktur_multi']))	:@$faktur_multi; 
$keterangan_multi 	= isset($_REQUEST['keterangan_multi']) 	? $_REQUEST['keterangan_multi']							:@$keterangan_multi; 
$status_multi 		= isset($_REQUEST['status_multi']) 		? $sanitize->number($_REQUEST['status_multi'])			:@$status_multi; 
$nopo_multi 		= isset($_REQUEST['nopo_multi']) 		? $sanitize->str(strtoupper($_REQUEST['nopo_multi']))	:@$nopo_multi; 
$downpay_multi 		= isset($_REQUEST['downpay_multi']) 	? $sanitize->number($_REQUEST['downpay_multi'])			:@$downpay_multi; 
$diskon_multi		= isset($_REQUEST['diskon_multi']) 		? $sanitize->number($_REQUEST['diskon_multi'])			:@$diskon_multi; 
$kredit_multi 		= isset($_REQUEST['kredit_multi']) 		? $sanitize->number($_REQUEST['kredit_multi'])			:@$kredit_multi; 

$propinsi_multi 	= isset($_REQUEST['propinsi_multi']) 	? $sanitize->number($_REQUEST['propinsi_multi'])		:@$propinsi_multi; 
$kota_multi 		= isset($_REQUEST['kota_multi']) 		? $sanitize->number($_REQUEST['kota_multi'])			:@$kota_multi; 
$kecamatan_multi 	= isset($_REQUEST['kecamatan_multi']) 	? $sanitize->number($_REQUEST['kecamatan_multi'])		:@$kecamatan_multi; 
$kelurahan_multi 	= isset($_REQUEST['kelurahan_multi']) 	? $sanitize->number($_REQUEST['kelurahan_multi'])		:@$kelurahan_multi; 

if(!empty($direction) && $direction == "insert_multi"){
	$jml_produk 		= count($id_product_multi);
	$termin				= $termin_multi;
	if(!empty($id_sales_multi) && !empty($status_multi) && $jml_produk > 0){		
			$parent_id				= 69;
			$product_direction		= "4";
			if($status_multi != 2){
				if(!empty($downpay_multi)){
					$paid = $downpay_multi;
				}else{
					$paid   = 0;
					$kredit_multi	= $harga_multi;
				}
			}else{
				$paid 			= $harga_multi;
				$kredit_multi	= 0;
			}
	
			if(empty($new_cash)){ $new_cash	= $cash; } 
			$cash_residual_value 	= $new_cash+$harga_multi;
			$id_cash_flow 			= insert_cash($parent_id,"1",@$harga_multi,@$paid,$product_direction);
			
			if($status_multi != 2){
				insert_decre($id_cash_flow,"3","+",$harga_multi,$harga_multi,$tgl_jual_multi);	
				if(!empty($downpay_multi)){
					insert_decre($id_cash_flow,"3","-",$paid,$kredit_multi,$tgl_jual_multi);	
				}
				
				if(!empty($downpay_multi)){ 
					$content = array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CASH_FLOW",$id_cash_flow),
								array("ORDINAL","1"),
								array("DEBT_CREDIT","3"),
								array("STATUS","1"),
								array("REMINDER_DATE",$tgl_jual_multi));
					$db->insert($tpref."debt_credit_reminder",$content);
					$id_ordinal = 1;
				}
				if(empty($id_ordinal)){ $id_ordinal = 0; }
				foreach($tgl_tempo_multi as &$tgl_tempo){
					if(!empty($tgl_tempo)){
						$id_ordinal++;
						$tgl_tempo		= $dtime->indodate2date(@$tgl_tempo);
						$content = array(1=>
									array("ID_CLIENT",$_SESSION['cidkey']),
									array("ID_CASH_FLOW",$id_cash_flow),
									array("ORDINAL",$id_ordinal),
									array("DEBT_CREDIT","3"),
									array("STATUS","0"),
									array("REMINDER_DATE",$tgl_tempo));
						$db->insert($tpref."debt_credit_reminder",$content);
					}
				}
			}
			
			$new_cash		= $cash_residual_value;
			$terms			= $termin_multi+1;
			$factur_content = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_SALES",@$id_sales_multi),
							  array("ID_CUSTOMER",@$id_customer_multi),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTURE_NUMBER",@$faktur_multi),
							  array("PAID_STATUS",@$status_multi),
							  array("TERMS",@$terms),
							  array("PO_NUMBER",@$nopo_multi),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit_multi),
							  array("NOTE",@$keterangan_multi),
						  	  array("MODULE","SALE"),
							  array("BY_ID_USER",@$_SESSION['uidkey']),
							  array("TRANSACTION_DATE",@$tgl_jual_multi),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."factures",$factur_content);
			$id_faktur_multi = mysql_insert_id();
			
			$note            = "Terjadi Penjualan Pada ".$_SESSION['cname']." Sebesar ".money("Rp.",$paid);
			if($_SESSION['uclevelkey'] != 2){ send_notification($id_client,$product_direction,$note); }
			send_notification($_SESSION['cidkey'],$product_direction,$note);
		$e = 0;
		foreach($id_product_multi as &$item){
			
			$harga 					= isset($_REQUEST['harga']) 		? $_REQUEST['harga']			:""; 
			$quantity 				= isset($_REQUEST['jumlah']) 		? $_REQUEST['jumlah']			:""; 
			$stock 					= isset($_REQUEST['stock']) 		? $_REQUEST['stock']			:""; 
			$diskon 				= isset($_REQUEST['diskon']) 		? $_REQUEST['diskon']			:""; 
			$total					= $harga[$e]*$quantity[$e];
			if(!empty($diskon[$e])){
				$diskon_total		= $total*($diskon[$e]/100);
				$total 				= $total-$diskon_total;
			}
			$prod_content = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_PRODUCT",@$item),
							  array("ID_FACTURE",@$id_faktur_multi),
							  array("QUANTITY",@$quantity[$e]),
							  array("PRICE",@$harga[$e]),
							  array("DISCOUNT",@$diskon_multi),
							  array("TOTAL",@$total),
							  array("PROVINCE",@$propinsi_multi),
							  array("CITY",@$kota_multi),
							  array("DISTRICT",@$kecamatan_multi),
							  array("SUBDISTRICT",@$kelurahan_multi),
							  array("BY_ID_USER",@$_SESSION['uidkey']));
			$db->insert($tpref."products_sales",$prod_content);
	
			$prod_content = array(1=>
							  array("STOCK",@$stock[$e]),
							  array("ENTER_DATE",@$tgl_jual_multi),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->update($tpref."products_stocks",$prod_content," WHERE ID_PRODUCT='".$item."' AND ID_CLIENT='".@$_SESSION['cidkey']."'");
			
			$prod_sale_his = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_SALES",@$id_sales_multi),
							  array("ID_CUSTOMER",@$id_customer_multi),
							  array("ID_PRODUCT",@$item),
							  array("ID_FACTURE",@$id_faktur_multi),
							  array("QUANTITY",@$quantity[$e]),
							  array("PRICE",@$harga[$e]),
							  array("DISCOUNT",@$diskon_multi),
							  array("TOTAL",@$total),
							  array("PROVINCE",@$propinsi_multi),
							  array("CITY",@$kota_multi),
							  array("DISTRICT",@$kecamatan_multi),
							  array("SUBDISTRICT",@$kelurahan_multi),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTURE_NUMBER",@$faktur_multi),
							  array("PAID_STATUS",@$status_multi),
							  array("TERMS",@$termin_multi),
							  array("PO_NUMBER",@$nopo_multi),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit_multi),
							  array("NOTE",@$keterangan_multi),
							  array("ID_DIRECTION",@$product_direction),
							  array("TRANSACTION_DATE",@$tgl_jual_multi),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$result = $db->insert($tpref."products_sales_history",$prod_sale_his);
	
			$prod_stock_his = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_PRODUCT",@$item),
							  array("STOCK",@$stock[$e]),
							  array("ENTER_DATE",@$tgl_jual_multi),
							  array("ID_DIRECTION",@$product_direction),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."products_stocks_history",$prod_stock_his);
			$e++;
		}
		$db->delete($tpref."draft"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_USER = '".$_SESSION['uidkey']."' AND PAGE='".$page."'");
		redirect_page($lparam."&msg=3");
	}else{
		$msg = 2;	
	}
}
?>