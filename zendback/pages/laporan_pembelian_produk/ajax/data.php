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
?>

<?php 
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
	
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 		}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 				}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 				}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 			}

	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}
	if(!empty($_REQUEST['id_product']))		{ $id_product 		= $sanitize->number($_REQUEST['id_product']); 		}
	if(!empty($_REQUEST['faktur']))			{ $faktur 			= $sanitize->str($_REQUEST['faktur']); 				}
	if(!empty($_REQUEST['harga_pokok']))	{ $harga_pokok 		= $sanitize->number($_REQUEST['harga_pokok']); 		}
	if(!empty($_REQUEST['harga']))			{ $harga 			= $sanitize->number($_REQUEST['harga']); 			}
	if(!empty($_REQUEST['stock']))			{ $stock 			= $sanitize->number($_REQUEST['stock']); 			}
	if(!empty($_REQUEST['total']))			{ $total 			= $sanitize->str($_REQUEST['total']); 				}
	if(!empty($_REQUEST['lunas']))			{ $lunas 			= $sanitize->str($_REQUEST['lunas']); 				}
	if(!empty($_REQUEST['downpay']))		{ $downpay 			= $sanitize->str($_REQUEST['downpay']); 			}
	if(!empty($_REQUEST['kredit']))			{ $kredit 			= $sanitize->str($_REQUEST['kredit']); 				}

	$condition = "";
	if( !empty($tgl_1) && 
		!empty($tgl_2))					{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 		.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 							}
		if(!empty($faktur))					{ $condition 	.= " AND a.FACTURE_NUMBER		LIKE '%".$faktur."%'";			}
		if(!empty($lunas))					{ $condition 	.= " AND a.PAID_STATUS 			= '".$lunas."' "; 				}

		if(!empty($harga_pokok))			{ $condition 	.= " AND b.BUY_PRICE 			= '".$harga_pokok."' "; 		}
		if(!empty($harga))					{ $condition 	.= " AND b.SALE_PRICE 			= '".$harga."' "; 				}
		if(!empty($stock))					{ $condition 	.= " AND b.QUANTITY 			= '".$stock."' "; 			}
		if(!empty($total))					{ $condition 	.= " AND b.TOTAL 				= '".$total."' "; 				}
		
		if(!empty($id_kategori))			{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 		}
		if(!empty($code))					{ $condition 	.= " AND c.CODE 				= '".$code."' "; 				}
		if(!empty($nama))					{ $condition 	.= " AND c.NAME					LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))				{ $condition 	.= " AND c.DESCRIPTION 			LIKE '%".$deskripsi."%' "; 		}
	
	$query_str	= "
				SELECT 
					*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,a.REMAIN AS HUTANG 
				FROM 
					".$tpref."factures a,".$tpref."products_buys b 
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					a.ID_CLIENT='".$_SESSION['cidkey']."' AND 
					a.ID_CASH_FLOW < ".$lastID." 
					".$condition." 
				GROUP BY a.ID_FACTURE
				ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	//echo $query_str." LIMIT 0,10";
	$q_buy 		= $db->query($query_str." LIMIT 0,10");

		$total_all		= 0;
		$jumlah_all		= 0;
		$hutang_all		= 0;
          while($dt_buy = $db->fetchNextObject($q_buy)){ 
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
        <td style="vertical-align:top; position:relative;" >
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
			<?php  
			include $call->inc("modules/laporan_pembelian_produk/includes","list_kolektif.php");
			$total_all 	= $dt_buy->SUMMARY	+$total_all;
            $jumlah_all	= $dt_buy->JML		+$jumlah_all;
			$hutang_all	= $dt_buy->HUTANG+$hutang_all;
            ?>
            <br style="clear:both">
        </td>
        <td style='text-align:center'>
        <?php if(allow('delete') == 1){?>
                <a href='javascript:void()' onclick="removal('<?php echo $dt_buy->ID_CASH_FLOW; ?>','<?php echo $dt_buy->ID_PRODUCT_BUY; ?>')" class="btn btn-mini" title="Delete">
                    <i class="icon-trash"></i>
                </a>
        <?php } ?>
        </td>
      </tr>
      <?php }
	  if(empty($total_all)){ $total_all = "0"; }
	  if(empty($jumlah_all)){ $jumlah_all = "0"; }
	  if(empty($hutang_all)){ $hutang_all = "0"; }
?>
<input type="hidden" id="new_total" value='"jumlah_all":"<?php echo @$jumlah_all?>","total_all":"<?php echo @$total_all; ?>","hutang_all":"<?php echo @$hutang_all; ?>"'/>
<?php
}
?>
