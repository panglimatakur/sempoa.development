<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: ""; 
	$email_letter 	= isset($_REQUEST['email_letter']) 	? $sanitize->email($_REQUEST['email_letter']) : "";
	
	if((!empty($direction) && $direction == "send_newsletter")){
		if(!empty($email_letter) && $validate->email($email_letter)){
			$ch_email = $db->recount("SELECT EMAIL_NEWSLETTER FROM ".$tpref."newsletters WHERE EMAIL_NEWSLETTER = '".$email_letter."' AND ID_NEWSLETTER_SOURCE = '1'");
			if($ch_email == 0){
				$encrypted_email = md5($email_letter);
				$content = array(1=>array("ID_CLIENT","1"),
									array("EMAIL_NEWSLETTER",$email_letter),
									array("ENCRYPTED_EMAIL",$encrypted_email),
									array("ID_NEWSLETTER_SOURCE","1"),
									array("SUBSCRIBE_STATUS","2"), 
									array("TGLUPDATE",$tglupdate),
									array("WKTUPDATE",$wktupdate));
				$db->insert($tpref."newsletters",$content);
			}
			echo "Terimakasih telah mendaftarkan email anda";
		}else{
			echo "Alamat Email tidak valid, silahkan gunakan alamat email yang lain";
		}
	}
	
}else{  
	defined('mainload') or die('Restricted Access'); 
}
?>