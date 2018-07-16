<?php defined('mainload') or die('Restricted Access'); ?>
<?php
class calling{

	public function clas($file){
		global $basepath;
		global $sanitize;
		$res = $sanitize->path($basepath)."/includes/classes/".$sanitize->str($file).".php";
		return $res;
	}	
	public function func($file){
		global $basepath;
		global $sanitize;
		$res = $sanitize->path($basepath)."/includes/functions/".$sanitize->str($file).".php";
		return $res;
	}	
	public function inc($path,$file){
		global $basepath;
		global $sanitize;
		$res = $sanitize->path($basepath)."/".$sanitize->path($path)."/".$sanitize->str($file);
		return $res;
	}	
	public function lib($lib_name){
		global $basepath;
		global $sanitize;
		$res = $sanitize->path($basepath)."/libraries/".$sanitize->str($lib_name)."/lib.php";
		return $res;
	}
	public function css($file = NULL){
		global $module;
		global $page;
		global $dirhost;
		global $sanitize;
		if($file == NULL){ $file = "style"; }
		$res = "<link href='".$sanitize->url($dirhost)."/modules/".$sanitize->str($page)."/css/".$sanitize->str($file).".css' rel='stylesheet' type='text/css'/>";
		return $res;
	}	
	public function js($file = NULL){
		global $module;
		global $page;
		global $dirhost;
		global $sanitize;
		if($file == NULL){ $file = "js.js"; }
		$res = "<script src='".$sanitize->url($dirhost)."/modules/".$sanitize->str($page)."/js/".$sanitize->str($file)."' type='text/javascript'></script>";
		return $res;
	}
	public function ajax($file){
		global $module;
		global $page;
		global $dirhost;
		global $sanitize;
		$res = $sanitize->url($dirhost)."/modules/".$sanitize->str($page)."/ajax/".$sanitize->str($file);
		return $res;
	}	
}
$call = new calling();

?>