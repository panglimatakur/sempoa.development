<?php
error_reporting(E_ERROR|E_WARNING);
date_default_timezone_set('Asia/Jakarta');
defined('mainload') or die('Restricted Access');

	$conf = 2;
	if($conf == 1){
		$db_host 		= 'localhost';
		$db_user 		= 'root';
		$db_password 	= '';
		$db_name 		= 'db_sempoa_community';
		$dirhost		= "http://localhost/sempoa.community";
		$basepath 		= $_SERVER['DOCUMENT_ROOT']."/sempoa.community";
		$discoin_api 	= "http://localhost/sempoa.community/discoin_api";
		$websock_conn	= "http://localhost:3000";
	}
	if($conf == 2){
		$db_host 		= 'localhost';
		$db_user 		= 'www';
		$db_password 	= 'ternoda212';
		$db_name 		= 'db_sempoa';
		$host 			= substr_count($_SERVER['HTTP_HOST'],"www.");
		if (!empty($_SERVER['HTTPS'])) {
			$dirhost		= "http://sempoa.community";
			$websock_conn	= "http://sempoa.community:3000";
			if($host > 0){
				$dirhost		= "http://sempoa.community";
				$websock_conn	= "https://www.sempoa.community:3000";
			}
		}else{
			$dirhost		= "http://sempoa.community";
			$websock_conn	= "http://sempoa.community:3000";
			if($host > 0){
				$dirhost		= "http://www.sempoa.community";
				$websock_conn	= "http://www.sempoa.community:3000";
			}
		}
		$mainpath		= "/var/www/html/paladin/sempoa";
		$basepath 		= $_SERVER['DOCUMENT_ROOT'];
		$discoin_api 	= "http://103.23.244.112:8866/discoin_api";
	}
	$website_name 	= "sempoa.community"; //do not use www
	$product_name 	= "sempoa";
	
	$web_template 	= 'multicolor';
	$file_id 		= substr(md5(rand(0,100000)),0,5);

	$wktupdate		= date("H:i:s");
	$tglupdate 		= date("Y-m-d");
	
	$tpref 			= "cat_";
	$list_per_page 	= 30;
	$realtime		= 1;
	$fb_api			= '647564608684001';
	$fb_secret		= '617185c43667a81d27d0fd46820196b8';
	$google_api_key = 'AIzaSyBHida1N6Ers5ygKlp0tYak0PjUfWqPt54';

	$sempoa_bank				= "Bank Central Asia";
	$sempoa_rek_name			= "Nadia Puspita Sari";
	$sempoa_rek_account			= "4-370-988-299";
	$finance_email				= "finance@sempoa.biz";
	$finance_phone				= "085722144803";
	$finance_pin				= "7E9481F0";
	$discoin_fee				= "30000"; 
?>
