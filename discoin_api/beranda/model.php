<?php
session_start();
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
if(!defined('mainload')) { define('mainload','SEMPOA',true); }

	include_once("../../includes/config.php");
	include_once("../../includes/classes.php");
	include_once("../../includes/functions.php");
	include_once("../../includes/declarations.php");
	
	$direction 		= isset($_REQUEST['direction']) 		? $_REQUEST['direction'] 		: "";
	$id_merchant 	= isset($_REQUEST['id_merchant']) 		? $_REQUEST['id_merchant'] 		: "";
	$id_customer 	= isset($_REQUEST['id_customer']) 		? $_REQUEST['id_customer'] 		: "";
	$id_coin 		= isset($_REQUEST['id_coin']) 			? $_REQUEST['id_coin'] 		: "";

	$callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) 		? $_REQUEST['mycallback'] 		: "";
		
	$result['msg_log'] 	= "";
	$result['io_log']  	= "";
	
	if(empty($_SESSION['sidkey'])){
		$data 			 	= relogin($id_merchant,$id_customer);
		$result['io_log'] 	= $data['io_log'];
		$result['msg_log']  = $data['msg_log'];
	}
		
	if(!empty($direction) && $direction == "scan"){
		
		$match_partner 			 = array();
		$result['content'] 		 = "";
		$result['match'] 		 = "";
		//================= MERCHANT COMMUNITY LIST ===============//
		$q_merchant 			 = $db->query("SELECT ID_CLIENT,CLIENT_NAME 
											   FROM ".$tpref."clients 
											   WHERE CLIENT_COIN = '".$id_coin."'");
		$dt_merchant 			 = $db->fetchNextObject($q_merchant);
		$merchant_name 		 	 = $dt_merchant->CLIENT_NAME;
		$id_merchant_coin 		 = $dt_merchant->ID_CLIENT;
		
		$id_community_merchant	 = array();
		$str_community_merch 	 = "SELECT 
										b.NAME,
										b.ID_COMMUNITY
								   	FROM 
								   		".$tpref."communities_merchants a,
										".$tpref."communities b
								   	WHERE 
								   		a.ID_CLIENT = '".$id_merchant_coin."' AND
										a.ID_COMMUNITY = b.ID_COMMUNITY";
		$q_community_merch 		  = $db->query($str_community_merch);
		while($dt_community_merch = $db->fetchNextObject($q_community_merch)){ 
			$id_community_merchant[] = $dt_community_merch->ID_COMMUNITY;
		}
		//============ END OF MERCHANT COMMUNITY LIST ===============//
		
		//================= CUSTOMER COMMUNITY LIST ===============//
		$community_name		 	 = "";
		$id_community_cust	     = array();
		$str_community_cust 	 = "SELECT 
										b.NAME,
										b.ID_COMMUNITY
								   	FROM 
								   		".$tpref."communities_merchants a,
										".$tpref."communities b
								   	WHERE 
								   		a.ID_CLIENT = '".$id_merchant."' AND
										a.ID_COMMUNITY = b.ID_COMMUNITY";
		$q_community_cust 		 = $db->query($str_community_cust);
		while($dt_community_cust = $db->fetchNextObject($q_community_cust)){ 
			if (in_array($dt_community_cust->ID_COMMUNITY,$id_community_merchant)) {
				array_push($match_partner,$dt_community_cust->ID_COMMUNITY);
				$community_name  .=  $dt_community_cust->NAME.",";
			}
			
		}
		//============ END OF CUSTOMER COMMUNITY LIST ===============//
		
		
		//======================== CUSTOMER INFO =================//
		$str_user_info 			 = "SELECT 
										a.COIN_NUMBER, 
										a.CUSTOMER_NAME, 
										a.CUSTOMER_EMAIL,
										a.CUSTOMER_PHOTO,
										a.CUSTOMER_SEX,
										a.CUSTOMER_STATUS,
										a.EXPIRATION_DATE,
										b.CLIENT_NAME 
									FROM 
										cat_customers a,
										cat_clients b 
									WHERE 
										a.ID_CLIENT = '".$id_merchant."' AND 
										a.ID_CLIENT = b.ID_CLIENT AND 
										a.ID_CUSTOMER = '".$id_customer."'";
		$q_user_info			 = $db->query($str_user_info);
		$dt_user_info 			 = $db->fetchNextObject($q_user_info);
		@$gender 				 = $dt_user_info->CUSTOMER_SEX;
		$today_date 			 = date("d-m-Y", strtotime($tglupdate));
		$expiration_date 		 = date("d-m-Y", strtotime($dt_user_info->EXPIRATION_DATE));
		
		$non_img 				 = "noimage-m.jpg";
		if(!empty($gender) && $gender == "L"){ $kelamin = "Laki-laki"; 								}
		if(!empty($gender) && $gender == "P"){ $kelamin = "Perempuan"; $non_img = "noimage-f.jpg";  }
		
		if($dt_user_info->CUSTOMER_STATUS != 3){ $active_status = "Non Aktif";  $class_status = "label-danger";  }
		else								   { $active_status = "Aktif"; 		$class_status = "label-success"; }
		//=================== END OF CUSTOMER INFO ===============//
		
		
		$num_match 				 = count($match_partner);
		if($num_match > 0){
			$noactive = '0';
			if($today_date>$expiration_date){ $noactive = 1; }
			
			if($dt_user_info->EXPIRATION_DATE < $tglupdate){
				
				$result['io'] = "nomatch";
				$result['msg'] = "<div class='alert alert-danger'>Whopss...<br><br>Maafkan kami <b class='text-warning'>".$dt_user_info->CUSTOMER_NAME."</b><br><br>Masa aktif COIN anda sudah berakhir, dan untuk saat ini belum berhak menerima diskon dari merchant <b class='text-warning'>".$merchant_name."</b>,<br><br>Silahkan diperpanjang melalui atau dengan menghubungi merchant <b class='text-success'>".$dt_user_info->CLIENT_NAME."</b> langganan anda, dan silahkan kembali lagi kesini.<br><br>..:D</div>";	
				
				
			}else{
				$result['io'] = "match";
				$result['content'] .= '
				<div class="col-xs-12 col-sm-12 col-md-12 text-justify">';
				
				if($id_merchant != $id_merchant_coin){
				$result['content'] .= '
					<b class="text-success">'.$merchant_name.'</b> dan <b class="text-success">'.$dt_user_info->CLIENT_NAME.'</b> adalah merchant dari komunitas <b class="text-danger">'.substr($community_name,0,-1).'</b>';
				}
				
				$result['content'] .= '
					<br><br>
					<b class="text-warning">'.$dt_user_info->CUSTOMER_NAME.'</b> adalah member <span class="label '.$class_status.'">'.$active_status.'</span> dari merchant <b class="text-success">'.$dt_user_info->CLIENT_NAME.'</b> dengan Nomor COIN <b  class="text-danger">'.$dt_user_info->COIN_NUMBER.'</b>,  berhak mendapatkan
					
					<br><br>
					<div class="well">';
					
				//================= MERCHANT DISKON ===============//
				include $call->inc("discoin_api/beranda/includes","discount.php");
				//============= END OF MERCHANT DISKON ===============//
										
				$result['content'] .= '
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>';
			}
			
			
		}else{
			$result['io'] = "nomatch";
			$result['msg'] = "<div class='alert alert-danger'>Maaf, layanan diskon anda tidak berlaku di merchant <b class='text-warning'>".$merchant_name."</b></div>";	
		}
		$result['client_name']	 = $merchant_name." -> ".$noactive;
		$result['match']		 = $num_match;
		
	
		echo $callback.'('.json_encode($result).')';
	} 
}?>

