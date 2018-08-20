<?php
if(!defined('mainload')) { define('mainload','Master Web Card',true); }
include_once('../../includes/config.php');
include_once('../../includes/classes.php');
include_once('../../includes/functions.php');
include_once('../../includes/declarations.php');
function resizeupload($src,$dest,$new_width){
	error_reporting(0);
	global $file_id;
	$info 			= pathinfo($src); 
	$nama_file 		= $info['basename'];
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
		echo "Tipe file yang diijinkan : GIF, JPG, JPEG, PNG<br>";
	}
}

if(!empty($_REQUEST['proses']) ){
	$proses 	= $_REQUEST['proses'];
	$id_project = trim($_REQUEST['id_project']);
	$project 	= trim($_REQUEST['project']);
	$app 		= trim($_REQUEST['app']);
	//PROJECT
	if($proses == "create"){
		$new_project  = str_replace(" ","",$project."COIN");
		if(!is_dir($new_project)){ mkdir($new_project,0777); }
		include("~core/android/index.php");	
		include("~core/blackberry/index.php");	
	}
	//unlink("ic_launcher.png");
}
?>

<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="">
  ID Client & Nama Proyek &amp; Nama Aplikasi<br />
  <input type='text' name='id_project' value='<?php echo @$id_project; ?>' style='width:60px'/>
  <input type='text' name='project' value='<?php echo @$project; ?>' />
  <input type='text' name='app' value='<?php echo @$app; ?>' />
  <br />
  ICON<br />
  <input type="file" name="icon"/>
  <br />
  <br />
  <button type="submit" name="proses" value="create">Create Icon</button>
  <button type="submit" name="proses" value="delete">Delete Icon</button>
</form>
