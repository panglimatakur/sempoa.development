<?php
session_start(); 
if(!empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");

	if(!empty($_REQUEST['parent_id']))	{ $parent_id = $sanitize->number($_REQUEST['parent_id']); 		}
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
		$label			= "HARIAN Bulan ".$dtime->nama_bulan($bln)." Tahun ".$thn;
	}
	if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
		$r 				= 0;
		$parameter		= 12;
		$condition3		= "AND YEAR(TGLUPDATE) = '".$thn."'";
		$label			= "BULANAN Tahun ".$thn;
	}
	if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
		$r 				= $thn;
		$parameter		= $thn2;
		$condition3		= "AND YEAR(TGLUPDATE) BETWEEN '".$thn."' AND '".$thn2."'";
		$label			= "TAHUNAN Tahun ".$thn." S/D Tahun ".$thn2;
	}
	  @$nm_transaksi	= $db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$parent_id."'");
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Riwayat Transaksi Keuangan ".$nm_transaksi."<br>".$label); ?>
		<div id="print_content">
        <table width="100%" class="table table-striped" id="table_data">
            <thead>
                <tr>
                  <th width="15%" style="text-align:center"></th>
                    <th width="19%" style="text-align:center">TRANSAKSI</th>
                    <th width="17%" style="text-align:right">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
              <?php
                  @$nm_transaksi	= $db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$parent_id."'");
                  while($r<$parameter){ 
                    $r++;
                    if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
                        if(strlen($r)==1){ $r = "0".$r; }
                        $tgl_jual	= $thn."-".$bln."-".$r;
                        $label		= $dtime->indodate2date($tgl_jual);
                        $condition	= " AND TGLUPDATE='".$tgl_jual."' ";
                    }
                    if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
                        if(strlen($r)==1){ $r = "0".$r; }
                        $label		= $dtime->nama_bulan($r);
                        $condition	= " AND MONTH(TGLUPDATE)='".$r."' AND YEAR(TGLUPDATE) = '".$thn."'";
                    }
                    if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
                        $label		= $r;
                        $condition	= " AND YEAR(TGLUPDATE)='".$r."'";
                    }
                    
                    $q_cash	 	= $db->query("SELECT SUM(CASH_VALUE) AS JML,ID_CASH_TYPE FROM ".$tpref."cash_flow WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." AND ID_CASH_TYPE='".@$parent_id."'");
                    $dt_cash 	= $db->fetchNextObject($q_cash);
                    if(empty($dt_cash->JML)){
                        $jml = 0;	
                    }else{
                        $jmll = $dt_cash->JML;	
                    }
              ?>
                  <tr>
                    <td style="text-align:center"><?php echo $label; ?></td>
                    <td style="text-align:center"><?php echo @$nm_transaksi; ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",@$dt_cash->JML)); ?></td>
                </tr>
              <?php 
                  }
                $q_total 	= $db->query("SELECT SUM(CASH_VALUE) JUMLAHNYA FROM ".$tpref."cash_flow WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition3." AND ID_CASH_TYPE='".@$parent_id."'");
                $dt_total	= $db->fetchNextObject($q_total); 
                    if(empty($dt_total->JUMLAHNYA)){
                        $total = 0;	
                    }else{
                        $total = $dt_total->JUMLAHNYA;	
                    }
              ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td style="text-align:center">&nbsp;</td>
                    <td style="text-align:right"><b><?php echo money("Rp.",$total); ?></b></td>
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
<?php } ?>