<?php
error_reporting(0);
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	include $call->clas("class.gcm");
	
	$username 	= isset($_REQUEST['username']) 		? $_REQUEST['username'] 	: "";	
	$pesan 		= isset($_REQUEST['pesan']) 		? $_REQUEST['pesan'] 		: "";	
	$src 		= isset($_REQUEST['src']) 			? $_REQUEST['src'] 			: "";
	$id_customer= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 	: "";	
	
	if((!empty($direction) && $direction == "get_target")){
		
		$str_customer 		= "SELECT ID_CLIENT,ID_CUSTOMER,CUSTOMER_NAME,CUSTOMER_PHOTO
							   FROM cat_customers
							   WHERE ID_CUSTOMER ='".$_REQUEST['id_customer']."' AND 
									 ID_CLIENT = '".$_SESSION['cidkey']."'";
									 
		$q_customer 		= $db->query($str_customer);
		$num_customer		= $db->numRows($q_customer);
		if($num_customer > 0){
			$dt_customer		= $db->fetchNextObject($q_customer);
			@$customer_name 	= $dt_customer->CUSTOMER_NAME;
			@$customer_photo	= $dt_customer->CUSTOMER_PHOTO;
			if(is_file($basepath."/files/images/members/".$customer_photo)) {
					$customer_photo = "members/".$customer_photo;  			}
			else{	$customer_photo = "noimage-m.jpg";  							}
			$customer_photo 	= "<img src='".$dirhost."/files/images/".$customer_photo."'>"; 
			
			$last_chat_target 	= '
				<div class="ibox-content-clean target">
					<div class="chat-avatar" style="width:40px;height:40px; overflow:hidden">
						'.$customer_photo.'            
					</div>
					<div class="chat-user-name">
						<a href="#" id="chat-user-name">'.$customer_name.'</a>
					</div>
				</div>
			';
			echo $last_chat_target;
		}
		
		
	}
	
	
	if((!empty($direction) && $direction == "send_chat")){
		$wkt_chat		= substr($wktupdate,0,5);
		$tgl_chat		= $dtime->date2indodate($tglupdate);
		
		
		$ch_id_topic 		= $db->recount("SELECT ID_CHAT_ATTRIBUTE FROM ".$tpref."chat_attribute
											WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND 
												  ID_PARENT = '".$id_customer."'");
		if($ch_id_topic == 0){
			$topic		= array(1=>
							array("CHAT_SECRECY","private"),
							array("CHAT_SRC","CUSTOMER"),
							array("CHAT_SUBJECT",$pesan),
							array("ID_PARENT",$id_customer),
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("PARTICIPANTS_ID","{ID_USER:".$_SESSION['uidkey'].",ID_CUSTOMER:".$id_customer."}"),
							array("PARTICIPANTS_ID_VIEW","{ID_USER:".$_SESSION['uidkey']."}"),
							array("ORDER_DTIME",$tglupdate." ".$wktupdate),
							array("FIRSTUPDATETIME",$tglupdate." ".$wktupdate));
			$db->insert($tpref."chat_attribute",$topic);
			$id_topic 		= mysql_insert_id();
		}else{
			$chat_subject	= array(1=>
									array("PARTICIPANTS_ID_VIEW","{ID_USER:".$_SESSION['uidkey']."}"),
									array("CHAT_SUBJECT",$pesan),
									array("ORDER_DTIME",$tglupdate." ".$wktupdate));
			$db->update($tpref."chat_attribute",$chat_subject," 
						 WHERE 
							ID_CLIENT	='".$_SESSION['cidkey']."' AND 
							ID_PARENT	='".$id_customer."'");
			
			
			@$id_topic = $db->fob("ID_CHAT_ATTRIBUTE",$tpref."chat_attribute"," WHERE ID_PARENT = '".$id_customer."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");		
		}

		@$id_head_cs 	= $db->fob("ID_USER","system_users_client"," WHERE FLAG_HEAD_CS = '1' AND 
																	 	   ID_CLIENT = '".$_SESSION['cidkey']."'");
		$chat			= array(1=>
							array("ID_CHAT_ATTRIBUTE",$id_topic),
							array("SENDER_LEVEL_NAME","USER"),
							array("ID_SENDER",$_SESSION['uidkey']),
							array("IP_SENDER",@$ip_address),
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("ID_USER_HEAD_CS",$id_head_cs),
							array("PARTICIPANTS_ID_VIEW","{ID_USER:".$_SESSION['uidkey']."}"),
							array("CHAT_MESSAGE",$pesan),
							array("UPDATEDATETIME",$tglupdate." ".$wktupdate));
		$db->insert($tpref."chat",$chat);
		$id_chat		= mysql_insert_id();
		
		
		$result['content'] = "
			<div class='chat-message' id='chat_".$id_chat."' data-id-sender='".$_SESSION['uidkey']."'>
				<div class='message-avatar'>
					".getuserfoto($_SESSION['uidkey'],"")."
				</div>
				<div class='message'>
					<a class='message-author' href='javascript:void();'>".@$username."</a>
					<span class='message-date'>".@$wkt_chat."</span>
					<span class='message-content'>
					".@$pesan."
					</span>
						<a href='javascript:void();' class='removal' onclick='remove_chat(\"chat\",\"".$id_chat."\")' title='Hapus Pesan'>x</a>
				</div>
			</div>";
		$result['id_chat'] = $id_chat;
		
		$q_user_subject		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
		@$dt_user_subject	= $db->fetchNextObject($q_user_subject);
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
		
		$subject 		= "Pesan Dari ".@$user_name_subject;
		$from			= "Info Discoin Community <info@".@$website_name.">";
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
				".cutext(@$pesan,100)."
				</td>
				<tr>
			</table>
			<br>
			<br>
			Terimakasih<br><br>
			- info@".@$website_name." - <br><br>
			<img src='".@$logo_path."'><br>
			
		</div>	";		
		//sendmail($to,$subject,$msg,$from,$type);	
		echo json_encode($result);
	} 
	
	
}

	if(!empty($direction) && $direction == "show_chat" || !empty($last_customer)){
		if(empty($id_customer)){ $id_customer = $last_customer; }
		echo '<span class="chat-message" data-id-sender="'.@$id_customer.'" style="display:none"></span>';
		
		@$id_topic = $db->fob("ID_CHAT_ATTRIBUTE",$tpref."chat_attribute"," 
							   WHERE ID_PARENT = '".$id_customer."' AND 
							   		 ID_CLIENT = '".$_SESSION['cidkey']."'");	
			
		if(!empty($id_topic)){
			
			@$ch_view_st  = $db->recount("SELECT PARTICIPANTS_ID_VIEW FROM ".$tpref."chat_attribute
										 WHERE 
											ID_CLIENT='".$_SESSION['cidkey']."' AND 
											ID_PARENT='".$id_customer."' AND
											PARTICIPANTS_ID_VIEW LIKE '%{ID_USER:".$_SESSION['uidkey']."}%'");
			if($ch_view_st == 0){
				@$view_ids	 	= $db->fob("PARTICIPANTS_ID_VIEW",$tpref."chat_attribute"," 
											WHERE 
												ID_CLIENT	= '".$_SESSION['cidkey']."' AND 
												ID_PARENT	= '".$id_customer."'");			
				@$new_views		= $view_ids.'{USER_ID:'.$_SESSION['uidkey'].'}';
				@$chat_subject	= array(1=>array("PARTICIPANTS_ID_VIEW",$new_views));
				$db->update($tpref."chat_attribute",$chat_subject," 
							 WHERE 
								ID_CLIENT	='".$_SESSION['cidkey']."' AND 
								ID_PARENT	='".$id_customer."'");
			}			
				
			$str_chat = "SELECT * FROM ".$tpref."chat WHERE ID_CHAT_ATTRIBUTE = '".$id_topic."' ORDER BY ID_CHAT ASC LIMIT 0,100";
			$q_chat	= $db->query($str_chat);
			while($dt_chat = $db->fetchNextObject($q_chat)){
				$remove_subject		= "";
				@$id_sender 		= $dt_chat->ID_SENDER;
				@$info_tanggal 		= explode(" ",$dt_chat->UPDATEDATETIME);
				@$wkt_chat			= substr($info_tanggal[1],0,5);
				@$tgl_chat			= "";
				if($tglupdate != $info_tanggal[0]){
					@$tgl_chat		= $dtime->date2indodate($info_tanggal[0])."<br>";
				}
				
				switch($dt_chat->SENDER_LEVEL_NAME){
					case "USER":
						$q_user_chat	= $db->query("SELECT ID_USER,USER_PHOTO,USER_NAME 
													  FROM system_users_client 
													  WHERE ID_USER = '".$dt_chat->ID_SENDER."'");
						@$dt_user_chat	= $db->fetchNextObject($q_user_chat);
						@$user_foto_chat	= $dt_user_chat->USER_PHOTO;
						if(!empty($user_foto_chat) && is_file($basepath."/files/images/users/".$user_foto_chat)) {
								$user_foto_chat = "users/".$user_foto_chat;  			}
						else{	$user_foto_chat = "noimage-m.jpg";  							}
						@$user_chat 		= "<img src='".$dirhost."/files/images/".$user_foto_chat."'>"; 								
						@$user_name_chat	= $dt_user_chat->USER_NAME;
						
						@$remove_subject = '<a href="javascript:void()" class="removal" 
												onclick="remove_chat(\'chat\',\''.@$dt_chat->ID_CHAT.'\')" 
												title="Hapus Pesan">x</a>';
					break;
					case "CUSTOMER":
						$q_user_chat	= $db->query("SELECT ID_CUSTOMER,CUSTOMER_PHOTO,CUSTOMER_NAME 
													  FROM ".$tpref."customers 
													  WHERE ID_CUSTOMER = '".@$dt_chat->ID_SENDER."'"); 
													  
						@$dt_user_chat	= $db->fetchNextObject($q_user_chat);								
						@$user_foto_chat	= $dt_user_chat->CUSTOMER_PHOTO;
						if(!empty($user_foto_chat) && is_file($basepath."/files/images/members/".$user_foto_chat)) {
								@$user_foto_chat = "members/".$user_foto_chat;  			}
						else{	@$user_foto_chat = "noimage-m.jpg";  							}
						@$user_chat 		= "<img src='".$dirhost."/files/images/".$user_foto_chat."'>"; 								
						@$user_name_chat	= $dt_user_chat->CUSTOMER_NAME;
					break;	
				}
			?> 
				<?php if(!empty($sender_id) && $sender_id == $dt_chat->ID_SENDER){?><div></div><?php } ?>
                <div class="chat-message" 
                	 id="chat_<?php echo $dt_chat->ID_CHAT; ?>" 
                     data-id-sender="<?php echo $id_sender; ?>">
                    <div class='message-avatar' style='width:50px;height:50px;overflow:hidden'>
                        <?php echo $user_chat; ?>
                    </div>
                    <div class="message">
                        <a class="message-author" href="javascript:void();"><?php echo $user_name_chat; ?></a>
                        <span class="message-date"><?php echo $tgl_chat." ".$wkt_chat; ?></span>
                        <span class="message-content">
                        <?php echo $dt_chat->CHAT_MESSAGE; ?>
                        </span>
                        <?php echo @$remove_subject; ?>
                    </div>
                </div>

			<?php $sender_id 			= $dt_chat->ID_SENDER;} 
				
			
		}else{
			@$nm_customer = $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER = '".$id_customer."'");
			echo "<div class='alert alert-info' id='alert_".$id_customer."' style='margin:5px;'>Anda belum melakukan chat sama sekali dengan <b>".@$nm_customer."</b></div>";	
		}
	}


?>
