<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	//if($num_ip == 0){
		
		cuser_log("customer","0","download ".strtoupper($file_name),@$id_coin); 
	
		$email_admin	 = "thetakur@gmail.com,indwic@gmail.com,junjungan70@gmail.com";
		$subject 		 = "Seseorang Mengunduh Aplikasi Discoin ".$nm_merchant;
		$from 			= "Support Sempoa <support@sempoa.biz>";
		$msg 			 = "
			Dear admin ".$nm_merchant."<br><br>
			Pada tanggal ".$dtime->now2indodate2($tglupdate)." dan Pukul ".$wktupdate."<br>
			Seseoranng mengunduh, aplikasi Discoin anda, jika ada pertanyaan-pertanyaan tentang pelayanan dan penjelasan kepada calon pelanggan anda yang ingin menjadi pelanggan anda dengan menggunakan aplikasi Discoin anda.<br><br>
			Silahkan konsultasikan kepada kami dengan membalas email ini, atau mengirim email pertanyaan anda ke email <b>support@sempoa.biz</b>
			<br>
			<br>
			Terimakasih<br><br>
			<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
			<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>";
		$type 			= "html";
		if(!empty($email_merchant)){
			sendmail(trim($email_merchant),$subject,$msg,$from,$type);
		}
		sendmail(trim($email_admin),$subject,$msg,$from,$type);
	//}
	$name			= strtoupper($file_name)."COIN.apk";
	$file			= $file_path;
	//output_file($file, $name, $mime_type='');
	?>
	<script language="javascript">
	   location.href = "<?php echo $dirhost; ?>/files/coin/<?php echo $file_path; ?>";
	</script>
