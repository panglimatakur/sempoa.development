<?php
session_start();
session_destroy();
error_reporting(0);
if((!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
	define('mainload','SEMPOA',true);
	include("../../includes/config.php");
	include("../../includes/classes.php");
	include("../../includes/functions.php");
	include("../../includes/declarations.php");	

	$id_community		= isset($_REQUEST['id_community']) 	? $_REQUEST['id_community'] 	: "";
	$id_merchant		= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] 		: "";
	$id_customer		= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 		: "";
	$isi				= isset($_REQUEST['isi']) 			? $_REQUEST['isi'] 				: "";
	$start_row			= isset($_REQUEST['start_row']) 	? $_REQUEST['start_row'] 		: "";
	
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	
	$result['content']	= "";
	$result['msg_log'] 	= "";
	$result['io_log']  	= "";
	
	if(empty($_SESSION['sidkey'])){
		$data 			 = relogin($id_merchant,$id_customer);
		$result['io_log'] 	= $data['io_log'];
		$result['msg_log']  = $data['msg_log'];
	}
	$result['content'] = "";
	if(!empty($id_customer)){
		if((!empty($direction) && $direction == "load")){
		   $condition 			= '';
		   @$id_chat_attribute   = $db->fob("ID_CHAT_ATTRIBUTE",
		   									$tpref."chat_attribute",
											" WHERE ID_PARENT = '".$id_customer."'");
		   $str_subject			= "SELECT * FROM ".$tpref."chat WHERE 
										ID_CLIENT='".$id_merchant."' AND 
										ID_CHAT_ATTRIBUTE = '".$id_chat_attribute."'
									ORDER BY 
										ID_CHAT ASC";
		   $num_subject			= $db->recount($str_subject);
		   if(empty($start_row)){ $start_row = $num_subject; }
		   $start_row 		= $start_row - 10;
		   if($start_row < 0){ $start_row = 0; }
		   $result['content'] .= '
		   <input type="hidden" id="start_row" value="'.$start_row.'">';
		   
		   
		   $q_subject			= $db->query($str_subject." LIMIT ".@$start_row.",10"); 
		   //$result['content']  .= $str_subject." LIMIT ".@$start_row.",10";
		   $num_q_subject 		= $db->numRows($q_subject);
		   $q_merchant 			= $db->query("SELECT CLIENT_NAME,CLIENT_LOGO FROM ".$tpref."clients  
		   									  WHERE ID_CLIENT = '".$id_merchant."'");
		   $dt_merchant 		= $db->fetchNextObject($q_merchant);
		   $nm_merchant 		= $dt_merchant->CLIENT_NAME;
		   $logo_merchant 		= $dt_merchant->CLIENT_LOGO;
		   if(empty($logo_merchant) && is_file($basepath."/files/images/logos".$dt_merchant->CLIENT_LOGO)){
				$logo_path = "<img src='".$dirhost."/files/images/logos".$dt_merchant->CLIENT_LOGO."'>";  
		   }else{
				$logo_path = "<img src='".$dirhost."/files/images/no_image.jpg'>";  
		   }
		   if($num_subject == 0){
	$result['content'] .= '
			<div class="alert alert-info alert-chat">
				Kamu belum memiliki pesan untuk <b>'.$nm_merchant.'</b>
			</div>
			<div class="chat-item" style="display:none"></div>';
		   }else{
			   while($dt_subject = $db->fetchNextObject($q_subject)){
				    $info_tanggal 	= explode(" ",$dt_subject->UPDATEDATETIME);
					@$wkt_subject	= substr($info_tanggal[1],0,5);
					
					@$tgl_subject	= "";
					if($tglupdate != $info_tanggal[0]){
						@$tgl_subject	= $dtime->date2indodate($info_tanggal[0])."<br>";
					}
					$remove_subject	= "";
					
					switch($dt_subject->SENDER_LEVEL_NAME){
						case "USER":
							$q_user_subject		= $db->query("SELECT USER_PHOTO,USER_PHOTO,USER_NAME
															  FROM system_users_client 
															  WHERE ID_USER = '".$dt_subject->ID_SENDER."'");
							$dt_user_subject	= $db->fetchNextObject($q_user_subject);
							@$foto				= $dt_user_subject->USER_PHOTO;
							if(is_file($basepath."/files/images/users/".$foto)) {$foto = "users/".$foto;  }
							else												{$foto = "noimage-m.jpg";  }
							$user_subject 		= "<img src='".$dirhost."/files/images/".$foto."' width='100%'>"; 
							
							@$user_name_subject	= $dt_user_subject->USER_NAME;
							
							$chat_content 		= '
							<div class="chat-item">
								<div class="col-xs-10 col-sm-10 col-md-10" id="subject_'.$dt_subject->ID_CHAT.'" >
									<div class="bubble bubble-right">
										<div class="ch-content">
											<small style="color:#C03F3F"><b>'.@$user_name_subject.'</b></small><br>
											'.$dt_subject->CHAT_MESSAGE.'
										</div>
										<div class="ch-time" style="margin-top:-7px">
											'.@$tgl_subject.' '.@$wkt_subject.'
										</div>
									</div>
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 text-center" style="padding:0 0 0 20px">
									<div class="img-circle img-box">
										'.$user_subject.'
									</div>
								</div>
								<div class="clearfix"></div>
							</div>';
						break;
						case "CUSTOMER":
							$q_user_subject		= $db->query("SELECT ID_CUSTOMER,CUSTOMER_PHOTO,CUSTOMER_NAME 
															  FROM ".$tpref."customers 
															  WHERE ID_CUSTOMER = '".$dt_subject->ID_SENDER."'"); 
							$dt_user_subject	= $db->fetchNextObject($q_user_subject);
							@$user_subject		= getmemberfoto($dt_user_subject->ID_CUSTOMER,"width='100%' ");
							@$user_foto_subject	= $dt_user_subject->CUSTOMER_PHOTO;
							@$user_name_subject	= $dt_user_subject->CUSTOMER_NAME;
							
							$chat_content 		= '
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
											'.$dt_subject->CHAT_MESSAGE.'
										</div>
										<div class="ch-time" style="margin-top:-7px">'.$tgl_subject.' '.$wkt_subject.'</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>';
						break;
					}
					$nickname 				= explode(" ",$user_name_subject);
					$newlastSubjectID		= $dt_subject->ID_CHAT;
					$result['content'] 	   .= $chat_content;
				}
		   }
		}
	}
	
	echo $callback.'('.json_encode($result).')';

}

?>

