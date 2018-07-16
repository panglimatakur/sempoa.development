<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$parent_id 			= isset($_REQUEST['parent_id']) 	? $sanitize->number($_REQUEST['parent_id']):"0";
$name 				= isset($_REQUEST['name']) 			? $sanitize->str(ucwords($_REQUEST['name'])):"";			
$user_pass 			= isset($_REQUEST['user_pass']) 	? $sanitize->str($_REQUEST['user_pass']):""; 				
$user_email 		= isset($_REQUEST['user_email']) 	? $sanitize->email(strtolower($_REQUEST['user_email'])):"";
$add_info 			= isset($_REQUEST['add_info']) 		? $sanitize->str($_REQUEST['add_info']):"";				
$level 				= isset($_REQUEST['level']) 		? $sanitize->number($_REQUEST['level']):""; 				
$insert_proses 		= isset($_REQUEST['insert_proses']) ? $sanitize->number($_REQUEST['insert_proses']):""; 		
$edit_proses 		= isset($_REQUEST['edit_proses']) 	? $sanitize->number($_REQUEST['edit_proses']):""; 			
$delete_proses 		= isset($_REQUEST['delete_proses']) ? $sanitize->number($_REQUEST['delete_proses']):""; 		

if(!empty($_FILES['photo']['tmp_name'])) 	{ $photo 			= $_FILES['photo']['name']; 							}


$id_client_form 	= isset($_REQUEST['id_client_form']) ? $sanitize->str($_REQUEST['id_client_form']):""; 


if(($_SESSION['cidkey'] != 2 || $_SESSION['cparentkey'] != 2)){
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($parent_id) &&  !empty($name) && !empty($user_pass)  && !empty($user_email) && !empty($level)){
			
			@$id_client_level	= $db->fob("ID_CLIENT_LEVEL",$tpref."clients"," WHERE ID_CLIENT='".$parent_id."'");
		
			if(!empty($direction) && $direction == "insert"){ 
				
				$ch_email = $db->recount("SELECT USER_EMAIL FROM system_users_client WHERE USER_EMAIL = '".$user_email."'");
				if($ch_email == 0){
					if(!empty($photo)){
						$dest			= $basepath."/files/images/users/";
						$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
						$filename		= $file_id."-".$_FILES['photo']['name'];
						$src			= array($_FILES['photo']['tmp_name'],$filename);
						$dest_thumb		= $basepath."/files/images/users/big";
						$new_width		= '300';
						
						$cupload->upload($src,$dest,$extensions);
						$cupload->resizeupload($dest."/".$filename,$dest_thumb,$new_width);
					}else{
						$filename = "";
					}
					
					$container = array(1=>
									array("ID_CLIENT",@$parent_id),
									array("USER_NAME",@$name),
									array("USER_PASS",@$user_pass),
									array("USER_PHOTO",@$filename),
									array("USER_EMAIL",@$user_email),
									array("ID_CLIENT_LEVEL",@$id_client_level),
									array("ID_CLIENT_USER_LEVEL",@$level),
									array("ADDITIONAL_INFO",$add_info),
									array("INSERT_DATA",@$insert_proses),
									array("EDIT_DATA",@$edit_proses),
									array("DELETE_DATA",@$delete_proses));
						$db->insert("system_users_client",$container);
						$no		= mysql_insert_id();
						$done 	= 1;
				}else{
					$msg = 3;
				}
			}			
			
			if(!empty($direction) && $direction == "save"){ 
			
				$photori = $db->fob("USER_PHOTO","system_users_client","WHERE ID_USER='".$no."'");
				if(!empty($photo)){
					unlink($basepath."/files/images/users/".$photori);
					
					$dest			= $basepath."/files/images/users/";
					$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
					$filename		= $file_id."-".$_FILES['photo']['name'];
					$src			= array($_FILES['photo']['tmp_name'],$filename);
					$dest_thumb		= $basepath."/files/images/users/big";
					$new_width		= '300';
					
					$cupload->upload($src,$dest,$extensions);
					$cupload->resizeupload($dest."/".$filename,$dest_thumb,$new_width);
				}
				else{ $filename = $photori; }
				$container = array(1=>
								array("ID_CLIENT",@$parent_id),
								array("USER_NAME",@$name),
								array("USER_PASS",@$user_pass),
								array("USER_PHOTO",@$filename),
								array("USER_EMAIL",@$user_email),
								array("ID_CLIENT_LEVEL",$id_client_level),
								array("ID_CLIENT_USER_LEVEL",@$level),
								array("ADDITIONAL_INFO",$add_info),
								array("INSERT_DATA",@$insert_proses),
								array("EDIT_DATA",@$edit_proses),
								array("DELETE_DATA",@$delete_proses));
				$db->update("system_users_client",$container," WHERE ID_USER='".$no."' ");
				$done = 1;
			}
			
			if($done == 1){
				
				if(!is_dir($basepath."/files/documents/users/".$no)){
					mkdir($basepath."/files/documents/users/".$no,"0777");
				}
				if(count($_FILES['document']['tmp_name']) > 0){
					foreach($_FILES['document']['tmp_name'] as $key => $tmp_name){
						$document	= "";
						$file_tmp 	= "";
						$judul		= "Pengguna ".$_SESSION['uidkey']." ".$name." Dokumen";
						
						$document 	= $file_id."-".$_FILES['document']['name'][$key];
						if(!empty($_FILES['document']['name'][$key])){
							$file_tmp 		= $_FILES['document']['tmp_name'][$key];
							$file_size 		= $_FILES['document']['size'][$key];
							move_uploaded_file($file_tmp,$basepath."/files/documents/users/".$no."/".$document);
							$file_content 	= array(1=>
											  array("ID_CLIENT",$parent_id),
											  array("TITLE_DOCUMENT",@$judul),
											  array("FILE_DOCUMENT",$document),
											  array("ID_USER",$no),
											  array("BY_ID_USER",$_SESSION['uidkey']),
											  array("TGLUPDATE",@$tglupdate),
											  array("WKTUPDATE",@$wktupdate));
							$db->insert($tpref."documents",$file_content);
						}
					}
				}
				
				redirect_page($lparam."&msg=1");
			}
		}else{
			$msg = 2;
		}
	}
}else{
	echo msg("Maaf Anda Tidak Bisa Merubah dan Menambah Data DEMO ini, ","error");	
}
		
?>