<?php
ini_set('session.gc_maxlifetime', 30*60);
session_start();
/*(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['sidkey'])) || (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765" &&*/
if((!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../includes/config.php");
	include_once("../../includes/classes.php");
	include_once("../../includes/functions.php");
	include_once("../../includes/declarations.php");
	include $call->clas("class.fileanddirectory");
	
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	$id_merchant 	= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] 	: "";
	$id_customer	= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] 		: "";
	
	if(!empty($_FILES['file']['name'])){ $photo 	= $_FILES['file']['name']; 	}

	if(!empty($_SESSION['sidkey'])){
		if(!empty($direction) && $direction == "save_photo"){ 
			$q_user 	= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."'"); 
			$dt_user	= $db->fetchNextObject($q_user);
			@$photori 	= $dt_user->CUSTOMER_PHOTO;
			if(!empty($photo)){
				unlink($basepath."/files/images/members/".$photori);
				unlink($basepath."/files/images/members/big/".$photori);
				$filename = $file_id."-".str_replace("%3A","",$photo).".jpg";
				$img 		= getimagesize($_FILES['file']['tmp_name']);
				$img_width	= $img[0];
				move_uploaded_file($_FILES['file']['tmp_name'],$basepath."/files/images/members/".$filename);
				copy($basepath."/files/images/members/".$filename,$basepath."/files/images/members/big/".$filename);
				
				$cupload->resizeupload($basepath."/files/images/members/".$filename,$basepath."/files/images/members",300,$prefix = false);
				if($img_width > 500){
					$cupload->resizeupload($basepath."/files/images/members/big/".$filename,$basepath."/files/images/members/big/",500,$prefix = false);
				}
				
				$container = array(1=>array("CUSTOMER_PHOTO",@$filename));
				$db->update($tpref."customers",$container," WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."'");
				$result['cust_name']= $dt_user->CUSTOMER_NAME;
				$result['sidkey'] 	= $dt_user->ID_CUSTOMER;
				$result['csidkey'] 	= $dt_user->ID_CLIENT;
				$result["io"]			= "2";
			}else{
				$result["io"]			= "1";
			}
			$result['extension'] = $ext;
			$result['picture'] 	= $filename;
			//$response = json_encode($result);	
			echo json_encode($result);
		}
	}
}
?>