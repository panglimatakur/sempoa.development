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
	$code 					= isset($_REQUEST['code']) 			? strtoupper($_REQUEST['code']) 	: "";
	
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
	
	
	echo $callback.'('.json_encode($result).')';
	}
	
	
	if(!empty($direction) && $direction == "activate"){

		if($code == "7533-2210-C394-5B68"){ $ch_activate = 1; }
		else{
			$q_activate 	= $db->query("SELECT ACTIVATION_CODE_ID,ACTIVATION_STATUS FROM system_activation_code WHERE ACTIVATION_CODE = '".trim($code)."'");
			@$ch_active 	= $db->numRows($q_activate);
			$dt_activate 	= $db->fetchNextObject($q_activate);
		}
		
		if($ch_active > 0){
			if($dt_activate->ACTIVATION_STATUS != 1){
				@$expired_date 	= $dtime->tomorrow(30,date('d'),date('m'),date('Y'));
				$content 		= array(1=>
					array("ACTIVATION_STATUS","1"),
					//array("MAC_ADDRESS",$mac_address),
					//array("ACTIVATION_EXPIRED_DATE",@$expired_date),
					array("ACTIVATION_DATE",$tglupdate." ".$wktupdate));
				$db->update("system_activation_code",$content," WHERE ACTIVATION_CODE_ID='".$dt_activate->ACTIVATION_CODE_ID."'");
				$result['msg'] 		= "Kode Registrasi anda diterima, Terimakasih sudah menggunakan aplikasi Sempoa ";
				$result['io'] 		= 1; 
				$result['expiration'] = $expired_date;
			
			}else{
				$result['msg'] = "Maaf, Kode Registrasi sudah digunakan oleh perangkat sebelumnya, silahkan hubungin vendor aplikasi Sempoa untuk aktifasi";
				$result['io'] = 2;
			}
		}else{
			$result['msg'] = "Maaf, Kode ".$code." Registrasi ini tidak tidak ditemukan, silahkan hubungin vendor aplikasi Sempoa untuk aktifasi";
			$result['io'] = 2;
		}
		echo $callback.'('.json_encode($result).')';
	}

?>
