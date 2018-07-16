<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
    <form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
        <div class="col-md-6">
            <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
            <input type="hidden" id="data_page"  value="<?php echo $ajax_dir; ?>/data.php" />
            <div class="ibox-title no-padding-l">
                <h5>Daftar Merchant</h4>
            </div>
            <div style="min-height:671px; max-height:671px; overflow:scroll">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title no-padding-l">
                <h5>Form Pendaftaran Komunitas</h4>
            </div>
            <?php 
                if(!empty($msg)){
                    switch ($msg){
                        case "1":
                            echo msg("Data Link Berhasil Disimpan","success");
                        break;
                        case "2":
                            echo msg("Pengisian Form Belum Lengkap","error");
                        break;
                    }
                }
            ?>
            <?php include $call->inc($page_dir."/includes","form.php"); ?>
            <div class="clearfix"></div>
        </div>
    </form>
    <div class="clearfix"></div>
    </div>
</div>
