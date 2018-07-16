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
		header("Content-Disposition: attachment; filename=piutang_penjualan_produk.xls");	
	}
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
}
if(!empty($prompt) && $prompt == "true" && !empty($_SESSION['uidkey'])){
?>
<form id="form_print" method="post" action="<?php echo $dirhost; ?>/modules/piutang_penjualan_produk/includes/print.php" target="_new" class="">
	<div class="form-group">
    <label>Jumlah Data Ditampilkan :</label>
    <small class='code'>NB : Kosongkan Untuk Menampilkan Seluruh Data</small>
    <br>
    <input type="text" id="show_data">
    </div>
	<div class="form-group">
    <span id="print_container"></span>
    <button type="button" id="button_show" class="btn btn-sempoa-1" onClick="print_r();"><i class="icsw16-printer icsw16-white"></i>Cetak Data</button>
    </div>
</form>
<?php }else{ 
	$show_data 		= isset($_REQUEST['show_data']) ? $_REQUEST['show_data'] 	: "";
	$limit			= "";
	if(!empty($show_data))					{ $limit 		= " LIMIT 0,".$show_data."";							}

	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 	= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 	= $_REQUEST['tgl_2']; 								}
	
	$marketing 	= isset($_REQUEST['marketing'])	? $sanitize->number($_REQUEST['marketing']) :""; 
	$faktur 	= isset($_POST['faktur']) 		? $sanitize->str($_POST['faktur']) 			: "";
	$harga 		= isset($_POST['harga']) 		? $sanitize->str($_POST['harga']) 			: "";
	$keterangan = isset($_REQUEST['keterangan'])? $_REQUEST['keterangan']					:""; 
	$lunas 		= isset($_REQUEST['lunas'])		? $_REQUEST['lunas']						:""; 
	
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
	$condition	= "";
	if( !empty($tgl_1) && 
		!empty($tgl_2))			{ 
			$tgl_1_new		= $dtime->date2sysdate($tgl_1);
			$tgl_2_new		= $dtime->date2sysdate($tgl_2);
				$condition 	.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 				}
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
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					a.PAID_STATUS = '3' AND
					a.ID_CLIENT		= '".$_SESSION['cidkey']."' 
					".$condition." 
				GROUP BY a.ID_FACTURE
				ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	$q_sale		= $db->query($query_str." ".$limit);

?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
    <div id="print_wrapper">
		<?php echo print_header("Laporan Piutang Penjualan Produk"); ?>
		<div id="print_content">
              <?php
                  while($dt_sale = $db->fetchNextObject($q_sale)){ 
                    
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
                    <span class='code'>
                        <b>NO FAKTUR: <?php echo $dt_sale->FACTURE_NUMBER; ?></b>
                    </span>
                  	<br>
                    <small>
                        <?php echo $dtime->now2indodate2($dt_sale->TRANSACTION_DATE); ?> 
                    </small>
                    
                    <?php $total_jual = $dt_sale->SUMMARY; ?>
                      <?php
                      $q_piutang = $db->query("SELECT * FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$dt_sale->ID_CASH_FLOW."' AND PLUS_MINUS = '-' ORDER BY ORDINAL ASC");
                      $pembayaran_2 = 0;
                    ?>
                        <table width="100%" class="rt cf">
                            <thead>
                                 <tr>
                                   <th width="17%">NO PO</th>
                                   <th width="17%">Total </th>
                                   <th width="20%">Bayar</th>
                                   <th width="29%">Sisa</th>
                                 </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                   <td><?php if(!empty($dt_sale->PO_NUMBER)){ echo $dt_sale->PO_NUMBER; }  ?></td>
                                   <td><?php echo money("Rp.",$total_jual); ?></td>
                                   <td><?php if(!empty($new_bayar)){ echo money("Rp.",$new_bayar);}else{ echo "0"; }  ?></td>
                                   <td><?php if(!empty($sisa)){ echo money("Rp.",$sisa); }else{ echo "0"; }?></td>
                                 </tr>
                            </tbody>
                            <thead>
                                <tr>
                               	  <th>&nbsp;</th>
                                    <th><b>JML Bayar</b></th>
                                    <th><b>TGL Bayar</b></th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                              while($dt_piutang = $db->fetchNextObject($q_piutang)){
                              ?>
                              <tr>
                                <td width="27%"><b>Pembayaran <?php echo $dt_piutang->ORDINAL; ?></b></td>
                                <td width="27%"><?php echo money("Rp.",$dt_piutang->AMOUNT); ?></td>
                                <td width="23%"><?php echo $dtime->date2indodate($dt_piutang->PAY_DATE); ?></td>
                                <td width="50%"><?php echo @$dt_piutang->NOTE; ?>&nbsp;</td>
                              </tr>
                              <?php } ?>
                            </tbody>
                        </table>
                  <br clear="all" />
              <?php } ?>
    	</div>
		<div id="footer"><?php echo print_footer(); ?></div>
	</div>
</body>

	<?php if(empty($export) || (!empty($export) && $export != "true")){?>
    <script language="javascript">
        window.print();
    </script>
    <?php } ?>
<?php } ?>
