<?php defined('mainload') or die('Restricted Access'); ?>
<?php
//session_start();
if(!empty($_REQUEST['ct_nama'])) 		{ $ct_nama 		= $sanitize->str($_REQUEST['ct_nama']); 		}
if(!empty($_REQUEST['ct_email'])) 		{ $ct_email 	= $sanitize->email($_REQUEST['ct_email']); 		}
if(!empty($_REQUEST['ct_pesan'])) 		{ $ct_pesan 	= $sanitize->str($_REQUEST['ct_pesan']); 		}
if(!empty($_REQUEST['captcha'])) 		{ $captcha 		= $sanitize->str($_REQUEST['captcha']); 		}

if(!empty($direction) && $direction == "send"){
	if (empty($_SESSION['captcha']) || $_REQUEST['captcha'] != $_SESSION['captcha']) {
		echo msg("Maaf, Text Captcha yang anda masukan salah, mohon ulangi lagi","error");
	}else{
		if(!empty($ct_email) && !empty($ct_nama) && !empty($ct_pesan)){
			if($validate->email($ct_email) == 1){
				$from			= $ct_nama." <".$ct_email.">";
				$subject 		= "Pertanyaan For Sempoa";
				$to 		 	= "thetakur@gmail.com";
				$type			= "html";
				$msg 			= "
				<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
					Dear Mister Alibaba,<br><br>
					Seseorang, mengirimkan pesan singkat dari website sempoa.biz, mohon di jawab sekarang juga, jangan sok sibuk...
					<br><br>
					\"<i>".$ct_pesan."</i>\"
					<br>
					<br>
					<br>
					Terimakasih<br><br>
					- info@sempoa.biz - <br><br>
					<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
					<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
				</div>	";		
				sendmail($to,$subject,$msg,$from,$type);
				redirect_page($lparam."&msg=1");
			}
		}else{
			echo msg("Maaf, Pengisian form belum lengkap","error");
		}
	}
	session_destroy();
} ?>
