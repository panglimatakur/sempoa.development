<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
		if(!empty($_POST['form_name'])){ $form_name 		= isset($_POST['form_name']) ? $_POST['form_name'] : ""; }
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');
}

	if((!empty($direction) && $direction == "get_city") || !empty($kota)){
		if(!empty($_POST['propinsi'])){ $propinsi 		= isset($_POST['propinsi']) 		? $_POST['propinsi'] : ""; }
	?>
            <div class="form-group">
                <label class="req">Kota</label>
                <select name="kota" id="kota" class="form-control validate[required] text-input">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' <?php if(!empty($kota) && $kota == $data_kota->ID_LOCATION){?> selected<?php } ?>>
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	?>