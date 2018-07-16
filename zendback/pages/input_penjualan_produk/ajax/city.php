<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	if(empty($direction)){
		$id_product = isset($_REQUEST['id_product'])? $sanitize->str($_REQUEST['id_product']) 	:"";
		$propinsi 	= isset($_REQUEST['propinsi'])	? $sanitize->number($_REQUEST['propinsi']) 		:"";
	}
?>
	<select name="kota_<?php echo $id_product; ?>" id="kota_<?php echo $id_product; ?>" onchange="open_location('district','<?php echo $id_product; ?>')">
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
<?php
}

?>