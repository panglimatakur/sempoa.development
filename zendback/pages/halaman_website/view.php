<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class="ibox float-e-margins">
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Link Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Data Link Berhasil Disimpan dan Di Perbaiki","success");
                break;
                case "3":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
        }
    ?>
    <div class="ibox-content no-padding-lr">
        <div class="col-md-6">
            <div class="ibox-title">
                <h5>Menu Induk Website</h5>
            </div>
            <div class="ibox-content inner-content-div">
                <?php include $call->inc($page_dir."/includes","list.php");  ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title">
                <h5>Pendaftaran Menu Website</h5>
            </div>
            <div class="ibox-content"><?php include $call->inc($page_dir."/includes","form.php"); ?></div>
        </div>
        <div class="col-md-12">
            <div class="ibox-title">
                <h5>Isi Halaman Website</h5>
            </div>
            <div class="ibox-content">
            
                <div class="form-group col-md-12">
                    <?php include $call->lib("redactor"); ?>
                    <script type="text/javascript">
                    $(document).ready(
                        function(){
                            $('#redactor_content').redactor({
                                imageUpload: '<?php echo $redactor; ?>scripts/image_upload.php'
                            });
                        }
                    );
                    </script>
                    <textarea id="redactor_content" name="isi"><?php echo @$isi; ?></textarea>
                </div>
                <div class="form-group col-md-12">
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
                    <button name="direction" 
                    		type="submit"  
                            class="btn btn-sempoa-1" 
                            value="<?php echo $directionvalue; ?>">
                            <i class="icsw16-white icsw16-create-write"></i> Simpan Data
                    </button>
                    <?php echo @$addbutton; ?>
                </div>
            
            
            </div>
		</div>        
        <div class="clearfix"></div>
    </div>
</div>
</form>