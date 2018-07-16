<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$_SESSION['uidkey']."'"); 
	$dt_user_chat	= $db->fetchNextObject($q_user_chat);
	$user_foto		= $dt_user_chat->USER_PHOTO;
	$user_name		= $dt_user_chat->USER_NAME;

	if(!empty($show)){
		$str_info 		= "SELECT * FROM ".$tpref."chat_subject WHERE ID_CHAT_SUBJECT='".$show."'";	
		$q_str_chat		= $db->query($str_info);
		$dt_str_chat	= $db->fetchNextObject($q_str_chat);
		@$subjek		= $dt_str_chat->CHAT_SUBJECT;
		@$parts			= ",".$dt_str_chat->BY_ID_USER.",".$dt_str_chat->ID_USER_PARTICIPANTS;
		@$parts			= explode(",",$parts);
		@$num_parts		= substr_count($dt_str_chat->ID_USER_PARTICIPANTS,",");
		foreach($parts as &$part){
			if(!empty($part)){ $part_user[] = $part; }
		}
		$str_chat = "SELECT * FROM ".$tpref."chat WHERE ID_CHAT_SUBJECT='".$show."' ORDER BY ID_CHAT ASC LIMIT 0,100";	
		 
	}else{
		$src	 = "";
		if(!empty($outcome)){
			$src = 	"AND BY_ID_USER = '".$_SESSION['uidkey']."'";
		}else{
			$src = 	"AND ID_USER_PARTICIPANTS LIKE '%,".$_SESSION['uidkey'].",%' ";
		}
		$str_chat = "SELECT 
						* 
					  FROM 
						".$tpref."chat_subject
					  WHERE 
						ID_CHAT_SUBJECT IS NOT NULL
						".$src."
					  ORDER BY 
						ID_CHAT_SUBJECT DESC 
					  LIMIT 0,100";
	}
	//echo $str_chat;
	$q_chat	= $db->query($str_chat);	
?>