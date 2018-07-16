<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");

	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	include $call->clas("class.gcm");
	
	$pesan 		= isset($_REQUEST['pesan']) ? $_REQUEST['pesan'] : "";
	$chat		= array(1=>
				  	array("ID_CLIENT",$_SESSION['cidkey']),
					array("CHAT_SUBJECT",$pesan),
					array("CHAT_SRC","CUSTOMER"),
					array("CHAT_SECRECY","public"),
					array("BY_ID_USER",$_SESSION['uidkey']),
					array("ORDER_DTIME",$tglupdate." ".$wktupdate),
					array("TGLUPDATE",$tglupdate),
					array("WKTUPDATE",$wktupdate)
				  );
	$db->insert($tpref."chat_subject",$chat);
	$id_topic 		= mysql_insert_id();
	
	$from			= "Info ".@$_SESSION['cname']." <info@".$website_name.">";
	$subject 		= "Pesan dari ".@$_SESSION['cname'];
	$type			= "html";
	
	$q_client 		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT = '".$_SESSION['cidkey']."' ");
	$dt_client  	= $db->fetchNextObject($q_client);
	@$foto			= $dt_client->CLIENT_LOGO;
	@$playstore 	= $dt_client->PLAY_STORE;
	@$app_url 		= "<a href='".$dirhost."/".$dt_client->CLIENT_APP.".coin' targte='_blank' style=font-family:tahoma;font-size:11px; color:#FF0000'>".$dirhost."/".$dt_client->CLIENT_APP.".coin</a><br>";
	if(is_file($basepath."/files/images/logos/".$foto))	{
		$img = $dirhost."/files/images/logos/".$foto;   }
	else{ 
		$img = $dirhost."/files/images/no_image.jpg"; 	}
	if(!empty($playstore) && $playstore == '1'){
		$playstore_img = "
		<br> atau <br> <br> 
		<a href='http://play.google.com/store/apps/details?id=com.hepifarm.coin'>
			<img src='".$dirhost."/files/images/playStore.png' style='width:20%'>
		</a>
		<br>
		<br>";
	}
	$q_customer 	= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ( CUSTOMER_EMAIL IS NOT NULL || CUSTOMER_EMAIL != '') ");
	$result['content'] = "";
	$registatoin_ids 	= array();
	while($dt_customer = $db->fetchNextObject($q_customer)){
		@$nama_customer 	= $dt_customer->CUSTOMER_NAME; 
		@$email_customer	= $dt_customer->CUSTOMER_EMAIL;  
		@$regId		 		= $dt_customer->CUSTOMER_REG_ID;
		array_push($registatoin_ids,$regId);
		
		if(!empty($dt_customer->CUSTOMER_SEX) && $dt_customer->CUSTOMER_SEX == "L"){ $say = "Om"; 		}
		if(!empty($dt_customer->CUSTOMER_SEX) && $dt_customer->CUSTOMER_SEX == "P"){ $say = "Tante"; 	}
		//$result['content'] .= $regId." - ".$dt_customer->CUSTOMER_NAME."<br>";
		if(!empty($dt_customer->CUSTOMER_REG_ID)){
			$chat			= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CHAT_SUBJECT",$id_topic),
								array("ID_CUSTOMER",$dt_customer->ID_CUSTOMER),
								array("ID_USER",$_SESSION['uidkey']),
								array("CHAT_MESSAGE",$pesan),
								array("TGLUPDATE",$tglupdate),
								array("WKTUPDATE",$wktupdate)
							);
			$db->insert($tpref."chat",$chat);	
		}
		$msg 				= "
			Hai ".@$say." ".@$nama_customer.",<br><br>
			
			<div style='border: 1px solid #EAEAEA;border-radius: 3px; -moz-border-radius: 3px;-webkit-border-radius: 3px;padding: 10px;margin:6px;background: #DFDFDF;-webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);'>
				<img src='".$img."' style='float:left; width:20%; '>
				<div style='float:left; width:70%; margin-left:3px;'>
				".$pesan."
				<br clear='all'>
				<br clear='all'>
				Temukan ".$_SESSION['cname']." di <br>
				".@$app_url."
				".@$playstore_img."
				</div>
				<br clear='all'>
			</div>
			
			<br><br>
			Terimakasih<br><br>
			Pesan ".@$_SESSION['cname']." ini disampaikan kepada anda melalui <br><br>
			<img src='".$logo_path."'><br>
			
			";
		sendmail(trim($email_customer),$subject,$msg,$from,$type);
	}
	
	//SEND GCM NOTIFICATION//
	if(count($registatoin_ids) > 0){
		@$tgl_chat			= $dtime->date2indodate($tglupdate);
		@$wkt_chat			= substr($wktupdate,0,5);
		$notification		= "private_chat";
		$message 			= array("notification" => $notification,
								 "sound" 		=> "beep.wav", 
								 "id_chat" 		=> "23",
								 "id_customer" 	=> "",
								 "id_client" 	=> @$_SESSION['cidkey'],
								 "userfoto" 	=> $img,
								 "username" 	=> @$_SESSION['cname'],
								 "wktchat" 		=> @$wkt_chat,
								 "title" 		=> "Pesan Dari ".@$_SESSION['cname'],
								 "message" 		=> @$pesan,
								 "msgcnt" 		=> count($pesan),
								 "timestamp" 	=> date('Y-m-d H:i:s'));
		$gcm->send_notification($registatoin_ids,$message);
	}
	//END OF SEND GCM NOTIFICATION//
	//$result['content'] .= "To:".count($registatoin_ids)." + ".$msg;
	

	$q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$_SESSION['uidkey']."'"); 
	$dt_user_chat	= $db->fetchNextObject($q_user_chat);
	$user_foto_chat	= $dt_user_chat->USER_PHOTO;
	$user_name_chat	= $dt_user_chat->USER_NAME;
	$tgl_chat		= $dtime->date2indodate($tglupdate);
	$wkt_chat		= substr($wktupdate,0,5);
	$result['content'] .= "
    <div class='ch-topic-item clearfix' style='cursor:pointer' id='subject_".$id_topic."'>
        <div class='img-box'>".getuserfoto($_SESSION['uidkey']," style='width:50px' ")."</div>
        <div class='ch-content'>
            <p class='ch-name'>
                <strong>".$user_name_chat."</strong>
                <span class='ch-time'>
					<span class='public'>PUBLIC</span> :
					".$wkt_chat."
				</span>
            </p>
			".$pesan."
            <br />
            <small class='code'>".$tgl_chat." : 0 Komentar</small>
			<button class='btn btn-mini ptip_sw' onclick='show_chat(\"".trim($id_topic)."\")' title='Pilih Subjek Pesan' style='float:right; margin-left:4px'>
               <i class='icon-search'></i>
            </button>
			<button class='btn btn-mini removal ptip_sw' onclick='remove_chat(\"subject\",\"".$id_topic."\")' title='Hapus Subjek Pesan'>
				<i class='icon-trash'></i>
			</button>
        </div>
    </div>";
	$result['id_topic'] = $id_topic;
	echo json_encode($result);
}
?>
