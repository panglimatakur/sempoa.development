<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");

	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$pesan 		= isset($_REQUEST['pesan']) ? $_REQUEST['pesan'] : "";
	$chat		= array(1=>
				  	array("ID_CLIENT",$_SESSION['cidkey']),
					array("CHAT_SUBJECT",$pesan),
					array("CHAT_SRC","CUSTOMER"),
					array("CHAT_SECRECY","public"),
					array("BY_ID_USER",$_SESSION['uidkey']),
					array("TGLUPDATE",$tglupdate),
					array("WKTUPDATE",$wktupdate)
				  );
	$db->insert($tpref."chat_subject",$chat);
	$id_topic 		= mysql_insert_id();
	$chat		= array(1=>
				array("ID_CLIENT",$_SESSION['cidkey']),
				array("ID_CHAT_SUBJECT",$id_topic),
				array("ID_USER",$_SESSION['uidkey']),
				array("CHAT_MESSAGE",$pesan),
				array("TGLUPDATE",$tglupdate),
				array("WKTUPDATE",$wktupdate)
			  );
	$db->insert($tpref."chat",$chat);
	$q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$_SESSION['uidkey']."'"); 
	$dt_user_chat	= $db->fetchNextObject($q_user_chat);
	$user_foto_chat	= $dt_user_chat->USER_PHOTO;
	$user_name_chat	= $dt_user_chat->USER_NAME;
	$tgl_chat		= $dtime->date2indodate($tglupdate);
	$wkt_chat		= substr($wktupdate,0,5);
	$result['content'] = "
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
