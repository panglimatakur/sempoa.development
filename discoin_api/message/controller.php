<?php
session_start();
error_reporting(0);
if((!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
	define('mainload','SEMPOA',true);
	include("../../includes/config.php");
	include("../../includes/classes.php");
	include("../../includes/functions.php");
	include("../../includes/declarations.php");

	$direction			= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$id_merchant		= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] 	: "";
	$id_customer		= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 	: "";
	$isi				= isset($_REQUEST['isi']) 			? $_REQUEST['isi'] 			: "";
	
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	$result['content']	= "";
	if(empty($_SESSION['sidkey'])){
		$data 			 	= relogin($id_merchant,$id_customer);
		$result['io_log'] 	= $data['io_log'];
		$result['msg_log']  = $data['msg_log'];
	}
	
	if(!empty($direction) && $direction == "send_chat"){
		
		@$tgl_subject	= $dtime->date2indodate($tglupdate);
		@$wkt_subject	= substr($wktupdate,0,5);
		
		$id_topic 		= $db->fob("ID_CHAT_ATTRIBUTE",$tpref."chat_attribute"," WHERE ID_CLIENT = '".$id_merchant."' AND ID_PARENT = '".$id_customer."'");
		$id_head_cs 		= $db->fob("ID_USER","system_users_client"," WHERE FLAG_HEAD_CS = '1' AND 
	   																	 ID_CLIENT = '".$id_merchant."'");
		if(empty($id_topic)){
			$topic		= array(1=>
							array("CHAT_SECRECY","private"),
							array("CHAT_SRC","CUSTOMER"),
							array("ID_PARENT",$id_customer),
							array("ID_CLIENT",$id_merchant),
							array("PARTICIPANTS_ID","{ID_USER:".$id_head_cs.",ID_CUSTOMER:".$id_customer."}"),
							array("ORDER_DTIME",$tglupdate." ".$wktupdate),
							array("FIRSTUPDATETIME",$tglupdate." ".$wktupdate));
			$db->insert($tpref."chat_attribute",$topic);
			$id_topic 		= mysql_insert_id();
		}
		
		$chat			= array(1=>
							array("ID_CHAT_ATTRIBUTE",$id_topic),
							array("SENDER_LEVEL_NAME","CUSTOMER"),
							array("ID_SENDER",$id_customer),
							array("IP_SENDER",@$ip_address),
							array("ID_CLIENT",$id_merchant),
							array("ID_USER_HEAD_CS",$id_head_cs),
							array("PARTICIPANTS_ID_VIEW","{ID_CUSTOMER:".$id_customer."}"),
							array("CHAT_MESSAGE",mysql_real_escape_string($isi)),
							array("UPDATEDATETIME",$tglupdate." ".$wktupdate));
		$db->insert($tpref."chat",$chat);
		$id_chat			= mysql_insert_id();
	
		$q_user_subject		= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."'"); 
		$dt_user_subject	= $db->fetchNextObject($q_user_subject);
		@$user_subject		= getmemberfoto($id_customer," width='100%'");
		@$user_name_subject	= $dt_user_subject->CUSTOMER_NAME;
	
	
		$result['content'] .= '
		<div class="chat-item">
			<div class="col-xs-2 col-sm-2 col-md-2 text-center" id="subject_'.$dt_subject->ID_CHAT.'"  style="padding:0 0 0 3px">
				<div class="img-circle img-box">
					'.$user_subject.'
				</div>
			</div>
			<div class="col-xs-10 col-sm-10 col-md-10" >
				<div class="bubble bubble-left">
					<div class="ch-content">
						<small style="color:#C03F3F"><b>'.@$user_name_subject.'</b></small><br>
						'.$isi.'
					</div>
					<div class="ch-time" style="margin-top:-6px">'.$wkt_subject.'</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>';
		
		/*$q_client 		= $db->query("SELECT CLIENT_EMAIL,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT = '".$id_merchant."' ");
		$dt_client 		= $db->fetchNextObject($q_client);
		$to				= $dt_client->CLIENT_EMAIL;
		if(!empty($to)){
			$merchant_coin = $dt_client->CLIENT_NAME;
			$subject 		= "Pesan Dari ".$user_name_subject;
			$from			= "Info Discoin Community <info@sempoa.biz>";
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
						".getmemberfoto($id_merchant," style='width:100%'")."
					</td>
					<td style='width:90%; padding-left:5px; vertical-align:top'>
						Tanggal ".$tgl_chat.", pukul ".@$wkt_subject." <strong>".@$username."</strong> Mengirim pesan di Forum Discoin anda, mohon segera di tanggapi.
					</td>
					<tr>
				</table>
				<br>
				<br>
				Terimakasih<br><br>
				- info@sempoa.biz - <br><br>
				<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
				<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
			</div>	";		
			sendmail($to,$subject,$msg,$from,$type);	
		}*/
		echo $callback.'('.json_encode($result).')';
	}
}
?>

