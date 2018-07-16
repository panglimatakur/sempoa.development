<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
<div class="row-fluid">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Komunitas Yang Terdaftar</h4>
        </div>
		<?php 
            if(!empty($msg)){
                switch ($msg){
                    case "1":
                        echo msg("Data Link Berhasil Disimpan","success");
                    break;
                    case "2":
                        echo msg("Pengisian Form Belum Lengkap","error");
                    break;
                }
            }
        ?>
        <div class="ibox-content">
            <div class="form-group">
              <label>Nama Komunitas</label>
                <select name="id_com" id="id_com" class="form-control">
                    <option value=''>--PILIH KOMUNITAS--</option>
                    <?php
                    while($data_com = $db->fetchNextObject($query_com)){
                    ?>
                        <option value='<?php echo $data_com->ID_COMMUNITY; ?>' <?php if(!empty($id_com) && $id_com == $data_com->ID_COMMUNITY){?> selected<?php } ?>><?php echo $data_com->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="show" class="btn btn-sempoa-1" value="insert">Lihat Komunitas</button>
                
            </div>
        </div>
    </div>
</div>
<br />
<div class="row-fluid">
	<?php include $call->inc($inc_dir,"list.php"); ?>
    <div id="lastPostsLoader"></div>
    <input type="hidden" id="proses_page" value="<?php echo $ajax_dir; ?>/proses.php">
    <input type="hidden" id="data_page"   value="<?php echo $ajax_dir; ?>/data.php" />
    <br style="clear:both" />
   <div class="ibox float-e-margins">     
        <div class="ibox-title" style="text-align:center">
            <?php if($num_community > 3){?>
                <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
            <?php } ?>
            <br clear="all" />
        </div>            
    </div> 
</div>
</form>