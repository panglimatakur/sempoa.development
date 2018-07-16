<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	
	$parent_id		= 70;
	$direction 		= isset($_POST['direction']) 		? $_POST['direction'] : "";
	if(!empty($direction)){
		function update_stock($flow,$first_stock,$sum,$tgl,$id_direction){
			global $db;
			global $tpref;
			global $tglupdate;
			global $wktupdate;
			global $id_product;
			if($flow == "save")		{ $condition = "STOCK = (STOCK-".$first_stock.")+".$sum.","; }
			if($flow == "delete")	{ $condition = "STOCK = (STOCK-".$sum."),"; }
			
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
		$show_list 		= isset($_REQUEST['show_list']) 	? $sanitize->number($_REQUEST['show_list']) :"";
		
		$id_facture 	= isset($_REQUEST['id_facture']) 	? $sanitize->number($_REQUEST['id_facture']) :"";
		$id_product_buy = isset($_REQUEST['id_product_buy'])? $sanitize->number($_REQUEST['id_product_buy']) :"";
		$id_product 	= isset($_REQUEST['id_product']) 	? $sanitize->number($_REQUEST['id_product']) :"";
		$tgl_beli 		= isset($_REQUEST['tgl_beli']) 		? $sanitize->str($_REQUEST['tgl_beli']) :"";
		if(!empty($tgl_beli)){
			$tgl_beli		= $dtime->indodate2date(@$tgl_beli);
		}
		$id_partner 	= isset($_REQUEST['id_partner']) 	? $sanitize->number($_REQUEST['id_partner']) :"";
		$harga_pokok 	= isset($_REQUEST['harga_pokok']) 	? $sanitize->number($_REQUEST['harga_pokok']) :"";
		$harga 			= isset($_REQUEST['harga']) 		? $sanitize->number($_REQUEST['harga']) :"";
		$stock 			= isset($_REQUEST['stock']) 		? $sanitize->number($_REQUEST['stock']) :"";
		$total 			= isset($_REQUEST['total']) 		? $sanitize->str($_REQUEST['total']) :"";
		
		$id_cash_flow 	= isset($_REQUEST['id_cash_flow']) 	? $sanitize->number($_REQUEST['id_cash_flow']) :"";
		$faktur 		= isset($_REQUEST['faktur']) 		? $sanitize->str(strtoupper($_REQUEST['faktur'])) :"";
		$lunas 			= isset($_REQUEST['lunas']) 		? $sanitize->str($_REQUEST['lunas']) :"";
		$nopo 			= isset($_REQUEST['nopo']) 			? $sanitize->str(strtoupper($_REQUEST['nopo'])) :"";
		$downpay 		= isset($_REQUEST['downpay']) 		? $sanitize->str($_REQUEST['downpay']) :"";
		$termin 		= isset($_REQUEST['termin']) 		? $sanitize->number($_REQUEST['termin']) 	:"";
		$tgl_tempo 		= isset($_REQUEST['tgl_tempo']) 	? $_REQUEST['tgl_tempo'] 				:"";
		$st_termin 		= isset($_REQUEST['st_termin']) 	? $sanitize->str($_REQUEST['st_termin']) 	:"";
		$kredit 		= isset($_REQUEST['kredit']) 		? $sanitize->str($_REQUEST['kredit']) :"";
		$keterangan 	= isset($_REQUEST['keterangan']) 	? $_REQUEST['keterangan'] :"";

		$first_stock 		= isset($_REQUEST['first_stock']) 		? $sanitize->str($_REQUEST['first_stock']) 			: "";
		$real_total_beli 	= isset($_REQUEST['real_total_beli']) 	? $sanitize->str($_REQUEST['real_total_beli']) 	: "";
		$real_total_bayar 	= isset($_REQUEST['real_total_bayar']) 	? $sanitize->str($_REQUEST['real_total_bayar']) 	: "";
	
		$total_beli		= ($real_total_bayar - $real_total_beli) + $total;
		//echo $total_beli."= (".$real_total_bayar."+".$real_total_beli.") - ".$total;
		$paid 			= $total_beli;

		if(!empty($downpay)){
			$paid = $downpay;
		}else{
			$paid   = 0;
			$kredit	= $total;
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
		if(!empty($direction) && $direction == "save"){
			$product_direction  	= "2";
			$original_cash			= $db->fob("PAID",$tpref."factures","WHERE ID_CASH_FLOW='".$id_cash_flow."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");
			$cash_residual_value 	= save_cash($id_cash_flow,"2",$total_beli,$paid,$product_direction);
			$check_debcre			= $db->recount("SELECT ID_CASH_FLOW FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$id_cash_flow."' ");
			if($lunas != 2){
				if($check_debcre > 0){ save_decre($id_cash_flow,$total_beli,$total_beli,$tgl_beli,"+"); }
				else				 { insert_decre($id_cash_flow,"1","+",$total_beli,"",$tgl_beli); }
				if(!empty($downpay)){
					if($check_debcre > 0){ save_decre($id_cash_flow,$paid,$kredit,$tgl_beli,"-");	}
					else				 { insert_decre($id_cash_flow,"1","-",$paid,$kredit,$tgl_beli);	}
				}
			}
			
			$db->delete($tpref."debt_credit_reminder"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND STATUS = '0'");
			$tempo 			= 0;
			$tgl_tempos 	= substr_count($tgl_tempo,";");
			$tgl_tempo_edit = explode(";",$tgl_tempo);
			$st_termins		= explode(";",$st_termin);
			$tgl_tempox	 	= "";
			while($tempo < $tgl_tempos){
				$tempo++;
				if($st_termins[$tempo] != "1"){
				$tgl_tempo	= "";
				$tgl_tempo	= $dtime->indodate2date(@$tgl_tempo_edit[$tempo]);
				$tgl_tempox .= $tgl_tempo;
				$content 	= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CASH_FLOW",$id_cash_flow),
								array("DEBT_CREDIT","1"),
								array("ORDINAL",$tempo),
								array("STATUS","0"),
								array("REMINDER_DATE",$tgl_tempo));
				$db->insert($tpref."debt_credit_reminder",$content);
				}
			}
			$factur_content = array(1=>
							  array("ID_CLIENT",@$_SESSION['cidkey']),
							  array("ID_PARTNER",@$id_partner),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTURE_NUMBER",@$faktur),
							  array("PAID_STATUS",@$lunas),
							  array("TERMS",@$termin),
							  array("PO_NUMBER",@$nopo),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit),
							  array("NOTE",@$keterangan),
							  array("BY_ID_USER",@$_SESSION['uidkey']),
						  	  array("TRANSACTION_DATE",@$tgl_beli),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->update($tpref."factures",$factur_content," WHERE ID_FACTURE = '".$id_facture."'"); 
			//echo $tpref."factures PAID='".$paid."' WHERE ID_FACTURE = '".$id_facture."'";
			$prod_content = array(1=>
							  array("BUY_PRICE",@$harga_pokok),
							  array("SALE_PRICE",@$harga),
							  array("QUANTITY",@$stock),
							  array("TOTAL",@$total),
							  array("BY_ID_USER",$_SESSION['uidkey']));
			$db->update($tpref."products_buys",$prod_content," WHERE ID_PRODUCT_BUY='".$id_product_buy."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");
			update_stock("save",$first_stock,$stock,$tgl_beli,$product_direction);
			$show_list 		= 1;
			$save_history	= 1;
		}
	
		if(!empty($direction) && ($direction == "delete" || $direction == "delete_single")){
			$product_direction 	= "3";			
			if($direction == "delete")		 { $condition = "AND a.ID_CASH_FLOW='".$id_product_buy."' "; 		$delete_condition = 2; }
			if($direction == "delete_single"){ $condition = "AND b.ID_PRODUCT_BUY ='".$id_product_buy."' "; 	$delete_condition = 1; }

			$q_buy 	 	= $db->query("SELECT * FROM ".$tpref."factures a ,".$tpref."products_buys b WHERE a.ID_FACTURE = b.ID_FACTURE AND a.ID_CLIENT='".$_SESSION['cidkey']."' ".$condition."");
			while($dt_buy		= $db->fetchNextObject($q_buy)){
				@$id_cash_flow 		= $dt_buy->ID_CASH_FLOW;
				@$id_product 		= $dt_buy->ID_PRODUCT;
				@$id_partner 		= $dt_buy->ID_PARTNER;
				@$id_facture 		= $dt_buy->ID_FACTURE;
				@$harga_pokok 		= $dt_buy->BUY_PRICE;
				@$harga 			= $dt_buy->SALE_PRICE;
				@$stock 			= $dt_buy->QUANTITY;
				@$total 			= $dt_buy->TOTAL;
				@$total_all			= $db->sum("TOTAL",$tpref."products_buys"," WHERE ID_FACTURE='".$dt_buy->ID_FACTURE."'");
				@$faktur 			= $dt_buy->FACTURE_NUMBER;
				@$lunas 			= $dt_buy->PAID_STATUS;
				@$nopo 				= $dt_buy->PO_NUMBER;
				@$paid 				= $dt_buy->PAID;
				@$kredit 			= $dt_buy->REMAIN;
				@$tgl_beli			= $dt_buy->BUY_DATE;
			
				if($delete_condition ==  1){ 
					$delete_condition 	= "AND ID_PRODUCT_BUY ='".$dt_buy->ID_PRODUCT_BUY."'";
				}else{
					$delete_condition 	= "AND ID_FACTURE ='".$dt_buy->ID_FACTURE."'";
				}
				$db->delete($tpref."products_buys"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' ".$delete_condition);
				update_stock("delete",$stock,$stock,$tgl_beli,$product_direction);
			}
			
			$num_list				= $db->recount("SELECT * FROM ".$tpref."products_buys WHERE ID_FACTURE='".$id_facture."'");
			
			if($direction == "delete"){ 
				$cash_residual_value = delete_cash($id_cash_flow,$product_direction);
				delete_decre($id_cash_flow);
				echo $cash_residual_value;
			}else{
				$cash_condition 		= "";
				if($lunas == 2){
					$new_total		= $paid - $total;
					//echo $new_total."=".$paid." + ".$total;
					if($num_list > 0){
						$cash_residual_value = save_cash($id_cash_flow,2,$new_total,$new_total,$product_direction);
					}else{
						$cash_residual_value = delete_cash($id_cash_flow,2,$new_total,$new_total,$product_direction);	
					}
					$cash_condition .= "PAID='".$new_total."',";
				}else{
					$result 		= array();
					$cash_condition	= "";
					$new_total		= $paid - $total_all;
					if($total > $kredit){
						$new_total			= $total_all - $paid;
						$new_total_all		= $total_all - $total;
						$remain 			= $paid - $new_total_all;
						$cash_residual_value= save_cash($id_cash_flow,2,$new_total_all,$new_total_all,$product_direction);
						$code				= $db->fob("CODE",$tpref."products"," WHERE ID_PRODUCT='".$id_product."'");
						$note 				= "
	No Faktur <b class='code'>".$faktur."</b> ini telah ada pembayaran sebesar <b class='code'>".money("Rp.",$paid)."</b> dari total pembelian <b class='code'>".money("Rp.",$total_all)."</b>, hingga menghasilkan sisa pembayaran hutang sebesar <b class='code'>".money("Rp.",$new_total)."</b>,- yang harus anda bayarkan kepada penjual.
	
	<br><br>
	Namun, dikarenakan anda (pembeli) melakukan pembatalan (menghapus) pembelian produk kode <b class='code'>".$code."</b> yang bernilai <b class='code'>".money("Rp.",$total)."</b>, artinya, total pembayaran, seharusnya berkurang menjadi <b class='code'>".money("Rp.",$new_total_all)."</b> 
	
	<br><br>
	Dikarenakan Pembayaran yang sudah anda bayarkan kepada penjual, sebesar <b class='code'>".money("Rp.",$paid)."</b> tidak sesuai dengan total pembelian seharusnya yang sebesar <b class='code'>".money("Rp.",$new_total_all)."</b>, terjadi lebih bayar, yang menjadi beban piutang sebesar <b class='code'>".money("Rp.",$remain)."</b> yang harus di kembalikan kepada anda, dan hutang pembelian otomatis  menjadi status lunas.
	";
						$keterangan 		 = "Piutang lebih bayar pembelian No Faktur <b class='code'>".$faktur."</b>";
						insert_decre($id_cash_flow,2,"+",$new_total,0,$tglupdate);
						$id_cash_type		= 77;
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
						$cash_condition 	.= "PAID = '".$new_total_all."',PAID_STATUS='2',";
						$result['note'] 	 = $note;
					}
					$cash_condition .= "REMAIN='',";
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
			while($dt_reminder = $db->fetchNextObject($q_ordinal)){ $f++;
				$db->query("UPDATE ".$tpref."debt_credit_reminder SET ORDINAL='".$f."' WHERE ID_DEBT_CREDIT_REMINDER='".$dt_reminder->ID_DEBT_CREDIT_REMINDER."'");
			}
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
			$prod_buy_his  = array(1=>
							  array("ID_CLIENT",$_SESSION['cidkey']),
							  array("ID_PRODUCT",@$id_product),
							  array("ID_FACTURE",@$id_facture),
							  array("BUY_PRICE",@$harga_pokok),
							  array("SALE_PRICE",@$harga),
							  array("QUANTITY",@$stock),
							  array("TOTAL",@$total),
							  array("ID_DIRECTION",@$product_direction),
							  array("ID_CASH_FLOW",@$id_cash_flow),
							  array("FACTUR_NUMBER",@$faktur),
							  array("PAID_STATUS",@$lunas),
							  array("PO_NUMBER",@$nopo),
							  array("PAID",@$paid),
							  array("REMAIN",@$kredit),
							  array("BY_ID_USER",$_SESSION['uidkey']),
						  	  array("TRANSACTION_DATE",@$tgl_beli),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."products_buys_history",$prod_buy_his);
					
			$catalog_content = array(1=>array("SALE_PRICE",@$harga));
			$db->update($tpref."products",$catalog_content," WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
		}
		
		if($show_list == 1){
			$str_buy	= "
				SELECT 	
					*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,a.REMAIN AS HUTANG  
				FROM 
					".$tpref."factures a ,".$tpref."products_buys b,".$tpref."products c
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					a.MODULE 	 = 'BUY' AND
					a.ID_CLIENT='".$_SESSION['cidkey']."' AND
					a.ID_CASH_FLOW='".$id_cash_flow."'";
			//echo $str_buy;
			$q_buy 				= $db->query($str_buy); 
			$dt_buy 			= $db->fetchNextObject($q_buy);	
		    if($dt_buy->PAID_STATUS == "2"){
                $paid_status = "LUNAS";	
            }else{
                $paid_status = "<a href='".$dirhost."/?page=hutang_pembelian_produk&id_buy=".$dt_buy->ID_PRODUCT_BUY."'>HUTANG</a>";	
            }
	
	?>
          <tr class="wrdLatest" data-info='<?php echo $dt_buy->ID_CASH_FLOW; ?>'  id="tr_<?php echo $dt_buy->ID_CASH_FLOW; ?>">
            <td style="vertical-align:top; text-align:center">
                <input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_buy->ID_CASH_FLOW; ?>'/>
            </td>
            <td style="vertical-align:top; position:relative;">
                <span class='code'>
                    <b>NO FAKTUR : <?php echo $dt_buy->FACTURE_NUMBER; ?></b>
					<?php if(!empty($dt_buy->ID_PARTNER)){?>
                        <br />
                        Pembelian Dari : <?php echo $db->fob("PARTNER_NAME",$tpref."partners"," WHERE ID_PARTNER='".$dt_buy->ID_PARTNER."'"); ?>
                    <?php } ?>
                </span>
                <br>
                <small>
                    <i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_buy->TRANSACTION_DATE); ?>  
                </small>
                <?php  include $call->inc("modules/laporan_pembelian_produk/includes","list_kolektif.php"); ?>
            </td>
            <td style='text-align:center'>
                    <a href='javascript:void()' onclick="removal('<?php echo $dt_buy->ID_CASH_FLOW; ?>','<?php echo $dt_buy->ID_PRODUCT_BUY; ?>')" class="btn btn-mini" title="Delete">
                        <i class="icon-trash"></i>
                    </a>
            </td>
          </tr>
    <?php	
			}
	}
	
	if(!empty($direction) && $direction == "search_produk"){
		include $call->inc("modules/".$page."/includes","product_list.php"); 
	}
	if(!empty($direction) && $direction == "add_supplier"){
		$id_product 	= isset($_REQUEST['id_product']) 	? $_REQUEST['id_product'] :"";
		$nama 			= isset($_REQUEST['nama']) 	? $sanitize->str(ucwords($_REQUEST['nama'])) :"";
		$container = array(1=>
			array("ID_CLIENT",$_SESSION['cidkey']),
			array("PARTNER_NAME",$nama),
			array("TGLUPDATE",$tglupdate));
		$db->insert($tpref."partners",$container);
		$id_partner = mysql_insert_id();
	?>
<div class="category" id='id_cat_<?php echo $id_product; ?>'>
        <select id='id_partner_<?php echo $id_product; ?>' style='margin:0'>
            <option value=''>--PILIH SUPPLIER--</option>
			<?php 
                $q_partner = $db->query("SELECT * FROM ".$tpref."partners WHERE ID_CLIENT='".$_SESSION['cidkey']."'");  
                while($dt_partner = $db->fetchNextObject($q_partner)){
            ?>
                <option value='<?php echo $dt_partner->ID_PARTNER; ?>' <?php if(!empty($id_partner) && $id_partner == $dt_partner->ID_PARTNER){ ?>selected<?php } ?>>
                    <?php echo $dt_partner->PARTNER_NAME; ?>
                </option>
            <?php 	
                }
            ?>
        </select>  
        <a href="javascript:void()" class='btn new_cat'>
            <i class="icon-plus"></i>Tambah Supplier
        </a>
    </div>
    <?php
	}
}
?>