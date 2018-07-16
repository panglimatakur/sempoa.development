<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
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
	if(!empty($_REQUEST['customer']))		{ $customer = $sanitize->number($_REQUEST['customer']); 		}
	$marketing 	= isset($_REQUEST['marketing'])		? $sanitize->number($_REQUEST['marketing']) 	:""; 
	$faktur 	= isset($_REQUEST['faktur']) 		? $sanitize->str($_REQUEST['faktur']) 			: "";
	$harga 		= isset($_REQUEST['harga']) 		? $sanitize->str($_REQUEST['harga']) 			: "";
	$jual 		= isset($_REQUEST['jual']) 			? $sanitize->str($_REQUEST['jual']) 			: "";
	$diskon 	= isset($_REQUEST['diskon']) 		? $sanitize->str($_REQUEST['diskon']) 			: "";
	$total_jual = isset($_REQUEST['total_jual']) 	? $sanitize->str($_REQUEST['total_jual']) 		: "";
	$keterangan = isset($_REQUEST['keterangan'])	? $_REQUEST['keterangan']						:""; 
	$lunas 		= isset($_REQUEST['lunas'])			? $_REQUEST['lunas']							:""; 
	$condition	= "";
	
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 	}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 			}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 			}
	if(!empty($_REQUEST['satuan']))			{ $satuan 			= $sanitize->str($_REQUEST['satuan']); 			}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 		}
	
	if( !empty($tgl_1) && 
		!empty($tgl_2))			{ 
			$tgl_1_new		= $dtime->date2sysdate($tgl_1);
			$tgl_2_new		= $dtime->date2sysdate($tgl_2);
			$condition 	.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 			}
			
		if(!empty($faktur))			{ $condition 	.= " AND a.FACTURE_NUMBER	LIKE '%".$faktur."%'";			}
		if(!empty($keterangan))		{ $condition 	.= " AND a.NOTE 	LIKE '%".$keterangan."%'";				}
		if(!empty($lunas))			{ $condition 	.= " AND a.PAID_STATUS 			= '".$lunas."' "; 			}
		if(!empty($marketing))		{ $condition 	.= " AND a.ID_SALES				= '".$marketing."'";		}
		if(!empty($customer))		{ $condition 	.= " AND a.ID_CUSTOMER			= '".$customer."'";			}

		if(!empty($harga))			{ $condition 	.= " AND b.PRICE				= '".$harga."'";			}
		if(!empty($jual))			{ $condition 	.= " AND b.QUANTITY  			= '".$jual."'";				}
		if(!empty($diskon))			{ $condition 	.= " AND b.DISCOUNT 			= '".$diskon."'";			}
		if(!empty($total_jual))		{ $condition 	.= " AND b.TOTAL 				= '".$total_jual."'";		}
		
		if(!empty($id_type_report))	{ $condition 	.= " AND c.ID_PRODUCT_TYPE 		= '".$id_type_report."' "; 	}
		if(!empty($id_kategori))	{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
		if(!empty($code))			{ $condition 	.= " AND c.CODE 				= '".$code."' "; 			}
		if(!empty($nama))			{ $condition 	.= " AND c.NAME				LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))		{ $condition 	.= " AND c.DESCRIPTION 		LIKE '%".$deskripsi."%' "; 		}
	$query_str	= "
				SELECT 	
					*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,a.REMAIN AS PIUTANG  
				FROM 
					".$tpref."factures a ,".$tpref."products_sales b,".$tpref."products c
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") AND
					a.ID_CASH_FLOW < ".$lastID." 
					".$condition." 
					GROUP BY a.ID_FACTURE
					ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	//echo $query_str." LIMIT 0,10<br>";
	$q_sale		= $db->query($query_str." LIMIT 0,10");
	$total_all		= "";
	$jumlah_all		= "";
	$piutang_all	= "";
	while($dt_sale = $db->fetchNextObject($q_sale)){ 
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
        <td style="vertical-align:top; text-align:center"><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_sale->ID_CASH_FLOW; ?>'/></td>
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
?>
<input type="hidden" id="new_total" value='"jumlah_all":"<?php echo @$jumlah_all?>","total_all":"<?php echo @$total_all; ?>","piutang_all":"<?php echo @$piutang_all; ?>"'/>
<?php
}
?>

