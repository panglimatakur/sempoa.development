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
$id_product_multi 	= isset($_REQUEST['id_product']) 		? $_REQUEST['id_product']									:"";
$harga_beli_multi 	= isset($_REQUEST['harga_beli_multi']) 	? $_REQUEST['harga_beli_multi']								:""; 
$harga_jual_multi 	= isset($_REQUEST['harga_jual_multi']) 	? $_REQUEST['harga_jual_multi']								:""; 
$jumlah_multi 		= isset($_REQUEST['jumlah_multi']) 		? $_REQUEST['jumlah_multi']									:@$jumlah_multi; 
$tgl_beli_multi 	= isset($_REQUEST['tgl_beli_multi']) 	? $_REQUEST['tgl_beli_multi']								:""; 
if(!empty($tgl_beli_multi)){
	$tgl_beli_multi		= $dtime->indodate2date(@$tgl_beli_multi);
}

$termin_multi 		= isset($_REQUEST['termin_multi']) 		? $_REQUEST['termin_multi']									:@$termin_multi; 
$tgl_tempo_multi 	= isset($_REQUEST['tgl_tempo_multi']) 	? $_REQUEST['tgl_tempo_multi']								:@$tgl_tempo_multi; 

$id_partner_multi 	= isset($_REQUEST['id_partner_multi']) 	? $sanitize->number($_REQUEST['id_partner_multi'])			:@$id_partner_multi; 
$no_faktur_multi 	= isset($_REQUEST['no_faktur_multi']) 	? $sanitize->str(strtoupper($_REQUEST['no_faktur_multi']))	:@$no_faktur_multi; 
$total_bayar_multi 	= isset($_REQUEST['total_bayar_multi']) ? $sanitize->number($_REQUEST['total_bayar_multi'])			:@$total_bayar_multi; 
$keterangan_multi 	= isset($_REQUEST['keterangan_multi']) 	? $_REQUEST['keterangan_multi']								:@$keterangan_multi; 
$status_multi 		= isset($_REQUEST['status_multi']) 		? $sanitize->number($_REQUEST['status_multi'])				:@$status_multi; 
$nopo_multi 		= isset($_REQUEST['nopo_multi']) 		? $sanitize->str(strtoupper($_REQUEST['nopo_multi']))		:@$nopo_multi; 
$downpay_multi 		= isset($_REQUEST['downpay_multi']) 	? $sanitize->number($_REQUEST['downpay_multi'])				:@$downpay_multi; 
$kredit_multi 		= isset($_REQUEST['kredit_multi']) 		? $sanitize->number($_REQUEST['kredit_multi'])				:@$kredit_multi; 


if(!empty($direction) && $direction == "insert_multi"){
	$jml_produk 		= count($id_product_multi);
	$termin				= $termin_multi;
	if(!empty($status_multi) && $jml_produk > 0){
		$product_direction	= "1";
		$parent_id			= 70;
		
		if($status_multi != 2){
			if(!empty($downpay_multi)){
				$paid = $downpay_multi;
			}else{
				$paid   = 0;
				$kredit_multi	= $total_bayar_multi;
			}
		}else{
			$paid 			= $total_bayar_multi;
			$kredit_multi	= 0;
		}

		if(empty($new_cash)){ $new_cash	= $cash; } 
		$cash_residual_value 	= $new_cash-$paid;
		$id_cash_flow			= insert_cash($parent_id,"2",@$total_bayar_multi,@$paid,$product_direction);
		
		if($status_multi != 2){
			insert_decre($id_cash_flow,"1","+",$total_bayar_multi,$total_bayar_multi,$tgl_beli_multi);	
			if(!empty($downpay_multi)){
				insert_decre($id_cash_flow,"1","-",$paid,$kredit_multi,$tgl_beli_multi);	
			}
			
			if(!empty($downpay_multi)){ 
				$content = array(1=>
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("ID_CASH_FLOW",$id_cash_flow),
							array("ORDINAL","1"),
							array("DEBT_CREDIT","1"),
							array("STATUS","1"),
							array("REMINDER_DATE",$tgl_beli_multi));
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
								array("DEBT_CREDIT","1"),
								array("STATUS","0"),
								array("REMINDER_DATE",$tgl_tempo));
					$db->insert($tpref."debt_credit_reminder",$content);
				}
			}
		}
		$new_cash		= $cash_residual_value;
		$terms			= $termin_multi+1;
		$factur_content = array(1=>
						  array("ID_CLIENT",$_SESSION['cidkey']),
						  array("ID_PARTNER",@$id_partner_multi),
						  array("ID_CASH_FLOW",@$id_cash_flow),
						  array("FACTURE_NUMBER",@$no_faktur_multi),
						  array("PAID_STATUS",@$status_multi),
						  array("TERMS",@$terms),
						  array("PO_NUMBER",@$nopo_multi),
						  array("PAID",@$paid),
						  array("REMAIN",@$kredit_multi),
						  array("NOTE",@$keterangan_multi),
						  array("MODULE","BUY"),
						  array("BY_ID_USER",@$_SESSION['uidkey']),
						  array("TRANSACTION_DATE",@$tgl_beli_multi),
						  array("TGLUPDATE",@$tglupdate),
						  array("WKTUPDATE",@$wktupdate));
		$db->insert($tpref."factures",$factur_content);
		$id_faktur_multi = mysql_insert_id();

		$e = 0;
		foreach($id_product_multi as &$item){
			
			$harga_beli 		= $_REQUEST['harga_beli'];
			$harga_jual 		= $_REQUEST['harga_jual'];
			$jumlah 			= $_REQUEST['jumlah'];
			$stock 				= $_REQUEST['stock'];
			
			$total				= $harga_beli[$e]*$jumlah[$e];
			
			$prod_content 		= array(1=>
								  array("ID_CLIENT",$_SESSION['cidkey']),
								  array("ID_PRODUCT",@$item),
							  	  array("ID_FACTURE",@$id_faktur_multi),
								  array("BUY_PRICE",@$harga_beli[$e]),
								  array("SALE_PRICE",@$harga_jual[$e]),
								  array("QUANTITY",@$jumlah[$e]),
								  array("TOTAL",@$total),
								  array("BY_ID_USER",$_SESSION['uidkey']));
			$db->insert($tpref."products_buys",$prod_content);
			
						
			$prod_buy_his  = array(1=>
							  array("ID_CLIENT",$_SESSION['cidkey']),
							  array("ID_PRODUCT",@$item),
							  array("ID_PARTNER",@$id_partner_multi),
							  array("ID_FACTURE",@$id_faktur_multi),
							  array("BUY_PRICE",@$harga_beli[$e]),
							  array("SALE_PRICE",@$harga_jual[$e]),
							  array("QUANTITY",@$jumlah[$e]),
							  array("TOTAL",@$total),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTUR_NUMBER",@$no_faktur_multi),
							  array("PAID_STATUS",@$status_multi),
							  array("PO_NUMBER",@$nopo_multi),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit_multi),
							  array("ID_DIRECTION",@$product_direction),
							  array("BY_ID_USER",$_SESSION['uidkey']),
						  	  array("TRANSACTION_DATE",@$tgl_beli_multi),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."products_buys_history",$prod_buy_his);
			
			$check_stock   		= $db->recount("SELECT * FROM ".$tpref."products_stocks WHERE ID_PRODUCT='".$item."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
			if($check_stock == 0){
				$prod_content2 = array(1=>
								  array("ID_CLIENT",$_SESSION['cidkey']),
								  array("ID_PRODUCT",@$item),
								  array("STOCK",@$jumlah[$e]),
								  array("ENTER_DATE",@$tgl_beli_multi),
								  array("BY_ID_USER",$_SESSION['uidkey']),
								  array("TGLUPDATE",@$tglupdate),
								  array("WKTUPDATE",@$wktupdate));
				$db->insert($tpref."products_stocks",$prod_content2);
			}else{
				$db->query("UPDATE ".$tpref."products_stocks SET STOCK = (STOCK+".$jumlah[$e]."),ENTER_DATE='".@$tgl_beli_multi."',TGLUPDATE='".$tglupdate."',WKTUPDATE='".$wktupdate."',BY_ID_USER='".$_SESSION['uidkey']."' WHERE ID_PRODUCT ='".$item."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
			}
			
			$prod_stock_his = array(1=>
							  array("ID_CLIENT",$_SESSION['cidkey']),
							  array("ID_PRODUCT",@$item),
							  array("STOCK",@$stock[$e]),
							  array("ENTER_DATE",@$tgl_beli_multi),
							  array("ID_DIRECTION",@$product_direction),
							  array("BY_ID_USER",$_SESSION['uidkey']),
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