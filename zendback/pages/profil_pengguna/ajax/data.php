<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 	= isset($_POST['direction']) 		? $_POST['direction'] 	: "";
	$propinsi 		= isset($_POST['propinsi']) 	? $_POST['propinsi'] 	: "";
	$kota 			= isset($_POST['kota']) 		? $_POST['kota'] 		: "";
	$kecamatan 		= isset($_POST['kecamatan']) 	? $_POST['kecamatan'] 	: "";
}else{
	defined('mainload') or die('Restricted Access');
}
	if((!empty($direction) && $direction == "get_kota") || !empty($propinsi)){
	?>
            <div class="form-group col-md-6">
                <label>Kota</label>
                <select name="kota" id="kota" class="form-control" onchange="get_kecamatan(this)">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' 
								<?php if(!empty($kota) && $kota == $data_kota->ID_LOCATION){?> selected<?php } ?>>
								<?php echo $data_kota->ID_LOCATION; ?> - <?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	?>
	<?php
	if((!empty($direction) && $direction == "get_kecamatan") || !empty($kota)){
	?>
            <div class="form-group col-md-6">
                <label>Kecamatan</label>
                <select name="kecamatan" id="kecamatan" class="form-control" onchange="get_kelurahan(this)">
                    <option value=''>--PILIH KECAMATAN--</option>
                    <?php
                    $query_kecamatan = $db->query("SELECT * FROM system_master_location 
												   WHERE PARENT_ID = '".$kota."' ORDER BY NAME ASC");
                    while($data_kecamatan = $db->fetchNextObject($query_kecamatan)){
                    ?>
                        <option value='<?php echo $data_kecamatan->ID_LOCATION; ?>' 
								<?php if(!empty($kecamatan) && $kecamatan == $data_kecamatan->ID_LOCATION){?> 
                                		selected
								<?php } ?>>
								<?php echo $data_kecamatan->ID_LOCATION; ?> - <?php echo $data_kecamatan->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	?>
	
	<?php
	if((!empty($direction) && $direction == "get_kelurahan") || !empty($kecamatan)){
	?>
            <div class="form-group col-md-6">
                <label>Kelurahan</label>
                <select name="kelurahan" id="kelurahan" class="form-control">
                    <option value=''>--PILIH KELURAHAN--</option>
                    <?php
                    $query_kelurahan = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kecamatan."' ORDER BY NAME ASC");
                    while($data_kelurahan = $db->fetchNextObject($query_kelurahan)){
                    ?>
                        <option value='<?php echo $data_kelurahan->ID_LOCATION; ?>' <?php if(!empty($kelurahan) && $kelurahan == $data_kelurahan->ID_LOCATION){?> selected<?php } ?>>
							<?php echo $data_kelurahan->ID_LOCATION; ?> - <?php echo $data_kelurahan->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	?>