<?php defined('mainload') or die('Restricted Access'); ?>
<div class="row-fluid">
    <div class="ibox float-e-margins" style="background:#FFF">
        <div class="ibox-title"><h4>Laporan dan Riwayat Hutang Piutang Transaksi</h4></div>
            <form method="post" action="" name="form_periode">
                <div class="form-group">
                    <div class="col-md-3">
                        <label>Tanggal Awal</label>
                        <div class="input-append date" id="dpStart" data-date-format="dd/mm/yyyy">
                            <input class="col-md-12"  value="<?php echo @$tgl_1; ?>" readonly="" type="text" id="tgl_1" name="tgl_1">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Akhir</label>
                        <div class="input-append date" id="dpEnd" data-date-format="dd/mm/yyyy" >
                            <input class="col-md-12" value="<?php echo @$tgl_2; ?>" readonly="" type="text" id="tgl_2" name="tgl_2">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <?php if(empty($parent_id)){?>
                <div class="form-group">
                    <label class='req'>Status Bayar</label>
                    <select id="status_lunas" name="status_lunas" class="mousetrap col-md-3">
                        <option value='1'<?php if(!empty($status_lunas) && $status_lunas == 1){?>selected<?php } ?>>Hutang</option>
                        <option value='3'<?php if(!empty($status_lunas) && $status_lunas == 3){?>selected<?php } ?>>Piutang</option>
                    </select>
                </div>
                <?php } ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-sempoa-1" name="direction" value="show" style='margin-left:0'>
                        <i class="icsw16-info-about icsw16-white"></i>Lihat
                    </button>
                    <button type="button" class="btn btn-beoro-2" id="reset_date" style='margin-left:0'>
                        <i class="icsw16-refresh-3 icsw16-white"></i>Reset
                    </button>
                </div>
            </form>
		<?php
        if(empty($parent_id)){
            include $call->inc($inc_dir,"report.php");	
        }else{
            include $call->inc($inc_dir,"history.php");	
        }
        ?>
	</div>
</div> 
<br clear="all">
<input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
