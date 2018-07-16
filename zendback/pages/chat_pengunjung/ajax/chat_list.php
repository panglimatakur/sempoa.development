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
	$id_customer= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 	: "";	
	
	if((!empty($direction) && $direction == "send_chat")){
		$wkt_chat		= substr($wktupdate,0,5);
		$tgl_chat		= $dtime->date2indodate($tglupdate);
		
		$chat_subject	= array(1=>array("PARTICIPANTS_ID_VIEW","{ID_USER:".$_SESSION['uidkey']."}"));
		$db->update($tpref."chat_attribute",$chat_subject," 
					 WHERE 
						ID_CLIENT			='".$_SESSION['cidkey']."' AND 
						ID_PARENT	='".$id_customer."'");
		
		$ch_id_topic 		= $db->recount("SELECT ID_CHAT FROM ".$tpref."chat 
										WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND 
										ID_SENDER = '".$id_customer."'");
		if($ch_id_topic == 0){
			$topic		= array(1=>
							array("CHAT_SECRECY","private"),
							array("CHAT_SRC","CUSTOMER"),
							array("ID_PARENT",$id_customer),
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("PARTICIPANTS_ID","{ID_USER:".$_SESSION['uidkey'].",ID_CUSTOMER:".$id_customer."}"),
							array("PARTICIPANTS_ID_VIEW","{ID_USER:".$_SESSION['uidkey']."}"),
							array("ORDER_DTIME",$tglupdate." ".$wktupdate),
							array("FIRSTUPDATETIME",$tglupdate." ".$wktupdate));
			$db->insert($tpref."chat_attribute",$topic);
			$id_topic 		= mysql_insert_id();
		}else{
			$id_topic = $db->fob("ID_CHAT_ATTRIBUTE",$tpref."chat_attribute"," WHERE ID_PARENT = '".$id_customer."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");		
		}

		$id_head_cs 	= $db->fob("ID_USER","system_users_client"," WHERE FLAG_HEAD_CS = '1' AND 
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
		
		
		$result['content'] = 
		"<div class='ch-message-item clearfix' id='chat_".$id_chat."'>
			<div class='img-box'>".getuserfoto($_SESSION['uidkey']," style='width:50px'")."</div>
			<div class='ch-content'>
				<p class='ch-name'>
					<strong>".$username."</strong>
					<span class='ch-time'>".@$wkt_chat."</span>
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
		//sendmail($to,$subject,$msg,$from,$type);	
		echo json_encode($result);
	} ?>
  
<?php
	if(!empty($direction) && $direction == "show_chat"){
		$id_topic = $db->fob("ID_CHAT_ATTRIBUTE",$tpref."chat_attribute"," WHERE ID_PARENT = '".$id_customer."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");	
			
		if(!empty($id_topic)){
			
			$ch_view_st  = $db->recount("SELECT PARTICIPANTS_ID_VIEW FROM ".$tpref."chat_attribute
										 WHERE 
											ID_CLIENT='".$_SESSION['cidkey']."' AND 
											ID_PARENT='".$id_customer."' AND
											PARTICIPANTS_ID_VIEW LIKE '%{ID_USER:".$_SESSION['uidkey']."}%'");
			if($ch_view_st == 0){
				@$view_ids	 	= $db->fob("PARTICIPANTS_ID_VIEW",$tpref."chat_attribute"," 
											WHERE 
												ID_CLIENT	= '".$_SESSION['cidkey']."' AND 
												ID_PARENT	= '".$id_customer."'");			
				$new_views		= $view_ids.'{USER_ID:'.$_SESSION['uidkey'].'}';
				$chat_subject	= array(1=>array("PARTICIPANTS_ID_VIEW",$new_views));
				$db->update($tpref."chat_attribute",$chat_subject," 
							 WHERE 
								ID_CLIENT	='".$_SESSION['cidkey']."' AND 
								ID_PARENT	='".$id_customer."'");
			}			
				
			$str_chat = "SELECT * FROM ".$tpref."chat WHERE ID_CHAT_ATTRIBUTE = '".$id_topic."' ORDER BY ID_CHAT ASC LIMIT 0,100";
			$q_chat	= $db->query($str_chat);
			while($dt_chat = $db->fetchNextObject($q_chat)){
				$remove_subject		= "";
				$info_tanggal 		= explode(" ",$dt_chat->UPDATEDATETIME);
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
						$dt_user_chat	= $db->fetchNextObject($q_user_chat);
						$user_foto_chat	= $dt_user_chat->USER_PHOTO;
						if(is_file($basepath."/files/images/users/".$user_foto_chat)) {
								$user_foto_chat = "users/".$user_foto_chat;  			}
						else{	$user_foto_chat = "noimage-m.jpg";  							}
						$user_chat 		= "<img src='".$dirhost."/files/images/".$user_foto_chat."' 
												style='width:50px'>"; 								
						$user_name_chat	= $dt_user_chat->USER_NAME;
						
						$remove_subject = '<button class="btn btn-mini removal ptip_sw" 
												onclick="remove_chat(\'chat\',\''.$dt_chat->ID_CHAT.'\')" 
												title="Hapus Pesan">
												<i class="icon-trash"></i>
										   </button>';
					break;
					case "CUSTOMER":
						$q_user_chat	= $db->query("SELECT ID_CUSTOMER,CUSTOMER_PHOTO,CUSTOMER_NAME 
													  FROM ".$tpref."customers 
													  WHERE ID_CUSTOMER = '".$dt_chat->ID_SENDER."'"); 
													  
						$dt_user_chat	= $db->fetchNextObject($q_user_chat);								
						$user_foto_chat	= $dt_user_chat->CUSTOMER_PHOTO;
						if(is_file($basepath."/files/images/members/".$user_foto_chat)) {
								$user_foto_chat = "members/".$user_foto_chat;  			}
						else{	$user_foto_chat = "noimage-m.jpg";  							}
						$user_chat 		= "<img src='".$dirhost."/files/images/".$user_foto_chat."' 
												style='width:50px'>"; 								
						$user_name_chat	= $dt_user_chat->CUSTOMER_NAME;
					break;	
				}
			?> 
				<div class="ch-message-item clearfix" id="chat_<?php echo $dt_chat->ID_CHAT; ?>">
					<div class='img-box'><?php echo $user_chat; ?></div>
					<div class="ch-content">
						<p class="ch-name">
							<strong><?php echo $user_name_chat; ?></strong>
							<span class="ch-time"><?php echo $tgl_chat." ".$wkt_chat; ?></span>
						</p>
						<?php echo $dt_chat->CHAT_MESSAGE; ?>
						<br />
						<?php echo @$remove_subject; ?>
					</div>
				</div>
			<?php } 
				
			
		}else{
			$nm_customer = $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER = '".$id_customer."'");
			echo "<div class='alert alert-info' id='alert_".$id_customer."' style='margin:5px;'>Anda belum melakukan chat sama sekali dengan <b>".$nm_customer."</b></div>";	
		}
?>
	<script language="javascript">
        var conf 		= JSON.parse("{"+$("#config").val()+"}");
        tulcom.subscribe("/to_chat_merchant_<?php echo $id_customer; ?>", function(datas) {
            data 			= JSON.parse(datas);
            id_customer		= data.id_customer;
            message 		= data.msg;
            <?php
                $q_user_subject		= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_PHOTO FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."'"); 
                $dt_user_subject	= $db->fetchNextObject($q_user_subject);
                @$user_foto_subject	= $dt_user_subject->CUSTOMER_PHOTO;
                @$user_name_subject	= $dt_user_subject->CUSTOMER_NAME;
            ?>
            if(message != ""){
                response 	= 
                "<div class='ch-message-item clearfix' id='chat_"+id_customer+"'>"+
                    "<div class='img-box'><img src='<?php echo $dirhost; ?>/files/images/members/<?php echo $user_foto_subject; ?>' style='width:50px'></div>"
                    +"<div class='ch-content'>"
                        +"<p class='ch-name'>"
                            +"<strong><?php echo $user_name_subject; ?></strong>"
                            +"<span class='ch-time'>"+data.date+" "+data.time+"</span>"
                        +"</p>"
                        +message+
                        "<br>"+
                    "</div>"+
                "</div>";
                $("#alert_"+id_customer).remove();
				$("#onwrite").val("");
				$("#subject_"+id_customer).removeClass("ch-topic-item").addClass("ch-topic-item-new");
                $('.ch-message-item:last').after(response);
                $(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
                if($("#cust_"+id_customer).length > 0){ $("#cust_"+id_customer).remove();}
                
            }
        });
        tulcom.subscribe("/write_chat_merchant_<?php echo $id_customer; ?>", function(data) {
            if(data.flag == "2"){
                $("#write_status").html("&nbsp;"+data.name+" sedang menulis pesan....<br>");
            }else{
                $("#write_status").empty();
            }
        });
    </script>    
<?php
	}
}
?>
