<?php defined('mainload') or die('Restricted Access'); ?>
    <div class="ibox float-e-margins" style="background:#FFF">
        <div class="ibox-title"><h4>Rekapitulasi Keuangan</h4></div>
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
            </div>
            <div class='form-group'>
            <label>Alur Keuangan</label>
            <select style="width:20%; margin:5px 0 9px 9px" name="alur" id="alur" >
                <option value="1" <?php if(!empty($alur) && $alur=="1"){?>selected<?php } ?>>BIAYA MASUK</option>
                <option value="2" <?php if(!empty($alur) && $alur=="2"){?>selected<?php } ?>>BIAYA KELUAR</option>
            </select>
            </div>
            <div class='form-group'>
            <button type="submit" class="btn btn-sempoa-1" style="margin:5px 0 9px 0" id="save_button" name="direction" value="periode">
                <i class="icsw16-info-about icsw16-white"></i>Lihat
            </button>
            </div>
            
        </form>
    
    <div style="clear:both; height:20px"></div>
	</div>
    <div class="ibox float-e-margins">
        <div class='col-md-12' id="div_rekap">
            <?php include $call->inc($inc_dir,"rekap.php");  ?>
       </div> 
       <div id="div_report" data-info='<?php echo $ajax_dir; ?>/report.php'></div>
       <div id="div_detail" data-info='<?php echo $ajax_dir; ?>/detail.php'></div>
       <div style="clear:both; height:20px"></div>
   </div> 
<br clear="all">
<input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
