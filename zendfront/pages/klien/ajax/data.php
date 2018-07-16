<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	if(!empty($_REQUEST['form_name'])){ $form_name 		= isset($_REQUEST['form_name']) ? $_REQUEST['form_name'] : ""; }
	if(!empty($_REQUEST['propinsi'])){ $propinsi 		= isset($_REQUEST['propinsi']) 		? $_REQUEST['propinsi'] : ""; }

}else{
	defined('mainload') or die('Restricted Access');
}

	if((!empty($direction) && $direction == "get_city") || !empty($kota)  || 
	   (!empty($direction) && $direction == "search") && !empty($propinsi)){
		
	?>
            <div class="col-md-3">
                <select name="kota" id="kota" class="form-control">
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