<?php defined('mainload') or die('Restricted Access'); ?>
<form method="post" action="" id="form_report">
	<?php if($_SESSION['uclevelkey'] == 1 && $_SESSION['ulevelkey'] == 1){?>
    <div class="form-group col-md-4">
        <label>Daftar Client</label>
        <select name="id_client_report_sel" id="id_client_report_sel" class="form-control mousetrap" >
            <option value=''>--PILIH CLIENT--</option>
            <?php while($data_branch = $db->fetchNextObject($query_branch)){ ?>
                <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_client_report) && $id_client_report == $data_branch->ID_CLIENT){?>selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?></option>
        	<?php } ?>
        </select>
    </div> 
    <?php } ?>
    <input type="hidden" name="id_client_report" id="id_client_report" value="<?php echo @$id_client_report; ?>" />
    <div class="form-group col-md-4" id="div_reg_by">
      <label>Didaftarkan Oleh</label>
        <select name="reg_by" id="reg_by" class="form-control mousetrap">
            <option value=''>--PILIH NAMA PENGGUNA--</option>
			<?php
            while($data_user = $db->fetchNextObject($query_user)){
            ?>
                <option value='<?php echo $data_user->ID_USER; ?>' <?php if(!empty($reg_by) && $reg_by == $data_user->ID_USER){?> selected<?php } ?>><?php echo $data_user->USER_NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-4">
       <label>Nama</label>
      <input name="nama_report" type="text" value="<?php echo @$nama_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Email</label>
      <input type="text" name="email_report" value="<?php echo @$email_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Nomor HP</label>
      <input type="text" name="kontak_report" value="<?php echo @$kontak_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Jenis Kelamin</label>
        <select name="sex_report" id="sex_report" class="form-control ">
            <option value=''>--PILIH JENIS KELAMIN--</option>
            <option value='L'<?php if(!empty($sex_report) && $sex_report == "L"){?> selected <?php } ?>>LAKI-LAKI</option>
            <option value='P'<?php if(!empty($sex_report) && $sex_report == "P"){?> selected <?php } ?>>PEREMPUAN</option>
        </select>
    </div>
    <div class="form-group col-md-4">
      <label>Kode COIN</label>
      <input type="text" name="nocoin" value="<?php echo @$nocoin; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Status COIN</label>
        <select name="coin_stat" id="coin_stat" class="form-control">
            <option value=''>--PILIH STATUS COIN--</option>
            <option value='1' <?php if(!empty($coin_stat) && $coin_stat == 1){?> selected <?php } ?>>PENGAJUAN</option>
            <option value='2' <?php if(!empty($coin_stat) && $coin_stat == 2){?> selected <?php } ?>>REVIEW</option>
            <option value='3' <?php if(!empty($coin_stat) && $coin_stat == 3){?> selected <?php } ?>>AKTIF</option>
        </select>
    </div>
    
    <div class="form-group col-md-4">
      <label>Nomor Identitas</label>
      <input type="text" name="id_member_report" id="id_member_report" value="<?php echo @$id_member_report; ?>" class="form-control " />
    </div>
    <div class="form-group col-md-4">
      <label>Alamat</label>
      <input type="text" name="alamat_report" class="form-control" value="<?php echo @$alamat_report; ?>" />
    </div>
    <div class="form-group col-md-4">
      <label>Propinsi</label>
        <select name="propinsi_report" id="propinsi2" class="form-control">
            <option value=''>--PILIH PROPINSI--</option>
            <?php
            $query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
            while($data_propinsi = $db->fetchNextObject($query_propinsi)){
            ?>
                <option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi_report) && $propinsi_report == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
    <span id="div_kota_report"><?php if(!empty($kota_report)){ include $call->inc($ajax_dir,"data.php"); }?></span>

    <div class="form-group col-md-12">
      <label >&nbsp;</label><br>
      <button name='direction' id='direction' type="submit" class="btn btn-sempoa-1" value="show">
      	<i class="fa fa-eye"></i> Lihat Data
      </button>
    </div>

</form>
