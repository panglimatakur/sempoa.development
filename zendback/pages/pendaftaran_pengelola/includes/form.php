<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
<?php if((empty($direction) && allow('insert') == 1) || 
		 (!empty($direction) && allow('edit') == 1)){?> 
    <iframe src="" id="download" frameborder="0" height="0" width="0"></iframe><!---->
    <div class="col-md-4">
        <div class="form-group">
            <?php 
            if(!empty($direction) && ($direction == "insert" || $direction == "save"|| $direction == "edit"))  {
                if(is_file($basepath."/files/images/users/".@$photo)){
            ?>
            <div class="thumbnail">
                <div class="thumbnail-inner" style="max-height:300px; overflow:hidden">
                	<img src='<?php echo $dirhost; ?>/files/images/users/big/<?php echo $photo; ?>' style="width:100%"/>
            	</div>
            </div>
			<?php } 
            } ?>
            <label>Foto User </label>
            <input type="file" name="photo" id="photo" class='file_1'/>
        </div>
    </div>
    <div class="col-md-4">
        <?php if($_SESSION['cidkey'] == 1 && $_SESSION['ulevelkey'] == 1){?>
        <div class="form-group">
        	<label class="req">Pengguna dari Merchant</label>
            <select id="productGroup" name="productGroup" class="form-control chosen-select validate[required]">
            	<option class="category" value="">-- PILIH MERCHANT --</option>
                <?php while($dt_merchant = $db->fetchNextObject($q_merchant)){ ?>
                	<option class="category" 
                    		value="<?php echo $dt_merchant->ID_CLIENT; ?>"
                            <?php if(!empty($parent_id) && $dt_merchant->ID_CLIENT == $parent_id){?>selected<?php } ?>>
						<?php echo $dt_merchant->CLIENT_NAME; ?>
                    </option>
                    <?php echo merchant_list($dt_merchant->ID_CLIENT,1);?>
               	<?php } ?>
            </select>
    	</div>
        <?php } ?>
        <input type="hidden" name="parent_id" id="parent_id" value="<?php echo @$parent_id; ?>" />
        <div class="form-group">
            <label class="req">Nama</label>
            <input name="name"  id="name" type="text" value="<?php echo @$name; ?>" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
        </div>
        <div class="form-group">
            <label class="req">Email</label>
            <input name="user_email"  id="user_email" type="text" value="<?php echo @$user_email; ?>" class="form-control validate[required] text-input"/>
        </div>
        <div class="form-group">
            <label class="req">Password</label>
            <input name="user_pass"  id="user_pass" type="password" value="<?php echo @$user_pass; ?>" class="form-control validate[required] text-input"/>
        </div>
        <div class="form-group">
            <label>Keterangan </label>
            <textarea name="add_info"  id="add_info" type="text" class="form-control"><?php echo @$add_info; ?></textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
          <label class="req">Jabatan</label>
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
        <?php 		  	
            $last_index = 1;
            if(!empty($direction) && $direction == "edit" && $num_doc > 0){
        ?>
        <div class="form-group">
          <label class="req">Dokumen</label>
          <?php while($dt_doc = $db->fetchNextObject($q_doc)){?>
            <div class='file_list' id="list_<?php echo $dt_doc->ID_DOCUMENT; ?>">
                <div class="col-md-8 no-padding-l file-name-doc"><?php echo cutext($dt_doc->FILE_DOCUMENT,20); ?></div>
                <div class="col-md-4">
                    <div class="btn-group">
                        <a href='javascript:void()' class='btn btn-sm btn-sempoa-3 delete_file' data-info='<?php echo $dt_doc->ID_DOCUMENT; ?>'>
                            <i class="fa fa-trash"></i>
                        </a>
                        <a href='javascript:void()' class='btn btn-sm btn-sempoa-4 download' onclick="download_document('<?php echo $dt_doc->ID_DOCUMENT; ?>')">
                            <i class='fa fa-download'></i>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
             </div>
          <?php	} ?>
        </div>
        <?php } ?>
        <div id="ans_content">
            <div class='form-group elm' id="pilihan_<?php echo $last_index; ?>">
                <label class="option">Dokumen <?php echo $last_index; ?></label>
                <div class="input-group">
                	<button class="btn btn-sm btn-white btn-get-file" type="button" value="<?php echo $last_index; ?>"
                    		onclick="get_file('<?php echo $last_index; ?>')">
                    	<i class="icsw16-books"></i> Unggah Dokumen
                    </button>
                    <input type="file" name="document[]" style="display:none" id="doc_file_<?php echo $last_index; ?>">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-sm btn-primary" onclick="add_document('<?php echo $last_index; ?>')" id="add_doc">
                            <i class='fa fa-plus'></i> Tambah
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
          <label>Hak Khusus Proses  </label><br />
          <small>(Hak Pengguna dalam melakukan proses transaksi)</small>
            <table width="100%" border="0">
              <tr>
                <td width="90%">Tambah Data</td>
                <td width="10%">
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
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-8">
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
            <input type='hidden' name='no' value='<?php echo $no; ?>'>
            <?php } ?>
            <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
            <input type='hidden' id='download_page' value='<?php echo $ajax_dir; ?>/download.php'>
            <button name="direction" type="submit"  class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>">
                <i class="fa fa-check-square-o"></i> Simpan Data
            </button>
            <?php echo @$addbutton." ".@$delbutton; ?>
        </div>
    </div>
<?php }else{
	echo msg("Maaf, Anda Tidak Di Izinkan melakukan Pengisian Data Baru Pengguna, karena hak proses anda di batasi","error");	
}?>
</form>