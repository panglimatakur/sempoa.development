<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
	$id_type_report = isset($_REQUEST['id_type_report']) 	? $_REQUEST['id_type_report'] : "";

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
		
		$marketing 	= isset($_REQUEST['marketing'])	? $sanitize->number($_REQUEST['marketing']) :""; 
		$faktur 	= isset($_POST['faktur']) 		? $sanitize->str($_POST['faktur']) 			: "";
		$harga 		= isset($_POST['harga']) 		? $sanitize->str($_POST['harga']) 			: "";
		$keterangan = isset($_REQUEST['keterangan'])? $_REQUEST['keterangan']					:""; 
		
		if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 	}
		if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 			}
		if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 			}
		if(!empty($_REQUEST['satuan']))			{ $satuan 			= $sanitize->str($_REQUEST['satuan']); 			}
		if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 		}
		
		if(empty($tgl_1)){ $tgl_1_new = date("d/m/Y"); } 
		if(empty($tgl_2)){ 
			$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
			$tgl_1_new 		=  date("d/m/Y", $dateformat);
		} 
		
		$condition  = ""; 
		if( !empty($tgl_1) && 
			!empty($tgl_2))			{ 
				$tgl_1_new		= $dtime->date2sysdate($tgl_1);
				$tgl_2_new		= $dtime->date2sysdate($tgl_2);
				$condition 	.= " AND a.SALE_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 				}
		if(!empty($faktur))			{ $condition 	.= " AND a.FACTURE_NUMBER	LIKE '%".$faktur."%'";			}
	
		if(!empty($marketing))		{ $condition 	.= " AND b.ID_SALES				= '".$marketing."'";		}
		if(!empty($harga))			{ $condition 	.= " AND b.PRICE				= '".$harga."'";			}
		if(!empty($keterangan))		{ $condition 	.= " AND b.NOTE 	LIKE '%".$keterangan."%'";		}
		
		if(!empty($id_kategori))	{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
		if(!empty($code))			{ $condition 	.= " AND c.CODE 				= '".$code."' "; 			}
		if(!empty($nama))			{ $condition 	.= " AND c.NAME				LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))		{ $condition 	.= " AND c.DESCRIPTION 		LIKE '%".$deskripsi."%' "; 		}
	
		$query_str	= "
					SELECT *,SUM(b.TOTAL) AS SUMMARY
					FROM 
						".$tpref."factures a,".$tpref."products_sales b,".$tpref."products c
					WHERE 
						a.ID_FACTURE = b.ID_FACTURE AND
						b.ID_PRODUCT 	= c.ID_PRODUCT AND 
						a.PAID_STATUS = '3' AND
						a.ID_CASH_FLOW > '".$lastID."' AND 
						a.ID_CLIENT		= '".$_SESSION['cidkey']."' 
						".$condition." 
					GROUP BY a.ID_FACTURE
					ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
		$q_sale		= $db->query($query_str." LIMIT 0,10");
	
		  while($dt_sale = $db->fetchNextObject($q_sale)){ 
			$photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_sale->ID_PRODUCT."'");
			@$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_sale->ID_PRODUCT_UNIT."'"); 
			
			$num_kolektif	= "";
			$kolektif		= "";
			$paid_status	= "";
			$total			= $dt_sale->PRICE*$dt_sale->QUANTITY;
			
			$q_marketing 	= $db->query("SELECT USER_NAME,USER_PHOTO FROM system_users_client WHERE ID_USER='".$dt_sale->ID_SALES."'");
			$dt_marketing 	= $db->fetchNextObject($q_marketing);
			@$nm_sales		= $dt_marketing->USER_NAME;
			@$pt_sales		= $dt_marketing->USER_PHOTO;
			
			if($dt_sale->PAID_STATUS == "2"){
				$paid_status = "LUNAS";	
			}else{
				$paid_status = "PIUTANG";	
			}
			$new_bayar 	= $dt_sale->PAID;
			$sisa		= $dt_sale->REMAIN;
		   ?>
		  <tr class="wrdLatest" data-info='<?php echo $dt_sale->ID_CASH_FLOW; ?>'  id="tr_<?php echo $dt_sale->ID_CASH_FLOW; ?>">
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
				<?php  
				   include $call->inc($inc_dir,"list.php");
				?>
				<br clear="all" />
			</td>
			<td style='text-align:center; vertical-align:top'>
				<?php if(allow('insert') == 1){?>
						<a href="<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>&direction=edit&no=<?php echo $dt_sale->ID_PRODUCT_SALE; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Bayar">
							<i class="icsw16-money"></i>
						</a>
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
?>