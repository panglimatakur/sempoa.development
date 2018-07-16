<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		$kecamatan 	= isset($_REQUEST['kecamatan'])		? $sanitize->number($_REQUEST['kecamatan']) 	:"";
	}else{
		defined('mainload') or die('Restricted Access');
	}
	
}else{
	defined('mainload') or die('Restricted Access');	
}
?>
<select name="kelurahan" id="kelurahan">
    <option value=''>--PILIH KELURAHAN--</option>
    <?php
    $query_kelurahan = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kecamatan."' ORDER BY NAME ASC");
    while($data_kelurahan = $db->fetchNextObject($query_kelurahan)){
    ?>
        <option value='<?php echo $data_kelurahan->ID_LOCATION; ?>' <?php if(!empty($kelurahan) && $kelurahan == $data_kelurahan->ID_LOCATION){?> selected<?php } ?>>
            <?php echo $data_kelurahan->NAME; ?>
        </option>
<?php } ?>
</select>
