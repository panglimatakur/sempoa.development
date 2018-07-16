<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.chart-legend .line-legend{ padding-left:0; }
.chart-legend li{ float:left; list-style:none; margin-right:10px;} 
.chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
</style>
<div class="col-md-12">
    <div class="ibox-content">
        <form method="post" action="">
            <div class='form-group col-md-4'>
                <label>Periode</label>
                <select name="periode" id="periode" class='form-control'>
                    <option value="">--PERIODE--</option>
                    <option value="harian" 
                            <?php if(empty($periode) || 
									(!empty($periode) && $periode=="harian")){?>selected<?php } ?>>HARIAN</option>
                    <option value="bulanan" 
                            <?php if(!empty($periode) && $periode=="bulanan"){?>selected<?php } ?>>BULANAN</option>
                    <option value="tahunan" 
                            <?php if(!empty($periode) && $periode=="tahunan"){?>selected<?php } ?>>TAHUNAN</option>
                </select>
                <span id="div_periode"><?php if(!empty($periode)){ include $call->inc($ajax_dir,"data.php"); } ?></span>
            </div>
            <div class="form-group col-md-4 periode_options" id="div_month">
                <label>Bulan</label>
                <select name="cur_month" id="cur_month" class="form-control">
                    <option value="">--BULAN--</option>
                    <?php $q = 0; 
						  while($q<12){ 
						  $q++; if(strlen($q) == 1){ $num_bulan = "0".$q; }else{ $num_bulan = $q; }
					?>
                        <option value="<?php echo $num_bulan; ?>" 
								<?php if($cur_month == $num_bulan){?> selected <?php } ?>>
                            	<?php echo $dtime->nama_bulan($q); ?>
                        </option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group col-md-4 periode_options" id="div_year">
                <label>Tahun</label>
                <select name="cur_year" id="cur_year" class="form-control">
                    <option value="">--TAHUN--</option>
                    <?php $z = date("Y")-8; while($z<date('Y')){ $z++; ?>
                        <option value="<?php echo $z; ?>" <?php if($cur_year == $z){?> selected <?php } ?>>
                            <?php echo $z; ?>
                        </option>
                    <?php }?>
                </select>
            </div>
            <div class="form-group col-md-8 periode_options" id="div_year2" style="display:none">
                <label>Tahun</label>
                <div class="input-group">
                    <select name="cur_year2" id="cur_year2" class="form-control">
                        <option value="">--TAHUN--</option>
                        <?php $z = date("Y")-15; while($z<date('Y')){ $z++; ?>
                            <option value="<?php echo $z; ?>" 
									<?php if(!empty($cur_year2) && $cur_year2 == $z){?> selected <?php } ?>>
                                	<?php echo $z; ?>
                            </option>
                        <?php }?>
                    </select>
                    <span class="input-group-addon">s.d</span>
                    <select name="cur_year3" id="cur_year3"  class="form-control">
                        <option value="">--TAHUN--</option>
                        <?php $z2 = date("Y")-15; while($z2<date('Y')){ $z2++; ?>
                            <option value="<?php echo $z2; ?>" 
                                    <?php if(!empty($cur_year3) && $cur_year3 == $z2){?> selected <?php } ?>>
                                    <?php echo $z2; ?>
                            </option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                  <label>&nbsp;</label><br />
                  <button type="button" class='btn btn-sempoa-1' id='show'>
                  	<i class="fa fa-eye"></i> Lihat Statistik
                  </button>
            </div>
            <div class="clearfix"></div>
    	</form>
	</div>
    <input type="hidden" id="data_page" value = "<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php" />

    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Statistik Toko Online <?php echo $_SESSION['cname']; ?> - 
                <span id="label_periode">
                    Bulan <?php echo $dtime->nama_bulan($cur_month); ?>
                    Tahun <?php echo $cur_year; ?>
                 </span>
             </h5>
             <i class="icsw16-graph icsw16-white pull-right"></i>
        </div>
        <div class="ibox-content">
            <div>
                <div id="js-legend" class="chart-legend"></div>
                <canvas id="lineChart" height="140"></canvas>
            </div>
        </div>
    </div>
    
</div>
