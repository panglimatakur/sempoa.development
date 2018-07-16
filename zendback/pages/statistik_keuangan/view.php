<?php defined('mainload') or die('Restricted Access'); ?>
<!-- main content -->
<div class="row-fluid">
    <div class="col-md-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Daftar Transaksi</h4>
            </div>
            <div class="ibox-content" style="height:auto; max-height:500px; overflow:scroll">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
            </div>
        </div>
    </div>
    <div class="span8">
        <form method="post" action="" name="form_periode" style="background:#FFF">
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
            <input id="parent_id" type="hidden"  value="<?php echo @$parent_id; ?>" />
        </form>
        <div class="ibox float-e-margins">
            <div class="ibox-title" >
                <h4>Keuangan PERIODE <?php echo @$label; ?></h4>
            </div>
            <div class="ibox-content">
                    <div id="ch_sale" class="chart_a"></div>
            </div>
        </div>
    </div>
</div>
<input id="stat_page" type="hidden"  value="<?php echo $ajax_dir; ?>/statistik.php" />
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
