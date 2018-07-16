<?php defined('mainload') or die('Restricted Access'); ?>
    <span id='divparent_id'>
        <?php if(!empty($parent_id)){ include $call->inc($ajax_dir,"parent.php"); } ?>
    </span>
    <?php if($_SESSION['uclevelkey'] == 1){?>
        <div class="form-group col-md-12">
          <label>Oleh</label>
            <select name="purple" id="purple" class="form-control">
                <option value=''>--PILIH PURPLE--</option>
                <?php
                $query_purple = $db->query("SELECT 
												ID_CLIENT,CLIENT_NAME
											FROM 
												".$tpref."clients
											WHERE 
												CLIENT_ID_PARENT = '1'
											ORDER BY 
												CLIENT_NAME ASC");
                while($data_purple = $db->fetchNextObject($query_purple)){
                ?>
                    <option value='<?php echo $data_purple->ID_CLIENT; ?>' <?php if(!empty($purple) && $purple == $data_purple->ID_CLIENT){?> selected<?php } ?>><?php echo $data_purple->CLIENT_NAME; ?>
                    </option>
            <?php } ?>
            </select>
        </div>
    <?php } ?>
    <div class="form-group col-md-12">
        <?php 
        if(!empty($direction) && ($direction == "insert" || $direction == "save"|| $direction == "edit"))  {
            if(is_file($basepath."/files/images/logos/".@$photo)){
        ?>
        	<div class="thumbnail" style="width:40%">
            	<div class="thumbnail-inner">
                <img src='<?php echo $dirhost; ?>/files/images/logos/<?php echo $photo; ?>' width="100%"/>
                </div>
        	</div>
		<?php } 
        } ?>
    
        <label>Logo</label>
        <input type="file" name="photo" id="photo" class='file_1'/>
    </div>
    <div class="form-group col-md-12">
        <?php 
        if(!empty($direction) && ($direction == "insert" || $direction == "save" || $direction == "edit"))  {
			$r = 0;
			$discon_icon_folder = $discoin_folder."/".$no."-".$app;
			$discoin_icon_dir	= $dirhost."/files/images/icons/discoin/".$no."-".$app;
			if(is_dir($discon_icon_folder)){
				while($r < 3){ $r++;
            		if(is_file($discon_icon_folder."/res/".$folder[$r]."/icon.png")){
        ?>
                    <img src='<?php echo $discoin_icon_dir; ?>/res/<?php echo $folder[$r]; ?>/icon.png' 
                    	 style="float:left"/>
		<?php 		} 
				}
			}
		?>
    	<div class="clearfix"></div>
        <?php
        } ?>
        <label>Icon Discoin</label>
        <input type="file" name="icon" id="icon" class='file_1'/>
    </div>
    <div class="form-group col-md-6">
        <label class="req">Tingkat Client</label>
        <select name="id_client_level" id="id_client_level" class="form-control validate[required] text-input" >
            <option value="">--LEVEL CLIENT--</option>
            <?php while($dt_client_level = $db->fetchNextObject($q_client_level)){?>
                <option value='<?php echo $dt_client_level->ID_CLIENT_LEVEL; ?>' <?php if(!empty($id_client_level) && $id_client_level == $dt_client_level->ID_CLIENT_LEVEL){?> selected<?php } ?>>
                    <?php echo $dt_client_level->ID_CLIENT_LEVEL; ?> - <?php echo $dt_client_level->NAME; ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
        <label class="req">Nama Client</label>
        <input name="nama"  id="nama" type="text" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input" style="text-transform:uppercase"/>
    </div>
    <div class="form-group col-md-12">
      <label class="req">Alamat</label>
      <textarea name="alamat" id="alamat" class="form-control validate[required] text-input" style="text-transform:capitalize"><?php echo @$alamat; ?></textarea>
    </div>
    <div class="form-group col-md-6">
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
    <div class="form-group col-md-6">
      <label class="req">No Tlp</label>
      <input type="text" name="tlp" id="tlp" value="<?php echo @$tlp; ?>" class="form-control validate[required] text-input" />
    </div>
    <div class="form-group col-md-6">
      <label class="req">Email</label>
      <input type="text" name="email" id="email" value="<?php echo @$email; ?>" class="form-control validate[required] text-input" />
    </div>
    <div class="form-group col-md-6">
      <label>Website</label>
      <input type="text" name="website" id="website" value="<?php echo @$website; ?>" class="form-control" style="text-transform:lowercase"/>
    </div>
    
    <div class="form-group col-md-6">
      <label>Nama Kontak Person</label>
      <input type="text" name="nmkontak" id="nmkontak" value="<?php echo @$nmkontak; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-6">
      <label>HP Kontak Person</label>
      <input type="text" name="kontak" id="kontak" value="<?php echo @$kontak; ?>" class="form-control" />
    </div>

    <div class="form-group col-md-12">
      <label>Program Refferal</label><br />
      <small>Pilih <b>"Ya"</b>, jika Merchant ini mengikuti program refferal</small><br /><br />
      <input type="checkbox" name="ch_ref" class="i-switch" <?php if(!empty($ch_ref) && $ch_ref == 1){?> checked <?php } ?>> 
      
    </div>
    <div class="form-group col-md-12">
      <label class="req">Deskripsi Client</label>
      <textarea name="deskripsi" id="deskripsi" class="form-control validate[required] text-input"><?php echo @$deskripsi; ?></textarea>
    </div>
    <div class="form-group col-md-12">
        <label class="req">Warna</label>
        <div class="input-daterange input-group" id="datepicker">
            <input type='text' name='w[1]' value='<?php echo @$w[1]; ?>' id="color_1" class="validate[required] text-input form-control" placeholder="Warna 1"/>
            <span class="input-group-addon"> Dan </span>
            <input type='text' name='w[2]' value='<?php echo @$w[2]; ?>' id="color_2" class="validate[required] text-input form-control" placeholder="Warna 1"/>
        </div>
    </div>        
    <div class="form-group col-md-6">
        <label>Tanggal Berakhir</label>
        <div class="input-append date" id="dp2" data-date="<?php echo date("d-m-Y"); ?>" data-date-format="dd-mm-yyyy">
            <input class="form-control" name="expiration" size="16" value="<?php if(!empty($expiration)){ echo $expiration; }else{ echo date("d-m-Y"); } ?>" readonly type="text">
            <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
    </div>
    <div class="form-group col-md-6">
      <label class="req">Nama Aplikasi</label>
      <input type="text" name="app" id="app" value="<?php echo @$app; ?>" class="form-control validate[required] text-input" readonly/>
      <span id='app_load'></span>
    </div>
    <?php if($_SESSION['cparentkey'] == 1 || $_SESSION['cidkey'] == "1"){?>
    <!--<div class="form-group">
      <label class="req">Pernyataan Discount</label>
      <textarea name="statement" id="statement" class="form-control"><?php echo @$statement; ?></textarea>
    </div>-->
    <?php } ?>
    <div class="form-group col-md-12">
    	<label>&nbsp;</label><br />
		<?php
        if(empty($direction) || (!empty($direction) && ($direction == "insert" || $direction == 'delete'))){
			$directionvalue	= "insert";	
        }
        if(!empty($direction) && ($direction == "edit" || $direction == "save")){
			$directionvalue = "save";
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
        <input type='hidden' name='no' id="no" value='<?php echo $no; ?>'>
        <?php } ?>
        <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
        <input type='hidden' id='parent_page' value='<?php echo $ajax_dir; ?>/parent.php'>
        <button name="direction" id="direction" type="submit"  class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>"><i class="icsw16-white icsw16-create-write"></i> Simpan Data</button>
        <?php echo @$addbutton; ?>
        
    </div>
