<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	if(empty($direction)){ 
		$id_product = isset($_REQUEST['id_product'])	? $sanitize->str($_REQUEST['id_product']) 	:"";
		$kecamatan 	= isset($_REQUEST['kecamatan'])		? $sanitize->number($_REQUEST['kecamatan']) 	:"";
	}
?>
    <select name="kelurahan_<?php echo $id_product; ?>" id="kelurahan_<?php echo $id_product; ?>">
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
<?php
}

?>