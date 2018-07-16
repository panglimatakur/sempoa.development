<?php defined('mainload') or die('Restricted Access'); ?>

<div class="col-md-12" >
    <div class="ibox-title">
         <h4>
         Rekapitulasi Rugi Laba ( <?php if(!empty($bln)){ echo @$dtime->nama_bulan($bln); } echo " TAHUN ".$thn; if(!empty($thn2)){ echo "S/D  TAHUN ".$thn2; } ?> )
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
         <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
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
                    <td style="text-align:right"><?php if(!empty($dt_sale->TOTAL)){ echo trim(money("Rp.",$dt_sale->TOTAL)); }else{ echo "Rp.0,-"; } ?></td>
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
                    <td style="text-align:right"><b><?php if(!empty($dt_total->TOTALNYA)){ echo money("Rp.",$dt_total->TOTALNYA); }else{ echo "Rp.0,00"; } ?></b></td>
                    <td style="text-align:right"><b><?php echo money("Rp.",$total_modal); ?></b></td>
                    <td style="text-align:right"><b><?php if($total_untung > 0){ echo money("Rp.",$total_untung); }else{ echo "Rp.0,00"; } ?></b></td>
                    <td style="text-align:right"><b><?php if($total_untung < 0){ echo money("Rp.",$total_untung); }else{ echo "Rp.0,00"; } ?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
