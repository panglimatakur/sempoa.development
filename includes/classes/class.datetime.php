<?php defined('mainload') or die('Restricted Access'); ?>
<?php
//-----------------Fungsi umum standar---------------------
class datetimer{
	public function nama_bulan($no_bulan) {
		switch($no_bulan) {
			case 1:
				$nama_bulan = 'Januari';
				break;
			case '01':
				$nama_bulan = 'Januari';
				break;
			case 2:
				$nama_bulan = 'Februari';
				break;
			case '02':
				$nama_bulan = 'Februari';
				break;
			case 3:
				$nama_bulan = 'Maret';
				break;
			case '03':
				$nama_bulan = 'Maret';
				break;
			case 4:
				$nama_bulan = 'April';
				break;
			case '04':
				$nama_bulan = 'April';
				break;
			case 5:
				$nama_bulan = 'Mei';
				break;
			case '05':
				$nama_bulan = 'Mei';
				break;
			case 6:
				$nama_bulan = 'Juni';
				break;
			case '06':
				$nama_bulan = 'Juni';
				break;
			case 7:
				$nama_bulan = 'Juli';
				break;
			case '07':
				$nama_bulan = 'Juli';
				break;
			case 8:
				$nama_bulan = 'Agustus';
				break;
			case '08':
				$nama_bulan = 'Agustus';
				break;
			case 9:
				$nama_bulan = 'September';
				break;
			case '09':
				$nama_bulan = 'September';
				break;
			case 10:
				$nama_bulan = 'Oktober';
				break;
			case 11:
				$nama_bulan = 'Nopember';
				break;
			case 12:
				$nama_bulan = 'Desember';
				break;
		}
		return $nama_bulan;
	}
	public function dateNum($num){
		$jml = strlen(trim($num));
		if($jml == 1){ $num = "0".$num; }
		return $num;
	}
	public function date2sysdate($tanggal) { //FORMAT 01/01/2001 MENJADI 2001-01-01
		$split1 = explode('/',$tanggal);
		$tanggal = $split1[2].'-'.$split1[1].'-'.$split1[0];
		return $tanggal;
	}
	public function now2indodate($waktu) { //FORMAT DARI 01 01 2001 MENJADI 01-01-2001
		$split1 = explode(' ',$waktu);
		$split2 = explode('-',$split1[0]);
		$tanggal = $split2[0].'-'.$split2[1].'-'.$split2[2];
		return $tanggal;
	}
	
	public function now2indodate2($waktu) { //FORMAT 01 Januari 2001
		$split1 = explode(' ',$waktu);
		$split2 = explode('-',$split1[0]);
		$bulan  = $this->nama_bulan($split2[1]);
		$tanggal = $split2[2].' '.$bulan.' '.$split2[0];
		return $tanggal;
	}
	public function date2indodate($tanggal) { //FORMAT 2001-01-01 MENJADI 01-01-2001
		$split1 = explode('-',$tanggal);
		$tanggal = $split1[2].'-'.$split1[1].'-'.$split1[0];
		return $tanggal;
	}
	public function indodate2date($tanggal) { //FORMAT 01-01-2001 MENJADI 2001-01-01
		$split1 = explode('-',$tanggal);
		$tanggal = $split1[2].'-'.$split1[1].'-'.$split1[0];
		return $tanggal;
	}
	
	public function daysamonth($bln,$thn){
		$tmp = mktime(0,0,0,$bln+1,0,$thn);
		$day=date("d",$tmp);
		return $day;
	}
	
	public function daytohari($daynya){
		$arhari = array("Sunday"=>"Minggu","Monday"=>"Senin","Tuesday"=>"Selasa","Wednesday"=>"Rabu","Thursday"=>"Kamis","Friday"=>"Jum'at","Saturday"=>"Sabtu");
		$harinya = $arhari[$daynya];
		return $harinya;
	}
	public function getday($tgl,$bln,$thn){
		$tmp 	= mktime(0,0,0,$bln,$tgl,$thn);
		$day 	= date("l", $tmp);
		$hari 	= $this->daytohari($day);
		return $hari;
		
	}
	
	public function yesterday($min,$date,$month,$year){
		$dateformat = mktime(0,0,0,$month,$date-$min,$year);
		$yesterday =  date("Y-m-d", $dateformat);
		return $yesterday;
	}
	
	public function tomorrow($plus,$date,$month,$year){
		$dateformat = mktime(0,0,0,$month,$date+$plus,$year);
		$tomorrow =  date("Y-m-d", $dateformat);
		return $tomorrow; 
	}
	
	
	public function defaultimedate($zone){
		date_default_timezone_set($zone); //'Asia/Jakarta'
	}
	
	public function diffMonth($tgl1,$tgl2){
		$pecah1 = explode("-", $tgl1);
		$date1 = $pecah1[2];
		$month1 = $pecah1[1];
		$year1 = $pecah1[0];
		
		$pecah2 = explode("-", $tgl2);
		$date2 = $pecah2[2];
		$month2 = $pecah2[1];
		$year2 =  $pecah2[0];
		
		$jd1 = GregorianToJD($month1, $date1, $year1);
		$jd2 = GregorianToJD($month2, $date2, $year2);
		
		$selisih = $jd2 - $jd1;
		$scale = ceil((12/365)*$selisih);
		//echo $tgl1." s/d ".$tgl2." = ".$scale;
		return $scale;
	}
	
	public function daysDiff($startDate,$endDate){
		$dStart 			= new DateTime($startDate);
		$dEnd  				= new DateTime($endDate);
		$dDiff 				= $dStart->diff($dEnd);
		$result['format']	= $dDiff->format('%R');
		$result['days']		= $dDiff->days;
		return $result;
	}
	
	public function dateDifference($startDate, $endDate){
		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);
		if ($startDate === false || $startDate < 0 || $endDate === false || $endDate < 0 || $startDate > $endDate)
			return false;
		   
		$years = date('Y', $endDate) - date('Y', $startDate);
	   
		$endMonth = date('m', $endDate);
		$startMonth = date('m', $startDate);
	   
		// Calculate months
		$months = $endMonth - $startMonth;
		if ($months <= 0)  {
			$months += 12;
			$years--;
		}
		if ($years < 0)
			return false;
	   
		// Calculate the days
					$offsets = array();
					if ($years > 0)
						$offsets[] = $years . (($years == 1) ? ' year' : ' years');
					if ($months > 0)
						$offsets[] = $months . (($months == 1) ? ' month' : ' months');
					$offsets = count($offsets) > 0 ? '+' . implode(' ', $offsets) : 'now';
	
					$days = $endDate - strtotime($offsets, $startDate);
					$days = date('z', $days);   
				   
		return array($years, $months, $days);
	}
	function timeDiff($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'thn',
			'm' => 'bln',
			'w' => 'mgu',
			'd' => 'hri',
			'h' => 'jam',
			'i' => 'mnt',
			's' => 'det',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . '' . $v . ($diff->$k > 1 ? '' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' Lalu' : 'Barusan';
	}
}
$dtime = new datetimer(); 
?>