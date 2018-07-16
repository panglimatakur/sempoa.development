<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
<?php 
	if(!empty($msg)){
		echo "<div class='col-md-12'>";
		switch ($msg){
			case "1":
				echo msg("Data Link Berhasil Disimpan","success");
			break;
			case "2":
				echo msg("Pengisian Form Belum Lengkap","error");
			break;
			case "3":
				echo msg("Maaf, email pengguna ini sudah terdaftar, silahkan gunakan email lain","error");
			break;
		}
		echo "</div>";
	}
?>
<div class="row-fluid">
<div class="col-md-6">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Pengguna</h4>
        </div>
        <div class='alert alert-warning' style="margin:0">Klik salah satu daftar Klien dibawah ini untuk menentukan dimana pengguna yang akan di daftarkan ini bekerja</div>
        <div class="ibox-content" style="min-height:671px; max-height:671px; overflow:scroll">
            <?php include $call->inc($page_dir."/includes","list.php");  ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Form Input Pengguna</h4>
        </div>
        <div class="ibox-content">
            <?php include $call->inc($page_dir."/includes","form.php"); ?>
        </div>
    </div>
</div>
</div>
    <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />

</form>