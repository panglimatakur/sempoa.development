<?php defined('mainload') or die('Restricted Access'); ?>
<?php if((empty($direction) && allow('insert') == 1) || 
		 (!empty($direction) && allow('edit') == 1)){?> 
    <span id='divparent_id'>
        <?php if(!empty($parent_id)){ include $call->inc($ajax_dir,"parent.php"); } ?>
    </span>
    <iframe src="" id="download" frameborder="0" height="0" width="0"></iframe>
    <div class="form-group">
        <label>Foto User </label>
        <?php 
        if(!empty($direction) && ($direction == "insert" || $direction == "save"|| $direction == "edit"))  {
            if(is_file($basepath."/files/images/users/".@$photo)){
        ?>
            <img src='<?php echo $dirhost; ?>/files/images/users/<?php echo $photo; ?>' width="30%" class='photo'/><br />
        <?php } 
        } ?>
        <input type="file" name="photo" id="photo" class='file_1'/>
    </div>
    <div class="form-group">
        <label class="req">Nama User</label>
        <input name="name"  id="name" type="text" value="<?php echo @$name; ?>" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
    </div>
    <div class="form-group">
        <label class="req">Password User</label>
        <input name="user_pass"  id="user_pass" type="password" value="<?php echo @$user_pass; ?>" class="form-control validate[required] text-input"/>
    </div>
    <div class="form-group">
        <label class="req">Email User</label>
        <input name="user_email"  id="user_email" type="text" value="<?php echo @$user_email; ?>" class="form-control validate[required] text-input"/>
    </div>
    <div class="form-group">
      <label class="req">Jabatan User</label>
        <select name="level" id="level" class="form-control validate[required] text-input">
            <option value=''>--PILIH JABATAN--</option>
            <?php
            while($data_level = $db->fetchNextObject($query_level)){
            ?>
                <option value='<?php echo $data_level->ID_CLIENT_USER_LEVEL; ?>' <?php if(!empty($level) && $level == $data_level->ID_CLIENT_USER_LEVEL){?> selected<?php } ?>><?php echo $data_level->NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label>Keterangan </label>
        <textarea name="add_info"  id="add_info" type="text" class="form-control"><?php echo @$add_info; ?></textarea>
    </div>
	<?php 		  	
        $last_index = 1;
        if(!empty($direction) && $direction == "edit" && $num_doc > 0){
    ?>
    <div class="form-group">
      <label class="req">Dokumen</label>
      <?php
	  	while($dt_doc = $db->fetchNextObject($q_doc)){
	  ?>
      	<div class='file_list' id="list_<?php echo $dt_doc->ID_DOCUMENT; ?>">
	  		<?php echo $dt_doc->FILE_DOCUMENT; ?>
      		<a href='javascript:void()' class='btn btn-mini delete_file' data-info='<?php echo $dt_doc->ID_DOCUMENT; ?>'>
            	<i class="icsw16-trashcan"></i>
            </a>
            <a href='javascript:void()' class='btn btn-mini download' data-info='<?php echo $dt_doc->ID_DOCUMENT; ?>'><!--onclick="downloads('<?php echo $dt_doc->ID_DOCUMENT; ?>')"-->
            	<i class='icon-download-alt'></i>
            </a>
         </div>
	  <?php	
		}
	  ?>
	</div>
	<?php } ?>
    <div id="ans_content">
        <div class='form-group elm' id="pilihan_<?php echo $last_index; ?>">
            <label class="option">Dokumen <?php echo $last_index; ?></label>
            <input type="file" name="document[]">
            <button type="button" class="btn" style='margin:-9px 0 0 0' id="add_more"><i class='icon-plus'></i>Tambah Pilihan</button>
        </div>
    </div>
    <div class="form-group">
      <label>Hak Khusus Proses (Hak Pengguna dalam melakukan proses transaksi) </label>
        <table width="100%" border="0">
          <tr>
            <td>Tambah Data</td>
            <td>
<input type="checkbox" name="insert_proses" id="akses_off_1" value='1' style="margin:0" <?php if(empty($direction) || (!empty($insert_proses) && $insert_proses == 1)){ ?>checked<?php } ?>/>
            </td>
          </tr>
          <tr>
            <td>Perbaikan Data</td>
            <td>
<input type="checkbox" name="edit_proses" id="akses_off_2" value='1' style="margin:0"  <?php if((!empty($direction) && $edit_proses == 1)){ ?>checked<?php } ?>/>
            </td>
          </tr>
          <tr>
            <td>Hapus Data</td>
            <td>
<input type="checkbox" name="delete_proses" id="akses_off_3" value='1' style="margin:0"  <?php if(empty($direction) || (!empty($delete_proses) && $delete_proses == 1)){ ?>checked<?php } ?>/>
            
            </td>
          </tr>
        </table>
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
					<input name='button' type='button' class='btn btn-beoro-3' value='Tambah Data'>
				</a>";
        ?>
        <input type='hidden' name='no' value='<?php echo $no; ?>'>
        <?php } ?>
        <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
        <input type='hidden' id='download_page' value='<?php echo $ajax_dir; ?>/download.php'>
        <input type='hidden' id='parent_page' value='<?php echo $ajax_dir; ?>/parent.php'>
        <button name="direction" type="submit"  class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>">Simpan Data</button>
        <?php echo @$addbutton; ?>
    </div>
<?php }else{
	echo msg("Maaf, Anda Tidak Di Izinkan melakukan Pengisian Data Baru Pengguna, karena hak proses anda di batasi","error");	
}?>