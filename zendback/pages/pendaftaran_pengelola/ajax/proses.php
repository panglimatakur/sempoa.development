<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$no 		= isset($_POST['no']) 			? $_POST['no'] : "";
	
	if(!empty($direction) && $direction == "delete"){
		$q_doc		= $db->query("SELECT * FROM ".$tpref."documents WHERE ID_USER='".$no."' ");
		while($dt_doc	= $db->fetchNextObject($q_doc)){
			$dir 		= $basepath."/files/documents/users/".$no;
			$file		= $dir."/".$dt_doc->FILE_DOCUMENT;
			if(is_file($file)){
				unlink($file);
				rmdir($dir);
				$db->delete($tpref."documents"," WHERE ID_DOCUMENT='".$dt_doc->ID_DOCUMENT."'");
			}
		}
		$q_photos 	= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$no."'");
		$dt_photos = $db->fetchNextObject($q_photos);
		if(is_file($basepath."/files/images/users/".$dt_photos->USER_PHOTO)){
			unlink($basepath."/files/images/users/".$dt_photos->USER_PHOTO);
		}
		$db->delete("system_users_client"," WHERE ID_USER ='".$no."'");
	}
	
	if(!empty($direction) && $direction == "delete_file"){ 
		$q_doc		= $db->query("SELECT * FROM ".$tpref."documents WHERE ID_DOCUMENT='".$no."'");
		$dt_doc		= $db->fetchNextObject($q_doc);
		$id_user	= $dt_doc->ID_USER;
		$name		= $dt_doc->FILE_DOCUMENT;
		$file		= $basepath."/files/documents/users/".$id_user."/".$dt_doc->FILE_DOCUMENT;
		if(is_file($file)){
			unlink($file);
			$db->delete($tpref."documents"," WHERE ID_DOCUMENT='".$no."'");
		}
	}
	
	if(!empty($direction) && $direction == "starterpack"){
		
		$logo_merchant	= "";
		$q_merchant	 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT = '".$no."' AND ID_CLIENT != '1'");
		$dt_merchant 	= $db->fetchNextObject($q_merchant);
		if(empty($dt_merchant->CLIENT_LOGO)){
			$logo_merchant .= "<img src='".$dirhost."/files/images/no_image.jpg' style='height:60px;'>";	
		}else{
			$logo_merchant .= "<img src='".$dirhost."/files/images/logos/".$dt_merchant->CLIENT_LOGO."' height='width:60px;'>";	
		}
		
		@$logo_merchants .= "<div class='logos'>
								<div class='logos_inset'>
									".@$logo_merchant."
								</div>
							</div>";		
		
		$q_user 	= $db->query("SELECT USER_NAME,USER_USERNAME,USER_PASS,USER_EMAIL FROM system_users_client WHERE ID_CLIENT_LEVEL ='2' AND ID_CLIENT_USER_LEVEL ='1' AND ID_CLIENT = '".$no."'");
		$dt_user 	= $db->fetchNextObject($q_user);
		
		$sender			= "Sempoa Discoin Community <info@".$website_name.">";
		$type			= "html";
		$recipients 	= $dt_user->USER_EMAIL;
		$subject_coin 	= "[PENTING] Informasi Login dan StarterPack Akun ".$website_name;
		$msg_coin		= "
		<style type='text/css'>
			.logos{
				position:relative;
				border:2px solid #CCCCCC; 
				border-radius:4px;
				-moz-border-radius:4px;
				-webkit-border-radius:4px;
				width:80px;
				height:80px;
				margin:4px;
				float:left;
			}
			.logos_inset{
				width:78px;
				height:78px;
				overflow:hidden;
			}
		</style>
		<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
				Dear ".$dt_user->USER_NAME."<br><br>
				Selamat, Account Sempoa anda telah kami aktifkan,
Sebelum anda memulai menggunakan layanan ".$website_name.", mohon untuk membaca isi dari keseluruhan email ini dengan seksama. Cetaklah & simpan email ini untuk penggunaan anda dimasa mendatang.
				<br clear='all'><br>	
				
				Username : ".$dt_user->USER_USERNAME."<br>	
				Password : ".$dt_user->USER_PASS."<br>
				
				<br><br>
				
				<b>Alamat URL Download Discoin</b><br>
					<a href='".$dirhost."/".$dt_merchant->CLIENT_APP.".coin' target='_blank'>
						".$dirhost."/".$dt_merchant->CLIENT_APP.".coin 
					</a>
					<br>
					Dari telephone genggam pelanggan anda. 
				<br><br>

				<b>Alamat URL Tutorial Sempoa Discoin Community</b><br>
				<a href='".$dirhost."/files/tutorial.rar' target='_blank'>
					".$dirhost."/files/tutorial.rar
				</a>
				<br>
				<br>
				Terimakasih<br><br>
				<br>
				NB : Pesan ini disampaikan oleh<br><br>
				<img src='".$logo_path."'><br>
				
		</div>";
		sendmail($recipients,$subject_coin,$msg_coin,$sender,$type);
		echo "Informasi Starterpack ".$dt_merchant->CLIENT_NAME." Berhasil Dikirim";
	}
}
?>