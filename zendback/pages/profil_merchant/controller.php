<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(!empty($_FILES['photo']['tmp_name'])) 	{ $photo 		= $_FILES['photo']['name']; 								}
if(!empty($_REQUEST['nama'])) 				{ $nama 		= $sanitize->str(strtoupper($_REQUEST['nama']));			}
if(!empty($_REQUEST['propinsi']))			{ $propinsi 	= $sanitize->str($_REQUEST['propinsi']); 					}
if(!empty($_REQUEST['kota']))				{ $kota 		= $sanitize->str($_REQUEST['kota']); 						}
if(!empty($_REQUEST['alamat']))				{ $alamat 		= $sanitize->str(ucwords($_REQUEST['alamat'])); 			}
if(!empty($_REQUEST['tlp']))				{ $tlp 			= $sanitize->number($_REQUEST['tlp']); 						}
if(!empty($_REQUEST['email']))				{ $email 		= $sanitize->email($_REQUEST['email']); 					}
if(!empty($_REQUEST['website']))			{ $website 		= $sanitize->url(strtolower($_REQUEST['website'])); 		}
if(!empty($_REQUEST['app']))				{ $app 			= $sanitize->str($_REQUEST['app']); 						}
if(!empty($_REQUEST['w'][1]))				{ $w[1] 		= $_REQUEST['w'][1]; 					}
if(!empty($_REQUEST['w'][2]))				{ $w[2]			= $_REQUEST['w'][2]; 					}


if(!empty($_REQUEST['deskripsi']))			{ $deskripsi 	= $_REQUEST['deskripsi']; 									}
if(!empty($_REQUEST['meta_title']))			{ $meta_title 	= $sanitize->str($_REQUEST['meta_title']); 					}
if(!empty($_REQUEST['meta_description']))	{ $meta_description = $sanitize->str($_REQUEST['meta_description']); 		}
if(!empty($_REQUEST['meta_keywords']))		{ $meta_keywords 	= $sanitize->str($_REQUEST['meta_keywords']); 			}

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
		echo "<div class='alert alert-danger'>Tipe file yang diijinkan : GIF, JPG, JPEG, PNG</div>";
	}
}
if(!empty($direction) && $direction == "save"){

	
	if(!empty($nama) && !empty($propinsi) && !empty($kota) && !empty($alamat) && !empty($tlp)){
		$done	= "";
		$q_logos		= $db->query("SELECT CLIENT_LOGO FROM ".$tpref."clients 
									  WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
		$dt_logos		= $db->fetchNextObject($q_logos);
		$photori 		= $dt_logos->CLIENT_LOGO;
		
		if(!empty($_FILES['photo']['name'])){
			$info	   					= pathinfo($_FILES['photo']["name"]); 
			$type 	   					= $info['extension'];
			if($type == "png" || $type == "PNG" || $type == "jpg" || $type == "JPG" || 
			   $type == "gif" || $type == "GIF"){ 
				unlink($basepath."/files/images/logos/".$photori);
				$filename = $file_id."-".$photo;
				move_uploaded_file($_FILES['photo']['tmp_name'],$basepath."/files/images/logos/".$filename);
				$done = 1;
			}else{
				$msg = 3;
			}
		}
		else{ 
			$filename = $photori; 
			$done = 1;
		}
		if($done == 1){
			$r = 0;
			$container = array(1=>
				array("CLIENT_NAME",@$nama),
				array("CLIENT_LOGO",@$filename),
				array("CLIENT_URL",@$website),
				array("CLIENT_EMAIL",@$email),
				array("CLIENT_PHONE",@$tlp),
				array("CLIENT_ADDRESS",addslashes(@$alamat)),
				array("CLIENT_PROVINCE",@$propinsi),
				array("CLIENT_CITY",@$kota),
				array("CLIENT_APP",@$app),
				array("COLOUR",@$w[1].";".@$w[2]),
				array("CLIENT_DESCRIPTIONS",addslashes(@$deskripsi)),
				array("META_TITLE",addslashes(@$meta_title)),
				array("META_KEYWORDS",addslashes(@$meta_keywords)),
				array("META_DESCRIPTION	",addslashes(@$meta_description)),			
				array("BY_ID_USER",$_SESSION['uidkey']),
				array("TGLUPDATE",$tglupdate));
			$db->update($tpref."clients",$container," WHERE ID_CLIENT='".$_SESSION['cidkey']."' ");
			redirect_page($lparam."&msg=1");
		}
	}else{
		$msg = 2;
	}
}
?>
