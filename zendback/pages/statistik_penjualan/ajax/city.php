<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		$propinsi 	= isset($_REQUEST['propinsi'])	? $sanitize->number($_REQUEST['propinsi']) 		:"";
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');	
}
?>
	<select name="kota" id="kota" onchange="open_location('district')">
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
