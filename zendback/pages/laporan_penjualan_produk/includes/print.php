<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$export 		= isset($_REQUEST['export']) ? $_REQUEST['export'] 	: "";
	if($export == "true"){
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=laporan_penjualan_produk.xls");	
	}

	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	

if(!empty($prompt) && $prompt == "true" && !empty($_SESSION['uidkey'])){
?>
<form id="form_print" method="post" action="<?php echo $dirhost; ?>/modules/laporan_penjualan_produk/includes/print.php" target="_new" class="">
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

	if(!empty($_REQUEST['faktur'])) 		{ $faktur 		= $sanitize->str($_REQUEST['faktur']); 			}
	if(!empty($_REQUEST['jual'])) 			{ $jual 		= $sanitize->str($_REQUEST['jual']);			}
	if(!empty($_REQUEST['harga'])) 			{ $harga 		= $sanitize->str($_REQUEST['harga']);			}
	if(!empty($_REQUEST['diskon'])) 		{ $diskon		= $sanitize->str($_REQUEST['diskon']);			}
	if(!empty($_REQUEST['total_jual'])) 	{ $total_jual 	= $sanitize->str($_REQUEST['total_jual']);		}
	if(!empty($_REQUEST['lunas']))			{ $lunas 		= $sanitize->str($_REQUEST['lunas']); 			}

	$condition = "";
	if( !empty($tgl_1) && 
		!empty($tgl_2))		{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 	.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; }
		if(!empty($faktur))			{ $condition 	.= " AND a.FACTURE_NUMBER	LIKE '%".$faktur."%'";			}
		if(!empty($keterangan))		{ $condition 	.= " AND a.NOTE 	LIKE '%".$keterangan."%'";		}
		if(!empty($lunas))			{ $condition 	.= " AND a.PAID_STATUS 			= '".$lunas."' "; 			}
		
		if(!empty($marketing))		{ $condition 	.= " AND b.ID_SALES				= '".$marketing."'";		}
		if(!empty($harga))			{ $condition 	.= " AND b.PRICE				= '".$harga."'";			}
		if(!empty($jual))			{ $condition 	.= " AND b.QUANTITY  			= '".$jual."'";				}
		if(!empty($diskon))			{ $condition 	.= " AND b.DISCOUNT 			= '".$diskon."'";			}
		if(!empty($total_jual))		{ $condition 	.= " AND b.TOTAL 				= '".$total_jual."'";		}
		
		if(!empty($id_kategori))	{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
		if(!empty($code))			{ $condition 	.= " AND c.CODE 				= '".$code."' "; 			}
		if(!empty($nama))			{ $condition 	.= " AND c.NAME				LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))		{ $condition 	.= " AND c.DESCRIPTION 		LIKE '%".$deskripsi."%' "; 		}
	$query_str	= "
				SELECT 	
					*,SUM(b.PRICE*b.QUANTITY) AS SUMMARY,SUM(b.QUANTITY) AS JML,a.REMAIN AS PIUTANG  
				FROM 
					".$tpref."factures a ,".$tpref."products_sales b,".$tpref."products c
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") 
					".$condition." 
					GROUP BY a.ID_FACTURE
					ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	$q_sale		= $db->query($query_str." ".@$limit);
	
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Laporan Penjualan"); ?>
		<div id="print_content">
            <table width="100%"> 
            <thead>
                <tr>
                  <th width="2%" style='text-align:center'>No</th>
                  <th width="13%">No Faktur / TGL</th>
                  <th width="16%">Marketing</th>
                  <th width="6%">Status</th>
                  <th width="8%" style='text-align:right'>Harga</th>
                    <th width="6%" style='text-align:center'>Jumlah</th>
                    <th width="6%" style='text-align:center'>Diskon</th>
                    <th style='text-align:right'>Total</th>
                    <th width="13%" style='text-align:right'>Bayar</th>
                    <th width="9%" style='text-align:center'>No PO</th>
                    <th width="9%" style='text-align:right'>Piutang</th>
                </tr>
            </thead>
            <tbody>
              <?php
			  	  $r 			= 0;
				  $jumlah_all	= 0;
				  $total_all	= 0;
				  $bayar_all	= 0;
				  $piutang_all	= 0;
                  while($dt_sale = $db->fetchNextObject($q_sale)){ 
            		$r++;
					$total			= "";
					$total			= $dt_sale->PRICE*$dt_sale->QUANTITY;
					$q_marketing 	= $db->query("SELECT USER_NAME,USER_PHOTO FROM system_users_client WHERE ID_USER='".$dt_sale->ID_SALES."'");
					$dt_marketing 	= $db->fetchNextObject($q_marketing);
					$nm_sales		= $dt_marketing->USER_NAME;
					$pt_sales		= $dt_marketing->USER_PHOTO;
					if(!empty($diskon)){
						$diskon_new	= $total*($dt_sale->DISCOUNT/100);
						$total 		= $total-$diskon_new;
					}
                    if($dt_sale->PAID_STATUS == "2"){
                        $paid_status = "LUNAS";	
                    }else{
                        $paid_status = "PIUTANG";	
                    }
					if(empty($diskon)){ $diskon = "0%"; }
              ?>
              <tr>
                <td style='text-align:center'><?php echo $r; ?></td>
                <td>
                	<strong class="code"><?php echo $dt_sale->FACTURE_NUMBER; ?></strong>
                    <br>
                    <?php echo $dtime->date2indodate($dt_sale->TRANSACTION_DATE); ?>
                </td>
                <td><?php echo $nm_sales; ?></td>
                <td><?php echo $paid_status; ?></td>
                <td style='text-align:right'><?php if(!empty($dt_sale->PRICE)){ echo money("Rp.",@$dt_sale->PRICE); } ?></td>
                <td style='text-align:center'><?php echo $dt_sale->QUANTITY; ?></td>
                <td style='text-align:center'><?php echo $diskon; ?></td>
                <td width="12%" style='text-align:right'><?php if(!empty($total)){ echo money("Rp.",$total); } ?></td>
                <td style='text-align:right'>
					<?php if(!empty($dt_sale->PAID)){ echo money("Rp.",@$dt_sale->PAID);}else{ echo "Rp.0,-"; }  ?>
                </td>
                <td style='text-align:center'><strong class='code'><?php echo $dt_sale->PO_NUMBER; ?></strong></td>
                <td style='text-align:right'>
					<?php if(!empty($dt_sale->REMAIN)){ echo money("Rp.",$dt_sale->REMAIN); }else{ echo "Rp.0,-"; } ?>
                </td>
              </tr>
              <?php 
			  	$jumlah_all = $jumlah_all+$dt_sale->QUANTITY;
			  	$total_all 	= $total_all+$total;
				$bayar_all 	= $bayar_all+$dt_sale->PAID;
				$piutang_all= $dt_sale->PIUTANG	+$piutang_all;
			  } 
			  ?>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style='text-align:center'><strong><?php echo $jumlah_all; ?></strong></td>
                <td style='text-align:center'><strong><?php echo $diskon; ?></strong></td>
                <td style='text-align:right'><strong><?php echo money("Rp.",$total_all); ?></strong></td>
                <td style='text-align:right'><strong><?php echo money("Rp.",$bayar_all); ?></strong></td>
                <td style='text-align:right'>&nbsp;</td>
                <td style='text-align:right'><strong><?php echo money("Rp.",@$piutang_all); ?></strong></td>
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
