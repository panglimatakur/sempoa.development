<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(!empty($id_customer)){ $last_chat_condition = " b.ID_PARENT = '".$id_customer."' AND "; }
//LAST SUBJECT 
$str_last_subject 	= "SELECT 
					   		a.ID_CLIENT,
							a.ID_CUSTOMER, 
							a.CUSTOMER_NAME, 
							a.CUSTOMER_PHOTO,
							b.ID_CHAT_ATTRIBUTE,
							b.ID_PARENT,
							b.CHAT_SUBJECT,
							b.ORDER_DTIME
					   FROM 
					   		cat_customers a,
							cat_chat_attribute b 
					   WHERE 
					   		a.ID_CLIENT = b.ID_CLIENT AND 
							".$last_chat_condition."
							a.ID_CUSTOMER = b.ID_PARENT AND 
							a.ID_CLIENT = '".$_SESSION['cidkey']."' AND
							b.CHAT_SRC = 'CUSTOMER' 
					   ORDER BY 
					   		b.ORDER_DTIME DESC";
$q_last_subject 	= $db->query($str_last_subject);
$num_last_subject	= $db->numRows($q_last_subject);
if($num_last_subject > 0){
	$dt_last_subject	= $db->fetchNextObject($q_last_subject);
	@$last_customer 	= $dt_last_subject->ID_PARENT;
	@$last_subject  	= $dt_last_subject->ID_CHAT_ATTRIBUTE;
	@$last_dtime  		= explode(" ",$dt_last_subject->ORDER_DTIME);
	@$last_tgl 			= $dtime->now2indodate2($last_dtime[0]);
	@$last_time 		= substr($last_dtime[1],0,5);
	@$last_customer_nm 	= $dt_last_subject->CUSTOMER_NAME;
	@$foto_subject		= $dt_last_subject->CUSTOMER_PHOTO;
	if(is_file($basepath."/files/images/members/".$foto_subject)) {
			$foto_subject = "members/".$foto_subject;  			}
	else{	$foto_subject = "noimage-m.jpg";  							}
	$last_foto_subject 	= "<img src='".$dirhost."/files/images/".$foto_subject."'>"; 
	
	$last_chat_target 	= '
		<div class="ibox-content-clean target">
			<div class="chat-avatar" style="width:40px;height:40px; overflow:hidden">
				'.$last_foto_subject.'            
			</div>
			<div class="chat-user-name">
				<a href="#" id="chat-user-name">'.$last_customer_nm.'</a>
			</div>
		</div>
	';
	$last_id_sender		= $last_customer;
}else{
	@$last_customer 	= "";
	@$last_subject  	= "";
	$last_chat_target 	= '';
	$no_msg_bg			= "no-msg-bg";
	$last_id_sender		= $_SESSION['uidkey'];
}

$str_cust_chat 		= "SELECT 
					   		a.ID_CLIENT,
							a.ID_CUSTOMER, 
							a.CUSTOMER_NAME, 
							a.CUSTOMER_PHOTO,
							b.ID_CHAT_ATTRIBUTE,
							b.CHAT_SUBJECT,
							b.ORDER_DTIME
					   FROM 
					   		cat_customers a,
							cat_chat_attribute b 
					   WHERE 
					   		a.ID_CLIENT = b.ID_CLIENT AND 
							a.ID_CUSTOMER = b.ID_PARENT AND 
							a.ID_CLIENT = '".$_SESSION['cidkey']."' 
					   ORDER BY 
					   		b.ORDER_DTIME DESC";
$q_cust_chat 		= $db->query($str_cust_chat);	
$num_cust_chat 		= $db->numRows($q_cust_chat);

$str_cust_list 		= "SELECT * 
					   FROM 
					   		".$tpref."customers 
					   WHERE 
					   		ID_CLIENT = '".$_SESSION['cidkey']."' AND 
							CUSTOMER_NAME IS NOT NULL AND 
							CUSTOMER_STATUS = '3' AND 
							ID_CUSTOMER NOT IN (SELECT ID_PARENT FROM cat_chat_attribute WHERE CHAT_SRC = 'CUSTOMER') 
					   ORDER BY 
					   		CUSTOMER_NAME ASC LIMIT 0,100";
$q_cust_list		= $db->query($str_cust_list);
$num_cust_list 		= $db->numRows($q_cust_list);



$cond = "";
if($_SESSION['admin_only'] == "false"){ $cond = "AND ID_CLIENT='".$_SESSION['cidkey']."'"; }
$str_chat 	= "SELECT * FROM ".$tpref."chat 
			   WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND
					 ID_CHAT_ATTRIBUTE = '".$last_subject."'
					 ".$cond." 
			   ORDER BY 
			   		 UPDATEDATETIME ASC LIMIT 0,100";
$q_chat		= $db->query($str_chat);

?>