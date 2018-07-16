<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class="row-fluid">

    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Daftar Transaksi</h4>
            </div>
            <div class="ibox-content" style="height:auto; max-height:500px; overflow:scroll">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Form Input Transaksi</h4>
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
            <div class="ibox-content" id="form_value">
			<?php 
			if(!empty($direction)){
				include $call->inc($ajax_dir,"form.php"); 
            }else{?>
                <div class='alert alert-warning' style="margin:0">
                    <ol><i class="icsw16-money"></i> Berfungsi Untuk Memasukan Nilai Uang Untuk Jenis Transaksi Yang Di Tunjuk</ol>
                </div>
            <?php } ?>
            </div>
        	<input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
        	<input type='hidden' id='form_page' value='<?php echo $ajax_dir; ?>/form.php'>
        </div>
    </div>
</div>
</form>