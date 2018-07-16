<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	$id 		= isset($_REQUEST['id']) 			? $_REQUEST['id'] : "";
	$id_client 	= isset($_REQUEST['id_client']) 	? $_REQUEST['id_client'] 	: "";	
	if((!empty($direction) && $direction == "send_chat")){
		$sessichat 	= isset($_REQUEST['sessichat']) 	? $_REQUEST['sessichat'] 	: "";	
		$username 	= isset($_REQUEST['username']) 		? $_REQUEST['username'] 	: "";	
		$pesan 		= isset($_REQUEST['pesan']) 		? $_REQUEST['pesan'] 		: "";	
		
		$chat			= array(1=>
							array("ID_CLIENT",$id_client),
							array("SENDER",$username),
							array("SESSION",$sessichat),
							array("MESSAGE",$pesan),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate)
						  );
		$db->insert($tpref."chat_visitors",$chat);
		$id_chat		= mysql_insert_id();
		$wkt_chat		= substr($wktupdate,0,5);
		$tgl_chat		= $dtime->date2indodate($tglupdate);
		$result['content'] = 
		"<div class='ch-message-item clearfix' id='chat_".$id_chat."'>
			<div class='img-box'><img src=".$dirhost."/files/images/noimage-m.jpg' class='ch-image img-avatar'/></div>
			<div class='ch-content'>
				<p class='ch-name'>
					<strong>".$username."</strong>
					<span class='ch-time'>".$tgl_chat." : ".$wkt_chat."</span>
				</p>
				".$pesan."
                <br />
                <button class='btn btn-mini removal ptip_sw' onclick='remove_chat(\"chat\",\"".$sessichat."\")' title='Hapus Pesan'>
                    <i class='icon-trash'></i>
                </button>
			</div>
		</div>";
		$result['id_chat'] = $id_chat;
		echo json_encode($result);
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
			$subject 		= "Pertanyaan DisCOIN ".$merchant_coin;
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
	
	if((!empty($direction) && $direction == "save_scroll")){
		$ip_address 		= $_SERVER['REMOTE_ADDR'];
		$user_agent     	= $_SERVER['HTTP_USER_AGENT'];
		cuser_log("customer","0","Scrolling FROM ".$user_os,@$id_client); 
	}
	if((!empty($direction) && $direction == "save_close")){
		$ip_address 		= $_SERVER['REMOTE_ADDR'];
		$user_agent     	= $_SERVER['HTTP_USER_AGENT'];
		cuser_log("customer","0","Closing Autorespon ".$user_os,@$id_client); 
	}
}
?>