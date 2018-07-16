<?php defined('mainload') or die('Restricted Access'); ?>
<?php  if(allow('insert') == 1 || (!empty($direction) && $direction == "edit" && allow('edit') == 1)){?> 
<form method="post" action="" enctype="multipart/form-data">
<div class="col-md-4">
    <div class="form-group">
        <label>Foto User </label>
        <?php 
        if(!empty($direction) && ($direction == "insert" || $direction == "save"|| $direction == "edit"))  {
            if(is_file($basepath."/files/images/members/".@$photo)){
        ?>
        <div class="thumbnail">
        	<div class="thumbnail-inner" style="max-height:500px; overflow:hidden">
            	<img src='<?php echo $dirhost; ?>/files/images/members/<?php echo $photo; ?>' width="100%"/>
        	</div>
        </div>
		<?php } 
        } ?>
        <input type="file" name="photo" id="photo" class='file_1'/>
    </div>
</div>
<div class="col-md-8">
	<?php if($_SESSION['cidkey'] == 1 && $_SESSION['ulevelkey'] == 1){?>
    <div class="form-group col-md-6">
        <label class="req">Pelanggan dari Merchant</label><br />
        <select id="productGroup" name="productGroup" class="form-control chosen-select validate[required]">
            <option class="category" value="">-- PILIH MERCHANT --</option>
            <?php while($dt_merchant = $db->fetchNextObject($q_merchant)){ ?>
                <option class="category" 
                        value="<?php echo $dt_merchant->ID_CLIENT; ?>"
                        <?php if(!empty($id_client_form) && $dt_merchant->ID_CLIENT == $id_client_form){?>
                        	selected
						<?php } ?>>
                    <?php echo $dt_merchant->CLIENT_NAME; ?>
                </option>
                <?php echo merchant_list($dt_merchant->ID_CLIENT,1);?>
            <?php } ?>
        </select>
    </div>
    <?php } ?>
    <input type="hidden" name="id_client_form" id="id_client_form" value="<?php echo @$id_client_form; ?>" />
    <div class="form-group col-md-6">
        <label>Kode COIN (Jika ada)</label>
        <input name="number"  id="number" type="text" value="<?php echo @$number; ?>" class="form-control validate[required] text-input uppercase"/>
    </div>
    <div class="form-group col-md-6">
       <label class="req">Nama</label>
      <input name="nama" type="text" id="nama" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
    </div>
    <div class="form-group col-md-6">
      <label class="req">Email</label>
      <input type="text" name="email" id="email" value="<?php echo @$email; ?>" class="mousetrap form-control validate[required] text-input" style="text-transform:lowercase"/>
    </div>
    <div class="form-group col-md-6">
      <label class="req">Nomor HP</label>
      <input type="text" name="kontak" id="kontak" value="<?php echo @$kontak; ?>" class="mousetrap form-control validate[required] text-input" />
    </div>
    <div class="form-group col-md-6">
      <label class="req">Jenis Kelamin</label>
        <select name="sex" id="sex" class="form-control validate[required] text-input">
            <option value=''>--PILIH JENIS KELAMIN--</option>
            <option value='L' <?php if(!empty($sex) && $sex == "L"){?> selected <?php } ?>>LAKI-LAKI</option>
            <option value='P' <?php if(!empty($sex) && $sex == "P"){?> selected <?php } ?>>PEREMPUAN</option>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label>Nomor Identitas</label>
      <input type="text" name="id_member" id="id_member" value="<?php echo @$id_member; ?>" class="form-control " style='text-transform:uppercase'/>
    </div>

    <div class="form-group col-md-6">
      <label >Propinsi</label>
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
    <div class="form-group col-md-12">
      <label>Alamat</label>
      <textarea name="alamat" id="alamat" class="form-control validate[required]" style="text-transform:capitalize"><?php echo @$alamat; ?></textarea>
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
							class='btn btn-info' 
							value='Tambah Data'>
							<i class='fa fa-plus'></i>  Tambah Data
					</button>
                </a>";
			$delbutton = "
					<button name='button' 
							type='button' 
							class='btn btn-danger' 
							value='Tambah Data'
							onclick='removal(\"".$no."\",\"form\")'>
							<i class='fa fa-trash'></i>  Hapus Data
					</button>";
    ?>
        <input type='hidden' name='no' id='no' value='<?php echo $no; ?>' />
        <?php
        }
    ?>
        <button name="Submit" type="submit" id="button_cmd" class="btn btn-sempoa-1">
        	<i class="fa fa-check-square-o"></i> Simpan Data
        </button>
		<?php echo @$addbutton." ".@$delbutton; ?>
        <input type='hidden' name='direction' id='direction' value='<?php echo $prosesvalue; ?>' />
    </div>
    
    <div class="clearfix"></div>
</div>
</form>
<?php }else{
	echo msg("Maaf, Anda Tidak Di Izinkan melakukan Pengisian Data Baru Pelanggan, karena hak proses anda di batasi","error");	
}?>