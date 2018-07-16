<?php defined('mainload') or die('Restricted Access'); ?>
    <span id='divparent_id'>
        <?php if(!empty($parent_id)){ include $call->inc($ajax_dir,"parent.php"); } ?>
    </span>
    <div class="form-group col-md-6">
        <label class='req'>Posisi Nama Halaman</label>
        <select name="posisi" id="posisi" class="form-control validate[required] text-input"/>
            <option value="top" 	<?php if(!empty($posisi) && $posisi =="top"){?> selected<?php } ?>>ATAS</option>
            <option value="bottom" 	<?php if(!empty($posisi) && $posisi =="bottom"){?> selected<?php } ?>>BAWAH</option>
            <option value="left" 	<?php if(!empty($posisi) && $posisi =="left"){?> selected<?php } ?>>KIRI</option>
            <option value="right" 	<?php if(!empty($posisi) && $posisi =="right"){?> selected<?php } ?>>KANAN</option>
        </select>		
    </div>
    <div class="form-group col-md-6">
        <label class='req'>Betuk Halaman</label>
        <select name="is_folder" id="is_folder" onchange="getcontenttype('<?php echo $ajax_dir; ?>/type.php?page=<?php echo $page; ?>','divctype');" class="form-control validate[required] text-input">
        <option value=''>--BENTUK LINK--</option>
        <option value='1' <?php if(!empty($is_folder) && $is_folder == 1){ echo "selected"; } ?>>Folder</option>
        <option value='2' <?php if(!empty($is_folder) && $is_folder == 2){ echo "selected"; } ?>>File</option>
        </select>
    </div>
    <span id='divctype'>
		<?php if(!empty($direction) && $direction != "delete" && $is_folder == 2){ include($ajax_dir."/type.php"); } ?>
    </span>
    <div class="form-group col-md-6">
        <label class='req'>Status Halaman</label><br />
        <input type="checkbox" id="sb_off" name='status' value='1' <?php if(!empty($status) && $status == 1){?> checked <?php } ?>/>

    </div>
    <div class="form-group col-md-6">
        <label>Icon </label>
        <?php 
        if(!empty($direction) && ($direction == "insert" || $direction == "save"))  {
            if(is_file($basepath."/files/image/icons/menu/".@$icon)){
        ?>
            <img src='<?php echo $dirhost; ?>/files/image/icons/menu/<?php echo $icon; ?>' width="30"/><br />
        <?php } 
        } ?>
        <input type="file" name="icon" id="icon" class='file_1'/>
    </div>
    <div class="form-group col-md-12">
        <label class='req'>Nama Halaman</label>
        <input name="nama" id="nama" type="text" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input"/>
    </div>
    <div class="form-group col-md-12">
        <label class='req'>Judul Halaman</label>
        <input name="judul" id="judul" type="text"  value="<?php echo @$judul; ?>" class="form-control validate[required] text-input"/> 
        <span id="counter"></span>
    </div>
    <div class="form-group col-md-12">
        <label >Keywords</label>
        <input name="keywords" id="keywords" type="text"  value="<?php echo @$keywords; ?>" class="form-control"/>
    </div>
    <div class="form-group col-md-12">
        <label >Description</label>
        <textarea name="description" id="description" class="form-control "><?php echo @$description; ?></textarea>
    </div>
