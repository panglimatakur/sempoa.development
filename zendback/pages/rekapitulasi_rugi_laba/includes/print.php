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
		if(empty($bln)){ $bln = date('m'); }
		if(empty($thn)){ $thn = date('Y'); }
		$r 				= 0;
		$parameter		= $dtime->daysamonth($bln,$thn);
		$condition3		= "AND MONTH(TGLUPDATE)='".$bln."' AND YEAR(TGLUPDATE) = '".$thn."'";
	}
	if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
		$r 				= 0;
		$parameter		= 12;
		$condition3		= "AND YEAR(TGLUPDATE) = '".$thn."'";
	}
	if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
		$r 				= $thn;
		$parameter		= $thn2;
		$condition3		= "AND YEAR(TGLUPDATE) BETWEEN '".$thn."' AND '".$thn2."'";
	}
	
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Laporan Rugi Laba"); ?>
		<div id="print_content">
        <table width="100%" class="table table-striped" id="table_data">
            <thead>
                <tr>
                  <th width="15%" style="text-align:center"></th>
                    <th width="19%" style="text-align:center">TRANSAKSI</th>
                    <th width="17%" style="text-align:right">TOTAL</th>
                    <th width="19%" style="text-align:right">MODAL</th>
                    <th width="15%" style="text-align:right">UNTUNG</th>
                    <th width="15%" style="text-align:right">RUGI</th>
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
                        $condition	= " AND TGLUPDATE='".$tgl_jual."' ";
                        $condition2	= " AND a.TGLUPDATE='".$tgl_jual."' ";
                    }
                    if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
                        if(strlen($r)==1){ $r = "0".$r; }
                        $label		= $dtime->nama_bulan($r);
                        $condition	= " AND MONTH(TGLUPDATE)='".$r."' AND YEAR(TGLUPDATE) = '".$thn."'";
                        $condition2	= " AND MONTH(a.TGLUPDATE)='".$r."' AND YEAR(a.TGLUPDATE) = '".$thn."'";
                    }
                    if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
                        $label		= $r;
                        $condition	= " AND YEAR(TGLUPDATE)='".$r."'";
                        $condition2	= " AND YEAR(a.TGLUPDATE)='".$r."'";
                    }
                    
                    $q_sale	 	= $db->query("SELECT SUM(QUANTITY) AS JML,SUM(TOTAL) AS TOTAL FROM ".$tpref."products_sales WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." ");
                    $dt_sale 	= $db->fetchNextObject($q_sale);
                    if(empty($dt_sale->JML)){
                        $jml_jual = 0;	
                    }else{
                        $jml_jual = $dt_sale->JML;	
                    }
                    $modal		 = "";
                    $untung		 = "";
                    $q_sale2	 = $db->query("SELECT 
                                                    (b.BUY_PRICE*SUM(a.QUANTITY)) AS MODAL,
                                                    (SUM(a.TOTAL)- (b.BUY_PRICE*SUM(a.QUANTITY))) AS UNTUNG
                                                FROM 
                                                    cat_products_sales a, cat_products_buys b
                                                WHERE 
                                                    a.ID_PRODUCT = b.ID_PRODUCT AND a.ID_CLIENT = b.ID_CLIENT AND (a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") ".$condition2." GROUP BY a.ID_PRODUCT");
                    while($dt_sale2 = $db->fetchNextObject($q_sale2)){
                        $modal 	= $dt_sale2->MODAL+$modal;
                        $untung = $dt_sale2->UNTUNG+$untung;
                    }
                    $total_modal 	= $modal+@$total_modal;
                    $total_untung 	= $untung+@$total_untung;	 
              ?>
                  <tr>
                    <td style="text-align:center"><?php echo $label; ?></td>
                    <td style="text-align:center"><?php echo trim($jml_jual); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_sale->TOTAL)); ?></td>
                    <td style="text-align:right"><?php if(!empty($modal)){ echo trim(money("Rp.",$modal)); }else{ echo "Rp.0,-"; } ?></td>
                    <td style="text-align:right">
                    <?php 
                    if(!empty($untung)){ 
                        if($untung > 0){
                            echo trim(money("Rp.",$untung)); 
                        }else{ echo "Rp.0,-"; } 
                    }
                    else{ echo "Rp.0,-"; } 
                    ?>
                    </td>
                    <td style="text-align:right">
                    <?php 
                    if(!empty($untung)){ 
                        if($untung < 0){
                            echo trim(money("Rp.",$untung)); 
                        }else{ echo "Rp.0,-"; } 
                    }
                    else{ echo "Rp.0,-"; } 
                    ?>
                    </td>
                </tr>
              <?php 
                  }
                $q_total 	= $db->query("SELECT SUM(QUANTITY) JUMLAHNYA, SUM(TOTAL) AS TOTALNYA FROM ".$tpref."products_sales WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition3."");
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
                    <td style="text-align:right"><b><?php echo money("Rp.",$total_modal); ?></b></td>
                    <td style="text-align:right"><b><?php if($total_untung > 0){ echo money("Rp.",$total_untung); }else{ echo "Rp.0,-"; } ?></b></td>
                    <td style="text-align:right"><b><?php if($total_untung < 0){ echo money("Rp.",$total_untung); }else{ echo "Rp.0,-"; } ?></b></td>
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
