<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class="ibox float-e-margins">
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Link Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Data Link Berhasil Disimpan dan Di Perbaiki","success");
                break;
                case "3":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
        }
    ?>
    <div class="ibox-content no-padding-lr">
        <div class="col-md-6">
            <div class="ibox-title">
                <h5>Menu Induk Cpanel</h5>
            </div>
            <div class="ibox-content inner-content-div">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title">
                <h5>Pendaftaran Menu Cpanel</h5>
            </div>
            <div class="ibox-content"><?php include $call->inc($page_dir."/includes","form.php"); ?></div>
        </div>
        
        <div class="clearfix"></div>
    </div>
</div>
</form>