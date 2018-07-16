<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	if(!empty($_REQUEST['direction']))		{ $direction 	= $sanitize->str($_REQUEST['direction']); 		}
	if(!empty($direction)){
		
		if($direction == "reply" || $direction == "send"){
			$pesan 		= isset($_REQUEST['pesan']) 	? $_REQUEST['pesan'] : "";
			if(!empty($pesan)){
				$product_direction	= 18;
				$subjek 			= isset($_REQUEST['subjek']) 	? $_REQUEST['subjek'] : "";
				$id_topic 			= isset($_REQUEST['id_topic']) 	? $_REQUEST['id_topic'] : "";
				$user_name 			= isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : "";
				$kepada 			= isset($_REQUEST['kepada']) 	? $_REQUEST['kepada'] : "";
				$parts				= explode(",",$kepada);
				$num_parts			= substr_count($kepada,",");
				$part_user			= "";
				foreach($parts as &$part){
					if(!empty($part)){ 
						$part_user .= ','.$part.','; 
						$nm_topic	= $db->fob("ID_CHAT_SUBJECT",$tpref."chat_subject"," WHERE ID_CHAT_SUBJECT='".$id_topic."'");
						$note       = @$user_name." : <a href='?page=pesan&show=".$id_topic."'>".$nm_topic."</a>";
						$rid_client	= $db->fob("ID_CLIENT","system_users_client"," WHERE ID_USER='".$part."'");
						send_notification($rid_client,$product_direction,$note,$part);
					}
				}
				$filenames	= "";
				if(isset($_FILES["attachment"])){
					foreach ($_FILES["attachment"]["error"] as $key => $error) {
						if ($error == UPLOAD_ERR_OK) {
							$filenames 	.= "|".$file_id."-".$_FILES["attachment"]["name"][$key];
							move_uploaded_file( $_FILES["attachment"]["tmp_name"][$key],$basepath."/".$file_dir."/".$file_id."-".$_FILES['attachment']['name'][$key]);
						}
					}
				}
				
				if($direction == "reply"){
					$num_subjek = $db->recount("SELECT CHAT_SUBJECT FROM ".$tpref."chat_subject WHERE CHAT_SUBJECT = '".$subjek."' AND ID_CHAT_SUBJECT = '".$id_topic."'");
					if($num_subjek > 0){
						$chat_subject = array(1=>
											array("ID_CLIENT",$_SESSION['cidkey']),
											array("CHAT_SUBJECT",$subjek),
											array("CHAT_ATTACHMENTS",$filenames),
											array("ID_USER_PARTICIPANTS",$part_user),
											array("BY_ID_USER",$_SESSION['uidkey']),
											array("TGLUPDATE",$tglupdate),
											array("WKTUPDATE",$wktupdate)
										);
						$db->update($tpref."chat_subject",$chat_subject," WHERE ID_CHAT_SUBJECT = '".$id_topic."'"); 
					}
				}
				if($direction == "send" || ($direction == "reply" && $num_subjek == 0)){
					$chat_subject = array(1=>
										array("ID_CLIENT",$_SESSION['cidkey']),
										array("CHAT_SUBJECT",$subjek),
										array("CHAT_ATTACHMENTS",$filenames),
										array("ID_USER_PARTICIPANTS",$part_user),
										array("BY_ID_USER",$_SESSION['uidkey']),
										array("TGLUPDATE",$tglupdate),
										array("WKTUPDATE",$wktupdate)
									 );
					$db->insert($tpref."chat_subject",$chat_subject); 
					$id_topic	= mysql_insert_id();
				}
				
				$chat		= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CHAT_SUBJECT",$id_topic),
								array("ID_USER",$_SESSION['uidkey']),
								array("CHAT_MESSAGE",$pesan),
								array("CHAT_ATTACHMENTS",$filenames),
								array("TGLUPDATE",$tglupdate),
								array("WKTUPDATE",$wktupdate)
							  );
				$db->insert($tpref."chat",$chat); 
				$wkt_chat		= substr($wktupdate,0,5);
				
				if($direction == "reply"){
					echo "    
					<div class='ch-message-item clearfix' id='ch-message-temp'>
						".getuserfoto($_SESSION['uidkey'],' class="ch-image img-avatar"')."
						<div class='ch-content'>
							<p class='ch-name'>
								<strong>".@$user_name."</strong>
								<span class='ch-time'>".$wkt_chat."</span>
							</p>
							<span class='ch-text'>".$pesan."</span>
						</div>
					</div>";
				}
			}
		}
	}
}
?>