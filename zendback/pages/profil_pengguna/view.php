<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-12">
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap, silahkan isi form yang bertanda bintang ( <b>*</b> ) ","error");
                break;
                case "3":
                    echo msg("Alamat Email ini sudah terdaftar, silahkan gunakan email lain","error");
                break;
            }
        }
    ?>
    <iframe src="" id="download" frameborder="0" height="0" width="0"></iframe>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h4>Profil Pengguna</h4>
        </div>
        <form name="formyu" id="formID" class='form' method="post" action="" enctype="multipart/form-data">
        <div class="ibox-content">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Foto Pengguna </label>
                    <?php 
                        if(is_file($basepath."/files/images/users/big/".@$photo)){
                    ?>
                        <div class="thumbnail">
                            <div class="thumbnail-inner" style="height:260px">
                        <img src='<?php echo $dirhost; ?>/files/images/users/big/<?php echo $photo; ?>' style='width:100%'/>
                            </div>
                        </div>
                    <?php } 
                    ?>
                    <input type="file" name="photo" id="photo" class='file_1'/>
                </div>
            </div>
            <div class="col-md-8 no-padding">
                <div class="form-group col-md-6">
                  <label class="req">Email</label>
                  <input type="email" name="email" id="email" value="<?php echo @$email; ?>" class="form-control lowercase validate[required] text-input" >
                </div>
                <div class="form-group col-md-6">
                  <label>Password</label>
                  <input type="text" name="password" id="password" value="<?php echo @$password; ?>" class="form-control" >
                </div>
                <div class="form-group col-md-6">
                  <label class="req">Nama</label>
                  <input type="text" name="name" id="name" value="<?php echo @$name; ?>" class="form-control  validate[required] text-input capitalize" >
                </div>
                <div class="form-group col-md-6">
                  <label class="req">No HP</label>
                  <input type="number" name="phone" id="phone" value="<?php echo @$phone; ?>" class="form-control validate[required] text-input" >
                </div>
                <div class="form-group col-md-12">
                  <label class="req">Alamat</label>
                  <textarea name="alamat" id="alamat" class="form-control capitalize validate[required] text-input" ><?php echo @$alamat; ?></textarea>
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
                <span id="div_kota"><?php if(!empty($propinsi))	{ include $call->inc($ajax_dir,"data.php"); }?></span>
                <span id="div_kecamatan"></span>
                <span id="div_kelurahan"></span>
                <div class="clearfix"></div>
                <?php 		  	
                    $last_index = 1;
                    if($num_doc > 0){
                ?>
                <div class="form-group col-md-12">
                  <label>Dokumen</label>
                  <?php while($dt_doc = $db->fetchNextObject($q_doc)){?>
                    <div class='file_list' id="list_<?php echo $dt_doc->ID_DOCUMENT; ?>">
                        <div class="col-md-10 no-padding-l file-name-doc">
                            <?php echo cutext($dt_doc->FILE_DOCUMENT,50); ?>
                        </div>
                        <div class="col-md-2 no-padding-lr">
                            <div class="btn-group pull-right">
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
                
                
                <div  class="col-md-12" id="ans_content">
                    <div class='elm' id="pilihan_<?php echo $last_index; ?>">
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
                    



                <div class="col-md-12">   
                     <div class="form-group">
                         <button type="submit" name="direction" class="btn btn-sempoa-1" value="save"/>
                            <i class="fa fa-check-square-o"></i> Simpan Data
                         </button>
                        <input type='hidden' id='data_page' value='<?php echo $ajax_dir; ?>/data.php'>
                        <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
                        <input type='hidden' id='download_page' value='<?php echo $ajax_dir; ?>/download.php'>
                        <div class="clearfix"></div>
                    </div>
                </div>
                
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        </form>
        
        
    </div>
</div>