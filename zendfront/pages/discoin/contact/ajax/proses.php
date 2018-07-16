<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../../includes/config.php");
	include_once("../../../../../includes/classes.php");
	include_once("../../../../../includes/functions.php");
	include_once("../../../../../includes/declarations.php");
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	$id 		= isset($_REQUEST['id']) 			? $_REQUEST['id'] : "";
	$id_client 	= isset($_REQUEST['id_client']) 	? $_REQUEST['id_client'] 	: "";	
	$email_letter 	= isset($_REQUEST['email_letter']) 	? $sanitize->email($_REQUEST['email_letter']) : "";
	
	if((!empty($direction) && $direction == "send_newsletter")){
		if(!empty($email_letter) && $validate->email($email_letter)){
			$ch_email = $db->recount("SELECT EMAIL_NEWSLETTER FROM ".$tpref."newsletters WHERE EMAIL_NEWSLETTER = '".$email_letter."' AND ID_NEWSLETTER_SOURCE = '2'");
			if($ch_email == 0){
				$encrypted_email = md5($email_letter);
				$content = array(1=>array("ID_CLIENT",$id_client),
									array("EMAIL_NEWSLETTER",$email_letter),
									array("ENCRYPTED_EMAIL",$encrypted_email),
									array("ID_NEWSLETTER_SOURCE","2"),
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

	if(!empty($direction) && $direction == "send"){
		$nama 		= isset($_REQUEST['nama']) 			? $_REQUEST['nama'] : "";
		$email 		= isset($_REQUEST['email']) 			? $_REQUEST['email'] : "";
		$pesan 		= isset($_REQUEST['pesan']) 			? $_REQUEST['pesan'] : "";
		
		$merchant_coin	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_client."'");
		
		$chat			= array(1=>
							array("ID_CLIENT",$id_client),
							array("CONTACT_NAME",$nama),
							array("CONTACT_EMAIL",$email),
							array("CONTACT_MSG",$pesan),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate)
						  );
		$db->insert($tpref."contact",$chat);
		if($validate->email($email) == 1){
			$to_email		= "thetakur@gmail.com";
			$subject 		= "Pertanyaan Dari Hallama Sempoa DisCOIN ";
			$from			= $nama." <".$email.">";
			$type			= "html";
			$msg 			= "
			<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
				Pertanyaan dari ".@$nama.",<br><br>
				".$pesan."
			</div>	";		
			sendmail(trim($to_email),$subject,$msg,$from,$type);
		}
	}
	
}
?>