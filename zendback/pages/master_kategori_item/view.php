<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class="ibox float-e-margins">
    <div class="ibox-content no-padding-lr">
        <div class="col-md-6">
            <div class="ibox-title">
                <h4>Daftar Kategori Yang Terdaftar</h4>
            </div>
            <div class="ibox-content inner-content-div">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title">
                <h4>Form Input Kategori</h4>
            </div>
            <?php 
                if(!empty($msg)){
                    switch ($msg){
                        case "1":
                            echo msg("Data Kategori Berhasil Disimpan","success");
                        break;
                        case "2":
                            echo msg("Data Kategori Berhasil Disimpan dan Di Perbaiki","success");
                        break;
                    }
                }
            ?>
            <div class="ibox-content"><?php include $call->inc($page_dir."/includes","form.php"); ?></div>
        </div>
        <div class="clearfix"></div>
    </div>
    
</div>
</form>