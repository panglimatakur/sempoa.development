<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 								: ""; 
	$existing 		= isset($_REQUEST['existing']) 		? $sanitize->str($_REQUEST['existing']) 				: "";			
	$merchant_id 	= isset($_REQUEST['merchant_id']) 	? $sanitize->number($_REQUEST['merchant_id']) 			: "";			
	$nama 			= isset($_REQUEST['nama']) 			? $sanitize->str(strtoupper($_REQUEST['nama'])) 		: "";			
	$propinsi 		= isset($_REQUEST['propinsi']) 		? $sanitize->str($_REQUEST['propinsi']) 				: "0";					
	$kota 			= isset($_REQUEST['kota']) 			? $sanitize->str($_REQUEST['kota']) 					: "0";						
	$kelurahan   	= isset($_REQUEST['kelurahan']) 	? $sanitize->str($_REQUEST['kelurahan']) 				: "0";						
	$kecamatan   	= isset($_REQUEST['kecamatan']) 	? $sanitize->str($_REQUEST['kecamatan']) 				: "0"; 						
	$alamat 	  	= isset($_REQUEST['alamat']) 		? $sanitize->str(ucwords($_REQUEST['alamat'])) 			: ""; 			
	$email_brand 	= isset($_REQUEST['email_brand'])	? $sanitize->email(strtolower($_REQUEST['email_brand'])): ""; 						
	$tlp 		 	= isset($_REQUEST['tlp']) 			? $sanitize->str($_REQUEST['tlp']) 						: ""; 						
	$website 	 	= isset($_REQUEST['website']) 		? $sanitize->url(strtolower($_REQUEST['website'])) 		: "";		
	$deskripsi   	= isset($_REQUEST['deskripsi']) 	? $sanitize->str(ucfirst($_REQUEST['deskripsi'])) 		: ""; 								
	
	$nama_pemohon 	= isset($_REQUEST['nama_pemohon']) 	? $sanitize->str(strtoupper($_REQUEST['nama_pemohon'])) : ""; 				
	$kontak 		= isset($_REQUEST['kontak']) 		? $sanitize->str($_REQUEST['kontak']) 					: "";						
	$id_jabatan 	= isset($_REQUEST['id_jabatan'])	? $sanitize->number($_REQUEST['id_jabatan']) 			: ""; 
	$email 			= isset($_REQUEST['email']) 		? $sanitize->email(strtolower($_REQUEST['email'])) 		: ""; 	
	$new_pass 		= isset($_REQUEST['new_pass']) 		? $sanitize->str($_REQUEST['new_pass']) 				: ""; 	
	$konf_new_pass 	= isset($_REQUEST['konf_new_pass']) ? $sanitize->str($_REQUEST['konf_new_pass']) 			: ""; 	
	
	
	if(!empty($direction) && $direction == "register"){
		if($existing == "2"){ if($merchant_id != ""){ $done_merchant = '2'; }else{ $done_merchant = '1'; } }
		if($existing == "1"){ 
			if($nama != "" && $email_brand != "" && $tlp != "" && $alamat != "" && $deskripsi != ""){ 
				$ch_email_brand = $db->recount("SELECT CLIENT_EMAIL FROM ".$tpref."clients 
												WHERE CLIENT_EMAIL = '".$email_brand."'");
			
				if($ch_email_brand == 0){
					$done_merchant = '2'; 
				}else{
					$done_merchant = '1'; 
					$result["io"]  = 1;
					$result["msg"] = "Akun email merchant <b>".@$email_brand."</b>, sudah terdaftar, silahkan gunakan email lain";	
				}
			}else{
				$done_merchant = '1'; 
			}
		}
		
		
		$package = isset($_REQUEST['package']) 		? $sanitize->str($_REQUEST['package']) 			: "";
		if(!empty($package)){
			$paket = explode("_",$package);
			switch($paket[1]){
				case "1":
					$caption = "Paket Starter Brand";
				break;	
				case "2":
					$caption =  "Paket Advance Brand";
				break;	
				case "3":
					$caption =  "Paket Profesional Brand";
				break;	
				case "4":
					$caption =  "Paket Expert Brand";
				break;	
				case "5":
					$caption =  "Paket Corporate Brand";
				break;	
				case "6":
					$caption =  "Paket Enterprise Brand";
				break;	
			}
			$paket_id	= $paket[1];
			@$jenis 		= strtoupper($paket[0]);
		}

		if($done_merchant == 2 && !empty($nama_pemohon) && !empty($id_jabatan) && 
		   !empty($nama_pemohon) && !empty($kontak) && !empty($email) && !empty($new_pass)){
			$ch_email_user = $db->recount("SELECT USER_EMAIL FROM system_users_client 
										   WHERE USER_EMAIL = '".$email."'");
			
			if($ch_email_user == 0){
				if($konf_new_pass == $new_pass){	
					
					
					if($existing == 1){
						//OPEN++++++++++++++++++++++++++++++++++++++CHECK AND DECLARE DISCOIN APP NAME	
						$nama_app		= $sanitize->str(strtolower(str_replace(" ","",$nama)));
						$count_app		= $db->recount("SELECT CLIENT_APP FROM ".$tpref."clients 
														WHERE CLIENT_APP='".$nama_app."'");							
						if($count_app > 0){ @$nama_app = $nama_app."".md5(substr($nama_app,0,3)); }
						//CLOSE=====================================END OF CHECK AND DECLARE DISCOIN APP NAME	
						
						//OPEN++++++++++++++++++++++++++++++++++++++DECLARE EXPIRATION DATE	
						$expired_date = "";
						if(!empty($jenis) && $jenis == "TRIAL"){
							$next_year		= date('Y')+1;
							$expired_date 	= $next_year."-".date('m')."-".date('d');
						}
						//CLOSE=====================================END OF DECLARE EXPIRATION DATE	
						

						//OPEN++++++++++++++++++++++++++++++++++++++ADD MERCHANT ACCOUNT TEMPORARY		
						/*$container = array(1=>
							array("CLIENT_PACKAGE",@$package),
							//array("CLIENT_LOGO",@$logo_file),
							array("CLIENT_NAME",@$nama),
							array("CLIENT_URL",@$website),
							array("CLIENT_PHONE",@$tlp),
							array("CLIENT_ADDRESS",@$alamat),
							array("CLIENT_PROVINCE",@$propinsi),
							array("CLIENT_EMAIL",@$email_brand),
							array("CLIENT_DESCRIPTIONS",@$deskripsi),
							array("CLIENT_PERSON_CONTACT",@$kontak),
							array("CLIENT_PERSON_EMAIL",@$email),
							array("CLIENT_PERSON_NAME",@$nama_pemohon),
							array("EXPIRATION_DATE",@$expired_date),
							array("TGLUPDATE",$tglupdate));
						$db->insert($tpref."clients_register",$container);*/
						//OPEN++++++++++++++++++++++++++++++++++++++ADD MERCHANT ACCOUNT TEMPORARY	
		
		
						//OPEN++++++++++++++++++++++++++++++++++++++ADD MERCHANT ACCOUNT PERMANENTLY	
						$client_code 	= 	substr(md5($nama.$email_brand.$tglupdate),0,17);
						$container = array(1=>
							array("CLIENT_PACKAGE",@$package),
							//array("CLIENT_LOGO",@$logo_file),
							array("CLIENT_COIN",@$client_code),
							array("CLIENT_NAME",@$nama),
							array("CLIENT_URL",@$website),
							array("CLIENT_PHONE",@$tlp),
							array("CLIENT_ADDRESS",@$alamat),
							array("CLIENT_PROVINCE",@$propinsi),
							array("CLIENT_EMAIL",@$email_brand),
							array("CLIENT_DESCRIPTIONS",@$deskripsi),
							array("CLIENT_PERSON_CONTACT",@$kontak),
							array("CLIENT_PERSON_EMAIL",@$email),
							array("CLIENT_PERSON_NAME",@$nama_pemohon),
							array("ID_CLIENT_LEVEL","2"),
							array("CLIENT_APP",@$nama_app),
							array("ACTIVATE_STATUS","3"),
							array("EXPIRATION_DATE",@$expired_date),
							array("TGLUPDATE",$tglupdate));
						$db->insert($tpref."clients",$container);
						$id_client 		 = mysql_insert_id();
						//CLOSE=====================================END OF ADD MERCHANT ACCOUNT PERMANENTLY	
						
						//OPEN++++++++++++++++++++++++++++++++++++++ADD DISCOIN DEFAULT ADDONS	
						$ch_addon_config = $db->recount("SELECT ID_CLIENT FROM ".$tpref."discoin_configs 
														 WHERE ID_CLIENT = '".$id_client."'");
						if($ch_addon_config == 0){
							$q_addons = $db->query("SELECT * FROM ".$tpref."discoin_addons WHERE ADDON_PRICE = '0'");
							while($dt_addons = $db->fetchNextObject($q_addons)){
								$addons = array(1=>
												  array("ID_CLIENT",$id_client),
												  array("ID_DISCOIN_ADDON",$dt_addons->ID_DISCOIN_ADDON));		
								$db->insert($tpref."discoin_configs",$addons);			
							}
						}
						//CLOSE=====================================END OF ADD DISCOIN DEFAULT ADDONS	
						
						//OPEN++++++++++++++++++++++++++++++++++++++JOIN DEFAULT COMMUNITY		
						$ins_community = array(1=>
							array("ID_COMMUNITY","5"),
							array("ID_CLIENT",@$id_client),
							array("TGLUPDATE",@$tglupdate));
						$db->insert($tpref."communities_merchants",$ins_community);
						//CLOSE=====================================END OF JOIN DEFAULT COMMUNITY	
						
						
						//OPEN++++++++++++++++++++++++++++++++++++++ACTIVATE 10 COIN		
						if($id_jabatan == 1){
							$q_coin = $db->query("SELECT * FROM ".$tpref."discoin_activation_codes 
												  WHERE 
												  	(ACTIVATE_BY_ID_CLIENT IS NULL OR ACTIVATE_BY_ID_CLIENT = '0')
												   LIMIT 0,10");	
							while($dt_coin = $db->fetchNextObject($q_coin)){
							$db->query("UPDATE ".$tpref."discoin_activation_codes  
										SET ACTIVATE_BY_ID_CLIENT = '".$id_client."',UPDATEDATE = '".$tglupdate."'
										WHERE 
											ID_DISCOIN_ACTIVATION_CODE  = '".$dt_coin->ID_DISCOIN_ACTIVATION_CODE."'");
							}
						}
						//CLOSE=====================================END OF ACTIVATE 10 COIN
						
						$_SESSION['uclevelkey']		= 2;
						$_SESSION['ori_uclevelkey']	= 2;
						$_SESSION['cparentkey']		= 0;
						@$_SESSION['ori_cparentkey']= 0;
						$_SESSION['ccoin']			= $client_code;
						$_SESSION['app'	]			= $nama_app;
						
					}else{
						$id_client 		 = $merchant_id;
						$q_client					= $db->query("SELECT 
																	ID_CLIENT_LEVEL,
																	CLIENT_COIN,
																	CLIENT_ID_PARENT,
																	CLIENT_APP 
																  FROM ".$tpref."clients 
																  WHERE 
																	ID_CLIENT='".$id_client."'");
																	
						$dt_client					= $db->fetchNextObject($q_client);
						$_SESSION['uclevelkey']		= $dt_client->ID_CLIENT_LEVEL;
						$_SESSION['ori_uclevelkey']	= $dt_client->ID_CLIENT_LEVEL;
						@$_SESSION['cparentkey']	= $dt_client->CLIENT_ID_PARENT;
						@$_SESSION['ori_cparentkey']= $dt_client->CLIENT_ID_PARENT;
						$_SESSION['ccoin']			= $dt_client->CLIENT_COIN;
						$_SESSION['app'	]			= $dt_client->CLIENT_APP;
						
					}
				
					
					//OPEN++++++++++++++++++++++++++++++++++++++ADD MERCHANT USER		
					$insert_user = array(1=>
						array("ID_CLIENT",@$id_client),
						array("USER_NAME",@$nama_pemohon),
						array("USER_PHONE",@$kontak),
						array("USER_EMAIL",@$email),
						array("USER_PASS",@$new_pass),
						array("ID_CLIENT_LEVEL","2"),
						array("ID_CLIENT_USER_LEVEL",$id_jabatan),
						array("INSERT_DATA","1"),
						array("EDIT_DATA","1"),
						array("DELETE_DATA","1"));
					$db->insert("system_users_client",$insert_user);
					$id_user 		 = mysql_insert_id();
					//CLOSE=====================================END OF ADD MERCHANT USER		

					//OPEN++++++++++++++++++++++++++++++++++++++ADD PRIVILEDGE ACCESS
					switch($id_jabatan){
						case "1":
							$page_list = array("4","204","118","9","207","128","17","202","191","183","205","166","196","121","208","209","129","168");
						break;
						case "2":
							$page_list = array("207","128","17","129");
						break;
						case "3":	
							$page_list = array("121","208","209","129");
						break;
						case "4":	
							$page_list = array("202","191","183","205","196","121","208","209","129");
						break;
					}
					
					foreach($page_list as &$page_id){
						$insert_akses = array(1=>
							  array("ID_CLIENT",@$id_client),
							  array("ID_PAGE_CLIENT",@$page_id),
							  array("ID_CLIENT_LEVEL","2"),
							  array("ID_CLIENT_USER_LEVEL",$id_jabatan));
						$db->insert("system_pages_client_rightaccess",$insert_akses);
					}
					//CLOSE=====================================END OF ADD PRIVILEDGE ACCESS
					

					//CLOSE=====================================DECLARE GLOBAL SESSION
					@$_SESSION['cname']			= $nama;
					$_SESSION['cidkey'] 		= $id_client;
					$_SESSION['ori_cidkey']		= $id_client;
					
					$_SESSION['comidkey']		= 6;
					
					
					$_SESSION['uidkey'] 		= $id_user; 
					$_SESSION['ulevelkey']		= $id_jabatan;
					$_SESSION['levelname']		= $db->fob("NAME","system_master_client_users_level"," 
															WHERE 
																ID_CLIENT_USER_LEVEL = '".$id_jabatan."'");
					$_SESSION['username']		= $email;
					$_SESSION['loginname']		= $nama_pemohon;
					$_SESSION['insert']			= 1; 
					$_SESSION['edit']			= 1;	
					$_SESSION['delete']			= 1;	
					//CLOSE=====================================END OF DECLARE GLOBAL SESSION
					
					//OPEN++++++++++++++++++++++++++++++++++++++ADD RANK FORMULA
					@$ranks 					= rank_formula($id_client);
					$_SESSION['ranks']			= $ranks;
					//CLOSE=====================================END OF ADD RANK FORMULA

					//CLOSE=====================================SENDING EMAIL
					$to 			 = "thetakur@gmail.com";
					if(empty($dt_coin->CLIENT_EMAIL)){ $sender = "crew@sempoa.biz"; }else{ $sender = $dt_coin->CLIENT_EMAIL; }
					$subject 		= "[PENTING]PENDAFTARAN MERCHANT Sempoa ".$nama;
					$headers 		= "From: " . strip_tags($sender) . "\r\n";
					$headers 		.= "MIME-Version: 1.0\r\n";
					$headers 		.= "Content-Type: text/html; charset=ISO-8859-1\r\n";			
					
					$msg 			= "
					Merchant baru saja melakukan pendaftaran<br> <br> 
					
					<b>Nama Merchant</b><br>
						".$nama."<br><br>
						
					<b>Alamat Merchant</b><br>
						&nbsp;&nbsp;".$alamat."<br><br>
						
					<b>Deskripsi Merchant</b><br>
						&nbsp;&nbsp;".$deskripsi."<br><br>
						
					<b>Kontak Person</b><br>
						&nbsp;&nbsp;".$nama_pemohon."<br>
						&nbsp;&nbsp;".$kontak."<br>
						&nbsp;&nbsp;".$email."<br>
					";
					//mail($to,$subject,$msg,$headers);	
					
					$msg2merchant 			= "
					Dear  ".$nama_pemohon."<br> <br> 
					
					Nama Merchant <b>".$nama." baru saja terdaftar sebagai anggota komunitas bisnis sempoa,<br><br>
					silahkan masuk menggunakan email dan password pendaftar, lalu lakukan beberapa hal berikut, untuk memulai pengelolaan toko online anda, 
					
					<ol>
						<li>Pilih menu Konfigurasi -> Manajemen Merchant -> Profil Merchant</li>
						<li>Masukan Logo Brand Bisnis anda</li>
						<li>Tentukan warna khas brand bisnis anda</li>
						<li>Isi kolom-kolom yang kosong untuk melengkapi data informasi usaha anda.</li>
					</ol>";
					//mail($email,$subject,$msg2merchant,$headers);
					//CLOSE=====================================END OF SENDING EMAIL
					
					$_SESSION['admin_only']	= "false";
					$result["redirect_page"] = $dirhost."/?page=profil_pengguna";
					$result["io"]  = 3;
					$result["msg"] = "Terimakasih <b>".@$nama."</b>, Pendaftaran anda telah diterima, silahakn login menggunakan akun email dan pasword yang tertera pada kolom email pendaftar dan password pendaftar.";	
					
					
				}else{
					$result["io"]  = 1;
					$result["msg"] = "Password anda tidak sama, mohon konfirmasikan password anda pada kolom \"Konfirmasi Password\"";	
				}
			}else{
				$result["io"]  = 1;
				$result["msg"] = "Akun email pengguna/pengelola <b>".@$email."</b>, sudah terdaftar, silahkan gunakan email lain";	
				if($id_jabatan != 1){
					$result["msg"] .= ", atau hubungi Super Administrator <b>".$nama."</b>";	
				}
			}
		
		}else{
			$result["io"]  = 1;
			if($done_merchant == 2){
			$result["msg"] = "Pegisian Form, belum lengkap, silahkan lengkapi kolom isian dengan simbol bintang ( <span style='color:#990000;'>*</span>";
			}else{
				$result["msg"] = "Merchant ini sudah terdaftar...";	
			}
			
		}
		echo json_encode($result);
	}
	
}else{  
	defined('mainload') or die('Restricted Access'); 
}
?>