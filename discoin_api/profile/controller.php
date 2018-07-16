<?php
ini_set('session.gc_maxlifetime', 30*60);
session_start();
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
if(!defined('mainload')) { define('mainload','SEMPOA',true); }

	include_once("../../includes/config.php");
	include_once("../../includes/classes.php");
	include_once("../../includes/functions.php");
	include_once("../../includes/declarations.php");

	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$id_merchant 	= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] 	: "";
	$id_customer	= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 		: "";

	$lastID 		= isset($_REQUEST['lastID']) 		? $_REQUEST['lastID'] 		: "";
	$callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";

	$result['msg_log'] 	= "";
	$result['io_log']  	= "";
	
	if(empty($_SESSION['sidkey'])){
		$data 			 = relogin($id_merchant,$id_customer);
		$result['io_log'] 	= $data['io_log'];
		$result['msg_log']  = $data['msg_log'];
	}

	if(!empty($id_customer)){
		if(!empty($direction) && $direction == "save"){
		$nama 			= isset($_REQUEST['nama']) 			? $sanitize->str(ucwords($_REQUEST['nama']))		: "";				
		$ori_user_name	= isset($_REQUEST['ori_user_name']) ? $sanitize->email($_REQUEST['ori_user_name'])		: "";				
		$email 			= isset($_REQUEST['email']) 		? $sanitize->email(strtolower($_REQUEST['email']))	: ""; 		
		$user_pass		= isset($_REQUEST['user_pass']) 	? $sanitize->str($_REQUEST['user_pass'])			: "";				
		$alamat 		= isset($_REQUEST['alamat']) 		? $sanitize->str(ucwords($_REQUEST['alamat']))		: "";			
		$tlp 			= isset($_REQUEST['tlp']) 			?  $sanitize->str($_REQUEST['tlp'])					: "";						
		$sex 			= isset($_REQUEST['sex']) 			?  $sanitize->str($_REQUEST['sex'])					: "";						
		
		$num_user = $db->recount("SELECT CUSTOMER_USERNAME FROM ".$tpref."customers WHERE CUSTOMER_EMAIL = '".$email."' AND ID_CLIENT='".$id_merchant."'"); 
			if(!empty($email) && !empty($nama) && !empty($tlp)){
				$done	= "";
				if($num_user == 0 || ($num_user > 0 && $email == $ori_user_name)){ $done = 1; }
				if($num_user > 0 && $email != $ori_user_name){ $done = 2; }
				
				if($done == 1){
						$q_user 	= $db->query("SELECT CUSTOMER_PHOTO,CUSTOMER_USERNAME,CUSTOMER_PASS FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."' AND ID_CLIENT='".$id_merchant."'"); 
						$dt_user	= $db->fetchNextObject($q_user);
						
						if(empty($user_pass)){ @$user_pass 	= $dt_user->CUSTOMER_PASS; }
						if(empty($email))	 { @$email 		= $dt_user->CUSTOMER_EMAIL; }
						
						$container = array(1=>
							array("CUSTOMER_NAME",@$nama),
							array("CUSTOMER_PASS",@$user_pass),
							array("CUSTOMER_SEX",@$sex),
							array("CUSTOMER_EMAIL",@$email),
							array("CUSTOMER_PHONE",@$tlp),
							array("CUSTOMER_ADDRESS",@$alamat));
						$db->update($tpref."customers",$container," WHERE ID_CUSTOMER = '".$id_customer."' AND ID_CLIENT='".$id_merchant."'");
						$result['cust_name'] 	= $nama;
						$result['cust_email'] 	= $email;
						$result['cust_sex'] 	= $sex;
						$result['cust_phone'] 	= $tlp;
						$result['cust_add'] 	= $alamat;
						$result["io"]	= "2";
						$result['msg'] 	= "Profil Anda Berhasil Disimpan";					
					
				}else{
					$result["io"]	= "1";
					$result['msg'] 	= " Maaf, Akun Username ini sudah terdaftar";
				}
			}else{
				$result["io"]	= "1";
				$result['msg'] 	= "Pengisian Form Belum Lengkap";
			}
			echo $callback.'('.json_encode($result).')';
		}
	}
}
