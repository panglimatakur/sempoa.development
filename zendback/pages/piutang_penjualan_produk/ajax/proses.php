<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 			= isset($_POST['direction']) 			? $_POST['direction'] : "";
	$id_facture 		= isset($_REQUEST['id_facture'])		? $sanitize->number($_REQUEST['id_facture']) 		:"";
	$termin 			= isset($_REQUEST['termin'])			? $sanitize->number($_REQUEST['termin']) 			:"";
	$id_product_sale 	= isset($_REQUEST['id_product_sale']) 	? $sanitize->number($_REQUEST['id_product_sale']) 	:"";
	$keterangan 		= isset($_REQUEST['keterangan']) 		? $sanitize->str($_REQUEST['keterangan']) 			:"";
	$tgl_tempo 			= isset($_REQUEST['tgl_tempo']) 		? $sanitize->str($_REQUEST['tgl_tempo']) 			:"";
	if(!empty($tgl_tempo)){
		$tgl_tempo		= $dtime->indodate2date(@$tgl_tempo);
	}
	$tgl_bayar 			= isset($_REQUEST['tgl_bayar']) 		? $_REQUEST['tgl_bayar'] :"";
	if(!empty($tgl_bayar)){
		$tgl_bayar		= $dtime->indodate2date(@$tgl_bayar);
	}
	$bayar 			= isset($_REQUEST['bayar']) 		? $sanitize->str($_REQUEST['bayar']) 	:"";
	$total 			= isset($_REQUEST['total']) 		? $sanitize->str($_REQUEST['total']) 	:"";
	$parent_id		= 4;
		
	$query_str	= "
				SELECT *,SUM(b.TOTAL) AS SUMMARY
				FROM 
					".$tpref."factures a,".$tpref."products_sales b
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND
					a.PAID_STATUS = '3' AND
					a.ID_CLIENT	= '".$_SESSION['cidkey']."'  AND
					a.ID_FACTURE = '".$id_facture."'
				ORDER BY 
					b.ID_PRODUCT_SALE DESC";
	$q_sale 	 	= $db->query($query_str);
	$dt_sale		= $db->fetchNextObject($q_sale);
	@$id_sales 		= $dt_sale->ID_SALES;
	@$id_customer 	= $dt_sale->ID_CUSTOMER;
	@$id_product	= $dt_sale->ID_PRODUCT;
	@$id_facture 	= $dt_sale->ID_FACTURE;
	@$id_cash_flow 	= $dt_sale->ID_CASH_FLOW;
	@$jual			= $dt_sale->QUANTITY;
	@$harga			= $dt_sale->PRICE;
	@$diskon		= $dt_sale->DISCOUNT;
	@$total			= $dt_sale->SUMMARY;
	@$propinsi		= $dt_sale->PROVINCE;
	@$kota			= $dt_sale->CITY;
	@$kecamatan		= $dt_sale->DISTRICT;
	@$kelurahan		= $dt_sale->SUBDISTRICT;
	@$faktur		= $dt_sale->FACTURE_NUMBER;
	@$lunas			= $dt_sale->PAID_STATUS;
	@$nopo			= $dt_sale->PO_NUMBER;
	@$paid			= $dt_sale->PAID;
	@$kredit		= $dt_sale->REMAIN;
	@$tgl_jual		= $dt_sale->TRANSACTION_DATE;

	if(!empty($direction) && $direction == "save"){

		$product_direction	= "17";
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
		insert_decre($id_cash_flow,"3","-",$bayar,$sisa,$tgl_bayar);
		save_cash($id_cash_flow,"2",$total,$new_bayar,$product_direction);
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
		$prod_content = array(1=>
						  array("ID_CLIENT",$_SESSION['cidkey']),
						  array("ID_SALES",@$id_sales),
						  array("ID_CUSTOMER",@$id_customer),
						  array("ID_PRODUCT",@$id_product),
						  array("ID_FACTURE",@$id_facture),
						  array("QUANTITY",@$jual),
						  array("PRICE",@$harga),
						  array("DISCOUNT",@$diskon),
						  array("TOTAL",@$total),
						  array("NOTE",@$keterangan),
						  array("SALE_DATE",@$tgl_jual),
						  array("PROVINCE",@$propinsi),
						  array("CITY",@$kota),
						  array("DISTRICT",@$kecamatan),
						  array("SUBDISTRICT",@$kelurahan),
						  array("ID_CASH_FLOW",@$id_cash_flow),
						  array("FACTURE_NUMBER",@$faktur),
						  array("PAID_STATUS",@$status_lunas),
						  array("TERMS",@$termin),
						  array("PO_NUMBER",@$nopo),
						  array("PAID",@$new_bayar),
						  array("REMAIN",@$sisa),
						  array("ID_DIRECTION",@$product_direction),
						  array("BY_ID_USER",$_SESSION['uidkey']),
						  array("TGLUPDATE",@$tglupdate),
						  array("WKTUPDATE",@$wktupdate));
		$db->insert($tpref."products_sales_history",$prod_content);
		
		
		if(!empty($direction) && ($direction == "insert" || $direction == "save")){
			
			$q_marketing 	= $db->query("SELECT USER_NAME,USER_PHOTO FROM system_users_client WHERE ID_USER='".$dt_sale->ID_SALES."'");
			$dt_marketing 	= $db->fetchNextObject($q_marketing);
			$nm_sales		= $dt_marketing->USER_NAME;
			$pt_sales		= $dt_marketing->USER_PHOTO;
			if($status_lunas == "2"){
				$paid_status = "LUNAS";	
			}else{
				$paid_status = "PIUTANG";	
			}
			if($status_lunas != "2"){
	?>
        <td style="vertical-align:top;">
            <span class='code'>
				<b>NO FAKTUR : <?php echo $dt_sale->FACTURE_NUMBER; ?></b>
            </span>
            <br>
            <small>
            	<i class="icsw16-day-calendar"></i>
				<?php echo $dtime->now2indodate2($dt_sale->TRANSACTION_DATE); ?> - 
                Oleh : <?php echo $nm_sales; ?>
            </small>
			<?php include $call->inc($inc_dir,"list.php"); ?>

        </td>
        <td style='text-align:center; vertical-align:top'>
			<?php if(allow('insert') == 1){?>
                    <a href="<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>&direction=edit&no=<?php echo $dt_sale->ID_FACTURE; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Bayar">
                        <i class="icsw16-money"></i>
                    </a>
                    <a href="javascript:void()" class="btn btn-mini" title="Hapus" onclick="del_debcre()">
                        <i class="icsw16-trashcan"></i>
                    </a>
            <?php } ?>
        </td>
    <?php	
			}
		}
	}
}
?>