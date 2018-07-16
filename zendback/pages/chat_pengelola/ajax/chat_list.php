<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	include $call->clas("class.gcm");
	
	$username 	= isset($_REQUEST['username']) 		? $_REQUEST['username'] 	: "";	
	$pesan 		= isset($_REQUEST['pesan']) 		? $_REQUEST['pesan'] 		: "";	
	$src 		= isset($_REQUEST['src']) 			? $_REQUEST['src'] 			: "";
	$id_topic 	= isset($_REQUEST['id_topic']) 		? $_REQUEST['id_topic'] 	: "";	
	$id_customer= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 	: "";	
	
	if((!empty($direction) && $direction == "send_chat")){
		$chat_subject= array(1=>
								array("VIEWS_ID",""),
								array("ORDER_DTIME",$tglupdate." ".$wktupdate));
		$db->update($tpref."chat_subject",$chat_subject," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$id_topic."'");
		
		$chat			= array(1=>
							array("ID_CLIENT",@$_SESSION['cidkey']),
							array("ID_CHAT_SUBJECT",@$id_topic),
							array("ID_USER",@$_SESSION['uidkey']),
							array("CHAT_MESSAGE",@$pesan),
							array("TGLUPDATE",@$tglupdate),
							array("WKTUPDATE",@$wktupdate)
						  );
		$db->insert($tpref."chat",$chat);
		$id_chat		= mysql_insert_id();
		$wkt_chat		= substr($wktupdate,0,5);
		$tgl_chat		= $dtime->date2indodate($tglupdate);
		$result['content'] = 
		"<div class='ch-message-item clearfix' id='chat_".$id_chat."'>
			<div class='img-box'>".getuserfoto($_SESSION['uidkey']," style='width:50px'")."</div>
			<div class='ch-content'>
				<p class='ch-name'>
					<strong>".$username."</strong>
					<span class='ch-time'>".$tgl_chat." : ".$wkt_chat."</span>
				</p>
				".$pesan."
                <br />
                <button class='btn btn-mini removal ptip_sw' onclick='remove_chat(\"chat\",\"".$id_chat."\")' title='Hapus Pesan'>
                    <i class='icon-trash'></i>
                </button>
			</div>
		</div>";
		$result['id_chat'] = $id_chat;
		
		$q_user_subject		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
		$dt_user_subject	= $db->fetchNextObject($q_user_subject);
		@$user_name_subject	= $dt_user_subject->CLIENT_NAME;
		@$foto				= $dt_user_subject->CLIENT_LOGO;
		if(is_file($basepath."/files/images/logos/".$foto))	{
			$img = $dirhost."/files/images/logos/".$foto;   }
		else{ 
			$img = $dirhost."/files/images/no_image.jpg"; 	}

		@$tgl_chat			= $dtime->date2indodate($tglupdate);
		@$wkt_chat			= substr($wktupdate,0,5);
		/*$regId		 		= $db->fob("CUSTOMER_REG_ID",$tpref."customers"," WHERE ID_CUSTOMER = '".$id_customer."'");
		$notification		= "private_chat";
		$registatoin_ids 	= array($regId);
		$message 			= array("notification" => $notification,
								 "sound" 		=> "beep.wav", 
								 "id_chat"		=> $id_chat,
								 "id_customer" 	=> $id_customer,
								 "userfoto" 	=> $img,
								 "username" 	=> $user_name_subject,
								 "wktchat" 		=> $wkt_chat,
								 "title" 		=> "Pesan Dari ".$user_name_subject,
								 "message" 		=> $pesan,
								 "msgcnt" 		=> count($pesan),
								 "timestamp" 	=> date('Y-m-d H:i:s'));
		$gcm->send_notification($registatoin_ids,$message);*/
		
		$subject 		= "Pesan Dari ".$user_name_subject;
		$from			= "Info Discoin Community <info@".$website_name.">";
		$type			= "html";
		$msg 			= "
		<style type='text/css'>
			.img-email-frame .inline{
				
			}
		</style>
		<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
			<table width='100%'>
				<tr>
				<td style='width:10%; height:59px; text-align:center; position:relative; overflow:hidden;  border:2px solid #CCCCCC;'>
					".getclientlogo($_SESSION["cidkey"]," style='width:100%'")."
				</td>
				<td style='width:90%; padding-left:5px; vertical-align:top'>
					Tanggal ".$tgl_chat.", pukul ".@$wkt_subject." <strong>".@$user_name_subject."</strong> Membalas Pesan anda...<br><br>
				".cutext($pesan,100)."
				</td>
				<tr>
			</table>
			<br>
			<br>
			Terimakasih<br><br>
			- info@".$website_name." - <br><br>
			<img src='".$logo_path."'><br>
			
		</div>	";		
		sendmail($to,$subject,$msg,$from,$type);	
		echo json_encode($result);
	} ?>
  
<?php
	if(!empty($direction) && $direction == "show_chat"){
		$views_id		= $db->fob("VIEWS_ID_USER",$tpref."chat_subject"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$id_topic."'");
		$ch_view_id		= substr_count($views_id,";".$_SESSION['uidkey'].";");
		if($ch_view_id == 0){
			$chat_subject 	= array(1=>array("VIEWS_ID_USER",$views_id.";".$_SESSION['uidkey'].";"));
			$db->update($tpref."chat_subject",$chat_subject," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$id_topic."'");
		}
		$q_chat	= $db->query("SELECT * FROM ".$tpref."chat WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$id_topic."' ORDER BY ID_CHAT ASC LIMIT 0,100");
		while($dt_chat = $db->fetchNextObject($q_chat)){
			if(!empty($dt_chat->ID_CUSTOMER)){
				$q_user_chat	= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$dt_chat->ID_CUSTOMER."'"); 
				$dt_user_chat	= $db->fetchNextObject($q_user_chat);
				$user_chat		= getmemberfoto($dt_chat->ID_CUSTOMER," style='width:50px'  ");
				$user_foto_chat	= $dt_user_chat->CUSTOMER_PHOTO;
				$user_name_chat	= $dt_user_chat->CUSTOMER_NAME;
			}
			if(empty($dt_chat->ID_CUSTOMER)){
				$q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER = '".$dt_chat->ID_USER."'");
				$dt_user_chat	= $db->fetchNextObject($q_user_chat);
				$user_chat		= getuserfoto($dt_chat->ID_USER," style='width:50px' ");
				$user_foto_chat	= $dt_user_chat->USER_PHOTO;
				$user_name_chat	= $dt_user_chat->USER_NAME;
			}
			$remove_subject = '<button class="btn btn-mini removal ptip_sw" onclick="remove_chat(\'chat\',\''.$dt_chat->ID_CHAT.'\')" title="Hapus Pesan">
									<i class="icon-trash"></i>
							   </button>';
			$tgl_chat		= $dtime->date2indodate($dt_chat->TGLUPDATE);
			$wkt_chat		= substr($dt_chat->WKTUPDATE,0,5);
		?> 
		<div class="ch-message-item clearfix" id="chat_<?php echo $dt_chat->ID_CHAT; ?>">
			<div class='img-box'><?php echo $user_chat; ?></div>
			<div class="ch-content">
				<p class="ch-name">
					<strong><?php echo $user_name_chat; ?></strong>
					<span class="ch-time"><?php echo $tgl_chat." : ".$wkt_chat; ?></span>
				</p>
				<?php echo $dt_chat->CHAT_MESSAGE; ?>
                <br />
                <?php echo @$remove_subject; ?>
			</div>
		</div>
		<?php } 
?>
		<script language="javascript">
            var conf 		= JSON.parse("{"+$("#config").val()+"}");
            tulcom.subscribe("/chat_merchant_<?php echo $id_topic; ?>", function(message) {
                var container 	= JSON.parse(message.nick);
                user_id			= container.uidkey; 
                if(message.msg != ""){
					id_chat		= container.id_chat; 
					if($("#chat_"+id_chat).length == 0){
						user_photo	= container.user_photo; 
						user_name	= container.user_name; 
						wkt_chat	= container.wkt_chat;
						tgl_chat	= container.tgl_chat;
						response 	= 
						"<div class='ch-message-item clearfix' id='chat_"+id_chat+"'>"+
							"<div class='img-box'><img src='"+user_photo+"' style='width:50px'></div>"
							+"<div class='ch-content'>"
								+"<p class='ch-name'>"
									+"<strong>"+user_name+" nah</strong>"
									+"<span class='ch-time'>"+tgl_chat+" : "+wkt_chat+"</span>"
								+"</p>"
								+message.msg
								+"<br>";
							if(conf.uidkey == user_id){
						 response 	+= 
								"<button class='btn btn-mini removal ptip_sw' onclick='remove_chat(\"chat\",\"<?php echo $last_topic; ?>\")' title='Hapus Pesan'>"+
									"<i class='icon-trash'></i>"+
								 "</button>";
							}
						 response 	+= 
							"</div>"
						+"</div>";
						$('.ch-messages').append(response);
						$(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
						if($("#cust_"+user_id).length > 0){
							$("#cust_"+user_id).remove();
						}
					}
                }
            });
			tulcom.subscribe("/write_merchant_<?php echo $id_topic; ?>", function(message) {
				var container 	= JSON.parse(message.nick);
				onwrite 			= message.msg;
				if(onwrite == "2" && container.id_cust != conf.uidkey){
					id_cust	= container.id_cust;
					name	= container.name;
					if($("#cust_"+id_cust).length == 0){
						content = "<div id='cust_"+id_cust+"' class='onwrite'>"+name+" Sedang mengetik...</div>";
						$("#write_status").html(content);
					}
				}
				if(onwrite == "1"){
					if($("#cust_"+id_cust).length > 0){
						$("#cust_"+id_cust).remove();
					}
				}
			});
        </script>    
<?php
	}
}
?>
