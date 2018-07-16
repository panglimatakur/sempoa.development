<?php defined('mainload') or die('Restricted Access'); ?>
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
        }
    ?>

<div class="ibox float-e-margins" id="n_wysiwg" > <!---->
	<div class="ibox-title">
		<h4>Form</h4>
	</div>
	<div class="ibox-content">
		<iframe src="" name="proses" frameborder="0" height="0"></iframe><!-- -->
		<form method="post" id="form_forum" action="<?php echo $ajax_dir; ?>/proses.php" enctype="multipart/form-data" target="proses">
			<div class="form-group">
				<label>Judul Tulisan</label>
				<input type="text" id="subject" name="subject" class="col-md-6" placeholder="Judul" value="<?php echo @$subject; ?>" />
			</div>
			<div class="form-group">
				<label>Isi Tulisan</label>
				<textarea name="question" id="question" cols="30" rows="10"><?php echo @$question; ?></textarea>
				<div class="ibox-content" id="ans_content"></div>
				<span id="div_dest">
					<br />
					<div id="participants"></div>
					<br clear="all" />
					<label>Siapa saja yang dapat melihat tulisan ini?</label>
					<select id="destiny" name="destiny">
						<option value="umum">Umum</option>
						<option value="komunitas">Komunitas</option>
                        <option value="merchant">Merchant</option>
						<option value="personal">Personal</option>
					</select>
					<span id="id_search" style="display:none">
						<input type="text" class="col-md-6" id="search"/>
						<button type="button" id="btn_search" class="btn" style="margin:-9px 0 0 0"><i class="icsw16-magnifying-glass"></i></button>
						<span id="div_destiny"></span>
					</span>
			   </span>
			   
				<div class='form-group' style="padding:4px 0 0 0" >
					<button type="sumbit" name="direction" value="send" style="margin:0" class="btn btn-sempoa-1" >Kirim Pesan</button>
				</div>
			</div>
		</form>
	</div>
</div>
<input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
