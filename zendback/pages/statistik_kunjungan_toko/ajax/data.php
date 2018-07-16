<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
	strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && 
	!empty($_SESSION['uidkey'])) {
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		
	$direction 	= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$periode 	= isset($_REQUEST['periode']) 	? $_REQUEST['periode'] 		: "";
	$cur_month 	= isset($_REQUEST['cur_month']) ? $_REQUEST['cur_month'] 	: "";
	$cur_month 	= isset($_REQUEST['cur_month']) ? $_REQUEST['cur_month'] 	: "";
	$cur_year 	= isset($_REQUEST['cur_year']) 	? $_REQUEST['cur_year']		: "";
	$cur_year2 	= isset($_REQUEST['cur_year2']) ? $_REQUEST['cur_year2'] 	: "";
	
	if(!empty($direction) && $direction == "get_statistik"){
		
		switch($periode){
			case "harian":
				$num 	= $dtime->daysamonth($cur_month,$cur_year);
				$data["label_periode"] = "Bulan ".$dtime->nama_bulan($cur_month)." Tahun ".$cur_year;
			break;
			case "bulanan":
				$num 	= 12;
				$data["label_periode"] = "Tahun ".$cur_year;
			break;
			case "tahunan":
				$num 	= $cur_year2 - $cur_year; 
				$num 	= str_replace("-","",$num);
				$data["label_periode"] = "Tahun ".$cur_year." s.d Tahun ".$cur_year2;
			break;	
		}
		
		$b 			= 0;
		$data		= array();					
		while($b<$num){
			$b++;
			$condition		= "";
			if($_SESSION['cidkey'] != '1'){ $condition .= " AND ID_CLIENT = '".$_SESSION['cidkey']."'"; }
			
			switch($periode){
				case "harian":
					$tgl_kunjung 	 = $cur_year."-".$cur_month."-".$dtime->dateNum($b);
					$condition 		.= " AND TGLUPDATE ='".$tgl_kunjung."'";
					$data["label"][] =  $dtime->dateNum($b);
				break;
				case "bulanan":
					$condition .= " AND (MONTH(TGLUPDATE) ='".$dtime->dateNum($b)."' AND 
										 YEAR(TGLUPDATE)  ='".$cur_year."')";
					$data["label"][] =  $dtime->nama_bulan($dtime->dateNum($b));
				break;
				case "tahunan":
					$condition 		.= " AND BETWEEN (YEAR(TGLUPDATE) ='".$cur_year."' AND 
													  YEAR(TGLUPDATE) ='".$cur_year2."')";
					$data["label"][] =  $b;
				break;	
			}
			
			$str_unduh 		=  "SELECT IP_ADDRESS 
								FROM ".$tpref."logs 
								WHERE 
									IP_ADDRESS IS NOT NULL AND
									(ACTIVITY LIKE '%Melihat Halaman Discoin%' ".@$condition." AND
									 ACTIVITY NOT LIKE '%bot.html%' AND
									 ACTIVITY NOT LIKE '%bingbot.htm%' AND
									 ACTIVITY NOT LIKE '%Windows NT 10.0; Win64; x64%')
								GROUP BY IP_ADDRESS";
			$jml_unduh 		= $db->recount($str_unduh);
			if(empty($jml_unduh)){ $jml_unduh = 0; }
			$data["kunjungan"][] 	= $jml_unduh;
		 }
		 $data["pemesanan"] 	= array(8,9,5,10,13,15,16,13,16,18,12,15,17,18,13,13,15,17,13,13,15,16,23,13,26,23,14,35,15,16,13);
		 $data["penjualan"] 	= array(4,2,5,2,3,5,6,3,6,8,2,5,7,8,3,3,5,7,3,3,5,6,3,3,6,23,34,35,23,6,36);
		 echo json_encode($data);
	}

}else{
	defined('mainload') or die('Restricted Access');
}
?>
