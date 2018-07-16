<?php defined('mainload') or die('Restricted Access'); ?>
<?php
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

if(!empty($direction) && $direction == "save_icon"){
	$done			= "";
	$q_logos		= $db->query("SELECT CLIENT_APP,CLIENT_LOGO_LABEL FROM ".$tpref."clients 
								  WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
	$dt_logos		= $db->fetchNextObject($q_logos);
	$app 			= $dt_logos->CLIENT_APP;
	$photori 		= $dt_logos->CLIENT_LOGO_LABEL;
	if(!empty($_FILES['icon']['name'])){
		$icon						= $_FILES['icon']["name"];
		$info	   					= pathinfo($_FILES['icon']["name"]); 
		$type 	   					= $info['extension'];
		if($type == "png" || $type == "PNG"){ 
			$icon_name 				= $file_id."-".$icon;			
			$client_dir 			= $_SESSION['cidkey']."-".$app;
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
			
		}else{ $msg = 3; }
	}
	else{ 
		$done = 1;
	}
	
	if($done == 1){
		$container = array(1=>array("CLIENT_LOGO_LABEL",@$icon_name));
		$db->update($tpref."clients",$container," WHERE ID_CLIENT='".$_SESSION['cidkey']."' ");
		redirect_page($lparam."&msg=1");
	}
}

?>