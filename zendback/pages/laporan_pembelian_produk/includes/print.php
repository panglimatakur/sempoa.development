<?php
session_start(); 
if(!empty($_SESSION['uidkey']) && $_SESSION['uclevelkey'] == 2) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$export 		= isset($_REQUEST['export']) ? $_REQUEST['export'] 	: "";
	if($export == "true"){
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=laporan_pembelian_produk.xls");	
	}
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";

if(!empty($prompt) && $prompt == "true" && !empty($_SESSION['uidkey'])){
?>
<form id="form_print" method="post" action="<?php echo $dirhost; ?>/modules/laporan_pembelian_produk/includes/print.php" target="_new" class="">
	<div class="form-group">
    <label>Jumlah Data Ditampilkan :</label>
    <small class='code'>NB : Kosongkan Untuk Menampilkan Seluruh Data</small>
    <br>
    <input type="text" id="show_data">
    </div>
	<div class="form-group">
    <span id="print_container"></span>
    <button type="button" id="button_show" class="btn btn-sempoa-1" onClick="print_r();"><i class="icsw16-printer icsw16-white"></i>Cetak Data</button>
    <input type='hidden' name='export' value='<?php echo @$export; ?>'>
    </div>
</form>
<?php }else{
	$show_data 		= isset($_REQUEST['show_data']) ? $_REQUEST['show_data'] 	: "";
	$limit			= "";
	if(!empty($show_data))					{ $limit 		= " LIMIT 0,".$show_data."";							}

	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 		= $_REQUEST['tgl_1']; 							}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 		= $_REQUEST['tgl_2']; 							}
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 	= $sanitize->number($_REQUEST['id_kategori']); 	}
	if(!empty($_REQUEST['code']))			{ $code 		= $sanitize->str($_REQUEST['code']); 			}
	if(!empty($_REQUEST['nama']))			{ $nama 		= $sanitize->str($_REQUEST['nama']); 			}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 	= $sanitize->str($_REQUEST['deskripsi']); 		}

	if(!empty($_REQUEST['id_product']))		{ $id_product 	= $sanitize->number($_REQUEST['id_product']); 	}
	if(!empty($_REQUEST['faktur']))			{ $faktur 		= $sanitize->str($_REQUEST['faktur']); 			}
	if(!empty($_REQUEST['harga_pokok']))	{ $harga_pokok 	= $sanitize->number($_REQUEST['harga_pokok']); 	}
	if(!empty($_REQUEST['harga']))			{ $harga 		= $sanitize->number($_REQUEST['harga']); 		}
	if(!empty($_REQUEST['stock']))			{ $stock 		= $sanitize->number($_REQUEST['stock']); 		}
	if(!empty($_REQUEST['total']))			{ $total 		= $sanitize->str($_REQUEST['total']); 			}
	if(!empty($_REQUEST['lunas']))			{ $lunas 		= $sanitize->str($_REQUEST['lunas']); 			}
	if(!empty($_REQUEST['downpay']))		{ $downpay 		= $sanitize->str($_REQUEST['downpay']); 		}
	if(!empty($_REQUEST['kredit']))			{ $kredit 		= $sanitize->str($_REQUEST['kredit']); 			}

	$condition = "";
	if( !empty($tgl_1) && 
		!empty($tgl_2))					{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 	.= " AND b.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 	}
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
					*,SUM(b.BUY_PRICE*b.QUANTITY) AS SUMMARY,SUM(b.QUANTITY) AS JML,SUM(a.REMAIN) AS HUTANG 
				FROM 
					".$tpref."factures a,".$tpref."products_buys b,".$tpref."products c 
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND
					a.ID_CLIENT='".$_SESSION['cidkey']."' 
					".$condition." 
				GROUP BY a.ID_FACTURE
				ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	$q_buy 		= $db->query($query_str." ".@$limit);
	
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Laporan Pembelian"); ?>
		<div id="print_content">
            <table width="100%"> 
            <thead>
                <tr>
                  <th width="2%" style='text-align:center'>No</th>
                  <th width="13%">No Faktur / TGL</th>
                  <th width="6%">Status </th>
                  <th width="11%" style='text-align:center'>Harga Jual</th>
                  <th width="10%" style='text-align:center'>Harga Beli</th>
                    <th width="6%" style='text-align:center'>Jumlah</th>
                    <th style='text-align:right'>Total</th>
                    <th width="12%" style='text-align:right'>Bayar</th>
                    <th width="14%" style='text-align:center'>No PO</th>
                    <th width="14%" style='text-align:right'>Hutang</th>
                </tr>
            </thead>
            <tbody>
              <?php
			  	  $r 				= 0;
				  $harga_beli_all 	= 0;
				  $harga_jual_all 	= 0;
				  $jumlah_all		= 0;
				  $total_all		= 0;
				  $bayar_all		= 0;
				  $hutang_all		= 0;
                  while($dt_buy = $db->fetchNextObject($q_buy)){ 
				  	$r++;
                    $total_buy		= $dt_buy->TOTAL;
                    if($dt_buy->PAID_STATUS == "2"){
                        $paid_status = "LUNAS";	
                    }else{
                        $paid_status = "HUTANG";	
                    }
              ?>
              <tr>
                <td style='text-align:center'><?php echo $r; ?></td>
                <td>
                	<strong class="code"><?php echo $dt_buy->FACTURE_NUMBER; ?></strong>
                    <br>
                    <?php echo $dtime->date2indodate($dt_buy->TRANSACTION_DATE); ?>
                </td>
                <td><?php echo $paid_status; ?></td>
                <td style='text-align:right'><?php if(!empty($dt_buy->SALE_PRICE)){ echo money("Rp.",@$dt_buy->SALE_PRICE); } ?></td>
                <td style='text-align:right'><?php if(!empty($dt_buy->BUY_PRICE)){ echo money("Rp.",@$dt_buy->BUY_PRICE); } ?></td>
                <td style='text-align:center'><?php echo $dt_buy->QUANTITY; ?></td>
                <td width="12%" style='text-align:right'><?php if(!empty($total_buy)){ echo money("Rp.",$total_buy); } ?></td>
                <td style='text-align:right'><?php if(!empty($dt_buy->PAID)){ echo money("Rp.",@$dt_buy->PAID);}  ?></td>
                <td style='text-align:center'><strong class='code'><?php echo $dt_buy->PO_NUMBER; ?></strong></td>
                <td style='text-align:right'><?php if(!empty($dt_buy->REMAIN)){ echo money("Rp.",$dt_buy->REMAIN); }else{ echo "Rp.0,-"; } ?></td>
              </tr>
              <?php 
			  	$harga_beli_all = $harga_beli_all+$dt_buy->BUY_PRICE;
			  	$harga_jual_all = $harga_jual_all+$dt_buy->SALE_PRICE;
			  	$jumlah_all 	= $jumlah_all+$dt_buy->QUANTITY;
			  	$total_all 		= $total_all+$total_buy;
				$bayar_all 		= $bayar_all+$dt_buy->PAID;
				$hutang_all	= $dt_buy->HUTANG+$hutang_all;
			  } 
			  ?>
              <tr>
                <td colspan="2" style='text-align:center'>&nbsp;</td>
                <td style='text-align:right; font-weight:bold'>&nbsp;</td>
                <td style='text-align:right; font-weight:bold'><?php echo money("Rp.",$harga_jual_all); ?></td>
                <td style='text-align:right; font-weight:bold'>
					<?php echo money("Rp.",$harga_beli_all); ?>
               	</td>
                <td style='text-align:center'><strong><?php echo $jumlah_all; ?></strong></td>
                <td style='text-align:right'><strong><?php echo money("Rp.",$total_all); ?></strong></td>
                <td style='text-align:right'><strong><?php echo money("Rp.",$bayar_all); ?></strong></td>
                <td style='text-align:right'>&nbsp;</td>
                <td style="text-align:right" ><strong><?php echo money("Rp.",@$hutang_all); ?></strong></td>
              </tr>
            </tbody>
        	</table>        
</div>
		<div id="footer"><?php echo print_footer(); ?></div>
	</div>
</body>


	<?php if(empty($export) || (!empty($export) && $export != "true")){?>
    <script language="javascript">
        window.print();
    </script>
    <?php } ?>
<?php }
}
?>
