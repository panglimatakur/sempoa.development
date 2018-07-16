<div class="ibox-title">
    <div class="pull-right">
        <div class="toggle-group">
            
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo $inc_dir; ?>/print_rekap.php?page=<?php echo $page; ?><?php echo $link_print; ?>" target="_blank">
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
                <th width="17%" style="text-align:right">JUMLAH</th>
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
                    $condition	= " AND a.TGLUPDATE='".$tgl_jual."' ";
					$link		= $tgl_jual;
                }
                if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
                    if(strlen($r)==1){ $r = "0".$r; }
                    $label		= $dtime->nama_bulan($r);
                    $condition	= " AND MONTH(a.TGLUPDATE)='".$r."' AND YEAR(a.TGLUPDATE) = '".$thn."'";
					$link		= $r."-".$thn;
                }
                if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
                    $label		= $r;
                    $condition	= " AND YEAR(a.TGLUPDATE)='".$r."'";
					$link		= $thn."-".$thn2;
                }
                
                $q_cash	 	= $db->query("
										SELECT 
											SUM(a.PAID) AS JML,
											a.ID_CASH_TYPE 
										FROM 
											".$tpref."cash_flow a,
											".$tpref."cash_type b
										WHERE 
											a.ID_CASH_TYPE = b.ID_CASH_TYPE AND  
											b.IN_OUT = '".$alur."' AND 
											(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") 
											".$condition." ");											
                $dt_cash 	= $db->fetchNextObject($q_cash);
                if(empty($dt_cash->JML)){
                    $jml = 0;	
                }else{
                    $jmll = $dt_cash->JML;	
                }
          ?>
              <tr>
                <td style="text-align:center"><?php echo $label; ?></td>
                <td style="text-align:right"><a href='javascript:void()' onclick="get_report('div_rekap','div_report','<?php echo $periode; ?>','<?php echo $link; ?>')"><?php echo trim(money("Rp.",@$dt_cash->JML)); ?></a></td>
            </tr>
          <?php 
              }
            $q_total 	= $db->query("SELECT 
											SUM(a.PAID) AS JUMLAHNYA
										FROM 
											".$tpref."cash_flow a,
											".$tpref."cash_type b
										WHERE 
											a.ID_CASH_TYPE = b.ID_CASH_TYPE AND
											b.IN_OUT = '".$alur."' AND 
											(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") ".$condition3." ");
            $dt_total	= $db->fetchNextObject($q_total); 
                if(empty($dt_total->JUMLAHNYA)){
                    $total = 0;	
                }else{
                    $total = $dt_total->JUMLAHNYA;	
                }
          ?>
              <tr>
                <td>&nbsp;</td>
                <td style="text-align:right"><b><?php echo money("Rp.",$total); ?></b></td>
            </tr>
        </tbody>
    </table>
</div>