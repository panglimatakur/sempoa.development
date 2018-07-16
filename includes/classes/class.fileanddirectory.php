<?php defined('mainload') or die('Restricted Access'); ?>
<?php	
class directories{
	function mode($check,$mode){
		if(is_dir($check)){ chmod($check,$mode); }
	}
	
	function copydir($check,$destiny){
		$handle = opendir($check);
		while ($file = readdir($handle)){
			if(is_file($check."/".$file)){ copy($check."/".$file,$destiny."/".$file); unlink($check."/".$file); }
			
		}
		closedir($handle);
	}
	
	function makedir($check,$mode){
		if(!is_dir($check)){ mkdir($check,$mode); }
	}
	
	function removedir($check){
		if(is_dir($check)){ rmdir($check); }
	}
	
	function renamedir($check,$new){
		if(is_dir($check)){ rename($check,$new); }
	}
}

$cdir = new directories;

class files{
	public function chextension($file){
		if(!is_file($file)){ 
		$info = pathinfo($filename); 
		return $info['extenstion']; 
		}
	}
	public function copyfile($src,$check){
		if(!is_file($check)){ copy($src,$check); }
	}
	
	public function upfile($src,$check){
		move_uploaded_file($src,$check);
	}
	
	public function delfile($check){
		if(is_file($check)){ unlink($check); }
	}
	public function renamefile($check,$new){
		if(is_file($check)){ rename($check,$new); }
	}
}
$cfile = new files;

class uploads{
	private $extensions = array();
	protected $src;
	protected $dest;
	
	public function resizeupload($src,$dest,$new_width,$prefix = false){
		error_reporting(0);
		global $file_id;
		$info 			= pathinfo($src); 
		$nama_file 		= $info['basename'];
		$file_type 		= $info['extension'];

		if($file_type == "gif" || $file_type == "GIF")												{ $type = 1; 	}
		if($file_type == "jpg" || $file_type == "jpeg" || $file_type == "JPG" || $type == "JPEG")	{ $type = 2; 	}
		if($file_type == "png" || $file_type == "PNG")												{ $type = 3; 	}
		
		list($old_width, $old_height, $type, $attr) = getimagesize($src);
		if (trim($type) == 1 || trim($type) == 2 || trim($type) == 3){
				if ($type == 1) { $new_image = imagecreatefromgif($src);  } 
				if ($type == 2) { $new_image = imagecreatefromjpeg($src); } 
				if ($type == 3) { $new_image = imagecreatefrompng($src);  }
								
				if ($old_width > $old_height){
					  $percentage 	= ($new_width / $old_width); 	} 
				else{ $percentage 	= ($new_width / $old_height); 	}
			
				$new_width 			= round($old_width * $percentage);
				$new_height 		= round($old_height * $percentage);
				
				if (function_exists(imagecreatetruecolor)){ 
					   $resized_img = imagecreatetruecolor($new_width,$new_height); 								}
				else{  $alert 		= "Error: Pastikan GD library ver 2+ Terinstal Di Server Anda"; echo $alert; 	}
		
				imagecopyresampled($resized_img,$new_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
				if ($prefix == true)	{ $nama_file = $file_id."_".$nama_file;				}
				if ($type == 1) 		{ imagegif($resized_img,$dest."/".$nama_file);		} 
				if ($type == 2) 		{ imagejpeg($resized_img,$dest."/".$nama_file); 	} 
				if ($type == 3) 		{ imagepng($resized_img,$dest."/".$nama_file);  	}		
				ImageDestroy($resized_img);
				ImageDestroy($new_image);	
		} 
		else {
			$alert = "Tipe file yang diijinkan : GIF, JPG, JPEG, PNG<br>";
			
		}
	}
	//resizeupload($photo,"1",200,true);
	public function upload($src,$dest,$extensions,$prefix = false){
		error_reporting(0);
		global $file_id;
		$info 			= pathinfo($src[1]); 
		$nama_file 		= $info['basename'];
		$file_type 		= $info['extension'];
		//$file_id		= substr(md5(rand(0,1000)),0,8);
		if(in_array($file_type,$extensions)){
			if($prefix == true)	{ $nama_file = $file_id."_".$nama_file;				}
			move_uploaded_file($src[0],$dest."/".$nama_file);
		}else{
			foreach ($extensions as &$extension) {
				$value .= $extension.",";
			}			
			$alert 		= "Tipe file yang diijinkan : ".substr($value,0,-1)."<br>";
			echo $alert;
		}
	}
	//$cupload->upload($src,$dest,array("doc","xls","pdf");,false);
}
$cupload = new uploads();
?>
