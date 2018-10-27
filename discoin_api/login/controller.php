<?php
	session_start();
	if(!defined('mainload')) { define('mainload','ALIBABA',true); }
	include_once("../../includes/config.php");
	include_once("../../includes/classes.php");
	include_once("../../includes/functions.php");

	$callback 				= 'mycallback';
	$callback 				= isset($_REQUEST['mycallback'])	? $_REQUEST['mycallback'] 				: "";
	$direction 				= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 				: "";
	$username 				= isset($_REQUEST['username']) 		? $_REQUEST['username'] 				: "";
	$password 				= isset($_REQUEST['password']) 		? $_REQUEST['password'] 				: "";

	if(!empty($direction) && $direction=="logout"){
		session_destroy();
		$result['page']				= "index.html";
		echo $callback.'('.json_encode($result).')';
	}
		
	if(!empty($direction) && $direction == "login"){
		if(!empty($_REQUEST['username']))	{ $username = $sanitize->str($_REQUEST['username']); 	}
		if(!empty($_REQUEST['password']))	{ $password = $sanitize->str($_REQUEST['password']); 	}
		if(!empty($username) && !empty($password)){
			$query_login 					= $db->query("SELECT * FROM system_users_client WHERE USER_EMAIL = '".$username."' AND USER_PASS ='".$password."'");
			$num_login						= $db->numRows($query_login);

			if($num_login > 0){
				$data_login 				= $db->fetchNextObject($query_login);
				$_SESSION['uidkey']			= $data_login->ID_USER;
				$_SESSION['username']		= $data_login->USER_NAME;
				$_SESSION['ulevelkey']		= $data_login->ID_USER_LEVEL;
				$levelname				 	= $db->fob("NAME","system_users_level_master"," WHERE ID_USER_LEVEL ='".$data_login->ID_USER_LEVEL."'");
				$_SESSION['levelname']		= $levelname;
				$_SESSION['uemail']			= $data_login->USER_EMAIL;

				//END OF CHECK CHILDREN//
				$result['uidkey']			= $data_login->ID_USER;
				$result['username']			= $data_login->USER_NAME;
				$result['ulevelkey']		= $data_login->ID_USER_LEVEL;
				$result['levelname']		= $levelname;
				$result['uemail']			= $data_login->USER_EMAIL;
				$result['upassword']		= $data_login->USER_PASS;
				
				$result['page']				= "profile";
				$result['io']				= "1";
			}else{
				$result['io']				= "2";
				$result['msg']				= "Maaf, akun ini tidak terdaftar, silahkan hubungin admin";
			}
		}else{
			$result['io']					= "3";
			$result['msg']					= "Maaf, pengisian form belum lengkap";
		}
	}
	echo $callback.'('.json_encode($result).')';
	
	if(!empty($direction) && $direction == "activate"){

		$q_activate 	= $db->query("SELECT ACTIVATION_CODE_ID FROM system_activation_code WHERE ACTIVATION_CODE = '".trim($code)."' AND STATUS = '0' ");
		@$ch_active 	= $db->numRows($q_activate);
		$dt_activate 	= $db->fetchNextObject($q_activate);
		
		$done = 0;
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
		}
		echo $callback.'('.json_encode($result).')';
	}

?>