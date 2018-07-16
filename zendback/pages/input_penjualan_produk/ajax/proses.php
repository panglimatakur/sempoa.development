<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$parent_id			= 69;	
	$direction 			= isset($_POST['direction']) 			? $_POST['direction'] 	: "";
	if(!empty($direction)){
		function update_stock($flow,$first_stock,$sum,$tgl,$id_direction){
			global $db;
			global $tpref;
			global $tglupdate;
			global $wktupdate;
			global $id_product;
			if($flow == "delete")	{ $condition = "STOCK = (STOCK-".$first_stock.")+".$sum.","; }
			if($flow == "save")		{ $condition = "STOCK = (STOCK+".$sum."),"; }
			
			$db->query("UPDATE ".$tpref."products_stocks SET ".$condition."ENTER_DATE='".@$tgl."',BY_ID_USER='".$_SESSION['uidkey']."',TGLUPDATE='".$tglupdate."',WKTUPDATE='".$wktupdate."' WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");
			
			$prod_stock_his = array(1=>
							  array("ID_CLIENT",$_SESSION['cidkey']),
							  array("ID_PRODUCT",@$id_product),
							  array("STOCK",@$sum),
							  array("ENTER_DATE",@$tgl),
							  array("ID_DIRECTION",@$id_direction),
							  array("BY_ID_USER",$_SESSION['uidkey']),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."products_stocks_history",$prod_stock_his);
			$new_stock 		= $db->fob("STOCK",$tpref."products_stocks","WHERE ID_PRODUCT='".$id_product."'");
			return $new_stock;
		}
		$save_history	= "";
		$show_list 		= isset($_REQUEST['show_list']) 			? $sanitize->number($_REQUEST['show_list']) :"";
	
		$id_facture 		= isset($_REQUEST['id_facture']) 		? $sanitize->number($_REQUEST['id_facture']) :"";
		$id_product_sale 	= isset($_REQUEST['id_product_sale'])	? $sanitize->number($_REQUEST['id_product_sale']) :"";
		$id_product 		= isset($_REQUEST['id_product']) 		? $sanitize->number($_REQUEST['id_product']) :"";
	
		$tgl_jual 			= isset($_REQUEST['tgl_jual']) 			? $sanitize->str($_REQUEST['tgl_jual']) :"";
		if(!empty($tgl_jual)){
			$tgl_jual		= $dtime->indodate2date($tgl_jual);
		}
		$id_sales 		= isset($_REQUEST['id_sales']) 				? $sanitize->number($_REQUEST['id_sales']) 				: "";
		$id_customer 	= isset($_REQUEST['id_customer']) 			? $sanitize->number($_REQUEST['id_customer']) 			: "";
		$jual 			= isset($_REQUEST['jual']) 					? $sanitize->str($_REQUEST['jual']) 					: "";
		$harga 			= isset($_REQUEST['harga']) 				? $sanitize->str($_REQUEST['harga']) 					: "";
		$diskon 		= isset($_REQUEST['diskon']) 				? $sanitize->str($_REQUEST['diskon']) 					: "";
		$total 			= isset($_REQUEST['total']) 				? $sanitize->str($_REQUEST['total']) 					: "";
		$propinsi 		= isset($_REQUEST['propinsi']) 				? $sanitize->str(strtoupper($_REQUEST['propinsi'])) 	:"";
		$kota 			= isset($_REQUEST['kota']) 					? $sanitize->str(strtoupper($_REQUEST['kota'])) 		:"";
		$kecamatan 		= isset($_REQUEST['kecamatan']) 			? $sanitize->str(strtoupper($_REQUEST['kecamatan'])) 	:"";
		$kelurahan 		= isset($_REQUEST['kelurahan']) 			? $sanitize->str(strtoupper($_REQUEST['kelurahan'])) 	:"";
	
	
		$keterangan 	= isset($_REQUEST['keterangan'])	? $_REQUEST['keterangan'] 	: "";
	
		$id_cash_flow 	= isset($_REQUEST['id_cash_flow']) 	? $sanitize->number($_REQUEST['id_cash_flow']) :"";
		$faktur 		= isset($_REQUEST['faktur']) 		? $sanitize->str(strtoupper($_REQUEST['faktur'])) :"";
		$lunas 			= isset($_REQUEST['lunas']) 		? $sanitize->str($_REQUEST['lunas']) 	:"";
		$nopo 			= isset($_REQUEST['nopo']) 			? $sanitize->str(strtoupper($_REQUEST['nopo'])) :"";
		$downpay 		= isset($_REQUEST['downpay']) 		? $sanitize->str($_REQUEST['downpay']) 	:"";
		$termin 		= isset($_REQUEST['termin']) 		? $sanitize->number($_REQUEST['termin']) 	:"";
		$tgl_tempo 		= isset($_REQUEST['tgl_tempo']) 	? $_REQUEST['tgl_tempo'] 				:"";
		$st_termin 		= isset($_REQUEST['st_termin']) 	? $sanitize->str($_REQUEST['st_termin']) 	:"";
		$kredit 		= isset($_REQUEST['kredit']) 		? $sanitize->str($_REQUEST['kredit']) 	:"";
		
		$first_stock 		= isset($_REQUEST['first_stock']) 		? $sanitize->str($_REQUEST['first_stock']) 			: "";
		$real_total_jual 	= isset($_REQUEST['real_total_jual']) 	? $sanitize->str($_REQUEST['real_total_jual']) 		: "";
		$real_total_bayar 	= isset($_REQUEST['real_total_bayar']) 	? $sanitize->str($_REQUEST['real_total_bayar']) 	: "";
		
		$total_jual 		= ($real_total_bayar - $real_total_jual)+$total;
		$paid 				= $total_jual;
		if($lunas != 2){
			if(!empty($downpay)){
				$paid = $downpay;
			}else{
				$kredit	= $paid;
				$paid	= 0;
			}
		}else{
			$kredit	= 0;	
		}
		if($direction == "save_draft"){
			$jml_page = $db->recount("SELECT ID_CLIENT FROM ".$tpref."draft WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_USER = '".$_SESSION['uidkey']."' AND PAGE='".$page."'");
			if($jml_page == 0){
				$draft = array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_USER",$_SESSION['uidkey']),
								array("PAGE",$page),
								array("DRAFT",$keterangan));
				$db->insert($tpref."draft",$draft);
			}else{
				$draft = array(1=>array("DRAFT",$keterangan));
				$db->update($tpref."draft",$draft," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_USER = '".$_SESSION['uidkey']."' AND PAGE='".$page."'");
			}
		}


		if(!empty($direction) && $direction == "check_facture"){
			$num_facture = $db->recount("SELECT FACTURE_NUMBER WHERE FACTURE_NUMBER = '".$facture."'");
			if($num_facture > 0){
				$result = "false";	
			}else{
				$result = "true";	
			}
		}
		if(!empty($direction) && $direction == "save"){
			$product_direction		= "5";
			@$original_cash			= $db->fob("PAID",$tpref."factures","WHERE ID_CASH_FLOW='".$id_cash_flow."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");
			
			$cash_residual_value 	= save_cash($id_cash_flow,"2",$total_jual,$paid,$product_direction);
			$check_debcre			= $db->recount("SELECT ID_CASH_FLOW FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$id_cash_flow."' ");
			
			if($lunas != 2){
				if($check_debcre > 0){ save_decre($id_cash_flow,$total_jual,$total_jual,$tgl_jual,"+"); }
				else				 { insert_decre($id_cash_flow,"1","+",$total_jual,"",$tgl_jual); }
				if(!empty($downpay)){
					if($check_debcre > 0){ save_decre($id_cash_flow,$paid,$kredit,$tgl_jual,"-");	}
					else				 { insert_decre($id_cash_flow,"1","-",$paid,$kredit,$tgl_jual);	}
				}
			}
			
			$db->delete($tpref."debt_credit_reminder"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND STATUS = '0'");
			$tempo 			= 0;
			$tgl_tempos 	= substr_count($tgl_tempo,";");
			$tgl_tempo_edit = explode(";",$tgl_tempo);
			$st_termins		= explode(";",$st_termin);
			$tgl_temponya ="";
			
			while($tempo < $tgl_tempos){
				$tempo++;
				if($st_termins[$tempo] != "1"){
				$tgl_tempo	= "";
				$tgl_tempo	= $dtime->indodate2date(@$tgl_tempo_edit[$tempo]);
				$content 	= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CASH_FLOW",$id_cash_flow),
								array("DEBT_CREDIT","3"),
								array("ORDINAL",$tempo),
								array("STATUS","0"),
								array("REMINDER_DATE",$tgl_tempo));
				$db->insert($tpref."debt_credit_reminder",$content);
				}
			}
			$factur_content = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_SALES",@$id_sales),
							  array("ID_CUSTOMER",@$id_customer),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTURE_NUMBER",@$faktur),
							  array("PAID_STATUS",@$lunas),
							  array("TERMS",@$termin),
							  array("PO_NUMBER",@$nopo),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit),
							  array("NOTE",@$keterangan),
							  array("BY_ID_USER",@$_SESSION['uidkey']),
							  array("TRANSACTION_DATE",@$tgl_jual),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->update($tpref."factures",$factur_content," WHERE ID_FACTURE = '".$id_facture."'"); 
	
			$prod_content = array(1=>
							  array("QUANTITY",@$jual),
							  array("PRICE",@$harga),
							  array("DISCOUNT",@$diskon),
							  array("TOTAL",@$total),
							  array("PROVINCE",@$propinsi),
							  array("CITY",@$kota),
							  array("DISTRICT",@$kecamatan),
							  array("SUBDISTRICT",@$kelurahan),
							  array("BY_ID_USER",$_SESSION['uidkey']));
			$db->update($tpref."products_sales",$prod_content," WHERE ID_PRODUCT_SALE='".$id_product_sale."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
						
			update_stock("save",$first_stock,$jual,$tgl_jual,$product_direction);
			$show_list 		= 1;
			$save_history	= 1;
		}
			
		if(!empty($direction) && ($direction == "delete" || $direction == "delete_single")){
			$product_direction		= "6";
			if($direction == "delete")		 { $condition = "AND a.ID_CASH_FLOW='".$id_product_sale."' "; 		$delete_condition = 2; }
			if($direction == "delete_single"){ $condition = "AND b.ID_PRODUCT_SALE ='".$id_product_sale."' "; 	$delete_condition = 1; }
			
				
			$q_sale 	 		= $db->query("SELECT * FROM ".$tpref."factures a ,".$tpref."products_sales b WHERE a.ID_FACTURE = b.ID_FACTURE AND a.ID_CLIENT='".$_SESSION['cidkey']."' ".$condition."");
			while($dt_sale		= $db->fetchNextObject($q_sale)){
				@$id_sales		= $dt_sale->ID_SALES;
				@$id_product	= $dt_sale->ID_PRODUCT;
				@$id_facture 	= $dt_sale->ID_FACTURE;
				@$jual			= $dt_sale->QUANTITY;
				@$harga			= $dt_sale->PRICE;
				@$diskon		= $dt_sale->DISCOUNT;
				@$total			= $dt_sale->TOTAL;
				@$total_all		= $db->sum("TOTAL",$tpref."products_sales"," WHERE ID_FACTURE='".$dt_sale->ID_FACTURE."'");
				@$tgl_jual		= $dt_sale->SALE_DATE;
				@$propinsi		= $dt_sale->PROVINCE;
				@$kota			= $dt_sale->CITY;
				@$kecamatan		= $dt_sale->DISTRICT;
				@$kelurahan		= $dt_sale->SUBDISTRICT;
				
				@$faktur		= $dt_sale->FACTURE_NUMBER;
				@$lunas			= $dt_sale->PAID_STATUS;
				@$nopo			= $dt_sale->PO_NUMBER;
				@$paid			= $dt_sale->PAID;
				@$kredit		= $dt_sale->REMAIN;
				
				if($delete_condition ==  1){ 
					$delete_condition 	= "AND ID_PRODUCT_SALE ='".$dt_sale->ID_PRODUCT_SALE."'";
				}else{
					$delete_condition 	= "AND ID_FACTURE ='".$dt_sale->ID_FACTURE."'";
				}
				$db->delete($tpref."products_sales "," WHERE ID_CLIENT='".$_SESSION['cidkey']."' ".$delete_condition);			
				update_stock("delete",$jual,$jual,$tgl_jual,$product_direction);
			
			}
			
			$num_list	= $db->recount("SELECT * FROM ".$tpref."products_sales WHERE ID_FACTURE='".$id_facture."'");
			if($direction == "delete"){ 
				$cash_residual_value = delete_cash($id_cash_flow,$product_direction);
				delete_decre($id_cash_flow);
				$db->delete($tpref."debt_credit_reminder","WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' ");
				echo $cash_residual_value;
			}else{
				$cash_condition 		= "";
				$cash_residual_value 	= $cash - $total;
				if($lunas == 2){
					$new_total		= $paid - $total;
					if($num_list > 0){
						$cash_residual_value = save_cash($id_cash_flow,2,$new_total,$new_total,$product_direction);
					}else{
						$cash_residual_value = delete_cash($id_cash_flow,2,$new_total,$new_total,$product_direction);	
					}
					$cash_condition .= "PAID='".$new_total."',";
				}else{
					$result 		= array();
					$new_total		= $paid - $total_all;
					$cash_condition	= "";
					if($total > $kredit){
						$new_total			= $total_all - $paid;
						$new_total_all		= $total_all - $total;
						$remain 			= $paid - $new_total_all;
						$cash_residual_value= save_cash($id_cash_flow,2,$new_total_all,$new_total_all,$product_direction);
						$code				= $db->fob("CODE",$tpref."products"," WHERE ID_PRODUCT='".$id_product."'");
						$note 				= "
	Pembeli No Faktur <b class='code'>".$faktur."</b> ini telah melakukan pembayaran sebesar <b class='code'>".money("Rp.",$paid)."</b> dari total pembelian <b class='code'>".money("Rp.",$total_all)."</b>, hingga menghasilkan sisa pembayaran piutang sebesar <b class='code'>".money("Rp.",$new_total)."</b>,- yang harus di bayarkan pembeli kepada penjual.
	
	<br><br>
	Namun, dikarenakan anda (penjual) melakukan pembatalan (menghapus) penjualan produk kode <b class='code'>".$code."</b> yang bernilai <b class='code'>".money("Rp.",$total)."</b>, artinya, total pembayaran, seharusnya berkurang menjadi <b class='code'>".money("Rp.",$new_total_all)."</b> 
	
	<br><br>
	Dikarenakan Pembayaran yang sudah di bayarkan pembeli kepada penjual, sebesar <b class='code'>".money("Rp.",$paid)."</b> tidak sesuai dengan total pembelian seharusnya yang sebesar <b class='code'>".money("Rp.",$new_total_all)."</b>, terjadi lebih bayar, menjadi beban hutang anda (penjual) kepada pembeli sebesar <b class='code'>".money("Rp.",$new_total)."</b> yang harus di kembalikan, dan piutang pembeli otomatis  menjadi status lunas.
	";
						$keterangan 		 = "Hutang lebih bayar penjualan No Faktur <b class='code'>".$faktur."</b>";
						insert_decre($id_cash_flow,1,"+",$new_total,0,$tglupdate);
						$id_cash_type		= 78;
						$content 			= array(1=>
												array("ID_CLIENT",$_SESSION['cidkey']),
												array("ID_CASH_TYPE",$id_cash_type),
												array("ID_CASH_SOURCE",2),
												array("CASH_VALUE",@$remain),
												array("PAID","0"),
												array("REMAIN",@$remain),
												array("NOTE",@$keterangan),
												array("PAID_STATUS","3"),
												array("BY_ID_USER",$_SESSION['uidkey']),
												array("TGLUPDATE",$tglupdate),
												array("WKTUPDATE",$wktupdate));
						$db->insert($tpref."cash_flow",$content);						
						$content_history 	= array(1=>
												array("ID_CLIENT",$_SESSION['cidkey']),
												array("ID_CASH_FLOW",$id_cash_flow),
												array("ID_CASH_TYPE",$id_cash_type),
												array("ID_CASH_SOURCE",2),
												array("CASH_VALUE",@$remain),
												array("PAID","0"),
												array("REMAIN",@$remain),
												array("PAID_STATUS","3"),
												array("NOTE",@$keterangan),
												array("CASH_RESIDUAL_VALUE",@$cash_residual_value),
												array("BY_ID_USER",$_SESSION['uidkey']),
												array("ID_DIRECTION",$product_direction),
												array("TGLUPDATE",$tglupdate),
												array("WKTUPDATE",$wktupdate));
						$db->insert($tpref."cash_flow_history",$content_history);
						
						$new_total			 = 0;
						$cash_condition 	.= "PAID = '".$paid."',PAID_STATUS='2',";
						$result['note'] 	 = $note;
					}
					$cash_condition .= "REMAIN='".$new_total."',";
				}
				$result['cash']  		= $cash_residual_value;
				$result['num_list']  	= $num_list;
				echo json_encode($result);
			}
			if($num_list > 0){
				$db->query("UPDATE ".$tpref."factures SET ".$cash_condition."BY_ID_USER='".$_SESSION['uidkey']."',TGLUPDATE='".$tglupdate."',WKTUPDATE='".$wktupdate."' WHERE ID_CASH_FLOW='".$id_cash_flow."' AND ID_CLIENT='".$_SESSION['cidkey']."'");	
			}else{
				$db->delete($tpref."factures "," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW ='".$id_cash_flow."'");	
			}
			$save_history	= 1;
		}
		
		if(!empty($direction) && ($direction == "delete_tempo")){
			//HAPUS NOTIFIKASI
			$tgl_tempo = $db->fob("REMINDER_DATE",$tpref."debt_credit_reminder","WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND ORDINAL='".$termin."'");
			$db->delete($tpref."notifications","WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND TGLWKTUPDATE LIKE '%".$tgl_tempo."%'");

			//HAPUS DEBT CREDIT REMINDER
			$db->delete($tpref."debt_credit_reminder","WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND ORDINAL='".$termin."'");
			$q_ordinal	 = $db->query("SELECT * FROM ".$tpref."debt_credit_reminder WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' ORDER BY ID_DEBT_CREDIT_REMINDER ASC");
			$f	= 0;
			$r	= "";
			while($dt_reminder = $db->fetchNextObject($q_ordinal)){ $f++;
				$db->query("UPDATE ".$tpref."debt_credit_reminder SET ORDINAL='".$f."' WHERE ID_DEBT_CREDIT_REMINDER='".$dt_reminder->ID_DEBT_CREDIT_REMINDER."'");
				$r .="UPDATE ".$tpref."debt_credit_reminder SET ORDINAL='".$f."' WHERE ID_DEBT_CREDIT_REMINDER='".$dt_reminder->ID_DEBT_CREDIT_REMINDER."'";
			}
			echo $r;
			//EDIT TERM FACTURE
			$terms = 1;
			$db->query("UPDATE ".$tpref."factures SET TERMS = (TERMS-".$terms.") WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' ");

			//EDIT TERM CASH_FLOW
			$db->query("UPDATE ".$tpref."cash_flow SET TERMS = (TERMS-".$terms.") WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' ");

			//EDIT TERM HISTORY CASH
			$id_cash_history		= $db->last("ID_CASH_FLOW_HISTORY",$tpref."cash_flow_history"," WHERE ID_CASH_FLOW='".$id_cash_flow."'");
			$q_cash					= $db->query("SELECT * FROM ".$tpref."cash_flow_history WHERE ID_CASH_FLOW_HISTORY='".$id_cash_history."'");
			$dt_cash				= $db->fetchNextObject($q_cash);
			$id_client				= $dt_cash->ID_CLIENT;
			$id_cash_type 			= $dt_cash->ID_CASH_TYPE;
			$sumber 				= $dt_cash->ID_CASH_SOURCE;
			$total 					= $dt_cash->CASH_VALUE;
			$cash_residual_value	= $dt_cash->CASH_RESIDUAL_VALUE;
			$paid 					= $dt_cash->PAID;
			$kredit 				= $dt_cash->REMAIN;
			$status 				= $dt_cash->PAID_STATUS;
			$termin 				= $dt_cash->TERMS - 1;
			$id_direction 			= $dt_cash->ID_DIRECTION;
			$uidkey	 				= $dt_cash->BY_ID_USER;
			$tglupdate 				= $dt_cash->TGLUPDATE;
			$wktupdate				= $dt_cash->WKTUPDATE;
			$content = array(1=>
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("ID_CASH_FLOW",$id_cash_flow),
						array("ID_CASH_TYPE",$id_cash_type),
						array("ID_CASH_SOURCE",@$sumber),
						array("CASH_VALUE",@$total),
						array("PAID",@$paid),
						array("REMAIN",@$kredit),
						array("PAID_STATUS",@$status),
						array("TERMS",@$termin),
						array("CASH_RESIDUAL_VALUE",@$cash_residual_value),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("ID_DIRECTION",$id_direction),
						array("TGLUPDATE",$tglupdate),
						array("WKTUPDATE",$wktupdate));
			$db->insert($tpref."cash_flow_history",$content);
		}
			
		if($save_history == 1){
			$prod_sale_his = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_SALES",@$id_sales),
							  array("ID_CUSTOMER",@$id_customer),
							  array("ID_PRODUCT",@$id_product),
							  array("ID_FACTURE",@$id_facture),
							  array("QUANTITY",@$jual),
							  array("PRICE",@$harga),
							  array("DISCOUNT",@$diskon),
							  array("TOTAL",@$total),
							  array("PROVINCE",@$propinsi),
							  array("CITY",@$kota),
							  array("DISTRICT",@$kecamatan),
							  array("SUBDISTRICT",@$kelurahan),
							  array("ID_DIRECTION",@$product_direction),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTURE_NUMBER",@$faktur),
							  array("PO_NUMBER",@$nopo),
							  array("PAID_STATUS",@$lunas),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit),
							  array("NOTE",@$keterangan),
							  array("BY_ID_USER",$_SESSION['uidkey']),
							  array("TRANSACTION_DATE",@$tgl_jual),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."products_sales_history",$prod_sale_his);
		}
		if($show_list == 1){
			$show_condition = " AND a.ID_CASH_FLOW='".$id_cash_flow."'"; 
			$query_str	= "
					SELECT 	
						*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,SUM(a.REMAIN) AS PIUTANG  
					FROM 
						".$tpref."factures a ,".$tpref."products_sales b,".$tpref."products c
					WHERE 
						a.ID_FACTURE = b.ID_FACTURE AND 
						b.ID_PRODUCT = c.ID_PRODUCT AND 
						a.MODULE = 'SALE' AND
						a.ID_CLIENT='".$_SESSION['cidkey']."' 
						".$show_condition."";
		//echo $query_str." ".$limit;
			$q_sale			= $db->query($query_str);
			$dt_sale		= $db->fetchNextObject($q_sale);
			
			$total_all		= 0;
			$jumlah_all		= 0;
			$piutang_all	= 0;
	
			$total			= $dt_sale->PRICE*$dt_sale->QUANTITY;
			$q_marketing 	= $db->query("SELECT USER_NAME,USER_PHOTO FROM system_users_client WHERE ID_USER='".$dt_sale->ID_SALES."'");
			$dt_marketing 	= $db->fetchNextObject($q_marketing);
			@$nm_sales		= $dt_marketing->USER_NAME;
			@$pt_sales		= $dt_marketing->USER_PHOTO;
			if(!empty($diskon)){
				$diskon_new	= $total*($dt_sale->DISCOUNT/100);
				$total 		= $total-$diskon_new;
			}
			$paid_status	= "";
			if($dt_sale->PAID_STATUS == "2"){
				$paid_status = "LUNAS";	
			}else{
				$paid_status = "<a href='".$dirhost."/?page=piutang_penjualan_produk&id_sale=".$dt_sale->ID_PRODUCT_SALE."' >PIUTANG</a>";	
			}
			
		?>
		  <tr class="wrdLatest" data-info='<?php echo $dt_sale->ID_CASH_FLOW; ?>' id="tr_<?php echo $dt_sale->ID_CASH_FLOW; ?>">
			<td style="vertical-align:top; text-align:center"><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_sale->ID_PRODUCT_SALE; ?>'/></td>
			<td style="vertical-align:top; position:relative;">
					
				<span class='code'>
					<b>NO FAKTUR : <?php echo $dt_sale->FACTURE_NUMBER; ?></b>
				</span>
				<br>
				<small>
					<i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_sale->TRANSACTION_DATE); ?> - 
					Oleh : <?php echo $nm_sales; ?><br />
				</small>
				<?php if(!empty($dt_sale->NOTE)){?>
                    <div class="cnt_a invoice_preview" style="margin:7px; background:#FFF; font-size:12px">
                        <div class="inv_notes" >
                            <span class="label label-info">Notes</span>
                            <?php echo $dt_sale->NOTE; ?>
                        </div>
                   </div>
              <?php } ?>
					<?php  
					include $call->inc("modules/laporan_penjualan_produk/includes","list_kolektif.php");
					$total_all 	= $dt_sale->SUMMARY	+$total_all;
					$jumlah_all	= $dt_sale->JML		+$jumlah_all;
					$piutang_all= $dt_sale->PIUTANG	+$piutang_all;
					?>
				<br clear="all" />
			</td>
			<td style='text-align:center; vertical-align:top'>
					<?php if(!empty($pt_sales) && is_file($basepath."/".$user_foto_dir."/".$pt_sales)){?>
					<a href='<?php echo $dirhost."/".$user_foto_dir."/".$pt_sales; ?>' class="fancybox">
						<img src='<?php echo $dirhost."/".$user_foto_dir."/".$pt_sales; ?>' class='photo' width='70%' />
					</a>
					<br clear="all">
					<?php } ?>
					<?php if(allow('delete') == 1){?>
					<a href='javascript:void()' onclick="removal('<?php echo $dt_sale->ID_CASH_FLOW; ?>','<?php echo $dt_sale->ID_PRODUCT_SALE; ?>')" class="btn btn-mini" title="Delete">
						<i class="icon-trash"></i>
					</a>
					<?php } ?>
                    <a href='javascript:void()' onclick="window.open('<?php echo $dirhost; ?>/modules/laporan_penjualan_produk/includes/invoice.php?id_facture=<?php echo $dt_sale->ID_FACTURE; ?>','Invoice','width=700, height=700')" class="btn btn-mini" title="Print Invoice">
                        <i class="icsw16-cash-register"></i>
                   </a>
					<br />
			</td>
		  </tr>
		<?php
			}
	}	
}
?>