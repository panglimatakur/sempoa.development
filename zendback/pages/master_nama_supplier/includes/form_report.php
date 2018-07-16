<?php defined('mainload') or die('Restricted Access'); ?>
<form method="post" action="" >
    <div class="form-group col-md-4">
       <label>Nama</label>
      <input name="nama_report" type="text" value="<?php echo @$nama_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>No Tlp</label>
      <input type="text" name="tlp_report" value="<?php echo @$tlp_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Kontak Person</label>
      <input type="text" name="kontak_report" value="<?php echo @$kontak_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Email</label>
      <input type="text" name="email_report" value="<?php echo @$email_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Website</label>
      <input type="text" name="website_report" value="<?php echo @$website_report; ?>" class="form-control" />
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
    <div class="form-group col-md-4">
      <label>Alamat</label>
      <textarea name="alamat_report" class="form-control"><?php echo @$alamat_report; ?></textarea>
    </div>
    <div class="form-group col-md-12">
      <label >&nbsp;</label><br />
        <button name='direction' id='direction' type="submit" class="btn btn-sempoa-2" value="show">
            <i class="icsw16-white icsw16-magnifying-glass"></i> Lihat Data
         </button>
    </div>
    <div class="clearfix"></div>
</form>
