<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 		= isset($_POST['direction']) 			? $_POST['direction'] : "";
	$id_facture 	= isset($_REQUEST['id_facture'])		? $sanitize->number($_REQUEST['id_facture']) :"";
	$termin 		= isset($_REQUEST['termin'])			? $sanitize->number($_REQUEST['termin']) :"";
	$id_product_buy = isset($_REQUEST['id_product_buy']) 	? $sanitize->number($_REQUEST['id_product_buy']) :"";
	$keterangan 	= isset($_REQUEST['keterangan']) 		? $sanitize->str($_REQUEST['keterangan']) :"";
	$tgl_tempo 		= isset($_REQUEST['tgl_tempo']) 		? $sanitize->str($_REQUEST['tgl_tempo']) :"";
	if(!empty($tgl_tempo)){
		$tgl_tempo		= $dtime->indodate2date(@$tgl_tempo);
	}
	$tgl_bayar 		= isset($_REQUEST['tgl_bayar']) 		? $_REQUEST['tgl_bayar'] :"";
	if(!empty($tgl_bayar)){
		$tgl_bayar		= $dtime->indodate2date(@$tgl_bayar);
	}
	$bayar 			= isset($_REQUEST['bayar']) 		? $sanitize->str($_REQUEST['bayar']) 	:"";
	$total 			= isset($_REQUEST['total']) 		? $sanitize->str($_REQUEST['total']) :"";
	$parent_id		= 3;	
	
	$str_buy	= "
				SELECT *,SUM(b.TOTAL) AS SUMMARY
				FROM 
					".$tpref."factures a,".$tpref."products_buys b
				WHERE 
					a.ID_FACTURE 	= b.ID_FACTURE AND
					a.ID_FACTURE 	='".$id_facture."' AND  
					a.ID_CLIENT		='".$_SESSION['cidkey']."' 
				ORDER BY 
					b.ID_PRODUCT_BUY DESC";
	$q_buy 	 			= $db->query($str_buy);
	$dt_buy				= $db->fetchNextObject($q_buy);
	@$id_product 		= $dt_buy->ID_PRODUCT;
	@$id_facture 		= $dt_buy->ID_FACTURE;
	@$faktur 			= $dt_buy->FACTURE_NUMBER;
	@$id_partner 		= $dt_buy->ID_PARTNER;
	@$harga_pokok 		= $dt_buy->BUY_PRICE;
	@$harga 			= $dt_buy->SALE_PRICE;
	@$stock 			= $dt_buy->QUANTITY;
	@$total				= $dt_buy->SUMMARY;
	@$id_cash_flow		= $dt_buy->ID_CASH_FLOW;
	@$lunas 			= $dt_buy->PAID_STATUS;
	@$nopo 				= $dt_buy->PO_NUMBER;
	@$paid 				= $dt_buy->PAID;
	@$kredit 			= $dt_buy->REMAIN;
	@$tgl_beli			= $dt_buy->TRANSACTION_DATE;
	
	if(!empty($direction) && $direction == "save"){
		$product_direction	= "16";
		$status_lunas		= $lunas;	
		
		if($bayar >= $kredit){
			$status_lunas	= "2";	
			$reminder_content	= array(1=>array("STATUS",1));
			$db->update($tpref."debt_credit_reminder",$reminder_content," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW = '".$id_cash_flow."'");
			
			$notification_content	= array(1=>array("NOTIFICATION_STATUS",1));
			$db->update($tpref."notifications",$notification_content," WHERE FOR_ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW = '".$id_cash_flow."'");
		}
		
		$new_bayar			= $paid+$bayar;
		$sisa				= $total-$new_bayar;
		insert_decre($id_cash_flow,"1","-",$bayar,$sisa,$tgl_bayar);
		save_cash($id_cash_flow,"1",$total,$new_bayar,$product_direction);
		$prod_content 		= array(1=>
							  array("PAID_STATUS",@$status_lunas),
							  array("PAID",@$new_bayar),
							  array("REMAIN",@$sisa),
							  array("BY_ID_USER",$_SESSION['uidkey']),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
		$db->update($tpref."factures",$prod_content," WHERE ID_FACTURE = '".$id_facture."' AND ID_CASH_FLOW='".$id_cash_flow."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
				
	}
	
	if((!empty($direction) && ($direction == "save" || $direction == "delete"))){
		$prod_buy_his  = array(1=>
						  array("ID_CLIENT",$_SESSION['cidkey']),
						  array("ID_PRODUCT",@$id_product),
						  array("ID_FACTURE",@$id_facture),
						  array("ID_PARTNER",@$id_partner),
						  array("BUY_PRICE",@$harga_pokok),
						  array("SALE_PRICE",@$harga),
						  array("QUANTITY",@$stock),
						  array("TOTAL",@$total),
						  array("ID_CASH_FLOW",@$id_cash_flow),
						  array("FACTURE_NUMBER",@$faktur),
						  array("PAID_STATUS",@$status_lunas),
						  array("TERMS",@$termin),
						  array("PO_NUMBER",@$nopo),
						  array("PAID",@$new_bayar),
						  array("REMAIN",@$sisa),
						  array("ID_DIRECTION",@$product_direction),
						  array("BUY_DATE",@$tgl_beli),
						  array("BY_ID_USER",$_SESSION['uidkey']),
						  array("TGLUPDATE",@$tglupdate),
						  array("WKTUPDATE",@$wktupdate));
		$db->insert($tpref."products_buys_history",$prod_buy_his);
		
		if(!empty($direction) && $direction == "save"){
			
			$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_buy->ID_PRODUCT."'");
			@$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_buy->ID_PRODUCT_UNIT."'"); 
			$total_buy			= $dt_buy->TOTAL;
			if($status_lunas == "2"){
				$paid_status = "LUNAS";	
			}else{
				$paid_status = "HUTANG";	
			}
			if($status_lunas != "2"){
	?>
    <tr class="wrdLatest" data-info='<?php echo $dt_buy->ID_FACTURE; ?>' id="tr_<?php echo $dt_buy->ID_FACTURE; ?>">
        <td style="vertical-align:top;">
            <span class='code'>
				<b>NO FAKTUR : <?php echo $dt_buy->FACTURE_NUMBER; ?></b>
            </span>
            <br>
            <small>
				<i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_buy->TRANSACTION_DATE); ?> 
            </small>
			<?php include $call->inc($inc_dir,"list.php"); ?>
        </td>
        <td style='text-align:center'>
        <?php if(allow('insert') == 1){?>
                <a href="<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo @$page; ?>&direction=edit&no=<?php echo $dt_buy->ID_FACTURE; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Edit">
                    <i class="icsw16-money"></i>
                </a>
        <?php } ?>
        <?php if(allow('delete') == 1){?>
                <a href="javascript:void()" class="btn btn-mini" title="Hapus" onclick="del_debcre()">
                    <i class="icsw16-trashcan"></i>
                </a>
        <?php } ?>
        </td>
    </tr>
    <?php	
			}
		}
	}
}
?>