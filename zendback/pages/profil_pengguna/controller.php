<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$email 		= isset($_REQUEST['email']) 	? 	$sanitize->str(strtolower($_REQUEST['email']))	:""; 			
$password 	= isset($_REQUEST['password']) 	? 	$sanitize->str($_REQUEST['password'])			:""; 		
$name 		= isset($_REQUEST['name']) 		? 	$sanitize->str(ucwords($_REQUEST['name']))		:""; 			
$phone 		= isset($_REQUEST['phone']) 	? 	$sanitize->number($_REQUEST['phone'])			:""; 	

$alamat 	= isset($_REQUEST['alamat']) 	? 	$sanitize->str(ucwords($_REQUEST['alamat']))	:""; 			
$propinsi 	= isset($_REQUEST['propinsi']) 	? 	$sanitize->number($_REQUEST['propinsi'])		:"0"; 	
$kota 		= isset($_REQUEST['kota']) 		? 	$sanitize->number($_REQUEST['kota'])			:"0";			
$kecamatan 	= isset($_REQUEST['kecamatan']) ? 	$sanitize->number($_REQUEST['kecamatan'])		:"0"; 	
$kelurahan 	= isset($_REQUEST['kelurahan']) ? 	$sanitize->number($_REQUEST['kelurahan'])		:"0"; 	

		
if(!empty($_FILES['photo']['name'])){	$photo 		= $_FILES['photo']['name']; }
if(!empty($direction) && $direction == "save"){
	
	if(!empty($name) && !empty($email) && !empty($phone) && !empty($alamat) && !empty($propinsi)){	
		$ch_username 	= $db->recount("SELECT * FROM system_users_client WHERE USER_EMAIL = '".$email."'");	
		
		if(($ch_username == 0) || ($ch_username > 0 && $email == $_SESSION['username'])){
			$q_ori		= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$_SESSION['uidkey']."'");
			$dt_ori		= $db->fetchNextObject($q_ori);
			$photori 	= $dt_ori->USER_PHOTO;
			$passori	= $dt_ori->USER_PASS;
			if(empty($password)){
				$password = $passori;	
			}
			if(!empty($photo)){
				unlink($basepath."/files/images/users/".$photori);
				unlink($basepath."/files/images/users/big/".$photori);
				$filename = $file_id."-".$photo;
				$img 		= getimagesize($_FILES['photo']['tmp_name']);
				$img_width	= $img[0];
				move_uploaded_file($_FILES['photo']['tmp_name'],$basepath."/files/images/users/".$filename);
				copy($basepath."/files/images/users/".$filename,$basepath."/files/images/users/big/".$filename);
				if($img_width > 400){
					$cupload->resizeupload($basepath."/files/images/users/big/".$filename,$basepath."/files/images/users/big",400,$prefix = false);
				}
				$cupload->resizeupload($basepath."/files/images/users/".$filename,$basepath."/files/images/users",70,$prefix = false);
			}
			else{ $filename = $photori; }
			$container = array(1=>
							array("USER_NAME",@$name),
							array("USER_PASS",@$password),
							array("USER_PHOTO",@$filename),
							array("USER_EMAIL",@$email),
							array("USER_PHONE",@$phone),
							array("USER_ADDRESS",@$alamat),
							array("USER_PROVINCE",@$propinsi),
							array("USER_CITY",@$kota),
							array("USER_DISTRICT",@$kecamatan),
							array("USER_SUBDISTRICT",@$kelurahan));
			$db->update("system_users_client",$container," WHERE ID_USER='".$_SESSION['uidkey']."' ");
			
			if(!is_dir($basepath."/files/documents/users/".$_SESSION['uidkey'])){
				mkdir($basepath."/files/documents/users/".$_SESSION['uidkey'],"0777");
			}
			$r =0;
			
			if(count($_FILES['document']['tmp_name']) > 0){
				foreach($_FILES['document']['tmp_name'] as $key => $tmp_name){
					$r++;
					$document	= "";
					$file_tmp 	= "";
					
					$judul		= "Pengguna ".$_SESSION['uidkey']." ".$name." Dokumen";
					$document 	= $file_id."-".$_FILES['document']['name'][$key];
					if(!empty($_FILES['document']['name'][$key])){
					$file_tmp 	= $_FILES['document']['tmp_name'][$key];
					$file_size 	= $_FILES['document']['size'][$key];
					move_uploaded_file($file_tmp,$basepath."/files/documents/users/".$_SESSION['uidkey']."/".$document);
					$file_content = array(1=>
									  array("ID_CLIENT",$_SESSION['cidkey']),
									  array("TITLE_DOCUMENT",@$judul),
									  array("FILE_DOCUMENT",$document),
									  array("ID_USER",$_SESSION['uidkey']),
									  array("BY_ID_USER",$_SESSION['uidkey']),
									  array("TGLUPDATE",@$tglupdate),
									  array("WKTUPDATE",@$wktupdate));
					$db->insert($tpref."documents",$file_content);
					}
				}
			}
			redirect_page($lparam."&msg=1");
			
		}else{
			$msg = 3;	
		}
	}else{
		$msg = 2;	
	}
}

?>