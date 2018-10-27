<?php
	session_start();
	if(!defined('mainload')) { define('mainload','ALIBABA',true); }
	include("../../includes/config.php");
	include("../../includes/classes.php");
	include("../../includes/functions.php");

	$callback 				= 'mycallback';
	$callback 				= isset($_REQUEST['mycallback'])	? $_REQUEST['mycallback'] 			: "";
	$direction 				= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 			: "";
	$username 				= isset($_REQUEST['username']) 		? $_REQUEST['username'] 			: "";
	$password 				= isset($_REQUEST['password']) 		? $_REQUEST['password'] 			: "";

	$code 					= isset($_REQUEST['code']) 			? $_REQUEST['code'] 				: "";
	
	//if(!empty($direction) && $direction == "activate"){

		/*$q_activate 	= $db->query("SELECT ACTIVATION_CODE_ID FROM system_activation_code WHERE ACTIVATION_CODE = '".trim($code)."' AND STATUS = '0' ");
		@$ch_active 	= $db->numRows($q_activate);
		$dt_activate 	= $db->fetchNextObject($q_activate);*/
		$result['msg'] = "Maaf, Kode Registrasi ini tidak berlaku atau sudah berakhir, silahkan hubungin vendor aplikasi Sempoa untuk aktifasi";
		/*$done = 0;
		if($ch_active == 0){
			
			@$expired_date 	= $dtime->tomorrow(365,date('d'),date('m'),date('Y'));
			$content 		= array(1=>
				array("ACTIVATION_STATUS","1"),
				//array("MAC_ADDRESS",$mac_address),
				array("ACTIVATION_DATE",$tglupdate." ".$wktupdate),
				array("ACTIVATION_EXPIRED_DATE",@$expired_date));
			$db->update("system_activation_code",$content," WHERE APPLICATION_ID = '".$apps_id."' AND ACTIVATION_CODE='".$code."'");
			$result['msg'] 		= "Kode Registrasi anda diterima, Terimakasih sudah menggunakan aplikasi Sempoa ";
			$result['io'] 		= 1; 
			
		}else{
			$result['msg'] = "Maaf, Kode Registrasi ini tidak berlaku atau sudah berakhir, silahkan hubungin vendor aplikasi Sempoa untuk aktifasi";
			$result['io'] = 2;
		}*/
		echo $callback.'('.json_encode($result).')';
	//}

?>
