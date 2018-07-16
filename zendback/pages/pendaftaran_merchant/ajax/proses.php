<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','ADMIN',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	
	$direction 		= isset($_POST['direction']) 		? $sanitize->str($_POST['direction']) 		: "";
	$no 			= isset($_POST['no']) 				? $sanitize->number($_POST['no']) 			: "";
	$client_name 	= isset($_POST['client_name']) 		? $sanitize->str($_POST['client_name']) 	: "";
	$form_direction	= isset($_POST['form_direction']) 	? $sanitize->str($_POST['form_direction']) 	: "";
	
	if(!empty($direction) && $direction == "delete"){ 
		$q_logos		= $db->query("SELECT CLIENT_LOGO,CLIENT_EMAIL,CLIENT_APP FROM ".$tpref."clients WHERE ID_CLIENT='".$no."'");
		$dt_logos		= $db->fetchNextObject($q_logos);
		@$photori 		= $dt_logos->CLIENT_LOGO;
		@$app_ori		= $dt_logos->CLIENT_APP; 
		@$client_email	= $dt_logos->CLIENT_EMAIL; 
		if(!is_dir($basepath."/files/images/logos/".$photori)){
			unlink($basepath."/files/images/logos/".$photori);
		}
		if(!is_dir($basepath."/files/images/logos/".$dt_logos->CLIENT_LOGO_LABEL)){
			unlink($basepath."/files/images/logos/".$dt_logos->CLIENT_LOGO_LABEL);
		}
		$db->delete($tpref."clients","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."clients_discounts","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."clients_ranks","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."clients_register","WHERE CLIENT_EMAIL='".$client_email."'");//
		$db->delete($tpref."clients_surfers","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."clients_testimonials","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."clients_visitors","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."communities_merchants","WHERE ID_CLIENT='".$no."'");//
		$db->delete($tpref."contact","WHERE ID_CLIENT='".$no."'");


		$q_customer = $db->query("SELECT ID_CUSTOMER,CUSTOMER_PHOTO FROM ".$tpref."customers 
								   WHERE ID_CLIENT='".$no."'");
		while($dt_customer = $db->fetchNextObject($q_customer)){
			$id_cust 		= $dt_customer->ID_CUSTOMER;
			if(is_file($basepath."/files/images/members/".$dt_customer->CUSTOMER_PHOTO)){
				unlink($basepath."/files/images/members/".$dt_customer->CUSTOMER_PHOTO);	
			}
			if(is_file($basepath."/files/images/members/big/".$dt_customer->CUSTOMER_PHOTO)){
				unlink($basepath."/files/images/members/big/".$dt_customer->CUSTOMER_PHOTO);	
			}			
			$db->delete($tpref."customers_carts","WHERE ID_CUSTOMER		='".$id_cust."'");
			$db->delete($tpref."customers_config","WHERE ID_CUSTOMER	='".$id_cust."'");
			$db->delete($tpref."customers_dealers","WHERE ID_CUSTOMER	='".$id_cust."'");
			$db->delete($tpref."customers_messages","WHERE ID_CUSTOMER	='".$id_cust."'");
		}
		$db->delete($tpref."customers","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."customers_payment_history","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."customers_purchases","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."customers_purchases_detail","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."discoin_configs","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."discoin_activation_codes","WHERE ACTIVATE_BY_ID_CLIENT`='".$no."'");
		
		$q_document = $db->query("SELECT ID_DOCUMENT,ID_USER,FILE_DOCUMENT 
								  FROM ".$tpref."documents 
								  WHERE ID_CLIENT='".$no."'");
		while($dt_document = $db->fetchNextObject($q_document)){
			$id_doc_user = $dt_document->ID_USER;
			if(is_file($basepath."/files/documents/users/".$id_doc_user."/".$dt_document->FILE_DOCUMENT)){
				unlink($basepath."/files/documents/users/".$id_doc_user."/".$dt_document->FILE_DOCUMENT);	
			}
			if(is_dir($basepath."/files/documents/users/".$id_doc_user)){
				rmdir($basepath."/files/documents/users/".$id_doc_user);	
			}
		}
		$db->delete($tpref."documents","WHERE ID_CLIENT='".$no."'");
		
		$q_product = $db->query("SELECT 
									a.ID_PRODUCT,
									a.ID_CLIENT,
									b.PHOTOS
								 FROM 
								 	".$tpref."products a,
									".$tpref."products_photos b 
								 WHERE 
								 	a.ID_CLIENT='".$no."' AND
									a.ID_PRODUCT = b.ID_PRODUCT");
		while($dt_product = $db->fetchNextObject($q_product)){
			$id_product = $dt_product->ID_PRODUCT;
			if(is_file($basepath."/files/images/products/".$no."/".$dt_customer->PHOTOS)){
				unlink($basepath."/files/images/products/".$no."/".$dt_customer->PHOTOS);	
			}
			if(is_file($basepath."/files/images/products/".$no."/thumbnails/".$dt_customer->PHOTOS)){
				unlink($basepath."/files/images/products/".$no."/thumbnails/".$dt_customer->PHOTOS);	
			}
			if(is_dir($basepath."/files/images/products/".$no."/thumbnails")){
				rmdir($basepath."/files/images/products/".$no."/thumbnails");	
			}
			if(is_dir($basepath."/files/images/products/".$no)){
				rmdir($basepath."/files/images/products/".$no);	
			}
			$db->delete($tpref."products_photos","WHERE ID_PRODUCT='".$id_product."'");
		}
		$db->delete($tpref."products","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."products_categories","WHERE ID_CLIENT='".$no."'");
		$db->delete($tpref."products_ranks","WHERE ID_CLIENT='".$no."'");
		$db->delete("system_pages_client_rightaccess","WHERE ID_CLIENT='".$no."'");
		
		$q_user = $db->query("SELECT ID_USER,USER_PHOTO FROM system_users_client WHERE ID_CLIENT='".$no."'");
		while($dt_user = $db->fetchNextObject($q_user)){
			if(is_file($basepath."/files/images/users/".$dt_user->USER_PHOTO)){
				unlink($basepath."/files/images/users/".$dt_user->USER_PHOTO);	
			}	
			if(is_file($basepath."/files/images/users/big/".$dt_user->USER_PHOTO)){
				unlink($basepath."/files/images/users/big/".$dt_user->USER_PHOTO);	
			}			
		}
		$db->delete("system_users_client","WHERE ID_CLIENT='".$no."'");
		
	}
	if(!empty($direction) && $direction == "check_app"){
		$app_name		= $sanitize->str(strtolower(str_replace(" ","",$client_name)));
		if($form_direction == "insert"){
			$count_app		= $db->recount("SELECT CLIENT_APP FROM ".$tpref."clients WHERE CLIENT_APP='".$app_name."'");
		}else{
			$count_app		= $db->recount("SELECT CLIENT_APP FROM ".$tpref."clients WHERE CLIENT_APP='".$app_name."' AND ID_CLIENT != '".$no."'");
		}
		
		if($count_app > 0){
			$result["nama_app"] = $app_name."".md5(substr($app_name,0,3));;
		}else{
			$result["nama_app"] = $app_name;
		}
		echo json_encode($result);
	}
}
else{  defined('mainload') or die('Restricted Access'); }
?>
