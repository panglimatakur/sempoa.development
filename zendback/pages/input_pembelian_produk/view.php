<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-12" >
    <div class="ibox float-e-margins">
            <br />
            <div class="tabbable tabbable-bordered" style="margin:0 0 0 0">
				<?php 
                    if(!empty($msg)){
                        switch ($msg){
                            case "1":
                                echo msg("Data Berhasil Disimpan","success");
                                $class_report = "active";
                            break;
                            case "2":
                                echo msg("Pengisian Form Belum Lengkap","error");
                            break;
                            case "3":
                                echo msg("Data Berhasil Disimpan","success");
                                if(empty($direction)){ $class_multi_proses = "active"; }
                            break;
                        }
                    }
                ?>
                <ul class="nav nav-tabs">
                    <li class="<?php echo @$class_multi_proses; ?>"><a data-toggle="tab" href="#tb1_b">Form Proses</a></li>
                    <!--<li class="<?php echo @$class_export; ?>"><a data-toggle="tab" href="#tb1_c">Export</a></li>-->
                </ul>
                <div class="tab-content" style="min-height:0px; background:#FFF">
                    <div id="tb1_b" class="tab-pane <?php echo @$class_multi_proses; ?>">
                        <p><?php include $call->inc($page_dir."/includes","form_multi_proses.php"); ?></p>
                    </div>
                    <!--<div id="tb1_c" class="tab-pane <?php //echo @$class_export; ?>">
                        <div class="ibox-title">
                            <h4>Export Data Penjualan</h4>
                        </div>
                        <p><?php //include $call->inc($page_dir."/includes","form_export.php"); ?></p>
                    </div>-->
                </div>
            </div>
    </div>
    <div class="ibox float-e-margins" style="background:#FFF">
        <?php include $call->inc("modules/laporan_pembelian_produk","page.php"); ?>
        <input id="product_list" type="hidden"  value="<?php echo $inc_dir; ?>/product_list.php" />
        <input id="product_list_kolektif" type="hidden"  value="<?php echo $inc_dir; ?>/product_list_kolektif.php" />
    </div>
</div>
