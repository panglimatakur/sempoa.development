<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		$kota 		= isset($_REQUEST['kota'])		? $sanitize->number($_REQUEST['kota']) 			:"";
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');	
}
?>
<select name="kecamatan" id="kecamatan" onchange="open_location('subdistrict')">
    <option value=''>--PILIH KECAMATAN--</option>
    <?php
    $query_kecamatan = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kota."' ORDER BY NAME ASC");
    while($data_kecamatan = $db->fetchNextObject($query_kecamatan)){
    ?>
        <option value='<?php echo $data_kecamatan->ID_LOCATION; ?>' <?php if(!empty($kecamatan) && $kecamatan == $data_kecamatan->ID_LOCATION){?> selected<?php } ?>>
            <?php echo $data_kecamatan->NAME; ?>
        </option>
<?php } ?>
</select>
