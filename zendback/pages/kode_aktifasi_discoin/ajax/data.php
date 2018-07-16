<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		
		
		$direction			= isset($_REQUEST['direction']) 	? $sanitize->str($_REQUEST['direction']) 			: "";
		$id_client_form		= isset($_REQUEST['id_client_form'])? $sanitize->number($_REQUEST['id_client_form']) 	: "";
		$status 			= isset($_REQUEST['status']) 		? $sanitize->number($_REQUEST['status'])			: "";
		$label_no			= isset($_REQUEST['label_no']) 		? $sanitize->number($_REQUEST['label_no'])			: "";
		$lastID 			= isset($_REQUEST['lastID']) 		? $sanitize->number($_REQUEST['lastID'])			: "";
		$condition			= "";
		
		if(!empty($direction) && $direction == "get_merchant"){
			$q_merchant 		= $db->query("SELECT ID_CLIENT,CLIENT_NAME FROM ".$tpref."clients 
											  WHERE ID_CLIENT IS NOT NULL 
											  ORDER BY CLIENT_NAME");
	?>
			<select name="q_merchant" class="q_merchant form-control mousetrap" 
					data-target = 'id_merchant'>
				<option value=''>--PILIH MERCHANT--</option>
				<?php while($dt_merchant = $db->fetchNextObject($q_merchant)){?>
					<option value='<?php echo $dt_merchant->ID_CLIENT; ?>'><?php echo $dt_merchant->CLIENT_NAME; ?></option>
				<?php } ?>
			</select>
			<input type="hidden" id="id_merchant" value="<?php echo @$id_merchant; ?>" />
			<div class="clearfix"></div>
	<?php
		}
		
		if(!empty($direction) && $direction == "list_report"){
			//FILTER CONDITION
			if($_SESSION['admin_only'] == "false"){
				$condition .= " AND ACTIVATE_BY_ID_CLIENT  = '".$_SESSION['cidkey']."'";	  
			}else{
				if(!empty($id_client_form))	{ 
					$condition .= " AND ACTIVATE_BY_ID_CLIENT = '".$id_client_form."'"; 	
				}else{
					$condition .= " AND ACTIVATE_BY_ID_CLIENT IS NULL"; 	
				}
			}
			if(!empty($status))			{ $condition .= " AND ACTIVATE_STATUS  = '".$status."'"; 				}
			//END OF FILTER CONDITION
			
			$str_coin 	 	  = "SELECT * FROM ".$tpref."discoin_activation_codes 
								 WHERE ID_DISCOIN_ACTIVATION_CODE IS NOT NULL ".$condition." 
								 AND ID_DISCOIN_ACTIVATION_CODE < '".$lastID."' 
								 ORDER BY ID_DISCOIN_ACTIVATION_CODE DESC";
								 
			$q_coin 		  = $db->query($str_coin." LIMIT 0,100");		
			$num_coin			= $db->recount($str_coin);
			$t = $label_no; 
			while($dt_coin	= $db->fetchNextObject($q_coin)){ $t++; 
				$nm_merchant	 = "";
				$nm_customer	 = "";
				$lastID 		 = $dt_coin->ID_DISCOIN_ACTIVATION_CODE;
				if(!empty($dt_coin->ACTIVATE_BY_ID_CLIENT)){
					$nm_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," 
											 WHERE ID_CLIENT='".$dt_coin->ACTIVATE_BY_ID_CLIENT."'");
				}else{ $nm_merchant = "<label class='label label-info'>Belum diaktifkan merchant</label>"; }
				
				if(!empty($dt_coin->ACTIVATE_BY_ID_CUSTOMER)){
					$nm_customer = $db->fob("CUSTOMER_NAME",$tpref."customers"," 
											 WHERE ID_CUSTOMER = '".$dt_coin->ACTIVATE_BY_ID_CUSTOMER."' AND 
												   ID_CLIENT='".$dt_coin->ACTIVATE_BY_ID_CLIENT."'");
				}else{ $nm_customer = "<label class='label label-warning'>Belum diaktifkan pelanggan</label>"; }
				$tgl_aktif = $dt_coin->ACTIVATE_DATETIME;
				if(empty($dt_coin->ACTIVATE_DATETIME) || $dt_coin->ACTIVATE_DATETIME == "0000-00-00 00:00:00"){ 
					$tgl_aktif = "<label class='label label-success'>None</label>"; 
				}
				switch($dt_coin->ACTIVATE_STATUS){ 
					case "3":
						$status_coin = "AKTIF"; 
						$class_label = "label-success";
					break;
					default:
						$status_coin = "NON AKTIF";
						$class_label = "label-danger";
					break;
				}
		  ?>
			  <tr class='wrdLatest' data-info='<?php echo @$lastID; ?>'>
				<td class="text-center align-top">
					<input type="checkbox" class="row_sel" value="<?php echo @$lastID; ?>"/>
				</td>
				<td class="text-center align-top label_no"><?php echo $t; ?></td>
				<td class="align-top text-center">
					<b class='code'>
						<?php echo strtoupper(@$dt_coin->ACTIVATION_CODE); ?>
					</b>
				</td>
				<td class="align-top">
					<?php echo @$nm_merchant; ?>
					<br><br>
					<?php echo @$nm_customer; ?>
				</td>
				<td class="text-center align-top">
					<label class="label <?php echo $class_label; ?>">
						<?php echo @$status_coin; ?>
					</label>
				</td>
				<td class="text-center align-top">
					<b class='code'><?php echo $dtime->now2indodate2(@$dt_coin->UPDATEDATE); ?></b>
				</td>
				<td class="text-center align-top">
					<b class='code'><?php echo @$tgl_aktif; ?></b>
				</td>
			</tr>
		<?php } ?>    
	<?php 	
		} 
		
		
		
		
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
?>