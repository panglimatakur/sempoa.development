<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
		$id_type_report = isset($_REQUEST['id_type_report']) 	? $_REQUEST['id_type_report'] : "";
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');
}

if((!empty($display) && $display == "kategori_report") || !empty($id_type_report)){
    $query_kategori_report 	= $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_PRODUCT_TYPE='".$id_type_report."' AND ID_PARENT = '0' AND ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY NAME ASC");
	$num_kategori_report 	= $db->numRows($query_kategori_report);
	if($num_kategori_report > 0){
?>
<div class="form-group form-control">
    <label>Kategori</label>
    <input type="hidden" name="id_kategori" id="id_kategori" class="form-control mousetrap" value="<?php echo @$id_kategori; ?>">
    <ul class="kategori_list">
        <?php
        while($data_kategori = $db->fetchNextObject($query_kategori_report)){
            $class_selected = "";
            if(!empty($id_kategori) && $id_kategori == $data_kategori->ID_PRODUCT_CATEGORY){
                $class_selected = "class='class_selected' style='border:1px solid #F9ECF7;'";	
            }
        ?>	
            <li id="cat_<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>" <?php echo @$class_selected; ?>>
                <img src="<?php echo $dirhost; ?>/files/images/icons/bullet_go.png" />
                <a href='javascript:void()' onclick="select_category('<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>')">
                    <?php echo $data_kategori->NAME; ?>
                </a>
                <?php echo category_list($data_kategori->ID_PRODUCT_CATEGORY); ?>
            </li>
        <?php } ?>
    </ul>
</div>
<?php 
	}
} 
?>

<?php
	if((!empty($display) && $display == "list_report")){
		@$lastID 	= $_REQUEST['lastID'];
		if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 	= $_REQUEST['tgl_1']; 								}
		if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 	= $_REQUEST['tgl_2']; 								}
		
		if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 		}
		if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 				}
		if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 				}
		if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 			}
	
		if(!empty($_REQUEST['id_product']))		{ $id_product 		= $sanitize->number($_REQUEST['id_product']); 		}
		if(!empty($_REQUEST['faktur']))			{ $faktur 			= $sanitize->str($_REQUEST['faktur']); 				}
		if(!empty($_REQUEST['harga_pokok']))	{ $harga_pokok 		= $sanitize->number($_REQUEST['harga_pokok']); 		}
		
		$condition = "";
		if( !empty($tgl_1) && 
			!empty($tgl_2))					{ 
			$tgl_1_new		= $dtime->date2sysdate($tgl_1);
			$tgl_2_new		= $dtime->date2sysdate($tgl_2);
			$condition 		.= " AND b.BUY_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 							}
	
			if(!empty($id_kategori))			{ $condition 	.= " AND a.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 		}
			if(!empty($code))					{ $condition 	.= " AND a.CODE 				= '".$code."' "; 				}
			if(!empty($nama))					{ $condition 	.= " AND a.NAME					LIKE '%".$nama."%' "; 			}
			if(!empty($deskripsi))				{ $condition 	.= " AND a.DESCRIPTION 			LIKE '%".$deskripsi."%' "; 		}
			
			if(!empty($faktur))					{ $condition 	.= " AND b.FACTURE_NUMBER		LIKE '%".$faktur."%'";			}
			if(!empty($harga_pokok))			{ $condition 	.= " AND b.BUY_PRICE 			= '".$harga_pokok."' "; 		}
	
		$query_str	= "
		SELECT *,SUM(b.TOTAL) AS SUMMARY
		FROM 
			".$tpref."factures a,".$tpref."products_buys b,".$tpref."products c
		WHERE 
			a.ID_FACTURE = b.ID_FACTURE AND
			b.ID_PRODUCT 	= c.ID_PRODUCT AND 
			a.PAID_STATUS 	= '1' AND 
			b.ID_CASH_FLOW > '".$lastID."' 
			a.ID_CLIENT		= '".$_SESSION['cidkey']."' 
			".$condition." 
		GROUP BY a.ID_FACTURE
		ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
		$q_buy 		= $db->query($query_str." LIMIT 0,10");
			  while($dt_buy = $db->fetchNextObject($q_buy)){ 
				$photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_buy->ID_PRODUCT."'");
				@$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_buy->ID_PRODUCT_UNIT."'"); 
				$total_buy		= "";
				$num_kolektif	= "";
				$kolektif		= "";
				$paid_status	= "";
				$total_buy		= $dt_buy->SUMMARY;
				if($dt_buy->PAID_STATUS == "2"){
					$paid_status = "LUNAS";	
				}else{
					$paid_status = "HUTANG";	
				}
				$new_bayar 	= $dt_buy->PAID;
				$sisa		= $dt_buy->REMAIN;
		  ?>
		  <tr class="wrdLatest" data-info='<?php echo $dt_buy->ID_CASH_FLOW; ?>' id="tr_<?php echo $dt_buy->ID_CASH_FLOW; ?>">
			<td style="vertical-align:top;">
				<span class='code'>
					<b>NO FAKTUR : <?php echo $dt_buy->FACTURE_NUMBER; ?></b>
				</span>
				<br>
				<small>
					<i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_buy->TRANSACTION_DATE); ?> 
				</small>
				<?php include $call->inc($inc_dir,"list.php"); ?>
				<br clear="all" />
			</td>
			<td style='text-align:center'>
			<?php if(allow('insert') == 1){?>
					<a href="<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>&direction=edit&no=<?php echo $dt_buy->ID_PRODUCT_BUY; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Bayar">
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
		  <?php } 
	}
?>