<?php defined('mainload') or die('Restricted Access'); ?>
	<?php if((empty($direction) && allow('insert') == 1) || (!empty($direction) && $direction == "edit" && allow('edit') == 1)){?> 
    <span id='divparent_id'>
        <?php if(!empty($parent_id)){ include $call->inc($ajax_dir,"parent.php"); } ?>
    </span>
    <div class="form-group">
        <label>Tipe Kategori</label>
        <select name="contenttype" id="contenttype" class="form-control validate[required] text-input">
            <option value=''>--TIPE KATEGORI--</option>
            <option value='1' <?php if(@$contenttype == "1"){ ?> selected <?php } ?>>Produk Barang</option>
            <option value='2' <?php if(@$contenttype == "2" ){ ?> selected <?php } ?>>Produk Jasa</option>
         </select>
    </div>    
    <div class="form-group">
        <label class='req'>Nama Kategori</label>
        <input name="nama" id="nama" type="text" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
    </div>
    <div class="form-group">
        <label class='req'>Judul Kategori</label>
        <input name="judul" id="judul" type="text"  value="<?php echo @$judul; ?>" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
    </div>
	<div class="form-group">
        <label class='req'>Status Kategori</label><br />
        <input type="checkbox" id="sb_off" name='status' value='1' <?php if(!empty($status) && $status == 1){?> checked <?php } ?>/>
    </div>
    <div class="form-group">
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
						<i class='fa fa-plus'></i> Tambah Data
					</button>
				</a>";
        ?>
        <input type='hidden' name='no' value='<?php echo $no; ?>'>
        <?php } ?>
        <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
        <input type='hidden' id='parent_page' value='<?php echo $ajax_dir; ?>/parent.php'>
        <button name="direction" type="submit"  class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>">
        	<i class="icsw16-white icsw16-create-write"></i> Simpan Data
        </button>
        <?php echo @$addbutton; ?>
    </div>
<?php }else{
	echo msg("Maaf, Anda Tidak Di Izinkan melakukan Pengisian Data Baru Kategori, karena hak proses anda di batasi","error");	
}?>