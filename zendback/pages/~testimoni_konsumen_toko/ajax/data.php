<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	if(!empty($_REQUEST['form_name'])){ $form_name 		= isset($_REQUEST['form_name']) ? $_REQUEST['form_name'] : ""; }
}else{
	defined('mainload') or die('Restricted Access');
}
	
	if((!empty($direction) && $direction == "show_form")){
		$id_customer = isset($_REQUEST['id_customer']) ? $_REQUEST['id_customer'] : "";	
		
		$q_member			= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_CUSTOMER = '".$id_customer."'");
		@$dt_member 	 		= $db->fetchNextObject($q_member);
		
		@$result['nama']		= $dt_member->CUSTOMER_NAME;
		@$result['user_id'] 	= "<input type='hidden' name='id_customer' value='".$dt_member->ID_CUSTOMER."'>";
		if(is_file($basepath."/files/images/members/".$dt_member->CUSTOMER_PHOTO)){
			$photo = "
			<div class='thumbnail' class='col-md-4'>
				<img src='".$dirhost."/files/images/members/big/".$dt_member->CUSTOMER_PHOTO."' style='width:100%'>
			</div>";	
		}else{
			$photo = "
			<div class='thumbnail' >
				<img src='".$dirhost."/files/images/members/big/".$dt_member->CUSTOMER_PHOTO."' style='width:100%'>
			</div>";	
		}
		@$result['foto']		= $photo;
		echo json_encode($result);
	}
?>