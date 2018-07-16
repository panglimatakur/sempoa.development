<?php
ob_start("ob_gzhandler");
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include ("../../includes/config.php");
include ("../../includes/classes.php");
include ("../../includes/functions.php");
include ("../../includes/declarations.php");

$file_string 	= $_REQUEST['filename'];
$direction 	  	= isset($_REQUEST['direction']) ? $_REQUEST['direction']:"";
$file_str 	  	= explode(".",$file_string);
$file_name     	= $file_str[0];
$user_agent     = $_SERVER['HTTP_USER_AGENT'];
if(!empty($file_name)){
	$q_merchant  		= $db->query("SELECT * FROM ".$tpref."clients WHERE CLIENT_APP='".$file_name."'");
	$dt_merchant 		= $db->fetchNextObject($q_merchant);
	@$id_coin 			= $dt_merchant->ID_CLIENT;
	@$nm_merchant		= $dt_merchant->CLIENT_NAME;
	@$illuminate 		= explode(" ",$nm_merchant);
	@$email_merchant 	= $dt_merchant->CLIENT_EMAIL;
	@$url_merchant 		= $dt_merchant->CLIENT_URL;
	@$alamat_merchant 	= $dt_merchant->CLIENT_ADDRESS;
	@$deskripsi_merchant= $dt_merchant->CLIENT_DESCRIPTIONS;
	
	@$meta_title 		= $dt_merchant->META_TITLE;
	@$meta_keywords 	= $dt_merchant->META_KEYWORDS;
	@$meta_description 	= $dt_merchant->META_DESCRIPTION;
	
	@$logo_merchant		= $dt_merchant->CLIENT_LOGO;
	@$warna_merchant	= $dt_merchant->COLOUR;
	@$app_merchant		= $dt_merchant->CLIENT_APP;
	@$playstore			= $dt_merchant->PLAY_STORE;
	echo $playstore." testing drive";
	@$color				= explode(";",$dt_merchant->COLOUR);
	@$bg_1 				= $color[0]; //#993366
	@$bg_2 				= $color[1]; //#732b4f
	if(empty($bg_1))	{ $bg_1 = "#993366"; }
	if(empty($bg_2))	{ $bg_2 = "#732b4f"; }
	
	$str_community				= " SELECT 
										a.BY_ID_PURPLE, 
										a.NAME,
										b.ID_COMMUNITY 
									FROM 
										".$tpref."communities a, 
										".$tpref."communities_merchants b 
									WHERE 
										b.ID_CLIENT = '".$id_coin."' AND
										a.ID_COMMUNITY = b.ID_COMMUNITY";
	//echo $str_community;
	$q_community 				= $db->query($str_community);
	$num_community				= $db->numRows($q_community);
	$join 						= "";
	$nm_community				= ""; 	
	if($num_community > 0){
		while($dt_join = $db->fetchNextObject($q_community)){
			$join 			.= $dt_join->ID_COMMUNITY;	
			$nm_community	.= $dt_join->NAME.",";	
		}
	}
	$nm_community	= substr($nm_community,0,-1);
}
$user_os        = getOS($user_agent);
$file_folder	= $file_name;
if($file_name == "sempoa"){ $file_name = ""; }
$done = 1;

$file_path 	= $file_folder."/".strtoupper($file_name)."COIN.apk";
if($user_os == "Android"){
	$file_path 	= $file_folder."/".strtoupper($file_name)."COIN.apk";
	$done = 1;
}
if($user_os == "BlackBerry"){
	$file_path 	= $file_folder."/".strtoupper($file_name)."COIN.jad";
	$done = 1;
}
	$ip_address 		= $_SERVER['REMOTE_ADDR'];
	cuser_log("customer","0","Melihat Halaman Discoin ".strtoupper($nm_merchant)." Dari ".$user_os,@$id_coin); 
	$num_ip 	 		= $db->recount("SELECT IP_ADDRESS FROM ".$tpref."logs WHERE IP_ADDRESS = '".$ip_address."'");
	$downloaded			= $db->recount("SELECT ID_LOGS FROM ".$tpref."logs WHERE ACTIVITY LIKE '%download%' AND ID_CLIENT ='".$id_coin."'");
	if(!empty($direction) && $direction == "download"){
		include $call->inc("files/coin/download/includes","proses.php");
	}else{
		include $call->inc("templates/discoin","index.php");
	}

?>