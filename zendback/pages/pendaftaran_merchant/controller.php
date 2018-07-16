<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$id_client_level 	= isset($_REQUEST['id_client_level']) 	? $sanitize->number($_REQUEST['id_client_level'])	:"0";
$parent_id 			= isset($_REQUEST['parent_id']) 		? $sanitize->number($_REQUEST['parent_id'])			:"0";	
if(!empty($_FILES['photo']['tmp_name'])) 		{ 
	$photo 			= $_FILES['photo']['name']; 						
}
$nama 				= isset($_REQUEST['nama']) 				? $sanitize->str(strtoupper($_REQUEST['nama']))		:"";	
$propinsi 			= isset($_REQUEST['propinsi']) 			? $sanitize->str($_REQUEST['propinsi'])				:"0"; 			
$kota 				= isset($_REQUEST['kota']) 				? $sanitize->str($_REQUEST['kota'])					:"0"; 				
$alamat 			= isset($_REQUEST['alamat']) 			? $sanitize->str(ucwords($_REQUEST['alamat']))		:""; 	
$tlp 				= isset($_REQUEST['tlp']) 				? $sanitize->str($_REQUEST['tlp'])					:""; 				
$nmkontak 			= isset($_REQUEST['nmkontak']) 			? $sanitize->str($_REQUEST['nmkontak'])				:""; 			
$kontak 			= isset($_REQUEST['kontak']) 			? $sanitize->str($_REQUEST['kontak'])				:""; 				
$email 				= isset($_REQUEST['email']) 			? $sanitize->email($_REQUEST['email'])				:""; 			
$website 			= isset($_REQUEST['website']) 			? $sanitize->url(strtolower($_REQUEST['website']))	:""; 
$deskripsi 			= isset($_REQUEST['deskripsi']) 		? $_REQUEST['deskripsi']							:""; 							
$ch_ref 			= isset($_REQUEST['ch_ref']) 			? $_REQUEST['ch_ref']								:"0"; 								
$statement 			= isset($_REQUEST['statement']) 		? $_REQUEST['statement']							:""; 							
						
$w[1] 				= isset($_REQUEST['w'][1]) 				? $_REQUEST['w'][1]									:""; 								
$w[2] 				= isset($_REQUEST['w'][2]) 				? $_REQUEST['w'][2]									:""; 									
@$colour			= str_replace(" ","",$w[1].";".$w[2]);

$purple 			= isset($_REQUEST['purple']) 			? $sanitize->number($_REQUEST['purple'])			:"0"; 
if(empty($purple))	{ $purple = 1; } 

$app 				= isset($_REQUEST['app']) 				? $sanitize->str($_REQUEST['app'])					:"";
$app 				= strtolower(str_replace(" ","",$app)); 								

function resizeupload($src,$dest,$new_width,$nama_file){
	error_reporting(0);
	global $file_id;
	$info 			= pathinfo($src); 
	$file_type 		= $info['extension'];
	
	if($file_type == "gif" || $file_type == "GIF")												{ $tipe = 1; 	}
	if($file_type == "jpg" || $file_type == "jpeg" || $file_type == "JPG" || $type == "JPEG")	{ $tipe = 2; 	}
	if($file_type == "png" || $file_type == "PNG")												{ $tipe = 3; 	}
	
	list($old_width, $old_height, $type, $attr) = getimagesize($src);
	if (trim($tipe) == 1 || trim($tipe) == 2 || trim($tipe) == 3){
			if ($tipe == 1) { $new_image = imagecreatefromgif($src);  } 
			if ($tipe == 2) { $new_image = imagecreatefromjpeg($src); } 
			if ($tipe == 3) { $new_image = imagecreatefrompng($src);  }
			
			$percentage 	= ($new_width / $old_width); 	
			$new_width 		= round($old_width * $percentage);
			$new_height 	= round($old_height * $percentage);
				
			if (function_exists(imagecreatetruecolor)){ 
				$resized_img = imagecreatetruecolor($new_width,$new_height); }
			else{  echo "Error: Pastikan GD library ver 2+ Terinstal Di Server Anda"; 	}
			
			if($tipe == 1 || $tipe == 3){
				imagecolortransparent($resized_img, imagecolorallocatealpha($resized_img, 0, 0, 0, 127));
				imagealphablending($resized_img, false);
				imagesavealpha($resized_img, true);
			}
			imagecopyresampled($resized_img,$new_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);	
			if ($tipe == 1) 		{ imagegif($resized_img,$dest."/".$nama_file);		} 
			if ($tipe == 2) 		{ imagejpeg($resized_img,$dest."/".$nama_file); 	} 
			if ($tipe == 3) 		{ imagepng($resized_img,$dest."/".$nama_file,9);  	}		
			ImageDestroy($resized_img);
			ImageDestroy($new_image);	
	} 
	else {
		echo "<div class='alert alert-danger'>Tipe file yang diijinkan adalah PNG</div>";
	}
}

	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama) && !empty($propinsi) && !empty($kota) && !empty($alamat) && !empty($tlp) && !empty($app) && !empty($w[1]) && !empty($w[2])){
			$done = 0;
			if(!empty($direction) && $direction == "insert"){ 
				$ch_email = $db->recount("SELECT CLIENT_EMAIL FROM ".$tpref."clients WHERE CLIENT_EMAIL = '".$email."'");
				if($ch_email == 0){
					if(!empty($parent_id)){
						@$parent_id_list = $db->fob("CLIENT_ID_PARENT_LIST",$tpref."clients"," 
													 WHERE ID_CLIENT='".$parent_id."'");
					}
					
					if(!empty($_FILES['icon']['name'])){
						$icon						= $_FILES['icon']["name"];
						$info	   					= pathinfo($_FILES['icon']["name"]); 
						$type 	   					= $info['extension'];
						if($type == "png" || $type == "PNG"){ 
							$icon_name 				= $file_id."-".$icon;
							$last_id 				= $db->last("ID_CLIENT",$tpref."clients","");
							$client_dir 			= $last_id."-".$app;
							$src					= $discoin_folder.$icon_name;
							move_uploaded_file($_FILES['icon']['tmp_name'],$src);	
				
							if(!is_dir($discoin_folder.$client_dir)){
								mkdir($discoin_folder.$client_dir,0755); 
							}
							if(!is_dir($discoin_folder.$client_dir."/res")){
								mkdir($discoin_folder.$client_dir."/res",0755); 
							}
													
							$t = 0;
							while($t<6){
								$t++;
								if(!is_dir($discoin_folder.$client_dir."/res/".$folder[$t])){
									mkdir($discoin_folder.$client_dir."/res/".$folder[$t],0755); 
								}
								$icon_folder = $discoin_folder.$client_dir."/res/".$folder[$t];
								$nama_file	 = "icon.png";
								
								if(is_file($discoin_folder.$client_dir."/res/".$folder[$t]."/".$nama_file)){
									unlink($discoin_folder.$client_dir."/res/".$folder[$t]."/".$nama_file);
								}
								resizeupload($src,$icon_folder,$size[$t],$nama_file);
							}
							unlink($src);
							$done = 1;
							
						}else{ $msg = 5; }
					}
					else{ $done = 1; }
					
					if(!empty($_FILES['photo']['name'])){
						$info	   					= pathinfo($_FILES['photo']["name"]); 
						$type 	   					= $info['extension'];
						if($type == "png" || $type == "PNG" || $type == "jpg" || $type == "JPG" || 
						   $type == "gif" || $type == "GIF"){ 
						   unlink($basepath."/files/images/logos/".$photori);
						   $filename = $file_id."-".$photo;
						   move_uploaded_file($_FILES['photo']['tmp_name'],$basepath."/files/images/logos/".$filename);
						   $done = 1;
						}else{ $msg = "4"; }
					}
					
					if($done == 1){
					
						if(!empty($app)){
							if(!is_dir($basepath."/files/coin/".$app)){
								mkdir($basepath."/files/coin/".$app,0777);	
							}
						}
						if(!empty($photo)){
							$filename 		= $file_id."-".$photo;
							$src			= array($_FILES['photo']['tmp_name'],$filename);
							$new_width		= '200';
							$cupload->upload($src,$dest,$extensions);
							$cupload->resizeupload($dest."/".$filename,$dest_thumb,$new_width);
						}else{
							$filename = "";
						}
						$container = array(1=>
							array("CLIENT_NAME",@$nama),
							array("CLIENT_LOGO",@$filename),
							array("CLIENT_LOGO_LABEL",@$icon_name),
							array("CLIENT_URL",@$website),
							array("CLIENT_EMAIL",@$email),
							array("CLIENT_PHONE",@$tlp),
							array("CLIENT_PERSON_NAME",@$nmkontak),
							array("CLIENT_PERSON_CONTACT",@$kontak),
							array("CLIENT_ADDRESS",addslashes(@$alamat)),
							array("CLIENT_PROVINCE",@$propinsi),
							array("CLIENT_CITY",@$kota),
							array("CLIENT_ID_PARENT",@$parent_id),
							array("CLIENT_DESCRIPTIONS",addslashes(@$deskripsi)),
							array("CLIENT_STATEMENT",addslashes(@$statement)),
							array("CLIENT_ID_PARENT_LIST",@$parent_id_list),
							array("ID_CLIENT_LEVEL",@$id_client_level),
							array("COLOUR",@$colour),
							array("CLIENT_APP",@$app),
							array("BY_ID_PURPLE",@$purple),
							array("BY_ID_USER",$_SESSION['uidkey']),
							array("AFFILIATE_FLAG",@$ch_ref),
							array("TGLUPDATE",$tglupdate));
						$db->insert($tpref."clients",$container);
						$new_id		= mysql_insert_id();
						
						if(!empty($parent_id_list)){
							$parent_id_list = $parent_id_list.",".$new_id.",";
						}else{
							$parent_id_list = ",".$new_id.",";
						}
						
						$client_code 	= 	substr(md5($nama.$email.$new_id.$tglupdate),0,17);
						$container2 	= array(1=>
							array("CLIENT_COIN",@$client_code),
							array("CLIENT_ID_PARENT_LIST",$parent_id_list));
						$db->update($tpref."clients",$container2," WHERE ID_CLIENT='".$new_id."' ");
											
						redirect_page($lparam."&msg=1");
					}
					
				}else{ $msg = 3; }
			}
			
			if(!empty($direction) && $direction == "save"){ 
				if(!empty($parent_id)){
					$parent_id_list = $parent_id_list.",".$no.",";
				}
				$q_logos		= $db->query("SELECT CLIENT_LOGO,CLIENT_LOGO_LABEL,CLIENT_APP 
											  FROM ".$tpref."clients WHERE ID_CLIENT='".$no."'");
				$dt_logos		= $db->fetchNextObject($q_logos);
				$photori 		= $dt_logos->CLIENT_LOGO;
				$app_ori		= $dt_logos->CLIENT_APP; 
				
				if($app != $app_ori){
					if(!is_dir($basepath."/files/coin/".$app)){
						rename($basepath."/files/coin/".$app_ori,$basepath."/files/coin/".$app);	
					}
				}else{
					if(!is_dir($basepath."/files/coin/".$app)){
						mkdir($basepath."/files/coin/".$app,0777);	
					}
				}

				if(!empty($_FILES['icon']['name'])){
					$icon						= $_FILES['icon']["name"];
					$info	   					= pathinfo($_FILES['icon']["name"]); 
					$type 	   					= $info['extension'];
					if($type == "png" || $type == "PNG"){ 
						$icon_name 				= $file_id."-".$icon;
						
						$client_dir 			= $no."-".$app;
						$src					= $discoin_folder.$icon_name;
						move_uploaded_file($_FILES['icon']['tmp_name'],$src);	
			
						if(!is_dir($discoin_folder.$client_dir)){
							mkdir($discoin_folder.$client_dir,0755); 
						}
						if(!is_dir($discoin_folder.$client_dir."/res")){
							mkdir($discoin_folder.$client_dir."/res",0755); 
						}
					
						$folder	   = array();
						$size	   = array();
						$folder[1] = "mipmap-ldpi";
						$size[1]   = "36";
						$folder[2] = "mipmap-hdpi";
						$size[2]   = "72";
						$folder[3] = "mipmap-mdpi";
						$size[3]   = "48";
						$folder[4] = "mipmap-xhdpi";
						$size[4]   = "96";
						$folder[5] = "mipmap-xxhdpi";
						$size[5]   = "144";
						$folder[6] = "mipmap-xxxhdpi";
						$size[6]   = "192";
						
						$t = 0;
						while($t<6){
							$t++;
							if(!is_dir($discoin_folder.$client_dir."/res/".$folder[$t])){
								mkdir($discoin_folder.$client_dir."/res/".$folder[$t],0755); 
							}
							$icon_folder = $discoin_folder.$client_dir."/res/".$folder[$t];
							$nama_file	 = "icon.png";
							
							if(is_file($discoin_folder.$client_dir."/res/".$folder[$t]."/".$nama_file)){
								unlink($discoin_folder.$client_dir."/res/".$folder[$t]."/".$nama_file);
							}
							resizeupload($src,$icon_folder,$size[$t],$nama_file);
						}
						unlink($src);
						$done = 1;
					}else{ $msg = 5; }
				}
				else{ $done = 1; }
				
				if(!empty($_FILES['photo']['name'])){
					$info	   					= pathinfo($_FILES['photo']["name"]); 
					$type 	   					= $info['extension'];
					if($type == "png" || $type == "PNG" || $type == "jpg" || $type == "JPG" || 
					   $type == "gif" || $type == "GIF"){ 
						unlink($basepath."/files/images/logos/".$photori);
						$filename = $file_id."-".$photo;
						move_uploaded_file($_FILES['photo']['tmp_name'],$basepath."/files/images/logos/".$filename);
						$done = 1;
					}else{ $msg = "4"; }
				}
				else{ $filename = $photori; $done = 1; }
				
				if($done == 1){
					$container = array(1=>
						array("CLIENT_NAME",@$nama),
						array("CLIENT_LOGO",@$filename),
						array("CLIENT_URL",@$website),
						array("CLIENT_EMAIL",@$email),
						array("CLIENT_PHONE",@$tlp),
						array("CLIENT_PERSON_NAME",@$nmkontak),
						array("CLIENT_PERSON_CONTACT",@$kontak),
						array("CLIENT_ADDRESS",addslashes(@$alamat)),
						array("CLIENT_PROVINCE",@$propinsi),
						array("CLIENT_CITY",@$kota),
						array("CLIENT_ID_PARENT",@$parent_id),
						array("CLIENT_DESCRIPTIONS",addslashes(@$deskripsi)),
						array("CLIENT_STATEMENT",addslashes(@$statement)),
						array("CLIENT_ID_PARENT_LIST",@$parent_id_list),
						array("ID_CLIENT_LEVEL",$id_client_level),
						array("COLOUR",$colour),
						array("CLIENT_APP",$app),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("BY_ID_PURPLE",$purple),
						array("AFFILIATE_FLAG",@$ch_ref),
						array("TGLUPDATE",$tglupdate));
					$db->update($tpref."clients",$container," WHERE ID_CLIENT='".$no."' ");
					redirect_page($lparam."&msg=1");
				
				}
			}
			
		}else{
			$msg = 2;
		}
	}
?>
