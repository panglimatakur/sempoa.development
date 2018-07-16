<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		$direction 		= isset($_POST['direction']) 	? $_POST['direction'] : "";
		$display 		= isset($_POST['display']) 		? $_POST['display'] : "";
		$id_type_report = isset($_POST['display']) 		? $_POST['id_type_report'] : "";
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');	
}


if(!empty($direction) && ($direction == "periode" || $direction == "show")){
		$periode 		= isset($_POST['periode']) 		? $_POST['periode'] : "";
		if($periode == "harian"){
	?>
		<select name="bln" style="width:110px; margin:0 0 9px 9px">
			<?php
			$t2 = 0;
			while($t2<12){
			$t2++;
			if(strlen($t2) == 1){ $t2='0'.$t2; }
			?>
			<option value='<?php echo $t2; ?>' <?php if((!empty($bln) && $t2 == $bln)){?>selected<?php } ?>>
				<?php echo $dtime->nama_bulan($t2); ?>
			</option>
			<?php 
			} 
			?>
		</select>
		<select name="thn" style="width:90px; margin:0 0 9px 9px">
			<?php
			$t3 = date('Y')-70;
			while($t3<date('Y')){
			$t3++;
			?>
			<option value='<?php echo $t3; ?>' <?php if((!empty($thn) && $t3 == $thn) || (empty($thn) && $t3 == date('Y'))){?>selected<?php } ?>><?php echo $t3; ?></option>
			<?php 
			} 
			?>
		</select>
	<?php }
	
		if($periode == "bulanan"){ ?>
		<select name="thn" style="width:90px; margin:0 0 9px 9px">
			<?php
			$t3 = date('Y')-70;
			while($t3<date('Y')){
			$t3++;
			?>
			<option value='<?php echo $t3; ?>' <?php if((!empty($thn) && $t3 == $thn) || (empty($thn) && $t3 == date('Y'))){?>selected<?php } ?>><?php echo $t3; ?></option>
			<?php 
			} 
			?>
		</select>
	<?php }
	
	
		if($periode == "tahunan"){?>
		<select name="thn" style="width:90px; margin:0 0 9px 9px">
			<?php
			$t3 = date('Y')-70;
			while($t3<date('Y')){
			$t3++;
			?>
			<option value='<?php echo $t3; ?>' <?php if((!empty($thn) && $t3 == $thn) || (empty($thn) && $t3 == date('Y'))){?>selected<?php } ?>><?php echo $t3; ?></option>
			<?php 
			} 
			?>
		</select> 
		&nbsp;S/D 
		<select name="thn2" style="width:90px; margin:0 0 9px 9px">
			<?php
			$t4 = date('Y')-70;
			while($t4<date('Y')){
			$t4++;
			?>
			<option value='<?php echo $t4; ?>' <?php if((!empty($thn2) && $t4 == $thn2) || (empty($thn2) && $t4 == date('Y'))){?>selected<?php } ?>><?php echo $t4; ?></option>
			<?php 
			} 
			?>
		</select>
	<?php }
}
?>

<?php 
if((!empty($display) && $display == "kategori_report") || !empty($id_type_report)){
	$query_kategori_report 	= $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_PRODUCT_TYPE='".$id_type_report."' AND ID_PARENT = '0' AND ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY NAME ASC");
	$num_kategori_report 	= $db->numRows($query_kategori_report);
	if($num_kategori_report > 0){
?>
<div class="form-group form-control">
	<label>Kategori</label>
	<input type="hidden" name="id_kategori" id="id_kategori" class="form-control mousetrap" value="<?php echo @$id_kategori; ?>">
	<ul class="kategori_list">
		<?php
		while($data_kategori = $db->fetchNextObject($query_kategori_report)){
			$class_selected = "";
			if(!empty($id_kategori) && $id_kategori == $data_kategori->ID_PRODUCT_CATEGORY){
				$class_selected = "class='class_selected' style='border:1px solid #F9ECF7;'";	
			}
		?>	
			<li id="cat_<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>" <?php echo @$class_selected; ?>>
				<img src="<?php echo $dirhost; ?>/files/images/icons/bullet_go.png" />
				<a href='javascript:void()' onclick="select_category('<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>')">
					<?php echo $data_kategori->NAME; ?>
				</a>
				<?php echo category_list($data_kategori->ID_PRODUCT_CATEGORY); ?>
			</li>
		<?php } ?>
	</ul>
</div>
<?php 
	}
} 
?>