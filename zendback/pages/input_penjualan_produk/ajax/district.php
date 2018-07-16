<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	if(empty($direction)){ 
		$id_product = isset($_REQUEST['id_product'])? $sanitize->str($_REQUEST['id_product']) 	:"";
		$kota 		= isset($_REQUEST['kota'])		? $sanitize->number($_REQUEST['kota']) 			:"";
	}
?>
    <select name="kecamatan_<?php echo $id_product; ?>" id="kecamatan_<?php echo $id_product; ?>" onchange="open_location('subdistrict','<?php echo $id_product; ?>')">
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
<?php
}

?>