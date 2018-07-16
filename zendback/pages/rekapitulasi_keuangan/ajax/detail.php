<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	if(!empty($_REQUEST['parent_id']))	{ $parent_id 	= $sanitize->number($_REQUEST['parent_id']); 	}
	if(!empty($_REQUEST['periode']))	{ $periode 		= $sanitize->str($_REQUEST['periode']); 		}
	if(!empty($_REQUEST['bln']))		{ $bln 			= $sanitize->str($_REQUEST['bln']); 			}
	if(!empty($_REQUEST['thn']))		{ $thn 			= $sanitize->number($_REQUEST['thn']); 			}
	if(!empty($_REQUEST['thn2']))		{ $thn2 		= $sanitize->number($_REQUEST['thn2']); 			}

	if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
		$periode	= "harian";
		if(empty($bln)){ $bln = date('m'); }
		if(empty($thn)){ $thn = date('Y'); }
		$r 				= 0;
		$parameter		= $dtime->daysamonth($bln,$thn);
		$condition3		= "AND MONTH(TGLUPDATE)='".$bln."' AND YEAR(TGLUPDATE) = '".$thn."'";
		$link_print		= "&periode=".$periode."&bln=".$bln."&thn=".$thn; 
	}
	if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
		$r 				= 0;
		$parameter		= 12;
		$condition3		= "AND YEAR(TGLUPDATE) = '".$thn."'";
		$link_print		= "&periode=".$periode."&thn=".$thn; 
	}
	if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
		$r 				= $thn;
		$parameter		= $thn2;
		$condition3		= "AND YEAR(TGLUPDATE) BETWEEN '".$thn."' AND '".$thn2."'";
		$link_print		= "&periode=".$periode."&thn=".$thn."&thn2=".$thn2; 
	}
	$query_str		= "SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC";
	$q_transaksi 	= $db->query($query_str);
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<div class="ibox-content">
    <input type='hidden' id='parent_id' value='<?php echo @$parent_id; ?>'>
    <div class="ibox-title">
        <div class="pull-right">
            <div class="toggle-group">
                
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo $inc_dir; ?>/print_detail.php?page=<?php echo $page; ?>&parent_id=<?php echo $parent_id; ?><?php echo $link_print; ?>" target="_blank">
                        <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="ibox-content">
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
                    
                    $q_cash	 	= $db->query("SELECT SUM(PAID) AS JML,ID_CASH_TYPE FROM ".$tpref."cash_flow WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." AND ID_CASH_TYPE='".@$parent_id."'");
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
                $q_total 	= $db->query("SELECT SUM(PAID) JUMLAHNYA FROM ".$tpref."cash_flow WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition3." AND ID_CASH_TYPE='".@$parent_id."'");
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
</div>
