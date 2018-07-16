<?php defined('mainload') or die('Restricted Access'); ?>
<?php if((empty($direction) && allow('insert') == 1) || (!empty($direction) && $direction == "edit" && allow('edit') == 1)){?> 
<form method="post" action="" >
    <div class="form-group col-md-4">
       <label class="req">Nama</label>
      <input name="nama" type="text" id="nama" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input" />
    </div>
    <div class="form-group col-md-4">
      <label class="req">No Tlp</label>
      <input type="text" name="tlp" id="tlp" value="<?php echo @$tlp; ?>" class="form-control validate[required] text-input" />
    </div>
    <div class="form-group col-md-4">
      <label class="req">Kontak Person</label>
      <input type="text" name="kontak" id="kontak" value="<?php echo @$kontak; ?>" class="form-control validate[required] text-input" />
    </div>
    <div class="form-group col-md-4">
      <label>Email</label>
      <input type="text" name="email" id="email" value="<?php echo @$email; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label>Website</label>
      <input type="text" name="website" id="website" value="<?php echo @$website; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-4">
      <label class="req">Propinsi</label>
        <select name="propinsi" id="propinsi" class="form-control validate[required] text-input">
            <option value=''>--PILIH PROPINSI--</option>
            <?php
            $query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
            while($data_propinsi = $db->fetchNextObject($query_propinsi)){
            ?>
                <option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
    <span id="div_kota"><?php if(!empty($kota)){ include $call->inc($ajax_dir,"data.php"); }?></span>
    <div class="form-group col-md-4">
      <label class="req">Alamat</label>
      <textarea name="alamat" id="alamat" class="form-control validate[required] text-input"><?php echo @$alamat; ?></textarea>
    </div>
    <div class="form-group col-md-12">
      <label >&nbsp;</label><br />
        <?php
        if(empty($direction) || 
        (!empty($direction) && ($direction != "edit" || $direction != "show"))){
            $prosesvalue = "insert";	
        }
        if(!empty($direction) && ($direction != "get_form" && ($direction != "insert" || $direction != "delete" || $direction != "show"))){
            $prosesvalue = "save";
            $addbutton = "
                <a href='".$lparam."'>
					<button name='button' 
							type='button' 
							class='btn btn-danger' 
							value='Tambah Data'>
							<i class='fa fa-plus'></i>  Tambah Data
					</button>
                </a>";
    ?>
        <input type='hidden' name='no' id='no' value='<?php echo $no; ?>' />
        <?php
        }
    ?>
        <button name="Submit" type="submit" id="button_cmd" class="btn btn-sempoa-1">
            <i class="icsw16-white icsw16-create-write"></i> Simpan Data
        </button>
        <?php echo @$addbutton; ?>
        <input type='hidden' name='direction' id='direction' value='<?php echo $prosesvalue; ?>' />
    </div>
    <div class="clearfix"></div>
</form>
<?php }else{
	echo msg("Maaf, Anda Tidak Di Izinkan melakukan Pengisian Data Baru Supplier, karena hak proses anda di batasi","error");	
}?>