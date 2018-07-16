<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$q_topic			= $db->query("SELECT * FROM ".$tpref."chat_subject WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND CHAT_SRC='CUSTOMER' ORDER BY ORDER_DTIME DESC LIMIT 0,100");

	@$q_subject 		= $db->query("SELECT CHAT_SECRECY,CHAT_SUBJECT,ID_CHAT_SUBJECT FROM ".$tpref."chat_subject WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND CHAT_SRC='CUSTOMER' ORDER BY ORDER_DTIME DESC");
	@$dt_subject		= $db->fetchNextObject($q_subject);
	@$last_topic 		= $dt_subject->ID_CHAT_SUBJECT;
	@$subject			= $dt_subject->CHAT_SUBJECT;
	@$type_subject		= $dt_subject->CHAT_SECRECY;
	
	$q_chat	= $db->query("SELECT * FROM ".$tpref."chat WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$last_topic."' ORDER BY ID_CHAT ASC LIMIT 0,100");
	
	$q_visit = $db->query("SELECT ID_CUSTOMER,TGLUPDATE,ID_CLIENT FROM ".$tpref."clients_visitors WHERE ID_CLIENT = '".$_SESSION['cidkey']."' ORDER BY ID_CLIENT_VISIT DESC");
?>