<?php defined("mainload") or die("Restricted Access"); ?>
<?php
function billing_support(){
	global $dirhost;
	echo '
	<fieldset style="margin-bottom:5px">
		<legend>Billing Support</legend>
		<div class="col-md-2 thumbnail">
			<img src="'.$dirhost.'/files/images/users/jhoty.jpg" width="100%">
		</div>
		<div class="col-md-10" style="text-align:justify">
			Hai, ketemu lagi dengan <b>Pita</b>, jika kamu ingin bertanya tentang hal-hal mengenai transaksi hingga pembayaran, untuk kenyamanan anda, silahkan jangan sungkan untuk menghubungi <b>Pita</b> ya, di <br />
			<img src="'.$dirhost.'/files/images/icons/whatsapp.png" width="28" /> <b>081288616068</b><br />
			<img src="'.$dirhost.'/files/images/icons/bbm.png"  width="28" /> <b>D0F544A9</b>
			<br /><br />
			<b>-Terimakasih-</b> 
		</div>
	</fieldset>';
}

function cookie_destroy(){
	if (isset($_SERVER['HTTP_COOKIE'])) {
		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		foreach($cookies as $cookie) {
			$parts = explode('=', $cookie);
			$name  = trim($parts[0]);
			setcookie($name, '', time()-1000);
			setcookie($name, '', time()-1000, '/');
		}
	}
}
function set_array_cookie($cookie_name,$cookie_array_value){
	setcookie($cookie_name,json_encode($cookie_array_value), time()+3600);
}
function get_array_cookie($cookie_name){
	$cookie 			= $_COOKIE[$cookie_name];
	$cookie 			= stripslashes($cookie);
	$decoded_cookie 	= json_decode($cookie, true);	
	return $decoded_cookie;
}
function generate_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

function generateString($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function pembulatan($uang){
	$ratusan = substr($uang, -3);
	if($ratusan<500)
		$akhir = $uang - $ratusan;
	else
		$akhir = $uang + (1000-$ratusan);
	return $akhir;
}

function random_color(){
   $color = "#".dechex(rand(0,10000000));
   return $color;
}
function embedYoutube($idvideo,$attr){
	$result = '<iframe '.$attr.' src="http://www.youtube.com/embed/'.$idvideo.'" allowfullscreen></iframe>';
	return $result;
}

function youtubeThumb($id,$attr){
	$result = '<img src="http://img.youtube.com/vi/'.$id.'/default.jpg"  '.$attr.'/>';
	return $result;
}


function createThumbs($pathToImages,$pathToThumbs,$thumbWidth ) {
	$info = pathinfo($pathToImages);
	if(strtolower($info['extension']) == 'jpg')  { $img = imagecreatefromjpeg("{$pathToImages}");  	}
	if(strtolower($info['extension']) == 'gif')  { $img = imagecreatefromgif("{$pathToImages}");  	}
	if(strtolower($info['extension']) == 'png')  { $img = imagecreatefrompng("{$pathToImages}");  	}

	$width 		= imagesx($img);
	$height 	= imagesy($img);
	$new_width 	= $thumbWidth;
	$new_height = floor($height * ( $thumbWidth / $width ));
	$tmp_img 	= imagecreatetruecolor( $new_width, $new_height );
	imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	
	if(strtolower($info['extension']) == 'jpg')  { imagejpeg($tmp_img, "{$pathToThumbs}" );			}
	if(strtolower($info['extension']) == 'gif')  { imagegif($tmp_img, "{$pathToThumbs}" );			} 
	if(strtolower($info['extension']) == 'png')  { imagepng($tmp_img, "{$pathToThumbs}" );			} 
}


function permalink($str,$prefix){ 
	$links = preg_replace('/[^-a-z]+/', $prefix, trim(strtolower($str)));
	return $links;
}

function phone_filter($no){
	$no_tlp = str_replace(" ","",$no);
	$no_tlp = filter_var($no_tlp,FILTER_SANITIZE_NUMBER_INT);
	$result = substr($no_tlp,0,1);
	if($result == "0"){
		$no_tlp = "+62.".substr($no_tlp,1);
	}
	return $no_tlp;
}
function msg($isi,$type){
	if($type == "error")	{ $class = "alert-danger"; 		}
	if($type == "warning")	{ $class = "alert-warning"; 	}
	if($type == "success")	{ $class = "alert-success"; 	}
	if($type == "info")		{ $class = "alert-info"; 	}
	$msg = "<div class='alert alert-dismissable ".@$class."' style='margin:5px;'>
				<a data-dismiss='alert' class='close'>×</a>".$isi."
			</div>";
	return $msg;
}
function note($mwi_message){
	if(!empty($mwi_message)){
		$msg = explode("{@}",$mwi_message);
		$result = msg($msg[1],$msg[0]);
	}else{
		$result = "";
	}
	return $result;
}
function redirect_page($page_target) {
	echo "<script language=javascript>self.location.href = '".$page_target."';</script>	";
}

function money($cur,$str){
	$num=$cur."".str_replace(",",".",number_format($str)).",00";
	return $num;
}
function cutext($text,$limit){
	$jmlstr = strlen($text);
	if($jmlstr > $limit){
		$result = substr($text,0,$limit)."...";	
	}
	else{
		$result = $text;
	}
	return $result;
}

/*
Fungsi terbilang dari angka menjadi pernyataan teks yang menyatakan bilangan tersebut.
*/
function bilang($x) {
    $x = abs($x);
    $total = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $result = "";
    if ($x <12) {
        $result = " ". $total[$x];
    } else if ($x <20) {
        $result = bilang($x - 10). " belas";
    } else if ($x <100) {
        $result = bilang($x/10)." puluh". bilang($x % 10);
    } else if ($x <200) {
        $result = " seratus" . bilang($x - 100);
    } else if ($x <1000) {
        $result = bilang($x/100) . " ratus" . bilang($x % 100);
    } else if ($x <2000) {
        $result = " seribu" . bilang($x - 1000);
    } else if ($x <1000000) {
        $result = bilang($x/1000) . " ribu" . bilang($x % 1000);
    } else if ($x <1000000000) {
        $result = bilang($x/1000000) . " juta" . bilang($x % 1000000);
    } else if ($x <1000000000000) {
        $result = bilang($x/1000000000) . " milyar" . bilang(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $result = bilang($x/1000000000000) . " trilyun" . bilang(fmod($x,1000000000000));
    }      
        return $result;
}
function terbilang($x, $style=4) {
    if($x<0) {
        $hasil = "minus ". trim(bilang($x));
    } else {
        $hasil = trim(bilang($x));
    }      
    switch ($style) {
        case 1:
            $hasil = strtoupper($hasil);
            break;
        case 2:
            $hasil = strtolower($hasil);
            break;
        case 3:
            $hasil = ucwords($hasil);
            break;
        default:
            $hasil = ucfirst($hasil);
            break;
    }      
    return $hasil;
}		
		

function transletNum($a){
	$jml = strlen($a);
	switch($jml){
		case "1":
			$a= "000000".$a;
		break;
		case "2":
			$a= "00000".$a;
		break;
		case "3":
			$a= "0000".$a;
		break;
		case "4":
			$a= "000".$a;
		break;
	}
return $a;
}

function romawi($number){
	switch($number){
		case "I":
		$number = "01";
		break;
		case "II":
		$number = "02";
		break;
		case "III";
		$number = "03";
		case "IV":
		$number = "04";
		break;
	}
	return $number;
}

function sendmail($to,$subject,$msg,$from,$type){
	if($type == 'html'){ 
	$headers = "From: ".$from."\r\n"; 
    $headers .= "MIME-Version: 1.0\r\n"; 
    $boundary = uniqid("HTMLEMAIL"); 
    $headers .= "Content-Type: multipart/alternative;".
                "boundary = ".$boundary."\r\n\r\n"; 
    $headers .= "This is a MIME encoded message.\r\n\r\n"; 
    $headers .= "--".$boundary."\r\n".
                "Content-Type: text/plain; charset=ISO-8859-1\r\n".
                "Content-Transfer-Encoding: base64\r\n\r\n";              
    $headers .= chunk_split(base64_encode(strip_tags($msg))); 
    $headers .= "--".$boundary."\r\n".
                "Content-Type: text/html; charset=ISO-8859-1\r\n".
                "Content-Transfer-Encoding: base64\r\n\r\n";    
    $headers .= chunk_split(base64_encode($msg)); 	
	}
	
	if($type == 'text'){ 
		$headers = "From: ".$from."\r\nReply-To:".$from;
	}
	mail($to,$subject,'',$headers);
}


function kirim_sms2($nohp,$isi){
	$user		=	"info7am";
	$password 	=	"cukangkawung60";
	$auth		=	md5($user.$password.$nohp);
	$msg		=	urlencode($isi);
	$myurl		=	"http://send.smsmaskingsender.com/sms/api_proxy/sendsms.php?username=".$user."&mobile=".$nohp."&message=".$msg."&auth=".$auth;
	sendsms($myurl);
}

function sendsms($url){
$curlHandle = curl_init(); // init curl
curl_setopt($curlHandle, CURLOPT_URL, $url); // set the url to fetch
curl_setopt($curlHandle, CURLOPT_HEADER, false);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlHandle, CURLOPT_TIMEOUT,60);
curl_setopt($curlHandle, CURLOPT_POST, 0);
$content = curl_exec($curlHandle);
	if(!$content){
		//return 'error '.curl_error($curlHandle).print_r(curl_getinfo($curlHandle));
		
		//return 'Curl error: ' . curl_error($curlHandle);
	}else {
		return $content;
	}
curl_close($curlHandle);
}

function kirim_sms($user_key,$nohp,$isi){
global $kdskl_id;
global $sms_service_app;

$webservice = new jsonRPCClient($sms_service_app);
//Replace dengan +62 jika awalan nomor hp 0
$hp_tujuan = $nohp[0] == 0 ?
substr_replace($nohp, '+62', 0, 1) : $nohp;
$webservice->sendPrivate($kdskl_id, $user_key, $hp_tujuan, $isi);
}


function current_val($n,$maxn,$maxsize){
	if($n > 1){
	$scale 	= $maxn/$n;
	$result = round($maxsize/$scale);
	}else{ $result = 1; }
	return $result;
	
}
function getOS($user_agent) { 
    $os_platform    =   $user_agent;
    $os_array       =   array(
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }   
    return $os_platform;
}


?>