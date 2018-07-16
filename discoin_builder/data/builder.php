<?php
session_start();
ini_set("max_execution_time",6000000);
ini_set('memory_limit','900M');
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once('../../includes/config.php');
include_once('../../includes/classes.php');
include_once('../../includes/functions.php');
?>
<style>
	body{ 
		font-size:11px; 
		font-family:"Century Gothic";
	}
</style>
<?php
$old_name 			= "Angelicious";
$id_user  			= $_SESSION['uidkey'];
$icon				= $_FILES['icon']["name"];
$app_name 			= $_REQUEST['app_name'];

//PROPERTIES//
$old_string			= $sanitize->str(strtolower(str_replace(" ","",$old_name)));
$new_string			= $sanitize->str(strtolower(str_replace(" ","",$app_name)));

$discoin_old_string	= "sempoa.discoin.".$old_string;
$discoin_new_string	= "sempoa.discoin.".$new_string;

$discoin_folder 	= $new_string."/platforms/android/res/";
$basepath 	 		= $basepath."/discoin_builder/data/".$new_string;
//END OF PROPERTIES//

function unzip(){
	global $new_string;
	$zip = new ZipArchive;
	$res = $zip->open('sources.zip');
	if ($res === TRUE) {
		$result = 2;
		$zip->extractTo($new_string);
		$zip->close();
	} else {
		$result = 1;
	}
	return $result;
}
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
}
function create_icon(){
	global $id_user;
	global $file_id;
	global $discoin_folder;
	global $new_string;
	if(!empty($_FILES['icon']['name'])){
		$icon						= $_FILES['icon']["name"];
		$info	   					= pathinfo($_FILES['icon']["name"]); 
		$type 	   					= $info['extension'];
		if($type == "png" || $type == "PNG"){ 
			$icon_name 				= $file_id."-".$icon;			
			$src					= $discoin_folder.$icon_name;
			move_uploaded_file($_FILES['icon']['tmp_name'],$src);	

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
				if(!is_dir($discoin_folder."".$folder[$t])){
					mkdir($discoin_folder."".$folder[$t],0755); 
				}
				$icon_folder = $discoin_folder."".$folder[$t];
				$nama_file	 = "icon.png";
				
				if(is_file($discoin_folder."".$folder[$t]."/".$nama_file)){
					unlink($discoin_folder."".$folder[$t]."/".$nama_file);
				}
				resizeupload($src,$icon_folder,$size[$t],$nama_file);
			}
			unlink($src);
			$done = 1;
			
		}else{ 
			$done = 3; 
			//echo '<div class="alert alert-danger">Format file icon, haruslah png</div>'; 
		}
	}else{
		$done = 4;	
	}
	//change_dir();
	return $done;
}


function count_file($dir){
	global $count;
	if($dh = opendir($dir)) {
        while(($file = readdir($dh)) !== false) {
            if($file != "." && $file != ".."){ $count++;
				$new_path = $dir."/".$file;
				if(is_dir($new_path)){
					count_file($new_path);
				}
			}
        }
        closedir($dh);
    }
	return $count;
}


function read_dir($r,$dir){
	global $old_name;
	global $app_name;

	global $old_string;
	global $new_string;

	global $discoin_old_string;
	global $discoin_new_string;
	
	global $progress;
	global $jumlah_file;
	
	$r 			= $r+1;
	$new_path	= "";
	$mleft		= "";
	if($dh = opendir($dir)) { $mleft = 9*$r."px";
        while(($file = readdir($dh)) !== false) {
            if($file != "." && $file != ".." && $file != ".gradle"){ $progress++;
				$percent = round(($progress/$jumlah_file)*100);
				$new_path = $dir."/".$file;
				if(is_file($new_path)){
					echo "<div style='margin-left:".@$mleft."'>&nbsp;&nbsp;".$file."</div>";
					echo '<script>parent.set_bar('.$percent.')</script>';
					replace_string_in_file($new_path, $discoin_old_string, $discoin_new_string);
					replace_string_in_file($new_path, "\\".$old_string,"\\".$new_string);
					replace_string_in_file($new_path, $old_name, $app_name);
				}
				if(is_dir($new_path)){
					read_dir($r,$new_path);
				}
			}
        }
        closedir($dh);
    }
}
function replace_string_in_file($filename, $old_string, $new_string){
    $content		= file_get_contents($filename);
    $content_chunks	= explode($old_string, $content);
    $content		= implode($new_string, $content_chunks);
    file_put_contents($filename, $content);
}


if(!empty($_REQUEST['build'])){
	$unzip = unzip();
	
	echo "<script>parent.first_status()</script>";
	$count 		 = 0;
	$progress  	 = 0; 
	$r 			 = 1;
	if($unzip == 2){
		/*echo "<script>parent.builder_finish()</script>";*/
		$jumlah_file = count_file($basepath)+2;
		if($jumlah_file > 0){
			replace_string_in_file($basepath."/platforms/android/build.gradle",$old_string, $new_string);
			echo "<br>Menghitung file dan folder<br>";
			if (is_dir($basepath)) {
				if(!is_dir($basepath."/platforms/android/src/sempoa/discoin/".$new_string)){
					rename($basepath."/platforms/android/src/sempoa/discoin/".$old_string,
						   $basepath."/platforms/android/src/sempoa/discoin/".$new_string);
				}
						  
				echo "Mengubah java package<br>";
				
				
				if($dh = opendir($basepath)) {
					while(($file = readdir($dh)) !== false) {
						$new_path	= "";
						if($file != "." && $file != ".." && $file != ".gradle"){  $progress++;
							$percent 	= round(($progress/$jumlah_file)*100);
							$new_path 	= $basepath."/".$file;
							if(is_file($new_path)){
								echo "&nbsp;&nbsp;".$file."<br />";
								echo '<script>parent.set_bar('.$percent.')</script>';
								replace_string_in_file($new_path, $discoin_old_string, $discoin_new_string);
								replace_string_in_file($new_path, "\\".$old_string."\\","\\".$new_string."\\");
								replace_string_in_file($new_path, $old_name, $app_name);
							}
							if(is_dir($new_path)){
								read_dir($r,$new_path);
							}
						}
					}
					$build = 2;
					closedir($dh);
				}
				if($build == 2){
					$done = create_icon();
					echo "Membuat Icon<br>";
					
					if($done == 1){
						echo "<br>Membangun Aplikasi Discoin ".$app_name."<br>";
						echo getcwd() . "\n";
						chdir('d:/xampp/htdocs/sempoa.community/discoin_builder/data/'.$new_string);
						echo getcwd() . "\n";
						
						echo '<pre>';
							$last_line = system('cordova build android', $retval)."<br>";
						echo '
						</pre>
						<hr />Last line of the output: ' . $last_line . '
						<hr />Return value: ' . $retval;
						echo '<script>parent.set_bar(100)</script>';
					}
				}
			}
			
		}
	}
}
?>
