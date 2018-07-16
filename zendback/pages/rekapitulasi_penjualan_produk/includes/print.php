<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
}
	if(!empty($_REQUEST['periode']))	{ $periode 	= $sanitize->str($_REQUEST['periode']); 		}
	if(!empty($_REQUEST['bln']))		{ $bln 		= $sanitize->str($_REQUEST['bln']); 			}
	if(!empty($_REQUEST['thn']))		{ $thn 		= $sanitize->str($_REQUEST['thn']); 			}
	if(!empty($_REQUEST['thn2']))		{ $thn2 	= $sanitize->str($_REQUEST['thn2']); 			}
	
	if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
		$periode	= "harian";
		if(empty($bln)){ $bln = date('m'); }
		if(empty($thn)){ $thn = date('Y'); }
		$r 				= 0;
		$parameter		= $dtime->daysamonth($bln,$thn);
		$condition3		= "AND MONTH(a.TRANSACTION_DATE)='".$bln."' AND YEAR(a.TRANSACTION_DATE) = '".$thn."'";
		$label 			= "Bulan ".$dtime->nama_bulan($bln)." Tahun ".$thn;
	}
	if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
		$r 				= 0;
		$parameter		= 12;
		$condition3		= "AND YEAR(a.TRANSACTION_DATE) = '".$thn."'";
		$label 			= "Tahun ".$thn;
	}
	if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
		$r 				= $thn-1;
		$parameter		= $thn2;
		$condition3		= "AND YEAR(a.TRANSACTION_DATE) BETWEEN '".$thn."' AND '".$thn2."'";
		$label 			= "Tahun ".$thn." s/d Tahun ".$thn2;
	}
	
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Rekapitulasi Penjualan Produk <br> ".$label); ?>
		<div id="print_content">
        <table width="100%" class="table table-striped" id="table_data">
            <thead>
                <tr>
                  <th width="15%" style="text-align:center"></th>
                    <th width="19%" style="text-align:center">Jumlah</th>
                    <th width="17%" style="text-align:right">Total</th>
                    <th width="19%" style="text-align:right">Bayar</th>
                    <th width="15%" style="text-align:right">Piutang</th>
                </tr>
            </thead>
            <tbody>
              <?php
                  while($r<$parameter){ 
                    $r++;
                    if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
                        if(strlen($r)==1){ $r = "0".$r; }
                        $tgl_jual	= $thn."-".$bln."-".$r;
                        $label		= $dtime->indodate2date($tgl_jual);
                        $condition	= " AND a.TRANSACTION_DATE='".$tgl_jual."' ";
                    }
                    if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
                        if(strlen($r)==1){ $r = "0".$r; }
                        $label		= $dtime->nama_bulan($r);
                        $condition	= " AND MONTH(a.TRANSACTION_DATE)='".$r."' AND YEAR(a.TRANSACTION_DATE) = '".$thn."'";
                    }
                    if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
                        $label		= $r;
                        $condition	= " AND YEAR(a.TRANSACTION_DATE)='".$r."'";
                    }
					$str_query = "
					SELECT 
						SUM(b.QUANTITY) AS JML, SUM(b.TOTAL) AS TOTAL, 	
						(SELECT SUM(a.PAID) 	FROM cat_factures a WHERE a.MODULE = 'SALE' ".$condition.") AS BAYAR,
					 	(SELECT SUM(a.REMAIN) 	FROM cat_factures a WHERE a.MODULE = 'SALE'  ".$condition.") AS REMAIN
					FROM 
						cat_factures a,
						cat_products_sales b
						
					WHERE 
						a.ID_FACTURE = b.ID_FACTURE AND 
						a.MODULE = 'SALE'
						".$condition;
                    $q_sale	 	= $db->query($str_query);
                    $dt_sale 	= $db->fetchNextObject($q_sale);
                    if(empty($dt_sale->JML)){
                        $jml_beli = 0;	
                    }else{
                        $jml_beli = $dt_sale->JML;	
                    }
              ?>
                  <tr>
                    <td style="text-align:center"><?php echo $label; ?></td>
                    <td style="text-align:center"><?php echo trim($jml_beli); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_sale->TOTAL)); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_sale->BAYAR)); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_sale->REMAIN)); ?></td>
                </tr>
              <?php 
                  }
				$str_total = "
				SELECT 
					SUM(b.QUANTITY) AS JUMLAHNYA, 
					SUM(b.TOTAL) AS TOTALNYA, 	
					(SELECT SUM(a.PAID) 	FROM cat_factures a WHERE a.MODULE = 'SALE' ".$condition3.") AS BAYARNYA,
					(SELECT SUM(a.REMAIN) 	FROM cat_factures a WHERE a.MODULE = 'SALE' ".$condition3.") AS SISANYA
				FROM 
					cat_factures a,
					cat_products_sales b
					
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					a.MODULE = 'SALE'
					".$condition3;
                $q_total 	= $db->query($str_total);
                $dt_total	= $db->fetchNextObject($q_total); 
                    if(empty($dt_total->JUMLAHNYA)){
                        $total_jual = 0;	
                    }else{
                        $total_jual = $dt_total->JUMLAHNYA;	
                    }
              ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td style="text-align:center"><b><?php echo $total_jual; ?></b></td>
                    <td style="text-align:right"><b><?php echo money("Rp.",$dt_total->TOTALNYA); ?></b></td>
                    <td style="text-align:right"><b><?php echo money("Rp.",$dt_total->BAYARNYA); ?></b></td>
                    <td style="text-align:right"><b><?php if($dt_total->SISANYA > 0){ echo money("Rp.",$dt_total->SISANYA); }else{ echo "Rp.0,-"; } ?></b></td>
                </tr>
            </tbody>
        </table>
		</div>
		<div id="footer"><?php echo print_footer(); ?></div>
	</div>
</body>


<script language="javascript">
	window.print();
</script>
