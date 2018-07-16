<?php defined('mainload') or die('Restricted Access'); ?>

<div class="col-md-12" >
    <div class="ibox-title">
         <h4>
         Rekapitulasi Pembelian ( <?php if(!empty($bln)){ echo @$dtime->nama_bulan($bln); } echo " TAHUN ".$thn; if(!empty($thn2)){ echo "S/D  TAHUN ".$thn2; } ?> )
         </h4>
    </div>
    <div class="ibox-content">
        <div class="ibox float-e-margins">
            <form method="post" action="" name="form_periode">
            	<div class='form-group'>
                <label>Periode</label>
                <select style="width:20%; margin:5px 0 9px 9px" name="periode" id="periode" >
                    <option value="">--PERIODE--</option>
                    <option value="harian" <?php if(!empty($periode) && $periode=="harian"){?>selected<?php } ?>>HARIAN</option>
                    <option value="bulanan" <?php if(!empty($periode) && $periode=="bulanan"){?>selected<?php } ?>>BULANAN</option>
                    <option value="tahunan" <?php if(!empty($periode) && $periode=="tahunan"){?>selected<?php } ?>>TAHUNAN</option>
                </select>
                <span id="div_periode"><?php if(!empty($periode)){ include $call->inc($ajax_dir,"data.php"); } ?></span>
                <button type="submit" class="btn btn-sempoa-1" style="margin:5px 0 9px 0" id="save_button" name="direction" value="periode">
                    <i class="icsw16-info-about icsw16-white"></i>Lihat
                </button>
                </div>
            </form>
         </div>
         <input id="data_page" type="hidden" value="<?php echo $ajax_dir; ?>/data.php" />
    </div>
    <div class="ibox float-e-margins" style="background:#FFF">
        <div class="ibox-title">
            <div class="pull-right">
                <div class="toggle-group">
                    
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo $inc_dir; ?>/print.php?page=<?php echo $page; ?><?php echo $lcondition; ?>" target="_blank">
                            <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <table width="100%" class="table table-striped" id="table_data">
            <thead>
                <tr>
                  <th width="15%" style="text-align:center"></th>
                    <th width="19%" style="text-align:center">Jumlah</th>
                    <th width="17%" style="text-align:right">Total</th>
                    <th width="19%" style="text-align:right">Bayar</th>
                    <th width="15%" style="text-align:right">Hutang</th>
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
						(SELECT SUM(a.PAID) 	FROM cat_factures a WHERE a.MODULE = 'BUY' ".$condition.") AS BAYAR,
					 	(SELECT SUM(a.REMAIN) 	FROM cat_factures a WHERE a.MODULE = 'BUY' ".$condition.") AS REMAIN
					FROM 
						cat_factures a,
						cat_products_buys b
						
					WHERE 
						a.ID_FACTURE = b.ID_FACTURE AND 
						a.MODULE = 'SALE'
						".$condition;
                    $q_buy	 	= $db->query($str_query);
                    $dt_buy 	= $db->fetchNextObject($q_buy);
                    if(empty($dt_buy->JML)){
                        $jml_beli = 0;	
                    }else{
                        $jml_beli = $dt_buy->JML;	
                    }
              ?>
                  <tr>
                    <td style="text-align:center"><?php echo $label; ?></td>
                    <td style="text-align:center"><?php echo trim($jml_beli); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_buy->TOTAL)); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_buy->BAYAR)); ?></td>
                    <td style="text-align:right"><?php echo trim(money("Rp.",$dt_buy->REMAIN)); ?></td>
                </tr>
              <?php 
                  }
				$str_total = "
				SELECT 
					SUM(b.QUANTITY) AS JUMLAHNYA, 
					SUM(b.TOTAL) AS TOTALNYA, 	
						(SELECT SUM(a.PAID) 	FROM cat_factures a WHERE a.MODULE = 'BUY' ".$condition3.") AS BAYARNYA,
					 	(SELECT SUM(a.REMAIN) 	FROM cat_factures a WHERE a.MODULE = 'BUY' ".$condition3.") AS SISANYA
				FROM 
					cat_factures a,
					cat_products_buys b
					
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					a.MODULE = 'BUY'
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
</div>
