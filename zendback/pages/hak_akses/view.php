<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
    <div class="ibox float-e-margins">
        <?php 
            if(!empty($msg)){
                switch ($msg){
                    case "1":
                        echo "<br>".msg("Proses Simpan Hak Akses Berhasil","success")."<br>";
                    break;
                    case "2":
                        echo "<br>".msg("Tentukan Tingkat Kantor dan Jabatan User","error")."<br>";
                    break;
                }
            }
        ?>
        <input type="hidden" id='treeview_check_page' value='libraries/treeform' />
        <input type="hidden" id='proses_page' value='<?php echo $ajax_dir."/proses.php"; ?>' />
        <input type="hidden" id='clients_page' value='<?php echo $ajax_dir."/clients.php"; ?>' />
        <input type="hidden" id='modules_page' value='<?php echo $ajax_dir."/modules.php"; ?>' />
        <input type="hidden" id="single" name="single" value="<?php echo @$single; ?>" />
        <?php if(!empty($single)){?>
            <input type="hidden" id="client_id" name="client_id" value="<?php echo @$client_id; ?>" />
        <?php } ?>
        <div class="ibox-title">
            <h5>Tingkat Struktur Merchant</h5>
        </div>
        <div class="ibox-content no-padding-lr">
            <div class="form-group col-md-6">
                <select name="id_client_level" id="id_client_level" 
                        class="form-control validate[required] text-input" >
                    <option value="">--LEVEL CLIENT--</option>
                    <?php while($dt_client_level = $db->fetchNextObject($q_client_level)){?>
                        <option value='<?php echo $dt_client_level->ID_CLIENT_LEVEL; ?>' <?php if(!empty($id_client_level) && $id_client_level == $dt_client_level->ID_CLIENT_LEVEL){?> selected<?php } ?>>
                            <?php echo $dt_client_level->ID_CLIENT_LEVEL; ?> - <?php echo $dt_client_level->NAME; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    
    <div id="div_clients">
        <?php if(!empty($direction) && ($direction == "insert" || $direction == "edit")){ 
			include($ajax_dir."/clients.php");  } 
        ?>
    </div>
    <div class="clearfix"></div>
</form>
