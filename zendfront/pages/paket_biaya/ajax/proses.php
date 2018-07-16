<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../includes/config.php");
	include_once("../../../includes/classes.php");
	include_once("../../../includes/functions.php");
	include_once("../../../includes/declarations.php");
	$direction = isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : ""; 
	$ct_email 	= isset($_REQUEST['ct_email']) 	? $_REQUEST['ct_email'] : ""; 
	if((!empty($direction) && $direction == "demo")){

		if($validate->email($ct_email) == 1){
			$from			= "Sempoa Notification <no-reply@sempoa.biz>";
			$subject 		= "Informasi Akun Demo Sempoa";
			$to 		 	= $ct_email;
			$type			= "html";
			$msg 			= "
			<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
				Dear Visitor,<br><br>
				Dibawah ini adalah, akun sempoa sementara, untuk demo sistem website sempoa.biz
				<br><br>
				Username : demosempoa
				<br>
				Passwors : 123
				<br>
				<br>
				Silahkan login di link ini <a href='".$dirhost."/website/login' target='_blank'>Login</a>, dan masukan informasi akun demo diatas.
				<br>
				<br>
				Terimakasih<br><br>
				<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
				<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
			</div>	";		
			sendmail($to,$subject,$msg,$from,$type);
			cuser_log("customer","0",$ct_email." Meminta Info Demo - Dari ".$user_os,"1");
			echo "1";
		}else{
			echo "2";	
		}
	}	
}else{  
	defined('mainload') or die('Restricted Access'); 
}
?>