<?php
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once('../includes/config.php');
include_once('../includes/classes.php');
include_once('../includes/functions.php');
include_once('../includes/declarations.php');
if (isset($_GET["regId"]) && isset($_GET["title"]) && isset($_GET["message"])) {
    $regId 			= $_GET["regId"];
    $title 			= $_GET["title"];
	$message 		= $_GET["message"];
	$id_customer	= "4547";
    $notification	= "private_chat";
	include_once './GCM.php';
	
	$q_user_subject		= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."'"); 
	$dt_user_subject	= $db->fetchNextObject($q_user_subject);
	@$foto				= $dt_user_subject->CUSTOMER_PHOTO;
	if(!empty($foto)){
		if(is_file($basepath."/files/images/members/".$foto)){
			$user_subject = "<img src='".$dirhost."/files/images/members/".$foto."' width='100%'/>" ;
		}else{
			$user_subject = "<img src='".$dirhost."/files/images/no_image.jpg' width='100%'/>";
		}
	}
	else{
		$user_subject = "<img src='".$dirhost."/files/images/no_image.jpg' width='100%'/>";
	}
	@$user_name_subject	= $dt_user_subject->CUSTOMER_NAME;
	$id_profile			= $dt_user_subject->ID_CUSTOMER;	
  
    $gcm 			= new GCM();
	$message		= '
			<div class="ch-topic-item clearfix" style="cursor:pointer;" id="subject_78">
				<div class="img-box">'.@$user_subject.'</div>
				<div class="ch-content">
					<p class="ch-name">
						<strong>'.@$user_name_subject.'</strong>
						<span class="ch-time">
							'.@$wkt_subject.'
						</span> 
						<br />
						<span style="color:#000">
							'.$message.'
					   </span>
					</p>
					<br />
				</div>
			</div>';
    $registatoin_ids = array($regId);
    $message = array("notification" => $notification,
					 "id_customer" 	=> $id_customer,
					 "title" 		=> $title,
					 "message" 		=> $message,
					 "msgcnt" 		=> count($message),
					 "timestamp" 	=> date('Y-m-d H:i:s'));

    $result = $gcm->send_notification($registatoin_ids, $message);

    echo $result;
}
?>
