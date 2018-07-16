<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox float-e-margins">
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Link Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
                case "3":
                    echo msg("Merchant ini sudah terdaftar","error");
                break;
                case "4":
					echo msg("Logo silahkan di isi dengan format *.png, *.jpg atau *.gif","error");
                break;
                case "5":
					echo msg("Logo silahkan di isi dengan format *.png transparant dan ukuran minimal 200px x 200px","error");
                break;
            }
        }
    ?>
	<div class="ibox-content no-padding-lr">
        <div class="col-md-6">
            <div class="ibox-title">
                <h5>Daftar Merchant Induk</h5>
            </div>
            <div class="ibox-content inner-content-div">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
                <div class="clearfix"></div>
            </div>
        </div>
    
        <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
        <form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
            <div class="col-md-6">
                <div class="ibox-title">
                    <h5>Form Merchant Baru</h5>
                </div>
                <div class="ibox-content">
                    <?php include $call->inc($page_dir."/includes","form.php"); ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
        <div class="clearfix"></div>
    </div>
</div>
