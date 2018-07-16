<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction 		= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$display 		= isset($_POST['display']) 		? $_POST['display'] : "";
	$id_type_report = isset($_POST['display']) 		? $_POST['id_type_report'] : "";
}else{
	defined('mainload') or die('Restricted Access');
}

if(!empty($direction) && ($direction == "periode" || $direction == "show")){
		$periode 		= isset($_POST['periode']) 		? $_POST['periode'] : "";
    	if($periode == "harian"){
	?>
    	<select name="bln" style="width:110px; margin:5px 0 9px 9px">
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
    	<select name="thn" style="width:90px; margin:5px 0 9px 9px">
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
    	<select name="thn" style="width:90px; margin:5px 0 9px 9px">
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
    	<select name="thn" style="width:90px; margin:5px 0 9px 9px">
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
		<select name="thn2" style="width:90px; margin:5px 0 9px 9px">
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

