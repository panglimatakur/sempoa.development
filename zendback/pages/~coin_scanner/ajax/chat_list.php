<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$username 	= isset($_REQUEST['username']) 		? $_REQUEST['username'] : "";	
	$pesan 		= isset($_REQUEST['pesan']) 		? $_REQUEST['pesan'] : "";	
	$src 		= isset($_REQUEST['src']) 			? $_REQUEST['src'] 			: "";
	$id_topic 	= isset($_REQUEST['id_topic']) 		? $_REQUEST['id_topic'] : "";	
	if((!empty($direction) && $direction == "send_chat")){
		$chat_subject= array(1=>
								array("VIEWS_ID",""),
								array("ORDER_DTIME",$tglupdate." ".$wktupdate));
		$db->update($tpref."chat_subject",$chat_subject," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$id_topic."'");
		$chat			= array(1=>
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("ID_CHAT_SUBJECT",$id_topic),
							array("ID_USER",$_SESSION['uidkey']),
							array("CHAT_MESSAGE",$pesan),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate)
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
									+"<strong>"+user_name+"</strong>"
									+"<span class='ch-time'>"+tgl_chat+" : "+wkt_chat+"</span>"
								+"</p>"
								+message.msg
								+"<br>"
								+"<button class='btn btn-mini removal ptip_sw' onclick='remove_chat(\"chat\",\""+id_chat+"\")' title='Hapus Pesan'>"+
									"<i class='icon-trash'></i>"+
								 "</button>"
							+"</div>"
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
				if(onwrite == "2"){
					id_cust	= container.id_cust;
					name	= container.name;
					if($("#cust_"+id_cust).length == 0){
						content = "<div id='cust_"+id_cust+"' class='onwrite'>"+name+" Sedang mengetik...</div>";
						$("#write_status").append(content);
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
