<?php defined('mainload') or die('Restricted Access'); ?>
<?php 
	$user_email = isset($_REQUEST['user_email']) ? $sanitize->email(strtolower($_REQUEST['user_email'])):"";


	if(!empty($direction) && $direction == "forgot_password"){
		
		if(!empty($user_email)){
			$query_login 			= $db->query("SELECT USER_EMAIL,USER_NAME FROM system_users_client WHERE USER_EMAIL = '".$user_email."'");
			$num_login				= $db->numRows($query_login);
			if($num_login > 0){
				$dt_login 		 = $db->fetchNextObject($query_login);
				$nama			 = $dt_login->USER_NAME;
				$to 			 = $dt_login->USER_EMAIL;
				$sender			 = "info@".$website_name;
				$new_password 	 = substr(md5($nama.$to),0,6);
				
				$db->query("UPDATE system_users_client SET USER_PASS = '".$new_password."' WHERE USER_EMAIL = '".$user_email."'");
				$subject 		= "[PENTING]PENGEMBALIAN PASSWORD AKUN Sempoa ".$nama;
				$headers 		= "From: " . strip_tags($sender) . "\r\n";
				$headers 		.= "MIME-Version: 1.0\r\n";
				$headers 		.= "Content-Type: text/html; charset=ISO-8859-1\r\n";			
				
				$msg 			= "
				".$nama." baru saja melakukan pengembalian password <br> <br> 
				
				
				<div class=''>".$new_password."</div>
				<br>
				<br>	
				";
				//mail($to,$subject,$msg,$headers);	
			}
		}
	}
?>
