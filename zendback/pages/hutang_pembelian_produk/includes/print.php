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
		header("Content-Disposition: attachment; filename=piutang_penjualan_produk.xls");	
	}
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
}
if(!empty($prompt) && $prompt == "true" && !empty($_SESSION['uidkey'])){
?>
<form id="form_print" method="post" action="<?php echo $dirhost; ?>/modules/hutang_pembelian_produk/includes/print.php" target="_new" class="">
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

	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 		}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 				}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 				}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 			}

	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}
	if(!empty($_REQUEST['id_product']))		{ $id_product 		= $sanitize->number($_REQUEST['id_product']); 		}
	if(!empty($_REQUEST['faktur']))			{ $faktur 			= $sanitize->str($_REQUEST['faktur']); 				}
	if(!empty($_REQUEST['harga_pokok']))	{ $harga_pokok 		= $sanitize->number($_REQUEST['harga_pokok']); 		}
	
	if(empty($tgl_1)){ $tgl_1_new = date("d/m/Y"); } 
	if(empty($tgl_2)){ 
		$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
		$tgl_1_new 		=  date("d/m/Y", $dateformat);
	} 

	$condition 	= "";
	if( !empty($tgl_1) && 
		!empty($tgl_2))					{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 		.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 							}
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
		a.ID_CLIENT		= '".$_SESSION['cidkey']."' 
	 	".$condition." 
	GROUP BY a.ID_FACTURE
	ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	$q_buy 		= $db->query($query_str." ".$limit);
	
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
	<?php echo print_header("Laporan Hutang Pembelian Produk"); ?>
    <div id="print_content">
      <?php
      while($dt_buy = $db->fetchNextObject($q_buy)){ 
        $total_buy		= "";
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
        <span class='code'>
            <b>NO FAKTUR: <?php echo $dt_buy->FACTURE_NUMBER; ?></b>
        </span>
        <br>
        <small>
            <?php echo $dtime->now2indodate2($dt_buy->TRANSACTION_DATE); ?> 
        </small>

    <?php $total_beli = $dt_buy->SUMMARY; ?>
        <?php
          $q_hutang = $db->query("SELECT * FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$dt_buy->ID_CASH_FLOW."' AND PLUS_MINUS = '-' ORDER BY ORDINAL ASC");
          $pembayaran_2 = 0;
        ?>
        <table width="100%">
        <thead>
             <tr>
               <th width="27%">NO PO</th>
               <th width="27%">Total </th>
               <th width="23%">Bayar</th>
               <th width="25%">Sisa</th>
             </tr>
        </thead>
            <tbody>
                 <tr>
                   <td> <?php if(!empty($dt_buy->PO_NUMBER)){ echo $dt_buy->PO_NUMBER;  } ?>&nbsp;</td>
                   <td><?php echo money("Rp.",$total_beli); ?></td>
                   <td><?php if(!empty($new_bayar)){ echo money("Rp.",$new_bayar);}else{ echo "0"; }  ?></td>
                   <td><?php if(!empty($sisa)){ echo money("Rp.",$sisa); }else{ echo "0"; }?></td>
                 </tr>
            </tbody>
          <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><b>JML Bayar</b></th>
                    <th><b>TGL Bayar</b></th>
                    <th><b>Keterangan</b></th>
                </tr>
          </thead>
        <tbody>
        <?php
          while($dt_hutang = $db->fetchNextObject($q_hutang)){
        ?>
              <tr>
                <td width="23%"><b>Pembayaran <?php echo $dt_hutang->ORDINAL; ?></b></td>
                <td width="21%"><?php echo money("Rp.",$dt_hutang->AMOUNT); ?></td>
                <td width="18%"><?php echo $dtime->date2indodate($dt_hutang->PAY_DATE); ?></td>
                <td width="38%"><?php echo @$dt_hutang->NOTE; ?>&nbsp;</td>
              </tr>
        <?php } ?>
        </tbody>
       </table>        
    <br>
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
